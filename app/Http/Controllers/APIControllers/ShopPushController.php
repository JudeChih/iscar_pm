<?php

namespace App\Http\Controllers\APIControllers;
use App\Http\Controllers\Controller;

class ShopPushController extends Controller {

    /** query_shoppush_content	推播項目內容查詢 **/ 
    function queryshoppushcontent() {
        $QueryShoppushContent = new ShopPush\QueryShoppushContent;
        return $QueryShoppushContent->queryshoppushcontent();
    }
    
    /** query_shoppush_record	推播記錄列表查詢 **/
    function queryshoppushrecord() {
        $QueryShoppushRecord = new ShopPush\QueryShoppushRecord;
        return $QueryShoppushRecord->queryshoppushrecord();
    }
  
    /**push_shopad2member 特約商會員優惠訊息推播 **/
    function pushshopad2member() {
        $PushShopAd2Member = new ShopPush\PushShopAd2Member;
        return $PushShopAd2Member->pushshopad2member();
    } 
    
    /**push_shopad2nonmember 特約商會員優惠訊息推播 **/
    function pushshopad2nonmember() {
        $PushShopAd2NonMember = new ShopPush\PushShopAd2NonMember;
        return $PushShopAd2NonMember->pushshopad2nonmember();
        
    } 
    
    /** query_shopservice_fee	特約商服務費用項目 **/
    function queryshopservicefee() {
        $QueryShopserviceFee = new ShopPush\QueryShopserviceFee;
        return $QueryShopserviceFee->queryshopservicefee();
    }
    
    /** query_push_nonmember	特約商推播非會員對象前查詢可推播總數 **/
    function querypushnonmember() {
        $QueryPushNonmember = new ShopPush\QueryPushNonmember;
        return $QueryPushNonmember->querypushnonmember();
    }

}
