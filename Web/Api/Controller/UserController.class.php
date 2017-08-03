<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/22
 * Time: 9:57
 */
namespace Api\Controller;

use Common\Common\Api\flow\GoodsCls;
use Think\Controller;
use Common\Common\PublicCode\HttpRequest;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\Code\myResponse;
require_once 'User/CONTROLLER.USER.INC.php';

class UserController extends Controller
{
    use UserClsDo, UserPage;

    public function test()
    {
        $spath = 'E:\DWWEB\img\Legal\image002.jpg';
        $data = array (
            'attachment' => '@'.$spath
        );
        echo HttpRequest::PostUrlJson('userModifyHeadPicDo?tokenKey=3eb10b0e86cfc945fcc2eea8936cbe7c',$data);
    }
    public function SetTest()
    {
        //session('myvalue',I('get.value'));
        S('myvalue',I('get.value'),100);
        echo 'ok';
    }
    public function SetNULL()
    {
        //session('myvalue',I('get.value'));
        S('myvalue',null);
        echo 'ok';
    }
    public function getTest()
    {
        //echo session('myvalue');
        echo S('myvalue');
    }
    public function getDefultAddress()
    {
        $myKey = UserCls::GetRequestTokenKey();
        if (!UserCls::IsAnyUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }
        echo myResponse::ToResponseJsonString(UserCls::getDefultAddress($myKey));
    }
    public function getGoodsL1L2CategoryList()
    {
        echo myResponse::ToResponseJsonString(GoodsCls::getGoodsL1L2CategoryList());
    }

}