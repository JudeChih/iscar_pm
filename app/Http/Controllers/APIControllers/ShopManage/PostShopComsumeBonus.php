<?php

namespace App\Http\Controllers\APIControllers\ShopManage;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** post_shopcomsume_bonus	現場消費紅利贈與 **/
class PostShopComsumeBonus {
   function postshopcomsumebonus() {
        $functionName = 'postshopcomsumebonus';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //輸入值
            if(!PostShopComsumeBonus::CheckInput($inputData)){
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
            if (!Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            $ShopManage = new ShopManage;
            if ( !$ShopManage->CheckMdId($inputData['scbr_md_id'], $messageCode)) {
                throw new \Exception($messageCode);
            }
            if (!PostShopComsumeBonus::InsertShopComsumeBonusR($scbr_id, $md_id, $inputData)) {
                throw new \Exception($messageCode);
            }
            $BounsData = \App\Models\ICR_ShopBonusStock::GetStockByMDID_COSType($inputData['scbr_md_id'], 0);
            $memberBouns = (is_null($BounsData[0]['sbs_end'])) ? 0 : $BounsData[0]['sbs_end'];
            if (!Commontools::UpdateBounsAndModifyBounsRecord('2', '0', $inputData['scbr_md_id'], $inputData['sd_id'], $scbr_id, $inputData['scbr_bonusgive'], $memberBouns)) {
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
        if (!Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        } 
        if (!Commontools::CheckRequestArrayValue($value, 'scbr_md_id', 32, false, false)) {
            return false;
        } 
        if (!Commontools::CheckRequestArrayValue($value, 'scbr_comsumeamount', 8, false, false)) {
            return false;
        } 
        if (!Commontools::CheckRequestArrayValue($value, 'scbr_bonusgive', 8, false, false)) {
            return false;
        } 
        return true;
    }
    /**
     * 建立特約商現場消費記錄
     * @param type $scbr_id
     * @param type $md_id
     * @param type $arrayData
     * @return boolean
     */
    private function InsertShopComsumeBonusR(&$scbr_id, $md_id, $arrayData) {
       try {
         $scbr_id = \App\Library\Commontools::NewGUIDWithoutDash();
         $saveData = [
                       'scbr_id'            => $scbr_id,
                       'sd_id'              => $arrayData['sd_id'],
                       'scbr_clerkid'       => $md_id,
                       'scbr_md_id'         => $arrayData['scbr_md_id'],
                       'scbr_comsumeamount' => $arrayData['scbr_comsumeamount'],
                       'scbr_bonusgive'     => $arrayData['scbr_bonusgive'],
                     ];
          return \App\Models\ICR_ShopComsumeBonus_R::InsertData($saveData);
       } catch (\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
       }
    }
       
 
}