<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/7/3
 * Time: 11:58
 */
namespace Common\Common\Api\flow;

use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\flow\ModelCls;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\Code\ValidateCode;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ConvertType;
use Common\Common\PublicCode\isHaveCustomer;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\PublicCode\BllPublic;
trait UserParameter
{
    public static function modifyUserStoneAddtionDo($memberId,$value)
    {
        $data["stoneAddtion"] = $value;
        $data["updateDate"] = FunctionCode::GetNowTimeDate();
        $re = M("member")->where(array("id"=>$memberId,"IsMasterAccount"=>1))->save($data);
        if($re)
        {
            UserCls::getUserStoneAddtionValue($memberId,true);
            return myResponse::ResponseDataTrueObj('修改成功');
        }
        return myResponse::ResponseDataFalseObj('修改失败');
    }
    public static function modifyUserModelAddtionDo($memberId,$value)
    {
        $data["modelAddtion"] = $value;
        $data["updateDate"] = FunctionCode::GetNowTimeDate();
        $re = M("member")->where(array("id"=>$memberId,"IsMasterAccount"=>1))->save($data);
        if($re)
        {
            UserCls::getUserModelAddtionValue($memberId,true);
            return myResponse::ResponseDataTrueObj('修改成功');
        }
        return myResponse::ResponseDataFalseObj('修改失败');
    }
    public static function modifyUserIsShowOriginalPriceDo($memberId,$value)
    {
        $data = M("member")->field("id,IsMasterAccount")->where("id=$memberId")->select();
        if($data[0]["IsMasterAccount"] == 1) {
            $key = "IsShowOriginalPrice" . $memberId;
            S($key, $value);
            UserCls::getUserStoneAddtionValue($memberId,true);
            UserCls::getUserModelAddtionValue($memberId,true);
            return myResponse::ResponseDataTrueObj('修改成功');
        }
        else {
            return myResponse::ResponseDataFalseObj('修改失败');
        }
    }
    public static function getUserIsShowOriginalPrice($memberId)
    {
        $key = "IsShowOriginalPrice".$memberId;
        $value = intval(S($key));
        UserCls::getUserStoneAddtionValue($memberId,true);
        UserCls::getUserStoneAddtionValue($memberId,true);
        return $value;
    }
    public static function getUserStoneAddtionValue($memberId,$isForce=false)
    {
        $key = "getUserStoneAddtion".$memberId;
        $value = S($key);
        if($value == null || $isForce) {
            $value = 0;
            $data = M("member")->field("id,stoneAddtion")->where("(IsMasterAccount=1 and id=$memberId) or myMasterAccountMId=" . $memberId)->order("id desc")->select();
            if (count($data) > 0) {
                $n = FunctionCode::FindEqArrReN($data, "id", $memberId);
                if ($n < 0) {
                    $value = floatval($data[0]["stoneAddtion"]);
                }
                else
                {
                    $value = floatval($data[$n]["stoneAddtion"]);
                }
            }
            S($key,$value,UserCls::$CACHE_SECOND);
        }
        if($value == 0) {
            return 1;
        }
        else {
            return $value/100;
        }
        //return 1 + (floatval($value) <= 0?0:$value/100);
    }
    //仅仅获取计算值（待权限）
    public static function getUserStoneAddtion($memberId,$isForce=false)
    {
        $IsShowOriginalPrice = UserCls::getUserIsShowOriginalPrice($memberId);
        if($IsShowOriginalPrice == 1)
        {
            return 1;
        }
        $value = UserCls::getUserStoneAddtionValue($memberId,$isForce);
        return $value;
    }
    //仅仅获取百分比值
    public static function getUserStoneAddtionPercentValue($memberId,$isForce=false)
    {
        $value = UserCls::getUserStoneAddtionValue($memberId,$isForce);
        //$value = ($value>=1?$value-1:1)*100;
        return $value*100;
    }
    //仅仅获取百分比值（待权限）
    public static function getUserStoneAddtionPercent($memberId,$isForce=false)
    {
        $IsShowOriginalPrice = UserCls::getUserIsShowOriginalPrice($memberId);
        if($IsShowOriginalPrice == 1)
        {
            return 100;
        }
        $value = UserCls::getUserStoneAddtionPercentValue($memberId,$isForce);
        return $value*100;
    }
    public static function getUserModelAddtionValue($memberId,$isForce=false)
    {
        $key = "getUserModelAddtion".$memberId;
        $value = S($key);
        if($value == null || $isForce) {
            $value = 0;
            $data = M("member")->field("id,modelAddtion")->where("(IsMasterAccount=1 and id=$memberId) or myMasterAccountMId=" . $memberId)->order("id desc")->select();
            if (count($data) > 0) {
                $n = FunctionCode::FindEqArrReN($data, "id", $memberId);
                if ($n < 0) {
                    $value = floatval($data[0]["modelAddtion"]);
                }
                else
                {
                    $value = floatval($data[$n]["modelAddtion"]);
                }
            }
            S($key,$value,UserCls::$CACHE_SECOND);
        }
        if($value == 0) {
            return 1;
        }
        else {
            return $value/100;
        }
        //return 1 + (floatval($value) <= 0?0:$value/100);
    }
    //仅仅获取计算值（待权限）
    public static function getUserModelAddtion($memberId,$isForce=false)
    {
        $IsShowOriginalPrice = UserCls::getUserIsShowOriginalPrice($memberId);
        if($IsShowOriginalPrice == 1)
        {
            return 1;
        }
        return UserCls::getUserModelAddtionValue($memberId,$isForce);
    }
    //仅仅获取百分比值
    public static function getUserModelAddtionPercentValue($memberId,$isForce=false)
    {
        $value = UserCls::getUserModelAddtionValue($memberId,$isForce);
        //$value = ($value>=1?$value-1:1)*100;
        return $value*100;
    }
    public static function getUserModelAddtionPercent($memberId,$isForce=false)
    {
        $IsShowOriginalPrice = UserCls::getUserIsShowOriginalPrice($memberId);
        if($IsShowOriginalPrice)
        {
            return 100;
        }
        $value = UserCls::getUserModelAddtionPercentValue($memberId,$isForce);
        return $value*100;
    }
}