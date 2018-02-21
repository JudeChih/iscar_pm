<?php

namespace App\Http\Controllers\APIControllers\Invoice;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/**createinvoice 	 **/
class Invalid_Invoice {
   function invalidinvoice($inputData) {
        $functionName = 'invalidinvoice';
        /*$inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;*/
        $messageCode = null;
        try{
            if($inputData == null){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //輸入值
           /* if(!$this->CheckInput($inputData)){
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
            }*/

            //取得訂單內容
            $scgData = \App\Models\ICR_ShopCouponData_g::GetData_CouponDataM_LogisticsDetial($inputData['scg_id']);
            $adaminService = new \App\Services\AdminService;
            $PriRepo = new \App\Repositories\ICR_PmReceiptIssue_LogRepository;
            $pril_receiptnumber = $PriRepo->getReceiptNumber($scgData[0]['sd_id'], $inputData['scg_id'] );
            $postdata = [
                'ril_voidreason' =>$inputData['refund_note'],
                'ril_receiptnumber' =>$pril_receiptnumber,
                'ril_shopid' =>$scgData[0]['sd_id'],
                'ril_ordernumber' =>$inputData['scg_id']
            ];
            if ($adaminService ->InvalidInvoice ($postdata, $response, $post) ) {
                $this->updatePrlData($scgData, $inputData, $response, $post);
                $this->updateScgData($scgData);
            }
            $messageCode = '000000000';
       } catch(\Exception $e) {
            if (is_null($messageCode)) {
              $messageCode = '999999999';
              \App\Models\ErrorLog::InsertData($e);
            }
            return false;
         }
         return true;
      /* $resultArray = Commontools::ResultProcess($messageCode, $resultData);
       Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode); 
       $result = [$functionName . 'result' => $resultArray];
       return $result;*/
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }
        
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'refund_note', 0, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scg_id', 0, false, false)) {
            return false;
        }
        
        return true;
    }

    
    
    function updatePrlData($scgData,$inputData,$response,$post) {
        try {
            $PriRepo = new \App\Repositories\ICR_PmReceiptIssue_LogRepository;
            $pril_serno = $PriRepo->getPrilSerno($scgData[0]['sd_id'], $scgData[0]['scg_id'] );
            $saveData =[
                'pril_serno' => $pril_serno,
                'pril_voidrequest'=> json_encode($post),
                'pril_voidresponse'=>urldecode(json_encode($response)),
                'pril_voiddatetime'=>urldecode($response['invalidInvoiceresult']['ecpay_return']['ril_voiddatetime']),
                'pril_voidreason'=>$inputData['refund_note'],
                'pril_voidrtncode'=>$response['invalidInvoiceresult']['ecpay_return']['RtnCode']  ,
                'pril_voidrtnmsg'=> urldecode($response['invalidInvoiceresult']['ecpay_return']['RtnMsg']),
                'pril_receiptstatus' => ($response['invalidInvoiceresult']['ecpay_return']['RtnCode'] == 1) ? 2 : null,
            ];
            return $PriRepo->UpdateData($saveData);
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
    
     function updateScgData($scgData) {
        try {
              $scgData = [ 
                                  'scg_id' => $scgData[0]['scg_id'],
                                  'scg_receipt_status'=>2,
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