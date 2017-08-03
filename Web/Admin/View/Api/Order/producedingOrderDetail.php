<extend name="public/rightLayout"/>
<block name="head">
    <?php use Common\Common\PublicCode\FunctionCode;
          use Common\Common\PublicCode\BllPublic;?>
</block>
<block name="manageTitle">
    生产中订单
</block>
<block name="content">
    <table class="qxTable" id="mytable" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <th scope="col">
                <table width="100%">
                    <tr>
                        <td  style="border-width: 0px">
                            订单号：<?php echo $orderInfo->OrderID; ?>
                        </td>
                        <td style="border-width: 0px">

                        </td>
                        <td style="text-align: right;border-width: 0px">
                            下单日期：<?php echo $orderInfo->OrderDate; ?>
                        </td>
                        <td style="text-align: right;border-width: 0px;width: 150px;">
                            当前状态:<?php echo $orderInfo->OrderStatusTitle; ?>
                        </td>
                    </tr>
                </table>
            </th>
        </tr>
        <tr>
            <td>
                <table class="qxTable"  style="width: 100%"  cellspacing="0">
                    <tr>
                        <th style="width: 100px">
                            客户名称:
                        </th>
                        <td>
                            <?php echo $orderInfo->CustomerName; ?>
                        </td>
                        <th style="width: 50px">字样:</th>
                        <td>
                            <?php echo $orderInfo->Sigil; ?>
                        </td>
                        <th style="width: 50px">成色:</th>
                        <td>
                            <?php echo $orderInfo->PurityName; ?>
                        </td>
                        <th style="width: 100px">质量等级:</th>
                        <td>
                            <?php echo $orderInfo->QualityName; ?>
                        </td>
                        <th style="width: 100px">当前金价:</th>
                        <td>
                            <?php echo $orderInfo->GoldPrice; ?>
                        </td>
                    </tr>
                </table>
                <table class="qxTable"  style="width: 100%"  cellspacing="0">
                    <tr>
                        <th style="width: 100px">
                            提货地址方式:
                        </th>
                        <td>
                            <?php echo $orderInfo->postAddress; ?>
                        </td>
                    </tr>
                </table>
                <table class="qxTable"  style="width: 100%"  cellspacing="0">
                    <tr>
                        <th style="width: 100px">
                            备注:
                        </th>
                        <td>
                            <?php echo $orderInfo->OrderMemo; ?>
                        </td>
                    </tr>
                </table>
                <table class="qxTable"  style="width: 100%"  cellspacing="0">
                    <tr>
                        <th>
                            订货详情
                        </th>
                    </tr>
                </table>
                <table class="qxTable"  style="width: 100%"  cellspacing="0">
                    <tr>
                        <th width="100px">
                            图片
                        </th>
                        <th>
                            名称
                        </th>

                        <th>
                            石头信息
                        </th>
                        <th>
                            数量
                        </th>
                        <th>小备注</th>
                    </tr>
                    <?php foreach($modelList as $value){?>
                        <tr>
                            <td>
                                <?php
                                $pic = FunctionCode::FindEqArrReField($oModellist,"modelNum","pic",$value->ModuleID);
                                ?>
                                <img src="<?php if(!empty($pic)){echo BllPublic::GetPicBasePath().$pic;}else{echo "/images/forImage/Images/nopic.png";}?>" style="width: 100px;height: 100px;cursor: pointer"/>
                            </td>
                            <td>
                                <?php echo $value->ModuleID;?>
                            </td>
                            <td>
                                <?php echo $value->info;?>
                            </td>
                            <td>
                                <?php echo $value->QuantityDetail;?>
                            </td>
                            <td>
                                <?php echo $value->remarks;?>
                            </td>
                        </tr>
                    <?php }?>
                </table>
            </td>
        </tr>
    </table>
</block>