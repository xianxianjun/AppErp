<extend name="public/rightLayout"/>
<block name="head">

</block>
<block name="manageTitle">
    缓存管理
</block>
<block name="content">
    <div style="width: 100%;">
        <input STYLE="float: right;margin: 8px 0px 8px 0px" value="同步缓存" type="button" onclick="updateCacheDo();return false;"/>
    </div>
    <script>
        function updateCacheDo()
        {
            jNotify('请等待', { ShowOverlay: true,autoHide : false });
            $.get('/admin/AdminApi/updateCacheDo', function (data) {
                closejNotify();
                if (CheckObjErrorAndTure(data)) {

                }
                setTimeout("location.reload()", 200);
            },'json');
        }
    </script>
    <table class="qxTable" id="mytable" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <th scope="col">名称</th>
            <th scope="col" style="width: 100px;">数据</th>
        </tr>
        <tr>
            <td class="row" style="width: 30px">
                款号成色
            </td>
            <td>
                <?php echo json_encode($ModelAttributeObj->modelPuritys);?>
            </td>
        </tr>
        <tr>
            <td class="row" style="width: 30px">
                款号质量等级
            </td>
            <td>
                <?php echo json_encode($ModelAttributeObj->modelQualitys);?>
            </td>
        </tr>
        <tr>
            <td class="row" style="width: 30px">
                石头形状
            </td>
            <td>
                <?php echo json_encode($ModelAttributeObj->stoneShapes);?>
            </td>
        </tr>
        <tr>
            <td class="row" style="width: 30px">
                石头类型
            </td>
            <td>
                <?php echo json_encode($ModelAttributeObj->stoneCategorys);?>
            </td>
        </tr>
        <tr>
            <td class="row" style="width: 30px">
                Erp生产中订单状态
            </td>
            <td>
                <?php echo json_encode($ModelAttributeObj->modelOrderStatus);?>
            </td>
        </tr>
    </table>
</block>