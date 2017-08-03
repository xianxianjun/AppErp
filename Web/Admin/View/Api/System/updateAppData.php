<extend name="public/rightLayout"/>
<block name="head">

</block>
<block name="manageTitle">
    同步数据
</block>
<block name="content">
    <div style="width: 100%;">
        <input STYLE="float: right;margin: 8px 0px 8px 8px" value="批量同步数据" type="button"
               onclick="openPopWin('/admin/AdminApi/updateAppDataBath');return false;"/>
        <input STYLE="float: right;margin: 8px 0px 8px 0px" value="批量同步款号图片数据" type="button"
               onclick="openPopWin('/admin/AdminApi/updateAppImageDataBath');return false;"/>
        <input STYLE="float: right;margin: 8px 0px 8px 0px" value="同步数据" type="button" onclick="updateDataDo();return false;"/>
        <input STYLE="float: right;margin: 8px 8px 8px 0px" type="button"
               onclick="location.href='<?php if($all==1){?>/admin/AdminApi/updateAppData<?php }else{ ?>/admin/AdminApi/updateAppData?all=1<?php }?>';"
               value="<?php if($all==1){?>仅显示更新数据<?php }else{ ?>显示全部<?php }?>"/>
    </div>
    <script>
        function updateDataDo()
        {
            jNotify('请等待', { ShowOverlay: true,autoHide : false });
            $.get('/admin/AdminApi/updateAppDataDo', function (data) {
                closejNotify();
                if (CheckObjErrorAndTure(data)) {

                }
                setTimeout("location.reload()", 200);
            },'json');
        }
    </script>
    <table class="qxTable" id="mytable" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <th scope="col">id</th>
            <th scope="col" style="width: 80px;">更新类型</th>
            <th scope="col"  style="width: 150px;">更新时间</th>
            <th scope="col">修改信息</th>
            <th scope="col">增加信息</th>
            <th scope="col"  style="width: 250px;">更新时间段</th>
        </tr>
        <?php foreach($data as $item){
            $jsonObj = json_decode($item["updateData"]);
            ?>
            <tr>
                <td class="row" style="width: 30px">
                    <?php echo $item["id"];?>
                </td>
                <td class="row">
                    <?php echo $item["type"];?>
                </td>
                <td class="row">
                    <?php echo $item["createDate"];?>
                </td>
                <td class="row" style="width:400px;word-wrap:break-word;word-break:break-all">
                    <?php echo $jsonObj->data->updateData
                                .$jsonObj->data->updatePic.((!empty($jsonObj->data->setNotShowObj))?"<br><b>为修改设定不显示</b>:".$jsonObj->data->setNotShowObj:"")?>
                </td>
                <td class="row" style="width:400px;word-wrap:break-word;word-break:break-all">
                    <?php echo $jsonObj->data->addData
                                .$jsonObj->data->addPic.((!empty($jsonObj->data->upriPicAddObj))?"<br><b>为添加修改了</b>:".$jsonObj->data->upriPicAddObj:"")?>
                </td>
                <td class="row">
                    <?php echo $jsonObj->data->startTime."~".$jsonObj->data->endTime.$jsonObj->data->edate?>
                </td>
            </tr>
        <?php }?>
    </table>
</block>