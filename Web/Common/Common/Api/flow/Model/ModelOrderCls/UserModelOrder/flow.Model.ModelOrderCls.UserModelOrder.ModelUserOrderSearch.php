<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/3/16
 * Time: 14:26
 */
namespace Common\Common\Api\flow;

use Common\Common\Api\Code\myResponse;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ConvertType;
use Common\Common\PublicCode\WithSql;
use Common\Common\PublicCode\ErpPublicCode;
use Common\Common\Api\flow\BaseCls;
use Common\Common\Api\flow\ModelCls;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\Code\ValidateCode;
use Common\Common\PublicCode\isHaveCustomer;

trait ModelUserOrderSearch
{
    public static function ModelUserOrderSearchPage()
    {
        $searchKeyword =
            array(
                array("id"=>1,"title"=>"订单号"),
                array("id"=>2,"title"=>"款号")
            );
        $searchScope =
            array(
                array("id"=>1,"title"=>"我的订单"),
                array("id"=>2,"title"=>"所有订单")
            );
        $searchDateScope =
            array(
                array("title"=>"今天"
                        ,"sdate"=>FunctionCode::GetNowDate()
                        ,"edate"=>FunctionCode::GetNowDate()
                        ,"isDefault"=>1),
                array("title"=>"昨天"
                        ,"sdate"=>date('Y-m-d',strtotime('-1 day'))
                        ,"edate"=>date('Y-m-d',strtotime('-1 day'))
                        ,"isDefault"=>0),
                array("title"=>"最近三天"
                        ,"sdate"=>date('Y-m-d',strtotime('-3 day'))
                        ,"edate"=>FunctionCode::GetNowDate()
                        ,"isDefault"=>0),
                array("title"=>"最近一个月"
                        ,"sdate"=>date('Y-m-d',strtotime('-1 month'))
                        ,"edate"=>FunctionCode::GetNowDate()
                        ,"isDefault"=>0)
            );
        $endDate = FunctionCode::GetNowDate();
        $startDate = FunctionCode::GetNowDate();
        foreach($searchDateScope as $value)
        {
            if($value["isDefault"] == 1)
            {
                $endDate = $value["sdate"];
                $startDate = $value["edate"];
            }
        }

        return array("searchKeyword"=>$searchKeyword,"searchScope"=>$searchScope
                    ,"searchDateScope"=>$searchDateScope
                    ,"startDate"=>$startDate
                    ,"endDate"=>$endDate);
    }
}