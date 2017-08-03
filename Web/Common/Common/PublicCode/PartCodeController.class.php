<?php
namespace Common\Common\PublicCode;
class PartCodeController
{
    public static  function DisplayPath($ViewPatnName)
    {
        if($ViewPatnName==null)
        {
            $path = BASE_PATH.str_replace("./","",APP_PATH).MODULE_NAME.'/View/'.ACTION_NAME.'.php';
        }
        else
       {
           $ViewPatnName = str_replace('\\','/',$ViewPatnName);
           $path = BASE_PATH.str_replace("./","",APP_PATH).MODULE_NAME.'/View/'.$ViewPatnName.'.php';
        }
        return $path;
    }
    public static  function GotoForErr($Message)
    {
        header("Location: index.php?m=QxWeb&a=Err&c=index&mes=".$Message);
        exit;
    }
    public static  function GotoForLogin()
    {
        setcookie('LoginUrl', $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
        header("Location: index.php?m=QxWeb&a=login&c=index");
        exit;
    }
    public static function DisplayPathThis()
    {
        return PartCodeController::DisplayPath(null);
    }
}