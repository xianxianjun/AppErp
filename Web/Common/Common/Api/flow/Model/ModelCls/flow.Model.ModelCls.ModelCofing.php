<?php
namespace Common\Common\Api\flow;
trait ModelCofing
{
    public static $priceCategoryArr = array("id"=>"200001","title"=>"价格（不带主石）","sign"=>"price","sort"=>500,"mulSelect"=>0,"groupKey"=>"price","attributeList"=>array(
        array("id"=>"1000013","title"=>"0-2000","value"=>"0|2000","groupKey"=>"price","isSelect"=>"0"),
        array("id"=>"1000014","title"=>"2000-4000","value"=>"2000|4000","groupKey"=>"price","isSelect"=>"0"),
        array("id"=>"1000015","title"=>"4000-8000","value"=>"4000|8000","groupKey"=>"price","isSelect"=>"0"),
        array("id"=>"1000016","title"=>"8000以上","value"=>"8000|","groupKey"=>"price","isSelect"=>"0")
    ));
    public static $weightCategoryArr = array("id"=>"200002","title"=>"主石","sign"=>"mainstone","sort"=>400,"mulSelect"=>0,"groupKey"=>"weight","attributeList"=>array(
        array("id"=>"1000000","title"=>"0-0.03","value"=>"0|0.03","groupKey"=>"weight","isSelect"=>"0"),
        array("id"=>"1000001","title"=>"0.03-0.07","value"=>"0.03|0.07","groupKey"=>"weight","isSelect"=>"0"),
        array("id"=>"1000002","title"=>"0.08-0.12","value"=>"0.08|0.12","groupKey"=>"weight","isSelect"=>"0"),
        array("id"=>"1000003","title"=>"0.13-0.17","value"=>"0.13|0.17","groupKey"=>"weight","isSelect"=>"0"),
        array("id"=>"1000004","title"=>"0.18-0.22","value"=>"0.18|0.22","groupKey"=>"weight","isSelect"=>"0"),
        array("id"=>"1000005","title"=>"0.25","value"=>"0.25","groupKey"=>"weight","isSelect"=>"0"),
        array("id"=>"1000006","title"=>"0.3","value"=>"0.3","groupKey"=>"weight","isSelect"=>"0"),
        array("id"=>"1000007","title"=>"0.4","value"=>"0.4","groupKey"=>"weight","isSelect"=>"0"),
        array("id"=>"1000008","title"=>"0.5","value"=>"0.5","groupKey"=>"weight","isSelect"=>"0"),
        array("id"=>"1000009","title"=>"0.6","value"=>"0.6","groupKey"=>"weight","isSelect"=>"0"),
        array("id"=>"1000010","title"=>"0.7","value"=>"0.7","groupKey"=>"weight","isSelect"=>"0"),
        array("id"=>"1000011","title"=>"0.8","value"=>"0.8","groupKey"=>"weight","isSelect"=>"0"),
        array("id"=>"1000012","title"=>"0.9","value"=>"0.9","groupKey"=>"weight","isSelect"=>"0")
    ));
    public static $testArr = array("id"=>"200003","title"=>"测试分类","sign"=>"test","sort"=>1000,"mulSelect"=>1,"groupKey"=>"test","attributeList"=>array(
        array("id"=>"9000000","title"=>"test1","value"=>"test1","groupKey"=>"test","isSelect"=>"0"),
        array("id"=>"9000001","title"=>"test2","value"=>"test2","groupKey"=>"test","isSelect"=>"0"),
        array("id"=>"9000002","title"=>"test3","value"=>"test3","groupKey"=>"test","isSelect"=>"0"),
        array("id"=>"9000003","title"=>"test4","value"=>"test4","groupKey"=>"test","isSelect"=>"0"),
        array("id"=>"9000004","title"=>"test5","value"=>"test5","groupKey"=>"test","isSelect"=>"0"),
        array("id"=>"9000005","title"=>"test6","value"=>"test6","groupKey"=>"test","isSelect"=>"0"),
        array("id"=>"9000006","title"=>"test7","value"=>"test7","groupKey"=>"test","isSelect"=>"0"),
        array("id"=>"9000007","title"=>"test8","value"=>"test8","groupKey"=>"test","isSelect"=>"0")
    ));
    public static $myKeywordArr = array("id"=>"200004","title"=>"热门","sign"=>"hot","sort"=>0,"mulSelect"=>1,"groupKey"=>"keyword1","attributeList"=>array(
        array("id"=>"2000001","title"=>"黄金","value"=>"黄金","groupKey"=>"keyword1","isSelect"=>"0"),
        array("id"=>"2000002","title"=>"白金","value"=>"白金","groupKey"=>"keyword1","isSelect"=>"0"),
        array("id"=>"2000003","title"=>"18k","value"=>"18k","groupKey"=>"keyword1","isSelect"=>"0"),
        array("id"=>"2000004","title"=>"24k","value"=>"24k","groupKey"=>"keyword1","isSelect"=>"0"),
        array("id"=>"2000005","title"=>"情侣对戒","value"=>"情侣","groupKey"=>"keyword1","isSelect"=>"0"),
        array("id"=>"2000006","title"=>"镶嵌钻石","value"=>"钻石","groupKey"=>"keyword1","isSelect"=>"0")
    ));
}