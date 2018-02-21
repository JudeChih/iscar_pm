<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家基本資料
 */
class ICR_ShopCouponData_r extends Model {

//
    public $table = 'icr_shopcoupondata_r';
    public $primaryKey = 'scr_serno';
    public $timestamps = false;
    public $incrementing = true;

    /** ██████████▍CREATE 建立資料 */

    /**
     * 
     * @param type $scm_id
     * @param type $scm_startdate
     * @param type $scm_enddate
     * @param type $scm_dailystart
     * @param type $scm_dailyend
     * @param type $interval
     * @param type $scm_includeweekend
     * @return \DateTime
     */
    public static function Create_ReservationData($scm_id, $scm_startdate, $scm_enddate, $scm_dailystart, $scm_dailyend, $interval, $scm_includeweekend) {
        try {
            $startdate = new \DateTime($scm_startdate);
            $enddate = new \DateTime($scm_enddate);
            $enddate = $enddate->add(new \DateInterval('P1D'));


            $dayperiod = new \DatePeriod($startdate, new \DateInterval('P1D'), $enddate);

            $result['START'] = $startdate;
            $result['END'] = $enddate;

            foreach ($dayperiod as $date) {
                $startdatetime = new \DateTime($date->format('Y-m-d') . ' ' . $scm_dailystart);
                $enddatetime = new \DateTime($date->format('Y-m-d') . ' ' . $scm_dailyend);
                $timeperiod = new \DatePeriod($startdatetime, new \DateInterval('PT' . $interval * 30 . 'M'), $enddatetime);

                foreach ($timeperiod as $datetime) {
                    //是否需跳過「六、日」
                    if ($scm_includeweekend == '1' && (date('w', strtotime($datetime->format('Y/m/d'))) == 6 || date('w', strtotime($datetime->format('Y/m/d'))) == 0)) {
                        continue;
                    }

                    $savedata['scm_id'] = $scm_id;
                    $savedata['scr_rvdate'] = $datetime->format('Y/m/d');
                    $savedata['scr_rvtime'] = $datetime->format('H:i:s');
                    $savedata['scr_reservationamount'] = 0;
                    $savedata['scr_effective'] = 1;

                    $savedata['last_update_user'] = 'webapi';
                    $savedata['create_user'] = 'webapi';


                    DB::table('icr_shopcoupondata_r')->insert($savedata);
                }
            }
            return $result;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
        }
    }

    /** ██████████▍READ 讀取資料 */

    /**
     * 取得資料
     * @param type $scr_serno
     * @return type
     */
    public static function GetData($scr_serno) {
        try {
            if ($scr_serno == null || strlen($scr_serno) == 0) {
                return null;
            }

            $results = ICR_ShopCouponData_r::where('isflag', '=', '1')
                            ->where('scr_serno', '=', $scr_serno)
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 依「商家優惠券取用編號」對應出該編號的預約資料
     * @param type $scg_id
     * @return type
     */
    public static function GetData_BySCG_ID($scg_id) {
        try {
            if ($scg_id == null || strlen($scg_id) == 0) {
                return null;
            }

            $results = ICR_ShopCouponData_r::
                            join('icr_shopcoupondata_g', 'icr_shopcoupondata_r.scr_serno', '=', 'icr_shopcoupondata_g.scr_serno')
                            ->where('icr_shopcoupondata_g.isflag', '=', '1')
                            ->where('icr_shopcoupondata_r.isflag', '=', '1')
                            ->where('icr_shopcoupondata_g.scg_id', '=', $scg_id)
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    
    public static function GetData_BySCM_ID($isflag, $array_scm_id, $startdatetime, $enddatetime) {
         try {
            if ($array_scm_id == null) {
                return null;
            }
            $startdatetime = date_format($startdatetime,"Y-m-d H:i:00");
            $enddatetime = date_format($enddatetime,"Y-m-d H:i:00");
            $results = ICR_ShopCouponData_r::where('icr_shopcoupondata_r.isflag', '=', $isflag)
                            ->whereIn('scm_id', $array_scm_id)
                            ->whereRaw(" (TIMESTAMP(`scr_rvdate`,`scr_rvtime`) BETWEEN '$startdatetime' AND '$enddatetime' ) ")
                           ->select('scr_serno')
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    

    /** ██████████▍UPDATE 更新資料 */

    /**
     * 更新預約次數
     * @param type $scr_serno
     * @param boolean $is_add 新增或刪減。true：新增、false：刪減
     * @return boolean
     */
    public static function Update_ReservationAmount($scr_serno, $is_add) {
        try {
            if (is_null($scr_serno) || strlen($scr_serno) == 0) {
                return false;
            }
            if ($is_add) {
                DB::table('icr_shopcoupondata_r')
                        ->where('scr_serno', $scr_serno)
                        ->increment('scr_reservationamount', 1, array('last_update_user' => 'webapi'));
            } else {
                DB::table('icr_shopcoupondata_r')
                        ->where('scr_serno', $scr_serno)
                        ->decrement('scr_reservationamount', 1, array('last_update_user' => 'webapi'));
            }

            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 更新資料 停用原有可預約時段
     * @param type $scm_id
     * @param type $scr_effective
     * @return boolean
     */
    public static function Update_SCR_Effective($scm_id, $scr_effective) {
        try {
            if (is_null($scm_id) || strlen($scm_id) == 0) {
                return false;
            }
            $arraydata['scr_effective'] = $scr_effective;

            $arraydata['last_update_user'] = 'webapi';

            DB::table('icr_shopcoupondata_r')
                    ->where('scm_id', $scm_id)
                    ->update($arraydata);

            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }
    
    
      public static function Update_SCR_Effective_ISFLAG($array_scr_serno, $isflag, $scr_effective) {
        try {
           
            $arraydata['scr_effective'] = $scr_effective;
            $arraydata['isflag'] = $isflag;
            $arraydata['last_update_user'] = 'webapi';

            DB::table('icr_shopcoupondata_r')
                    ->whereIn('scr_serno', $array_scr_serno)
                    ->update($arraydata);

            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /** ██████████▍DELETE 刪除資料 */
    /** ██████████▍CHECK 檢查資料 */
    /** ██████████▍QUERY 查詢資料 */

    /**
     * 查詢所有未過期的活動券
     * @param type $scm_id
     * @return type
     */
    public static function QueryCanReservationTime($scm_id) {
        try {
            if ($scm_id == null || strlen($scm_id) == 0) {
                return null;
            }

            $results = ICR_ShopCouponData_r::
                            join('icr_shopcoupondata_m', function($query) {
                                      $query->on('icr_shopcoupondata_r.scm_id', '=', 'icr_shopcoupondata_m.scm_id');
                             })
                            ->where('icr_shopcoupondata_m.isflag', '=', '1')
                            ->where('icr_shopcoupondata_r.isflag', '=', '1')
                            ->where('icr_shopcoupondata_r.scr_effective', '=', '1')
                            ->whereRaw("concat(icr_shopcoupondata_r.scr_rvdate ,concat(' ',icr_shopcoupondata_r.scr_rvtime)) > NOW()")
                            ->where('icr_shopcoupondata_r.scm_id', '=', $scm_id)
                            ->whereRaw("icr_shopcoupondata_r.scr_reservationamount  < icr_shopcoupondata_m.scm_reservationavailable")
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

}
