<?php

namespace App\Http\Controllers\APIControllers\ShopManage;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** post_shopclerk_manager	商家店員管理 **/
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
            //輸入值
            if(!PostShopClerkManager::CheckInput($inputData)){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
           //檢查身份模組驗證
            $memService = new \App\Services\MemberService;
            if ( !$memService->checkModuleAccount($inputData['modacc'], $inputData['modvrf'], $messageCode)) {
              //模組身份驗證失敗
              $messageCode = '999999961';
              throw new \Exception($messageCode);
            }
            if (!$memService->checkServiceAccessToken($inputData['sat'], $md_id, $messageCode)) {
              //呼叫「MemberAPI」檢查SAT的狀態，驗證SAT有效性
               //$messageCode = '999999962';
               throw new \Exception($messageCode);
            }
             //檢查「店家」、「管理員」權限
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
             $message = "errormessage:$messageCode，API:PostShopClerkManager，jio_id:$jio_id";
             //Commontools::PushNotificationToManagers($message);
        } else {
             Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);   
        }   
        $result = [$functionName . 'result' => $resultArray];
        return $result;
   }
    /**
     * 檢查輸入值是否正確
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
     * 檢查店家會原綁定店長，店員記錄資料
     *
     *
     *
     */
    function CheckMemberBindShopData($sd_id, $owner_id, $clerk_id, &$smb_shoptype, &$smb_validity, &$smb_releation_id, &$smb_serno, &$clerk_exist, &$messageCode) {
        try {
           //依店長id,店員id，尋找店家綁定資料
           $queryData = \App\Models\ICR_SdmdBind::GetData_BySdId($sd_id);
           $booleanSearchId = array( 'owner'=> false, 'clerk'=> false, 'countClerkData' => 0 );
           foreach ( $queryData as $rowData ) {
              //尋找店長資料
              if ( $owner_id == $rowData['smb_md_id'] ) {
                 //確定綁定型態為店長
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
              //尋找店員資料
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
              //將會員身分，升為品牌商用戶
              if (!PostShopClerkManager::updateMemberCilentType($md_id , '1', $sat, $messageCode)) {
                  return false; 
              }
           } else if ($type == 1 ) {
              if ($clerK_exist) {
                  if(! PostShopClerkManager::updateSdmBind($smb_serno, 3)) {
                      $messageCode = '999999988';
                      return false; 
                  } 
                  //檢查會員是否還有綁訂店家，無則將身分降為 一般用戶。
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