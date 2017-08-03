<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/6/23
 * Time: 14:17
 */
namespace Api\Controller;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\BllPublic;
trait IndexPageInc
{
    public function IndexPage()
    {
        $path = "images/ad/20170727/";
        $scrollAd = array(
            array("key"=>"scrollAd_1","pic"=>FunctionCode::GetRootURL().$path."round1.jpg")
        );
        $classAd_1 = array(
            array("key"=>"classAd_1_1","pic"=>FunctionCode::GetRootURL().$path."aa11.jpg"),
            array("key"=>"classAd_1_2","pic"=>FunctionCode::GetRootURL().$path."aa22.jpg"),
            array("key"=>"classAd_1_3","pic"=>FunctionCode::GetRootURL().$path."aa33.jpg"),
            array("key"=>"classAd_1_4","pic"=>FunctionCode::GetRootURL().$path."aa44.jpg")
        );
        $classAd_2 = array(
            array("key"=>"classAd_2_1","pic"=>FunctionCode::GetRootURL().$path."ad1.jpg"),
            array("key"=>"classAd_2_2","pic"=>FunctionCode::GetRootURL().$path."ad2.jpg")
        );
        $data = array("scrollAd"=>$scrollAd,"classAd_1"=>$classAd_1,"classAd_2"=>$classAd_2);
        echo myResponse::ResponseDataTrueDataString($data);
        //echo myResponse::ResponseDataTrueDataString(C('indexPage'));
    }

    public function IndexRoundPic()
    {
        $root = C("BaseUrl") . "images/ad/20170726/";
        $horizontal = array($root . "1.jpg", $root . "2.jpg");
        $vertical = array($root . "b1.jpg", $root . "b2.jpg");
        echo myResponse::ResponseDataTrueDataString(array("horizontal" => $horizontal, "vertical" => $vertical));
        //echo myResponse::ResponseDataTrueDataString(C('indexPage'));
    }

    public function IndexPageForQxzx()
    {
        self::IndexRoundPic();
    }

    public function IndexPageForYoour()
    {
        self::IndexPage();
    }
}