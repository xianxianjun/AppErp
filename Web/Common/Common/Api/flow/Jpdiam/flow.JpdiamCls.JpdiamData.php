<?php
namespace Common\Common\Api\flow;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ErpPublicCode;
trait JpdiamData
{
    public static function GetWhiteStone()
    {
        $obj = jpdiamCls::JpdiamPostForObjData("vipquerystocklist",array("s_protype"=>0));
        return $obj;
    }
}