<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家基本資料
 */
class ICR_PmReceiptIssue_Log extends Model {

//
    public $table = 'icr_pmreceiptissue_log';
    public $primaryKey = 'pril_serno';
    public $timestamps = false;
   // public $incrementing = false;

}
