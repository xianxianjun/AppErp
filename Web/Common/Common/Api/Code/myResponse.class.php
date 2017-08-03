<?php
namespace Common\Common\Api\Code;
class ValidateCode
{
    public static $noError = 0;//操作成功
    public static $haveError = 1;//操作失败
    public static $noLogin = 2;//没有登录
    public static $systemErr = 2;//系统错误
}
class myResponseCls {
    public $response = '';
    public $error = 0;
    public $message = '';
    public $data = null;
}
class myResponse {
    public static function ResponseDataObjBase($response = '',$error = 0,$message = '',$data = null)
    {
        $obj = new myResponseCls();
        $obj->data = $data;
        $obj->response = $response;
        $obj->error = $error;
        $obj->message = $message;
        return $obj;
    }
    public static function ToResponseJsonString($obj)
    {
        $str = json_encode($obj);
        ob_clean();
        //$str = substr($str,strpos($str,"{"));
        return $str;
    }
    public static function IsResponseErr($obj)
    {
        if($obj->error != 0)
        {
            return true;
        }
        return false;
    }
    public static function IsResponseErrs($objs)
    {
        foreach($objs as $obj) {
            if ($obj->error != 0) {
                return $obj;
            }
        }
        return null;
    }
    public static function ResponseDataStringBase($response = '',$error = 0,$message = '',$data = null)
    {
        $str = json_encode(myResponse::ResponseDataObjBase($response,$error,$message,$data));
        ob_clean();
        //$str = substr($str,strpos($str,"{"));
        return $str;
    }
    //string
    public static function ResponseDataString($error,$data = null)
    {
        return myResponse::ResponseDataStringBase('',$error,'',$data);
    }
    public static function ResponseDataTrueString($message = null,$data = null)
    {
        return myResponse::ResponseDataStringBase('',ValidateCode::$noError,$message,$data);
    }
    public static function ResponseDataTrueDataString($data = null)
    {
        return myResponse::ResponseDataStringBase('',ValidateCode::$noError,'',$data);
    }
    public static function ResponseDataFalseString($message = null,$data = null)
    {
        return myResponse::ResponseDataStringBase('',ValidateCode::$haveError,$message,$data);
    }
    public static function ResponseDataNoLoginString()
    {
        return myResponse::ResponseDataStringBase('',2,'没有登录','');
    }
    //obj
    public static function ResponseDataObj($error,$data = null)
    {
        return myResponse::ResponseDataObjBase('',$error,'',$data);
    }

    public static function ResponseDataTrueDataObj($data = null)
    {
        return myResponse::ResponseDataObjBase('',ValidateCode::$noError,'',$data);
    }
    public static function ResponseDataTrueObj($message = null,$data = null)
    {
        return myResponse::ResponseDataObjBase('',ValidateCode::$noError,$message,$data);
    }
    public static function ResponseDataFalseObj($message = null,$data = null)
    {
        return myResponse::ResponseDataObjBase('',ValidateCode::$haveError,$message,$data);
    }
    public static function ResponseDataNoLoginObj()
    {
        return myResponse::ResponseDataObjBase('',2,'没有登录','');
    }
}