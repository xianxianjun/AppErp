<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/10/13
 * Time: 16:20
 */
namespace Common\Common\Api\flow;

use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\flow\ModelCls;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\Code\ValidateCode;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ConvertType;
use Common\Common\PublicCode\isHaveCustomer;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\PublicCode\BllPublic;

trait ModelCurrentOrderPage
{
    public static function getModelDetailByCurrentItemId($itemId, $memberId,$tokenKey)
    {
        $id = ModelOrderCls::getModelIdByCurrentItemId($itemId);
        if (empty($id)) {
            return myResponse::ResponseDataFalseObj("没有任何id");
        }
        $modeData = M('model_product a')->where(array('id' => $id))->select();
        //$currentOrderItem = M('model_main_order_current_detail')->where(array('id'=>$itemId))->select();
        $Model = new \Think\Model();
        $currentOrderItem = $Model->query(ModelOrderCls::Every_Stone_Price_For_Id_Sql($itemId, $memberId));
        if (count($modeData) > 0 && count($currentOrderItem) > 0) {
            $modeId = $modeData[0]["id"];
            $isSelfStone = $currentOrderItem[0]["isSelfStone"];
            $modelCategoryId = ModelCls::getTop1ModelCategoryId($modeData[0]["id"]);
            $goldenPrice = array(array("title" => "pt", "price" => "355/g"), array("title" => "18k", "price" => "356/g"));

            //$pics = M('model_puroduct_file')->field("id,".BllPublic::SqlConnPicHttpBasePath('file','pic'))->where('model_product_id=' . $modeId . " and `group` like '%_s'")->select();
            $pics =  M('model_puroduct_file')->field("id,".BllPublic::SqlConnPicHttpBasePath('file','pic')
                .",".BllPublic::SqlConnPicHttpBasePath('file1','picm')
                .",".BllPublic::SqlConnPicHttpBasePath('file2','picb'))->where('model_product_id='.$modeId." and isShow>0 and `group`='productPic'")->order("sort")->select();
            if(count($pics)<=0)
            {
                $pics = array(array("id"=>0,"pic"=>BllPublic::GetPicBasePathPic($modeData[0]["pic"])
                ,"picm"=>BllPublic::GetPicBasePathPic($modeData[0]["picm"])
                ,"picb"=>BllPublic::GetPicBasePathPic($modeData[0]["picb"])));
            }
            $modelType = BaseCls::CacheModelCategory();
            $stoneType = BaseCls::CacheStoneCategory();
            $stoneSpec = BaseCls::CacheStoneSpec();
            $stoneShape = BaseCls::CacheStoneShape();
            $stonePurity = BaseCls::CacheStonePurity();
            $stoneColor = BaseCls::CacheStoneColor();

            $remarks = M('model_remarks')->field("id,title,content")->select();

            $stone = array("typeId" => FunctionCode::ReurnArrDefault($currentOrderItem[0]["stone_category_id"], array(-1,0), ""), "typeTitle" => FunctionCode::FindEqObjReField($stoneType, "id", "title", $currentOrderItem[0]["stone_category_id"])
            , "specId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_spec_id"], 0, ""), "specTitle" => $currentOrderItem[0]["stone_spec_value"]//FunctionCode::FindEqArrReField($stoneSpec, "id", "title", $currentOrderItem[0]["stone_spec_id"])
            , "shapeId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_shape_id"], 0, ""), "shapeTitle" => FunctionCode::FindEqObjReField($stoneShape, "id", "title", $currentOrderItem[0]["stone_shape_id"])
            , "specSelectTitle"=>BllPublic::GetSpecErpSelectTitle($modeData[0]["stone_spec_value"])
            , "purityId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_purity_id"], 0, ""), "purityTitle" => FunctionCode::FindEqArrReField($stonePurity, "id", "title", $currentOrderItem[0]["stone_purity_id"])
            , "colorId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_color_id"], 0, ""), "colorTitle" => FunctionCode::FindEqArrReField($stoneColor, "id", "title", $currentOrderItem[0]["stone_color_id"])
            , "number" => FunctionCode::ReurnDefault($currentOrderItem[0]['stone_number'], 0, ""), "price" => $currentOrderItem[0]['sprice']);

            $stoneA = array("typeId" => FunctionCode::ReurnArrDefault($currentOrderItem[0]["stone_category_id_A"], array(-1,0), ""), "typeTitle" => FunctionCode::FindEqObjReField($stoneType, "id", "title", $currentOrderItem[0]["stone_category_id_A"])
            , "specId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_spec_id_A"], 0, ""), "specTitle" => $currentOrderItem[0]["stone_spec_value_A"]//FunctionCode::FindEqArrReField($stoneSpec, "id", "title", $currentOrderItem[0]["stone_spec_id_A"])
            , "shapeId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_shape_id_A"], 0, ""), "shapeTitle" => FunctionCode::FindEqObjReField($stoneShape, "id", "title", $currentOrderItem[0]["stone_shape_id_A"])
            , "specSelectTitle"=>BllPublic::GetSpecErpSelectTitle($modeData[0]["stone_spec_value_A"])
            , "purityId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_purity_id_A"], 0, ""), "purityTitle" => FunctionCode::FindEqArrReField($stonePurity, "id", "title", $currentOrderItem[0]["stone_purity_id_A"])
            , "colorId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_color_id_A"], 0, ""), "colorTitle" => FunctionCode::FindEqArrReField($stoneColor, "id", "title", $currentOrderItem[0]["stone_color_id_A"])
            , "stoneOut"=>ConvertType::ConvertInt($currentOrderItem[0]["stone_out_A"],0)
            , "number" => FunctionCode::ReurnDefault($currentOrderItem[0]['stone_number_A'], 0, ""), "price" => $currentOrderItem[0]['saprice']);

            $stoneB = array("typeId" => FunctionCode::ReurnArrDefault($currentOrderItem[0]["stone_category_id_B"], array(-1,0), ""), "typeTitle" => FunctionCode::FindEqObjReField($stoneType, "id", "title", $currentOrderItem[0]["stone_category_id_B"])
            , "specId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_spec_id_B"], 0, ""), "specTitle" => $currentOrderItem[0]["stone_spec_value_B"]//FunctionCode::FindEqArrReField($stoneSpec, "id", "title", $currentOrderItem[0]["stone_spec_id_B"])
            , "shapeId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_shape_id_B"], 0, ""), "shapeTitle" => FunctionCode::FindEqObjReField($stoneShape, "id", "title", $currentOrderItem[0]["stone_shape_id_B"])
            , "specSelectTitle"=>BllPublic::GetSpecErpSelectTitle($modeData[0]["stone_spec_value_B"])
            , "purityId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_purity_id_B"], 0, ""), "purityTitle" => FunctionCode::FindEqArrReField($stonePurity, "id", "title", $currentOrderItem[0]["stone_purity_id_B"])
            , "colorId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_color_id_B"], 0, ""), "colorTitle" => FunctionCode::FindEqArrReField($stoneColor, "id", "title", $currentOrderItem[0]["stone_color_id_B"])
            , "stoneOut"=>ConvertType::ConvertInt($currentOrderItem[0]["stone_out_B"],0)
            , "number" => FunctionCode::ReurnDefault($currentOrderItem[0]['stone_number_B'], 0, ""), "price" => $currentOrderItem[0]['sbprice']);

            $stoneC = array("typeId" => FunctionCode::ReurnArrDefault($currentOrderItem[0]["stone_category_id_C"], array(-1,0), ""), "typeTitle" => FunctionCode::FindEqObjReField($stoneType, "id", "title", $currentOrderItem[0]["stone_category_id_C"])
            , "specId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_spec_id_C"], 0, ""), "specTitle" => $currentOrderItem[0]["stone_spec_value_C"]//FunctionCode::FindEqArrReField($stoneSpec, "id", "title", $currentOrderItem[0]["stone_spec_id_C"])
            , "shapeId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_shape_id_C"], 0, ""), "shapeTitle" => FunctionCode::FindEqObjReField($stoneShape, "id", "title", $currentOrderItem[0]["stone_shape_id_C"])
            , "specSelectTitle"=>BllPublic::GetSpecErpSelectTitle($modeData[0]["stone_spec_value_C"])
            , "purityId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_purity_id_C"], 0, ""), "purityTitle" => FunctionCode::FindEqArrReField($stonePurity, "id", "title", $currentOrderItem[0]["stone_purity_id_C"])
            , "colorId" => FunctionCode::ReurnDefault($currentOrderItem[0]["stone_color_id_C"], 0, ""), "colorTitle" => FunctionCode::FindEqArrReField($stoneColor, "id", "title", $currentOrderItem[0]["stone_color_id_C"])
            , "stoneOut"=>ConvertType::ConvertInt($currentOrderItem[0]["stone_out_C"],0)
            , "number" => FunctionCode::ReurnDefault($currentOrderItem[0]['stone_number_C'], 0, ""), "price" => $currentOrderItem[0]['scprice']);

            if(!empty($stoneC["typeId"]) || !empty($stoneC["stoneOut"]))
            {
                $stoneC["isNotEmpty"] = 1;
                $stoneB["isNotEmpty"] = 1;
                $stoneA["isNotEmpty"] = 1;
                $stone["isNotEmpty"] = 1;
            }
            else if(!empty($stoneB["typeId"])|| !empty($stoneB["stoneOut"]))
            {
                $stoneC["isNotEmpty"] = 0;
                $stoneB["isNotEmpty"] = 1;
                $stoneA["isNotEmpty"] = 1;
                $stone["isNotEmpty"] = 1;
            }
            else if(!empty($stoneA["typeId"]) || !empty($stoneA["stoneOut"]))
            {
                $stoneC["isNotEmpty"] = 0;
                $stoneB["isNotEmpty"] = 0;
                $stoneA["isNotEmpty"] = 1;
                $stone["isNotEmpty"] = 1;
            }
            else if(!empty($stone["typeId"]) || !empty($isSelfStone))
            {
                $stoneC["isNotEmpty"] = 0;
                $stoneB["isNotEmpty"] = 0;
                $stoneA["isNotEmpty"] = 0;
                $stone["isNotEmpty"] = 1;
            }
            $UserModelAddtion = UserCls::getUserModelAddtion($memberId);
            $totalPrice = floatval($modeData[0]["price"])*$UserModelAddtion+floatval($currentOrderItem[0]['sprice'])
                +floatval($currentOrderItem[0]['saprice'])+floatval($currentOrderItem[0]['sbprice'])+floatval($currentOrderItem[0]['scprice']);
            $jewelStoneItem = M("model_order_jewel_stone_log")->where("model_main_order_current_detail_id=".$currentOrderItem[0]["id"])->select();
            if(count($jewelStoneItem)>0) {
                //$totalPrice = sprintf("%.2f", floatval($totalPrice) + floatval($jewelStoneItem[0]["Price"]));
                $stone["specSelectTitle"] = $jewelStoneItem[0]["Weight"];
            }
            $totalPrice = $totalPrice*floatval($currentOrderItem[0]['number']);
            $mode = array("title" => $modeData[0]["name"],"isSelfStone"=>$isSelfStone
            , "price" => $totalPrice, "handSize" => floatval($currentOrderItem[0]['handSize']), "number" => floatval($currentOrderItem[0]['number'])
            , "remark"=>$currentOrderItem[0]["remarks"], "categoryId" => $modelCategoryId, "categoryTitle" => FunctionCode::FindEqArrReField($modelType, "id", "title", $modelCategoryId)
            , "stone" => $stone, "stoneA" => $stoneA, "stoneB" => $stoneB, "stoneC" => $stoneC, "pics" => $pics);
            $jewelStone = null;
            if(count($jewelStoneItem)>0) {
                $UserStoneAddtion = UserCls::getUserStoneAddtion($memberId);
                $jewelStone = array("jewelStonePrice" => floatval($jewelStoneItem[0]["Price"])*$UserStoneAddtion
                , "jewelStoneId" => $jewelStoneItem[0]["jewel_stone_id"]
                , "jewelStoneWeight" => $jewelStoneItem[0]["Weight"]
                , "jewelStoneCode" => $jewelStoneItem[0]["CertCode"]
                , "jewelStoneColor" => $jewelStoneItem[0]["Color"]
                , "jewelStonePurity" => $jewelStoneItem[0]["Purity"]
                , "jewelStoneShape" => $jewelStoneItem[0]["Shape"]);
            }

            $data = array("goldenPrice" => $goldenPrice, "model" => $mode,"jewelStone"=>$jewelStone, "stoneType" => $stoneType,"handSizeData"=>BllPublic::GethandSizeData(), "stoneSpec" => $stoneSpec, "stoneShape" => $stoneShape, "stoneColor" => $stoneColor, "stonePurity" => $stonePurity, "remarks" => $remarks,"IsCanSelectStone"=>UserCls::GetIsCanSelectStone($tokenKey));
            return myResponse::ResponseDataTrueDataObj($data);
        }
        return myResponse::ResponseDataFalseObj("获取数据出错");
    }

    public static function getModelIdByCurrentItemId($itemId)
    {
        if (FunctionCode::isInteger($itemId)) {
            $cmodel = M("model_main_order_current_detail")->field("model_product_id")->where(array("id" => $itemId))->select();
            if (count($cmodel) > 0) {
                return $cmodel[0]["model_product_id"];
            }
        }
        return null;
    }

    public static function OrderListItem($itemId, $memberId, $purityId, $qualityId)
    {
        $id = self::getModelIdByCurrentItemId($itemId);
        if (empty($id)) {
            return myResponse::ResponseDataFalseObj("没有任何id");
        }
        $modelData = M('model_product a')->where(array('a.id' => $id))->select();
        $currentOrderItem = M('model_main_order_current_detail')->where(array('id' => $itemId, "member_id" => $memberId))->select();
        if (count($modelData) > 0 && count($currentOrderItem) > 0) {
            //$modelType = M('model_category aa')->field("id,name as title")->where("exists (select * from app_model_category a where aa.model_category_id = a.id and sign='mode')")->select();
            //$stoneType = M('stone_category')->field("id,name as title")->select();
            //$stoneSpec = M('stone_spec')->field("id,if(up=0,down,CONCAT(down,'-',up)) as title")->select();
            //$stoneShape = M('stone_shape')->field("id,name as title")->select();
            //$stonePurity = M('stone_purity')->field("id,name as title")->select();
            //$stoneColor = M('stone_color')->field("id,name as title")->select();

            $modelType = BaseCls::CacheModelCategory();
            $stoneType = BaseCls::CacheStoneCategory();
            $stoneSpec = BaseCls::CacheStoneSpec();
            $stoneShape = BaseCls::CacheStoneShape();
            $stonePurity = BaseCls::CacheStonePurity();
            $stoneColor = BaseCls::CacheStoneColor();

            $id = $itemId;
            $modelCategoryId = ModelCls::getTop1ModelCategoryId($id);
            $modelId = $modelData[0]["id"];
            $title = $modelData[0]["name"] . "(" . $modelData[0]["modelNum"] . ")";
            $pic = BllPublic::GetPicBasePath() . $modelData[0]["pic"];
            $weight = (double)$modelData[0]['weight'];
            $baseInfo = "类型:" . FunctionCode::FindEqArrReField($modelType, "id", "title", $modelCategoryId)
                . (empty($currentOrderItem[0]['handSize'])?"":";手寸:" . floatval($currentOrderItem[0]['handSize']));

            $number = $currentOrderItem[0]['number'];
            $Model = new \Think\Model();
            $priceData = $Model->query(ModelOrderCls::Stone_Price_For_Id_Sql($itemId, $memberId));
            $stonePrice = (double)$priceData[0]["stonePrice"];
            $UserModelAddtion = UserCls::getUserModelAddtion($memberId);
            $price = floatval($stonePrice) + floatval($modelData[0]["price"])*$UserModelAddtion;
            $erpTypeId = $priceData[0]["erpTypeId"];
            /*if (!empty($erpTypeId) && !empty($purityId) && !empty($qualityId)) {
                $ErpValuePriceData = ErpPublicCode::GetUrlObjData("GetModelValuePrice", "model", array("typeIds" => $erpTypeId, "purityId" => $purityId, "qualityId" => $qualityId));
                if (!empty($ErpValuePriceData) && count($ErpValuePriceData) > 0) {
                    $lossCostPer = (double)($ErpValuePriceData[0]->lossCostPer) > 0 ? (double)($ErpValuePriceData[0]->lossCostPer) : 0.15;//损耗
                    $goldPrice = (double)($ErpValuePriceData[0]->goldPrice) > 0 ? (double)($ErpValuePriceData[0]->goldPrice) : 0;//金重
                    $processCost = (double)($ErpValuePriceData[0]->processCost) > 0 ? (double)($ErpValuePriceData[0]->processCost) : 0;
                    $ProportionToWax = (double)($ErpValuePriceData[0]->ProportionToWax) > 0 ? (double)($ErpValuePriceData[0]->ProportionToWax) : 18;
                    if ($goldPrice != 0) {
                        $price = ((double)$stonePrice
                                + $weight * $ProportionToWax * $goldPrice * ($lossCostPer + 1) + $processCost) * intval($number);
                        $price = sprintf("%.2f", $price);
                    } else {
                        $price = "0.00";
                    }
                }
                $price = BllPublic::GetModelPriceHaveStoneUnit($ErpValuePriceData[0],$weight,floatval($stonePrice))* intval($number);
            }*/
            $jewelStoneItem = M("model_order_jewel_stone_log")->where("model_main_order_current_detail_id=".$itemId)->select();
            if(count($jewelStoneItem)<=0) {
                $stoneInfo = (ConvertType::ConvertInt($currentOrderItem[0]['stone_category_id'], -1) > 0 ? "类型:" . FunctionCode::FindEqObjReField($stoneType, "id", "title", $currentOrderItem[0]["stone_category_id"]) : "")
                    . (ConvertType::ConvertInt($currentOrderItem[0]['stone_number'], -1) > 0 ? ",数量:" . $currentOrderItem[0]['stone_number'] . "粒" : "")
                    //. (ConvertType::ConvertInt($currentOrderItem[0]['stone_spec_id'], -1) > 0 ? ",规格:" . FunctionCode::FindEqArrReField($stoneSpec, "id", "title", $currentOrderItem[0]["stone_spec_id"]) : "")
                    . (!empty($currentOrderItem[0]['stone_spec_value']) ? ",规格:" . $currentOrderItem[0]['stone_spec_value'] : "")
                    . (ConvertType::ConvertInt($currentOrderItem[0]['stone_shape_id'], -1) > 0 ? ",形状:" . FunctionCode::FindEqObjReField($stoneShape, "id", "title", $currentOrderItem[0]["stone_shape_id"]) : "")
                    . (ConvertType::ConvertInt($currentOrderItem[0]['stone_color_id'], -1) > 0 ? ",颜色:" . FunctionCode::FindEqArrReField($stoneColor, "id", "title", $currentOrderItem[0]["stone_color_id"]) : "")
                    . (ConvertType::ConvertInt($currentOrderItem[0]['stone_purity_id'], -1) > 0 ? ",纯度:" . FunctionCode::FindEqArrReField($stonePurity, "id", "title", $currentOrderItem[0]["stone_purity_id"]) : "")
                    . (ConvertType::ConvertInt($currentOrderItem[0]['isSelfStone'], 0) == 1 ? ",自带石头" : "");
                $stoneInfo = empty($stoneInfo) ? "" : "主石(" . $stoneInfo . ")";
            }else{
                $stoneInfo = "选择主石头编号:".$jewelStoneItem[0]["CertCode"];
                $UserStoneAddtion = UserCls::getUserStoneAddtion($memberId);
                $price = $price + floatval($jewelStoneItem[0]["Price"])*$UserStoneAddtion;
            }
            $stoneInfoA =
                ConvertType::ConvertInt($currentOrderItem[0]['stone_out_A'], 0)==1?"封石":
                (ConvertType::ConvertInt($currentOrderItem[0]['stone_category_id_A'], -1) > 0 ? "类型:" . FunctionCode::FindEqObjReField($stoneType, "id", "title", $currentOrderItem[0]["stone_category_id_A"]) : "")
                . (ConvertType::ConvertInt($currentOrderItem[0]['stone_number_A'], -1) > 0 ? ",数量:" . $currentOrderItem[0]['stone_number_A'] . "粒" : "")
                //. (ConvertType::ConvertInt($currentOrderItem[0]['stone_spec_id_A'], -1) > 0 ? ",规格:" . FunctionCode::FindEqArrReField($stoneSpec, "id", "title", $currentOrderItem[0]["stone_spec_id_A"]) : "")
                . (!empty($currentOrderItem[0]['stone_spec_value_A'])?",规格:".$currentOrderItem[0]['stone_spec_value_A']:"")
                . (ConvertType::ConvertInt($currentOrderItem[0]['stone_shape_id_A'], -1) > 0 ? ",形状:" . FunctionCode::FindEqObjReField($stoneShape, "id", "title", $currentOrderItem[0]["stone_shape_id_A"]) : "")
                . (ConvertType::ConvertInt($currentOrderItem[0]['stone_color_id_A'], -1) > 0 ? ",颜色:" . FunctionCode::FindEqArrReField($stoneColor, "id", "title", $currentOrderItem[0]["stone_color_id_A"]) : "")
                . (ConvertType::ConvertInt($currentOrderItem[0]['stone_purity_id_A'], -1) > 0 ? ",纯度:" . FunctionCode::FindEqArrReField($stonePurity, "id", "title", $currentOrderItem[0]["stone_purity_id_A"]) : "");
            $stoneInfoA = empty($stoneInfoA) ? "" : ";副石A(" . $stoneInfoA . ")";

            $stoneInfoB =
                ConvertType::ConvertInt($currentOrderItem[0]['stone_out_B'], 0)==1?"封石":
                (ConvertType::ConvertInt($currentOrderItem[0]['stone_category_id_B'], -1) > 0 ? "类型:" . FunctionCode::FindEqObjReField($stoneType, "id", "title", $currentOrderItem[0]["stone_category_id_B"]) : "")
                . (ConvertType::ConvertInt($currentOrderItem[0]['stone_number_B'], -1) > 0 ? ",数量:" . $currentOrderItem[0]['stone_number_B'] . "粒" : "")
                //. (ConvertType::ConvertInt($currentOrderItem[0]['stone_spec_id_B'], -1) > 0 ? ",规格:" . FunctionCode::FindEqArrReField($stoneSpec, "id", "title", $currentOrderItem[0]["stone_spec_id_B"]) : "")
                . (!empty($currentOrderItem[0]['stone_spec_value_B'])?",规格:".$currentOrderItem[0]['stone_spec_value_B']:"")
                . (ConvertType::ConvertInt($currentOrderItem[0]['stone_shape_id_B'], -1) > 0 ? ",形状:" . FunctionCode::FindEqObjReField($stoneShape, "id", "title", $currentOrderItem[0]["stone_shape_id_B"]) : "")
                . (ConvertType::ConvertInt($currentOrderItem[0]['stone_color_id_B'], -1) > 0 ? ",颜色:" . FunctionCode::FindEqArrReField($stoneColor, "id", "title", $currentOrderItem[0]["stone_color_id_B"]) : "")
                . (ConvertType::ConvertInt($currentOrderItem[0]['stone_purity_id_B'], -1) > 0 ? ",纯度:" . FunctionCode::FindEqArrReField($stonePurity, "id", "title", $currentOrderItem[0]["stone_purity_id_B"]) : "");
            $stoneInfoB = empty($stoneInfoB) ? "" : ";副石B(" . $stoneInfoB . ")";

            $stoneInfoC =
                ConvertType::ConvertInt($currentOrderItem[0]['stone_out_C'], 0)==1?"封石":
                (ConvertType::ConvertInt($currentOrderItem[0]['stone_category_id_C'], -1) > 0 ? "类型:" . FunctionCode::FindEqObjReField($stoneType, "id", "title", $currentOrderItem[0]["stone_category_id_C"]) : "")
                . (ConvertType::ConvertInt($currentOrderItem[0]['stone_number_C'], -1) > 0 ? ",数量:" . $currentOrderItem[0]['stone_number_C'] . "粒" : "")
                //. (ConvertType::ConvertInt($currentOrderItem[0]['stone_spec_id_C'], -1) > 0 ? ",规格:" .FunctionCode::FindEqArrReField($stoneSpec, "id", "title", $currentOrderItem[0]["stone_spec_id_C"]) : "")
                . (!empty($currentOrderItem[0]['stone_spec_value_C'])?",规格:". $currentOrderItem[0]['stone_spec_value_C']:"")
                . (ConvertType::ConvertInt($currentOrderItem[0]['stone_shape_id_C'], -1) > 0 ? ",形状:" . FunctionCode::FindEqObjReField($stoneShape, "id", "title", $currentOrderItem[0]["stone_shape_id_C"]) : "")
                . (ConvertType::ConvertInt($currentOrderItem[0]['stone_color_id_C'], -1) > 0 ? ",颜色:" . FunctionCode::FindEqArrReField($stoneColor, "id", "title", $currentOrderItem[0]["stone_color_id_C"]) : "")
                . (ConvertType::ConvertInt($currentOrderItem[0]['stone_purity_id_C'], -1) > 0 ? ",纯度:" . FunctionCode::FindEqArrReField($stonePurity, "id", "title", $currentOrderItem[0]["stone_purity_id_C"]) : "");
            $stoneInfoC = empty($stoneInfoC) ? "" : ";副石C(" . $stoneInfoC . ")";

            $remarks = $currentOrderItem[0]["remarks"];
            $remarks = empty($remarks) ? "" : ";备注(" . $remarks . ")";

            $info = $stoneInfo . $stoneInfoA . $stoneInfoB . $stoneInfoC . $remarks;
            $userPercent = UserCls::GetUserPayPercentByMemberId($memberId);
            $price = $price*intval($number);
            $needPayPrice = $price == 0 ? 0 : $price * $userPercent;
            $data = array("id" => $id, "modelId" => $modelId, "title" => $title, "typeId" => $erpTypeId, "weight" => $weight, "pic" => $pic, "baseInfo" => $baseInfo, "stonePrice" => $stonePrice, "price" => $price, "needPayPrice" => $needPayPrice, "number" => floatval($number), "info" => $info);
            return myResponse::ResponseDataTrueDataObj($data);
        }
        return myResponse::ResponseDataFalseObj("获取数据出错");
    }

    public static function GetOrderListCount($memberId)
    {
        $Model = new \Think\Model();
        $data = $Model->query(ModelOrderCls::Current_Order_List_Count_Sql($memberId));
        if (count($data) > 0) {
            return $data[0]["cou"];
        }
        return 0;
    }

    public static function GetOrderPricePageList($memberId, $qualityId, $purityId, $cpage = 1)
    {
        $Model = new \Think\Model();
        $baseData = $Model->query(ModelOrderCls::Current_Order_List_For_Price_Sql($memberId, $cpage));
        $currentOrderlList = array();
        //$n = 0;
        //$erpTypeIds = "";
        foreach ($baseData as $value) {
            $id = $value["id"];
            $stonePrice = $value["stonePrice"];
            $number = $value['number'];
            //$weight = $value['weight'];
            $price = (floatval($value['price'])+floatval($stonePrice))*$number;
            //$dataItem = array("id" => $id, "typeId" => $value["erpTypeId"], "weight" => $weight, "stonePrice" => $stonePrice, "price" => 0, "number" => $number);
            //$currentOrderlList[$n++] = $dataItem;
            /*if (!empty($value["erpTypeId"]) && !strpos("," . $erpTypeIds . ",", "," . $value["erpTypeId"] . ",")) {
                $erpTypeIds = empty($erpTypeIds) ? $value["erpTypeId"] : $erpTypeIds . "," . $value["erpTypeId"];
            }*/
            $item = array("id" => $id, "price" => $price);
            $data[] = $item;
        }
        //------------------------ErpData
        /*$data = array();
        $ErpValuePriceData = array();
        if (!empty($erpTypeIds) && !empty($purityId) && !empty($qualityId) && !empty($erpTypeIds)) {
            $ErpValuePriceData = ErpPublicCode::GetUrlObjData("GetModelValuePrice", "model", array("typeIds" => $erpTypeIds, "purityId" => $purityId, "qualityId" => $qualityId));
        }
        if (!empty($currentOrderlList) && count($currentOrderlList) > 0) {
            $nn = 0;
            foreach ($currentOrderlList as $value) {
                $currentOrderlList[$nn]["price"] = BllPublic::GetModelPriceHaveStone($ErpValuePriceData,$value["typeId"]
                    ,$value["weight"],$value["qualityId"],$value["purityId"],floatval($value["stonePrice"]))* intval($currentOrderlList[$nn]["number"]);
                $item = array("id" => $value["id"], "price" => $currentOrderlList[$nn]["price"]);
                $data[$nn] = $item;
                $nn++;
            }
        }*/

        //-------------------------ErpData
        return myResponse::ResponseDataTrueDataObj(array("priceList" => $data));
    }

    public static function OrderListPage($memberId, $qualityId, $purityId, $tokenKey, $addressId = '', $cpage = 1)
    {
        $modelType = BaseCls::CacheModelCategory();
        $stoneType = BaseCls::CacheStoneCategory();
        $stoneSpec = BaseCls::CacheStoneSpec();
        $stoneShape = BaseCls::CacheStoneShape();
        $stonePurity = BaseCls::CacheStonePurity();
        $stoneColor = BaseCls::CacheStoneColor();

        $Model = new \Think\Model();
        $baseData = $Model->query(ModelOrderCls::Current_Order_List_Sql($memberId, $cpage));


        $currentOrderlList = array();
        $n = 0;
        $erpTypeIds = "";
        $jewelStoneLog = M("model_order_jewel_stone_log a")
            ->where("member_Id=$memberId AND exists (select * from app_model_main_order_current_detail where a.model_main_order_current_detail_id=id AND orderStatus ="
                .ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0.")")
            ->select();
        foreach ($baseData as $value) {
            $stonePrice = $value["stonePrice"];
            $number = $value['number'];
            $weight = $value['weight'];

            $UserModelAddtion = UserCls::getUserModelAddtion($memberId);
            $price = (floatval($value["price"])*$UserModelAddtion + floatval($stonePrice))*$number;

            $sn = FunctionCode::FindEqArrReN($jewelStoneLog,"model_main_order_current_detail_id",$value["id"]);
            if($sn<0) {
                $stoneInfo = (ConvertType::ConvertInt($value['stone_category_id'], -1) > 0 ? "类型:" . FunctionCode::FindEqObjReField($stoneType, "id", "title", $value["stone_category_id"]) : "")
                    . (ConvertType::ConvertInt($value['stone_number'], -1) > 0 ? ",数量:" . $value['stone_number'] . "粒" : "")
                    //. (ConvertType::ConvertInt($value['stone_spec_id'], -1) > 0 ? ",规格:" . FunctionCode::FindEqArrReField($stoneSpec, "id", "title", $value["stone_spec_id"]) : "")
                    . (!empty($value["stone_spec_value"]) ? ",规格:" . $value["stone_spec_value"] : "")
                    . (ConvertType::ConvertInt($value['stone_shape_id'], -1) > 0 ? ",形状:" . FunctionCode::FindEqObjReField($stoneShape, "id", "title", $value["stone_shape_id"]) : "")
                    . (ConvertType::ConvertInt($value['stone_color_id'], -1) > 0 ? ",颜色:" . FunctionCode::FindEqArrReField($stoneColor, "id", "title", $value["stone_color_id"]) : "")
                    . (ConvertType::ConvertInt($value['stone_purity_id'], -1) > 0 ? ",纯度:" . FunctionCode::FindEqArrReField($stonePurity, "id", "title", $value["stone_purity_id"]) : "")
                    . (ConvertType::ConvertInt($value['isSelfStone'], 0) == 1 ? ",自带石头" : "");
                $stoneInfo = empty($stoneInfo) ? "" : "主石(" . $stoneInfo . ")";
            }
            else
            {
                $stoneInfo = "选择主石头编号:".$jewelStoneLog[$sn]["CertCode"];
                $UserStoneAddtion = UserCls::getUserStoneAddtion($memberId);
                $price = $price + floatval($jewelStoneLog[$sn]["Price"])*$UserStoneAddtion*$number;
            }

            $stoneInfoA =
                ConvertType::ConvertInt($value['stone_out_A'],0) == 1?"封石":
                (ConvertType::ConvertInt($value['stone_category_id_A'], -1) > 0 ? "类型:" . FunctionCode::FindEqObjReField($stoneType, "id", "title", $value["stone_category_id_A"]) : "")
                . (ConvertType::ConvertInt($value['stone_number_A'], -1) > 0 ? ",数量:" . $value['stone_number_A'] . "粒" : "")
                //. (ConvertType::ConvertInt($value['stone_spec_id_A'], -1) > 0 ? ",规格:" . FunctionCode::FindEqArrReField($stoneSpec, "id", "title", $value["stone_spec_id_A"]) : "")
                . (!empty($value["stone_spec_value_A"])?",规格:".$value["stone_spec_value_A"]:"")
                . (ConvertType::ConvertInt($value['stone_shape_id_A'], -1) > 0 ? ",形状:" . FunctionCode::FindEqObjReField($stoneShape, "id", "title", $value["stone_shape_id_A"]) : "")
                . (ConvertType::ConvertInt($value['stone_color_id_A'], -1) > 0 ? ",颜色:" . FunctionCode::FindEqArrReField($stoneColor, "id", "title", $value["stone_color_id_A"]) : "")
                . (ConvertType::ConvertInt($value['stone_purity_id_A'], -1) > 0 ? ",纯度:" . FunctionCode::FindEqArrReField($stonePurity, "id", "title", $value["stone_purity_id_A"]) : "");
            $stoneInfoA = empty($stoneInfoA) ? "" : ";副石A(" . $stoneInfoA . ")";

            $stoneInfoB =
                ConvertType::ConvertInt($value['stone_out_B'],0) == 1?"封石":
                (ConvertType::ConvertInt($value['stone_category_id_B'], -1) > 0 ? "类型:" . FunctionCode::FindEqObjReField($stoneType, "id", "title", $value["stone_category_id_B"]) : "")
                . (ConvertType::ConvertInt($value['stone_number_B'], -1) > 0 ? ",数量:" . $value['stone_number_B'] . "粒" : "")
                //. (ConvertType::ConvertInt($value['stone_spec_id_B'], -1) > 0 ? ",规格:" . FunctionCode::FindEqArrReField($stoneSpec, "id", "title", $value["stone_spec_id_B"]) : "")
                . (!empty($value["stone_spec_value_B"])?",规格:".$value["stone_spec_value_B"]:"")
                . (ConvertType::ConvertInt($value['stone_shape_id_B'], -1) > 0 ? ",形状:" . FunctionCode::FindEqObjReField($stoneShape, "id", "title", $value["stone_shape_id_B"]) : "")
                . (ConvertType::ConvertInt($value['stone_color_id_B'], -1) > 0 ? ",颜色:" . FunctionCode::FindEqArrReField($stoneColor, "id", "title", $value["stone_color_id_B"]) : "")
                . (ConvertType::ConvertInt($value['stone_purity_id_B'], -1) > 0 ? ",纯度:" . FunctionCode::FindEqArrReField($stonePurity, "id", "title", $value["stone_purity_id_B"]) : "");
            $stoneInfoB = empty($stoneInfoB) ? "" : ";副石B(" . $stoneInfoB . ")";

            $stoneInfoC =
                ConvertType::ConvertInt($value['stone_out_C'],0) == 1?"封石":
                (ConvertType::ConvertInt($value['stone_category_id_C'], -1) > 0 ? "类型:" . FunctionCode::FindEqObjReField($stoneType, "id", "title", $value["stone_category_id_C"]) : "")
                . (ConvertType::ConvertInt($value['stone_number_C'], -1) > 0 ? ",数量:" . $value['stone_number_C'] . "粒" : "")
                //. (ConvertType::ConvertInt($value['stone_spec_id_C'], -1) > 0 ? ",规格:" . FunctionCode::FindEqArrReField($stoneSpec, "id", "title", $value["stone_spec_id_C"]) : "")
                . (!empty($value["stone_spec_value_C"])?",规格:".$value["stone_spec_value_C"]:"")
                . (ConvertType::ConvertInt($value['stone_shape_id_C'], -1) > 0 ? ",形状:" . FunctionCode::FindEqObjReField($stoneShape, "id", "title", $value["stone_shape_id_C"]) : "")
                . (ConvertType::ConvertInt($value['stone_color_id_C'], -1) > 0 ? ",颜色:" . FunctionCode::FindEqArrReField($stoneColor, "id", "title", $value["stone_color_id_C"]) : "")
                . (ConvertType::ConvertInt($value['stone_purity_id_C'], -1) > 0 ? ",纯度:" . FunctionCode::FindEqArrReField($stonePurity, "id", "title", $value["stone_purity_id_C"]) : "");
            $stoneInfoC = empty($stoneInfoC) ? "" : ";副石B(" . $stoneInfoC . ")";

            $remarks = $value["remarks"];
            $remarks = empty($remarks) ? "" : ";备注(" . $remarks . ")";

            $info = $stoneInfo . $stoneInfoA . $stoneInfoB . $stoneInfoC . $remarks;

            $id = $value["id"];
            $modelId = $value["model_product_id"];
            $title = $value["name"] . "(" . $value["modelNum"] . ")";
            $pic = $value["pic"];
            $handSize = $value['handSize'];
            $baseInfo = "类型:" . FunctionCode::FindEqArrReField($modelType, "id", "title", $value["model_category_id"])
                . (empty($handSize)||$handSize==0?"":";手寸:" . floatval($handSize));

            $dataItem = array("id" => $id, "modelId" => $modelId, "title" => $title, "typeId" => $value["erpTypeId"], "weight" => $weight, "pic" => $pic
            , "baseInfo" => $baseInfo, "stonePrice" => $stonePrice, "price" => $price, "number" => floatval($number), "info" => $info);
            $currentOrderlList[$n++] = $dataItem;
            if (!empty($value["erpTypeId"]) && !strpos("," . $erpTypeIds . ",", "," . $value["erpTypeId"] . ",")) {
                $erpTypeIds = empty($erpTypeIds) ? $value["erpTypeId"] : $erpTypeIds . "," . $value["erpTypeId"];
            }
        }
        //------------------------ErpData
        //if(!empty($erpTypeIds) && !empty($purityId) && !empty($qualityId) && !empty($currentOrderlList) && count($currentOrderlList)>0) {
        //$ErpData = null;
        /*$ErpValuePriceData = null;
        if (FunctionCode::isInteger($cpage) && intval($cpage) > 1) {
            if (!empty($erpTypeIds) && !empty($purityId) && !empty($qualityId) && !empty($currentOrderlList) && count($currentOrderlList) > 0) {
                $ErpValuePriceData = ErpPublicCode::GetUrlObjData("GetModelValuePrice", "model", array("typeIds" => $erpTypeIds, "purityId" => $purityId, "qualityId" => $qualityId));
            }
        } else {
            $ErpData = ErpPublicCode::GetUrlObjData("GetModelCurrentOrderPage", "model", array("customerId" => $orderErpId, "typeIds" => $erpTypeIds, "purityId" => $purityId, "qualityId" => $qualityId));
            $ErpValuePriceData = $ErpData->modelValuePrice;
        }

        if (!empty($ErpValuePriceData) && count($ErpValuePriceData) > 0) {//计算价格
            $nn = 0;
            foreach ($currentOrderlList as $value) {
                $currentOrderlList[$nn]["price"] = BllPublic::GetModelPriceHaveStone($ErpValuePriceData,$value["typeId"]
                    ,$value["weight"],$value["qualityId"],$value["purityId"],floatval($value["stonePrice"]))* intval($currentOrderlList[$nn]["number"]);
                $nn++;
            }
        }*/

        //}
        //-------------------------ErpData
        if (FunctionCode::isInteger($cpage) && intval($cpage) > 1) {
            $data = array("currentOrderlList" => array("list" => $currentOrderlList, "list_count" => ModelOrderCls::GetOrderListCount($memberId)));
        } else {
            //$ErpData = ErpPublicCode::GetUrlObjData("GetModelCurrentOrderPage","model",array("customerId" => $orderErpId,"typeIds"=>$erpTypeIds,"purityId"=>$purityId,"qualityId"=>$qualityId));
            //$customer = ErpPublicCode::GetUrlObjData("GetCustomerById", "customer", array("customerId" => $orderErpId));
            //$modelPurity = BaseCls::CacheModelPurity();
            //$modelQuality = BaseCls::CacheModelQuality();
            $orderErpId = UserCls::GetOrderErpId($tokenKey);
            $ErpData = ErpPublicCode::GetUrlObjData("GetModelCurrentOrderPage", "model", array("typeIds" => $erpTypeIds, "purityId" => $purityId, "qualityId" => $qualityId,"customerId" => $orderErpId));
            $customer = $ErpData->customer;
            $modelPurity = $ErpData->modelPurity;
            $modelQuality = $ErpData->modelQuality;
            $address = null;
            if (FunctionCode::isInteger($addressId) && intval($addressId) > 0) {
                $reObj = UserCls::getAddressInfoById($tokenKey, $addressId);
                $address = $reObj->error != ValidateCode::$noError ? null : $reObj;
            } else if($addressId!='0') {
                $reObj = UserCls::getDefultAddress($tokenKey);
                $address = $reObj->error != ValidateCode::$noError ? null : $reObj;
            }
            if(empty($address))
            {
                $address = myResponse::ResponseDataTrueDataObj(UserCls::$PickDefaultAddress);
            }
            $data = array("currentOrderlList" => array("list" => $currentOrderlList, "list_count" => ModelOrderCls::GetOrderListCount($memberId)), "address" => $address->data, "customer" => $customer, "modelColor" => $modelPurity, "modelQuality" => $modelQuality);
        }
        return myResponse::ResponseDataTrueDataObj($data);
    }
}