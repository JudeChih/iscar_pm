<?php

namespace App\Http\Controllers\APIControllers\ShopPush;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** query_push_nonmember	�S���ӱ����D�|����H�e�d�ߥi�����`�� **/
class QueryPushNonmember {
   function querypushnonmember() {
        $functionName = 'querypushnonmember';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //��J��
            if(!QueryPushNonmember::CheckInput($inputData)){
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
            $ShopPush = new ShopPush;
            $pushObjectData = $ShopPush -> QueryPushObject($inputData['fbgender'], $inputData['age_min'], $inputData['age_max'], $inputData['rl_citys_id'], $inputData['sd_id'], $objectamount= null, $messageCode);
            if (is_null($pushObjectData) || count($pushObjectData) == 0  ) {
                throw new \Exception($messageCode);
            } 
            //�إߦ^�ǭ�
            if (!QueryPushNonmember::CreateResultData($pushObjectData, $resultData)) {
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'fbgender', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'age_min', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 0, true, false)) {
            return false;
        }
       
        return true;
    }
    
    
    /**
     * �إߦ^�ǭ�
     * @param type $arrayData
     * @param type $resultData
     * @return boolean
     */
    function CreateResultData ($pushObjectData, &$resultData) {
       try {
             $resultData = [
                              'objectamount' => count($pushObjectData),
                              'push_object'  => $pushObjectData,
                           ];
         
         return true;
       } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
       }
    }
   
       
 
}