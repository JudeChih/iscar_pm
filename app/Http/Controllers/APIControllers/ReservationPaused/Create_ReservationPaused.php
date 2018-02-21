<?php

namespace App\Http\Controllers\APIControllers\ReservationPaused;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** create_reservationpaused   建立「暫停預約資料」資料* */
class Create_ReservationPaused {
   function create_reservationpaused() {
        $functionName = 'create_reservationpaused';
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
            if (!\App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //判斷檢查，並回傳需要停用的array(scr_serno)
            if (!$this->checkData($inputData['sd_id'], $inputData['rp_start_datetime'],  $inputData['rp_end_datetime'] , $messageCode, $array_scr_serno)) {
                throw new \Exception($messageCode);
            }
            
            //停用查出的scr預約時間
            if (!\App\Models\ICR_ShopCouponData_r::Update_SCR_Effective_ISFLAG($array_scr_serno, $isflag= 0,  $scr_effective= 0)) {
                 throw new \Exception($messageCode);
            }
            
            //建立「暫停預約資料」資料
            if (!$this->insertICR_RPData($inputData) ) {
              throw new \Exception($messageCode);
            }
          
            $messageCode = '000000000';
       } catch(\Exception $e) {
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'rp_start_datetime', 0, false, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'rp_end_datetime', 0, false, true)) {
            return false;
        }
        
        return true;
    }

    /**
     * 判斷檢查，並回傳需要停用的array(scr_serno)
     * @param type $sd_id
     * @param type $rp_start_datetime
     * @param type $rp_end_datetime
     * @param type $messageCode
     * @param type $array_scr_serno
     * @return boolean
     */
    function checkData($sd_id, $rp_start_datetime,  $rp_end_datetime , &$messageCode, &$array_scr_serno) {
        $max_work_hour = 0;
        $array_scm_id = array();
        $array_scr_serno = array();
        $ReservationRepo = new \App\Repositories\ICR_Reservation_PausedRepository();
        try {
            $ReservationData = $ReservationRepo->getDataByDatetime ($rp_start_datetime, $rp_end_datetime, $sd_id);
            $rp_start_datetime = date_create("$rp_start_datetime");
            $rp_end_datetime = date_create("$rp_end_datetime");
            $nowDate = new \DateTime('now');
            if ($rp_start_datetime <$nowDate || $rp_end_datetime <= $rp_start_datetime) {
                //傳入日期區間錯誤，請重新輸入
                $messageCode = '011702001';
                return false;
            }
            if (count($ReservationData) > 0 ) {
                //該時段中已有暫停預約記錄，請重新設定
                $messageCode = '011702002';
                return false;
            }
            //取得店家需要預約的所有紀錄
            $scmData = \App\Models\ICR_ShopCouponData_m::GetReservationDataBy_SD_ID($isflag = 1,$sd_id);
            if (count($scmData)==0) {
                //該店家無需要預約的服務
                $messageCode = '011702003';
                return false;
            }
            //取得所有scm_id，並取出最大值的工作時間
            foreach ($scmData as $rowData) {
                if ($rowData["scm_workhour"] > $max_work_hour) {
                    $max_work_hour = $rowData["scm_workhour"];
                }
                array_push($array_scm_id, $rowData['scm_id']);
            }
            //將工作時建換算小時，並改變判斷的時間區間，避免暫停到服務區間
            $max_work_hour *= 0.5;
            $rp_start_datetime ->modify("-$max_work_hour hours"); 
            $scrData = \App\Models\ICR_ShopCouponData_r::GetData_BySCM_ID($isflag = 1, $array_scm_id, $rp_start_datetime, $rp_end_datetime) ;
            //取得所有scr_serno
            foreach ($scrData as $row) {
                array_push($array_scr_serno, $row['scr_serno']);
            }
            //取得暫停區間，是否有預約紀錄
            $scgData = \App\Models\ICR_ShopCouponData_g::GetData_BySCR_SERNO($array_scr_serno);
            if (count($scgData) > 0 ) {
                 //該時段已有預約資料，無法暫停
                $messageCode = '011702004';
                return false;
            }
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    
    /**
     * 檢查可不刪除紀錄
     * @param type $sd_id
     * @param type $rp_serno
     *  @param type &$ReservationData
     * @return boolean
     */
    function insertICR_RPData($arrayData) {
        try {
            $ReservationRepo = new \App\Repositories\ICR_Reservation_PausedRepository();
            $saveData = [
                'sd_id'=>$arrayData['sd_id'],
                'rp_start_datetime'=>$arrayData['rp_start_datetime'],
                'rp_end_datetime'=>$arrayData['rp_end_datetime'],
            ];
            return $ReservationRepo->InsertData($saveData) ;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }
  
   


}