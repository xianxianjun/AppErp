<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/22
 * Time: 10:47
 */
namespace Common\Common\Api\flow;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\FunctionCode;
trait UserAddress
{
    public static function addUserAddressDo($tokenKey,$name,$provinceId,$cityId,$areaId,$phone,$addr,$isDefault)
    {
        $member_id = UserCls::GetUserId($tokenKey);
        $data['member_id'] = $member_id;
        if(empty($member_id))
        {
            return myResponse::ResponseDataFalseObj('添加用户地址失败');
        }
        $data['name'] = $name;
        $data['province_id'] = $provinceId;
        $data['city_id'] = $cityId;
        $data['area_id'] = $areaId;
        $data['phone'] = $phone;
        $data['addr'] = $addr;
        if($isDefault == 1) {
            $data['isDefaultDate'] = FunctionCode::GetNowTimeDate();
        }

        $mAd = M('member_address');
        $count = $mAd->where(array("member_id" => $member_id))->count();
        if($count>=5)
        {
            return myResponse::ResponseDataFalseObj('用户添加地址不能大于5个');
        }

        $re = $mAd->data()->add($data);
        if(!$re)
        {
            return myResponse::ResponseDataFalseObj('添加用户地址失败');
        }
        $reObj = UserCls::getAddressInfoById($tokenKey,$re);
        return $reObj;
        //return myResponse::ResponseDataTrueObj('添加用户地址成功',array("id"=>$re));
    }
    public static function modifyAddressDo($tokenKey,$name,$provinceId,$cityId,$areaId,$phone,$addr,$id,$isDefault)
    {
        $member_id = UserCls::GetUserId($tokenKey);
        if(empty($member_id))
        {
            return myResponse::ResponseDataFalseObj('修改用户地址失败');
        }
        $data['name'] = $name;
        $data['province_id'] = $provinceId;
        $data['city_id'] = $cityId;
        $data['area_id'] = $areaId;
        $data['phone'] = $phone;
        $data['addr'] = $addr;
        if($isDefault == 1) {
            $data['isDefaultDate'] = FunctionCode::GetNowTimeDate();
        }

        $mAd = M('member_address');
        $re = $mAd->where(array("member_id" => $member_id,"id"=>$id))->data($data)->save();
        if(!$re)
        {
            return myResponse::ResponseDataFalseObj('修改用户地址失败');
        }
        $reObj = UserCls::getAddressInfoById($tokenKey,$id);
        return $reObj;
    }
    public static function deleteAddressDo($tokenKey,$id)
    {
        $member_id = UserCls::GetUserId($tokenKey);
        if(empty($member_id))
        {
            return myResponse::ResponseDataFalseObj('删除用户地址失败');
        }
        $mAd = M('member_address');
        $re = $mAd->where(array("member_id" => $member_id,"id"=>$id))->delete();
        if(!$re)
        {
            return myResponse::ResponseDataFalseObj('删除用户地址失败');
        }
        return myResponse::ResponseDataTrueObj('删除用户地址成功');
    }
    public static function setDefaultAddressDo($tokenKey,$id)
    {
        $member_id = UserCls::GetUserId($tokenKey);
        if(empty($member_id))
        {
            return myResponse::ResponseDataFalseObj('设置地址默认地址失败');
        }
        if($id == '0')//自提地址
        {
            S("setUserPickDefaultAddress_".$member_id,"1");
        }
        else {
            $data['isDefaultDate'] = FunctionCode::GetNowTimeDate();
            $mAd = M('member_address');
            $re = $mAd->where(array("member_id" => $member_id, "id" => $id))->data($data)->save();
            if (!$re) {
                return myResponse::ResponseDataFalseObj('设置默认值地址失败');
            }
            else
            {
                S("setUserPickDefaultAddress_".$member_id,null);
            }
        }
        return myResponse::ResponseDataTrueObj('设置默认值地址成功');
    }
    public static function IsUserUsePickDefaultAddress($member_id)
    {
        if(intval($member_id)>0 && intval(S("setUserPickDefaultAddress_".$member_id)) == 1)
        {
            return true;
        }
        return false;
    }
    public static function getAddressById($tokenKey,$id)
    {
        $member_id = UserCls::GetUserId($tokenKey);
        if(empty($member_id))
        {
            return myResponse::ResponseDataFalseObj('获取用户地址失败');
        }
        $mAd = M('member_address');
        $addr = $mAd->field('id,name,province_id,city_id,area_id,phone,addr')->where(array("id"=>$id,"member_id" => $member_id))->select();
        if(count($addr)>0) {
            return myResponse::ResponseDataTrueDataObj($addr[0]);
        }
        return myResponse::ResponseDataFalseObj('获取用户地址失败');
    }
    public static function getAddressInfoById($tokenKey,$id)
    {
        $member_id = UserCls::GetUserId($tokenKey);
        if(empty($member_id))
        {
            return myResponse::ResponseDataFalseObj('获取用户地址失败');
        }
        return UserAddress::getAddressInfoByMemberId($member_id,$id);
    }
    public static function getAddressInfoByMemberId($member_id,$id)
    {
        if(!empty($member_id) && intval($member_id)>=0 && !empty($id) && intval($id)>=0)
        {
            $mAd = M('member_address');
            $addr = $mAd->field('id,name,province_id,city_id,area_id,phone,addr')->where(array("id"=>$id,"member_id" => $member_id))->select();
            if(count($addr)>0) {
                $province = BaseCls::getProvinceById($addr[0]['province_id'])['name'];
                $city = BaseCls::getCityById($addr[0]['city_id'])['name'];
                $area = BaseCls::getAreaById($addr[0]['area_id'])['name'];
                $addra = array('id'=>$addr[0]["id"],"name"=>$addr[0]["name"],"phone"=>$addr[0]["phone"],"addr"=>$province.' '.$city.' '.$area.' '.$addr[0]['addr']);

                return myResponse::ResponseDataTrueDataObj($addra);
            }
        }

        return myResponse::ResponseDataFalseObj('获取用户地址失败');
    }
    public static function getDefultAddress($tokenKey)
    {
        $member_id = UserCls::GetUserId($tokenKey);
        if(empty($member_id))
        {
            return myResponse::ResponseDataFalseObj('获取用户默认地址失败');
        }
        $mAd = M('member_address');
        $addr = $mAd->field('id,name,province_id,city_id,area_id,phone,addr')->where(array("member_id" => $member_id))->order(array('isDefaultDate'=>'desc','id'=>'desc'))->limit(1)->select();
        if(count($addr)>0)
        {
            $province = BaseCls::getProvinceById($addr[0]['province_id'])['name'];
            $city = BaseCls::getCityById($addr[0]['city_id'])['name'];
            $area = BaseCls::getAreaById($addr[0]['area_id'])['name'];
            $addra = array('id'=>$addr[0]["id"],"name"=>$addr[0]["name"],"phone"=>$addr[0]["phone"],"addr"=>$province.' '.$city.' '.$area.' '.$addr[0]['addr']);
            return myResponse::ResponseDataTrueDataObj($addra);
        }
        return myResponse::ResponseDataFalseObj('获取用户默认地址失败');
    }
    public static function getAddressListForMember($tokenKey,$orderBy="")
    {
        $member_id = UserCls::GetUserId($tokenKey);
        if(empty($member_id))
        {
            return myResponse::ResponseDataFalseObj('获取用户默认地址失败');
        }
        //$mAd = M('member_address');
        //$addr = $mAd->field('id,name,province_id,city_id,area_id,phone,addr')->where(array("member_id" => $member_id))->order(array('isDefaultDate'=>'desc','id'=>'desc'))->select();
        $Model = new \Think\Model();
        $sql = "select aa.id,aa.name,aa.addr,aa.phone,if((@rowNO := @rowNo+1)=1,1,0) AS isDefault from
                  (select a.id,a.name,CONCAT(b.name,' ',c.name,' ',d.name,' ',a.addr) as addr,a.phone,a.isDefaultDate
                from app_member_address a left join app_province b on a.province_id=b.id
								left join app_city c on a.city_id=c.id
								left join app_area d on a.area_id=d.id
                where  a.member_id=%d) aa,(select @rowNO :=0) bb
                ORDER BY aa.isDefaultDate desc";
        $sql = "select * from (".$sql.") aaa ".$orderBy;
        $addr = $Model->query($sql,$member_id);
        return myResponse::ResponseDataTrueDataObj($addr);
    }
    public static function getAddressListForMemberHaveDefult($tokenKey)
    {
        $addr = UserCls::getAddressListForMember($tokenKey,"order by id desc");
        $defaultArr = UserCls::$PickDefaultAddress;

        if($addr->error == 0)
        {
            if(count($addr->data)>0) {
                $member_id = UserCls::GetUserId($tokenKey);
                if (!empty($member_id) && UserCls::IsUserUsePickDefaultAddress($member_id)) {
                    $n = FunctionCode::FindEqArrReN($addr->data, "isDefault", "1");
                    if ($n >= 0) {
                        $addr->data[$n]["isDefault"] = 0;
                    }
                    $defaultArr["isDefault"] = 1;
                }
            }
            else
            {
                $defaultArr["isDefault"] = 1;
            }

            array_unshift($addr->data,$defaultArr);
        }
        return $addr;
    }
}