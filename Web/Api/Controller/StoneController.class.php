<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/4/18
 * Time: 11:18
 */

namespace Api\Controller;

use Think\Controller;
use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\Code\ApiPublicCode;
use Common\Common\PublicCode\HttpRequest;
use Common\Common\PublicCode\ErpPublicCode;
require_once 'stone/CONTROLLER.STONE.INC.php';
class StoneController extends Controller
{
    use stoneInfo;
    use stoneOrderPage,stoneOrderDo;
}