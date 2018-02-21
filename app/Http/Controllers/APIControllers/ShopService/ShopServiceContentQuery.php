<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** shopservicecontentquery	商家服務排隊項目內容查詢 * */
class ShopServiceContentQuery {

    function shopservicecontentquery() {
        $functionName = 'shopservicecontentquery';
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
            if (!ShopServiceContentQuery::CheckInput($inputData)) {
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
            if (!ShopServiceContentQuery::CreateResultData($inputData['ssqd_id'], $resultData, $messageCode)) {
                throw new \Exception($messageCode);
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqd_id', 32, false, false)) {
            return false;
        }
        return true;
    }

    /**
     * 建立回傳資料
     * @param type $ssqd_id
     * @return type
     */
    private function CreateResultData($ssqd_id, &$result, &$messageCode) {
        try {

            $quedata_d = \App\Models\ICR_ShopServiceQue_d::GetData($ssqd_id);

            if (count($quedata_d) == 0) {
                //010913001	服務編號有誤，請確認後重發
                $messageCode = '010913001';
                $result['ssqd_title'] = null;
                $result['ssqd_content'] = null;
                $result['ssqd_mainpic'] = null;
                $result['ssqd_workhour'] = null;
                $result['ssqd_serviceprice'] = null;
                $result['ssqd_effectivity'] = null;
                $result['ssqd_maxqueueamount'] = null;
                return false;
            }

            $result['ssqd_title'] = $quedata_d[0]['ssqd_title'];
            $result['ssqd_content'] = $quedata_d[0]['ssqd_content'];
            $result['ssqd_mainpic'] = $quedata_d[0]['ssqd_mainpic'];
            $result['ssqd_workhour'] = $quedata_d[0]['ssqd_workhour'];
            $result['ssqd_serviceprice'] = $quedata_d[0]['ssqd_serviceprice'];
            $result['ssqd_effectivity'] = $quedata_d[0]['ssqd_effectivity'];
            $result['ssqd_maxqueueamount'] = $quedata_d[0]['ssqd_maxqueueamount'];

            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            $messageCode = '999999999';
            return false;
        }
    }

}
