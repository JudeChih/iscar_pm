<?php

namespace App\Repositories;

use App\Models\ICR_ShopSettleMentrec_M;
use DB;
use App\Library\Commontools;

class ICR_ShopSettleMentrec_mRepository  {

     //新增資料
    public function InsertDataGetId($arraydata, &$ssrm_id) {
     try {
        if (
                !\App\Library\Commontools::CheckArrayValue($arraydata, 'sd_id') || !\App\Library\Commontools::CheckArrayValue($arraydata, 'ssrm_settledate')
             || !\App\Library\Commontools::CheckArrayValue($arraydata, 'ssrm_billingcycle_start') || !\App\Library\Commontools::CheckArrayValue($arraydata, 'ssrm_billingcycle_end')
             || !\App\Library\Commontools::CheckArrayValue($arraydata, 'ssrm_billpaymentday')  
             /*|| !\App\Library\Commontools::CheckArrayValue($arraydata, 'ssrm_actualbillpaymentday')*/ 
             || !\App\Library\Commontools::CheckArrayValue($arraydata, 'ssrm_execute_day')
        ) {
            return false;
        }
        $savedata['ssrm_id'] = $this->GetShotUuid();
        $savedata['sd_id'] = $arraydata['sd_id'];
        $savedata['ssrm_settledate'] = $arraydata['ssrm_settledate'];
        $savedata['ssrm_billingcycle_start'] = $arraydata['ssrm_billingcycle_start'];
        $savedata['ssrm_billingcycle_end'] = $arraydata['ssrm_billingcycle_end'];
        $savedata['ssrm_billpaymentday'] = $arraydata['ssrm_billpaymentday'];
        //$savedata['ssrm_actualbillpaymentday'] = $arraydata['ssrm_actualbillpaymentday'];
        $savedata['ssrm_execute_day'] = $arraydata['ssrm_execute_day'];
        
        
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "ssrm_totaltransatctioncount")) {
                $savedata['ssrm_totaltransatctioncount'] = $arraydata['ssrm_totaltransatctioncount'];
        } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "ssrm_salewithoutdiscount")) {
                $savedata['ssrm_salewithoutdiscount'] = $arraydata['ssrm_salewithoutdiscount'];
        } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "ssrm_salewithgp")) {
                $savedata['ssrm_salewithgp'] = $arraydata['ssrm_salewithgp'];
        } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "ssrm_salebypp")) {
                $savedata['ssrm_salebypp'] = $arraydata['ssrm_salebypp'];
         } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "ssrm_totalppconsume")) {
                $savedata['ssrm_totalppconsume'] = $arraydata['ssrm_totalppconsume'];
         } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "ssrm_amountafterdiscount")) {
                $savedata['ssrm_amountafterdiscount'] = $arraydata['ssrm_amountafterdiscount'];
        } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "ssrm_totalgpconsume")) {
                $savedata['ssrm_totalgpconsume'] = $arraydata['ssrm_totalgpconsume'];
         } 
        
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "ssrm_totalgpexchangetomoney")) {
                $savedata['ssrm_totalgpexchangetomoney'] = $arraydata['ssrm_totalgpexchangetomoney'];
        } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "ssrm_totaliscarplatformfee")) {
                $savedata['ssrm_totaliscarplatformfee'] = $arraydata['ssrm_totaliscarplatformfee'];
        } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "ssrm_totalpaymentflowfee")) {
                $savedata['ssrm_totalpaymentflowfee'] = $arraydata['ssrm_totalpaymentflowfee'];
        } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "ssrm_settlementpayamount")) {
                $savedata['ssrm_settlementpayamount'] = $arraydata['ssrm_settlementpayamount'];
        } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "ssrm_settlementreview")) {
                $savedata['ssrm_settlementreview'] = $arraydata['ssrm_settlementreview'];
         } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "ssrm_settlementpayment")) {
                $savedata['ssrm_settlementpayment'] = $arraydata['ssrm_settlementpayment'];
         } 
         if (\App\Library\Commontools::CheckArrayValue($arraydata, "ssrm_settlementcomplete")) {
                $savedata['ssrm_settlementcomplete'] = $arraydata['ssrm_settlementcomplete'];
         } 
        
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
         if (DB::table('icr_shopsettlementrec_m')->insert($savedata)) {
            $ssrm_id = $savedata['ssrm_id'];
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
     * 修改資料
     * @param array $arraydata 要更新的資料
     * @return boolean
     */
    public static function UpdateData(array $arraydata) {
        try {
            if (!Commontools::CheckArrayValue($arraydata, 'ssrm_id')) {
                return false;
            }

            if (Commontools::CheckArrayValue($arraydata, 'sd_id')) {
                $savedata['sd_id'] = $arraydata['sd_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_settledate')) {
                $savedata['ssrm_settledate'] = $arraydata['ssrm_settledate'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_execute_day')) {
                $savedata['ssrm_execute_day'] = $arraydata['ssrm_execute_day'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_billingcycle_start')) {
                $savedata['ssrm_billingcycle_start'] = $arraydata['ssrm_billingcycle_start'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_billingcycle_end')) {
                $savedata['ssrm_billingcycle_end'] = $arraydata['ssrm_billingcycle_end'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_billpaymentday')) {
                $savedata['ssrm_billpaymentday'] = $arraydata['ssrm_billpaymentday'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_actualbillpaymentday')) {
                $savedata['ssrm_actualbillpaymentday'] = $arraydata['ssrm_actualbillpaymentday'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_totaltransatctioncount')) {
                $savedata['ssrm_totaltransatctioncount'] = $arraydata['ssrm_totaltransatctioncount'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_salewithoutdiscount')) {
                $savedata['ssrm_salewithoutdiscount'] = $arraydata['ssrm_salewithoutdiscount'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_salewithgp')) {
                $savedata['ssrm_salewithgp'] = $arraydata['ssrm_salewithgp'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_salebypp')) {
                $savedata['ssrm_salebypp'] = $arraydata['ssrm_salebypp'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_saleamountnodiscount')) {
                $savedata['ssrm_saleamountnodiscount'] = $arraydata['ssrm_saleamountnodiscount'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_totalppconsume')) {
                $savedata['ssrm_totalppconsume'] = $arraydata['ssrm_totalppconsume'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_amountafterdiscount')) {
                $savedata['ssrm_amountafterdiscount'] = $arraydata['ssrm_amountafterdiscount'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_totalgpconsume')) {
                $savedata['ssrm_totalgpconsume'] = $arraydata['ssrm_totalgpconsume'];
            }
            
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_totalgpexchangetomoney')) {
                $savedata['ssrm_totalgpexchangetomoney'] = $arraydata['ssrm_totalgpexchangetomoney'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_totaliscarplatformfee')) {
                $savedata['ssrm_totaliscarplatformfee'] = $arraydata['ssrm_totaliscarplatformfee'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_totalpaymentflowfee')) {
                $savedata['ssrm_totalpaymentflowfee'] = $arraydata['ssrm_totalpaymentflowfee'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_settlementpayamount')) {
                $savedata['ssrm_settlementpayamount'] = $arraydata['ssrm_settlementpayamount'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_settlementreview')) {
                $savedata['ssrm_settlementreview'] = $arraydata['ssrm_settlementreview'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_settlementpayment')) {
                $savedata['ssrm_settlementpayment'] = $arraydata['ssrm_settlementpayment'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_settlementcomplete')) {
                $savedata['ssrm_settlementcomplete'] = $arraydata['ssrm_settlementcomplete'];
            }
            

            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            }

            $savedata['last_update_user'] = 'Pmapi';
            // $savedata['last_update_date'] = \Carbon\Carbon::now();

            // \App\Models\ErrorLog::InsertLog(json_encode($savedata));
            DB::table('icr_shopsettlementrec_m')
                    ->where('ssrm_id', $arraydata['ssrm_id'])
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }
    
    public  function GetShotUuid() {
        try {
            $query = "SELECT ";
            $query = $query . "UUID_SHORT() as ssrm_id  ";
            $query = $query . " limit 1 offset 0 ";
            $queryData = DB::connection('mysql')->select($query);
            $checkData = ICR_ShopSettleMentrec_M::where('icr_shopsettlementrec_m.ssrm_id', '=',  $queryData[0]->ssrm_id)->get()->toArray();
            if (count($checkData) > 0) {
                $this->GetShotUuid();
            }
            return  $queryData[0]->ssrm_id;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
     /**
     * 依「ssrm_id」取得資料
     * @param type $ssrm_id
     * @return type
     */
    public  function GetDataBySsrmId_SdId($ssrm_id, $sd_id) {
        try {
            if ($ssrm_id == null || strlen($ssrm_id) == 0 || $sd_id == null || strlen($sd_id) == 0 ) {
                return null;
            }
            /*$query = ICR_ShopSettleMentrec_M::where('isflag', '=', '1')
                    ->where('ssrm_id', $ssrm_id)
                    ->where('sd_id', $sd_id);

            
            $results = $query->select(  
                                       'icr_shopsettlementrec_m.sd_id'
                                      ,'icr_shopsettlementrec_m.ssrm_id'
                                      ,'icr_shopsettlementrec_m.ssrm_settledate'
                                      ,'icr_shopsettlementrec_m.ssrm_billingcycle_start'
                                      ,'icr_shopsettlementrec_m.ssrm_billingcycle_end'
                                      ,'icr_shopsettlementrec_m.ssrm_billpaymentday'
                                      ,'icr_shopsettlementrec_m.ssrm_actualbillpaymentday'
                                      ,'icr_shopsettlementrec_m.ssrm_totaltransatctioncount'
                                      ,'icr_shopsettlementrec_m.ssrm_salebypp'
                                      ,DB::raw("(ssrm_salewithoutdiscount + ssrm_salewithgp ) as salecount ")
                                      ,DB::raw("( ssrm_saleamountnodiscount ) as saleamount")
                                      ,'icr_shopsettlementrec_m.ssrm_totalppconsume'
                                      ,'icr_shopsettlementrec_m.ssrm_totaliscarplatformfee'
                                      ,'icr_shopsettlementrec_m.ssrm_totalpaymentflowfee'
                                      ,'icr_shopsettlementrec_m.ssrm_settlementpayamount'
                                      ,'icr_shopsettlementrec_m.ssrm_settlementcomplete'
                                      ,'icr_shopsettlementrec_m.ssrm_settlementreview'
                                      ,'icr_shopsettlementrec_m.ssrm_settlementpayment'
                                      ,'icr_shopsettlementrec_m.ssrm_billpaymentday as settlementreview_deadine'
                                     )->get()->toArray();*/
            
            $query = "SELECT ";
            $query = $query . " icr_shopsettlementrec_m.sd_id ,icr_shopsettlementrec_m.ssrm_id,icr_shopsettlementrec_m.ssrm_settledate ";
            $query = $query ." ,icr_shopsettlementrec_m.ssrm_billingcycle_start,icr_shopsettlementrec_m.ssrm_billingcycle_end,icr_shopsettlementrec_m.ssrm_billpaymentday ";
            $query = $query ." ,icr_shopsettlementrec_m.ssrm_actualbillpaymentday,icr_shopsettlementrec_m.ssrm_totaltransatctioncount,icr_shopsettlementrec_m.ssrm_salebypp ";
            $query = $query .",(ssrm_salewithoutdiscount + ssrm_salewithgp ) as salecount  ,( ssrm_saleamountnodiscount ) as saleamount,icr_shopsettlementrec_m.ssrm_totalppconsume ";
            $query = $query .",icr_shopsettlementrec_m.ssrm_totaliscarplatformfee,icr_shopsettlementrec_m.ssrm_totalpaymentflowfee,icr_shopsettlementrec_m.ssrm_settlementpayamount ";
            $query = $query ." ,icr_shopsettlementrec_m.ssrm_settlementcomplete,icr_shopsettlementrec_m.ssrm_settlementreview ,icr_shopsettlementrec_m.ssrm_settlementpayment ";
            $query = $query . ",icr_shopsettlementrec_m.ssrm_billpaymentday as settlementreview_deadine  ";
            $query = $query ."from  icr_shopsettlementrec_m ";
            $query = $query ."where isflag = 1 ";
            $query = $query ."and sd_id = '$sd_id' ";
            $query = $query ."and ssrm_id = '$ssrm_id' ";
            $results = DB::connection('mysql')->select($query);
            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    /**
     * 依「sd_id」取得資料
     * @param type $sd_id
     * @return type
     */
    public  function GetDataBySdId($sd_id) {
        try {
            if ($sd_id == null || strlen($sd_id) == 0 ) {
                return null;
            }
           /*  $query = ICR_ShopSettleMentrec_M::where('isflag', '=', '1')
                    ->where('sd_id', $sd_id)
                    ->where('ssrm_settlementcomplete', 1)
                    ->orderby('ssrm_billingcycle_start', 'desc');

            
            $results = $query->select(  
                                       'icr_shopsettlementrec_m.sd_id'
                                      //,DB::raw(" (CAST(icr_shopsettlementrec_m.ssrm_id AS  char CHARACTER SET utf8)) as ssrm_id ")
                                      ,'icr_shopsettlementrec_m.ssrm_id'
                                      ,'icr_shopsettlementrec_m.ssrm_settledate'
                                      ,'icr_shopsettlementrec_m.ssrm_billingcycle_start'
                                      ,'icr_shopsettlementrec_m.ssrm_billingcycle_end'
                                      ,'icr_shopsettlementrec_m.ssrm_billpaymentday'
                                      ,'icr_shopsettlementrec_m.ssrm_actualbillpaymentday'
                                      ,'icr_shopsettlementrec_m.ssrm_totaltransatctioncount'
                                      ,'icr_shopsettlementrec_m.ssrm_salebypp'
                                      ,DB::raw("(ssrm_salewithoutdiscount + ssrm_salewithgp ) as salecount ")
                                      ,DB::raw("( ssrm_saleamountnodiscount ) as saleamount")
                                      ,'icr_shopsettlementrec_m.ssrm_totalppconsume'
                                      ,'icr_shopsettlementrec_m.ssrm_totaliscarplatformfee'
                                      ,'icr_shopsettlementrec_m.ssrm_totalpaymentflowfee'
                                      ,'icr_shopsettlementrec_m.ssrm_settlementpayamount'
                                      ,'icr_shopsettlementrec_m.ssrm_settlementcomplete'
                                      ,'icr_shopsettlementrec_m.ssrm_settlementreview'
                                      ,'icr_shopsettlementrec_m.ssrm_settlementpayment'
                                      ,'icr_shopsettlementrec_m.ssrm_billpaymentday as settlementreview_deadine'
                                     );*/
            
           $query = "SELECT ";
            $query = $query . " icr_shopsettlementrec_m.sd_id ,icr_shopsettlementrec_m.ssrm_id,icr_shopsettlementrec_m.ssrm_settledate ";
            $query = $query ." ,icr_shopsettlementrec_m.ssrm_billingcycle_start,icr_shopsettlementrec_m.ssrm_billingcycle_end,icr_shopsettlementrec_m.ssrm_billpaymentday ";
            $query = $query ." ,icr_shopsettlementrec_m.ssrm_actualbillpaymentday,icr_shopsettlementrec_m.ssrm_totaltransatctioncount,icr_shopsettlementrec_m.ssrm_salebypp ";
            $query = $query .",(ssrm_salewithoutdiscount + ssrm_salewithgp ) as salecount  ,( ssrm_saleamountnodiscount ) as saleamount,icr_shopsettlementrec_m.ssrm_totalppconsume ";
            $query = $query .",icr_shopsettlementrec_m.ssrm_totaliscarplatformfee,icr_shopsettlementrec_m.ssrm_totalpaymentflowfee,icr_shopsettlementrec_m.ssrm_settlementpayamount ";
            $query = $query ." ,icr_shopsettlementrec_m.ssrm_settlementcomplete,icr_shopsettlementrec_m.ssrm_settlementreview ,icr_shopsettlementrec_m.ssrm_settlementpayment ";
            $query = $query . ",icr_shopsettlementrec_m.ssrm_billpaymentday as settlementreview_deadine  ";
            $query = $query ."from  icr_shopsettlementrec_m ";
            $query = $query ."where isflag = 1 ";
            $query = $query ."and sd_id = '$sd_id' ";
            $query = $query ."and ssrm_settlementcomplete = 1 ";
            $query = $query ."order by  ssrm_billingcycle_start desc ";
            $results = DB::connection('mysql')->select($query);
            //\App\Models\ErrorLog::InsertLog(json_encode($results[0]->ssrm_id));
            
            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
      /**
     * 依「ssrm_id」取得資料
     * @param type $ssrm_id
     * @return type
     */
    public  function GetDataBySsrmIdForEmail($ssrm_id) {
        try {
            if ($ssrm_id == null || strlen($ssrm_id) == 0 ) {
                return null;
            }
            $query = ICR_ShopSettleMentrec_M::where('isflag', '=', '1')
                    ->where('ssrm_id', $ssrm_id)
                    ->leftjoin('icr_shopdata', function($query) {
                                      $query->on('icr_shopsettlementrec_m.sd_id', '=', 'icr_shopdata.sd_id');
                             });

            
            $results = $query->select(  
                                       'icr_shopdata.sd_shopname'
                                       ,'icr_shopdata.sd_shoptel'
                                       ,'icr_shopdata.sd_salescode'
                                      ,DB::raw("(CASE ssrm_settlementreview WHEN '1' THEN '已覆核' WHEN '2' THEN '帳務有誤' WHEN '3' THEN '已覆核﹙系統﹚'  END) as ssrm_settlementreview")
                                      )->get()->toArray();
            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

}