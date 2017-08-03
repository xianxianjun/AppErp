<?php
namespace Api\Controller;

use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\flow\JpdiamCls;
use Think\Controller;
use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\Code\ApiPublicCode;
use Common\Common\PublicCode\HttpRequest;
use Common\Common\PublicCode\ErpPublicCode;

class PublicController extends Controller
{
    public function test()
    {
        /*$spath = 'E:\DWWEB\img\Legal\image002.jpg';
        $data = array (
            'attachment' => '@'.$spath
        );
        echo HttpRequest::PostUrlJson('userModifyHeadPicDo?tokenKey=e961f14258452209196f8972f0ddfe6b',$data);*/
        //$keyword = I("get.keyword");
        //$cpage = I("get.cpage");
        //echo ErpPublicCode::GetUrl("GetCustomerList","customer",array("keyword"=>$keyword,"cpage"=>$cpage));
        //echo ErpPublicCode::GetUrl("GetCustomerListCount","customer",array("keyword"=>$keyword));
        //echo ErpPublicCode::GetUrl("isHaveMultiCustomer","customer",array("keyword"=>$keyword));
        //$id = I("get.id");
        //echo ErpPublicCode::GetUrl("GetCustomerById","customer",array("id"=>$id));
        /*$json = ErpPublicCode::PostBaseJsonUrlParam('https://www.jpdiam.com/plugin/apitool'
            ,array("action"=>"vipquerystocklist","token"=>"d3d09a69-f0ba-4d9e-94bd-8fe8bbb0fa8f","s_protype"=>1));*/
        /*$json = ErpPublicCode::PostBaseJsonUrlParam('https://www.jpdiam.com/plugin/apitool'
            ,array("action"=>"viplogin","vipid"=>"your12","vippsd"=>"123456"),true,true);
        echo $json;*/
        //$json = JpdiamCls::GetWhiteStone();
        /*$obj = ErpPublicCode::GetUrlObj("GetModelAttributeBaseInfo", "model", array());

        foreach($obj->data->modelPuritys as $item)
        {
            $item->pic = C('BaseUrl').'stoneShape/'.$item->id.'.jpg';
        }
        $json = json_encode($obj);*/
        $obj = JpdiamCls::GetWhiteStone();
        $json = json_encode($obj);
        echo $json;
    }
    public function currentVersion()
    {
        $device = I('get.device', '');
        $url = "";
        if($device == 'ios')
        {
            $url = "https://www.pgyer.com/DkCS";
        }
        if($device == 'android')
        {
            $url = C('AndroidUrl');
        }
            echo myResponse::ResponseDataTrueDataString(array("version"=>VERSION,"QxVersion"=>QXVERSION,'url'=>$url,"message"=>"当前版本过低，请立即更新！"));
    }
}



