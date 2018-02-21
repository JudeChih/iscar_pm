<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家基本資料
 */
class ICR_AgentVisitRecord extends Model {

    //
    public $table = 'icr_agentvisitrecord';
    public $primaryKey = 'avr_id';
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
                   !Commontools::CheckArrayValue($arraydata, "avr_id")  || !Commontools::CheckArrayValue($arraydata, "sar_id")
                || !Commontools::CheckArrayValue($arraydata, "sd_id")  || !Commontools::CheckArrayValue($arraydata, "md_id") 
                || !Commontools::CheckArrayValue($arraydata, "dbir_id") || !Commontools::CheckArrayValue($arraydata, "smb_serno")
                ) {
                return false;
             }
             $savadata['avr_id'] = $arraydata['avr_id'];
             $savadata['sar_id'] = $arraydata['sar_id'];
             $savadata['sd_id'] = $arraydata['sd_id'];
             $savadata['md_id'] = $arraydata['md_id'];
             $savadata['dbir_id'] = $arraydata['dbir_id'];
             $savadata['smb_serno'] = $arraydata['smb_serno'];
             
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
            if (DB::table('icr_agentvisitrecord')->insert($savadata)) {
               return true;
            } else {
               return false;
            }
            
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($e);
            return false;
        }
    }     

}
