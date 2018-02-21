<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class ICR_ShopBackend_Register extends Model {

    //
    public $table = 'icr_shopbackend_register';
    public $primaryKey = 'sbr_no';
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
              if (!Commontools::CheckArrayValue($arraydata, "md_id") || !Commontools::CheckArrayValue($arraydata, "shop_backend_Id")) {
                return false;
              } 
              $savadata['md_id'] = $arraydata['md_id'];
              $savadata['shop_backend_Id'] = $arraydata['shop_backend_Id'];
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
             
              DB::table('icr_shopbackend_register')->insert($savadata);  
              return true;
        } catch (Exception $e) {
            \App\models\ErrorLog::InsertData($e);
            return false;
        }
    }
    
          

    /**
     * ██████████▍READ 讀取資料
     */
     
     
     public static function GetDataByBackendId ($backendId) {
        try {
             $result = ICR_ShopBackend_Register::where('icr_shopbackend_register.shop_backend_Id', '=', $backendId)
                                               ->where('icr_shopbackend_register.isflag','=',1)
                                               ->get()->toArray();
            return $result;
        } catch (\Exception $e) {
           ErrorLog::InsertData($e);
           return false;
        }
     }

    
    /**
     * ██████████▍UPDATE 更新資料
     */
    public static function UpdateData(array $arraydata) {

        try {
            if (!Commontools::CheckArrayValue($arraydata, "sbr_no")) {
                return false;
            }
           // DB::beginTransaction();
            $savedata['sbr_no'] = $arraydata['sbr_no'];
           
            if (Commontools::CheckArrayValue($arraydata, "md_id")) {
                $savedata['md_id'] = $arraydata['md_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, "shop_backend_Id")) {
                $savedata['shop_backend_Id'] = $arraydata['shop_backend_Id'];
            }
            if (Commontools::CheckArrayValue($arraydata, "create_user")) {
                $savedata['create_user'] = $arraydata['create_user'];
            }
            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
            }
            $savedata['last_update_user'] = 'webapi';
            $savedata['last_update_date'] = date('Y-m-d H:i:s');
            DB::table('icr_shopbackend_register')
                    ->where('sbr_no', '=', $savedata['sbr_no'])
                    ->update($savedata);
            //DB::commit();
            return true;
        } catch (Exception $ex) {
            //DB::rollBack();
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }
   

    /**
     * ██████████▍DELETE 刪除資料
     */
    /**
     * ██████████▍CHECK 檢查資料
     */
}
