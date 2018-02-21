<?php

namespace App\Repositories;

use App\Models\ICR_Reservation_Paused;
use DB;

class ICR_Reservation_PausedRepository  {

    //新增資料
    public function InsertData($arraydata) {
     try {
        if (
                !\App\Library\Commontools::CheckArrayValue($arraydata, 'sd_id') || !\App\Library\Commontools::CheckArrayValue($arraydata, 'rp_start_datetime')
             || !\App\Library\Commontools::CheckArrayValue($arraydata, 'rp_end_datetime')
        ) {
            return false;
        }
        $savedata['sd_id'] = $arraydata['sd_id'];
        $savedata['rp_start_datetime'] = $arraydata['rp_start_datetime'];
        $savedata['rp_end_datetime'] = $arraydata['rp_end_datetime'];
        
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
        }
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "create_user")) {
                $savedata['create_user'] = $arraydata['create_user'];
            } else {
                $savedata['create_user'] = 'Pmapi';
        }
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "last_update_user")) {
                $savedata['last_update_user'] = $arraydata['last_update_user'];
            } else {
                $savedata['last_update_user'] = 'Pmapi';
        }
        $savedata['create_date'] = date('Y-m-d H:i:s');
        $savedata['last_update_date'] = date('Y-m-d H:i:s');

        //新增資料並回傳「自動遞增KEY值」
         if (DB::table('icr_reservation_paused')->insert($savedata)) {
            return true;
        } else {
            return false;
        }
     } catch (\Exception $e) {
               //DB::rollBack();
               \App\Models\ErrorLog::InsertData($e);
               return false;
     }
    }

     

     /**
     * 刪除資料
     * @param $rp_serno 要刪除的資料
     * @return boolean
     */
    public function DeleteData($rp_serno) {
       try {
            if ($rp_serno == null || strlen($rp_serno) == 0) {
              return false;
            }
            DB::table('icr_reservation_paused')
                   ->where('rp_serno', $rp_serno)
                   ->delete();
           return true;
       } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
       }
    }



    public function  getDataBySdId ($sd_id) {
        if ($sd_id == null || strlen($sd_id) == 0)
            return null;

        $query = ICR_Reservation_Paused::where('sd_id', $sd_id) 
                                                              ->whereRaw('icr_reservation_paused.rp_end_datetime > NOW() ')
                                                              ->where('isflag', '=', '1');
        $result = $query->select( 'icr_reservation_paused.rp_serno'
                                                 ,'icr_reservation_paused.rp_start_datetime'
                                                 ,'icr_reservation_paused.rp_end_datetime'
                                     )->orderby('rp_start_datetime', 'asc')
                                    ->get()->toArray();
        return $result;
    }
    
     public function  getDataBySdId_RpSerno ($sd_id, $rp_serno) {
        if ($sd_id == null || strlen($sd_id) == 0 || $rp_serno == null || strlen($rp_serno) == 0)
            return null;

        $result = ICR_Reservation_Paused::where('sd_id', $sd_id) 
                                                              ->where('rp_serno',$rp_serno)
                                                              ->where('isflag', '=', '1')
                                                              ->get()->toArray();
        return $result;
    }
    
    
    
    public function  getDataByDatetime ($rp_start_datetime, $rp_end_datetime, $sd_id) {
        if ($rp_start_datetime == null || $rp_end_datetime == null )
            return null;
         $result = ICR_Reservation_Paused::where('isflag', '=', '1')
                                                              ->where("sd_id","=",$sd_id)
                                                              ->whereRaw(" ( ('$rp_start_datetime' BETWEEN `rp_start_datetime` AND `rp_end_datetime`) or ('$rp_end_datetime'  BETWEEN `rp_start_datetime` AND `rp_end_datetime`) ) ")
                                                              //->whereRaw(" `rp_start_datetime` BETWEEN '$rp_start_datetime' AND '$rp_end_datetime' OR `rp_end_datetime` BETWEEN '$rp_start_datetime' AND '$rp_end_datetime' ")
                                                               ->get()->toArray();
        return $result;
    }

}