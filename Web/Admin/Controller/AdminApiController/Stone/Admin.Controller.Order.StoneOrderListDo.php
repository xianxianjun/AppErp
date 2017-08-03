<?php
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
trait StoneOrderListDo
{
    public function AdminChangeStoneOrderStatusDo()
    {
        $value = I("get.value");
        $orderId = I("get.orderId");
        $valueStr = StoneCls::GetOrderStatusName($value);
        if(!empty($valueStr) && intval($orderId)>=0)
        {
            $udata["orderStatus"] = $value;
            $re = M("jewel_stone_order")->where("id=$orderId")->save($udata);
            if($re) {
                $re = M("jewel_stone_order_detail")->where("jewel_stone_order_id=$orderId")->save($udata);
            }
            echo myResponse::ResponseDataTrueString("修改成功");
            return;
        }
        echo myResponse::ResponseDataFalseString("修改错误");
    }
}