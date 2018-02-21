<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** shopservicequeabandom	用戶放棄排隊 * */
class ShopServiceQueAbandom {
   function shopservicequeabandom() {
        $functionName = 'shopservicequeabandom';
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
            //輸入值
            if(!ShopServiceQueAbandom::CheckInput($inputData)){
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
            $querydata = \App\Models\ICR_ShopServiceQue_q::Query_FindNeedAbandomService($inputData['ssqq_id']);
            if(count($querydata) == 0||$querydata[0]['md_id'] != $md_id) {
               //查無記錄，請重新確認
               $messageCode = '010922001';
               throw new \Exception($messageCode);
            }
            if (in_array($querydata[0]['ssqq_usestatus'],array(2,5,6,8))) {
               //該記錄已用畢，請重啟APP以更新服務排隊狀態
               $messageCode = '010922002';
               throw new \Exception($messageCode);
            }
            if (in_array($querydata[0]['ssqq_usestatus'],array(3,4))) {
               //該記錄已棄用或失約，請重啟APP以更新活動券持有記錄
               $messageCode = '010922003';
               throw new \Exception($messageCode);
            }
            if(!ShopServiceQueAbandom::Update_ShopServiceQueQ($inputData['ssqq_id'],$inputData['ssqq_abandomreason'])) {
               throw new \Exception($messageCode);
            }
            if(!\App\Models\ICR_ShopServiceQue_q::Query_QueueCount($querydata[0]['sd_id'], $querydata[0]['ssqd_id'], $quecount, $serviced)) {
               throw new \Exception($messageCode);
            }
            if (!ShopServiceQueAbandom::SendMessageToShop($querydata[0]['sd_id'], $querydata[0]['ssqd_title'], $serviced)) {
               throw new \Exception($messageCode);
            }
            
            $messageCode = '010922000';
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
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'ssqq_id', 36, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'ssqq_abandomreason', 1, false, false)) {
            return false;
        }
        return true;
    }
    /**
     * 更新ShopServiceQ資料
     * @param type $ssqq_id
     * @param type $ssqq_abandomreason
     * @return boolean
     */
    private function Update_ShopServiceQueQ($ssqq_id, $ssqq_abandomreason){
        try {
            $modifydata['ssqq_id'] = $ssqq_id;
            $modifydata['ssqq_usestatus'] = 3;
            $modifydata['ssqq_abandomreason'] = $ssqq_abandomreason;
            return \App\Models\ICR_ShopServiceQue_q::UpdateData($modifydata);
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
    
    /**
     * 通知商店，用戶取消排隊
     * @param type $sd_id
     * @param type $ssqq_title
     * @param type $unservicecount
     * @return boolean
     */
    function SendMessageToShop($sd_id, $ssqq_title, $unservicecount) {
       try {
         $querydata = \App\Models\ICR_SdmdBind::GetData_BySD_ID($sd_id,true);
         foreach($querydata as $rowdata) {
           if(!ShopServiceQueAbandom::Insert_MsLog($rowdata['smb_md_id'], $ssqq_title, $unservicecount)) { 
             return false;
           }
         }
       } catch(\Exception $e) {
           \App\Models\Errorlog::InsertData($e);
           return false;
       }
       return true;
    }
    
    /**
     * 新增用戶通知紀錄資料
     * @param type $md_id
     * @param type $ssqq_title
     * @param type $unservicecount
     * @return boolean
     */
    private function Insert_MsLog($md_id,$ssqd_title,$unservicecount) {
        try {
            $savadata['uml_type'] = 810;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = '用戶棄用'.$ssqd_title.'服務，退出排隊，目前排隊中總人數為'.$unservicecount.'人';
            //$savadata['uml_object'] = null;
            //$savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
            $appService = new \App\Services\AppService;
            return $appService->PostMessageLog($savadata);
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        } 
    }
}