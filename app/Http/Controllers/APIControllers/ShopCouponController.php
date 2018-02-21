<?php

namespace App\Http\Controllers\APIControllers;
use App\Http\Controllers\Controller;
class ShopCouponController extends Controller {

    /** shopcouponlistquery	商家優惠活動券列表查詢 * */
    function shopcouponlistquery() {
         $ShopCouponListQuery = new ShopCoupon\ShopCouponListQuery;
         return $ShopCouponListQuery->shopcouponlistquery();
    }

    /** shopcouponcontentquery	商家優惠活動券內容查詢 * */
    function shopcouponcontentquery() {
         $ShopCouponContentQuery = new ShopCoupon\ShopCouponContentQuery;
         return $ShopCouponContentQuery->shopcouponcontentquery();
    }

    /** shopcouponget	商家優惠活動券取用 * */
    function shopcouponget() {
         $ShopCouponGet = new ShopCoupon\ShopCouponGet;
         return $ShopCouponGet->shopcouponget();
    }

    /** shopcouponreservationinfo	商家優惠活動券預約時段查詢 * */
    function shopcouponreservationinfo() {
         $ShopCouponReservationInfo = new ShopCoupon\ShopCouponReservationInfo;
         return $ShopCouponReservationInfo->shopcouponreservationinfo();
    }

    /** shopcouponreservationbook	商家優惠活動券預約 * */
    function shopcouponreservationbook() {
         $ShopCouponReservationBook = new ShopCoupon\ShopCouponReservationBook;
         return $ShopCouponReservationBook->shopcouponreservationbook();
    }

    /** shopcouponmanager	商家優惠活動券管理 * */
    function shopcouponmanager() {
         $ShopCouponManager = new ShopCoupon\ShopCouponManager;
         return $ShopCouponManager->shopcouponmanager();
    }

    /** shopcouponreservationquery	商家優惠活動券已預約項目查詢 * */
    function shopcouponreservationquery() {
         $ShopCouponReservationQuery = new ShopCoupon\ShopCouponReservationQuery;
         return $ShopCouponReservationQuery->shopcouponreservationquery();
    }

    /** shopcouponrecorver	用戶已索取之商家優惠券項目回復 * */
    function shopcouponrecorver() {
         $ShopCouponRecorver = new ShopCoupon\ShopCouponRecorver;
         return $ShopCouponRecorver->shopcouponrecorver();
    }

    /** shopcouponabandon	用戶棄用已已索取之商家優惠券 * */
    function shopcouponabandon() {
         $ShopCouponAbandon = new ShopCoupon\ShopCouponAbandon;
         return $ShopCouponAbandon->shopcouponabandon();
    }

    /** shopcouponscan	商家掃描優惠券條碼查核內容 * */
    function shopcouponscan() {
         $ShopCouponScan = new ShopCoupon\ShopCouponScan;
         return $ShopCouponScan->shopcouponscan();
    }

    /** shopcouponexec	商家執行優惠券內容 * */
    function shopcouponexec() {
         $ShopCouponExec = new ShopCoupon\ShopCouponExec;
         return $ShopCouponExec->shopcouponexec();
    }
    
    
    /** update_couponreplystatus	更新「預約回覆狀態」 * */
    function update_couponreplystatus() {
         $Update_CouponReplyStatus = new ShopCoupon\Update_CouponReplyStatus;
         return $Update_CouponReplyStatus->update_couponreplystatus();
    }
    
    /** query_reservationreplystatus	商家優惠活動券已預約未回覆項目查詢 * */
    function query_reservationreplystatus() {
         $Query_ReservationReplyStatus = new ShopCoupon\Query_ReservationReplyStatus;
         return $Query_ReservationReplyStatus->query_reservationreplystatus();
    }

}
