<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/6/23
 * Time: 14:54
 */
namespace Api\Controller;

trait IndexProxy
{
    public function IndexPage()
    {
        IndexController::IndexPage();
    }
    public function IndexRoundPic()
    {
        IndexController::IndexRoundPic();
    }
    public function IndexPageForQxzx()
    {
        IndexController::IndexPageForQxzx();
    }
    public function IndexPageForYoour()
    {
        IndexController::IndexPageForYoour();
    }
}