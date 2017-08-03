<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/22
 * Time: 17:27
 */
namespace Common\Common\Api\flow;
//基本信息
require_once 'BaseCls/FLOW.BASECLS.INC.php';
class BaseCls
{
    use Area,CacheForModel,BaseModelOrderCls;
    public static $CACHE_TIME = 60;//缓存时间
    public static $EACH_PAGE_COUNT = 10;//系统全局分页数
}