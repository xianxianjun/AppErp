<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/5/18
 * Time: 17:05
 */
namespace Api\Controller;
use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\ModelOrderCls;
use Common\Common\PublicCode\BllPublic;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\Api\flow\StoneCls;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\Api\Code\ValidateCode;

trait stoneOrderPage
{
    public function stoneOrderDetailpage()
    {
        $orderId = I("get.orderId");
        if(!empty($orderId)) {
            $myKey = UserCls::GetRequestTokenKey();
            $memberId = UserCls::GetUserId($myKey);
            $data = M('jewel_stone_order')->where(array("id"=>$orderId,"member_Id"=>$memberId))->select();
            $list = M('jewel_stone_order_detail')->where(array("jewel_stone_order_id"=>$orderId,"member_Id"=>$memberId))->select();
            $orderStatusTitle = StoneCls::GetOrderStatusName($data[0]["orderStatus"]);
            $customerName = $data[0]["customerName"];
            $orderNum = $data[0]["orderNum"];
            $postName = $data[0]["postName"];
            $postTel = $data[0]["phone"];
            $postAddress = $data[0]["address"];
            $totelPrice = $data[0]["discountTotalPrice"];
            $orderNumber = count($list);
            $remark = FunctionCode::ReurnDefault($data[0]["remark"],null,"");
            $orderDate = $data[0]["createDate"];
            $isNeetPay = intval($data[0]["orderStatus"])<=StoneCls::$STONE_ORDER_WAIT_PAY && intval($data[0]["orderStatus"])>0?1:0;//是否需要支付
            $listarr = array();
            foreach($list as $item)
            {
                $otherStr = (empty($item['CertCode'])?"":"证书号:".$item['CertCode'])
                    .(empty($item['CertAuth'])?"":"证书:".$item['CertAuth'])
                    .(empty($item['Weight'])?"":" 重量:".$item['Weight'])
                    .(empty($item['Shape'])?"":" 形状:".$item['Shape'])
                    .(empty($item['Color'])?"":" 颜色:".$item['Color'])
                    .(empty($item['Purity'])?"":" 净度:".$item['Purity'])
                    .(empty($item['Cut'])?"":" 切工:".$item['Cut'])
                    .(empty($item['Polishing'])?"":" 抛光:".$item['Polishing'])
                    .(empty($item['symmetric'])?"":" 对称:".$item['symmetric'])
                    .(empty($item['Fluorescence'])?"":" 荧光:".$item['Fluorescence']);
                $listarr[] = array("info"=>$otherStr
                ,"price"=>sprintf("%.2f",floatval($item['discountPrice'])*intval($item['number']))
                ,"number"=>$item['number']);
            }
            $data = array("orderStatusTitle"=>$orderStatusTitle
            ,"customerName"=>$customerName
            ,"orderNum"=>$orderNum
            ,"postName"=>$postName
            ,"postTel"=>$postTel
            ,"postAddress"=>$postAddress
            ,"totelPrice"=>$totelPrice
            ,"orderNumber"=>$orderNumber
            ,"remark"=>$remark
            ,"isNeetPay"=>$isNeetPay
            ,"orderDate"=>$orderDate,"list"=>$listarr);
            echo myResponse::ResponseDataTrueDataString($data);
        }
    }
    public function stoneOrderListPage()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $addressId = I("get.addressId");
        $ids = I("get.id");
        //$percent = I("get.percent");
        $percent = UserCls::getUserStoneAddtion($memberId);
        if(empty($ids))
        {
            $ids = 0;
        }
        //地址
        $address = null;
        if(empty($addressId) && UserCls::IsUserUsePickDefaultAddress($memberId)) {
            $address = myResponse::ResponseDataTrueDataObj(UserCls::$PickDefaultAddress);
            $address = $address->data;
        }
        else {
            if (FunctionCode::isInteger($addressId) && intval($addressId) > 0) {
                $reObj = UserCls::getAddressInfoById($myKey, $addressId);
                $address = $reObj->error != ValidateCode::$noError ? UserCls::$PickDefaultAddress : $reObj->data;
            } else {
                $reObj = UserCls::getDefultAddress($myKey);
                $address = $reObj->error != ValidateCode::$noError ? UserCls::$PickDefaultAddress : $reObj->data;
            }
        }

        //$list = M("jewel_stone_product")->field("*")->where("id in ($ids)")->select();
        $cdata = ErpPublicCode::GetUrlObjData("stoneOrderListPage", "stone", array("ids"=>$ids,"percent"=>floatval($percent)));
        $list = $cdata->stoneList;
        $arrData = array();
        foreach($list as $item)
        {
            /*$str =
                (empty($item['CertAuth'])?"":"证书:".$item['CertAuth'])
                .(empty($item['CertCode'])?"":" 证书号:".$item['CertCode'])
                .(empty($item['Weight'])?"":" 重量:".$item['Weight'])
                .(empty($item['Shape'])?"":" 形状:".$item['Shape'])
                .(empty($item['Color'])?"":" 颜色:".$item['Color'])
                .(empty($item['Purity'])?"":" 净度:".$item['Purity'])
                .(empty($item['Cut'])?"":" 切工:".$item['Cut'])
                .(empty($item['Polishing'])?"":" 抛光:".$item['Polishing'])
                .(empty($item['symmetric'])?"":" 对称:".$item['symmetric'])
                .(empty($item['Fluorescence'])?"":" 荧光:".$item['Fluorescence']);
            $arrData[] = array("id"=>$item['id'],"price"=>sprintf("%.2f",floatval($item['Price'])*$percent/100),"info"=>$str);*/

            $str =
                (empty($item->CertAuth)?"":"证书:".$item->CertAuth)
                .(empty($item->CertCode)?"":" 证书号:".$item->CertCode)
                .(empty($item->Weight)?"":" 重量:".$item->Weight)
                .(empty($item->Shape)?"":" 形状:".$item->Shape)
                .(empty($item->Color)?"":" 颜色:".$item->Color)
                .(empty($item->Purity)?"":" 净度:".$item->Purity)
                .(empty($item->Cut)?"":" 切工:".$item->Cut)
                .(empty($item->Polishing)?"":" 抛光:".$item->Polishing)
                .(empty($item->symmetric)?"":" 对称:".$item->symmetric)
                .(empty($item->Fluorescence)?"":" 荧光:".$item->Fluorescence);
            $arrData[] = array("id"=>$item->id,"price"=>sprintf("%.2f",$item->Price),"info"=>$str);

        }
        $data = array("address"=>$address,"list"=>$arrData,"customer"=>$cdata->customer);
        echo myResponse::ResponseDataTrueDataString($data);
    }
    public function StoneInvoicePage()
    {
        echo myResponse::ResponseDataTrueDataString(array("invoiceType"=>StoneCls::$INVOICE_TYPE));
    }
    public function PaymentCurrentOrderStonePage()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $orderId = I("get.orderId");
        $order = M("jewel_stone_order")->where(array("id"=>$orderId,"member_Id"=>$memberId))->select();
        if(empty($order) || count($order)<=0)
        {
            echo myResponse::ResponseDataFalseString("找不到订单");
            return;
        }
        echo myResponse::ResponseDataTrueDataString(array("title" => $order[0]["orderNum"] . "订单支付"
            , "needPayPrice" => $order[0]["discountTotalPrice"]));
    }
    public function stoneWaitPayOrderList()//待付款
    {
        /*$cpage = I("get.cpage");
        if(FunctionCode::isInteger($cpage))
        {
            if(empty($pageCount)) {
                $pageCount = 3;
            }
            if ($cpage <= 1) {
                $upnum = 0;
            } else {
                $upnum = ($cpage - 1) * $pageCount;
            }
            $limit = " LIMIT " . $upnum . "," . $pageCount;
        }
        $sql = "select orderNum,createDate,customerName,remark,discountTotalPrice as totalPrice
          ,(select count(*) from app_jewel_stone_order_detail where a.id=jewel_stone_order_id) as number
          from app_jewel_stone_order a where orderStatus<=".StoneCls::$STONE_ORDER_WAIT_PAY.$limit;
        $Model = new \Think\Model();
        $data = $Model->query($sql);
        $count = M("jewel_stone_order")->where("orderStatus<=".StoneCls::$STONE_ORDER_WAIT_PAY)->count();
        echo myResponse::ResponseDataTrueDataString(array("list"=>$data,"list_count"=>$count));*/
        $cpage = I("get.cpage");
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $obj = StoneCls::GetStoneOrderListAndCount(0,StoneCls::$STONE_ORDER_WAIT_PAY,$cpage,$memberId);
        echo myResponse::ResponseDataTrueDataString($obj);
    }
    public function stoneAlreadyPayOrderList()//已付款
    {
        $cpage = I("get.cpage");
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $obj = StoneCls::GetStoneOrderListAndCount(StoneCls::$STONE_ORDER_WAIT_PAY,StoneCls::$STONE_ORDER_ALREADY_PAY,$cpage,$memberId);
        echo myResponse::ResponseDataTrueDataString($obj);
    }
    public function stoneAlreadyDeliverGoodsOrderList()//已发货
    {
        $cpage = I("get.cpage");
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $obj = StoneCls::GetStoneOrderListAndCount(StoneCls::$STONE_ORDER_ALREADY_PAY,StoneCls::$STONE_ORDER_ALREADY_DELIVER_GOODS,$cpage,$memberId);
        echo myResponse::ResponseDataTrueDataString($obj);
    }
    public function stoneAlreadyFinishOrderList()//已发货
    {
        $cpage = I("get.cpage");
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $obj = StoneCls::GetStoneOrderListAndCount(StoneCls::$STONE_ORDER_ALREADY_DELIVER_GOODS,StoneCls::$STONE_ORDER_ALREADY_FINISH,$cpage,$memberId);
        echo myResponse::ResponseDataTrueDataString($obj);
    }
}
trait stoneOrderDo
{
    public function stoneSubmitOrderDo()
    {
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        $list = I("get.id");
        $ItemArr = explode('|',$list);
        $ids = "";

        $addressId = I("get.addressId");
        $invoiceTitle = I("get.invoiceTitle");
        $invoiceType = I("get.invoiceType");
        $erp_client_id = UserCls::GetOrderErpId($myKey);
        $customerId = I("get.customerId");
        $percent = I("get.percent");
        $remark = I("get.remark");
        $address = "";
        if($addressId == 0)
        {
            $address = myResponse::ResponseDataTrueDataObj(UserCls::$PickDefaultAddress);
        }
        else {
            if (FunctionCode::isInteger($addressId) && intval($addressId) > 0) {
                $reObj = UserCls::getAddressInfoByMemberId($memberId, $addressId);
                $address = $reObj->error != ValidateCode::$noError ? null : $reObj;
            }
            if (empty($address->data)) {
                echo myResponse::ResponseDataFalseString("没有选择地址");
                return;
            }
        }

        $idNumArr = array();
        foreach($ItemArr as $item)
        {
            $itemObj = explode(',',$item);
            if(count($itemObj) == 2)
            {
                $id = $itemObj[0];
                $number = $itemObj[1];
                if(FunctionCode::isInteger($id) && FunctionCode::isInteger($number)
                    && intval($id)>0 && intval($number)>0)
                {
                    $idNumArr[] = array("id"=>$id,"number"=>$number);
                    $ids = empty($ids)?$id:$ids.",".$id;
                }

            }
        }
        if(count($idNumArr)<=0)
        {
            echo myResponse::ResponseDataFalseString("没有选择任何石头");
            return;
        }

        $customerName = "";
        $cdata = array();
        //M("jewel_stone_product")->field("*")->where("id in ($ids)")->select();
        if(!empty($customerId))
        {
            $cdata = ErpPublicCode::GetUrlObjData("stoneSubmitOrderDoPage", "stone", array("customerId" => $customerId,"ids"=>$ids));
            if(empty($cdata->customer)) {
                echo myResponse::ResponseDataFalseString("选择客户无效");
                return;
            }
            $customerName = $cdata->customer->customerName;
        }
        else{
            echo myResponse::ResponseDataFalseString("没有选择客户");
            return;
        }
        $stoneList = $cdata->stoneList;
        $sn = BllPublic::makePaySn($memberId);
        $totalPrice = 0;
        if(count($stoneList)<=0)
        {
            echo myResponse::ResponseDataFalseString("没有选择任何石头");
            return;
        }
        $arrData = array();
        foreach($stoneList as $item)
        {
            /*$number = FunctionCode::FindEqArrReField($idNumArr,"id","number",$item["id"]);
            $discountPrice = sprintf("%.2f",floatval($item['Price'])*(1 + $percent)/100);
            $arrData[] = array("orderNum"=>$sn,"Erpid"=>$item['Erpid'],"CertAuth"=>$item['CertAuth']
                    ,"member_Id"=>$memberId
                    ,"CertCode"=>$item['CertCode'],"Weight"=>$item['Weight'],"Shape"=>$item['Shape']
                    ,"Color"=>$item['Color'],"Purity"=>$item['Purity'],"Cut"=>$item['Cut']
                    ,"Polishing"=>$item['Polishing'],"symmetric"=>$item['symmetric'],"Fluorescence"=>$item['Fluorescence']
                    ,"Price"=>$item['Price'],"discountPrice"=>$discountPrice,"orderStatus"=>StoneCls::$STONE_ORDER_WAIT_PAY
                    ,"number"=>$number);
            $totalPrice = $totalPrice + floatval($item['Price'])*intval($number);*/
            $number = FunctionCode::FindEqArrReField($idNumArr,"id","number",$item->id);
            $discountPrice = sprintf("%.2f",BllPublic::StonePriceAddPercent($item->Price,$percent));
            $arrData[] = array("orderNum"=>$sn,"Erpid"=>$item->id,"CertAuth"=>$item->CertAuth
            ,"member_Id"=>$memberId
            ,"CertCode"=>$item->CertCode,"Weight"=>$item->Weight,"Shape"=>$item->Shape
            ,"Color"=>$item->Color,"Purity"=>$item->Purity,"Cut"=>$item->Cut
            ,"Polishing"=>$item->Polishing,"symmetric"=>$item->symmetric,"Fluorescence"=>$item->Fluorescence
            ,"Price"=>$item->Price,"discountPrice"=>$discountPrice,"orderStatus"=>StoneCls::$STONE_ORDER_WAIT_PAY
            ,"number"=>$number,"BarCode"=>$item->Barcode
            ,"Source"=>$item->Source,"StoreName"=>$item->StoreName);
            $totalPrice = $totalPrice + floatval($item->Price)*intval($number);
        }
        $orderData = array("orderNum"=>$sn,"phone"=>$address->data['phone'],"address"=>$address->data['addr']
                            ,"postName"=>$address->data['name']
                            ,"member_Id"=>$memberId
                            ,"invoiceTitle"=>$invoiceTitle
                            ,"invoiceType"=>FunctionCode::FindEqArrReField(StoneCls::$INVOICE_TYPE,"id","title",$invoiceType)
                            ,"erp_client_id"=>$erp_client_id,"customerId"=>$customerId,"remark"=>$remark
                            ,"customerName"=>$customerName,"percent"=>floatval($percent),"orderStatus"=>StoneCls::$STONE_ORDER_WAIT_PAY
                            ,"totalPrice"=>$totalPrice,"discountTotalPrice"=>sprintf("%.2f",BllPublic::StonePriceAddPercent($totalPrice,$percent)));
        $re = M("jewel_stone_order")->add($orderData);
        if(!$re)
        {
            echo myResponse::ResponseDataFalseString("下单失败");
            return;
        }
        M("jewel_stone_order_detail")->addAll($arrData);
        M("jewel_stone_order_detail")->where(array("orderNum"=>$sn))->save(array("jewel_stone_order_id"=>$re));
        echo myResponse::ResponseDataTrueDataString(array("orderId"=>$re));
    }
    public function stoneCancelOrderDo()
    {
        $orderId = I("get.orderId");
        $myKey = UserCls::GetRequestTokenKey();
        $memberId = UserCls::GetUserId($myKey);
        if(intval($orderId)>0 && intval($memberId)>0)
        {
            $newDate = FunctionCode::GetNowTimeDate();
            $data["orderStatus"] = -1;
            $data["updateDate"] = $newDate;
            $jewel_stone_order = M("jewel_stone_order");
            $jewel_stone_order->startTrans();
            $re = $jewel_stone_order->where("member_Id=$memberId and id=$orderId and orderStatus>0 and orderStatus<="
                .StoneCls::$STONE_ORDER_WAIT_PAY)->save($data);
            if($re)
            {
                $re = M("jewel_stone_order_detail")->where("member_Id=$memberId and jewel_stone_order_id=$orderId")->save($data);
                if($re) {
                    $jewel_stone_order->commit();
                    echo myResponse::ResponseDataTrueString("取消成功");
                    return;
                }
                else {
                    $jewel_stone_order->rollback();
                }
            }
        }
        echo myResponse::ResponseDataFalseString("取消失败");
    }
}