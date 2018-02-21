<?php

namespace App\Http\Controllers\APIControllers\Logistics;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** reportcargopack	供商家用戶回報實體商品出貨狀態 * */
class ReportCarGoPack {
   function reportcargopack() {
        $functionName = 'reportcargopack';
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
            if(!ReportCarGoPack::CheckInput($inputData)){
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
            $querydata = $LogisticsRepo->getNeedReportLogistic($inputData['scl_id'], 1, 6);
            //$resultData['123'] = $querydata[0];
            if (count($querydata) == 0 ) {
              $messageCode=  '999999983';
              throw new \Exception($messageCode);
            }
            if (! ReportCarGoPack::updateLogisticsData($inputData['scl_cargopack_pic'],$querydata, $LogisticsRepo)) {
              throw new \Exception($messageCode);
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scl_cargopack_pic', 0, false, false)) {
            return false;
        }
        return true;
    }


    public function updateLogisticsData($scl_cargopack_pic, $queryData, $LogisticsRepo){
      try {
           $updatedata = [
                                'scl_id' => $queryData[0]['SCLID'],
                                'scl_deliverstatus' => 2,
                                'scl_cargopicktime' =>date('Y-m-d H:i:s'),
                                'scl_cargopack_pic' =>$scl_cargopack_pic
           ];
            if (! $LogisticsRepo->UpdateData($updatedata) ) {
              throw new \Exception();
            }
            $updatedata = [
                                      'scg_usestatus' => '7',
                                      'scg_id' => $queryData[0]['scg_id']
                                      ];
            if (! \App\Models\ICR_ShopCouponData_g::UpdateData($updatedata) ) {
              throw new \Exception();
            }
            return true;
      } catch (\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return false;
      }
    }


}