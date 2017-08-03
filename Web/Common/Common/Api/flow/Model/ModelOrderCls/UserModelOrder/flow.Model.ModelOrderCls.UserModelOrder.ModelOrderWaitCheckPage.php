<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/11/4
 * Time: 15:20
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

trait ModelOrderWaitCheckPage
{
    public static function ModelOrderWaitCheckDetail($tokenKey,$memberId,$orderId,$cpage,$pageCount = '')
    {
        $orderData = M("model_main_order_current a")
            ->field("a.*,(select sum(amount) as finishPrice from app_payment_to_log where objid=a.id and isPaySuccess=1) as finishPrice")
            ->where(array("member_id"=>$memberId,"id"=>$orderId))
            ->select();
        if(count($orderData)<=0)
        {
            return myResponse::ResponseDataFalseObj("获取数据出错");
        }
        $customerId = $orderData[0]["customerId"];
        $qualityId = $orderData[0]["model_quality_id"];
        $purityId = $orderData[0]["model_purity_id"];
        $word = $orderData[0]["word"];
        $addressId = $orderData[0]["member_address_id"];
        $orderNum = $orderData[0]["orderNum"];
        $orderDate = $orderData[0]["createDate"];
        $invoiceTitle = $orderData[0]["invoiceTitle"];
        $invoiceType = FunctionCode::FindEqArrReField(ModelOrderCls::$INVOICE_TYPE,"id","title",$orderData[0]["invoiceType"]);
        $orderStatus = ModelOrderCls::GetOrderStatusName($orderData[0]["orderStatus"]);
        $orderNote = $orderData[0]["orderNote"];
        $finishPrice = $orderData[0]["finishPrice"];
        $isNeetPay = $orderData[0]["orderStatus"] == ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_WAIT_PAY_100?1:0;




        $modelType = BaseCls::CacheModelCategory();
        $stoneType = BaseCls::CacheStoneCategory();
        $stoneSpec = BaseCls::CacheStoneSpec();
        $stoneShape = BaseCls::CacheStoneShape();
        $stonePurity = BaseCls::CacheStonePurity();
        $stoneColor = BaseCls::CacheStoneColor();

        $Model = new \Think\Model();
        $baseData = $Model->query(ModelOrderCls::Stone_Price_For_WaitCheck_OrderItem_Sql($memberId,$orderId,$cpage,$pageCount));


        $currentOrderlList = array();
        $n = 0;
        $erpTypeIds = "";
        $unitTotelPrice = 0;
        $totelPrice = 0;
        $userPercent = UserCls::GetUserPayPercentByMemberId($memberId);
        $jewelStoneLog = M("model_order_jewel_stone_log")->where("model_main_order_current_id=".$orderId)->select();
        $UserModelAddtion = UserCls::getUserModelAddtion($memberId);
        $UserStoneAddtion = UserCls::getUserStoneAddtion($memberId);
        foreach($baseData as $value)
        {
            $number = $value['number'];
            $weight = $value['weight'];

            $unitTotelPrice = floatval($value["modelPrice"])*$UserModelAddtion + floatval($value["stonePrice"]);
            $sn = FunctionCode::FindEqArrReN($jewelStoneLog,"model_main_order_current_detail_id",$value["id"]);
            if($sn<0) {
                $stoneInfo = (ConvertType::ConvertInt($value['stone_category_id'], -1) > 0 ? "类型:" . FunctionCode::FindEqObjReField($stoneType, "id", "title", $value["stone_category_id"]) : "")
                    . (ConvertType::ConvertInt($value['stone_number'], -1) > 0 ? ",数量:" . $value['stone_number'] . "粒" : "")
                    . (ConvertType::ConvertInt($value['stone_spec_id'], -1) > 0 ? ",规格:" . FunctionCode::FindEqArrReField($stoneSpec, "id", "title", $value["stone_spec_id"]) : "")
                    . (ConvertType::ConvertInt($value['stone_shape_id'], -1) > 0 ? ",形状:" . FunctionCode::FindEqObjReField($stoneShape, "id", "title", $value["stone_shape_id"]) : "")
                    . (ConvertType::ConvertInt($value['stone_color_id'], -1) > 0 ? ",颜色:" . FunctionCode::FindEqArrReField($stoneColor, "id", "title", $value["stone_color_id"]) : "")
                    . (ConvertType::ConvertInt($value['stone_purity_id'], -1) > 0 ? ",纯度:" . FunctionCode::FindEqArrReField($stonePurity, "id", "title", $value["stone_purity_id"]) : "")
                    . (ConvertType::ConvertInt($value[0]['isSelfStone'], 0) == 1 ? ",自带石头" : "");
                $stoneInfo = empty($stoneInfo) ? "" : "主石(" . $stoneInfo . ")";
            }
            else
            {
                $stoneInfo = "选择主石头编号:".$jewelStoneLog[$sn]["CertCode"];
                $unitTotelPrice = $unitTotelPrice + floatval($jewelStoneLog[$sn]["Price"])*$UserStoneAddtion;
            }

            $stoneInfoA =
                ConvertType::ConvertInt($value['stone_out_A'],0) == 1?"封石":
                (ConvertType::ConvertInt($value['stone_category_id_A'],-1)>0?"类型:".FunctionCode::FindEqObjReField($stoneType,"id","title",$value["stone_category_id_A"]):"")
                .(ConvertType::ConvertInt($value['stone_number_A'],-1)>0?",数量:".$value['stone_number_A']."粒":"")
                .(ConvertType::ConvertInt($value['stone_spec_id_A'],-1)>0?",规格:".FunctionCode::FindEqArrReField($stoneSpec,"id","title",$value["stone_spec_id_A"]):"")
                .(ConvertType::ConvertInt($value['stone_shape_id_A'],-1)>0?",形状:".FunctionCode::FindEqObjReField($stoneShape,"id","title",$value["stone_shape_id_A"]):"")
                .(ConvertType::ConvertInt($value['stone_color_id_A'],-1)>0?",颜色:".FunctionCode::FindEqArrReField($stoneColor,"id","title",$value["stone_color_id_A"]):"")
                .(ConvertType::ConvertInt($value['stone_purity_id_A'],-1)>0?",纯度:".FunctionCode::FindEqArrReField($stonePurity,"id","title",$value["stone_purity_id_A"]):"");
            $stoneInfoA = empty($stoneInfoA)?"":";副石A(".$stoneInfoA.")";

            $stoneInfoB =
                ConvertType::ConvertInt($value['stone_out_B'],0) == 1?"封石":
                (ConvertType::ConvertInt($value['stone_category_id_B'],-1)>0?"类型:".FunctionCode::FindEqObjReField($stoneType,"id","title",$value["stone_category_id_B"]):"")
                .(ConvertType::ConvertInt($value['stone_number_B'],-1)>0?",数量:".$value['stone_number_B']."粒":"")
                .(ConvertType::ConvertInt($value['stone_spec_id_B'],-1)>0?",规格:".FunctionCode::FindEqArrReField($stoneSpec,"id","title",$value["stone_spec_id_B"]):"")
                .(ConvertType::ConvertInt($value['stone_shape_id_B'],-1)>0?",形状:".FunctionCode::FindEqObjReField($stoneShape,"id","title",$value["stone_shape_id_B"]):"")
                .(ConvertType::ConvertInt($value['stone_color_id_B'],-1)>0?",颜色:".FunctionCode::FindEqArrReField($stoneColor,"id","title",$value["stone_color_id_B"]):"")
                .(ConvertType::ConvertInt($value['stone_purity_id_B'],-1)>0?",纯度:".FunctionCode::FindEqArrReField($stonePurity,"id","title",$value["stone_purity_id_B"]):"");
            $stoneInfoB = empty($stoneInfoB)?"":";副石B(".$stoneInfoB.")";

            $stoneInfoC =
                ConvertType::ConvertInt($value['stone_out_C'],0) == 1?"封石":
                (ConvertType::ConvertInt($value['stone_category_id_C'],-1)>0?"类型:".FunctionCode::FindEqObjReField($stoneType,"id","title",$value["stone_category_id_C"]):"")
                .(ConvertType::ConvertInt($value['stone_number_C'],-1)>0?",数量:".$value['stone_number_C']."粒":"")
                .(ConvertType::ConvertInt($value['stone_spec_id_C'],-1)>0?",规格:".FunctionCode::FindEqArrReField($stoneSpec,"id","title",$value["stone_spec_id_C"]):"")
                .(ConvertType::ConvertInt($value['stone_shape_id_C'],-1)>0?",形状:".FunctionCode::FindEqObjReField($stoneShape,"id","title",$value["stone_shape_id_C"]):"")
                .(ConvertType::ConvertInt($value['stone_color_id_C'],-1)>0?",颜色:".FunctionCode::FindEqArrReField($stoneColor,"id","title",$value["stone_color_id_C"]):"")
                .(ConvertType::ConvertInt($value['stone_purity_id_C'],-1)>0?",纯度:".FunctionCode::FindEqArrReField($stonePurity,"id","title",$value["stone_purity_id_C"]):"");
            $stoneInfoC = empty($stoneInfoC)?"":";副石C(".$stoneInfoC.")";

            $remarks = $value["remarks"];
            $remarks = empty($remarks)?"":";备注(".$remarks.")";

            $info = $stoneInfo.$stoneInfoA.$stoneInfoB.$stoneInfoC.$remarks;

            $id = $value["id"];
            $modelId = $value["model_product_id"];
            $title = $value["name"]."(".$value["modelNum"].")";
            $pic = $value["fpic"];
            $baseInfo = "类型:".FunctionCode::FindEqArrReField($modelType,"id","title",$value["model_category_id"])
                .(empty($value['handSize']) || $value['handSize']==0?"":";手寸:".floatval($value['handSize']));

            $totelPrice = $totelPrice*$number;
            $dataItem = array("id"=>$id,"modelId"=>$modelId,"title"=>$title,"typeId"=>$value["erpTypeId"],"weight"=>$weight,"pic"=>$pic,"baseInfo"=>$baseInfo,"stonePrice"=>$stonePrice
            ,"price"=>$unitTotelPrice
            ,"needPayPrice"=>$unitTotelPrice*$userPercent
            ,"number"=>floatval($number),"info"=>$info);
            $currentOrderlList[$n++] = $dataItem;
            if(!empty($value["erpTypeId"]) && !strpos(",".$erpTypeIds.",",",".$value["erpTypeId"].","))
            {
                $erpTypeIds = empty($erpTypeIds)?$value["erpTypeId"]:$erpTypeIds.",".$value["erpTypeId"];
            }
            $totelPrice = $totelPrice + $unitTotelPrice;
        }
        //------------------------ErpData
        $goldPriceItem = "";
        //-------------------------ErpData
        if(FunctionCode::isInteger($cpage) && intval($cpage)>1) {
            $data = array("currentOrderlList"=>array("list" => $currentOrderlList,"list_count"=>ModelOrderCls::GetWaitCheckOrderItemCount($memberId,$orderId)));
        }
        else {
            $ErpData = ErpPublicCode::GetUrlObjData("GetModelCurrentOrderPage","model",array("customerId" => $customerId,"typeIds"=>$erpTypeIds,"purityId"=>$purityId,"qualityId"=>$qualityId));
            $customer = $ErpData->customer;
            $modelPurity = $ErpData->modelPurity;
            $modelQuality = $ErpData->modelQuality;
            $thisModelQualityTitle = FunctionCode::FindEqObjReItem($modelQuality,"id",$qualityId)->title;
            $thisModelPurityTitle = FunctionCode::FindEqObjReItem($modelPurity,"id",$purityId)->title;
            if(FunctionCode::isInteger($addressId) && $addressId!=0)
            {
                $reObj = UserCls::getAddressInfoById($tokenKey,$addressId);
                $address = $reObj->error!=ValidateCode::$noError?null:$reObj;
            }
            else if($addressId!='0')
            {
                $reObj = UserCls::getDefultAddress($tokenKey);
                $address = $reObj->error!=ValidateCode::$noError?null:$reObj;
            }
            if(empty($address))
            {
                $address = myResponse::ResponseDataTrueDataObj(UserCls::$PickDefaultAddress);
            }
            $data = array("currentOrderlList"=>array("list" => $currentOrderlList,"list_count"=>ModelOrderCls::GetWaitCheckOrderItemCount($memberId,$orderId))
            , "address" => $address->data
            ,"orderInfo"=>array("orderNum"=>$orderNum,"goldPrice"=>$goldPriceItem,"orderDate"=>$orderDate,"orderStatus"=>$orderStatus,"customerName"=>$customer->customerName,"purityName"=>$thisModelPurityTitle
                ,"qualityName"=>$thisModelQualityTitle,"word"=>$word,"invoiceTitle"=>$invoiceTitle,"invoiceType"=>$invoiceType,"orderNote"=>$orderNote)
            , "modelColor" => $modelPurity, "modelQuality" => $modelQuality
            ,"totalPrice"=>$totelPrice,"totalNeedPayPrice"=>$totelPrice*$userPercent,"finishPrice"=>$finishPrice,"isNeetPay"=>$isNeetPay);//$totalNeedPayPrice $totalPrice
        }
        return myResponse::ResponseDataTrueDataObj($data);
    }
    public static function GetWaitCheckOrderItemCount($memberId,$orderId)
    {
        $Model = new \Think\Model();
        $data = $Model->query(ModelOrderCls::Stone_Price_For_WaitCheck_OrderItem_Count_Sql($memberId,$orderId));
        if(count($data)>0)
        {
            return $data[0]["cou"];
        }
        return 0;
    }
    public static function ModelOrderWaitCheckList($memberId,$cpage,$orderId=0)
    {
        $pageCount = 8;//BaseCls::$EACH_PAGE_COUNT;
        if ($cpage <= 1) {
            $upnum = 0;
        } else {
            $upnum = ($cpage - 1) * $pageCount;
        }
        $whereOrderId = "";
        if(intval($orderId)>0)
        {
            $whereOrderId = " and id=".$orderId." ";
        }
        $where = "member_id=".$memberId." and orderStatus>".ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0
            ." and orderStatus<=".ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000.$whereOrderId;
        $orderData = M("model_main_order_current a")
            ->field("a.*,(select sum(amount) as finishPrice from app_payment_to_log where objid=a.id and isPaySuccess=1) as finishPrice")
            ->where($where)->order("id desc")->limit($upnum.','.$pageCount)->select();
        $list_count = M("model_main_order_current")->where($where)->count();
        if(!empty($orderData) && count($orderData)>0)
        {
            $purityIds = "";
            $qualityIds = "";
            $customerIds = "";
            $orderIdList = "";
            $data = array();
            $n = 0;
            foreach($orderData as $line)
            {
                $orderIdList = empty($orderIdList)?$line["id"]:$orderIdList.",".$line["id"];
                $purityIds = empty($purityIds)?$line["model_purity_id"]:$purityIds.",".$line["model_purity_id"];
                $qualityIds = empty($qualityIds)?$line["model_quality_id"]:$qualityIds.",".$line["model_quality_id"];
                $customerIds = empty($customerIds)?$line["customerId"]:$customerIds.",".$line["customerId"];
                $itemData = array("id"=>$line["id"],"orderNum"=>$line["orderNum"],"customerId"=>$line["customerId"]
                ,"orderDate"=>$line["createDate"],"modifyDate"=>$line["updateDate"],"word"=>$line["word"]
                ,"qualityId"=>$line["model_quality_id"],"purityId"=>$line["model_purity_id"]
                ,"finishPrice"=>$line["finishPrice"],"orderStatus"=>$line["orderStatus"]);
                $data[$n++] = $itemData;
            }
            if(!empty($orderIdList)) {
                $Model = new \Think\Model();
                $orderPriceData = $Model->query(ModelOrderCls::Stone_Price_For_WaitCheck_OrderList_Sql($orderIdList,$memberId));
                if(!empty($orderPriceData) && count($orderPriceData)>0) {
                    $orderId = "";
                    $userPercent = UserCls::GetUserPayPercentByMemberId($memberId);

                    $jewelStoneLog = M("model_order_jewel_stone_log")->where("model_main_order_current_id in (".$orderIdList.")")->select();//选择的石头
                    $cn = -1;
                    $UserModelAddtion = UserCls::getUserModelAddtion($memberId);
                    $UserStoneAddtion = UserCls::getUserStoneAddtion($memberId);
                    foreach($orderPriceData as $line)//按组来获取相关数据,获取类型id如： 1,2|3,4，把石头价格加上$data里的订单详情的类型
                    {
                        if($orderId != $line["model_main_order_current_id"]) {
                            $cn = FunctionCode::FindEqArrReN($data, "id", $line["model_main_order_current_id"]);
                            $orderId = $line["model_main_order_current_id"];
                        }
                        if($cn>=0) {
                            $sn = FunctionCode::FindEqArrReN($jewelStoneLog,"model_main_order_current_detail_id",$line["id"]);
                            $jewelStonePrice = 0;
                            if($sn>=0) {
                                $jewelStonePrice = $jewelStoneLog[$sn]["Price"];
                            }
                            $data[$cn]["totalPrice"] = floatval($data[$cn]["totalPrice"])
                                + (floatval($line["stonePrice"])+ floatval($line["modelPrice"])*$UserModelAddtion + floatval($jewelStonePrice)*$UserStoneAddtion)*floatval($line["number"]);
                            $data[$cn]["number"] = intval($data[$cn]["number"])+1;
                            if(!empty($line["pic"]))
                            {
                                $data[$cn]["pics"][count($data[$cn]["pics"])] = $line["pic"];
                            }
                            //$data[$cn]["needPayPrice"] = floatval($data[$cn]["totalPrice"])*$userPercent;
                        }
                    }
                    $SubmitData = ErpPublicCode::GetUrlObjData("GetModeGetWaitCheckOrderList","model",array("purityIds"=>$purityIds,"qualityIds"=>$qualityIds,"customerIds"=>$customerIds
                    ,"cpage"=>$cpage,"appmid"=>$memberId));
                    //计算总价
                    if($SubmitData && count($SubmitData)>0)
                    {
                        for($nn = 0;$nn<count($data);$nn++) {
                            $data[$nn]["customerName"] = FunctionCode::FindEqObjReItem($SubmitData->customer, "customerID", $data[$nn]["customerId"])->customerName;
                            $data[$nn]["otherInfo"] = "成色:".FunctionCode::FindEqObjReItem($SubmitData->modelPurity, "id", $data[$nn]["purityId"])->title
                                ."; 质量等级:".FunctionCode::FindEqObjReItem($SubmitData->modelQuality, "id", $data[$nn]["qualityId"])->title
                                ."; 字印:".$data[$nn]["word"]
                                ."; 金价:".FunctionCode::FindEqObjReItem($SubmitData->modelValuePrice, "purityId", $data[$nn]["purityId"])->goldPrice."/g"//金价只与成色有关
                                ."; 件数:".$data[$nn]["number"];
                            $data[$nn]["orderStatusTitle"] = ModelOrderCls::GetOrderStatusName($data[$nn]["orderStatus"]);
                            $data[$nn]["needPayPrice"] = ($data[$nn]["totalPrice"])*$userPercent;//2017-05-17
                        }
                    }
                }
            }

        }
        if(empty($cpage) || $cpage<=1) {
            if(empty($SubmitData))
            {
                $productedingCount = -1;
                $waitForSendCount = -1;
            }
            else {
                $productedingCount = intval($SubmitData->getOrderGroupByStatusEntity->productedingCount);
                $waitForSendCount = intval($SubmitData->getOrderGroupByStatusEntity->sendedCount);
            }
            $statusCount = ModelOrderCls::GetOrderGroupByStatus($memberId,$productedingCount,$waitForSendCount);
            $redata = array("orderList"=>array("list"=>$data,"list_count"=>$list_count),"statusCount"=>$statusCount);
        }
        else
        {
            $redata = array("orderList"=>array("list"=>$data,"list_count"=>$list_count));
        }
        return myResponse::ResponseDataTrueDataObj($redata);
    }
}