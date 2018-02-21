<?php

namespace App\Http\Controllers\APIControllers\ShopQuestionnaire;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** shopquestionnaire_result	合作社問卷答覆結果查看 * */
class ShopQuestionnaire_Result {

    function shopquestionnaire_result() {
        $functionName = 'shopquestionnaire_result';
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
            if (!ShopQuestionnaire_Result::CheckInput($inputData)) {
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
            //查詢店家問卷分數
            if (!ShopQuestionnaire_Result::QueryShopQuestionnaire($inputData['sd_id'], $sd_questionnaireresult, $sd_questiontotalaverage)) {
                $messageCode = '999999999';
                throw new \Exception($messageCode);
            }

            $resultData['activemessage'] = ShopQuestionnaire_Result::Query_ResultData($inputData['sd_id'], $datacount);
            $resultData['answercount'] = $datacount;
            $resultData['sd_questionnaireresult'] = $sd_questionnaireresult;
            $resultData['sd_questiontotalaverage'] = $sd_questiontotalaverage;

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
     * 取得回傳問卷資料
     * @return type
     */
    private function Query_ResultData($sd_id, &$datacount) {
        try {

            $querydata = \App\Models\ICR_ShopQuestionnaire_a::Query_QuestionnaireData($sd_id);
            if (($datacount = count($querydata)) == 0) {
                return null;
            }

            return ShopQuestionnaire_Result::TransToActiveMessageList($querydata);
        } catch (Exception $ex) {
            return null;
        }
    }

    /**
     * 轉換問卷資料成陣列
     * @param type $questionnairedata
     * @return type
     */
    private function TransToActiveMessageList($questionnairedata) {

        if (count($questionnairedata) == 0) {
//            \App\Models\ErrorLog::InsertLog('row99');

            return null;
        }

        foreach ($questionnairedata as $rowdata) {
            $resultdata[] = [
                'sqna_id' => $rowdata['sqna_id']
                , 'md_cname' => $rowdata['md_cname']
                , 'ssd_picturepath' => $rowdata['ssd_picturepath']
                , 'activetitle' => ShopQuestionnaire_Result::QueryActiveTitle($rowdata['event_type'], $rowdata['event_id'])
                , 'sqna_message' => $rowdata['sqna_message']
                , 'ci_serno' => $rowdata['ci_serno']
                , 'sqnr_id' => $rowdata['sqnr_id']
                , 'sqnr_responsemessage' => $rowdata['sqnr_responsemessage']
                , 'sqna_last_update' => $rowdata['sqna_last_update']
                , 'sqnr_last_update' => $rowdata['sqnr_last_update']
            ];
        }

        return $resultdata;
    }

    /**
     * 查詢 問卷記錄所對應名稱
     * @param type $event_type
     * @param type $event_id
     * @return type
     */
    private function QueryActiveTitle($event_type, $event_id) {
        try {
            if ($event_type == '0') {
                return ShopQuestionnaire_Result::QueryActiveTitle_0($event_id);
            } else if ($event_type == '1') {
                return ShopQuestionnaire_Result::QueryActiveTitle_1($event_id);
            } else {
                return null;
            }
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 查詢 問卷記記錄所對應〔０：優惠券〕所對應的名稱
     * @param type $event_id 事件代碼
     * @return type
     */
    private function QueryActiveTitle_0($event_id) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_g::GetData_Event($event_id);
            if (count($querydata) == 0) {
                return null;
            }

            return $querydata[0]['scm_title'];
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 查詢 問卷記記錄所對應〔１：服務排隊〕所對應的名稱
     * @param type $event_id 事件代碼
     * @return type
     */
    private function QueryActiveTitle_1($event_id) {
        try {
            $querydata = \App\Models\ICR_ShopServiceQue_q::GetData_Event($event_id);
            if (count($querydata) == 0) {
                return null;
            }

            return $querydata[0]['ssqd_title'];
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    private function QueryShopQuestionnaire($sd_id, &$sd_questionnaireresult, &$sd_questiontotalaverage) {
        try {
            $querydata = \App\Models\ICR_ShopData::GetData($sd_id);

            if (count($querydata) == 0) {
                return false;
            }

            $sd_questionnaireresult = $querydata[0]['sd_questionnaireresult'];
            $sd_questiontotalaverage = $querydata[0]['sd_questiontotalaverage'];

            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

}
