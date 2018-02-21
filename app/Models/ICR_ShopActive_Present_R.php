<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 車團資料表
 */
class ICR_ShopActive_Present_R extends Model {

    //
    public $table = 'icr_shopactive_present_r';
    public $primaryKey = 'sapr_id';
    public $timestamps = false;
    public $incrementing = false;

     /**
     * ██████████▍CREATE 建立資料
     */                      
      public static function InsertData($arraydata) {
        try {
              if (  !Commontools::CheckArrayValue($arraydata, "sd_id") || !Commontools::CheckArrayValue($arraydata, "sapr_activeid") 
                 || !Commontools::CheckArrayValue($arraydata, "sapr_gift_id") || !Commontools::CheckArrayValue($arraydata, "sapr_id")
                 || !Commontools::CheckArrayValue($arraydata, "sapr_md_id") 
                 ) {
                return false;
              } 
              $savadata['sd_id'] = $arraydata['sd_id'];
              $savadata['sapr_activeid'] = $arraydata['sapr_activeid'];
              $savadata['sapr_gift_id'] = $arraydata['sapr_gift_id'];
              $savadata['sapr_id'] = $arraydata['sapr_id'];
              $savadata['sapr_md_id'] = $arraydata['sapr_md_id'];
              
              if (Commontools::CheckArrayValue($arraydata, "sapr_gifttype")) {
                $savadata['sapr_gifttype'] = $arraydata['sapr_gifttype'];
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
             if( DB::table('icr_shopactive_present_r')->insert($savadata) ) {
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
