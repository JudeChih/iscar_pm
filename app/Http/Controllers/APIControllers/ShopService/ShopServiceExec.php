<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
use DB;
/** shopserviceexec	商家掃描QR後開始服務 * */
class ShopServiceExec {

    function shopserviceexec(){
        $functionName = 'shopserviceexec';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        //DB::beginTransaction();
        try {
             if($inputData == null){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //輸入值
            if(!ShopServiceExec::CheckInput($inputData)){
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
            $querydata = \App\Models\ICR_ShopServiceQue_q::GetData_BYSSQQID($inputData['ssqq_id']);
           //檢查排隊資料
            if (!ShopServiceExec::Check_ServiceQue($querydata, $inputData['sd_id'], $messageCode,$ssqq_queserno)) {
                throw new \Exception($messageCode);
            }
            //查詢下個服務資料
            $ShopService = new ShopService;
            if ($ShopService->query_NextService($inputData['sd_id'], $inputData['ssqd_id'], $servicedNO, $nextServiceNO, $nextClientID, $nextServiceQueID)) {
                if ($ssqq_queserno > $nextServiceNO) {
                    $result['QQQQ'] = ($ssqq_queserno);
                    //return $result;
                    //010916004	用戶未到號，請用戶稍候
                    $messageCode = '010916004';
                    throw new \Exception($messageCode);
                }
            }

            if (!ShopServiceExec::Confirm_ServiceQue($querydata[0]['ssqq_usestatus'],$ssqq_usestatus)) {
                throw new \Exception($messageCode);
            }

            if(!ShopServiceExec::UpdateData_ServiceQue($inputData['ssqq_id'], $md_id, $ssqq_usestatus, $messageCode)) {
                 throw new \Exception($messageCode);
            }

            if(!ShopServiceExec::Check_ServiceQueOvertime_end($querydata[0]['ssqd_workhour'],$querydata[0]['ssqm_dailyend'],$serviceendtime,$messageCode)) {
                 throw new \Exception($messageCode);
            }
            //查詢下個服務資料
            if (!$ShopService->query_NextService($inputData['sd_id'], $inputData['ssqd_id'], $servicedNO, $nextServiceNO, $nextClientID, $nextServiceQueID)) {
                 throw new \Exception($messageCode);  
            }
            if (is_null($nextClientID) || strlen($nextClientID) == 0) {
                //暫無次一隊列用戶，請等候新用戶選用服務
                 $messageCode = '010917004';
                 throw new \Exception($messageCode); 
            } 
            $dateNow = date("H:i:s"); 
            if ($dateNow < $querydata[0]['ssqm_dailystart'] || $dateNow > $querydata[0]['ssqm_dailyend']) {
                 //正式服務時間尚未開始，停止自動叫號 
                 $messageCode = '010917002';
                 throw new \Exception($messageCode);
            }
            $target = 1;
            $iscar_push = '{"target" :"'. $target. '","id_1":"","id_2":""}';
            if(!$memService->push_notification( $inputData['sat'], array($nextClientID) , null, null, $target, $iscar_push ) ) {
                throw new \Exception($messageCode);
            }
            if(!ShopServiceExec::Insert_MsLog($nextClientID, $querydata[0]['ssqd_title'], $serviceendtime)) {
                //$messageCode = 'md_id:'.$nextClientID.' ssqd_title:'.$querydata[0]['ssqd_title'].' serviceendtime:'.$serviceendtime;
                throw new \Exception($messageCode);
            }
            //驗證完成，請開始服務，已自動呼叫下一個排隊用戶到訪
            $messageCode = '010917000';
            //DB::commit();
        } catch (\Exception $e) {
            if (is_null($messageCode)) {
                //DB::rollBack();
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'ssqd_id', 32, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'ssqq_id', 32, false, false)) {
            return false;
        }
        return true;
    }
     /**
     * 檢查排隊服務資料
     * @param type $querydata
     * @param type $messageCode
     * @return boolean
     */
    function Check_ServiceQue($querydata, $sd_id, &$messageCode, &$ssqq_queserno) {
        try {
            if (count($querydata) == 0) {
                //010916001	憑證號碼無效，請重新輸入
                $messageCode = '010916001';
                return false;
            }
            if ($querydata[0]['sd_id'] != $sd_id) {
                //010916002	非貴司所發行之服務，請通知用戶前往正確商家使用
                $messageCode = '010916002';
                return false;
            }
            if (in_array($querydata[0]['ssqq_usestatus'],array(2,5,6,8))) {
                //010916006	用戶已服務完成，無法再次使用
                $messageCode = '010916006';
                return false;
            }
            $ssqq_queserno = $querydata[0]['ssqq_queserno'];  
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            $messageCode = '999999999';
            return false;
        }
   }
    /**
     * 確認排隊服務資料
     * @param type $value
     * @param type $ssqq_usestatus
     * @return boolean
     */
   private function Confirm_ServiceQue($value, &$ssqq_usestatus) {
        try {
            if ($value == 1) {
                $ssqq_usestatus = 2;
            } else if ($value == 3) {
                $ssqq_usestatus = 6;
            } else if ($value ==  4) {
                $ssqq_usestatus = 5;
            } else if ($value ==  7) {
                $ssqq_usestatus = 8;
            }
            return true; 
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($ex);
            return fasle;
        }
   }
   
   private function UpdateData_ServiceQue($ssqq_id, $md_id, $ssqq_usestatus, &$messageCode) {
        try {
           $modifydata = [
                           'ssqq_id'         => $ssqq_id,
                           'ssqq_receivtime' => date("Y-m-d H:i:s"),
                           'ssqq_usestatus'  => $ssqq_usestatus,
                           'ssqq_receiver'   => $md_id
                         ];
           if(!\App\Models\ICR_ShopServiceQue_q::UpdateData($modifydata)) {
              return false;
           }
           if($ssqq_usestatus == 8) {
           //當前用戶為過號用戶，暫停呼叫次一號用戶
             $messageCode = '010917003';
             return false; 
           }
            return true;
        } catch (\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return fasle;
        }
   }
   
   /**
    * 檢查服務逾時
    * @param type $ssqd_workhour
    * @param type $ssqm_dailyend
    * @return boolean
    */
   function Check_ServiceQueOvertime_end($ssqd_workhour,$ssqm_dailyend,&$serviceendtime, &$messageCode) {
        try { 
          $dateTime = new \DateTime('now');
          $time = $ssqd_workhour * 30 ;
          $dateTime -> add(new \DateInterval('PT'.$time.'M'));
          $serviceendtime = $dateTime -> format('H:i:s');
          if ($serviceendtime > $ssqm_dailyend) {
          //服務完成後將超過今日服務時間，將停止自動叫號，請問是否結束今日排隊服務
            $messageCode = '010917001';
            return false;
          }
          return true;
        } catch (\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
           return fasle;
        }
   }
   
   
   
   private function Insert_MsLog($md_id, $ssqd_title, $serviceendtime) {
     try {
       $savadata['uml_type'] = 802;
       $savadata['md_id'] =  $md_id;
       $savadata['uml_message'] = '親愛的用戶你好，服務:'.$ssqd_title.'將於'.$serviceendtime.'後到號，請按時前往。';
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
}