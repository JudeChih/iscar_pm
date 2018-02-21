<?php

namespace App\Http\Controllers\APIControllers\Logistics;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** queryordercontent	供商家查看單筆訂單詳細內容 * */
class QueryOrderContent {
   function queryordercontent() {
        $functionName = 'queryordercontent';
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
            if(!QueryOrderContent::CheckInput($inputData)){
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
            
            $querydata = \App\Models\ICR_ShopCouponData_g::GetData_CouponDataM_LogisticsDetial($inputData['scg_id']);
            if (! QueryOrderContent::creatResultData ($resultData, $querydata[0]) ) {
               throw new \Exception($messageCode);
            }
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scg_id',0, false, false)) {
            return false;
        }
        return true;
    }

    function creatResultData (&$resultData, $arrayData) {
      try {
        $resultData = [
             'scm_title' => $arrayData['scm_title'],
             'scg_id' => $arrayData['scg_id'],
             'scm_id' => $arrayData['scm_id'],
             'sd_id'    => $arrayData['sd_id'],
             'scl_id' => $arrayData['scl_id'],
             'scm_price' => $arrayData['scm_price'],
             'scm_mainpic' => $arrayData['scm_mainpic'],
             'create_date' => $arrayData['create_date'],
             'scg_usestatus' => $arrayData['scg_usestatus'],
             'scg_buyamount' => $arrayData['scg_buyamount'],
             'scg_totalamount' => $arrayData['scg_totalamount'],
             'scg_subtract_totalamount' => $arrayData['scg_subtract_totalamount'],
             'scg_buyermessage' => $arrayData['scg_buyermessage'],
             'scg_buyername' => $arrayData['scg_buyername'],
             'scg_identifier'=> $arrayData['scg_identifier'],
             'scg_addr' =>$arrayData['scg_addr'],
             'scg_contact_phone' =>$arrayData['scg_contact_phone'],
             'scg_contact_email' =>$arrayData['scg_contact_email'],
             'scg_tax_title' =>$arrayData['scg_tax_title'],
             'scg_create_date' => $arrayData['scg_create_date'],
             'scg_paid_time' => $arrayData['scg_paid_time'],
             'scg_usedate' => $arrayData['scg_usedate'],
             'reservation_times' => $arrayData['reservation_times'],
             'scm_producttype' => $arrayData['scm_producttype'],
            'scm_coupon_providetype' => $arrayData['scm_coupon_providetype'],
            'scm_bonus_payamount' => $arrayData['scm_bonus_payamount'],
            'scm_bonus_giveamount' => $arrayData['scm_bonus_giveamount'],
            'scm_reservationtag' =>$arrayData['scm_reservationtag'],
             'scl_deliverstatus' => $arrayData['scl_deliverstatus'],
             'scl_orderprinttime' => $arrayData['scl_orderprinttime'],
             'scl_cargopicktime' => $arrayData['scl_cargopicktime'],
             'scl_senddeliverytime' => $arrayData['scl_senddeliverytime'],
             'scl_cargoarrivetime' => $arrayData['scl_cargoarrivetime'],
             'scl_cargopack_pic' => $arrayData['scl_cargopack_pic'],
             'scl_postcode' => $arrayData['scl_postcode'],
             'scl_city' => $arrayData['scl_city'],
             'scl_district' => $arrayData['scl_district'],
             'scl_receiveaddress' => $arrayData['scl_receiveaddress'],
             'scl_tracenum' => $arrayData['scl_tracenum'],
             'scr_rvdate_time' => $arrayData['scr_rvdate_time'],
             'sd_shopname' => $arrayData['sd_shopname'],
             'sd_shoptel' => $arrayData['sd_shoptel'],     
            
        ];
        return true;
      } catch( \Exception $e ) {
        \App\Models\ErrorLog::InsertData($e);
        return false;
      }
    }
}