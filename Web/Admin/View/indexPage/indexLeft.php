<extend name="public/layout"/>
<block name="head">
    <script src="/images/forImage/Js/prototype.lite.js" type="text/javascript"></script>
    <script src="/images/forImage/Js/moo.fx.js" type="text/javascript"></script>
    <script src="/images/forImage/Js/moo.fx.pack.js" type="text/javascript"></script>
    <script type="text/javascript">
        window.onload = function () {
            var contents = document.getElementsByClassName('content');
            var toggles = document.getElementsByClassName('type');

            var myAccordion = new fx.Accordion(
                toggles, contents, {opacity: true, duration: 400}
            );
            myAccordion.showThisHideOpen(contents[0]);
            for(var i=0; i<document .getElementsByTagName("a").length; i++){
                var dl_a = document.getElementsByTagName("a")[i];
                dl_a.onfocus=function(){
                    this.blur();
                }
            }
            document.styleSheets[0].rules[0].style.display = "inline";
            document.getElementById("leftMeum").style.display = '';
        }
    </script>
</block>
<block name="top"></block>
<block name="content">
    <table id="leftMeum" width="100%" height="280" border="0" cellpadding="0" cellspacing="0" bgcolor="#EEF2FB" style="display: none">
        <tr>
            <td width="182" valign="top">
                <div id="container">
                    <!--<h1 class="type"><a href="javascript:void(0)">网站栏目</a></h1>
                    <div class="content">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><img src="/images/forImage/Images/menu-top-line.gif" width="182" height="5" /></td>
                            </tr>
                        </table>
                        <ul class="RM">
                            <li><a href="./cat_add.html" target="main">添加栏目</a></li>
                            <li><a href="./cat_manage.html" target="main">栏目管理</a></li>
                        </ul>
                    </div>
                    <h1 class="type"><a href="javascript:void(0)">产品管理</a></h1>
                    <div class="content">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><img src="/images/forImage/Images/menu-top-line.gif" width="182" height="5" /></td>
                            </tr>
                        </table>
                        <ul class="RM">
                            <li><a href="./goods_sort.html" target="main">产品分类</a></li>
                            <li><a href="./goods_add.html" target="main">添加产品</a></li>
                            <li><a href="./goods_list.html" target="main">产品列表</a></li>
                        </ul>
                    </div>-->
                    <h1 class="type"><a href="javascript:void(0)">款号管理</a></h1>
                    <div class="content">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><img src="/images/forImage/Images/menu-top-line.gif" width="182" height="5" /></td>
                            </tr>
                        </table>
                        <ul class="RM">
                            <li><a href="/admin/AdminApi/addModel" target="main">添加款号</a></li>
                            <li><a href="/admin/AdminApi/modelList" target="main">款号列表</a></li>
                        </ul>
                    </div>
                    <!-- *********** -->
                    <h1 class="type"><a href="javascript:void(0)">款号分类</a></h1>
                    <div class="content">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><img src="/images/forImage/Images/menu-top-line.gif" width="182" height="5" /></td>
                            </tr>
                        </table>
                        <ul class="RM">
                            <li><a href="/admin/AdminApi/addCategory" target="main">添加分类</a></li>
                            <li><a href="/admin/AdminApi/categoryList" target="main">分类列表</a></li>
                            <li><a href="/admin/AdminApi/addAttribute" target="main">添加属性</a></li>
                            <li><a href="/admin/AdminApi/attributeList" target="main">属性列表</a></li>
                        </ul>
                    </div>
                    <h1 class="type"><a href="javascript:void(0)">款号订单</a></h1>
                    <div class="content">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><img src="/images/forImage/Images/menu-top-line.gif" width="182" height="5" /></td>
                            </tr>
                        </table>
                        <ul class="RM">
                            <li><a href="/admin/AdminApi/waitVerifyOrder" target="main">待审核订单</a></li>
                            <li><a href="/admin/AdminApi/producedingOrder" target="main">生产中订单</a></li>
                        </ul>
                    </div>
                    <h1 class="type"><a href="javascript:void(0)">石头订单</a></h1>
                    <div class="content">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><img src="/images/forImage/Images/menu-top-line.gif" width="182" height="5" /></td>
                            </tr>
                        </table>
                        <ul class="RM">
                            <li><a href="/admin/AdminApi/adminStoneWaitPayOrderList" target="main">待付款</a></li>
                            <li><a href="/admin/AdminApi/adminStoneAlreadyPayOrderList" target="main">已付款</a></li>
                            <li><a href="/admin/AdminApi/adminStoneAlreadyDeliverGoodsOrderList" target="main">已发货</a></li>
                            <li><a href="/admin/AdminApi/adminStoneAlreadyFinishOrderList" target="main">已完成</a></li>
                        </ul>
                    </div>
                    <h1 class="type"><a href="javascript:void(0)">会员管理</a></h1>
                    <div class="content">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><img src="/images/forImage/Images/menu-top-line.gif" width="182" height="5" /></td>
                            </tr>
                        </table>
                        <ul class="RM">
                            <li><a href="/admin/AdminApi/addMember" target="main">添加会员</a></li>
                            <li><a href="/admin/AdminApi/memberList" target="main">会员列表</a></li>
                        </ul>
                    </div>
                    <h1 class="type"><a href="javascript:void(0)">系统设置</a></h1>
                    <div class="content">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><img src="/images/forImage/Images/menu-top-line.gif" width="182" height="5" /></td>
                            </tr>
                        </table>
                        <ul class="RM">
                            <li><a href="/admin/AdminApi/updateAppData" target="main">同步数据</a></li>
                            <li><a href="/admin/AdminApi/updateCache" target="main">缓存管理</a></li>
                        </ul>
                    </div>
                    <!-- *********** -->
                    <!--<h1 class="type"><a href="javascript:void(0)">其它设置</a></h1>
                    <div class="content">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><img src="/images/forImage/Images/menu-top-line.gif" width="182" height="5" /></td>
                            </tr>
                        </table>
                        <ul class="RM">
                            <li><a href="javascript:void(0)" target="main">友情连接</a></li>
                            <li><a href="javascript:void(0)" target="main">在线留言</a></li>
                            <li><a href="javascript:void(0)" target="main">网站投票</a></li>
                            <li><a href="javascript:void(0)" target="main">邮箱设置</a></li>
                            <li><a href="javascript:void(0)" target="main">图片上传</a></li>
                        </ul>
                    </div>-->
                    <!-- *********** -->
                </div>
            </td>
        </tr>
    </table>
</block>
<block name="floor"></block>
<block name="bottom"></block>