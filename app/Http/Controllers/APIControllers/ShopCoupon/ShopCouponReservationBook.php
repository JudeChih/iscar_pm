<?php

namespace App\Http\Controllers\APIControllers\ShopCoupon;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class ShopCouponReservationBook {
    function shopcouponreservationbook() {
        $functionName = 'shopcouponreservationbook';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        $md_id = null;
        $reservation_times = null;
        try {
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!ShopCouponReservationBook::CheckInput($inputData)) {
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
            //可預約時段檢查
            $shopCouponReservationInfo = new \App\Http\Controllers\APIControllers\ShopCoupon\ShopCouponReservationInfo;
            if (!$shopCouponReservationInfo->CheckReservationTime($inputData['scm_id'], $reservationdata)) {
                //無可預約時間
                $messageCode = '999999999';
                throw new \Exception($messageCode);
            }
            //檢查活動券狀態
           // $shopCouponGet = new \App\Http\Controllers\APIControllers\ShopCoupon\ShopCouponGet;
            if (!$this->CheckCouponStatus($md_id,$inputData['scm_id'], $sd_id, $scm_coupon_providetype, $scm_bonus_payamount, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //檢查活動券索取記錄是否存在，並檢查更新預約次數
            if (!ShopCouponReservationBook::CheckCouponData_g($inputData['scm_id'], $inputData['scg_id'], $md_id, $inputData['booktype'], $reservation_times, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //檢查所選預約時間是否還有剩餘數量
            if (!ShopCouponReservationBook::CheckCouponData_r($reservationdata, $inputData['scr_serno'], $reservation_datetime, $reservationarray, $messageCode)) {
                $resultData['reservationarray'] = $reservationarray;
                throw new \Exception($messageCode);
            }
            //更新活動券預約時間
            if (!ShopCouponReservationBook::Update_ReservationData($inputData['booktype'], $inputData['scg_id'], $inputData['scr_serno'], $reservation_times, $messageCode)) {
                throw new \Exception($messageCode);
            }
            if ($inputData['booktype'] == 1 ) {
               $mailController = new  \App\Http\Controllers\APIControllers\MailController;
               $mailController->reservation_datetime_sendMail($inputData['scg_id']); 
            }
            
            //010906000	預約完成
            $messageCode = '010906000';
            $resultData['reservation_times'] = $reservation_times;
            $resultData['reservation_datetime'] = $reservation_datetime;
        } catch (\Exception $e) {
            $resultData['scm_cname'] = null;
            $resultData['reservation_datetime'] = null;
            $resultData['reservation_times'] = null;
            if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($e);
            }
        }

        $resultData['scm_cname'] = \App\Models\ICR_ShopCouponData_m::Query_SCM_CName($inputData['scm_id']);

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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_id', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scr_serno', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'booktype', 1, false, false)) {
            return false;
        }
        return true;
    }

    /**
     * 檢查是否有活動券取用記錄
     * @param type $scm_id
     * @param type $scg_id
     * @param type $messageCode
     * @return boolean
     */
   function CheckCouponData_g($scm_id, $scg_id, $md_id, $booktype, &$reservation_times, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_g::GetData($scg_id);

            if (count($querydata) != 1 || $querydata[0]['scm_id'] != $scm_id || $querydata[0]['md_id'] != $md_id) {
                //010906001	查無索取記錄，請重新索取優惠券
                $messageCode = '010906001';
                return false;
            }
            if ($querydata[0]['scr_serno'] != '' && $booktype == 0) {
                //已有預約記錄
                $messageCode = '010906004';
                return false;
            }
            $reservation_times = $querydata[0]['reservation_times'] ;
            if ($reservation_times >= 2 ) {
                //預約修改次數已達上限(兩次)
                $messageCode = '010906005';
                return false;
            }
            
            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }
    
    
    /**
     * 檢查活動券狀態
     * @param type $scm_id
     * @param type $sd_id
     * @param type $messageCode
     * @return boolean
     */
    function CheckCouponStatus($md_id,$scm_id, &$sd_id, &$scm_coupon_providetype, &$scm_bonus_payamount, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_m::GetData($scm_id);

            if (count($querydata) == 0) {
                //010904001	查無商品項目，請確認後重發
                $messageCode = '010904001';
                return false;
            }
            if (strtotime('now') < strtotime($querydata[0]['scm_startdate'])) {
                //010904002	該活動尚未開始，請選用其他活動券
                $messageCode = '010904002';
                return false;
            }
            if (strtotime('now') > strtotime($querydata[0]['scm_enddate'])) {
                //010904003	該活動已截止，請選用其他活動券
                $messageCode = '010904003';
                \App\Models\ICR_ShopCouponData_m::UpdateData_PostStatus($scm_id, 3);
                return false;
            }
            if ($querydata[0]['scm_poststatus'] != 1) {
                //010904004	該活動已停刊，請選用其他活動券或稍後再試
                $messageCode = '010904004';
                //\App\Models\ICR_ShopCouponData_m::UpdateData_PostStatus($scm_id, 3);
                return false;
            }
           
            $scm_bonus_payamount = $querydata[0]['scm_bonus_payamount'];
            $scm_coupon_providetype = $querydata[0]['scm_coupon_providetype'];
            $sd_id =  $querydata[0]['sd_id'];
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
        }
    }

    /**
     * 檢查是否有該預約時段
     * @param type $scr_serno
     * @param type $scm_id
     * @param type $messageCode
     * @return boolean
     */
   function CheckCouponData_r($reservationdata, $scr_serno, &$reservation_datetime, &$reservationarray, &$messageCode) {
        $reservation_datetime = null;
        $reservationarray = null;

        try {
            $filterdata = array_filter($reservationdata, function($obj) use($scr_serno, &$reservation_datetime) {
                if ($obj['scr_serno'] == $scr_serno) {
                    $reservation_datetime = $obj['scr_rvdate'] . ' ' . $obj['scr_rvtime'];
                    return true;
                }
                return false;
            });

            if (count($filterdata) == 0) {
                //010906002	所選預約時段已被預約，請重新選取
                $messageCode = '010906002';
                ShopCouponReservationInfo::TransDataToReservationArray($reservationdata, $reservationarray);
                return false;
            }

            //$reservation_datetime = $scr_datetime; // $filterdata[1]['scr_rvdate'] . ' ' . $filterdata[1]['scr_rvtime'];
            return true;
        } catch (\Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 更新活動券預約資料
     * @param type $booktype
     * @param type $scg_id
     * @param type $scr_serno
     * @param type $messageCode
     * @return boolean
     */
    function Update_ReservationData($booktype, $scg_id, $scr_serno, &$reservation_times, &$messageCode) {
        try {
            if ($booktype == 1) {
                if (!ShopCouponReservationBook::CheckOldReservationDateTime($scg_id, $old_scr_serno, $messageCode)) {
                    return false;
                }
                \App\Models\ICR_ShopCouponData_r::Update_ReservationAmount($old_scr_serno, false);
                 $reservation_times = ($reservation_times +1);
            }
            \App\Models\ICR_ShopCouponData_r::Update_ReservationAmount($scr_serno, true);
            $arraydata = [
                       'scg_id' => $scg_id,
                       'scr_serno' => $scr_serno,
                       'reservation_times' => $reservation_times,
                       'scg_replystatus'  => 0,
            ];
            \App\Models\ICR_ShopCouponData_g::UpdateData($arraydata);
            

            return true;
        } catch (\Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 檢查舊有預約記錄是否即將到期
     * @param type $scg_id
     * @param type $messageCode
     * @return boolean
     */
    private function CheckOldReservationDateTime($scg_id, &$old_scr_serno, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_r::GetData_BySCG_ID($scg_id);
            if (count($querydata) == 0) {
                $messageCode = '999999999';
                return false;
            }

            $interval = (new \DateTime('now'))->diff(new \DateTime($querydata[0]['scr_rvdate'] . ' ' . $querydata[0]['scr_rvtime']));
            //\App\Models\ErrorLog::InsertLog('INTERVAL：Y(' . $interval->y . ')M(' . $interval->m . ')D(' . $interval->d . ')H(' . $interval->h . ')');
            if ($interval->invert == '1' || ($interval->y == 0 && $interval->m == 0 && $interval->d == 0 && $interval->h < 6)) {
                //010906003	原有預約時段即將到期，無法變更預約時間
                $messageCode = '010906003';
                return false;
            }

            $old_scr_serno = $querydata[0]['scr_serno'];

            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

}
