<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/12/15
 * Time: 16:18
 */
namespace Common\Common\Api\flow;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ConvertType;
use Common\Common\PublicCode\WithSql;
use Common\Common\PublicCode\ErpPublicCode;
trait ModelCurrentOrderOther
{
    public function GetOrderGroupByStatus($member_id,$produceding=-1,$waitForSend=-1)
    {
        $sql = ModelOrderCls::Get_Order_Group_By_Status_Sql($member_id);
        $Model = new \Think\Model();
        $data = $Model->query($sql);
        $waitForValidate = 0;
        $finished = 0;
        foreach($data as $value)
        {
            if($value["orderStatus"] > ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0
                    && $value["orderStatus"] < ModelOrderCls::$ORDER_STATUS_PRODUCE_ORDER_START_TO_FLOW_1001)
            {
                $waitForValidate = $waitForValidate + $value["num"];
            }
        }
        if($produceding==-1) {
            $listData = ErpPublicCode::GetUrlObjData("GetOrderGroupByStatus", "model", array("appmid" => $member_id));
            $produceding = intval($listData->productedingCount);
            $waitForSend = intval($listData->sendedCount);
        }
        $numData = array("waitForValidate"=>$waitForValidate,"produceding"=>$produceding,"waitForSend"=>$waitForSend,"finished"=>$finished);
        return $numData;
    }
    public function GetOrderGroupByStatusForWaitForValidate($member_id)
    {
        $reobj = ModelOrderCls::GetOrderGroupByStatus($member_id,0,0);
        return array("waitForValidate"=>$reobj['waitForValidate']);
    }
}