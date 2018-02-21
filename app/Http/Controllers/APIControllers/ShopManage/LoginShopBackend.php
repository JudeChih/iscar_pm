<?php

namespace App\Http\Controllers\APIControllers\ShopManage;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
 /** login_shop_backend	¯S¬ù°Ó«á¥xµn¤J **/
class LoginShopBackend {
   function loginshopbackend() {
        $functionName = 'loginshopbackend';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //¿é¤J­È
            if(!LoginShopBackend::CheckInput($inputData)){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //ÀË¬dJWTÅç¥¿¡C
            if( !Commontools::JWTokenVerification($inputData['api_token'], $messageCode)) {
               throw new \Exception($messageCode);
            }
            if(!LoginShopBackend::CheckIdVerify ($inputData['shop_backend_Id'], $inputData['idverify'], $md_id, $mur_id, $messageCode)){
               throw new \Exception($messageCode);
            }
            if(!LoginShopBackend::CheckExistAndCreate_MemberMobileLink (0, $md_id, $mur_id)){
               throw new \Exception($messageCode);
            }
            if(!LoginShopBackend::CreateOrUpdate_ServiceAccessToken(0, $md_id, $mur_id, $servicetoken, $messageCode)){
               throw new \Exception($messageCode);
            }
            if(!LoginShopBackend:: CreateResultData($servicetoken, $mur_id, $resultData)){
               throw new \Exception($messageCode);
            }
            $messageCode ='010101000';
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
     * ÀË¬d¿é¤J­È¬O§_¥¿½T
     * @param type $value
     * @return boolean
     */
    function CheckInput(&$value) {
        if ($value == null) {
            return false;
        }
        if (!Commontools::CheckRequestArrayValue($value, 'api_token', 0, false, false)) {
            return false;
        }
        if (!Commontools::CheckRequestArrayValue($value, 'shop_backend_Id', 50, false, false)) {
            return false;
        }  
        if (!Commontools::CheckRequestArrayValue($value, 'idverify', 0, false, false)) {
            return false;
        } 
        return true;
    }
    
    function CheckIdVerify ($backend_id, $idverify, &$md_id, &$mur_id, &$messageCode) {
      try {
         $queryData = \App\Models\ICR_ShopBackend_Register::GetDataByBackendId ($backend_id);
         if (is_null($queryData) || count($queryData) == 0 || count($queryData) > 1 ) {
             $messageCode = '010101001';
             return false;
         }
         $md_id = $queryData[0]['md_id'];
         $shaMdBackend_Id = Hash('sha256', $md_id.$backend_id);
         if ( $idverify != $shaMdBackend_Id ) {
             $messageCode = '010101001';
             return false;
         } 
         $mur_id = 'shopbackend00000000';
         return true; 
      } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
         return false;
      }
    }
    
    /**
     * ÀË¬d¡uIsCarMemberMobileLink¡v¬O§_¤w¦s¦b¡A­Y¤£¦b«h«Ø¥ß
     * @param type $mml_apptype
     * @param type $md_id
     * @param type $mur_id
     * @return boolean
     */
    function CheckExistAndCreate_MemberMobileLink ($mml_apptype, $md_id, $mur_id) {
       try {
          $queryData = \App\Models\IsCarMemberMobileLink::GetDataByAppType_MURID($mml_apptype, $md_id, $mur_id);
          if (is_null($queryData) || count($queryData) == 0 ) {
              return \App\Models\IsCarMemberMobileLink::InsertData(array('mml_apptype' => $mml_apptype,'mur_id' => $mur_id, 'md_id' => $md_id), $mml_serno);
          } 
          return true;
       } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
         return false;
       }                                      
    }
    
     /**
     * ÀË¬d¡uIsCarServiceAccessToken¡v¬O§_¦s¦b¡A­Y¤£¦s¦b«h«Ø¥ß¡A­Y¦s¦b«hÀË¬d¬O§_¤w¹L´Á
     * @param type $sat_apptype
     * @param type $md_id
     * @param type $mur_id
     * @param type $servicetoken
     * @return boolean
     */
    function CreateOrUpdate_ServiceAccessToken($sat_apptype, $md_id, $mur_id, &$servicetoken, &$messageCode) {

        if ( $md_id == null || mb_strlen($md_id) == 0 || $mur_id == null || mb_strlen($mur_id) == 0) {
            return false;
        }
        //ÀË¬d¡uIsCarServiceAccessToken¡v¸ê®Æ
        $selectdata = \App\Models\IsCarServiceAccessToken::GetDataByMDID_MURID($sat_apptype, $md_id, $mur_id);
        if (count($selectdata) != 0) {
            //¦³¸ê®Æ
            $expiredate = new \DateTime($selectdata[0]['sat_expiredate']);
            $datenow = new \DateTime('now');
            if ($expiredate > $datenow) {
                //¥¼¹L´Á
                $servicetoken = $selectdata[0]['sat_token'];
                //§ó·s¡A©µªø´Á­­
                $selectdata[0]['sat_expiredate'] = $datenow->add(new \DateInterval('P1D'))->format('Y-m-d H:i:s');
                if (\App\Models\IsCarServiceAccessToken::UpdateData($selectdata[0]) ) {
                   return true;
                } else {
                   return false;
                }
            } else {
                //¤w¹L´Á¡A§ó·s¸ê®Æ¬°¹L´Á
                $selectdata[0]['sat_effective'] = '2';
                if (!\App\Models\IsCarServiceAccessToken::UpdateData($selectdata[0])) {
                    return false;
                }
            }
        } 
        if (\App\Models\IsCarServiceAccessToken::InsertData(array('sat_apptype' => $sat_apptype, 'md_id' => $md_id, 'mur_id' => $mur_id), $sat_serno)) {
            $querydata = \App\Models\IsCarServiceAccessToken::GetData($sat_serno);
            if (count($querydata) == 0) {
                return false;
            }
            $servicetoken = $querydata[0]['sat_token'];
            return true;
        }
        return false;
    }
    
    
    function CreateResultData ($servicetoken, $mur_id, &$resultData) {
      try {
           $resultData['servicetoken'] =  $servicetoken;
           $resultData['mur_id'] = $mur_id;
           return true;
      } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
         return false;
      }
    }

 
}