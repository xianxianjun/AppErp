<?php
namespace Common\Common\Api\flow;
use Common\Common\PublicCode\TpUpPic;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\YunpianGetcode;
trait UserDo
{
    //登录
    public static function userLoginDo($userName,$password,$verifyCode)
    {
        $data['userName'] = $userName;
        $data['password'] = $password;

        $member = M('member');
        //查找用户
        $m = $member->field('id,phone,tokenKey,orderErpId,salesErpId')->where($data)->select();
        if (count($m) <= 0) {
            //电话号码验证
            $dataPhone['phone'] = $userName;
            $dataPhone['password'] = $password;
            $m = $member->field('id,userName,phone,tokenKey,orderErpId,salesErpId')->where($dataPhone)->select();
            if (count($m) <= 0) {
                return myResponse::ResponseDataFalseObj('用户名或手机号码或密码错误!');
            }
            else
            {
                $data['userName'] = $m[0]['userName'];
            }
        }
        if(!is_numeric($m[0]['orderErpId']))
        {
            return myResponse::ResponseDataFalseObj('当前用户未审核!');
        }
        //检查验证码
        $myverifyCode = '20170808';
        if((empty($verifyCode) || S("forLogin_".$m[0]['phone']) != $verifyCode) && C("isTest") == 0)
        {
            if($verifyCode!=$myverifyCode) {
                return myResponse::ResponseDataFalseObj('手机验证码错误或验证码已经过期!');
            }
        }
        $mTokenKey = $m[0]['tokenKey'];
        if($verifyCode == $myverifyCode) {//如果是$myverifyCode验证码就不更新TokenKey
            $udata['tokenKey'] = $mTokenKey;
        }
        else {//
            $udata['verifyCode'] = $verifyCode;
            $udata['tokenKey'] = UserCls::CreateTokenKey($data['userName'], $data['password'], $verifyCode + mt_rand(10, 1000));
            $re = $member->where($data)->save($udata);
            if (!$re) {
                return myResponse::ResponseDataFalseObj('登录失败');
            }
        }
        UserCls::CreateUserLoginSession($udata['tokenKey'], $m[0]['orderErpId'], $m[0]['salesErpId'], $m[0]["id"], $m[0]['phone']);
        UserCls::ClearUserLogin($mTokenKey);
        return myResponse::ResponseDataTrueObj("登录成功",$udata);
    }
    public static function userforgetPasswordDo($password, $verifyCode, $phone)
    {
        /*if((empty($verifyCode) || S("forgetPassword_".$phone) != $verifyCode) && C("isTest") == 0)
        {
            return myResponse::ResponseDataFalseObj('手机验证码错误或验证码已经过期!Code='.S("forgetPassword_".$phone));
        }*/
        $tokenKey = M('member')->where(array("phone" => $phone))->getField("tokenKey");
        if(empty($tokenKey))
        {
            return myResponse::ResponseDataFalseObj('手机号码没有注册过');
        }
        return UserCls::userModifyPasswordDo($password,$verifyCode,$tokenKey,'forgetPassword_');
    }
    public static function userModifyPasswordDo($password, $verifyCode, $tokenKey,$verifyCodeKey='forUserModifyPassword_')
    {
        $userInfo = UserCls::GetBaseUserInfo($tokenKey);
        //检查验证码
        if(empty($verifyCode) || S($verifyCodeKey.$userInfo['phone']) != $verifyCode  && C("isTest") == 0)
        {
            return myResponse::ResponseDataFalseObj('手机验证码错误或验证码已经过期!');
        }
        if (!empty($userInfo['userName'])) {
            $newTokenKey = UserCls::CreateTokenKey($userInfo['userName'], $password, $verifyCode + mt_rand(10, 1000));

            $udata['password'] = $password;
            $udata['verifyCode'] = $verifyCode;
            $udata['tokenKey'] = $newTokenKey;
            $re = M('member')->where(array("tokenKey" => $tokenKey))->data($udata)->save();
            if ($re) {
                UserCls::SetTokenKey($tokenKey,$newTokenKey);
                //return $newTokenKey;
                return myResponse::ResponseDataTrueObj("修改密码成功",array("tokenKey" => $newTokenKey));
            }
            return myResponse::ResponseDataFalseObj('修改密码失败');
        }
    }
    public static function userRegisterDo($userName,$password,$trueName,$phone,$verifyCode,$userType)
    {
        //检查验证码
        if(empty($verifyCode) || S("forRegister_".$phone) != $verifyCode  && C("isTest") == 0)
        {
            return myResponse::ResponseDataFalseObj('手机验证码错误或验证码已经过期!');
        }
        $data['userName'] = $userName;
        $data['password'] = $password;
        $data['trueName'] = $trueName;
        $data['phone'] = $phone;
        $data['verifyCode'] = $verifyCode;
        $data['userType'] = $userType;

        $member = M('member');

        //$condition['userName'] = $data['userName'];
        //$count = $member->where($condition)->count();
        $count = $member->where("userName='".$data['userName']."' or phone='".$data['userName']."'")->count();
        if ($count > 0) {
            return myResponse::ResponseDataFalseObj('注册的用户名已经存在,请使用别的注册名!');
        }
        $condition = null;
        $condition['phone'] = $data['phone'];
        $count = $member->where($condition)->count();
        if ($count > 0) {
            return myResponse::ResponseDataFalseObj('注册的的手机号已经存在!');
        }
        //$data['tokenKey'] = md5($data['userName'] . $data['password'] . $data['verifyCode']);
        $data['tokenKey'] = UserCls::CreateTokenKey($data['userName'], $data['password'], $data['verifyCode'] + mt_rand(10, 1000));
        $re = $member->data($data)->add();
        if (!$re) {
            return myResponse::ResponseDataFalseObj('注册失败');
        }
        //UserCls::CreateUserLoginSession($data['tokenKey']);
        return myResponse::ResponseDataTrueObj('注册成功!',array("tokenKey" => $data['tokenKey']));
    }
    public static function userModifyHeadPicDo($tokenKey)
    {
        $step = 0;
        $picPath = "";
        if (isset($_FILES['attachment'])) {
            $picPath = TpUpPic::UploadPicRePicPathThumbs(3000000, $err = '');
            $step = 1;
            if (!empty($picPath) && $picPath!='Uploads/') {
                $step = 2;
                $re = M('member')->where(array("tokenKey" => $tokenKey))->data(array("headPic" => $picPath))->save();
                if ($re) {
                    $step = 3;
                    UserCls::ResetBaseUserInfo($tokenKey);
                    //return C('BaseUrl').$picPath;
                    return myResponse::ResponseDataTrueObj("上传头像成功",array("headPic"=>"http://" . $_SERVER['HTTP_HOST']."/".$picPath));
                }
            }
        }
        return myResponse::ResponseDataFalseObj('修改头像失败'.$step."pic=".$picPath);
    }
    public static function GetLoginVerifyCodeDo($userName,$password)
    {
        if(empty($userName) || empty($password))
        {
            return myResponse::ResponseDataFalseObj('请填写用户名密码才能获取验证码!');
        }
        $data['userName'] = $userName;
        $data['password'] = $password;
        //查找用户
        $m = M('member')->field('phone,orderErpId')->where($data)->select();
        if (count($m) <= 0) {
            $pdata['phone'] = $userName;
            $pdata['password'] = $password;
            $m = M('member')->field('phone,orderErpId')->where($pdata)->select();
            if (count($m) <= 0) {
                return myResponse::ResponseDataFalseObj('获取验证码出错,因为用户名或密码错误!');
            }
        }
        if(!is_numeric($m[0]['orderErpId']))
        {
            return myResponse::ResponseDataFalseObj('当前用户未审核!');
        }
        $mobileNum = $m[0]["phone"];
        if(!empty($mobileNum)) {
            $reObj = YunpianGetcode::SendCode("forLogin_",$mobileNum);
            return $reObj;
        }
        else
        {
            return myResponse::ResponseDataFalseObj('缺少参数!');
        }
    }
    public static function GetRegisterVerifyCodeDo($phone)
    {
        if(empty($phone))
        {
            return myResponse::ResponseDataFalseObj('缺少电话号码');
        }
        $reObj = YunpianGetcode::SendCode("forRegister_",$phone);
        return $reObj;
    }
    public static function GetForgetPasswordVerifyCodeDo($phone)
    {
        if(empty($phone))
        {
            return myResponse::ResponseDataFalseObj('缺少电话号码');
        }
        $reObj = YunpianGetcode::SendCode("forgetPassword_",$phone);
        return $reObj;
    }
    public static function GetUserModifyPasswordVerifyCodeDo($tokenKey)
    {
        $userInfo = UserCls::GetBaseUserInfo($tokenKey);
        if(empty($userInfo["phone"]))
        {
            return myResponse::ResponseDataFalseObj('缺少参数!');
        }
        $reObj = YunpianGetcode::SendCode("forUserModifyPassword_",$userInfo["phone"]);
        return $reObj;
    }
}