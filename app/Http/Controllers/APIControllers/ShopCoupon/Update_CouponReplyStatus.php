<?php

namespace App\Http\Controllers\APIControllers\ShopCoupon;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** update_couponreplystatus	更新「預約回覆狀態」 * */
class Update_CouponReplyStatus {

    function update_couponreplystatus() {
        $functionName = 'update_couponreplystatus';
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
            if (!\App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //檢查活動券
            if (!$this->CheckData_CouponData_g( $inputData['scg_id'], $inputData['sd_id'], $coupondata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            $this->UpdateCouponDataReplyStatus($inputData['scg_id']);
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 36, false, false)) {
            return false;
        }

        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_reply_status', 0, false, false)) {
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
    function CheckData_CouponData_g( $scg_id, $sd_id, &$coupondata, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_g::getMdidByScgid($scg_id);
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
            if ($querydata[0]['scg_usestatus'] == '5' && $querydata[0]['scg_replystatus'] == '0') {

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

    

    /**
     * 更新活動券已過期
     * @param type $scg_id
     * @return boolean
     */
    function UpdateCouponDataReplyStatus($scg_id) {
        try {
            $modifydata['scg_replystatus'] = 1;
            $modifydata['scg_id'] = $scg_id;
            return \App\Models\ICR_ShopCouponData_g::UpdateData($modifydata);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    

}
