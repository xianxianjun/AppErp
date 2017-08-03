<extend name="public/rightLayout"/>
<block name="head">

</block>
<block name="manageTitle">
    会员列表
</block>
<block name="content">
    <?php
    PageTabsHtml('MemberList',$pageQrt,$Listcpage,$pageCount,$reCount);
    ?>
    <table class="qxTable" id="mytable" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <th scope="col" style="width: 30px;"></th>
            <th scope="col"><input id="userName_s" type="text" style="width: 100%;"/></th>
            <th scope="col"><input id="trueName_s" type="text" style="width: 100%;"/></th>
            <th scope="col"><input id="phone_s" type="text" style="width: 100%;"/></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"><input type="button" value="搜索" onclick="searchOrder();"/></th>
            <script>
                function searchOrder()
                {
                    var userName = $('#userName_s').val();
                    var trueName = $('#trueName_s').val();
                    var phone = $('#phone_s').val();
                    var qstr = ResquestStrKeyValueArr(new Array("userName","trueName","phone"),new Array(userName,trueName,phone));
                    location.href = "/admin/AdminApi/memberList?"+qstr;
                }
            </script>
        </tr>
        <tr scope="col">
            <th scope="col">id</th>
            <th scope="col" style="width: 100px;">会员名</th>
            <th scope="col">会员真实姓名</th>
            <th scope="col">电话号码</th>
            <th scope="col">捆绑ERP ID</th>
            <th scope="col">订单是否需要审核</th>
            <th scope="col">注册时间</th>
            <th scope="col" style="width: 200px">操作</th>
        </tr>
        <?php foreach($memdata as $item){ ?>
            <tr>
                <td class="row" style="width: 30px">
                    <?php echo $item["id"];?>
                </td>
                <td class="row">
                    <input id="userName_<?php echo $item["id"];?>" value="<?php echo $item["userName"];?>" type="text"/>
                </td>
                <td class="row">
                    <input id="trueName_<?php echo $item["id"];?>" value="<?php echo $item["trueName"];?>" type="text"/>
                </td>
                <td class="row">
                    <input id="phone_<?php echo $item["id"];?>" value="<?php echo $item["phone"];?>" type="text"/>
                </td>
                <td class="row">
                    <input id="orderErpId_<?php echo $item["id"];?>" value="<?php echo $item["orderErpId"];?>" type="text"/>
                </td>
                <td class="row">
                    <input onclick="setIsCheckErpOrder('<?php echo $item["id"];?>');" name="IsCheckErpOrder_<?php echo $item["id"];?>" id="IsCheckErpOrder_<?php echo $item["id"];?>"
                           <?php if($item["IsCheckErpOrder"] != 0){?>checked="checked"<?php }?> type="checkbox"/>
                    <span id="IsCheckErpOrder_Lable"><?php if($item["IsCheckErpOrder"] != 0){?>需要审核<?php }else{?>不需要审核<?php }?></span><span style="color: #FFFFFF"><?php echo $item["IsCheckErpOrder"];?></span>
                </td>
                <td class="row">
                    <?php echo $item["createDate"];?>
                </td>
                <td>
                    <input type="button" onclick="updateInfo('<?php echo $item["id"];?>');" value="更新"/>
                    <input type="button" onclick="openPopWin('/admin/AdminApi/modifyMember?uid=<?php echo $item["id"];?>');" value="更多"/>
                </td>
            </tr>
        <?php }?>
    </table>
    <?php
    PageTabsHtml('MemberList',$pageQrt,$Listcpage,$pageCount,$reCount);
    ?>
    <script language="JavaScript">
        function setIsCheckErpOrder(uidt)
        {
            /*var isselect = $('#IsCheckErpOrder_'+uidt).attr("checked");
            if(isselect)
            {
                //不用验证

            }
            else
            {
                //验证
            }*/
            jNotify('请等待', { ShowOverlay: true,autoHide : false });
            $.get('/admin/AdminApi/updateIsCheckErpOrderDo',{ uid:uidt}, function (data) {
                closejNotify();
                if (CheckObjErrorAndTure(data)) {
                    setTimeout("location.reload()", 200);
                }
                else {
                    setTimeout("location.reload()", 200);
                }
            },'json');
        }
        function updateInfo(uidt)
        {
            var eidt = $.trim($('#orderErpId_'+uidt).val());
            var tnt = $.trim($('#trueName_'+uidt).val());
            var unt = $.trim($('#userName_'+uidt).val());
            var pt = $.trim($('#phone_'+uidt).val());
            if(!isInteger(eidt) && $.trim(eidt)!='')
            {
                jError('请输入正确的捆绑erpid');
                return;
            }
            if(!tnt)
            {
                jError('请输入真实姓名');
                return;
            }
            if(!unt)
            {
                jError('请输入用户名');
                return;
            }
            if(!pt)
            {
                jError('请输入电话号码');
                return;
            }
            jNotify('请等待', { ShowOverlay: true,autoHide : false });
            $.get('/admin/AdminApi/updateUserInfoDo',{ uid:uidt,eid: eidt, tn: tnt,un:unt,p:pt }, function (data) {
                closejNotify();
                if (CheckObjErrorAndTure(data)) {

                }
                //setTimeout("location.reload()", 200);
            },'json');
        }
    </script>
</block>