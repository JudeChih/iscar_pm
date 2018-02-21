<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家管理會員綁定表
 */
class ICR_SdmdBind extends Model {

    //
    public $table = 'icr_sdmdbind';
    public $primaryKey = 'smb_serno';
    public $timestamps = false;

    /**
     * 依「$cdbu_serno」取得資料
     * @param type $smb_serno
     * @param type $check_validity 是否需要判斷已過期﹙icr_sdmdbind.smb_validity > now()﹚
     * @return type
     */
    public static function GetData($smb_serno, $check_validity) {
        try {
            if ($smb_serno == null || strlen($smb_serno) == 0) {
                return null;
            }
            $query = ICR_SdmdBind::where('isflag', '=', '1')
                    ->where('smb_serno', $smb_serno);

            if ($check_validity) {
                $query = $query->whereRaw('icr_sdmdbind.smb_validity > now()');
            }

            $results = $query->get()->toArray();
            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 依「$sd_id」取得資料
     * @param type $sd_id 
     * @return type 查詢結果
     */
    public static function GetData_BySD_ID($sd_id, $check_validity) {
        try {

            if ($sd_id == null || strlen($sd_id) == 0) {
                return null;
            }
            $query = ICR_SdmdBind::where('isflag', '=', '1')
                    ->where('smb_sd_id', '=', $sd_id)
                    ->where('smb_activestatus', '=', '1');

            if ($check_validity) {
                $query = $query->whereRaw('icr_sdmdbind.smb_validity > now()');
            }

            $results = $query->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    
    
    /**
     * 依「$sd_id,$smb_bindlevel」取得資料
     * @param type $sd_id 
     * @param type $md_id
     * @param type $smb_bindlevel 
     * @return type 查詢結果
     */
    public static function GetData_BySdId($sd_id) {
        try {

            if ($sd_id == null || strlen($sd_id) == 0) {
                return null;
            }
            $query = ICR_SdmdBind::where('icr_sdmdbind.isflag', '=', '1')
                    ->where('icr_sdmdbind.smb_sd_id', '=', $sd_id)
                    ->leftjoin('iscarmemberdata','iscarmemberdata.md_id','=','icr_sdmdbind.smb_md_id');
            $results = $query->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    

    /**
     * 依「$md_id」取得資料
     * @param type $md_id 會員記錄id
     * @return type 查詢結果
     */
    public static function GetData_ByMd_ID($md_id, $check_validity) {
        try {

            if ($md_id == null || strlen($md_id) == 0) {
                return null;
            }
            $query = ICR_SdmdBind::where('isflag', '=', '1')
                    ->where('smb_md_id', '=', $md_id)
                    ->where('smb_activestatus', '=', '1');

            if ($check_validity) {
                $query = $query->whereRaw('icr_sdmdbind.smb_validity > now()');
            }

            $results = $query->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    public static function GetDataJoinShopData($md_id) {
        try {

            if ($md_id == null || strlen($md_id) == 0) {
                return null;
            }
            $query = ICR_SdmdBind::where('icr_sdmdbind.isflag', '=', '1')
                    ->where('icr_sdmdbind.smb_md_id', '=', $md_id)
                    ->where('icr_sdmdbind.smb_activestatus', '=', '1')
                    ->leftJoin('icr_shopdata' ,'icr_sdmdbind.smb_sd_id','=','icr_shopdata.sd_id');
           
            $results = $query->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    public static function GetData_ByMdid($md_id) {
        try {

            if ($md_id == null || strlen($md_id) == 0) {
                return null;
            }
            $query = ICR_SdmdBind::where('icr_sdmdbind.isflag', '=', '1')
                    ->where('icr_sdmdbind.smb_md_id', '=', $md_id)
                    ->where('icr_sdmdbind.smb_activestatus', '=', '1')
                    ->where('icr_sdmdbind.isflag', '=', '1')
                    ->leftJoin('icr_shopdata' ,'icr_sdmdbind.smb_sd_id','=','icr_shopdata.sd_id');
           
            $results = $query->select(  
                                       'icr_shopdata.sd_salescode'
                                     )->orderBy('icr_sdmdbind.create_date','desc')
                                    ->take(1)
                                    ->get()->toArray();;

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    
    public static function GetDataJoinShopDataByAmount($md_id, $queryamount, $sd_id) {
        try {

            if ($md_id == null || strlen($md_id) == 0) {
                return null;
            }
            $query = ICR_SdmdBind::where('icr_sdmdbind.isflag', '=', '1')
                    ->where('icr_sdmdbind.smb_md_id', '=', $md_id)
                    ->where('icr_sdmdbind.smb_activestatus', '=', '1')
                    ->orderBy('icr_shopdata.sd_id','asc')
                    ->leftJoin('icr_shopdata' ,'icr_sdmdbind.smb_sd_id','=','icr_shopdata.sd_id')
                    ->take($queryamount);
            
            if ( ! is_null($sd_id) ) {
                   $query->where('icr_shopdata.sd_id','>',$sd_id);
            };
           
            $results = $query->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    public static function GetDataByMdid_Type($sd_id) {
        try {
             $query = ICR_SdmdBind::where('isflag', '=', '1')
                    ->where('smb_sd_id', '=', $sd_id)
                    ->where('smb_activestatus', '=', '1')
                    ->where('smb_shoptype', '=', '1')
                    ->whereRaw('smb_validity > now()');
             $result = $query->select('smb_md_id as md_id')->get()->toArray();
             return $result;
        } catch(\Exception $e) {
           ErrorLog::InsertData($e);
           return null;
        }
    }


    /**
     * 依「$sd_id」、「$md_id」取得資料
     * @param type $sd_id 會員記錄id
     * @param type $md_id 會員記錄id
     * @return type 查詢結果
     */
    public static function GetData_By_SDID_MDID($sd_id, $md_id, $check_validity) {
        try {

            if ($sd_id == null || strlen($sd_id) == 0 || $md_id == null || strlen($md_id) == 0) {
                return null;
            }
            $query = ICR_SdmdBind::where('isflag', '=', '1')
                    ->where('smb_sd_id', '=', $sd_id)
                    ->where('smb_md_id', '=', $md_id)
                    ->where('smb_activestatus', '=', '1');

            if ($check_validity) {
                $query = $query->whereRaw('icr_sdmdbind.smb_validity > now()');
            }

            $results = $query->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    public static function GetData_BySentId($sentid) {
        try {
           if ($sentid == null || mb_strlen($sentid) == 0 ) {
                return null;
            }
            $query = ICR_SdmdBind::where('isflag', '=', '1')
                    ->where('smb_mail_sentid', '=', $sentid);
            $results = $query->select(  
                                       'icr_sdmdbind.smb_sd_id'
                                      ,'icr_sdmdbind.smb_md_id'
                                      ,'icr_sdmdbind.smb_mail_sentid'
                                      ,'icr_sdmdbind.smb_activestatus'
                                     )
                                    ->get()->toArray();

            return $results;
        } catch(\Exception $e) {
           ErrorLog::InsertData($e);
           return null;
        }
    }
    

    public static function InsertData(array $arraydata, &$smb_serno) {
        try {

            if (!Commontools::CheckArrayValue($arraydata, "smb_sd_id") || !Commontools::CheckArrayValue($arraydata, "smb_md_id") || !Commontools::CheckArrayValue($arraydata, "smb_validity") || !Commontools::CheckArrayValue($arraydata, "smb_activestatus") /*|| !Commontools::CheckArrayValue($arraydata, "smb_sar_id")*/) {
                return false;
            }
            $savedata['smb_sd_id'] = $arraydata['smb_sd_id'];
            $savedata['smb_md_id'] = $arraydata['smb_md_id'];
            $savedata['smb_validity'] = $arraydata['smb_validity'];
            $savedata['smb_activestatus'] = $arraydata['smb_activestatus'];
            //$savedata['smb_sar_id'] = $arraydata['smb_sar_id'];

            if (Commontools::CheckArrayValue($arraydata, 'smb_shoptype')) {
                $savedata['smb_shoptype'] = $arraydata['smb_shoptype'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'smb_releation_id')) {
                $savedata['smb_releation_id'] = $arraydata['smb_releation_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'smb_bindway')) {
                $savedata['smb_bindway'] = $arraydata['smb_bindway'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'smb_bindlevel')) {
                $savedata['smb_bindlevel'] = $arraydata['smb_bindlevel'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'smb_bind_mail')) {
                $savedata['smb_bind_mail'] = $arraydata['smb_bind_mail'];
            } 
            if (Commontools::CheckArrayValue($arraydata, 'smb_mail_sentid')) {
                $savedata['smb_mail_sentid'] = $arraydata['smb_mail_sentid'];
            }    
            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
            }

            if (Commontools::CheckArrayValue($arraydata, "create_user")) {
                $savedata['create_user'] = $arraydata['create_user'];
            } else {
                $savedata['create_user'] = 'webapi';
            }
            if (Commontools::CheckArrayValue($arraydata, "last_update_user")) {
                $savedata['last_update_user'] = $arraydata['last_update_user'];
            } else {
                $savedata['last_update_user'] = 'webapi';
            }

            //新增資料並回傳「自動遞增KEY值」
             $result = DB::table('icr_sdmdbind')->insertGetId($savedata);
             $smb_serno = $result;
              
            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
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

            if (!Commontools::CheckArrayValue($arraydata, 'smb_serno')) {
                return false;
            }

            $savedata['smb_serno'] = $arraydata['smb_serno'];

            if (Commontools::CheckArrayValue($arraydata, "smb_sd_id")) {
                $savedata['smb_sd_id'] = $arraydata['smb_sd_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, "smb_md_id")) {
                $savedata['smb_md_id'] = $arraydata['smb_md_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, "smb_validity")) {
                $savedata['smb_validity'] = $arraydata['smb_validity'];
            }
            if (Commontools::CheckArrayValue($arraydata, "smb_activestatus")) {
                $savedata['smb_activestatus'] = $arraydata['smb_activestatus'];
            }
            if (Commontools::CheckArrayValue($arraydata, "smb_bindlevel")) {
                $savedata['smb_bindlevel'] = $arraydata['smb_bindlevel'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'smb_shoptype')) {
                $savedata['smb_shoptype'] = $arraydata['smb_shoptype'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'smb_releation_id')) {
                $savedata['smb_releation_id'] = $arraydata['smb_releation_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'smb_bindway')) {
                $savedata['smb_bindway'] = $arraydata['smb_bindway'];
            }    
            if (Commontools::CheckArrayValue($arraydata, 'smb_bind_mail')) {
                $savedata['smb_bind_mail'] = $arraydata['smb_bind_mail'];
            } 
            if (Commontools::CheckArrayValue($arraydata, 'smb_mail_sentid')) {
                $savedata['smb_mail_sentid'] = $arraydata['smb_mail_sentid'];
            }   
            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            }

            $savedata['last_update_user'] = 'webapi';
            $savedata['last_update_date'] = date('Y-m-d H:i:s');

            DB::table('icr_sdmdbind')
                    ->where('smb_serno', $savedata['smb_serno'])
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }
    
    /**
     * 修改資料
     * @param array $arraydata 要更新的資料
     * @return boolean
     */
    public static function UpdateDataBySdId_MdId(array $arraydata) {

        try {

            if (!Commontools::CheckArrayValue($arraydata, 'smb_sd_id') 
            || !Commontools::CheckArrayValue($arraydata,  'smb_md_id') ) {
                return false;
            }

            $savedata['smb_sd_id'] = $arraydata['smb_sd_id'];
            $savedata['smb_md_id'] = $arraydata['smb_md_id'];
            
            if (Commontools::CheckArrayValue($arraydata, "smb_validity")) {
                $savedata['smb_validity'] = $arraydata['smb_validity'];
            }
            if (Commontools::CheckArrayValue($arraydata, "smb_activestatus")) {
                $savedata['smb_activestatus'] = $arraydata['smb_activestatus'];
            }
            if (Commontools::CheckArrayValue($arraydata, "smb_bindlevel")) {
                $savedata['smb_bindlevel'] = $arraydata['smb_bindlevel'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'smb_shoptype')) {
                $savedata['smb_shoptype'] = $arraydata['smb_shoptype'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'smb_releation_id')) {
                $savedata['smb_releation_id'] = $arraydata['smb_releation_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'smb_bindway')) {
                $savedata['smb_bindway'] = $arraydata['smb_bindway'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'smb_bind_mail')) {
                $savedata['smb_bind_mail'] = $arraydata['smb_bind_mail'];
            } 
            if (Commontools::CheckArrayValue($arraydata, 'smb_mail_sentid')) {
                $savedata['smb_mail_sentid'] = $arraydata['smb_mail_sentid'];
            }    
            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            }

            $savedata['last_update_user'] = 'webapi';
            $savedata['last_update_date'] = date('Y-m-d H:i:s');

            DB::table('icr_sdmdbind')
                    ->where('smb_sd_id', $savedata['smb_sd_id'])
                    ->where('smb_md_id', $savedata['smb_md_id'])
                    ->where('smb_bindlevel', $savedata['smb_bindlevel'])
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }
    
    
     /**
     * 修改資料
     * @param array $arraydata 要更新的資料
     * @return boolean
     */
    public static function UpdateDataBySdId_MdId_SentID(array $arraydata) {

        try {

            if (!Commontools::CheckArrayValue($arraydata, 'smb_sd_id') 
            || !Commontools::CheckArrayValue($arraydata,  'smb_md_id') 
            || !Commontools::CheckArrayValue($arraydata,  'smb_mail_sentid')) {
                return false;
            }

            $savedata['smb_sd_id'] = $arraydata['smb_sd_id'];
            $savedata['smb_md_id'] = $arraydata['smb_md_id'];
            $savedata['smb_mail_sentid'] = $arraydata['smb_mail_sentid'];
             
            if (Commontools::CheckArrayValue($arraydata, "smb_validity")) {
                $savedata['smb_validity'] = $arraydata['smb_validity'];
            }
            if (Commontools::CheckArrayValue($arraydata, "smb_activestatus")) {
                $savedata['smb_activestatus'] = $arraydata['smb_activestatus'];
            }
            if (Commontools::CheckArrayValue($arraydata, "smb_bindlevel")) {
                $savedata['smb_bindlevel'] = $arraydata['smb_bindlevel'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'smb_shoptype')) {
                $savedata['smb_shoptype'] = $arraydata['smb_shoptype'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'smb_releation_id')) {
                $savedata['smb_releation_id'] = $arraydata['smb_releation_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'smb_bindway')) {
                $savedata['smb_bindway'] = $arraydata['smb_bindway'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'smb_bind_mail')) {
                $savedata['smb_bind_mail'] = $arraydata['smb_bind_mail'];
            }    
            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            }

            $savedata['last_update_user'] = 'webapi';
            $savedata['last_update_date'] = date('Y-m-d H:i:s');

            DB::table('icr_sdmdbind')
                    ->where('smb_sd_id', $savedata['smb_sd_id'])
                    ->where('smb_md_id', $savedata['smb_md_id'])
                    ->where('smb_mail_sentid', $savedata['smb_mail_sentid'])
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }

}
