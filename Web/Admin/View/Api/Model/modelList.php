<extend name="public/rightLayout"/>
<block name="head">

</block>
<block name="manageTitle">
    款号列表
</block>
<block name="content">
    <?php
    PageTabsHtml('ModelList',$pageQrt,$Listcpage,$pageCount,$reCount);
    ?>
    <table class="qxTable" id="mytable" style="width: 100%"  cellspacing="0">
        <tr scope="col">
            <th scope="col" style="width: 30px;"></th>
            <th scope="col"></th>
            <th scope="col" colspan="2"><input id="modelNumModelName_s" type="text" style="width: 100%;"/></th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col"><input type="button" onclick="search();return false;" value="搜索"/></th>
        </tr>
        <script>
            function search()
            {
                var keyword = $('#modelNumModelName_s').val();
                if(keyword != '')
                {
                    location.href='modelList?keyword='+escape(keyword);
                }
                else
                {
                    //jError('请输入搜索条件');
                    location.href='modelList';
                }
                return false;
            }
        </script>
        <tr scope="col">
            <th scope="col">id</th>
            <th scope="col">相片</th>
            <th scope="col">款号</th>
            <th scope="col" style="width: 100px;">名称</th>
            <th scope="col">创建时间</th>
            <th scope="col">修改时间</th>
            <th scope="col" style="width: 200px;">操作</th>
        </tr>
        <?php foreach($modelData as $item) {?>
        <tr>
            <td class="row" style="width: 30px">
                <?php echo $item["id"];?>
            </td>
            <td class="row" style="width: 100px">
                <img onclick="openPopWin('/admin/index/showPic?id=<?php echo $item["id"];?>');" src="<?php if(!empty($item["pic"])){echo C('PicBasePath').$item["pic"];}else{echo "/images/forImage/Images/nopic.png";}?>" style="width: 100px;height: 100px;cursor: pointer"/>
            </td>
            <td>
                <?php echo $item["modelNum"];?>
            </td>
            <td>
                <?php echo $item["name"];?>
            </td>
            <td>
                <?php echo $item["createDate"];?>
            </td>
            <td>
                <?php echo $item["updateDate"];?>
            </td>
            <td>
                <input type="button" onclick="openPopWin('/admin/AdminApi/editModel');return false;" value="编辑"/>
                <input type="button" onclick="return false;" value="隐藏"/>
                <input type="button" onclick="return false;" value="删除"/>
            </td>
        </tr>
        <?php }?>
        </table>
    <?php
    PageTabsHtml('ModelList',$pageQrt,$Listcpage,$pageCount,$reCount);
    ?>
</block>