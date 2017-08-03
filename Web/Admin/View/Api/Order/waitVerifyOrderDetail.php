<extend name="public/rightLayout"/>
<block name="head">
    <?php use Common\Common\PublicCode\FunctionCode;?>
</block>
<block name="manageTitle">
    待验证订单
</block>
<block name="content">
    <table class="qxTable" id="mytable" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <th scope="col">
                <table width="100%">
                    <tr>
                        <td  style="border-width: 0px">
                            订单号：<?php echo $orderObj["orderInfo"]["orderNum"]; ?>
                        </td>
                        <td style="border-width: 0px">

                        </td>
                        <td style="text-align: right;border-width: 0px">
                            下单日期：<?php echo $orderObj["orderInfo"]["orderDate"]; ?>
                        </td>
                        <td style="text-align: right;border-width: 0px;width: 150px;">
                            当前状态:<?php echo $orderObj["orderInfo"]["orderStatus"]; ?>
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
                            <?php echo $orderObj["orderInfo"]["customerName"]; ?>
                        </td>
                        <th style="width: 50px">字样:</th>
                        <td>
                            <?php echo $orderObj["orderInfo"]["word"]; ?>
                        </td>
                        <th style="width: 50px">成色:</th>
                        <td>
                            <?php echo $orderObj["orderInfo"]["purityName"]; ?>
                        </td>
                        <th style="width: 100px">质量等级:</th>
                        <td>
                            <?php echo $orderObj["orderInfo"]["qualityName"]; ?>
                        </td>
                        <th style="width: 100px">当前金价:</th>
                        <td>
                            <?php echo $orderObj["orderInfo"]["goldPrice"]; ?>
                        </td>
                    </tr>
               </table>
               <table class="qxTable"  style="width: 100%"  cellspacing="0">
                   <tr>
                       <th style="width: 100px">
                           提货地址方式:
                       </th>
                       <td>
                           <?php echo $orderObj["address"]["name"]; ?>
                           &nbsp;<?php echo $orderObj["address"]["addr"]; ?>
                           &nbsp;<?php echo $orderObj["address"]["phone"]; ?>
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
                       <th width="50px">id</th>
                       <th width="100px">
                           图片
                       </th>
                       <th>
                           名称
                       </th>
                       <th>
                           款式信息
                       </th>
                       <th>
                           石头价格
                       </th>
                       <th>
                           总价
                       </th>
                       <th>
                           数量
                       </th>
                       <th>
                           石头信息
                       </th>
                   </tr>
                   <?php foreach($orderObj["currentOrderlList"]["list"] as $value){?>
                   <tr>
                       <td>
                           <?php echo $value["modelId"];?>
                       </td>
                       <td>
                           <?php
                           //$pic = trim(str_replace(C("PicBasePath")."/","",$value["pic"]));
                           $pic = $value["pic"];
                           ?>
                           <img onclick="openPopWin('/admin/index/showPic?id=<?php echo $value["modelId"];?>');" src="<?php if(!empty($pic)){echo $pic;}else{echo "/images/forImage/Images/nopic.png";}?>" style="width: 100px;height: 100px;cursor: pointer"/>
                       </td>
                       <td>
                           <?php echo $value["title"];?>
                       </td>
                       <td>
                           <?php echo $value["baseInfo"];?>
                       </td>
                       <td>
                           <?php echo $value["stonePrice"];?>
                       </td>
                       <td>
                           <?php echo $value["price"];?>
                       </td>
                       <td>
                           <?php echo $value["number"];?>
                       </td>
                       <td>
                           <?php echo $value["info"];?>
                       </td>
                   </tr>
                   <?php }?>
               </table>
           </td>
        </tr>
    </table>
</block>