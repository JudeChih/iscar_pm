<?php

namespace App\Http\Controllers\APIControllers\ShopManage;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
 /** query_shopclerk_list	�d�߰Ӯa������� **/
class QueryShopclerkList {
   function queryshopclerklist() {
        $functionName = 'queryshopclerklist';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //��J��
            if(!QueryShopclerkList::CheckInput($inputData)){
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
            if ( !$ShopManage->CheckMdId($inputData['md_id'], $messageCode)) {
                throw new \Exception($messageCode);
            }
            
            if (!QueryShopclerkList::CreateResultData($inputData['md_id'], $inputData['sd_id'], $resultData, $messageCode)) {
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'md_id', 36, true, false)) {
            return false;
        }  
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, true, false)) {
            return false;
        } 
        return true;
    }
   
   private function CreateResultData($md_id, $sd_id, &$resultData, &$messageCode) {
     try {
        $queryData = \App\Models\ICR_SdmdBind::GetData_BySdId($sd_id);
        $resultData['clerk_list'] = null;
        $boolMdId = false;
        foreach ($queryData as $rowData) {
          if ($rowData['smb_md_id'] == $md_id) {
              $boolMdId = true;
          }
          $resultData['clerk_list'][] = [
                                          'smb_serno'         => $rowData['smb_serno'],
                                          'smb_md_id'         => $rowData['smb_md_id'],
                                          'smb_bindlevel'     => $rowData['smb_bindlevel'],
                                          'smb_activestatus'  => $rowData['smb_activestatus'],
                                          'md_cname'          => $rowData['md_cname'],
                                          'ssd_picturepath'   => $rowData['ssd_picturepath'],
                                        ];
        }
        if (!$boolMdId) {
          $messageCode = '010902002';
          $resultData['clerk_list'] = null;
          return false;
        }
        return true;
     } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return false;
     }
   }
       
 
}