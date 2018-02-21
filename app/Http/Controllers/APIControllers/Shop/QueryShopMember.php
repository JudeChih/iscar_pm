<?php

namespace App\Http\Controllers\APIControllers\Shop;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
//use \App\Http\Controllers\CrossModelAPI;
/* query_shopmember  查詢商家所有會員資料 */
class QueryShopMember {
     public static function queryshopmember() {
        $functionName = 'queryshopmember';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            if(is_null($inputData)){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //輸入值
            if(!QueryShopMember::CheckInput($inputData)){
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
            if (!QueryShopMember::CreateResultData($inputData['sd_id'], $messageCode, $resultData)) {
               throw new \Exception($messageCode);
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
    public static function CheckInput(&$value) {
        if (is_null($value)) {
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }
        return true;
    }
    
    
    public static function CreateResultData($sd_id, &$messageCode, &$resultData) {
       try {
         $queryData = \App\Models\IsCarUserBookmark::GetMemberBySD_ID($sd_id);
         if ( is_null($queryData) || count($queryData) == 0 ) {
           $messageCode = '000000003';
           return false;
         }
         
         foreach( $queryData as $row ) {
            $resultData['shop_member_array'][] = [
                                                   'md_id'           => $row['md_id'],
                                                   'md_cname'        => QueryShopMember::getSalesCode($row['md_id'], $row['md_cname']),
                                                   'ssd_picturepath' => $row['ssd_picturepath']
                                                 ];
         }
         return true;
       } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
       }       
    }
    
    
    public static function getSalesCode($md_id, $md_cname) {
       try {
         $queryData = \App\Models\ICR_SdmdBind::GetData_ByMdid($md_id);
         $nameCode = null;
         if ( is_null($queryData) || count($queryData) == 0 || strlen($queryData[0]['sd_salescode']) == 0 ) {
           return $md_cname;
         } else {
             $nameCode = $md_cname . '(' .$queryData[0]['sd_salescode'] . ')';
         }
         return $nameCode;
       } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
       } 
    }
    
    
    
    
}
