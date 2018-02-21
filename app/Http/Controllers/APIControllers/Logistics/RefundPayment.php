<?php

namespace App\Http\Controllers\APIControllers\Logistics;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
define('SunTech_RefundPayment', config('global.SunTech_RefundPayment'));
/** refundpayment	金流退費 * */
class RefundPayment {
   function refundpayment() {
        $functionName = 'refundpayment';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        $refund_amount = null;
        try{
            if($inputData == null){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //輸入值
            if(!RefundPayment::CheckInput($inputData)){
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
            /*if (!\App\library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }*/
            $shopdata = \App\Models\ICR_ShopData::GetData($inputData['sd_id']);
            //$resultData['shopdata'] = $shopdata;
            if (!RefundPayment::checkShopData($inputData['sd_id'], $shopdata)) {
                throw new \Exception($messageCode);
            }
            //取得訂單內容
            $scgData = \App\Models\ICR_ShopCouponData_g::GetData_CouponDataM_LogisticsDetial($inputData['scg_id']);
            if (!RefundPayment::checkScgData($scgData, $messageCode, $nowDayPay)) {
              throw new \Exception($messageCode);
            }

           //$paymentflowArray = Commontools::ConvertStringToArray($shopdata[0]['sd_paymentflowdata']);
           //$resultData['scgData'] = $scgData;
           //$resultData['shopdata'] = $shopdata;
           //if ($shopdata[0]['sd_paymentflow'] == 1) {
               //$resultData['paymentflow'] = 1;
               $response = RefundPayment::RefundSunTechPayment($inputData['scg_id'], $scgData, $inputData['refund_note'], $nowDayPay,$inputData['is_mobile'], $refund_amount);
               $resultData['payment_system_return'] = $response;
               if (is_null($response)) {
                 $messageCode = '011502004'; 
                 throw new \Exception($messageCode);
               } else if ( $response == 'E0' ) {
                  RefundPayment::updateScgData($inputData['scg_id']);
                  RefundPayment::pushNotification_InsertMessageLog($inputData['sat'], $inputData['scg_id'], $refund_amount );
                  //退發票
                  $invoiceClass = new \App\Http\Controllers\APIControllers\Invoice\Invalid_Invoice;
                  $invoiceClass-> invalidinvoice($inputData);
               } else {
                  $messageCode = '011502004';
                   \App\Models\ErrorLog::InsertLog($response);
                  throw new \Exception($messageCode);
               }
         /*  } else if ($shopdata[0]['sd_paymentflow'] == 2 ) {
              $resultData['paymentflow'] = 2;
              $resultData['paymentdata'] = RefundPayment::RefundECpayPayment($inputData['scg_id'], $scgData, $paymentflowArray, $systemNote);
               if (is_null($resultData['paymentdata'])) {
                 throw new \Exception($messageCode);
               }
           }*/
           
            $mailController = new  \App\Http\Controllers\APIControllers\MailController;
            $mailController->refund_payment_sendMail($inputData['scg_id']);
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'refund_note', 0, false, false)) {
            return false;
        } 
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'is_mobile', 0, false, false)) {
            return false;
        }
        return true;
    }

    function checkShopData($sd_id, $shopData) {
      try {
        if ( count($shopData)  == 0 ) {
          return false;
        }  else if ($shopData[0]['sd_paymentflow'] == 0  || is_null($shopData[0]['sd_paymentflowdata'])) {
          return false;
        }
        return true;
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return false;
      }
    }

    function checkScgData($scgData, &$messageCode, &$nowDayPay = false){
      try {
        $nowdate = date('Y-m-d H:i:s');
        $daysAgo =  (strtotime($nowdate) - strtotime($scgData[0]['scg_create_date'] )) / (60*60*24) ;
          //未付款
          if ($scgData[0]['scg_paymentstatus'] == 0 ) {
            $messageCode = '011502001';
             return false;
          } /*已用畢*/else if ($scgData[0]['scg_usestatus'] == 2) {
             $messageCode = '011502002';
            return false;
          }  /*退款最大時限60日*/else if ( $daysAgo > 60 ) {
            $messageCode = '011502003';
             return false;
          } /* 判斷是否今天付款*/else if ( date("Y-m-d",strtotime($nowdate)) ==  date("Y-m-d",strtotime($scgData[0]['scg_paid_time'])) ) {
            $nowDayPay = true;
          }
          return true;
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return false;
      }
    }

    function updateScgData($scg_id){
      try {
        $updateData = [  'scg_id' => $scg_id ,
                                    'scg_usestatus' => '10'  ];
        return \App\Models\ICR_ShopCouponData_g::UpdateData($updateData);
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertDatat($e);
        return false;
      }
    }


  function RefundSunTechPayment($scg_id, $scgData,$refund_note, $nowDayPay, $is_mobile, &$MN) {
    try { 
            if (substr($scgData[0]['payment_no'],0,2) == 'S0') {
                $is_mobile = 0;
            } else if (substr($scgData[0]['payment_no'],0,2) == 'S7'){
                $is_mobile = 1;
            }
            RefundPayment::getSuntechAccountPassword($is_mobile, $web, $pwd);
            $MN = $scgData[0]['scg_subtract_totalamount'];
            //非今日付款 扣除15%手續費
            if ($nowDayPay == false) {
                //扣除15%手續費 退出85%  無條件進位
                $MN = ceil($MN *0.85);
            } 
            $buysafeno = $scgData[0]['payment_no'];
            $Td = $scg_id;
            //$web = $paymentflowArray['web'];
            //$pwd = $paymentflowArray['TransPwd'];
            $RefundMemo = $refund_note;
            $ChkValue = hash('sha256', ($web.$pwd.$buysafeno.$MN.$Td));
            $post = array(
                          'MN'           => $MN,
                          'web'          => $web,
                          'buysafeno'    =>  $buysafeno,
                          'Td'           => $Td,
                          'RefundMemo'   => $RefundMemo,
                          'ChkValue'     => $ChkValue
                    );
            $route = SunTech_RefundPayment;
            if (is_null($response=  RefundPayment::curlModule($post, $route))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
             }
      return $response;
    } catch(\Exception $ex) {
      \App\Models\ErrorLog::InsertData($ex);
      return null;
    }
  }

 /* function RefundECpayPayment($scg_id, $scgData, $paymentflowArray, $systemNote) {
    try {
      return $post;
    } catch(\Exception $ex) {
      \App\Models\ErrorLog::InsertData($ex);
      return null;
    }
  }*/

  function pushNotification_InsertMessageLog($sat, $scg_id, $amount ){
    try {
       $querydata = \App\Models\ICR_ShopCouponData_g::getMdidByScgid($scg_id);
       $Logistics = new \App\Http\Controllers\APIControllers\Logistics\Logistics;
       $memService = new \App\Services\MemberService;
       $Md_Id_Array = array($querydata[0]['md_id']);
       $Logistics->Insert_MsLog_1106( $querydata[0]['md_id'], $querydata[0]['sd_shopname'], $querydata[0]['scm_title'], $amount);
       foreach ($querydata as $row) {
           array_push($Md_Id_Array, $row['smb_md_id']);
           $Logistics->Insert_MsLog_1108( $row['smb_md_id'],  $scg_id,  $amount );
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


  /**
     * Curl模組化使用
     * @param type array $post 傳送資料
     * @param type string $route 傳送route
     * @return array or null
     */
    private static function curlModule ($post, $route){
        try {
                $options = array(
                     'http' => array(
                              'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                              'method'  => 'POST',
                              'content' => http_build_query($post)
                    )
               );
              $context  = stream_context_create($options);
              $result = file_get_contents($route, false, $context);
              if ($result === FALSE) {
                \App\Models\ErrorLog::InsertLog($result);
              }
             return $result;
        } catch(\Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    
    
    function getSuntechAccountPassword($is_mobile, &$web, &$pwd) {
    try { 
       if ($is_mobile == 0) {
                $suntechData = \App\Models\ICR_SystemParameter::getSuntechBuySafe();
            } else if ($is_mobile == 1) {
                $suntechData = \App\Models\ICR_SystemParameter::getSuntechSwipy();
            }
            foreach($suntechData as $value) {
               if ( ($value['sp_parameterkey'] == "suntech_buysafe_account") || ($value['sp_parameterkey'] == "suntech_swipy_account") ) {
                   $web = $value['sp_paramatervalue'];
               } else if ( ($value['sp_parameterkey'] == "suntech_buysafe_password") || ($value['sp_parameterkey'] == "suntech_swipy_password") ) {
                  $pwd = $value['sp_paramatervalue'];
               }
           }
    } catch(\Exception $ex) {
      \App\Models\ErrorLog::InsertData($ex);
      return null;
    }
  }

}