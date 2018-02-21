<?php

namespace App\Repositories;

use App\Models\ICR_PmReceiptIssue_Log;
use DB;

class ICR_PmReceiptIssue_LogRepository  {

    //新增資料
    public function InsertData($arraydata) {
     try {
        if (
                !\App\Library\Commontools::CheckArrayValue($arraydata, 'sd_id') || !\App\Library\Commontools::CheckArrayValue($arraydata, 'pril_ordernumber')
             || !\App\Library\Commontools::CheckArrayValue($arraydata, 'pril_customerphone') || !\App\Library\Commontools::CheckArrayValue($arraydata, 'pril_customeremail')
             || !\App\Library\Commontools::CheckArrayValue($arraydata, 'pril_customeraddr')  
             || !\App\Library\Commontools::CheckArrayValue($arraydata, 'pril_issuereturncode') || !\App\Library\Commontools::CheckArrayValue($arraydata, 'pril_issuertnmsg')
             || !\App\Library\Commontools::CheckArrayValue($arraydata, 'pril_issuerequest') || !\App\Library\Commontools::CheckArrayValue($arraydata, 'pril_issueresponse') 
             || !\App\Library\Commontools::CheckArrayValue($arraydata, 'pril_ordernumber')
        ) {
            return false;
        }
        $savedata['sd_id'] = $arraydata['sd_id'];
        $savedata['pril_ordernumber'] = $arraydata['pril_ordernumber'];
        $savedata['pril_customerphone'] = $arraydata['pril_customerphone'];
        $savedata['pril_customeremail'] = $arraydata['pril_customeremail'];
        $savedata['pril_customeraddr'] = $arraydata['pril_customeraddr'];
        $savedata['pril_issuereturncode'] = $arraydata['pril_issuereturncode'];
        $savedata['pril_issuertnmsg'] = $arraydata['pril_issuertnmsg'];
        $savedata['pril_issuerequest'] = $arraydata['pril_issuerequest'];
        $savedata['pril_issueresponse'] = $arraydata['pril_issueresponse'];
        $savedata['pril_ordernumber'] = $arraydata['pril_ordernumber'];
        
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_receiptnumber")) {
                $savedata['pril_receiptnumber'] = $arraydata['pril_receiptnumber'];
        } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_invoicedate")) {
                $savedata['pril_invoicedate'] = $arraydata['pril_invoicedate'];
        } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_randomnumber")) {
                $savedata['pril_randomnumber'] = $arraydata['pril_randomnumber'];
        } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_customeridentifier")) {
                $savedata['pril_customeridentifier'] = $arraydata['pril_customeridentifier'];
         } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_trade_type")) {
                $savedata['pril_trade_type'] = $arraydata['pril_trade_type'];
         } else {
                $savedata['pril_trade_type'] = '0';
        }
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_issueresult")) {
                $savedata['pril_issueresult'] = $arraydata['pril_issueresult'];
        } else {
                $savedata['pril_issueresult'] = '0';
        }
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_receiptstatus")) {
                $savedata['pril_receiptstatus'] = $arraydata['pril_receiptstatus'];
         } else {
                $savedata['pril_receiptstatus'] = '0';
        }
        
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_voidrequest")) {
                $savedata['pril_voidrequest'] = $arraydata['pril_voidrequest'];
        } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_voidresponse")) {
                $savedata['pril_voidresponse'] = $arraydata['pril_voidresponse'];
        } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_voiddatetime")) {
                $savedata['pril_voiddatetime'] = $arraydata['pril_voiddatetime'];
        } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_voidreason")) {
                $savedata['pril_voidreason'] = $arraydata['pril_voidreason'];
        } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_voidrtncode")) {
                $savedata['pril_voidrtncode'] = $arraydata['pril_voidrtncode'];
         } 
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_voidrtnmsg")) {
                $savedata['pril_voidrtnmsg'] = $arraydata['pril_voidrtnmsg'];
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
         if (DB::table('icr_pmreceiptissue_log')->insert($savedata)) {
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
    public function UpdateData($arraydata) {
        try {

            if (!\App\Library\Commontools::CheckArrayValue($arraydata, 'pril_serno')) {
                return false;
            }

            $savedata['pril_serno'] = $arraydata['pril_serno'];
        
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "sd_id")) {
                $savedata['sd_id'] = $arraydata['sd_id'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_ordernumber")) {
                $savedata['pril_ordernumber'] = $arraydata['pril_ordernumber'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_customeridentifier")) {
                $savedata['pril_customeridentifier'] = $arraydata['pril_customeridentifier'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_customerphone")) {
                $savedata['pril_customerphone'] = $arraydata['pril_customerphone'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_customeremail")) {
                $savedata['pril_customeremail'] = $arraydata['pril_customeremail'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_customeraddr")) {
                $savedata['pril_customeraddr'] = $arraydata['pril_customeraddr'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_receiptnumber")) {
                $savedata['pril_receiptnumber'] = $arraydata['pril_receiptnumber'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_invoicedate")) {
                $savedata['pril_invoicedate'] = $arraydata['pril_invoicedate'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_randomnumber")) {
                $savedata['pril_randomnumber'] = $arraydata['pril_randomnumber'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_issuereturncode")) {
                $savedata['pril_issuereturncode'] = $arraydata['pril_issuereturncode'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_issuertnmsg")) {
                $savedata['pril_issuertnmsg'] = $arraydata['pril_issuertnmsg'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_issuerequest")) {
                $savedata['pril_issuerequest'] = $arraydata['pril_issuerequest'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_issueresponse")) {
                $savedata['pril_issueresponse'] = $arraydata['pril_issueresponse'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_ordernumber")) {
                $savedata['pril_ordernumber'] = $arraydata['pril_ordernumber'];
            } 
           
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_trade_type")) {
                $savedata['pril_trade_type'] = $arraydata['pril_trade_type'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_issueresult")) {
                $savedata['pril_issueresult'] = $arraydata['pril_issueresult'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_receiptstatus")) {
                $savedata['pril_receiptstatus'] = $arraydata['pril_receiptstatus'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_voidrequest")) {
                $savedata['pril_voidrequest'] = $arraydata['pril_voidrequest'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_voidresponse")) {
                $savedata['pril_voidresponse'] = $arraydata['pril_voidresponse'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_voiddatetime")) {
                $savedata['pril_voiddatetime'] = $arraydata['pril_voiddatetime'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_voidreason")) {
                $savedata['pril_voidreason'] = $arraydata['pril_voidreason'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_voidrtncode")) {
                $savedata['pril_voidrtncode'] = $arraydata['pril_voidrtncode'];
            } 
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "pril_voidrtnmsg")) {
                $savedata['pril_voidrtnmsg'] = $arraydata['pril_voidrtnmsg'];
            } 
            $savedata['last_update_date'] = date('Y-m-d H:i:s');

            DB::table('icr_pmreceiptissue_log')
                    ->where('pril_serno', $savedata['pril_serno'])
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

     /**
     * 刪除資料
     * @param $cmsg_serno 要刪除的資料
     * @return boolean
     */
    public function DeleteData($pril_serno) {
       try {
            if ($pril_serno == null || strlen($pril_serno) == 0) {
              return false;
            }
            DB::table('icr_pmreceiptissue_log')
                   ->where('pril_serno', $pril_serno)
                   ->delete();
           return true;
       } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
       }
    }
    
    
    
    public function getPrilSerno($sd_id, $pril_ordernumber ) {
      try {
            $query = ICR_PmReceiptIssue_Log::where('sd_id', $sd_id)
                                                ->where('icr_pmreceiptissue_log.pril_ordernumber',$pril_ordernumber);
                                                
            $result = $query->select( 
                                                     'icr_pmreceiptissue_log.pril_serno'
                                                    )->get()->toArray();
            //\App\Models\ErrorLog::InsertLog($result);
            return $result[0]['pril_serno'];
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return null;
      }
    }
    
    
    public function getReceiptNumber($sd_id, $pril_ordernumber ) {
      try {
            $query = ICR_PmReceiptIssue_Log::where('sd_id', $sd_id)
                                                ->where('icr_pmreceiptissue_log.pril_ordernumber',$pril_ordernumber);
                                                
            $result = $query->select( 
                                                     'icr_pmreceiptissue_log.pril_receiptnumber'
                                                    )->get()->toArray();
            //\App\Models\ErrorLog::InsertLog($result);
            return $result[0]['pril_receiptnumber'];
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return null;
      }
    }

}