<?php
namespace Api\Controller;

use Common\Common\Api\flow\GoodsCls;
use Think\Controller;
require_once 'Payment/CONTROLLER.PAYMENT.INC.php';

class PaymentController extends Controller
{
    use alipayPayment,Wxpayment;
}