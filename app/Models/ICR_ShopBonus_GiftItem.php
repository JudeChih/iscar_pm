<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 車團資料表
 */
class ICR_ShopBonus_GiftItem extends Model {

    //
    public $table = 'icr_shopbonus_giftitem';
    public $primaryKey = 'sbgi_id';
    public $timestamps = false;
    public $incrementing = false;

     /**
     * ██████████▍CREATE 建立資料
     */                      
      public static function InsertData($arraydata) {
        try {
              if (  !Commontools::CheckArrayValue($arraydata, "sd_id") || !Commontools::CheckArrayValue($arraydata, "sbgi_itemname") 
                 || !Commontools::CheckArrayValue($arraydata, "sbgi_itemamount") || !Commontools::CheckArrayValue($arraydata, "sbgi_id") 
                 ) {
                return false;
              } 
              $savadata['sbgi_id'] = $arraydata['sbgi_id'];
              $savadata['sd_id'] = $arraydata['sd_id'];
              $savadata['sbgi_itemname'] = $arraydata['sbgi_itemname'];
              $savadata['sbgi_itemamount'] = $arraydata['sbgi_itemamount'];
            
              if (Commontools::CheckArrayValue($arraydata, "sbgi_fittype")) {
                $savadata['sbgi_fittype'] = $arraydata['sbgi_fittype'];
              } 
              if (Commontools::CheckArrayValue($arraydata, "sbgi_effective")) {
                $savadata['sbgi_effective'] = $arraydata['sbgi_effective'];
              } 
              if (Commontools::CheckArrayValue($arraydata, "sbgi_effectivedate")) {
                $savadata['sbgi_effectivedate'] = $arraydata['sbgi_effectivedate'];
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
             if( DB::table('icr_shopbonus_giftitem')->insert($savadata) ) {
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
     
      public static function GetEffectiveGiftItem($sd_id, $sbgi_id) {
        try {
             $result = ICR_ShopBonus_GiftItem::where('icr_shopbonus_giftitem.sd_id',$sd_id)
                                             ->where('icr_shopbonus_giftitem.sbgi_id',$sbgi_id)
                                             ->where('icr_shopbonus_giftitem.sbgi_effective',1)
                                             ->get()->toArray();
                                                       
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null; 
        }
     }
     
     
     public static function GetDataBySdId_SbgiId($sd_id, $sbgi_id) {
       try {
             $query = ICR_ShopBonus_GiftItem::where('icr_shopbonus_giftitem.sd_id',$sd_id)
                                             ->where('icr_shopbonus_giftitem.isflag',1);
             if ( !is_null($sbgi_id) && mb_strlen($sbgi_id) != 0 ) {
                 $query->where('icr_shopbonus_giftitem.sbgi_id',$sbgi_id);
             }
             $result = $query->get()->toArray();
                                                       
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null; 
        }
     }
     
     public static function GetDataBySbgiId($sbgi_id) {
        try {
             $result = ICR_ShopBonus_GiftItem::where('icr_shopbonus_giftitem.sbgi_id',$sbgi_id)
                                             ->where('icr_shopbonus_giftitem.isflag',1)
                                             ->get()->toArray();
                                                       
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null; 
        }
     }

     
     
    /**
     * ██████████▍UPDATE 更新資料
     */
      public static function UpdateData($arraydata) {
        try {
            if ( !Commontools::CheckArrayValue($arraydata, 'sbgi_id') ) {
                return false;
            }
            $savedata['sbgi_id'] = $arraydata['sbgi_id'];
            
            if (Commontools::CheckArrayValue($arraydata, "sd_id")) {
              $savedata['sd_id'] = $arraydata['sd_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sbgi_fittype")) {
              $savedata['sbgi_fittype'] = $arraydata['sbgi_fittype'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sbgi_itemname")) {
              $savedata['sbgi_itemname'] = $arraydata['sbgi_itemname'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sbgi_itemamount")) {
              $savedata['sbgi_itemamount'] = $arraydata['sbgi_itemamount'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sbgi_effective")) {
              $savedata['sbgi_effective'] = $arraydata['sbgi_effective'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sbgi_effectivedate")) {
              $savedata['sbgi_effectivedate'] = $arraydata['sbgi_effectivedate'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'isflag')) {
                $savedata['isflag'] = $arraydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
            }

            $savedata['last_update_user'] = 'webapi';

            DB::table('icr_shopbonus_giftitem')
                    ->where('sbgi_id', $savedata['sbgi_id'])
                    ->update($savedata);

            return true;
        } catch (Exception $ex) {
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
