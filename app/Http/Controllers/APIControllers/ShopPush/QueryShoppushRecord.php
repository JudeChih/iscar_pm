<?php

namespace App\Http\Controllers\APIControllers\ShopPush;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** query_shoppush_record	±À¼½°O¿ý¦Cªí¬d¸ß **/
class QueryShoppushRecord {
   function queryshoppushrecord() {
        $functionName = 'queryshoppushrecord';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //¿é¤J­È
            if(!QueryShoppushRecord::CheckInput($inputData)){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //ÀË¬d¨­¥÷¼Ò²ÕÅçÃÒ
            $memService = new \App\Services\MemberService;
            if ( !$memService->checkModuleAccount($inputData['modacc'], $inputData['modvrf'], $messageCode)) {
              //¼Ò²Õ¨­¥÷ÅçÃÒ¥¢±Ñ
              $messageCode = '999999961';
              throw new \Exception($messageCode);
            }
            if (!$memService->checkServiceAccessToken($inputData['sat'], $md_id, $messageCode)) {
              //©I¥s¡uMemberAPI¡vÀË¬dSATªºª¬ºA¡AÅçÃÒSAT¦³®Ä©Ê
               //$messageCode = '999999962';
               throw new \Exception($messageCode);
            }
             //ÀË¬d¡u©±®a¡v¡B¡uºÞ²z­û¡vÅv­­
            if (!Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //ÀË¬dtype°Ê§@
            if ( !QueryShoppushRecord::CheckObjectType($inputData['sapm_objecttype'], $messageCode)) {
                throw new \Exception($messageCode);
            }
            //«Ø¥ß¦^¶Ç­È
            if (!QueryShoppushRecord::CreateResultData ($inputData, $resultData)) {
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
        Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);   
        $result = [$functionName . 'result' => $resultArray];
        return $result;
   }
    /**
     * ÀË¬d¿é¤J­È¬O§_¥¿½T
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        } 
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sapm_objecttype', 2, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'create_date', 20, true, true)) {
            return false;
        } 
         if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'queryamount', 3, true, false)) {
            return false;
        } 
        return true;
    }
    
     /**
     * ÀË¬dtype°Ê§@
     * @param type $sapm_objecttype
     * @param type $messageCode
     * @return boolean
     */
    function CheckObjectType($sapm_objecttype, &$messageCode) {
        if ( !in_array($sapm_objecttype,array(0,1) ) ) {
            $messageCode = '999999989';
            return false;
        }
        return true;
    }
    
    /**
     * «Ø¥ß¦^¶Ç­È
     * @param type $arrayData
     * @param type $resultData
     * @return boolean
     */
    function CreateResultData ($arrayData, &$resultData) {
       try {
         $queryData = \App\Models\ICR_ShopAdpush_M::GetPushDataList($arrayData);
         $resultData['pushhistory_array'] = null;
         foreach ($queryData as $rowData) {
            $resultData['pushhistory_array'][] = [
                                                   'sapm_id'           => $rowData['sapm_id'],
                                                   'sapm_objecttype'   => $rowData['sapm_objecttype'],
                                                   'sapm_pushpic'      => $rowData['sapm_pushpic'],
                                                   'sapm_pushcontent'  => $rowData['sapm_pushcontent'],
                                                   'sapm_objectamount' => $rowData['sapm_objectamount'],
                                                   'sapm_pushresult'   => $rowData['sapm_pushresult'],
                                                   'create_date'       => $rowData['create_date']
                                                 ];
         }
         return true;
       } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
       }
    }
   
       
 
}