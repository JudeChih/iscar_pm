<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class ICR_ShopServicefee_Item extends Model {

//
    public $table = 'icr_shopservicefee_item';
    public $primaryKey = 'ssfi_serno';
    public $timestamps = false;
    public $incrementing = false;
    


    public static function InsertData($arraydata){
      try {
             if (!Commontools::CheckArrayValue($arraydata, "ssfi_servicename") || !Commontools::CheckArrayValue($arraydata, "ssfi_feename") ) {
                return false;
              }
              $savadata['ssfi_servicename'] = $arraydata['ssfi_servicename'];
              $savadata['ssfi_feename'] = $arraydata['ssfi_feename'];
             
              if (Commontools::CheckArrayValue($arraydata, "ssfi_servicetype")) {
                $savadata['ssfi_servicetype'] = $arraydata['ssfi_servicetype'];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssfi_itemprice")) {
                $savadata['ssfi_itemprice'] = $arraydata['ssfi_itemprice'];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssfi_pointforconsume")) {
                $savadata['ssfi_pointforconsume'] = $arraydata['ssfi_pointforconsume'];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssfi_functioncontent")) {
                $savadata['ssfi_functioncontent'] = $arraydata['ssfi_functioncontent'];
              }
              if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savadata['isflag'] = $arraydata['isflag'];
              } else {
                $savadata['isflag'] = '1';
              }
              $savadata['create_user'] = 'webapi';
              $savadata['last_update_user'] = 'webapi';
              DB::table('icr_shopservicefee_item')->insert($savadata);
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
              if (!Commontools::CheckArrayValue($arraydata, 'ssfi_serno')) {
                 return false;
              }
              $savedata['ssfi_serno'] = $arraydata['ssfi_serno'];

              if (Commontools::CheckArrayValue($arraydata, "ssfi_servicename")) {
                $savadata['ssfi_servicename'] = $arraydata['ssfi_servicename'];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssfi_feename")) {
                $savadata['ssfi_feename'] = $arraydata['ssfi_feename'];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssfi_servicetype")) {
                $savadata['ssfi_servicetype'] = $arraydata['ssfi_servicetype'];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssfi_itemprice")) {
                $savadata['ssfi_itemprice'] = $arraydata['ssfi_itemprice'];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssfi_pointforconsume")) {
                $savadata['ssfi_pointforconsume'] = $arraydata['ssfi_pointforconsume'];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssfi_functioncontent")) {
                $savadata['ssfi_functioncontent'] = $arraydata['ssfi_functioncontent'];
              }
              if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savadata['isflag'] = $arraydata['isflag'];
              }
           
            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            }

            $savedata['last_update_user'] = 'webapi';


            DB::table('icr_shopservicefee_item')
                    ->where('ssfi_serno', $savedata['ssfi_serno'])
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }
    
    public static function GetShopPushFee($serno, $servicetype) {
       try {
            if ( is_null($serno) && is_null($servicetype) ) {
                return null;
            }
            
            $query = ICR_ShopServicefee_Item::where('icr_shopservicefee_item.isflag', '=', '1');

             
             if ( !is_null($serno) || mb_strlen($serno) != 0  ) {
                 $query->where('icr_shopservicefee_item.ssfi_serno',$serno);
             } 
             if ( !is_null($servicetype) || mb_strlen($servicetype) != 0  ) {
                 $query->where('icr_shopservicefee_item.ssfi_servicetype',$servicetype);
             } 
            $results = $query->get()->toArray();

            return $results;
            
       } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null;
       }
    }

   
}
