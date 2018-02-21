<?php

namespace App\Http\Controllers\APIControllers\CarNews;

/** CarNews共用Function * */
class CarNews {

    /**
     *
     *
     */
      function QueryCosEnd($md_id, $dcil_id, &$querydata, &$messageCode) {
       try {
           $bankservice = new \App\Services\BankService;
            if(!$bankservice->getMemBuyPointQuery(null, $md_id, 1, $pointData, $messageCode)) {
              throw new \Exception($messageCode);
            }
            //$cosdata = \App\Models\IsCarCoinStock::GetStockByMDID_COSType($md_id, 0);
            $dcildata = \App\Models\ICR_DepositCostItemList::GetDataByDCILID_DCILCATEGORY($dcil_id, 1);
            if (is_null($dcildata) || count($dcildata) == 0) {
               $messageCode = '011103001';
               return false;
            }
            if ((int)$dcildata[0]['dcil_depositamount'] > (int)$pointData['bpmr_point'] ) {
               $messageCode = '011103002';
               return false;
            }
            if (!CarNews::CreateQueryCosEndResult( $dcildata, $querydata)) {
               return false;
            }
            return true;
       } catch (\Exception $e) {
          if (is_null($messageCode) ) {
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($e);
                return false;
            }
         }
     }
     
      function CreateQueryCosEndResult($dcildata, &$arraydata) {
       try {
          $arraydata = [/*'cos_begin'          => $cosdata['cos_begin']
                       ,'cos_add'            => $cosdata['cos_add']
                       ,'cos_use'            => $cosdata['cos_use']
                       ,'cos_return'         => $cosdata['cos_return']
                       ,'cos_end'            => $cosdata['cos_end']
                       ,'cos_add_date'       => $cosdata['cos_add_date']
                       ,'cos_use_date'       => $cosdata['cos_use_date']
                       ,'cos_begin_date'     => $cosdata['cos_begin_date']
                       ,'cos_end_date'       => $cosdata['cos_end_date']
                       ,*/'dcil_availabledays' => $dcildata[0]['dcil_availabledays']
                       ,'dcil_depositamount' => $dcildata[0]['dcil_depositamount']];
          return true;
       } catch (\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return false;
       }
     }
}
