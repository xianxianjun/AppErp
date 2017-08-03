<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/12/1
 * Time: 9:31
 */
namespace Common\Common\Api\flow;

use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ConvertType;
use Common\Common\PublicCode\WithSql;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\flow\ModelCls;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\Code\ValidateCode;
use Common\Common\PublicCode\isHaveCustomer;
use Common\Common\PublicCode\BllPublic;

trait ModelOrderProducePage
{
    public static function ModelOrderProduceListPage($memberId,$cpage)
    {
        $listData = ErpPublicCode::GetUrlObj("ModelOrderProduceListPage","model",array("cpage"=>$cpage,"pagenum"=>8,"appmid"=>$memberId));
        if(empty($listData) || $listData->error>0)
        {
            return myResponse::ResponseDataFalseObj("获取数据错误");
        }

        $orderNums = "";
        foreach($listData->data->produceList as $value)
        {
            $orderNums = FunctionCode::ConnectStrForComm($orderNums,$value->OrderID,",");
        }
        $appListData = array();
        if(!empty($orderNums))
        {
            $inorderNums  = WithSql::ForInString("orderNum",",",$orderNums);
            $appListData = M("model_main_order")->where($inorderNums." and member_id=".$memberId)->select();
        }

        $reList = null;
        $OrderStatusArr = BaseCls::CacheModelOrderStatus();
        foreach($listData->data->produceList as $value)
        {
            $Item = FunctionCode::FindEqArr($appListData,"orderNum",$value->OrderID);
            if(!empty($Item)) {
                $picsObj = M("model_main_order_detail")->field(BllPublic::SqlConnPicHttpBasePath("model_product_pic"))
                    ->where("model_main_order_id=".$Item["id"]." and ifnull(model_product_pic,'')!='' and member_id=".$memberId)->limit(4)->select();
                $pics = FunctionCode::ArrToFieldArr($picsObj,"pic");
                $orderStatus = FunctionCode::FindEqObjReField($OrderStatusArr, "statusKey", "statusTitle", $value->OrderStatus);
                $word = empty($value->Sigil)?"":"字印:".$value->Sigil."; ";
                $otherInfo = "成色:".$Item["model_purity"]."; 质量等级:".$Item["model_quality"]."; ".$word."金价:".$value->GoldPrice."/g; 件数:".$value->number;
                $reList[] = array("id"=>$Item["id"],"orderNum"=>$Item["orderNum"]
                ,"customerName"=>$value->CustomerName,"orderDate"=>$value->OrderDate,"confirmDate"=>$value->ConfirmDate
                ,"otherInfo"=>$otherInfo,"totalPrice"=>$Item["totalPrice"]
                ,"needPayPrice"=>$Item["needPayPrice"],"orderStatusTitle"=>$orderStatus,"pics"=>$pics);
            }
        }
        if(empty($cpage) || $cpage<=1) {
            if(empty($listData))
            {
                $productedingCount = -1;
                $waitForSendCount = -1;
            }
            else {
                $productedingCount = intval($listData->data->getOrderGroupByStatusEntity->productedingCount);
                $waitForSendCount = intval($listData->data->getOrderGroupByStatusEntity->sendedCount);
            }
            $statusCount = ModelOrderCls::GetOrderGroupByStatus($memberId,$productedingCount,$waitForSendCount);
            $reObj = array("orderList"=>array("list"=>$reList,"list_count"=>$listData->data->list_count),"statusCount"=>$statusCount);
        }
        else {
            $reObj = array("orderList" => array("list" => $reList, "list_count" => $listData->data->list_count));
        }
        return myResponse::ResponseDataTrueDataObj($reObj);
    }
    public static function ModelOrderProduceDetailPageData($orderInfo,$tmodelList,$memberId,$orderNum)
    {
        if(empty($orderInfo))
        {
            return null;
        }
        $Item = M("model_main_order")->where(" orderNum='".$orderNum."' and member_id=".$memberId)->select();
        $word = empty($orderInfo->Sigil)?"":"字印:".$orderInfo->Sigil."; ";
        $orderStatus = FunctionCode::FindEqObjReField(BaseCls::CacheModelOrderStatus(), "statusKey", "statusTitle", $orderInfo->OrderStatus);
        $orderotherInfo = "成色:".$Item[0]["model_purity"]."; 质量等级:".$Item[0]["model_quality"]."; ".$word."金价:".$orderInfo->GoldPrice."/g; 件数:".$orderInfo->number;
        $orderInfot = array("orderNum"=>$orderInfo->OrderID,"customerName"=>$orderInfo->CustomerName
        ,"orderDate"=>$orderInfo->OrderDate,"confirmDate"=>$orderInfo->ConfirmDate,"orderNote"=>$orderInfo->OrderMemo,"otherInfo"=>$orderotherInfo,"totalPrice"=>$Item[0]["totalPrice"]
        ,"needPayPrice"=>$Item[0]["needPayPrice"],"orderStatusTitle"=>$orderStatus
        ,"address"=>$Item[0]["member_address"],"invoiceTitle"=>$Item[0]["invoiceTitle"],"invoiceType"=>$Item[0]["invoiceType"]
        ,"orderNote"=>$orderInfo->OrderMemo);
        $modelList = M("model_main_order_detail")->where(" model_main_order_id='".$Item[0]["id"]."' and member_id=".$memberId)->select();
        $list = array();
        $modelStr = "";
        foreach ($tmodelList as $value)
        {
            $modelStr = empty($modelStr)?"'".$value->ModuleID."'":$modelStr.",'".$value->ModuleID."'";
        }
        $piclist = array();
        if(!empty($modelStr)) {
            $piclist = M('model_product')->field("modelNum,pic")->where(" modelNum in (".$modelStr.")")->select();
        }
        //if(count($modelList)>0) {
            foreach ($tmodelList as $value) {
                $orderitem = null;
                $orderitem = FunctionCode::FindEqArr($modelList,"model_main_order_current_detail_id",$value->appDetailID);
                //if(!empty($orderitem)) {
                    //-------------
                    $stoneInfo = (empty($value->StoneP)?"":"类型:".$value->StoneP)
                        .(empty($value->StonePQuantity)?"":",数量:".$value->StonePQuantity."粒")
                        .(empty($value->StonePSpecs)?"":",规格:".$value->StonePSpecs)
                        .(empty($value->StonePFigure)?"":",形状:".$value->StonePFigure)
                        .(empty($orderitem["stone_color"])?"":",颜色:".$orderitem["stone_color"])
                        .(empty($orderitem["stone_purity"])?"":",纯度:".$orderitem["stone_purity"])
                        .(ConvertType::ConvertInt($value->IsCSP,0) == 1?",自带石头":"");
                    $stoneInfo = empty($stoneInfo)?"":"主石(".$stoneInfo.")";

                    $stoneInfoA = (empty($value->StoneSA)?"":"类型:".$value->StoneSA)
                        .(empty($value->StoneSAQuantity)?"":",数量:".$value->StoneSAQuantity."粒")
                        .(empty($value->StoneSASpecs)?"":",规格:".$value->StoneSASpecs)
                        .(empty($value->StoneSAFigure)?"":",形状:".$value->StoneSAFigure)
                        .(empty($orderitem["stone_color_A"])?"":",颜色:".$orderitem["stone_color_A"])
                        .(empty($orderitem["stone_purity_A"])?"":",纯度:".$orderitem["stone_purity_A"]);
                    $stoneInfoA = empty($stoneInfoA)?"":";副石A(".$stoneInfoA.")";

                    $stoneInfoB = (empty($value->StoneSB)?"":"类型:".$value->StoneSB)
                        .(empty($value->StoneSBQuantity)?"":",数量:".$value->StoneSBQuantity."粒")
                        .(empty($value->StoneSBSpecs)?"":",规格:".$value->StoneSBSpecs)
                        .(empty($value->StoneSBFigure)?"":",形状:".$value->StoneSBFigure)
                        .(empty($orderitem["stone_color_B"])?"":",颜色:".$orderitem["stone_color_B"])
                        .(empty($orderitem["stone_purity_B"])?"":",纯度:".$orderitem["stone_purity_B"]);
                    $stoneInfoB = empty($stoneInfoB)?"":";副石B(".$stoneInfoB.")";

                    $stoneInfoC = (empty($value->StoneSC)?"":"类型:".$value->StoneSC)
                        .(empty($value->StoneSCQuantity)?"":",数量:".$value->StoneSCQuantity."粒")
                        .(empty($value->StoneSCSpecs)?"":",规格:".$value->StoneSCSpecs)
                        .(empty($value->StoneSCFigure)?"":",形状:".$value->StoneSCFigure)
                        .(empty($orderitem["stone_color_C"])?"":",颜色:".$orderitem["stone_color_C"])
                        .(empty($orderitem["stone_purity_C"])?"":",纯度:".$orderitem["stone_purity_C"]);
                    $stoneInfoC = empty($stoneInfoC)?"":";副石C(".$stoneInfoC.")";

                    $remarks = $value->remarks;
                    $remarks = empty($remarks)?"":";备注(".$remarks.")";

                    $info = $stoneInfo.$stoneInfoA.$stoneInfoB.$stoneInfoC.$remarks;
                    $baseInfo = "类型:".$value->TypeName.(empty($value->Perimeter)?"":";手寸:".floatval($value->Perimeter));
                    //----------------
                    $listItem = array("id"=>$orderitem["model_main_order_current_detail_id"]
                    ,"modelId"=>$orderitem["model_product_id"]
                    ,"title"=>empty($orderitem["model_product"])?$value->ModuleID:$orderitem["model_product"]
                    //,"pic"=>BllPublic::GetPicBasePath().$orderitem["model_product_pic"]
                    ,"pic"=>BllPublic::GetPicBasePath().FunctionCode::FindEqArrReField($piclist,"modelNum","pic",$value->ModuleID)
                    ,"baseInfo"=>$baseInfo
                    ,"stonePrice"=>$orderitem["stoneTotalPrice"]
                    ,"price"=>$orderitem["unitprice"]
                    ,"needPayPrice"=>$orderitem["unitNeedPrice"]
                    ,"number"=>$value->QuantityDetail
                    ,"info"=>$info);
                    $list[] = $listItem;
                //}
            //}
        }
        return myResponse::ResponseDataTrueDataObj(array("orderInfo"=>$orderInfot,"modelList"=>$list));
    }
    public static function ModelOrderProduceDetailPage($memberId,$orderNum,$erpid)
    {
        $listData = ErpPublicCode::GetUrlObj("ModelOrderProduceDetailPage","model",array("appmid"=>$memberId,"orderNum"=>$orderNum,"erpid"=>$erpid));
        if(empty($listData) || $listData->error>0 || empty($listData->data->orderInfo))
        {
            return myResponse::ResponseDataFalseObj("获取数据错误");
        }
        return ModelOrderCls::ModelOrderProduceDetailPageData($listData->data->orderInfo,$listData->data->modelList,$memberId,$orderNum);
    }
    public static function ModelOrderProduceDetailHistoryPage($memberId,$orderNum)
    {
        $Item = M("model_main_order")->where(" orderNum='".$orderNum."' and member_id=".$memberId)->select();
        if(empty($Item) || count($Item)<=0)
        {
            return myResponse::ResponseDataFalseObj("获取数据错误");
        }
        $modelList = M("model_main_order_detail")->where(" model_main_order_id='".$Item[0]["id"]."' and member_id=".$memberId)->select();
        return ModelOrderCls::ModelOrderProduceDetailHistoryPageData($memberId,$orderNum,$Item,$modelList);
    }
    public static function ModelOrderProduceDetailHistoryPageData($memberId,$orderNum,$Item,$modelList)
    {
        /*$Item = M("model_main_order")->where(" orderNum='".$orderNum."' and member_id=".$memberId)->select();
        if(empty($Item) || count($Item)<=0)
        {
            return myResponse::ResponseDataFalseObj("获取数据错误");
        }*/
        $word = empty($Item[0]["word"])?"":"字印:".$Item[0]["word"]."; ";
        $orderStatus = FunctionCode::FindEqObjReField(BaseCls::CacheModelOrderStatus(), "statusKey", "statusTitle", $Item[0]["orderStatus"]);
        $orderNote = $Item[0]["orderNote"];
        $orderotherInfo = "成色:".$Item[0]["model_purity"]."; 质量等级:".$Item[0]["model_quality"]."; ".$word."金价:".$Item[0]["goldPrice"]."/g; 件数:".$Item[0]["number"];
        $orderInfot = array("orderNum"=>$orderNum,"customerName"=>$Item[0]["customerName"]
        ,"orderDate"=>$Item[0]["orderDate"],"confirmDate"=>$Item[0]["createDate"],"orderNote"=>$orderNote,"otherInfo"=>$orderotherInfo,"totalPrice"=>$Item[0]["totalPrice"]
        ,"needPayPrice"=>$Item[0]["needPayPrice"],"orderStatusTitle"=>$orderStatus
        ,"address"=>$Item[0]["member_address"],"invoiceTitle"=>$Item[0]["invoiceTitle"],"invoiceType"=>$Item[0]["invoiceType"]);
        //$modelList = M("model_main_order_detail")->where(" model_main_order_id='".$Item[0]["id"]."' and member_id=".$memberId)->select();
        $list = array();
        if(count($modelList)>0) {
            foreach ($modelList as $value) {
                $stoneInfo = (empty($value["stone_category"])?"":"类型:".$value["stone_category"])
                    .(empty($value["stone_number"])?"":",数量:".$value["stone_number"]."粒")
                    .(empty($value["stone_spec"])?"":",规格:".$value["stone_spec"])
                    .(empty($value["stone_shape"])?"":",形状:".$value["stone_shape"])
                    .(empty($value["stone_color"])?"":",颜色:".$value["stone_color"])
                    .(empty($value["stone_purity"])?"":",纯度:".$value["stone_purity"])
                    . (ConvertType::ConvertInt($value['isSelfStone'],0)==1?",自带石头":"");
                $stoneInfo = empty($stoneInfo)?"":"主石(".$stoneInfo.")";

                $stoneInfoA = (empty($value["stone_category_A"])?"":"类型:".$value["stone_category_A"])
                    .(empty($value["stone_number_A"])?"":",数量:".$value["stone_number_A"]."粒")
                    .(empty($value["stone_spec_A"])?"":",规格:".$value["stone_spec_A"])
                    .(empty($value["stone_shape_A"])?"":",形状:".$value["stone_shape_A"])
                    .(empty($value["stone_color_A"])?"":",颜色:".$value["stone_color_A"])
                    .(empty($value["stone_purity_A"])?"":",纯度:".$value["stone_purity_A"]);
                $stoneInfoA = empty($stoneInfoA)?"":";副石A(".$stoneInfoA.")";

                $stoneInfoB =  (empty($value["stone_category_B"])?"":"类型:".$value["stone_category_B"])
                    .(empty($value["stone_number_B"])?"":",数量:".$value["stone_number_B"]."粒")
                    .(empty($value["stone_spec_B"])?"":",规格:".$value["stone_spec_B"])
                    .(empty($value["stone_shape_B"])?"":",形状:".$value["stone_shape_B"])
                    .(empty($value["stone_color_B"])?"":",颜色:".$value["stone_color_B"])
                    .(empty($value["stone_purity_B"])?"":",纯度:".$value["stone_purity_B"]);
                $stoneInfoB = empty($stoneInfoB)?"":";副石B(".$stoneInfoB.")";

                $stoneInfoC = (empty($value["stone_category_C"])?"":"类型:".$value["stone_category_C"])
                    .(empty($value["stone_number_C"])?"":",数量:".$value["stone_number_C"]."粒")
                    .(empty($value["stone_spec_C"])?"":",规格:".$value["stone_spec_C"])
                    .(empty($value["stone_shape_C"])?"":",形状:".$value["stone_shape_C"])
                    .(empty($value["stone_color_C"])?"":",颜色:".$value["stone_color_C"])
                    .(empty($value["stone_purity_C"])?"":",纯度:".$value["stone_purity_C"]);
                $stoneInfoC = empty($stoneInfoC)?"":";副石C(".$stoneInfoC.")";

                $remarks = $value["remarks"];
                $remarks = empty($remarks)?"":";备注(".$remarks.")";

                $info = $stoneInfo.$stoneInfoA.$stoneInfoB.$stoneInfoC.$remarks;
                $baseInfo = "类型:".$value["model_category"].(empty($value["handSize"])?"":";手寸:".$value["handSize"]);
                //----------------
                $listItem = array("id"=>$value["model_main_order_current_detail_id"]
                ,"modelId"=>$value["model_product_id"]
                ,"title"=>$value["model_product"]
                ,"pic"=>BllPublic::GetPicBasePath().$value["model_product_pic"]
                ,"baseInfo"=>$baseInfo
                ,"stonePrice"=>$value["stoneTotalPrice"]
                ,"price"=>$value["unitprice"]
                ,"needPayPrice"=>$value["unitNeedPrice"]
                ,"number"=>$value["number"]
                ,"info"=>$info);
                $list[] = $listItem;
            }
        }
        return myResponse::ResponseDataTrueDataObj(array("orderInfo"=>$orderInfot,"modelList"=>$list));
    }
    public static function ModelOrderProduceDetailShowRateProgressPage($memberId,$orderNum)
    {
        $thisOrderItem = M("model_main_order")->where(" orderNum='".$orderNum."' and member_id=".$memberId)->select();
        if(empty($thisOrderItem) || count($thisOrderItem)<=0)
        {
            return myResponse::ResponseDataFalseObj("获取数据错误");
        }
        $thisOrdermodelList = M("model_main_order_detail a,app_model_product b")
            ->field("a.model_main_order_current_detail_id as id,b.pic")
            ->where("a.model_product_id=b.id and a.model_main_order_id='"
                .$thisOrderItem[0]["id"]."' and a.member_id=".$memberId)->select();
        $listData = ErpPublicCode::GetUrlObj("GetModelOrderProduceDetailShowRateProgressPage","model",array("orderNum"=>$orderNum,"pagenum"=>2,"appmid"=>$memberId));
        return ModelOrderCls::ModelOrderProduceDetailShowRateProgressPageData($thisOrdermodelList,$listData);
    }
    public static function ModelOrderProduceDetailShowRateProgressPageData($listData)
    {
        /*$thisOrderItem = M("model_main_order")->where(" orderNum='".$orderNum."' and member_id=".$memberId)->select();
        if(empty($thisOrderItem) || count($thisOrderItem)<=0)
        {
            return myResponse::ResponseDataFalseObj("获取数据错误");
        }
        $thisOrdermodelList = M("model_main_order_detail a,app_model_product b")
            ->field("a.model_main_order_current_detail_id as id,b.pic")
            ->where("a.model_product_id=b.id and a.model_main_order_id='"
            .$thisOrderItem[0]["id"]."' and a.member_id=".$memberId)->select();
        $listData = ErpPublicCode::GetUrlObj("GetModelOrderProduceDetailShowRateProgressPage","model",array("orderNum"=>$orderNum,"pagenum"=>2,"appmid"=>$memberId));*/
        if(empty($listData) || $listData->error>0)
        {
            return myResponse::ResponseDataFalseObj("获取数据错误");
        }
        $order = $listData->data->modelOrderProduceListItem;
        if(empty($order))
        {
            return myResponse::ResponseDataFalseObj("获取数据错误");
        }
        $orderModelList = $listData->data->modelOrderProduceDetailListItems;
        $orderModelProgressList = $listData->data->produceDetailRateProgressList;
        $produceFlow = $listData->data->modelProduceFlowList;
        $orderInfo = array("orderNum"=>$order->OrderID,"ConfirmDate"=>$order->ConfirmDate,
        "otherInfo"=>(empty($order->PurityName)?"":"成色:".$order->PurityName.";")
        .(empty($order->QualityName)?"":"质量:".$order->QualityName.";")
        .(empty($order->Sigil)?"":"字印:".$order->Sigil.";"));
        $typeList = BaseCls::CacheModelCategory();

        //$progress = array();
        $thisOrderModelList = array();
        $modelNumList = "";
        foreach($orderModelList as $value)
        {
            $modelNumList = empty($modelNumList)?"'".$value->ModuleID."'":$modelNumList.",'".$value->ModuleID."'";
        }
        $picList = array();
        if(!empty($modelNumList))
        {
            $picList = M("model_product")->field('modelNum,pic')->where("modelNum in (".$modelNumList.")")->select();
        }
        foreach($orderModelList as $value)
        {
            //$thisId = $value->appDetailID;
            //$itemn = $thisOrdermodelList[FunctionCode::FindEqArrReN($thisOrdermodelList,"id",$thisId)];

            $modelNum = $value->ModuleID;
            $typeName = $value->TypeName;
            $perimeter = $value->Perimeter;
            $number = $value->QuantityDetail;
            $pic = FunctionCode::FindEqArrReField($picList,"modelNum","pic",$modelNum);
            $progress = array();
            foreach($orderModelProgressList as $pvalue)
            {
                if($pvalue->ModuleID == $value->ModuleID) {
                    $progress[] = array(
                        "flowInfo" => "类型:" . FunctionCode::FindEqArrReField($typeList, "erpTypeId", "title", $pvalue->TypeID) . ";数量:" . $pvalue->QuatityBill
                            . ";进度:" . $pvalue->SectionName
                    , "currentFlow" => FunctionCode::FindEqObjReField($produceFlow, "flow", "step", $pvalue->CurrentFlowSection));
                }
            }
            $thisOrderModelList[] = array("title"=>$modelNum
                ,"modelInfo"=>"类型:".$typeName.(empty($perimeter)?"":";手寸:".$perimeter),"pic"=>empty($pic)?"":BllPublic::GetPicBasePathPic($pic)
                ,"number"=>$number,"progress"=>$progress);
        }
        $data = array("orderInfo"=>$orderInfo,"orderlList"=>$thisOrderModelList,"flowTotalCount"=>count($produceFlow));
        return myResponse::ResponseDataTrueDataObj($data);
    }
}