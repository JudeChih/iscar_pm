<?php

namespace App\Http\Controllers\APIControllers\ShopPush;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** query_shopservice_fee	特約商服務費用項目 **/
class QueryShopserviceFee {
   function queryshopservicefee() {
        $functionName = 'queryshopservicefee';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //輸入值
            if(!QueryShopserviceFee::CheckInput($inputData)){
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
             //檢查「店家」、「管理員」權限
            if (!Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            $ShopPush = new ShopPush;
            $pushFeeData = $ShopPush->QueryShopPushFee($serno = null, $servicetype = $inputData['ssfi_servicetype'] , $messageCode);
            if (is_null($pushFeeData) || count($pushFeeData) == 0 ) {
                throw new \Exception($messageCode);
            } 
            //建立回傳值
            if (!QueryShopserviceFee::CreateResultData ($pushFeeData, $resultData)) {
                throw new \Exception($messageCode);
            }
           
            $messageCode ='000000000';
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        } 
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssfi_servicetype', 0, false, false)) {
            return false;
        } 
       
        return true;
    }
    
    
    /**
     * 建立回傳值
     * @param type $arrayData
     * @param type $resultData
     * @return boolean
     */
    function CreateResultData ($pushFeeData, &$resultData) {
       try {
            $resultData['parameterkey'] = null;
            foreach ($pushFeeData as $rowData) {
                $resultData['parameterkey'][] = [
                                                 'ssfi_serno'            => $rowData['ssfi_serno'],
                                                 'ssfi_feename'          => $rowData['ssfi_feename'],
                                                 'ssfi_itemprice'        => $rowData['ssfi_itemprice'],
                                                 'ssfi_pointforconsume'  => $rowData['ssfi_pointforconsume'],
                                               ];
            }
            return true;
       } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
       }
    }
   
       
 
}