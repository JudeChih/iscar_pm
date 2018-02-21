<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** shopservicequeask	用戶選擇服務項目進行排隊 * */
class ShopServiceQueask {

    function shopservicequeask(){
        $functionName = 'shopservicequeask';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        $quecount = null;
        try{
            if($inputData == null){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //輸入值
            if(!ShopServiceQueask::CheckInput($inputData)){
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
            $result = \App\Models\ICR_ShopServiceQue_d::Query_ShopServiceData($inputData['ssqd_id']);
            if(!ShopServiceQueask::Check_ShopQueData($result,$messageCode,$quecount)) {
               throw new \Exception($messageCode);
            }
            //新增排隊服務項目
            if(!\App\Models\ICR_ShopServiceQue_q::InsertData(ShopServiceQueAsk::InsertData_ShopServiceQueQ($result[0]["sd_id"], $md_id,$quecount,$inputData,$ssqq_id))) {
               throw new \Exception($messageCode);
            }
             //判斷商家管理會員綁定，有無資料，有資料並新增通知資料
            if(!ShopServiceQueAsk::CountOf_SdmdBind($result[0]["sd_id"], $result[0]["ssqd_title"], $result[0]['ssqd_mainpic'], $quecount, $inputData['sat'])) { 
               throw new \Exception($messageCode);
            }
            $messageCode = '010914000';
            $resultData['ssqq_id'] = $ssqq_id;
            $resultData['ssqq_queserno'] = $quecount +1;
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqd_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ci_serno', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqq_queremark', 140, true, true)) {
            return false;
        }
        return true;
    }
    
    /**檢查resultData狀態
     */
    private function Check_ShopQueData($result,&$messageCode,&$quecount) {
        try{
            //服務編號有誤，請確認後重發
            if($result == null || count($result) == 0) {
               $messageCode = '010913001';
               return false;
            }
            //該商家已暫停排隊服務，請隨時關注該商家近況
            if($result[0]['ssqm_servicestart'] == 0) {
               $messageCode = '010914001';
               return false;
            }
            //該服務項目已停止提供，請隨時關注該商家近況
            if($result[0]['ssqd_effectivity'] == 0) {
               $messageCode = '010914002';
               return false;
            }
            if(!ShopServiceQueAsk::Check_ShopQueDateTime($result[0]['ssqm_dailystart'],$result[0]['ssqm_dailyend']
            ,$result[0]['ssqm_weekstart'],$result[0]['ssqm_weekend'],$result[0]['ssqm_dayoffarray'])) {
               $messageCode = '010914003';
               return false;
            } 
            //當前隊列人數已滿，請稍候再試
            if(\App\Models\ICR_ShopServiceQue_q::Query_QueueCountData($result[0]['sd_id'], $result[0]['ssqd_id'], $quecount, $serviced)) {
                if($serviced > $result[0]['ssqd_maxqueueamount']) {
                   $messageCode = '010914004';
                   return false;
                }
            } else {
               throw new \Exception($messageCode);
            }   
            return true;
        } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
        }
    }
    /**檢查resultData狀態  DateTime
     */
    private function Check_ShopQueDateTime($ssqm_dailystart,$ssqm_dailyend,$ssqm_weekstart,$ssqm_weekend,$ssqm_dayoffarray) {
        try {
            $datenow = new \DateTime('now');
            $datetime = date("H:i:s");
            $dailystart = new \DateTime($ssqm_dailystart);
            $dailyend = new \DateTime($ssqm_dailyend);
            //非服務時間，請於商家服務時間，點選排隊
            if($ssqm_dailystart > $datetime || $datetime > $ssqm_dailyend) {
               return false;
            }
            $strnow = $datenow->format('Y-m-d'); 
            $weekday = date('w', strtotime($strnow));
            $weeklist = array( '', '一', '二', '三', '四', '五', '六','日');
            $weekstart = array_search($ssqm_weekstart,$weeklist);
            $weekend = array_search($ssqm_weekend,$weeklist);
             //非服務時間，請於商家服務時間，點選排隊(是否不在星期區間)
            if($weekstart > $weekday || $weekday > $weekend ) {
               return false;
            }  
            //非服務時間，請於商家服務時間，點選排隊(是否為休息日)
            if (false !== (strpos($ssqm_dayoffarray, $strnow))) {
                return false;
            }
            return true;
        } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    
    }
    
    /**
     *
     * 重整需Insert資料
     */
     private function InsertData_ShopServiceQueQ($sd_id,$md_id,$quecount,$inputData,&$ssqq_id){
         try {
              $ssqq_id = Commontools::NewGUIDWithoutDash();
              $InsertData["ssqq_id"] = $ssqq_id;
              $InsertData["ssqq_queserno"] = $quecount + 1;
              $InsertData["md_id"] = $md_id;
              $InsertData["sd_id"] = $sd_id;
              $InsertData["ssqd_id"] = $inputData['ssqd_id'];
              $InsertData["ci_serno"] = $inputData['ci_serno'];
              $InsertData["ssqq_queremark"] = $inputData['ssqq_queremark'];
              $InsertData["ssqq_usestatus"] = 1;
              $InsertData["ssqq_questarttime"] = (new \DateTime('now'))->format('Y-m-d');
              return $InsertData;
              
         } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e); 
         }
     }
     
      /**計算 SdmdBind 數量
       * $params type $sd_id
       * $params string $ssqd_title
       * $params int  $quecount
       */
       function CountOf_SdmdBind($sd_id, $ssqd_title, $pic, $quecount, $sat)
       {
          try {
               $result = \App\Models\ICR_SdmdBind::GetData_BySD_ID($sd_id,true);
               $memService = new \App\Services\MemberService;
               $Md_Id_Array = array();
               foreach($result as $row) {
                 if(!ShopServiceQueAsk::InsertData_IsCarUserMessageLog($row['smb_md_id'],$ssqd_title, $pic, $quecount)) {
                      return false;
                 } 
                 array_push($Md_Id_Array,$row['smb_md_id']);
               }
                $target = 1;
                $iscar_push = '{"target" :"'.$target.'","id_1":"","id_2":""}';
                $memService->push_notification($sat, $Md_Id_Array, null, null, $iscar_push, $target);
               return true;
          } catch(\Exception $e) {
               \App\Models\ErrorLog::InsertData($e);
               return false;
          }
       }
       
       
       /**
        *
        *
        */
        private function InsertData_IsCarUserMessageLog($md_id, $ssqd_title, $pic, $quecount) {
           try {
                $savadata['uml_type'] = 801;
                $savadata['md_id'] = $md_id;
                $savadata['uml_message'] = "用戶選用" .$ssqd_title."服務加入排隊，目前總排隊人數".($quecount + 1)."人";
                $savadata['uml_object'] = null;
                $savadata['uml_pic'] = $pic;
                $savadata['uml_status'] = 0;

                $appService = new \App\Services\AppService;
                return $appService->PostMessageLog($savadata);
           } catch(\Exception $ex) {
               \App\Models\ErrorLog::InsertData($ex); 
               return false;
           }
        }
    
    
}