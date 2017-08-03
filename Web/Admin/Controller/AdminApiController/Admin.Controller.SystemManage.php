<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/1/6
 * Time: 15:40
 */
namespace Admin\Controller;
use Common\Common\Api\flow\UpdateModel;
use Common\Common\PublicCode\FunctionCode;
use Think\Controller;
use Common\Common\PublicCode\PartCodeController;
use Common\Common\Api\flow\ModelOrderCls;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\PublicCode\ErpPublicCode;
trait SystemManage
{
    public function updateAppData()
    {
        $this->all = I("get.all");
        if($this->all == 1) {
            $this->data = M("update_model_log")->order("id desc")->limit(50)->select();
        }
        else
        {
            $this->data = M("update_model_log")->where("ishaveUpdate=1")->order("id desc")->limit(50)->select();
        }
        $this->display(PartCodeController::DisplayPath("Api/System/updateAppData"));
    }
    public function updateAppDataDo()
    {
        \Api\Controller\UpdateController::UpdateFromErpModelNow();
    }
    public function updateAppDataBath()
    {
        $this->display(PartCodeController::DisplayPath("Api/System/updateAppData/updateAppDataBath"));
    }
    public function updateAppImageDataBath()
    {
        $this->display(PartCodeController::DisplayPath("Api/System/updateAppData/updateAppImageDataBath"));
    }

    public function updateAppDataBathDo()
    {
        \Api\Controller\UpdateController::UpdateFromErpModelByStringList();
    }
    public function updateAppImagesDataBathDo()
    {
        \Api\Controller\UpdateController::UpdateModelImagesDataByStringList();
    }
    public function updateCache()
    {
        $this->ModelAttributeObj = \Common\Common\Api\flow\BaseCls::CacheModelAttributeBaseInfo();
        $this->display(PartCodeController::DisplayPath("Api/System/updateCache"));
    }
    public function updateCacheDo()
    {
        \Common\Common\Api\flow\BaseCls::ClearCacheModelAttributeBaseInfo();
        echo myResponse::ResponseDataTrueString("同步成功");
    }
}