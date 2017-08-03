<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/23
 * Time: 11:41
 */
//商品
namespace Common\Common\Api\flow;
require_once 'Model/ModelCls/FLOW.MODEL.MODELCLS.INC.php';
class ModelCls
{
    use ModelCategory,Model,ModelCategoryFiler,ModelCofing,ModelDetail,ModelClsSql,ModelClsFunction;
}