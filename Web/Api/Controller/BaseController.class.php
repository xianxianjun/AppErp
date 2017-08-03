<?php
namespace Api\Controller;

use Common\Common\Api\flow\BaseCls;
use Think\Controller;
use Common\Common\PublicCode\HttpRequest;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\Code\myResponse;
require_once 'Base/CONTROLLER.BASE.INC.php';

class BaseController extends Controller
{
    public static function getCity()
    {
        /*$myKey = UserCls::GetRequestTokenKey();
        if (!UserCls::IsOrderErpUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/
        $provinceId = $_GET["id"];
        $data = BaseCls::getCityByProvinceId($provinceId);
        echo myResponse::ResponseDataTrueDataString($data);

    }
    public static function getArea()
    {
        /*$myKey = UserCls::GetRequestTokenKey();
        if (!UserCls::IsOrderErpUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/
        $cityId = $_GET["id"];
        $data = BaseCls::getAreaByCityId($cityId);
        echo myResponse::ResponseDataTrueDataString($data);
    }
}