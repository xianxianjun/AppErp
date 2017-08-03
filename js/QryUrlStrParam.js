function GetRequest() {
    var url = location.search; //获取url中"?"符后的字串
    var theRequest = new Object();
    if (url.indexOf("?") != -1) {
        var str = url.substr(1);
        strs = str.split("&");
        for (var i = 0; i < strs.length; i++) {
            theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
        }
    }
    return theRequest;
}
function getQueryString() {
    var result = location.search.match(new RegExp("[\?\&][^\?\&]+=[^\?\&]+", "g"));
    for (var i = 0; i < result.length; i++) {
        result[i] = result[i].substring(1);
    }
    return result;
}
function getQueryStringForName(name) {
    var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
    return null;
}
function getQueryStringForNameDefault(name,defaultValue) {
    var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
    return defaultValue;
}
function getQueryStringForUrl(url) {
    var urlarr = url.split('?');
    var result = null;
    if (urlarr.length > 1) {
        result = ("?"+urlarr[1]).match(new RegExp("[\?\&][^\?\&]+=[^\?\&]+", "g"));
        for (var i = 0; i < result.length; i++) {
            result[i] = result[i].substring(1);
        }
    }
    return result;
}
function getQueryStringForUrlCurrentUrl() {
    var currenturl = window.location.pathname + window.location.search;
    return getQueryStringForUrl(currenturl);
}
function replaceParamVal(Url, paramName, value) {
    var re = eval('/(' + paramName + '=)([^&]*)/gi');
    var nUrl = Url.replace(re, paramName + '=' + value);
    return nUrl;
}
function replaceParamValCurrentUrl(paramName, value) {
    var currenturl = window.location.pathname + window.location.search;
    return replaceParamVal(currenturl, paramName,value);
}
function removeParam(Url,paramName)
{
    var re = eval('/(' + paramName + '=)([^&]*)/gi');
    var nUrl = Url.replace(re, '').trim();
    re = eval('/&+/');
    nUrl = nUrl.replace(re, '&');
    re = eval('/[\\?|&]\s*$/gi');
    nUrl = nUrl.replace(re, '');
    re = eval('/\\?&/gi');
    nUrl = nUrl.replace(re, '?');
    return nUrl;
}
function removeParamCurrentUrl(paramName)
{
    var currenturl = window.location.pathname + window.location.search;
    return removeParam(currenturl,paramName)
}

function replaceOrAddParamVal(Url, paramName, value) {
    if(Url.indexOf(paramName+"=")<0)
    {
        if (Url.indexOf("?") > 0)
            return Url + "&" + paramName + "=" + value;
        else
            return Url + "?" + paramName + "=" + value;
    }
    else
    {
        return replaceParamVal(Url, paramName, value);
    }
}
function replaceOrAddParamValCurrentUrl(paramName, value) {
    var currenturl = window.location.pathname + window.location.search;
    return replaceOrAddParamVal(currenturl, paramName, value);
}

function getQueryString(name) {
    var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
    return null;
}