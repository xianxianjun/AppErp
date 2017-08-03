<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/22
 * Time: 9:59
 */
namespace Api\Controller;

use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\Api\flow\UserCls;
trait UserClsDo
{
    public function userRegisterDo()
    {
        $userName = I('get.userName', '');
        $password = I('get.password', '');
        $trueName = I('get.trueName', '');
        $phone = I('get.phone', '');
        $verifyCode = I('get.phoneCode', '');
        $userType = I('get.userType', '');
        $ValidateArr = array(myValidate::ConnectStr(array($userName, 3, "名字不能为空并字符个数大于3")),
            myValidate::ConnectStr(array($password, 0, "密码不能为空")),
            myValidate::ConnectStr(array($trueName, 2, "真实名字不能为空并字符个数大于2")),
            myValidate::ConnectStr(array($phone, 0, "手机号码不能为空")),
            myValidate::ConnectStr(array($verifyCode, 4, "手机验证码不正确")));
        $err = myValidate::ValidateEmtry($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return;
        }
        if (empty($userType) || ($userType != 1 && $userType != 2)) {
            echo myResponse::ResponseDataFalseString('请选择用户类型!');
            return;
        }
        $reObj = UserCls::userRegisterDo($userName,$password,$trueName,$phone,$verifyCode,$userType);
        echo myResponse::ToResponseJsonString($reObj);

    }

    public function userLoginDo()
    {
        $userName = I('get.userName', '');
        $password = I('get.password', '');
        $verifyCode = I('get.phoneCode', '');
        $ValidateArr = array(myValidate::ConnectStr(array($userName, 3, "名字不能为空并字符个数大于3")),
            myValidate::ConnectStr(array($password, 0, "密码不能为空")),
            myValidate::ConnectStr(array($verifyCode, 4, "手机验证码错误")));
        $err = myValidate::ValidateEmtry($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return;
        }

        $reObj = UserCls::userLoginDo(I('get.userName', ''),I('get.password', ''),I('get.phoneCode', ''));
        echo myResponse::ToResponseJsonString($reObj);

    }

    //修改密码
    public function userModifyPasswordDo()
    {
        $myKey = UserCls::GetRequestTokenKey();
        /*if (!UserCls::IsAnyUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/

        $password = I('get.password', '');
        $verifyCode = I('get.phoneCode', '');
        $ValidateArr = array(myValidate::ConnectStr(array($password, 5, "密码大于5位")),
            myValidate::ConnectStr(array($verifyCode, 4, "手机验证码不正确")));
        $err = myValidate::ValidateEmtry($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return;
        }
        $reObj = UserCls::userModifyPasswordDo($password, $verifyCode, $myKey);
        echo myResponse::ToResponseJsonString($reObj);
    }
    //忘记密码
    public function userForgetPasswordDo()
    {
        $phone = I('get.phone', '');
        $password = I('get.password', '');
        $verifyCode = I('get.phoneCode', '');
        $ValidateArr = array(myValidate::ConnectStr(array($password, 5, "密码大于5位")),
            myValidate::ConnectStr(array($verifyCode, 4, "手机验证码不正确")),
            myValidate::ConnectStr(array($phone, 10, "手机号码不正确")));
        $err = myValidate::ValidateEmtry($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return;
        }
        $reObj = UserCls::userforgetPasswordDo($password, $verifyCode, $phone);
        echo myResponse::ToResponseJsonString($reObj);
    }
    //上传头像
    public function userModifyHeadPicDo()
    {
        $myKey = UserCls::GetRequestTokenKey();
        /*if (!UserCls::IsAnyUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/
        $reObj = UserCls::userModifyHeadPicDo($myKey);;
        echo myResponse::ToResponseJsonString($reObj);
    }

    public function addUserAddressDo()
    {
        $myKey = UserCls::GetRequestTokenKey();
        /*if (!UserCls::IsAnyUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/
        $name = I('get.name', '');
        $provinceId = I('get.provinceId', '');
        $cityId = I('get.cityId', '');
        $areaId = I('get.areaId', '');
        $phone = I('get.phone', '');
        $addr = I('get.addr', '');
        $isDefault = I('get.isDefault', '');
        $ValidateArr = array(myValidate::ConnectStr(array($name, 3, "名字不能为空并字符个数大于3")),
            myValidate::ConnectStr(array($provinceId, 0, "请选择省份")),
            myValidate::ConnectStr(array($cityId, 0, "请选择城市")),
            myValidate::ConnectStr(array($areaId, 0, "请选择地区或县份")),
            myValidate::ConnectStr(array($phone, 0, "请填写联系电话")),
            myValidate::ConnectStr(array($addr, 0, "请填写详细地址")));
        $err = myValidate::ValidateEmtry($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return;
        }
        $reObj  = UserCls::addUserAddressDo($myKey,$name,$provinceId,$cityId,$areaId,$phone,$addr,$isDefault);
        echo myResponse::ToResponseJsonString($reObj);
    }
    public function modifyAddressDo()
    {
        $myKey = UserCls::GetRequestTokenKey();
        /*if (!UserCls::IsAnyUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/
        $name = I('get.name', '');
        $provinceId = I('get.provinceId', '');
        $cityId = I('get.cityId', '');
        $areaId = I('get.areaId', '');
        $phone = I('get.phone', '');
        $addr = I('get.addr', '');
        $id = I('get.id', '');
        $isDefault = I('get.isDefault', '');
        $ValidateArr = array(myValidate::ConnectStr(array($name, 3, "名字不能为空并字符个数大于3")),
            myValidate::ConnectStr(array($id, 0, "id不能为空")),
            myValidate::ConnectStr(array($provinceId, 0, "请选择省份")),
            myValidate::ConnectStr(array($cityId, 0, "请选择城市")),
            myValidate::ConnectStr(array($areaId, 0, "请选择地区或县份")),
            myValidate::ConnectStr(array($phone, 0, "请填写联系电话")),
            myValidate::ConnectStr(array($addr, 0, "请填写详细地址")));
        $err = myValidate::ValidateEmtry($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return;
        }
        $reObj  = UserCls::modifyAddressDo($myKey,$name,$provinceId,$cityId,$areaId,$phone,$addr,$id,$isDefault);

        echo myResponse::ToResponseJsonString($reObj);
    }
    public function deleteAddressDo()
    {
        $myKey = UserCls::GetRequestTokenKey();
        /*if (!UserCls::IsAnyUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/
        $id = I('get.id', '');
        $ValidateArr = array(myValidate::ConnectStr(myValidate::ConnectStr(array($id, 0, "id不能为空"))));
        $err = myValidate::ValidateEmtry($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return;
        }

        $reObj  = UserCls::deleteAddressDo($myKey,$id);
        echo myResponse::ToResponseJsonString($reObj);

    }
    public function setDefaultAddressDo()
    {
        $myKey = UserCls::GetRequestTokenKey();
        /*if (!UserCls::IsAnyUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/
        $id = I('get.id', '');
        $ValidateArr = array(myValidate::ConnectStr(myValidate::ConnectStr(array($id, 0, "id不能为空"))));
        $err = myValidate::ValidateEmtry($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return;
        }
        $reObj  = UserCls::setDefaultAddressDo($myKey,$id);
        echo myResponse::ToResponseJsonString($reObj);

    }
    public function GetLoginVerifyCodeDo()
    {
        $userName = I('get.userName', '');
        $password = I('get.password', '');

        if(empty($userName) || empty($password))
        {
            echo myResponse::ResponseDataFalseString('请填写用户名密码才能获取验证码!');
            //return myResponse::ResponseDataFalseObj('请填写用户名密码才能获取验证码!');
        }
        else {
            $reObj = UserCls::GetLoginVerifyCodeDo($userName, $password);
            echo myResponse::ToResponseJsonString($reObj);
        }
    }
    public function GetRegisterVerifyCodeDo()
    {
        $phone = I('get.phone', '');
        if(empty($phone))
        {
            echo myResponse::ResponseDataFalseString('请输入手机号码');
        }
        else
        {
            $reObj = UserCls::GetRegisterVerifyCodeDo($phone);
            echo myResponse::ToResponseJsonString($reObj);
        }
    }
    public function GetUserModifyPasswordVerifyCodeDo()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $reObj = UserCls::GetUserModifyPasswordVerifyCodeDo($myKey);
        echo myResponse::ToResponseJsonString($reObj);
    }
    public function GetForgetPasswordVerifyCodeDo()
    {
        $phone = I('get.phone', '');
        if(empty($phone))
        {
            echo myResponse::ResponseDataFalseString('请输入手机号码');
        }
        else
        {
            $reObj = UserCls::GetForgetPasswordVerifyCodeDo($phone);
            echo myResponse::ToResponseJsonString($reObj);
        }
    }
    public function UserShowVerifyCode()
    {
        $prefix = I("get.prefix");
        $key= I("get.key");
        echo myResponse::ResponseDataTrueDataString(S($prefix."_".$key));
    }
    public function UpdateIsShowPrice()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $value = I('get.value');
        if($value==1||$value==0) {
            if(UserCls::UpdateisShowPrice($myKey,$value))
            {
                echo myResponse::ResponseDataTrueString('更新成功');
            }
            else
            {
                echo myResponse::ResponseDataFalseString('更新失败');
            }
        }
    }
    public function modifyUserStoneAddtionDo()
    {
        $value = floatval(I('get.value'));
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        echo myResponse::ToResponseJsonString(UserCls::modifyUserStoneAddtionDo($memberId, $value));
    }
    public function modifyUserModelAddtionDo()
    {
        $value = floatval(I('get.value'));
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        echo myResponse::ToResponseJsonString(UserCls::modifyUserModelAddtionDo($memberId, $value));
    }
    public function modifyUserIsShowOriginalPriceDo()
    {
        $value = intval(I('get.isShow'));
        $value = $value!=1?$value=0:1;
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        echo myResponse::ToResponseJsonString(UserCls::modifyUserIsShowOriginalPriceDo($memberId, $value));
    }
}