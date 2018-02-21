<?php

namespace App\Http\Controllers\APIControllers\CarNews;
use Illuminate\Support\Facades\Input;
/** postcarreservationask	新增車輛約看詢問記錄 * */
class PostCarreservationAsk {
   function postcarreservationask() {
        $functionName = 'postcarreservationask';
        $inputString = Input::All();
        $inputData = \App\Library\Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
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
            $querydata = \App\Models\ICR_CarBasicInfo::GetDataByCbiid($inputData['cbi_id']);
            if(is_null($querydata) || count($querydata) == 0 ) {
               //查無該車輛，請確認後重發
               $messageCode = '011104001';
               throw new \Exception($messageCode);
            }
            if(count($querydata) > 1 ) {
                //車輛記錄大於一筆，請聯絡iscar管理員進行處理
               $messageCode = '011104002';
               throw new \Exception($messageCode);
            }
            if (!$this->InsertCarreservation ($crn_id,$inputData['cbi_id'], $md_id, $querydata[0]['cbi_postownertype'], $querydata[0]['cbi_owner_id'], $inputData['crn_buyer_realname'], $inputData['crn_buyer_ask_message'], $inputData['crn_available_timearray'], $inputData['crn_carinstore_ask'])) {
                //約看詢問，發送失敗，請稍後再試
               $messageCode = '011104003';
               throw new \Exception($messageCode);
            }
            if ($querydata[0]['cbi_postownertype'] == 0 ) {
                $arrayMd_id =   \App\Models\ICR_SdmdBind::GetDataByMdid_Type($querydata[0]['cbi_owner_id']);
            } else if ($querydata[0]['cbi_postownertype'] == 1 ) {
                $arrayMd_id[0] = ['md_id' => $querydata[0]['cbi_owner_id']];
            }
            $target = 1;
            $iscar_push = '{"target" :"'. $target. '","id_1":"","id_2":""}';
            $memService->push_notification($inputData['sat'],$arrayMd_id, null, null, $target, $iscar_push);
            foreach ($arrayMd_id as $row) {
                if (!$this->InsertMsLog_901($row['md_id'], $crn_id, $inputData['cbi_id'], $querydata[0]['cps_picpath'])) {
                    throw new \Exception($messageCode);
                }
            }

             //約看詢問發送完成，請等候賣家回覆
            $messageCode ='011104000';
         } catch(\Exception $e){
           if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($e);
            }
        }
        $resultArray = \App\Library\Commontools::ResultProcess($messageCode, $resultData);
        \App\Library\Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'crn_buyer_realname', 20, false, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'crn_buyer_ask_message', 140, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'crn_available_timearray', 0, false, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'crn_carinstore_ask', 1, false, false)) {
            return false;
        }
        return true;
    }
    
   private function InsertCarreservation (&$crn_id, $cbi_id, $md_id, $type, $owner_id, $buyer_name, $message, $timearray, $carinstore_ask) {
       try {
         $crn_id = \App\Library\Commontools::NewGUIDWithoutDash();
         $insertdata = [
                           'crn_id'                  => $crn_id,
                           'cbi_id'                  => $cbi_id,
                           'crn_buyer_md_id'         => $md_id,
                           'crn_ownertype'           => $type,
                           'crn_owner_id'            => $owner_id,
                           'crn_buyer_realname'      => $buyer_name,
                           'crn_buyer_ask_message'   => $message,
                           'crn_available_timearray' => $timearray,
                           'crn_carinstore_ask'      => $carinstore_ask
                       ];
         return    \App\Models\ICR_Carreservation::InsertData($insertdata);
       } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
         return false;
       }
   }
   
    private function InsertMsLog_901($md_id, $crn_id, $cbi_id, $cps_picpath) {
     try {
            $savadata['uml_type'] = 901;
            $savadata['md_id'] =  $md_id;
            $savadata['uml_message'] = '親愛的賣家你好，您的車輛已收到買家約看通知，請點擊回復約看時間。';
            $savadata['uml_object'] = "{\"crn_id\":\"$crn_id\",\"cbi_id\":\"$cbi_id\"}";
            $savadata['uml_pic'] = $cps_picpath;
            $savadata['uml_status'] = 0;
            return  \App\Library\Commontools::PostMessageLog($savadata);
     } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
     }
    }
}