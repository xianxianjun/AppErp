<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/22
 * Time: 17:28
 */
namespace Common\Common\Api\flow;
trait Area
{
    public static function getAreaByCityId($cityId)
    {
        $area = M('area')->field('id,name')->where(array('city_id'=>$cityId))->select();
        return $area;
    }

    public static function getAreaById($Id)
    {
        $area = M('area')->field('id,name')->where(array('id'=>$Id))->select();
        return $area[0];
    }

    public static function getCityByProvinceId($provinceId)
    {
        $province = M('city')->field('id,name')->where(array('province_id'=>$provinceId))->select();
        return $province;
    }

    public static function getCityById($id)
    {
        $province = M('city')->field('id,name')->where(array('id'=>$id))->select();
        return $province[0];
    }

    public static function getProvince()
    {
        $province = M('province')->field('id,name')->select();
        return $province;
    }

    public static function getProvinceById($id)
    {
        $province = M('province')->field('id,name')->where(array('id'=>$id))->select();
        return $province[0];
    }
}