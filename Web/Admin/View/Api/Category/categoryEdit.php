<extend name="public/rightLayout"/>
<block name="head">

</block>
<block name="manageTitle">
    <?php echo $title; ?>
</block>
<block name="content">
    <table class="qxTable" id="mytable" style="width: 100%" cellspacing="0">
        <tr>
            <th colspan="2"></th>
        </tr>
        <tr>
            <td class="row" style="width: 120px">
                分类名称:
            </td>
            <td class="row">
                <input id="categoryName" type="text" value="<?php echo $editdata[0]["name"]?>"/>
            </td>
        </tr>
        <tr>
            <td class="row" style="width: 120px">
                对应ERPID:
            </td>
            <td class="row">
                <input id="erpId" type="text" value="<?php echo $editdata[0]["erpTypeId"]?>"/>
            </td>
        </tr>
        <tr>
            <td class="row" colspan="2" align="center">
                <input type="submit" onclick="<?php if(empty($editdata)){?>addCategory();<?php }else{?>editCategory(<?php echo $editdata[0]["id"];?>);<?php }?>" value="确定"/>
            </td>
        </tr>
    </table>
    <script>
        function editCategory(idt)
        {
            var namet = $("#categoryName").val();
            var erpidt = $("#erpId").val();
            var qdata = {id:idt,name:namet,erpId:erpidt};
            jNotify("处理中请稍后...")
            $.get("/admin/AdminApi/editCategoryDo",qdata,function(data)
            {
                closejNotify();
                if(CheckObjErrorAndTure(data))
                {
                    setTimeout(function () {
                        parent.location.replace(parent.location);
                    },500);
                }
            },'json');
            return false;
        }
        fu
        function addCategory()
        {
            var namet = $("#categoryName").val();
            var erpidt = $("#erpId").val();
            var qdata = {name:namet,erpId:erpidt};
            jNotify("处理中请稍后...")
            $.get("/admin/AdminApi/addCategoryDo",qdata,function(data)
            {
                closejNotify();
                if(CheckObjErrorAndTure(data))
                {
                    setTimeout(function () {
                        location.replace(this.location);
                    },500);
                }
            },'json');
            return false;
        }
    </script>
</block>