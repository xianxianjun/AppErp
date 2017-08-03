<?php
namespace Admin\Behaviors;
use Think\Controller;
use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
class AdminPurviewBehavior extends \Think\Behavior
{
    public function run(&$param)
    {
        //echo CONTROLLER_NAME . "/" . ACTION_NAME . "/";
        if(MODULE_NAME == "Admin" && ACTION_NAME != 'login') {
            if(session("adminKey") == C("adminPassword"))
            {
                return;
            }
            if(ACTION_NAME != "loginDo") {
                if (preg_match('/^[a-zA-Z]+.*Do$/', ACTION_NAME)) {
                    session("adminKey", null);
                    echo myResponse::ResponseDataNoLoginString();
                } else {
                    header("Location: /admin/index/login");
                }
            }
        }
    }
}