<?php

namespace App\Http\Controllers\APIControllers;
use App\Http\Controllers\Controller;
class ReservationPausedController extends Controller {

    /**
     * query_reservationpausedlist   查詢「暫停預約資料」列表 
     */
    function query_reservationpausedlist() {
        $Query_ReservationPausedList = new ReservationPaused\Query_ReservationPausedList;
        return $Query_ReservationPausedList->query_reservationpausedlist();
    }

    /**
     * delete_reservationpaused 刪除「暫停預約資料」資料
     */
    function delete_reservationpaused() {
        $Delete_ReservationPaused = new ReservationPaused\Delete_ReservationPaused;
        return $Delete_ReservationPaused->delete_reservationpaused();
    }
    
     /**
     * create_reservationpaused   建立「暫停預約資料」資料
     */
    function create_reservationpaused() {
        $Create_ReservationPaused = new ReservationPaused\Create_ReservationPaused;
        return $Create_ReservationPaused->create_reservationpaused();
    }
    
    function query_couponreservation_year() {
        $Query_CouponReservation_Year = new ReservationPaused\Query_CouponReservation_Year;
        return $Query_CouponReservation_Year->query_couponreservation_year();
    }

   

}
