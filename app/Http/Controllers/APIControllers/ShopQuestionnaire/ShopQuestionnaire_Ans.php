<?php

namespace App\Http\Controllers\APIControllers\ShopQuestionnaire;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** shopquestionnaire_ans	合作社問卷答覆接收 * */
class ShopQuestionnaire_Ans {

    function shopquestionnaire_ans() {
        $functionName = 'shopquestionnaire_ans';
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
            if (!ShopQuestionnaire_Ans::CheckInput($inputData)) {
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
            if (!ShopQuestionnaire_Ans::Check_Eevent_ID($inputData['event_type'], $inputData['event_id'], $md_id, $sd_id, $messageCode)) {
                throw new \Exception($messageCode);
            }
            
            //建立「icr_shopquestionnaire_a」
            ShopQuestionnaire_Ans::InsertData_ICR_ShopQuestionnaire_a($inputData, $md_id, $sd_id, $sqna_id);

            ShopQuestionnaire_Ans::Calculate_AVG($sd_id, $inputData['answercontent'], $inputData['averagescore']);
            
           
            //評論 給與禮點
            $modifytype=($inputData['event_type']=='0')? 9 : 8;
            $appService = new \App\Services\AppService;
            $messageCode = $appService->GetGiftPointDayLimit($md_id,$modifytype);
            $giftPointAmount = $appService->getGiftPointsAmount($modifytype );
            $gpmr_point = $appService->modifyGiftPoint($md_id, $modifytype, $sqna_id, 1, true, $giftPointAmount);
            
            //ShopQuestionnaire_Ans::SendBonusByComment($inputData['modacc'], $inputData['modvrf'], $inputData['sat'] , $md_id, $sqna_id, $cos_end);
            $resultData['cos_end_giftpoint'] = $gpmr_point;
            if ($messageCode != '000000000') {
                throw new \Exception($messageCode);
            }
            //011002000	感謝你的填寫
            $messageCode = '011002000';
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'answercontent', 0, false, true)) {
            return false;
        } 
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ci_serno', 3, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sqna_message', 70, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'averagescore', 5, false, false)) {
            return false;
        }

        if (!array_key_exists('ci_serno', $value)) {
            $value['ci_serno'] = null;
        }
        if (!array_key_exists('sqna_message', $value)) {
            $value['sqna_message'] = null;
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
    private function Check_Eevent_ID($event_type, $event_id, $md_id, &$sd_id, &$messageCode) {
        try {
            if ($event_type == '0') {
                return ShopQuestionnaire_Ans::Check_Eevent_ID_0($event_id, $md_id, $sd_id, $messageCode);
            } else if ($event_type == '1') {
                return ShopQuestionnaire_Ans::Check_Eevent_ID_1($event_id, $md_id, $sd_id, $messageCode);
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
    private function Check_Eevent_ID_0($event_id, $md_id, &$sd_id, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_g::GetData_Event($event_id);
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
            if ($querydata[0]['scg_usestatus'] != '2') {
                //011001003	查無使用完畢記錄，請確認後重發
                $messageCode = '011001003';
                return false;
            }
            if (!is_null($querydata[0]['sqna_id'])) {
                 //011002001 您已完成該問卷，感謝你的填寫
                 $messageCode = '011002001';
                return false;
            }

            $sd_id = $querydata[0]['sd_id'];
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
    private function Check_Eevent_ID_1($event_id, $md_id, &$sd_id, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopServiceQue_q::GetData_Event($event_id);
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
            if (!is_null($querydata[0]['sqna_id'])) {
                 //011002001 您已完成該問卷，感謝你的填寫
                 $messageCode = '011002001';
                return false;
            }

            $sd_id = $querydata[0]['sd_id'];
            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 建立問卷填寫記錄
     * @param type $modifydata
     * @param type $md_id
     * @param type $sd_id
     * @return boolean
     */
    private function InsertData_ICR_ShopQuestionnaire_a($modifydata, $md_id, $sd_id, &$sqna_id) {
        try {

            $modify['md_id'] = $md_id;
            $modify['sd_id'] = $sd_id;
            $modify['event_type'] = $modifydata['event_type'];
            $modify['event_id'] = $modifydata['event_id'];
            $modify['sqna_answercontent'] = $modifydata['answercontent'];
            $modify['ci_serno'] = $modifydata['ci_serno'];
            $modify['sqna_message'] = $modifydata['sqna_message'];
            $modify['sqna_averagescore'] = $modifydata['averagescore'];

            return \App\Models\ICR_ShopQuestionnaire_a::InsertData($modify, $sqna_id);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    private function Calculate_AVG($sd_id, $answercontent, $averagescore) {
        $shopdata = \App\Models\ICR_ShopData::GetData($sd_id);

        if (count($shopdata) == 0) {
            return false;
        }
        //現在資料庫中的資料
        $sd_questionnaireresult = json_decode($shopdata[0]['sd_questionnaireresult'], true);
        $sd_questiontotalaverage = json_decode($shopdata[0]['sd_questiontotalaverage'], true);

        ShopQuestionnaire_Ans::Calculate_AVG_Categoty($sd_id, $answercontent, $sd_questionnaireresult);
        ShopQuestionnaire_Ans::Calculate_AVG_AverageScore($sd_id, $averagescore, $sd_questiontotalaverage);
    }

    private function Calculate_AVG_Categoty($sd_id, $answercontent, $sd_questionnaireresult) {

        //這次輸入的資料
        $answer = json_decode($answercontent, true);
        $questionnaireanswer = $answer['questionnaireanswer'];
        $questionnaireresult = $sd_questionnaireresult['questionnaireresult'];

        for ($i = 0; $i <= 7; $i++) {
            //篩選 資料庫中的類別
            $sd_questionnairFilter = null;
            if (count($sd_questionnaireresult) > 0) {
                $sd_questionnairFilter = array_reduce($questionnaireresult, function( $carry, $obj ) use ( $i ) {
                    if ( ($obj['sd_question_category']) == $i) {
                        $carry['sd_question_category'] = $i;
                        $carry['amount'] = $obj['amount'];
                        $carry['count'] = $obj['count'];
                        $carry['avg'] = $obj['avg'];
                    }
                    return $carry;
                });
            }
            //篩選 這次輸入的類別
            $answerFilter = array_filter($questionnaireanswer, function($obj) use($i) {
                return $obj['sd_question_category'] == $i;
            });

            if (count($sd_questionnairFilter) == 0) {
                //資料庫中無資料建立 起始資料
                $temp['sd_question_category'] = $i;
                $temp['amount'] = 0;
                $temp['count'] = 0;
                $temp['avg'] = 0;
            } else {
                $temp = $sd_questionnairFilter;
            }
            if (count($answerFilter) == 0) {
                $QQQ[] = $temp;
                continue;
            }

            foreach ($answerFilter as $rowdata) {
                $temp['amount'] += $rowdata['answer'];
                $temp['count'] += 1;
            }

            $temp['avg'] = round($temp['amount'] / $temp['count'], 1);
            $QQQ['questionnaireresult'][] = $temp;
            $temp = null;
        }
        $savedata['sd_id'] = $sd_id;
        $savedata['sd_questionnaireresult'] = json_encode($QQQ, false);

        \App\Models\ICR_ShopData::UpdateData($savedata);
    }

    private function Calculate_AVG_AverageScore($sd_id, $averagescore, $sd_questiontotalaverage) {
        //這次輸入的資料
        $questiontotalaverage = $sd_questiontotalaverage['questiontotalaverage'];

        if (count($questiontotalaverage) == 0) {
            $questiontotalaverage['totalamount'] = 0;
            $questiontotalaverage['count'] = 0;
            $questiontotalaverage['average'] = 0;
        }
        $questiontotalaverage['totalamount'] += $averagescore;
        $questiontotalaverage['count'] += 1;
        $questiontotalaverage['average'] = round($questiontotalaverage['totalamount'] / $questiontotalaverage['count'], 1);

        $QQ['questiontotalaverage'] = $questiontotalaverage;

        $savedata['sd_id'] = $sd_id;
        $savedata['sd_questiontotalaverage'] = json_encode($QQ, false);

        \App\Models\ICR_ShopData::UpdateData($savedata);
    }

    /**
     * 發送「評論文章」所贈送的紅利點數
     * @param type $md_id 會員代碼
     * @param type $sqna_id
     * @return boolean 執行結果
     */
   /* public function SendBonusByComment($modacc, $modvrf, $sat, $md_id, $sqna_id, &$cos_end) {
         $commentbonus = \App\Models\IsCarBonusEventList::GetBonus_ShopQuestionnaire();
         $bankService = new \App\Services\BankService;
         if ( !$bankService->modifyMemGiftPoint($md_id, 1, $commentbonus, $sqna_id, 1, null, true, $messageCode)) {
            throw new \Exception($messageCode);
         }
         $bankService-> getMemGiftPointQuery(null, $md_id, 1, $pointData, $messageCode);
        $cos_end = $pointData['gpmr_point'];
        return true;
    }*/

}
