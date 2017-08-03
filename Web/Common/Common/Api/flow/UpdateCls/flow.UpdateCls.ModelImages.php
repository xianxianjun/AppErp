<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/2/20
 * Time: 9:13
 */
namespace Common\Common\Api\flow;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ConvertType;
use Common\Common\PublicCode\WithSql;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\PublicCode\ExpandTp;
trait UpdateModelImages
{
    public static function UpdateModelImagesData($startDate,$endDate)
    {
        $edata = ErpPublicCode::GetUrlObj("GetUpdateModelImagesData","update",array("sd"=>$startDate,"ed"=>$endDate));
        if(!empty($edata) && $edata->error == 0)
        {
            $reModelImages = UpdateCls::ChangeModelImagesData($edata->data->modelImages);
            return myResponse::ResponseDataTrueDataObj(array("model"=>$reModelImages->data));
        }
        else
        {
            if($edata->error == 0) {
                return myResponse::ResponseDataTrueObj("没有更新任何数据");
            }
            else
            {
                return myResponse::ResponseDataFalseObj("获取数据出错");
            }
        }
    }
    public static function UpdateModelImagesDataByModels($Arrlist)
    {
        $data = array("models"=>$Arrlist);
        $edata = ErpPublicCode::PostUrlForObj($data,"GetUpdateModelImagesDataByModels","update","GetUpdateModelRlatedDataByModelNumsEntity","entity");
        if(!empty($edata) && $edata->error == 0)
        {
            $reModelImages = UpdateCls::ChangeModelImagesData($edata->data->modelImages);
            return myResponse::ResponseDataTrueDataObj(array("model"=>$reModelImages->data));
        }
        else
        {
            if($edata->error == 0) {
                return myResponse::ResponseDataTrueObj("没有更新任何数据");
            }
            else
            {
                return myResponse::ResponseDataFalseObj("获取数据出错");
            }
        }
    }
    public static function UpdateModelImagesModelId()
    {
        $sql = "update app_model_puroduct_file INNER JOIN app_model_product ON app_model_puroduct_file.modelNum = app_model_product.modelNum
                  set app_model_puroduct_file.model_product_id=app_model_product.id where
                   (IFNULL(app_model_puroduct_file.model_product_id,'')='' or app_model_puroduct_file.model_product_id=0)
                   and isShow>0";
        $Model = new \Think\Model();
        $Model->execute($sql);
    }
    public static function ChangeModelImagesData($data)
    {
        $addData = array();
        $updateData = array();
        $updateForAdd = array();
        $updateForModelupdateDateforSort = array();
        if(!empty($data) && count($data)>0)
        {
            $idStr = "";
            $modelStr = "";
            foreach ($data as $value) {
                if (!empty($value->erpId)) {
                    $idStr = empty($idStr) ? "'".$value->erpId."'"  : $idStr . ",'" . $value->erpId."'" ;
                    $modelStr = empty($modelStr) ? "'".trim($value->modelNum)."'"  : $modelStr . ",'" . trim($value->modelNum)."'" ;
                }
            }
            if(!empty($idStr) && !empty($modelStr)) {
                $setNotShowData = M("model_puroduct_file")->field("id,0 as isShow")->where("isShow>0 and modelNum in (" . $modelStr . ")")->select();
                $tupdateData = M("model_puroduct_file")->field("id,modelNum,erpId")->where("isShow>0 and erpId in (" . $idStr . ")")->select();
                foreach ($data as $value) {
                    $n = FunctionCode::FindEqArrReN($tupdateData, "erpId", $value->erpId);
                    $valueTmp = null;
                    if(!empty($value->modelNum) && !empty($value->updateDate)) {
                        $updateForModelupdateDateforSort[] = array("modelNum" => $value->modelNum, "updateDateforSort"=>$value->updateDate);
                    }
                    if ($n >= 0) {
                        /*$valueTmp->id = $tupdateData[$n]["id"];
                        $valueTmp->updateDate = FunctionCode::GetNowTimeDate();
                        if($tupdateData[$n]["modelNum"] != trim($value->modelNum))
                        {
                            $valueTmp->modelNum = trim($value->modelNum);
                            $valueTmp->model_product_id = null;
                        }
                        $valueTmp->file = $value->file;
                        $valueTmp->file1 = $value->file1;
                        $valueTmp->file2 = $value->file2;
                        $valueTmp->sort = $value->sort;
                        $valueTmp->erpId = $value->erpId;
                        $valueTmp->isShow = 3;*/
                        $valueTmp['id'] = $tupdateData[$n]["id"];
                        $valueTmp['updateDate'] = FunctionCode::GetNowTimeDate();
                        if($tupdateData[$n]["modelNum"] != trim($value->modelNum))
                        {
                            $valueTmp['modelNum'] = trim($value->modelNum);
                            $valueTmp['model_product_id'] = null;
                        }

                        $valueTmp['file'] = $value->file;
                        $valueTmp['file1'] = $value->file1;
                        $valueTmp['file2'] = $value->file2;
                        $valueTmp['sort'] = $value->sort;
                        $valueTmp['erpId'] = $value->erpId;
                        $valueTmp['isShow'] = 3;
                        $valueTmp['`group`'] = 'productPic';
                        $updateData[] = $valueTmp;
                        $ni = FunctionCode::FindEqArrReN($setNotShowData, "id", $valueTmp['id']);
                        if($ni>=0)
                        {
                            array_splice($setNotShowData,$ni,1);
                        }
                    }
                    else
                    {
                        /*$valueTmp->createDate = FunctionCode::GetNowTimeDate();
                        $valueTmp->modelNum = $value->modelNum;
                        $valueTmp->file = $value->file;
                        $valueTmp->file1 = $value->file1;
                        $valueTmp->file2 = $value->file2;
                        $valueTmp->sort = $value->sort;
                        $valueTmp->erpId = $value->erpId;
                        $valueTmp->isShow = 2;
                        $valueTmp->group = 'productPic';*/
                        $valueTmp['createDate'] = FunctionCode::GetNowTimeDate();
                        $valueTmp['modelNum'] = $value->modelNum;
                        $valueTmp['file'] = $value->file;
                        $valueTmp['file1'] = $value->file1;
                        $valueTmp['file2'] = $value->file2;
                        $valueTmp['sort'] = $value->sort;
                        $valueTmp['erpId'] = $value->erpId;
                        $valueTmp['isShow'] = 2;
                        $valueTmp['group'] = 'productPic';
                        $addData[] = $valueTmp;
                    }
                }
                if(count($addData)>0)
                {
                    $addn = count($addData);
                    $loseData = M("model_puroduct_file")->where("isShow=0")->limit($addn)->select();
                    $nn = 0;
                    foreach($loseData as $value)
                    {
                        $valueTmp = null;
                        /*$valueTmp->id = $value["id"];
                        $valueTmp->model_product_id = null;
                        $valueTmp->updateDate = FunctionCode::GetNowTimeDate();
                        $valueTmp->modelNum = $addData[$nn]->modelNum;
                        $valueTmp->file = $addData[$nn]->file;
                        $valueTmp->file1 = $addData[$nn]->file1;
                        $valueTmp->file2 = $addData[$nn]->file2;
                        $valueTmp->sort = $addData[$nn]->sort;
                        $valueTmp->erpId = $addData[$nn]->erpId;
                        $valueTmp->isShow = 4;
                        $value->group = 'productPic';*/
                        $valueTmp['id'] = $value["id"];
                        $valueTmp['model_product_id'] = null;
                        $valueTmp['updateDate'] = FunctionCode::GetNowTimeDate();
                        $valueTmp['modelNum'] = $addData[$nn]['modelNum'];
                        $valueTmp['file'] = $addData[$nn]['file'];
                        $valueTmp['file1'] = $addData[$nn]['file1'];
                        $valueTmp['file2'] = $addData[$nn]['file2'];
                        $valueTmp['sort'] = $addData[$nn]['sort'];
                        $valueTmp['erpId'] = $addData[$nn]['erpId'];
                        $valueTmp['isShow'] = 4;
                        $valueTmp['`group`'] = 'productPic';
                        $updateForAdd[] = $valueTmp;
                        $nn++;
                    }
                    if($nn>0)
                    {
                        array_splice($addData,0,$nn);
                    }
                }
            }
        }
        return myResponse::ResponseDataTrueDataObj(array("updateImagesData" => $updateData, "addImagesData" => $addData,"setNotShowData"=>$setNotShowData,
            "updateForAdd"=>$updateForAdd,"updateForModelupdateDateforSort"=>$updateForModelupdateDateforSort));
    }

    public static function UpdateModelImagesInfo($data)
    {
        if(empty($data) && count($data)<=0)
        {
            return myResponse::ResponseDataTrueObj("没有任何更新款号数据");
        }
        $res = ExpandTp::batch_update("model_puroduct_file",$data,"id");
        if(!$res)
        {
            return myResponse::ResponseDataFalseObj("更新失败");
        }
        else
        {
            $valuestr = "";
            foreach($data as $value)
            {
                $valuestr = FunctionCode::ConnectStrForComm($valuestr,empty($value['modelNum'])?$value['id']:$value['modelNum'],",");
            }
            return myResponse::ResponseDataTrueObj("更新了：".$res."条款号记录,id或款号是".$valuestr);
        }
    }
    public static function AddModelImagesInfo($data)
    {
        if(empty($data) && count($data)<=0)
        {
            return myResponse::ResponseDataTrueObj("没有任何添加的款号数据");
        }
        $res = M('model_puroduct_file')->addAll($data);
        if(!$res)
        {
            return myResponse::ResponseDataFalseObj("更新失败");
        }
        else
        {
            $addDataed = M("model_puroduct_file")->field("modelNum")->where(array("id"=>array("egt",$res)))->select();
            $valuestr = "";
            foreach($addDataed as $value)
            {
                $valuestr = FunctionCode::ConnectStrForComm($valuestr,$value["modelNum"],",");
            }
            return myResponse::ResponseDataTrueObj("新增了：".count($addDataed)."条图片记录,款号是".$valuestr);
        }
    }
}