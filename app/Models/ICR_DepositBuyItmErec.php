<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家基本資料
 */
class ICR_DepositBuyItmErec extends Model {

    //
    public $table = 'icr_depositbuyitmerec';
    public $primaryKey = 'dbir_id';
    public $timestamps = false;
    public $incrementing = false;

     /**
     * ██████████▍CREATE 建立資料
     */
     
      /**
     * InsertData
     * @param array $arraydata
     */
    public static function InsertData($arraydata) {
        try {
             if (
                !Commontools::CheckArrayValue($arraydata, "dbir_id") || !Commontools::CheckArrayValue($arraydata, "md_id")
                || !Commontools::CheckArrayValue($arraydata, "dbir_object_type") || !Commontools::CheckArrayValue($arraydata, "dbir_object_id") 
                || !Commontools::CheckArrayValue($arraydata, "dcil_id") /**|| !Commontools::CheckArrayValue($arraydata, "dbir_activatedate")
                || !Commontools::CheckArrayValue($arraydata, "dbir_expiredate" )**/
                ) {
                return false;
             }
             $savadata['dbir_id'] = $arraydata['dbir_id'];
             $savadata['md_id'] = $arraydata['md_id'];
             $savadata['dbir_object_type'] = $arraydata['dbir_object_type'];
             $savadata['dbir_object_id'] = $arraydata['dbir_object_id'];
             $savadata['dcil_id'] = $arraydata['dcil_id'];
             $savadata['dbir_activatedate'] = $arraydata['dbir_activatedate'];
             $savadata['dbir_expiredate'] = $arraydata['dbir_expiredate'];
             if (Commontools::CheckArrayValue($arraydata, 'isflag')) {
                $savadata['isflag'] = $arraydata['isflag'];
             } else {
                $savadata['isflag'] = '1';
             }
             if (Commontools::CheckArrayValue($arraydata, 'create_user')) {
                $savadata['create_user'] = $arraydata['create_user'];
             } else {
                $savadata['create_user'] = 'webapi';
             }
             if (Commontools::CheckArrayValue($arraydata, 'last_update_user')) {
                $savadata['last_update_user'] = $arraydata['last_update_user'];
             } else {
                $savadata['last_update_user'] = 'webapi';
             }
            if (DB::table('icr_depositbuyitmerec')->insert($savadata)) {
               return true;
            } else {
               return false;
            }
            
        } catch (Exception $ex) {
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
