<?php

namespace App\Http\Controllers\APIControllers\Logistics;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** printdeliverorder	供商家批量列印時間區間內所有未執行之物流單據 * */
class PrintDeliverOrder {
   function printdeliverorder() {
        $functionName = 'printdeliverorder';
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
            if(!PrintDeliverOrder::CheckInput($inputData)){
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
            $LogisticsData = $LogisticsRepo->getPrintDataAndUpdate(Commontools::ConvertStringToArray($inputData['scl_id_array']), $inputData['sd_id']);
            if ( ! PrintDeliverOrder::creatResultData($resultData, $LogisticsData)) {
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scl_id_array', 0, true, false)) {
            return false;
        }
        return true;
    }


    function creatResultData(&$resultData, $arrayData) {
      try {

        foreach ($arrayData as $rowData) {
             $resultData['deliverorder_array'][] = [
                 'scl_id' => $rowData['Scl_id'],
                 'scg_id' => $rowData['scg_id'],
                 'scm_id' => $rowData['scm_id'],
                 'scm_title' => $rowData['scm_title'],
                 'scm_producttype' => $rowData['scm_producttype'],
                 'scm_price' => $rowData['scm_price'],
                 'scg_paymentstatus' => $rowData['scg_paymentstatus'],
                 'scg_buyamount' => $rowData['scg_buyamount'],
                 'scg_totalamount' => $rowData['scg_totalamount'],
                 'scg_buyermessage' => $rowData['scg_buyermessage'],
                 'scl_receivername' => $rowData['scl_receivername'],
                 'scl_receivermobile' => $rowData['scl_receivermobile'],
                 'scl_receiverphone' => $rowData['scl_receiverphone'],
                 'scl_email' => $rowData['scl_email'],
                 'scl_postcode' => $rowData['scl_postcode'],
                 'scl_city' => $rowData['scl_city'],
                 'scl_district' => $rowData['scl_district'],
                 'scl_receiveaddress' => $rowData['scl_receiveaddress'],
                 'sd_shopname' => $rowData['sd_shopname'],
                 'sd_contact_address' => $rowData['sd_contact_address'],
                 'sd_contact_tel' => $rowData['sd_contact_tel']
             ];
        }
        return true;
      } catch( \Exception $e ) {
        \App\Models\ErrorLog::InsertData($e);
        return false;
      }
    }
}