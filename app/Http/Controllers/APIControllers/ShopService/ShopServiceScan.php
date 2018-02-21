<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** shopservicescan	商家掃描用戶QR憑證 * */
class ShopServiceScan {

     function shopservicescan() {
        $functionName = 'shopservicescan';
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
            if (!ShopServiceScan::CheckInput($inputData)) {
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
            //檢查排隊資料
            if (!ShopServiceScan::Check_ServiceQue($inputData['sd_id'], $inputData['ssqq_id'], $inputData['ssqd_id'], $ssqq_queserno, $resultData, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //查詢下個服務資料
            $ShopService = new ShopService;
            if ($ShopService->query_NextService($inputData['sd_id'], $inputData['ssqd_id'], $servicedNO, $nextServiceNO, $nextClientID, $nextServiceQueID)) {
                $result['$servicedNO'] = ($servicedNO);
                $result['$nextServiceNO'] = ($nextServiceNO);
                $result['$nextClientID'] = ($nextClientID);
                $result['$nextServiceQueID'] = ($nextServiceQueID);
                $result['$ssqq_queserno'] = ($ssqq_queserno);

                if ($ssqq_queserno > $nextServiceNO) {
                    $result['QQQQ'] = ($ssqq_queserno);
                    //return $result;
                    //010916004	用戶未到號，請用戶稍候
                    $messageCode = '010916004';
                    throw new \Exception($messageCode);
                }
            }

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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqd_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqq_id', 32, false, false)) {
            return false;
        }
        return true;
    }

    /**
     * 檢查排隊服務資料
     * @param type $sd_id
     * @param type $ssqq_id
     * @param type $ssqd_id
     * @param type $resultdata
     * @param type $messageCode
     * @return boolean
     */
    function Check_ServiceQue($sd_id, $ssqq_id, $ssqd_id, &$ssqq_queserno, &$resultdata, &$messageCode) {
        $resultdata = null;
        try {
            $querydata = \App\Models\ICR_ShopServiceQue_q::Query_ShopServiceQueData($ssqq_id, $ssqd_id);
            if (count($querydata) == 0) {
                //010916001	憑證號碼無效，請重新輸入
                $messageCode = '010916001';
                return fasle;
            }
            if ($querydata[0]['sd_id'] != $sd_id) {
                //010916002	非貴司所發行之服務，請通知用戶前往正確商家使用
                $messageCode = '010916002';
                return fasle;
            }
            if ($querydata[0]['ssqq_usestatus'] == '2' || $querydata[0]['ssqq_usestatus'] == '5' || $querydata[0]['ssqq_usestatus'] == '6' || $querydata[0]['ssqq_usestatus'] == '8') {
                //010916006	用戶已服務完成，無法再次使用
                $messageCode = '010916006';
                return fasle;
            }
            //建立回傳資料
            $resultdata = ShopServiceScan::Create_ResultData($querydata);
            $ssqq_queserno = $querydata[0]['ssqq_queserno'];

            if ($querydata[0]['ssqq_usestatus'] == '3') {
                //010916003	用戶已棄用，由商家自行決定是否提供服務
                $messageCode = '010916003';
                return fasle;
            }
            if ($querydata[0]['ssqq_usestatus'] == '4') {
                //010916005	用戶已設置失約，由商家自行決定是否提供服務
                $messageCode = '010916005';
                return fasle;
            }
            if ($querydata[0]['ssqq_usestatus'] == '7') {
                //010916007	用戶已過號，由於商家自行決定是否提供服務
                $messageCode = '010916007';
                return fasle;
            }
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            $messageCode = '999999999';
            return fasle;
        }
    }

    private function Create_ResultData($data) {
        try {
            if (count($data) == 0) {
                return null;
            }
            $result['ssqd_title'] = $data[0]['ssqd_title'];
            $result['ssqd_serviceprice'] = $data[0]['ssqd_serviceprice'];
            $result['ssqd_mainpic'] = $data[0]['ssqd_mainpic'];
            $result['md_cname'] = $data[0]['md_cname'];
            $result['ssd_picturepath'] = $data[0]['ssd_picturepath'];
            $result['ssqq_questarttime'] = $data[0]['ssqq_questarttime'];

            return $result;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            $messageCode = '999999999';
            return null;
        }
    }

}
