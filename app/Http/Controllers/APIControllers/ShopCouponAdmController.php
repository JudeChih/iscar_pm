<?php

namespace App\Http\Controllers\APIControllers;
use App\Http\Controllers\Controller;
class ShopCouponAdmController extends Controller {

    /**
     * queryshopcouponlist    取用對應類別商家資料列表回覆
     */
    function queryshopcouponlist() {
         $QueryShopCouponList = new ShopCouponAdm\QueryShopCouponList;
         return $QueryShopCouponList->queryshopcouponlist();
    }

    /**
     * queryshopcouponcontent 取用商家內容
     */
    function queryshopcouponcontent() {
         $QueryShopCouponContent = new ShopCouponAdm\QueryShopCouponContent;
         return $QueryShopCouponContent->queryshopcouponcontent();
    }

    /**
     * modifyshopcoupon   停用/啟用商家優惠活動券
     */
    function modifyshopcoupon() {
         $ModifyShopCoupon = new ShopCouponAdm\ModifyShopCoupon;
         return $ModifyShopCoupon->modifyshopcoupon();
    }

}
