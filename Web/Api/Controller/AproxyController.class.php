<?php
namespace Api\Controller;
use Think\Controller;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\BllPublic;
use Common\Common\PublicCode\FunctionCode;
require_once 'Aproxy/CONTROLLER.APROXY.INC.php';
class AproxyController extends Controller {
    use userDoProxy,userPageProxy,modelPageProxy,BaseProxy,ErpCustomerProxy,BillProxy,stoneProxy,IndexProxy;
    public function currentVersion()
    {
        PublicController::currentVersion();
    }
    public function test()
    {
        //echo  date('Y-m-d H:i:s', time());
        //echo microtime();
        //echo session('model');
        //session('model','api');
        //$this->show('Api');
        //UserController::test();
        //$data = M('model_category')->where('')->select();
        //$n = 0;

    }
    public function SetTest()
    {
        UserController::SetTest();
    }
    public function GetTest()
    {
        UserController::GetTest();
    }
    public function SetNULL()
    {
        UserController::SetNULL();
    }
    public function testAlipayPay()
    {
        $sn = FunctionCode::getTimeId();
        $orderId = 'AP'.substr($sn,-8);
        $out_trade_no = BllPublic::makePaySn(10);
        $total_fee = "0.01";
        $proName = "测试支付";
        $probody = "千禧之星";
        $data = array("orderId"=>$orderId,"out_trade_no"=>$out_trade_no,
        "total_fee"=>$total_fee,"proName"=>$proName,"probody"=>$probody);
        $json = myResponse::ResponseDataTrueDataString($data);
        //$myfile = fopen(BASE_PATH."/log/".$orderId.".txt", "w") or die("Unable to open file!");
        //$txt = $json;
        //fwrite($myfile, $txt);
        //fclose($myfile);
        file_put_contents(BASE_PATH."/log/GetOrder.txt", "\r\n【".FunctionCode::GetNowTimeDate()."】".$json."\r\n\r\n\r\n", FILE_APPEND);
        echo $json;
    }
    public function testAlipay_return_url()
    {
        FunctionCode::GetWebParam('');
    }
}
