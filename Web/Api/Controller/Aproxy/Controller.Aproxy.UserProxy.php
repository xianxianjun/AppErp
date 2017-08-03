<?php
/**
 * Created by PhpStorm.
 * User: qianxi
 * Date: 2016/9/22
 * Time: 10:12
 */
namespace Api\Controller;

trait userDoProxy {
    public function userRegisterDo()
    {
        UserController::userRegisterDo();
    }
    public function userLoginDo()
    {
        UserController::userLoginDo();
    }
    public function userModifyPasswordDo()
    {
        UserController::userModifyPasswordDo();
    }
    public function userForgetPasswordDo()
    {
        UserController::userForgetPasswordDo();
    }
    public function userModifyHeadPicDo()
    {
        UserController::userModifyHeadPicDo();
    }
    public function addUserAddressDo()
    {
        UserController::addUserAddressDo();
    }
    public function modifyAddressDo()
    {
        UserController::modifyAddressDo();
    }
    public function deleteAddressDo()
    {
        UserController::deleteAddressDo();
    }
    public function setDefaultAddressDo()
    {
        UserController::setDefaultAddressDo();
    }
    public function GetLoginVerifyCodeDo()
    {
        UserController::GetLoginVerifyCodeDo();
    }
    public function GetRegisterVerifyCodeDo()
    {
        UserController::GetRegisterVerifyCodeDo();
    }
    public function GetUserModifyPasswordVerifyCodeDo()
    {
        UserController::GetUserModifyPasswordVerifyCodeDo();
    }
    public function GetForgetPasswordVerifyCodeDo()
    {
        UserController::GetForgetPasswordVerifyCodeDo();
    }
}
trait userPageProxy {
    public function userAdminPage()
    {
        UserController::userAdminPage();
    }
    public function userMessagePageList()
    {
        UserController::userMessagePageList();
    }
    public function userModifyAddressPage()
    {
        UserController::userModifyAddressPage();
    }
    public function userAddAddressPage()
    {
        UserController::userAddAddressPage();
    }
    public function AddressListPage()
    {
        UserController::AddressListPage();
    }
    public function AddressListPageForSelect()
    {
        UserController::AddressListPageForSelect();
    }
    public function UserShowVerifyCode()
    {
        UserController::UserShowVerifyCode();
    }
    public function userModifyPage()
    {
        UserController::userModifyPage();
    }
    public function UpdateIsShowPrice()
    {
        UserController::UpdateIsShowPrice();
    }
    public function modifyUserStoneAddtionDo()
    {
        UserController::modifyUserStoneAddtionDo();
    }
    public function modifyUserModelAddtionDo()
    {
        UserController::modifyUserModelAddtionDo();
    }
    public function modifyUserIsShowOriginalPriceDo()
    {
        UserController::modifyUserIsShowOriginalPriceDo();
    }
}