<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/4/18
 * Time: 11:33
 */
namespace Api\Controller;
trait stoneProxy {
    public function stoneSearchInfo()
    {
        StoneController::stoneSearchInfo();
    }
    public function stoneList()
    {
        StoneController::stoneList();
    }
    public function stoneListForJphk()
    {
        StoneController::stoneListForJphk();
    }
    public function stoneOffer()
    {
        StoneController::stoneOffer();
    }
    public function stoneOrderListPage()
    {
        StoneController::stoneOrderListPage();
    }
    public function stoneSubmitOrderDo()
    {
        StoneController::stoneSubmitOrderDo();
    }
    public function stoneWaitPayOrderList()
    {
        StoneController::stoneWaitPayOrderList();
    }
    public function stoneAlreadyPayOrderList()
    {
        StoneController::stoneAlreadyPayOrderList();
    }
    public function stoneAlreadyDeliverGoodsOrderList()
    {
        StoneController::stoneAlreadyDeliverGoodsOrderList();
    }
    public function stoneAlreadyFinishOrderList()
    {
        StoneController::stoneAlreadyFinishOrderList();
    }
    public function stoneOrderDetailpage()
    {
        StoneController::stoneOrderDetailpage();
    }
    public function StoneInvoicePage()
    {
        StoneController::StoneInvoicePage();
    }
    public function PaymentCurrentOrderStonePage()
    {
        StoneController::PaymentCurrentOrderStonePage();
    }
    public function stoneCancelOrderDo()
    {
        StoneController::stoneCancelOrderDo();
    }
}