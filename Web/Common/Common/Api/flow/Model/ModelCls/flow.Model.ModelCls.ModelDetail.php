<?php
namespace Common\Common\Api\flow;
use Common\Common\PublicCode\ConvertType;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\WithSql;
use Common\Common\PublicCode\BllPublic;

trait ModelDetail
{
    public static function getModelDetailById($id,$memberId,$tokenKey)
    {
        //$modeData = M('model_product')->where(array('id'=>$id))->select();
        $Model = new \Think\Model();
        $modeData = $Model->query(ModelCls::Get_Model_Include_Stone_Price($id));
        if(count($modeData)>0)
        {
            $modeId = $modeData[0]["id"];
            /*$categoryData = M('mode_category')->field("id,name")->where("exists (select * from app_model_product_category a "
                ."where app_model_category.id=a.mode_category_id and model_product_id=".$modeId
                .") and exists (select * from app_model_category b where app_model_category.mode_category_id = b.id and b.sign='mode')")->select();*/
            /*$categoryData = M('model_category aa')->field("id")->where("exists (select * from app_model_product_category a "
                ."where aa.id=a.model_category_id and model_product_id=".$modeId
                .") and exists (select * from app_model_category b where aa.model_category_id = b.id and b.sign='mode')")->select();*/
            $modelCategoryId = ModelCls::getTop1ModelCategoryId($id);
            //$goldenPrice = BaseCls::CacheModelPurityForPrice();

            $pics =  M('model_puroduct_file')->field("id,".BllPublic::SqlConnPicHttpBasePath('file','pic')
                .",".BllPublic::SqlConnPicHttpBasePath('file1','picm')
                .",".BllPublic::SqlConnPicHttpBasePath('file2','picb'))->where('model_product_id='.$modeId." and isShow>0 and `group`='productPic'")->order("sort")->select();
            //$modelType = M('model_category aa')->field("id,name as title")->where("exists (select * from app_model_category a where aa.model_category_id = a.id and sign='mode')")->select();
            //$stoneType = M('stone_category')->field("id,name as title")->select();
            //$stoneSpec = M('stone_spec')->field("id,if(up=0,down,CONCAT(down,'-',up)) as title")->select();
            //$stoneShape = M('stone_shape')->field("id,name as title")->select();
            //$stonePurity = M('stone_purity')->field("id,name as title")->select();
            //$stoneColor = M('stone_color')->field("id,name as title")->select();
            if(count($pics)<=0)
            {
                $pics = array(array("pic"=>BllPublic::GetPicBasePathPic($modeData[0]["pic"])
                ,"picm"=>BllPublic::GetPicBasePathPic($modeData[0]["picm"])
                ,"picb"=>BllPublic::GetPicBasePathPic($modeData[0]["picb"])));
            }
            $modelType = BaseCls::CacheModelCategory();
            $stoneType = BaseCls::CacheStoneCategory();
            //$stoneSpec = BaseCls::CacheStoneSpec();
            $stoneShape = BaseCls::CacheStoneShape();
            $stonePurity = BaseCls::CacheStonePurity();
            $stoneColor = BaseCls::CacheStoneColor();

            $remarks = M('model_remarks')->field("id,title,content")->select();


            $typeTitle = FunctionCode::FindEqObjReField($stoneType,"id","title",$modeData[0]["stone_category_id"]);
            $shapeTitle = FunctionCode::FindEqObjReField($stoneShape,"id","title",$modeData[0]["stone_shape_id"]);
            $purityTitle = FunctionCode::FindEqArrReField($stonePurity,"id","title",$modeData[0]["stone_purity_id"]);
            $colorTitle = FunctionCode::FindEqArrReField($stoneColor,"id","title",$modeData[0]["stone_color_id"]);

            $specTitle = BllPublic::ChangeSpecErpValue($modeData[0]["stone_spec_value"]);
            $number = FunctionCode::ReurnDefault($modeData[0]["stone_number"],0,null);

            $stone = array("typeId"=>empty($typeTitle)?null:$modeData[0]["stone_category_id"],"typeTitle"=>$typeTitle
                            ,"specId"=>$modeData[0]["stone_spec_id"],"specTitle"=>$specTitle//FunctionCode::FindEqArrReField($stoneSpec,"id","title",$modeData[0]["stone_spec_id"])
                            ,"specSelectTitle"=>BllPublic::GetSpecErpSelectTitle($modeData[0]["stone_spec_value"])
                            ,"shapeId"=>empty($shapeTitle)?null:$modeData[0]["stone_shape_id"],"shapeTitle"=>$shapeTitle
                            ,"purityId"=>empty($purityTitle)?null:$modeData[0]["stone_purity_id"],"purityTitle"=>$purityTitle
                            ,"colorId"=>empty($colorTitle)?null:$modeData[0]["stone_color_id"],"colorTitle"=>$colorTitle
                            ,"number"=>$number,"price"=>$modeData[0]["sprice"]);

            $typeTitle = FunctionCode::FindEqObjReField($stoneType,"id","title",$modeData[0]["stone_category_id_A"]);
            $shapeTitle = FunctionCode::FindEqObjReField($stoneShape,"id","title",$modeData[0]["stone_shape_id_A"]);
            $purityTitle = FunctionCode::FindEqArrReField($stonePurity,"id","title",$modeData[0]["stone_purity_id_A"]);
            $colorTitle = FunctionCode::FindEqArrReField($stoneColor,"id","title",$modeData[0]["stone_color_id_A"]);

            $specTitle = BllPublic::ChangeSpecErpValue($modeData[0]["stone_spec_value_A"]);
            $stoneOut = ConvertType::ConvertInt($modeData[0]["stone_out_A"],0);
            $number = FunctionCode::ReurnDefault($modeData[0]["stone_number_A"],0,null);

            $stoneA = array("typeId"=>empty($typeTitle)?null:$modeData[0]["stone_category_id_A"],"typeTitle"=>$typeTitle
                            ,"specId"=>$modeData[0]["stone_spec_id_A"],"specTitle"=>$specTitle//FunctionCode::FindEqArrReField($stoneSpec,"id","title",$modeData[0]["stone_spec_id_A"])
                            ,"specSelectTitle"=>BllPublic::GetSpecErpSelectTitle($modeData[0]["stone_spec_value_A"])
                            ,"shapeId"=>empty($shapeTitle)?null:$modeData[0]["stone_shape_id_A"],"shapeTitle"=>$shapeTitle
                            ,"purityId"=>empty($purityTitle)?null:$modeData[0]["stone_purity_id_A"],"purityTitle"=>$purityTitle
                            ,"colorId"=>empty($colorTitle)?null:$modeData[0]["stone_color_id_A"],"colorTitle"=>$colorTitle
                            ,"stoneOut"=>$stoneOut
                            ,"number"=>$number,"price"=>$modeData[0]["saprice"]);

            $typeTitle = FunctionCode::FindEqObjReField($stoneType,"id","title",$modeData[0]["stone_category_id_B"]);
            $shapeTitle = FunctionCode::FindEqObjReField($stoneShape,"id","title",$modeData[0]["stone_shape_id_B"]);
            $purityTitle = FunctionCode::FindEqArrReField($stonePurity,"id","title",$modeData[0]["stone_purity_id_B"]);
            $colorTitle = FunctionCode::FindEqArrReField($stoneColor,"id","title",$modeData[0]["stone_color_id_B"]);

            $specTitle = BllPublic::ChangeSpecErpValue($modeData[0]["stone_spec_value_B"]);
            $stoneOut = ConvertType::ConvertInt($modeData[0]["stone_out_B"],0);
            $number = FunctionCode::ReurnDefault($modeData[0]["stone_number_B"],0,null);

            $stoneB = array("typeId"=>empty($typeTitle)?null:$modeData[0]["stone_category_id_B"],"typeTitle"=>$typeTitle
                            ,"specId"=>$modeData[0]["stone_spec_id_B"],"specTitle"=>$specTitle//FunctionCode::FindEqArrReField($stoneSpec,"id","title",$modeData[0]["stone_spec_id_B"])
                            ,"specSelectTitle"=>BllPublic::GetSpecErpSelectTitle($modeData[0]["stone_spec_value_B"])
                            ,"shapeId"=>empty($shapeTitle)?null:$modeData[0]["stone_shape_id_B"],"shapeTitle"=>$shapeTitle
                            ,"purityId"=>empty($purityTitle)?null:$modeData[0]["stone_purity_id_B"],"purityTitle"=>$purityTitle
                            ,"colorId"=>empty($colorTitle)?null:$modeData[0]["stone_color_id_B"],"colorTitle"=>$colorTitle
                            ,"stoneOut"=>$stoneOut
                            ,"number"=>$number,"price"=>$modeData[0]["sbprice"]);

            $typeTitle = FunctionCode::FindEqObjReField($stoneType,"id","title",$modeData[0]["stone_category_id_C"]);
            $shapeTitle = FunctionCode::FindEqObjReField($stoneShape,"id","title",$modeData[0]["stone_shape_id_C"]);
            $purityTitle = FunctionCode::FindEqArrReField($stonePurity,"id","title",$modeData[0]["stone_purity_id_C"]);
            $colorTitle = FunctionCode::FindEqArrReField($stoneColor,"id","title",$modeData[0]["stone_color_id_C"]);

            $specTitle = BllPublic::ChangeSpecErpValue($modeData[0]["stone_spec_value_C"]);
            $stoneOut = ConvertType::ConvertInt($modeData[0]["stone_out_C"],0);
            $number = FunctionCode::ReurnDefault($modeData[0]["stone_number_C"],0,null);

            $stoneC = array("typeId"=>empty($typeTitle)?null:$modeData[0]["stone_category_id_C"],"typeTitle"=>$typeTitle
                            ,"specId"=>$modeData[0]["stone_spec_id_C"],"specTitle"=>$specTitle//FunctionCode::FindEqArrReField($stoneSpec,"id","title",$modeData[0]["stone_spec_id_C"])
                            ,"specSelectTitle"=>BllPublic::GetSpecErpSelectTitle($modeData[0]["stone_spec_value_C"])
                            ,"shapeId"=>empty($shapeTitle)?null:$modeData[0]["stone_shape_id_C"],"shapeTitle"=>$shapeTitle
                            ,"purityId"=>empty($purityTitle)?null:$modeData[0]["stone_purity_id_C"],"purityTitle"=>$purityTitle
                            ,"colorId"=>empty($colorTitle)?null:$modeData[0]["stone_color_id_C"],"colorTitle"=>$colorTitle
                            ,"stoneOut"=>$stoneOut
                            ,"number"=>$number,"price"=>$modeData[0]["scprice"]);

            if((!empty($stoneC["typeTitle"]) || !empty($stoneC["specTitle"]) || !empty($stoneC["shapeTitle"]) || !empty($stoneC["purityTitle"])
                    || !empty($stoneC["colorTitle"]) || !empty($stoneC["stoneOut"]) ||  !empty($stoneC["number"])))
            {
                $stoneC["isNotEmpty"] = 1;
                $stoneB["isNotEmpty"] = 1;
                $stoneA["isNotEmpty"] = 1;
                $stone["isNotEmpty"] = 1;
            }
            else if((!empty($stoneB["typeTitle"]) || !empty($stoneB["specTitle"]) || !empty($stoneB["shapeTitle"]) || !empty($stoneB["purityTitle"])
                || !empty($stoneB["colorTitle"]) || !empty($stoneB["stoneOut"]) ||  !empty($stoneB["number"])))
            {
                $stoneC["isNotEmpty"] = 0;
                $stoneB["isNotEmpty"] = 1;
                $stoneA["isNotEmpty"] = 1;
                $stone["isNotEmpty"] = 1;
            }
            else if((!empty($stoneA["typeTitle"]) || !empty($stoneA["specTitle"]) || !empty($stoneA["shapeTitle"]) || !empty($stoneA["purityTitle"])
                || !empty($stoneA["colorTitle"]) || !empty($stoneA["stoneOut"]) ||  !empty($stoneA["number"])))
            {
                $stoneC["isNotEmpty"] = 0;
                $stoneB["isNotEmpty"] = 0;
                $stoneA["isNotEmpty"] = 1;
                $stone["isNotEmpty"] = 1;
            }
            else//封石是一定有的isSelfStone
            {
                $stoneC["isNotEmpty"] = 0;
                $stoneB["isNotEmpty"] = 0;
                $stoneA["isNotEmpty"] = 0;
                $stone["isNotEmpty"] = 1;
            }
            $weightStr = "";
            $weightB = floatval($modeData[0]["weight"]);
            if($weightB>0)
            {
                $weightStr = "PT: ".sprintf("%.2f",$weightB*18)."g 18K: ".sprintf("%.2f",$weightB*16)."g";
            }
            $UserModelAddtion = UserCls::getUserModelAddtion($memberId);
            $UserStoneAddtion = UserCls::getUserStoneAddtion($memberId);
            $mode = array("title"=>$modeData[0]["modelNum"],"weight"=>$weightStr,"isSelfStone"=>"1"
            ,"modelAddtion"=>$UserModelAddtion,"UserStoneAddtion"=>$UserStoneAddtion
            ,"price"=>floatval($modeData[0]["price"])*$UserModelAddtion,"remark"=>FunctionCode::ReurnDefault($modeData[0]["memo"],NULL,""),"categoryId"=>$modelCategoryId,"categoryTitle"=>FunctionCode::FindEqArrReField($modelType,"id","title",$modelCategoryId)
                            ,"stone"=>$stone,"stoneA"=>$stoneA,"stoneB"=>$stoneB,"stoneC"=>$stoneC,"pics"=>$pics);

            $data = array("goldenPrice"=>array(),"model"=>$mode,"stoneType"=>$stoneType,"handSizeData"=>BllPublic::GethandSizeData(),"stoneShape"=>$stoneShape,"stoneColor"=>$stoneColor,"stonePurity"=>$stonePurity,"remarks"=>$remarks,"IsCanSelectStone"=>UserCls::GetIsCanSelectStone($tokenKey));
            return myResponse::ResponseDataTrueDataObj($data);
        }
        return myResponse::ResponseDataFalseObj("获取数据出错");
    }
    public static function getStonePrice($stoneColorId,$stoneCategoryId,$stoneSpecId,$stonePurityId)
    {
        $stonePrice = M('stone_price')->where(array("stone_color_id"=>$stoneColorId,"stone_category_id"=>$stoneCategoryId,"stone_spec_id"=>$stoneSpecId,"stone_purity_id"=>$stonePurityId))->select();
        if(count($stonePrice)>0)
        {
            return $stonePrice[0]["price"];
        }
        return -1;
    }

}