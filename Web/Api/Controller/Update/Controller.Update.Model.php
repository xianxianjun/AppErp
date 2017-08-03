<?php
namespace Api\Controller;

use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\PaymentCls;
use Common\Common\Api\flow\UpdateCls;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ExpandTp;

trait ErpUpdateModel
{
    public function UpdateFromErpModelNow()
    {
        $edate=strtotime('now');
        $sdate=$edate-30*60;
        $edate = date('Y-m-d G:i:s',$edate);
        $sdate = date('Y-m-d G:i:s',$sdate);
        UpdateController::UpdateFromErpModelBase($sdate,$edate);
    }
    public function UpdateFromErpModel()
    {
        $sdate = I("get.sd");
        $edate = I("get.ed");
        UpdateController::UpdateFromErpModelBase($sdate,$edate);
    }
    public function UpdateModelImagesDataByDate()
    {
        $sdate = I("get.sd");
        $edate = I("get.ed");
        UpdateController::UpdateModelImagesDataByDateBase($sdate,$edate);
    }
    public function UpdateFromErpModelByStringList()
    {
        $list = I("post.list");
        //$list = "A0001,A0002,A0003,A0004";//,ASY0268,C04028,C04056,C04093,C04106,E454,FY0176-15
        if(!empty($list))
        {
            $arrList = explode(',',$list);
            $reObj = UpdateCls::UpdateRelatedDataByModels($arrList);

            $uCareObj = UpdateCls::UpdateCategory($reObj->data['category']['updateCateData']);
            $aCareObj = UpdateCls::addCategoryInfo($reObj->data['category']['addCateData']);

            $ureObj = UpdateCls::UpdateModelInfo($reObj->data['model']['updateData']);//addData
            $areObj = UpdateCls::addModelInfo($reObj->data['model']['addData']);
            $cateObj = UpdateCls::UpdateModelCategoryInfo($reObj->data['model']['updateCategoryData']);
            $upriPicObj = UpdateCls::UpdatePriorSortData($reObj->data['model']['updateData']);
            $apriPicObj = UpdateCls::UpdatePriorSortData($reObj->data['model']['addData']);

            $updateData = myResponse::ResponseDataTrueString("同步成功",
                array(
                    "updateCategory"=>$uCareObj->message,"addCategory"=>$aCareObj->message
                ,"updateData"=>$ureObj->message,"addData"=>$areObj->message
                ,"updateModelCategory"=>$cateObj->data["updateDateCategory"],"addModelCategory"=>$cateObj->data["addDateCategory"]
                ,"upriPicObj"=>$upriPicObj->data,"apriPicObj"=>$apriPicObj->data));
            $data['updateData'] = $updateData;
            $data['ishaveUpdate'] = count($reObj->data['model']['addData'])>0 || count($reObj->data['model']['updateData'])>0
            || count($reObj->data['model']['updateCategoryData'])>0?1:0;
            $data['type'] = '手动款号更新数据';
            M('update_model_log')->add($data);
            echo $updateData;
        }
    }

    public function UpdateFromErpModelBase($sdate,$edate)
    {
        if(!FunctionCode::isDate($sdate) || !FunctionCode::isDate($edate) || strtotime($sdate)>strtotime($edate))
        {
            echo myResponse::ResponseDataFalseString('传递参数错误');
            return;
        }
        $reObj = UpdateCls::UpdateRelatedData(str_replace(' ','%20',$sdate),str_replace(' ','%20',$edate));
        if($reObj->error == 1)
        {
            echo myResponse::ToResponseJsonString($reObj);
            return;
        }
        //echo myResponse::ToResponseJsonString($reObj);
        $uCareObj = UpdateCls::UpdateCategory($reObj->data['category']['updateCateData']);
        $aCareObj = UpdateCls::addCategoryInfo($reObj->data['category']['addCateData']);

        $ureObj = UpdateCls::UpdateModelInfo($reObj->data['model']['updateData']);//addData
        $areObj = UpdateCls::addModelInfo($reObj->data['model']['addData']);
        $cateObj = UpdateCls::UpdateModelCategoryInfo($reObj->data['model']['updateCategoryData']);
        $upriPicObj = UpdateCls::UpdatePriorSortData($reObj->data['model']['updateData']);
        $apriPicObj = UpdateCls::UpdatePriorSortData($reObj->data['model']['addData']);

        $updateData = myResponse::ResponseDataTrueString("同步成功",
            array(
            "updateCategory"=>$uCareObj->message,"addCategory"=>$aCareObj->message
            ,"updateData"=>$ureObj->message,"addData"=>$areObj->message
            ,"updateModelCategory"=>$cateObj->data["updateDateCategory"],"addModelCategory"=>$cateObj->data["addDateCategory"]
            ,"upriPicObj"=>$upriPicObj->data,"apriPicObj"=>$apriPicObj->data
            ,"startTime"=>$sdate,"endTime"=>$edate));
        $data['updateData'] = $updateData;
        $data['startTime'] = $sdate;
        $data['endTime'] = $edate;
        $data['ishaveUpdate'] = count($reObj->data['model']['addData'])>0 || count($reObj->data['model']['updateData'])>0
                                || count($reObj->data['model']['updateCategoryData'])>0?1:0;
        $data['type'] = '自动更新数据';
        M('update_model_log')->add($data);
        echo $updateData;
    }
    public function UpdateModelImagesDataByDateBase($sdate,$edate)
    {
        if(!FunctionCode::isDate($sdate) || !FunctionCode::isDate($edate) || strtotime($sdate)>strtotime($edate))
        {
            echo myResponse::ResponseDataFalseString('传递参数错误');
            return;
        }
        $reObj = UpdateCls::UpdateModelImagesData(str_replace(' ','%20',$sdate),str_replace(' ','%20',$edate));
        $upriPicObj = UpdateCls::UpdateModelImagesInfo($reObj->data['model']['updateImagesData']);
        $upriPicAddObj = UpdateCls::UpdateModelImagesInfo($reObj->data['model']['updateForAdd']);
        $apriPicObj = UpdateCls::AddModelImagesInfo($reObj->data['model']['addImagesData']);

        $setNotShowObj = UpdateCls::UpdateModelImagesInfo($reObj->data['model']['setNotShowData']);
        UpdateCls::UpdateModelImagesModelId();
        $res = ExpandTp::batch_update("model_product",$reObj->data['model']['updateForModelupdateDateforSort'],"modelNum",true);
        $updateData = myResponse::ResponseDataTrueString("同步成功",
            array("updatePic"=>$upriPicObj->message, "addPic"=>$apriPicObj->message,"setNotShowObj"=>$setNotShowObj->message,
                "upriPicAddObj"=>$upriPicAddObj->message,"startTime"=>$sdate,"endTime"=>$edate,"res"=>$res));

        $data['updateData'] = $updateData;
        $data['startTime'] = $sdate;
        $data['endTime'] = $edate;
        $data['ishaveUpdate'] = count($reObj->data['model']['updateImagesData'])>0 || count($reObj->data['model']['addImagesData'])>0?1:0;
        $data['type'] = '自动图片数据';
        M('update_model_log')->add($data);
        echo $updateData;
    }
    public function UpdateModelImagesDataByStringList()
    {
        $list = I("post.list");
        //$list = "A36670-40,A39191";//,ASY0268,C04028,C04056,C04093,C04106,E454,FY0176-15
        if(!empty($list))
        {
            $arrList = explode(',',$list);
            $reObj = UpdateCls::UpdateModelImagesDataByModels($arrList);
            $upriPicObj = UpdateCls::UpdateModelImagesInfo($reObj->data['model']['updateImagesData']);
            $upriPicAddObj = UpdateCls::UpdateModelImagesInfo($reObj->data['model']['updateForAdd']);
            $apriPicObj = UpdateCls::AddModelImagesInfo($reObj->data['model']['addImagesData']);

            $setNotShowObj = UpdateCls::UpdateModelImagesInfo($reObj->data['model']['setNotShowData']);
            UpdateCls::UpdateModelImagesModelId();
            $res = ExpandTp::batch_update("model_product",$reObj->data['model']['updateForModelupdateDateforSort'],"modelNum",true);
            $updateData = myResponse::ResponseDataTrueString("同步成功",
                array("updatePic"=>$upriPicObj->message, "addPic"=>$apriPicObj->message,"setNotShowObj"=>$setNotShowObj->message,
                    "upriPicAddObj"=>$upriPicAddObj->message,"startTime"=>$sdate,"endTime"=>$edate,"res"=>$res));

            $data['updateData'] = $updateData;
            $data['startTime'] = $sdate;
            $data['endTime'] = $edate;
            $data['ishaveUpdate'] = (count($reObj->data['model']['updateImagesData'])>0
            || $res>0
            || count($reObj->data['model']['updateForAdd'])>0
            || count($reObj->data['model']['addImagesData'])>0
            || count($reObj->data['model']['setNotShowData'])>0)?1:0;
            $data['type'] = '手动更新图片数据';
            M('update_model_log')->add($data);
            echo $updateData;
        }
    }
    public function UpdateModelCustomByDate()
    {
        $sdate = I("get.sd");
        $edate = I("get.ed");
        UpdateController::UpdateModelCustomBase($sdate,$edate);
    }
    public function UpdateModelCustomBase($sdate,$edate)
    {
        if(!FunctionCode::isDate($sdate) || !FunctionCode::isDate($edate) || strtotime($sdate)>strtotime($edate))
        {
            echo myResponse::ResponseDataFalseString('传递参数错误');
            return;
        }
        $data = UpdateCls::UpdateModelCustomData(str_replace(' ','%20',$sdate),str_replace(' ','%20',$edate));
        $mes = "";
        if(!empty($data->data['deleteData']) && count($data->data['deleteData'])>0) {
            $re = UpdateCls::DeleteCustomData($data->data['deleteData']);
            $mes = "属性产品删除了：".$re."记录";
        }
        if(!empty($data->data['updateCategoryData']["updateData"]) && count($data->data['updateCategoryData']["updateData"])>0)
        {
            $rescateupdate = ExpandTp::batch_update("model_category",$data->data['updateCategoryData']["updateData"],"id",true);
            $mes = "属性类型更新了：".$rescateupdate."记录";
            //echo "updateCategoryData->updateData";
        }
        if(!empty($data->data['updateCategoryData']["addData"]) && count($data->data['updateCategoryData']["addData"])>0)
        {
            //echo "updateCategoryData->addData";
            M('model_category')->addAll($data->data['updateCategoryData']["addData"]);
            $mes = $mes.";属性类型增加了：".count($data->data['updateCategoryData']["addData"])."记录";
        }
        if(!empty($data->data['updateData']['updateData']) && count($data->data['updateData']['updateData'])>0)
        {
            $resupdate = ExpandTp::batch_update("model_product_category",$data->data['updateData']['updateData'],"id",true);
            $mes = $mes.";属性产品更新了：". count($data->data['updateData']['updateData'])."记录";
            //echo "updateData->updateData";
        }
        if(!empty($data->data['updateData']['addData']) && count($data->data['updateData']['addData'])>0)
        {
            M('model_product_category')->addAll($data->data['updateData']['addData']);
            $mes = $mes.";属性产品增加了：". count($data->data['updateData']['addData'])."记录";
        }
        $datalog['updateData'] = myResponse::ResponseDataTrueDataString($mes);
        $datalog['startTime'] = $sdate;
        $datalog['endTime'] = $edate;
        $datalog['ishaveUpdate'] = empty($mes)?0:1;
        $datalog['type'] = '更新属性产品';
        M('update_model_log')->add($datalog);
        echo myResponse::ResponseDataTrueString("已经更新",$mes);
    }
}