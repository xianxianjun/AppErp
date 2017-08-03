<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/29
 * Time: 10:51
 */
namespace Common\Common\Api\flow;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ConvertType;
use Common\Common\PublicCode\WithSql;
use Common\Common\PublicCode\ErpPublicCode;

trait ModelCurrentOrderDo
{
    public static function OrderCurrentModelItemDo($modeProductId,$memberId,$number,$handSize
        ,$stoneCategory,$stoneCategoryA,$stoneCategoryB,$stoneCategoryC
        ,$stoneSpecValue,$stoneSpecValueA,$stoneSpecValueB,$stoneSpecValueC
        ,$stoneSpec,$stoneSpecA,$stoneSpecB,$stoneSpecC
        ,$stoneShape,$stoneShapeA,$stoneShapeB,$stoneShapeC
        ,$stoneColorId,$stoneColorIdA,$stoneColorIdB,$stoneColorIdC
        ,$stonePurityId,$stonePurityIdA,$stonePurityIdB,$stonePurityIdC
        ,$stoneNumber,$stoneNumberA,$stoneNumberB,$stoneNumberC
        ,$stoneOutA,$stoneOutB,$stoneOutC
        ,$remarks,$isSelfStone,$jewelStoneId,$orderStatus)
    {
        if(FunctionCode::isInteger($modeProductId))
        {
            $con = M('model_product')->where(array("id"=>$modeProductId))->count();
            if($con <= 0)
            {
                return myResponse::ResponseDataFalseObj("此id没有任何产品");
            }
        }
        else
        {
            return myResponse::ResponseDataFalseObj("没有id");
        }

        $con = array("member_id"=>$memberId
        ,"model_product_id"=>$modeProductId
        ,"handSize"=>$handSize
        //,"model_category_id"=>$modeCategoryId
        ,"orderStatus"=>$orderStatus//状态orderStatusarray('ELT',$orderStatus)

        ,"jewelStoneId"=>ConvertType::ConvertInt($jewelStoneId,0)

        ,"stone_category_id"=>ConvertType::ConvertInt($stoneCategory,-1)
        ,"stone_category_id_A"=>ConvertType::ConvertInt($stoneCategoryA,-1)
        ,"stone_category_id_B"=>ConvertType::ConvertInt($stoneCategoryB,-1)
        ,"stone_category_id_C"=>ConvertType::ConvertInt($stoneCategoryC,-1)

        ,"stone_spec_id"=>ConvertType::ConvertInt($stoneSpec,0)
        ,"stone_spec_id_A"=>ConvertType::ConvertInt($stoneSpecA,0)
        ,"stone_spec_id_B"=>ConvertType::ConvertInt($stoneSpecB,0)
        ,"stone_spec_id_C"=>ConvertType::ConvertInt($stoneSpecC,0)

        ,"stone_spec_value"=>$stoneSpecValue
        ,"stone_spec_value_A"=>$stoneSpecValueA
        ,"stone_spec_value_B"=>$stoneSpecValueB
        ,"stone_spec_value_C"=>$stoneSpecValueC

        ,"stone_shape_id"=>ConvertType::ConvertInt($stoneShape,0)
        ,"stone_shape_id_A"=>ConvertType::ConvertInt($stoneShapeA,0)
        ,"stone_shape_id_B"=>ConvertType::ConvertInt($stoneShapeB,0)
        ,"stone_shape_id_C"=>ConvertType::ConvertInt($stoneShapeC,0)

        ,"stone_color_id"=>ConvertType::ConvertInt($stoneColorId,0)
        ,"stone_color_id_A"=>ConvertType::ConvertInt($stoneColorIdA,0)
        ,"stone_color_id_B"=>ConvertType::ConvertInt($stoneColorIdB,0)
        ,"stone_color_id_C"=>ConvertType::ConvertInt($stoneColorIdC,0)

        ,"stone_purity_id"=>ConvertType::ConvertInt($stonePurityId,0)
        ,"stone_purity_id_A"=>ConvertType::ConvertInt($stonePurityIdA,0)
        ,"stone_purity_id_B"=>ConvertType::ConvertInt($stonePurityIdB,0)
        ,"stone_purity_id_C"=>ConvertType::ConvertInt($stonePurityIdC,0)

        ,"stone_number"=>ConvertType::ConvertInt($stoneNumber,0)
        ,"stone_number_A"=>ConvertType::ConvertInt($stoneNumberA,0)
        ,"stone_number_B"=>ConvertType::ConvertInt($stoneNumberB,0)
        ,"stone_number_C"=>ConvertType::ConvertInt($stoneNumberC,0)

        ,"stone_out_A"=>ConvertType::ConvertInt($stoneOutA,0)
        ,"stone_out_B"=>ConvertType::ConvertInt($stoneOutB,0)
        ,"stone_out_C"=>ConvertType::ConvertInt($stoneOutC,0)

        ,"isSelfStone"=>$isSelfStone
        );
        $modeMainOrder = M('model_main_order_current_detail');
        $ModelData = $modeMainOrder->field('id,number')->where($con)->select();
        //echo $modeMainOrder->getLastSql();
        if(count($ModelData)>0)
        {
            $conUp = array("id"=>$ModelData[0]["id"]);
            $data["number"] = $ModelData[0]["number"]+$number;
            $data["updateDate"] = FunctionCode::GetNowTimeDate();
            $re = $modeMainOrder->where($conUp)->save($data);
            if(!$re)
            {
                return myResponse::ResponseDataFalseObj("添加到当前订单失败");
            }
        }
        else {
            $jewelStoneData = array();

            $data["model_product_id"] = $modeProductId;
            $data["member_id"] = $memberId;
            $data["number"] = $number;
            $data["handSize"] = $handSize;
            //$data["model_category_id"] = $modeCategoryId;
            if($jewelStoneId>0) {
                $stoneData = ErpPublicCode::PostUrlForObjData(array("id"=>$jewelStoneId),"SearchStone","stone","GetstoneSearchConditionEntity","entity");
                $stoneDataItem = $stoneData->list[0];
                if(count($stoneDataItem)>0 && !empty($stoneDataItem->CertAuth) && !empty($stoneDataItem->CertCode)) {
                    $data["jewelStoneId"] = ConvertType::ConvertInt($stoneDataItem->id, 0);
                    $data["jewelStoneCode"] = $stoneDataItem->CertCode;

                    $jewelStoneData["CertAuth"] = $stoneDataItem->CertAuth;
                    $jewelStoneData["CertCode"] = $stoneDataItem->CertCode;
                    $jewelStoneData["Weight"] = $stoneDataItem->Weight;
                    $jewelStoneData["Price"] = $stoneDataItem->Price;
                    $jewelStoneData["discountPrice"] = $stoneDataItem->Price;
                    $jewelStoneData["BarCode"] = $stoneDataItem->BarCode;
                    $jewelStoneData["Shape"] = $stoneDataItem->Shape;
                    $jewelStoneData["Color"] = $stoneDataItem->Color;
                    $jewelStoneData["Purity"] = $stoneDataItem->Purity;
                    $jewelStoneData["Cut"] = $stoneDataItem->Cut;
                    $jewelStoneData["Polishing"] = $stoneDataItem->Polishing;
                    $jewelStoneData["Symmetric"] = $stoneDataItem->Symmetric;
                    $jewelStoneData["Fluorescence"] = $stoneDataItem->Fluorescence;
                    $jewelStoneData["Source"] = $stoneDataItem->Source;
                    $jewelStoneData["StoreName"] = $stoneDataItem->StoreName;
                    $jewelStoneData["member_Id"] = $memberId;
                    $jewelStoneData["jewel_stone_id"] = $stoneDataItem->id;
                    $jewelStoneData["indate"] = $stoneDataItem->InDate;
                    $jewelStoneData["number"] = 1;
                }
                else
                {
                    return myResponse::ResponseDataFalseObj("没有选择的石头或选择石头出错!");
                }
            }

            $data["stone_category_id"] = ConvertType::ConvertInt($stoneCategory,0);
            $data["stone_category_id_A"] = ConvertType::ConvertInt($stoneCategoryA,0);
            $data["stone_category_id_B"] = ConvertType::ConvertInt($stoneCategoryB,0);
            $data["stone_category_id_C"] = ConvertType::ConvertInt($stoneCategoryC,0);

            $data["stone_spec_id"] = ConvertType::ConvertInt($stoneSpec,0);
            $data["stone_spec_id_A"] = ConvertType::ConvertInt($stoneSpecA,0);
            $data["stone_spec_id_B"] = ConvertType::ConvertInt($stoneSpecB,0);
            $data["stone_spec_id_C"] = ConvertType::ConvertInt($stoneSpecC,0);

            $data["stone_shape_id"] = ConvertType::ConvertInt($stoneShape,0);
            $data["stone_shape_id_A"] = ConvertType::ConvertInt($stoneShapeA,0);
            $data["stone_shape_id_B"] = ConvertType::ConvertInt($stoneShapeB,0);
            $data["stone_shape_id_C"] = ConvertType::ConvertInt($stoneShapeC,0);


            $data["stone_spec_value"] = $stoneSpecValue;
            $data["stone_spec_value_A"] = $stoneSpecValueA;
            $data["stone_spec_value_B"] = $stoneSpecValueB;
            $data["stone_spec_value_C"] = $stoneSpecValueC;

            $data["stone_color_id"] = ConvertType::ConvertInt($stoneColorId,0);
            $data["stone_color_id_A"] = ConvertType::ConvertInt($stoneColorIdA,0);
            $data["stone_color_id_B"] = ConvertType::ConvertInt($stoneColorIdB,0);
            $data["stone_color_id_C"] = ConvertType::ConvertInt($stoneColorIdC,0);

            $data["stone_purity_id"] = ConvertType::ConvertInt($stonePurityId,0);
            $data["stone_purity_id_A"] = ConvertType::ConvertInt($stonePurityIdA,0);
            $data["stone_purity_id_B"] = ConvertType::ConvertInt($stonePurityIdB,0);
            $data["stone_purity_id_C"] = ConvertType::ConvertInt($stonePurityIdC,0);

            $data["stone_number"] = ConvertType::ConvertInt($stoneNumber,0);
            $data["stone_number_A"] = ConvertType::ConvertInt($stoneNumberA,0);
            $data["stone_number_B"] = ConvertType::ConvertInt($stoneNumberB,0);
            $data["stone_number_C"] = ConvertType::ConvertInt($stoneNumberC,0);

            $data["stone_out_A"] = ConvertType::ConvertInt($stoneOutA,0);
            $data["stone_out_B"] = ConvertType::ConvertInt($stoneOutB,0);
            $data["stone_out_C"] = ConvertType::ConvertInt($stoneOutC,0);

            $data["remarks"] = $remarks;
            $data["isSelfStone"] = $isSelfStone;


            $data["updateDate"] = FunctionCode::GetNowTimeDate();
            $data["createDate"] = FunctionCode::GetNowTimeDate();

            $re = $modeMainOrder->data($data)->add();
            if(!$re)
            {
                return myResponse::ResponseDataFalseObj("添加到当前订单失败");
            }
            if(!empty($jewelStoneData["CertCode"])) {
                $jewelStoneData["model_main_order_current_detail_id"] = $re;
                $re = M("model_order_jewel_stone_log")->data($jewelStoneData)->add();
            }
        }
        $waitOrderCount = ModelOrderCls::GetOrderListCount($memberId);
        return myResponse::ResponseDataTrueObj("添加到当前订单成功",array("waitOrderCount"=>$waitOrderCount));
    }

    public static function OrderCurrentDeleteModelItemDo($id,$memberId)
    {
        //状态orderStatus
        $re = M('model_main_order_current_detail')->where(array("id"=>$id,"member_id"=>$memberId,"orderStatus"=>array('ELT',ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000)))->delete();
        if(!$re)
        {
            return myResponse::ResponseDataFalseObj("删除失败");
        }
        $waitOrderCount = ModelOrderCls::GetOrderListCount($memberId);
        return myResponse::ResponseDataTrueObj("删除成功",array("waitOrderCount"=>$waitOrderCount));
    }
    public static function OrderCurrentEditModelItemDo($orderItemId,$memberId,$number,$handSize
        ,$stoneCategory,$stoneCategoryA,$stoneCategoryB,$stoneCategoryC
        , $stoneSpecValue,$stoneSpecValueA,$stoneSpecValueB,$stoneSpecValueC
        ,$stoneSpec,$stoneSpecA,$stoneSpecB,$stoneSpecC
        ,$stoneShape,$stoneShapeA,$stoneShapeB,$stoneShapeC
        ,$stoneColorId,$stoneColorIdA,$stoneColorIdB,$stoneColorIdC
        ,$stonePurityId,$stonePurityIdA,$stonePurityIdB,$stonePurityIdC
        ,$stoneNumber,$stoneNumberA,$stoneNumberB,$stoneNumberC
        ,$stoneOutA,$stoneOutB,$stoneOutC
        ,$remarks,$purityId,$qualityId,$isSelfStone,$jewelStoneId)
    {
        //$data["model_product_id"] = $modeProductId;
        //$data["member_id"] = $memberId;
        $data["number"] = $number;
        $data["handSize"] = $handSize;
        //$data["model_category_id"] = $modeCategoryId;
        if($jewelStoneId>0) {
            $stoneData = ErpPublicCode::PostUrlForObjData(array("id"=>$jewelStoneId),"SearchStone","stone","GetstoneSearchConditionEntity","entity");
            $stoneDataItem = $stoneData->list[0];
            if(count($stoneDataItem)>0 && !empty($stoneDataItem->CertAuth) && !empty($stoneDataItem->CertCode)) {
                $data["jewelStoneId"] = ConvertType::ConvertInt($stoneDataItem->CertAuth, 0);
                $data["jewelStoneCode"] = $stoneDataItem->CertCode;

                $jewelStoneData["CertAuth"] = $stoneDataItem->CertAuth;
                $jewelStoneData["CertCode"] = $stoneDataItem->CertCode;
                $jewelStoneData["Weight"] = $stoneDataItem->Weight;
                $jewelStoneData["discountPrice"] = $stoneDataItem->Price;
                $jewelStoneData["Price"] = $stoneDataItem->Price;
                $jewelStoneData["BarCode"] = $stoneDataItem->BarCode;
                $jewelStoneData["Shape"] = $stoneDataItem->Shape;
                $jewelStoneData["Color"] = $stoneDataItem->Color;
                $jewelStoneData["Purity"] = $stoneDataItem->Purity;
                $jewelStoneData["Cut"] = $stoneDataItem->Cut;
                $jewelStoneData["Polishing"] = $stoneDataItem->Polishing;
                $jewelStoneData["Symmetric"] = $stoneDataItem->Symmetric;
                $jewelStoneData["Fluorescence"] = $stoneDataItem->Fluorescence;
                $jewelStoneData["Source"] = $stoneDataItem->Source;
                $jewelStoneData["StoreName"] = $stoneDataItem->StoreName;
                $jewelStoneData["member_Id"] = $memberId;
                $jewelStoneData["jewel_stone_id"] = $stoneDataItem->id;
                $jewelStoneData["indate"] = $stoneDataItem->InDate;
                $jewelStoneData["number"] = 1;
            }
            else
            {
                return myResponse::ResponseDataFalseObj("没有选择的石头或选择石头出错!");
            }
        }
        else {
            $data["jewelStoneId"] = 0;
            $data["jewelStoneCode"] = '';
        }

        $data["stone_category_id"] = ConvertType::ConvertInt($stoneCategory,0);
        $data["stone_category_id_A"] = ConvertType::ConvertInt($stoneCategoryA,0);
        $data["stone_category_id_B"] = ConvertType::ConvertInt($stoneCategoryB,0);
        $data["stone_category_id_C"] = ConvertType::ConvertInt($stoneCategoryC,0);

        $data["stone_spec_id"] = ConvertType::ConvertInt($stoneSpec,0);
        $data["stone_spec_id_A"] = ConvertType::ConvertInt($stoneSpecA,0);
        $data["stone_spec_id_B"] = ConvertType::ConvertInt($stoneSpecB,0);
        $data["stone_spec_id_C"] = ConvertType::ConvertInt($stoneSpecC,0);

        $data["stone_spec_value"] = $stoneSpecValue;
        $data["stone_spec_value_A"] = $stoneSpecValueA;
        $data["stone_spec_value_B"] = $stoneSpecValueB;
        $data["stone_spec_value_C"] = $stoneSpecValueC;

        $data["stone_shape_id"] = ConvertType::ConvertInt($stoneShape,0);
        $data["stone_shape_id_A"] = ConvertType::ConvertInt($stoneShapeA,0);
        $data["stone_shape_id_B"] = ConvertType::ConvertInt($stoneShapeB,0);
        $data["stone_shape_id_C"] = ConvertType::ConvertInt($stoneShapeC,0);

        $data["stone_color_id"] = ConvertType::ConvertInt($stoneColorId,0);
        $data["stone_color_id_A"] = ConvertType::ConvertInt($stoneColorIdA,0);
        $data["stone_color_id_B"] = ConvertType::ConvertInt($stoneColorIdB,0);
        $data["stone_color_id_C"] = ConvertType::ConvertInt($stoneColorIdC,0);

        $data["stone_purity_id"] = ConvertType::ConvertInt($stonePurityId,0);
        $data["stone_purity_id_A"] = ConvertType::ConvertInt($stonePurityIdA,0);
        $data["stone_purity_id_B"] = ConvertType::ConvertInt($stonePurityIdB,0);
        $data["stone_purity_id_C"] = ConvertType::ConvertInt($stonePurityIdC,0);

        $data["stone_out_A"] = ConvertType::ConvertInt($stoneOutA,0);
        $data["stone_out_B"] = ConvertType::ConvertInt($stoneOutB,0);
        $data["stone_out_C"] = ConvertType::ConvertInt($stoneOutC,0);

        $data["stone_number"] = ConvertType::ConvertInt($stoneNumber,0);
        $data["stone_number_A"] = ConvertType::ConvertInt($stoneNumberA,0);
        $data["stone_number_B"] = ConvertType::ConvertInt($stoneNumberB,0);
        $data["stone_number_C"] = ConvertType::ConvertInt($stoneNumberC,0);

        $data["isSelfStone"] = $isSelfStone;
        $data["remarks"] = $remarks;

        $data["updateDate"] = FunctionCode::GetNowTimeDate();
        //状态orderStatus
        $re = M('model_main_order_current_detail')->where(array("id"=>$orderItemId,"member_id"=>$memberId,"orderStatus"=>array('ELT',ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000)))->save($data);
        if(!$re)
        {
            return myResponse::ResponseDataFalseObj("修改下单款号失败");
        }
        //选择石头
        $jewelStoneCount = M('model_order_jewel_stone_log')->where(array("model_main_order_current_detail_id" => $orderItemId, "member_id" => $memberId))->count();
        if(!empty($jewelStoneData["CertCode"])) {
            if($jewelStoneCount>0) {
                $jewelStoneData["UpdateDate"] = FunctionCode::GetNowTimeDate();
                $re = M('model_order_jewel_stone_log')->where(array("model_main_order_current_detail_id" => $orderItemId, "member_id" => $memberId))->save($jewelStoneData);
            }
            else {
                $jewelStoneData["model_main_order_current_detail_id"] = $orderItemId;
                $re = M('model_order_jewel_stone_log')->data($jewelStoneData)->add();
            }
        }
        else if($jewelStoneCount>0) {
            M('model_order_jewel_stone_log')->where(array("model_main_order_current_detail_id" => $orderItemId, "member_id" => $memberId))->delete();
        }
        return ModelOrderCls::OrderListItem($orderItemId,$memberId,$purityId,$qualityId);
    }
    public static function OrderCurrentSubmitDo($itemIds,$memberId,$customerID,$OrderErpId,$addressId,$purityId,$qualityId,$word,$invoiceTitle,$invoiceType,$orderNote,$IsCheckErpOrderCount)
    {
        $inItemId = WithSql::ForInInteger("id","|",$itemIds);
        if(empty($inItemId))
        {
            return myResponse::ResponseDataFalseObj("没有任何id");
        }
        $orderDetail = M('model_main_order_current_detail');
        $con = $orderDetail->where($inItemId." and member_id=".$memberId." and orderStatus=".ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0)->count();
        if($con<=0)
        {
            return myResponse::ResponseDataFalseObj("没有选择任何下单款号");
        }
        $SubmitData = ErpPublicCode::GetUrlObjData("GetModelCurrentOrderPageSubmitData","model",array("purityId"=>$purityId,"qualityId"=>$qualityId,"erpid"=>$OrderErpId,"gid"=>$customerID));
        if(empty($SubmitData) || $SubmitData->isHaveGroupCustomerCount <= 0)
        {
            return myResponse::ResponseDataFalseObj("获取不到客户信息");
        }
        if($IsCheckErpOrderCount>0)//如果是不需要审核用户就不需要付定金
        {
            $isNeetPay = 0;
        }
        else//如果需要审核用户，看定金支付比例，如果是等于0，也不需要付定金
        {
            $payPercent = UserInfo::GetUserPayPercentByMemberId($memberId);
            $isNeetPay = $payPercent<=0?0:1;//是否需要定金
        }

        $modelPurityName = $SubmitData->purity->title;
        $modelQualityName = $SubmitData->quality->title;
        $newDate = FunctionCode::GetNowTimeDate();
        $orderId = FunctionCode::getTimeId();
        $orderMain = M('model_main_order_current');
        $data["orderNum"] = $orderId;
        $data["member_id"] = $memberId;
        $data["erp_client_id"] = $OrderErpId;
        $data["member_address_id"] = $addressId;
        $data["customerId"] = $customerID;
        $data["model_purity_id"] = $purityId;
        $data["model_quality_id"] = $qualityId;
        $data["modelPurityName"] = $modelPurityName;
        $data["modelQualityName"] = $modelQualityName;
        if(!empty($invoiceTitle))
        {
            $data["invoiceTitle"] = $invoiceTitle;
        }
        if(intval($invoiceType)>0)
        {
            $data["invoiceType"] = $invoiceType;
        }
        if($isNeetPay == 0) {
            $data["orderStatus"] = ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_ALREADY_PAY_200;
        }
        else {
            $data["orderStatus"] = ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_WAIT_PAY_100;
        }
        $data["createDate"] = $newDate;
        $data["updateDate"] = $newDate;
        $data["updateDate"] = $newDate;
        $data["word"] = $word;
        $data["orderNote"] = $orderNote;
        $newOrderId = $orderMain->data($data)->add();
        if(!$newOrderId)
        {
            return myResponse::ResponseDataFalseObj("生成订单失败");
        }
        //更新订单号
        $maxfn = M('model_main_order_current')->field('(IFNULL(max(fn),0)+1) as maxfn')->where("date(createDate)=curdate()")->select()[0]["maxfn"];
        $thisOrderNum = "AP".str_pad(str_pad($maxfn,3,"0",STR_PAD_LEFT), 9, substr($orderId,2), STR_PAD_LEFT);
        $uOrdNumData["orderNum"] = $thisOrderNum;
        $uOrdNumData["fn"] = $maxfn;
        M('model_main_order_current')->where(array("id"=>$newOrderId))->save($uOrdNumData);

        if($isNeetPay==0) {
            $udata["orderStatus"] = ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_ALREADY_PAY_200;
        }
        else {
            $udata["orderStatus"] = ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_WAIT_PAY_100;
        }
        $udata["model_main_order_current_id"] = $newOrderId;
        $udata["updateDate"] = $newDate;
        $re = $orderDetail->where($inItemId." and member_id=".$memberId." and orderStatus=".ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0)->save($udata);

        if(!$re)
        {
            return myResponse::ResponseDataFalseObj("生成订单失败");
        }

        //石头更新
        $inItemId = WithSql::ForInInteger("model_main_order_current_detail_id","|",$itemIds);
        $ujewelStoneData["model_main_order_current_id"] = $newOrderId;
        $ujewelStoneData["orderNum"] = $thisOrderNum;
        $ujewelStoneData["UpdateDate"] = FunctionCode::GetNowTimeDate();
        $re = M('model_order_jewel_stone_log')->where($inItemId." and member_id=".$memberId)->save($ujewelStoneData);

        $waitOrderCount = ModelOrderCls::GetOrderListCount($memberId);
        //"isNeetPay"=>$isNeetPay目前没有做付款动作
        return myResponse::ResponseDataTrueObj("生成订单成功",array("orderNum"=>$thisOrderNum,"isNeetPay"=>0,"id"=>$newOrderId,"waitOrderCount"=>$waitOrderCount));
    }
}
