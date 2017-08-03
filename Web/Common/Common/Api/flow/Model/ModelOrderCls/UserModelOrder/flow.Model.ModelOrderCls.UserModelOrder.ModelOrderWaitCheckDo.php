<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/11/10
 * Time: 16:17
 */
namespace Common\Common\Api\flow;

use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\BllPublic;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ConvertType;
use Common\Common\PublicCode\WithSql;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\flow\ModelCls;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\Code\ValidateCode;
use Common\Common\PublicCode\isHaveCustomer;

trait ModelOrderWaitCheckDo
{
    public static function ModelOrderWaitCheckDetailModifyAddressDo($tokenKey, $memberId, $orderId, $addressId)
    {
        if (!empty($addressId) && intval($addressId) > 0) {
            $address = UserCls::getAddressInfoById($tokenKey, $addressId);
        }
        else if($addressId == '0')
        {
            $address = myResponse::ResponseDataTrueDataObj(UserCls::$PickDefaultAddress);
        }
            if(count($address->data)>0)
            {
                $data["member_address_id"] = $addressId;
                $data["updateDate"] = FunctionCode::GetNowTimeDate();
                $re = M("model_main_order_current")->where(array("member_id" => $memberId, "id" => $orderId))->save($data);
                if ($re) {
                    return myResponse::ResponseDataTrueDataObj(array("address"=>$address->data));
                }
            }

        return myResponse::ResponseDataFalseObj("没有找到地址");
    }

    public static function ModelOrderWaitCheckDetailModifyInfoDo($memberId, $orderId,$OrderErpId, $purityId, $qualityId, $word, $customerId,$invoiceTitle,$invoiceType,$orderNote)
    {
        $data = null;
        if (!empty($purityId) && intval($purityId) > 0) {
            $data["model_purity_id"] = $purityId;
        }
        if (!empty($qualityId) && intval($qualityId) > 0) {
            $data["model_quality_id"] = $qualityId;
        }
        if (!empty($word)) {
            $data["word"] = $word;
        }
        if(!empty($invoiceTitle))
        {
            $data["invoiceTitle"] = $invoiceTitle;
        }
        if(!empty($invoiceType))
        {
            $data["invoiceType"] = $invoiceType;
        }
        if($invoiceType == '-1')
        {
            $data["invoiceTitle"] = '';
            $data["invoiceType"] = '';
        }
        if(!empty($orderNote))
        {
            $data["orderNote"] = $orderNote;
        }
        if (!empty($customerId) && intval($customerId) > 0) {
            $isCustomerData = ErpPublicCode::GetUrlObjData("IsHaveGroupCustomer","customer",array("erpid"=>$OrderErpId,"gid"=>$customerId));
            if($isCustomerData <= 0) {
                return myResponse::ResponseDataFalseObj("客户信息不正确");
            }
            $data["customerId"] = $customerId;
        }
        if($data != null)
        {
            $data["updateDate"] = FunctionCode::GetNowTimeDate();
            $re = M("model_main_order_current")->where(array("member_id" => $memberId, "id" => $orderId))->save($data);
            if ($re) {
                return myResponse::ResponseDataTrueObj("更新信息成功");
            }
        }
        return myResponse::ResponseDataFalseObj("更新信息失败");
    }
    public static function ModelOrderWaitCheckCancelDo($memberId,$orderId)
    {
        $MmocData = M("model_main_order_current")->where(array("member_id" => $memberId, "id" => $orderId))->select();
        if(!empty($MmocData) && count($MmocData)>0)
        {
            $orderStatus = $MmocData[0]["orderStatus"];
            $data["orderStatus"] = 0 - intval($orderStatus);
            $data["updateDate"] = FunctionCode::GetNowTimeDate();
            $re = M("model_main_order_current")->where(array("member_id" => $memberId, "id" => $orderId))->save($data);
            $re1 = M("model_main_order_current_detail")->where(array("member_id" => $memberId, "model_main_order_current_id" => $orderId))->save($data);
            if ($re) {
                return myResponse::ResponseDataTrueObj("取消订单成功");
            }
        }
        return myResponse::ResponseDataFalseObj("取消订单失败");
    }
    public static function ModelOrderWaitCheckGetOrderPricePageListDo($memberId,$qualityId,$purityId,$orderId)
    {
        $Model = new \Think\Model();
        $baseData = $Model->query(ModelOrderCls::Current_Order_List_For_Wait_Check_Price_Sql($memberId,$orderId));
        $currentOrderlList = array();
        $n = 0;
        $erpTypeIds = "";
        foreach($baseData as $value)
        {
            $id = $value["id"];
            $stonePrice = $value["stonePrice"];
            $number = $value['number'];
            $weight = $value['weight'];
            $dataItem = array("id"=>$id,"typeId"=>$value["erpTypeId"],"weight"=>$weight,"stonePrice"=>$stonePrice,"price"=>$value['price'],"number"=>$number);
            $currentOrderlList[$n++] = $dataItem;
            if(!empty($value["erpTypeId"]) && !strpos(",".$erpTypeIds.",",",".$value["erpTypeId"].","))
            {
                $erpTypeIds = empty($erpTypeIds)?$value["erpTypeId"]:$erpTypeIds.",".$value["erpTypeId"];
            }
        }
        //------------------------ErpData
        $data = array();
        /*$ErpValuePriceData = array();
        if(!empty($erpTypeIds) && !empty($purityId) && !empty($qualityId) && !empty($erpTypeIds)) {
            $ErpValuePriceData = ErpPublicCode::GetUrlObjData("GetModelValuePrice", "model", array("typeIds" => $erpTypeIds, "purityId" => $purityId, "qualityId" => $qualityId));
        }*/
        if(!empty($currentOrderlList) && count($currentOrderlList)>0) {
            $nn = 0;
            foreach ($currentOrderlList as $value) {
                /*$n = FunctionCode::FindEqObjReN($ErpValuePriceData, "typeId", $value["typeId"]);
                if ($n >= 0) {
                    $lossCostPer = (double)($ErpValuePriceData[$n]->lossCostPer) > 0 ? (double)($ErpValuePriceData[$n]->lossCostPer) : 0.15;//损耗
                    $goldPrice = (double)($ErpValuePriceData[$n]->goldPrice) > 0 ? (double)($ErpValuePriceData[$n]->goldPrice) : -1;//金重
                    $processCost = (double)($ErpValuePriceData[$n]->processCost) > 0 ? (double)($ErpValuePriceData[$n]->processCost) : 0;
                    $ProportionToWax =  (double)($ErpValuePriceData[$n]->ProportionToWax)>0?(double)($ErpValuePriceData[$n]->ProportionToWax):18;
                    if ($goldPrice != -1) {
                        $currentOrderlList[$nn]["price"] = ((double)$value["stonePrice"]
                                + (double)$value["weight"] * $ProportionToWax * $goldPrice * (1+$lossCostPer) + $processCost) * intval($currentOrderlList[$nn]["number"]);
                    } else {
                        $currentOrderlList[$nn]["price"] = "0.00";
                    }
                } else {
                    $currentOrderlList[$nn]["price"] = "0.00";
                }*/
                //$currentOrderlList[$nn]["price"] = BllPublic::GetModelPriceHaveStone($ErpValuePriceData,$value["typeId"]
                    //,$value["weight"],$qualityId,$purityId,floatval($value["stonePrice"]));
                $price = (floatval($value["stonePrice"])+$currentOrderlList[$nn]["price"])* intval($currentOrderlList[$nn]["number"]);
                $userPercent = UserCls::GetUserPayPercentByMemberId($memberId);
                $needPayPrice = floatval($price)*floatval($userPercent);
                $item = array("id" => $value["id"], "price" => $price,"needPayPrice"=>$needPayPrice);
                $data[$nn] = $item;
                $nn++;
            }
        }

        //-------------------------ErpData
        return myResponse::ResponseDataTrueDataObj(array("priceList"=>$data));
    }

    public static function ModelOrderWaitCheckVerifyToProduceDo($memberId,$orderId)
    {
        $Model = new \Think\Model();
        $mainOrder = $Model->query(ModelOrderCls::Current_Main_Order_Submit_To_Erp_Sql($orderId,$memberId));
        $orderItems =  $Model->query(ModelOrderCls::Current_Main_Order_Items_Submit_To_Erp_Sql($orderId,$memberId));
        if(count($mainOrder)>0 && count($orderItems)>0)
        {
            $stoneColorArr = BaseCls::CacheStoneColor();
            $stonePurityArr = BaseCls::CacheStonePurity();

            $purityId = $mainOrder[0]["PurityID"];
            $qualityId = $mainOrder[0]["QualityID"];
            $customerId = $mainOrder[0]["CustomerID"];
            $erpTypeIds = "";
            $stoneCategorys = "";
            //$stoneSpecses = "";
            //$stoneQuantitys = "";
            $stoneFigures = "";
            foreach($orderItems as $value)
            {
                $erpTypeIds = FunctionCode::ConnectStrForComm($erpTypeIds,$value["TypeID"],",");

                $stoneCategorys = FunctionCode::ConnectStrArrForComm($stoneCategorys,array($value["StoneP"],$value["StoneSA"],$value["StoneSB"],$value["StoneSC"]),",");
                //$stoneSpecses = FunctionCode::ConnectStrArrForComm($stoneSpecses,array($value["StonePSpecs"],$value["StoneSASpecs"],$value["StoneSBSpecs"],$value["StoneSCSpecs"]),",");
                //$stoneQuantitys = FunctionCode::ConnectStrArrForComm($stoneQuantitys,array($value["StonePQuantity"],$value["StoneSAQuantity"],$value["StoneSBQuantity"],$value["StoneSCQuantity"]),",");
                $stoneFigures = FunctionCode::ConnectStrArrForComm($stoneFigures,array($value["StonePFigure"],$value["StoneSAFigure"],$value["StoneSBFigure"],$value["StoneSCFigure"]),",");
            }
            //$ErpValuePriceData = ErpPublicCode::GetUrlObjData("GetModelValuePrice", "model", array("typeIds" => $erpTypeIds, "purityId" => $purityId, "qualityId" => $qualityId));
            $SubmitData = ErpPublicCode::GetUrlObjData("GetModeGetCheckToProduceDo","model",array("typeIds"=>$erpTypeIds,"purityId"=>$purityId,"qualityId"=>$qualityId,"customerId"=>$customerId
                                                        ,"stoneCategorys"=>$stoneCategorys,"stoneFigures"=>$stoneFigures));
            $ErpValuePriceData = $SubmitData->modelValuePrice;
            $modelPurity = $SubmitData->modelPurity->title;
            $modelQuality = $SubmitData->modelQuality->title;
            $modelStoneCategoryArr = $SubmitData->stoneCategorys;
            $modelStoneShapeArr = $SubmitData->stoneShapes;
            $stoneTotalPriceArr = array();//石头总价
            $lossCostPerArr = array();//损耗
            $processCostArr = array();//加工费
            $ProportionToWaxArr = array();
            $goldPrice = 0.00;
            $totalPrice = 0.00;
            $unitPriceArr = array();
            $currentDate = FunctionCode::GetNowTimeDate();
            $nn = 0;
            $UserModelAddtion = UserCls::getUserModelAddtion($memberId);
            $UserStoneAddtion = UserCls::getUserStoneAddtion($memberId);
            foreach($orderItems as $value)
            {
                $n = FunctionCode::FindEqObjReN($ErpValuePriceData, "typeId", $value["TypeID"]);
                $orderItems[$nn]["remarks"] = $orderItems[$nn]["Memo"];
                $orderItems[$nn]["SameModuleIndex"] = $orderItems[$nn]["SameModuleIndex"] == 0?NULL:$orderItems[$nn]["SameModuleIndex"];

                $color = FunctionCode::FindEqArrReField($stoneColorArr,"id","title",$value["stone_color_id"]);
                $purity = FunctionCode::FindEqArrReField($stonePurityArr,"id","title",$value["stone_purity_id"]);
                $color_A = FunctionCode::FindEqArrReField($stoneColorArr,"id","title",$value["stone_color_id_A"]);
                $purity_A = FunctionCode::FindEqArrReField($stonePurityArr,"id","title",$value["stone_purity_id_A"]);
                $color_B = FunctionCode::FindEqArrReField($stoneColorArr,"id","title",$value["stone_color_id_B"]);
                $purity_B = FunctionCode::FindEqArrReField($stonePurityArr,"id","title",$value["stone_purity_id_B"]);
                $color_C = FunctionCode::FindEqArrReField($stoneColorArr,"id","title",$value["stone_color_id_C"]);
                $purity_C = FunctionCode::FindEqArrReField($stonePurityArr,"id","title",$value["stone_purity_id_C"]);

                $stone_out_A = ConvertType::ConvertInt($value["stone_out_A"],0);
                $stone_out_B = ConvertType::ConvertInt($value["stone_out_B"],0);
                $stone_out_C = ConvertType::ConvertInt($value["stone_out_C"],0);
                $colorPurity =
                    (empty($color)||empty($purity)?"":(($value["IsCSP"] == 1?"主石自带:":"主石厂配:").(empty($color)?"":$color." ").(empty($purity)?"":" ".$purity).";"))
                    .(empty($color_A)||empty($purity_A)?"":("副A:".(empty($color_A)?"":"".$color_A." ").(empty($purity_A)?"":" ".$purity_A).";"))
                    .(empty($color_B)||empty($purity_B)?"":("副B:".(empty($color_B)?"":"".$color_B." ").(empty($purity_B)?"":" ".$purity_B).";"))
                    .(empty($color_C)||empty($purity_C)?"":("副C:".(empty($color_C)?"":"".$color_C." ").(empty($purity_C)?"":" ".$purity_C).";"));

                $outStone = ($stone_out_A == 1?"封副石A;":"").($stone_out_B == 1?"封副石B;":"").($stone_out_C == 1?"封副石C;":"");
                $orderItems[$nn]["Memo"] = FunctionCode::curStr($colorPurity." ".$outStone." ".$orderItems[$nn]["Memo"],110,"...");
                $orderItems[$nn]["remarks"] = $colorPurity." ".$outStone." ".$colorPurity;
                if ($n >= 0) {
                    $detailId = $value["appDetailID"];
                    $weight = $value["weight"];
                    $itemNumber = $value["QuantityDetail"];
                    $goldPriceItem = (double)($ErpValuePriceData[$n]->goldPrice);
                    $lossCostPer = (double)($ErpValuePriceData[$n]->lossCostPer) > 0 ? (double)($ErpValuePriceData[$n]->lossCostPer) : 0.15;//损耗
                    $goldPrice = $goldPriceItem > 0 ? (double)($ErpValuePriceData[$n]->goldPrice) : 0;//金重
                    $processCost = (double)($ErpValuePriceData[$n]->processCost) > 0 ? (double)($ErpValuePriceData[$n]->processCost) : 0;
                    $ProportionToWax = (double)($ErpValuePriceData[$n]->ProportionToWax)>0?(double)($ErpValuePriceData[$n]->ProportionToWax):18;
                    $orderItems[$nn]["Fee"] = $processCost;
                    $StonePrice = $value["StonePrice"];
                    $stonePriceA = $value["stonePriceA"];
                    $stonePriceB = $value["stonePriceB"];
                    $stonePriceC = $value["stonePriceC"];
                    $stoneTotalPrice = $StonePrice+$stonePriceA+$stonePriceB+$stonePriceC;
                    $stoneTotalPriceArr[] = array("id"=>$detailId,"stoneTotalPrice"=>$stoneTotalPrice);
                    $lossCostPerArr[] = array("id"=>$detailId,"lossCostPer"=>$lossCostPer);
                    $processCostArr[] = array("id"=>$detailId,"processCost"=>$processCost);
                    $ProportionToWaxArr[] = array("id"=>$detailId,"ProportionToWaxArr"=>$ProportionToWax);
                    $unitPrice = $weight*$goldPrice*(1+$lossCostPer)+$processCost+$stoneTotalPrice;

                    $unitPriceArr[] = array("id"=>$detailId,"unitPrice"=>$unitPrice);
                    $totalPrice = $totalPrice + $unitPrice*$itemNumber;
                }
                $nn++;
            }
            $userPercent = UserCls::GetUserPayPercentByMemberId($memberId);
            $needPayPrice = $totalPrice*$userPercent;
            $mainOrder[0]["GoldPrice"] = $goldPrice;
            $DutyMan = M("member")->where(array("id"=>$memberId))->getField("trueName");
            $mainOrder[0]["DutyMan"] = FunctionCode::curStr($DutyMan,10);
            $OrderMemo = $mainOrder[0]["OrderMemo"];
            $mainOrder[0]["OrderMemo"] = FunctionCode::curStr($OrderMemo,200,"...");//Fun (, 0, 200, 'utf-8');
            $data = array("mainOrder"=>$mainOrder[0],"orderItems"=>$orderItems);

            //$da = myResponse::ResponseDataTrueDataObj($data);
            //return $da;
            $jsonObj = ErpPublicCode::PostUrlForObj($data, "AddModelToErpOrder", "model", "GetAddModelToErpOrderEntity", "entity");
            if(empty($jsonObj) || intval($jsonObj->error) >0)
            {
                return $jsonObj;
            }
            M("model_main_order_current")->where(array("id"=>$orderId))->data(array("orderStatus"=>ModelOrderCls::$ORDER_STATUS_PRODUCE_ORDER_START_TO_FLOW_1001))->save();
            M("model_main_order_current_detail")->where(array("model_main_order_current_id"=>$orderId))->data(array("orderStatus"=>ModelOrderCls::$ORDER_STATUS_PRODUCE_ORDER_START_TO_FLOW_1001))->save();

            $orderNum = $mainOrder[0]["OrderID"];
            //$memberId = $mainOrder[0]["member_id"];

            $addressId = $mainOrder[0]["member_address_id"];
            if (FunctionCode::isInteger($addressId) && intval($addressId) > 0) {
                $addressObj = UserCls::getAddressInfoByMemberId($memberId, $mainOrder[0]["member_address_id"]);
            }
            else if($addressId == '0')
            {
                $addressObj = myResponse::ResponseDataTrueDataObj(UserCls::$PickDefaultAddress);
            }
            $memberAddress = "";
            $memberAddressName = "";
            $memberAddressPhone = "";
            if(!empty($addressObj) && $addressObj->error == 0)
            {
                $memberAddress = $addressObj->data["addr"];
                $memberAddressName = $addressObj->data["name"];
                $memberAddressPhone = $addressObj->data["phone"];
            }


            $orderData["model_main_order_current_id"] = $orderId;
            $orderData["orderNum"] = $orderNum;
            $orderData["member_id"] = $memberId;
            $orderData["member_address"] = $memberAddress;
            $orderData["member_address_name"] = $memberAddressName;
            $orderData["member_address_phone"] = $memberAddressPhone;
            $orderData["model_purity"] = $modelPurity;
            $orderData["model_quality"] = $modelQuality;
            $orderData["word"] = $mainOrder[0]["Sigil"];
            $orderData["erp_client_id"] = $mainOrder[0]["erp_client_id"];
            $orderData["customerId"] = $customerId;
            $orderData["customerName"] = $SubmitData->customer->customerName;
            $orderData["totalPrice"] = $totalPrice;
            $orderData["needPayPrice"] = $needPayPrice;
            $orderData["orderDate"] = $mainOrder[0]["OrderDate"];
            $orderData["goldPrice"] = $goldPrice;
            $orderData["number"] = count($orderItems);
            $orderData["orderStatus"] = ModelOrderCls::$ORDER_STATUS_PRODUCE_ORDER_START_TO_FLOW_1001;
            $orderData["invoiceTitle"] = $mainOrder[0]["invoiceTitle"];
            $orderData["invoiceType"] = FunctionCode::FindEqArrReField(ModelOrderCls::$INVOICE_TYPE,"id","title",$mainOrder[0]["invoiceType"]);
            $orderData["createDate"] = $currentDate;
            $orderData["orderNote"] = $OrderMemo;//$mainOrder[0]["OrderMemo"];
            $re = M("model_main_order")->data($orderData)->add();
            if(!$re)
            {
                return myResponse::ResponseDataFalseObj("添加到当前订单失败");
            }
            $orderDetailData = array();

            foreach($orderItems as $value) {
                $orderDetailDataItem["model_main_order_id"] = $re;
                $orderDetailDataItem["model_main_order_current_detail_id"] = $value["appDetailID"];
                $orderDetailDataItem["orderNum"] = $orderNum;
                $orderDetailDataItem["model_product_id"] = $value["model_product_id"];
                $orderDetailDataItem["model_product"] = $value["modelTitle"];
                $orderDetailDataItem["modelWeight"] = $value["weight"];
                $orderDetailDataItem["modelNum"] = $value["modelNum"];
                $orderDetailDataItem["number"] = $value["QuantityDetail"];
                $orderDetailDataItem["model_category_id"] = $value["model_category_id"];
                $orderDetailDataItem["model_category"] = $value["categoryName"];
                $unitprice_1 = FunctionCode::FindEqArrReField($unitPriceArr,"id","unitPrice",$value["appDetailID"]);
                $orderDetailDataItem["unitprice"] = $unitprice_1;
                $orderDetailDataItem["unitNeedPrice"] = $unitprice_1*$userPercent;
                $orderDetailDataItem["userPercent"] = $userPercent;
                $orderDetailDataItem["handSize"] = $value["Perimeter"];
                $orderDetailDataItem["isSelfStone"] = $value["IsCSP"];
                $orderDetailDataItem["goldPrice"] = $goldPrice;
                $orderDetailDataItem["lossCostPer"] = FunctionCode::FindEqArrReField($lossCostPerArr,"id","lossCostPer",$value["appDetailID"]);
                $orderDetailDataItem["stoneTotalPrice"] = FunctionCode::FindEqArrReField($stoneTotalPriceArr,"id","stoneTotalPrice",$value["appDetailID"]);
                $orderDetailDataItem["processCost"] = FunctionCode::FindEqArrReField($processCostArr,"id","processCost",$value["appDetailID"]);
                $orderDetailDataItem["ProportionToWax"] = FunctionCode::FindEqArrReField($ProportionToWaxArr,"id","ProportionToWaxArr",$value["appDetailID"]);

                $orderDetailDataItem["unitStonePrice"] = $value["unitStonePrice"];
                $orderDetailDataItem["unitStonePriceA"] = $value["unitStonePriceA"];
                $orderDetailDataItem["unitStonePriceB"] = $value["unitStonePriceB"];
                $orderDetailDataItem["unitStonePriceC"] = $value["unitStonePriceC"];

                $orderDetailDataItem["stone_category"] = FunctionCode::FindEqObjReItem($modelStoneCategoryArr,"id",$value["StoneP"])->title;
                $orderDetailDataItem["stone_category_A"] = FunctionCode::FindEqObjReItem($modelStoneCategoryArr,"id",$value["StoneSA"])->title;
                $orderDetailDataItem["stone_category_B"] = FunctionCode::FindEqObjReItem($modelStoneCategoryArr,"id",$value["StoneSB"])->title;
                $orderDetailDataItem["stone_category_C"] = FunctionCode::FindEqObjReItem($modelStoneCategoryArr,"id",$value["StoneSC"])->title;

                $orderDetailDataItem["stone_spec"] = $value["spec"];
                $orderDetailDataItem["stone_spec_A"] = $value["speca"];
                $orderDetailDataItem["stone_spec_B"] = $value["specb"];
                $orderDetailDataItem["stone_spec_C"] = $value["specc"];

                $orderDetailDataItem["stone_spec_value"] = $value["StonePSpecs"];
                $orderDetailDataItem["stone_spec_value_A"] = $value["StoneSASpecs"];
                $orderDetailDataItem["stone_spec_value_B"] = $value["StoneSBSpecs"];
                $orderDetailDataItem["stone_spec_value_C"] = $value["StoneSCSpecs"];

                $orderDetailDataItem["stone_shape"] = FunctionCode::FindEqObjReItem($modelStoneShapeArr,"id",$value["StonePFigure"])->title;
                $orderDetailDataItem["stone_shape_A"] = FunctionCode::FindEqObjReItem($modelStoneShapeArr,"id",$value["StoneSAFigure"])->title;
                $orderDetailDataItem["stone_shape_B"] = FunctionCode::FindEqObjReItem($modelStoneShapeArr,"id",$value["StoneSBFigure"])->title;
                $orderDetailDataItem["stone_shape_C"] = FunctionCode::FindEqObjReItem($modelStoneShapeArr,"id",$value["StoneSCFigure"])->title;

                $orderDetailDataItem["stone_color"] = FunctionCode::FindEqArrReField($stoneColorArr,"id","title",$value["stone_color_id"]);
                $orderDetailDataItem["stone_color_A"] = FunctionCode::FindEqArrReField($stoneColorArr,"id","title",$value["stone_color_id_A"]);
                $orderDetailDataItem["stone_color_B"] = FunctionCode::FindEqArrReField($stoneColorArr,"id","title",$value["stone_color_id_B"]);
                $orderDetailDataItem["stone_color_C"] = FunctionCode::FindEqArrReField($stoneColorArr,"id","title",$value["stone_color_id_C"]);

                $orderDetailDataItem["stone_purity"] = FunctionCode::FindEqArrReField($stonePurityArr,"id","title",$value["stone_purity_id"]);
                $orderDetailDataItem["stone_purity_A"] = FunctionCode::FindEqArrReField($stonePurityArr,"id","title",$value["stone_purity_id_A"]);
                $orderDetailDataItem["stone_purity_B"] = FunctionCode::FindEqArrReField($stonePurityArr,"id","title",$value["stone_purity_id_B"]);
                $orderDetailDataItem["stone_purity_C"] = FunctionCode::FindEqArrReField($stonePurityArr,"id","title",$value["stone_purity_id_C"]);

                $orderDetailDataItem["remarks"] = $value["remarks"];
                $orderDetailDataItem["stone_number"] = $value["StonePQuantity"];
                $orderDetailDataItem["stone_number_A"] = $value["StoneSAQuantity"];
                $orderDetailDataItem["stone_number_B"] = $value["StoneSBQuantity"];
                $orderDetailDataItem["stone_number_C"] = $value["StoneSCQuantity"];
                $orderDetailDataItem["model_product_pic"] = $value["productPic"];
                $orderDetailDataItem["member_id"] = $memberId;
                $orderDetailDataItem["orderStatus"] = ModelOrderCls::$ORDER_STATUS_PRODUCE_ORDER_START_TO_FLOW_1001;
                $orderDetailDataItem["createDate"] = $currentDate;
                    /*unset($orderItems[$nn]["model_product_id"]);
                    unset($orderItems[$nn]["weight"]);
                    unset($orderItems[$nn]["model_product_id"]);*/
                $orderDetailData[] = $orderDetailDataItem;
            }
            if(count($orderDetailData)>0)
            {
                M("model_main_order_detail")->addAll($orderDetailData);
            }
            return myResponse::ResponseDataTrueObj("审核订单成功");
        }
        return myResponse::ResponseDataFalseObj("审核订单失败");
    }
}