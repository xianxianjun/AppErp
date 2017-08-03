<?php
namespace Common\Common\PublicCode;
trait BllPublicModelPrice
{
    public static function StonePriceAddPercent($price,$percent)
    {
        $perPrice = floatval($price) + floatval($price)*intval($percent)/100;
        return $perPrice;
    }
    public static function GetModelPrice($modelValuePrice,$type,$weight,$qualityId,$purityId,$lastPrice = 0.00)
    {
        if(!empty($type) && !empty($weight) && !empty($itemNumber)) {
            $n = FunctionCode::FindEqObjReN($modelValuePrice, "key", $qualityId . $purityId . $type);
            if ($n != -1) {
                return BllPublic::GetModelPriceUnit($modelValuePrice[$n],$weight,$lastPrice);
            }
        }
        return $lastPrice;
    }
    public static function GetModelPriceHaveStone($modelValuePrice,$type,$weight,$qualityId,$purityId,$StonePrice,$lastPrice = 0.00)
    {
        return BllPublic::GetModelPrice($modelValuePrice,$type,$weight,$qualityId,$purityId,$lastPrice)+$StonePrice;
    }
    public static function GetModelPriceUnit($modelValuePriceItem,$weight,$lastPrice = 0.00)
    {
        if (!empty($modelValuePriceItem)) {
            $lossCostPer = floatval($modelValuePriceItem[0]->lossCostPer) > 0 ? floatval($modelValuePriceItem[0]->lossCostPer) : 0.15;//损耗
            $goldPrice = floatval($modelValuePriceItem[0]->goldPrice) > 0 ? floatval($modelValuePriceItem[0]->goldPrice) : 0.00;//金重
            $processCost = floatval($modelValuePriceItem[0]->processCost) > 0 ? floatval($modelValuePriceItem[0]->processCost) : 0.00;
            $ProportionToWax = floatval($modelValuePriceItem[0]->ProportionToWax) > 0 ? floatval($modelValuePriceItem[0]->ProportionToWax) : 18;
            $amount = $weight*$ProportionToWax*$goldPrice*(1+$lossCostPer)+$processCost;
            return $amount;
        }
        return $lastPrice;
    }
    public static function GetModelPriceHaveStoneUnit($modelValuePriceItem,$weight,$StonePrice,$lastPrice = 0.00)
    {
        return BllPublic::GetModelPriceUnit($modelValuePriceItem,$weight,$lastPrice)+$StonePrice;
    }
}