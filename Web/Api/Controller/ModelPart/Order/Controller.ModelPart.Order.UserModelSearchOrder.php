<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/3/17
 * Time: 10:27
 */
namespace Api\Controller;
use Common\Common\Api\flow\ModelOrderCls;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\Api\flow\BillCls;

trait UserModelSearchOrder
{
    public static function ModelUserOrderSearchPage()
    {
        $data = ModelOrderCls::ModelUserOrderSearchPage();
        echo myResponse::ResponseDataTrueDataString($data);
    }
    public static function ModelOrderSearch()
    {
        $skeyid = I('get.skeyid', '');
        if($skeyid == 2) {
            $orderNum = '';
            $modelNum = I('get.keyword', '');
        }
        else
        {
            $orderNum = I('get.keyword', '');
            $modelNum = '';
        }

        $sscopeid = I('get.sscopeid', '');

        $cpage = I('get.cpage', '');
        $sd = I('get.sdate','');
        $ed = I('get.edate','');
        $customerID = I('get.customerID','');
        $data = ErpPublicCode::GetUrlObjData("ModelOrderSearch","Bill"
        ,array("serpid"=>$customerID,"scope"=>$sscopeid
        ,"orderNum"=>$orderNum, "modelNum"=>$modelNum
        ,"sd"=>$sd,"ed"=>$ed
        ,"cpage"=>$cpage,"pagenum"=>10));
        echo myResponse::ResponseDataTrueDataString($data);
    }
    public static function ModelOrderSearchDetail()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $orderNum = I('get.orderNum', '');
        $erpId = UserCls::GetOrderErpId($myKey);
        if(!empty($orderNum) && isset($erpId)) {
            $data = ErpPublicCode::GetUrlObjData("ModelOrderSearchDetail", "Bill", array("orderNum" => $orderNum));
            $orderProduce = ModelOrderCls::ModelOrderProduceDetailPageData($data->orderProduce->orderInfo
                ,$data->orderProduce->modelList,$memberId,$orderNum);
            $isMasterAccount = UserCls::GetIsMasterAccount($myKey,true);
            if($isMasterAccount != 1)
            {
                foreach($orderProduce->recList as $item)
                {
                    $item->totalPrice = 0;
                    foreach($item->moList as $item1)
                    {
                        $item1->totalPrice = 0;
                    }
                }
            }
            echo myResponse::ResponseDataTrueDataString(array("orderProduce"=>$orderProduce->data,"orderSended"=>$data->orderSended,"isMasterAccount"=>$isMasterAccount));
        }
        else
        {
            echo myResponse::ResponseDataFalseString("参数错误或没有捆定ERP");
        }
    }
    public static function ModelBillFinishDetailRecForSearch()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $erpId = UserCls::GetOrderErpId($myKey);
        $recNum = I('get.recNum','');
        if(!empty($recNum)  && isset($erpId)) {
            $data = ErpPublicCode::GetUrlObjData("ModelBillFinishDetailRecForSearch", "Bill", array("appmid" => $memberId
            ,"erpid"=>$erpId, "recNum" => $recNum));
            $reData = BillCls::ModelBillFinishDetailRecHandle($data);
            echo myResponse::ResponseDataTrueDataString($reData);
        }
        else
        {
            echo myResponse::ResponseDataFalseString("参数错误");
        }
    }
    public static function ModelArriveBillMoForSearch()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $erpId = UserCls::GetOrderErpId($myKey);
        $moNum = I('get.moNum','');
        if(!empty($moNum)&& isset($erpId)) {
            $DetailMo = ErpPublicCode::GetUrlObjData("ModelArriveBillMoForSearch", "Bill", array("appmid" => $memberId
            ,"erpid"=>$erpId, "moNum" => $moNum));
            $data =  BillCls::ModelArriveBillMoData($DetailMo);
            echo myResponse::ResponseDataTrueDataString($data);
        }
        else
        {
            echo myResponse::ResponseDataFalseString("参数错误");
        }
    }
    public static function ModelOrderProduceDetailHistoryPageForSearch()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $erpId = UserCls::GetOrderErpId($myKey);
        $orderNum = I('get.orderNum', '');
        //这里没有判断权限
        $data = ErpPublicCode::GetUrlObjData("GetIsOrderNumView", "purview", array("orderNum" => $orderNum));
        if($data->$orderNum > 0)
        {
            $Item = M("model_main_order")->where(" orderNum='" . $orderNum . "'")->select();
            $where = ' and 1=1 ';
            if (empty($Item) || count($Item) <= 0) {
                $where = ' and 1=0 ';
                $model_main_order_id = 0;
            }
            else
            {
                $model_main_order_id = $Item[0]["id"];
            }
            $modelList = M("model_main_order_detail")->where(" model_main_order_id=" . $model_main_order_id.$where)->select();
            $data = ModelOrderCls::ModelOrderProduceDetailHistoryPageData($memberId, $orderNum, $Item, $modelList);
            echo myResponse::ResponseDataTrueDataString($data->data);
        }
        else {
            echo myResponse::ResponseDataTrueDataString(null);
        }
    }
    public static function ModelOrderProduceDetailShowRateProgressPageForSearch()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $erpId = UserCls::GetOrderErpId($myKey);
        $orderNum = I('get.orderNum', '');
        //这里没有判断权限
        /*$thisOrderItem = M("model_main_order")->where(" orderNum='".$orderNum."'")->select();
        if(empty($thisOrderItem) || count($thisOrderItem)<=0)
        {
            return myResponse::ResponseDataFalseObj("获取数据错误");
        }

        $thisOrdermodelList = M("model_main_order_detail a,app_model_product b")
            ->field("a.model_main_order_current_detail_id as id,b.pic")
            ->where("a.model_product_id=b.id and a.model_main_order_id='"
                .$thisOrderItem[0]["id"]."'")->select();
        */
        $listData = ErpPublicCode::GetUrlObj("GetModelOrderProduceDetailShowRateProgressPageForSearch","Bill",array("orderNum"=>$orderNum
        ,"erpid"=>$erpId,"pagenum"=>2,"appmid"=>$memberId));
        $data = ModelOrderCls::ModelOrderProduceDetailShowRateProgressPageData($listData);
        echo myResponse::ResponseDataTrueDataString($data->data);
    }
}