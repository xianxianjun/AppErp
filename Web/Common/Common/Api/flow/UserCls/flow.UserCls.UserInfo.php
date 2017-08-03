<?php
namespace Common\Common\Api\flow;
use Common\Common\PublicCode\FunctionCode;
trait UserInfo
{
    public static function GetBaseUserInfo($tokenKey,$forceGet = false)
    {
        /*if (!session('?userInfo') || session('userInfo') == null) {
            $member = M('member')->field('userName,headPic')->where(array("tokenKey" => $tokenKey))->select();
            if (count($member) > 0) {
                session('userInfo', array("userName" => $member[0]['userName']
                    //, "trueName" => $member[0]['trueName']
                , "headPic" => empty($member[0]['headPic']) ? 'http://' . $_SERVER['HTTP_HOST'] . '/image/imageForApi/defaultHead.jpg' : C('BaseUrl').$member[0]['headPic']));
            }
        }
        return session('userInfo');*/
        if (S('userinfo'.$tokenKey) == NULL || $forceGet) {
            $member = M('member')->field('userName,headPic,phone,trueName,modelAddtion,stoneAddtion,IsMasterAccount,IsCanSelectStone')->where(array("tokenKey" => $tokenKey))->select();
            if (count($member) > 0) {
                S('userinfo'.$tokenKey, array("userName" => $member[0]['userName']
                        //, "trueName" => $member[0]['trueName']
                    , "headPic" => empty($member[0]['headPic']) ? FunctionCode::GetRootURL() . 'images/imageForApi/defaultHead.jpg' : C('BaseUrl').$member[0]['headPic']
                    ,"phone"=>$member[0]['phone'],"trueName"=>$member[0]['trueName']
                    ,"UserModelAddtion"=>$member[0]['modelAddtion']
                    ,"UserStoneAddtion"=>$member[0]['stoneAddtion']
                    ,"IsMasterAccount"=>$member[0]['IsMasterAccount']
                    ,"IsCanSelectStone"=>$member[0]['IsCanSelectStone'])
                    ,UserCls::$CACHE_SECOND);
            }
        }
        return S('userinfo'.$tokenKey);
    }
    public static function GetIsCanSelectStone($tokenKey,$forceGet = false)
    {
        $IsCanSelectStone = S('GetIsCanSelectStone'.$tokenKey);
        if(!$IsCanSelectStone || $forceGet) {
            $userInfo = UserCls::GetBaseUserInfo($tokenKey, true);
            $IsCanSelectStone = intval($userInfo["IsCanSelectStone"]);
            S('GetIsCanSelectStone' . $tokenKey, $IsCanSelectStone, 5);
        }
        return $IsCanSelectStone;
    }
    public static function GetIsMasterAccount($tokenKey,$forceGet = false)
    {
        $userInfo = UserCls::GetBaseUserInfo($tokenKey,$forceGet);
        return intval($userInfo["IsMasterAccount"]);
    }
    public static function IfNetHaveKeyReGetBaseUserInfo($tokenKey,$key)
    {
        $userInfo = UserCls::GetBaseUserInfo($tokenKey);
        if (!empty($userInfo))
        {
            if(empty($userInfo[$key]))
            {
                $userInfo = UserCls::GetBaseUserInfo($tokenKey,true);
            }
        }
        return $userInfo;
    }
    public static function GetUserTrueName($tokenKey)
    {
        $key = 'trueName';
        $userInfo = UserCls::IfNetHaveKeyReGetBaseUserInfo($tokenKey,$key);
        return $userInfo[$key];
    }
    public static function ResetBaseUserInfo($tokenKey)
    {
        //session('userInfo',null);
        S('userinfo'.$tokenKey,NULL);
    }
    public static function GetUserMessageListNotReadCount($tokenKey)
    {
        $id = UserCls::GetUserId($tokenKey);
        $count = 0;
        if (!empty($id)) {
            $count = M('member_message')->where(array("member_id" => $id, "status" => 1))->count();
        }
        return $count;
    }
    public static function GetUserPayPercentByMemberId($memberId)
    {
        if (!empty($memberId)) {
            $data = M('member')->field("payPercent")->where(array("id" => $memberId))->select();
            if(count($data)>0)
            {
                if($data[0]["payPercent"] <= 0) return 0;
                return !empty($data[0]["payPercent"])?((double)$data[0]["payPercent"])/100:1;
            }
        }
        return 1;
    }
    public static function GetUserPayPercent($tokenKey)
    {
        $id = UserCls::GetUserId($tokenKey);
        return UserInfo::GetUserPayPercentByMemberId($id);
    }
    public static function GetUserMessageListCount($tokenKey)
    {
        $id = UserCls::GetUserId($tokenKey);
        $count = 0;
        if (!empty($id)) {
            $count = M('member_message')->where(array("member_id" => $id))->count();
        }
        return $count;
    }

    public static function GetUserMessageList($cpage,$tokenKey)
    {
        $id = UserCls::GetUserId($tokenKey);
        $pageCount = 20;
        if (empty($cpage) || !intval($cpage)) {
            $cpage = 1;
        }
        $meslist = M('member_message')->field('title,type,status,createDate')->where(array("member_id" => $id))
            ->order('createDate,status desc')
            ->page($cpage . ',' . $pageCount)->select();
        return $meslist;
    }


}