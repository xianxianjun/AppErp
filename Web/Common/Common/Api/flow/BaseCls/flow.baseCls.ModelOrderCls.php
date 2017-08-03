<?php
namespace Common\Common\Api\flow;
use Common\Common\PublicCode\ErpPublicCode;
use Think\Think;

trait BaseModelOrderCls
{
    public static function GetOrderInfoByitemId($itemId)
    {
        $model = new \Think\Model();
        $result = $model->query("SELECT
                            *
                        FROM
                            app_model_main_order_current a
                        WHERE
                            EXISTS (
                                SELECT
                                    *
                                FROM
                                    app_model_main_order_current_detail
                                WHERE
                                    id = ".$itemId."
                                AND IFNULL(
                                    model_main_order_current_id,
                                    ''
                                ) <> ''
                                AND a.id = model_main_order_current_id
                            )");
        if(!empty($result) && count($result)>0)
        {
            return $result[0];
        }
        return null;
    }
    public static function GetOrderInfoById($orderId)
    {
        $result = M("model_main_order_current")->where(array("id"=>$orderId))->select();
        if(!empty($result) && count($result)>0)
        {
            return $result[0];
        }
        return null;
    }
}