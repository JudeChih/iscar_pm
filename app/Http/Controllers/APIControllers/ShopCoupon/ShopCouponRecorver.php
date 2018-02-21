<?php

namespace App\Http\Controllers\APIControllers\ShopCoupon;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** shopcouponrecorver	用戶已索取之商家優惠券項目回復 * */
class ShopCouponRecorver {

    function shopcouponrecorver() {
        $functionName = 'shopcouponrecorver';
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
            if (!ShopCouponRecorver::CheckInput($inputData)) {
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

            $resultData['scg_list'] = ShopCouponRecorver::GetData_CouponData_g($md_id, $inputData['last_update_date'], $last_update_date);
            $resultData['last_update_date'] = $last_update_date;
            $resultData['scm_list'] = ShopCouponRecorver::GetData_CouponData_m($md_id, $inputData['last_update_date']);
            $resultData['sd_list'] = ShopCouponRecorver::GetData_ShopData($md_id, $inputData['last_update_date']);

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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'last_update_date', 20, true, true)) {
            return false;
        }

        if (!array_key_exists('last_update_date', $value)) {
            $value['last_update_date'] = null;
        }

        return true;
    }

    function GetData_CouponData_g($md_id, $lastupdate, &$last_update_date) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_g::GetData_ByMD_ID($md_id, $lastupdate);

            return ShopCouponRecorver::TransDataToSCG_List($querydata, $last_update_date);
        } catch (Exception $ex) {
            return null;
        }
    }

    function GetData_CouponData_m($md_id, $lastupdate) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_m::GetData_ByMD_ID($md_id, $lastupdate);

            return ShopCouponRecorver::TransDataToSCM_List($querydata);
        } catch (Exception $ex) {
            return null;
        }
    }

    function GetData_ShopData($md_id, $lastupdate) {
        try {
            $querydata = \App\Models\ICR_ShopData::GetData_ByMD_ID($md_id, $lastupdate);

            return ShopCouponRecorver::TransDataToSD_List($querydata);
        } catch (Exception $ex) {
            return null;
        }
    }

    function TransDataToSCG_List($arraydata, &$last_update_date) {
        try {
            if (count($arraydata) == 0) {
                return null;
            }

            foreach ($arraydata as $data) {
                $rvdate = $data['scr_rvdate'] . ' ' . $data['scr_rvtime'];
                if (strlen($rvdate) <= 1) {
                    $rvdate = null;
                }
                $resultarray[] = [
                     'scg_id' => $data['scg_id']
                    , 'scm_id' => $data['scm_id']
                    , 'reservationdatetime' => $rvdate
                    , 'scg_getdate' => $data['scg_getdate']
                    , 'scg_usedate' => $data['scg_usedate']
                    , 'scg_usestatus' => $data['scg_usestatus']
                    ,'scg_subtract_totalamount' => $data['scg_subtract_totalamount']
                    , 'scg_reservationstatus' => $data['scg_reservationstatus']
                    , 'scg_buyamount' => $data['scg_buyamount']
                ];

                if (is_null($last_update_date) || $data['last_update_date'] > $last_update_date) {
                    $last_update_date = $data['last_update_date'];
                }
            }

            return $resultarray;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    function TransDataToSCM_List($arraydata) {
        try {
            if (count($arraydata) == 0) {
                return null;
            }

            foreach ($arraydata as $data) {
                $resultarray[] = [
                    'scm_id' => $data['scm_id']
                    , 'sd_id' => $data['sd_id']
                    , 'scm_title' => $data['scm_title']
                    , 'scm_fulldescript' => $data['scm_fulldescript']
                    , 'scm_category' => $data['scm_category']
                    , 'scm_mainpic' => $data['scm_mainpic']
                    , 'scm_activepics' => $data['scm_activepics']
                    , 'scm_price' => $data['scm_price']
                    , 'scm_startdate' => $data['scm_startdate']
                    , 'scm_enddate' => $data['scm_enddate']
                    , 'scm_reservationtag' => $data['scm_reservationtag']
                    , 'scm_poststatus'    => $data['scm_poststatus']
                    , 'scm_producttype' => $data['scm_producttype']
                    , 'scm_coupon_providetype' => $data['scm_coupon_providetype']
                    , 'scm_bonus_payamount'    => $data['scm_bonus_payamount']
                    , 'scm_bonus_giveamount' => $data['scm_bonus_giveamount']
                ];
            }

            return $resultarray;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

   function TransDataToSD_List($arraydata) {
        try {
            if (count($arraydata) == 0) {
                return null;
            }

            foreach ($arraydata as $data) {

                $resultarray[] = [
                    'sd_id' => $data['sd_id']
                    , 'sd_shopname' => $data['sd_shopname']
                    , 'sd_shopaddress' => $data['sd_shopaddress']
                    , 'sd_shoptel' => $data['sd_shoptel']
                    , 'sd_lat' => $data['sd_lat']
                    , 'sd_lng' => $data['sd_lng']
                    , 'sd_weeklystart' => $data['sd_weeklystart']
                    , 'sd_weeklyend' => $data['sd_weeklyend']
                    , 'sd_dailystart' => $data['sd_dailystart']
                    , 'sd_dailyend' => $data['sd_dailyend']
                ];
            }

            return $resultarray;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 檢查可預約時段
     * @param type $scm_id
     * @param type $reservationdata
     * @return boolean
     */
    function Get_ReservationData($sd_id, $lastupdate, &$reservationInfoarray, &$last_update_date, &$messageCode) {
        try {
            $coupondata = \App\Models\ICR_ShopCouponData_m::Query_HaveReservation($sd_id, $lastupdate);
            if (count($coupondata) == 0) {
                //010908001	尚無預約記錄
                $messageCode = '010908001';
                return false;
            }

            $reservationInfoarray = ShopCouponReservationQuery::TransDataToReservationInfoArray($coupondata, $last_update_date);

            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            $reservationdata = null;
            return false;
        }
    }

}
