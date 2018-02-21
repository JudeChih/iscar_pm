<?php

namespace App\Http\Controllers\APIControllers\Logistics;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
define('ECPAY_ReturnURL', config('global.ECPAY_ReturnURL'));
define('ECPAY_OrderResultURL', config('global.ECPAY_OrderResultURL'));
/** CreatePaymentFlow	scl，scg更新付款狀態 改以付款 * */
class CreatePaymentFlow {
   function createpaymentflow() {
        $functionName = 'createpaymentflow';
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
            if(!CreatePaymentFlow::CheckInput($inputData)){
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
           /* if (!CreatePaymentFlow::checkShopData($inputData['sd_id'], $shopdata)) {
                throw new \Exception($messageCode);
            }*/
            //取得訂單內容
            $scgData = \App\Models\ICR_ShopCouponData_g::GetLogisticsDataByScgId($inputData['scg_id']);

           //20171121 紅陽設定值改icr_systemparameter
           //$paymentflowArray = Commontools::ConvertStringToArray($shopdata[0]['sd_paymentflowdata']);

           // 建立 app 接收回傳值，決定動作跟導向地方。
           $togo =  $inputData['modacc'];
           $togo = str_replace ("iscar","",$togo);
           if ($togo == "news") {
              $togo = "app";
           } else if ($togo == "shop") {
             $togo = "pm";
           }
           $systemNote = 'redict_data@pm'.','.'susess_page@'.$togo.','.'sd_id@'.$inputData['sd_id'];
           //
           $resultData['paymentflow'] = 1;
           $resultData['paymentdata'] = CreatePaymentFlow::createSunTechPayment($inputData['scg_id'], $scgData, $inputData['is_mobile'], $systemNote);
               if (is_null($resultData['paymentdata'])) {
                 throw new \Exception($messageCode);
           }


           //20171121 紅陽設定值改icr_systemparameter
          /* if ($shopdata[0]['sd_paymentflow'] == 1) {
               $resultData['paymentflow'] = 1;
               $resultData['paymentdata'] = CreatePaymentFlow::createSunTechPayment($inputData['scg_id'], $scgData, $paymentflowArray, $systemNote);
               if (is_null($resultData['paymentdata'])) {
                 throw new \Exception($messageCode);
               }
           } else if ($shopdata[0]['sd_paymentflow'] == 2 ) {
              $resultData['paymentflow'] = 2;
              $resultData['paymentdata'] = CreatePaymentFlow::createECpayPayment($inputData['scg_id'], $scgData, $paymentflowArray, $systemNote);
               if (is_null($resultData['paymentdata'])) {
                 throw new \Exception($messageCode);
               }
           }*/


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


  function createSunTechPayment($scg_id, $scgData, $is_mobile, $systemNote) {
    try { 

            CreatePaymentFlow::getSuntechAccountPassword($is_mobile, $web, $pwd);
            $MN = $scgData[0]['scg_subtract_totalamount'];
            $Term = null;
            //$web = $paymentflowArray['web'];
            //$pwd = $paymentflowArray['TransPwd'];
            $ChkValue = strtoupper(sha1( $web .$pwd. $MN. $Term));
            $Td = $scg_id;
            $post = array(
                          'web'          => $web,
                          'TransPwd' => $pwd,
                          'MN'           => $MN,
                          'OrderInfo'    =>  $scgData[0]['scm_title'],
                          'Td'           => $Td,
                          'sna'          =>$scgData[0]['scg_buyername'],
                          'sdt'           =>$scgData[0]['scl_receivermobile'],
                          'email'        => $scgData[0]['scl_email'],
                          'note1'        => $scgData[0]['scl_postcode'].$scgData[0]['scl_city'].$scgData[0]['scl_district'].$scgData[0]['scl_receiveaddress'],
                          'note2'        => $systemNote,
                          'Card_Type'    => 0,
                          'Country_Type' => null,
                          'Term'         => $Term,
                          'ChkValue'     => $ChkValue
                    );
      return $post;
    } catch(\Exception $ex) {
      \App\Models\ErrorLog::InsertData($ex);
      return null;
    }
  }

  function createECpayPayment($scg_id, $scgData, $paymentflowArray, $systemNote) {
    try { 

      $post = array(
            'MerchantID' =>$paymentflowArray['MerchantID'],
            "ReturnURL"         => ECPAY_ReturnURL,
            'OrderResultURL'  =>ECPAY_OrderResultURL,
            //"ClientBackURL"     => '',
            //"OrderResultURL"    => '',
            "MerchantTradeNo"   =>$scg_id,
            "MerchantTradeDate" => date('Y/m/d H:i:s'),
            "PaymentType"       => 'aio',
            "TotalAmount"       => $scgData[0]['scm_price']* $scgData[0]['scg_buyamount'],
            "TradeDesc"         => $scgData[0]['scm_title'],
            "ChoosePayment"     => 'Credit',
            "Remark"            => '',
            //"ChooseSubPayment"  => ECPay_PaymentMethodItem::None,
            //"NeedExtraPaidInfo" => ECPay_ExtraPaymentInfo::No,
            "DeviceSource"      => '',
            "IgnorePayment"     => '',
            "PlatformID"        => '',
            //"InvoiceMark"       => ECPay_InvoiceState::No,
            //"Items"             => array('Name' => "歐付寶黑芝麻豆漿", 'Price' => (int)"2000",'Currency' => "元", 'Quantity' => (int) "1", 'URL' => "dedwed"),
             'ItemName' =>$scgData[0]['scm_title'],
            "StoreID"           => '',
            "CustomField1"      => $scgData[0]['scg_buyername'].'/'.$scgData[0]['scl_postcode'].$scgData[0]['scl_city'].$scgData[0]['scl_district'].$scgData[0]['scl_receiveaddress'],
            "CustomField2"      => $scgData[0]['scl_receivermobile'],
            "CustomField3"      => $scgData[0]['sd_shopname'],
            "CustomField4"      => $systemNote,
            'HoldTradeAMT'      => 0,
            'EncryptType' => 1,
        );
      ksort($post);
      $CheckMacValue = 'HashKey='.$paymentflowArray['HashKey'] . '&' ;
      foreach($post as $key => $value) {
          $CheckMacValue = $CheckMacValue . $key .'='.$value.'&';
      }
      $CheckMacValue = $CheckMacValue . 'HashIV='.$paymentflowArray['HashIV'];
      $CheckMacValue = strtolower(urlencode($CheckMacValue));
      $CheckMacValue = str_replace('%2d', '-', $CheckMacValue);
      $CheckMacValue = str_replace('%5f', '_', $CheckMacValue);
      $CheckMacValue = str_replace('%2e', '.', $CheckMacValue);
      $CheckMacValue = str_replace('%21', '!', $CheckMacValue);
      $CheckMacValue = str_replace('%2a', '*', $CheckMacValue);
      $CheckMacValue = str_replace('%28', '(', $CheckMacValue);
      $CheckMacValue = str_replace('%29', ')', $CheckMacValue);
      $CheckMacValue =  strtoupper(hash('sha256', $CheckMacValue));
      $post ['CheckMacValue'] = $CheckMacValue;
      return $post;
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