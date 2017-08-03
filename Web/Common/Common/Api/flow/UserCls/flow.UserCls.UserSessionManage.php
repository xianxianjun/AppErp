<?php
namespace Common\Common\Api\flow;
trait UserSessionManage
{
    public static function CreateUserLoginSession($tokenKey, $orderErpId, $salesErpId, $id, $phone)
    {
        /*session('user.tokenKey', $tokenKey);
        session('user.erpId', $erpId);
        session('user.orderGroup', $orderGroup);
        session('user.id', $id);
        session('user.phone', $phone);*/
        $data = array("tokenKey"=>$tokenKey,
            "orderErpId"=>$orderErpId,
            "salesErpId"=>$salesErpId,
            "id"=>$id,
            "phone"=>$phone);
        S('user'.$tokenKey,$data,UserCls::$CACHE_SECOND);

        //$udata['activeDate'] =
        //$udata['activeCount'] =
        //M('member')->where(array("id"=>$id))->save($udata);
        //$datel = date('Y-m-d H:i:s');
        $value = S('userSetActive'.$tokenKey);
        if(empty($value)) {
            $Model = new \Think\Model();
            //$Model->execute("update app_member set activeDate = '$datel',activeCount=activeCount+1 where id=$id");
            $Model->execute("insert into app_user_active (member_id) VALUE ($id)");
            S('userSetActive' . $tokenKey, "ok", 20);
        }
    }
    public static function CreateUserLoginReject($tokenKey,$key)
    {
        $data = array("Reject".$key=>1);
        S('user'.$tokenKey,$data,UserCls::$CACHE_SECOND);
    }
    public static function IsUserLoginReject($tokenKey,$key)
    {
        $data = array("Reject".$key=>1);
        if(S('user'.$tokenKey)['Reject'.$key] == 1)
        {
            return true;
        }
        return false;
    }
    public static function SetTokenKey($oldTokenKey,$newTokenKey)
    {
        //session('user.tokenKey', $tokenKey);
        $data = S('user'.$oldTokenKey);
        if($data != null) {
            $data['tokenKey'] = $newTokenKey;
            S('user' . $newTokenKey, $data, UserCls::$CACHE_SECOND);
            S('user' . $oldTokenKey, null);
        }
    }

    public static function GetTokenKey($tokenKey)//目的是为了唯一登录
    {
        //$tokenKey = session('user.tokenKey');
        //return $tokenKey;
        $tokenKey = S('user'.$tokenKey)['tokenKey'];
        return $tokenKey;
    }

    public static function GetRequestTokenKey()
    {
        $tokenKey = I('get.tokenKey', '');
        return $tokenKey;
    }

    public static function GetUserId($tokenKey)
    {
        //$id = session('user.id');
        $id = S('user'.$tokenKey)['id'];
        return $id;
    }

    public static function GetOrderErpId($tokenKey)
    {
        //$phone = session('user.phone');
        $erpId = S('user' . $tokenKey)['orderErpId'];
        return $erpId;
    }
    public static function GetSaleUserId($tokenKey)
    {
        //$phone = session('user.phone');
        $saleId = S('user' . $tokenKey)['salesErpId'];
        return $saleId;
    }
    /*public static function GetUserOrderGroup($tokenKey)
    {
        //$phone = session('user.phone');
        $orderGroup = S('user'.$tokenKey)['orderGroup'];
        return $orderGroup;
    }*/
    public static function GetUserPhone($tokenKey)
    {
        $phone = S('user'.$tokenKey)['phone'];
        return $phone;
    }
    public static function ClearOrderErpUser($tokenKey)
    {
        //session('user.orderGroup', null);
        $data = S('user'.$tokenKey);
        if($data != null) {
            $data['orderErpId'] = null;
            S('user' . $tokenKey, $data, UserCls::$CACHE_SECOND);
        }
    }

    public static function ClearSaleUser($tokenKey)
    {
        //session('user.erpId', null);
        $data = S('user'.$tokenKey);
        if($data != null) {
            $data['salesErpId'] = null;
            S('user' . $tokenKey, $data, UserCls::$CACHE_SECOND);
        }
    }

    public static function ClearUserLogin($tokenKey)
    {
        //session('user', null);
        S('user' . $tokenKey, null);
        S('userinfo' . $tokenKey, null);
    }
}