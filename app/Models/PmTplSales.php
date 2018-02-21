<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家基本資料
 */
class PmTplSales extends Model {

//
    public $table = 'pm_tpl_sales';
    public $primaryKey = 'tps_serno';
    public $timestamps = false;
   // public $incrementing = false;

}
