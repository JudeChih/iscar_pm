<?php

namespace App\Http\Controllers\APIControllers\ShopManage;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** query_shopbonus_item 紅利項目查詢 **/
class PostShopBonusMamager {
   function postshopbonusmamager() {
        $functionName = 'postshopbonusmamager';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //輸入值
            if(!PostShopBonusMamager::CheckInput($inputData)){
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
            if (!Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $inputData['md_id'], $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            
            if ($inputData['sbgi_fittype'] != 0 ) {
               $messageCode = '011101001';
               throw new \Exception($messageCode);
            } else if ($inputData['sbgi_effective'] != 1 && $inputData['sbgi_effective'] != 2 ) {
               $messageCode = '011101002';
               throw new \Exception($messageCode);
            }
            
            if (!PostShopBonusMamager::ManageBounsType($inputData, $messageCode)) {
               if (is_null($messageCode)) {
                  $messageCode = '999999988';
               }
               throw new \Exception($messageCode);
            }
            $messageCode ='000000000';
         } catch(\Exception $e){
           if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($e);
            }
        }
        $resultArray = Commontools::ResultProcess($messageCode, $resultData);
         if ($messageCode =='999999988') {
             Commontools::WriteExecuteLogGetId($functionName, $inputString, json_encode($resultArray), $messageCode ,$jio_id);
             $message = "errormessage:$messageCode"."，API:PostShopBonusMamager，jio_id:".$jio_id;
             //Commontools::PushNotificationToManagers($message);
        } else {
             Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);   
        }   
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
        if (!Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }
        if (!Commontools::CheckRequestArrayValue($value, 'md_id', 32, false, false)) {
            return false;
        }
        if (!Commontools::CheckRequestArrayValue($value, 'operation_type', 1, false, false)) {
            return false;
        }
        if (!Commontools::CheckRequestArrayValue($value, 'sbgi_id', 32, true, false)) {
            return false;
        }
        if (!Commontools::CheckRequestArrayValue($value, 'sbgi_fittype', 1,  false, false)) {
            return false;
        }
        if (!Commontools::CheckRequestArrayValue($value, 'sbgi_itemname', 20, false, false)) {
            return false;
        }
        if (!Commontools::CheckRequestArrayValue($value, 'sbgi_itemamount', 7, false, false)) {
            return false;
        }
        if (!Commontools::CheckRequestArrayValue($value, 'sbgi_effective', 1, false, false)) {
            return false;
        }  
        return true;
    }
    /**
     * 確認管理紅利動作(新增/修改)
     * @param type $inputData
     * @param type $messageCode
     * @return boolean
     */
    function ManageBounsType($inputData, &$messageCode){
        try {
             if ( $inputData['operation_type'] == 0 ) {
                if ( !PostShopBonusMamager::InsertNewBouns($inputData, $messageCode) ) {
                    return false; 
                }
             } else if ( $inputData['operation_type'] == 1 ) {
                if ( !PostShopBonusMamager::ModifyBouns($inputData, $messageCode) ) {
                    return false; 
                }
             } else {
                $messageCode = '999999989';
                return false;
             }
             return true;
        } catch(\Exception $e) {
             \App\Models\ErrorLog::InsertData($e);
             return false;
        }
    }
     /**
     * 建立新紅利所需新增資料
     * @param type $inputData
     * @param type $messageCode
     * @return boolean
     */
    private function InsertNewBouns($inputData, &$messageCode) {
        $datenow = new \Datetime();
        $sbgi_id = Commontools::NewGUIDWithoutDash();
        try {
              if ( mb_strlen($inputData['sbgi_id']) != 0 ) {
                  $messageCode = '011101003';
                  return false;
              } else if ( $inputData['sbgi_effective'] == 1 ) {
                   $inputData['sbgi_id'] = $sbgi_id;
                   $inputData['sbgi_effectivedate']  = $datenow-> format('Y-m-d H:i:s');
              } else if ( $inputData['sbgi_effective'] == 2 ) {
                   $inputData['sbgi_id'] = $sbgi_id;
                   $inputData['sbgi_effectivedate']  = null;
              }
              return PostShopBonusMamager::InsertShopBounsGiftItem($inputData);  
        } catch(\Exception $e) {
             \App\Models\ErrorLog::InsertData($e);
             return false;
        }
    }
    
    /**
     * 建立原有紅利所需修改資料
     * @param type $inputData
     * @param type $messageCode
     * @return boolean
     */
    private function ModifyBouns($inputData, &$messageCode) {
        $datenow = new \Datetime();
        $queryData = \App\Models\ICR_ShopBonus_GiftItem::GetDataBySbgiId($inputData['sbgi_id']);
        try {
              if (  mb_strlen($inputData['sbgi_id']) == 0  || is_null($queryData) || count($queryData) == 0 ) {
                  $messageCode = '011101004';
                  return false;
              } 
              if ( $inputData['sbgi_effective'] == 1 ) {
                  $inputData['sbgi_effectivedate'] = $datenow-> format('Y-m-d H:i:s');
                  $inputData['sbgi_failuredate'] = null;
              } else {
                  $inputData['sbgi_effectivedate'] = null;
                  $inputData['sbgi_failuredate'] = $datenow-> format('Y-m-d H:i:s');
              }
              
              return PostShopBonusMamager::UpdateShopBounsGiftItem ($inputData);
        } catch(\Exception $e) {
             \App\Models\ErrorLog::InsertData($e);
             return false;
        }
    }
    
    /**
     * 新增紅利
     * @param type $inputData
     * @return boolean
     */
    private function InsertShopBounsGiftItem ($inputData) {
        try {
           $saveData = [
                         'sbgi_id'            => $inputData['sbgi_id'],
                         'sd_id'              => $inputData['sd_id'],
                         'sbgi_fittype'       => $inputData['sbgi_fittype'],
                         'sbgi_itemname'      => $inputData['sbgi_itemname'],
                         'sbgi_itemamount'    => $inputData['sbgi_itemamount'],
                         'sbgi_effective'     => $inputData['sbgi_effective'],
                         'sbgi_effectivedate' => $inputData['sbgi_effectivedate'],
                         'create_user'        => $inputData['md_id'],
                       ];
            return \App\Models\ICR_ShopBonus_GiftItem::InsertData($saveData);
        } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
    
     
    /**
     * 修改紅利
     * @param type $inputData
     * @return boolean
     */
     private function UpdateShopBounsGiftItem ($inputData) {
        try {
           $saveData = [
                         'sbgi_id'            => $inputData['sbgi_id'],
                         'sbgi_fittype'       => $inputData['sbgi_fittype'],
                         'sbgi_itemname'      => $inputData['sbgi_itemname'],
                         'sbgi_itemamount'    => $inputData['sbgi_itemamount'],
                         'sbgi_effective'     => $inputData['sbgi_effective'],
                         'sbgi_effectivedate' => $inputData['sbgi_effectivedate'],
                         'sbgi_failuredate'   => $inputData['sbgi_failuredate'],
                         'last_update_user'   => $inputData['md_id'],
                       ];
            return \App\Models\ICR_ShopBonus_GiftItem::UpdateData($saveData);
        } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
       
 
}