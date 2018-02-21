<?php

namespace App\Http\Controllers\APIControllers\ShopSettleMentrec;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** update_settlementview	更新店家覆核狀態 * */
class Update_SettleMentView {
   function update_settlementview() {
        $functionName = 'update_settlementview';
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
            if(!$this->CheckInput($inputData)){
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
            if (!Commontools::CheckShopUserIdentity( $inputData['sat'], $inputData['sd_id'], $md_id, $shopdata , $messageCode)) {
                throw new \Exception($messageCode);
            }
            
            if(!$this->checkData($inputData['sd_id'],$inputData['ssrm_id'])) {
                 $messageCode = '011802001';
                  throw new \Exception($messageCode);
            }
           if (! $this -> UpdateSsrmData($inputData['settlementreview'], $inputData['ssrm_id'])) {
                 $messageCode = '999999999';
                  throw new \Exception($messageCode);
           }
           $mailController = new  \App\Http\Controllers\APIControllers\MailController;
           $mailController->shopsettlementrec_sendMail ($inputData['ssrm_id']);
            
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'ssrm_id', 20, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'settlementreview', 1, false, false)) {
            return false;
        }
        
        return true;
    }
    
    
    
    function checkData($sd_id, $ssrm_id) {
        try {
            $ssrmRepo = new \App\Repositories\ICR_ShopSettleMentrec_mRepository;
            $ssrmData = $ssrmRepo -> GetDataBySsrmId_SdId($ssrm_id, $sd_id);
            if ( count($ssrmData) == 0 || $ssrmData[0]['ssrm_settlementcomplete'] != 1 || $ssrmData[0]['ssrm_settlementreview'] != 0 ) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
        }
        
    }
  
    
    function UpdateSsrmData($settlementreview, $ssrm_id) {
        try {
            $ssrmRepo = new \App\Repositories\ICR_ShopSettleMentrec_mRepository;
            $saveData = [
              'ssrm_id' => $ssrm_id,
              'ssrm_settlementreview' =>$settlementreview
            ];
            return  $ssrmRepo -> UpdateData( $saveData);
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
        }
        
    }
    
    
    
}