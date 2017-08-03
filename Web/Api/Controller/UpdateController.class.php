<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/12/29
 * Time: 15:02
 */
namespace Api\Controller;

use Common\Common\Api\flow\GoodsCls;
use Common\Common\Api\flow\UpdateModel;
use Think\Controller;
use Common\Common\PublicCode\HttpRequest;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\Code\myResponse;
require_once 'Update/CONTROLLER.UPDATE.INC.php';

class UpdateController extends Controller
{
    use ErpUpdateModel,UpdateJpdiamData,UpdateCategoryData;
}