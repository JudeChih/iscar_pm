<?php

namespace App\Http\Controllers\APIControllers\CannedMessage;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** modifycannedmessage	�������Y�T����� * */
class ModifyCannedMessage {
   function modifycannedmessage() {
        $functionName = 'modifycannedmessage';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //��J��
            if(!ModifyCannedMessage::CheckInput($inputData, $messageCode)){
               if (is_null( $messageCode) )  {
                    $messageCode = '999999995';
               }
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
               $messageCode = '999999960';
               throw new \Exception($messageCode);
            }
            // �ˬd�u���a�v�B�u�޲z���v�v��
            if (! Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
              throw new \Exception($messageCode);
            }
            $CannedMSRepo = new \App\Repositories\PmCannedMessagesRepository();
            if ($inputData['modify_type'] == 0 ) {
              if ( ! ModifyCannedMessage::deleteCannedMsg($CannedMSRepo, $inputData['cmsg_serno']) ) {
                  throw new \Exception($messageCode);
              }
            } else if ($inputData['modify_type'] == 1) {
              if (! ModifyCannedMessage::insertCannedMsg($CannedMSRepo, $inputData['sd_id'], $inputData['cmsg_content'], $messageCode)) {
                 throw new \Exception($messageCode);
              }
            } else if ($inputData['modify_type'] == 2 ) {
               if (! ModifyCannedMessage::ModifyCannedNsg($CannedMSRepo, $inputData['cmsg_serno'], $inputData['cmsg_content'],  $inputData['sd_id'], $messageCode) ) {
                 throw new \Exception($messageCode);
              }
            }
            $messageCode ='000000000';
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
    function CheckInput(&$value, &$messageCode) {
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'modify_type', 1, false, false)) {
            return false;
        }
         if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cmsg_serno', 0, true, false)) {
            return false;
        }
         if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cmsg_content', 200, true, true)) {
            $messageCode = '010923007';
            return false;
        }
        //�R��
        if ( $value['modify_type'] == 0 ) {
          if ( is_null($value['cmsg_serno']) || mb_strlen($value['cmsg_serno']) == 0 ) {
            return false;
          }
        } /* �s�W */else if ( $value['modify_type'] == 1 ) {
          if (is_null ($value['cmsg_content']) || mb_strlen($value['cmsg_content']) == 0 ) {
            return false;
          }
        } /*�ק�*/ else if ( $value['modify_type'] == 2 ) {
          if ( (is_null($value['cmsg_serno']) || mb_strlen($value['cmsg_serno']) == 0) && (is_null ($value['cmsg_content']) || mb_strlen($value['cmsg_content']) == 0) ) {
            return false;
          }
        }
        return true;
    }

    function deleteCannedMsg($CannedMSRepo, $cmsg_serno) {
        try {
             if( !$CannedMSRepo->DeleteData($cmsg_serno) ) {
                return false;
             }
             return true;
        } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
        }
    }

    function insertCannedMsg($CannedMSRepo, $sd_id, $cmsg_content, &$messageCode) {
       try {
         $cannelMsgData = $CannedMSRepo->getDataBySdId($sd_id);
         //�P�_���ƬO�_�F��20��
         if (count($cannelMsgData) >= 20 ) {
           return false;
         }
         //�P�_�O�_�w���ۦP���e
         foreach ($cannelMsgData as $row) {
             if(strcmp($row['cmsg_content'],trim($cmsg_content)) == 0 ) {
               $messageCode = '010923006';
                return false;
             }
         }
         $arrayData = [ 'sd_id' => $sd_id, 'cmsg_content' => trim($cmsg_content) ];
        
         if (!$CannedMSRepo-> InsertData($arrayData) ) {
         
          return false;
         }
         
         return true;
       } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
      }
    }

    function ModifyCannedNsg($CannedMSRepo, $cmsg_serno, $cmsg_content, $sd_id, &$messageCode) {
       try {
            $cannelMsgData = $CannedMSRepo->getDataBySdId($sd_id);
            //�P�_���ƬO�_�F��20��
            if (count($cannelMsgData) >= 20 ) {
               return false;
            }
            //�P�_�O�_�w���ۦP���e
            foreach ($cannelMsgData as $row) {
               if(strcmp($row['cmsg_content'],trim($cmsg_content)) == 0 ) {
                   $messageCode = '010923006';
                   return false;
               }
            }
            $arrayData = [ 'cmsg_serno' => $cmsg_serno,
                                  'cmsg_content' => trim($cmsg_content) ];
            if (!$CannedMSRepo-> UpdateData($arrayData) ) {
                  return false;
            }
           return true;
      } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
      }
    }

}