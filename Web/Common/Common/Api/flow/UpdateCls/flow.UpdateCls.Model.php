<?php
namespace Common\Common\Api\flow;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ConvertType;
use Common\Common\PublicCode\WithSql;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\PublicCode\ExpandTp;
trait UpdateModel
{
    public static function UpdateCategory($data)
    {
        if(empty($data) && count($data)<=0)
        {
            return myResponse::ResponseDataTrueObj("没有任何更新的款号类型数据");
        }
        $res = ExpandTp::batch_update("model_category",$data,"id");
        if(!$res)
        {
            return myResponse::ResponseDataFalseObj("更新失败");
        }
        else
        {
            $valuestr = "";
            foreach($data as $value)
            {
                $valuestr = FunctionCode::ConnectStrForComm($valuestr,"[".$value['id'].",".$value['name']."]",",");
            }
            return myResponse::ResponseDataTrueObj("更新了：".$res."条款号类型记录,分别是".$valuestr);
        }
    }
    public static function addCategoryInfo($data)
    {
        if(empty($data) && count($data)<=0)
        {
            return myResponse::ResponseDataTrueObj("没有任何新增的款号数据");
        }
        $adata = FunctionCode::ArrObjToFieldsArr($data,array("createDate","name","erpTypeId"));
        $res = M("model_category")->addAll($adata);
        if(!$res)
        {
            return myResponse::ResponseDataFalseObj("更新失败");
        }
        else
        {
            $addDataed = M("model_category")->field("id,name")->where("id>=$res")->select();
            $valuestr = "";
            foreach($addDataed as $value)
            {
                $valuestr = FunctionCode::ConnectStrForComm($valuestr,"[".$value["id"].",".$value["name"]."]",",");
            }
            return myResponse::ResponseDataTrueObj("新增了：".count($addDataed)."条款号类型记录,分别是".$valuestr);
        }
    }
    public static function UpdateModelInfo($data)
    {
        if(empty($data) && count($data)<=0)
        {
            return myResponse::ResponseDataTrueObj("没有任何更新款号数据");
        }
        $res = ExpandTp::batch_update("model_product",$data,"id");
        if(!$res)
        {
            return myResponse::ResponseDataFalseObj("更新失败");
        }
        else
        {
            $valuestr = "";
            foreach($data as $value)
            {
                $valuestr = FunctionCode::ConnectStrForComm($valuestr,$value['modelNum'],",");
            }
            return myResponse::ResponseDataTrueObj("更新了：".$res."条款号记录,款号是".$valuestr);
        }
    }
    public static function addModelInfo($data)
    {
        if(empty($data) && count($data)<=0)
        {
            return myResponse::ResponseDataTrueObj("没有任何新增的款号数据");
        }
        $res = M("model_product")->addAll($data);
        if(!$res)
        {
            return myResponse::ResponseDataFalseObj("更新失败");
        }
        else
        {
            $addDataed = M("model_product")->field("modelNum")->where(array("id"=>array("egt",$res)))->select();
            $valuestr = "";
            foreach($addDataed as $value)
            {
                $valuestr = FunctionCode::ConnectStrForComm($valuestr,$value["modelNum"],",");
            }
            return myResponse::ResponseDataTrueObj("新增了：".count($addDataed)."条款号记录,款号是".$valuestr);
        }
    }
    public static function UpdatePriorSortData($data)
    {
        $ModelNums = "";
        $ModelNumsStr = "";
        foreach ($data as $value) {
            $pic = trim($value["pic"]);
            if(!empty($pic))
            {
                $ModelNums = empty($ModelNums)?"'".$value["modelNum"]."'":$ModelNums.",'".$value["modelNum"]."'";
                $ModelNumsStr = empty($ModelNumsStr)?$value["modelNum"]:$ModelNumsStr.",".$value["modelNum"];
            }
        }
        if(!empty($ModelNums)){
            $sql = "update app_model_product set priorSort=".UpdateCls::$priorSortForPic." where modelNum in (".$ModelNums.")";
            $model = new \Think\Model();
            $re = $model->execute($sql);
            if(!$re)
            {
                return myResponse::ResponseDataTrueObj("更新图片排序优先级成功",$ModelNumsStr);
            }
        }
        return myResponse::ResponseDataFalseObj("没有可更新图片排序优先级");
    }
    public static function UpdateModelCategoryInfo($data)
    {
        $modelNumStr = "";
        foreach($data as $value)
        {
            if(!empty($value["modelNum"]))
            {
                $modelNumStr = empty($modelNumStr)?"'".$value["modelNum"]."'":$modelNumStr.",'".$value["modelNum"]."'";
            }
        }
        if(empty($modelNumStr))
        {
            return myResponse::ResponseDataTrueDataObj(array("updateDateCategory"=>"没有任何款号类型数据更新"
            ,"addDateCategory"=>"没有任何款号类型数据添加"));
        }
        $model = new \Think\Model();
        $categoryProData = $model->query("select b.id,c.id as model_category_id,a.id as model_product_id,a.modelNum from app_model_product a
        left join app_model_product_category b on a.id=b.model_product_id
				left join (select * from app_model_category aa where EXISTS (select * from app_model_category where sign='mode'
        and aa.model_category_id = id)) c on c.id=b.model_category_id
        where a.modelNum in (".$modelNumStr.")");
        if(empty($categoryProData) && count($categoryProData)>0)
        {
            return myResponse::ResponseDataTrueDataObj(array("updateDateCategory"=>"没有任何款号类型数据更新"
            ,"addDateCategory"=>"没有任何款号类型数据添加"));
        }
        $Category = BaseCls::CacheModelCategory();
        $addData = array();
        $updateData = array();
        $addstr = "";
        $updatestr = "";
        foreach($categoryProData as $value)
        {
            $model_product_id = $value["model_product_id"];
            $modelNum = $value["modelNum"];
            $model_category_id_Old = $value["model_category_id"];
            $id = $value["id"];
            if(!empty($model_product_id) && !empty($modelNum)) {
                $erpTypeId = FunctionCode::FindEqArrReField($data, "modelNum","erpTypeId", $modelNum);//获取ERPid
                if(!empty($erpTypeId)) {
                    $model_category_id_New = FunctionCode::FindEqArrReField($Category, "erpTypeId", "id", $erpTypeId);//获取categoryId
                    if (!empty($model_category_id_New)) {
                        if (empty($model_category_id_Old)) {
                            $addstr = FunctionCode::ConnectStrForComm($addstr,$modelNum,",");
                            $addData[] = array("model_category_id" => $model_category_id_New, "model_product_id" => $model_product_id
                                , "createDate" => FunctionCode::GetNowTimeDate());
                        } else {
                            $updatestr = FunctionCode::ConnectStrForComm($updatestr,$modelNum,",");
                            $updateData[] = array("id"=>$id,"model_category_id" => $model_category_id_New, "model_product_id" => $model_product_id
                            , "updateDate" => FunctionCode::GetNowTimeDate());
                        }
                    }
                }
            }
        }
        $msga = "";
        $msgu = "";
        if(count($addData)>0)
        {
            $areObj = M("model_product_category")->addAll($addData);
            if(!$areObj)
            {
                $msga = "款号类型添加失败";
            }
        }
        else
        {
            $msga = "没有任何款号类型数据更新";
        }
        if(count($updateData)>0)
        {
            $upreObj = ExpandTp::batch_update("model_product_category",$updateData,"id");
            if(!$upreObj)
            {
                $msgu = "款号类型更新失败";
            }
        }
        else
        {
            $msgu = "没有任何款号类型数据添加";
        }
        return myResponse::ResponseDataTrueDataObj(array("updateDateCategory"=>(empty($msgu)?"类型更新了款号：".$updatestr:$msgu)
            ,"addDateCategory"=>(empty($msga)?"类型添加了款号:".$addstr:$msga)));
    }
    public static function UpdateRelatedDataByModels($Arrlist,$isChangeCategory = true)
    {
        $data = array("models"=>$Arrlist);
        $edata = ErpPublicCode::PostUrlForObj($data,"GetUpdateModelRlatedDataByModels","update","GetUpdateModelRlatedDataByModelNumsEntity","entity"
            ,array("isChangeCategory"=>$isChangeCategory?1:0));
        if(!empty($edata) && $edata->error == 0)
        {
            if($isChangeCategory) {
                $reCategory = UpdateModel::ChangeModelCategoryData($edata->data->updateCategoryDataEntitys);
            }
            $reModel = UpdateModel::ChangeModelInfoData($edata->data->updateModelDataEntitys);
            return myResponse::ResponseDataTrueDataObj(array("model"=>$reModel->data,"category"=>$reCategory->data));
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
    public static function UpdateRelatedData($startDate,$endDate)
    {
        $edata = ErpPublicCode::GetUrlObj("GetUpdateModelRlatedData","update",array("sd"=>$startDate,"ed"=>$endDate));
        if(!empty($edata) && $edata->error == 0)
        {
            $reCategory = UpdateModel::ChangeModelCategoryData($edata->data->updateCategoryDataEntitys);
            $reModel = UpdateModel::ChangeModelInfoData($edata->data->updateModelDataEntitys);
            return myResponse::ResponseDataTrueDataObj(array("model"=>$reModel->data,"category"=>$reCategory->data));
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
    public static function ChangeModelCategoryData($data)
    {
        if(count($data)>0) {
            $idStr = "";
            foreach ($data as $value) {
                if (!empty($value->id)) {
                    $idStr = empty($idStr) ? $value->id  : $idStr . "," . $value->id ;
                }
            }
            if(!empty($idStr)) {
                $cateData = M("model_category a,app_model_category b")->field("a.id,a.erpTypeId,a.name")->where("a.erpTypeId in (" . $idStr .
                    ") and b.sign='mode' and a.model_category_id=b.id")->select();
                $addData = array();
                $updateData = array();
                foreach($data as $value)
                {
                    $n = FunctionCode::FindEqArrReN($cateData, "erpTypeId", $value->id);
                    $valueTmp = null;
                    if ($n >= 0) {
                        if($value->title  != $cateData[$n]["name"])
                        {
                            $valueTmp->id = $cateData[$n]["id"];
                            $valueTmp->updateDate = FunctionCode::GetNowTimeDate();
                            $valueTmp->name = $value->title;
                            $updateData[] = $valueTmp;
                        }
                    }
                    else
                    {
                        $valueTmp->createDate = FunctionCode::GetNowTimeDate();
                        $valueTmp->name = $value->title;
                        $valueTmp->erpTypeId = $value->id;
                        $addData[] = $valueTmp;
                    }
                }

                return myResponse::ResponseDataTrueDataObj(array("updateCateData" => $updateData, "addCateData" => $addData));
            }

        }
        return myResponse::ResponseDataTrueObj("没有款号类型更新");
    }
    public static function ChangeModelInfoData($data)
    {
        if(count(data)>0) {
            $modelNumStr = "";
            foreach ($data as $value) {
                if (!empty($value->modelNum)) {
                    $modelNumStr = empty($modelNumStr) ? "'" . $value->modelNum . "'" : $modelNumStr . ",'" . $value->modelNum . "'";
                }
            }
            if (!empty($modelNumStr)) {
                $mproData = M("model_product")->field("id,modelNum")->where("modelNum in (" . $modelNumStr . ")")->select();
                $addData = array();
                $updateData = array();
                $tdate = FunctionCode::GetNowTimeDate();
                foreach ($data as $value) {
                    $value->modelNum = trim($value->modelNum);
                    if (!empty($value->modelNum)) {
                        $n = FunctionCode::FindEqArrReN($mproData, "modelNum", $value->modelNum);
                        if ($n >= 0) {
                            $value->updateDate = $tdate;
                            $value->id = $mproData[$n]["id"];
                            $updateData[] = $value;
                        } else {
                            //$value->createDate = FunctionCode::GetNowTimeDate();
                            $addData[] = $value;
                        }
                    }
                }
            }
            if (count($updateData) > 0 || count($addData) > 0) {
                /*$Model = new \Think\Model();
                $mproCategory = $Model->query();*/

                $updateCategoryData = FunctionCode::ArrObjToFieldsArr($data, array("modelNum", array("TypeID", "erpTypeId")));
                //$addCategoryData = FunctionCode::ArrObjToFieldsArr($addData,array("modelNum",array("TypeID","erpTypeId")));
                $fieldArr = array("modelNum", "name", "pic", "picb", "picm", "price","memo", "weight", "stone_category_id"
                , "stone_category_id_A", "stone_category_id_B", "stone_category_id_C"
                , "stone_spec_value", "stone_spec_value_A", "stone_spec_value_B", "stone_spec_value_C"
                , "stone_shape_id", "stone_shape_id_A", "stone_shape_id_B", "stone_shape_id_C"
                , "stone_number", "stone_number_A", "stone_number_B", "stone_number_C","createDate");
                $updateData = FunctionCode::ArrObjToFieldsArr($updateData, array_merge(array("id", "updateDate"), $fieldArr));
                $addData = FunctionCode::ArrObjToFieldsArr($addData, array_merge($fieldArr));

                return myResponse::ResponseDataTrueDataObj(array("updateData" => $updateData, "addData" => $addData
                , "updateCategoryData" => $updateCategoryData));
            } else {
                return myResponse::ResponseDataTrueObj("没有更新任何数据");
            }
        }
        else
        {
            return myResponse::ResponseDataTrueObj("没有更新任何数据");
        }
    }
}
