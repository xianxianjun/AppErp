<?php
namespace Admin\Controller;
use Common\Common\PublicCode\FunctionCode;
use Think\Controller;
use Common\Common\PublicCode\PartCodeController;
use Common\Common\Api\flow\ModelOrderCls;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\PublicCode\ErpPublicCode;
trait OrderProduceListPage
{
//==================生产中
    public function producedingOrderBase($cpage,$keyword='')
    {
        $keywordStr = "";
        if(empty($keyword)) {
            $keyword = I("get.orderNum");
            if(!empty($keyword))
            {
                $keywordStr = "orderNum like '%".$keyword."%'";
            }
        }
        $allCount = M('model_main_order')->where($keywordStr)->count();

        $pagePercount = 20;
        $cpage = $cpage<=0?1:$cpage;
        $countfloat = $allCount/$pagePercount;
        if($countfloat < $cpage)
        {
            $cpage = ceil($countfloat);
        }

        $this->Listcpage = $cpage;
        $this->pageCount = ceil($countfloat);
        $this->reCount = $allCount;
        $this->pageQrt = "?cpage=".$cpage
            .(empty($keyword)?"":"&orderNum=".$keyword);
        $up = $pagePercount*($cpage-1)>0?$pagePercount*($cpage-1):0;
        $conSql = (empty($keywordStr)?"":" where ".$keywordStr)." ORDER BY id desc LIMIT ".$up.",".$pagePercount;
        $Model = new \Think\Model();
        $sql = "select a.*,b.userName,b.trueName
        from (select * from app_model_main_order ".$conSql.") a
         left join app_member b on a.member_id = b.id
         where
         orderStatus>".ModelOrderCls::$CAN_MODITY_ORDER_ITEM_ELT_ORDER_STATUS_1000."
         and orderStatus<=".ModelOrderCls::$ORDER_STATUS_WAIT_SEND_TO_CUSTOMER_2001;
        $orderdata = $Model->query($sql);
        $this->orderdata = $orderdata;
        $this->display(PartCodeController::DisplayPath("Api/Order/producedingOrder"));
    }
    public function producedingOrder()
    {
        $cpage = intval(I("get.cpage"));
        self::producedingOrderBase($cpage);
    }
    public function nextProducedingOrder()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage + 1;
        self::producedingOrderBase($cpage);
    }
    public function perProducedingOrder()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage - 1;
        self::producedingOrderBase($cpage);
    }
    public function lastProducedingOrder()
    {
        self::producedingOrderBase(1000000);
    }
    public function firstProducedingOrder()
    {
        self::producedingOrderBase(1);
    }
    //==================生产中
    public function producedingOrderDetail()
    {
        $orderNum = I("get.orderNum");
        $orderId = I("get.id");
        $appmid = I("get.memberId");
        $torderInfo = M("model_main_order")->where("id=".$orderId)->select();
        $data = ErpPublicCode::GetUrlObjData("ModelOrderProduceDetailPage","model",array("orderNum"=>$orderNum,"appmid"=>$appmid));
        $modelstr = "";
        foreach($data->modelList as &$item)
        {
            $txt = (empty($item->TypeName)?"":"类型:$item->TypeName;")
            .(empty($item->Perimeter)?"":"手寸:$item->Perimeter;")
            .(empty($item->QuantityDetail)?"":"订购数量:$item->Perimeter;")
            .(empty($item->IsCSP)?"":"自带主石;")
            .(empty($item->StoneP)?"":"主石类别名称:$item->StoneP;")
            .(empty($item->StonePSpecs)?"":"主石规格:$item->StonePSpecs;")
            .(empty($item->StonePQuantity)?"":"主石数量:$item->StonePQuantity;")
            .(empty($item->StonePFigure)?"":"主石形状:$item->StonePFigure;")
            .(empty($item->StoneSA)?"":"副石A类型名称:$item->StoneSA;")
            .(empty($item->StoneSASpecs)?"":"副石A规格:$item->StoneSASpecs;")
            .(empty($item->StoneSAQuantity)?"":"副石A数量:$item->StoneSAQuantity;")
            .(empty($item->StoneSAFigure)?"":"副石A形状:$item->StoneSAFigure;")

            .(empty($item->StoneSB)?"":"副石B类型名称:$item->StoneSB;")
            .(empty($item->StoneSBSpecs)?"":"副石B规格:$item->StoneSBSpecs;")
            .(empty($item->StoneSBQuantity)?"":"副石B数量:$item->StoneSBQuantity;")
            .(empty($item->StoneSBFigure)?"":"副石B形状:$item->StoneSBFigure;")

            .(empty($item->StoneSC)?"":"副石C类型名称:$item->StoneSC;")
            .(empty($item->StoneSCSpecs)?"":"副石C规格:$item->StoneSCSpecs;")
            .(empty($item->StoneSCQuantity)?"":"副石C数量:$item->StoneSCQuantity;")
            .(empty($item->StoneSCFigure)?"":"副石C形状:$item->StoneSCFigure;")
            ;
            $modelstr = empty($modelstr)?"'".$item->ModuleID."'":"'".$item->ModuleID."',".$modelstr;
            $item->info = $txt;
        }
        $this->oModellist = array();
        if(!empty($modelstr)) {
            $this->oModellist = M("model_product")->field("modelNum,pic")->where("modelNum in (" . $modelstr . ")")->select();
        }

        $data->orderInfo->postAddress = $torderInfo[0]["member_address_name"]
                    ." ".$torderInfo[0]["member_address_phone"]
                    ." ".$torderInfo[0]["member_address"];
        $OrderStatusArr = BaseCls::CacheModelOrderStatus();
        $data->orderInfo->OrderStatusTitle = FunctionCode::FindEqObjReField($OrderStatusArr, "statusKey", "statusTitle", $data->orderInfo->OrderStatus);
        $this->orderInfo = $data->orderInfo;
        $this->modelList = $data->modelList;
        //echo myResponse::ResponseDataTrueDataString($data);
        $this->display(PartCodeController::DisplayPath("Api/Order/producedingOrderDetail"));
    }
}