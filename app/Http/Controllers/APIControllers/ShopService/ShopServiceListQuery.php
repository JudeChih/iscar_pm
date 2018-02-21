<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** shopservicelistquery	商家服務排隊項目列表查詢 * */
class ShopServiceListQuery {

    function shopservicelistquery() {
        $functionName = 'shopservicelistquery';
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
            if (!ShopServiceListQuery::CheckInput($inputData)) {
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

            if (!ShopServiceListQuery::Check_ShopQueData($inputData['sd_id'], $messageCode)) {
                throw new \Exception($messageCode);
            }

            $resultData = ShopServiceListQuery::CreateResultData($inputData['sd_id']);
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }
        return true;
    }

    /**
     *
     * @param type $sd_id
     * @param string $messageCode
     */
    private function Check_ShopQueData($sd_id, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopData::GetData($sd_id);
            if (count($querydata) == 0) {
                //010901002	無此商家編號，請重新輸入
                $messageCode = '010901002';
                return false;
            }
            
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
        }
    }

    private function CreateResultData($sd_id) {
        try {
    
            $quedata_m = \App\Models\ICR_ShopServiceQue_m::GetData($sd_id);
            $quedata_d = \App\Models\ICR_ShopServiceQue_d::GetDataBySDID($sd_id);
            \App\Models\ICR_ShopServiceQue_q::Query_QueCount($sd_id, $quecount, $serviced);

            if (count($quedata_m) == 0) {
                $result['ssqm_weekstart'] = null;
                $result['ssqm_weekend'] = null;
                $result['ssqm_dailystart'] = null;
                $result['ssqm_dailyend'] = null;
                $result['ssqm_servicestart'] = null;
            } else {
                $result['ssqm_weekstart'] = $quedata_m[0]['ssqm_weekstart'];
                $result['ssqm_weekend'] = $quedata_m[0]['ssqm_weekend'];
                $result['ssqm_dailystart'] = $quedata_m[0]['ssqm_dailystart'];
                $result['ssqm_dailyend'] = $quedata_m[0]['ssqm_dailyend'];
                $result['ssqm_servicestart'] = $quedata_m[0]['ssqm_servicestart'];
            }

            if (count($quedata_d) == 0) {
                $result['servicelist'] = null;
            } else {
                $result['servicelist'] = ShopServiceListQuery::Create_servicelist($quedata_d);
            }

            $result['today_tatal'] = $quecount;
            //$result['today_serviced'] = $serviced;

            return $result;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
        }
    }

    private function Create_servicelist($serviceData) {
        try {
            $result = null;
            $count = 0;
            $shopService = new \App\Http\Controllers\APIControllers\ShopService\ShopService;
            foreach ($serviceData as $rowdata) {
                \App\Models\ICR_ShopServiceQue_q::Query_QueueCount($rowdata['sd_id'], $rowdata['ssqd_id'], $quecount, $serviced);
                $result[$count]['ssqd_id']= $rowdata['ssqd_id'];
                $result[$count]['ssqd_title'] = $rowdata['ssqd_title'];
                $result[$count]['ssqd_mainpic'] = $rowdata['ssqd_mainpic'];
                $result[$count]['ssqd_serviceprice'] = $rowdata['ssqd_serviceprice'];
                $result[$count]['ssqd_effectivity'] = $rowdata['ssqd_effectivity'];
                $result[$count]['que_waiting_time'] = $shopService->query_WaitingTime($rowdata['sd_id'], $rowdata['ssqd_workhour']);
                $result[$count]['today_serviced'] = $serviced;
                $count ++;
            }

            return $result;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

}
