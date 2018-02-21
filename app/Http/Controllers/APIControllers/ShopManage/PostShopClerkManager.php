<?php

namespace App\Http\Controllers\APIControllers\ShopManage;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** post_shopclerk_manager	�Ӯa�����޲z **/
class PostShopClerkManager {
   function postshopclerkmanager() {
        $functionName = 'postshopclerkmanager';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //��J��
            if(!PostShopClerkManager::CheckInput($inputData)){
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
            if (!Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $inputData['md_id'], $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            $ShopManage = new ShopManage;
            if ( ! $ShopManage->CheckMdId($inputData['clerk_md_id'], $messageCode)) {
                throw new \Exception($messageCode);
            }

            if (!PostShopClerkManager::CheckMemberBindShopData($inputData['sd_id'], $inputData['md_id'], $inputData['clerk_md_id'], $smb_shoptype, $smb_validity, $smb_releation_id, $smb_serno, $clerk_exist, $messageCode)) {
                throw new \Exception($messageCode);
            }

            if (!PostShopClerkManager::modifyClerkByType($inputData['operation_type'], $clerk_exist, $smb_serno, $inputData['sd_id'], $inputData['clerk_md_id'], $smb_validity, $smb_releation_id, $smb_shoptype, $inputData['sat'], $messageCode)) {
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
        if ( $messageCode =='999999988') {
             Commontools::WriteExecuteLogGetId($functionName, $inputString, json_encode($resultArray), $messageCode ,$jio_id);
             $message = "errormessage:$messageCode�AAPI:PostShopClerkManager�Ajio_id:$jio_id";
             //Commontools::PushNotificationToManagers($message);
        } else {
             Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);   
        }   
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'md_id', 36, false, false)) {
            return false;
        } 
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        } 
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'operation_type', 1, false, false)) {
            return false;
        } 
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'clerk_md_id', 36, false, false)) {
            return false;
        }
        return true;
    }

    /**
     * �ˬd���a�|��j�w�����A�����O�����
     *
     *
     *
     */
    function CheckMemberBindShopData($sd_id, $owner_id, $clerk_id, &$smb_shoptype, &$smb_validity, &$smb_releation_id, &$smb_serno, &$clerk_exist, &$messageCode) {
        try {
           //�̩���id,����id�A�M�䩱�a�j�w���
           $queryData = \App\Models\ICR_SdmdBind::GetData_BySdId($sd_id);
           $booleanSearchId = array( 'owner'=> false, 'clerk'=> false, 'countClerkData' => 0 );
           foreach ( $queryData as $rowData ) {
              //�M�䩱�����
              if ( $owner_id == $rowData['smb_md_id'] ) {
                 //�T�w�j�w���A������
                 if ( $rowData['smb_bindlevel'] != 0 ) {
                     $messageCode = '01902002';
                     return false;
                 }
                 $booleanSearchId['owner'] = true;
                 $smb_shoptype = $rowData['smb_shoptype'];
                 $smb_validity = $rowData['smb_validity'];
                 $smb_releation_id = $rowData['smb_releation_id'];
                 continue;
              }
              //�M�䩱�����
              if ( $clerk_id == $rowData['smb_md_id'] && $rowData['smb_bindlevel'] == 1 ) {
                 $booleanSearchId['countClerkData'] ++;
                 if ( $booleanSearchId['countClerkData'] > 1 ) {
                     $booleanSearchId['clerk'] = false;
                     PostShopClerkManager::ModifySdmBind_IsFalg($sd_id,$clerk_id,1);
                     continue;
                 }
                 $booleanSearchId['clerk'] = true;
                 $smb_serno = $rowData['smb_serno'];
              }
           }
           if ( $booleanSearchId['owner'] == false ) {
               $messageCode = '999999980';
               return false;
           } 
           $clerk_exist = $booleanSearchId['clerk'];
           return true;
        } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
        }
    }

    private function ModifySdmBind_IsFalg($sd_id,$md_id,$smb_bindlevel) {
        try {
            $updateData = [
                            'smb_sd_id'     => $sd_id,
                            'smb_md_id'     => $md_id,
                            'isflag'        => 0,
                            'smb_bindlevel' => $smb_bindlevel,
                          ];
            return \App\Models\ICR_SdmdBind::UpdateDataBySdId_MdId($updateData);
        } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
        }
    }
    
    function modifyClerkByType($type, $clerK_exist, $smb_serno, $sd_id, $md_id, $smb_validity, $smb_releation_id, $smb_shoptype, $sat, &$messageCode) {
        try {
           if ($type == 0 ) {
              if ($clerK_exist) {
                  if(!PostShopClerkManager::updateSdmBind($smb_serno, 1)) {
                     $messageCode = '999999988';
                     return false; 
                  } 
              } else {
                  $arraydata = [
                                    'smb_sd_id'        => $sd_id, 
                                    'smb_md_id'        => $md_id, 
                                    'smb_validity'     => $smb_validity, 
                                    'smb_releation_id' => $smb_releation_id, 
                                    'smb_shoptype'     => $smb_shoptype
                                ];
                   if(! PostShopClerkManager::InsertSdmId($arraydata)) {
                      $messageCode = '999999988';
                      return false; 
                   }
              }
              //�N�|�������A�ɬ��~�P�ӥΤ�
              if (!PostShopClerkManager::updateMemberCilentType($md_id , '1', $sat, $messageCode)) {
                  return false; 
              }
           } else if ($type == 1 ) {
              if ($clerK_exist) {
                  if(! PostShopClerkManager::updateSdmBind($smb_serno, 3)) {
                      $messageCode = '999999988';
                      return false; 
                  } 
                  //�ˬd�|���O�_�٦��j�q���a�A�L�h�N�������� �@��Τ�C
                  $checkData = \App\Models\ICR_SdmdBind::GetData_ByMd_ID($md_id, false);
                  if ( count($checkData) == 0 ) {
                    // \App\Models\ErrorLog::InsertLog('02');
                       if (!PostShopClerkManager::updateMemberCilentType($md_id , '0', $sat, $messageCode)) {
                             return false; 
                        }
                  }
              } else {
                     $messageCode = '000000001';
                     return false;
              }
           } else {
              $messageCode = '999999989';
              return false;
           } 
           return true;
        } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
        }
    }
    
    private function updateSdmBind($smb_serno, $smb_activestatus) {
        try {
             $updateData = [ 
                             'smb_serno'        => $smb_serno,
                             'smb_activestatus' => $smb_activestatus,
                           ];
              return \App\Models\ICR_SdmdBind::UpdateData($updateData);
        } catch (\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
        }
    }
    
    private function InsertSdmId($arraydata) {
        try {
          $savedata = [
                         'smb_sd_id'         => $arraydata['smb_sd_id'],
                         'smb_activestatus'  => 1,
                         'smb_md_id'         => $arraydata['smb_md_id'],
                         'smb_validity'      => $arraydata['smb_validity'],
                         'smb_bindway'       => 3,
                         'smb_releation_id'  => $arraydata['smb_releation_id'],
                         'smb_shoptype'      => $arraydata['smb_shoptype'],
                         'smb_bindlevel'     => 1,
                      ];
           return \App\Models\ICR_SdmdBind::InsertData($savedata, $smb_serno);
        } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
        }
    }

    private function updateMemberCilentType ($md_id , $clienttype, $sat, $messageCode) {
      try {
        $memService = new \App\Services\MemberService;
        if (
             !\App\Models\IsCarMemberData::UpdateData_ClientType($md_id, $clienttype) ||
             !$memService->modify_member_clienttype($md_id, $sat, $clienttype, $messageCode)
           ) {
             return false;
        }
        return true;
      } catch(\Exception $ex) {
         \App\Models\ErrorLog::InsertData($ex);
         return false;
      }
    }

}