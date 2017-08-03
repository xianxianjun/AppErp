<?php
namespace Common\Common\Api\flow;
require_once 'Model/PaymentCls/FLOW.MODEL.PAYMENT.INC.php';
class PaymentCls
{
    use PaymentPage,PaymentSql;
}