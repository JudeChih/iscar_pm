<?php

namespace App\Repositories;

use App\Models\ICR_ShopSettleMentrec_D;
use DB;
use App\Library\Commontools;

class ICR_ShopSettleMentrec_dRepository  {

    //新增資料
    public function InsertData($arraydata) {
     try {
       foreach($arraydata as $row) {
           $row['isflag'] = '1';
           $row['create_user'] = 'Pmapi';
           $row['last_update_user'] = 'Pmapi';
           $row['create_date'] = date('Y-m-d H:i:s');
           $row['last_update_date'] = date('Y-m-d H:i:s');
       }

        //新增資料並回傳「自動遞增KEY值」
         if (DB::table('icr_shopsettlementrec_d')->insert($arraydata)) {
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
            if (!Commontools::CheckArrayValue($arraydata, 'ssrd_serno')) {
                return false;
            }

            $savedata['ssrd_serno'] = $arraydata['ssrd_serno'];

            if (Commontools::CheckArrayValue($arraydata, 'ssrm_id')) {
                $savedata['ssrm_id'] = $arraydata['ssrm_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'scg_id')) {
                $savedata['scg_id'] = $arraydata['scg_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrd_billingtype')) {
                $savedata['ssrd_billingtype'] = $arraydata['ssrd_billingtype'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_saleamountnodiscount')) {
                $savedata['ssrm_saleamountnodiscount'] = $arraydata['ssrm_saleamountnodiscount'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_spconsume')) {
                $savedata['ssrm_spconsume'] = $arraydata['ssrm_spconsume'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_amountafterdiscount')) {
                $savedata['ssrm_amountafterdiscount'] = $arraydata['ssrm_amountafterdiscount'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'ssrm_gpconsume')) {
                $savedata['ssrm_gpconsume'] = $arraydata['ssrm_gpconsume'];
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
            if (Commontools::CheckArrayValue($arraydata, 'ssrd_salerecorder')) {
                $savedata['ssrd_salerecorder'] = $arraydata['ssrd_salerecorder'];
            }
            

            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            }

            $savedata['last_update_user'] = 'webapi';
            // $savedata['last_update_date'] = \Carbon\Carbon::now();

            // \App\Models\ErrorLog::InsertLog(json_encode($savedata));
            DB::table('icr_shopsettlementrec_d')
                    ->where('ssrd_serno', $savedata['ssrd_serno'])
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }
    
    
    public  function GetDataBySsrmId_SdId_BillingType($ssrm_id, $sd_id, $billingtype, $data_pagination, $queryamount,&$count) {
        try {
            if ($ssrm_id == null || strlen($ssrm_id) == 0 || $sd_id == null || strlen($sd_id) == 0
                    || $billingtype == null || strlen($billingtype) == 0) {
                return null;
            }
            $query = ICR_ShopSettleMentrec_D::where('icr_shopsettlementrec_d.isflag', '=', '1')
                    ->leftJoin('icr_shopcoupondata_g', 'icr_shopsettlementrec_d.scg_id', '=', 'icr_shopcoupondata_g.scg_id')
                    ->leftJoin('icr_shopsettlementrec_m', 'icr_shopsettlementrec_d.ssrm_id', '=', 'icr_shopsettlementrec_d.ssrm_id')
                    ->where('icr_shopsettlementrec_d.ssrm_id', $ssrm_id)
                    ->where('icr_shopsettlementrec_m.sd_id', $sd_id);
            if ($billingtype == 1 ) {
                $query->whereIn('ssrd_billingtype', array(1,2))
                            ->select( 'icr_shopsettlementrec_d.ssrm_saleamountnodiscount as sale_amount' 
                                           ,DB::raw("(icr_shopsettlementrec_d.ssrm_saleamountnodiscount - icr_shopsettlementrec_d.ssrm_totaliscarplatformfee - icr_shopsettlementrec_d.ssrm_totalpaymentflowfee) as receive_amount ")
                                           ,'icr_shopsettlementrec_d.ssrd_serno'
                                           ,'icr_shopsettlementrec_d.scg_id'
                                           ,'icr_shopcoupondata_g.scg_paid_time'
                        );
            } else if ($billingtype == 3) {
                $query->where('ssrd_billingtype', $billingtype)
                            ->select( 'icr_shopsettlementrec_d.ssrm_spconsume as sale_amount'
                                          ,DB::raw(" 0 as receive_amount")
                                          ,'icr_shopsettlementrec_d.ssrd_serno'
                                          ,'icr_shopsettlementrec_d.scg_id'
                                          ,'icr_shopcoupondata_g.scg_paid_time');
            }
            $results = $query->get()->toArray();
            $count = count($results);
            $results = $query->skip($data_pagination)->take($queryamount) ->get()->toArray();
            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

}