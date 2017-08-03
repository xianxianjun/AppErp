<?php
namespace Api\Controller;

use Common\Common\Api\Code\myResponse;
use Common\Common\Api\Code\myValidate;
use Common\Common\Api\flow\UserCls;
use Common\Common\Api\flow\PaymentCls;
use Common\Common\Api\flow\UpdateCls;
use Common\Common\PublicCode\FunctionCode;
use Common\Common\PublicCode\ExpandTp;
use Common\Common\Api\flow\JpdiamCls;
use Common\Common\Api\flow\StoneCls;
use Common\Common\PublicCode\ErpPublicCode;

trait UpdateJpdiamData
{
    public function UpdateJpdiamStoneData1()
    {
        $bathNum = S('UpdateJpdiamStoneDatabathNum');
        if(!empty($bathNum)) {
            $sql = "UPDATE
                    app_jpdiam_white_stone_data
                    SET isDelete = 1
                WHERE
                    stoneid NOT IN (
                        SELECT
                            stoneid
                        FROM
                            app_jpdiam_white_stone_data_temp
                            where batchNum = $bathNum
                    );
                UPDATE
                    app_jpdiam_white_stone_data
                    SET isDelete = 0
                WHERE
                    stoneid IN (
                        SELECT
                            stoneid
                        FROM
                            app_jpdiam_white_stone_data_temp
                            where batchNum = $bathNum
                    );
                ";
            $sql1 = "UPDATE app_jpdiam_white_stone_data a,
                 app_jpdiam_white_stone_data_temp b
                SET a.shape = b.shape,
                 a.carat = b.carat,
                 a.color = b.color,
                 a.clarity = b.clarity,
                 a.cut = b.cut,
                 a.polish = b.polish,
                 a.symmetry = b.symmetry,
                 a.fluorescence = b.fluorescence,
                 a.milky = b.milky,
                 a.colsh = b.colsh,
                 a.green = b.green,
                 a.black = b.black,
                 a.eyeclean = b.eyeclean,
                 a.report = b.report,
                 a.reportno = b.reportno,
                 a.address = b.address,
                 a.prerecivetime = b.prerecivetime,
                 a.depth_scale = b.depth_scale,
                 a.table_scale = b.table_scale,
                 a.measurement = b.measurement,
                 a.rapprice = b.rapprice,
                 a.saleback = b.saleback,
                 a.saledollorprice = b.saledollorprice
                WHERE
                    a.stoneid = b.stoneid and b.batchNum = $bathNum;";
            $sql2 = "INSERT INTO app_jpdiam_white_stone_data (
                    stoneid,
                    shape,
                    carat,
                    color,
                    clarity,
                    cut,
                    polish,
                    symmetry,
                    fluorescence,
                    milky,
                    colsh,
                    green,
                    black,
                    eyeclean,
                    report,
                    reportno,
                    address,
                    prerecivetime,
                    depth_scale,
                    table_scale,
                    measurement,
                    rapprice,
                    saleback,
                    saledollorprice,
                    batchNum
                ) SELECT
                    *
                FROM
                    (
                        SELECT
                            stoneid,
                            shape,
                            carat,
                            color,
                            clarity,
                            cut,
                            polish,
                            symmetry,
                            fluorescence,
                            milky,
                            colsh,
                            green,
                            black,
                            eyeclean,
                            report,
                            reportno,
                            address,
                            prerecivetime,
                            depth_scale,
                            table_scale,
                            measurement,
                            rapprice,
                            saleback,
                            saledollorprice,
                            batchNum
                        FROM
                            app_jpdiam_white_stone_data_temp
                        WHERE
                            batchNum = $bathNum
                            and stoneid NOT IN (
                                SELECT
                                    stoneid
                                FROM
                                    app_jpdiam_white_stone_data
                            )
                    ) AS tb";
            $Model = new \Think\Model();
            $re = $Model->execute($sql);
            $re = $Model->execute($sql1);
            $re = $Model->execute($sql2);
            echo myResponse::ResponseDataTrueString("更新成功",$bathNum);
            S('UpdateJpdiamStoneDatabathNum',null);
        }
        else {
            echo myResponse::ResponseDataFalseString("更新失败", '');
        }
    }
    public function UpdateJpdiamStoneData()
    {
        $wobj = JpdiamCls::GetWhiteStone();
        $wrows = $wobj->rows;
        if (!empty($wrows) && count($wrows) > 0) {
            $Model = new \Think\Model();
            $Model->execute("TRUNCATE app_jpdiam_white_stone_data_temp");
            $bathNum = FunctionCode::getTimeId();
            S('UpdateJpdiamStoneDatabathNum',$bathNum);
            $fieldStr = "";
            $updateStr = "";
            foreach ($wrows[0] as $key => $value) {
                $fieldStr = FunctionCode::ConnectStrForComm($fieldStr, $key, ",");
                $updateStr = FunctionCode::ConnectStrForComm($updateStr, "a." . $key . "=b." . $key, ",");
            }
            $fieldArr = explode(",", $fieldStr);
            if (count($fieldArr) > 0) {
                $wrowsArr = FunctionCode::ArrObjToFieldsArr($wrows, $fieldArr, array("batchNum" => $bathNum));
                M("jpdiam_white_stone_data_temp")->addAll($wrowsArr);
            }
        }
        echo myResponse::ResponseDataTrueString("获取数据成功",'');
    }
    public function ToErpUpdateJpdiamStoneData()
    {
        $cpage = intval(I("cpage"));
        $cpage = $cpage == 0?1:$cpage;
        $sql = StoneCls::GetUpdatejpdianStone($cpage,2000);
        $Model = new \Think\Model();
        $list = $Model->query($sql);
        $paraArr = array("start"=>0);
        if($cpage <= 1)
        {
            $paraArr = array("start"=>1);
        }
        //$data = myResponse::ResponseDataTrueDataObj(array("jpdiamStonelist"=>$list));
        $obj = ErpPublicCode::PostUrlForObj(array("jpdiamStonelist"=>$list), "AddJpdiamStone", "update", "GetPostEntityDataEntity", "entity",$paraArr,false);
        if($obj->error == 1) {
            echo myResponse::ResponseDataFalseString('更新JPHK石头第' . $cpage . '出错', count($list));
        }
        else
        {
            echo myResponse::ResponseDataTrueString('更新JPHK石头第' . $cpage . '成功', count($list));
        }
        //echo $str;
    }
}