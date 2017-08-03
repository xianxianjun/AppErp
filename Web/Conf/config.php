<?php
$baseConfig = array(
    //'配置项'=>'配置值'
    /*'BaseUrl'=>'http://192.168.1.240:9112/',
    'BaseUrlApi'=>"http://192.168.1.240:9112/api/aproxy/"*/
    'BaseUrl'=>'http://appapi.fanerweb.com/',
    'BaseUrlApi'=>'http://appapi.fanerweb.com/api/Aproxy/',
    'PicBasePath'=>'http://124.172.169.117:9888/',

    'ErpBaseReceiveUrl' => 'http://localhost:21111/Receive',
    'ErpBaseRespondUrl' => 'http://localhost:21111/Respond',
    /*'ErpBaseReceiveUrl' => 'http://211.162.71.165:1113/Receive',
    'ErpBaseRespondUrl' => 'http://211.162.71.165:1113/Respond',*/
    //'ErpBaseReceiveUrl' => 'http://211.162.71.165:9111/Receive',
    //'ErpBaseRespondUrl' => 'http://211.162.71.165:9111/Respond',
    //'ErpBaseReceiveUrl' => 'http://211.162.71.165:9112/Receive',
    //'ErpBaseRespondUrl' => 'http://211.162.71.165:9112/Respond',
    //'ErpBaseReceiveUrl' => 'http://211.162.71.165:9113/Receive',
    //'ErpBaseRespondUrl' => 'http://211.162.71.165:9113/Respond',
    //'ErpBaseReceiveUrl' => 'http://211.162.71.165:9114/Receive',
    //'ErpBaseRespondUrl' => 'http://211.162.71.165:9114/Respond',
    //'ErpBaseReceiveUrl' => 'http://211.162.71.165:9115/Receive',
    //'ErpBaseRespondUrl' => 'http://211.162.71.165:9115/Respond',
    //'ErpBaseReceiveUrl' => 'http://211.162.71.165:9115/Receive',
    //'ErpBaseRespondUrl' => 'http://211.162.71.165:9115/Respond',

    'isTest'=>'1',
    'adminUser'=>'qianxi',
    'adminPassword' => 'mstar',
    'webUsername' => 'oa.mstar.cn',
    'webKeyword' => '201606161630',

    'AndroidUrl'=>'https://www.pgyer.com/kbRT',


    'YunPianAipKey'=>'489ffa6f41394d0e7201764e3fcda8bf',
    'YunPianAipText'=>'【yoour钻戒】你的验证码是'
    /*'db_type'  => 'mysql',
    'db_user'  => 'mstar',
    'db_pwd'   => 'mstarpw#@$',
    'db_host'  => '120.25.13.223',
    'db_port'  => '3306',
    'db_name'  => 'qxappstoreLine_jz0755'*/
);
return $baseConfig;
//return array_merge(include 'indexPage.php',$baseConfig);