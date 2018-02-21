<?php

namespace App\Http\Controllers\APIControllers\ShopCoupon;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** query_reservationreplystatus	商家優惠活動券已預約未回覆項目查詢 * */
class Query_ReservationReplyStatus {

    function query_reservationreplystatus() {
        $functionName = 'query_reservationreplystatus';
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
            if (!$this->CheckInput($inputData)) {
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
            //檢查「店家」、「管理員」權限
            if (! \App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //取得資料
            if (!$this->Get_ReservationData($inputData['sd_id'], $reservationInfoarray, $last_update_date, $messageCode)) {
                throw new \Exception($messageCode);
            }

            $resultData['reservationinfo'] = $reservationInfoarray;
            $resultData['last_update_date'] = $last_update_date;

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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
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
    function Get_ReservationData($sd_id, &$reservationInfoarray, &$last_update_date, &$messageCode) {
        try {
            //$coupondata = \App\Models\ICR_ShopCouponData_m::Query_HaveReservation($sd_id, $lastupdate);
            $coupondata = \App\Models\ICR_ShopCouponData_m::getReplyStatusDataBySdId($sd_id);
            if (count($coupondata) == 0) {
                //010908001	尚無預約記錄
                $messageCode = '010908001';
                return false;
            }

            $reservationInfoarray = $this->TransDataToReservationInfoArray($coupondata, $last_update_date);

            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            $reservationdata = null;
            return false;
        }
    }

    function TransDataToReservationInfoArray($reservationdata, &$last_update_date) {
        try {
            if (count($reservationdata) == 0) {
                return null;
            }
            $tempdate = null;
            $temp = null;
            $temp02 = null;
            foreach ($reservationdata as $date) {
                if ($tempdate == null || $tempdate != $date['scr_rvdate']) {
                    if ($tempdate != null && $temp02 != null) {
                        $temp['reservationdate'] = $tempdate;
                        $temp['reservelist'] = $temp02;
                        $reservationarray[] = $temp;
                    }
                    $tempdate = $date['scr_rvdate'];
                    $temp02 = null;
                }
                $temp02[] = [
                    'scm_id' => $date['scm_id']
                    , 'scg_id' => $date['scg_id']
                    , 'md_id' => $date['md_id']
                    , 'scr_rvtime' => $date['scr_rvtime']
                    , 'scm_title' => $date['scm_title']
                    , 'md_cname' => $date['md_cname']
                    , 'ssd_picturepath' => $date['ssd_picturepath']
                    , 'scg_usestatus' => $date['scg_usestatus']
                    , 'scg_reservationstatus' => $date['scg_reservationstatus']
                    ,'scg_buyermessage' =>$date['scg_buyermessage']
                ];

                if (is_null($last_update_date) || $date['last_update_date'] > $last_update_date) {
                    $last_update_date = $date['last_update_date'];
                }
            }
            $temp['reservationdate'] = $tempdate;
            $temp['reservelist'] = $temp02;
            $reservationarray[] = $temp;
            return $reservationarray;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

}
