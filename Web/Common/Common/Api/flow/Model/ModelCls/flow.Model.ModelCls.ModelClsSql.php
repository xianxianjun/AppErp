<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/10/14
 * Time: 16:41
 */
namespace Common\Common\Api\flow;
trait ModelClsSql
{
    public static function Get_Model_TOP1_ModeCategory_Id_Sql($id)
    {
        $sql = "SELECT
                    model_category_id
                FROM
                    app_model_product_category c1
                WHERE
                model_product_id = ".$id."
                AND
                    EXISTS (
                        SELECT
                            *
                        FROM
                            app_model_category c2
                        WHERE
                            EXISTS (
                                SELECT
                                    *
                                FROM
                                    app_model_category
                                WHERE
                                    c2.model_category_id = id
                                AND sign = 'mode'
                            )
                        AND c1.model_category_id = c2.id
                    ) LIMIT 1";
        return $sql;
    }
    public static function Get_Model_Include_Stone_Price($id)
    {
        $sql = "select a.*,
                IFNULL(p.price ,'') AS sprice,
                IFNULL(pa.price ,'') AS saprice,
                IFNULL(pb.price ,'') AS sbprice,
                IFNULL(pb.price ,'') AS scprice
                 from app_model_product a
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
                    where a.id=".$id;
        return $sql;
    }
}