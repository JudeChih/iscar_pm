<?php

namespace App\Http\Controllers\APIControllers\ShopPush;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** query_shoppush_content	�������ؤ��e�d�� **/
class QueryShoppushContent {
   function queryshoppushcontent() {
        $functionName = 'queryshoppushcontent';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //��J��
            if(!QueryShoppushContent::CheckInput($inputData)){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
           //�ˬd�����Ҳ�����
            $memService = new \App\Services\MemberService;
            if ( !$memService->checkModuleAccount($inputData['modacc'], $inputData['modvrf'], $messageCode)) {
              //�Ҳը������ҥ���
              $messageCode = '999999961';
              throw new \Exception($messageCode);
            }
            if (!$memService->checkServiceAccessToken($inputData['sat'], $md_id, $messageCode)) {
              //�I�s�uMemberAPI�v�ˬdSAT�����A�A����SAT���ĩ�
               //$messageCode = '999999962';
               throw new \Exception($messageCode);
            }
             //�ˬd�u���a�v�B�u�޲z���v�v��
            if (!Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //�ˬd�ëإߦ^�ǭ�
            if (!QueryShoppushContent::CheckAndCreateResultData($inputData['sapm_id'], $inputData['sd_id'], $messageCode, $resultData)) {
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
     * �ˬd��J�ȬO�_���T
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sapm_id', 32, false, false)) {
            return false;
        }
        
        return true;
    }
    
  
    /**
     * �ˬd�ëإߦ^�ǭ�  
     * @param type $sapm_id
     * @param type $sd_id
     * @param type $messageCode
     * @param type $resultData
     * @return boolean
     */
    function CheckAndCreateResultData ($sapm_id, $sd_id, &$messageCode, &$resultData) {
       try {
         $readed_count = 0;
         $queryData = \App\Models\ICR_ShopAdpush_M::GetPushContent($sapm_id);
         
         if (is_null($queryData) || count($queryData) == 0 ) {
            $messageCode = '000000003';
            return false;
         } else if ($queryData[0]['sd_id'] != $sd_id ) {
            $messageCode = '999999977';
            return false;
         }
        // \App\Models\ErrorLog::InsertLog( '01');
         $appService = new \App\Services\AppService;
         $readed_count = $appService->getUmlMessageLogReadCount($sapm_id);
         // \App\Models\ErrorLog::InsertLog( '02');
         $resultData = [
                         'sapm_id'                => $queryData[0]['sapm_id'] ,
                         'sapm_objecttype'        => $queryData[0]['sapm_objecttype'] ,
                         'sapm_pushpic'           => $queryData[0]['sapm_pushpic'] ,
                         'sapm_pushcontent'       => $queryData[0]['sapm_pushcontent'] ,
                         'sapm_designatefunction' => $queryData[0]['sapm_designatefunction'] ,
                         'sapm_objectamount'      => $queryData[0]['sapm_objectamount'] ,
                         'sapm_pushfee'           => $queryData[0]['sapm_pushfee'] ,
                         'sapm_pushresult'        => $queryData[0]['sapm_pushresult'] ,
                         'readed_count'          =>  $readed_count  ,
                         'md_cname'               => $queryData[0]['md_cname'] ,
                       ] ;
         
         return true;
       } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
       }
    }
   
       
 
}