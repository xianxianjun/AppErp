function openPopWin(url)
{
    var bw = $(document).width();
    var bh = document.documentElement.clientHeight;
    jNotify("加载中请等待....");
    closepopWin();
    if($("#rightTopPopDiv").length<=0)
    {
        $("body").append('<div id="rightTopPopDiv" style="position:absolute;background-color: #666666;z-index:5;display: none;width: 100%;"></div>');
        $("body").append('<iframe id="popForwiniframe" style="position:absolute;z-index:10;display: none;" onload="popWin('+bw+','+bh+');" src="'+url+'"></iframe>');
        $("body").append('<div class="circle" id="popForwinline" style="text-align:right;cursor: pointer;position:absolute;z-index:15;display: none;color: #FFFFFF;line-height: 20px;" title="关闭" onclick="closepopWin();"><span style="margin-right: 60px;font-weight: bolder">X&nbsp;&nbsp;<span></div>');
        $("#popForwinline").mouseover(function(){
            $("#popForwinline").css("background","#000000");
            $("#popForwinline").css("color","red");
        });
        $("#popForwinline").mouseout(function(){
            $("#popForwinline").css("background","red");
            $("#popForwinline").css("color","#FFFFFF");
        });
    }
}
$(function() {
    $(window).scroll(function () {
        if($("#popForwiniframe").length>0 && $("#rightTopPopDiv").length>0 && $("#popForwinline").length>0) {
            var top = $(window).scrollTop();
            var bw = $(document).width();
            var left = $(window).scrollLeft();
            $("#popForwiniframe").css({top: (top + 30) + "px"});
            $("#rightTopPopDiv").css({top: top + "px"});
            $("#popForwinline").css({top: (top+35) + "px"});
        }
    });
});
function closepopWin()
{
    $("#rightTopPopDiv").remove();
    $("#popForwiniframe").remove();
    $("#popForwinline").remove();
}
function popWin(bw,bh)
{
    var h = $(window).height();
    var st = $(window).scrollTop();
    $("#rightTopPopDiv").css('opacity','0.5');
    $("#rightTopPopDiv").width(bw);
    $("#rightTopPopDiv").height(h);
    $("#rightTopPopDiv").css("left","0px");
    $("#rightTopPopDiv").css("top", st);
    $("#popForwiniframe").width(bw-40);
    $("#popForwiniframe").height(h-60);
    $("#popForwiniframe").css("left","20px");
    $("#popForwiniframe").css("top",(st + 30)+'px');
    $("#popForwinline").css("left",(bw-50)+"px");
    $("#popForwinline").css("top",st+35);

    var iframe = document.getElementById('popForwiniframe');//获取那个iframe，也可以用$('#iframe')[0]替代
    var iframeWindow = iframe.contentWindow;//获取iframe里的window对象
    var $c = iframeWindow.$;//获取iframe中的jquery对象
    var ifh = $c('body').height();//获取iframe中body元素，其他的话自己用$c('#aaa')去获取吧
    if(ifh>0 && ifh<bh) {
        $("#popForwiniframe").height(ifh);
    }
    else
    {
        $("#popForwiniframe").height(bh-60);
    }
    $("#rightTopPopDiv").css('display','');
    $("#popForwiniframe").css('display','');
    $("#popForwinline").css('display','');
    closejNotify();
}
