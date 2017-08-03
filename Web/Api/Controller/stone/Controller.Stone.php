<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/4/18
 * Time: 11:20
 */
namespace Api\Controller;
use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\ModelOrderCls;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\Api\flow\StoneCls;
use Common\Common\PublicCode\BllPublic;
use Common\Common\PublicCode\FunctionCode;

trait stoneInfo
{
    public function stoneSearchInfo()
    {
        $data = StoneCls::stoneSearchInfo();
        echo myResponse::ResponseDataTrueDataString($data);
    }
    public function stoneList()
    {
        $certAuth = I('get.certAuth', '');
        $color = I('get.color', '');
        $shape = I('get.shape', '');
        $purity = I('get.purity', '');
        $cut = I('get.cut', '');
        $polishing = I('get.polishing', '');
        $symmetric = I('get.symmetric', '');
        $fluorescence = I('get.fluorescence', '');
        $weight = I('get.weight', '');
        $price = I('get.price', '');
        $cpage = I('get.cpage','');
        $orderby = I('get.orderby');
        //$percent = I('get.percent');
        //$percent = floatval($percent);
        FunctionCode::GetWebParam("stoneList");
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $data = StoneCls::stoneSearchErpDo($cpage, $certAuth, $color, $shape, $purity, $cut, $polishing, $symmetric, $fluorescence
            , $price, $weight, $orderby, $memberId);
        echo myResponse::ResponseDataTrueDataString($data);
    }
    public function stoneListForJphk()
    {
        $certAuth = I('get.certAuth', '');
        $color = I('get.color', '');
        $shape = I('get.shape', '');
        $purity = I('get.purity', '');
        $cut = I('get.cut', '');
        $polishing = I('get.polishing', '');
        $symmetric = I('get.symmetric', '');
        $fluorescence = I('get.fluorescence', '');
        $weight = I('get.weight', '');
        $price = I('get.price', '');
        $cpage = I('get.cpage','');
        $orderby = I('get.orderby');
        //$percent = I('get.percent');
        //$percent = floatval($percent);
        FunctionCode::GetWebParam("stoneList");
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $data = StoneCls::stoneSearchJpdiamDo($cpage, $certAuth, $color, $shape, $purity, $cut, $polishing, $symmetric, $fluorescence
            , $price, $weight, $orderby, $memberId);
        echo myResponse::ResponseDataTrueDataString($data);
    }
    public function stoneOffer()
    {
        $ids = I('get.id', '');
        if(!empty($ids))
        {
            //$percent = I('get.percent');
            //$percent = floatval($percent);
            $myKey = UserCls::GetRequestTokenKey();
            $memberId = UserCls::GetUserId($myKey);
            $data = StoneCls::stoneOffer($ids,$memberId);
            echo myResponse::ResponseDataTrueDataString(array("list"=>$data));
        }
        else
        {
            echo myResponse::ResponseDataFalseString('没有任何id');
        }
    }

}