<?php

namespace App\Http\Controllers\APIControllers\ShopCoupon;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class ShopCouponGet {

     function shopcouponget() {
        $functionName = 'shopcouponget';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        $bankService = new \App\Services\BankService;
        try {
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!ShopCouponGet::CheckInput($inputData)) {
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
            if (!$memService->verify_memberseccode($md_id, $inputData['md_securitycode'],  $messageCode)) {
               throw new \Exception($messageCode);
            }
            //檢查活動券狀態
            if (!ShopCouponGet::CheckCouponStatus($md_id, $inputData['scm_id'], $sd_id, $coupon_providetype, $bonus_payamount, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //檢查活動券取用狀態
            if (!ShopCouponGet::CheckShopCouponLimit($inputData['scm_id'], $md_id, $messageCode)) {
                throw new \Exception($messageCode);
            }
            
            //取得活動券
            if (!ShopCouponGet::GetCoupon($inputData, $md_id, $scg_id)) {
                $messageCode = '999999999';
                throw new \Exception($messageCode);
            }
            //檢查禮點折抵
            if ($inputData['scg_gp_subtract_amount'] > 0 ) {
                if (ShopCouponGet::checkGiftPoint($inputData['scg_totalamount'], $inputData['scg_gp_subtract_amount'], $inputData['scg_subtract_price'], $md_id, $bankService, $messageCode)) {
                    $appService = new \App\Services\AppService;
                    $gpmr_point = $appService->modifyGiftPoint($md_id, 14, $scg_id, 1, false, $inputData['scg_gp_subtract_amount'] );
                    $resultData['gpmr_point'] = $gpmr_point;   
                } else {
                     throw new \Exception($messageCode);
                }
            }
             $resultData['scg_id'] = $scg_id;
            //檢查是否需預約
            if ( ! ShopCouponGet::CheckCouponReservation($inputData['scm_id'], $scm_reservationtag, $messageCode) ) {
                $messageCode = '999999999';
                throw new \Exception($messageCode);
            }
            if ($coupon_providetype == 1) {
                if ( !$bankService->modifyMemPmPoint($md_id, $sd_id, 4, $bonus_payamount, $inputData['scm_id'], 1,  null, false, $messageCode) ){
                    $messageCode = '999999999';
                    throw new \Exception($messageCode);
                }
            }
            $resultData['scm_reservationtag'] = $scm_reservationtag;
            $messageCode = '000000000';
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'md_securitycode', 0, false, false)) {
            return false;
        }


        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_paymentstatus', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_buyamount', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_totalamount', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_buyermessage', 250, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_buyername', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_identifier', 8, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_addr', 50, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_contact_phone', 10, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_contact_email', 50, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_tax_title', 30, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sharer_id', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_gp_subtract_amount', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scg_subtract_price', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'moc_id', 0, true, false)) {
            return false;
        }

        return true;
    }

    /**
     * 檢查會員認證狀態
     * @param type $md_id
     * @param type $messageCode
     * @return boolean
     */
    function CheckMemberStatus($md_id, &$messageCode) {

        try {
            $querydata = \App\Models\IsCarMemberData::GetData($md_id);

            if (count($querydata) == 0) {
                $messageCode = '999999999';
                return false;
            }
            if ($querydata[0]['ssd_onlinestatus'] != '2') {
                //010905001	為避免無效取用，請會員先完成FB登入綁定
                $messageCode = '010905001';
                return false;
            }
            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 檢查活動券狀態
     * @param type $scm_id
     * @param type $sd_id
     * @param type $messageCode
     * @return boolean
     */
    function CheckCouponStatus($md_id,$scm_id, &$sd_id, &$scm_coupon_providetype, &$scm_bonus_payamount, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_m::GetData($scm_id);

            if (count($querydata) == 0) {
                //010904001	查無商品項目，請確認後重發
                $messageCode = '010904001';
                return false;
            }
            if (strtotime('now') < strtotime($querydata[0]['scm_startdate'])) {
                //010904002	該活動尚未開始，請選用其他活動券
                $messageCode = '010904002';
                return false;
            }
            if (strtotime('now') > strtotime($querydata[0]['scm_enddate'])) {
                //010904003	該活動已截止，請選用其他活動券
                $messageCode = '010904003';
                \App\Models\ICR_ShopCouponData_m::UpdateData_PostStatus($scm_id, 3);
                return false;
            }
            if ($querydata[0]['scm_poststatus'] != 1) {
                //010904004	該活動已停刊，請選用其他活動券或稍後再試
                $messageCode = '010904004';
                //\App\Models\ICR_ShopCouponData_m::UpdateData_PostStatus($scm_id, 3);
                return false;
            }
            if ($querydata[0]['scm_coupon_providetype'] == 1) {
                $shopMemberData = \App\Models\IsCarUserBookmark::GetMemberBySD_ID($querydata[0]['sd_id']);
                $memberTrack = false;
                foreach($shopMemberData as $values) {
                    if ($values['md_id'] == $md_id) {
                        $memberTrack = true;
                        break;
                    }
                }
                if ($memberTrack == false) {
                    //用戶並無追蹤此商家，請先訂閱後，使用特點消費。
                    $messageCode = '010904005';
                    return false;
                }
                $bankService = new \App\Services\BankService;
                $bankService->getMemPmPointQuery(null, $md_id, $querydata[0]['sd_id'], 1, $pmPointData, $messageCode);
                if( $pmPointData['spmr_point'] < $querydata[0]['scm_bonus_payamount'] ) {
                    //此商品需用特點消費，用戶特點不足。
                    $messageCode = '010905006';
                    return false;
                }
            }
            $scm_bonus_payamount = $querydata[0]['scm_bonus_payamount'];
            $scm_coupon_providetype = $querydata[0]['scm_coupon_providetype'];
            $sd_id =  $querydata[0]['sd_id'];
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
        }
    }

    /**
     * 檢查活動券可索取次數
     * @param type $scm_id
     * @param string $messageCode
     * @return boolean
     */
    function CheckShopCouponLimit($scm_id, $md_id, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_m::GetData($scm_id);

            if (count($querydata) == 0) {
                //010904001	查無商品項目，請確認後重發
                $messageCode = '010904001';
                return false;
            }

            if ($querydata[0]['scm_member_limit'] <= 0) {
                return true;
            }
            $getcount = \App\Models\ICR_ShopCouponData_g::QueryMemberGetCount($scm_id, $md_id);

            if ($getcount >= $querydata[0]['scm_member_limit']) {
                //010905004	該券設有索取數限制，無法再索取
                $messageCode = '010905004';
                return false;
            }
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 取得活動券
     * @param type $scm_id
     * @param type $md_id
     * @param type $scg_id
     * @return boolean
     */
    function GetCoupon($arraydata, $md_id, &$scg_id) {
        try {

            $savedata['scm_id'] = $arraydata['scm_id'];
            $savedata['scg_paymentstatus'] = $arraydata['scg_paymentstatus'];
            $savedata['scg_buyamount'] = $arraydata['scg_buyamount'];
            $savedata['scg_totalamount'] = $arraydata['scg_totalamount'];
            $savedata['scg_buyermessage'] = $arraydata['scg_buyermessage'];
            $savedata['scg_buyername'] = $arraydata['scg_buyername'];
            $savedata['scg_identifier'] = $arraydata['scg_identifier'];
            $savedata['scg_addr'] = $arraydata['scg_addr'];
            $savedata['scg_contact_phone'] = $arraydata['scg_contact_phone'];
            $savedata['scg_contact_email'] = $arraydata['scg_contact_email'];
            $savedata['scg_tax_title'] = $arraydata['scg_tax_title'];
            
            $savedata['scg_subtract_price'] = $arraydata['scg_subtract_price'];
            $savedata['scg_gp_subtract_amount'] = $arraydata['scg_gp_subtract_amount'];
            $savedata['scg_subtract_totalamount'] = (int)$arraydata['scg_totalamount'] -  (int)$arraydata['scg_subtract_price'];
            $savedata['scg_gp_subtract_status'] = 1;
            
            $savedata['md_id'] = $md_id;
            $savedata['scg_getdate'] = date('Y-m-d H:i:s');
            $savedata['scg_usestatus'] = 1;
            $savedata['sharer_id'] = $arraydata['sharer_id'];
            $savedata['moc_id'] = $arraydata['moc_id'];

            if (!\App\Models\ICR_ShopCouponData_g::InsertGetCouponData($savedata, $scg_id)) {
                return false;
            }
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
        }
    }

    /**
     * 檢查活動券預約狀態
     * @param type $scm_id
     * @param type $messageCode
     * @return boolean
     */
     function CheckCouponReservation($scm_id, &$scm_reservationtag, &$messageCode) {
        try {

            $querydata = \App\Models\ICR_ShopCouponData_m::GetData($scm_id);
            if (count($querydata) == 0) {
                //010904001	查無商品項目，請確認後重發
                $messageCode = '010904001';
                $scm_reservationtag = null;
                return false;
            }
            //如果是商品，不用預約，故結束。
            if ($querydata[0]['scm_producttype'] == 2 ) {
               return true;
            }
            $scm_reservationtag = $querydata[0]['scm_reservationtag'];
            if ($querydata[0]['scm_reservationtag'] == 0) {
                $messageCode = '000000000';
                return true;
            }
            if ($querydata[0]['scm_reservationfulltag'] != 1) {
                //010905002	優惠券索取完成，請先預約後使用
                $messageCode = '010905002';
                return true;
            }
            //010905003	該券預約額滿，已排入候補，請隨時關注可預約時間
            $messageCode = '010905003';
            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }
    
    /**
     * 
     * 
     * 
     * 
     * 
     */
    function  checkGiftPoint($scg_totalamount, $scg_gp_subtract_amount, $scg_subtract_price, $md_id, $bankService, &$messageCode) {
        try {
            $gpExchangeCashRateData = \App\Models\ICR_SystemParameter::getGPExchangeCashRate();
            $should_be_subtract_price = floor($scg_gp_subtract_amount/$gpExchangeCashRateData[0]['sp_paramatervalue']);
            if ($should_be_subtract_price != $scg_subtract_price) {
                //禮點折抵金額，驗證失敗。
                $messageCode = '010905007';
                return false;
            }

            $gpMaxUseRateData = \App\Models\ICR_SystemParameter::getGPMaxUseRate();
            $should_be_max_rate = $scg_totalamount * ($gpMaxUseRateData[0]['sp_paramatervalue'] / 100);
            if ($scg_subtract_price > $should_be_max_rate) {
                //禮點折抵金額，超出最大折抵範圍
                $messageCode = '010905008';
                return false;
            }

            $pointData = null;
            $bankService->getMemGiftPointQuery(null, $md_id, 1, $pointData, $messageCode);
            if ($scg_gp_subtract_amount > $pointData['gpmr_point']) {
                //會員禮點不足，無法使用點數，折抵金額
                $messageCode = '010905009';
                return false;
            }
            
           return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }
    
    

}
