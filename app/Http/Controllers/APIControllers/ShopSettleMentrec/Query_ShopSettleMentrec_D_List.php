<?php

namespace App\Http\Controllers\APIControllers\ShopSettleMentrec;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** query_shopsettlementrec_d_list	取得該店家銷售結算子表清單 * */
class Query_ShopSettleMentrec_D_List {
   function query_shopsettlementrec_d_list() {
        $functionName = 'query_shopsettlementrec_d_list';
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
            if(!$this->CheckInput($inputData)){
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
            if (!Commontools::CheckShopUserIdentity( $inputData['sat'], $inputData['sd_id'], $md_id, $shopdata , $messageCode)) {
                throw new \Exception($messageCode);
            }
            $queryData = $this->createResultData($inputData['sd_id'],$inputData['ssrm_id'],$inputData['billingtype'], $inputData['data_pagination'], $inputData['queryamount'], $count);
            $resultData['totalcount'] = $count;
            $resultData['query_shopsettlementrec_d_list'] = $queryData;
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'ssrm_id', 20, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'billingtype', 1, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'data_pagination', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'queryamount', 3, true, false)) {
            return false;
        }
        if ( is_null($value['queryamount']) ) {
          $value['queryamount'] = 10;
        } else if($value['queryamount'] > 100 ) {
          $value['queryamount'] =  100;
        }
        $value['data_pagination'] = ( $value['data_pagination'] - 1 ) * $value['queryamount'];
        
        return true;
    }
    
    
    
    
    function createResultData($sd_id, $ssrm_id, $billingtype, $data_pagination, $queryamount, &$count) {
        try {
            $ssrdRepo = new \App\Repositories\ICR_ShopSettleMentrec_dRepository;
            $ssrdData = $ssrdRepo -> GetDataBySsrmId_SdId_BillingType($ssrm_id, $sd_id, $billingtype, $data_pagination, $queryamount, $count);
            return $ssrdData;
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
        }
        
    }
    
    
    
}