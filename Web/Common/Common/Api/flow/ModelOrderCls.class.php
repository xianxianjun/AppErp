<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/22
 * Time: 9:55
 */
namespace Common\Common\Api\flow;
require_once 'Model/ModelOrderCls/FLOW.MODEL.MODELORDERCLS.INC.php';
class ModelOrderCls
{
    use ModelCurrentOrderDo,ModelCurrentOrderPage,ModelOrderSql
    ,ModelOrderWaitCheckPage,ModelOrderWaitCheckDo,ModelUserOrderWaitCheckSql
    ,ModelOrderProducePage,ModelUserOrderProduceSql
    ,ModelOrderAdminDo
    ,ModelCurrentOrderOther
    ,ModelUserOrderSearch;
    //发票类型
    public static $INVOICE_TYPE = array(array("id"=>1,"title"=>"明细"),array("id"=>2,"title"=>"珠宝"));
    //每增加1000代表一个大状态
    public static $CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000 = 1000;//能够修改最大状态
    public static $ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0 = 0;//没有生成订单前状态
    public static $ORDER_STATUS_CREATE_ORDER_WAIT_PAY_100 = 100;//待审核->待付定金
    public static $ORDER_STATUS_CREATE_ORDER_ALREADY_PAY_200 = 200;//待审核->已付定金
    //生产中
    public static $ORDER_STATUS_PRODUCE_ORDER_START_TO_FLOW_1001 = 1001;//生产中->开始生产流程
    public static $ORDER_STATUS_WAIT_SEND_TO_CUSTOMER_2001 = 2001;//生产中->待发货
    public static function GetOrderStatusName($value)
    {
        if($value == ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_WAIT_PAY_100)
        {
            return "待付定金";
        }
        else if($value == ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_ALREADY_PAY_200)
        {
            return "已付定金";
        }
        else if($value == ModelOrderCls::$ORDER_STATUS_PRODUCE_ORDER_START_TO_FLOW_1001)//生产中待生产
        {
            return "待生产";
        }
        return "";
    }
    //ERP状态
    public static $ORDER_STATUS_ERP_PRODUCE_ORDER_START_TO_FLOW_A0 = 'A0';//生产中->开始生产流程
    public static function GetOrderProduceStatusName($value)
    {
        if($value == ModelOrderCls::$ORDER_STATUS_ERP_PRODUCE_ORDER_START_TO_FLOW_A0)
        {
            return "待生产";
        }
        return "";
    }
}