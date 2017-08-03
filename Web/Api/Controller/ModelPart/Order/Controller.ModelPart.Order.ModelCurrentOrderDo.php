<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/10/11
 * Time: 15:48
 */
namespace Api\Controller;

use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\ModelOrderCls;
use Common\Common\PublicCode\BllPublic;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\PublicCode\FunctionCode;
trait ModelCurrentOrderDo
{
    public static function OrderCurrentDeleteModelItemDo()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $itemId = I('get.itemId', '');
        $ValidateArr = array(myValidate::ConnectStr(array($itemId, 1, "没有Id")));
        $err = myValidate::VlidateIntegerGt($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return false;
        }
        $memberId = UserCls::GetUserId($myKey);
        if(empty($memberId))
        {
            echo myResponse::ResponseDataNoLoginString();
            return false;
        }
        $reObj = ModelOrderCls::OrderCurrentDeleteModelItemDo($itemId,$memberId);
        echo myResponse::ToResponseJsonString($reObj);
    }
    public static function OrderCurrentEditModelItemDo()
    {
        $itemId = I('get.itemId', '');
        $purityId = I('get.purityId', '');
        $qualityId = I('get.qualityId', '');
        ModelController::OrderCurrentEditModelItemDoForPublic($itemId,$purityId,$qualityId);
    }

    public static function OrderCurrentEditModelItemForDefaultDo()
    {
        $itemId = I('get.itemId', '');
        $purityId = I('get.purityId', '');
        $qualityId = I('get.qualityId', '');
        ModelController::OrderCurrentEditModelItemForDefaultDoForPublic($itemId,$purityId,$qualityId);
    }
    //验证副石头统一方法
    private static function CHECKORDERCURRENTDOMODELITEMFORSTONEVALUE($stone,$stoneName
        ,&$stoneCategory,&$stoneSpec,&$stoneSpecValue,&$stoneShape,&$stoneColorId,&$stonePurityId,&$stoneNumber,&$stoneOut = 0,$IsCheckStone = true)
    {
        if(!empty($stone)) {
            $arr = explode('|', $stone);
            if (myValidate::IsArrStringHaveNotEmtry($arr)) {
                if (count($arr) == 7) {
                    $stoneOut = intval($arr[6]);//是否封石
                    if (intval($arr[6]) == 1) {//封石
                        return true;
                    } else {
                        $stoneCategory = intval($arr[0]);
                        if (empty($arr[1]) && $IsCheckStone) {
                            echo myResponse::ResponseDataFalseString('请填写' . $stoneName . '规格');
                            return false;
                        }
                        $stoneSpecValue = BllPublic::GetStandardSpec($arr[1]);
                        if (empty($stoneSpecValue) && $IsCheckStone) {
                            echo myResponse::ResponseDataFalseString('填写' . $stoneName . '规格格式错误');
                            return false;
                        }
                        $stoneSpec = BllPublic::GetSpecValueId($stoneSpecValue);
                        $stoneSpec = empty($stoneSpec) ? 0 : $stoneSpec;

                        $stoneShape = intval($arr[2]);
                        $stoneColorId = intval($arr[3]);
                        $stonePurityId = intval($arr[4]);
                        $stoneNumber = intval($arr[5]);
                        $ValidateArr = array(
                            myValidate::ConnectStr(array($stoneCategory, 1, "请选择" . $stoneName . "头类别")),
                            //myValidate::ConnectStr(array($stoneSpec, 1, "请选择主石规格")),
                            myValidate::ConnectStr(array($stoneShape, 1, "请选择" . $stoneName . "形状")),
                            myValidate::ConnectStr(array($stoneColorId, 1, "请选择" . $stoneName . "颜色")),
                            myValidate::ConnectStr(array($stonePurityId, 1, "请选择" . $stoneName . "纯净度")),
                            myValidate::ConnectStr(array($stoneNumber, 1, "请正确填写" . $stoneName . "数量"))
                        );
                        $err = myValidate::VlidateIntegerGt($ValidateArr);
                        if (!empty($err) && $IsCheckStone) {
                            echo myResponse::ResponseDataFalseString($err);
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }
//主石统一验证方法
    private static function CHECKORDERCURRENTDOMODELITEMFORMAINSTONEVALUE($stone,$stoneName
        ,&$stoneCategory,&$stoneSpec,&$stoneSpecValue,&$stoneShape,&$stoneColorId,&$stonePurityId,&$stoneNumber,$isSelfStone = 0,$IsCheckStone = true)
    {
        if(!empty($stone)) {
            $arr = explode('|', $stone);
            if (myValidate::IsArrStringHaveNotEmtry($arr) && count($arr) == 6) {
                $stoneCategory = intval($arr[0]);
                if (empty($arr[1]) && $isSelfStone == 0 && $IsCheckStone) {
                    echo myResponse::ResponseDataFalseString('请填写'. $stoneName .'规格');
                    return false;
                }
                $stoneSpecValue = BllPublic::GetStandardSpec($arr[1]);
                if(empty($stoneSpecValue) && $isSelfStone == 0 && $IsCheckStone) {
                    echo myResponse::ResponseDataFalseString('填写'. $stoneName .'规格格式错误');
                    return false;
                }
                $stoneSpec = BllPublic::GetSpecValueId($stoneSpecValue);
                $stoneSpec = empty($stoneSpec) ? 0 : $stoneSpec;

                $stoneShape = intval($arr[2]);
                $stoneColorId = intval($arr[3]);
                $stonePurityId = intval($arr[4]);
                $stoneNumber = intval($arr[5]);
                if($isSelfStone == 0) {
                    $ValidateArr = array(
                        myValidate::ConnectStr(array($stoneCategory, 1, "请选择" . $stoneName . "头类别")),
                        //myValidate::ConnectStr(array($stoneSpec, 1, "请选择主石规格")),
                        myValidate::ConnectStr(array($stoneShape, 1, "请选择" . $stoneName . "形状")),
                        myValidate::ConnectStr(array($stoneColorId, 1, "请选择" . $stoneName . "颜色")),
                        myValidate::ConnectStr(array($stonePurityId, 1, "请选择" . $stoneName . "纯净度")),
                        myValidate::ConnectStr(array($stoneNumber, 1, "请正确填写" . $stoneName . "数量"))
                    );
                    $err = myValidate::VlidateIntegerGt($ValidateArr);
                    if (!empty($err) && $IsCheckStone) {
                        echo myResponse::ResponseDataFalseString($err);
                        return false;
                    }
                }
            }
        }
        return true;
    }
    private static function CHECKORDERCURRENTDOMODELITEMDO(&$memberId,&$number,&$handSize
        ,&$stoneCategory,&$stoneCategoryA,&$stoneCategoryB,&$stoneCategoryC
        ,&$stoneSpecValue,&$stoneSpecValueA,&$stoneSpecValueB,&$stoneSpecValueC
        ,&$stoneSpec,&$stoneSpecA,&$stoneSpecB,&$stoneSpecC
        ,&$stoneShape,&$stoneShapeA,&$stoneShapeB,&$stoneShapeC
        ,&$stoneColorId,&$stoneColorIdA,&$stoneColorIdB,&$stoneColorIdC
        ,&$stonePurityId,&$stonePurityIdA,&$stonePurityIdB,&$stonePurityIdC
        ,&$stoneNumber,&$stoneNumberA,&$stoneNumberB,&$stoneNumberC
        ,&$stoneOutA,&$stoneOutB,&$stoneOutC
        ,&$remarks,&$isSelfStone,&$jewelStoneId,$stone = '',$stoneA = '',$stoneB = '',$stoneC = '',$IsCheckStone = true)
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        if(empty($memberId))
        {
            echo myResponse::ResponseDataNoLoginString();
            return false;
        }
        $number = I('get.number', '');
        $handSize = I('get.handSize', '');
        $jewelStoneId = intval(I("get.jewelStoneId"));
        if($jewelStoneId>0)//选择石头（主石）
        {
            $stoneCategory = 0;
            $stoneShape = 0;
            $stoneColorId = 0;
            $stonePurityId = 0;
            $stoneNumber = 1;
            return true;
        }
        $isSelfStone = intval(I("get.isSelfStone"));
        /*$ValidateArr = array(
            myValidate::ConnectStr(array($number, 1, "请正确填写订购件数")));
        $err = myValidate::VlidateIntegerGt($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return false;
        }*/
        /*if(!is_numeric($handSize))
        {
            echo myResponse::ResponseDataFalseString("请正确填写手寸");
            return false;
        }*/
        //if(!is_int($number) && $number != 0.5)
        if(!FunctionCode::isInteger($number) && (floatval($number) - floor(floatval($number))) != 0.5)
        {
            echo myResponse::ResponseDataFalseString("请正确填写订购件数,件数只能是整数或x.5");
            return false;
        }
        if(empty($stone)) {
            $stone = I('get.stone', '');
        }
        if(!self::CHECKORDERCURRENTDOMODELITEMFORMAINSTONEVALUE($stone,"主石",$stoneCategory
            ,$stoneSpec,$stoneSpecValue,$stoneShape,$stoneColorId,$stonePurityId,$stoneNumber,$isSelfStone,$IsCheckStone))
            //如果是自带主石1那么信息不需要都填写
        {
            return false;
        }
        if(empty($stoneA)) {
            $stoneA = I('get.stoneA', '');
        }
        if(!self::CHECKORDERCURRENTDOMODELITEMFORSTONEVALUE($stoneA,"副石A",$stoneCategoryA
            ,$stoneSpecA,$stoneSpecValueA,$stoneShapeA,$stoneColorIdA,$stonePurityIdA,$stoneNumberA,$stoneOutA,$IsCheckStone))
        {
            return false;
        }
        if(empty($stoneB)) {
            $stoneB = I('get.stoneB', '');
        }
        if(!self::CHECKORDERCURRENTDOMODELITEMFORSTONEVALUE($stoneB,"副石B",$stoneCategoryB
            ,$stoneSpecB,$stoneSpecValueB,$stoneShapeB,$stoneColorIdB,$stonePurityIdB,$stoneNumberB,$stoneOutB,$IsCheckStone))
        {
            return false;
        }
        if(empty($stoneC)) {
            $stoneC = I('get.stoneC', '');
        }
        if(!self::CHECKORDERCURRENTDOMODELITEMFORSTONEVALUE($stoneC,"副石C",$stoneCategoryC
            ,$stoneSpecC,$stoneSpecValueC,$stoneShapeC,$stoneColorIdC,$stonePurityIdC,$stoneNumberC,$stoneOutC,$IsCheckStone))
        {
            return false;
        }

        $remarks = I('get.remarks', '');

        return true;
    }
    //添加到当前下单
    public static function OrderCurrentDoModelItemDo()
    {
        $modelProductId = I('get.productId', '');
        $ValidateArr = array(
            myValidate::ConnectStr(array($modelProductId, 1, "没有id")));
        $err = myValidate::VlidateIntegerGt($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return false;
        }
        if(self::CHECKORDERCURRENTDOMODELITEMDO($memberId,$number,$handSize
            ,$stoneCategory,$stoneCategoryA,$stoneCategoryB,$stoneCategoryC
            ,$stoneSpecValue,$stoneSpecValueA,$stoneSpecValueB,$stoneSpecValueC
            ,$stoneSpec,$stoneSpecA,$stoneSpecB,$stoneSpecC
            ,$stoneShape,$stoneShapeA,$stoneShapeB,$stoneShapeC
            ,$stoneColorId,$stoneColorIdA,$stoneColorIdB,$stoneColorIdC
            ,$stonePurityId,$stonePurityIdA,$stonePurityIdB,$stonePurityIdC
            ,$stoneNumber,$stoneNumberA,$stoneNumberB,$stoneNumberC
            ,$stoneOutA,$stoneOutB,$stoneOutC
            ,$remarks,$isSelfStone,$jewelStoneId)) {

            $reObj = ModelOrderCls::OrderCurrentModelItemDo($modelProductId, $memberId, $number, $handSize
                , $stoneCategory, $stoneCategoryA, $stoneCategoryB, $stoneCategoryC
                , $stoneSpecValue,$stoneSpecValueA,$stoneSpecValueB,$stoneSpecValueC
                , $stoneSpec, $stoneSpecA, $stoneSpecB, $stoneSpecC
                , $stoneShape, $stoneShapeA, $stoneShapeB, $stoneShapeC
                , $stoneColorId, $stoneColorIdA, $stoneColorIdB, $stoneColorIdC
                , $stonePurityId, $stonePurityIdA, $stonePurityIdB, $stonePurityIdC
                , $stoneNumber, $stoneNumberA, $stoneNumberB, $stoneNumberC
                , $stoneOutA,$stoneOutB,$stoneOutC
                , $remarks,$isSelfStone,$jewelStoneId,ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0);
            echo myResponse::ToResponseJsonString($reObj);
        }
    }
    public static function SETORDERCURRENTDOMODELSTONEITEMFORDEFULTVALUE($stone)
    {
        if(!empty($stone)) {
            $arrStone = explode('|', $stone);
            $split = "|";
            if (count($arrStone) == 7) {
                $stoneCategory = intval($arrStone[0]) == 0 ? -1 : intval($arrStone[0]);
                $stoneSpecValue = BllPublic::GetStandardSpec($arrStone[1]);
                //$stoneSpec = BllPublic::GetSpecValueId($stoneSpecValue);
                $stoneSpec = empty($stoneSpecValue) ? 0.3 : $stoneSpecValue;
                $stoneShape = intval($arrStone[2]) == 0 ? -1 : intval($arrStone[2]);
                $stoneColorId = intval($arrStone[3]) == 0 ? -1 : intval($arrStone[3]);
                $stonePurityId = intval($arrStone[4]) == 0 ? -1 : intval($arrStone[4]);
                $stoneNumber = intval($arrStone[5]) == 0 ? -1 : intval($arrStone[5]);
                $isstoneOut = intval($arrStone[6]);
                $stoneNew = $stoneCategory . $split . $stoneSpec . $split . $stoneShape . $split . $stoneColorId . $split . $stonePurityId . $split . $stoneNumber.$split.$isstoneOut;
                return $stoneNew;
            }
        }
        return $split.$split.$split.$split.$split.$split;
    }
    public static function SETORDERCURRENTDOMODELMAINSTONEITEMFORDEFULTVALUE($stone)
    {
        if(!empty($stone)) {
            $arrStone = explode('|', $stone);
            $split = "|";
            if (count($arrStone) == 6) {
                $stoneCategory = intval($arrStone[0]) == 0 ? -1 : intval($arrStone[0]);
                $stoneSpecValue = BllPublic::GetStandardSpec($arrStone[1]);
                //$stoneSpec = BllPublic::GetSpecValueId($stoneSpecValue);
                $stoneSpec = empty($stoneSpecValue) ? 0.3 : $stoneSpecValue;
                $stoneShape = intval($arrStone[2]) == 0 ? -1 : intval($arrStone[2]);
                $stoneColorId = intval($arrStone[3]) == 0 ? -1 : intval($arrStone[3]);
                $stonePurityId = intval($arrStone[4]) == 0 ? -1 : intval($arrStone[4]);
                $stoneNumber = intval($arrStone[5]) == 0 ? -1 : intval($arrStone[5]);
                $stoneNew = $stoneCategory . $split . $stoneSpec . $split . $stoneShape . $split . $stoneColorId . $split . $stonePurityId . $split . $stoneNumber;
                return $stoneNew;
            }
        }
        return $split.$split.$split.$split.$split;
    }
    //添加到当前下单(使用默认值)
    public static function OrderCurrentDoModelItemForDefaultDo()
    {
        $modelProductId = I('get.productId', '');
        $ValidateArr = array(
            myValidate::ConnectStr(array($modelProductId, 1, "没有id")));
        $err = myValidate::VlidateIntegerGt($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return false;
        }
        $stone = self::SETORDERCURRENTDOMODELMAINSTONEITEMFORDEFULTVALUE(I('get.stone', ''));
        $stoneA = self::SETORDERCURRENTDOMODELSTONEITEMFORDEFULTVALUE(I('get.stoneA', ''));
        $stoneB = self::SETORDERCURRENTDOMODELSTONEITEMFORDEFULTVALUE(I('get.stoneB', ''));
        $stoneC = self::SETORDERCURRENTDOMODELSTONEITEMFORDEFULTVALUE(I('get.stoneC', ''));
        if(self::CHECKORDERCURRENTDOMODELITEMDO($memberId,$number,$handSize
            ,$stoneCategory,$stoneCategoryA,$stoneCategoryB,$stoneCategoryC
            ,$stoneSpecValue,$stoneSpecValueA,$stoneSpecValueB,$stoneSpecValueC
            ,$stoneSpec,$stoneSpecA,$stoneSpecB,$stoneSpecC
            ,$stoneShape,$stoneShapeA,$stoneShapeB,$stoneShapeC
            ,$stoneColorId,$stoneColorIdA,$stoneColorIdB,$stoneColorIdC
            ,$stonePurityId,$stonePurityIdA,$stonePurityIdB,$stonePurityIdC
            ,$stoneNumber,$stoneNumberA,$stoneNumberB,$stoneNumberC
            ,$stoneOutA,$stoneOutB,$stoneOutC
            ,$remarks,$isSelfStone,$jewelStoneId,$stone,$stoneA,$stoneB,$stoneC,false)) {

            $reObj = ModelOrderCls::OrderCurrentModelItemDo($modelProductId, $memberId, $number, $handSize
                , $stoneCategory, $stoneCategoryA, $stoneCategoryB, $stoneCategoryC
                , $stoneSpecValue,$stoneSpecValueA,$stoneSpecValueB,$stoneSpecValueC
                , $stoneSpec, $stoneSpecA, $stoneSpecB, $stoneSpecC
                , $stoneShape, $stoneShapeA, $stoneShapeB, $stoneShapeC
                , $stoneColorId, $stoneColorIdA, $stoneColorIdB, $stoneColorIdC
                , $stonePurityId, $stonePurityIdA, $stonePurityIdB, $stonePurityIdC
                , $stoneNumber, $stoneNumberA, $stoneNumberB, $stoneNumberC
                , $stoneOutA,$stoneOutB,$stoneOutC
                , $remarks,$isSelfStone,$jewelStoneId,ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0);
            echo myResponse::ToResponseJsonString($reObj);
        }
    }

    public static function OrderCurrentEditModelItemDoForPublic($itemId,$purityId,$qualityId)
    {
        $ValidateArr = array(
            myValidate::ConnectStr(array($itemId, 1, "没有id")));
        $err = myValidate::VlidateIntegerGt($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return false;
        }
        if(self::CHECKORDERCURRENTDOMODELITEMDO($memberId,$number,$handSize
            ,$stoneCategory,$stoneCategoryA,$stoneCategoryB,$stoneCategoryC
            ,$stoneSpecValue,$stoneSpecValueA,$stoneSpecValueB,$stoneSpecValueC
            ,$stoneSpec,$stoneSpecA,$stoneSpecB,$stoneSpecC
            ,$stoneShape,$stoneShapeA,$stoneShapeB,$stoneShapeC
            ,$stoneColorId,$stoneColorIdA,$stoneColorIdB,$stoneColorIdC
            ,$stonePurityId,$stonePurityIdA,$stonePurityIdB,$stonePurityIdC
            ,$stoneNumber,$stoneNumberA,$stoneNumberB,$stoneNumberC
            ,$stoneOutA,$stoneOutB,$stoneOutC
            ,$remarks,$isSelfStone,$jewelStoneId)) {

            $reObj = ModelOrderCls::OrderCurrentEditModelItemDo($itemId, $memberId, $number, $handSize
                , $stoneCategory, $stoneCategoryA, $stoneCategoryB, $stoneCategoryC
                , $stoneSpecValue,$stoneSpecValueA,$stoneSpecValueB,$stoneSpecValueC
                , $stoneSpec, $stoneSpecA, $stoneSpecB, $stoneSpecC
                , $stoneShape, $stoneShapeA, $stoneShapeB, $stoneShapeC
                , $stoneColorId, $stoneColorIdA, $stoneColorIdB, $stoneColorIdC
                , $stonePurityId, $stonePurityIdA, $stonePurityIdB, $stonePurityIdC
                , $stoneNumber, $stoneNumberA, $stoneNumberB, $stoneNumberC
                , $stoneOutA,$stoneOutB,$stoneOutC
                , $remarks,$purityId,$qualityId,$isSelfStone,$jewelStoneId);
            echo myResponse::ToResponseJsonString($reObj);
        }
    }
    public static function OrderCurrentEditModelItemForDefaultDoForPublic($itemId,$purityId,$qualityId)
    {
        $ValidateArr = array(
            myValidate::ConnectStr(array($itemId, 1, "没有id")));
        $err = myValidate::VlidateIntegerGt($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return false;
        }
        $stone = self::SETORDERCURRENTDOMODELMAINSTONEITEMFORDEFULTVALUE(I('get.stone', ''));
        $stoneA = self::SETORDERCURRENTDOMODELSTONEITEMFORDEFULTVALUE(I('get.stoneA', ''));
        $stoneB = self::SETORDERCURRENTDOMODELSTONEITEMFORDEFULTVALUE(I('get.stoneB', ''));
        $stoneC = self::SETORDERCURRENTDOMODELSTONEITEMFORDEFULTVALUE(I('get.stoneC', ''));
        if(self::CHECKORDERCURRENTDOMODELITEMDO($memberId,$number,$handSize
            ,$stoneCategory,$stoneCategoryA,$stoneCategoryB,$stoneCategoryC
            ,$stoneSpecValue,$stoneSpecValueA,$stoneSpecValueB,$stoneSpecValueC
            ,$stoneSpec,$stoneSpecA,$stoneSpecB,$stoneSpecC
            ,$stoneShape,$stoneShapeA,$stoneShapeB,$stoneShapeC
            ,$stoneColorId,$stoneColorIdA,$stoneColorIdB,$stoneColorIdC
            ,$stonePurityId,$stonePurityIdA,$stonePurityIdB,$stonePurityIdC
            ,$stoneNumber,$stoneNumberA,$stoneNumberB,$stoneNumberC
            ,$stoneOutA,$stoneOutB,$stoneOutC
            ,$remarks,$isSelfStone,$jewelStoneId,$stone,$stoneA,$stoneB,$stoneC,false)) {

            $reObj = ModelOrderCls::OrderCurrentEditModelItemDo($itemId, $memberId, $number, $handSize
                , $stoneCategory, $stoneCategoryA, $stoneCategoryB, $stoneCategoryC
                , $stoneSpecValue,$stoneSpecValueA,$stoneSpecValueB,$stoneSpecValueC
                , $stoneSpec, $stoneSpecA, $stoneSpecB, $stoneSpecC
                , $stoneShape, $stoneShapeA, $stoneShapeB, $stoneShapeC
                , $stoneColorId, $stoneColorIdA, $stoneColorIdB, $stoneColorIdC
                , $stonePurityId, $stonePurityIdA, $stonePurityIdB, $stonePurityIdC
                , $stoneNumber, $stoneNumberA, $stoneNumberB, $stoneNumberC
                , $stoneOutA,$stoneOutB,$stoneOutC
                , $remarks,$purityId,$qualityId,$isSelfStone,$jewelStoneId);
            echo myResponse::ToResponseJsonString($reObj);
        }
    }
    //提交生成订单
    public static function OrderCurrentSubmitDo()
    {
        $myKey = UserCls::GetRequestTokenKey();

        $itemId = I('get.itemId', '');
        if(!myValidate::IsArrIntegerForSplit($itemId,'|')) {
            echo myResponse::ResponseDataFalseString("传递参数不正确");
            return;
        }
        $addressId = I('get.addressId', '');
        $purityId = I('get.purityId', '');
        $customerID  = I('get.customerID', '');
        $qualityId = I('get.qualityId', '');
        $word = I('get.word', '');
        $invTitle = I('get.invTitle', '');
        $invoiceType = I('get.invType', '');
        $orderNote = I('get.orderNote', '');
        $ValidateArr = array(
            myValidate::ConnectStr(array($addressId, 0, "请选择收货地址")),
            myValidate::ConnectStr(array($purityId, 1, "请选择成色")),
            myValidate::ConnectStr(array($qualityId, 1, "请选择质量等级")),
            myValidate::ConnectStr(array($customerID, 1, "请选择客户"))
        );
        $err = myValidate::VlidateIntegerGt($ValidateArr);
        if (!empty($err)) {
            echo myResponse::ResponseDataFalseString($err);
            return false;
        }

        $memberId = UserCls::GetUserId($myKey);
        if(empty($memberId))
        {
            echo myResponse::ResponseDataNoLoginString();
            return false;
        }
        $OrderErpId = UserCls::GetOrderErpId($myKey);
        $IsCheckErpOrderCount =  M("member")->where(array("id"=>$memberId,"IsCheckErpOrder"=>0))->count();
        $reObj = ModelOrderCls::OrderCurrentSubmitDo($itemId,$memberId,$customerID,$OrderErpId,$addressId,$purityId,$qualityId,$word,$invTitle,$invoiceType,$orderNote,$IsCheckErpOrderCount);
        if(!empty($reObj) && $reObj->error == 0 && !empty($reObj->data["id"]))
        {
            $reObj->data["isErpOrder"] = 0;
            if($IsCheckErpOrderCount>0)
            {
                $reErpObj = ModelOrderCls::ModelOrderWaitCheckVerifyToProduceDo($memberId,$reObj->data["id"]);
                if($reErpObj->error == 0) {
                    $reObj->data["isErpOrder"] = 1;
                    $reObj->data["isNeetPay"] = 0;
                }
            }
            echo myResponse::ToResponseJsonString($reObj);
        }
        else {
            echo myResponse::ToResponseJsonString($reObj);
        }
    }

}
