<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/10/14
 * Time: 17:04
 */
namespace Common\Common\Api\flow;
trait ModelClsFunction
{
    public static function getTop1ModelCategoryId($id)
    {
        $Model = new \Think\Model();
        $data = $Model->query(ModelCls::Get_Model_TOP1_ModeCategory_Id_Sql($id));
        if(count($data)>0)
        {
            return $data[0]["model_category_id"];
        }
        return null;
    }
}