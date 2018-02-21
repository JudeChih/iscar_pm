<?php

namespace App\Http\Controllers\APIControllers\Logistics;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
define('ECPAY_ReturnURL', config('global.ECPAY_ReturnURL'));
define('ECPAY_OrderResultURL', config('global.ECPAY_OrderResultURL'));
/** paymentcancel	取消付款 **/
class PaymentCancel {
   function paymentcancel() {
        $functionName = 'paymentcancel';
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
            if(!PaymentCancel::CheckInput($inputData)){
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
            $shopdata = \App\Models\ICR_ShopData::GetData($inputData['sd_id']);
            //取得訂單內容
            $scgData = \App\Models\ICR_ShopCouponData_g::GetData_CouponDataM_LogisticsDetial($inputData['scg_id']);
            if (!PaymentCancel::checkScgData($scgData, $messageCode)) {
              throw new \Exception($messageCode);
            }
            if (!PaymentCancel::updateScgData($inputData['scg_id'])) {
              throw new \Exception($messageCode);
            } else if (!PaymentCancel::pushNotification_InsertMessageLog($inputData['sat'], $inputData['scg_id'] )) {
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scg_id', 0, false, false)) {
            return false;
        }
        return true;
    }

    function checkScgData($scgData, &$messageCode){
      try {
          /*已用畢*/
           if ($scgData[0]['scg_usestatus'] == 2) {
             $messageCode = '011502002';
            return false;
          }  
          return true;
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return false;
      }
    }

function pushNotification_InsertMessageLog($sat, $scg_id){
    try {
       $querydata = \App\Models\ICR_ShopCouponData_g::getMdidByScgid($scg_id);
       $Logistics = new \App\Http\Controllers\APIControllers\Logistics\Logistics;
       $memService = new \App\Services\MemberService;
       $Md_Id_Array = array($querydata[0]['md_id']);
       $Logistics->Insert_MsLog_1107( $querydata[0]['md_id'], $querydata[0]['sd_shopname'], $querydata[0]['scm_title']);
       foreach ($querydata as $row) {
           array_push($Md_Id_Array, $row['smb_md_id']);
           $Logistics->Insert_MsLog_1109( $row['smb_md_id'],  $scg_id );
       }
       $target = 1;
       $iscar_push = '{"target" :"'.$target.'","id_1":"","id_2":""}';
       $memService->push_notification($sat, $Md_Id_Array, null, null, $iscar_push, $target);
       return true;
    } catch(\Exception $ex) {
       \App\Models\ErrorLog::InsertData($ex);
       return false;
    }
  }

    function updateScgData($scg_id){
      try {
        $updateData = [  'scg_id' => $scg_id ,
                                    'scg_usestatus' => '11'  ];
        return \App\Models\ICR_ShopCouponData_g::UpdateData($updateData);
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertDatat($e);
        return false;
      }
    }

}