<?php

namespace App\Http\Controllers\APIControllers\Invoice;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/**createinvoice 	 **/
class Create_Invoice {
   function createinvoice($inputData) {
        $functionName = 'createinvoice';
        /*$inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }*/
        $resultData = null;
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
            $suntechData = Commontools::ConvertStringToArray($scgData[0]['respone_payment_json']);
            $postdata = [
                'md_id' => $inputData['md_id'],
                'ril_shopid'=>$scgData[0]['sd_id'],
                'ril_customeridentifier'=>$inputData['identifier'],
                'ril_ordernumber' =>$inputData['scg_id'],
                'ril_ordercreatedate' =>$scgData[0]['scg_create_date'],
                'ril_orderpaydate'=>$scgData[0]['scg_paid_time'],
                'ril_customeraddr' =>$inputData['addr'],
                'ril_customerphone' =>$inputData['phone'], 
                'ril_customeremail' =>$inputData['email'],
                'CustomerName' => $inputData['md_cname'],
                'InvoiceRemark' =>$suntechData['Card_NO'],
                'ItemName' =>$scgData[0]['scm_title'],
                'ItemCount' =>$scgData[0]['scg_buyamount'],
                'ItemPrice' =>$scgData[0]['scg_subtract_totalamount'],
                'ItemAmount' =>$scgData[0]['scg_subtract_totalamount'],
                'SalesAmount' =>$scgData[0]['scg_subtract_totalamount'],
                'RelateNumber' =>$inputData['scg_id'],
            ];
            if ($adaminService ->CreateInvoice ($postdata, $response, $post, $messageCode) ) {
                $this->updateScgData($scgData);
            }
            $this->insertPrlData($scgData,$inputData,$response,$post);
            if (is_null($messageCode)) {
                $messageCode = '000000000';   
            }
       } catch(\Exception $e) {
            if (is_null($messageCode)) {
              $messageCode = '999999999';
              \App\Models\ErrorLog::InsertData($e);
            }
         }
         $resultArray = Commontools::ResultProcess($messageCode, $resultData);
         Commontools::WriteExecuteLog($functionName, json_encode($response), json_encode($resultArray), $messageCode); 
         if ($messageCode != '000000000') {
             return false;
         }
         return true;
       /* $result = [$functionName . 'result' => $resultArray];
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
       /* if (!\App\library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }*/
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scg_id', 0, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'identifier', 0, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'addr', 0, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'phone', 0, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'email', 0, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'md_cname', 0, false, false)) {
            return false;
        }
        
        
        
        return true;
    }

    
    
    function insertPrlData($scgData,$inputData,$response,$post) {
        try {
            $PriRepo = new \App\Repositories\ICR_PmReceiptIssue_LogRepository;
            //\App\Models\ErrorLog::InsertLog("1111");
            $saveData =[
                'sd_id'=>$scgData[0]['sd_id'],
                'pril_trade_type'=>'1',  
                'pril_ordernumber'=>$scgData[0]['scg_id'],
                'pril_customeridentifier'=>$inputData['identifier'],
                'pril_customerphone'=>$inputData['phone'],
                'pril_customeremail'=>$inputData['email'],
                'pril_customeraddr'=>$inputData['addr'],
                'pril_issueresult'=>($response['createInvoiceresult']['ecpay_return']['RtnCode'] == 1) ? 1 : 0,
                'pril_receiptnumber'=>$response['createInvoiceresult']['ecpay_return']['InvoiceNumber'],
                'pril_invoicedate'=> urldecode($response['createInvoiceresult']['ecpay_return']['InvoiceDate']),
                'pril_randomnumber'=>$response['createInvoiceresult']['ecpay_return']['RandomNumber'],
                'pril_issuereturncode'=>$response['createInvoiceresult']['ecpay_return']['RtnCode'],
                'pril_issuertnmsg'=>urldecode($response['createInvoiceresult']['ecpay_return']['RtnMsg']),
                'pril_receiptstatus'=>($response['createInvoiceresult']['ecpay_return']['RtnCode'] == 1) ? 1 : 0 ,
                'pril_issuerequest'=> json_encode($post),
                'pril_issueresponse'=>urldecode(json_encode($response)),
            ];
            \App\Models\ErrorLog::InsertLog(json_encode($saveData));
            return $PriRepo->InsertData($saveData);
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
    
     function updateScgData($scgData) {
        try {
              $scgData = [ 
                                  'scg_id' => $scgData[0]['scg_id'],
                                  'scg_receipt_status'=>1,
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