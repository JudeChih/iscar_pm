<?php

namespace App\Http\Controllers\APIControllers\CarNews;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** postcarreservationask	新增車輛約看詢問記錄 * */
class RecorverCarresvation {
   function recorvercarresvation() {
        $functionName = 'recorvercarresvation';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //輸入值
            if(!RecorverCarresvation::CheckInput($inputData)) {
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
            $crn_last_updatedate =  $inputData['crn_last_updatedate'];
            
           //記錄用戶端所屬功能類型，0:一般用戶端 1:合作社商家用戶 
            if ($inputData['md_clienttype'] == 0 ) {
               if ($inputData['reservation_usertype'] == 0) {
                  $crn_owner_id = null;
                  $crn_buyer_md_id = $md_id;
                  $Published = 1;
               } else if ($inputData['reservation_usertype'] == 1){
                  $crn_owner_id = $md_id;
                  $crn_buyer_md_id = null;
                  $Published = 1;
               }  
            } else if ($inputData['md_clienttype'] ==  1 ) {
                //檢查「店家」、「管理員」權限
               if (!\App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                   throw new \Exception($messageCode);
               } else {
                   $crn_owner_id = $inputData['sd_id'];
                   $crn_buyer_md_id = null;
                   $Published = 1;
               }
            }
            $querydata = \App\Models\ICR_Carreservation::GatData_ByOwnerid_OrBuyerid($Published, $crn_last_updatedate, $crn_buyer_md_id, $crn_owner_id);
            if (is_null($querydata) || count($querydata) == 0 ) {
                //無需更新，作業完成
                $messageCode = '000000001';
                \App\Models\ErrorLog::InsertData($e);
            }
            //取得cbi_id，進行查詢。
            $CbiIdArray = array();
            foreach ($querydata as $rowdata) {
               array_push($CbiIdArray,$rowdata['cbi_id']);
            }
            $querydataCbi = \App\Models\ICR_CarBasicInfo::GetData_ByCbiIdArray($CbiIdArray);
            //建立回傳資料。
            if (!RecorverCarresvation::CreateResultData($querydata, $querydataCbi, $resultData)) {
                throw new \Exception($messageCode);
            }
            //資料已異動，請進行更新
            $messageCode ='000000009';
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'crn_last_updatedate', 20, true, true)) {
            return false;
        }
       
        return true;
    }  
    /**
     * 建立回傳值。
     * @param type $queryDataCrn
     * @param type $queryDataCbi
     * @param type $resultData
     * @return boolean
     */
    private function CreateResultData($queryDataCrn, $queryDataCbi, &$resultData) {
        try {
            $count = 0;
            $crn_last_updatedate = null ;
            foreach ($queryDataCrn as $rowdata) {
              if (is_null($crn_last_updatedate) || $crn_last_updatedate < $rowdata['last_update_date']) {
                 $crn_last_updatedate = $rowdata['last_update_date'];
              } 
              $resultData['crn_list'][$count] = [
                                                   'crn_id'               => $rowdata['CRN_ID']
                                                  ,'cbi_id'               => $rowdata['cbi_id']
                                                  ,'crn_buyer_md_id'      => $rowdata['crn_buyer_md_id']
                                                  ,'crn_buyer_realname'   => $rowdata['crn_buyer_realname']
                                                  ,'crn_ownertype'        => $rowdata['crn_ownertype']
                                                  ,'crn_owner_id'         => $rowdata['crn_owner_id']
                                                  ,'crn_reservationtime'  => $rowdata['crn_reservationtime']
                                                  ,'crn_seller_reply_tag' => $rowdata['crn_seller_reply_tag']
                                                  ,'crn_cancel_tag'       => $rowdata['crn_cancel_tag']
                                                  ,'crn_cancel_date'      => $rowdata['crn_cancel_date']
                                                  ,'crn_reservationexec_tag' => $rowdata['crn_reservationexec_tag']
                                                ];
              $count  = $count + 1;   
            }
            $count = 0;
            $resultData['last_update_date'] = $crn_last_updatedate;
            foreach ($queryDataCbi as $rowdata) {
               $resultData['cbi_list'][$count] = [
                                                    "cbi_id"                 => $rowdata['cbi_id'],
                                                    "cbl_fullname"           => $rowdata['cbl_fullname'],
                                                    "cbm_fullname"           => $rowdata['cbm_fullname'],
                                                    "cms_fullname"           => $rowdata['cms_fullname'],
                                                    "cps_picpath"            => $rowdata['cps_picpath'],
                                                    "cbi_advertisementtitle" => $rowdata['cbi_advertisementtitle'],
                                                    "sd_shopname"            => 
                                                        ($rowdata['cbi_postownertype'] = 0) ? $rowdata['md_cname'] : ($rowdata['cbi_postownertype'] = 1) ? $rowdata['sd_shopname'] : null,
                                                    "sd_shopaddress"         => $rowdata['sd_shopaddress']
                                                  ];  
               $count  = $count + 1;            
            }
            return true;
        } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
}