<?php
namespace App\Http\Controllers\APIControllers\ShopManage;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
use Redirect; 
/** verifyshopmailbind	驗證商家註冊信箱的驗證碼 */
class VerifyShopMailBind {
   function verifyshopmailbind() {
        $functionName = 'verifyshopmailbind';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);        
        $resultData = null;
        $messageCode = null;
        try{
            //輸入值
            if(!VerifyShopMailBind::CheckInput($inputData)){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //檢查JWT驗正。
            /*if( !Commontools::JWTokenVerification($inputData['api_token'], $messageCode)) {
               throw new \Exception($messageCode);
            }  */
             //檢查身份模組驗證
            $memService = new \App\Services\MemberService;
            if ( !$memService->checkModuleAccount($inputData['modacc'], $inputData['modvrf'], $messageCode)) {
              //模組身份驗證失敗
              $messageCode = '999999961';
              throw new \Exception($messageCode);
            }
            //檢查sendid cert
            if (!VerifyShopMailBind::CheckMailSentId_Cert($inputData['mail_sentid'], $inputData['mail_cert'], $sd_id, $md_id, $messageCode)) {
               throw new \Exception($messageCode);
            }
            
            if (!VerifyShopMailBind::UpdateIcrSdmdbind($sd_id, $md_id, $inputData['mail_sentid'])) {
               $messageCode = '999999988';
               throw new \Exception($messageCode);
            } 
           
            $messageCode ='000000000';
         } catch(\Exception $e) {
           if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($e);
            }
        }
        $resultArray = Commontools::ResultProcess($messageCode, $resultData);
         if ( $messageCode == '999999988' ) {
             $message = "errormessage:".$messageCode."，API:VerifyShopMailBind，jio_id:";
             Commontools::WriteExecuteLog_NotificationToManagers($functionname, $inputString, json_encode($resultArray), $messagecode, $message)
        } else {
             Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);   
        }
        return Redirect::away('https://tw.yahoo.com?ms='.$messageCode);
         
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'mail_sentid', 32, false, false)) {
            return false;
        } 
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'mail_cert', 0, false, false)) {
            return false;
        }   
        
        return true;
    }
    
    
    function CheckMailSentId_Cert($sentid, $cert, &$sd_id, &$md_id, &$messageCode) {
       try {
          $queryData = \App\Models\ICR_SdmdBind::GetData_BySentId($sentid);
          if ( is_null($queryData) || count($queryData) == 0 ) {
             $messageCode = '000000003';
             return false;
          } else if ( count($queryData) > 1 ) {
             $messageCode = '000000005';
             return false;
          } else if ($queryData[0]['smb_activestatus'] != 0 ) {
             $messageCode = '999999997';
             return false;
          } else if ( $cert !=  Hash('sha256',$queryData[0]['sd_id'].$queryData[0]['md_id'].$sentid)) {
             $messageCode = '999999979';
             return false;
          }
          $sd_id = $queryData[0]['sd_id'];
          $md_id = $queryData[0]['md_id'];
          return true;
       } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
         $messageCode = '999999999'; 
         return false;
       }
    }
    
    
    private function UpdateIcrShopData($sd_id) {
       try {
         $updatedata = [
                          'sd_id'           => $sd_id ,
                          'sd_activestatus' => 0,
                       ];
         return \App\Models\ICR_ShopData::UpdateData($updatedata);
       } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
         return false;
       }
    }
    
    
    private function UpdateIcrSdmdbind($sd_id, $md_id, $sentid) {
      try {
         $updateData = [
                          'smb_sd_id' =>       $sd_id,
                          'smb_md_id' =>       $md_id,
                          'smb_mail_sentid' => $sentid,
                       ];
         return \App\Models\ICR_SdmdBind::UpdateDataBySdId_MdId_SentID($updateData);
      } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
         return false;
      }
    }
   
  
 
}