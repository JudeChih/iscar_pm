<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家基本資料
 */
class PmCannedMessages extends Model {

//
    public $table = 'pm_canned_messages';
    public $primaryKey = 'cmsg_serno';
    public $timestamps = false;
   // public $incrementing = false;

}
