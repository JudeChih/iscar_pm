<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家基本資料
 */
class ICR_ShopAdpush_D extends Model {

//
    public $table = 'icr_shopadpush_d';
    public $primaryKey = 'sapd_serno';
    public $timestamps = false;
    public $incrementing = false;
    


    public static function InsertData($arraydata){
      try {
             if (!Commontools::CheckArrayValue($arraydata, "sapm_id") || !Commontools::CheckArrayValue($arraydata, "sapd_object_mdid")
              || !Commontools::CheckArrayValue($arraydata, "uml_id")  ) {
                return false;
              }
              $savadata['sapm_id'] = $arraydata['sapm_id'];
              $savadata['sapd_object_mdid'] = $arraydata['sapd_object_mdid'];
              $savadata['uml_id'] = $arraydata['uml_id'];
             
              if (Commontools::CheckArrayValue($arraydata, "sapd_pushtag")) {
                $savadata['sapd_pushtag'] = $arraydata['sapd_pushtag'];
              }
              
              
              if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savadata['isflag'] = $arraydata['isflag'];
              } else {
                $savadata['isflag'] = '1';
              }
              $savadata['create_user'] = 'webapi';
              $savadata['last_update_user'] = 'webapi';
              DB::table('icr_shopadpush_d')->insert($savadata);
              return true;
       } catch(Exception $e) {
            ErrorLog::Insert($ex);
            return false;
       }
    }


  
    /**
     * 修改資料
     * @param array $arraydata 要更新的資料
     * @return boolean
     */
    public static function UpdateData(array $arraydata) {
        try {
            if (!Commontools::CheckArrayValue($arraydata, 'sapd_serno')) {
                return false;
            }

            $savedata['sapd_serno'] = $arraydata['sapd_serno'];

            if (Commontools::CheckArrayValue($arraydata, 'sapm_id')) {
                $savedata['sapm_id'] = $arraydata['sapm_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sapd_object_mdid')) {
                $savedata['sapd_object_mdid'] = $arraydata['sapd_object_mdid'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'uml_id')) {
                $savedata['uml_id'] = $arraydata['uml_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sapd_pushtag')) {
                $savedata['sapd_pushtag'] = $arraydata['sapd_pushtag'];
            }
            
           
            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            }

            $savedata['last_update_user'] = 'webapi';


            DB::table('icr_shopadpush_d')
                    ->where('sapd_serno', $savedata['sapd_serno'])
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }

   
}
