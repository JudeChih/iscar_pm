<?php

namespace App\Http\Controllers\APIControllers\ShopAdm;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class QueryShopContent {

    /**
     * 取用商家內容
     * @param  [string] $modacc [模組帳號]
     * @param  [string] $modvrf [模組驗證碼]
     * @param  [string] $sd_id  [商家代號]
     */
    function queryshopcontent() {
        $functionName = 'queryshopcontent';
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
            if (!QueryShopContent::CheckInput($inputData)) {
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

            // 抓取特約商資訊
            $resultData['shopdata'] = QueryShopContent::GetShopData($inputData['sd_id'], $messageCode);
            // 抓取該特約商的商品資訊
            $resultData['shopcoupon'] = QueryShopContent::GetShopCouponList($inputData['sd_id']);
            if (is_null($messageCode) && strlen($messageCode) == 0) {
                $messageCode = '000000000';
            }
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 0, false, false)) {
            return false;
        }

        return true;
    }

    /**
     * 取得店家資訊
     * @param  string $sd_id [店家代碼]
     */
    function GetShopData($sd_id, &$messageCode) {
        $sd_r = new \App\Repositories\ICR_ShopDataRepository;
        try {
            $querydata = $sd_r->GetData($sd_id);
            return QueryShopContent::CreateResultData($querydata[0]);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 取得店家的商品資訊
     * @param string $sd_id [店家代碼]
     */
    function GetShopCouponList($sd_id) {
        $scm_r = new \App\Repositories\ICR_ShopCouponData_mRepository;
        try {
            $querydata = $scm_r->GetDataBy_SD_ID($sd_id);
            return $querydata;
        } catch (Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return null;
        }
    }

    /**
     * 建立回傳資料格式
     * @param type $shopdata
     * @return type
     */
    private function CreateResultData($shopdata) {

        if (count($shopdata) == 0) {
            return null;
        }
        $result['sd_id'] = $shopdata['sd_id'];
        $result['sd_shopname'] = $shopdata['sd_shopname'];
        $result['sd_shoptel'] = $shopdata['sd_shoptel'];
        $result['sd_zipcode'] = $shopdata['sd_zipcode'];
        $result['sd_shopaddress'] = $shopdata['sd_shopaddress'];
        $result['sd_lat'] = $shopdata['sd_lat'];
        $result['sd_lng'] = $shopdata['sd_lng'];
        $result['sd_weeklystart'] = $shopdata['sd_weeklystart'];
        $result['sd_weeklyend'] = $shopdata['sd_weeklyend'];
        $result['sd_dailystart'] = $shopdata['sd_dailystart'];
        $result['sd_dailyend'] = $shopdata['sd_dailyend'];
        $result['sd_shopphotopath'] = $shopdata['sd_shopphotopath'];
        $result['sd_introtext'] = $shopdata['sd_introtext'];

        $result['shop_layout'] = null;
        $result['sd_contact_person'] = $shopdata['sd_contact_person'];
        $result['sd_contact_tel'] = $shopdata['sd_contact_tel'];
        $result['sd_contact_mobile'] = $shopdata['sd_contact_mobile'];
        $result['sd_contact_address'] = $shopdata['sd_contact_address'];
        $result['sd_contact_email'] = $shopdata['sd_contact_email'];
        $result['sd_advancedata'] = $shopdata['sd_advancedata'];
        $result['sd_questiontotalaverage'] = $shopdata['sd_questiontotalaverage'];
        $result['sd_activestatus'] = $shopdata['sd_activestatus'];
        $result['sd_paymentflow'] = $shopdata['sd_paymentflow'];
        $result['sd_paymentflowdata'] = $shopdata['sd_paymentflowdata'];
        $result['sd_havebind'] = $shopdata['sd_havebind'];

        return $result;
    }

}
