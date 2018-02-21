<?php

namespace App\Http\Controllers\APIControllers\ShopCouponAdm;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class QueryShopCouponContent {

    /**
     * 獲取活動卷的詳細資訊
     * @param [string] $modacc [模組帳號]
     * @param [string] $modvrf [模組驗證碼]
     * @param [string] $scm_id [優惠卷編號]
     */
    function queryshopcouponcontent() {
        $functionName = 'queryshopcouponcontent';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;

        try {
            //檢查輸入值
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            if (!QueryShopCouponContent::CheckInput($inputData)) {
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

            //抓取該商品的詳細內容資訊
            $resultData['shopcoupon'] = QueryShopCouponContent::GetShopCouponData($inputData['scm_id']);

            if (!is_null($messageCode) && strlen($messageCode) != 0) {
                throw new \Exception($messageCode);
            }

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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_id', 32, false, false)) {
            return false;
        }

        return true;
    }

    /**
     * 獲取該商品的詳細資訊
     * @param [type] $scm_id [description]
     */
    function GetShopCouponData($scm_id) {
        try {
            if ($scm_id == null) {
                return null;
            }
            $coupondata = \App\Models\ICR_ShopCouponData_m::GetData($scm_id);

            if (count($coupondata) == 0) {
                return null;
            }
            return QueryShopCouponContent::TransToResultData($coupondata);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 編輯要回傳的優惠卷詳細資訊
     * @param [type] $coupondata [優惠絹詳細資訊]
     */
    function TransToResultData($coupondata) {

        if (count($coupondata) == 0) {
            return null;
        }
        $DataCount =  \App\Models\ICR_ShopCouponData_g::QueryGetCount($coupondata[0]['scm_id']);
        $inventory = $coupondata[0]['scm_member_limit'] - $DataCount;

        $resultdata = ['scm_id' => $coupondata[0]['scm_id']
            , 'sd_id' => $coupondata[0]['sd_id']
            , 'scm_title' => $coupondata[0]['scm_title']
            , 'scm_fulldescript' => $coupondata[0]['scm_fulldescript']
            , 'scm_category' => $coupondata[0]['scm_category']
            , 'scm_mainpic' => $coupondata[0]['scm_mainpic']
            , 'scm_activepics' => $coupondata[0]['scm_activepics']
            , 'scm_price' => $coupondata[0]['scm_price']
            , 'scm_startdate' => $coupondata[0]['scm_startdate']
            , 'scm_enddate' => $coupondata[0]['scm_enddate']
            , 'scm_reservationtag' => $coupondata[0]['scm_reservationtag']
            , 'scm_poststatus' => $coupondata[0]['scm_poststatus']
            , 'scm_member_limit' => $coupondata[0]['scm_member_limit']
            , 'sd_shopname' => $coupondata[0]['sd_shopname']
            , 'sd_shopaddress' => $coupondata[0]['sd_shopaddress']
            , 'sd_shoptel' => $coupondata[0]['sd_shoptel']
            , 'sd_lat' => $coupondata[0]['sd_lat']
            , 'sd_lng' => $coupondata[0]['sd_lng']
            , 'sd_weeklystart' => $coupondata[0]['sd_weeklystart']
            , 'sd_weeklyend' => $coupondata[0]['sd_weeklyend']
            , 'sd_dailystart' => $coupondata[0]['sd_dailystart']
            , 'sd_dailyend' => $coupondata[0]['sd_dailyend']
            , 'scm_reservationfulltag' => $coupondata[0]['scm_reservationfulltag']
            , 'scm_workhour' => $coupondata[0]['scm_workhour']
            , 'scm_preparehour' => $coupondata[0]['scm_preparehour']
            , 'scm_includeweekend' => $coupondata[0]['scm_includeweekend']
            , 'scm_reservationavailable' => $coupondata[0]['scm_reservationavailable']
            , 'scm_balanceno' => $coupondata[0]['scm_balanceno']
            , 'scm_dailystart' => $coupondata[0]['scm_dailystart']
            , 'scm_dailyend' => $coupondata[0]['scm_dailyend']
            , 'scm_producttype' => $coupondata[0]['scm_producttype']
            , 'scm_advancedescribe' => $coupondata[0]['scm_advancedescribe']
            , 'sd_introtext' => $coupondata[0]['sd_introtext']
            , 'sd_advancedata' => $coupondata[0]['sd_advancedata']
            ,'inventory' => $inventory
        ];

        return $resultdata;
    }

}
