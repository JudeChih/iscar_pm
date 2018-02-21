<?php

namespace App\Http\Controllers\APIControllers\ShopPush;
use DB;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
define('ShopData_FTP_Img_Path', config('global.ShopData_FTP_Img_Path'));
/**push_shopad2member ¯S¬ù°Ó·|­ûÀu´f°T®§±À¼½ **/
class PushShopAd2Member {
   function pushshopad2member() {
        $functionName = 'pushshopad2member';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            if (is_null($inputData)) {
              $messageCode = '999999994';
               throw new \Exception($messageCode);
            }
            if(!PushShopAd2Member::CheckInput($inputData)){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
           
            $memService = new \App\Services\MemberService;
            if ( !$memService->checkModuleAccount($inputData['modacc'], $inputData['modvrf'], $messageCode)) {
              
              $messageCode = '999999961';
              throw new \Exception($messageCode);
            }
            if (!$memService->checkServiceAccessToken($inputData['sat'], $md_id, $messageCode)) {
             
               $messageCode = '999999962';
               throw new \Exception($messageCode);
            }
            if (!PushShopAd2Member::CheckSignContent($inputData['psr_no'], $inputData['sign_content'], $inputData['push_content'], $messageCode )) {
              //$resultData['sapm_pushcontent']  = $inputData['push_content']['sapm_pushcontent'];
               throw new \Exception($messageCode);
            }
             
            if (!Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['push_content']['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            $ShopPush = new ShopPush;
            $pushFeeData = $ShopPush->QueryShopPushFee($inputData['push_content']['sapm_feeitem'], $servicetype = null , $messageCode);
            if (is_null($pushFeeData) || count($pushFeeData) == 0 ) {
                throw new \Exception($e);
            }
            if (!PushShopAd2Member::CountPushPriceAndCheckShopCos(explode(",",$inputData['push_content']['push_object']), $pushFeeData[0]['ssfi_itemprice'], $md_id,  $inputData['sat'], $membercount, $push_price, $cos_end)) {
               $messageCode = '999999972';
               throw new \Exception($messageCode);
            }
            if (!PushShopAd2Member::CkeckDesignateFunction($inputData['push_content']['sapm_designatefunction'])) {
               $messageCode = '999999989';
               throw new \Exception($messageCode);
            }
            if (!PushShopAd2Member::InsertShopadpushM($inputData['push_content'], $md_id, $membercount, $push_price, $sapm_id)) {
               throw new \Exception($messageCode);
            }
            if (!PushShopAd2Member::PushToMember($inputData['push_content'], $sapm_id, $inputData['sat'])  ) {
               throw new \Exception($messageCode);
            }
            $bankService = new \App\Services\BankService;
            /*if (!$bankService->memPointQuery($inputData['sat'], $pushFeeData[0]['ssfi_pointforconsume'], $pointData, $messageCode)) {
              throw new \Exception($messageCode);
            }*/
           // \App\Models\ErrorLog::InsertLog("md_id:".$md_id);
            if ( !$bankService->modifyMemBuyPoint($md_id, 1 , $push_price, $inputData['push_content']['sd_id'], 1, "PushShopAd2Member_pm", false, $messageCode)) {
              throw new \Exception($messageCode);
            }
             $resultData['sapm_pushfee'] = $push_price;
            $messageCode ='000000000';
         } catch(\Exception $e){
            if (is_null($messageCode)) {
               $messageCode = '999999999';
               \App\Models\ErrorLog::InsertData($e);
            }
        }
        $resultArray = Commontools::ResultProcess($messageCode, $resultData);
        if ( $messageCode =='999999974') {
            Commontools::WriteExecuteLogGetId($functionName, $inputString, json_encode($resultArray), $messageCode ,$jio_id);
            $message = "errormessage:$messageCode¡API:PushShopAd2Member¡Ajio_id:$jio_id¡Aspam_id:$spam_id";
            //Commontools::PushNotificationToManagers($message);
        } else {
            Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);   
        }
        $result = [$functionName . 'result' => $resultArray];
        return $result;
   }
    /**
     * 
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'psr_no', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sign_content', 0, false, false)) {
            return false;
        }
        /**if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'push_content', 0, false, false)) {
            return false;
        }**/
        return true;
    }


    private function CheckSignContent($psr_no , $signContent, $push_content, &$messageCode ) {
       try {
            $memService = new \App\Services\MemberService;
            if (!$memService-> query_salt($salt_no, $salt)) {
              $messageCode = '999999999';
              return false;
            }
            $string_push_content = str_replace('\/','/',json_encode($push_content, JSON_UNESCAPED_UNICODE));
            if ( is_null($salt)) {
               $messageCode = '999999976';
               return false;
            }  else if ( $signContent != Hash('sha256',  $string_push_content. $salt) ) {
               $messageCode = '999999975';
               return false;
            }
            return true;
       } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }

    private function CountPushPriceAndCheckShopCos($push_member, $itemprice, $md_id, $sat, &$member_count, &$push_price, &$cos_end) {
       try {
            $member_count = count($push_member);
            (int)$push_price = $member_count * $itemprice;
            $bankService = new \App\Services\BankService;
            $bankService->getMemBuyPointQuery( null, $md_id, 1, $pointData, $ms);
            // $cosData = \App\Models\IsCarCoinStock::GetDataByMdId_Type($md_id, 0);
            //\App\Models\ErrorLog::InsertLog("bpmr_point:".$pointData['bpmr_point']);
            $bpmr_point = $pointData['bpmr_point'];
            if ($bpmr_point < $push_price) {
               return false;
            }
            $cos_end =  $bpmr_point ;
            return true;
       } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }

    public function CkeckDesignateFunction($designatefunction) {
       try {
            if ($designatefunction != 0 ) {
               return false;
            }
            return true;
       } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }

    private function InsertShopadpushM($arrayData, $md_id, $membercount, $push_price, &$sapm_id) {
       try {
            $sapm_id = Commontools::NewGUIDWithoutDash();
            $saveData = [
                         'sapm_id'                         => $sapm_id,
                         'sd_id'                               => $arrayData['sd_id'],
                         'md_id'                              => $md_id,
                         'sapm_objecttype'            => 0,
                         'sapm_pushpic'                => $arrayData['sapm_pushpic'],
                         'sapm_pushcontent'         => $arrayData['sapm_pushcontent'],
                         'sapm_designatefunction' => $arrayData['sapm_designatefunction'],
                         'sapm_objectamount'       => $membercount,
                         'sapm_pushfee'                => $push_price,
                         'sapm_feeitem'                => $arrayData['sapm_feeitem'],
                         'sapm_pushresult'           => 1  ];
            return \App\Models\ICR_ShopAdpush_M::InsertData($saveData);
       } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }

    function PushToMember($arrayData, $spam_id, $sat) {
       try {
           if ($arrayData['target']==1 || $arrayData['target']==2) {
               $uml_type = '1101';
               $uml_object =  '{"sd_id":"'.$arrayData['sd_id'].'"}' ;
           } else if ($arrayData['target']== 3 ) {
               $uml_type = '1115';
               $uml_object =  '{"scm_id":"'.$arrayData['scm_id'].'"}' ;
           } else if ($arrayData['target']== 4 ) {
               $uml_type = '1116';
               $uml_object =  '{"sd_id":"'.$arrayData['sd_id'].'"}' ;
           }
            $message = $arrayData['sapm_pushcontent'];
            $title = $arrayData['title'];
            $target = $arrayData['target'];
            $iscar_push = '{"target":"'. $target.'","id_1":"'.$arrayData['sd_id'].'","id_2":"'.$arrayData['scm_id'].'"}';
            $pic = $arrayData['sapm_pushpic'];
            $not_has_error = true;
            $memService = new \App\Services\MemberService;
            foreach ( explode(",", $arrayData['push_object']) as $md_id) {
               $uml_id = Commontools::NewGUIDWithoutDash();
               if ( !$memService->push_notification($sat, array($md_id), $message, $title, $iscar_push, $target) ) {
                  $pushtag = 0;
               } else {
                  $pushtag = 1;
               }
               DB::beginTransaction();
               if (!PushShopAd2Member::Insert_MsLog($spam_id, $uml_id, $uml_type , $md_id, $message, $uml_object, $pic) ||
                   !PushShopAd2Member::Insert_PushadD($spam_id, $md_id, $uml_id, $pushtag)) {
                  DB::rollBack();
                  PushShopAd2Member::Insert_PushadD($spam_id, $md_id, $uml_id, 2);
                  $not_has_error = false;
               }
               DB::commit();
            }
            if ( $not_has_error == false ) {
               $message = "errormessage:999999988 spam_id:$spam_id";
               //Commontools::PushNotificationToManagers($message);
            }
            return true;
       } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }

    private function Insert_MsLog($spam_id, $id, $type, $md_id, $message, $uml_object, $pic) {
       try {
            $appService = new \App\Services\AppService;
            $savadata = [
                          'uml_id'           => $id,
                          'uml_type'        => $type,
                          'md_id'            => $md_id,
                          'uml_message' => $message,
                          'uml_object'      => $uml_object,
                          'uml_pic'         => ShopData_FTP_Img_Path.$pic,
                          'uml_status'     => 0 ,
                          'uml_releationid' => $spam_id ];
            return  $appService->PostMessageLog($savadata);
       } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }

    private function Insert_PushadD($sapm_id, $md_id, $uml_id, $sapd_pushtag) {
       try {
            $savedata = [ 
                         'sapm_id'               => $sapm_id,
                         'sapd_object_mdid' => $md_id,
                         'uml_id'                  => $uml_id,
                         'sapd_pushtag'       => $sapd_pushtag ];
            return \App\Models\ICR_ShopAdpush_D::InsertData($savedata);
       } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }
}