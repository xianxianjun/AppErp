<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/26
 * Time: 16:01
 */
namespace Common\Common\Api\flow;

use Common\Common\PublicCode\WithSql;

trait ModelCategoryFiler
{
    public static function  CategoryFilerPage()
    {
        $cateData = M('model_category');
        $ModeType = $cateData->field('id,name as title')->where(array('group'=>'filter','sign'=>'mode'))->order('sign')->select();
        if(count($ModeType)>0)
        {
            $typelist = $cateData->field("id,name as title,id as  'value','category' as 'groupKey'")->where(array('model_category_id'=>$ModeType[0]['id']))->select();
        }
        $attributeFiler = array();
        $ModelLevel1 = $cateData->field("id,name as title,sort")->where("`group`='filter' and model_category_id=0 and IFNULL(sign,'')!='mode'")->order(array("sort" => "asc","id"=>'desc'))->select();
        $i = 0;
        foreach ($ModelLevel1 as $value) {
            $ModelLevel2 = $cateData->field("id,name as title,id as  'value','category' as 'groupKey'")->where(array('model_category_id' => $value["id"]))->select();
            $attributeFiler[$i++] = array("id"=>$value["id"],"title" => $value["title"],"sort"=>intval($value["sort"]),"groupKey"=>"category", "mulSelect"=>1,"attributeList" => $ModelLevel2);
        }
        $weightArr = ModelCls::$weightCategoryArr;
        $priceArr = ModelCls::$priceCategoryArr;

        array_push($attributeFiler,$weightArr,$priceArr);
        usort($attributeFiler,function($a,$b){
            if ($a["sort"]==$b["sort"]) return 0;
            return ($a["sort"]<$b["sort"])?-1:1;
        });
        return array("typeList"=>$typelist,'typeFiler'=>$attributeFiler);
    }
}