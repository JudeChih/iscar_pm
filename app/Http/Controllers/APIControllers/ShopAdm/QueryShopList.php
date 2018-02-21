<?php

namespace App\Http\Controllers\APIControllers\ShopAdm;

use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class QueryShopList {
    /**
     * 取用對應類別商家資料列表回覆
     * @param  [string] $modacc            [模組帳號]
     * @param  [string] $modvrf            [模組驗證碼]
     * @param  [string] $sd_type           [商家類別]
     * @param  [string] $sd_zipcode        [商家郵遞區號]
     * @param  [string] $sd_shopname       [商家名稱]
     * @param  [string] $skip_page         [跳過幾頁作查詢，一頁有10筆]
     * @param  [string] $sd_havebind       [綁定狀態 0:未綁定 1:已綁定]
     * @param  [string] $sd_activestatus   [商家有效狀態]
     * @param  [string] $sd_contact_person [商家聯絡人]
     * @param  [string] $sort              [排序根據]
     * @param  [string] $order             [排序方式 DESC(倒序) ASC(正序)]
     */
    function queryshoplist() {
        $functionName = 'queryshoplist';
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
            if (!QueryShopList::CheckInput($inputData)) {
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
            $inputData = QueryShopList::setDefaultValue($inputData);

            //透過傳入值抓取符合的特約商資料
            if(!$querydata = QueryShopList::GetShopListData($inputData,$count)){
                $messageCode = '101702001';
                throw new \Exception($messageCode);
            }

            //查詢不到任何資料
            if(count($querydata) == 0 ){
                $messageCode = '101702002';
                throw new \Exception($messageCode);
            }

            $resultData['shoplistarray'] = $querydata;
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_type', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_zipcode', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_shopname', 50, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'skip_page', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_havebind', 1, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_activestatus', 1, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_contact_person', 20, true, true)) {
            return false;
        }
        return true;
    }

    /**
     * 針對未傳入的欄位設定預設值
     * @param [array] $inputData [傳入值陣列]
     */
    function setDefaultValue($inputData){
        //商家類別
        if(!isset($inputData['sd_type'])){
            $inputData['sd_type'] = null;
        }
        //商家郵遞區號
        if(!isset($inputData['sd_zipcode'])){
            $inputData['sd_zipcode'] = null;
        }
        //跳過幾頁作查詢
        if(!isset($inputData['skip_page'])){
            $inputData['skip_page'] = 0;
        }
        //綁定狀態
        if(!isset($inputData['sd_havebind'])){
            $inputData['sd_havebind'] = null;
        }
        //商家有效狀態
        if(!isset($inputData['sd_activestatus'])){
            $inputData['sd_activestatus'] = 1;
        }
        //店家名稱
        if(!isset($inputData['sd_shopname'])){
            $inputData['sd_shopname'] = null;
        }
        //店家聯絡人
        if(!isset($inputData['sd_contact_person'])){
            $inputData['sd_contact_person'] = null;
        }
        //排序根據
        if(!isset($inputData['sort'])){
            $inputData['sort'] = 'sd_shopname';
        }
        //排序方式
        if(!isset($inputData['order'])){
            $inputData['order'] = 'DESC';
        }
        return $inputData;
    }

    /**
     * 取得符合傳入值條件的特約商資訊
     * @param type $inputData   [搜尋欄位的值]
     */
    function GetShopListData($inputData,&$count) {
        $sd_r = new \App\Repositories\ICR_ShopDataRepository;
        try {
            // 抓總數
            $countdata = $sd_r->getShopData($inputData['sd_type'], $inputData['sd_zipcode'], $inputData['sd_shopname'], $inputData['skip_page'], $inputData['sd_havebind'], $inputData['sd_activestatus'], $inputData['sd_contact_person'],$inputData['sort'],$inputData['order']) ;
            $count = count($countdata);
            // 只抓10筆
            $querydata = $sd_r->getShopDataByQueryConditions($inputData['sd_type'], $inputData['sd_zipcode'], $inputData['sd_shopname'], $inputData['skip_page'], $inputData['sd_havebind'], $inputData['sd_activestatus'], $inputData['sd_contact_person'],$inputData['sort'],$inputData['order']) ;
            return $querydata;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    // /**
    //  * 編輯要回傳的特約商資料
    //  * @param [type] $querydata [撈取到的特約商資料]
    //  */
    // function setShopDataList($querydata){

    //     foreach ($querydata as $rowdata) {
    //         $resultdata[] = [
    //               'sd_id' => $rowdata['sd_id']
    //             , 'sd_type' => $rowdata['sd_type']
    //             , 'sd_shopname' => $rowdata['sd_shopname']
    //             , 'sd_zipcode' => $rowdata['sd_zipcode']
    //             , 'sd_contact_person' => $rowdata['sd_contact_person']
    //             , 'sd_activestatus' => $rowdata['sd_activestatus']
    //             , 'smb_serno' => $rowdata['smb_serno']
    //         ];
    //     }

    //     return $resultdata;
    // }
}
