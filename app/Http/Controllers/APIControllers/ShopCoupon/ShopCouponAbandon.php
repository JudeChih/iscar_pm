<?php

namespace App\Http\Controllers\APIControllers\ShopCoupon;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** shopcouponabandon	用戶棄用已已索取之商家優惠券 * */
class ShopCouponAbandon {

     function shopcouponabandon() {
        $functionName = 'shopcouponabandon';
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
            if (!ShopCouponAbandon::CheckInput($inputData)) {
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
            //檢查活動券
            if (!ShopCouponAbandon::CheckData_CouponData_g($inputData['scm_id'], $inputData['scg_id'], $md_id, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //棄用活動券
            ShopCouponAbandon::AbandonCouponData($inputData['scg_id'], $md_id, $inputData['scg_abandomreason']);

            $messageCode = '010909000';
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scm_id', 36, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scg_id', 36, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scg_abandomreason', 1, false, false)) {
            return false;
        }

        return true;
    }

    /**
     * 檢查活動券取用記錄
     * @param type $scm_id
     * @param type $scg_id
     * @param type $md_id
     * @param type $messageCode
     * @return boolean
     */
      function CheckData_CouponData_g($scm_id, $scg_id, $md_id, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_g::GetData_BySCMID_SCGID($scm_id, $scg_id);
            if (count($querydata) != 1) {
                $messageCode = '010304001';
                return false;
            }

            if ($querydata[0]['md_id'] != $md_id) {
                //010909001	查無記錄，請重新確認	
                $messageCode = '010909001';
                return false;
            }
            if ($querydata[0]['scg_usestatus'] == '2') {
                //010909002	該券已用畢，請重啟APP以更新活動券持有記錄	
                $messageCode = '010909002';
                return false;
            }
            if ($querydata[0]['scg_usestatus'] == '3' || $querydata[0]['scg_usestatus'] == '4') {
                //010909003	該券已棄用或逾期，請重啟APP以更新活動券持有記錄	
                $messageCode = '010909003';
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
     * 棄用活動券
     * @param type $scg_id
     * @param type $scg_abandomreason
     * @return boolean
     */
    function AbandonCouponData($scg_id, $md_id, $scg_abandomreason) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_g::GetData($scg_id);
            if (count($querydata) != 1) {
                return false;
            }

            $querydata[0]['scg_usestatus'] = '3';
            $querydata[0]['scg_abandomreason'] = $scg_abandomreason;

            if (!\App\Models\ICR_ShopCouponData_g::UpdateData($querydata[0])) {
                return false;
            }

            if ($querydata[0]['scr_serno'] != null && strlen($querydata[0]['scr_serno']) != 0) {
                ShopCouponAbandon::Cancel_Reservation($md_id, $scg_id, $querydata[0]['scr_serno']);
            }

            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 取消預約
     * @param type $md_id
     * @param type $scg_id
     * @param type $scr_serno
     * @return type
     */
    public static function Cancel_Reservation($md_id, $scg_id, $scr_serno) {
        try {
            \App\Models\ICR_ShopCouponData_r::Update_ReservationAmount($scr_serno, false);
            \App\Models\ICR_ShopCouponData_g::Update_SCR_Serno($scg_id, null);

            $querydata = \App\Models\ICR_ShopCouponData_r::GetData($scr_serno);
            if (count($querydata) != 1) {
                return;
            }
            if (strtotime('now') < strtotime($querydata[0]['scr_rvdate'] . ' ' . $querydata[0]['scr_rvtime'])) {

                $scm_title = \App\Models\ICR_ShopCouponData_m::Query_SCM_CName($querydata[0]['scm_id']);
                ShopCouponAbandon::Create_MsgLog_Abandon($md_id, $querydata[0]['scr_rvdate'] . ' ' . $querydata[0]['scr_rvtime'], $scm_title);
            }
        } catch (Exception $ex) {
            
        }
    }

    /**
     * 建立「使用者通知記錄」-停用活動券
     * @param type $md_id
     * @param type $scr_rvdatetime
     * @param type $scm_title
     * @return boolean
     */
    private static function Create_MsgLog_Abandon($md_id, $scr_rvdatetime, $scm_title) {

        try {
            $savedata['uml_type'] = '701';
            $savedata['md_id'] = $md_id;
            $savedata['uml_message'] = "預約時段" . $scr_rvdatetime . "，活動項目" . $scm_title . "，用戶取消預約，請查看預約記錄";
            //$savedata['uml_object'] = '{"cdm_id" : "' . $cdm_id . '" ,"cdd_qrcode" : "' . $cdd_qrcode . '"} ';
            //$savedata['uml_status'] = '0';
            //$savedata['uml_pic'] = \App\library\Commontools::$Coupon_BannerURL . $cdm_active_pic;

            return Commontools::PostMessageLog($savedata, $ms);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

}
