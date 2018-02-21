<?php

namespace App\Http\Controllers\APIControllers\Shop;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class SalesAgentLogin {

    function salesagentlogin() {
        $functionName = 'salesagentlogin';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;

        try {

            if ($inputData == null) {
                $messageCode = '999999996';
                throw new \Exception($messageCode);
            }
            //檢查輸入值

            if (!SalesAgentLogin::CheckInput($inputData)) {
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
           /* if (!SalesAgentLogin::Check_MobileUnitRecData($inputData['mur_id'], $messageCode)) {
                throw new \Exception($messageCode);
            }*/

            if (!SalesAgentLogin::Check_SalesAgentRent($inputData['sar_account'], $inputData['login_pass'],/*$inputData['mur_id'],*/ $resultData, $messageCode)) {

                $resultData['login_countdown'] = \App\Models\ICR_ServiceAgentRecord::Query_LoginCountDown($querydata[0]['sar_id']);

                throw new \Exception($messageCode);
            }
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
    function CheckInput($value) {
        if (is_null($value)) {
            return false;
        }
         if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'modacc', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'modvrf', 0, false, false)) {
            return false;
        }

        /*if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'mur_id', 0, false, false)) {
            return false;
        }*/
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sar_account', 20, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'login_pass', 0, false, false)) {
            return false;
        }
        return true;
    }

    /**
     * 檢查「行動裝置記錄」
     * @param type $mur_id
     * @return boolean
     */
    function Check_MobileUnitRecData($mur_id, &$messageCode) {

        $querydata = \App\Models\IsCarMobileUnitRec::GetDataByMUR_ID($mur_id);
        //檢查是否有值
        if (is_null($querydata) || count($querydata) == 0) {
            //030101008	行動裝置無法辨識，請重新安裝APP
            $messageCode = '010101002';
            return false;
        }
        //檢查有效性
        if ($querydata[0]['mur_mobileeffective'] != '1') {
            //030101009	行動裝置記錄無效，請重新安裝APP
            $messageCode = '030101009';
            return false;
        }

        return true;
    }

    /**
     * 檢查 業務登入
     * @param type $sar_account 
     * @param type $login_pass
     * @return boolean
     */
    function Check_SalesAgentRent($sar_account, $login_pass/*, $mur_id*/, &$resultdata, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ServiceAgentRecord::GetData_BySarAccount($sar_account);

            if ($querydata == null || count($querydata) == 0) {
                //030101003	無此帳號，請重新登入
                $messageCode = '030101003';
                return false;
            }
            if (count($querydata) > 1) {
                //030101002	使用者註冊記錄大於一筆，無法辨識身份，請聯絡系統管理者進行確認
                $messageCode = '030101002';
                return false;
            }
            if ($querydata[0]['sar_loginerror'] > 4) {
                //030101004	密碼錯誤超過五次，登入鎖定，請聯絡通路管理者重置密碼
                $messageCode = '030101004';
                return false;
            }
            if ($querydata[0]['sar_activestatus'] == 3) {
                //030101005	帳號已停用，請聯絡通路管理者確認
                $messageCode = '030101005';
                return false;
            }
            if ($querydata[0]['sar_activestatus'] != 2) {
                //030101007	帳號未放行，請聯絡管理者確認
                $messageCode = '030101007';
                return false;
            }
            $origenalstring = $querydata[0]['sar_account'] . $querydata[0]['sar_password'];
            $hashstring = base64_encode(hash('sha256', $origenalstring, True));
            //\App\Models\ErrorLog::InsertLog($hashstring);
            if ($hashstring != $login_pass) {
                //030101006	密碼錯誤，請重新登入
                $messageCode = '030101006';
                SalesAgentLogin::Update_LoginError($querydata[0]['sar_id'], $querydata[0]['sar_loginerror'] + 1);
                $resultdata['login_countdown'] = \App\Models\ICR_ServiceAgentRecord::Query_LoginCountDown($querydata[0]['sar_id']);

                return false;
            }

            // 登入成功
            SalesAgentLogin::Update_LoginError($querydata[0]['sar_id'], 0);
            //SalesAgentLogin::CheckExistAndCreate_MemberMobileLink(3, $querydata[0]['sar_id'], $mur_id);

            $resultdata['login_countdown'] = \App\Models\ICR_ServiceAgentRecord::Query_LoginCountDown($querydata[0]['sar_id']);
            $resultdata['sar_id'] = $querydata[0]['sar_id'];
            $resultdata['sar_acountname'] = $querydata[0]['sar_acountname'];

            $messageCode = '030101000';
            return true;
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }

    /*
     * 更新登入失敗次數
     * @param type $sar_id
     * @param type $errorcount
     * @return type
     */
    private function Update_LoginError($sar_id, $errorcount) {
        $savedata['sar_id'] = $sar_id;
        $savedata['sar_loginerror'] = $errorcount;

        return \App\Models\ICR_ServiceAgentRecord::UpdateData($savedata);
    }

    /**
     * 檢查「IsCarMemberMobileLink」是否已存在，若不在則建立
     * @param type $mml_apptype
     * @param type $md_id
     * @param type $mur_id
     * @return boolean
     */
    /*
    private function CheckExistAndCreate_MemberMobileLink($mml_apptype, $md_id, $mur_id) {
        try {

            $selectdata = \App\Models\IsCarMemberMobileLink::GetDataByMDID_MURID($mml_apptype, $md_id, $mur_id);

            if (count($selectdata) != 0) {
                return true;
            }

            return \App\Models\IsCarMemberMobileLink::InsertData(array('mml_apptype' => $mml_apptype, 'md_id' => $md_id, 'mur_id' => $mur_id), $mml_serno);
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }  */

}
