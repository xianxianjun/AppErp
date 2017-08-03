<?php
namespace Api\Controller;

use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\PaymentCls;
use Common\Common\Api\flow\UpdateCls;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\PublicCode\ExpandTp;

trait UpdateCategoryData
{
    public function UpdateCategoryData()
    {
        $Model = new \Think\Model();
        $Model->execute("TRUNCATE app_model_category_temp");
        $edata = ErpPublicCode::GetUrlObj("GetUpdateCategoryData","update");
        if(!empty($edata->data) && count($edata->data)>0)
        {
            $arr = FunctionCode::ArrObjToFieldsArr($edata->data, array("erpId","title","type"));
            $re = M("model_category_temp")->addAll($arr);
        }
        echo myResponse::ResponseDataTrueString("",$re);
    }
    public function UpdateCategoryModelData()
    {
        $Model = new \Think\Model();
        $Model->execute("TRUNCATE app_model_product_category_temp");
        $edata = ErpPublicCode::GetUrlObj("GetUpdateCategoryModelData","update",array("sd"=>"2016-01-01","ed"=>"2017-09-09"));
        if(!empty($edata->data) && count($edata->data)>0)
        {
            $arr = FunctionCode::ArrObjToFieldsArr($edata->data, array("erpId","modelNum","type","isvalid"));
            $re = M("model_product_category_temp")->addAll($arr);
        }
        echo myResponse::ResponseDataTrueString("",$re);
    }
}
