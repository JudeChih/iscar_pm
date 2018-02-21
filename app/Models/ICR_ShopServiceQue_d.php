<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class ICR_ShopServiceQue_d extends Model {

//
    public $table = 'icr_shopserviceque_d';
    public $primaryKey = 'ssqd_id';
    public $timestamps = false;
    public $incrementing = false;

    /** ██████████▍CREATE 建立資料 */
    /** ██████████▍READ 讀取資料 */

    /**
     * 依「$ssqd_id」取得資料
     * @param type $ssqd_id
     * @return type
     * 
     */
    public static function GetData($ssqd_id) {
        try {
            if ($ssqd_id == null || strlen($ssqd_id) == 0) {
                return null;
            }

            $results = ICR_ShopServiceQue_d::where('icr_shopserviceque_d.isflag', '=', '1')
                            ->where('icr_shopserviceque_d.ssqd_id', '=', $ssqd_id)
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 依「$ssqd_id」取得資料
     * @param type $ssqd_id
     * @return type
     */
    public static function GetDataBySDID($sd_id) {
        try {
            if ($sd_id == null || strlen($sd_id) == 0) {
                return null;
            }

            $results = ICR_ShopServiceQue_d::where('icr_shopserviceque_d.isflag', '=', '1')
                            ->where('icr_shopserviceque_d.sd_id', '=', $sd_id)
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    public static function InsertData($arraydata) {
       try {
             if ( !Commontools::CheckArrayValue($arraydata, "ssqd_id") || !Commontools::CheckArrayValue($arraydata, "sd_id") || !Commontools::CheckArrayValue($arraydata, "ssqd_title") || 
                  !Commontools::CheckArrayValue($arraydata, "ssqd_content")|| !Commontools::CheckArrayValue($arraydata, "ssqd_mainpic") || !Commontools::CheckArrayValue($arraydata, "ssqd_workhour")||
                  !Commontools::CheckArrayValue($arraydata, "ssqd_serviceprice") || !Commontools::CheckArrayValue($arraydata, "ssqd_maxqueueamount") ) {
                return false;
              }
              $savadata['ssqd_id'] = $arraydata['ssqd_id'];
              $savadata['sd_id'] = $arraydata['sd_id'];
              $savadata['ssqd_title'] = $arraydata['ssqd_title'];
              $savadata['ssqd_content'] = $arraydata['ssqd_content'];
              $savadata['ssqd_mainpic'] = $arraydata['ssqd_mainpic'];
              $savadata['ssqd_workhour'] = $arraydata['ssqd_workhour'];
              $savadata['ssqd_serviceprice'] = $arraydata['ssqd_serviceprice'];
              $savadata['ssqd_maxqueueamount'] = $arraydata['ssqd_maxqueueamount'];
          
              if (Commontools::CheckArrayValue($arraydata, "ssqd_effectivity")) {
                $savadata['ssqd_effectivity'] = $arraydata['ssqd_effectivity'];
              } else {
                $savadata['ssqd_effectivity'] = '0';
              }
              if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savadata['isflag'] = $arraydata['isflag'];
              } else {
                $savadata['isflag'] = '1';
              }
              $savadata['create_user'] = 'webapi';
              $savadata['last_update_user'] = 'webapi';
              DB::table('icr_shopserviceque_d')->insert($savadata);
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
              if (!Commontools::CheckArrayValue($modifydata, "ssqd_id")) {
                return false;
              }
              $savedata['ssqd_id'] = $modifydata['ssqd_id'];
              if (Commontools::CheckArrayValue($modifydata, "ssqd_title")) {
                $savedata['ssqd_title'] = $modifydata['ssqd_title'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqd_content")) {
                $savedata['ssqd_content'] = $modifydata['ssqd_content'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqd_mainpic")) {
                $savedata['ssqd_mainpic'] = $modifydata['ssqd_mainpic'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqd_workhour")) {
                $savedata['ssqd_workhour'] = $modifydata['ssqd_workhour'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqd_serviceprice")) {
                $savedata['ssqd_serviceprice'] = $modifydata['ssqd_serviceprice'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqd_effectivity")) {
                $savedata['ssqd_effectivity'] = $modifydata['ssqd_effectivity'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqd_maxqueueamount")) {
                $savedata['ssqd_maxqueueamount'] = $modifydata['ssqd_maxqueueamount'];
              }
              if (Commontools::CheckArrayValue($modifydata, "isflag")) {
                $savedata['isflag'] = $modifydata['isflag'];
              }else {
                $savedata['isflag'] = '1';
              }
              $savadata['last_update_user'] = 'webapi';
              DB::table('icr_shopserviceque_d')
                    ->where('ssqd_id', '=', $savedata['ssqd_id'])
                    ->update($savedata);

             return true;
       } catch (Exception $e) {
           ErrorLog::Insert($ex);
           return false;
       }
       
    }
    
    /**
     * 依「$ssqd_id」，查詢服務編號服務狀態
     *  @param type $ssqd_id
     *  @return type
     */
    public static function Query_ShopServiceData($ssqd_id){
       try{
           if($ssqd_id == null || strlen($ssqd_id) == 0){
              return null;
           }
           
           $results = ICR_ShopServiceQue_d::where('icr_shopserviceque_d.isflag','=','1')
                           ->leftjoin('icr_shopserviceque_m','icr_shopserviceque_d.sd_id','=','icr_shopserviceque_m.sd_id')
                           ->leftjoin('icr_shopdata','icr_shopserviceque_d.sd_id','=','icr_shopdata.sd_id')
                           ->where('icr_shopserviceque_d.ssqd_id','=',$ssqd_id)
                           ->get()->toArray();
           return $results;
       } catch (Exception $ex) {
           ErrorLog::Insert($ex);
           return null;
       }
       
    }
    
    public static function Query_ServiceData_ByARRAY($ssqd_id_array){
       try {
            if (is_null($ssqd_id_array) || !is_array($ssqd_id_array)) {
                return false;
            }
            $query = ICR_ShopServiceQue_d::where('icr_shopserviceque_d.isflag', '=', '1')
                            ->whereIn('icr_shopserviceque_d.ssqd_id',$ssqd_id_array);
           $result = $query->select(
                               'icr_shopserviceque_d.ssqd_id',
                               'icr_shopserviceque_d.sd_id',
                               'icr_shopserviceque_d.ssqd_title',
                               'icr_shopserviceque_d.ssqd_content',
                               'icr_shopserviceque_d.ssqd_mainpic',
                               'icr_shopserviceque_d.ssqd_workhour',
                               'icr_shopserviceque_d.ssqd_serviceprice'
                            )->get()->toArray();
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($ex);
          return null; 
        } 
    }
}