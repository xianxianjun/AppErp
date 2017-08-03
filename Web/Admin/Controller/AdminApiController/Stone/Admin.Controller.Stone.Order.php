<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/5/23
 * Time: 16:35
 */
namespace Admin\Controller;
use Common\Common\PublicCode\FunctionCode;
use Think\Controller;
use Common\Common\PublicCode\PartCodeController;
use Common\Common\Api\flow\ModelOrderCls;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\flow\StoneCls;
use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\PublicCode\ErpPublicCode;
trait StoneOrderListPage
{
    //======================待付款
    private function adminStoneWaitPayOrderListBase($cpage)
    {
        if (!FunctionCode::isInteger($cpage) && intval($cpage) <= 0) {
            $cpage  = 1;
        }
        $this->defaultValue = StoneCls::$STONE_ORDER_WAIT_PAY;
        self::adminOrderList(0,StoneCls::$STONE_ORDER_WAIT_PAY,$cpage);
        $this->display(PartCodeController::DisplayPath("Api/Stone/StoneOrder/adminStoneWaitPayOrderList"));
    }
    public function adminStoneWaitPayOrderList()
    {
        self::adminStoneWaitPayOrderListBase(1);
    }
    public function nextAdminStoneWaitPayOrderList()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage + 1;
        self::adminStoneWaitPayOrderListBase($cpage);
    }
    public function perAdminStoneWaitPayOrderList()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage - 1;
        self::adminStoneWaitPayOrderListBase($cpage);
    }
    public function lastAdminStoneWaitPayOrderList()
    {
        self::adminStoneWaitPayOrderListBase(1000000);
    }
    public function firstAdminStoneWaitPayOrderList()
    {
        self::adminStoneWaitPayOrderListBase(1);
    }
    //====================已付款
    private function adminStoneAlreadyPayOrderListBase($cpage)
    {
        if (!FunctionCode::isInteger($cpage) && intval($cpage) <= 0) {
            $cpage  = 1;
        }
        $this->defaultValue = StoneCls::$STONE_ORDER_ALREADY_PAY;
        self::adminOrderList(StoneCls::$STONE_ORDER_WAIT_PAY,StoneCls::$STONE_ORDER_ALREADY_PAY,$cpage);
        $this->display(PartCodeController::DisplayPath("Api/Stone/StoneOrder/adminStoneAlreadyPayOrderList"));
    }
    public function adminStoneAlreadyPayOrderList()
    {
        self::adminStoneAlreadyPayOrderListBase(1);
    }
    public function nextAdminStoneAlreadyPayOrderList()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage + 1;
        self::adminStoneAlreadyPayOrderListBase($cpage);
    }
    public function perAdminStoneAlreadyPayOrderList()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage - 1;
        self::adminStoneAlreadyPayOrderListBase($cpage);
    }
    public function lastAdminStoneAlreadyPayOrderList()
    {
        self::adminStoneAlreadyPayOrderListBase(1000000);
    }
    public function firstAdminStoneAlreadyPayOrderList()
    {
        self::adminStoneAlreadyPayOrderListBase(1);
    }
    //=============================已发货
    public function adminStoneAlreadyDeliverGoodsOrderListBase($cpage)
    {
        if (!FunctionCode::isInteger($cpage) && intval($cpage) <= 0) {
            $cpage  = 1;
        }
        $this->defaultValue = StoneCls::$STONE_ORDER_ALREADY_DELIVER_GOODS;
        self::adminOrderList(StoneCls::$STONE_ORDER_ALREADY_PAY,StoneCls::$STONE_ORDER_ALREADY_DELIVER_GOODS,$cpage);
        $this->display(PartCodeController::DisplayPath("Api/Stone/StoneOrder/adminStoneAlreadyDeliverGoodsOrderList"));
    }
    public function adminStoneAlreadyDeliverGoodsOrderList()
    {
        self::adminStoneAlreadyDeliverGoodsOrderListBase(1);
    }
    public function nextAdminStoneAlreadyDeliverGoodsOrderList()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage + 1;
        self::adminStoneAlreadyDeliverGoodsOrderListBase($cpage);
    }
    public function perAdminStoneAlreadyDeliverGoodsOrderList()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage - 1;
        self::adminStoneAlreadyDeliverGoodsOrderListBase($cpage);
    }
    public function lastAdminStoneAlreadyDeliverGoodsOrderList()
    {
        self::adminStoneAlreadyDeliverGoodsOrderListBase(1000000);
    }
    public function firstAdminStoneAlreadyDeliverGoodsOrderList()
    {
        self::adminStoneAlreadyDeliverGoodsOrderListBase(1);
    }
    //======================已完成
    public function adminStoneAlreadyFinishOrderListBase($cpage)
    {
        if (!FunctionCode::isInteger($cpage) && intval($cpage) <= 0) {
            $cpage  = 1;
        }
        $this->defaultValue = StoneCls::$STONE_ORDER_ALREADY_FINISH;
        self::adminOrderList(StoneCls::$STONE_ORDER_ALREADY_DELIVER_GOODS,StoneCls::$STONE_ORDER_ALREADY_FINISH,$cpage);
        $this->display(PartCodeController::DisplayPath("Api/Stone/StoneOrder/adminStoneAlreadyFinishOrderList"));
    }
    public function adminStoneAlreadyFinishOrderList()
    {
        self::adminStoneAlreadyFinishOrderListBase(1);
    }
    public function nextAdminStoneAlreadyFinishOrderList()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage + 1;
        self::adminStoneAlreadyFinishOrderListBase($cpage);
    }
    public function perAdminStoneAlreadyFinishOrderList()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage - 1;
        self::adminStoneAlreadyFinishOrderListBase($cpage);
    }
    public function lastAdminStoneAlreadyFinishOrderList()
    {
        self::adminStoneAlreadyFinishOrderListBase(1000000);
    }
    public function firstAdminStoneAlreadyFinishOrderList()
    {
        self::adminStoneAlreadyFinishOrderListBase(1);
    }
    //=================
    private function adminOrderList($MinOrderStatus,$MaxOrderStatus,$cpage)
    {
        $cpage = $cpage<=0?1:$cpage;
        $pageCount = 20;//BaseCls::$EACH_PAGE_COUNT;

        $Model = new \Think\Model();
        $sql = "select count(*) as num from app_jewel_stone_order where orderStatus>$MinOrderStatus and orderStatus<=$MaxOrderStatus";
        $cdata = $Model->query($sql);
        $allCount = $cdata[0]["num"];

        $countfloat = $allCount/$pageCount;
        if($countfloat < $cpage)
        {
            $cpage = ceil($countfloat);
        }
        $this->Listcpage = $cpage;
        $this->pageCount = ceil($countfloat);
        $this->reCount = $allCount;
        $this->pageQrt = "?cpage=".$cpage
            .(empty($keyword)?"":"&keyword=".$keyword);

        if ($cpage <= 1) {
            $upnum = 0;
        } else {
            $upnum = ($cpage - 1) * $pageCount;
        }
        $limit = " LIMIT " . $upnum . "," . $pageCount;
        $sql = "select a.*,b.userName,b.trueName,
            (select IFNULL(SUM(total_amount),0.00) from app_payment_notity_log where objid=a.id and paySign='stone') as alreadyTotalPrice
            from app_jewel_stone_order a left join app_member b on a.member_Id=b.id where orderStatus>$MinOrderStatus and orderStatus<=$MaxOrderStatus$limit";
        $this->data = $Model->query($sql);

        $OrderStatusData = array(
        array("title"=>StoneCls::GetOrderStatusName(StoneCls::$STONE_ORDER_WAIT_PAY),"value"=>StoneCls::$STONE_ORDER_WAIT_PAY)
        ,array("title"=>StoneCls::GetOrderStatusName(StoneCls::$STONE_ORDER_ALREADY_PAY),"value"=>StoneCls::$STONE_ORDER_ALREADY_PAY)
        ,array("title"=>StoneCls::GetOrderStatusName(StoneCls::$STONE_ORDER_ALREADY_DELIVER_GOODS),"value"=>StoneCls::$STONE_ORDER_ALREADY_DELIVER_GOODS)
        ,array("title"=>StoneCls::GetOrderStatusName(StoneCls::$STONE_ORDER_ALREADY_FINISH),"value"=>StoneCls::$STONE_ORDER_ALREADY_FINISH)
        );
        $optionStr = "";
        foreach($OrderStatusData as $item){
            if($item["value"] <= $MaxOrderStatus  && $item["value"]>$MinOrderStatus) {
                $optionStr = $optionStr . '<option  selected="selected" value="' . $item["value"] . '">' . $item["title"] . '</option>';
            }
            else {
                $optionStr = $optionStr . '<option value="' . $item["value"] . '">' . $item["title"] . '</option>';
            }
        }
        $this->soptionStr = $optionStr;
    }
    public function adminStoneOrderDetails()
    {
        $id = intval(I("get.id"));
        $Model = new \Think\Model();
        $sql = "select a.*,b.userName,b.trueName,
            (select IFNULL(SUM(total_amount),0.00) from app_payment_notity_log where objid=a.id and paySign='stone') as alreadyTotalPrice
            from app_jewel_stone_order a left join app_member b on a.member_Id=b.id where a.id=$id";
        $this->orderData = $Model->query($sql)[0];
        $sql = "select *
            from app_jewel_stone_order_detail where jewel_stone_order_id=$id";
        $this->orderListData = $Model->query($sql);
        $this->display(PartCodeController::DisplayPath("Api/Stone/StoneOrder/adminStoneOrderDetails"));
    }
}