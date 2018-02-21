<?php

namespace App\Http\Controllers\APIControllers\ShopCoupon;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class ShopCouponReservationInfo {

     function shopcouponreservationinfo() {
        $functionName = 'shopcouponreservationinfo';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;

        try {
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!ShopCouponReservationInfo::CheckInput($inputData)) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            $md_id = null;
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
            //檢查活動券狀態
            $shopCouponGet = new \App\Http\Controllers\APIControllers\ShopCoupon\ShopCouponGet;
            if (! $shopCouponGet -> CheckCouponStatus($md_id,$inputData['scm_id'], $sd_id, $scm_coupon_providetype, $scm_bonus_payamount, $messageCode)) {
               
                throw new \Exception($messageCode);
            }
            //檢查預約時段
            if (!ShopCouponReservationInfo::CheckReservationTime($inputData['scm_id'], $reservationdata)) {
               
                $messageCode = '999999999';
                throw new \Exception($messageCode);
            }

            if (!ShopCouponReservationInfo::TransDataToReservationArray($reservationdata, $reservationarray)) {
                
                $messageCode = '999999999';
                throw new \Exception($messageCode);
            }
            $resultData['reservationarray'] = $reservationarray;
            $messageCode = '000000000';
        } catch (\Exception $e) {
            if (is_null($messageCode)) {
               
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($e);
            }
        }

        //回傳值
        $resultArray = Commontools::ResultProcess($messageCode, $resultData);
        Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray];

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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_id', 32, false, false)) {
            return false;
        }

        return true;
    }

    /**
     * 檢查可預約時段
     * @param type $scm_id
     * @param type $reservationdata
     * @return boolean
     */
    function CheckReservationTime($scm_id, &$reservationdata) {
        try {
            $coupondata = \App\Models\ICR_ShopCouponData_m::GetData($scm_id);
            if (count($coupondata) == 0) {
                return false;
            }
            $reservationdata = \App\Models\ICR_ShopCouponData_r::QueryCanReservationTime($scm_id);
            if (count($reservationdata) == 0) {
                \App\Models\ICR_ShopCouponData_m::UpdateData_ReservationFull($scm_id, '1');
                $reservationdata = null;
               // \App\Models\ErrorLog::InsertLog('02');
                return false;
            }
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            $reservationdata = null;
            return false;
        }
    }
/**
 * 
 * @param type $reservationdata
 * @param type $reservationarray
 * @return boolean
 */
    function TransDataToReservationArray($reservationdata, &$reservationarray) {
        try {
            if (count($reservationdata) == 0) {
                return false;
            }
            $tempdate = null;
            $temp = null;
            $temp02 = null;
            foreach ($reservationdata as $date) {
                if ($tempdate == null || $tempdate != $date['scr_rvdate']) {
                    if ($tempdate != null && $temp02 != null) {
                        $temp['scr_rvdate'] = $tempdate;
                        $temp['dailytime'] = $temp02;
                        $reservationarray[] = $temp;
                    }
                    $tempdate = $date['scr_rvdate'];
                    $temp02 = null;
                }
                $temp02[] = [
                    'scr_serno' => $date['scr_serno']
                    , 'scr_rvtime' => $date['scr_rvtime']
                ];
            }
            $temp['scr_rvdate'] = $tempdate;
            $temp['dailytime'] = $temp02;
            $reservationarray[] = $temp;
            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }

}
