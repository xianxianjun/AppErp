<extend name="public/rightLayout"/>
<block name="head">
    <script type="text/javascript" src="/js/adminjs.js"></script>
    <?php use Common\Common\PublicCode\FunctionCode;?>
    <?php use Common\Common\Api\flow\StoneCls;?>
</block>
<block name="manageTitle">
    <?php echo StoneCls::GetOrderStatusName($orderData["orderStatus"]); ?>订单
</block>
<block name="content">
    <style>
        .sPrice
        {
            font-style: italic;
        }
        .sPrice:before
        {
            content:"￥ ";
        }
    </style>
    <table class="qxTable" id="mytable2" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <th scope="col">订单id:</th>
            <td class="row">
                <?php echo $orderData["id"]; ?>
            </td>
            <th scope="col">订单号:</th>
            <td class="row">
                <?php echo $orderData["orderNum"]; ?>
            </td>
            <th scope="col">下单时间:</th>
            <td class="row">
                <?php echo $orderData["createDate"]; ?>
            </td>
            <th scope="col">需付金额:</th>
            <td class="row">
                <span class="sPrice"><?php echo $orderData["discountTotalPrice"]; ?></span>
            </td>
            <th scope="col">实际金额:</th>
            <td class="row">
                <span class="sPrice"><?php echo $orderData["totalPrice"]; ?></span>
            </td>
        </tr>
        <tr>
            <th scope="col">已付定金:</th>
            <td class="row">
                <span class="sPrice"><?php echo $orderData["alreadyTotalPrice"]; ?></span>
            </td>
            <th scope="col">发票头:</th>
            <td class="row">
                <?php echo $orderData["invoiceTitle"]; ?>
            </td>
            <th scope="col">发票类型:</th>
            <td class="row">
                <?php echo $orderData["invoiceType"]; ?>
            </td>
            <th scope="col">订单状态:</th>
            <td class="row">
                <?php echo StoneCls::GetOrderStatusName($orderData["orderStatus"]); ?>
            </td>
            <th scope="col">加点:</th>
            <td class="row">
                <?php echo floatval($orderData["percent"])>0?intval($orderData["percent"])."%":0; ?>
            </td>
        </tr>
    </table>
    <table class="qxTable" id="mytable2" style="width: 100%"  cellspacing="0">
        <th scope="col" width="100px">备注:</th>
        <td class="row">
            <?php echo $orderData["remark"]; ?>
        </td>
    </table>
    <table class="qxTable" id="mytable3" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <th scope="col">客户名称:</th>
            <td class="row">
                <?php echo $orderData["customerName"]; ?>
            </td>
            <th scope="col">客户电话:</th>
            <td class="row">
                <?php echo $orderData["phone"]; ?>
            </td>
            <th scope="col">联系人:</th>
            <td class="row">
                <?php echo $orderData["postName"]; ?>
            </td>
        </tr>

    </table>
    <table class="qxTable" id="mytable3" style="width: 100%"  cellspacing="0">
        <tr>
            <th scope="col" width="100px">客户地址：</th>
            <td colspan="3"><?php echo $orderData["address"]; ?></td>
        </tr>
    </table>
    <table class="qxTable" id="mytable3" style="width: 100%"  cellspacing="0">
    <tr>
        <th>
            证书
        </th>
        <th>
            证书编号
        </th>
        <th>
            重量
        </th>
        <th>
            实际单价
        </th>
        <th>
            加点单价
        </th>
        <th>
            形状
        </th>
        <th>
            颜色
        </th>
        <th>
            成色
        </th>
        <th>
            切工
        </th>
        <th>
            抛光
        </th>
        <th>
            对称
        </th>
        <th>
            荧光
        </th>
        <th>
            数量
        </th>
    </tr>
        <?php foreach($orderListData as $Item){ ?>
        <tr>
            <td>
                <?php echo $Item["CertAuth"]; ?>
            </td>
            <td>
                <?php echo $Item["CertCode"]; ?>
            </td>
            <td>
                <?php echo $Item["Weight"]; ?>
            </td>
            <td>
                <span class="sPrice"><?php echo $Item["Price"]; ?></span>
            </td>
            <td>
                <span class="sPrice"><?php echo $Item["discountPrice"]; ?></span>
            </td>
            <td>
                <?php echo $Item["Purity"]; ?>
            </td>
            <td>
                <?php echo $Item["Purity"]; ?>
            </td>
            <td>
                <?php echo $Item["Color"]; ?>
            </td>
            <td>
                <?php echo $Item["Cut"]; ?>
            </td>
            <td>
                <?php echo $Item["Polishing"]; ?>
            </td>
            <td>
                <?php echo $Item["Symmetric"]; ?>
            </td>
            <td>
                <?php echo $Item["Fluorescence"]; ?>
            </td>
            <td>
                <?php echo $Item["number"]; ?>
            </td>
        </tr>
        <?php }?>
    </table>
</block>