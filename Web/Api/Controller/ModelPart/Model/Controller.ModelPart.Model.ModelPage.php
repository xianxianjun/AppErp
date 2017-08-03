<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/23
 * Time: 14:26
 */
namespace Api\Controller;

use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\Api\flow\Model;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\flow\ModelCls;
use Common\Common\PublicCode\BllPublic;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\Api\flow\ModelOrderCls;

trait ModelPage
{
    public function modelListPage()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $categoryList = I('get.category', '');
        $customList = I('get.custom', '');
        if(FunctionCode::isInteger($categoryList) && intval($categoryList)==0)
        {
            $categoryList = null;
        }
        $weight = I('get.weight', '');
        $price = I('get.price', '');
        $keyword = I('get.keyword', '');
        $keyword1 = I('get.keyword1', '');
        $cpage = I('get.cpage', '');
        $pageNum = I('get.pageNum', '');

        $keywordStr = FunctionCode::ArrayConnByChar(array($keyword,$keyword1),"|");

        $model = ModelCls::getModelList($memberId,$categoryList,$customList,$keywordStr, $price, $weight, $cpage,$pageNum);
        if(UserCls::IsOrderErpUserForOfficeLogin($myKey) && (empty($model) || count($model)<=0))
        {
            $modelf = ModelCls::getSearChErpModelItem($myKey,$keyword);
            if(!empty($modelf) && count($modelf)>0)
            {
                $model = $modelf;
                $count = count($modelf);
            }
            else {
                $count = 0;
            }
        }
        else {
            $count = ModelCls::getModelListCount($categoryList,$customList, $keywordStr, $price, $weight);
        }

        if (intval($cpage) > 1) {
            echo myResponse::ResponseDataTrueDataString(array("model" => array("modelList" => $model, "list_count" => $count)));
        } else {
            //$cate = ModelCls::getModelL1L2CategoryList();
            $categoryFiler = ModelCls::getSearchCategoryFilterCondition($categoryList,$customList);
            $keyword1Filer = ModelCls::getSearchKeyword1FilterCondition($keyword1);
            $typeFiler = array_merge($categoryFiler,$keyword1Filer);
            //$searchKeyword = array();
            //$keywordInfo = ModelCls::getSearchKeywordCondition($keyword1);
            //if(!empty($keywordInfo)) array_push($searchKeyword,array("keyWord"=>$keywordInfo));
            $weightInfo = ModelCls::getWeightCondition($weight);
            //if(!empty($weightInfo)) array_push($searchKeyword,array("weight"=>$weightInfo));
            $priceInfo = ModelCls::getPriceCondition($price);
            //if(!empty($priceInfo)) array_push($searchKeyword,$priceInfo);
            //$searchKeyword = array("keyWord" => $keywordInfo, "weight" => $weightInfo, "price" => $priceInfo);
            $searchValue = array(array("name"=>"weight","txt"=>$weightInfo,"value"=>$weight), array("name"=>"price","txt"=>$priceInfo,"value"=>$price));
            $cate = ModelCls::getModelL1L2AttributeList($categoryList,$customList,$weight,$price,$keyword1);
            $customList = BaseCls::CacheModelCustom();
            array_unshift($customList,array("id"=>"0","title"=>"全部"));
            $waitOrderCount = ModelOrderCls::GetOrderListCount($memberId);
            echo myResponse::ResponseDataTrueDataString(array("typeList" => $cate,"customList"=>$customList, "model" => array("modelList" => $model, "list_count" => $count
            ,"isShowPrice"=>UserCls::isShowPrice($myKey)?1:0),
                "typeFiler" => $typeFiler, "searchValue" => $searchValue,"waitOrderCount"=>$waitOrderCount));
        }

    }

    public function modelFilerPage()
    {
        /*$myKey = UserCls::GetRequestTokenKey();
        if (!UserCls::IsOrderErpUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/
        echo myResponse::ResponseDataTrueDataString(ModelCls::CategoryFilerPage());
    }

    public function ModelDetailPage()
    {
        /*$myKey = UserCls::GetRequestTokenKey();
        if (!UserCls::IsOrderErpUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/
        $id = I("get.id", "");
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        if (empty($id)) {
            echo myResponse::ResponseDataFalseString('没有id');
        } else {
            $reObj = ModelCls::getModelDetailById($id,$memberId,$myKey);
            echo myResponse::ToResponseJsonString($reObj);
        }
    }

    public function getStonePrice()
    {
        /*$myKey = UserCls::GetRequestTokenKey();
        if (!UserCls::IsOrderErpUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }*/
        $stoneColorId = I("get.colorId", '');
        $stoneCategoryId = I("get.categoryId", '');
        //$stoneSpecId = I("get.specId", '');
        $specValue = I("get.specValue", '');
        $stonePurityId = I("get.purityId", '');
        $stoneSpecId = BllPublic::GetSpecValueId($specValue);//如果填写的规格符合语法就可以查找stoneSpecId
        if(empty($stoneSpecId)) {
            echo myResponse::ResponseDataFalseString("获取石头失败");
        }
        else {
            $ValidateArr = array(
                myValidate::ConnectStr(array($stoneColorId, 1, "缺少石头颜色id")),
                myValidate::ConnectStr(array($stoneCategoryId, 1, "缺少石头类型id")),
                myValidate::ConnectStr(array($stoneSpecId, 1, "缺少石头规格id")),
                myValidate::ConnectStr(array($stonePurityId, 1, "缺少石头纯净度id"))
            );
            $err = myValidate::VlidateIntegerGt($ValidateArr);
            if (!empty($err)) {
                echo myResponse::ResponseDataFalseString("获取石头价格参数不正确," . $err);
                return;
            }

            $price = ModelCls::getStonePrice($stoneColorId, $stoneCategoryId, $stoneSpecId, $stonePurityId);
            if ($price == -1) {
                echo myResponse::ResponseDataFalseString('获取石头价格出错');
            } else {
                echo myResponse::ResponseDataTrueDataString(array("price" => $price . "元/件"));
            }
        }
    }
    public function CheckSpecificationsForm()
    {
        $value = I("get.value", '');
        $value = BllPublic::GetStandardSpec($value);
        if(empty($value))
        {
            echo myResponse::ResponseDataFalseString('规格格式错误');
        }
        else
        {
            echo myResponse::ResponseDataTrueDataString(array("value" => $value));
        }
    }
    public function modelFilerPageDo()
    {
        /*$myKey = UserCls::GetRequestTokenKey();
        if (!UserCls::IsOrderGroupUserLogin($myKey)) {
            echo myResponse::ResponseDataNoLoginString();
            return;
        }
        $categoryList = I('get.categoryList','');
        $type = I('get.type','');
        $categoryFiler = ModelCls::getSearchFilterCondition($categoryList);*/
    }
}