<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
define('ShopData_FTP_Img_Path', config('global.ShopData_FTP_Img_Path'));
/** shoponoffoperation	商家通知伺服器當日服務啟動終止操作 * */
class ShopOnOffOperation {
    function shoponoffoperation() {
        $functionName = 'shoponoffoperation';
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
            if(!ShopOnOffOperation::CheckInput($inputData)){
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
            if (!\App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            $querydata = \App\Models\ICR_ShopServiceQue_m::GetData($inputData['sd_id']);
            if($querydata == null || count($querydata) == 0 ) {
               //無啟用記錄，請使用新增功能先行新增
               $messageCode = '010915002';
               throw new \Exception($messageCode);
            }
            if ($inputData['operation'] == 0) {
               if(!ShopOnOffOperation::ShopService_Start($inputData['sd_id'], $querydata[0]['ssqm_servicestart'],$querydata[0]['ssqm_dailyend'], $resultData, $messageCode)) {
                 throw new \Exception($messageCode);
               }
            } else if ($inputData['operation'] == 1 ) {
               if(!ShopOnOffOperation::ShopService_Stop($inputData['sd_id'], $querydata[0]['ssqm_servicestart'], $resultData, $messageCode)) {
                 throw new \Exception($messageCode);
               }
            } else if ($inputData['operation'] == 2 ) {
               if(!ShopOnOffOperation::ShopService_End( $inputData['sat'], $inputData['sd_id'], $querydata[0]['ssqm_servicestart'], $resultData, $messageCode)) {
                 throw new \Exception($messageCode);
               }
            } else {
              //錯誤的作業內容，請重新選擇
                $messageCode = '999999989';
                throw new \Exception($messageCode);
            }
            
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
        if (!Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }
        if (!Commontools::CheckRequestArrayValue($value, 'operation', 0, false, false)) {
            return false;
        }
        return true;
    }
    
    /**更新資料---[ICR_ShopServiceQue_M]
     * @param type $sd_id
     * @param type $ssqm_servicestart
     * @return boolean
     */
    private function Update_ShopServiceQueM($sd_id, $ssqm_servicestart) {
        try {
            $modifydata['sd_id'] = $sd_id;
            $modifydata['ssqm_servicestart'] = $ssqm_servicestart;
            return \App\Models\ICR_ShopServiceQue_m::UpdateData($modifydata);
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
    /**更新資料---[ICR_ShopServiceQue_Q]
     * @param type $sd_id
     * @return boolean
     */ 
    private function Update_ShopServiceQueQ($sd_id) {
        try {
            $modifydata['ssqq_status'] = 4;
            $modifydata['sd_id'] = $sd_id;
            $modifydata['ssqq_questarttime'] = date('Y-m-d');
            $modifydata['ssqq_usestatus'] = 7;
            return \App\Models\ICR_ShopServiceQue_q::Update_BySdId($modifydata);
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
    /** 新增用戶通知紀錄資料，服務開始步驟
     * @param type $md_id
     * @param type $ssqq_id 
     * @param type $ssqd_title 
     * @return boolean
     */ 
    private function Insert_MsLog_StartStep($md_id, $ssqq_id, $ssqd_title) {
        try {
            $savadata['uml_type'] = 805;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = '親愛的用戶你好，服務:'.$ssqd_title.'，已開始服務，請前往商家接受服務。';
            $savadata['uml_object'] = '{"ssqq_id" : "' . $ssqq_id . '"} ';// '{"ssqq_id":"'.$ssqq_id.'"}';
            $savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
             $appService = new \App\Services\AppService;
             return $appService->PostMessageLog($savadata);
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
    /** 推播用戶，服務開始步驟
     * @param type $sd_id
     * @param type $ssqd_id 
     * @param type $judge 
     * @return boolean
     */ 
    public function ShopService_StartStep($sat, $sd_id, $ssqd_id, &$judge) {
        try {
            if (ShopService::query_NextService($sd_id, $ssqd_id, $servicedNO, $nextServiceNO, $nextClientID, $nextServiceQueID)) {
               $judge = true;
               //推播
               $target = 1;
               $iscar_push = '{"target" :"'. $target. '","id_1":"","id_2":""}';
               $memService = new \App\Services\MemberService;
               if(!$memService->push_notification($sat, array(nextClientID), null, null, $target, $iscar_push) ) {
                  return false; 
               }
               $querydata = \App\Models\ICR_ShopServiceQue_q::Query_BYSSQQID($nextServiceQueID);
               if(!ShopOnOffOperation::Insert_MsLog_StartStep($nextClientID, $nextServiceQueID, $querydata[0]['ssqd_title'])) {
                  return false; 
               } 
            } 
            return true;
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
    /**
     * 新增用戶通知紀錄資料，服務結束步驟
     * @param type $md_id
     * @param type $ssqq_id 
     * @return boolean
     */
    private function Insert_MsLog_EndStep($md_id, $ssqq_id, $ssqd_mainpic){
        try {
            $savadata['uml_type'] = 808;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = "排隊服務使用完畢，請填寫使用評價。";
            $savadata['uml_object'] =  '{"Event_type" : "1" ,"Event_id" : "' . $ssqq_id . '"} ';//"{\"Event_type\":\"1\",\"Event_id\":\"$ssqq_id\"}";
            $savadata['uml_pic'] = ShopData_FTP_Img_Path . $ssqd_mainpic;
            $savadata['uml_status'] = 0;
            $appService = new \App\Services\AppService;
            return $appService->PostMessageLog($savadata);
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        } 
    }
    
    private function Insert_MsLog_EndStep02($md_id, $ssqd_title) {
        try {
            $savadata['uml_type'] = 809;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = "親愛的用戶你好，服務:".$ssqd_title."，由於商家服務結束，服務排隊已移至次營業日，請注意叫號。";
            //$savadata['uml_object'] = null;
            //$savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
            $appService = new \App\Services\AppService;
            return $appService->PostMessageLog($savadata);
        } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    } 
    /**
     * 使用者使用服務後流程。
     * @param type $sd_id
     * @return boolean
     */
    function ShopServiceAfterUse ($sd_id) {
        try {
            $querydata = \App\Models\ICR_ShopServiceQue_q::Query_FindCompleteService($sd_id);
            foreach($querydata as $rowdata) {
               if(!ShopOnOffOperation::Insert_MsLog_EndStep($rowdata['md_id'], $rowdata['ssqq_id'], $rowdata['ssqd_mainpic'])) {
                  return false;
               }
            } 
            return true;
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
    /**
     * 當服務結束，查詢下一個服務日期
     * @param type $sd_id
     * @param type $date 
     * @return boolean
     */
    function NextServiceDay($sd_id, &$strnow) {
        try {
            $date = new \DateTime('now');
            $querydata = \App\Models\ICR_ShopServiceQue_m::GetData($sd_id);
            $ssqm_weekstart = $querydata[0]['ssqm_weekstart'];
            $ssqm_weekend = $querydata[0]['ssqm_weekend'];
            $ssqm_dayoffarray =  $querydata[0]['ssqm_dayoffarray'];
            $weeklist = array('日', '一', '二', '三', '四', '五', '六');
            $weekstart = array_search($ssqm_weekstart,$weeklist);
            $weekend = array_search($ssqm_weekend,$weeklist);
            $decideTrue = false;
            for($x=1; $x <=365; $x++){
                 $date -> add(new \DateInterval('P1D'));;
                 $strnow = $date -> format('Y-m-d');
                 $weekday = date('w', strtotime($strnow));
                 if($weekstart <= $weekday && $weekday <= $weekend ) {
                    if (false === (strpos($ssqm_dayoffarray, $strnow))) {
                        $decideTrue = true;
                        break;
                    } 
                 }  
            } 
            return $decideTrue;
          } catch (\Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }
    
    private function Update_ShopServiceQueQ_EndStep($ssqq_id, $ssqq_queserno, $ssqq_questarttime, $ssqq_movetimes) {
        try {
          $modifydata['ssqq_queserno'] = $ssqq_queserno;
          $modifydata['ssqq_questarttime'] = $ssqq_questarttime;
          $modifydata['ssqq_movetimes'] = $ssqq_movetimes;
          $modifydata['ssqq_id'] = $ssqq_id;
          
          return \App\Models\ICR_ShopServiceQue_q::UpdateData($modifydata); 
        } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
    
    function ShopService_Start($sd_id, $ssqm_servicestart,$ssqm_dailyend, &$resultData, &$messageCode) {
        try {
            $judge = false;
            if($ssqm_servicestart == 1) {
               //服務狀態已變更，無需重新操作，請更新狀態
               $messageCode = '010920001';
               return false;
            }
            if(!ShopOnOffOperation::Update_ShopServiceQueM($sd_id, 1)) {
               return false;
            }
            $querydata = \App\Models\ICR_ShopServiceQue_q::Query_FindTodayShopService($sd_id);
            foreach($querydata as $rowdata) {
               if(!ShopOnOffOperation::ShopService_StartStep( $inputData['sat'], $sd_id, $rowdata['ssqd_id'], $judge)) {
                  return false;
               }
            } 
            if($judge == false) {
               //010917004	暫無次一隊列用戶，請等候新用戶選用服務
               $messageCode = '010917004';
            }
            if (is_null($messageCode)) {
               $messageCode = '000000000'; 
            }
            $resultData['ssqm_dailyend'] = $ssqm_dailyend;
            return true;
        } catch (\Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
    
    function ShopService_Stop($sd_id, $ssqm_servicestart, &$resultData, &$messageCode) {
        try {
            if($ssqm_servicestart == 0) {
               //服務狀態已變更，無需重新操作，請更新狀態
               $messageCode = '010920001';
               return false;
            }
            if(!ShopOnOffOperation::Update_ShopServiceQueM($sd_id, 0)) {
               return false;
            }
            if(!ShopOnOffOperation::Update_ShopServiceQueQ($sd_id)) {
               return false;
            }
            //排隊服務已設置暫停，若需繼續叫號請先啟動服務
            $messageCode = '010920002';
            $resultData['ssqm_dailyend'] = null;
            return true;
        } catch (\Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
    
    function ShopService_End($sat, $sd_id, $ssqm_servicestart, &$resultData, &$messageCode) {
        $ssqq_queserno = 1;
        $memService = new \App\Services\MemberService;
        try {
            if($ssqm_servicestart == 0) {
               //服務狀態已變更，無需重新操作，請更新狀態
               $messageCode = '010920001';
               return false;
            }
            if(!ShopOnOffOperation::Update_ShopServiceQueM($sd_id, 0)) {
               return false;
            }
            //取值，今日未完成服務。
            $querydata = \App\Models\ICR_ShopServiceQue_q::Query_StopShopService($sd_id);
            if($querydata == null || count($querydata) == 0 ) {
               if(ShopOnOffOperation::ShopServiceAfterUse($sd_id)) {
                  //今日排隊服務已終止，次日開始前請先啟動服務
                  $messageCode = '010920003';
                  return false;
               } 
              return false;    
            }
            if(ShopOnOffOperation::NextServiceDay($sd_id, $date)) {
               $target = 1;
               $iscar_push = '{"target":"'. $target. '","id_1":"","id_2":""}';
               foreach($querydata as $rowdata) {
                 if(!ShopOnOffOperation::Update_ShopServiceQueQ_EndStep($rowdata['ssqq_id'], $ssqq_queserno, $date, ((int)$rowdata['ssqq_movetimes'] + 1))) {
                     return false; 
                  }
                  $ssqq_queserno ++;
                  if(!ShopOnOffOperation::Insert_MsLog_EndStep02($rowdata['md_id'], $rowdata['ssqd_title'])) {
                     return false;
                  } 
                  if(   ! $memService->push_notification($sat ,array($rowdata['md_id']), null, null, $target, $iscar_push) ) {
                     return false;
                  } 
               }
            } else {
              return false;
            }  
            if(!ShopOnOffOperation::ShopServiceAfterUse($sd_id)) {
              return false;
            } 
            
            //今日排隊服務已終止，次日啟動服務後將自動叫號
            $messageCode = '010920004';
            $resultData['ssqm_dailyend'] = null;
            return true;
        } catch (\Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }   
}