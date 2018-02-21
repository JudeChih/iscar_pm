<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class ICR_ShopServiceQue_m extends Model {

//
    public $table = 'icr_shopserviceque_m';
    public $primaryKey = 'sd_id';
    public $timestamps = false;
    public $incrementing = false;

    /** ██████████▍CREATE 建立資料 */
    /** ██████████▍READ 讀取資料 */

    /**
     * 依「$sd_id」取得資料
     * @param type $sd_id
     * @return type
     */
    public static function GetData($sd_id) {
        try {
            if ($sd_id == null || strlen($sd_id) == 0) {
                return null;
            }

            $results = ICR_ShopServiceQue_m::where('icr_shopserviceque_m.isflag', '=', '1')
                            ->where('icr_shopserviceque_m.sd_id', '=', $sd_id)
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
     public static function InsertData($arraydata) {
       try {
             if ( !Commontools::CheckArrayValue($arraydata, "sd_id") || !Commontools::CheckArrayValue($arraydata, "ssqm_weekstart") || 
                  !Commontools::CheckArrayValue($arraydata, "ssqm_weekend") || !Commontools::CheckArrayValue($arraydata, "ssqm_dailystart")|| 
                  !Commontools::CheckArrayValue($arraydata, "ssqm_dailyend") /* || !Commontools::CheckArrayValue($arraydata, "ssqd_maxqueueamount") */) {
                return false;
              }
              $savadata['sd_id'] = $arraydata['sd_id'];
              $savadata['ssqm_weekstart'] = $arraydata['ssqm_weekstart'];
              $savadata['ssqm_weekend'] = $arraydata['ssqm_weekend'];
              $savadata['ssqm_dailystart'] = $arraydata['ssqm_dailystart'];
              $savadata['ssqm_dailyend'] = $arraydata['ssqm_dailyend'];
             // $savadata['ssqd_maxqueueamount'] = $arraydata['ssqd_maxqueueamount'];
               
              if (Commontools::CheckArrayValue($arraydata, "ssqm_dayoffarray")) {
                $savadata['ssqm_dayoffarray'] = $arraydata['ssqm_dayoffarray'];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssqm_servicestart")) {
                $savadata['ssqm_servicestart'] = $arraydata['ssqm_servicestart'];
              } else {
                $savadata['ssqm_servicestart'] = '0';
              }
              if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savadata['isflag'] = $arraydata['isflag'];
              } else {
                $savadata['isflag'] = '1';
              }
              $savadata['create_user'] = 'webapi';
              $savadata['last_update_user'] = 'webapi';
              DB::table('icr_shopserviceque_m')->insert($savadata);
              return true;
       } catch(Exception $e) {
            ErrorLog::Insert($ex);
            return false;
       }
    }

    /** ██████████▍UPDATE 更新資料 */
    /** ██████████▍DELETE 刪除資料 */
    /** ██████████▍CHECK 檢查資料 */
    /** ██████████▍QUERY 查詢資料 */
     public static function UpdateData($modifydata) {
       try {
              if (!Commontools::CheckArrayValue($modifydata, "sd_id")) {
                return false;
              }
              $savadata['sd_id'] = $modifydata['sd_id']; 
              
              if (Commontools::CheckArrayValue($modifydata, "ssqm_weekstart")) {
                $savadata['ssqm_weekstart'] = $modifydata['ssqm_weekstart'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqm_weekend")) {
                $savadata['ssqm_weekend'] = $modifydata['ssqm_weekend'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqm_dailystart")) {
                $savadata['ssqm_dailystart'] = $modifydata['ssqm_dailystart'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqm_dailyend")) {
                $savadata['ssqm_dailyend'] = $modifydata['ssqm_dailyend'];
              }
              if (key_exists("ssqm_dayoffarray",$modifydata)) {
                $savadata['ssqm_dayoffarray'] = $modifydata['ssqm_dayoffarray'];
              }
              /*if (Commontools::CheckArrayValue($modifydata, "ssqm_maxqueueamount")) {
                $savedata['ssqm_maxqueueamount'] = $modifydata['ssqm_maxqueueamount'];
              } */
              if (Commontools::CheckArrayValue($modifydata, "ssqm_servicestart")) {
                $savadata['ssqm_servicestart'] = $modifydata['ssqm_servicestart'];
              }
              if (Commontools::CheckArrayValue($modifydata, "isflag")) {
                $savadata['isflag'] = $modifydata['isflag'];
              }else {
                $savadata['isflag'] = '1';
              }
              $savadata['last_update_user'] = 'webapi';
              DB::table('icr_shopserviceque_m')
                    ->where('sd_id', '=', $savadata['sd_id'])
                    ->update($savadata);

             return true;
       } catch (Exception $e) {
           ErrorLog::Insert($ex);
           return false;
       }
       
    }
}
