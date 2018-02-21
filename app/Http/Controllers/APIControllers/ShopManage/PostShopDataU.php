<?php

namespace App\Http\Controllers\APIControllers\ShopManage;
use DB;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/**        開店申請        **/
class PostShopDataU {
    function postshopdatau() {
        $functionName = 'postshopdatau';
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
            if(!PostShopData::CheckInput($inputData)){
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
            $sat = $inputData['sat'];
            if (!$memService->checkServiceAccessToken( $sat , $md_id, $messageCode)) {
              //呼叫「MemberAPI」檢查SAT的狀態，驗證SAT有效性
               //$messageCode = '999999962';
               throw new \Exception($messageCode);
            }
            //取得，並查詢是否有儲值紀錄。
            $carNews = new \App\Http\Controllers\APIControllers\CarNews\CarNews;
            if (!$carNews->QueryCosEnd($md_id, $inputData['dcil_id'], $cosdata, $messageCode)) {
                throw new \Exception($messageCode);
            } 

            if (!PostShopData::CheckCreateShopType($inputData['operation_type'], $inputData['sd_type'], $md_clienttype, $stx_tag_type, $stx_tag_id, $smb_shoptype, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //依傳入值sd_shopaddress，取得經緯度。
            if (!\App\Library\Commontools::Query_GeoCodeByGoogle($inputData['sd_shopaddress'], $longitude, $latitude)) {
               throw new \Exception($messageCode);
            }
            //DB::beginTransaction();
            if(  !PostShopData::InsertShopData($inputData, $sd_id, $latitude, $longitude)
               ||!PostShopData::InsertShopDataXref($stx_tag_type, $stx_tag_id, $sd_id)
               ||!PostShopData::UpdateMemberData($md_clienttype, $md_id)
               ||!$memService->modify_member_clienttype( '', $sat, $md_clienttype, $messageCode)
               ||!PostShopData::InsertDepositBuyItmEreData($dbir_id, $dbir_expiredate, $md_id, $sd_id, $inputData , $cosdata)
               ||!PostShopData::InsertSmbBind($sd_id, $md_id, $dbir_expiredate, $dbir_id, $smb_shoptype, $inputData['smb_bind_mail'], $smb_mail_sentid)
               ||!PostShopData::Send_Email($inputData['smb_bind_mail'], $smb_mail_sentid, Hash('sha256',$sd_id.$md_id.$smb_mail_sentid))) {
               $messageCode = '999999998';
               throw new \Exception($messageCode);
            }
            
           //取得現在庫存資料
            $bankService = new \App\Services\BankService;
            if (!$bankService->memPointQuery( $inputData['sat'], 0, $pointData, $messageCode)) {
              throw new \Exception($messageCode);
            }
            if ( !$bankService->memPointModify( $sat , $inputData['modacc'], $inputData['modvrf'], 8, $dbir_id, $cosdata['dcil_depositamount'], $stockPoint, $messageCode)) {
              throw new \Exception($messageCode);
            }
            //扣點完成,刊登即時生效
            $messageCode = '011103000';
            //DB::commit();
        } catch(\Exception $e){
            //DB::rollBack();
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'operation_type', 1, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_type', 1, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_shopname', 50, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_shoptel', 20, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_zipcode', 3, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_shopaddress', 100, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'dcil_id', 32, false, false)) {
            return false;
        }
        return true;
    }
    
    function CheckCreateShopType($operation_type, $sd_type, &$md_clienttype, &$stx_tag_type, &$stx_tag_id, &$smb_shoptype, &$messageCode) {
      try {
           $stx_tag_type = 0;
           $stx_tag_id = 'stg'.$sd_type ;
           $md_clienttype = 1;
           //operation_type 0:申請一般商家 1:申請二手車商 2:驗正傳入地址
           if( $operation_type == 0 ) {
              $smb_shoptype = 0;
           } else if ( $operation_type == 1 ) {
              $smb_shoptype = 1;
           } else {
              //無效的操作動作，請重新輸入
              $messageCode = '999999989';
              return false;
           }
           return true;
      } catch (\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
      }
    }
    
    /**
     * 新增店家資料。
     * @param type $arraydata
     * @param type $sd_lat
     * @param type $sd_lng
     * @return boolean
     */
    private function InsertShopData($arraydata, &$sd_id,$sd_lat, $sd_lng) {
       try {
           $sd_id = Commontools::NewGUIDWithoutDash();
           $insertdata = [
                          'sd_id'          => $sd_id,
                          'sd_type'        => $arraydata['sd_type'], 
                          'sd_shopname'    => $arraydata['sd_shopname'],
                          'sd_shoptel'     => $arraydata['sd_shoptel'],
                          'sd_zipcode'     => $arraydata['sd_zipcode'],
                          'sd_shopaddress' => $arraydata['sd_shopaddress'],
                          'sd_lat'         => $sd_lat,
                          'sd_lng'         => $sd_lng,
                          'sd_activestatus'=> 0
                         ];
           return \App\Models\ICR_ShopData::InsertData($insertdata);
       } catch (\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return false;
       }
    }
    
    /**
     * 新增商店型態
     * @param type $stx_tag_type
     * @param type $stx_tag_id
     * @param type $sd_id
     * @return boolean
     */
    private function InsertShopDataXref($stx_tag_type, $stx_tag_id, $sd_id) {
       try {
           $insertdata = [
                          'stx_tag_type' => $stx_tag_type,
                          'stx_tag_id'   => $stx_tag_id, 
                          'stx_sd_id'    => $sd_id
                         ];
           return \App\Models\ICR_ShopTag_xref::InsertData($insertdata);
       } catch (\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return false;
       }
    }
    
    /**
     * 新增綁定商店，會員
     * @param type $sd_id
     * @param type $md_id
     * @param type $dbir_expiredate
     * @param type $dbir_id
     * @param type $smb_shoptype  
     * @return boolean
     */
    private function InsertSmbBind($sd_id, $md_id, $dbir_expiredate, $dbir_id, $smb_shoptype, $smb_bind_mail, &$smb_mail_sentid) {
       try {
            $smb_mail_sentid = Commontools::NewGUIDWithoutDash();
            $insertdata = [
                           'smb_sd_id'        => $sd_id,
                           'smb_md_id'        => $md_id, 
                           'smb_validity'     => $dbir_expiredate,
                           'smb_activestatus' => 0,
                           'smb_bindway'      => 1,
                           'smb_releation_id' => $dbir_id,
                           'smb_shoptype'     => $smb_shoptype,
                           'smb_bindlevel'    => 1,
                           'smb_bind_mail'    => $smb_bind_mail,
                           'smb_mail_sentid'  => $smb_mail_sentid
                          ];
           return \App\Models\ICR_SdmdBind::InsertData($insertdata, $smb_serno);
       } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
         return false;
       }
    }
    
    
    /**
     * 修改用端運行類別，md_clienttype，0:一般用戶端 1:商家用戶 2: 2手車商用戶
     * @param type $md_cliendtype
     * @param type $md_id
     * @return boolean 
     */
    private function UpdateMemberData($md_clienttype, $md_id) {
       try {
           $updatedata = [
                          'md_clienttype' => $md_clienttype,
                          'md_id'         => $md_id
                         ];
           return \App\Models\IsCarMemberData::UpdateData($updatedata);
       } catch (\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
       }
    }
    
    /**
     * 新增車子基本資料
     * @param type $md_id
     * @param type $inpdata
     * @param type $cosdata
     * @return boolean 
     */
    private function InsertDepositBuyItmEreData(&$dbir_id, &$dbir_expiredate, $md_id, $sd_id, $inputdata , $cosdata) { 
       try {
           $dbir_id = \App\Library\Commontools::NewGUIDWithoutDash();
           $datenow = new \Datetime();
           $availabledays = $cosdata['dcil_availabledays'];
           $dbir_expiredate =  $datenow-> modify("+$availabledays day") -> format('Y-m-d H:i:s');
           $insertdata = [
                           'dbir_id'           => $dbir_id,
                           'md_id'             => $md_id,
                           'dbir_object_id'    => $sd_id,
                           'dcil_id'           => $inputdata['dcil_id'],
                           'dbir_activatedate' => $datenow-> format('Y-m-d H:i:s'),
                           'dbir_expiredate'   => $dbir_expiredate,
                           'dbir_object_type'  => '1'
                         ];
           return \App\Models\ICR_DepositBuyItmErec::InsertData($insertdata);
       } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
       }
    }
    
     /**
     * 寄送註冊郵件
     * @param type $user_email
     * @param type $sentid
     * @param type $cert
     * @return boolean 
     */
     function Send_Email($user_email, $sentid, $cert) {
        try {
             
             $get_value = Commontools::$VerifyShopMailBind."?mail_cert=$cert&mail_sentid=$sentid";
             Mail::send( ['html' => 'register_email'], ['text' => $get_value], function($message) {
                $message->to("$user_email")->subject('isCar 特約商註冊');
             });
             return true;
        } catch(\Exception $e) {
             \App\Models\ErrorLog::InsertData($e);
             return false;
        }
    }
    
}
