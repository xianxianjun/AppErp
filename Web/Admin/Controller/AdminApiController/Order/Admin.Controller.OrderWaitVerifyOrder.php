<?php
namespace Admin\Controller;
use Common\Common\PublicCode\FunctionCode;
use Think\Controller;
use Common\Common\PublicCode\PartCodeController;
use Common\Common\Api\flow\ModelOrderCls;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\PublicCode\ErpPublicCode;
trait OrderWaitVerifyPage
{
    public function ModelOrderWaitCheckVerifyToProduceForAdminDo()
    {
        $orderId = I("get.orderId", "");
        if(intval($orderId) <=0)
        {
            echo myResponse::ResponseDataFalseString('请填写参数');
            return;
        }
        $reObj = ModelOrderCls::ModelOrderWaitCheckVerifyToProduceForAdminDo($orderId);
        echo myResponse::ToResponseJsonString($reObj);
    }
    public function waitVerifyOrder()
    {
        $cpage = intval(I("get.cpage"));
        self::waitVerifyOrderBase($cpage);
    }
    public function nextwaitVerifyOrder()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage + 1;
        self::waitVerifyOrderBase($cpage);
    }
    public function perwaitVerifyOrder()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage - 1;
        self::waitVerifyOrderBase($cpage);
    }
    public function lastwaitVerifyOrder()
    {
        self::waitVerifyOrderBase(1000000);
    }
    public function firstwaitVerifyOrder()
    {
        self::waitVerifyOrderBase(1);
    }
    public function waitVerifyOrderBase($cpage)
    {
        $orderNum = I("get.orderNum");
        $userName = I("get.userName");
        $where = "";
        $and = "";
        $keyword = "";
        $pan = "";
        if(!empty($orderNum))
        {
            $where = " a.orderNum='$orderNum'";
            $and = " and ";
            $keyword = $pan."a.orderNum=".$orderNum;
            $pan = "&";
        }
        if(!empty($userName))
        {
            $where = $where.$and." b.userName='$userName'";
            $and = " and ";
            $keyword = $pan."userName=".$userName;
            $pan = "&";
        }

        $Model = new \Think\Model();
        $sql = "
        select count(1) as con from app_model_main_order_current a
         left join app_member b on a.member_id = b.id where
         orderStatus>".ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0."
         and orderStatus<=".ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000."$and$where
        ";
        $couData = $Model->query($sql)[0];
        $allCount = $couData["con"];
        $pagePercount = 18;
        $cpage = $cpage<=0?1:$cpage;
        $countfloat = $allCount/$pagePercount;
        if($countfloat < $cpage)
        {
            $cpage = ceil($countfloat);
        }
        $this->Listcpage = $cpage;
        $this->pageCount = ceil($countfloat);
        $this->reCount = $allCount;
        $this->pageQrt = "?cpage=".$cpage.$pan.$keyword;
        $up = $pagePercount*($cpage-1)>0?$pagePercount*($cpage-1):0;
        $limit = " LIMIT ".$up.",".$pagePercount;

        $sql = "
        select a.*,b.userName,b.trueName
        ,(select count(1) from app_model_main_order_current_detail b where a.id=b.model_main_order_current_id"
            ." and b.orderStatus>".ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0
            ." and b.orderStatus<=".ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000.") as itemCount
        from app_model_main_order_current a
         left join app_member b on a.member_id = b.id
         where
         orderStatus>".ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0."
         and orderStatus<=".ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000."$and$where
         order by a.id desc $limit";
        $orderdata = $Model->query($sql);
        $this->orderdata = $orderdata;
        $cus = "";
        $purs = "";
        $quas = "";
        foreach ($orderdata as $value) {
            $cus = FunctionCode::ConnectStrForComm($cus,$value["customerId"],",");
            $purs = FunctionCode::ConnectStrForComm($purs,$value["model_purity_id"],",");
            $quas = FunctionCode::ConnectStrForComm($quas,$value["model_quality_id"],",");
        }
        $SubmitData = ErpPublicCode::GetUrlObjData("GetVerifyOrderPage","admin",array("cus"=>$cus,"purs"=>$purs,"quas"=>$quas));
        $this->custromers = $SubmitData->customers;
        $this->puritys = $SubmitData->puritys;
        $this->qualitys = $SubmitData->qualitys;
        $this->display(PartCodeController::DisplayPath("Api/Order/waitVerifyOrder"));
    }
    public function waitVerifyOrderDetail()
    {
        $itemId = I("get.itemId", "");
        $memberId = I("get.memberId", "");
        if(!empty($itemId) && !empty($memberId)) {
            $memberObj = M("member")->where(array("id" => $memberId))->select();
            if(count($memberObj)>0) {
                $this->orderObj = ModelOrderCls::ModelOrderWaitCheckDetail($memberObj[0]["tokenKey"], $memberObj[0]["id"], $itemId, 1,100000);
                $this->orderObj = $this->orderObj->data;
                //echo myResponse::ResponseDataTrueString($this->orderObj);
                $this->display(PartCodeController::DisplayPath("Api/Order/waitVerifyOrderDetail"));
            }
        }
    }
}