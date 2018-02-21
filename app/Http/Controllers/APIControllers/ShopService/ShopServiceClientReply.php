<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** shopserviceclientreply	被叫號用戶回覆前往狀況 * */
class ShopServiceClientReply {
    function shopserviceclientreply(){
        $functionName = 'shopserviceclientreply';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try {
             if($inputData == null){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //輸入值
            if(!ShopServiceClientReply::CheckInput($inputData)){
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
            $querydata = \App\Models\ICR_ShopServiceQue_q::Query_FindData_BySSQQID($inputData['ssqq_id']);
            if(is_null($querydata) || count($querydata) == 0) {
               //服務編號有誤，請確認後重發
               $messageCode = '010913001';
               throw new \Exception($messageCode);
            }
            if($querydata[0]['md_id'] != $md_id) {
               //服務編號有誤，請確認後重發
               $messageCode = '010913001';
               throw new \Exception($messageCode);
            }
            if($inputData['reply_answer'] == 0 ) {
               if(!ShopServiceClientReply::WillArrive($inputData['sat'], $querydata[0]['sd_id'], $querydata[0]['ssqq_queserno'], $messageCode)) {
                 throw new \Exception($messageCode);
               }
            }
            if($inputData['reply_answer'] == 1 ) {
               if(!ShopServiceClientReply::WontBeAbleToGo($inputData['sat'] , $querydata[0]['sd_id'], $querydata[0]['ssqq_queserno'], $inputData['ssqq_id'], $messageCode)) {
                 throw new \Exception($messageCode);
               }
            }
        } catch (\Exception $e) {
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqq_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'reply_answer', 1, false, false)) {
            return false;
        } 
        return true;
    }
    
    private function Insert_MsLog811($md_id ,$ssqq_queserno) {
        try {
            $savadata['uml_type'] = 811;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = "排隊編號:".$ssqq_queserno."用戶回復即將抵達，請稍後。";
            $savadata['uml_object'] = null;
            $savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
            $appService = new \App\Services\AppService;
            return $appService->PostMessageLog($savadata);
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        } 
    }
    private function Insert_MsLog812($md_id ,$ssqq_queserno) {
        try { 
            $savadata['uml_type'] = 812;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = "排隊編號:".$ssqq_queserno."用戶回復無法前往，已設置過號用戶，請自行選擇呼叫用戶。";
            $savadata['uml_object'] = null;
            $savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
            $appService = new \App\Services\AppService;
            return $appService->PostMessageLog($savadata);
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        } 
    }
    private function Update_ShopServiceQueQ ($ssqq_id) {
        try {
           $modifydata['ssqq_usestatus'] = 7;
           $modifydata['ssqq_id'] = $ssqq_id;
           return \App\Models\ICR_ShopServiceQue_q::UpdateData($modifydata); 
         } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        } 
    }
    
    public function WillArrive($sat, $sd_id, $ssqq_queserno,&$messageCode) {
        try {
            $memService = new \App\Services\MemberService;
            $querydata = \App\Models\ICR_SdmdBind::GetData_BySD_ID($sd_id,true);
            foreach ($querydata as $rowdata) {
             /* if(!Commontools::PushNotificationFromMem($rowdata['smb_md_id'])) {
                return false;
              }*/
              $target = 1;
              $iscar_push = '{"target" :"'. $target. '","id_1":"","id_2":""}';
              if(!$memService->push_notification(   $sat ,array($rowdata['smb_md_id']) , null, null, $target, $iscar_push ) ) {
               throw new \Exception('999999999');
              }
              if(!ShopServiceClientReply::Insert_MsLog811($rowdata['smb_md_id'], $ssqq_queserno)) { 
                return false;
              } 
            }
            //已通知商家[即將前往，請注意安全]
            $messageCode = '010923001';
        } catch(\Exception $e) {
           \App\Models\Errorlog::InsertData($e);
           return false;
        }
    }
    
    function WontBeAbleToGo($sat, $sd_id, $ssqq_queserno, $ssqq_id, &$messageCode) {
        try {
             $memService = new \App\Services\MemberService;
             $querydata = \App\Models\ICR_SdmdBind::GetData_BySD_ID($sd_id,true);
             $target = 1;
            $iscar_push = '{"target" :"'. $target. '","id_1":"","id_2":""}';
             foreach ($querydata as $rowdata) {
              if(!$memService->push_notification(   $sat ,array($rowdata['smb_md_id']) , null, null, $target, $iscar_push ) ) {
               return false;
              }
              if(!ShopServiceClientReply::Insert_MsLog812($rowdata['smb_md_id'], $ssqq_queserno)) {
                return false;
              }
              if(!ShopServiceClientReply::Update_ShopServiceQueQ($ssqq_id)) {
                return false;
              } 
            }
            //已通知商家[無法前往，並設置為過號]
            $messageCode = '010923002';
            return true;
        } catch(\Exception $e) {
           \App\Models\Errorlog::InsertData($e);
           return false;
        }
    }
  
}