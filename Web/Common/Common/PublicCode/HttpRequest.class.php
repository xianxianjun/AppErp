<?php
namespace Common\Common\PublicCode;
class HttpRequest
{
    public static function  getTempUser($curl)
    {
        if (empty($_SESSION["TempName"])) {
            //dirname(__FILE__)=E:\wamp\www\QxWeb\AppMall\Controller
            $_SESSION["TempName"] = tempnam(dirname(__FILE__) . '\\temp', 'cookie');
            curl_setopt($curl, CURLOPT_COOKIEJAR, $_SESSION["TempName"]);
        }
        //echo $_SESSION["TempName"];
        //http://localhost:9090/index.php?m=QxWeb&a=lists
        curl_setopt($curl, CURLOPT_COOKIEFILE, $_SESSION["TempName"]);
        return $_SESSION["TempName"];
    }

    public static function GetUrlHtml($url)
    {
        header("Content-Type:text/html;charset=utf-8");
        //$Html = file_get_contents($url);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        HttpRequest::getTempUser($ch);
        $Html = curl_exec($ch);
        curl_close($ch);
        //echo $Html;
        $Html = iconv("gb2312", "utf-8//IGNORE", $Html);
        return $Html;
    }

    public static function GetUrlHtmlUtf8($url)
    {
        header("Content-Type:text/html;charset=utf-8");
        //$Html = file_get_contents($url);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        HttpRequest::getTempUser($ch);
        $Html = curl_exec($ch);
        curl_close($ch);
        //echo $Html;
        $Html = iconv("utf-8", "utf-8//IGNORE", $Html);
        return $Html;
    }
    public static function GetUrlJson($url)
    {
        header("Content-Type:text/html;charset=utf-8");
        //$json = file_get_contents(C('BaseUrl') . $url);
        $thisUrl = C('BaseUrl');
        //echo $thisUrl. $url;
        return HttpRequest::GetBaseUrlJson($thisUrl . $url);
    }

    public static function GetBaseUrlJson($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        HttpRequest::getTempUser($ch);
        $json = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            return HttpRequest::SetJsonObjString('1', '', $err);
        }
        curl_close($ch);
        //echo C('BaseUrl') . $url;
        $json = iconv("gb2312", "utf-8//IGNORE", $json);
        return HttpRequest::unescape($json);
    }

    public static function AsynGetBaseUrlJson($url, $timeout = 1)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        HttpRequest::getTempUser($ch);
        $json = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            return HttpRequest::SetJsonObjString('1', '', $err);
        }
        curl_close($ch);
        //echo C('BaseUrl') . $url;
        $json = iconv("gb2312", "utf-8//IGNORE", $json);
        return HttpRequest::unescape($json);
    }

    public static function PostUrlJson($url, $data)
    {
        header("Content-Type:text/html;charset=utf-8");
        //$json = file_get_contents(C('BaseUrl') . $url);
        $thisUrl = C('BaseUrlApi');
        return HttpRequest::PostBaseUrlJson($thisUrl . $url, $data);
    }

    public static function PostBaseUrlJson($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        HttpRequest::getTempUser($ch);
        $json = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            return HttpRequest::SetJsonObjString('1', '', $err);
        }
        curl_close($ch);
        //echo C('BaseUrl') . $url;
        $json = iconv("gb2312", "utf-8//IGNORE", $json);
        return HttpRequest::unescape($json);
    }

    public static function AsynPostBaseUrlJson($url, $data, $timeout = 1)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        HttpRequest::getTempUser($ch);
        $json = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            return HttpRequest::SetJsonObjString('1', '', $err);
        }
        curl_close($ch);
        //echo C('BaseUrl') . $url;
        $json = iconv("gb2312", "utf-8//IGNORE", $json);
        return HttpRequest::unescape($json);
    }

    public static function GetUrlJsonObj($url)
    {
        $obj = null;
        $json = HttpRequest::GetUrlJson($url);
        //echo $json;
        if (strlen($json) > 0) {
            $obj = json_decode($json);
        }
        //echo $obj->error;
        return $obj;
    }

    public static function PostUrlJsonObj($url, $data)
    {
        $obj = null;
        $json = HttpRequest::PostUrlJson($url, $data);
        //echo $json;
        if (strlen($json) > 0) {
            $obj = json_decode($json);
        }
        //echo $obj->error;
        return $obj;
    }
    public static function GetUrlJsonObjCheckErr($url)
    {
        return HttpRequest::GetUrlJsonObjCheckErrBackUrl($url, '');
    }

    public static function GetUrlJsonObjCheckErrBackUrl($url, $BackUrl)
    {
        $obj = HttpRequest::GetUrlJsonObj($url);
        if ($obj == null || intval($obj->error) >= 1) {
            //echo '<script>alert('.json_encode($obj->message).')</script>';
            $isLocation = true;
            if (empty($BackUrl)) {
                $burl = $_SERVER['HTTP_REFERER'];
                if (intval(strpos($burl, $_SERVER['SERVER_NAME'], 7)) <= 0) {
                    $isLocation = false;
                }
            } else {
                $burl = $BackUrl;
            }
            //echo $url.'='.$_SERVER['SERVER_NAME'].'='.intval(strpos($url, $_SERVER['SERVER_NAME'],7));
            if (!empty($burl) && $isLocation) {
                setcookie('ErrReturnUrl', $burl);
            }
            header("Location: index.php?m=QxWeb&a=Err&mes=" . urlencode($obj->message));
            exit;
        }
        return $obj;
    }

    public static function GetUrlJsonObjString($url)
    {
        $objt = HttpRequest::GetUrlJsonObj($url);
        return json_encode($objt);
    }

    public static function GetUrlJsonObjData($url)
    {
        $obj = HttpRequest::GetUrlJsonObj($url);
        //echo json_encode($obj->data->webPage);
        if ($obj != null) {
            return $obj->data;
        }
        return null;
    }

    public static function GetUrlJsonObjDataCheckErr($url)
    {
        $obj = HttpRequest::GetUrlJsonObjCheckErr($url);
        //echo json_encode($obj->data->webPage);
        if ($obj != null) {
            return $obj->data;
        }
        return null;
    }

    public static function GetUrlJsonObjDataCheckErrBackUrl($url, $BackUrl)
    {
        $obj = HttpRequest::GetUrlJsonObjCheckErrBackUrl($url, $BackUrl);
        //echo json_encode($obj->data->webPage);
        if ($obj != null) {
            return $obj->data;
        }
        return null;
    }

    public static function GetUrlJsonObjDataString($url)
    {
        $objt = HttpRequest::GetUrlJsonObj($url);
        if ($objt != null) {
            $objtdata = $objt->data;
            return json_encode($objtdata);
        }
        return '';
    }

    public static function PostUrlJsonObjDataString($url, $data)
    {
        $objt = HttpRequest::PostUrlJsonObj($url, $data);
        if ($objt != null) {
            $objtdata = $objt->data;
            return json_encode($objtdata);
        }
        return '';
    }
    public static function SetJsonObj($error, $data, $message)
    {
        $objt = array(
            'response' => '',
            'error' => $error,
            'message' => $message,
            'data' => $data
        );
        return $objt;
    }

    public static function SetJsonObjString($error, $data, $message)
    {
        $objt = HttpRequest::SetJsonObj($error, $data, $message);
        return json_encode($objt);
    }
    public static function SetJsonObjStringForLogin()
    {
        return HttpRequest::SetJsonObjString('10000', '', '请先登录');
    }
    public static function unescape($str)
    {
        $ret = '';
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            if ($str[$i] == '%' && $str[$i + 1] == 'u') {
                $val = hexdec(substr($str, $i + 2, 4));
                if ($val < 0x7f)
                    $ret .= chr($val);
                else
                    if ($val < 0x800)
                        $ret .= chr(0xc0 | ($val >> 6)) . chr(0x80 | ($val & 0x3f));
                    else
                        $ret .= chr(0xe0 | ($val >> 12)) . chr(0x80 | (($val >> 6) & 0x3f)) . chr(0x80 | ($val & 0x3f));
                $i += 5;
            } else
                if ($str[$i] == '%') {
                    $ret .= urldecode(substr($str, $i, 3));
                    $i += 2;
                } else
                    $ret .= $str[$i];
        }
        return $ret;
    }

    public static function escape($string)
    {
        $n = $bn = $tn = 0;
        $output = '';
        $special = "-_.+@/*0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        while ($n < strlen($string)) {
            $ascii = ord($string[$n]);
            if ($ascii == 9 || $ascii == 10 || (32 <= $ascii && $ascii <= 126)) {
                $tn = 1;
                $n++;
            } elseif (194 <= $ascii && $ascii <= 223) {
                $tn = 2;
                $n += 2;
            } elseif (224 <= $ascii && $ascii <= 239) {
                $tn = 3;
                $n += 3;
            } elseif (240 <= $ascii && $ascii <= 247) {
                $tn = 4;
                $n += 4;
            } elseif (248 <= $ascii && $ascii <= 251) {
                $tn = 5;
                $n += 5;
            } elseif ($ascii == 252 || $ascii == 253) {
                $tn = 6;
                $n += 6;
            } else {
                $n++;
            }
            $singleStr = substr($string, $bn, $tn);
            $charVal = bin2hex(iconv('utf-8', 'ucs-2', $singleStr));
            if (base_convert($charVal, 16, 10) > 0xff) {
                if (!preg_match("/win/i", PHP_OS))
                    $charVal = substr($charVal, 2, 2) . substr($charVal, 0, 2);
                $output .= '%u' . $charVal;
            } else {
                if (false !== strpos($special, $singleStr))
                    $output .= $singleStr;
                else
                    $output .= "%" . dechex(ord($string[$bn]));
            }

            $bn = $n;
        }
        return $output;
    }
}