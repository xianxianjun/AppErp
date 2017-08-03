<extend name="public/layout"/>
<block name="head">
        <style type="text/css">
        .align-center{
            position:fixed;left:50%;top:50%;margin-left:width/2;margin-top:height/2;
        }
        .circlelogin {
            width: 5px;
            height: 5px;
            background-color: #b3895f;
            -moz-border-radius: 50px;
            -webkit-border-radius: 50px;
            border-radius: 50px;
            float: left;
            margin-top: 8px;
            margin-right: 5px;
        }
        .diamondLogin {
            float: left;
            width: 5px;
            height: 5px;
            background: #b3895f;
            /* Rotate */
            -webkit-transform: rotate(-45deg);
            -moz-transform: rotate(-45deg);
            -ms-transform: rotate(-45deg);
            -o-transform: rotate(-45deg);
            transform: rotate(-45deg);
            /* Rotate Origin */
            -webkit-transform-origin: 0 100%;
            -moz-transform-origin: 0 100%;
            -ms-transform-origin: 0 100%;
            -o-transform-origin: 0 100%;
            transform-origin: 0 100%;
            margin: 8px 5px;
        }
    </style>
</block>
<block name="content">
    <table width="100%">
        <!-- 顶部部分 -->
        <tr height="41"><td colspan="2" background="/images/forImage/Images/login_top_bg.gif">&nbsp;</td></tr>
        <!-- 主体部分 -->
        <tr style="background:url(/images/forImage/Images/login_bg.jpg) repeat-x;" height="532">
            <!-- 主体左部分 -->
            <td id="left_cont">
                <table width="100%" height="100%">
                    <tr height="155"><td colspan="2">&nbsp;</td></tr>
                    <tr>
                        <td width="20%" rowspan="2">&nbsp;</td>
                        <td width="60%">
                            <table width="100%">
                                <tr height="70"><td align="right"><img src="/images/forImage/Images/logo.gif" title="千禧之星" alt="千禧之星" /></td></tr>
                                <tr height="274">
                                    <td valign="top" align="right">
                                        <ul>
                                            <li><div class="diamondLogin"></div><div style="float: left">十六年钻石珠宝品牌,钻石婚戒定制首选品牌</div></li>
                                            <li><div class="diamondLogin"></div><div style="float: left">钻戒定制,对戒定制,结婚钻戒</div></li>
                                            <li><div class="diamondLogin"></div><div style="float: left">钻石品牌,定制钻戒,钻石珠宝网</div></li>
                                            <!--<li><img src="/images/forImage/Images/icon_demo.gif" />&nbsp;<a href="javascript:void(0)">使用说明</a>&nbsp;&nbsp;<span> <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=609307843&site=qq&menu=yes" onfocus="this.blur()"><img border="0" src="http://wpa.qq.com/pa?p=2:609307843:41" alt="瑞曼为您服务" title="瑞曼科技"></a> </span></li>-->
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        <td width="15%" rowspan="2">&nbsp;</td>
                        </td>
                    </tr>
                    <tr><td colspan="2">&nbsp;</td></tr>
                </table>
            </td>
            <!-- 主体右部分 -->
            <td id="right_cont">
                <table height="100%">
                    <tr height="30%"><td colspan="3">&nbsp;</td></tr>
                    <tr>
                        <td width="30%" rowspan="5">&nbsp;</td>
                        <td valign="top" id="form">
                            <form action="" method="">
                                <table valign="top" width="50%">
                                    <tr><td colspan="2"><h4 style="letter-spacing:1px;font-size:16px;">千禧之星 网站管理后台</h4></td></tr>
                                    <tr><td>管理员：</td><td><input type="text" id="user" name="user" value="qianxi" /></td></tr>
                                    <tr><td>密&nbsp;&nbsp;&nbsp;&nbsp;码：</td><td><input type="password" id="pwd" name="pwd" value="" /></td></tr>
                                    <!--<tr><td>验证码：</td><td><input type="text" name="" value="" style="width:80px;"/></td></tr>-->
                                    <tr class="bt" align="center"><td>&nbsp;<input type="submit" value="登陆" onclick="login();return false;" /></td><td>&nbsp;<input type="reset" value="重填" /></td></tr>
                                </table>
                            </form>
                        </td>
                        <td rowspan="5">&nbsp;</td>
                    </tr>
                    <tr><td colspan="3">&nbsp;</td></tr>
                </table>
            </td>
        </tr>
        <!-- 底部部分 -->
        <tr id="login_bot"><td colspan="2"><p>Copyright © 2016-2020 mstar</p></td></tr>
    </table>
        <script type="text/javascript">
            function login()
            {
                var pwd = $.trim($("#pwd").val());
                var user = $.trim($("#user").val());
                if(pwd != '') {
                    $.get('/admin/AdminApi/loginDo?pwd=' + pwd+'&user='+user, function (data) {
                        if (CheckObjError(data)) {
                            location.href = '/admin/index';
                        }
                        $("#pwd").val('');
                    },'json');
                }
                return false;
            }
        </script>
</block>