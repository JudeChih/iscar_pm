<?php

namespace App\Http\Controllers\APIControllers\Logistics;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** queryorderlist	供商家查看所有訂單資料列表 * */
class QueryOrderlist {
   function queryorderlist() {
        $functionName = 'queryorderlist';
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
            if(!QueryOrderlist::CheckInput($inputData)){
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
            if (!\App\library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            $querydata = \App\Models\ICR_ShopCouponData_g::GetData_CouponDataM_Logistics($inputData, $count);
            $resultData['totalcount'] = $count;
            $resultData['order_list'] =$querydata;

            //叫號完成，請等候用戶前往
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scm_producttype', 0, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scm_id', 0, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'create_date_start', 0, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'create_date_end', 0, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scg_usestatus', 0, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'data_pagination', 0, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'start_scg_id', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'queryamount', 3, true, false)) {
            return false;
        }
        if ( is_null($value['scm_producttype']) && is_null($value['scm_id']) && is_null($value['create_date_start']) && is_null($value['create_date_end']) && is_null($value['scg_usestatus']) ) {
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
}