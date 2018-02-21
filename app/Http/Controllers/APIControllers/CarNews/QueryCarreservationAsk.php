<?php

namespace App\Http\Controllers\APIControllers\CarNews;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** querycarreservationask	賣方查詢買方車輛約看詢問記錄 * */
class QueryCarreservationAsk {
   function querycarreservationask() {
        $functionName = 'querycarreservationask';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //輸入值
            if(!QueryCarreservationAsk::CheckInput($inputData)){
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
            //記錄用戶端所屬功能類型，0:一般用戶端 1:合作社商家用戶
            if ($inputData['md_clienttype'] == 0) {
               $crn_owner_id = $md_id ;
               $crn_id = $inputData['crn_id'];
               $cbi_id = $inputData['cbi_id'];
            } else if ($inputData['md_clienttype'] == 1) {
               //檢查「店家」、「管理員」權限
               if (!\App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                   throw new \Exception($messageCode);
               } else {
                   $crn_owner_id = $inputData['sd_id'];
                   $crn_id = $inputData['crn_id'];
                   $cbi_id = $inputData['cbi_id'];
               }
            } else {
               //無效的操作動作，請重新輸入
               $messageCode = '011101002';
               throw new \Exception($messageCode);
            }
            $querydata = \App\Models\ICR_Carreservation::GetData_ByCrnId($cbi_id,$crn_owner_id,$crn_id);
            if(is_null($querydata) || count($querydata) == 0 || count($querydata) > 1) {
               //查無約看記錄，請確認後重發
               $messageCode = '011105001';
               throw new \Exception($messageCode);
            }

            if (!QueryCarreservationAsk::CreateResultData($querydata, $resultData)) {
                throw new \Exception($messageCode);
            }

            $messageCode ='00000000';
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'md_clienttype', 1, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'crn_id', 32, false, true)) {
            return false;
        }
        return true;
    }


    function CreateResultData($querydata, &$resultData) {
       try {
           $resultData = [
                            'cbi_advertisementtitle'  => $querydata[0]['cbi_advertisementtitle'],
                            'cps_picpath'             => $querydata[0]['cps_picpath'],
                            'cbl_fullname'            => $querydata[0]['cbl_fullname'] ,
                            'cbm_fullname'            => $querydata[0]['cbl_fullname'],
                            'cms_fullname'            => $querydata[0]['cms_fullname'],
                            'crn_buyer_realname'      => $querydata[0]['crn_buyer_realname'],
                            'crn_buyer_ask_message'   => $querydata[0]['crn_buyer_ask_message'],
                            'crn_available_timearray' => $querydata[0]['crn_available_timearray'],
                            'crn_carinstore_ask'      => $querydata[0]['crn_carinstore_ask']
                         ];
           return true;
       } catch(\Exception $e) {
         return false;
         App\Models\ErrorLog::InsertData($e);
       }
    }
}