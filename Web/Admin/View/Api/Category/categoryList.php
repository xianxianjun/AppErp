<extend name="public/rightLayout"/>
<block name="head">

</block>
<block name="manageTitle">
    分类列表
</block>
<block name="content">
    <?php
    PageTabsHtml('CategoryList',$pageQrt,$Listcpage,$pageCount,$reCount);
    ?>
    <table class="qxTable" id="mytable" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <th scope="col" style="width: 30px;"></th>
            <th scope="col"><input id="namet" type="text" style="width: 100%;"/></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"><input type="button" onclick="search();return false;" value="搜索"/></th>
        </tr>
        <script>
            function search()
            {
                var keyword = $('#namet').val();
                if(keyword != '')
                {
                    location.href='categoryList?keyword='+keyword;
                }
                else
                {
                    //jError('请输入搜索条件');
                    location.href='categoryList';
                }
                return false;
            }
        </script>
        <tr scope="col">
            <th scope="col">id</th>
            <th scope="col">名称</th>
            <th scope="col">对应ErpId</th>
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
            <td class="row" style="width: 100px">
                <?php echo $item["erpTypeId"];?>
            </td>
            <td class="row">
                <?php echo $item["createDate"];?>
            </td>
            <td>
                <input type="button" value="删除" onclick="deleteCategory(<?php echo $item["id"];?>);"/>&nbsp;
                <input type="button" value="编辑" onclick="openPopWin('/admin/AdminApi/editCategory?id=<?php echo $item["id"];?>');return false;"/>&nbsp;
            </td>
        </tr>
        <?php }?>
    </table>
    <?php
    PageTabsHtml('CategoryList',$pageQrt,$Listcpage,$pageCount,$reCount);
    ?>
    <script language="JavaScript">
        function deleteCategory(idt)
        {
            if(confirm('你是否确认要删除分类')) {
                jNotify('处理中请稍后...');
                var qdata = {id: idt};
                $.get("/admin/AdminApi/deleteCategoryDo", qdata, function (data) {
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