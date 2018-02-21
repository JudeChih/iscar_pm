<?php

namespace App\Http\Controllers\APIControllers\Logistics;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** paysuccesstopush 付款完成 * */
class PaySuccessToPush {
   function paysuccesstopush() {
        $functionName = 'paysuccesstopush';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            if($inputData == null){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //輸入值
            if(!PaySuccessToPush::CheckInput($inputData)){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //檢查身份模組驗證
                $memService = new \App\Services\MemberService;
            if ( !$memService->checkModuleAccount($inputData['modacc'], $inputData['modvrf'], $messageCode)) {
              //模組身份驗證失敗
              $messageCode = '999999961';
              throw new \Exception($messageCode);
            }
            if (!$memService->checkServiceAccessToken($inputData['sat'], $md_id, $messageCode)) {
              //呼叫「MemberAPI」檢查SAT的狀態，驗證SAT有效性
               //$messageCode = '999999962';
               throw new \Exception($messageCode);
            }
            $querydata = \App\Models\ICR_ShopCouponData_g::getMdidByScgid($inputData['scg_id']);
            if ($querydata[0]['scm_coupon_providetype'] != 1) {
                $invoiceClass = new \App\Http\Controllers\APIControllers\Invoice\Create_Invoice;
                $inputData['md_id'] = $md_id;
                if (!$invoiceClass->createinvoice($inputData)) {
                     throw new \Exception($messageCode);
                }   
            }
            
            $Md_Id_Array = array($querydata[0]['md_id']);
            foreach ($querydata as $row) {
              array_push($Md_Id_Array, $row['smb_md_id']);
            }
            $target = 1;
            $iscar_push = '{"target" :"'.$target.'","id_1":"","id_2":""}';
            $memService->push_notification($inputData['sat'], $Md_Id_Array, null, null, $iscar_push, $target);
             
             if ($querydata[0]['scm_coupon_providetype'] == 1 ) {
                 $this->updatePaymentStatus($inputData['scg_id']);
                 $querydata = \App\Models\ICR_ShopCouponData_g::getMdidByScgid($inputData['scg_id']);
                 $Logistics = new \App\Http\Controllers\APIControllers\Logistics\Logistics;
                 $amount = $querydata[0]['scg_subtract_totalamount'] ;
                 foreach ($querydata as $row){
                       if ( ! $Logistics->Insert_MsLog_1112( $row['smb_md_id'],  $inputData['scg_id'],  $amount )) {
                         throw new \Exception();
                      }
                 }
                 if(! $Logistics->Insert_MsLog_1113( $querydata[0]['md_id'], $querydata[0]['sd_shopname'], $querydata[0]['scm_title'], $amount)) {
                      throw new \Exception();
                 }
             }
           
            $mailController = new  \App\Http\Controllers\APIControllers\MailController;
            $mailController->pay_end_sendMail($inputData['scg_id']);
            
            //叫號完成，請等候用戶前往
            $messageCode = '000000000';
       } catch(\Exception $e){ 
            if (is_null($messageCode)) { 
              $messageCode = '999999999'; 
              \App\Models\ErrorLog::InsertData($e); 
            } 
         } 
       $resultArray = Commontools::ResultProcess($messageCode, $resultData);
       Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode); 
       $result = [$functionName . 'result' => $resultArray]; 
       return $result;
   }
   /**
     * 檢查輸入值是否正確
     * @param type $value
     * @return boolean
     */
    function CheckInput(&$value) {
        if ($value == null) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'modacc', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'modvrf', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sat', 0, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scg_id',0, false, false)) {
            return false;
        }
        
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'identifier', 0, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'addr', 0, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'phone', 0, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'email', 0, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'md_cname', 0, true, false)) {
            return false;
        }
        return true;
    }
    
    
    
    function updatePaymentStatus($scg_id){
      try {
            
            $scgData = [ 
                                  'scg_id' => $scg_id,
                                  'scg_usestatus' => 5,
                                  'scg_paymentstatus' => 1 ,
                                  'scg_paid_time' => date('Y-m-d H:i:s'),
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