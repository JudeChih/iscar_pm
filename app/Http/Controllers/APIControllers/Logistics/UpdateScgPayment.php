<?php

namespace App\Http\Controllers\APIControllers\Logistics;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** updatescgpayment	scl，scg更新付款狀態 改以付款 * */
class UpdateScgPayment {
   function updatescgpayment() {
        $functionName = 'updatescgpayment';
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
            if(!UpdateScgPayment::CheckInput($inputData)){
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
            //$LogisticsRepo = new \App\Repositories\ICR_SCLRepository();
            $Logistics = new \App\Http\Controllers\APIControllers\Logistics\Logistics;
            $querydata =  \App\Models\ICR_ShopCouponData_g::getDataByScgId($inputData['scg_id']);
            if (count($querydata) == 0 ) {
              $messageCode=  '999999983';
              throw new \Exception($messageCode);
            }
            $Md_Id_Array = array($querydata[0]['smb_md_id'], $md_id);
            $target = 1;
            $iscar_push = '{"target" :"'. $target. '","id_1":"","id_2":""}';
            $amount = $querydata[0]['scm_price'] * $querydata[0]['scg_buyamount'];
            if (! UpdateScgPayment::updatePaymentStatus($querydata) 
            ||  ! $Logistics->Insert_MsLog_1104( $querydata[0]['smb_md_id'],  $querydata[0]['scg_id'],  $amount )
            ||  ! $Logistics->Insert_MsLog_1105( $md_id, $querydata[0]['sd_shopname'], $querydata[0]['scm_title'], $amount )
            ||  ! $memService->push_notification($inputData['sat'], $Md_Id_Array, null, null, $target, $iscar_push)
            ) {
              throw new \Exception($messageCode);
            }
            

            $messageCode = '000000000';
       } catch(\Exception $e) {
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scg_id', 0, false, false)) {
            return false;
        }
        return true;
    }


    public function updatePaymentStatus($queryData){
      try {
            
            $scgData = [ 
                                  'scg_id' => $queryData[0]['scg_id'], 
                                  'scg_usestatus' => 5 ,
                                  'scg_paymentstatus' => 1 ];
            if (! \App\Models\ICR_ShopCouponData_g::UpdateData($scgData) ) {
              throw new \Exception();
            }
            return true;
      } catch (\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return false;
      }
    }


}