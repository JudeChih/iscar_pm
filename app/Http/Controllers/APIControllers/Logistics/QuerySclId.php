<?php

namespace App\Http\Controllers\APIControllers\Logistics;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** querysclId	接收傳入之物流ID是否存在及仍有效 scl_iD * */
class QuerySclId {
   function querysclId() {
        $functionName = 'querysclid';
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
            if(!QuerySclId::CheckInput($inputData)){
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
            $LogisticsRepo = new \App\Repositories\ICR_SCLRepository();
            $querydata = $LogisticsRepo->getDataBySciId(array($inputData['scl_id']));
            if (count($querydata) == 0 ) {
              $messageCode=  '011501001';
              throw new \Exception($messageCode);
            } else {
              $resultData['scm_id'] = $querydata[0]['scm_id'];
              $resultData['scg_usestatus'] = $querydata[0]['scg_usestatus'];
            }
            $messageCode = '000000000';
       } catch(\Exception $e){
            if (is_null($messageCode)) {
              $messageCode = '999999999';
              \App\Models\ErrorLog::InsertData($e);
            }
         }
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scl_id', 0, false, false)) {
            return false;
        }
        return true;
    }
}