<extend name="public/rightLayout"/>
<block name="head">
    <?php use Common\Common\PublicCode\FunctionCode;?>
</block>
<block name="manageTitle">
    待审核订单
</block>
<block name="content">
    <?php
    PageTabsHtml('waitVerifyOrder',$pageQrt,$Listcpage,$pageCount,$reCount);
    ?>
    <table class="qxTable" id="mytable" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <th scope="col"></th>
            <th scope="col"><input id="orderNum_s" type="text" style="width: 100%;"/></th>
            <th scope="col"><input id="userName_s" type="text" style="width: 100%;"/></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"><input type="button" value="搜索" onclick="searchOrder();"/></th>
            <script>
                function searchOrder()
                {
                    var orderNum = $('#orderNum_s').val();
                    var userName = $('#userName_s').val();
                    var qstr = ResquestStrKeyValueArr(new Array("orderNum","userName"),new Array(orderNum,userName));
                    location.href = "/admin/AdminApi/waitVerifyOrder?"+qstr;
                }
            </script>
        </tr>
        <tr scope="col">
            <th scope="col">id</th>
            <th scope="col" style="width: 100px;">订单号</th>
            <th scope="col" style="width: 100px;">会员名</th>
            <th scope="col">会员真实姓名</th>
            <th scope="col">客户名称</th>
            <th scope="col">成色</th>
            <th scope="col">质量</th>
            <th scope="col">件数</th>
            <th scope="col">金价</th>
            <th scope="col">字印</th>
            <th scope="col">备注</th>
            <th scope="col">下单时间</th>
            <th scope="col" style="width: 200px">操作</th>
        </tr>
        <?php foreach($orderdata as $item){ ?>
            <tr>
                <td class="row" style="width: 30px">
                    <?php echo $item["id"];?>
                </td>
                <td class="row">
                    <?php echo $item["orderNum"];?>
                </td>
                <td class="row">
                    <?php echo $item["userName"];?>
                </td>
                <td class="row">
                    <?php echo $item["trueName"];?>
                </td>
                <td class="row">
                    <?php echo FunctionCode::FindEqObjReField($custromers,"customerID","customerName",$item["customerId"]);?>
                </td>
                <td class="row">
                    <?php $puritysItem = FunctionCode::FindEqObjReObjItem($puritys,"id","title",$item["model_purity_id"]);?>
                    <?php echo FunctionCode::FindEqObjReField($puritys,"id","title",$item["model_purity_id"]);?>
                </td>
                <td class="row">
                    <?php echo FunctionCode::FindEqObjReField($qualitys,"id","title",$item["model_quality_id"]);?>
                </td>
                <td class="row">
                    <?php echo $item["itemCount"];?>
                </td>
                <td class="row">
                    &yen;<?php echo $puritysItem->price;?>/g
                </td>
                <td class="row">
                    <?php echo $item["word"];?>
                </td>
                <td class="row">

                </td>
                <td class="row">
                    <?php echo $item["createDate"];?>
                </td>
                <td>
                    <input type="button" value="查看详情"
                           onclick="openPopWin('/admin/AdminApi/waitVerifyOrderDetail?itemId=<?php echo $item["id"];?>&memberId=<?php echo $item["member_id"];?>');"/>
                    <input type="button" onclick="checkVerifyOrder('<?php echo $item["id"];?>');" value="审核"/>
                </td>
            </tr>
        <?php }?>
    </table>
    <?php
    PageTabsHtml('waitVerifyOrder',$pageQrt,$Listcpage,$pageCount,$reCount);
    ?>
    <script type="text/javascript">
        function checkVerifyOrder(orderId)
        {
            if(confirm('确认审核吗？')) {
                jNotify('请等待', {ShowOverlay: true, autoHide: false});
                if (isNumber(orderId)) {
                    $.get('/admin/AdminApi/ModelOrderWaitCheckVerifyToProduceForAdminDo?orderId=' + orderId, function (data) {
                        if (CheckObjErrorAndTure(data)) {

                        }
                        setTimeout("location.reload()", 200);
                    }, 'json');
                }
            }
        }
    </script>
</block>