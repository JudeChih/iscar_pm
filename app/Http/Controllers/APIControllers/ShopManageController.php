<?php

namespace App\Http\Controllers\APIControllers;
use App\Http\Controllers\Controller;

class ShopManageController extends Controller {

    /** post_shopactivegift_give	行銷活動贈與 * */ 
    function postshopactivegiftgive() {
        $PostShopActiveGiftGive = new ShopManage\PostShopActiveGiftGive;
        return $PostShopActiveGiftGive -> postshopactivegiftgive();
    }
    
    /** query_shopbonus_item 紅利項目查詢 **/
    function queryshopbonusitem() {
        $QueryShopBonusItem = new ShopManage\QueryShopBonusItem;
        return $QueryShopBonusItem -> queryshopbonusitem();
    }
    
    /** query_shopbonus_item 紅利項目查詢 **/
    function postshopdata() {
        $PostShopData = new ShopManage\PostShopData;
        return $PostShopData -> postshopdata();
    }
     /** query_shopbonus_item 紅利項目查詢 **/
    function postshopdatau() {
        $PostShopDataU = new ShopManage\PostShopDataU;
        return $PostShopDataU -> postshopdatau();
    }
    /** post_shopbonus_mamager	紅利管理設置 **/
    function postshopbonusmamager() {
        $PostShopBonusMamager = new ShopManage\PostShopBonusMamager;
        return $PostShopBonusMamager -> postshopbonusmamager();
    }
    
    /** post_shopcomsume_bonus	現場消費紅利贈與 **/
    function postshopcomsumebonus() {
        $PostShopComsumeBonus = new ShopManage\PostShopComsumeBonus;
        return $PostShopComsumeBonus -> postshopcomsumebonus();
    }
    
    /** post_shopclerk_manager	商家店員管理 **/
    function postshopclerkmanager() {
        $PostShopClerkManager = new ShopManage\PostShopClerkManager;
        return $PostShopClerkManager -> postshopclerkmanager();
    }
    /** query_shopclerk_list	查詢商家店員資料 **/
    function queryshopclerklist() {
        $QueryShopclerkList = new ShopManage\QueryShopclerkList;
        return $QueryShopclerkList -> queryshopclerklist();
    }
    /** query_member_shopinfo	查詢會員已綁定之商家資料 **/
    function querymembershopinfo() {
        $QueryMemberShopInfo = new ShopManage\QueryMemberShopInfo;
        return $QueryMemberShopInfo -> querymembershopinfo();
    }
    /** login_shop_backend	特約商後台登入 **/
    function loginshopbackend() {
        $LoginShopBackend = new ShopManage\LoginShopBackend;
        return $LoginShopBackend -> loginshopbackend();
    }
    /** verifyshopmailbind	驗證商家註冊信箱的驗證碼 */
    function verifyshopmailbind() {
        $VerifyShopMailBind = new ShopManage\LoginShopBackend;
        return $VerifyShopMailBind -> verifyshopmailbind();
    }
    /** loginwebendadmin	驗證商家註冊信箱的驗證碼 */
    function loginwebendadmin() {
        $LoginWebendAdmin = new ShopManage\LoginWebendAdmin;
        return $LoginWebendAdmin -> loginwebendadmin();
    }

}
