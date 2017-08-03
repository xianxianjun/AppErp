<extend name="public/rightLayout"/>
<block name="head">

</block>
<block name="manageTitle">
    批量图片
</block>
<block name="content">
    <div style="width: 100%;">
        <p style="padding: 20px 20px 20px 0px;"><textarea id="modellist" style="width: 100%; height: 150px;"></textarea></p>
        <div style="margin: auto 0; width: 100%"><input type="button" value="更新" onclick="updateAppDataBath();"/></div>
    </div>
    <script>
        function updateAppDataBath()
        {
            var modellist_t = $("#modellist").val();
            modellist_t = $.trim(modellist_t);
            if(modellist_t) {
                jNotify('请等待', {ShowOverlay: true, autoHide: false});
                $.post('/admin/AdminApi/updateAppImagesDataBathDo', {list: modellist_t},
                    function (data) {
                        closejNotify();
                        if (CheckObjErrorAndTure(data)) {

                        }
                        setTimeout("location.reload()", 200);
                    }

                    ,
                    'json'
                )
                ;
            }
        }
    </script>
</block>