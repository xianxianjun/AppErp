<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/4/17
 * Time: 15:56
 */
namespace Common\Common\Api\flow;

use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\PublicCode\WithSql;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\flow\UserCls;
use Common\Common\PublicCode\BllPublic;
use Common\Common\Api\flow\UpdateCls;
use Common\Common\Api\Code\myResponse;
trait stoneInfo
{
    public static function stoneSearchInfo()
    {
        $data = array(
            "certAuth"=>StoneCls::GetStoneSearChCertAuth(),
            "color"=>StoneCls::GetStoneSearChColor(),
            "shape"=>StoneCls::GetStoneSearChShape(),
            "purity"=>StoneCls::GetStoneSearChPurity(),
            "cut"=>StoneCls::GetStoneSearChCut(),
            "polishing"=>StoneCls::GetStoneSearChPolishing(),
            "symmetric"=>StoneCls::GetStoneSearChSymmetric(),
            "fluorescence"=>StoneCls::GetStoneSearChFluorescence(),
            "weight"=>StoneCls::GetStoneSearChWeight(),
            "price"=>StoneCls::GetStoneSearChPrice()
        );
        return $data;
        //myResponse::ResponseDataTrueDataString()
    }
    public static function stoneSearchErpDo($cpage,$certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
        ,$price,$weight,$orderby,$memberId)
    {
        $percent = UserCls::getUserStoneAddtion($memberId);
        $priceArr = explode(',',$price);
        $weightArr = explode(',',$weight);
        $cpage = intval($cpage)>0?$cpage:0;
        $data = array("certAuth"=>$certAuth,"color"=>$color,"shape"=>$shape,"purity"=>$purity,"cut"=>$cut
        ,"polishing"=>$polishing,"symmetric"=>$symmetric,"fluorescence"=>$fluorescence
        ,"MinPrice"=>$priceArr[0],"MaxPrice"=>$priceArr[1]
        ,"MinWeight"=>$weightArr[0],"MaxWeight"=>$weightArr[1]);
        $edata = ErpPublicCode::PostUrlForObjData($data,"SearchStone","stone","GetstoneSearchConditionEntity","entity"
        ,array("cpage"=>$cpage,"percent"=>floatval($percent),"orderby"=>$orderby));
        $searchKey = StoneCls::GetStoneSearChCondition($certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
            ,$price,$weight,$percent);
        if($cpage<=1) {
            $headline = array("勾选","克拉","价格","形状","颜色","净度","切工","抛光","对称","荧光","证书","证书号");
            $sdata = array("stone" => array("list" => $edata->list, "list_count" => $edata->list_count, "headline" => $headline
            ,"searchKey"=>$searchKey));
        }
        else
        {
            $sdata = array("stone" => array("list" => $edata->list, "list_count" => $edata->list_count,"searchKey"=>$searchKey));
        }
        return $sdata;
    }
    public static function stoneSearchJpdiamDo($cpage,$certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
        ,$price,$weight,$orderby,$memberId)
    {
        $cpage = intval($cpage)>0?$cpage:0;
        $percent = UserCls::getUserStoneAddtion($memberId);
        $sql = StoneCls::Get_SearChjpdianStone($cpage,$certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
            ,$price,$weight,$percent,$orderby);
        $Model = new \Think\Model();
        $list = $Model->query($sql);
        $sqlcount = StoneCls::Get_SearChjpdianStoneCountSql($certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
            ,$price,$weight);
        $searchKey = StoneCls::GetStoneSearChCondition($certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
            ,$price,$weight,$percent);
        $listcount = $Model->query($sqlcount);
        if($cpage<=1) {
            $headline = array("勾选","克拉","价格","形状","颜色","净度","切工","抛光","对称","荧光","证书","证书号");
            $data = array("stone" => array("list" => $list, "list_count" => $listcount[0]["num"], "headline" => $headline
            ,"searchKey"=>$searchKey));
        }
        else
        {
            $data = array("stone" => array("list" => $list, "list_count" => $listcount[0]["num"],"searchKey"=>$searchKey));
        }
        return $data;

    }
    public static function stoneSearchDo($cpage,$certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
        ,$price,$weight,$percent,$orderby)
    {
        $cpage = intval($cpage)>0?$cpage:0;
        $sql = StoneCls::Get_SearChStoneSql($cpage,$certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
            ,$price,$weight,$percent,$orderby);
        $Model = new \Think\Model();
        $list = $Model->query($sql);
        $sqlcount = StoneCls::Get_SearChStoneCountSql($certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
            ,$price,$weight);
        $searchKey = StoneCls::GetStoneSearChCondition($certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
            ,$price,$weight,$percent);
        $listcount = $Model->query($sqlcount);
        if($cpage<=1) {
            $headline = array("勾选","克拉","价格","形状","颜色","净度","切工","抛光","对称","荧光","证书","证书号");
            $data = array("stone" => array("list" => $list, "list_count" => $listcount[0]["num"], "headline" => $headline
            ,"searchKey"=>$searchKey));
        }
        else
        {
            $data = array("stone" => array("list" => $list, "list_count" => $listcount[0]["num"],"searchKey"=>$searchKey));
        }
        return $data;
    }
    public static function stoneOffer($ids,$memberId)
    {
        //$sql = "select * from app_jewel_stone_product where id in ($ids)";
        //$Model = new \Think\Model();
        //$list = $Model->query($sql);
        $percent = UserCls::getUserStoneAddtion($memberId);
        $cdata = ErpPublicCode::GetUrlObjData("stoneOrderListPage", "stone", array("ids"=>$ids,"percent"=>floatval($percent)));
        $list = $cdata->stoneList;
        $listarr = array();
        foreach($list as $item)
        {
            $otherStr = (empty($item->Weight)?"":" 重量:".$item->Weight)
                        .(empty($item->Shape)?"":" 形状:".$item->Shape)
                        .(empty($item->Color)?"":" 颜色:".$item->Color)
                        .(empty($item->Purity)?"":" 净度:".$item->Purity)
                        .(empty($item->Cut)?"":" 切工:".$item->Cut)
                        .(empty($item->Polishing)?"":" 抛光:".$item->Polishing)
                        .(empty($item->symmetric)?"":" 对称:".$item->symmetric)
                        .(empty($item->Fluorescence)?"":" 荧光:".$item->Fluorescence)
                        .(empty($item->Price)?"":" 价格:".sprintf("%.2f",$item->Price));
            $ItemStr = array("title"=>"包含证书","content"=> (empty($item->CertAuth)?"":"证书:".$item->CertAuth)
                                                           .(empty($item->CertCode)?"":"证书号:".$item->CertCode)
                                                           .$otherStr);
            $ItemStr1 = array("title"=>"不包含证书","content"=>$otherStr);
            $listarr[] = $ItemStr;
            $listarr[] = $ItemStr1;
        }
        return $listarr;
    }
    public static function GetStoneOrderListAndCount($SorderStatus,$EorderStatus,$cpage,$memberId)
    {
        if(FunctionCode::isInteger($cpage))
        {
            if(empty($pageCount)) {
                $pageCount = 10;
            }
            if ($cpage <= 1) {
                $upnum = 0;
            } else {
                $upnum = ($cpage - 1) * $pageCount;
            }
            $limit = " LIMIT " . $upnum . "," . $pageCount;
        }
        $between = "orderStatus>0";
        if(intval($SorderStatus)<=intval($EorderStatus))
        {
            $between = "orderStatus > $SorderStatus AND orderStatus <= $EorderStatus ";
        }
        $sql = "select id,orderNum,createDate as orderDate,customerName,remark,discountTotalPrice as totalPrice
          ,(select count(*) from app_jewel_stone_order_detail where a.id=jewel_stone_order_id) as number
          from app_jewel_stone_order a where $between and member_Id=$memberId order by id desc".$limit;
        $Model = new \Think\Model();
        $data = $Model->query($sql);
        $count = M("jewel_stone_order")->where("$between  and member_Id=$memberId")->count();
        $data = array("list"=>$data,"list_count"=>$count);
        return $data;
    }
}
trait stoneBase
{
    public static function GetStoneSearChCondition($certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
        ,$price,$weight,$percent)
    {
        $str = "";
        if(!empty($certAuth))
        {
            $str = "$str 证书:$certAuth;";
        }
        if(!empty($color))
        {
            $str = "$str 颜色:$color;";
        }
        if(!empty($shape))
        {
            $str = "$str 形状:$shape;";
        }
        if(!empty($purity))
        {
            $str = "$str 净度:$purity;";
        }
        if(!empty($cut))
        {
            $str = "$str 切工:$cut;";
        }
        if(!empty($polishing))
        {
            $str = "$str 抛光:$polishing;";
        }
        if(!empty($symmetric))
        {
            $str = "$str 对称:$symmetric;";
        }
        if(!empty($fluorescence))
        {
            $str = "$str 荧光:$fluorescence;";
        }
        $priceArr = explode(',',$price);
        $weightArr = explode(',',$weight);

        if(floatval($priceArr[0])>0 && !empty($priceArr[1]))
        {
            $str = "$str 价格:$priceArr[0] ~ $priceArr[1]元;";
        }
        else if(floatval($priceArr[0])>0)
        {
            $str = "$str 价格:$priceArr[0]元以上;";
        }
        else if(floatval($priceArr[1])>0)
        {
            $str = "$str 价格:$priceArr[1]元以下;";
        }

        if(floatval($weightArr[0])>0 && !empty($weightArr[1]))
        {
            $str = "$str 重量:$weightArr[0] ~ $weightArr[1]克拉;";
        }
        else if(floatval($weightArr[0])>0)
        {
            $str = "$str 重量:$weightArr[0]克拉以上;";
        }
        else if(floatval($weightArr[1])>0)
        {
            $str = "$str 重量:$weightArr[1]克拉以下;";
        }
        /*if(!empty($percent) && floatval($percent)!=0)
        {
            $str = "$str 加点:".floatval($percent).";";
        }*/
        return $str;
    }
    public static function GetStoneSearChCertAuth()
    {
        $CERTAUTH =  array("title"=>"证书机构","keyword"=>"certAuth","values"=>array("GIA","IGI"));
        return $CERTAUTH;
    }
    public static function GetStoneSearChColor()
    {
        $COLOR =  array("title"=>"颜色","keyword"=>"color","values"=>array("D","E","F","G","H","I","J","K","L","M","N"));
        return $COLOR;
    }
    public static function GetStoneSearChWeight()
    {
        /*$max = S("jewel_stone_product_max_weight");
        if(empty($max)) {
            $max = M("jewel_stone_product")->max('weight');
            $max = ceil($max);
            S("jewel_stone_product_max_weight", $max, 300);
            //echo "jewel_stone_product_max_weight";
        }
        $min = 0;
        if($max<=$min){$max=$min+10;}
        $WEIGHT = array("title"=>"钻石重量(克拉) $min ~ $max","keyword"=>"weight","minimum"=>$min,"maximum"=>$max);*/
        $list = array(
        array("title"=>"小于0.3ct","key"=>"0,0.3")
        ,array("title"=>"0.3~0.5ct","key"=>"0.3,0.5")
        ,array("title"=>"0.5~0.7ct","key"=>"0.5,0.7")
        ,array("title"=>"0.8ct以上","key"=>"0.8,0")
        );
        $WEIGHT = array("title"=>"克拉","keyword"=>"weight","list"=>$list);
        return $WEIGHT;
    }
    public static function GetStoneSearChPrice()
    {
        /*$max = S("jewel_stone_product_max_price");
        if(empty($max)) {
            $max = M("jewel_stone_product")->max('price');
            $max = ceil($max);
            S("jewel_stone_product_max_price", $max, 300);
            //echo "jewel_stone_product_max_price";
        }
        $min = 0;
        if($max<=$min){$max=$min+1000;}
        $PRICE = array("title"=>"价格范围(元) $min ~ $max","keyword"=>"price","minimum"=>$min,"maximum"=>$max);*/
        $list = array(
        array("title"=>"￥3000元以下","key"=>"0,3000")
        ,array("title"=>"￥3000~5999元","key"=>"3000,5999")
        ,array("title"=>"￥5000~7999","key"=>"5000,7999")
        ,array("title"=>"￥8000~9999","key"=>"8000,9999")
        ,array("title"=>"￥10000~19999","key"=>"10000,19999")
        ,array("title"=>"￥20000以上","key"=>"20000,0")
        );
        $PRICE = array("title"=>"价格","keyword"=>"price","list"=>$list);
        return $PRICE;
    }
    public static function GetStoneSearChShape()
    {
        $SHAPE = array("title"=>"形状","keyword"=>"shape"
        ,"values"=>array(
                array(
                    "pic"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_1.png"
                ,"pic1"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_1_2.png"
                ,"name"=>"圆形"),
                array(
                    "pic"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_2.png"
                ,"pic1"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_2_2.png"
                ,"name"=>"公主方"),
                array(
                    "pic"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_3.png"
                ,"pic1"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_3_2.png"
                ,"name"=>"雷迪恩"),
                array(
                    "pic"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_4.png"
                ,"pic1"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_4_2.png"
                ,"name"=>"心形"),
                array(
                    "pic"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_5.png"
                ,"pic1"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_5_2.png"
                ,"name"=>"马眼形"),
                array(
                    "pic"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_6.png"
                ,"pic1"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_6_2.png"
                ,"name"=>"椭圆形"),
                array(
                    "pic"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_7.png"
                ,"pic1"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_7_2.png"
                ,"name"=>"梨形"),
                array(
                    "pic"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_8.png"
                ,"pic1"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_8_2.png"
                ,"name"=>"梯形"),
                array(
                    "pic"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_9.png"
                ,"pic1"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_9_2.png"
                ,"name"=>"祖母绿"),
                array(
                    "pic"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_10.png"
                ,"pic1"=>C('BaseUrl')."/images/stoneSearCh/Diamonds_10_2.png"
                ,"name"=>"三角形")
            )
        );
        return $SHAPE;
    }
    public static function GetStoneSearChPurity()
    {
        $PURITY =  array("title"=>"净度","keyword"=>"purity","values"=>array("FL","IF","VVS1","VVS2","VS1","VS2","SI1","SI2","SI3","I1"));
        return $PURITY;
    }
    public static function GetStoneSearChCut()
    {
        $CUT =  array("title"=>"切工","keyword"=>"cut","values"=>array("EX","VG","GD","FR"));
        return $CUT;
    }
    public static function GetStoneSearChPolishing()
    {
        $POLISHING =  array("title"=>"抛光","keyword"=>"polishing","values"=>array("EX","VG","GD","FR"));
        return $POLISHING;
    }
    public static function GetStoneSearChSymmetric()
    {
        $SYMMETRIC =  array("title"=>"对称","keyword"=>"symmetric","values"=>array("EX","VG","GD","FR"));
        return $SYMMETRIC;
    }
    public static function GetStoneSearChFluorescence()
    {
        $FLUORESCENCE =  array("title"=>"荧光","keyword"=>"Fluorescence","values"=>array("N","F","M","S","VS"));
        return $FLUORESCENCE;
    }
}
