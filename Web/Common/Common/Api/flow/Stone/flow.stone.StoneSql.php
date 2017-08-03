<?php
namespace Common\Common\Api\flow;
use Common\Common\PublicCode\FunctionCode;
trait StoneSql
{
    public static function GetSearChStoneConditionForSql($certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
        ,$price,$weight)
    {
        $where1 = "";
        $cont = "";
        if(!empty($certAuth))
        {
            $certAuth = str_replace(",","','",$certAuth);
            $where1  = $where1.$cont." certAuth in ('$certAuth') ";
            $cont = " and ";
        }
        if(!empty($color))
        {
            $color = str_replace(",","','",$color);
            $where1  = $where1.$cont." color in ('$color') ";
            $cont = " and ";
        }
        if(!empty($shape))
        {
            $shape = str_replace(",","','",$shape);
            $where1  = $where1.$cont." shape in ('$shape') ";
            $cont = " and ";
        }
        if(!empty($purity))
        {
            $purity = str_replace(",","','",$purity);
            $where1  = $where1.$cont." purity in ('$purity') ";
            $cont = " and ";
        }
        if(!empty($cut))
        {
            $cut = str_replace(",","','",$cut);
            $where1  = $where1.$cont." cut in ('$cut') ";
            $cont = " and ";
        }
        if(!empty($polishing))
        {
            $polishing = str_replace(",","','",$polishing);
            $where1  = $where1.$cont." polishing in ('$polishing') ";
            $cont = " and ";
        }
        if(!empty($symmetric))
        {
            $symmetric = str_replace(",","','",$symmetric);
            $where1  = $where1.$cont." symmetric in ('$symmetric') ";
            $cont = " and ";
        }
        if(!empty($fluorescence))
        {
            $fluorescence = str_replace(",","','",$fluorescence);
            $where1  = $where1.$cont." fluorescence in ('$fluorescence') ";
            $cont = " and ";
        }
        $and = "";
        $where2 = "";
        $priceArr = explode(',',$price);
        $weightArr = explode(',',$weight);
        if(floatval($priceArr[0])>=0 && !empty($priceArr[1]))
        {
            $where2  = $where2.$and." price BETWEEN $priceArr[0] and $priceArr[1] ";
            $and = " and ";
        }
        if(floatval($weightArr[0])>=0 && !empty($weightArr[1]))
        {
            $where2  = $where2.$and." weight BETWEEN $weightArr[0] and $weightArr[1] ";
            $and = " and ";
        }
        $and = "";
        $where = "";
        if(!empty($where1))
        {
            $where = " $where$and($where1)";
            $and = " and ";
        }
        if(!empty($where2))
        {
            $where = " $where$and($where2)";
        }
        if(!empty($where))
        {
            $where = " where$where";
        }
        return $where;
    }
    public static function Get_SearChStoneSql($cpage,$certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
        ,$price,$weight,$percent,$orderby)
    {
        $where = StoneCls::GetSearChStoneConditionForSql($certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
            ,$price,$weight);
        $cpage = intval($cpage)>0?$cpage:0;

        $pageCount = 30;//BaseCls::$EACH_PAGE_COUNT;
        if ($cpage <= 1) {
            $upnum = 0;
        } else {
            $upnum = ($cpage - 1) * $pageCount;
        }
        $limit = " LIMIT " . $upnum . "," . $pageCount;
        $PriceStr = "";
        $percentStr = "";
        if(empty($percent) || floatval($percent)==0)
        {
            $PriceStr = "IFNULL(Price,0.00)";
            $percentStr = "0 as percent";
        }
        else
        {
            //$PriceStr = "IFNULL(Price,0.00)";
            $PriceStr = "FORMAT(IFNULL(Price,0.00)*".(1+floatval($percent)/100).",2)";
            $percentStr = "$percent as percent";
        }
        $field = "id,IFNULL(CertAuth,'') as CertAuth,IFNULL(CertCode,'') as CertCode,IFNULL(Weight,'') as Weight
        ,$PriceStr as Price,$percentStr,IFNULL(Shape,'') as Shape,IFNULL(Color,'') as Color
        ,IFNULL(Purity,'') as Purity,IFNULL(Cut,'') as Cut,IFNULL(Polishing,'') as Polishing
        ,IFNULL(Symmetric,'') as Symmetric,IFNULL(Fluorescence,'') as Fluorescence";
        switch($orderby){
            case "weight_desc":
                $order = " ORDER BY weight desc ";
                break;
            case "weight_asc":
                $order = " ORDER BY weight asc ";
                break;
            default:
                $order = " ORDER BY id desc ";
        }
        $sql = "select $field from app_jewel_stone_product$where $order $limit";
        return $sql;
    }
    public static function Get_SearChStoneCountSql($certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
        ,$price,$weight)
    {
        $where = StoneCls::GetSearChStoneConditionForSql($certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
            ,$price,$weight);
        $sql = "select count(*) as num from app_jewel_stone_product$where";
        return $sql;
    }
    public static function GetSearChjpdianStoneConditionForSql($certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
        ,$price,$weight)
    {
        $where1 = "";
        $cont = "";
        if(!empty($certAuth))
        {
            $certAuth = str_replace(",","','",$certAuth);
            $where1  = $where1.$cont." a.report in ('$certAuth') ";
            $cont = " and ";
        }
        if(!empty($color))
        {
            $color = str_replace(",","','",$color);
            $where1  = $where1.$cont." a.color in ('$color') ";
            $cont = " and ";
        }
        if(!empty($shape))
        {
            $shape = str_replace(",","','",$shape);
            $where1  = $where1.$cont." b.`value` in ('$shape') ";
            $cont = " and ";
        }
        if(!empty($purity))
        {
            $purity = str_replace(",","','",$purity);
            $where1  = $where1.$cont." a.clarity in ('$purity') ";
            $cont = " and ";
        }
        if(!empty($cut))
        {
            $cut = str_replace(",","','",$cut);
            $where1  = $where1.$cont." a.cut in ('$cut') ";
            $cont = " and ";
        }
        if(!empty($polishing))
        {
            $polishing = str_replace(",","','",$polishing);
            $where1  = $where1.$cont." a.polish in ('$polishing') ";
            $cont = " and ";
        }
        if(!empty($symmetric))
        {
            $symmetric = str_replace(",","','",$symmetric);
            $where1  = $where1.$cont." a.symmetry in ('$symmetric') ";
            $cont = " and ";
        }
        if(!empty($fluorescence))
        {
            $fluorescence = str_replace(",","','",$fluorescence);
            $where1  = $where1.$cont." a.fluorescence in ('$fluorescence') ";
            $cont = " and ";
        }
        $and = "";
        $where2 = "";
        $priceArr = explode(',',$price);
        $weightArr = explode(',',$weight);
        if(floatval($priceArr[0])>=0 && !empty($priceArr[1]))
        {
            $where2  = $where2.$and." a.saledollorprice BETWEEN $priceArr[0] and $priceArr[1] ";
            $and = " and ";
        }
        if(floatval($weightArr[0])>=0 && !empty($weightArr[1]))
        {
            $where2  = $where2.$and." a.carat BETWEEN $weightArr[0] and $weightArr[1] ";
            $and = " and ";
        }
        $and = "";
        $where = "";
        if(!empty($where1))
        {
            $where = " $where$and($where1)";
            $and = " and ";
        }
        if(!empty($where2))
        {
            $where = " $where$and($where2)";
            $and = " and ";
        }
        $where = $and.$where;
        return $where;
    }
    public static function Get_SearChjpdianStoneCountSql($certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
        ,$price,$weight)
    {
        $where = StoneCls::GetSearChjpdianStoneConditionForSql($certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
            ,$price,$weight);
        $sql = "SELECT
                  count(*) as num
                FROM
                app_jpdiam_white_stone_data a LEFT JOIN app_jpdiam_wordbook b
            on a.shape=b.keywrod where b.kind='stoneShape' $where";
        return $sql;
    }
    public static function GetUpdatejpdianStone($cpage,$pageCount)
    {
        $pageCount = $pageCount;
        if ($cpage <= 1) {
            $upnum = 0;
        } else {
            $upnum = ($cpage - 1) * $pageCount;
        }
        $limit = " LIMIT " . $upnum . "," . $pageCount;
        $field = "IFNULL(stoneid,'') as Barcode,IFNULL(a.carat,'') as Weight,IFNULL(a.saledollorprice,0.00) as Price,IFNULL(IFNULL(b.`value`,a.shape),'') as Shape
        ,IFNULL(a.color,'') as Color,IFNULL(a.clarity,'') as Purity,IFNULL(a.cut,'') as Cut,IFNULL(a.polish,'') as Polishing
        ,IFNULL(a.symmetry,'') as Symmetric,IFNULL(a.fluorescence,'') as Fluorescence
        ,IFNULL(a.report,'') as CertAuth,IFNULL(a.reportno,'') as CertCode,3 as Source,'HKJP' as StoreName,now() as InDate";
        $sql = "SELECT
                  $field
                FROM
                app_jpdiam_white_stone_data a LEFT JOIN app_jpdiam_wordbook b
                on a.shape=b.keywrod where b.kind='stoneShape' $limit";
        return $sql;
    }
    public static function Get_SearChjpdianStone($cpage,$certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
        ,$price,$weight,$percent,$orderby)
    {
        $where = StoneCls::GetSearChjpdianStoneConditionForSql($certAuth,$color,$shape,$purity,$cut,$polishing,$symmetric,$fluorescence
            ,$price,$weight);
        $cpage = intval($cpage)>0?$cpage:0;

        $pageCount = 30;//BaseCls::$EACH_PAGE_COUNT;
        if ($cpage <= 1) {
            $upnum = 0;
        } else {
            $upnum = ($cpage - 1) * $pageCount;
        }
        $limit = " LIMIT " . $upnum . "," . $pageCount;
        $PriceStr = "";
        $percentStr = "";
        if(empty($percent) || floatval($percent)==0)
        {
            $PriceStr = "IFNULL(a.saledollorprice,0.00)";
            $percentStr = "0 as percent";
        }
        else
        {
            //$PriceStr = "IFNULL(Price,0.00)";
            $PriceStr = "FORMAT(IFNULL(a.saledollorprice,0.00)*".(1+floatval($percent)/100).",2)";
            $percentStr = "$percent as percent";
        }
        $field = "a.id,IFNULL(a.carat,'') as Weight,$PriceStr as Price,IFNULL(IFNULL(b.`value`,a.shape),'') as Shape
        ,IFNULL(a.color,'') as Color,IFNULL(a.clarity,'') as Purity,IFNULL(a.cut,'') as Cut,IFNULL(a.polish,'') as Polishing
        ,IFNULL(a.symmetry,'') as Symmetric,IFNULL(a.fluorescence,'') as Fluorescence
        ,IFNULL(a.report,'') as CertAuth,IFNULL(a.reportno,'') as CertCode";
        switch($orderby){
            case "weight_desc":
                $order = " ORDER BY a.carat desc ";
                break;
            case "weight_asc":
                $order = " ORDER BY a.carat asc ";
                break;
            default:
                $order = " ORDER BY a.id desc ";
        }
        $sql = "SELECT
                  $field
                FROM
                app_jpdiam_white_stone_data a LEFT JOIN app_jpdiam_wordbook b
            on a.shape=b.keywrod where b.kind='stoneShape' $where $order $limit";
        return $sql;
    }
}