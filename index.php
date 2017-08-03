<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);

// 定义应用目录
//define('BIND_MODULE','Api');
//define('BIND_MODULE','Admin');
/*$version = "";
$subject = "";
if(isset($_GET["QxVersion"]))
{
    $version = $_GET["QxVersion"];
}
if(isset($_GET["QxSubject"]))
{
    $subject = $_GET["QxSubject"];
}
if(empty($subject))
{
    $strl = explode("/",$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    if(strtolower($strl[count($strl)-2]) == "qxsubject") {
        $subject = end($strl);
    }
}
if(strtolower($subject) == "jz0755")
{
    define('APP_PATH', './JZ0755Web/');
    define('CONF_PATH', './JZ0755Web/Conf/');
}
else {
    if (strtolower($version) == "beta1.2") {
        define('APP_PATH', './WebBeta1_2/');
        define('CONF_PATH', './WebBeta1_2/Conf/');
    } else if (strtolower(substr($version, 0, strlen('beta'))) == 'beta') {
        define('APP_PATH', './WebBeta/');
        define('CONF_PATH', './WebBeta/Conf/');
    } else {
        define('APP_PATH', './Web/');
        define('CONF_PATH', './Web/Conf/');
    }
}*/
//define('APP_PATH','./Web/');
define('QXVERSION', 'mstar');
define('VERSION', '1.0');
if(isset($_GET["QxVersion"]))
{
    $version = $_GET["QxVersion"];
}
else
{
    $strl = explode("/",$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    if(strtolower($strl[count($strl)-2]) == "qxversion") {
        $version = end($strl);
    }
}
define('APP_PATH', './Web/');
define('CONF_PATH', './Web/Conf/');
define('BASE_PATH',str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/");
define('WEIXINPAY_PATH',str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/".'WxApi/payment/');
//define('CONF_PATH', './Web/Conf/');
// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单