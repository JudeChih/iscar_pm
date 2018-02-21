<?php

namespace App\Http\Controllers\APIControllers\Logistics;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** reportsentlogistics	供商家回報商品出貨之物流單號 * */
class ReportSentLogistics {
   function reportsentlogistics() {
        $functionName = 'reportsentlogistics';
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
            if(!ReportSentLogistics::CheckInput($inputData)){
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
            $querydata = $LogisticsRepo->getNeedReportLogistic($inputData['scl_id'], 2, 7);
            if (count($querydata) == 0 ) {
              $messageCode=  '999999983';
              throw new \Exception($messageCode);
            }
            if (! ReportSentLogistics::updateLogisticsData($inputData['scl_tracenum'], $querydata, $LogisticsRepo)) {
              throw new \Exception($messageCode);
            }
           /* $scgData = \App\Models\ICR_ShopCouponData_g::GetData($querydata[0]['scg_id']);
            if (!is_null($scgData[0]['sharer_id']) || stren($scgData[0]['sharer_id']) != 0) {
                  $appService = new \App\Services\AppService;
                //$systemData = \App\Models\ICR_SystemParameter::getSharerForGiftpointProportion();
                $giftpoint =  $appService->getGiftPointsAmount( 13);
                $addgiftpoint =  $scgData[0]['scg_totalamount'] * ($giftpoint / 100);
                if ($addgiftpoint > 1) {
                    $logistics = new \App\Http\Controllers\APIControllers\Logistics;
                    $appService->modifyGiftPoint($scgData[0]['sharer_id'], 13, $scgData[0]['scg_id'], 1, true, $addgiftpoint);
                    $target = 1;
                    $iscar_push = '{"target" :"'.$target.'","id_1":"","id_2":""}';
                    $memService->push_notification($inputData['sat'], array($scgData[0]['sharer_id']), null, null, $iscar_push, $target);
                    $logistics->Insert_MsLog_1111( $scgData[0]['sharer_id'],  $scgData[0]['scm_title'] ,$addgiftpoint);
                }
            }*/
            
             //購買 商品 贈送禮點
            $querydata = \App\Models\ICR_ShopCouponData_g::getMdidByScgid($querydata[0]['scg_id']);
            $scgData = \App\Models\ICR_ShopCouponData_g::GetData($querydata[0]['scg_id']);
            $logistics = new \App\Http\Controllers\APIControllers\Logistics;
            $appService = new \App\Services\AppService;
            $resultData['gpmr_point'] = null;
            $messageCode = $appService->GetGiftPointDayLimit($querydata[0]['md_id'], 10);
            $systemData = \App\Models\ICR_SystemParameter::getGPExchangeCashRate;
            $giftPointAmount = $appService->getGiftPointsAmount( 10 );
            $addgp = floor(($querydata[0]['scg_subtract_totalamount'])*($giftPointAmount/ 100)* $systemData[0]['sp_paramatervalue']);
            $gpmr_point = $appService->modifyGiftPoint($querydata[0]['md_id'], 10, $inputData['scg_id'], 1, true, $addgp );
            $logistics->Insert_MsLog_1114( $querydata[0]['md_id'],  $scgData[0]['scm_title'] ,$addgp);
            $resultData['gpmr_point'] = $gpmr_point;   
            if ($messageCode != '000000000') {
                throw new \Exception($messageCode);
            }  else {
                $messageCode = null;
            }
            //判斷 商品 有無贈送特點
            if ($querydata[0]['scm_bonus_giveafteruse'] == 1){
                $bankService = \App\Services\BankService;
                $bankService->modifyMemPmPoint($querydata[0]['md_id'], $querydata[0]['sd_id'], 2, $querydata[0]['scm_bonus_giveamount'],  $inputData['scg_id'], 1, null, true, $messageCode);
            }
             //贈與分享者禮點
            if (!is_null($scgData[0]['sharer_id']) || stren($scgData[0]['sharer_id']) != 0) {
                $giftpoint =  $appService->getGiftPointsAmount( 13);
                $addgiftpoint =  $scgData[0]['scg_subtract_totalamount'] * ($giftpoint / 100)* $systemData[0]['sp_paramatervalue'];
                if ($addgiftpoint >= 1) {
                    $appService->modifyGiftPoint($scgData[0]['sharer_id'], 13, $scgData[0]['scg_id'], 1, true, floor($addgiftpoint));
                    $target = 1;
                    $iscar_push = '{"target" :"'.$target.'","id_1":"","id_2":""}';
                    $memService->push_notification($inputData['sat'], array($scgData[0]['sharer_id']), null, null, $iscar_push, $target);
                    $logistics->Insert_MsLog_1111( $scgData[0]['sharer_id'],  $scgData[0]['scm_title'] ,$addgiftpoint);
                }
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scl_tracenum', 0, true, false)) {
            return false;
        }
        return true;
    }


    public function updateLogisticsData($scl_tracenum, $queryData, $LogisticsRepo){
      try {
           $updatedata = [
                                'scl_id' => $queryData[0]['SCLID'],
                                'scl_deliverstatus' => 3,
                                'scl_senddeliverytime' =>date('Y-m-d H:i:s'),
                                'scl_tracenum' =>$scl_tracenum
           ];
            if (! $LogisticsRepo->UpdateData($updatedata) ) {
              throw new \Exception();
            }
            $updatedata = [
                                      'scg_usestatus' => '8',
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