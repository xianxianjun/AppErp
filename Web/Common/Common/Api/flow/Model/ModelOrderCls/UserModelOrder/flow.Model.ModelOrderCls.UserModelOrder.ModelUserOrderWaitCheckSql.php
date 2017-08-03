<?php
namespace Common\Common\Api\flow;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\BllPublic;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ConvertType;
use Common\Common\PublicCode\WithSql;
use Common\Common\PublicCode\ErpPublicCode;
trait ModelUserOrderWaitCheckSql
{
    public static function Stone_Price_For_WaitCheck_OrderList_Sql($ids, $member_id)
    {
        $where = "AND orderStatus>".ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0." AND orderStatus <=".ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000." AND model_main_order_current_id in (".$ids.")";
        $sql = ModelOrderCls::Current_Order_List_Base_Sql($member_id,$where,"",",b.price as modelPrice");
        $sql = "select * from (".$sql.") aac order by model_main_order_current_id desc";
        return $sql;

    }
    public static function Stone_Price_For_WaitCheck_OrderItem_Sql($member_id,$orderId,$cpage,$pageCount = '')
    {
        if(FunctionCode::isInteger($cpage))
        {
            if(empty($pageCount)) {
                $pageCount = 3;//BaseCls::$EACH_PAGE_COUNT;
            }
            if ($cpage <= 1) {
                $upnum = 0;
            } else {
                $upnum = ($cpage - 1) * $pageCount;
            }
            $limit = " LIMIT " . $upnum . "," . $pageCount;
        }
        $where = "AND model_main_order_current_id=".$orderId." AND orderStatus>".ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0." AND orderStatus <=".ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000." ";
        $sql = ModelOrderCls::Current_Order_List_Base_Sql($member_id,$where,$limit,",concat('".BllPublic::GetPicBasePath()."',b.pic) as fpic,ifnull(b.price,0) as modelPrice");
        return $sql;
    }
    public static function Stone_Price_For_WaitCheck_OrderItem_Count_Sql($member_id,$orderId)
    {
        $where = "AND model_main_order_current_id=".$orderId." AND orderStatus>".ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0." AND orderStatus <=".ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000." ";
        $sql = ModelOrderCls::Current_Order_List_Count_Base_Sql($member_id,$where);
        return $sql;
    }
    public static function Current_Order_List_For_Wait_Check_Price_Sql($member_id,$orderId)
    {
        $limit = "";
        $where = "AND model_main_order_current_id=".$orderId." AND orderStatus>".ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0." AND orderStatus <=".ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000." ";
        $sql =  ModelOrderCls::Current_Order_List_Base_Sql($member_id,$where,$limit);
        return $sql;
    }
    public static function Current_Main_Order_Submit_To_Erp_Sql($orderId,$member_id)
    {
        $sql = "SELECT
                orderNum AS OrderID,
                model_quality_id AS QualityID,
                2 AS FactoryID,
                model_purity_id AS PurityID,
                customerId AS CustomerID,
                word AS Sigil,
                0.00 AS GoldPrice,
                createDate AS OrderDate,
                CURRENT_TIMESTAMP () AS ConfirmDate,
                date_add(
                    CURRENT_TIMESTAMP (),
                    INTERVAL 15 DAY
                ) AS ShipDate,
                orderNote AS OrderMemo,
                member_address_id,
                invoiceTitle,
                invoiceType,
                erp_client_id,".$member_id." as appCustomerID
            FROM
                app_model_main_order_current where member_id=".$member_id." and id=".$orderId
            ." and orderStatus>".ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0
            ." and orderStatus<=".ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000;
        return $sql;
    }
    public static function Current_Main_Order_Items_Submit_To_Erp_Sql($orderId,$member_id)
    {
        $whereAfter =" AND moc.id in (".$orderId.")"
            ." AND moc.orderStatus>".ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0
            ." AND moc.orderStatus<=".ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000;
        $joinTable = " INNER JOIN app_model_main_order_current moc ON moc.id=a.model_main_order_current_id"
            ." LEFT JOIN app_stone_spec sp on sp.id=a.stone_spec_id"
            ." LEFT JOIN app_stone_spec spa on spa.id=a.stone_spec_id_A"
            ." LEFT JOIN app_stone_spec spb on spb.id=a.stone_spec_id_B"
            ." LEFT JOIN app_stone_spec spc on spc.id=a.stone_spec_id_C";
        $addField =  ",moc.orderNum"
            .",CONCAT(sp.up,'~',sp.down) as spec"
            .",CONCAT(spa.up,'~',spa.down) as speca"
            .",CONCAT(spb.up,'~',spb.down) as specb"
            .",CONCAT(spc.up,'~',spc.down) as specc"
            .",IFNULL(p.price, 0) * IFNULL(a.stone_number, 0) as stonePriceMain"
            .",IFNULL(pa.price, 0) * IFNULL(a.stone_number_A, 0) as stonePriceA"
            .",IFNULL(pb.price, 0) * IFNULL(a.stone_number_B, 0) as stonePriceB"
            .",IFNULL(pc.price, 0) * IFNULL(a.stone_number_C, 0) as stonePriceC"
            .",IFNULL(p.price, 0) as unitStonePrice,IFNULL(pa.price, 0) as unitStonePriceA,IFNULL(pb.price, 0) as unitStonePriceB,IFNULL(pc.price, 0) as unitStonePriceC"
            .",a.id as appDetailID,top1c.categoryName,b.name as modelTitle,b.pic as productPic,a.isSelfStone as IsCSP";

        $sql = ModelOrderCls::Current_Order_List_Base_Sql($member_id,"","",$addField,$joinTable,$whereAfter);
        $sql = "select orderNum as OrderID,erpTypeId as TypeID"
            .",IF (@model = modelNum ,@rank:=@rank + 1 ,@rank := 0) as SameModuleIndex"
            .",(@model := modelNum) as ModuleID,number as QuantityDetail,handSize as Perimeter,0.00 as Fee"
            .",IF(stone_category_id=-1,NULL,stone_category_id) as StoneP,stone_spec_value as StonePSpecs,IF(stone_number=-1,NULL,stone_number) as StonePQuantity,IF(stone_shape_id=-1,NULL,stone_shape_id) as StonePFigure"
            .",IF(stone_category_id_A=-1,NULL,stone_category_id_A) as StoneSA,stone_spec_value_A as StoneSASpecs,IF(stone_number_A=-1 or stone_number_A=0,NULL,stone_number_A) as StoneSAQuantity,IF(stone_shape_id_A=-1 or stone_shape_id_A=0,NULL,stone_shape_id_A) as StoneSAFigure"
            .",IF(stone_category_id_B=-1,NULL,stone_category_id_B) as StoneSB,stone_spec_value_B as StoneSBSpecs,IF(stone_number_B=-1 or stone_number_B=0,NULL,stone_number_B) as StoneSBQuantity,IF(stone_shape_id_B=-1 or stone_shape_id_B=0,NULL,stone_shape_id_B) as StoneSBFigure"
            .",IF(stone_category_id_C=-1,NULL,stone_category_id_C) as StoneSC,stone_spec_value_C as StoneSCSpecs,IF(stone_number_C=-1 or stone_number_C=0,NULL,stone_number_C) as StoneSCQuantity,IF(stone_shape_id_C=-1 or stone_shape_id_C=0,NULL,stone_shape_id_C) as StoneSCFigure"
            .",stonePriceMain as StonePrice,stonePriceA,stonePriceB,stonePriceC"
            .",spec,speca,specb,specc"
            .",stone_color_id,stone_color_id_A,stone_color_id_B,stone_color_id_C"
            .",stone_purity_id,stone_purity_id_A,stone_purity_id_B,stone_purity_id_C"
            .",weight,model_product_id,model_category_id,remarks as Memo,categoryName,appDetailID,modelTitle,modelNum,productPic,IsCSP"
            .",unitStonePrice,unitStonePriceA,unitStonePriceB,unitStonePriceC"
            .",stone_out_A,stone_out_B,stone_out_C"
            ." from (".$sql.") orderItemInfo,(select @model:='',@rank:='') b order by modelNum";
        $sql = "select @rownum := @rownum +1 as DetailID,aatable.* from (".$sql.") aatable,(select @rownum := 0) b order by appDetailID desc";
        //echo $sql;
        return $sql;
    }
}