<?php

namespace App\Http\Controllers\APIControllers\ShopQuestionnaire;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** shopquestionnaire_read	合作社問卷內容讀取 * */
class ShopQuestionnaire_Read {

    function shopquestionnaire_read() {
        $functionName = 'shopquestionnaire_read';
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
            if (!ShopQuestionnaire_Read::CheckInput($inputData)) {
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

            if (!ShopQuestionnaire_Read::Check_Eevent_ID($inputData['event_type'], $inputData['event_id'], $md_id, $event_pic, $event_title, $messageCode)) {
                throw new \Exception($messageCode);
            }
             
            $messageCode = '000000000';
            $resultData['questionnaire_content'] = ShopQuestionnaire_Read::Query_ResultData();
            $resultData['event_pic'] = $event_pic;
            $resultData['event_title'] = $event_title;
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'event_type', 1, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'event_id', 32, false, false)) {
            return false;
        }

        return true;
    }

    /**
     * 依事件類別，檢查事件代碼
     * @param type $event_type 事件類別
     * @param type $event_id 事件代碼
     * @param type $md_id
     * @param string $messageCode
     * @return boolean
     */
    private function Check_Eevent_ID($event_type, $event_id, $md_id, &$event_pic, &$event_title, &$messageCode) {
        try {
            if ($event_type == '0') {
                return ShopQuestionnaire_Read::Check_Eevent_ID_0($event_id, $md_id, $event_pic, $event_title, $messageCode);
            } else if ($event_type == '1') {
                return ShopQuestionnaire_Read::Check_Eevent_ID_1($event_id, $md_id, $event_pic, $event_title, $messageCode);
            } else {
                //011001001	輸入之活動類別有誤，請確認後重發
                $messageCode = '011001001';
                return false;
            }
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 檢查類別〔０：優惠券〕的事件代碼
     * @param type $event_id 事件代碼
     * @param type $md_id
     * @param string $messageCode
     * @return boolean
     */
    private function Check_Eevent_ID_0($event_id, $md_id, &$event_pic, &$event_title, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_g::GetData_Read($event_id);
            if (count($querydata) == 0 || is_null($querydata)) {
                //011001002	查無取用記錄，請確認後重發
                $messageCode = '011001002';
                return false;
            }
           
            if ($querydata[0]['MD_ID'] != $md_id) {
                //011001002	查無取用記錄，請確認後重發
                $messageCode = '011001002-2';  
                return false;
            }
            if ($querydata[0]['scg_usestatus'] != '2') {
                //011001003	查無使用完畢記錄，請確認後重發
                $messageCode = '011001003';
                return false;
            }

            $event_title = $querydata[0]['scm_title'];
            $event_pic = $querydata[0]['scm_mainpic'];
            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 檢查類別〔１：服務排隊〕的事件代碼
     * @param type $event_id 事件代碼
     * @param type $md_id
     * @param string $messageCode
     * @return boolean
     */
    private function Check_Eevent_ID_1($event_id, $md_id, &$event_pic, &$event_title, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopServiceQue_q::GetData_Read($event_id);
            if (count($querydata) == 0) {
                //011001002	查無取用記錄，請確認後重發
                $messageCode = '011001002';
                return false;
            }
            if ($querydata[0]['MD_ID'] != $md_id) {
                //011001002	查無取用記錄，請確認後重發
                $messageCode = '011001002';
                return false;
            }
            if ($querydata[0]['ssqq_usestatus'] != '2' && $querydata[0]['ssqq_usestatus'] != '4' && $querydata[0]['ssqq_usestatus'] != '6' && $querydata[0]['ssqq_usestatus'] != '8') {
                //011001003	查無使用完畢記錄，請確認後重發
                $messageCode = '011001003';
                return false;
            }

            $event_title = $querydata[0]['ssqd_title'];
            $event_pic = $querydata[0]['ssqd_mainpic'];
            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 取得回傳問卷資料
     * @return type
     */
    private function Query_ResultData() {
        try {

            $querydata = \App\Models\ICR_ShopQuestionnaire::QueryTop10EffectiveItem();
            if (count($querydata) == 0) {
                return null;
            }
            return ShopQuestionnaire_Read::TransToQuestionnaire_ContentList($querydata);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 轉換問卷資料成陣列
     * @param type $questionnairedata
     * @return type
     */
    private function TransToQuestionnaire_ContentList($questionnairedata) {
        try {


            if (count($questionnairedata) == 0) {
                return null;
            }

            foreach ($questionnairedata as $rowdata) {
                $resultdata[] = [
                    'sqn_serno' => $rowdata['sqn_serno']
                    , 'sd_question_category' => $rowdata['sd_question_category']
                    , 'sqn_question' => $rowdata['sqn_question']
                    , 'sqn_answertype' => $rowdata['sqn_answertype']
                    , 'sqn_required' => $rowdata['sqn_required']
                    , 'sqn_questionorder' => $rowdata['sqn_questionorder']
                ];
            }
            return $resultdata;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

}
