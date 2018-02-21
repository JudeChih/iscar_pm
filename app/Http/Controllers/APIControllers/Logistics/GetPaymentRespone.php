<?php

namespace App\Http\Controllers\APIControllers\Logistics;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
use Redirect;

/** getpaymentrespone	 **/
class GetPaymentRespone {
   function getpaymentrespone($value) {
         // \App\Models\ErrorLog::InsertLog('Im here!!!_Row11');
        $functionName = 'getpaymentrespone';
        $inputData = Commontools::ConvertStringToArray(Input::All());
        $messageCode = null;
        $resultData = null;
        $urlvalue = null;
        try{
             if ($value != 'suntech' && $value != 'ecpay' ) {

             }
             if ($value == 'suntech') {
                if (!GetPaymentRespone::getSunTechResponse($inputData, $urlvalue)) {
                  throw new \Exception($messageCode);
                }
             } else if ($value == 'ecpay') {
                if (!GetPaymentRespone::getEcpayResponse($inputData, $urlvalue)) {
                  throw new \Exception($messageCode);
                }
             }
            $resultData['urlvalue'] = $urlvalue;
            $messageCode = '000000000';
       } catch(\Exception $e) {
            if (is_null($messageCode)) {
              $messageCode = '999999999';
              \App\Models\ErrorLog::InsertData($e);
            }
         }
       $resultArray = Commontools::ResultProcess($messageCode, $resultData);
        Commontools::WriteExecuteLog($functionName, json_encode($inputData), json_encode($resultArray), $messageCode); 
        $result = [$functionName . 'result' => $resultArray];
        return $result;
   }

   public function getSunTechResponse($inputdata, &$urlvalue) {
      try {
        if ((int)$inputdata['errcode'] != 00 ) {
           return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($inputdata, 'buysafeno', 0, false, false)) {
             $buysafeno = $inputdata['BuySafeNo'];
        } else {
             $buysafeno = $inputdata['buysafeno'];
        }
        if (!GetPaymentRespone::updatePaymentStatus($inputdata['Td'], $buysafeno, $inputdata) ) {
          throw new \Exception();
        }
        $querydata = \App\Models\ICR_ShopCouponData_g::getMdidByScgid($inputdata['Td']);
        $Logistics = new \App\Http\Controllers\APIControllers\Logistics\Logistics;
        $amount = $querydata[0]['scg_subtract_totalamount'] ;
        foreach ($querydata as $row){
            if ( ! $Logistics->Insert_MsLog_1104( $row['smb_md_id'],  $inputdata['Td'],  $amount )) {
              throw new \Exception();
            }
        }
        if(! $Logistics->Insert_MsLog_1105( $querydata[0]['md_id'], $querydata[0]['sd_shopname'], $querydata[0]['scm_title'], $amount)) {
           throw new \Exception();
        }
        $urlvalue = [
               'sd_shopname'   =>$querydata[0]['sd_shopname'],
               'scm_title'            =>$querydata[0]['scm_title'],
               'scg_id'                =>$inputdata['Td'],
               'sd_shoptel'         =>$querydata[0]['sd_shoptel'],
               'scm_price'          =>$querydata[0]['scm_price'],
               'scg_buyamount' =>$inputdata['MN'],
        ];
        return true;
      } catch (\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return false;
      }
   }

   public function getEcpayResponse($inputdata, &$urlvalue) {
    try{
        if ((int)$inputdata['RtnCode'] != 1) {
          return false;
        }
        if (!GetPaymentRespone::updatePaymentStatus($inputdata['MerchantTradeNo'],$inputdata['TradeNo'], $inputdata)) {
          throw new \Exception();
        }
        $querydata = \App\Models\ICR_ShopCouponData_g::getMdidByScgid($inputdata['MerchantTradeNo']);
        $Logistics = new \App\Http\Controllers\APIControllers\Logistics\Logistics;
        $amount = $querydata[0]['scm_price'] * $querydata[0]['scg_buyamount'];
        foreach ($querydata as $row){
            if ( ! $Logistics->Insert_MsLog_1104( $row['smb_md_id'],  $inputdata['MerchantTradeNo'],  $amount )) {
              throw new \Exception();
            }
        }
        if(! $Logistics->Insert_MsLog_1105( $querydata[0]['md_id'], $querydata[0]['sd_shopname'], $querydata[0]['scm_title'], $amount)) {
           throw new \Exception();
        }
        $urlvalue = [
               'sd_shopname'   =>$inputdata['CustomField3'],
               'scm_title'            =>$querydata[0]['scm_title'],
               'scg_id'                =>$inputdata['MerchantTradeNo'],
               'sd_shoptel'         =>$querydata[0]['sd_shoptel'],
               'scm_price'          =>$querydata[0]['scm_price'],
               'scg_buyamount' =>$inputdata['TradeAmt'],
        ];

        return true;
    } catch(\Exception $e) {
       \App\Models\ErrorLog::InsertData($e);
       return false;
    }
   }

public function updatePaymentStatus($scg_id,$payment_no, $inputData){
      try {
            
            $scgData = [ 
                                  'scg_id' => $scg_id,
                                  'scg_usestatus' => 5,
                                  'scg_paymentstatus' => 1 ,
                                  'scg_paid_time' => date('Y-m-d H:i:s'),
                                  'payment_no'    => $payment_no,
                                  'respone_payment_json' => json_encode($inputData)
                                   ];
            if (! \App\Models\ICR_ShopCouponData_g::UpdateData($scgData) ) {
              throw new \Exception();
            }
            return true;
      } catch (\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return false;
      }
    }

}