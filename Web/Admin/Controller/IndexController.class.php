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
class IndexController extends Controller {
    public function index(){
        //$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        //header("Location: /admin/Agent/login");
        //AgentController::verifyOrder();
        $this->display(PartCodeController::DisplayPath("indexPage/index"));
    }
    public function login(){
        $this->display(PartCodeController::DisplayPath("indexPage/login"));
    }
    public function indexTop(){
        $this->display(PartCodeController::DisplayPath("indexPage/indexTop"));
    }

    public function indexLeft(){
        $this->display(PartCodeController::DisplayPath("indexPage/indexLeft"));
    }

    public function indexMain(){
        $this->display(PartCodeController::DisplayPath("indexPage/indexMain"));
    }
    public function showPic()
    {
        $id = I("get.id");
        if(empty($id))
        {
            $pic='/images/forImage/Images/nopic.png';
        }
        else {
            $obj = M("model_product")->field("picb")->where("id=" . $id)->select();
            $pic = $obj[0]["picb"];
            if(empty($pic))
            {
                $pic='/images/forImage/Images/nopic.png';
            }
            else
            {
                $pic = C('PicBasePath').$pic;
            }
        }
        $this->tpic = $pic;
        $this->display(PartCodeController::DisplayPath("public/showPic"));
    }
}