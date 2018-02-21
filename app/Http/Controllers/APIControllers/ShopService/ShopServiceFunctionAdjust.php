<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use Illuminate\Support\Facades\Input;

use App\Library\Commontools;

/** shopservicefunctionadjust	�Ӯa�վ�A�ȱƶ������򥻼ƾ� * */
class ShopServiceFunctionAdjust {
    function shopservicefunctionadjust(){
        $functionName = 'shopservicefunctionadjust';
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
            if(!ShopServiceFunctionAdjust::CheckInput($inputData)){
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
            $result = \App\Models\ICR_ShopServiceQue_m::GetData($inputData['sd_id']);
             //�̷ӶǤJ��action_type
            switch($inputData['action_type']) {
                case 0:
                     ShopServiceFunctionAdjust::Insert_IcrShopSeviceQueM($result,$inputData,$messageCode);
                break;
                case 1:
                     ShopServiceFunctionAdjust::Updata_IcrShopSeviceQueM($result,$inputData,$messageCode);
                break;
                case 2:
                     ShopServiceFunctionAdjust::Read_IcrShopSeviceQueM($result,$inputData,$messageCode);
                break;
                default:
                     $messageCode = '010915001';
                break; 
            }
            if(!is_null($messageCode)) {
               throw new \Exception($messageCode);
            } 
            if (!ShopServiceFunctionAdjust::CreateResultData($inputData['action_type'],$result, $resultData)) {
               throw new \Exception($messageCode);
            }
            $messageCode = '000000000';
        } catch (\Exception $e) {
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqm_weekstart', 3, true, false)) {
            return false;
        } 
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqm_weekend', 3, true, false)) {
            return false;
        }  
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqm_dailystart', 8, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqm_dailyend', 8, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'ssqm_dayoffarray', 0, true, true)) {
            return false;
        }
        return true;
    }
    /**
     *�ǤJ�ȧ�s
     * @params array $value
     */
    private function UpdateData_IcrShopServiceQueM($values) {
      try {
           $updataData['sd_id'] = $values['sd_id'];
           $updataData['ssqm_weekstart'] =$values['ssqm_weekstart'];
           $updataData['ssqm_weekend'] =$values['ssqm_weekend'];
           $updataData['ssqm_dailystart'] =$values['ssqm_dailystart'];
           $updataData['ssqm_dailyend'] =$values['ssqm_dailyend'];
           $updataData['ssqm_dayoffarray'] = $values['ssqm_dayoffarray'];
           return \App\Models\ICR_ShopServiceQue_m::UpdateData($updataData);  
      } catch(\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return false;
      }
    }
    
    /**
     *�ǤJ�ȷs�W
     *@params array $values
     */
    private function InsertData_IcrShopServiceQueM($values) {
      try {
           $insertdata['sd_id'] = $values['sd_id'];
           $insertdata['ssqm_weekstart'] = $values['ssqm_weekstart'];
           $insertdata['ssqm_weekend'] = $values['ssqm_weekend'];
           $insertdata['ssqm_dailystart'] = $values['ssqm_dailystart'];
           $insertdata['ssqm_dailyend'] = $values['ssqm_dailyend'];
           $insertdata['ssqm_dayoffarray'] = $values['ssqm_dayoffarray'];
           return \App\Models\ICR_ShopServiceQue_m::InsertData($insertdata);
      } catch(\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return false;
      }
    }
    
    /**
     *action_type == 0
     *�s�W���IcrShopSeviceQueM
     *
     */
    private function Insert_IcrShopSeviceQueM($result,$inputData,&$messageCode) {
      try {
            if(count($result) != 0) { 
              $messageCode ='010915001';
              return;
            }
            if(!ShopServiceFunctionAdjust::InsertData_IcrShopServiceQueM($inputData)) {
              $messageCode = '999999999';
              return;
            }
          } catch(\Exception $e) {
              \App\Models\ErrorLog::InsertData($e);
          }
       
    }
    /**
     *action_type == 1
     *��s���IcrShopSeviceQueM
     */
    private function Updata_IcrShopSeviceQueM($result,$inputData,&$messageCode) {
      try {
           if(count($result) == 0) {
             $messageCode ='010915002';
             return;
           }
            if(!ShopServiceFunctionAdjust::UpdateData_IcrShopServiceQueM($inputData)) {
                $messageCode = '999999999';
                return;
            }
      } catch(\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
      }
    }
    /**
     *action_type == 2
     *Ū�����IcrShopSeviceQueM
     */
    private function Read_IcrShopSeviceQueM($result,$inputData,&$messageCode) {
      try {
           if(is_null($result)) {
             $messageCode ='010915002';
             return;
           }
      } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
      }
    }
    
     /**
     * �إߦ^�Ǹ��
     * @param type $action
     * @param type $result
     * @param type $resultData
     * 
     */
    private function CreateResultData($action, $result,&$resultData) {
        try {
            if ($action !=  2 || count($result) == 0 ) {
                $resultData['ssqm_weekstart'] = null;
                $resultData['ssqm_weekend'] = null;
                $resultData['ssqm_dailystart'] = null;
                $resultData['ssqm_dailyend'] = null;
                $resultData['ssqm_dayoffarray'] = null;
                $resultData['ssqm_servicestart'] = null;
            } else {
                $resultData['ssqm_weekstart'] = $result[0]['ssqm_weekstart'];
                $resultData['ssqm_weekend'] = $result[0]['ssqm_weekend'];
                $resultData['ssqm_dailystart'] = $result[0]['ssqm_dailystart'];
                $resultData['ssqm_dailyend'] = $result[0]['ssqm_dailyend'];
                $resultData['ssqm_dayoffarray'] = $result[0]['ssqm_dayoffarray'];
                $resultData['ssqm_servicestart'] = $result[0]['ssqm_servicestart'];
            }
            return true;
        } catch (\Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }
}