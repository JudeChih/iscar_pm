<?php

namespace App\Http\Controllers\APIControllers\CarNews;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** putcarreservationans	新增車輛約看回覆敲定記錄 * */
class PutCarreservationAns {
   function putcarreservationans() {
        $functionName = 'putcarreservationans';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //輸入值
            if(!PutCarreservationAns::CheckInput($inputData)){
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
               $crn_owner_id = $md_id ;
               $crn_id = $inputData['crn_id'];
               $cbi_id = $inputData['cbi_id'];;
               $crn_reply_id = $md_id;
               $crn_ownertype = 1;
            } else if ($inputData['md_clienttype'] == 1) {
               //檢查「店家」、「管理員」權限
               if (!\App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                   throw new \Exception($messageCode);
               } else {
                   $crn_owner_id = $inputData['sd_id'];
                   $crn_id = $inputData['crn_id'];
                   $cbi_id = $inputData['cbi_id'];
                   $crn_reply_id= $md_id;
                   $crn_ownertype = 0;
               }
            } else {
               //無效的操作動作，請重新輸入
               $messageCode = '011101002';
               throw new \Exception($messageCode);
            }
            $querydata = \App\Models\ICR_Carreservation::GetData_ByCrnkId($crn_id);
            if(is_null($querydata) || count($querydata) == 0 || count($querydata) > 1) {
               //查無約看記錄，請確認後重發
               $messageCode = '011105001';
               throw new \Exception($messageCode);
            }
            if($querydata[0]['crn_owner_id'] != $crn_owner_id || $querydata[0]['cbi_id'] != $cbi_id ) {
                //記錄編號查無資料，請確認後重發
               $messageCode = '011106002';
               throw new \Exception($messageCode);
            }
            if (false === (strpos($querydata[0]['crn_available_timearray'], $inputData['crn_reservationtime']))) {
                //敲定之約看時間，不符合買家提出之項目
               $messageCode = '011106001';
               throw new \Exception($messageCode); 
            }
            if(!PutCarreservationAns::UpdateCarReservation($crn_id ,$crn_owner_id ,$md_id , $inputData['crn_reservationtime'] ,$inputData['crn_seller_ans_message'] ,$inputData['crn_carinstore_comfirm'])) {
               throw new \Exception($messageCode);
            } 
            $target = 1;
            $iscar_push = '{"target" :"'. $target. '","id_1":"","id_2":""}';
            if( ! $memService->push_notification($inputData['sat'], array($querydata[0]['crn_buyer_md_id'] ), null, null, $target, $iscar_push) ) {
              throw new \Exception($messageCode);
            }
            if (!PutCarreservationAns::InsertMsLog_902($querydata[0]['crn_buyer_md_id'], $inputData['crn_id'], $inputData['cbi_id'], $querydata[0]['cps_picpath'])) {
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'md_clienttype', 1, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'crn_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'crn_seller_ans_message', 140, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'crn_reservationtime', 20, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'crn_carinstore_comfirm', 1, false, false)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * 新增車輛約看敲定紀錄
     * @param type $crn_id
     * @param type $cras_owner_id
     * @param type $crn_reply_md_id
     * @param type $crn_reservationtime
     * @param type $crn_seller_ans_ansmessage
     * @param type $crn_carinstore_comfirm
     * @return boolean
     */
    private function UpdateCarReservation($crn_id ,$crn_owner_id ,$crn_reply_md_id ,$crn_reservationtime ,$crn_seller_ans_ansmessage ,$crn_carinstore_comfirm) {
        try {
           $updatedata = [
                             'crn_id'                    => $crn_id,
                             'crn_owner_id'              => $crn_owner_id,
                             'crn_reply_md_id'           => $crn_reply_md_id,
                             'crn_reservationtime'       => $crn_reservationtime,
                             'crn_seller_ans_ansmessage' => $crn_seller_ans_ansmessage,
                             'crn_carinstore_comfirm'    => $crn_carinstore_comfirm,
                             'crn_seller_reply_tag'      => '1'
                         ];
             return \App\Models\ICR_Carreservation::UpdateData($updatedata);
       } catch (\Exception $e) {
             return false;
             \App\Models\ErrorLog::InsertData($e);
     } 
    }
    
    /**
     * 修改車輛詢問紀錄
     * @param type $crak_id
     * @return boolean
     */
    private function UpdateCarReservationAsk($crak_id) {
     try {
            $UpdateData = [
                            'crak_reply_tag' => '1' ,
                            'crak_id' => $crak_id
                          ];
             return \App\Models\ICR_Carreservation_Ask::UpdateData($UpdateData);
         
      } catch(\Exception $e) {
            return false;
            \App\Models\ErrorLog::InsertData($e);
      } 
    } 
  
    private function InsertMsLog_902($md_id, $crn_id, $cbi_id, $cps_picpath) {
     try {
            $savadata['uml_type'] = 902;
            $savadata['md_id'] =  $md_id;
            $savadata['uml_message'] = '親愛的用戶你好，已收到賣家的約看敲定通知，請點擊確認約看敲定應時間。';
            $savadata['uml_object'] = "{\"crn_id\":\"$crn_id\",\"cbi_id\":\"$cbi_id\"}";
            $savadata['uml_pic'] = $cps_picpath;
            $savadata['uml_status'] = 0;
            return  Commontools::PostMessageLog($savadata);
     } catch(\Exception $e) {
           return false;
           \App\Models\ErrorLog::InsertData($e);
     }
    } 
    
    
    
}