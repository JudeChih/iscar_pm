<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** shopservicequerecorver	用戶取得所有排隊記錄 * */
class ShopServiceQueRecorver {
   function shopservicequerecorver() {
        $functionName = 'shopservicequerecorver';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            if($inputData == null){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //輸入值
            if(!ShopServiceQueRecorver::CheckInput($inputData)){
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
            if (!$memService->checkServiceAccessToken($inputData['sat'], $md_id, $messageCode)) {
              //呼叫「MemberAPI」檢查SAT的狀態，驗證SAT有效性
               //$messageCode = '999999962';
               throw new \Exception($messageCode);
            }
            //依 last_update_date 取值，並判斷有無值
            if($inputData['last_update_date'] == null) {
               $ssqq_querydata = \App\Models\ICR_ShopServiceQue_q::Query_FindAllClientShopService($md_id, null);
               if (is_null($ssqq_querydata) || count($ssqq_querydata) == 0) {
                  //無需更新，作業完成
                  $messageCode = '000000001';
                  throw new \Exception($messageCode);
               }
            } else {
               $ssqq_querydata= \App\Models\ICR_ShopServiceQue_q::Query_FindAllClientShopService($md_id, $inputData['last_update_date']);
               if (is_null($ssqq_querydata) || count($ssqq_querydata) == 0) {
                  //無需更新，作業完成
                  $messageCode = '000000001';
                  throw new \Exception($messageCode);
               }
            }
            //取得各項list。
            $ssqq_list = $ssqq_querydata;
            $ssqdId_array = array();
            $sdId_array = array();
            foreach($ssqq_list as $rowdata) {
              array_push($ssqdId_array,$rowdata['ssqd_id']);
              array_push($sdId_array,$rowdata['sd_id']);
            }
            $ssqd_list = \App\Models\ICR_ShopServiceQue_d::Query_ServiceData_ByARRAY(array_unique($ssqdId_array));
            $sd_list = \App\Models\ICR_ShopData::Query_ShopData_ByARRAY(array_unique($sdId_array));
            $resultData['last_update_date'] = $ssqq_querydata[0]['last_update_date'];
            $resultData['ssqq_list'] = $ssqq_list;
            $resultData['ssqd_list'] = $ssqd_list;
            $resultData['sd_list'] = $sd_list;
            $messageCode = '000000009'; 
        } catch(\Exception $e){
           if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($e);
            }
        }
        $resultArray = Commontools::ResultProcess($messageCode, $resultData);
        Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [$functionName . 'result' => $resultArray];
        return  $result;
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'last_update_date', 20, true, true)) {
            return false;
        }
        return true;
    }
}
   