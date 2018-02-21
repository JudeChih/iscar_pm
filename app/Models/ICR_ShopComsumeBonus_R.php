<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 車團資料表
 */
class ICR_ShopComsumeBonus_R extends Model {

    //
    public $table = 'icr_shopcomsumebonus_r';
    public $primaryKey = 'scbr_id';
    public $timestamps = false;
    public $incrementing = false;

     /**
     * ██████████▍CREATE 建立資料
     */                      
      public static function InsertData($arraydata) {
        try {
              if (  !Commontools::CheckArrayValue($arraydata, "scbr_id")    || !Commontools::CheckArrayValue($arraydata, "sd_id") 
                 || !Commontools::CheckArrayValue($arraydata, "scbr_md_id") || !Commontools::CheckArrayValue($arraydata, "scbr_clerkid")
                 ) {
                return false;
              } 
              $savadata['scbr_id'] = $arraydata['scbr_id'];
              $savadata['sd_id'] = $arraydata['sd_id'];
              $savadata['scbr_md_id'] = $arraydata['scbr_md_id'];
              $savadata['scbr_clerkid'] = $arraydata['scbr_clerkid'];
              
             
              if (Commontools::CheckArrayValue($arraydata, "scbr_comsumeamount")) {
                $savadata['scbr_comsumeamount'] = $arraydata['scbr_comsumeamount'];
              } 
              if (Commontools::CheckArrayValue($arraydata, "scbr_bonusgive")) {
                $savadata['scbr_bonusgive'] = $arraydata['scbr_bonusgive'];
              } 
              
              if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savadata['isflag'] = $arraydata['isflag'];
              } else {
                $savadata['isflag'] = '1';
              }
              if (Commontools::CheckArrayValue($arraydata, "create_user")) {
                $savadata['create_user'] = $arraydata['create_user'];
              } else {
                $savadata['create_user'] = 'webapi';
              }
              if (Commontools::CheckArrayValue($arraydata, "last_update_user")) {
                $savadata['last_update_user'] = $arraydata['last_update_user'];
              } else {
                $savadata['last_update_user'] = 'webapi';
              } 
             if( DB::table('icr_shopcomsumebonus_r')->insert($savadata) ) {
                return true;
             } else {
                return false;
             }
        } catch (Exception $e) {
            \App\models\ErrorLog::InsertData($e);
            return false;
        }
    }
    
    /**
     * ██████████▍READ 讀取資料
     */
     
     
     
    /**
     * ██████████▍UPDATE 更新資料
     */
    


    /**
     * ██████████▍DELETE 刪除資料
     */
    /**
     * ██████████▍CHECK 檢查資料
     */
}
