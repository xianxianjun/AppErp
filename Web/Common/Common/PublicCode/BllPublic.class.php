<?php
namespace Common\Common\PublicCode;
use Common\Common\Api\flow\BaseCls;
require_once 'BllPublic/BLLPUBLIC.INC.php';
class BllPublic
{
    use BllPublicModelPrice;
    //石头加点

    public static function GetPicBasePath()
    {
        return C("PicBasePath");
    }
    public static function SqlConnPicHttpBasePath($picField = 'pic',$asPicField = 'pic')
    {
        return "CONCAT("."'" . BllPublic::GetPicBasePath() ."',".$picField.") as ".$asPicField;
    }
    public static function GetPicBasePathPic($picfile)
    {
        if(empty($picfile))
        {
            return "";
        }
        return C("PicBasePath").$picfile;
    }
    public static function ChangeSpecErpValue($valueStr)
    {

        $valueStr = trim($valueStr);
        $arr = array();
        if(preg_match('/^\s*(\d*[\.]{0,1}\d+)\s*$/',$valueStr,$arr))//0.5
        {
            return $arr[1];
        }
        else if(preg_match('/^\s*(\d*[\.]{0,1}\d+)\s*\'[\s|\']*$/',$valueStr,$arr))//12'
        {
            return strval($arr[1]/100);
        }
        return $valueStr;
    }
    public static function GetStandardSpec($valueStr)
    {
        $valueStr = trim($valueStr);
        $arr = array();

        if(preg_match('/^\s*(\d*[\.]{0,1}\d+)\s*$/',$valueStr,$arr))//0.5
        {
            return $arr[1];
        }
        else if(preg_match('/^\s*(\d*[\.]{0,1}\d+)\s*\'[\s|\']*$/',$valueStr,$arr))//12'
        {
            return $arr[1].'\'';
        }
        if(preg_match('/^\s*([0-9]*[\.|\*]{0,1}[0-9]+)\s*$/',$valueStr,$arr))
        {
            return $arr[1];
        }
       return null;
    }
    public static function GetSpecErpSelectTitle($valueStr)
    {
        $valueTemp = BllPublic::GetSpecValue($valueStr);
        if(!empty($valueTemp)) {
            $title = "请参考输入".$valueTemp["down"]."到".$valueTemp["up"]."之间的规格";
        }
        else if(!empty($valueStr))
        {
            $title = "请输入".$valueStr."的规格";
        }
        else
        {
            $title = "请输入的规格";
        }
        return $title;
    }
    public static function GetSpecValue($valueStr)
    {
        $value = BllPublic::ChangeSpecErpValue($valueStr);
        if(preg_match('/^\d*[\.]{0,1}\d+$/',$value)) {
            $stoneSpec = BaseCls::CacheStoneSpec();
            $valueTemp = null;
            foreach($stoneSpec as $tvalue)
            {
                if($value>$tvalue["down"] && $value<=$tvalue["up"])
                {
                    $valueTemp = $tvalue;
                    break;
                }
            }
        }
        return $valueTemp;
    }
    public static function GetSpecValueId($valueStr)
    {
        $value = BllPublic::GetSpecValue($valueStr);
        return $value["id"];
    }
    public static function GethandSizeData()
    {
        //-----------------handSizeData
        $handSizeData = S("handSizeData201704011111");
        if(empty($handSizeData)) {
            $handSizeData = array();
            $ni = 0.5;
            while (true) {
                $ni = $ni + 0.5;
                $handSizeData[] = strval($ni);
                if ($ni >= 30) break;
            }
        }
        //-----------------handSizeData
        return $handSizeData;
    }
    //生成支付单号
    public static function makePaySn($member_id) {
        return mt_rand(10,99)
        . sprintf('%010d',time() - 946656000)
        . sprintf('%03d', (float) microtime() * 1000)
        . sprintf('%03d', (int) $member_id % 1000);
    }
}