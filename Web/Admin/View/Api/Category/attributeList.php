<extend name="public/rightLayout"/>
<block name="head">

</block>
<block name="manageTitle">
    属性列表
</block>
<block name="content">
    <table class="qxTable" id="mytable" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <th scope="col" style="width: 30px;"></th>
            <th scope="col"><input id="namet" type="text" style="width: 100%;"/></th>
            <th scope="col"></th>
            <th scope="col"><input type="button" onclick="search();return false;" value="搜索"/></th>
        </tr>
        <script>
            function search()
            {
                var keyword = $('#namet').val();
                if(keyword != '')
                {
                    location.href='attributeList?keyword='+keyword;
                }
                else
                {
                    //jError('请输入搜索条件');
                    location.href='attributeList';
                }
                return false;
            }
        </script>
        <tr scope="col">
            <th scope="col">id</th>
            <th scope="col">名称</th>
            <th scope="col">创建时间</th>
            <th scope="col">操作</th>
        </tr>
        <?php foreach($data as $item) {?>
            <tr>
                <td class="row" style="width: 30px">
                    <?php echo $item["id"];?>
                </td>
                <td class="row">
                    <?php echo $item["name"];?>
                </td>
                <td class="row">
                    <?php echo $item["createDate"];?>
                </td>
                <td>
                    <input type="button" value="删除" onclick="deleteAttribute(<?php echo $item["id"];?>);"/>&nbsp;
                    <input type="button" value="编辑" onclick="openPopWin('/admin/AdminApi/editAttribute?id=<?php echo $item["id"];?>');return false;"/>&nbsp;
                </td>
            </tr>
        <?php }?>
    </table>
    <script language="JavaScript">
        function deleteAttribute(idt)
        {
            if(confirm('你是否确认要删除属性')) {
                jNotify('处理中请稍后...');
                var qdata = {id: idt};
                $.get("/admin/AdminApi/deleteAttributeDo", qdata, function (data) {
                    closejNotify();
                    if (CheckObjErrorAndTure(data)) {
                        setTimeout(function () {
                            location.replace(location.href);
                        }, 500);
                    }
                }, 'json');
            }
            return false;
        }
    </script>
</block>