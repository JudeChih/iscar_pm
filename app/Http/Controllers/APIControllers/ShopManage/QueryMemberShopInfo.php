<?php

namespace App\Http\Controllers\APIControllers\ShopManage;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** query_member_shopinfo	�d�߷|���w�j�w���Ӯa��� **/
class QueryMemberShopInfo {
   function querymembershopinfo() {
        $functionName = 'querymembershopinfo';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //��J��
            if(!QueryMemberShopInfo::CheckInput($inputData)){
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
            $ShopManage = new ShopManage;
            if ( !$ShopManage->CheckMdId($md_id, $messageCode)) {
                throw new \Exception($messageCode);
            }
            $ShopManage = new ShopManage;
            if (!$ShopManage-> CheckValidityDateByAmount($md_id, $inputData['queryamount'], $inputData['sd_id'], $arraydata, $md_clienttype, $messageCode)) {
                 throw new \Exception($messageCode);
            }
            if (!QueryMemberShopInfo::CreateResultData($arraydata,$resultData, $messageCode)) {
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
        /*if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'md_id', 36, true, false)) {
            return false;
        }*/
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'queryamount', 20, true, true)) {
            return false;
        }
         if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, true, false)) {
            return false;
        }
        if (!array_key_exists('queryamount', $value)) {
            $value['queryamount'] = 10;
        }
        if (!array_key_exists('sd_id', $value)) {
            $value['sd_id'] = null;
        }
        return true;
    }
   
   private function CreateResultData($arraydata, &$resultData, &$messageCode) {
     try {
         if (is_null($arraydata) || count($arraydata) == 0) {
            $resultData['shopdata_array']  = null;
            $messageCode = '000000003';
            return false;
         } else {
            $resultData['shopdata_array'] = $arraydata;  
         }
         return true;
     } catch(\Exception $e) {
      \App\Models\ErrorLog::InsertData($e);
      return false;
     }
   }
       
 
}