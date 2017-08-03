<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/28
 * Time: 14:03
 */
namespace Api\Controller;

trait BaseProxy
{
    public function getCity()
    {
        BaseController::getCity();
    }
    public function getArea()
    {
        BaseController::getArea();
    }
}