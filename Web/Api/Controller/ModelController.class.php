<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/23
 * Time: 14:24
 */
namespace Api\Controller;

use Common\Common\Api\flow\GoodsCls;
use Think\Controller;
require_once 'ModelPart/CONTROLLER.MODELPART.INC.php';

class ModelController extends Controller
{
    use ModelPage,ModelCurrentOrderPage,PaymentPage,ModelCurrentOrderDo;
    use UserModelOrder,UserModelSearchOrder;
}