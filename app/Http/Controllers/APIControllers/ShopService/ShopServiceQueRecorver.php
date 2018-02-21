<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** shopservicequerecorver	�Τ���o�Ҧ��ƶ��O�� * */
class ShopServiceQueRecorver {
   function shopservicequerecorver() {
        $functionName = 'shopservicequerecorver';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            if($inputData == null){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //��J��
            if(!ShopServiceQueRecorver::CheckInput($inputData)){
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
            //�� last_update_date ���ȡA�çP�_���L��
            if($inputData['last_update_date'] == null) {
               $ssqq_querydata = \App\Models\ICR_ShopServiceQue_q::Query_FindAllClientShopService($md_id, null);
               if (is_null($ssqq_querydata) || count($ssqq_querydata) == 0) {
                  //�L�ݧ�s�A�@�~����
                  $messageCode = '000000001';
                  throw new \Exception($messageCode);
               }
            } else {
               $ssqq_querydata= \App\Models\ICR_ShopServiceQue_q::Query_FindAllClientShopService($md_id, $inputData['last_update_date']);
               if (is_null($ssqq_querydata) || count($ssqq_querydata) == 0) {
                  //�L�ݧ�s�A�@�~����
                  $messageCode = '000000001';
                  throw new \Exception($messageCode);
               }
            }
            //���o�U��list�C
            $ssqq_list = $ssqq_querydata;
            $ssqdId_array = array();
            $sdId_array = array();
            foreach($ssqq_list as $rowdata) {
              array_push($ssqdId_array,$rowdata['ssqd_id']);
              array_push($sdId_array,$rowdata['sd_id']);
            }
            $ssqd_list = \App\Models\ICR_ShopServiceQue_d::Query_ServiceData_ByARRAY(array_unique($ssqdId_array));
            $sd_list = \App\Models\ICR_ShopData::Query_ShopData_ByARRAY(array_unique($sdId_array));
            $resultData['last_update_date'] = $ssqq_querydata[0]['last_update_date'];
            $resultData['ssqq_list'] = $ssqq_list;
            $resultData['ssqd_list'] = $ssqd_list;
            $resultData['sd_list'] = $sd_list;
            $messageCode = '000000009'; 
        } catch(\Exception $e){
           if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($e);
            }
        }
        $resultArray = Commontools::ResultProcess($messageCode, $resultData);
        Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [$functionName . 'result' => $resultArray];
        return  $result;
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'last_update_date', 20, true, true)) {
            return false;
        }
        return true;
    }
}
   