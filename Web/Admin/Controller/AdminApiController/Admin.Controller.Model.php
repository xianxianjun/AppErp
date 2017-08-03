<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/1/9
 * Time: 10:51
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
trait Model
{
    public function addModel()
    {
        $this->title = "添加款号";
        $this->display(PartCodeController::DisplayPath("Api/Model/modelEdit"));
    }
    public function editModel()
    {
        $this->title = "修改款号";
        $this->display(PartCodeController::DisplayPath("Api/Model/modelEdit"));
    }
    private function modelListBase($cpage,$keyword='')//
    {
        $keywordStr = "";
        if(empty($keyword)) {
            $keyword = I("get.keyword");
            if(!empty($keyword))
            {
                $keywordStr = "modelNum like '%".$keyword."%' or `name` like '%".$keyword."%'";
            }
        }
        $allCount = M('model_product')->where($keywordStr)->count();
        $pagePercount = 20;
        $cpage = $cpage<=0?1:$cpage;
        $countfloat = $allCount/$pagePercount;
        if($countfloat < $cpage)
        {
            $cpage = ceil($countfloat);
        }

        $this->Listcpage = $cpage;
        $this->pageCount = ceil($countfloat);
        $this->reCount = $allCount;
        $this->pageQrt = "?cpage=".$cpage
            .(empty($keyword)?"":"&keyword=".$keyword);
        $up = $pagePercount*($cpage-1)>0?$pagePercount*($cpage-1):0;
        $this->modelData = M('model_product')->where($keywordStr)->order("updateDateforSort desc,id desc")->limit($up,$pagePercount)->select();
        $this->display(PartCodeController::DisplayPath("Api/Model/modelList"));

    }
    public function modelList()
    {
        $cpage = intval(I("get.cpage"));
        self::modelListBase($cpage);
    }
    public function nextModelList()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage + 1;
        self::modelListBase($cpage);
    }
    public function perModelList()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage - 1;
        self::modelListBase($cpage);
    }
    public function lastModelList()
    {
        self::modelListBase(1000000);
    }
    public function firstModelList()
    {
        self::modelListBase(1);
    }
}