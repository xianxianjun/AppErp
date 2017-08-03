<?php
namespace Common\Common\PublicCode;
use Common\Common\Api\Code\myResponse;

/**
 * 获取验证码
 *
 *
 *
 *
 * @copyright  Copyright (c) 2015-2020 FanEr Inc. (http://www.faner.net)
 * @license    http://www.faner.net
 * @link       http://www.faner.net
 * @since      File available since Release v1.1
 */
class YunpianGetcode
{
	/**
	 * 获取验证码
	 */
	public static function SendCode($prefix,$key){
        $mobile = $key;
		$codes  = YunpianGetcode::random(6);
		//$apikey = "4c1b36f4b504b4950e004cc990b415f2";
		//$apikey = "63a4ec3f9d1d6afcd8bbfe91ed9279d7";
        $apikey = C("YunPianAipKey");//"dd63b51389b01dd06c689e6251e6fbe7";
		$text	= C("YunPianAipText").$codes;//"【千禧之星珠宝】您的验证码是".$codes;
        $return = YunpianGetcode::send_sms($apikey,$text,$mobile);
        S($prefix.$mobile,$codes,300);
        
        
		//echo $return;

        $arrreturn = json_decode($return,true);
        
        if($arrreturn['code']==0)
        {
            return myResponse::ResponseDataTrueObj("获取验证码成功");
        }
        if($arrreturn['code']==17)
        {
            return myResponse::ResponseDataFalseObj("发送次数超过当天限制，请您明天再试！");
        }
        if($arrreturn['code']==22)
        {
            return myResponse::ResponseDataFalseObj("操作频繁，请您一个小时后再试！");
        }
        if($arrreturn['code']==33)
        {
            return myResponse::ResponseDataFalseObj("请在30秒后再获取验证码！");
        }
        return myResponse::ResponseDataFalseObj("获取验证码失败",$return.$mobile);
    }

    public static function CheckCode($key,$code){

        $mobile = $key;
        if(empty($code)){
            return myResponse::ResponseDataFalseObj("认证码不能为空");
        }
        if(empty($mobile)){
            return myResponse::ResponseDataFalseObj("手机号码不能为空");
        }
        // 判断认证码是否正确
        if($code == S($mobile)){
            S($mobile,null);
            return myResponse::ResponseDataTrueObj("认证成功");

        }else{
            return myResponse::ResponseDataFalseObj("手机认证码不正确");
        }
    }

    private static function  random($length = 6 , $numeric = 0) {
		PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
		if($numeric) {
			$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
		} else {
			$hash = '';
			$chars = '0123456789';
			$max = strlen($chars) - 1;
			for($i = 0; $i < $length; $i++) {
				$hash .= $chars[mt_rand(0, $max)];
			}
		}
		return $hash;
	}

	
	private static function send_sms($apikey, $text, $mobile){
		$url="http://yunpian.com/v1/sms/send.json";
		$encoded_text = urlencode($text);
		$post_string="apikey=$apikey&text=$encoded_text&mobile=$mobile";
		return YunpianGetcode::sock_post($url, $post_string);
	}
	private static function sock_post($url,$query){
		$data = "";
		$info=parse_url($url);
		$fp=fsockopen($info["host"],80,$errno,$errstr,30);
		if(!$fp){
			return $data;
		}
		$head="POST ".$info['path']." HTTP/1.0\r\n";
		$head.="Host: ".$info['host']."\r\n";
		$head.="Referer: http://".$info['host'].$info['path']."\r\n";
		$head.="Content-type: application/x-www-form-urlencoded\r\n";
		$head.="Content-Length: ".strlen(trim($query))."\r\n";
		$head.="\r\n";
		$head.=trim($query);
		$write=fputs($fp,$head);
		$header = "";
		while ($str = trim(fgets($fp,4096))) {
			$header.=$str;
		}
		while (!feof($fp)) {
			$data .= fgets($fp,4096);
		}
		return $data;
	}
	

	
}
