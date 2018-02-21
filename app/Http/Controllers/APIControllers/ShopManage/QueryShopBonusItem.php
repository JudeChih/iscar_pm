<?php

namespace App\Http\Controllers\APIControllers\ShopManage;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** query_shopbonus_item ���Q���جd�� **/
class QueryShopBonusItem {
   function queryshopbonusitem() {
        $functionName = 'queryshopbonusitem';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //��J��
            if(!QueryShopBonusItem::CheckInput($inputData)){
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
            //�إߦ^�ǭ�
            if (!QueryShopBonusItem::CreateResultData($inputData['sd_id'],  $inputData['sbgi_id'], $messageCode, $resultData)) {
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
        if (!Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        } 
        if (!Commontools::CheckRequestArrayValue($value, 'sbgi_id', 32, true, false)) {
            return false;
        } 
        return true;
    }
    /**
     * �إߦ^�ǭ�
     * @param type $sd_id
     * @param type $messagecode
     * @param type $resultData
     * @return boolean
     */
    private function CreateResultData($sd_id, $sbgi_id, &$messagecode, &$resultData) {
       $queryData =  \App\Models\ICR_ShopBonus_GiftItem::GetDataBySdId_SbgiId($sd_id, $sbgi_id);
       if ( is_null($queryData) || count($queryData) == 0 ) {
           $messagecode = '000000003';
           $resultData['bonusitems'] = null;
           return false;
       }
       foreach ($queryData as $row ) {
           $resultData['bonusitems'][] = [
                                            'sbgi_id'            => $row['sbgi_id'],
                                            'sbgi_fittype'       => $row['sbgi_fittype'],
                                            'sbgi_itemname'      => $row['sbgi_itemname'],
                                            'sbgi_itemamount'    => $row['sbgi_itemamount'],
                                            'sbgi_effective'     => $row['sbgi_effective'],
                                            'sbgi_effectivedate' => $row['sbgi_effectivedate'],
                                         ];
       }
       return true;
    }
       
 
}