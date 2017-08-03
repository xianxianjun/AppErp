<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/4/17
 * Time: 15:50
 */
namespace Common\Common\Api\flow;
use Common\Common\PublicCode\BllPublic;
use Common\Common\PublicCode\FunctionCode;
require_once 'Stone/FLOW.STONE.INC.php';
class StoneCls
{
    use stoneBase,stoneInfo,StoneSql;
    public static $INVOICE_TYPE = array(array("id"=>1,"title"=>"明细"),array("id"=>2,"title"=>"宝石"));
    public static $STONE_ORDER_WAIT_PAY = 1000;//待付款
    public static $STONE_ORDER_ALREADY_PAY = 2000;//已付款
    public static $STONE_ORDER_ALREADY_DELIVER_GOODS = 3000;//已发货
    public static $STONE_ORDER_ALREADY_FINISH = 4000;//已完成
    public static function GetOrderStatusName($value)
    {
        if($value == StoneCls::$STONE_ORDER_WAIT_PAY)
        {
            return "待付款";
        }
        else if($value == StoneCls::$STONE_ORDER_ALREADY_PAY)
        {
            return "已付款";
        }
        else if($value == StoneCls::$STONE_ORDER_ALREADY_DELIVER_GOODS)//生产中待生产
        {
            return "已发货";
        }
        else if($value == StoneCls::$STONE_ORDER_ALREADY_FINISH)//生产中待生产
        {
            return "已完成";
        }
        return "";
    }
}