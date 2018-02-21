<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家基本資料
 */
class ICR_Reservation_Paused extends Model {

//
    public $table = 'icr_reservation_paused';
    public $primaryKey = 'rp_serno';
    public $timestamps = false;
   // public $incrementing = false;

}
