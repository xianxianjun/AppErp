function checkRate(input) {
    var re = /^[0-9]+.?[0-9]*$/;   //判断字符串是否为数字     //判断正整数 /^[1-9]+[0-9]*]*$/   
    if (!re.test(input)) {
        return false;
    }
    else
    {
        return true;
    }
}
function GetNum() {
    var myDate = new Date();
    var str = myDate.getHours() + '' + myDate.getMinutes() + '' + myDate.getSeconds() + '' + myDate.getMilliseconds();
    return str;
}
function SetLoginUrl(url)
{
    $.cookie('LoginUrl', url);
}
function GetLoginUrl()
{
    var str = $.cookie('LoginUrl');
    $.cookie('LoginUrl', null);
    if(str!=null && str!="")
    {
        return str;
    }
    return '';
}
function SetCurrentLoginUrl()
{
    var currenturl = window.location.pathname + window.location.search;
    SetLoginUrl(currenturl);
}
function CheckObjError(obj)
{
    var isNotError = true;
    if (Number(obj.error) == nologinId)
    {
        SetCurrentLoginUrl();
        //location.href = '/admin';
        jError(obj.message, { ShowOverlay: false });
        isNotError = false;
    }
    else if (Number(obj.error) > 0)
    {
        jError(obj.message, { ShowOverlay: false });
        isNotError = false;
    }
    return isNotError;
}
function CheckObjErrorAndTure(obj)
{
    if(CheckObjError(obj))
    {
        jSuccess(obj.message, { ShowOverlay: false });
        return true;
    }
    return false;
}
function OutLogin(funo) {
    $.get("login/out?rom=" + GetNum(), function (data, textStatus) {
        if (data.error == '0') {
            jNotify('退出成功', { ShowOverlay: false });
            $.cookie('loginname', '');
            funo();
        }
    }, 'json');
}
function SetCookieKey(key,value)
{
    $.cookie(key, value);
}
function GetCookieKey(key)
{
    var str = $.cookie(key);
    if(str == null) str = '';
    return str;
}
function SetLoginTokenKey(TokenKey)
{
    SetCookieKey('LoginTokenKey', TokenKey);
}
function GetLoginTokenKey()
{
    var str = GetCookieKey('LoginTokenKey');
    return str;
}
function SetLoginName(Name)
{
    SetCookieKey('LoginName', Name);
}
function GetLoginName()
{
    var str = GetCookieKey('LoginName');
    return str;
}
function SetLoginAccount(Name)
{
    $.cookie('LoginAccount', Name,{expires:7});
}
function GetLoginAccount()
{
    var str = GetCookieKey('LoginAccount');
    return str;
}
function SetLoginheadPic(pic)
{
    SetCookieKey('LoginHeadPic', pic);
}
function GetLoginHeadPic()
{
    var str = GetCookieKey('LoginHeadPic');
    return str;
}
function GetPageBase()
{
    var m = getQueryString('m');
    var a = getQueryString('a');
    var c = getQueryString('c');
    var url = '?';
    var urland  = '';
    if(m != null || $.trim(m)!='')
    {
        urland = url =='?'?'':'&';
        url = url +urland+'m='+$.trim(m);
    }
    if(a != null || $.trim(a)!='')
    {
        urland = url =='?'?'':'&';
        url = url +urland+'a='+$.trim(a);
    }
    if(c != null || $.trim(c)!='')
    {
        urland = url =='?'?'':'&';
        url = url +urland+'c='+$.trim(c)
    }
    return url;
}
function ReturnToHistory()
{
    var c = getQueryStringForName('c');
    c = c == null?'': c.trim().toLowerCase();
    var a = getQueryStringForName('a');
    a = a == null?'': a.trim().toLowerCase();
    if(c=='' && a =='setup')
    {
        QxRepaceUrl('index.php?m=QxWeb&a=mycenter');
    }
    else if(c=='shoppage' && a =='nearbyshoplist')
    {
        QxRepaceUrl('index.php');
    }
    else if(c=='index' && a=='selectpickupaddress')
    {
        var url = GetPickUpAddressUrl();
        if(url!='') {
            QxRepaceUrl(url);
        }
        else
        {
            history.back(-1);
        }
    }
    else if(c=='agent' && (a=='agentapply' || a=='agentapplyweixinpay' || a=='agentapplypaylose'|| a=='agentapplypayfinish'))
    {
        QxRepaceUrl('index.php?m=QxWeb&a=mycenter');
    }
    else {
        history.back(-1);
    }
}
function ReturnToHistoryForErp()
{
    history.back(-1);
}
//设置top菜单
function setTopMenu(urlName,url)
{
    var html = '';
         html = html + '<li><a href="'+ url
              + '" target="_self"><i class="index_menu_icon5"></i>'
              + urlName
              + '</a></li>'
    $(".index_menu ul").append(html);
}
function isInteger(x) {
    var re = /^\d+$/;
    return re.test(x);
}
function ChangeToInteger(x,defaultVal)
{
    if(isInteger(x))
    {
        return parseInt(x);
    }
    else
    {
        return defaultVal;
    }
}
function isNumber(x)
{
    var re = /^[0-9]+\.{0,1}[0-9]*$/;
    return re.test(x);
}
function validatemobile(mobile)
{
    if(mobile.length==0)
    {
        return false;
    }
    var isChinaMobile = /^134[0-8]\d{7}$|^(?:13[5-9]|147|15[0-27-9]|178|18[2-478])\d{8}$/; //移动方面最新答复
    var isChinaUnion = /^(?:13[0-2]|145|15[56]|176|18[56])\d{8}$/; //向联通微博确认并未回复
    var isChinaTelcom = /^(?:133|153|177|18[019])\d{8}$/; //1349号段 电信方面没给出答复，视作不存在
    var isOtherTelphone  = /^170([059])\d{7}$/;//其他运营商

    if(!isChinaMobile.test(mobile) &&  !isChinaUnion.test(mobile) &&  !isChinaTelcom.test(mobile) &&  !isOtherTelphone.test(mobile))
    {
        return false;
    }
    return true;
}
function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}
function QxRepaceUrl(url)
{
    location.replace(url);
}
function ResquestStrKeyValueItem(key,value)
{
    key = $.trim(key);
    value = $.trim(value);
    if(key.length > 0 && value.length > 0)
    {
        return key+"="+value;
    }
    return "";
}
function ResquestStrKeyValueArr(arrkey,arrvalue)
{
    if(isArray(arrkey) && isArray(arrvalue))
    {
        var str = '';
        for(var i = 0;i<arrkey.length;i++)
        {
            var key = arrkey[i];
            var value = arrvalue[i];
            var strItem = ResquestStrKeyValueItem(key,value)
            if(strItem.length > 0)
            {
                str = str==''?strItem:str+"&"+strItem;
            }
        }
        return str;
    }
    return "";
}
function isArray(obj) {
    return Object.prototype.toString.call(obj) === '[object Array]';
}