<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/10/21
 * Time: 11:17
 */
namespace Api\Behaviors;

use Common\Common\Api\flow\UserCls;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\FunctionCode;

class PurviewBehavior extends \Think\Behavior
{
    private static $AnyUserActionArr = array("userAdminPage"=>1,"userMessagePageList"=>1
    ,"userModifyPasswordDo"=>1,"userModifyHeadPicDo"=>1,"addUserAddressDo"=>1,"modifyAddressDo"=>1,"deleteAddressDo"=>1,"setDefaultAddressDo"=>1
    ,"userModifyAddressPage"=>1,"userAddAddressPage"=>1,"AddressListPage"=>1,"userModifyPage"=>1);
    private static $saleActionArr = array();
    private static $orderErpUserActionArr = array("modelListPage" => 1, "getCity" => 1, "getArea" => 1, "IsHaveCustomer" => 1
    , "GetCustomerById" => 1, "GetCustomerList" => 1, "modelFilerPage" => 1, "ModelDetailPage" => 1, "getStonePrice" => 1
    ,"OrderDoCurrentModelItemDo"=>1,"OrderCurrentDoModelItemDo"=>1
    ,"OrderCurrentDoModelItemForDefaultDo"=>1,"OrderCurrentEditModelItemForDefaultDo"=>1,"ModelOrderWaitCheckOrderCurrentEditModelItemForDefaultDo"=>1
    ,"OrderCurrentDeleteModelItemDo"=>1,"OrderCurrentEditModelItemDo"=>1
    ,"OrderCurrentSubmitDo"=>1,"OrderListPage"=>1,"GetOrderPricePageList"=>1,"ModelDetailPageForCurrentOrderEditPage"=>1
    ,"PaymentCurrentOrderPage"=>1,"PayMentCurrentOrderDo"=>1
    ,"ModelOrderWaitCheckList"=>1,"ModelOrderWaitCheckDetail"=>1,"ModelOrderWaitCheckDetailDeleteModelItemDo"=>1
    ,"ModelOrderWaitCheckModelDetailPageForCurrentOrderEditPage"=>1,"ModelOrderWaitCheckDetailModifyAddress"=>1
    ,"ModelOrderWaitCheckDetailModifyInfoDo"=>1,"ModelOrderWaitCheckOrderCurrentEditModelItemDo"=>1,"ModelOrderWaitCheckCancelDo"=>1
    ,"ModelOrderWaitCheckModifyGetOrderPricePageListDo"=>1
    ,"ModelOrderWaitCheckItem"=>1,"ModelOrderWaitCheckDetailModifyAddressDo"=>1
    ,"ModelOrderWaitCheckVerifyToProduceDo"=>1,"ModelOrderProduceListPage"=>1,"ModelOrderWaitCheckVerifyToProducesDo"=>1
    ,"ModelOrderProduceDetailPage"=>1,"ModelOrderProduceDetailHistoryPage"=>1
    ,"GetUserModifyPasswordVerifyCodeDo"=>1,"AddressListPageForSelect"=>1
    ,"ModelInvoicePage"=>1,"ModelOrderProduceDetailShowRateProgressPage"=>1
    ,"CheckSpecificationsForm"=>1,"ModelBillList"=>1,"ModelFinishBillList"=>1
    ,"ModelBillFinishDetailRec"=>1,"ModelArriveBillMo"=>1
    ,"ModelUserOrderSearchPage"=>1,"ModelOrderSearch"=>1,"ModelOrderSearchDetail"=>1
    ,"ModelBillFinishDetailRecForSearch"=>1,"ModelArriveBillMoForSearch"=>1,"ModelOrderProduceDetailHistoryPageForSearch"=>1
    ,"ModelOrderProduceDetailShowRateProgressPageForSearch"=>1,"UpdateIsShowPrice"=>1
    ,"stoneSearchInfo"=>1,"stoneSearchDo"=>1,"stoneList"=>1,"stoneListForJphk"=>1,"stoneOffer"=>1
    ,"GetAilpayPayStr"=>1,"GetAilpayModelOrderPayStr"=>1,"GetAilpayStoneOrderPayStr"=>1
    ,"GetWxpayModelParameter"=>1
    ,"stoneOrderListPage"=>1,"stoneSubmitOrderDo"=>1,"StoneInvoicePage"=>1,"stoneCancelOrderDo"=>1
    ,"stoneWaitPayOrderList"=>1,"stoneAlreadyPayOrderList"=>1,"PaymentCurrentOrderStonePage"=>1
    ,"stoneAlreadyDeliverGoodsOrderList"=>1,"stoneAlreadyFinishOrderList"=>1
    ,"stoneOrderDetailpage"=>1
    ,"getUserStoneAddtion"=>1,"getUserModelAddtion"=>1,"modifyUserStoneAddtionDo"=>1,"modifyUserModelAddtionDo"=>1,"modifyUserIsShowOriginalPriceDo"=>1);
    //行为执行入口
    public function run(&$param)
    {
        //echo CONTROLLER_NAME."/".ACTION_NAME;
        $action = ACTION_NAME;
        if (self::$orderErpUserActionArr[$action] == 1) {
            $myKey = UserCls::GetRequestTokenKey();
            if (!UserCls::IsOrderErpUserLogin($myKey)) {
                echo myResponse::ResponseDataNoLoginString();
                exit;
            }
        } else if (self::$saleActionArr[$action] == 1) {
            $myKey = UserCls::GetRequestTokenKey();
            if (!UserCls::IsSalesUserLogin($myKey)) {
                echo myResponse::ResponseDataNoLoginString();
                exit;
            }
        } else if (self::$AnyUserActionArr[$action] == 1) {
            $myKey = UserCls::GetRequestTokenKey();
            if (!UserCls::IsAnyUserLogin($myKey)) {
                echo myResponse::ResponseDataNoLoginString();
                exit;
            }
        }
        if(C("isTest") == 1) {
            file_put_contents(BASE_PATH . "/log/urllog".date('Ymd', time()).".txt", "\r\n" . FunctionCode::get_url() . "\r\n", FILE_APPEND);
        }
    }
}
