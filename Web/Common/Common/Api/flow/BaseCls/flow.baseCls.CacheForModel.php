<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/10/14
 * Time: 9:32
 */
namespace Common\Common\Api\flow;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\PublicCode\FunctionCode;

trait CacheForModel
{
    public static function CacheModelCategory()
    {
        return M('model_category aa')->field("id,name as title,erpTypeId")->where("exists (select * from app_model_category a where aa.model_category_id = a.id and sign='mode')")->cache(true,self::$CACHE_TIME)->select();
    }
    public static function CacheModelCustom()
    {
        return M('model_category aa')->field("id,name as title")->where("exists (select * from app_model_category a where aa.model_category_id = a.id and sign='custom')")->cache(true,self::$CACHE_TIME)->select();
    }
    public static function CacheStoneCategory()
    {
        //return M('stone_category')->field("id,name as title")->cache(true,self::$CACHE_TIME)->select();
        $obj = BaseCls::CacheModelAttributeBaseInfo();
        return $obj->stoneCategorys;
    }
    public static function CacheStoneSpec()
    {
        return M('stone_spec')->field("id,if(up=0,down,CONCAT(down,'-',up)) as title,down,up")->cache(true,self::$CACHE_TIME)->select();
    }
    public static function CacheStoneShape()
    {
        //return M('stone_shape')->field("id,name as title")->cache(true,self::$CACHE_TIME)->select();
        $obj = BaseCls::CacheModelAttributeBaseInfo();
        return $obj->stoneShapes;
    }
    public static function CacheModelWeightPice()
    {

    }
    public static function  CacheModelProportionToWax()
    {

    }
    public static function CacheModelPurityForPrice()
    {
        $obj = BaseCls::CacheModelAttributeBaseInfo();
        return $obj->modelPurityForPrices;
    }
    public static function CacheStonePurity()
    {
        return M('stone_purity')->field("id,name as title")->cache(true,self::$CACHE_TIME)->select();
    }
    public static function CacheStoneColor()
    {
        return M('stone_color')->field("id,name as title")->cache(true,self::$CACHE_TIME)->select();
    }

    public static function CacheModelAttributeBaseInfo($isforce = false)
    {
        $obj = S('CacheModelAttributeBaseInfo');
        if($isforce || empty($obj)
            || !isset($obj->modelPuritys)
            || !isset($obj->modelQualitys)
            || !isset($obj->stoneShapes)
            || !isset($obj->stoneCategorys)
            || !isset($obj->modelPurityForPrices)
            ) {

            $obj = ErpPublicCode::GetUrlObj("GetModelAttributeBaseInfo", "model", array());
            if (!empty($obj->data)) {
                foreach($obj->data->stoneShapes as $item)
                {
                    $itemIdstr = sprintf("%03d", $item->id);
                    $item->pic = C('BaseUrl').'images/stoneShape/'.$itemIdstr.'.png';
                    $item->pic1 = C('BaseUrl').'images/stoneShape/'.$itemIdstr.$itemIdstr.'.png';
                }
                S('CacheModelAttributeBaseInfo', $obj->data, 3600);
            }
            return $obj->data;
        }
        return $obj;
    }
    public static function ClearCacheModelAttributeBaseInfo()
    {
        S('CacheModelAttributeBaseInfo',null);
    }
    public static function CacheModelPurity()
    {
        //return M('model_purity')->field("id,name as title")->cache(true,self::$CACHE_TIME)->select();
        $obj = BaseCls::CacheModelAttributeBaseInfo();
        return $obj->modelPuritys;
    }
    public static function CacheModelQuality()
    {
        //return M('model_quality')->field("id,name as title")->cache(true,self::$CACHE_TIME)->select();
        $obj = BaseCls::CacheModelAttributeBaseInfo();
        return $obj->modelQulity;
    }
    public static function CacheModelOrderStatus()
    {
        $data = S('CacheModelAttributeBaseInfoForModelOrderStatus');
        if(empty($data)) {
            $data = ErpPublicCode::GetUrlObjData("GetModelAttributeBaseInfoFormodelOrderStatus", "model");
            if (!empty($data)) {
                S('CacheModelAttributeBaseInfoForModelOrderStatus', $data, 3600);
            }
            return $data;
        }
        return $data;
    }
}