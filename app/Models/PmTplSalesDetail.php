<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家基本資料
 */
class PmTplSalesDetail extends Model {

//
    public $table = 'pm_tpl_salesdetail';
    public $primaryKey = 'tpsd_serno';
    public $timestamps = false;
   // public $incrementing = false;

}
