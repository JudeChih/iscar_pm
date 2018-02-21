<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家基本資料
 */
class ICR_ShopCouponData_m extends Model {

//
    public $table = 'icr_shopcoupondata_m';
    public $primaryKey = 'scm_id';
    public $timestamps = false;
    public $incrementing = false;

    /** ██████████▍CREATE 建立資料 */
    public static function InsertData($modifydata, &$scm_id) {
        try {

            $savedata['scm_id'] = \App\library\Commontools::NewGUIDWithoutDash();

            if (Commontools::CheckArrayValue($modifydata, 'sd_id')) {
                $savedata['sd_id'] = $modifydata['sd_id'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_title')) {
                $savedata['scm_title'] = $modifydata['scm_title'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_fulldescript')) {
                $savedata['scm_fulldescript'] = $modifydata['scm_fulldescript'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_category')) {
                $savedata['scm_category'] = $modifydata['scm_category'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_mainpic')) {
                $savedata['scm_mainpic'] = $modifydata['scm_mainpic'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_activepics')) {
                $savedata['scm_activepics'] = $modifydata['scm_activepics'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_price')) {
                $savedata['scm_price'] = $modifydata['scm_price'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_startdate')) {
                $savedata['scm_startdate'] = $modifydata['scm_startdate'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_enddate')) {
                $savedata['scm_enddate'] = $modifydata['scm_enddate'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_reservationtag')) {
                $savedata['scm_reservationtag'] = $modifydata['scm_reservationtag'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_reservationfulltag')) {
                $savedata['scm_reservationfulltag'] = $modifydata['scm_reservationfulltag'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_dailystart')) {
                $savedata['scm_dailystart'] = $modifydata['scm_dailystart'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_dailyend')) {
                $savedata['scm_dailyend'] = $modifydata['scm_dailyend'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_workhour')) {
                $savedata['scm_workhour'] = $modifydata['scm_workhour'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_preparehour')) {
                $savedata['scm_preparehour'] = $modifydata['scm_preparehour'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_includeweekend')) {
                $savedata['scm_includeweekend'] = $modifydata['scm_includeweekend'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_reservationavailable')) {
                $savedata['scm_reservationavailable'] = $modifydata['scm_reservationavailable'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_poststatus')) {
                $savedata['scm_poststatus'] = $modifydata['scm_poststatus'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_member_limit')) {
                $savedata['scm_member_limit'] = $modifydata['scm_member_limit'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_balanceno')) {
                $savedata['scm_balanceno'] = $modifydata['scm_balanceno'];
            }
            
            if (Commontools::CheckArrayValue($modifydata, 'scm_producttype')) {
                $savedata['scm_producttype'] = $modifydata['scm_producttype'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_advancedescribe')) {
                $savedata['scm_advancedescribe'] = $modifydata['scm_advancedescribe'];
            }
            
            if (Commontools::CheckArrayValue($modifydata, 'scm_coupon_providetype')) {
                $savedata['scm_coupon_providetype'] = $modifydata['scm_coupon_providetype'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_bonus_giveafteruse')) {
                $savedata['scm_bonus_giveafteruse'] = $modifydata['scm_bonus_giveafteruse'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_bonus_giveamount')) {
                $savedata['scm_bonus_giveamount'] = $modifydata['scm_bonus_giveamount'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_bonus_payamount')) {
                $savedata['scm_bonus_payamount'] = $modifydata['scm_bonus_payamount'];
            }
            $savedata['isflag'] = '1';
            $savedata['create_user'] = 'pm_webapi';
            $savedata['last_update_user'] = 'pm_webapi';

            $result = DB::table('icr_shopcoupondata_m')->insert($savedata);
            if (is_null($result) || strlen($result) == 0) {
                $scg_id = null;
                return false;
            }
            $scm_id = $savedata['scm_id'];
            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            $scg_id = null;
            return false;
        }
    }

    /** ██████████▍READ 讀取資料 */

    /**
     * 依「$scm_id」取得資料
     * @param type $scm_id
     * @return type
     */
    public static function GetData($scm_id) {
        try {

            if ($scm_id == null || strlen($scm_id) == 0) {
                return null;
            }

            $results = ICR_ShopCouponData_m::leftJoin('icr_shopdata', 'icr_shopcoupondata_m.sd_id', '=', 'icr_shopdata.sd_id')
                    ->where('icr_shopcoupondata_m.scm_id', $scm_id)
                    //->where('icr_shopcoupondata_m.scm_poststatus', '1')
                    ->where('icr_shopcoupondata_m.isflag', '1')
                    //F->where('icr_shopdata.isflag', '1')
                    ->orderby('icr_shopcoupondata_m.create_date', 'desc')
                    ->get()
                    ->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    public static function GetDataBy_SD_ID($sd_id) {
        try {
            if ($sd_id == null || strlen($sd_id) == 0) {
                return null;
            }

            $results = ICR_ShopCouponData_m::where('isflag', '=', '1')
                            ->where('sd_id', '=', $sd_id)
                            ->orderby('create_date', 'desc')
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    public static function GetReservationDataBy_SD_ID($isflag ,$sd_id) {
        try {
            if ($sd_id == null || strlen($sd_id) == 0) {
                return null;
            }

            $results = ICR_ShopCouponData_m::where('isflag', '=', $isflag)
                            ->where('sd_id', '=', $sd_id)
                            ->where('scm_poststatus','1')
                            ->where('scm_reservationtag','1')
                            ->select(
                                      'scm_id',
                                      'scm_workhour')
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    public static function GetDataBy_Category($scm_category, $sd_zipcode) {
        try {
            if ($scm_category == null || strlen($scm_category) == 0) {
                return null;
            }

            $query = ICR_ShopCouponData_m::leftJoin('icr_shopdata', 'icr_shopcoupondata_m.sd_id', '=', 'icr_shopdata.sd_id')
                    ->where('icr_shopcoupondata_m.scm_category', $scm_category)
                    ->where('icr_shopcoupondata_m.scm_poststatus', '1')
                    ->where('icr_shopcoupondata_m.isflag', '1')
                    ->where('icr_shopdata.isflag', '1');

            if ($sd_zipcode != null) {
                $query = $query->whereIn('icr_shopdata.sd_zipcode', $sd_zipcode);
            }

            $results = $query
                    ->orderby('icr_shopcoupondata_m.create_date', 'desc')
                    ->get()
                    ->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    public static function GetData_ByMD_ID($md_id, $lastupdate) {
        try {
            if ($md_id == null || strlen($md_id) == 0) {
                return null;
            }

            $query = ICR_ShopCouponData_m::
                    join('icr_shopcoupondata_g', 'icr_shopcoupondata_m.scm_id', '=', 'icr_shopcoupondata_g.scm_id')
                    ->where('icr_shopcoupondata_m.isflag', '=', '1')
                    ->where('icr_shopcoupondata_g.md_id', '=', $md_id);

            if (!is_null($lastupdate) && strlen($lastupdate) != 0) {
                $query->where('icr_shopcoupondata_g.last_update_date', '>', $lastupdate);
            }

            $results = $query->select(
                              'icr_shopcoupondata_m.scm_id'
                            , 'icr_shopcoupondata_m.sd_id'
                            , 'icr_shopcoupondata_m.scm_title'
                            , 'icr_shopcoupondata_m.scm_fulldescript'
                            , 'icr_shopcoupondata_m.scm_category'
                            , 'icr_shopcoupondata_m.scm_mainpic'
                            , 'icr_shopcoupondata_m.scm_activepics'
                            , 'icr_shopcoupondata_m.scm_price'
                            , 'icr_shopcoupondata_m.scm_startdate'
                            , 'icr_shopcoupondata_m.scm_enddate'
                            , 'icr_shopcoupondata_m.scm_reservationtag'
                            , 'icr_shopcoupondata_m.scm_poststatus'
                            , 'icr_shopcoupondata_m.scm_producttype'
                            , 'icr_shopcoupondata_m.scm_coupon_providetype'
                            , 'icr_shopcoupondata_m.scm_bonus_payamount'
                            , 'icr_shopcoupondata_m.scm_bonus_giveamount')->distinct()->get()->toArray();

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

            $query = ICR_ShopCouponData_m::
                            join('icr_shopcoupondata_g', 'icr_shopcoupondata_m.scm_id', '=', 'icr_shopcoupondata_g.scm_id')
                            ->leftjoin('icr_shopcoupondata_r', 'icr_shopcoupondata_g.scr_serno', '=', 'icr_shopcoupondata_r.scr_serno')
                            ->where('icr_shopcoupondata_m.isflag', '=', '1')
                            ->where('icr_shopcoupondata_m.scm_id', '=', $scm_id)
                            ->where('icr_shopcoupondata_g.scg_id', '=', $scg_id)
                            ->distinct()->get()->toArray();

            return $query;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /** ██████████▍UPDATE 更新資料 */

    /**
     *  更新資料
     * @param type $modifydata
     * @return boolean
     */
    public static function UpdateData($modifydata) {
        try {
            if (
                    !Commontools::CheckArrayValue($modifydata, "scm_id")
            ) {
                return false;
            }

            $savedata['scm_id'] = $modifydata['scm_id'];

            if (Commontools::CheckArrayValue($modifydata, 'sd_id')) {
                $savedata['sd_id'] = $modifydata['sd_id'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_title')) {
                $savedata['scm_title'] = $modifydata['scm_title'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_fulldescript')) {
                $savedata['scm_fulldescript'] = $modifydata['scm_fulldescript'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_category')) {
                $savedata['scm_category'] = $modifydata['scm_category'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_mainpic')) {
                $savedata['scm_mainpic'] = $modifydata['scm_mainpic'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_activepics')) {
                $savedata['scm_activepics'] = $modifydata['scm_activepics'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_price')) {
                $savedata['scm_price'] = $modifydata['scm_price'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_startdate')) {
                $savedata['scm_startdate'] = $modifydata['scm_startdate'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_enddate')) {
                $savedata['scm_enddate'] = $modifydata['scm_enddate'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_reservationtag')) {
                $savedata['scm_reservationtag'] = $modifydata['scm_reservationtag'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_reservationfulltag')) {
                $savedata['scm_reservationfulltag'] = $modifydata['scm_reservationfulltag'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_dailystart')) {
                $savedata['scm_dailystart'] = $modifydata['scm_dailystart'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_dailyend')) {
                $savedata['scm_dailyend'] = $modifydata['scm_dailyend'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_workhour')) {
                $savedata['scm_workhour'] = $modifydata['scm_workhour'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_preparehour')) {
                $savedata['scm_preparehour'] = $modifydata['scm_preparehour'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_includeweekend')) {
                $savedata['scm_includeweekend'] = $modifydata['scm_includeweekend'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_reservationavailable')) {
                $savedata['scm_reservationavailable'] = $modifydata['scm_reservationavailable'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_poststatus')) {
                $savedata['scm_poststatus'] = $modifydata['scm_poststatus'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_member_limit')) {
                $savedata['scm_member_limit'] = $modifydata['scm_member_limit'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_balanceno')) {
                $savedata['scm_balanceno'] = $modifydata['scm_balanceno'];
            }

            if (Commontools::CheckArrayValue($modifydata, 'scm_producttype')) {
                $savedata['scm_producttype'] = $modifydata['scm_producttype'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_advancedescribe')) {
                $savedata['scm_advancedescribe'] = $modifydata['scm_advancedescribe'];
            }
            
             if (Commontools::CheckArrayValue($modifydata, 'scm_coupon_providetype')) {
                $savedata['scm_coupon_providetype'] = $modifydata['scm_coupon_providetype'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_bonus_giveafteruse')) {
                $savedata['scm_bonus_giveafteruse'] = $modifydata['scm_bonus_giveafteruse'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_bonus_giveamount')) {
                $savedata['scm_bonus_giveamount'] = $modifydata['scm_bonus_giveamount'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'scm_bonus_payamount')) {
                $savedata['scm_bonus_payamount'] = $modifydata['scm_bonus_payamount'];
            }
            
            if (Commontools::CheckArrayValue($modifydata, "isflag")) {
                $savedata['isflag'] = $modifydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
            }

            $savedata['last_update_user'] = 'pm_webapi';

            DB::table('icr_shopcoupondata_m')
                    ->where('scm_id', '=', $savedata['scm_id'])
                    ->update($savedata);

            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 更新商家優惠券主表的刊登狀態
     * @param type $scm_id
     * @param type $scm_poststatus
     * @return boolean
     */
    public static function UpdateData_PostStatus($scm_id, $scm_poststatus) {
        try {
            if (is_null($scm_id) || strlen($scm_id) == 0) {
                return false;
            }

            $arraydata['scm_poststatus'] = $scm_poststatus;
            $arraydata['last_update_user'] = 'webapi';

            DB::table('icr_shopcoupondata_m')
                    ->where('scm_id', $scm_id)
                    ->update($arraydata);

            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 更新商家優惠券主表的預約狀態
     * @param type $scm_id
     * @param type $scm_reservationfulltag
     * @return boolean
     */
    public static function UpdateData_ReservationFull($scm_id, $scm_reservationfulltag) {
        try {
            if (is_null($scm_id) || strlen($scm_id) == 0) {
                return false;
            }
//             \App\Models\ErrorLog::InsertLog($scm_id);

            $arraydata['scm_reservationfulltag'] = $scm_reservationfulltag;
            $arraydata['last_update_user'] = 'pm_webapi';

            DB::table('icr_shopcoupondata_m')
                    ->where('scm_id', $scm_id)
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

    /**
     * 查詢商家活動券名稱
     * @param type $scm_id
     * @return type
     */
    public static function Query_SCM_CName($scm_id) {
        try {
            $querydata = ICR_ShopCouponData_m::GetData($scm_id);
            if (count($querydata) == 0) {
                return null;
            }
            return $querydata[0]['scm_title'];
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    public static function Query_HaveReservation($sd_id, $lastupdate) {
        try {
            if ($sd_id == null || strlen($sd_id) == 0 || $lastupdate == null || strlen($lastupdate) == 0) {
                return null;
            }
            $query = ICR_ShopCouponData_m::

                    leftJoin('icr_shopcoupondata_g', 'icr_shopcoupondata_m.scm_id', '=', 'icr_shopcoupondata_g.scm_id')
                    ->leftJoin('icr_shopcoupondata_r', 'icr_shopcoupondata_g.scr_serno', '=', 'icr_shopcoupondata_r.scr_serno')
                    ->leftJoin('iscarmemberdata', 'icr_shopcoupondata_g.md_id', '=', 'iscarmemberdata.md_id')
                    //
                    ->where('icr_shopcoupondata_m.sd_id', $sd_id)
                    ->where('icr_shopcoupondata_m.scm_reservationtag', '1')
                    ->where('icr_shopcoupondata_g.last_update_date', '>', $lastupdate)
                    ->whereNotNull('icr_shopcoupondata_g.scr_serno')
                    //
                    ->where('icr_shopcoupondata_m.isflag', '1')
                    ->where('icr_shopcoupondata_g.isflag', '1')
                    ->where('icr_shopcoupondata_r.isflag', '1')
                    ->where('iscarmemberdata.isflag', '1')
                    //
                    ->orderby('icr_shopcoupondata_m.create_date', 'desc')
                    ->get()
                    ->toArray();
            

            return $query;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    public static function Query_FiveDayReservation($sd_id, $query_startdate, $query_reply_status) {
        try {
            if ($sd_id == null || strlen($sd_id) == 0) {
                return null;
            }

            //$startdate = new \DateTime('now');
            //$enddate = new \DateTime('now');
            //$enddate->add(new \DateInterval('P5D'));

            //ErrorLog::InsertLog('$startdate' . $startdate->format('Y/m/d'));
            //ErrorLog::InsertLog('$enddate' . $enddate->format('Y/m/d'));
             
            $query = ICR_ShopCouponData_m::

                    leftjoin('icr_shopcoupondata_g', function($query){
                                      $query->on('icr_shopcoupondata_m.scm_id', '=', 'icr_shopcoupondata_g.scm_id')
                                              ->where('icr_shopcoupondata_g.scg_usestatus', '!=', 1);
                             })
                    ->leftJoin('icr_shopcoupondata_r', 'icr_shopcoupondata_g.scr_serno', '=', 'icr_shopcoupondata_r.scr_serno')
                    ->leftJoin('iscarmemberdata', 'icr_shopcoupondata_g.md_id', '=', 'iscarmemberdata.md_id')
                    //
                    ->where('icr_shopcoupondata_m.sd_id', $sd_id)
                    ->where('icr_shopcoupondata_m.scm_reservationtag', '1')
                    //->where('icr_shopcoupondata_g.last_update_date', '>', $lastupdate)
                    //->where('icr_shopcoupondata_r.scr_rvdate', '>=', $startdate->format('Y/m/d'))
                    //->where('icr_shopcoupondata_r.scr_rvdate', '<=', $enddate->format('Y/m/d'))
                    ->where('icr_shopcoupondata_r.scr_rvdate', '=', $query_startdate)
                    ->whereNotNull('icr_shopcoupondata_g.scr_serno')
                    //
                    ->where('icr_shopcoupondata_m.isflag', '1')
                    ->where('icr_shopcoupondata_g.isflag', '1')
                    ->where('icr_shopcoupondata_r.isflag', '1')
                    ->where('iscarmemberdata.isflag', '1');
                     
                     if($query_reply_status != 9 ){
                        $query ->where('icr_shopcoupondata_g.scg_replystatus', '=' ,$query_reply_status);
                     } else {
                         $query ->whereIn('icr_shopcoupondata_g.scg_replystatus' ,array(0,1));
                     }
                             
              
            //ErrorLog::InsertLog($query->toSql());
            //
            /*
              ->orderby('icr_shopcoupondata_r.scr_rvdate', 'asc')
              ->get()
              ->toArray();
             */
                            
            return $query->orderby('icr_shopcoupondata_r.scr_rvdate', 'asc')
                            ->get()
                            ->toArray();
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    
     public static function getReplyStatusDataBySdId($sd_id) {
      try {
            if ($sd_id == null || strlen($sd_id) == 0) {
                return null;
            }
         $query = ICR_ShopCouponData_m::

                    leftjoin('icr_shopcoupondata_g', function($query){
                                      $query->on('icr_shopcoupondata_m.scm_id', '=', 'icr_shopcoupondata_g.scm_id')
                                                  ->where('icr_shopcoupondata_g.scg_usestatus', '=', '5') ;
                             })
                    ->Leftjoin('icr_shopcoupondata_r', function($query){
                                      $query->on('icr_shopcoupondata_g.scr_serno', '=', 'icr_shopcoupondata_r.scr_serno')
                                                  ->where('icr_shopcoupondata_r.scr_rvdate', '>=', date("Y-m-d"));
                             })
                    ->leftJoin('iscarmemberdata', 'icr_shopcoupondata_g.md_id', '=', 'iscarmemberdata.md_id')
                    //
                    
                    ->where('icr_shopcoupondata_m.sd_id', $sd_id)
                   ->where('icr_shopcoupondata_g.scg_replystatus', '=', '0')
                   ->whereNotNull('icr_shopcoupondata_g.scr_serno')
                    //
                    ->where('icr_shopcoupondata_m.isflag', '1')
                    ->where('icr_shopcoupondata_g.isflag', '1')
                    ->where('icr_shopcoupondata_r.isflag', '1')
                    ->where('iscarmemberdata.isflag', '1')
                    ->orderby('icr_shopcoupondata_r.scr_rvdate', 'asc');
                     
                             
              
            //ErrorLog::InsertLog($query->toSql());
            //
            /*
              ->orderby('icr_shopcoupondata_r.scr_rvdate', 'asc')
              ->get()
              ->toArray();
             */
           //\App\Models\ErrorLog::InsertLog($query->toSql());                 
            return $query->orderby('icr_shopcoupondata_r.scr_rvdate', 'asc')
                            ->get()
                            ->toArray();
            
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
     }

}
