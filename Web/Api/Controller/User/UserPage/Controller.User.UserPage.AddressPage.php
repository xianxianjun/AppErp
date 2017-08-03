<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/23
 * Time: 10:01
 */
namespace Api\Controller;

use Common\Common\Api\Code\myResponse;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\Code\myValidate;

trait AddressPage
{
    public function userModifyAddressPage()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $id = I('get.id', '');
        $ValidateArr = array(myValidate::ConnectStr(array($id, 0, "缺少id")));
        $err = myValidate::ValidateEmtry($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return;
        }

        $AdreObj = UserCls::getAddressById($myKey, $id);
        if(count($AdreObj)>0)
        {
            $pca = BaseCls::getProvinceById($AdreObj->data['province_id'])['name'];
            $pca = $pca.' '.BaseCls::getCityById($AdreObj->data['city_id'])['name'];
            $pca = $pca.' '.BaseCls::getAreaById($AdreObj->data['area_id'])['name'];
            $AdreObj->data['place'] = $pca;

        }
        $province = BaseCls::getProvince();
        if(!myResponse::IsResponseErr($AdreObj)) {
            echo myResponse::ResponseDataTrueDataString(array("address" => $AdreObj->data, "provinceList" => $province));
        }
        else {
            echo myResponse::ToResponseJsonString($AdreObj);
        }
    }

    public function userAddAddressPage()
    {
        $province = BaseCls::getProvince();
        echo myResponse::ResponseDataTrueDataString(array("provinceList" => $province));
    }

    public function AddressListPage()
    {
        $myKey = UserCls::GetRequestTokenKey();

        $addrs = UserCls::getAddressListForMember($myKey);
        if(!myResponse::IsResponseErr($addrs)) {
            echo myResponse::ResponseDataTrueDataString(array("addressList" => $addrs->data));
        }
        else {
            echo myResponse::ToResponseJsonString($addrs);
        }
    }
    public function AddressListPageForSelect()
    {
        $myKey = UserCls::GetRequestTokenKey();

        $addrs = UserCls::getAddressListForMemberHaveDefult($myKey);
        if(!myResponse::IsResponseErr($addrs)) {
            echo myResponse::ResponseDataTrueDataString(array("addressList" => $addrs->data));
        }
        else {
            echo myResponse::ToResponseJsonString($addrs);
        }
    }
}