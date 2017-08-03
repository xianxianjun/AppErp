<?php
namespace Common\Common\Api\flow;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\flow\ModelOrderCls;
use Common\Common\PublicCode\BllPublic;
trait ModelOrderSql
{

    public static function Every_Stone_Price_For_Id_Sql($id,$member_id)
    {
        $sql = "SELECT
                IFNULL(p.price ,'') AS sprice,
                IFNULL(pa.price ,'') AS saprice,
                IFNULL(pb.price ,'') AS sbprice,
                IFNULL(pb.price ,'') AS scprice,
                a.*
                    FROM
                        app_model_main_order_current_detail a
                    LEFT JOIN app_stone_price p ON p.stone_category_id = a.stone_category_id
                    AND p.stone_color_id = a.stone_color_id
                    AND p.stone_purity_id = a.stone_purity_id
                    AND p.stone_spec_id = a.stone_spec_id
                    LEFT JOIN app_stone_price pa ON pa.stone_category_id = a.stone_category_id_A
                    AND pa.stone_color_id = a.stone_color_id_A
                    AND pa.stone_purity_id = a.stone_purity_id_A
                    AND pa.stone_spec_id = a.stone_spec_id_A
                    LEFT JOIN app_stone_price pb ON pb.stone_category_id = a.stone_category_id_B
                    AND pb.stone_color_id = a.stone_color_id_B
                    AND pb.stone_purity_id = a.stone_purity_id_B
                    AND pb.stone_spec_id = a.stone_spec_id_B
                    LEFT JOIN app_stone_price pc ON pc.stone_category_id = a.stone_category_id_C
                    AND pc.stone_color_id = a.stone_color_id_C
                    AND pc.stone_purity_id = a.stone_purity_id_C
                    AND pc.stone_spec_id = a.stone_spec_id_C
                    WHERE
                        a.orderStatus <= ".ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000."
                    AND a.member_id = ".$member_id." and a.id=".$id;
        return $sql;
    }
    public static function Stone_Price_For_Id_Sql($id,$member_id)
    {
        $sql = "select sprice*stone_number+saprice*stone_number_A+sbprice*stone_number_B+scprice*stone_number_C as stonePrice,erpTypeId,number from
                (SELECT
                IFNULL(p.price ,0) AS sprice,
                IFNULL(pa.price ,0) AS saprice,
                IFNULL(pb.price ,0) AS sbprice,
                IFNULL(pb.price ,0) AS scprice,
                a.stone_category_id,
                a.stone_color_id,
                a.stone_purity_id,
                a.stone_spec_id,
                IFNULL(a.stone_number,0) as stone_number,
                a.stone_category_id_A,
                a.stone_color_id_A,
                a.stone_purity_id_A,
                a.stone_spec_id_A,
                IFNULL(a.stone_number_A,0) as stone_number_A,
                a.stone_category_id_B,
                a.stone_color_id_B,
                a.stone_purity_id_B,
                a.stone_spec_id_B,
                IFNULL(a.stone_number_B,0) as stone_number_B,
                a.stone_category_id_C,
                a.stone_color_id_C,
                a.stone_purity_id_C,
                a.stone_spec_id_C,
                IFNULL(a.stone_number_C,0) as stone_number_C,
                top1c.erpTypeId,
                a.number
                    FROM
                        app_model_main_order_current_detail a
                LEFT JOIN (
                        SELECT
                            ampc.id,
                            ampc.model_category_id,
                            ampc.model_product_id,
                            amc11.erpTypeId
                        FROM
                            app_model_product_category ampc
                        INNER JOIN (
                            SELECT
                                *
                            FROM
                                app_model_category amc1
                            WHERE
                                EXISTS (
                                    SELECT
                                        *
                                    FROM
                                        app_model_category
                                    WHERE
                                        sign = 'mode'
                                    AND amc1.model_category_id = id
                                )
                        ) amc11 ON ampc.model_category_id = amc11.id
                        WHERE
                            EXISTS (
                                SELECT
                                    *
                                FROM
                                    app_model_main_order_current_detail
                                WHERE
                                    member_id = ".$member_id."
                                AND orderStatus <= ".ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000."
                                AND model_product_id = ampc.model_product_id
                            )
                        GROUP BY
                            model_product_id
                    ) top1c ON top1c.model_product_id = a.model_product_id
                    LEFT JOIN app_stone_price p ON p.stone_category_id = a.stone_category_id
                    AND p.stone_color_id = a.stone_color_id
                    AND p.stone_purity_id = a.stone_purity_id
                    AND p.stone_spec_id = a.stone_spec_id
                    LEFT JOIN app_stone_price pa ON pa.stone_category_id = a.stone_category_id_A
                    AND pa.stone_color_id = a.stone_color_id_A
                    AND pa.stone_purity_id = a.stone_purity_id_A
                    AND pa.stone_spec_id = a.stone_spec_id_A
                    LEFT JOIN app_stone_price pb ON pb.stone_category_id = a.stone_category_id_B
                    AND pb.stone_color_id = a.stone_color_id_B
                    AND pb.stone_purity_id = a.stone_purity_id_B
                    AND pb.stone_spec_id = a.stone_spec_id_B
                    LEFT JOIN app_stone_price pc ON pc.stone_category_id = a.stone_category_id_C
                    AND pc.stone_color_id = a.stone_color_id_C
                    AND pc.stone_purity_id = a.stone_purity_id_C
                    AND pc.stone_spec_id = a.stone_spec_id_C
                    WHERE
                        a.orderStatus <= ".ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000."
                    AND a.member_id = ".$member_id." and a.id=".$id.") aa";
        return $sql;
    }
    public static function Current_Order_List_For_Price_Sql($member_id,$cpage)
    {
        $limit = "";
        if(FunctionCode::isInteger($cpage))
        {
            $pageCount = 8;//BaseCls::$EACH_PAGE_COUNT;
            $num = $cpage*$pageCount;
            $limit = " LIMIT 0,".$num;
        }
        $where = " AND orderStatus=".ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0." ";
        $sql =  ModelOrderCls::Current_Order_List_Base_Sql($member_id,$where,$limit);
        return $sql;
    }

    public static function Current_Order_List_Sql($member_id,$cpage)
    {
        if(FunctionCode::isInteger($cpage))
        {
            $pageCount = 8;//BaseCls::$EACH_PAGE_COUNT;
            if ($cpage <= 1) {
                $upnum = 0;
            } else {
                $upnum = ($cpage - 1) * $pageCount;
            }
            $limit = " LIMIT " . $upnum . "," . $pageCount;
        }
        $where = " AND orderStatus =".ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0." ";
        $sql = ModelOrderCls::Current_Order_List_Base_Sql($member_id,$where,$limit);
        return $sql;
    }
    public static function Current_Order_List_Count_Sql($member_id)
    {
        $where = " AND orderStatus =".ModelOrderCls::$ORDER_STATUS_NOT_CREATE_ORDER_BEFORRE_0." ";
        $sql = ModelOrderCls::Current_Order_List_Count_Base_Sql($member_id,$where);
        return $sql;
    }
    public static function Current_Order_List_Base_Sql($member_id,$where = '',$limit = '',$addField = "",$joinTable="",$whereAfter="")
    {

        $sql = "SELECT
                        b.`name`,
                        b.modelNum,
                        b.weight,
                        ifnull(b.price,0) as price,
                        top1c.model_category_id,
                        top1c.erpTypeId,
                        ".BllPublic::SqlConnPicHttpBasePath("b.pic").",
                        IFNULL(p.price, 0) * IFNULL(a.stone_number, 0) + IFNULL(pa.price, 0) * IFNULL(a.stone_number_A, 0) + IFNULL(pb.price, 0) * IFNULL(a.stone_number_B, 0) + IFNULL(pc.price, 0) * IFNULL(a.stone_number_C, 0) AS stonePrice,
                        a.*".$addField."
                    FROM
                        app_model_main_order_current_detail a
                    LEFT JOIN (
                        SELECT
                            ampc.id,
                            ampc.model_category_id,
                            ampc.model_product_id,
                            amc11.erpTypeId,
                            amc11.name as categoryName
                        FROM
                            app_model_product_category ampc
                        INNER JOIN (
                            SELECT
                                *
                            FROM
                                app_model_category amc1
                            WHERE
                                EXISTS (
                                    SELECT
                                        *
                                    FROM
                                        app_model_category
                                    WHERE
                                        sign = 'mode'
                                    AND amc1.model_category_id = id
                                )
                        ) amc11 ON ampc.model_category_id = amc11.id
                        WHERE
                            EXISTS (
                                SELECT
                                    *
                                FROM
                                    app_model_main_order_current_detail
                                WHERE
                                    member_id = ".$member_id."
                                ".$where."
                                AND model_product_id = ampc.model_product_id
                            )
                        GROUP BY
                            model_product_id
                    ) top1c ON top1c.model_product_id = a.model_product_id
                    INNER JOIN app_model_product b ON a.model_product_id = b.id "
                    .$joinTable
                    ." LEFT JOIN app_stone_price p ON p.stone_category_id = a.stone_category_id
                    AND p.stone_color_id = a.stone_color_id
                    AND p.stone_purity_id = a.stone_purity_id
                    AND p.stone_spec_id = a.stone_spec_id
                    LEFT JOIN app_stone_price pa ON pa.stone_category_id = a.stone_category_id_A
                    AND pa.stone_color_id = a.stone_color_id_A
                    AND pa.stone_purity_id = a.stone_purity_id_A
                    AND pa.stone_spec_id = a.stone_spec_id_A
                    LEFT JOIN app_stone_price pb ON pb.stone_category_id = a.stone_category_id_B
                    AND pb.stone_color_id = a.stone_color_id_B
                    AND pb.stone_purity_id = a.stone_purity_id_B
                    AND pb.stone_spec_id = a.stone_spec_id_B
                    LEFT JOIN app_stone_price pc ON pc.stone_category_id = a.stone_category_id_C
                    AND pc.stone_color_id = a.stone_color_id_C
                    AND pc.stone_purity_id = a.stone_purity_id_C
                    AND pc.stone_spec_id = a.stone_spec_id_C
                     WHERE
                        a.member_id = ".$member_id."
                        ".$where."
                        ".$whereAfter."
                    ORDER BY
                        id DESC".$limit;
        //echo $sql;
        return $sql;
    }
    public static function Current_Order_List_Count_Base_Sql($member_id,$where = '')
    {
        $sql = "SELECT
                    count(*) as cou
                FROM
                    app_model_main_order_current_detail a
                WHERE
                    EXISTS (
                        SELECT
                            *
                        FROM
                            app_model_product
                        WHERE
                            id = a.model_product_id
                    )
                ".$where." AND member_id=".$member_id;
        return $sql;
        /*
         * AND EXISTS (
                    SELECT
                        *
                    FROM
                        app_stone_price
                    WHERE
                        a.stone_category_id = stone_category_id
                    AND a.stone_color_id = stone_color_id
                    AND a.stone_purity_id = stone_purity_id
                    AND a.stone_spec_id = stone_spec_id
                )
                AND EXISTS (
                    SELECT
                        *
                    FROM
                        app_stone_price
                    WHERE
                        a.stone_category_id_A = stone_category_id_A
                    AND a.stone_color_id_A = stone_color_id_A
                    AND a.stone_purity_id_A = stone_purity_id_A
                    AND a.stone_spec_id_A = stone_spec_id_A
                )
                AND EXISTS (
                    SELECT
                        *
                    FROM
                        app_stone_price
                    WHERE
                        a.stone_category_id_B = stone_category_id_B
                    AND a.stone_color_id_B = stone_color_id_B
                    AND a.stone_purity_id_B = stone_purity_id_B
                    AND a.stone_spec_id_B = stone_spec_id_B
                )
                AND EXISTS (
                    SELECT
                        *
                    FROM
                        app_stone_price
                    WHERE
                        a.stone_category_id_C = stone_category_id_C
                    AND a.stone_color_id_C = stone_color_id_C
                    AND a.stone_purity_id_C = stone_purity_id_C
                    AND a.stone_spec_id_C = stone_spec_id_C
                )
         */
    }
    public static function Get_Order_Group_By_Status_Sql($member_id)
    {
        $sql = "SELECT
            orderStatus,
            count(*) num
        FROM
            app_model_main_order_current
        WHERE
            member_id = ".$member_id."
        AND orderStatus >= 0
        GROUP BY
            orderStatus";
        return $sql;
    }
}