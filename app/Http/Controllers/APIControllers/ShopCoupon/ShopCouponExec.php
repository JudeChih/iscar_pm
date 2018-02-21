<?php

namespace App\Http\Controllers\APIControllers\ShopCoupon;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
define('ShopData_FTP_Img_Path', config('global.ShopData_FTP_Img_Path'));
/** shopcouponexec	商家執行優惠券內容 * */
class ShopCouponExec {

    function shopcouponexec() {
        $functionName = 'shopcouponexec';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;

        try {
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!ShopCouponExec::CheckInput($inputData)) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            $md_id = null;
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
            if (!\App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //檢查活動券有效性
            if (!ShopCouponExec::CheckData_CouponData_g($inputData['scm_id'], $inputData['scg_id'], $inputData['sd_id'], $coupondata, $messageCode)) {
              //  throw new \Exception($messageCode);
            }

            //檢查「coupon_operation」
            if ($inputData['coupon_operation'] != 1) {
                //010911001	錯誤的作業內容，請重新選擇
                $messageCode = '010911001';
                throw new \Exception($messageCode);
            }

            //更新使用記錄
            if (!ShopCouponExec::UpdateCouponData_Used($inputData['scg_id'], $inputData['sat'], $coupondata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //更新使用者通知記錄 MsLog
            if (!ShopCouponExec::ShopCouponAfterUse($inputData['scm_id'], $inputData['scg_id'], $memService, $inputData['sat'])) {
                throw new \Exception($messageCode);
            }
            
            
             //購買 商品 贈送禮點
            $querydata = \App\Models\ICR_ShopCouponData_g::getMdidByScgid($inputData['scg_id']);
            $scgData = \App\Models\ICR_ShopCouponData_g::GetData($inputData['scg_id']);
            $appService = new \App\Services\AppService;
            $logistics = new \App\Http\Controllers\APIControllers\Logistics\Logistics;
            $resultData['gpmr_point'] = null;
            $messageCode =$appService->GetGiftPointDayLimit($querydata[0]['md_id'], 10);
            $systemData = \App\Models\ICR_SystemParameter::getGPExchangeCashRate();
            $giftPointAmount = $appService->getGiftPointsAmount( 10 );
            $addgp = floor(($querydata[0]['scg_subtract_totalamount'])*($giftPointAmount/ 100)* $systemData[0]['sp_paramatervalue']);
            $gpmr_point = $appService->modifyGiftPoint($querydata[0]['md_id'], 10, $inputData['scg_id'], 1, true, $addgp );
            $logistics->Insert_MsLog_1114( $querydata[0]['md_id'],  $scgData[0]['scm_title'] ,$addgp);
            $resultData['gpmr_point'] = $gpmr_point;   
            if ($messageCode != '000000000') {
                throw new \Exception($messageCode);
            }  else {
                $messageCode = null;
            }
            
            //判斷 商品 有無贈送特點
           if ($querydata[0]['scm_bonus_giveafteruse'] == 1){
                $bankService = new \App\Services\BankService;
                $bankService->modifyMemPmPoint($querydata[0]['md_id'], $querydata[0]['sd_id'], 2, $querydata[0]['scm_bonus_giveamount'],  $inputData['scg_id'], 1, null, true, $messageCode);
            }
             //贈與分享者禮點
            //$resultData['sharer_id'] = $scgData[0]['sharer_id'];
            if (!is_null($scgData[0]['sharer_id']) || strlen($scgData[0]['sharer_id']) != 0) {
                 //$appService = new \App\Services\AppService;
                $giftpoint =  $appService->getGiftPointsAmount( 13);
                $addgiftpoint =  floor($scgData[0]['scg_subtract_totalamount'] * ($giftpoint / 100) * $systemData[0]['sp_paramatervalue']);
                //$resultData['addgiftpoint'] = $addgiftpoint;
                if ($addgiftpoint >= 1) {
                    $appService->modifyGiftPoint($scgData[0]['sharer_id'], 13, $scgData[0]['scg_id'], 1, true, ($addgiftpoint));
                    $target = 1;
                    $iscar_push = '{"target" :"'.$target.'","id_1":"","id_2":""}';
                    $memService->push_notification($inputData['sat'], array($scgData[0]['sharer_id']), null, null, $iscar_push, $target);
                    $logistics->Insert_MsLog_1111( $scgData[0]['sharer_id'],  $scgData[0]['scm_title'] ,$addgiftpoint);
                }
            }
            $resultData['scm_balanceno'] = $coupondata[0]['scm_balanceno'];
            //010911000	服務完成，請提示客戶該券已用畢
            $messageCode = '010911000';
        } catch (\Exception $e) {
            if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($e);
            }
        }

        //回傳值
        $resultArray = Commontools::ResultProcess($messageCode, $resultData);
        Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray];

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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }

        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_id', 36, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_id', 36, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'coupon_operation', 1, false, false)) {
            return false;
        }


        return true;
    }

    /**
     * 檢查活動券取用記錄
     * @param type $scm_id
     * @param type $scg_id
     * @param type $sd_id
     * @param type $messageCode
     * @return boolean
     */
    function CheckData_CouponData_g($scm_id, $scg_id, $sd_id, &$coupondata, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_m::GetData_BySCMID_SCGID($scm_id, $scg_id);
           
            if (count($querydata) != 1) {
                //010910001	查無該券編號，請提醒消費者重新索取．
                $messageCode = '010910001';
                return false;
            }


            if ($querydata[0]['sd_id'] != $sd_id) {
                //010910009	該券非貴司發行，請告知客戶前往正確商家使用
                $messageCode = '010910009';
                return false;
            }

            if (strtotime(date('Y/m/d')) > strtotime($querydata[0]['scm_enddate'])) {
                ShopCouponScan::UpdateCouponDataExpired($scg_id);
                //010910002	該券活動已逾期，不可再使用
                $messageCode = '010910002';
                return false;
            }
            if ($querydata[0]['scg_usestatus'] == '5') {
                $coupondata = $querydata;
                return true;
            }

            //檢查活動券「可用狀態」
             if ($querydata[0]['scg_usestatus'] == '1') {
                //010910010 用戶已取該卷，但尚未付款
                $messageCode = '010910010';
                return false;
            }
            if ($querydata[0]['scg_usestatus'] == '2') {
                //010910004	該券已使用完畢，請提醒消費者進行狀態更新
                $messageCode = '010910004';
                return false;
            }
            if ($querydata[0]['scg_usestatus'] == '3') {
                //010910005	該券已放棄使用，請提醒消費者進行狀態更新
                $messageCode = '010910005';
                return false;
            }
            if ($querydata[0]['scg_usestatus'] == '4') {
                //010910002	該券活動已逾期，不可再使用
                $messageCode = '010910002';
                return false;
            }
            //010910008	該券取用記錄有誤，請告知客戶重新取用活動券
            $messageCode = '010910008';
            return false;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 更新活動券已使用
     * @param type $scg_id
     * @param type $servicetoken
     * @return boolean
     */
    private function UpdateCouponData_Used($scg_id, $servicetoken, $coupondata, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_g::GetData($scg_id);

            if (count($querydata) == 0) {
                return false;
            }

            $querydata[0]['scg_usestatus'] = '2';
            $querydata[0]['scg_receiver'] = $servicetoken;
            $querydata[0]['scg_usedate'] = date('Y-m-d H:i:s');

            if ($coupondata[0]['scm_reservationtag'] == 1) {
                $querydata[0]['scg_receiver'] = $servicetoken;
                $interval = (new \DateTime('now'))->diff(new \DateTime($coupondata[0]['scr_rvdate'] . ' ' . $coupondata[0]['scr_rvtime']));
                if ($interval->invert == '0' && ( $interval->y > 0 || $interval->m > 0 || $interval->d > 0 || $interval->h > 1)) {
                    $querydata[0]['scg_reservationstatus'] = '2';
                } else {
                    $querydata[0]['scg_reservationstatus'] = '1';
                }
            }

            return \App\Models\ICR_ShopCouponData_g::UpdateData($querydata[0]);
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }
    
      /**
     * 活動券已用畢，新增通知訊息至iscarusermessagelog
     * @param type $scm_id
     * @param type $scg_id
     * @return boolean
     */
     private function ShopCouponAfterUse($scm_id ,$scg_id, $memService, $sat) {
         try {
            $result = \App\Models\ICR_ShopCouponData_g::GetData_IcrShopCouponDataM($scm_id, $scg_id);

            $target = 1;
            $iscar_push = '{"target" :"'. $target. '","id_1":"","id_2":""}';
            if(!$memService->push_notification( $sat, array($result[0]['md_id']) , null, null, $iscar_push, $target ) ) {
                throw new \Exception($messageCode);
            }
            return ShopCouponExec::Create_MsLog_Comment($result);  
         } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
     }
       /**
     * 建立「使用者通知記錄」-評論通知
     * @param type $values
     * @return boolean
     */
     private function Create_MsLog_Comment($values) {
          try {
            $savadata['uml_type'] = 702;
            $savadata['md_id'] = $values[0]['md_id'];;
            $savadata['uml_message'] = '活動券使用完畢，請填寫使用評價。';
            $savadata['uml_object'] = '{"Event_type" : "0" ,"Event_id" : "' . $values[0]['scg_id'] . '"} ';
            $savadata['uml_pic'] = ShopData_FTP_Img_Path.$values[0]['scm_mainpic'];;
            $savadata['uml_status'] = 0;
            $appService = new \App\Services\AppService;
            return $appService->PostMessageLog($savadata);
        } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
            return false;
        }
     }



}
