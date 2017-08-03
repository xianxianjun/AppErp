<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
    <title>管理后台</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="/jNotify/jNotify.jquery.css">
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <script type="text/javascript" src="/jNotify/jNotify.jquery.js"></script>
    <script type="text/javascript" src="/js/Webconfig.js"></script>
    <script type="text/javascript" src="/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="/js/QxPublic.js"></script>
    <link rel="stylesheet" type="text/css" href="/images/forImage/Style/skin.css" />
    <link rel="shortcut icon" href="/images/favicon.ico" mce_href="/images/favicon.ico" type="image/x-icon" />
    


</head>
<body>


    <table cellpadding="0" width="100%" height="64" background="/images/forImage/Images/top_top_bg.gif">
        <tr valign="top">
            <td width="50%"><a href="javascript:void(0)"><img style="border:none" src="/images/forImage/Images/logo.png" /></a></td>
            <td width="40%" style="padding-top:13px;font:15px Arial,SimSun,sans-serif;color:#FFF;text-align: right;"><!--管理员：<b>RainMan</b> 您好，感谢登陆使用！--><div style="line-height: 20px;" id="timeShow"></div></td>
            <td style="padding-top:10px;" align="center"><a href="javascript:void(0)"><!--<img style="border:none" src="/images/forImage/Images/index.gif" />--></a></td>
            <td style="padding-top:10px;" align="center"><a href="javascript:void(0)"><img style="border:none" src="/images/forImage/Images/out.gif" onclick="logout();" /></td>
        </tr>
    </table>
        <script language="javascript">
        var t = null;
        t = setTimeout(time,1000);//开始执行
        function time()
        {
            clearTimeout(t);//清除定时器
            dt = new Date();
            var h=dt.getHours();
            var m=dt.getMinutes();
            var s=dt.getSeconds();
            document.getElementById("timeShow").innerHTML =  "现在的时间为："+h+"时"+m+"分"+s+"秒";
            t = setTimeout(time,1000); //设定定时器，循环执行
        }
            function logout()
            {
                //loginOut
                $.get('/admin/AdminApi/loginOut', function (data) {
                    top.location.reload();
                    //setTimeout("location.reload()", 200);
                },'json');
            }
    </script>




</body>
</html>