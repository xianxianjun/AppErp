<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/10/21
 * Time: 9:30
 */
namespace Api\Controller;

trait ErpCustomerProxy
{
    public function IsHaveCustomer()
    {
        ErpController::IsHaveCustomer();
    }
    public function GetCustomerById()
    {
        ErpController::GetCustomerById();
    }
    public function GetCustomerList()
    {
        ErpController::GetCustomerList();
    }
}