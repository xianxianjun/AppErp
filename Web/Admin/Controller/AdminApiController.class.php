<?php
namespace Admin\Controller;
use Common\Common\PublicCode\FunctionCode;
use Think\Controller;
use Common\Common\PublicCode\PartCodeController;
use Common\Common\Api\flow\ModelOrderCls;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\PublicCode\ErpPublicCode;
require_once 'AdminApiController/Admin.Controller.INC.php';
class AdminApiController extends Controller {

    use User,Model,Category,Attribute,Order,OrderProduceListPage,OrderWaitVerifyPage,Member,SystemManage;
    use StoneOrderListPage,StoneOrderListDo;
}