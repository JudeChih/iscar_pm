<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** shopservicecallup	商家呼叫到號用戶開始服務 * */
class ShopServiceCallUp {
   function shopservicecallup() {
        $functionName = 'shopservicecallup';
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
            if(!ShopServiceCallUp::CheckInput($inputData)){
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
             //查詢下個服務資料
             $ShopService = new ShopService;
            if (!$ShopService->query_NextService($inputData['sd_id'], $inputData['ssqd_id'], $servicedNO, $nextServiceNO, $nextClientID, $nextServiceQueID)) {
               //010919001	目前無排隊中用戶，請等候新用戶加入
               $messageCode = '010919001';
               throw new \Exception($messageCode);
            }
            if (is_null($nextClientID) || strlen($nextClientID) == 0 ) {
               ///010919001	目前無排隊中用戶，請等候新用戶加入
               $messageCode = '010919001';
               throw new \Exception($messageCode);
            }
            //查詢下個服務是否叫號過。
             if(!ShopServiceCallUp::Check_ShopServiceQ($nextServiceQueID, $ssqq_calltimes, $ssqd_title, $ssqd_mainpic)) {
               //距前次叫號未達五分鐘，請稍候再叫號
               $messageCode = '010919006';
               throw new \Exception($messageCode);
             }
             //更新服務資料
             if(!ShopServiceCallUp::Update_ShopServiceQueQ($nextServiceQueID, $ssqq_calltimes)) {
               throw new \Exception($messageCode);
             }
             //推播
             $target = 1;
            $iscar_push = '{"target" :"'. $target. '","id_1":"","id_2":""}';
             if(!$memService->push_notification( $inputData['sat'] ,array($nextClientID) , null, null, $target, $iscar_push ) ) {
               throw new \Exception($messageCode); 
             }
             if(!ShopServiceCallUp::Insert_MsLog($nextClientID, $ssqd_title, $nextServiceQueID, $ssqd_mainpic)) {
               throw new \Exception($messageCode);
             }
             if($ssqq_calltimes >= 2) {
               $messageCode = '010919007';

             } else {
               $messageCode = '010919000';
             }
             $resultData['nextservicequeid'] = $nextServiceQueID;
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqd_id', 32, false, false)) {
            return false;
        }
        return true;
    }
    /**
     * 查詢下個服務是否叫號過。
     * @param type $ssqq_id
     * @param type $ssqq_calltimes
     * @param type $ssqq_title
     * @return boolean
    */
   function Check_ShopServiceQ($ssqq_id, &$ssqq_calltimes, &$ssqd_title, &$ssqd_mainpic) {
        try {
            $querydata =  \App\Models\ICR_ShopServiceQue_q::Query_SatrtCallUp($ssqq_id);
            (int)$ssqq_calltimes = $querydata[0]['ssqq_calltimes'];
            $ssqq_title = $querydata[0]['ssqd_title'];
            $ssqd_mainpic = $querydata[0]['ssqd_mainpic'];
            if($ssqq_calltimes > 0) {
               $datenow = date("Y-m-d H:i:s");
               if(((strtotime($datenow) - strtotime($querydata[0]['ssqq_callingtime']))/60) < 5) {
                  return false;
               }
             }
            return true;
        } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }

    private function Update_ShopServiceQueQ($ssqq_id, &$ssqq_calltimes) {
        try {
            $ssqq_calltimes = $ssqq_calltimes + 1;
            $ssqq_callingtime = date("Y-m-d H:i:s");
            $modifydata['ssqq_calltimes'] = $ssqq_calltimes;
            $modifydata['ssqq_callingtime'] = $ssqq_callingtime;
            $modifydata['ssqq_id'] = $ssqq_id;
            return \App\Models\ICR_ShopServiceQue_q::UpdateData($modifydata);
        } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }


    private function Insert_MsLog($md_id, $ssqd_title, $ssqq_id, $ssqd_mainpic) {
        try {
            $savadata['uml_type'] = 804;
            $savadata['md_id'] =  $md_id;
            $savadata['uml_message'] = '親愛的用戶你好，服務:'.$ssqd_title.'已到號，請前往接受服務。';
            $savadata['uml_object'] = "{\"ssqq_id\":\"$ssqq_id\"}";
            $savadata['uml_pic'] = $ssqd_mainpic;
            $savadata['uml_status'] = 0;
            $appService = new \App\Services\AppService;
            return $appService->PostMessageLog($savadata);
        } catch(\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
           return false;
        }
    }

    
}