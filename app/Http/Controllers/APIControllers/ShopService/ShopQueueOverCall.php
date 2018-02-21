<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** shopqueueovercall	商家呼叫過號用戶前往接受服務 * */
class ShopQueueOverCall {
   function shopqueueovercall() {
        $functionName = 'shopqueueovercall';
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
            if(!ShopQueueOverCall::CheckInput($inputData)){
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
             //檢查「店家」、「管理員」權限
            if (!\App\library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            $querydata = \App\Models\ICR_ShopServiceQue_q::Query_BYSSQQID($inputData['ssqq_id']);
            if($querydata == null || count($querydata) == 0 ) {
              //憑證號碼無效，請重新輸入
              $messageCode = '010916001';
              throw new \Exception($messageCode);
            }
            if($querydata[0]['sd_id'] != $inputData['sd_id']) {
              //非貴司所發行之服務，請通知用戶前往正確商家使用
              $messageCode = '010916002';
              throw new \Exception($messageCode);
            }
            if($querydata[0]['ssqq_usestatus'] != 7 ) {
              //非過號隊列用戶無法進行呼叫
              $messageCode = '010921001';
              throw new \Exception($messageCode);
            }
             $target = 1;
            $iscar_push = '{"target" :"'. $target. '","id_1":"","id_2":""}';
            if(!$memService->push_notification(  $inputData['sat'] ,array($querydata[0]['md_id']) , null, null, $target, $iscar_push ) ) {
               throw new \Exception($messageCode); 
            }
            if(!ShopQueueOverCall::Insert_MsLog($querydata[0]['md_id'] ,$inputData['ssqq_id'],$querydata[0]['ssqd_title'])) {
              throw new \Exception($messageCode); 
            }
            
            //叫號完成，請等候用戶前往
            $messageCode = '010921000';
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'ssqq_id', 32, false, false)) {
            return false;
        }
        return true;
    }
    
    private function Insert_MsLog($md_id ,$ssqq_id,$ssqd_title) {
      try {
            $savadata['uml_type'] = 805;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = "親愛的用戶你好，服務:".$ssqd_title."可開始服務，請前往商家開始服務。";
            $savadata['uml_object'] = "{\"ssqq_id\":\"$ssqq_id\"}";
            $savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
            $appService = new \App\Services\AppService;
            return $appService->PostMessageLog($savadata);
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        } 
    }
}