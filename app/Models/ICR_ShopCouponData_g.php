<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class ICR_ShopCouponData_g extends Model {

//
    public $table = 'icr_shopcoupondata_g';
    public $primaryKey = 'scg_id';
    public $timestamps = false;
    public $incrementing = false;

    /** ██████████▍CREATE 建立資料 */

    /**
     * 建立一筆「活動券」資料，並取得該筆資料代碼
     * @param type $modifydata 要異動的資料
     * @param type $scg_id 活動券取用編號
     * @return boolean 執行結果
     */
    public static function InsertGetCouponData($modifydata, &$scg_id) {
        try {

            $savedata['scg_id'] =  ICR_ShopCouponData_g::GetShotUuid();

            if (Commontools::CheckArrayValue($modifydata, "scm_id")) {
                $savedata['scm_id'] = $modifydata['scm_id'];
            }
            if (Commontools::CheckArrayValue($modifydata, "md_id")) {
                $savedata['md_id'] = $modifydata['md_id'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scr_serno")) {
                $savedata['scr_serno'] = $modifydata['scr_serno'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_getdate")) {
                $savedata['scg_getdate'] = $modifydata['scg_getdate'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_usedate")) {
                $savedata['scg_usedate'] = $modifydata['scg_usedate'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_receiver")) {
                $savedata['scg_receiver'] = $modifydata['scg_receiver'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_usestatus")) {
                $savedata['scg_usestatus'] = $modifydata['scg_usestatus'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_reservationstatus")) {
                $savedata['scg_reservationstatus'] = $modifydata['scg_reservationstatus'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_abandomreason")) {
                $savedata['scg_abandomreason'] = $modifydata['scg_abandomreason'];
            }

            if (Commontools::CheckArrayValue($modifydata, "scg_paymentstatus")) {
                $savedata['scg_paymentstatus'] = $modifydata['scg_paymentstatus'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_buyamount")) {
                $savedata['scg_buyamount'] = $modifydata['scg_buyamount'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_totalamount")) {
                $savedata['scg_totalamount'] = $modifydata['scg_totalamount'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_buyermessage")) {
                $savedata['scg_buyermessage'] = $modifydata['scg_buyermessage'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_buyername")) {
                $savedata['scg_buyername'] = $modifydata['scg_buyername'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_identifier")) {
                $savedata['scg_identifier'] = $modifydata['scg_identifier'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_addr")) {
                $savedata['scg_addr'] = $modifydata['scg_addr'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_contact_email")) {
                $savedata['scg_contact_email'] = $modifydata['scg_contact_email'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_contact_phone")) {
                $savedata['scg_contact_phone'] = $modifydata['scg_contact_phone'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_tax_title")) {
                $savedata['scg_tax_title'] = $modifydata['scg_tax_title'];
            }
             if (Commontools::CheckArrayValue($modifydata, "scg_paid_time")) {
                $savedata['scg_paid_time'] = $modifydata['scg_paid_time'];
            }
            if (Commontools::CheckArrayValue($modifydata, "payment_no")) {
                $savedata['payment_no'] = $modifydata['payment_no'];
            }
            
            if (Commontools::CheckArrayValue($modifydata, "scg_gp_subtract_amount")) {
                $savedata['scg_gp_subtract_amount'] = $modifydata['scg_gp_subtract_amount'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_gp_subtract_status")) {
                $savedata['scg_gp_subtract_status'] = $modifydata['scg_gp_subtract_status'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_subtract_price")) {
                $savedata['scg_subtract_price'] = $modifydata['scg_subtract_price'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_subtract_totalamount")) {
                $savedata['scg_subtract_totalamount'] = $modifydata['scg_subtract_totalamount'];
            }
            if (Commontools::CheckArrayValue($modifydata, "respone_payment_json")) {
                $savedata['respone_payment_json'] = $modifydata['respone_payment_json'];
            }
            if (Commontools::CheckArrayValue($modifydata, "reservation_times")) {
                $savedata['reservation_times'] = $modifydata['reservation_times'];
            }
            if (Commontools::CheckArrayValue($modifydata, "sharer_id")) {
                $savedata['sharer_id'] = $modifydata['sharer_id'];
            }
            if (Commontools::CheckArrayValue($modifydata, "scg_receipt_status")) {
                $savedata['scg_receipt_status'] = $modifydata['scg_receipt_status'];
            } else {
                $savedata['scg_receipt_status'] = '0';
            }
            
            if (Commontools::CheckArrayValue($modifydata, "scg_replystatus")) {
                $savedata['scg_replystatus'] = $modifydata['scg_replystatus'];
            } else {
                $savedata['scg_replystatus'] = '99';
            }
            
             if (Commontools::CheckArrayValue($modifydata, "moc_id")) {
                $savedata['moc_id'] = $modifydata['moc_id'];
            }
            

            $savedata['isflag'] = '1';
            $savedata['create_user'] = 'webapi';
            $savedata['last_update_user'] = 'webapi';
            $savedata['create_date'] = date('Y-m-d H:i:s');
            if (! DB::table('icr_shopcoupondata_g')->insert($savedata)) {
                $scg_id = null;
                return false;
            }

            $scg_id = $savedata['scg_id'];
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            $scg_id = null;
            return false;
        }
    }

    /** ██████████▍READ 讀取資料 */

    /**
     * 依「$scm_id」取得資料
     * @param type $scg_id
     * @return type
     */
    public static function GetData($scg_id) {
        try {
            if ($scg_id == null || strlen($scg_id) == 0) {
                return null;
            }

            $results = ICR_ShopCouponData_g::where('icr_shopcoupondata_g.isflag', '=', '1')
                            ->leftJoin('icr_shopcoupondata_m', 'icr_shopcoupondata_g.scm_id', '=', 'icr_shopcoupondata_m.scm_id')
                            ->leftJoin('icr_shopdata','icr_shopcoupondata_m.sd_id','=','icr_shopdata.sd_id')
                            ->where('icr_shopcoupondata_g.scg_id', '=', $scg_id)
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }


    public static function GetShotUuid() {
        try {
            $query = "SELECT ";
            $query = $query . "UUID_SHORT() as scg_id  ";
            $query = $query . "FROM icr_shopcoupondata_g limit 1 offset 0 ";
            $queryData = DB::connection('mysql')->select($query);
            $checkData = ICR_ShopCouponData_g::where('icr_shopcoupondata_g.scg_id', '=',  $queryData[0]->scg_id)->get()->toArray();
            if (count($checkData) > 0) {
                ICR_ShopCouponData_g::GetShotUuid();
            }
            return  $queryData[0]->scg_id;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

      /**
     * 依「$scg_id」取得資料
     * @param type $scg_id
     * @return type
     */
    public static function GetLogisticsDataByScgId($scg_id) {
        try {
            if ($scg_id == null || strlen($scg_id) == 0) {
                return null;
            }

            $query = ICR_ShopCouponData_g::where('icr_shopcoupondata_g.isflag', '=', '1')
                            ->leftjoin('icr_shopcoupondata_m', 'icr_shopcoupondata_g.scm_id', '=', 'icr_shopcoupondata_m.scm_id')
                            ->leftjoin('icr_shopcoupondata_logistics','icr_shopcoupondata_g.scg_id', '=','icr_shopcoupondata_logistics.scg_id')
                            ->leftJoin('icr_shopdata','icr_shopcoupondata_m.sd_id','=','icr_shopdata.sd_id')
                            ->where('icr_shopcoupondata_g.scg_id', '=', $scg_id);
            $results = $query->select(
                                       'icr_shopcoupondata_g.*',
                                       'icr_shopcoupondata_m.*',
                                       'icr_shopcoupondata_logistics.*'
                                       ,'icr_shopdata.sd_shopname'
                                       )
                              ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }


     /**
     * 依「$scm_id」取得資料
     * @param type $scg_id
     * @return type
     */
    public static function GetData_Event($scg_id) {
        try {
            if ($scg_id == null || strlen($scg_id) == 0) {
                return null;
            }

            $query = ICR_ShopCouponData_g::where('icr_shopcoupondata_g.isflag', '=', '1')
                            ->leftJoin('icr_shopcoupondata_m', 'icr_shopcoupondata_g.scm_id', '=', 'icr_shopcoupondata_m.scm_id')
                            ->leftJoin('icr_shopdata','icr_shopcoupondata_m.sd_id','=','icr_shopdata.sd_id')
                            ->leftJoin('icr_shopquestionnaire_a','icr_shopquestionnaire_a.event_id',"=","icr_shopcoupondata_g.scg_id")
                            ->where('icr_shopcoupondata_g.scg_id', '=', $scg_id) ;
            $results = $query ->select(
                                       'icr_shopcoupondata_g.*',
                                       'icr_shopcoupondata_m.*',
                                       'icr_shopdata.*',
                                       'icr_shopquestionnaire_a.*',
                                       'icr_shopcoupondata_g.md_id as MD_ID',
                                       'icr_shopdata.sd_id')
                              ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
     /**
     * 依「$scm_id」取得資料
     * @param type $scg_id
     * @return type
     */
    public static function GetData_Read($scg_id) {
        try {
            if ($scg_id == null || strlen($scg_id) == 0) {
                return null;
            }

            $query = ICR_ShopCouponData_g::where('icr_shopcoupondata_g.isflag', '=', '1')
                            ->leftJoin('icr_shopcoupondata_m', 'icr_shopcoupondata_g.scm_id', '=', 'icr_shopcoupondata_m.scm_id')
                            ->leftJoin('icr_shopdata','icr_shopcoupondata_m.sd_id','=','icr_shopdata.sd_id')
                            ->where('icr_shopcoupondata_g.scg_id', '=', $scg_id);
            $results = $query -> select(
                                        'icr_shopcoupondata_g.*',
                                        'icr_shopcoupondata_m.*',
                                        'icr_shopdata.*',
                                        'icr_shopcoupondata_g.md_id as MD_ID')    
                                        ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 依「$md_id」取得資料
     * @param type $scg_id
     * @return type
     */
    public static function GetData_ByMD_ID($md_id, $lastupdate) {
        try {
            if ($md_id == null || strlen($md_id) == 0) {
                return null;
            }

            $query = ICR_ShopCouponData_g::
                    leftJoin('icr_shopcoupondata_r', 'icr_shopcoupondata_g.scr_serno', '=', 'icr_shopcoupondata_r.scr_serno')
                    ->where('icr_shopcoupondata_g.isflag', '=', '1')
                    //->where('icr_shopcoupondata_r.isflag', '=', '1')
                    ->where('icr_shopcoupondata_g.md_id', '=', $md_id);

            if (!is_null($lastupdate) && strlen($lastupdate) != 0) {
                $query->where('icr_shopcoupondata_g.last_update_date', '>', $lastupdate);
            }

            $results = $query->select('icr_shopcoupondata_g.scg_id'
                            , 'icr_shopcoupondata_g.scm_id'
                            , 'icr_shopcoupondata_r.scr_rvdate', 'icr_shopcoupondata_r.scr_rvtime'
                            , 'icr_shopcoupondata_g.scg_getdate'
                            , 'icr_shopcoupondata_g.scg_usedate'
                            , 'icr_shopcoupondata_g.scg_usestatus'
                            , 'icr_shopcoupondata_g.scg_reservationstatus'
                            , 'icr_shopcoupondata_g.scg_buyamount'
                            , 'icr_shopcoupondata_g.scg_subtract_totalamount'
                            , 'icr_shopcoupondata_g.last_update_date'
                            , 'icr_shopcoupondata_g.create_date')
                            ->orderBy('icr_shopcoupondata_g.create_date','desc')
                            ->distinct()->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    public static function GetData_BySCMID_SCGID($scm_id, $scg_id) {
        try {
            if ($scm_id == null || strlen($scm_id) == 0 || $scg_id == null || strlen($scg_id) == 0) {
                return null;
            }

            $query = ICR_ShopCouponData_g::where('icr_shopcoupondata_g.isflag', '=', '1')
                            ->where('icr_shopcoupondata_g.scm_id', '=', $scm_id)
                            ->where('icr_shopcoupondata_g.scg_id', '=', $scg_id)->get()->toArray();

            return $query;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    public static function GetData_IcrShopCouponDataM($scm_id, $scg_id) {
        try {
            if ($scm_id == null || strlen($scm_id) == 0 || $scg_id == null || strlen($scg_id) == 0) {
                return null;
            }

            $query = ICR_ShopCouponData_g::where('icr_shopcoupondata_g.isflag', '=', '1')
                            ->leftJoin('icr_shopcoupondata_m', 'icr_shopcoupondata_g.scm_id', '=', 'icr_shopcoupondata_m.scm_id')
                            ->where('icr_shopcoupondata_g.scm_id', '=', $scm_id)
                            ->where('icr_shopcoupondata_g.scg_id','=',$scg_id)
                            ->get()->toArray();
            return $query;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
     public static function GetData_BySCR_SERNO($array_scr_serno) {
        try {
            if ($array_scr_serno == null ) {
                return null;
            }
            $query = ICR_ShopCouponData_g::where('icr_shopcoupondata_g.isflag', '=', '1')
                            ->where('scg_usestatus', '5')
                            ->whereIn('scr_serno', $array_scr_serno)
                            ->get()->toArray();

            return $query;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }


    /** ██████████▍UPDATE 更新資料 */
    public static function UpdateData(array $arraydata) {

        try {
            if (
                    !Commontools::CheckArrayValue($arraydata, "scg_id")
            ) {
                return false;
            }

            $savedata['scg_id'] = $arraydata['scg_id'];


            if (Commontools::CheckArrayValue($arraydata, "scm_id")) {
                $savedata['scm_id'] = $arraydata['scm_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, "md_id")) {
                $savedata['md_id'] = $arraydata['md_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, "scr_serno")) {
                $savedata['scr_serno'] = $arraydata['scr_serno'];
            }
            if (Commontools::CheckArrayValue($arraydata, "scg_getdate")) {
                $savedata['scg_getdate'] = $arraydata['scg_getdate'];
            }
            if (Commontools::CheckArrayValue($arraydata, "scg_usedate")) {
                $savedata['scg_usedate'] = $arraydata['scg_usedate'];
            }
            if (Commontools::CheckArrayValue($arraydata, "scg_receiver")) {
                $savedata['scg_receiver'] = $arraydata['scg_receiver'];
            }
            if (Commontools::CheckArrayValue($arraydata, "scg_usestatus")) {
                $savedata['scg_usestatus'] = $arraydata['scg_usestatus'];
            }
             if (Commontools::CheckArrayValue($arraydata, "scg_settlement_type")) {
                $savedata['scg_settlement_type'] = $arraydata['scg_settlement_type'];
            }
            if (Commontools::CheckArrayValue($arraydata, "scg_reservationstatus")) {
                $savedata['scg_reservationstatus'] = $arraydata['scg_reservationstatus'];
            }
            if (Commontools::CheckArrayValue($arraydata, "scg_abandomreason")) {
                $savedata['scg_abandomreason'] = $arraydata['scg_abandomreason'];
            }
             if (Commontools::CheckArrayValue($arraydata, "scg_paymentstatus")) {
                $savedata['scg_paymentstatus'] = $arraydata['scg_paymentstatus'];
            }
             if (Commontools::CheckArrayValue($arraydata, "scg_buyermessage")) {
                $savedata['scg_buyermessage'] = $arraydata['scg_buyermessage'];
            }
             if (Commontools::CheckArrayValue($arraydata, "md_cname")) {
                $savedata['md_cname'] = $arraydata['md_cname'];
            }
             if (Commontools::CheckArrayValue($arraydata, "scg_buyamount")) {
                $savedata['scg_buyamount'] = $arraydata['scg_buyamount'];
            }
            if (Commontools::CheckArrayValue($arraydata, "scg_paid_time")) {
                $savedata['scg_paid_time'] = $arraydata['scg_paid_time'];
            }
            if (Commontools::CheckArrayValue($arraydata, "payment_no")) {
                $savedata['payment_no'] = $arraydata['payment_no'];
            }
            if (Commontools::CheckArrayValue($arraydata, "respone_payment_json")) {
                $savedata['respone_payment_json'] = $arraydata['respone_payment_json'];
            }
            if (Commontools::CheckArrayValue($arraydata, "reservation_times")) {
                $savedata['reservation_times'] = $arraydata['reservation_times'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sharer_id")) {
                $savedata['sharer_id'] = $arraydata['sharer_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, "scg_gp_subtract_amount")) {
                $savedata['scg_gp_subtract_amount'] = $arraydata['scg_gp_subtract_amount'];
            }
            if (Commontools::CheckArrayValue($arraydata, "scg_gp_subtract_status")) {
                $savedata['scg_gp_subtract_status'] = $arraydata['scg_gp_subtract_status'];
            }
            if (Commontools::CheckArrayValue($arraydata, "scg_subtract_price")) {
                $savedata['scg_subtract_price'] = $arraydata['scg_subtract_price'];
            }
            if (Commontools::CheckArrayValue($arraydata, "scg_subtract_totalamount")) {
                $savedata['scg_subtract_totalamount'] = $arraydata['scg_subtract_totalamount'];
            }
             if (Commontools::CheckArrayValue($arraydata, "scg_receipt_status")) {
                $savedata['scg_receipt_status'] = $arraydata['scg_receipt_status'];
            }
             if (Commontools::CheckArrayValue($arraydata, "scg_replystatus")) {
                $savedata['scg_replystatus'] = $arraydata['scg_replystatus'];
            } 
            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
            }

            $savedata['last_update_user'] = 'pm_api';
            $savedata['last_update_date'] = date('Y-m-d H:i:s');

            DB::table('icr_shopcoupondata_g')
                    ->where('scg_id', '=', $savedata['scg_id'])
                    ->update($savedata);

            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 更新預約時段
     * @param type $scg_id
     * @param type $scr_serno
     * @return booleans
     */
    public static function Update_SCR_Serno($scg_id, $scr_serno) {
        try {
            if (is_null($scg_id) || strlen($scg_id) == 0) {
                return false;
            }
            $arraydata['scr_serno'] = $scr_serno;
           
            $arraydata['last_update_user'] = 'webapi';

            DB::table('icr_shopcoupondata_g')
                    ->where('scg_id', $scg_id)
                    ->update($arraydata);

            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /** ██████████▍DELETE 刪除資料 */
    /** ██████████▍CHECK 檢查資料 */
    /** ██████████▍QUERY 查詢資料 */

    /** 查詢 活動券已發送張數
     * @param type $scm_id
     * @return type
     */
    public static function QuerySendCountBy_SCM_ID($scm_id) {
        try {
            if ($scm_id == null || strlen($scm_id) == 0) {
                return null;
            }

            $results = ICR_ShopCouponData_g::where('isflag', '=', '1')
                            ->where('scm_id', '=', $scm_id)
                            ->get()->toArray();

            return count($results);
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return 0;
        }
    }

    /**
     * 查詢 會員取用次數
     * @param type $scm_id
     * @param type $md_id
     * @return int
     */
    public static function QueryMemberGetCount($scm_id, $md_id) {
        try {
            if ($scm_id == null || strlen($scm_id) == 0) {
                return null;
            }

            $results = ICR_ShopCouponData_g::where('isflag', '=', '1')
                            ->where('scm_id', '=', $scm_id)
                            ->where('md_id', '=', $md_id)
                            ->get()->toArray();

            return count($results);
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return 0;
        }
    }

    /**
     * 取得已領取未使用的活動券
     * @param type $scm_id
     * @return int
     */
    public static function QueryUnUsedCount($scm_id) {
        try {
            if ($scm_id == null || strlen($scm_id) == 0) {
                return null;
            }

            $results = ICR_ShopCouponData_g::where('isflag', '=', '1')
                            ->where('scm_id', '=', $scm_id)
                            ->where('scg_usestatus', '=', '1')
                            ->get()->toArray();

            return count($results);
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return 0;
        }
    }

     public static function QueryGetCount($scm_id) {
        try {
            if ($scm_id == null || strlen($scm_id) == 0) {
                return null;
            }

            $results = ICR_ShopCouponData_g::where('isflag', '=', '1')
                            ->where('scm_id', '=', $scm_id)
                            ->get()->toArray();

            return count($results);
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return 0;
        }
    }

     public static function GetData_CouponDataM_Logistics($arraydata, &$count) {
        try {
            $query = ICR_ShopCouponData_g::where('icr_shopcoupondata_g.isflag', '=', '1')
                            ->join('icr_shopcoupondata_m', function($query) {
                                      $query->on('icr_shopcoupondata_g.scm_id', '=', 'icr_shopcoupondata_m.scm_id');
                             })
                            ->leftjoin('icr_shopcoupondata_logistics', function($query) {
                                      $query->on('icr_shopcoupondata_logistics.scg_id', '=', 'icr_shopcoupondata_g.scg_id');
                             })->leftjoin('icr_shopdata', function($query) {
                                      $query->on('icr_shopcoupondata_m.sd_id', '=', 'icr_shopdata.sd_id');
                             })->where('icr_shopcoupondata_m.sd_id','=',$arraydata['sd_id']);


                            if ( !is_null($arraydata['scm_id']) && mb_strlen($arraydata['scm_id']) !=0 ) {
                                 $query->where('icr_shopcoupondata_m.scm_id', '=', $arraydata['scm_id']);
                            }
                            if ( !is_null($arraydata['scm_producttype']) && mb_strlen($arraydata['scm_producttype']) !=0 ) {
                                 $query->where('icr_shopcoupondata_m.scm_producttype', '=', $arraydata['scm_producttype']);
                            }
                            if ( !is_null($arraydata['create_date_start'])  && !is_null($arraydata['create_date_end']) && mb_strlen($arraydata['create_date_start']) !=0 && mb_strlen($arraydata['create_date_end'])!=0 ) {
                                $query->whereBetween('icr_shopcoupondata_logistics.create_date', [ $arraydata['create_date_start'], $arraydata['create_date_end'] ]);
                            }
                            if ( !is_null($arraydata['scg_usestatus']) && mb_strlen($arraydata['scg_usestatus']) !=0 ) {
                                $query->where('icr_shopcoupondata_g.scg_usestatus', '=', $arraydata['scg_usestatus']);
                            }
                             if ( !is_null($arraydata['scm_id']) && mb_strlen($arraydata['scm_id']) !=0 ) {
                                $query ->where('icr_shopcoupondata_g.scm_id', '=', $arraydata['scm_id']);
                            }
                            if ($arraydata['scm_producttype'] == 2 ) {
                                $query->orderBy('logistics_create_date', 'desc');
                            }
                            if($arraydata['scm_producttype'] == 1 ) {
                                $query->orderBy('scg_create_date', 'desc');
                            }
                            
                            $results = $query->select('icr_shopcoupondata_m.scm_title'
                                                                      ,'icr_shopcoupondata_g.scg_id'
                                                                      ,'icr_shopcoupondata_logistics.scl_receivername'
                                                                      ,'icr_shopcoupondata_g.scg_buyamount'
                                                                      ,'icr_shopcoupondata_logistics.create_date as logistics_create_date'
                                                                      ,'icr_shopcoupondata_g.create_date as scg_create_date'
                                                                      ,'icr_shopcoupondata_logistics.scl_id'
                                                                      ,'icr_shopcoupondata_g.scg_buyername'
                                                                      ,'icr_shopcoupondata_g.scg_usestatus')->get()->toArray();
                            $count = count($results);
                            $results = $query->select('icr_shopcoupondata_m.scm_id'
                                                                      ,'icr_shopcoupondata_m.scm_title'
                                                                      ,'icr_shopcoupondata_g.scg_id'
                                                                      ,'icr_shopcoupondata_logistics.scl_receivername'
                                                                      ,'icr_shopcoupondata_g.scg_buyamount'
                                                                      ,'icr_shopcoupondata_logistics.create_date as logistics_create_date'
                                                                      ,'icr_shopcoupondata_g.create_date as scg_create_date'
                                                                      ,'icr_shopcoupondata_logistics.scl_id'
                                                                      ,'icr_shopcoupondata_g.scg_buyername'
                                                                      ,'icr_shopcoupondata_g.scg_usestatus'
                                                                      ,'icr_shopdata.sd_contact_tel')->skip($arraydata['data_pagination'])->take($arraydata['queryamount']) ->get()->toArray();
            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }


       public static function GetData_CouponDataM_LogisticsDetial($scg_id) {
        try {
            $query = ICR_ShopCouponData_g::where('icr_shopcoupondata_g.isflag', '=', '1')
                            ->join('icr_shopcoupondata_m', function($query) {
                                      $query->on('icr_shopcoupondata_g.scm_id', '=', 'icr_shopcoupondata_m.scm_id');
                             })
                            ->leftjoin('icr_shopcoupondata_logistics', function($query) {
                                      $query->on('icr_shopcoupondata_g.scg_id', '=', 'icr_shopcoupondata_logistics.scg_id');
                             })
                            ->leftjoin('icr_shopcoupondata_r', function($query) {
                                      $query->on('icr_shopcoupondata_g.scr_serno', '=', 'icr_shopcoupondata_r.scr_serno');
                             })
                            ->leftjoin('icr_shopdata', function($query) {
                                      $query->on('icr_shopcoupondata_m.sd_id', '=', 'icr_shopdata.sd_id');
                             })
                            ->where('icr_shopcoupondata_g.scg_id','=',$scg_id);
                            $results = $query->select('icr_shopcoupondata_m.scm_title'
                                                                      ,'icr_shopcoupondata_g.scg_id'
                                                                      ,'icr_shopcoupondata_m.scm_id'
                                                                      ,'icr_shopcoupondata_m.sd_id'
                                                                      ,'icr_shopcoupondata_logistics.scl_id'
                                                                      ,'icr_shopcoupondata_m.scm_price'
                                                                      ,'icr_shopcoupondata_m.scm_mainpic'
                                                                      ,'icr_shopcoupondata_logistics.create_date'
                                                                      ,'icr_shopcoupondata_g.scg_usestatus'
                                                                      ,'icr_shopcoupondata_g.scg_buyamount'
                                                                      ,'icr_shopcoupondata_g.scg_totalamount'
                                                                      ,'icr_shopcoupondata_g.scg_subtract_price'
                                                                      ,'icr_shopcoupondata_g.scg_buyermessage'
                                                                      ,'icr_shopcoupondata_g.scg_buyername'
                                                                      ,'icr_shopcoupondata_g.scg_identifier'
                                                                      ,'icr_shopcoupondata_g.scg_addr'
                                                                      ,'icr_shopcoupondata_g.scg_contact_phone'
                                                                      ,'icr_shopcoupondata_g.scg_contact_email'
                                                                      ,'icr_shopcoupondata_g.scg_tax_title'
                                                                      ,'icr_shopcoupondata_g.create_date as scg_create_date'
                                                                      ,'icr_shopcoupondata_g.scg_paid_time'
                                                                      ,'icr_shopcoupondata_g.scg_usedate'
                                                                      ,'icr_shopcoupondata_g.payment_no'
                                                                      ,'icr_shopcoupondata_g.scg_paymentstatus'
                                                                      ,'icr_shopcoupondata_g.reservation_times'
                                                                      ,'icr_shopcoupondata_g.scg_subtract_totalamount'
                                                                      ,'icr_shopcoupondata_g.respone_payment_json'
                                                                      ,'icr_shopcoupondata_m.scm_producttype'
                                                                      , 'icr_shopcoupondata_m.scm_coupon_providetype'
                                                                      , 'icr_shopcoupondata_m.scm_bonus_payamount'
                                                                      , 'icr_shopcoupondata_m.scm_bonus_giveamount'
                                                                      , 'icr_shopcoupondata_m.scm_reservationtag'
                                                                      ,'icr_shopcoupondata_logistics.scl_deliverstatus'
                                                                      ,'icr_shopcoupondata_logistics.scl_orderprinttime'
                                                                      ,'icr_shopcoupondata_logistics.scl_cargopicktime'
                                                                      ,'icr_shopcoupondata_logistics.scl_senddeliverytime'
                                                                      ,'icr_shopcoupondata_logistics.scl_cargoarrivetime'
                                                                      ,'icr_shopcoupondata_logistics.scl_cargopack_pic'
                                                                      ,'icr_shopcoupondata_logistics.scl_postcode'
                                                                      ,'icr_shopcoupondata_logistics.scl_city'
                                                                      ,'icr_shopcoupondata_logistics.scl_district'
                                                                      ,'icr_shopcoupondata_logistics.scl_receiveaddress'
                                                                      ,'icr_shopcoupondata_logistics.scl_tracenum'
                                                                      ,'icr_shopdata.sd_shopname'
                                                                      ,'icr_shopdata.sd_shoptel'
                                                                      ,'icr_shopdata.sd_salescode'
                                                                      ,DB::raw('concat(icr_shopcoupondata_r.scr_rvdate," ",icr_shopcoupondata_r.scr_rvtime) as scr_rvdate_time')
                                                                      )->get()->toArray();
            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }


     public static function getDataByScgId($scg_id) {
      try {
            $query = ICR_ShopCouponData_g::where('icr_shopcoupondata_g.isflag', '=', '1')
                                                ->where('icr_shopcoupondata_g.scg_id',$scg_id)
                                                ->leftJoin('icr_shopcoupondata_m', 'icr_shopcoupondata_g.scm_id', '=', 'icr_shopcoupondata_m.scm_id')
                                                ->leftJoin('icr_sdmdbind', function($query) {
                                                      $query->on('icr_shopcoupondata_m.sd_id', '=', 'icr_sdmdbind.smb_sd_id')
                                                                 ->where('icr_sdmdbind.smb_activestatus', '=', 1);
                                                })
                                                ->leftJoin('icr_shopdata', 'icr_shopcoupondata_m.sd_id', '=', 'icr_shopdata.sd_id');
                                                
              $result =  $query->select(
                                            'icr_shopcoupondata_g.*',
                                            'icr_sdmdbind.smb_md_id',
                                            'icr_shopcoupondata_m.scm_price',
                                            'icr_shopcoupondata_m.scm_title',
                                            'icr_shopdata.sd_shopname')->get()->toArray();
             return $result;
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return null;
      }
    }

     public static function getMdidByScgid($scg_id) {
       try {
            $query = ICR_ShopCouponData_g::where('icr_shopcoupondata_g.scg_id', '=' ,$scg_id)
                                                //->leftjoin('icr_shopcoupondata_logistics', 'icr_shopcoupondata_g.scg_id', '=', 'icr_shopcoupondata_logistics.scg_id')
                                                ->join('icr_shopcoupondata_m', 'icr_shopcoupondata_g.scm_id', '=', 'icr_shopcoupondata_m.scm_id')
                                                ->join('icr_sdmdbind', function($query) {
                                                      $query->on('icr_shopcoupondata_m.sd_id', '=', 'icr_sdmdbind.smb_sd_id')
                                                                 ->where('icr_sdmdbind.smb_activestatus', '=', 1)
                                                                 ->where('icr_sdmdbind.isflag', '=', 1)
                                                                 ->where('icr_sdmdbind.smb_bindlevel', '=', 0);
                                                })
                                                ->Join('icr_shopdata', 'icr_shopcoupondata_m.sd_id', '=', 'icr_shopdata.sd_id');
              $result =  $query->select(
                                            'icr_shopcoupondata_g.md_id',
                                            'icr_shopcoupondata_g.scg_id',
                                            'icr_sdmdbind.smb_md_id'
                                            ,'icr_shopcoupondata_m.scm_price'
                                            ,'icr_shopcoupondata_g.scg_buyamount'
                                            ,'icr_shopcoupondata_g.scg_usestatus'
                                            ,'icr_shopcoupondata_g.scg_replystatus'
                                            ,'icr_shopcoupondata_m.scm_title'
                                            ,'icr_shopcoupondata_m.scm_producttype'
                                            ,'icr_shopcoupondata_m.scm_coupon_providetype'
                                            , 'icr_shopcoupondata_m.scm_bonus_payamount'
                                            , 'icr_shopcoupondata_m.scm_bonus_giveamount'
                                            ,'icr_shopcoupondata_m.scm_enddate'
                                            ,'icr_shopcoupondata_g.scg_totalamount'
                                            ,'icr_shopcoupondata_g.scg_subtract_totalamount'
                                            ,'icr_shopdata.sd_shopname'
                                            ,'icr_shopdata.sd_id'
                                            ,'icr_shopcoupondata_m.scm_bonus_giveafteruse'
                                            ,'icr_shopcoupondata_m.scm_bonus_giveamount'
                                            ,'icr_shopdata.sd_shoptel')->distinct()->get()->toArray();
             return $result;
       } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return null;
       }
     }
     
     
     public static function getReplyStatusDataBySdId($sd_id) {
      try {
            $result = ICR_ShopCouponData_g::where('icr_shopcoupondata_g.isflag', '=', '1')
                                                ->where('icr_shopcoupondata_g.scg_replystatus', '=', '0')
                                               ->where('icr_shopcoupondata_g.scg_usestatus', '=', '5')
                                                ->whereNotNull('icr_shopcoupondata_g.scr_serno')
                                                ->leftJoin('icr_shopcoupondata_r', function($query) {
                                                      $query->on('icr_shopcoupondata_g.scr_serno', '=', 'icr_shopcoupondata_r.scr_serno')
                                                                 ->where('icr_shopcoupondata_r.scr_rvdate', '>=', "NOW()");
                                                })
                                                ->leftJoin('iscarmemberdata', 'icr_shopcoupondata_g.md_id', '=', 'iscarmemberdata.md_id')
                                                ->leftJoin('icr_shopcoupondata_m', function($query) use($sd_id) {
                                                      $query->on('icr_shopcoupondata_g.scm_id', '=', 'icr_shopcoupondata_m.scm_id')
                                                                 ->where('icr_shopcoupondata_m.sd_id', '=',$sd_id );
                                                })
                                                ->select(
                                                         'icr_shopcoupondata_m.scm_id' 
                                                        , 'icr_shopcoupondata_g.scg_id' 
                                                        , 'iscarmemberdata.md_id' 
                                                       ,'icr_shopcoupondata_r.scr_rvdate'
                                                       , 'icr_shopcoupondata_r.scr_rvtime' 
                                                       , 'icr_shopcoupondata_m.scm_title' 
                                                       , 'iscarmemberdata.md_cname' 
                                                       , 'iscarmemberdata.ssd_picturepath' 
                                                       , 'icr_shopcoupondata_g.scg_usestatus' 
                                                       , 'icr_shopcoupondata_g.scg_reservationstatus'
                                                        ,'icr_shopcoupondata_g.scg_buyermessage'
                                                        ,'icr_shopcoupondata_g.last_update_date')
                                                ->orderby('icr_shopcoupondata_r.scr_rvdate', 'asc')->get()->toArray();
                                  // \App\Models\ErrorLog::InsertLog($result);
             return $result;
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return null;
      }
    }
    
    public static function getReplyStatusDateBySdId_Years($sd_id, $year) {
      try {
            $result = ICR_ShopCouponData_g::where('icr_shopcoupondata_g.isflag', '=', '1')
                                               // ->where('icr_shopcoupondata_g.scg_replystatus', '=', '0')
                                               ->where('icr_shopcoupondata_m.sd_id', '=',$sd_id )
                                               ->where('icr_shopcoupondata_g.scg_usestatus', '!=', '1')
                                                ->whereNotNull('icr_shopcoupondata_g.scr_serno')
                                                ->whereNotNull('icr_shopcoupondata_r.scr_rvdate')
                                                ->leftJoin('icr_shopcoupondata_r', function($query) use($year) {
                                                      $query->on('icr_shopcoupondata_g.scr_serno', '=', 'icr_shopcoupondata_r.scr_serno')
                                                                 ->whereRaw(" YEAR(icr_shopcoupondata_r.scr_rvdate) = '$year' ");
                                                })
                                                ->leftJoin('icr_shopcoupondata_m', 'icr_shopcoupondata_g.scm_id', '=', 'icr_shopcoupondata_m.scm_id')
                                                ->select(
                                                         'icr_shopcoupondata_r.scr_rvdate'
                                                     )
                                                ->orderby('icr_shopcoupondata_r.scr_rvdate', 'asc')->distinct()->get()->toArray();
                                  // \App\Models\ErrorLog::InsertLog($result);
             return $result;
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return null;
      }
    }
    
     public static function getNeedShopSettleMentDataBySdId($sd_id, $start_day, $end_day) {
      try {
            $result = ICR_ShopCouponData_g::where('icr_shopcoupondata_g.isflag', '=', '1')
                                               ->where('icr_shopcoupondata_g.scg_usestatus', '=', '2')
                                              ->where('icr_shopcoupondata_g.scg_settlement_type', '=', '0')
                                               ->whereDate('scg_usedate', '>=', $start_day)
                                               ->whereDate('scg_usedate', '<=', $end_day)
                                               ->leftJoin('icr_shopcoupondata_r', function($query) {
                                                      $query->on('icr_shopcoupondata_g.scr_serno', '=', 'icr_shopcoupondata_r.scr_serno');
                                                })
                                                ->leftJoin('icr_shopcoupondata_m', function($query) use($sd_id) {
                                                      $query->on('icr_shopcoupondata_g.scm_id', '=', 'icr_shopcoupondata_m.scm_id')
                                                                 ->where('icr_shopcoupondata_m.sd_id', '=',$sd_id );
                                                })
                                                ->get()->toArray();
             return $result;
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return null;
      }
    }


}
