<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/3/14
 * Time: 11:59
 */
namespace Common\Common\Api\flow;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ConvertType;
use Common\Common\PublicCode\WithSql;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\PublicCode\ExpandTp;
trait UpdateClsCustom
{
    public static function UpdateModelCustomData($startDate,$endDate)
    {
        $edata = ErpPublicCode::GetUrlObj("GetUpdateModelCustomInfo","update",array("sd"=>$startDate,"ed"=>$endDate));
        if(!empty($edata) && $edata->error == 0)
        {
            $updateData = UpdateCls::UpdateModelChangeCustomData($edata->data->customModelList);
            $updateCategoryData = UpdateCls::UpdateModelChangeCustomCategoryData($edata->data->customCategory);
            return myResponse::ResponseDataTrueDataObj(array("updateData"=>$updateData,"updateCategoryData"=>$updateCategoryData
                ,"deleteData"=>$edata->data->customModelListForDelete));
        }
        else
        {
            if($edata->error == 0) {
                return myResponse::ResponseDataTrueObj("没有更新任何数据");
            }
            else
            {
                return myResponse::ResponseDataFalseObj("没有更新任何数据");
            }
        }
    }
    public static function DeleteCustomData($data)
    {
        $deleteArr = null;
        $modelStr = "";
        foreach($data as $value)
        {
            $id = $value->id;
            $title = $value->title;
            if(!empty($id) && !empty($title)) {
                $deleteArr->$id = empty($deleteArr->$id) ? "'" . $title . "'" : $deleteArr->$id . ",'" . $title . "'";
                $modelStr = empty($modelStr)?"'" . $title . "'" : $modelStr . ",'" . $title . "'";
            }
        }
        $customId = M("model_category")->where("sign='custom'")->limit(1)->getField("id");
        if(!empty($modelStr) && !empty($customId)) {
            $where = "";
            foreach ($deleteArr as $key => $value) {
                $where = $where.(empty($where) ? "":" or ")." (
                model_category_id in (select id from app_model_category a where erpTypeId=" . $key . " and model_category_id=".$customId.")
                and model_product_id in (select id from app_model_product where modelNum in (".$value.")))";
            }
        }
        $re = 0;
        if(!empty($where)) {
            $Model = new \Think\Model();
            $re = $Model->execute('delete from app_model_product_category where '.$where);
        }
        return $re;
    }
    public static function UpdateModelChangeCustomCategoryData($data)
    {
        $categoryCustomData = M("model_category a")->field("id,name,erpTypeId")->where("exists
            (select * from app_model_category where sign='custom' and a.model_category_id=id)")->select();
        $customId = M("model_category")->where("sign='custom'")->limit(1)->getField("id");
        if(empty($customId))
        {
            return array();
        }
        $addData = array();
        $upData = array();
        $upobj = null;
        $newDate = FunctionCode::GetNowTimeDate();
        foreach ($data as $value) {
            $item = FunctionCode::FindEqArr($categoryCustomData, "erpTypeId", $value->id);
            if(!empty($item))
            {
                if($item["name"] != $value->title) {
                    $upobj['id'] = $item["id"];
                    $upobj['name'] = $item["name"];
                    $upobj['updateDate'] = $newDate;
                    $upData[] = $upobj;
                }
            }
            else
            {
                $upobj->erpTypeId = $value->id;
                $upobj->name = $item["name"];
                $upobj->model_category_id = $customId;
                $addData[] = $upobj;
            }
        }
        return array("updateData"=>$upData,"addData"=>$addData);
    }
    public static function UpdateModelChangeCustomData($data)
    {
        $modelStr = "";
        foreach ($data as $value) {
            $modelStr = empty($modelStr)?"'".$value->title."'":$modelStr.",'".$value->title."'";
        }
        if(!empty($modelStr)) {
            $updateData = M('model_product_category a,app_model_product b')->field('a.id,a.model_product_id,a.model_category_id,b.modelNum')
                ->where('b.modelNum in (' . $modelStr
                    . ') and exists (select * from app_model_category aa where a.model_category_id=id and exists
                 (select * from app_model_category where sign=\'custom\' and aa.model_category_id=id)) and a.model_product_id=b.id')
                ->select();
            $categoryCustomData = M("model_category a")->field("id,erpTypeId")->where("exists
            (select * from app_model_category where sign='custom' and a.model_category_id=id)")->select();
            $addDatatem = array();
            $addData = array();
            $upData = array();
            $newDate = FunctionCode::GetNowTimeDate();
            $upobj = null;
            $modelStr = "";
            if(count($categoryCustomData)>0) {
                foreach ($data as $value) {
                    $modelNum = $value->title;
                    if (!empty($modelNum)) {
                        $item = FunctionCode::FindEqArr($updateData, "modelNum", $value->title);
                        if (empty($item)) {
                            $modelStr = empty($modelStr) ? "'" . $value->title . "'" : $modelStr . ",'" . $value->title . "'";
                            $addDatatem[] = $value;
                        } else {
                            $categoryId = FunctionCode::FindEqArrReField($categoryCustomData, "erpTypeId", "id", $value->id);
                            if (!empty($categoryId)) {
                                $upobj = null;
                                $upobj['id'] = $item["id"];
                                $upobj['model_product_id'] = $item["model_product_id"];
                                $upobj['model_category_id']= $categoryId;
                                $upobj['updateDate'] = $newDate;
                                $upData[] = $upobj;
                            }
                        }
                    }
                }
                if (!empty($modelStr)) {
                    $proData = M('model_product')->field('id,modelNum')->where('modelNum in (' . $modelStr . ')')->select();
                    if (!empty($proData) && count($proData) > 0) {
                        foreach ($addDatatem as $value) {
                            if(!empty($value->title) && !empty($value->id)) {
                                $proId = FunctionCode::FindEqArrReField($categoryCustomData, "modelNum", "id", $value->title);
                                $categoryId = FunctionCode::FindEqArrReField($categoryCustomData, "erpTypeId", "id", $value->id);
                                if (!empty($proId) && !empty($categoryId)) {
                                    $upobj = null;
                                    //$upobj->id = $item["id"];
                                    $upobj->model_product_id = $proId;
                                    $upobj->model_category_id = $categoryId;
                                    $addData[] = $upobj;
                                }
                            }
                        }
                    }
                }
            }
        }
        return array("updateData"=>$upData,"addData"=>$addData,"addstr"=>$modelStr);
    }
}