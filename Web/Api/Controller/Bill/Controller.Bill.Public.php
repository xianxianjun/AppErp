<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/3/7
 * Time: 15:03
 */
namespace Api\Controller;

use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\ModelOrderCls;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\Api\flow\BillCls;
trait BillPublic
{
    //订单单据列表
    public function ModelBillList()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        //$memberId = 30;
        $cpage = I('get.cpage', '');
        $BillListData = BillCls::ModelBillList($memberId,$cpage);
        echo myResponse::ResponseDataTrueDataString($BillListData);
    }
    //结算单列表
    public function  ModelFinishBillList()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        //$memberId = 30;
        $orderNum = I('get.orderNum', '');
        if(empty($orderNum))
        {
            echo myResponse::ResponseDataFalseString("缺少参数");
        }
        else {
            $BillListData = BillCls::ModelFinishBillList($orderNum, $memberId);
            $isMasterAccount = UserCls::GetIsMasterAccount($myKey,true);
            $BillListData->isMasterAccount = $isMasterAccount;
            if($isMasterAccount != 1)
            {
                foreach($BillListData->recList as $item)
                {
                    $item->totalPrice = 0;
                    foreach($item->moList as $item1)
                    {
                        $item1->totalPrice = 0;
                    }
                }
            }
            //$data = array_merge($BillListData,array("isMasterAccount"=>UserCls::GetIsMasterAccount($myKey,true)));
        }
        echo myResponse::ResponseDataTrueDataString($BillListData);
    }
    //结算单详情
    public function ModelBillFinishDetailRec()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        //$memberId = 30;
        $recNum = I('get.recNum', '');
        if(empty($recNum))
        {
            echo myResponse::ResponseDataFalseString("缺少参数");
        }
        else {
            $isMasterAccount = UserCls::GetIsMasterAccount($myKey,true);
            if($isMasterAccount != 1) {
                echo myResponse::ResponseDataFalseString("非主账户，禁止查看结算单");
            }
            else {
                $DetailRec = BillCls::ModelBillFinishDetailRec($recNum, $memberId);
                echo myResponse::ResponseDataTrueDataString($DetailRec);
            }
        }
    }
    //出库单详情
    public function ModelArriveBillMo()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        //$memberId = 30;
        $moNum = I('get.moNum', '');
        if(empty($moNum))
        {
            echo myResponse::ResponseDataFalseString("缺少参数");
        }
        else {
            $isMasterAccount = UserCls::GetIsMasterAccount($myKey,true);
            if($isMasterAccount != 1) {
                echo myResponse::ResponseDataFalseString("非主账户，禁止查看出库单");
            }
            else {
                $uDetailMo = BillCls::ModelArriveBillMo($moNum, $memberId);
                echo myResponse::ResponseDataTrueDataString($uDetailMo);
            }
        }
    }
}