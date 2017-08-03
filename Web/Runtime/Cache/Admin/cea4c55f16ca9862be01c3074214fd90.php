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
    <link rel="stylesheet" type="text/css" href="/images/forImage/Style/admin.css" />
    <style type="text/css">
        body {
            font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
            color: #4f6b72;
        }

        .qxTable a {
            color: #c75f3e;
        }

        #mytable {
            width: 700px;
            padding: 0;
            margin: 0;
        }

        .qxTable caption {
            padding: 0 0 5px 0;
            font: italic 18px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
            text-align: left;
        }

        .qxTable th {
            font: bold 12px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
            color: #4f6b72;
            border-left: 1px solid #e1e5ee;
            border-right: 1px solid #e1e5ee;
            border-bottom: 1px solid #e1e5ee;
            border-top: 1px solid #e1e5ee;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-align: left;
            padding: 6px 6px 6px 12px;
            background: #e7f1fe  no-repeat;
        }

        .qxTable th.nobg {
            border-top: 0;
            border-left: 0;
            border-right: 1px solid #e1e5ee;
            background: none;
        }

        .qxTable td {
            border-left: 1px solid #e1e5ee;
            border-right: 1px solid #e1e5ee;
            border-bottom: 1px solid #e1e5ee;
            background: #fff;
            font-size:12px;
            padding: 6px 6px 6px 12px;
            color: #4f6b72;
        }


        .qxTable td.alt {
            background: #F5FAFA;
            color: #797268;
        }

        .qxTable th.spec {
            border-left: 1px solid #C1DAD7;
            border-top: 0;
            background: #fff no-repeat;
            font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
        }

        .qxTable th.specalt {
            border-left: 1px solid #C1DAD7;
            border-top: 0;
            background: #f5fafa no-repeat;
            font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
            color: #797268;
        }
        /*---------for IE 5.x bug*/
        html>body td{ font-size:11px;}
        body,td,th {
            font-family: 宋体, Arial;
            font-size: 12px;
        }
    </style>
    


</head>
<body>
<?php require BASE_PATH.'Web/Admin/View/public/HtmlFun.php'?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <!-- 头部开始 -->
    <tr>
        <td width="17" valign="top" background="./Images/mail_left_bg.gif">
            <img src="/images/forImage/Images/left_top_right.gif" width="17" height="29" />
        </td>
        <td valign="top" background="/images/forImage/Images/content_bg.gif">
            <table width="100%" height="31" border="0" cellpadding="0" cellspacing="0" background="/images/forImage/Images/content_bg.gif">
                <tr><td height="31"><div class="title">
    款号列表
</div></td></tr>
            </table>
        </td>
        <td width="16" valign="top" background="./Images/mail_right_bg.gif"><img src="/images/forImage/Images/nav_right_bg.gif" width="16" height="29" /></td>
    </tr>
    <!-- 中间部分开始 -->
    <tr>
        <!--第一行左边框-->
        <td valign="middle" background="/images/forImage/Images/mail_left_bg.gif">&nbsp;</td>
        <!--第一行中间内容-->
        <td valign="top" bgcolor="#F7F8F9">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <!-- 空白行-->
                <tr><td colspan="2" valign="top">&nbsp;</td><td>&nbsp;</td><td valign="top">&nbsp;</td></tr>

                <!-- 一条线 -->
                <tr>
                    <td height="40" colspan="4">
                        <table width="100%" height="1" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
                            <tr><td></td></tr>
                        </table>
                    </td>
                </tr>
                <!-- 产品列表开始 -->
                <tr>
                    <td width="2%">&nbsp;</td>
                    <td width="96%">
                        <table width="100%">
                            <tr>
                                <td colspan="2">
                                    <!--内容-->
                                    
    <?php
 PageTabsHtml('ModelList',$pageQrt,$Listcpage,$pageCount,$reCount); ?>
    <table class="qxTable" id="mytable" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <th scope="col" style="width: 30px;"></th>
            <th scope="col"></th>
            <th scope="col" colspan="2"><input id="modelNumModelName_s" type="text" style="width: 100%;"/></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"><input type="button" onclick="search();return false;" value="搜索"/></th>
        </tr>
        <script>
            function search()
            {
                var keyword = $('#modelNumModelName_s').val();
                if(keyword != '')
                {
                    location.href='modelList?keyword='+escape(keyword);
                }
                else
                {
                    //jError('请输入搜索条件');
                    location.href='modelList';
                }
                return false;
            }
        </script>
        <tr scope="col">
            <th scope="col">id</th>
            <th scope="col">相片</th>
            <th scope="col">款号</th>
            <th scope="col" style="width: 100px;">名称</th>
            <th scope="col">创建时间</th>
            <th scope="col">修改时间</th>
            <th scope="col" style="width: 200px;">操作</th>
        </tr>
        <?php foreach($modelData as $item) {?>
        <tr>
            <td class="row" style="width: 30px">
                <?php echo $item["id"];?>
            </td>
            <td class="row" style="width: 100px">
                <img onclick="openPopWin('/admin/index/showPic?id=<?php echo $item["id"];?>');" src="<?php if(!empty($item["pic"])){echo C('PicBasePath').$item["pic"];}else{echo "/images/forImage/Images/nopic.png";}?>" style="width: 100px;height: 100px;cursor: pointer"/>
            </td>
            <td>
                <?php echo $item["modelNum"];?>
            </td>
            <td>
                <?php echo $item["name"];?>
            </td>
            <td>
                <?php echo $item["createDate"];?>
            </td>
            <td>
                <?php echo $item["updateDate"];?>
            </td>
            <td>
                <input type="button" onclick="openPopWin('/admin/AdminApi/editModel');return false;" value="编辑"/>
                <input type="button" onclick="return false;" value="隐藏"/>
                <input type="button" onclick="return false;" value="删除"/>
            </td>
        </tr>
        <?php }?>
        </table>
    <?php
 PageTabsHtml('ModelList',$pageQrt,$Listcpage,$pageCount,$reCount); ?>

                                    <!--内容-->
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="2%">&nbsp;</td>
                </tr>
                <!-- 产品列表结束 -->
                <tr>
                    <td height="40" colspan="4">
                        <table width="100%" height="1" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
                            <tr><td></td></tr>
                        </table>
                    </td>
            </table>
        </td>
        <td background="/images/forImage/Images/mail_right_bg.gif">&nbsp;</td>
    </tr>
    <!-- 底部部分 -->
    <tr>
        <td valign="bottom" background="/images/forImage/Images/mail_left_bg.gif">
            <img src="/images/forImage/Images/buttom_left.gif" width="17" height="17" />
        </td>
        <td background="/images/forImage/Images/buttom_bgs.gif">
            <img src="/images/forImage/Images/buttom_bgs.gif" width="17" height="17">
        </td>
        <td valign="bottom" background="./Images/mail_right_bg.gif">
            <img src="/images/forImage/Images/buttom_right.gif" width="16" height="17" />
        </td>
    </tr>
</table>
<script type="text/javascript" src="/js/popWin.js"></script>
</body>
</html>