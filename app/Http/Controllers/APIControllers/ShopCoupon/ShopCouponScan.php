<?php

namespace App\Http\Controllers\APIControllers\ShopCoupon;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** shopcouponscan	商家掃描優惠券條碼查核內容 * */
class ShopCouponScan {

    function shopcouponscan() {
        $functionName = 'shopcouponscan';
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
            if (!ShopCouponScan::CheckInput($inputData)) {
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
            if (!\App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }



            //檢查活動券
            if (!ShopCouponScan::CheckData_CouponData_g($inputData['scm_id'], $inputData['scg_id'], $inputData['sd_id'], $coupondata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            ShopCouponScan::GetResultData($coupondata, $resultData, $messageCode);
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 36, false, false)) {
            return false;
        }

        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_id', 36, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_id', 0, false, false)) {
            return false;
        }

        return true;
    }

    /**
     * 檢查活動券取用記錄
     * @param type $scm_id
     * @param type $scg_id
     * @param type $sd_id
     * @param type $messageCode
     * @return boolean
     */
    function CheckData_CouponData_g($scm_id, $scg_id, $sd_id, &$coupondata, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_m::GetData_BySCMID_SCGID($scm_id, $scg_id);
            if (count($querydata) != 1) {
                //010910001	查無該券編號，請提醒消費者重新索取．
                $messageCode = '010910001';
                return false;
            }

            if ($querydata[0]['sd_id'] != $sd_id) {
                //010910009	該券非貴司發行，請告知客戶前往正確商家使用
                $messageCode = '010910009';
                return false;
            }

            if (strtotime(date('Y/m/d')) > strtotime($querydata[0]['scm_enddate'])) {
                ShopCouponScan::UpdateCouponDataExpired($scg_id);
                //010910002	該券活動已逾期，不可再使用
                $messageCode = '010910002';
                return false;
            }
            if ($querydata[0]['scg_usestatus'] == '5') {

                $coupondata = $querydata;
                return true;
            }

            //
            if ($querydata[0]['scg_usestatus'] == '1') {
                //010910010 用戶已取該卷，但尚未付款
                $messageCode = '010910010';
                return false;
            }
            if ($querydata[0]['scg_usestatus'] == '2') {
                //010910004	該券已使用完畢，請提醒消費者進行狀態更新
                $messageCode = '010910004';
                return false;
            }
            if ($querydata[0]['scg_usestatus'] == '3') {
                //010910005	該券已放棄使用，請提醒消費者進行狀態更新
                $messageCode = '010910005';
                return false;
            }
            if ($querydata[0]['scg_usestatus'] == '4') {
                //010910002	該券活動已逾期，不可再使用
                $messageCode = '010910002';
                return false;
            }
            //010910008	該券取用記錄有誤，請告知客戶重新取用活動券
            $messageCode = '010910008';
            return false;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    function GetResultData($coupondata, &$resultdata, &$messageCode) {
        try {
            if (count($coupondata) == 0) {
                $messageCode = '999999999';
                return false;
            }

            $resultdata['scm_title'] = $coupondata[0]['scm_title'];
            $resultdata['scm_fulldescript'] = $coupondata[0]['scm_fulldescript'];
            $resultdata['scm_mainpic'] = $coupondata[0]['scm_mainpic'];
            $resultdata['scm_reservationtag'] = $coupondata[0]['scm_reservationtag'];
            $resultdata['scm_enddate'] = $coupondata[0]['scm_enddate'];
            $resultdata['scr_rvdate'] = $coupondata[0]['scr_rvdate'];
            $resultdata['scr_rvtime'] = $coupondata[0]['scr_rvtime'];

            if ($coupondata[0]['scm_reservationtag'] != '1') {
                //010910000	該券可正常使用．
                $messageCode = '010910000';
                return false;
            }


            $interval = (new \DateTime('now'))->diff(new \DateTime($coupondata[0]['scr_rvdate'] . ' ' . $coupondata[0]['scr_rvtime']));
            if ($interval->y != 0 || $interval->m != 0 || $interval->d != 0 || $interval->h != 0 || $interval->i > 10) {
                //010910006	該券預約使用時間不符，請確認是否使用
                $messageCode = '010910006';
                return false;
            }

            //010910007	該券為預約客戶，可正常使用
            $messageCode = '010910007';
            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 更新活動券已過期
     * @param type $scg_id
     * @return boolean
     */
    function UpdateCouponDataExpired($scg_id) {
        try {
            $modifydata['scg_id'] = $scg_id;
            $modifydata['scg_usestatus'] = '4';

            return \App\Models\ICR_ShopCouponData_g::UpdateData($modifydata);
        } catch (Exception $ex) {
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
    function Cancel_Reservation($md_id, $scg_id, $scr_serno) {
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
    private function Create_MsgLog_Abandon($md_id, $scr_rvdatetime, $scm_title) {

        try {
            $savedata['uml_type'] = '701';
            $savedata['md_id'] = $md_id;
            $savedata['uml_message'] = "預約時段" . $scr_rvdatetime . "，活動項目" . $scm_title . "，用戶取消預約，請查看預約記錄";
            //$savedata['uml_object'] = '{"cdm_id" : "' . $cdm_id . '" ,"cdd_qrcode" : "' . $cdd_qrcode . '"} ';
            //$savedata['uml_status'] = '0';
            //$savedata['uml_pic'] = \App\Library\Commontools::$Coupon_BannerURL . $cdm_active_pic;
            $appService = \App\Services\AppService;
            return $appService->PostMessageLog ($savedata);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

}
