<?php

namespace App\Http\Controllers\APIControllers\ShopQuestionnaire;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** shopquestionnaire_response	合作社商家回覆問卷留言 * */
class ShopQuestionnaire_Response {

    function shopquestionnaire_response() {
        $functionName = 'shopquestionnaire_response';
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
            if (!ShopQuestionnaire_Response::CheckInput($inputData)) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            $md_id = null;
            ///檢查身份模組驗證
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

            if (!ShopQuestionnaire_Response::Check_Questionnaire_a($inputData['sqna_id'], $sqnr_responsemessage, $messageCode)) {
                throw new \Exception($messageCode);
            }

            if (!ShopQuestionnaire_Response::ModifyData($inputData, $md_id, $sqnr_responsemessage, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //010912000	回覆完成
            $messageCode = '010912000';
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sqna_id', 32, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sqnr_id', 32, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'activetype', 1, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sqnr_responsemessage', 70, true, true)) {
            return false;
        }

        if (!array_key_exists('sqna_id', $value)) {
            $value['sqna_id'] = null;
        }
        if (!array_key_exists('sqnr_id', $value)) {
            $value['sqnr_id'] = null;
        }
        return true;
    }

    /**
     * 檢查問卷留言記錄
     * @param type $sqna_id 合作社問卷填寫記錄代碼
     * @param type $messageCode
     * @return boolean
     */
    private function Check_Questionnaire_a($sqna_id, &$sqnr_responsemessage, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopQuestionnaire_a::GetData($sqna_id);
            $sqnr_responsemessage = null;
            if (count($querydata) == 0) {
                //010912001	查無欲回覆項目，請更新留言記錄
                $messageCode = '010912001';
                return false;
            }
            if (count($querydata) > 1) {
                //010912002	記錄有誤，無法留言回覆，請聯絡iscar人員進行處理
                $messageCode = '010912002';
                return false;
            }
            if (strlen($querydata[0]['sqna_message']) == 0) {
                //010912001	查無欲回覆項目，請更新留言記錄
                $messageCode = '010912001';
                return false;
            }

            $sqnr_responsemessage = $querydata[0]['sqnr_responsemessage'];
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 異動資料
     * @param type $inputData
     * @param type $sqnr_responsemessage
     * @param type $messageCode
     * @return boolean
     */
    private function ModifyData($inputData, $md_id, $sqnr_responsemessage, &$messageCode) {
        try {
            if ($inputData['activetype'] == 0) {
                if (mb_strlen($sqnr_responsemessage) != 0) {
                    //010912003	該項目已回覆，無法再添加回覆，請改用修改功能
                    $messageCode = '010912003';
                    return false;
                }
                return ShopQuestionnaire_Response::ModifyData_Add($inputData['sd_id'], $md_id, $inputData['sqna_id'], $inputData['sqnr_responsemessage']);
            } else if ($inputData['activetype'] == 1) {
                if (mb_strlen($sqnr_responsemessage) == 0) {
                    //010912004	該項目未有回覆，無法更新，請改用新增功能
                    $messageCode = '010912004';
                    return false;
                }
                return ShopQuestionnaire_Response::ModifyData_Update($inputData['sqnr_id'], $inputData['sqnr_responsemessage']);
            } else if ($inputData['activetype'] == 2) {
                if (mb_strlen($sqnr_responsemessage) == 0) {
                    //010912004	該項目未有回覆，無法更新，請改用新增功能
                    $messageCode = '010912004';
                    return false;
                }
                return ShopQuestionnaire_Response::ModifyData_Delete($inputData['sqnr_id']);
            } else {
                //010901006	傳入值格式內容格式有誤，請重新輸入
                $messageCode = '010901006';
                return false;
            }
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            $messageCode = '999999999';
            return false;
        }
    }

    /**
     * 異動資料 新增
     * @param type $sd_id
     * @param type $md_id
     * @param type $sqna_id
     * @param type $sqnr_responsemessage
     * @param string $messageCode
     * @return boolean
     */
    private function ModifyData_Add($sd_id, $md_id, $sqna_id, $sqnr_responsemessage) {
        try {
            $modifydata['sd_id'] = $sd_id;
            $modifydata['md_id'] = $md_id;
            $modifydata['sqna_id'] = $sqna_id;
            $modifydata['sqnr_responsemessage'] = $sqnr_responsemessage;

            return \App\Models\ICR_ShopQuestionnaire_r::InsertData($modifydata, $primarykey);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 異動資料 修改
     * @param type $sqnr_id
     * @param type $sqnr_responsemessage
     * @return boolean
     */
    private function ModifyData_Update($sqnr_id, $sqnr_responsemessage) {
        try {
//            \App\Models\ErrorLog::InsertLog('ROW218');
            $modifydata['sqnr_id'] = $sqnr_id;
            $modifydata['sqnr_responsemessage'] = $sqnr_responsemessage;

            return \App\Models\ICR_ShopQuestionnaire_r::UpdateData($modifydata);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 異動資料 刪除
     * @param type $sqnr_id
     * @param type $sqnr_responsemessage
     * @return boolean
     */
    private function ModifyData_Delete($sqnr_id) {
        try {
            $modifydata['sqnr_id'] = $sqnr_id;
            $modifydata['isflag'] = 0;

            return \App\Models\ICR_ShopQuestionnaire_r::UpdateData($modifydata);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

}
