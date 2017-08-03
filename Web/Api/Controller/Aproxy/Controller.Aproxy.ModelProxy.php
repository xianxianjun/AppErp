<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/23
 * Time: 14:37
 */
namespace Api\Controller;

trait modelPageProxy
{
    public function modelListPage()
    {
        ModelController::modelListPage();
    }
    public function modelFilerPage()
    {
        ModelController::modelFilerPage();
    }
    public function ModelDetailPage()
    {
        ModelController::ModelDetailPage();
    }
    public function getStonePrice()
    {
        ModelController::getStonePrice();
    }
    public function OrderDoCurrentModelItemDo()
    {
        ModelController::OrderCurrentDoModelItemDo();
    }
    public function OrderCurrentDoModelItemForDefaultDo()
    {
        ModelController::OrderCurrentDoModelItemForDefaultDo();
    }
    public function OrderCurrentEditModelItemDo()
    {
        ModelController::OrderCurrentEditModelItemDo();
    }
    public function OrderCurrentEditModelItemForDefaultDo()
    {
        ModelController::OrderCurrentEditModelItemForDefaultDo();
    }
    public function OrderCurrentDeleteModelItemDo()
    {
        ModelController::OrderCurrentDeleteModelItemDo();
    }
    public function ModelDetailPageForCurrentOrderEditPage()
    {
        ModelController::ModelDetailPageForCurrentOrderEditPage();
    }
    public function OrderCurrentSubmitDo()
    {
        ModelController::OrderCurrentSubmitDo();
    }
    public function OrderListPage()
    {
        ModelController::OrderListPage();
    }
    public function GetOrderPricePageList()
    {
        ModelController::GetOrderPricePageList();
    }
    public function PaymentCurrentOrderPage()
    {
        ModelController::PaymentCurrentOrderPage();
    }
    public function PayMentCurrentOrderDo()
    {
        ModelController::PayMentCurrentOrderDo();
    }
    public function ModelOrderWaitCheckList()
    {
        ModelController::ModelOrderWaitCheckList();
    }
    public function ModelOrderWaitCheckDetail()
    {
        ModelController::ModelOrderWaitCheckDetail();
    }
    public function ModelOrderWaitCheckDetailDeleteModelItemDo()
    {
        ModelController::OrderCurrentDeleteModelItemDo();
    }
    public function ModelOrderWaitCheckOrderCurrentEditModelItemDo()
    {
        ModelController::ModelOrderWaitCheckOrderCurrentEditModelItemDo();
    }
    public function ModelOrderWaitCheckOrderCurrentEditModelItemForDefaultDo()
    {
        ModelController::ModelOrderWaitCheckOrderCurrentEditModelItemForDefaultDo();
    }
    public function ModelOrderWaitCheckModelDetailPageForCurrentOrderEditPage()
    {
        ModelController::ModelOrderWaitCheckModelDetailPageForCurrentOrderEditPage();
    }
    public function ModelOrderWaitCheckDetailModifyAddressDo()
    {
        ModelController::ModelOrderWaitCheckDetailModifyAddressDo();
    }
    public function ModelOrderWaitCheckDetailModifyInfoDo()
    {
        ModelController::ModelOrderWaitCheckDetailModifyInfoDo();
    }
    public function ModelOrderWaitCheckCancelDo()
    {
        ModelController::ModelOrderWaitCheckCancelDo();
    }
    public function ModelOrderWaitCheckModifyGetOrderPricePageListDo()
    {
        ModelController::ModelOrderWaitCheckModifyGetOrderPricePageListDo();
    }
    public function ModelOrderWaitCheckItem()
    {
        ModelController::ModelOrderWaitCheckItem();
    }
    /*public function ModelOrderWaitCheckVerifyToProduceDo()
    {
        ModelController::ModelOrderWaitCheckVerifyToProduceDo();
    }
    public function ModelOrderWaitCheckVerifyToProducesDo()
    {
        ModelController::ModelOrderWaitCheckVerifyToProducesDo();
    }*/
    public function ModelOrderProduceListPage()
    {
        ModelController::ModelOrderProduceListPage();
    }
    public function ModelOrderProduceDetailPage()
    {
        ModelController::ModelOrderProduceDetailPage();
    }
    public function ModelOrderProduceDetailHistoryPage()
    {
        ModelController::ModelOrderProduceDetailHistoryPage();
    }
    public function ModelInvoicePage()
    {
        ModelController::ModelInvoicePage();
    }
    public function ModelOrderProduceDetailShowRateProgressPage()
    {
        ModelController::ModelOrderProduceDetailShowRateProgressPage();
    }
    public function CheckSpecificationsForm()
    {
        ModelController::CheckSpecificationsForm();
    }
    public function ModelUserOrderSearchPage()
    {
        ModelController::ModelUserOrderSearchPage();
    }
    public function ModelOrderSearch()
    {
        ModelController::ModelOrderSearch();
    }
    public function ModelOrderSearchDetail()
    {
        ModelController::ModelOrderSearchDetail();
    }
    public function ModelBillFinishDetailRecForSearch()
    {
        ModelController::ModelBillFinishDetailRecForSearch();
    }
    public function ModelArriveBillMoForSearch()
    {
        ModelController::ModelArriveBillMoForSearch();
    }
    public function ModelOrderProduceDetailHistoryPageForSearch()
    {
        ModelController::ModelOrderProduceDetailHistoryPageForSearch();
    }
    public function ModelOrderProduceDetailShowRateProgressPageForSearch()
    {
        ModelController::ModelOrderProduceDetailShowRateProgressPageForSearch();
    }
}