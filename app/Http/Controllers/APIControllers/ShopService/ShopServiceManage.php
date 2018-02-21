<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use App\Library\Commontools;
use Illuminate\Support\Facades\Input;
/** shopservicemanage	�Τ��ܪA�ȶ��ضi��ƶ� * */
class ShopServiceManage {
   function shopservicemanage() {
        $functionName = 'shopservicemanage';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try {
            if($inputData == null){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //��J��
            if(!ShopServiceManage::CheckInput($inputData)){
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
            if (!\App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //�̷ӶǤJ��action_type
           switch($inputData['action_type']) {
                case 0:
                ShopServiceManage::Insert_IcrShopServiceQueD($inputData,$messageCode);
                break;
                case 1:
                ShopServiceManage::Update_IcrShopServiceQueD($inputData,$messageCode);
                break;
                default:
                $messageCode = '010915001';
                break;
            }
            if(!is_null($messageCode)) {
               throw new \Exception($messageCode);
            } else {
               $messageCode = '000000000';
            }

        } catch(\Exception $e) {
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'action_type', 1, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqd_id', 32, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqd_title', 20, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqd_content', 140, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqd_mainpic', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqd_workhour', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqd_serviceprice', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqd_effectivity', 1, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqd_maxqueueamount', 0, true, false)) {
            return false;
        }
        return true;
    }
    /**
     *�ǤJ�ȧ�s
     * @params array $value
     */
     private function UpdateData_IcrShopServiceQueD($values) {
        try {
             $updataData['ssqd_id'] = $values['ssqd_id'];
             $updataData['ssqd_title'] = $values['ssqd_title'];
             $updataData['ssqd_content'] =$values['ssqd_content'];
             $updataData['ssqd_mainpic'] =$values['ssqd_mainpic'];
             $updataData['ssqd_workhour'] =$values['ssqd_workhour'];
             $updataData['ssqd_serviceprice'] =$values['ssqd_serviceprice'];
             $updataData['ssqd_effectivity'] =$values['ssqd_effectivity'];
             $updataData['ssqd_maxqueueamount'] =$values['ssqd_maxqueueamount'];
             return \App\Models\ICR_ShopServiceQue_d::UpdateData($updataData);

        } catch(\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return false;
        }
     }

     /**
     *�ǤJ�ȷs�W
     *@params array $values
     */
     private function InsertData_IcrShopServiceQueD($values) {
        try {
             $insertdata['ssqd_id'] = \App\Library\Commontools::NewGUIDWithoutDash();
             $insertdata['sd_id'] = $values['sd_id'];
             $insertdata['ssqd_title'] = $values['ssqd_title'];
             $insertdata['ssqd_content'] = $values['ssqd_content'];
             $insertdata['ssqd_mainpic'] = $values['ssqd_mainpic'];
             $insertdata['ssqd_workhour'] = $values['ssqd_workhour'];
             $insertdata['ssqd_serviceprice'] = $values['ssqd_serviceprice'];
             $insertdata['ssqd_effectivity'] = $values['ssqd_effectivity'];
             $insertdata['ssqd_maxqueueamount'] = $values['ssqd_maxqueueamount'];
             return \App\Models\ICR_ShopServiceQue_d::InsertData($insertdata);
        } catch(\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return false;
        }
     }


    /**
     *�̶ǤJ�� ACTION_TYPE == 0
     * $params array $inptData
     * $params string $messageCode
     */
     private function Insert_IcrShopServiceQueD($inputData,&$messageCode){
        try {
             //�A�Ƚs�����~�A�нT�{�᭫�o
             if(strlen($inputData['ssqd_id']) != 0) {
                $messageCode = '010913001';
                return;
             }
             if(!ShopServiceManage::InsertData_IcrShopServiceQueD($inputData)) {
                $messageCode = '999999999';
                return;
             }
        } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
        }

     }

    /**
     *�̶ǤJ�� ACTION_TYPE == 1
     * $params array $inptData
     * $params string $messageCode
     */
     private function Update_IcrShopServiceQueD($inputData,&$messageCode){
        try {
             //�A�Ƚs�����~�A�нT�{�᭫�o
             if(is_null($inputData['ssqd_id'])) {
                $messageCode = '010913001';
                return;
             }
             $result = \App\Models\ICR_ShopServiceQue_d::GetData($inputData['ssqd_id']);
             if(is_null($result)||count($result) == 0) {
                $messageCode = '010913001';
                return;
             }
             if(!ShopServiceManage::UpdateData_IcrShopServiceQueD($inputData)) {
                $messageCode = '999999999';
                return;
             }
        } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
        }
     }
}