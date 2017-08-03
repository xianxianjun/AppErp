<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/1/9
 * Time: 10:20
 */
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
trait User
{
    public function loginDo(){
        $pwd = I("get.pwd");
        $user = I("get.user");
        if($pwd == C("adminPassword") && $user = "qianxi")
        {
            session("adminKey",C("adminPassword"));
            echo myResponse::ResponseDataTrueString("登录成功");
        }
        else
        {
            session("adminKey",null);
            echo myResponse::ResponseDataFalseString('登录密码或用户名错误');
        }
    }

    public function loginOut()
    {
        session("adminKey",null);
        echo myResponse::ResponseDataTrueString("更新成功");
    }
}