<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/11/3
 * Time: 15:55
 */
namespace Common\Common\Api\flow;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\Api\flow\ModelOrderCls;
trait PaymentSql
{
    public static function Current_Order_For_Price_Sum_Sql($member_id,$orderId)
    {

        $where = " AND orderStatus=".ModelOrderCls::$ORDER_STATUS_CREATE_ORDER_WAIT_PAY_100." AND model_main_order_current_id =".$orderId;
        $sql =  ModelOrderCls::Current_Order_List_Base_Sql($member_id,$where,"");
        return $sql;
    }
}