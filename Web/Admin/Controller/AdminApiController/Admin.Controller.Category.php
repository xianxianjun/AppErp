<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/1/9
 * Time: 14:20
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
trait Category
{
    public function addCategory()
    {
        $this->title = "添加分类";
        $this->display(PartCodeController::DisplayPath("Api/Category/categoryEdit"));
    }
    public function addCategoryDo()
    {
        $name = I("get.name");
        $erpId = I("get.erpId");
        $data = array();
        if(!empty($name))
        {
            $modeid = M("model_category")->where(array("sign"=>"mode"))->getField('id');
            if(!empty($modeid)) {
                $data["name"] = $name;
                $data["group"] = 'filter';

                $data["model_category_id"] = $modeid;
                if (FunctionCode::isInteger($erpId)) {
                    $data["erpTypeId"] = $erpId;
                }
                $re = M("model_category")->add($data);
                if($re)
                {
                    echo myResponse::ResponseDataTrueString("添加分类成功");
                    return;
                }
            }
        }
        echo myResponse::ResponseDataFalseString("请填写分类名称");
    }
    public function deleteCategoryDo()
    {
        $id = I("get.id");
        if(FunctionCode::isInteger($id))
        {
            $re = M("model_category")->where(array("id"=>$id))->delete();
            if($re)
            {
                $re = M("model_product_category")->where(array("model_category_id"=>$id))->delete();
                echo myResponse::ResponseDataTrueString("删除分类成功,并删除产品链接".$re."个!");
                return;
            }
        }
        echo myResponse::ResponseDataFalseString("参数错误");
    }
    public function editCategory()
    {
        $id = I("get.id");
        if(FunctionCode::isInteger($id)) {
            $this->editdata = M("model_category")->where(array("id"=>$id))->select();
            if(count($this->editdata)<=0) return;
        }
        $this->title = "编辑分类";
        $this->display(PartCodeController::DisplayPath("Api/Category/categoryEdit"));
    }
    public function editCategoryDo()
    {
        $id = I("get.id");
        $name = I("get.name");
        $erpId = I("get.erpId");
        if(FunctionCode::isInteger($id) && !empty($name)) {
            $data["name"] = $name;
            $data["erpTypeId"] = $erpId;
            $data["updateDate"] = FunctionCode::GetNowTimeDate();
            $re = M("model_category")->where(array("id"=>$id))->save($data);
            if($re)
            {
                echo myResponse::ResponseDataTrueString("编辑分类成功");
                return;
            }
        }
        echo myResponse::ResponseDataFalseString("参数错误");
    }
    public function categoryListBase($cpage,$sign,$pagePercount=15,$keyword='')
    {
        $keywordStr = "";
        if(empty($keyword)) {
            $keyword = I("get.keyword");
            if(!empty($keyword))
            {
                $keywordStr = " and `name` like '%".$keyword."%'";
            }
        }
        $allCount = M('model_category a')
            ->where("exists (select * from app_model_category where sign='".$sign."' and a.model_category_id=id)". $keywordStr)->count();
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
        $this->data = M('model_category a')
            ->where("exists(select * from app_model_category where sign='".$sign."' and a.model_category_id=id)".$keywordStr)
            ->order("id desc")
            ->limit($up,$pagePercount)->select();
    }
    public function categoryList()
    {
        $cpage = intval(I("get.cpage"));
        self::categoryListBase($cpage,'mode');
        $this->display(PartCodeController::DisplayPath("Api/Category/categoryList"));
    }
    public function nextCategoryList()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage + 1;
        self::categoryListBase($cpage,'mode');
        $this->display(PartCodeController::DisplayPath("Api/Category/categoryList"));
    }
    public function perCategoryList()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage - 1;
        self::categoryListBase($cpage,'mode');
        $this->display(PartCodeController::DisplayPath("Api/Category/categoryList"));
    }
    public function lastCategoryList()
    {
        self::categoryListBase(1000000,'mode');
        $this->display(PartCodeController::DisplayPath("Api/Category/categoryList"));
    }
    public function firstCategoryList()
    {
        self::categoryListBase(1,'mode');
        $this->display(PartCodeController::DisplayPath("Api/Category/categoryList"));
    }

}
trait Attribute
{
    public function addAttribute()
    {
        $this->title = "添加属性";
        $this->display(PartCodeController::DisplayPath("Api/Category/attributeEdit"));
    }
    public function editAttribute()
    {
        $id = I("get.id");
        if(FunctionCode::isInteger($id)) {
            $this->editdata = M("model_category")->where(array("id"=>$id))->select();
            if(count($this->editdata)<=0) return;
        }
        $this->title = "编辑属性";
        $this->display(PartCodeController::DisplayPath("Api/Category/attributeEdit"));
    }
    public function editAttributeDo()
    {
        $id = I("get.id");
        $name = I("get.name");
        if(FunctionCode::isInteger($id) && !empty($name)) {
            $data["name"] = $name;
            $data["updateDate"] = FunctionCode::GetNowTimeDate();
            $re = M("model_category")->where(array("id"=>$id))->save($data);
            if($re)
            {
                echo myResponse::ResponseDataTrueString("编辑属性成功");
                return;
            }
        }
        echo myResponse::ResponseDataFalseString("参数错误");
    }
    public function attributeList()
    {
        $cpage = intval(I("get.cpage"));
        self::categoryListBase($cpage,'custom',10000);
        $this->display(PartCodeController::DisplayPath("Api/Category/attributeList"));
    }
    public function addAttributeDo()
    {
        $name = I("get.name");
        $data = array();
        if(!empty($name))
        {
            $customid = M("model_category")->where(array("sign"=>"custom"))->getField('id');
            if(!empty($customid)) {
                $data["name"] = $name;
                $data["group"] = 'filter';

                $data["model_category_id"] = $customid;
                $re = M("model_category")->add($data);
                if($re)
                {
                    echo myResponse::ResponseDataTrueString("添加属性成功");
                    return;
                }
            }
        }
        echo myResponse::ResponseDataFalseString("请填写属性名称");
    }
    public function deleteAttributeDo()
    {
        $id = I("get.id");
        if(FunctionCode::isInteger($id))
        {
            $re = M("model_category")->where(array("id"=>$id))->delete();
            if($re)
            {
                $re = M("model_product_category")->where(array("model_category_id"=>$id))->delete();
                echo myResponse::ResponseDataTrueString("删除属性成功,并删除产品链接".$re."个!");
                return;
            }
        }
        echo myResponse::ResponseDataFalseString("参数错误");
    }
}