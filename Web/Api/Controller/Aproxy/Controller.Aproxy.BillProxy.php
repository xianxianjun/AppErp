<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/3/7
 * Time: 15:11
 */
namespace Api\Controller;
trait BillProxy
{
    public function ModelBillList()
    {
        BillController::ModelBillList();
    }
    public function ModelFinishBillList()
    {
        BillController::ModelFinishBillList();
    }
    public function ModelBillFinishDetailRec()
    {
        BillController::ModelBillFinishDetailRec();
    }
    public function ModelArriveBillMo()
    {
        BillController::ModelArriveBillMo();
    }
}