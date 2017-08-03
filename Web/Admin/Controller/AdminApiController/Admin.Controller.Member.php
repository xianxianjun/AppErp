<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2017/1/6
 * Time: 15:39
 */
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
trait Member
{
    public function memberListBase($cpage)
    {
        $userName = I("get.userName");
        $trueName = I("get.trueName");
        $phone = I("get.phone");
        $keyword = "";
        $where = "";
        $whereky = "";
        $and = "";
        $pan = "";

        if(!empty($userName))
        {
            $keyword = $keyword.$pan."userName=$userName";
            $where = $where.$and."userName like '%$userName%'";
            $and = " and ";
            $pan = "&";
            $whereky = " where ";
        }
        if(!empty($trueName))
        {
            $keyword = $keyword.$pan."trueName=$trueName";
            $where = $where.$and."trueName like '%$trueName%'";
            $and = " and ";
            $pan = "&";
            $whereky = " where ";
        }
        if(!empty($phone))
        {
            $keyword = $keyword.$pan."phone=$phone";
            $where = $where.$and." phone like '%$phone%'";
            $and = " and ";
            $pan = "&";
            $whereky = " where ";
        }
        $Model = new \Think\Model();
        $sql = "select count(1) as con from app_member $whereky$where";
        $conData = $Model->query($sql)[0];
        $allCount = $conData["con"];
        $pagePercount = 18;
        $cpage = $cpage<=0?1:$cpage;
        $countfloat = $allCount/$pagePercount;
        if($countfloat < $cpage)
        {
            $cpage = ceil($countfloat);
        }
        $this->Listcpage = $cpage;
        $this->pageCount = ceil($countfloat);
        $this->reCount = $allCount;
        $this->pageQrt = "?cpage=".$cpage.$pan.$keyword;
        $up = $pagePercount*($cpage-1)>0?$pagePercount*($cpage-1):0;
        $limit = " LIMIT ".$up.",".$pagePercount;

        $sql = "select * from app_member $whereky$where order by id desc$limit";
        $this->memdata = $Model->query($sql);
        $this->display(PartCodeController::DisplayPath("Api/Member/memberList"));
    }
    public function memberList()
    {
        $cpage = intval(I("get.cpage"));
        self::memberListBase($cpage);
    }
    public function nextMemberList()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage + 1;
        self::memberListBase($cpage);
    }
    public function perMemberList()
    {
        $cpage = intval(I("get.cpage"));
        $cpage  = $cpage - 1;
        self::memberListBase($cpage);
    }
    public function lastMemberList()
    {
        self::memberListBase(1000000);
    }
    public function firstMemberList()
    {
        self::memberListBase(1);
    }
    //===========================
    public function updateIsCheckErpOrderDo()
    {
        $uid = I("get.uid");
        if(empty($uid))
        {
            echo myResponse::ResponseDataFalseString('更新失败');
            return;
        }
        $IsCheckErpOrder = M('member')->where(array("id"=>$uid))->getField("IsCheckErpOrder");
        if($IsCheckErpOrder == 1)
        {
            $IsCheckErpOrder = 0;
            $IsCheckErpOrderDate = FunctionCode::GetNowTimeDate();
        }
        else
        {
            $IsCheckErpOrder = 1;
            $IsCheckErpOrderDate = null;
        }
        $data["IsCheckErpOrder"] = $IsCheckErpOrder;
        $data["IsCheckErpOrderDate"] = $IsCheckErpOrderDate;
        $re = M('member')->where(array("id"=>$uid))->save($data);
        if(!$re)
        {
            echo myResponse::ResponseDataFalseString('更新失败');
            return;
        }
        echo myResponse::ResponseDataTrueString("更新成功");
    }
    public function updateUserInfoDo()
    {
        $uid = I("get.uid");
        $erpid = I("get.eid");
        $userName = I("get.un");
        $trueName = I("get.tn");
        $phone = I("get.p");
        if(empty($uid))
        {
            echo myResponse::ResponseDataFalseString('更新失败');
            return;
        }
        $data = array();
        if(!empty($erpid) || $erpid=='0')
        {
            $data["orderErpId"] = intval($erpid);
        }
        else
        {
            $data["orderErpId"] = NULL;
        }
        if(!empty($userName))
        {
            $data["userName"] = $userName;
        }
        if(!empty($trueName))
        {
            $data["trueName"] = $trueName;
        }
        if(!empty($phone))
        {
            $data["phone"] = $phone;
        }
        if(count($data)<=0)
        {
            echo myResponse::ResponseDataFalseString('更新失败');
            return;
        }
        $data["updateDate"] = FunctionCode::GetNowTimeDate();
        $re = M('member')->where(array("id"=>$uid))->save($data);
        if(!$re)
        {
            echo myResponse::ResponseDataFalseString('更新失败');
            return;
        }
        $data["id"] = $uid;
        $udata = M('member')->field("tokenKey")->where(array("id"=>$uid))->select();
        UserCls::ClearUserLogin($udata[0]["tokenKey"]);
        echo myResponse::ResponseDataTrueString("更新成功",$data);
    }
    public function addMember()
    {
        $this->title="会员添加";
        $this->display(PartCodeController::DisplayPath("Api/Member/memberAdd"));
    }
    public function modifyMember()
    {
        $this->title="会员编辑";
        $uid = I("get.uid");
        $this->userid = $uid;
        $this->data = M("member")->where("id=$uid")->select();
        $this->masterAccountData = M("member")->field("id,userName,trueName")->where("IsMasterAccount=1 and id<>$uid")->select();
        $this->modelAddtiono = UserCls::getUserModelAddtionPercentValue($uid,true);
        $this->stoneAddtiono = UserCls::getUserStoneAddtionPercentValue($uid,true);
        $this->display(PartCodeController::DisplayPath("Api/Member/memberEdit"));
    }
    public function modifyMemberInfoDo()
    {
        $uid = I("get.uid");
        $myMasterAccountMId = I("get.myMasterAccountMId");
        $modelAddtion = I("get.modelAddtion");
        $stoneAddtion = I("get.stoneAddtion");
        $IsMasterAccount = I("get.IsMasterAccount");
        $data["myMasterAccountMId"] = intval($myMasterAccountMId);
        $data["modelAddtion"] = floatval($modelAddtion);
        $data["stoneAddtion"] = floatval($stoneAddtion);
        $data["IsMasterAccount"] = floatval($IsMasterAccount);
        M("member")->where("id=$uid")->save($data);
        $modelAddtion = UserCls::getUserModelAddtionPercentValue($uid,true);
        $stoneAddtion = UserCls::getUserStoneAddtionPercentValue($uid,true);
        echo myResponse::ResponseDataTrueString("修改成功",array("modelAddtion"=>$modelAddtion,"stoneAddtion"=>$stoneAddtion));
    }
}