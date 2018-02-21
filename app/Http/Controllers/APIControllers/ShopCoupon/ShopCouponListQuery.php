<?php

namespace App\Http\Controllers\APIControllers\ShopCoupon;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class ShopCouponListQuery {


   /** shopcouponlistquery	商家優惠活動券列表查詢 * */
    function shopcouponlistquery() {
        $functionName = 'shopcouponlistquery';
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
            //檢查身份模組驗證
            $memService = new \App\Services\MemberService;
            if ( !$memService->checkModuleAccount($inputData['modacc'], $inputData['modvrf'], $messageCode)) {
              //模組身份驗證失敗
              //$messageCode = '999999961';
              throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!ShopCouponListQuery::CheckInput($inputData)) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            $resultData['shopcoupon_list'] = ShopCouponListQuery::GetShopCouponData($inputData['sd_id'], $inputData['scm_category'], $inputData['sd_zipcode'], $inputData['queryamount'], $inputData['create_date'], $messageCode);

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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_category', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_zipcode', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'create_date', 20, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'queryamount', 0, true, false)) {
            return false;
        }
        if (!array_key_exists('queryamount', $value) || !is_numeric($value['queryamount'])) {
            $value['queryamount'] = 100;
        }
        if ($value['queryamount'] > 500) {
            $value['queryamount'] = 500;
        }

        if (array_key_exists('create_date', $value)) {
            $temp = date_create($value['create_date']);
            if (!$temp) {
                return false;
            }
            $value['create_date'] = $temp->format('Y-m-d H:i:s');
        } else {
            $value['create_date'] = null;
        }

        if (!array_key_exists('scm_category', $value)) {
            $value['scm_category'] = null;
        }
        if (!array_key_exists('sd_zipcode', $value)) {
            $value['sd_zipcode'] = null;
        }
        if (!array_key_exists('sd_id', $value)) {
            $value['sd_id'] = null;
        }

        if ($value['scm_category'] == null && $value['sd_id'] == null) {
            return false;
        }

        return true;
    }

    function GetShopCouponData($sd_id, $scm_category, $sd_zipcode, $queryamount, $createdate, &$messageCode) {
        try {

            if ($sd_id == null && $scm_category != null) {
                $coupondata = ShopCouponListQuery::GetDataBy_Category($scm_category, $sd_zipcode);
            } else if ($sd_id != null && $scm_category == null) {
                $coupondata = ShopCouponListQuery::GetCouponBy_Sd_ID($sd_id, $messageCode);
            } else {
                //010901006	傳入值格式內容格式有誤，請重新輸入
                $messageCode = '010901006';
                return null;
            }

            if (count($coupondata) == 0) {
                return null;
            }

            if ($createdate != null) {

                $coupondata = array_filter($coupondata, function($obj) use($createdate) {
                    return strtotime($obj['create_date']) < strtotime($createdate);
                });
            }
            $coupondata = array_slice($coupondata, 0, $queryamount);

            return ShopCouponListQuery::TransToShopCouponList($coupondata);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 取得
     * @param type $sd_id
     * @param string $messageCode
     * @return type
     */
    private function GetCouponBy_Sd_ID($sd_id, &$messageCode) {
        $querydata = \App\Models\ICR_ShopCouponData_m::GetDataBy_SD_ID($sd_id);

        if (count($querydata) == 0) {
            //010903001	該商家目前尚無優惠項目
            $messageCode = '010903001';
        }

        return $querydata;
    }

    /**
     *
     * @param type $scm_category
     * @param type $sd_zipcode
     * @return type
     */
    private function  GetDataBy_Category($scm_category, $sd_zipcode) {

        $querydata = \App\Models\ICR_ShopCouponData_m::GetDataBy_Category($scm_category, $sd_zipcode);
        $coupondata = null;
        foreach ($querydata as $rowdata) {
            if (strtotime('now') > strtotime($rowdata['scm_enddate'])) {
                \App\Models\ICR_ShopCouponData_m::UpdateData_PostStatus($rowdata['scm_id'], 3);
            } elseif (strtotime('now') > strtotime($rowdata['scm_startdate'])) {
                $coupondata[] = $rowdata;
            }
        }

        return $coupondata;
    }



    function TransToShopCouponList($coupondata) {

        if (count($coupondata) == 0) {
            return null;
        }

        foreach ($coupondata as $rowdata) {

            $resultdata[] = [
                  'scm_id' => $rowdata['scm_id']
                , 'scm_title' => $rowdata['scm_title']
                , 'sd_id' => $rowdata['sd_id']
                , 'scm_category' => $rowdata['scm_category']
                , 'scm_mainpic' => $rowdata['scm_mainpic']
                , 'scm_startdate' => $rowdata['scm_startdate']
                , 'scm_enddate' => $rowdata['scm_enddate']
                , 'scm_reservationtag' => $rowdata['scm_reservationtag']
                , 'scm_member_limit' => $rowdata['scm_member_limit']
                , 'sendamount' => \App\Models\ICR_ShopCouponData_g::QuerySendCountBy_SCM_ID($rowdata['scm_id'])
                , 'scm_poststatus' => $rowdata['scm_poststatus']
                , 'scm_reservationfulltag' => $rowdata['scm_reservationfulltag']
                , 'scm_producttype' => $rowdata['scm_producttype']
                , 'scm_advancedescribe' => $rowdata['scm_advancedescribe']
                , 'create_date' => $rowdata['create_date']
            ];
        }

        return $resultdata;
    }

}
