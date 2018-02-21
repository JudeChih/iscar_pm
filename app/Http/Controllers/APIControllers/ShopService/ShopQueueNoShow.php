<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** shopqueuenoshow	商家設置未到用戶為失約用戶 * */
class ShopQueueNoShow {
   function shopqueuenoshow() {
        $functionName = 'shopqueuenoshow';
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
            if(!ShopQueueNoShow::CheckInput($inputData)){
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
            //檢查傳入資料，吻合爾否。
            if(!ShopQueueNoShow::Check_Data($inputData['ssqq_id'], $inputData['sd_id'], $ssqd_title, $messageCode, $ssqd_id)) {
               throw new \Exception($messageCode);
            }
            //更新資料
            if(!ShopQueueNoShow::Update_ShopQueQ($inputData['ssqq_id'])) {
               throw new \Exception($messageCode); 
            }
             //查詢下個服務資料
             $ShopService = new  \App\Http\Controllers\APIControllers\ShopService\ShopService;
            if (!$ShopService->query_NextService($inputData['sd_id'], $ssqd_id, $servicedNO, $nextServiceNO, $nextClientID, $nextServiceQueID)) {
               //010917004	暫無次一隊列用戶，請等候新用戶選用服務
               $messageCode = '010917004';
               throw new \Exception($messageCode);
            }
            if (is_null($nextClientID) || strlen($nextClientID) == 0 ) {
               //010917004	暫無次一隊列用戶，請等候新用戶選用服務
               $messageCode = '010917004';
               throw new \Exception($messageCode);
            }
            //推播
            $target = 1;
            $iscar_push = '{"target" :"'. $target. '","id_1":"","id_2":""}';
            if( !$memService->push_notification($inputData['sat'] ,array($nextClientID), null, null, $target, $iscar_push ) ) {
               throw new \Exception($messageCode); 
            }
            //新增資料
            if(!ShopQueueNoShow::Insert_MsLog($nextClientID, $inputData['ssqq_id'], $ssqd_title)) {
               throw new \Exception($messageCode); 
            }
            //用戶過號設置完成，已自動呼叫下一排隊用戶
            $messageCode = '010918000';
         }
        catch(\Exception $e){
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
    
    
    function Check_Data($ssqq_id, $sd_id, &$ssqd_title, &$messageCode, &$ssqd_id) {
        try { 
             $datenow =  $datenow = date("Y-m-d H:i:s");
             $querydata = \App\Models\ICR_ShopServiceQue_q::Query_BYSSQQID($ssqq_id);
             $ssqq_callingtime = $querydata[0]['ssqq_callingtime'];
             if (is_null($querydata) || count($querydata) == 0) {
               //無效的排隊編號，請確認後重發
               $messageCode = '010918001';
               return false;
             }
             if($querydata[0]['sd_id'] != $sd_id) {
              //非貴司所發行之服務，無法操作過號設置
               $messageCode = '010918002';
               return false;
             }
             if(in_array($querydata[0]['ssqq_usestatus'],array(2,5,6,8))) {
               //該用戶已服務完畢，無法設置過號
               $messageCode = '010918003';
               return false;
             }
             if(in_array($querydata[0]['ssqq_usestatus'],array(0,3,4,7))) {
               //非排隊狀態，無法設置過號
               $messageCode = '010918004';
               return false;
             }
             if($querydata[0]['ssqq_calltimes'] < 2) {
              //非貴司所發行之服務，無法操作過號設置
               $messageCode = '010918005';
               return false;
             }
             if(((strtotime($datenow) - strtotime($ssqq_callingtime))/60) < 0.1 /*10*/) {
               //叫號後未達10分鐘以上，無法設置過號
               $messageCode = '010918006';
               return false;
             }
             $ssqd_title = $querydata[0]['ssqd_title'];
             $ssqd_id = $querydata[0]['ssqd_id']; 
             return true;
        } catch (\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return false;
        }
    }
    
    private function Update_ShopQueQ($ssqq_id) {
        try {
            $modifydata['ssqq_usestatus'] = 7;
            $modifydata['ssqq_id'] = $ssqq_id;
            
            return \App\Models\ICR_ShopServiceQue_q::UpdateData($modifydata); 
        } catch(\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return false; 
        }
    }
    
    private function Insert_MsLog($md_id, $ssqq_id, $ssqd_title) {
     try {
       $savadata['uml_type'] = 804;
       $savadata['md_id'] =  $md_id;
       $savadata['uml_message'] = '親愛的用戶你好，服務:'.$ssqd_title.'已到號，請前往接受服務。';
       $savadata['uml_object'] =  ' { "ssqq_id" : "' . $ssqq_id . '"} ' ;  //"{'ssqq_id':'$ssqq_id'}";
       $savadata['uml_pic'] = '';
       $savadata['uml_status'] = 0;

       $appService = new \App\Services\AppService;
       return $appService->PostMessageLog($savadata);
     } catch (\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
          return false;
     }
   }
}