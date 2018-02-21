<?php

namespace App\Http\Controllers\APIControllers\CarNews;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** querycarreservationfullinfo	 賣方查詢買方車輛約看詢問記錄 * */
class QueryCarreservationFullinfo {
   function querycarreservationfullinfo() {
        $functionName = 'querycarreservationfullinfo';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //輸入值
            if(!QueryCarreservationFullinfo::CheckInput($inputData)){
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
          
             //記錄用戶端所屬功能類型，0:一般用戶端 1:合作社商家用戶 
            if ($inputData['md_clienttype'] == 0) {
               if ($inputData['reservation_usertype'] == 0 ) {
                   $buyer_id = $md_id;
                   $Published = 1;
               } else if ($inputData['reservation_usertype'] == 1 ) {
                   $owner_id =  $md_id;
                   $Published = 1;
               }
            } else if ($inputData['md_clienttype'] == 1) {
               //檢查用戶端所屬功能類型，是否正確。
               if ($inputData['reservation_usertype'] == 0 ) {
                   $messageCode = '011105002';
                   throw new \Exception($messageCode);
               }
               //檢查「店家」、「管理員」權限
               if (!\App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                   throw new \Exception($messageCode);
               } else {
                   $owner_id = $inputData['sd_id'];
                   $Published = 1;
               }
            } else {
               //無效的操作動作，請重新輸入
               $messageCode = '011101002';
               throw new \Exception($messageCode);
            }
            $querydata = \App\Models\ICR_Carreservation::GetData_ByCrnid_Cbiid($inputData['cbi_id'], $inputData['crn_id']);
            if (is_null($querydata) || count($querydata) == 0 ) {
               //查無約看記錄，請確認後重發
               $messageCode = '011105001';
               throw new \Exception($messageCode);
            }
            
            //檢查傳入值，與DB資料，是否一致。
            if ($inputData['reservation_usertype'] == 0 ) {
                if ($querydata[0]['crn_buyer_md_id'] != $buyer_id ) {
                   //查無約看記錄，請確認後重發
                   $messageCode = '011105001';
                   throw new \Exception($messageCode);
                }
            } else if ($inputData['reservation_usertype'] == 1 ) {
                if ($querydata[0]['crn_owner_id'] != $owner_id ) {
                   //查無約看記錄，請確認後重發
                   $messageCode = '011105001';
                   throw new \Exception($messageCode);
                }
            }
            //依照DB[crn_ownertype]，建立變數，建立回傳值。
            if ($querydata[0]['crn_ownertype'] == 0) {
               $shopname = $querydata[0]['shopname0'];
               $shopaddress = $querydata[0]['shopaddress0'];
               $shoptel = $querydata[0]['shoptel0'];
            } else if ($querydata[0]['crn_ownertype'] == 1) {
               $shopname = $querydata[0]['shopname1'];
               $shopaddress = $querydata[0]['shopaddress1'];
               $shoptel = $querydata[0]['shoptel1'];
            }
            //建立回傳值。
            if (!QueryCarreservationFullinfo::CreateResultData($querydata, $shopname, $shopaddress, $shoptel, $resultData)){
                throw new \Exception($messageCode);
            }
            $messageCode ='00000000';
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'reservation_usertype', 1, false, false)) {
            return false;
        } 
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'md_clienttype', 1, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'crn_id', 32, false, true)) {
            return false;
        }      
        return true;
    } 
   /**
     * 建立回傳值
     * @param type $querydata
     * @param type $shopname
     * @param type $shopaddress
     * @param type $shoptel
     * @param type $resultData
     * @return boolean
     */ 
    private function CreateResultData($querydata, $shopname, $shopaddress, $shoptel, &$resultData) {
       try {
           $resultData = [
                            "crn_buyer_realname"      => $querydata[0]['crn_buyer_realname'],
                            "crn_buyer_ask_message"   => $querydata[0]['crn_buyer_ask_message'],
                            "crn_carinstore_ask"      => $querydata[0]['crn_carinstore_ask'],
                            "crn_seller_reply_tag"    => $querydata[0]['crn_seller_reply_tag'],
                            "crn_seller_ans_message"  => $querydata[0]['crn_seller_ans_message'],
                            "crn_reservationtime"     => $querydata[0]['crn_reservationtime'],
                            "crn_carinstore_comfirm"  => $querydata[0]['crn_carinstore_comfirm'],
                            "crn_soldout_tag"         => $querydata[0]['crn_soldout_tag'],
                            "crn_cancel_tag"          => $querydata[0]['crn_cancel_tag'],
                            "crn_cancel_date"         => $querydata[0]['crn_cancel_date'],
                            "crn_cancel_usertype"     => $querydata[0]['crn_cancel_usertype'],
                            "crn_reservationexec_tag" => $querydata[0]['crn_reservationexec_tag'],
                            "cbi_advertisementtitle"  => $querydata[0]['cbi_advertisementtitle'],
                            "cps_picpath"             => $querydata[0]['cps_picpath'],
                            "cbl_fullname"            => $querydata[0]['cbl_fullname'],
                            "cbm_fullname"            => $querydata[0]['cbm_fullname'],
                            "cms_fullname"            => $querydata[0]['cms_fullname'],
                            "shopname"                => $shopname,
                            "shopaddress"             => $shopaddress,
                            "shoptel"                 => $shoptel,
                            "crn_reply_md_cname"      => $querydata[0]['crn_reply_md_cname']
                         ];
           return true;
       } catch(\Exception $e) {
         return false;
         \App\Models\ErrorLog::InsertData($e);
       }
    } 
}
