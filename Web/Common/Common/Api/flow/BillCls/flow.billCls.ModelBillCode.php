<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/5/26
 * Time: 9:02
 */
namespace Common\Common\Api\flow;

use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\WithSql;
use Common\Common\Api\flow\BaseCls;
use Common\Common\PublicCode\BllPublic;
use Common\Common\Api\flow\UpdateCls;
use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\ErpPublicCode;

trait ModelBillCode
{
    public static function ModelArriveBillMo($moNum,$memberId)
    {
        $DetailMo = ErpPublicCode::GetUrlObjData("ModelArriveBillMo", "Bill", array("moNum"=>$moNum,"appmid" => $memberId));
        return BillCls::ModelArriveBillMoData($DetailMo);
    }
    //出库单
    public static function ModelArriveBillMoData($DetailMo)
    {
        $modelList = array();
        if(count($DetailMo->modelList) >0)
        {
            $modelNumList = "";
            foreach($DetailMo->modelList as $item) {
                if(!empty($item->modelNum)) {
                    $modelNumList = empty($modelNumList) ? "'" . $item->modelNum . "'" : $modelNumList . ",'" . $item->modelNum . "'";
                }
            }
            if(!empty($modelNumList))
            {
                $picArr = M('model_product')->field("modelNum,pic")->where("modelNum in (".$modelNumList.")")->select();
            }
            foreach($DetailMo->modelList as $item) {
                $pic = "";
                if(!empty($picArr) && count($picArr)>0 && !empty($item->modelNum)) {
                    $pic = BllPublic::GetPicBasePathPic(FunctionCode::FindEqArrReField($picArr,"modelNum","pic",$item->modelNum));
                }
                $sInfo = "手寸:".$item->handSize."  毛重:".sprintf("%.2f", $item->roughWeight)." 净金重:".sprintf("%.2f", $item->mGoldWeight)." 损耗:".sprintf("%.2f", $item->mRatio);
                $stInfoArr = array();
                $n = 0;
                foreach($item->stonesList as $stItem)
                {
                    $stInfoItem = "";
                    if($n == 0)
                    {
                        $stInfoItem = "[主石]";
                        $n++;
                    }
                    else
                    {
                        $stInfoItem = $stInfoItem."[副石]";
                    }
                    $stInfoItem = $stInfoItem."石号:".$stItem->sn.";数量:".$stItem->number.";重量:".$stItem->weight.";金额:".sprintf("%.2f", $stItem->uprice);
                    $stInfoArr[] = $stInfoItem;
                }
                $dInfo = "基本费用:".sprintf('%.2f', $item->baseFee)." 附加费用:".sprintf('%.2f', $item->addFee)." 其他费用:".sprintf('%.2f', $item->otherFee)." 起版费:".sprintf('%.2f', $item->sampleFee);//." 单价成本:".sprintf('%.2f', $item->unitPrice)
                $remark = "备注:$item->memo";
                $modelList[] = array("modNum"=>$item->omdNum,"modelNum" => $item->modelNum
                , "typeName" => $item->typeName, "unitPrice" => $item->unitPrice,"pic"=>$pic, "sInfo" => $sInfo,"stInfo"=>$stInfoArr
                , "dInfo" => $dInfo,"remark"=>$remark);

            }
        }
        $uDetailMo = array("moItem"=>$DetailMo->moItem,"modelList"=>$modelList);
        return $uDetailMo;
    }
    //结算单

    public static function ModelBillFinishDetailRec($recNum,$memberId)
    {
        $DetailRec = ErpPublicCode::GetUrlObjData("ModelBillFinishDetailRec", "Bill", array("recNum"=>$recNum,"appmid" => $memberId));
        return BillCls::ModelBillFinishDetailRecHandle($DetailRec);
    }
    public static function ModelBillFinishDetailRecHandle($DetailRec)
    {
        //=================材料
        $recMaterialn =  count($DetailRec->recMaterial);
        $recMRatioSum = 0.0;
        $MoneySum = 0.0;
        if($recMaterialn>0)
        {
            foreach($DetailRec->recMaterial as $item)
            {
                $recMRatioSum = $recMRatioSum + floatval($item->recMRatio);
                $MoneySum = $MoneySum + floatval($item->RecMMoney);
            }
            $DetailRec->recMaterial[$recMaterialn] =  FunctionCode::CopyEmptyObj($DetailRec->recMaterial[0]);//损耗
            $DetailRec->recMaterial[$recMaterialn]->recMRatio = $recMRatioSum;
            $DetailRec->recMaterial[$recMaterialn]->typeName = "小计";
        }
        array_unshift($DetailRec->recMaterial,array("typeName"=>"品名","recMWeight"=>"净金重","recMRatio"=>"损耗"
        ,"RecGoldPrice"=>"金价","RecMMoney"=>"金额"));
        $DetailRec->recMaterials["list"] = $DetailRec->recMaterial;
        $DetailRec->recMaterials["moneySum"] = $MoneySum;
        $DetailRec->recMaterials["title"] = "材料";
        unset($DetailRec->recMaterial);
        //=============加工费
        $recProcessExpensesn = count($DetailRec->recProcessExpenses);
        $MoneySum = 0.0;
        if($recProcessExpensesn>0)
        {
            foreach($DetailRec->recProcessExpenses as $item)
            {
                $MoneySum = $MoneySum + floatval($item->recPMoney)+floatval($item->recPFeeAddTotal)+floatval($item->SampleFee);
            }
            $DetailRec->recProcessExpenses[$recProcessExpensesn] =  FunctionCode::CopyEmptyObj($DetailRec->recProcessExpenses[0]);
            $DetailRec->recProcessExpenses[$recProcessExpensesn]->typeName = "小计";
        }
        array_unshift($DetailRec->recProcessExpenses,array("typeName"=>"品名","recPQuantity"=>"件数","recPUPrice"=>"单件工费"
        ,"recPFeeAddTotal"=>"超额工费","sampleFee"=>"起版费","recPMoney"=>"金额"));
        $DetailRec->recProcessExpenseses["list"] = $DetailRec->recProcessExpenses;
        $DetailRec->recProcessExpenseses["moneySum"] = $MoneySum;
        $DetailRec->recProcessExpenseses["title"] = "加工费";
        unset($DetailRec->recProcessExpenses);

        //====================其他加工费
        $recOtherProcessExpensesn = count($DetailRec->recOtherProcessExpenses);
        $MoneySum = 0.0;
        if($recOtherProcessExpensesn>0)
        {
            foreach($DetailRec->recOtherProcessExpenses as $item)
            {
                $MoneySum = $MoneySum + floatval($item->recOMoney);
            }
            $DetailRec->recOtherProcessExpenses[$recOtherProcessExpensesn] =  FunctionCode::CopyEmptyObj($DetailRec->recOtherProcessExpenses[0]);
            $DetailRec->recOtherProcessExpenses[$recOtherProcessExpensesn]->enChase = "小计";
        }
        array_unshift($DetailRec->recOtherProcessExpenses,array("enChase"=>"品名","recOQuantity"=>"数量","recOUPrice"=>"工费"
        ,"recOMoney"=>"金额"));
        $DetailRec->recOtherProcessExpenseses["list"] = $DetailRec->recOtherProcessExpenses;
        $DetailRec->recOtherProcessExpenseses["moneySum"] = $MoneySum;
        $DetailRec->recOtherProcessExpenseses["title"] = "其他加工费";
        unset($DetailRec->recOtherProcessExpenses);

        //====================宝石
        $recStonen = count($DetailRec->recStone);
        $MoneySum = 0.0;
        if($recStonen>0)
        {
            foreach($DetailRec->recStone as $item)
            {
                $MoneySum = $MoneySum + floatval($item->recSMoney);
            }
            $DetailRec->recStone[$recStonen] =  FunctionCode::CopyEmptyObj($DetailRec->recStone[0]);
            $DetailRec->recStone[$recStonen]->stoneTypeName = "小计";
        }
        array_unshift($DetailRec->recStone,array("stoneTypeName"=>"石名","comeFrom"=>"来源","recSStoneSN"=>"编号"
        ,"recSQuantity"=>"数量","recSWeight"=>"重量","recSUPrice"=>"单价","recSMoney"=>"金额"));
        $DetailRec->recStones["list"] = $DetailRec->recStone;
        $DetailRec->recStones["moneySum"] = $MoneySum;
        $DetailRec->recStones["title"] = "宝石";
        unset($DetailRec->recStone);
        return $DetailRec;
    }
    //结算单列表
    public static function ModelFinishBillList($orderNum,$memberId)
    {
        $BillListData = ErpPublicCode::GetUrlObjData("ModelFinishBillList", "Bill", array("orderNum"=>$orderNum,"appmid" => $memberId));
        return $BillListData;
    }
    //订单单据列表
    public static function ModelBillList($memberId,$cpage)
    {
        $BillListData = ErpPublicCode::GetUrlObjData("ModelBillList", "Bill", array("cpage" => $cpage, "appmid" => $memberId));
        return $BillListData;
    }
}