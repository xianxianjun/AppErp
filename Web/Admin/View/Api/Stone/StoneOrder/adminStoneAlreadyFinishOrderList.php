<extend name="public/rightLayout"/>
<block name="head">
    <script type="text/javascript" src="/js/adminjs.js"></script>
    <?php use Common\Common\PublicCode\FunctionCode;?>
</block>
<block name="manageTitle">
    已完成订单
</block>
<block name="content">
    <?php
    PageTabsHtml('AdminStoneAlreadyFinishOrderList',$pageQrt,$Listcpage,$pageCount,$reCount);
    ?>
    <table class="qxTable" id="mytable" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <th scope="col">
                id
            </th>
            <th scope="col">
                账号名
            </th>
            <th scope="col">
                用户真实名
            </th>
            <th scope="col">
                订单编号
            </th>
            <th scope="col">
                客户名称
            </th>
            <th scope="col">
                电话号码
            </th>
            <th scope="col">
                联系人
            </th>
            <th scope="col">
                送货地址
            </th>
            <th scope="col">
                已付金额
            </th>
            <th scope="col">
                需付款金额
            </th>
            <th scope="col">
                下单时间
            </th>
            <th scope="col">
                设置状态
            </th>
            <th scope="col">
                操作
            </th>
        </tr>
        <?php foreach($data as $item){ ?>
            <tr>
                <td class="row" style="width: 30px">
                    <?php echo $item["id"];?>
                </td>
                <td class="row" style="width: 80px">
                    <?php echo $item["userName"];?>
                </td>
                <td class="row" style="width: 80px">
                    <?php echo $item["trueName"];?>
                </td>
                <td class="row" style="width: 30px">
                    <?php echo $item["orderNum"];?>
                </td>
                <td class="row" style="width: 220px">
                    <?php echo $item["customerName"];?>
                </td>
                <td class="row" style="width: 40px">
                    <?php echo $item["phone"];?>
                </td>
                <td class="row" style="width: 70px">
                    <?php echo $item["postName"];?>
                </td>
                <td class="row" style="width: 400px">
                    <?php echo $item["address"];?>
                </td>
                <td class="row" style="width: 80px">
                    <?php echo $item["alreadyTotalPrice"];?>
                </td>
                <td class="row" style="width: 80px">
                    <?php echo $item["discountTotalPrice"];?>
                </td>
                <td class="row" style="width: 150px">
                    <?php echo $item["createDate"];?>
                </td>
                <td class="row" style="width: 150px">
                    <select id="setOrderStatus" onchange="ChangeOrderStatus(this,<?php echo $item["id"];?>,<?php echo $defaultValue?>);">
                        <?php echo $soptionStr;?>
                    </select>
                </td>
                <td class="row">
                    <input type="button" onclick="openPopWin('/admin/AdminApi/adminStoneOrderDetails?id=<?php echo $item["id"];?>');" value="查看详情"/>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?php
    PageTabsHtml('AdminStoneAlreadyFinishOrderList',$pageQrt,$Listcpage,$pageCount,$reCount);
    ?>
</block>