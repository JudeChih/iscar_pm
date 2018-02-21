<?php

namespace App\Http\Controllers\APIControllers\ReservationPaused;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** delete_reservationpaused   刪除「暫停預約資料」資料* */
class Delete_ReservationPaused {
   function delete_reservationpaused() {
        $functionName = 'delete_reservationpaused';
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
            //檢查有無紀錄
            if (!$this->checkData($inputData['sd_id'], $inputData['rp_serno'], $ReservationData, $array_scr_serno)) {
                //資料輸入錯誤，請重新輸入
              $messageCode = '011701001';
              throw new \Exception($messageCode);
            }
            //檢查可不可以刪除紀錄
            if ($this->checkDeleteOrNot($ReservationData)) {
                $ReservationRepo = new \App\Repositories\ICR_Reservation_PausedRepository();
                $ReservationData = $ReservationRepo->DeleteData($inputData['rp_serno']);
                //啟用停掉的預約時間。
                \App\Models\ICR_ShopCouponData_r::Update_SCR_Effective_ISFLAG($array_scr_serno, $isflag= 1,  $scr_effective= 1);
            } else {
                //該筆資料不能異動
                $messageCode = '011701002';
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'rp_serno', 0, false, false)) {
            return false;
        }
        return true;
    }

    /**
     * 檢查有無紀錄
     * @param type $sd_id
     * @param type $rp_serno
     *  @param type &$ReservationData
     * @return boolean
     */
    function checkData($sd_id, $rp_serno, &$ReservationData, &$array_scr_serno) {
        $max_work_hour = 0;
        $array_scm_id = array();
        $array_scr_serno = array();
        try {
            $ReservationRepo = new \App\Repositories\ICR_Reservation_PausedRepository();
            $ReservationData = $ReservationRepo->getDataBySdId_RpSerno ($sd_id, $rp_serno);
            if (count($ReservationData) == 0 ) {
                return false;
            }
            $rp_start_datetime = date_create($ReservationData[0]['rp_start_datetime']);
            $rp_end_datetime = date_create($ReservationData[0]['rp_end_datetime']);
            //取得店家需要預約的所有紀錄
            $scmData = \App\Models\ICR_ShopCouponData_m::GetReservationDataBy_SD_ID($isflag= 1,$sd_id);
            if (count($scmData)==0) {
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
            $scrData = \App\Models\ICR_ShopCouponData_r::GetData_BySCM_ID($isflag = 0, $array_scm_id, $rp_start_datetime, $rp_end_datetime) ;
            //取得所有scr_serno
            foreach ($scrData as $row) {
                array_push($array_scr_serno, $row['scr_serno']);
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
    function checkDeleteOrNot($ReservationData) {
        try {
            if ($ReservationData[0]['rp_start_datetime'] <  date("Y-m-d") && $ReservationData[0]['rp_end_datetime']  >  date("Y-m-d")) {
                 return false;
            }
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }
   


}