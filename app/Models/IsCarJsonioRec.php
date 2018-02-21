<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;

use DB;

class IsCarJsonioRec extends Model {

    //
    public $table = 'iscarjsoniorec';
    public $primaryKey = 'jio_serno';
    public $timestamps = false;
     
    //新增資料
    public static function InsertData($arraydata) {

        //檢查「必填值」
        if (!Commontools::CheckArrayValue($arraydata, "jio_receive") || !Commontools::CheckArrayValue($arraydata, "jio_return") || !Commontools::CheckArrayValue($arraydata, "jio_wcffunction") || !Commontools::CheckArrayValue($arraydata, "ps_id")) {
            return false;
        }

        $savedata['jio_receive'] = $arraydata['jio_receive'];
        $savedata['jio_return'] = $arraydata['jio_return'];
        $savedata['jio_wcffunction'] = $arraydata['jio_wcffunction'];
        $savedata['ps_id'] = $arraydata['ps_id'];


        if (!Commontools::CheckArrayValue($arraydata, "isflag")) {
            $savedata['isflag'] = '1';
        } else {
            $savedata['isflag'] = $arraydata['isflag'];
        }

        if (!Commontools::CheckArrayValue($arraydata, "create_user")) {
            $savedata['create_user'] = 'webapi';
        } else {
            $savedata['create_user'] = $arraydata['create_user'];
        }
        if (!Commontools::CheckArrayValue($arraydata, "last_update_user")) {
            $savedata['last_update_user'] = 'webapi';
        } else {
            $savedata['last_update_user'] = $arraydata['last_update_user'];
        }
        if (DB::table('iscarjsoniorec')->insert($savedata)) {
            return true;
        } else {
            return false;
        }
    }
    
     public static function InsertDataGetId($arraydata, &$jio_id) {

        //檢查「必填值」
        if (!Commontools::CheckArrayValue($arraydata, "jio_receive") || !Commontools::CheckArrayValue($arraydata, "jio_return") || !Commontools::CheckArrayValue($arraydata, "jio_wcffunction") || !Commontools::CheckArrayValue($arraydata, "ps_id")) {
            return false;
        }

        $savedata['jio_receive'] = $arraydata['jio_receive'];
        $savedata['jio_return'] = $arraydata['jio_return'];
        $savedata['jio_wcffunction'] = $arraydata['jio_wcffunction'];
        $savedata['ps_id'] = $arraydata['ps_id'];


        if (!Commontools::CheckArrayValue($arraydata, "isflag")) {
            $savedata['isflag'] = '1';
        } else {
            $savedata['isflag'] = $arraydata['isflag'];
        }

        if (!Commontools::CheckArrayValue($arraydata, "create_user")) {
            $savedata['create_user'] = 'webapi';
        } else {
            $savedata['create_user'] = $arraydata['create_user'];
        }
        if (!Commontools::CheckArrayValue($arraydata, "last_update_user")) {
            $savedata['last_update_user'] = 'webapi';
        } else {
            $savedata['last_update_user'] = $arraydata['last_update_user'];
        }   
        if ($jio_id = DB::table('iscarjsoniorec')->insertGetId($savedata)) {
            return true;
        } else {
            return false;
        }
    }
}
