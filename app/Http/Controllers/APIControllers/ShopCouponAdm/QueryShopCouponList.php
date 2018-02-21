<?php

namespace App\Http\Controllers\APIControllers\ShopCouponAdm;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class QueryShopCouponList {

    /**
     * 商家優惠活動券列表查詢
     * @param  [string] $modacc         [模組帳號]
     * @param  [string] $modvrf         [模組驗證碼]
     * @param  [string] $skip_page      [跳過幾頁作查詢(一頁有10筆)]
     * @param  [string] $scm_category   [活動類別：0.汽車美容 1.汽車維修 2.汽車百貨3.汽車零件]
     * @param  [string] $sd_shopname    [商家名稱]
     * @param  [string] $scm_title      [優惠標題]
     * @param  [string] $scm_poststatus [商品刊登狀態 0:停用 1:啟用]
     * @param  [string] $sort           [排序根據]
     * @param  [string] $order          [排序方式 DESC(倒序) ASC(正序)]
     */
    function queryshopcouponlist() {
        $functionName = 'queryshopcouponlist';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        $count = null;
        try {
            //檢查輸入值
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            if (!QueryShopCouponList::CheckInput($inputData)) {
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

            //下面這function還沒實行!!!!!!!!!!!!!!!!!!!!
            // //檢查模組是否為admin
            // if ( !$memService->getModuleData($inputData['modacc'], $inputData['modvrf'], $messageCode)) {
            //   //模組身份不是admin
            //   $messageCode = '999999963';
            //   throw new \Exception($messageCode);
            // }

            //如果未傳入的傳入值，設定預設值
            $inputData = QueryShopCouponList::setDefaultValue($inputData);

            //透過傳入值抓取符合的商品資料
            if(!$coupondata = QueryShopCouponList::GetShopCouponData($inputData,$count)){
                //撈取資料出現問題
                $messageCode = '101705001';
                throw new \Exception($messageCode);
            }

            //查詢不到任何資料
            if(count($coupondata) == 0 ){
                $messageCode = '101705002';
                throw new \Exception($messageCode);
            }

            $resultData['shopcouponlist'] = $coupondata;
            $resultData['page'] = ceil($count/10);
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'skip_page', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_category', 1, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_title', 40, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_shopname', 50, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_poststatus', 1, true, true)) {
            return false;
        }
        return true;
    }

    /**
     * 針對未傳入的欄位設定預設值
     * @param [array] $inputData [傳入值陣列]
     */
    function setDefaultValue($inputData){
        if(!isset($inputData['skip_page'])){
            $inputData['skip_page'] = 0;
        }
        if(!isset($inputData['scm_category'])){
            $inputData['scm_category'] = null;
        }
        if(!isset($inputData['scm_title'])){
            $inputData['scm_title'] = null;
        }
        if(!isset($inputData['sd_shopname'])){
            $inputData['sd_shopname'] = null;
        }
        if(!isset($inputData['scm_poststatus'])){
            $inputData['scm_poststatus'] = 1;
        }
        if(!isset($inputData['sort'])){
            $inputData['sort'] = 'sd_shopname';
        }
        if(!isset($inputData['order'])){
            $inputData['order'] = 'DESC';
        }
        return $inputData;
    }

    /**
     * 取得符合傳入值條件的商品資訊
     * @param [type] $inputData [搜尋欄位的值]
     * @param string $count     [回傳共幾筆商品]
     */
    function GetShopCouponData($inputData,&$count) {
        $scm_r = new \App\Repositories\ICR_ShopCouponData_mRepository;
        try {
            // 抓總數
            $countdata = $scm_r->getShopCoupon($inputData['scm_category'], $inputData['scm_title'], $inputData['sd_shopname'], $inputData['scm_poststatus'],$inputData['sort'],$inputData['order']) ;
            $count = count($countdata);
            // 只抓10筆
            $querydata = $scm_r->getShopCouponByQueryConditions($inputData['skip_page'], $inputData['scm_category'], $inputData['scm_title'], $inputData['sd_shopname'], $inputData['scm_poststatus'],$inputData['sort'],$inputData['order']) ;
            return $querydata;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }


    // function setShopCouponList($coupondata) {

    //     if (count($coupondata) == 0) {
    //         return null;
    //     }

    //     foreach ($coupondata as $rowdata) {

    //         $resultdata[] = [
    //               'scm_id' => $rowdata['scm_id']
    //             , 'scm_title' => $rowdata['scm_title']
    //             , 'sd_id' => $rowdata['sd_id']
    //             , 'scm_category' => $rowdata['scm_category']
    //             , 'scm_mainpic' => $rowdata['scm_mainpic']
    //             , 'scm_startdate' => $rowdata['scm_startdate']
    //             , 'scm_enddate' => $rowdata['scm_enddate']
    //             , 'scm_reservationtag' => $rowdata['scm_reservationtag']
    //             , 'scm_member_limit' => $rowdata['scm_member_limit']
    //             , 'sendamount' => \App\Models\ICR_ShopCouponData_g::QuerySendCountBy_SCM_ID($rowdata['scm_id'])
    //             , 'scm_poststatus' => $rowdata['scm_poststatus']
    //             , 'scm_reservationfulltag' => $rowdata['scm_reservationfulltag']
    //             , 'scm_producttype' => $rowdata['scm_producttype']
    //             , 'scm_advancedescribe' => $rowdata['scm_advancedescribe']
    //             , 'create_date' => $rowdata['create_date']
    //         ];
    //     }

    //     return $resultdata;
    // }

}
