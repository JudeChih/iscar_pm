<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class ICR_ShopDataModifyRecord extends Model {

    //
    public $table = 'icr_shopdatamodifyrecord';
    public $primaryKey = 'sdmr_serno';
    public $timestamps = false;
    public $incrementing = false;

}