<extend name="public/rightLayout"/>
<block name="head">
    <?php use Common\Common\PublicCode\FunctionCode;?>
</block>
<block name="manageTitle">
    生产中订单
</block>
<block name="content">
    <?php
    PageTabsHtml('ProducedingOrder',$pageQrt,$Listcpage,$pageCount,$reCount);
    ?>
    <table class="qxTable" id="mytable" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <th scope="col"></th>
            <th scope="col"><input id="orderNum_s" type="text" style="width: 100%;"/></th>
            <th scope="col"></th>
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
                    var qstr = ResquestStrKeyValueArr(new Array("orderNum"),new Array(orderNum));
                    location.href = "/admin/AdminApi/producedingOrder?"+qstr;
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
                    <?php echo $item["customerName"];?>
                </td>
                <td class="row">
                    <?php echo $item["model_purity"]?>
                </td>
                <td class="row">
                    <?php echo $item["model_purity"]?>
                </td>
                <td class="row">
                    <?php echo $item["itemCount"];?>
                </td>
                <td class="row">
                    &yen;<?php echo $item["goldPrice"];?>/g
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
                           onclick="openPopWin('/admin/AdminApi/producedingOrderDetail?orderNum=<?php echo $item["orderNum"];?>&id=<?php echo $item["id"];?>&memberId=<?php echo $item["member_id"];?>');"/>
                </td>
            </tr>
        <?php }?>
    </table>
    <?php
    PageTabsHtml('ProducedingOrder',$pageQrt,$Listcpage,$pageCount,$reCount);
    ?>
</block>