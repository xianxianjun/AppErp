<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/23
 * Time: 11:46
 */
namespace Common\Common\Api\flow;

use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\WithSql;
use Common\Common\Api\flow\BaseCls;
use Common\Common\PublicCode\BllPublic;
use Common\Common\Api\flow\UpdateCls;
use Common\Common\Api\Code\myResponse;

trait ModelCategory
{
    public static function getModelL1L2CategoryList($selectIds)
    {
        $data = array();
        $mode = M('model_category');
        $ModelLevel1 = $mode->field("id,name as title,sign,sort")->where(array('model_category_id' => 0, 'group' => 'filter'))->order(array("sort" => "asc"))->select();
        $i = 0;
        if(empty($selectIds))
        {
            $isSelectCon = ",0 as isSelect ";
        }
        else {
            $isSelectCon = ",if(position(concat('|',id,'|') in '|".$selectIds."|')>0,1,0) as isSelect ";
        }
        foreach ($ModelLevel1 as $value) {
            $ModelLevel2 = $mode->field("id,name as title,id as  'value','category' as 'groupKey'".$isSelectCon)->where(array('model_category_id' => $value["id"]))->select();
            $data[$i++] = array("id"=>$value["id"],"title" => $value["title"],"sign"=>$value["sign"],"sort"=>intval($value["sort"]),"mulSelect"=>1,"groupKey"=>"category", "attributeList" => $ModelLevel2);
        }
        return $data;
    }
    public static function getCategoryListByParent($parentId)
    {
        $data = M('model_category')->where(array('model_category_id'=>$parentId))->select();
        return $data;
    }
    //获取除过滤类型的条件外还有其他值的条件
    public static function getModelL1L2AttributeList($selectIds,$customList,$weight,$price,$keyword)
    {
        $seletIdsand = !empty($selectIds)&&!empty($customList)?$selectIds."|".$customList:$selectIds.$customList;
        $cate = ModelCls::getModelL1L2CategoryList($seletIdsand);
        $n = FunctionCode::FindEqArrReN($cate,'sign','custom');
        if($n>=0)
        {
            $cate[$n]["groupKey"] = 'custom';
            foreach($cate[$n]["attributeList"] as &$value)
            {
                $value["groupKey"] =  'custom';
            }
        }
        $weightArr = ModelCls::$weightCategoryArr;
        if(!empty($weight))
        {
            $n = FunctionCode::FindEqArrReN($weightArr['attributeList'],'value',$weight);
            if($n !=-1)
            {
                $weightArr['attributeList'][$n]["isSelect"] = "1";
            }
        }
        $priceArr = ModelCls::$priceCategoryArr;
        if(!empty($price))
        {
            $n = FunctionCode::FindEqArrReN($priceArr['attributeList'],'value',$price);
            if($n !=-1)
            {
                $priceArr['attributeList'][$n]["isSelect"] = "1";
            }
        }
        $myKeywordArr = ModelCls::$myKeywordArr;
        if(!empty($keyword))
        {
            $keywordArr = explode('|',$keyword);
            foreach($keywordArr as $key)
            {
                $key = str_replace("'","\'",$key);
                if(!empty($key))
                {
                    $n = FunctionCode::FindEqArrReN($myKeywordArr['attributeList'],'value',$key);
                    if($n !=-1)
                    {
                        $myKeywordArr['attributeList'][$n]["isSelect"] = "1";
                    }
                }
            }
        }
        /*$testArr = ModelCls::$testArr;
        if(!empty($weight))
        {
            $value = FunctionCode::FindEqArr($weightArr,'value',$weight);
            if($value!=null)
            {
                $value["isSelect"] = 1;
            }
        }*/

        array_push($cate,$weightArr,$priceArr,$myKeywordArr);
        usort($cate,function($a,$b){
            if ($a["sort"]==$b["sort"]) return 0;
            return ($a["sort"]<$b["sort"])?-1:1;
        });
        return $cate;
    }
}

trait Model
{
    public static function getModeListQry($filter = '',$customList='', $keyWord = '',$price = '',$weight = '',$percent=1)
    {
        $and = '';
        $where = '';
        $categoryfilter = '';
        $customfilter = '';
        $pricefilter = '';
        $weightfilter = '';
        $keyWordSearch = '';

        //类型
        if (!empty($filter)) {
            //类型
            $filterStrIn = WithSql::ForInIntegerOr(array('id','model_category_id'), '|', $filter);
            if(!empty($filterStrIn)) {
                $categoryfilter = $filterStrIn;
            }

            if(!empty($categoryfilter))
            {
                $categoryfilter = " EXISTS ( SELECT * FROM app_model_product_category b WHERE EXISTS (SELECT * FROM app_model_category a WHERE `group`='filter' and ("
                    .$categoryfilter.") and b.model_category_id = id) AND c.id = b.model_product_id)";
                $and = ' and ';
                $where = ' where ';
            }
        }
        //类型custom
        if (!empty($customList)) {
            //类型
            $filterStrIn = WithSql::ForInIntegerOr(array('id','model_category_id'), '|', $customList);
            if(!empty($filterStrIn)) {
                $customfilter = $filterStrIn;
            }

            if(!empty($customfilter))
            {
                $customfilter = " EXISTS ( SELECT * FROM app_model_product_category b WHERE EXISTS (SELECT * FROM app_model_category a WHERE `group`='filter' and ("
                    .$customfilter.") and b.model_category_id = id) AND c.id = b.model_product_id)";
                $customfilter = $and.$customfilter;
                $and = ' and ';
                $where = ' where ';
            }
        }
        //关键字
        if(!empty($keyWord))
        {
            $categoryfilterLike = WithSql::ForLikeOrOrStrings(array('name'),'|',$keyWord);
            $keyWordSearch = WithSql::ForLikeOrOrStrings(array('name','modelNum'),'|',$keyWord);

            if(!empty($categoryfilterLike)) {
                $categoryfilterLike = " EXISTS ( SELECT * FROM app_model_product_category b WHERE EXISTS (SELECT * FROM app_model_category a WHERE `group`='filter' and ("
                    .$categoryfilterLike.") and b.model_category_id = id) AND c.id = b.model_product_id)";
                $where = ' where ';
                $orKw = ' or ';
                $leftKw = '(';
                $rightKw = ')';
            }

            if(!empty($keyWordSearch)) {
                $keyWordSearch = $and.$leftKw.$keyWordSearch.$orKw.$categoryfilterLike.$rightKw;
            }
            if(!empty($keyWordSearch))
            {
                $and = ' and ';
                $where = ' where ';
            }
        }
        //价格
        if(!empty($price)) {
            $pricefilter = WithSql::ForGtLtEqString($price,"price",floatval(1/$percent));
            if(!empty($pricefilter))
            {
                $pricefilter = $and.$pricefilter;
                $and = ' and ';
                $where = ' where ';
            }
        }

        //重量
        if(!empty($weight)) {
            $weightfilter = WithSql::ForGtLtEqString($weight,"weight");
            if(!empty($weightfilter))
            {
                $weightfilter = $and.$weightfilter;
                $and = ' and ';
                $where = ' where ';
            }
        }
        return $where.$categoryfilter.$customfilter.$keyWordSearch.$pricefilter.$weightfilter;
    }
    public static function getModelList($memberId,$filter = '',$customList='', $keyWord = '',$price = '',$weight = '', $cpage = 1,$pageCount=-1)
    {

        if (!(is_numeric($cpage) && is_int($cpage + 0))) {
            $cpage = 1;
        }
        $percent = UserCls::getUserModelAddtion($memberId);
        $sqlTerm = ModelCls::getModeListQry($filter,$customList, $keyWord,$price,$weight,$percent);
        if(intval($pageCount)<=0) {
            $pageCount = 12;//BaseCls::$EACH_PAGE_COUNT;
        }
        if($cpage <= 1) {
            $upnum = 0;
            //$downnum = $pageCount;
        }
        else
        {
            $upnum = ($cpage-1)*$pageCount;
            //$downnum = $cpage*$pageCount - 1;
        }

        $Model = new \Think\Model();
        $modelAddtion = UserCls::getUserModelAddtion($memberId,true);
        $sql = "SELECT id,name as title,ROUND(ifnull(price,0)*".$modelAddtion.",0) as price,".BllPublic::SqlConnPicHttpBasePath().
            ",".BllPublic::SqlConnPicHttpBasePath("picm","picm").",".BllPublic::SqlConnPicHttpBasePath("picb","picb").
            " FROM app_model_product c ".$sqlTerm." order by priorSort desc,updateDateforSort desc,id desc limit ".$upnum.",".$pageCount;
        $data = $Model->query($sql);
        return $data;
    }
    public static function getSearChErpModelItem($myKey,$key)
    {
        if (!empty($key) && strlen($key)>=2) {
            $arrList = explode(',',$key);
            if(!empty($arrList[0]) && strlen($arrList[0])>=2) {
                $arrList = array($arrList[0]);//只查询一个
                $reObj = UpdateCls::UpdateRelatedDataByModels($arrList,false);

                $areObj = UpdateCls::addModelInfo($reObj->data['model']['addData']);
                $apriPicObj = UpdateCls::UpdatePriorSortData($reObj->data['model']['addData']);

                $updateData = myResponse::ResponseDataTrueString("同步成功",
                    array(
                        "updateCategory" => "没有数据更新", "addCategory" => "没有数据更新"
                    , "updateData" => "没有数据更新", "addData" => $areObj->message
                    , "updateModelCategory" => "没有数据更新", "addModelCategory" =>"没有数据更新"
                    , "upriPicObj" => "没有数据更新", "apriPicObj" => $apriPicObj->data));
                $data['updateData'] = $updateData;
                $data['ishaveUpdate'] = count($reObj->data['model']['addData']) > 0 || count($reObj->data['model']['updateData']) > 0
                || count($reObj->data['model']['updateCategoryData']) > 0 ? 1 : 0;
                $data['type'] = '前台查询款号插入数据';
                M('update_model_log')->add($data);
                if (count($reObj->data['model']['addData'])>0) {
                    return ModelCls::getModelList('',$arrList[0],'','','',1);
                    return $data;
                }
            }
            return array();
        }
    }
    public static function getModelListCount($filter = '',$customList='', $keyWord = '',$price = '',$weight = '')
    {
        $sqlTerm = ModelCls::getModeListQry($filter,$customList, $keyWord,$price,$weight);
        $Model = new \Think\Model();
        $sql = 'SELECT count(*) as cou FROM app_model_product c '.$sqlTerm;
        $data = $Model->query($sql);
        return $data[0]['cou'];
    }
    public static function getSearchCategoryFilterCondition($filter,$customList)
    {
        $data = array();
        $data1 = array();
        //$filter = !empty($filter)&&!empty($customList)?$filter.'|'.$customList:$filter.$customList;
        if (!empty($filter)) {
            $filterStrIn = WithSql::ForInIntegerOr(array('id'), '|', $filter);
            if(!empty($filterStrIn)) {
                $Model = new \Think\Model();
                $sql = "select id,name as title,id as 'value','category' as 'groupKey' from app_model_category where ".$filterStrIn;
                $data = $Model->query($sql);
            }
        }
        if (!empty($customList)) {
            $filterStrIn = WithSql::ForInIntegerOr(array('id'), '|', $customList);
            if(!empty($filterStrIn)) {
                $Model = new \Think\Model();
                $sql = "select id,name as title,id as 'value','custom' as 'groupKey' from app_model_category where ".$filterStrIn;
                $data1 = $Model->query($sql);
            }
        }
        return array_merge($data1,$data);
    }
    public static function getSearchKeyword1FilterCondition($filter)
    {
        if (!empty($filter)) {
            $filterArr = explode('|',$filter);
            $myKeywordArr = ModelCls::$myKeywordArr['attributeList'];
            $arr = array();
            $nn = 0;
            foreach($filterArr as $val)
            {
                if(!empty($val)) {
                   $n = FunctionCode::FindEqArrReN($myKeywordArr, "value", $val);
                    if($n!=-1)
                    {
                        $arr[$nn++] = array("id"=>$myKeywordArr[$n]["id"]
                        ,"title"=>$myKeywordArr[$n]["title"]
                        ,"value"=>$myKeywordArr[$n]["value"]
                        ,"groupKey"=>$myKeywordArr[$n]["groupKey"]);
                    }
                }
            }
            return $arr;
        }
        return array();
    }
    public static function getSearchKeywordCondition($keyword)
    {
        $keywordArr = explode('|',$keyword);
        $str = '';
        foreach($keywordArr as $value)
        {
            $value = trim($value);
            if(!empty($value))
            {
                //$value = str_replace("'","\'",$value);
                $str = empty($str)?$value:$str.' '.$value;
            }
        }
        return $str;
    }
    public static function getPriceCondition($price)
    {
        $str = '';
        if(!empty($price))
        {
            $priceArr = explode('|',$price);
            if(count($priceArr)==1 && is_numeric($priceArr[0]))
            {
                $str = '价格（元）='.$priceArr[0];
            }
            else if(count($priceArr)==2)
            {
                if(is_numeric($priceArr[0]) && is_numeric($priceArr[1])) {
                    $str = $priceArr[1] . '>价格（元）>' . $priceArr[0];
                }
                else if(is_numeric($priceArr[0]))
                {
                    $str = '价格（元）>' . $priceArr[0];
                }
                else if(is_numeric($priceArr[1]))
                {
                    $str = '价格（元）<' . $priceArr[1];
                }
            }

        }
        return $str;
    }
    public static function getWeightCondition($weight)
    {
        $str = '';
        if(!empty($weight))
        {
            $weightArr = explode('|',$weight);
            if(count($weightArr)==1 && is_numeric($weightArr[0]))
            {
                $str = '重量（克）='.$weightArr[0];
            }
            else if(count($weightArr)==2)
            {
                if(is_numeric($weightArr[0]) && is_numeric($weightArr[1])) {
                    $str = $weightArr[1] . '>重量（克）>' . $weightArr[0];
                }
                else if(is_numeric($weightArr[0]))
                {
                    $str = '重量（克）>' . $weightArr[0];
                }
                else if(is_numeric($weightArr[1]))
                {
                    $str = '重量（克）<' . $weightArr[1];
                }
            }

        }
        return $str;
    }
}