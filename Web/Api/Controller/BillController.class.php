<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/3/7
 * Time: 15:00
 */
namespace Api\Controller;

use Common\Common\Api\flow\BaseCls;
use Think\Controller;
use Common\Common\PublicCode\HttpRequest;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\Code\myResponse;
require_once 'Bill/CONTROLLER.Bill.INC.php';

class BillController extends Controller
{
    use BillPublic;
}