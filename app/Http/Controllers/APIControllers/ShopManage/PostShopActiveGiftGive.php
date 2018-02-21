<?php

namespace App\Http\Controllers\APIControllers\ShopManage;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** post_shopactivegift_give	行銷活動贈與 * */ 
class PostShopActiveGiftGive {
   function postshopactivegiftgive() {
        $functionName = 'postshopactivegiftgive';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //輸入值
            if(!PostShopActiveGiftGive::CheckInput($inputData)){
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
            //檢查SignContent    
            if(!PostShopActiveGiftGive::CheckSignContent($inputData['salt_no'], $inputData['sign_content'], $inputData['shopactivecontent'], $messageCode)) {
                throw new \Exception($messageCode);
            }
            $ShopManage = new ShopManage;
            if ( ! $ShopManage->CheckMdId($inputData['shopactivecontent']['sapr_md_id'], $messageCode)) {
                throw new \Exception($messageCode);
            }
            
            if(!PostShopActiveGiftGive::GiveGift_Type($inputData['shopactivecontent'], $messageCode)) {
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
        if ($messageCode == '999999986' || $messageCode =='999999988') {
             Commontools::WriteExecuteLogGetId($functionName, $inputString, json_encode($resultArray), $messageCode ,$jio_id);
             $message = "errormessage:".$messageCode."，API:PostShopActiveGiftGive，jio_id:".$jio_id;
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
        /*if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'shopactivecontent', 0, false, false)) {
            return false;
        } */
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'salt_no', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sign_content', 0, false, false)) {
            return false;
        }
        
        return true;
    }
       
    /**
     * 檢查signcontent
     * @param type $md_id  
     * @param type $serno 
     * @param type $signContent 
     * @param type $userpay 
     * @param type $messageCode 
     * @return boolean 檢查結果
     */
    private function CheckSignContent($serno, $signContent, $shopactivecontent, &$messageCode) {
        try {
            $queryData = \App\Models\ICR_PassWordSalt_R::GetSaltBySerno($serno);
            if (is_null($queryData) || count($queryData) == 0 ) {
                $messageCode = '040100001';
                return false;
            } else if (count($queryData) > 1) {
                $messageCode = '999999986';
                return false;
            } else if ($signContent != Hash('sha256',  str_replace("\\",'',json_encode($shopactivecontent)).$queryData[0]['psr_salt'])) {
                $messageCode = '040101001';
                return false;
            }
            
            return true;
        } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);  
            return false;
        }
    } 
    
    /**
     * 確認活動贈與方案(活動?/紅利)
     * @param type $activecontent 
     * @param type $messageCode 
     * @return boolean 檢查結果
     */
    function GiveGift_Type($activecontent, &$messageCode) {
      try {
           if ($activecontent['sapr_gifttype'] == 0 ) {
              if ( !PostShopActiveGiftGive::CheckMemberAskCouponTimes($activecontent['sapr_gift_id'], $activecontent['sapr_md_id'], $messageCode)
                || !PostShopActiveGiftGive::Insert_ICRShopActivePresentR($activecontent, $sapr_id)
                || !PostShopActiveGiftGive::Insert_ShopCouponDataG($activecontent['sapr_gift_id'], $activecontent['sapr_md_id'])) {
                 if (is_null($messageCode)) {
                     $messageCode = '999999988'; }
                 return false;
              }
           } else if ($activecontent['sapr_gifttype'] == 1 ) {
              if ( !PostShopActiveGiftGive::CheckShopBounsGift($activecontent['sd_id'], $activecontent['sapr_gift_id'], $sbgi_itemamount, $messageCode)
                || !PostShopActiveGiftGive::Insert_ICRShopActivePresentR($activecontent, $sapr_id) ) {
                  if (is_null($messageCode)) {
                     $messageCode = '999999988'; }
                  return false;
              }
              $BounsData = \App\Models\ICR_ShopBonusStock::GetStockByMDID_COSTypeQ($activecontent['sapr_md_id'], $sbs_type,$activecontent['sd_id']);
              $memberBouns = $BounsData[0]['sbs_end'];
              if ( !Commontools::UpdateBounsAndModifyBounsRecord('0', '0', $activecontent['sapr_md_id'], $activecontent['sd_id'], $sapr_id, $sbgi_itemamount, $memberBouns) ) {
                  $messageCode = '999999988'; 
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
    /**
     * 檢查活動?索取張數限次，確認是否可以再索取
     * @param type $gift_id
     * @param type $sapr_md_id
     * @param type $messageCode 
     * @return boolean 檢查結果
     */
    private function CheckMemberAskCouponTimes($gift_id, $sapr_md_id, &$messageCode) {
      try {
           $queryData = \App\Models\ICR_ShopCouponData_m::GetData($gift_id);
           if (is_null($queryData) || count($queryData) == 0 ) {
              $messageCode = '010904001';
              return false;
           } else if ($queryData[0]['scm_member_limit'] > 0 ) {
              $memberAskCouponTimes = \App\Models\ICR_ShopCouponData_g::QueryMemberGetCount($gift_id, $sapr_md_id);
              if (!is_null($memberAskCouponTimes) || count($memberAskCouponTimes) != 0 ) {
                  if ($memberAskCouponTimes >= $queryData[0]['scm_member_limit'] ) {
                      $messageCode = '010905004';
                      return false;
                  }
              }
           } 
           return true;
      } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);  
           return false;
      }
    }
    /**
     * 檢查特約商紅利方案是否正確，並取得贈與紅利點數。
     * @param type $sd_id
     * @param type $sbgi_id
     * @param type $sbgi_itemamount
     * @param type $messageCode 
     * @return boolean 檢查結果
     */     
    private function CheckShopBounsGift($sd_id, $sbgi_id, &$sbgi_itemamount, &$messageCode) {
      try {
           $queryData = \App\Models\ICR_ShopBonus_GiftItem::GetEffectiveGiftItem($sd_id, $sbgi_id);
           if (is_null($queryData) || count($queryData) ==0 ) {
               $messageCode = '010904001';
               return false;
           }
           $sbgi_itemamount = $queryData[0]['sbgi_itemamount'];
           return true;
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return false;
      }
    }
    
    /**
     * 新增活動?贈與記錄
     * @param type $arrayData
     * @param type $sapr_id
     * @return boolean 檢查結果
     */     
    private function Insert_ICRShopActivePresentR($arrayData, &$sapr_id) {
      try {
          $sapr_id = Commontools::NewGUIDWithoutDash();
          $saveData = [
                         'sapr_id'       => $sapr_id,
                         'sd_id'         => $arrayData['sd_id'],
                         'sapr_activeid' => $arrayData['sapr_activeid'],
                         'sapr_gifttype' => $arrayData['sapr_gifttype'],
                         'sapr_gift_id'  => $arrayData['sapr_gift_id'],
                         'sapr_md_id'    => $arrayData['sapr_md_id'],
                      ];
                
           return \App\Models\ICR_ShopActive_Present_R::InsertData($saveData); 
      } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);  
         return false;
      }
    }
    
    /**
     * 新增活動?取用記錄
     * @param type $gift_id
     * @param type $spar_md_id
     * @return boolean 檢查結果
     */   
    private function Insert_ShopCouponDataG($gift_id, $spar_md_id) {
      try {
         $datenow = new \Datetime();
         $saveData = [
                        'scm_id'        => $gift_id,
                        'md_id'         => $spar_md_id,
                        'scg_getdate'   => $datenow-> format('Y-m-d H:i:s'),
                        'scg_usestatus' => 1,
                     ];
         return\App\Models\ICR_ShopCouponData_g::InsertGetCouponData($saveData, $scg_id) ;
         
      } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
         return false;
      }
    }
    
    
}