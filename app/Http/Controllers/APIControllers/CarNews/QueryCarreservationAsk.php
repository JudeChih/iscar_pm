<?php

namespace App\Http\Controllers\APIControllers\CarNews;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** querycarreservationask	���d�߶R�訮�����ݸ߰ݰO�� * */
class QueryCarreservationAsk {
   function querycarreservationask() {
        $functionName = 'querycarreservationask';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //��J��
            if(!QueryCarreservationAsk::CheckInput($inputData)){
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
            //�O���Τ�ݩ��ݥ\�������A0:�@��Τ�� 1:�X�@���Ӯa�Τ�
            if ($inputData['md_clienttype'] == 0) {
               $crn_owner_id = $md_id ;
               $crn_id = $inputData['crn_id'];
               $cbi_id = $inputData['cbi_id'];
            } else if ($inputData['md_clienttype'] == 1) {
               //�ˬd�u���a�v�B�u�޲z���v�v��
               if (!\App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                   throw new \Exception($messageCode);
               } else {
                   $crn_owner_id = $inputData['sd_id'];
                   $crn_id = $inputData['crn_id'];
                   $cbi_id = $inputData['cbi_id'];
               }
            } else {
               //�L�Ī��ާ@�ʧ@�A�Э��s��J
               $messageCode = '011101002';
               throw new \Exception($messageCode);
            }
            $querydata = \App\Models\ICR_Carreservation::GetData_ByCrnId($cbi_id,$crn_owner_id,$crn_id);
            if(is_null($querydata) || count($querydata) == 0 || count($querydata) > 1) {
               //�d�L���ݰO���A�нT�{�᭫�o
               $messageCode = '011105001';
               throw new \Exception($messageCode);
            }

            if (!QueryCarreservationAsk::CreateResultData($querydata, $resultData)) {
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'md_clienttype', 1, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'crn_id', 32, false, true)) {
            return false;
        }
        return true;
    }


    function CreateResultData($querydata, &$resultData) {
       try {
           $resultData = [
                            'cbi_advertisementtitle'  => $querydata[0]['cbi_advertisementtitle'],
                            'cps_picpath'             => $querydata[0]['cps_picpath'],
                            'cbl_fullname'            => $querydata[0]['cbl_fullname'] ,
                            'cbm_fullname'            => $querydata[0]['cbl_fullname'],
                            'cms_fullname'            => $querydata[0]['cms_fullname'],
                            'crn_buyer_realname'      => $querydata[0]['crn_buyer_realname'],
                            'crn_buyer_ask_message'   => $querydata[0]['crn_buyer_ask_message'],
                            'crn_available_timearray' => $querydata[0]['crn_available_timearray'],
                            'crn_carinstore_ask'      => $querydata[0]['crn_carinstore_ask']
                         ];
           return true;
       } catch(\Exception $e) {
         return false;
         App\Models\ErrorLog::InsertData($e);
       }
    }
}