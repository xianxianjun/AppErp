<?php

/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/10/29
 * Time: 14:08
 */
//用户
namespace Common\Common\Api\flow;
require_once 'UpdateCls/FLOW.UPDATECLS.INC.php';
class UpdateCls
{
    use UpdateModel,UpdateOrder,UpdateModelImages,UpdateClsCustom;
    public static $priorSortForPic = 1000;//自动更新图片排序优先级
}