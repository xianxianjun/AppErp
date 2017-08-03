function ChangeOrderStatus(obj,orderId,defaultValue)
{
    var valt = $(obj).val();
    if(confirm('确认更新状态吗吗？')) {
        jNotify('请等待', {ShowOverlay: true, autoHide: false});
        if (isNumber(orderId)) {
            $.get('/admin/AdminApi/AdminChangeStoneOrderStatusDo?value='+valt+'&orderId='+orderId, function (sdata) {
                closejNotify();
                var data = JSON.parse(sdata);
                if (CheckObjErrorAndTure(data)) {

                }
                setTimeout("location.reload()", 200);
            });
        }
    }
    else
    {
        $(obj).val(defaultValue);
    }
}
