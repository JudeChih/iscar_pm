<?php

namespace App\Http\Controllers\APIControllers\ShopReport;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class QueryShopReport {

    /**
     * 查詢"已付款"的銷貨對帳表
     * @param  [string] $modacc      [模組帳號]
     * @param  [string] $modvrf      [模組驗證碼]
     * @param  [string] $sat         [用戶服務存取憑證]
     * @param  [string] $query_start [查詢區間起始日]
     * @param  [string] $query_end   [查詢區間截止日]
     * @param  [string] $sd_id       [商家代碼]
     */
    function queryshopreport() {
        $functionName = 'queryshopreport';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //輸入值
            if(!QueryShopReport::CheckInput($inputData)){
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
               $messageCode = '999999960';
               throw new \Exception($messageCode);
            }
            $sd_r = new \App\Repositories\ICR_ShopDataRepository;
            $shopData = $sd_r->getshopDataBySdId($inputData['sd_id']);
            // 檢查是否有這間店家
            if ( is_null($shopData) || count($shopData) == 0 || count($shopData) > 1 ) {
                $messageCode = '171011001';
                throw new \Exception($messageCode);
            }else{
                $shopData = $shopData[0];
            }
            // 抓取符合的銷貨資訊
            $shopReport = $sd_r->getShopReportBySdIdDate($inputData['sd_id'],$inputData['query_start'],$inputData['query_end']);
            // 檢查是否有銷貨報表
            if ( is_null($shopReport) || count($shopReport) == 0 ) {
                $messageCode = '171011002';
                throw new \Exception($messageCode);
            }
            $shopReport = QueryShopReport::createResultDataRecord($shopReport);
            $resultData['report_record'] = $shopReport;
            $resultData['report_head'] = QueryShopReport::createResultDataHeader($shopData,$inputData,$shopReport);
            $resultData['report_foot'] = QueryShopReport::createResultDataFoot();
            $messageCode ='000000000';
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'query_start', 20, false, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'query_end', 20, false, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sat', 0, false, false)) {
            return false;
        }
        if (mb_strlen($value['query_start']) == 10 ) {
           $value['query_start'] = $value['query_start'] . ' 00:00:00' ;
        }
         if (mb_strlen($value['query_end']) == 10 ) {
           $value['query_end'] = $value['query_end'] . ' 23:59:59' ;
        }
        return true;
    }

    /**
     * 編輯要回傳的銷貨對帳資訊
     * @param  [json] $shopReport [銷貨資訊]
     */
    public function createResultDataRecord($shopReport){
        $result = array();
        $scg_id = null;
        foreach ($shopReport as $value) {
            if($scg_id != $value['scg_id']){
                $scg_id = $value['scg_id'];
                //銷售金額
                $scg_totalamount = $value['scg_totalamount'];
                //金流手續
                $flow_fee = round(($value['scg_totalamount'] * $value['sd_paymentflowfeepct'])/100 );
                //平台手續
                $plat_fee = round($value['scg_totalamount'] * 0.1);
                //實收金額
                $revenue = $scg_totalamount - $flow_fee - $plat_fee;
                $result[] = [
                       'scg_id' =>$value['scg_id'],
                       'scg_totalamount' =>$scg_totalamount,
                       'flow_fee' =>$flow_fee,
                       'plat_fee' =>$plat_fee,
                       'revenue' =>$revenue,
                ];
            }
        }
        return $result;
    }

    /**
     * 編輯報表的頭所要放置的資訊
     * @param  [json]   $shopData      [店家資訊]
     * @param  [json]   $inputData     [Call此API所傳入的值]
     * @param  [json]   $shopReport    [銷貨資訊]
     * @return [string] $sd_shopname   [店家名稱]
     * @return [string] $query_start   [搜尋起始日]
     * @return [string] $query_end     [搜尋結束日]
     * @return [string] $totalcount    [訂單數量]
     * @return [string] $totalamount   [銷售總金額]
     * @return [string] $flowfeeamount [金流總手續費]
     * @return [string] $platfeeamount [平台總手續費]
     * @return [string] $revenueamount [實收總金額]
     */
    function createResultDataHeader($shopData,$inputData,$shopReport) {
        $result = [
                'sd_shopname' =>$shopData['sd_shopname'],
                'query_start' =>$inputData['query_start'],
                'query_end' =>$inputData['query_end']
                ];
        //訂單數量
        $result['totalcount'] = count($shopReport);
        //銷售總金額
        $totalamount = 0;
        foreach ($shopReport as $value) {
            $totalamount = $totalamount + $value['scg_totalamount'];
        }
        $result['totalamount'] = $totalamount;
        //金流總手續費
        $flowfeeamount = 0;
        foreach ($shopReport as $value) {
            $flowfeeamount = $flowfeeamount + $value['flow_fee'];
        }
        $result['flowfeeamount'] = $flowfeeamount;
        //平台總手續費
        $platfeeamount = 0;
        foreach ($shopReport as $value) {
            $platfeeamount = $platfeeamount + $value['plat_fee'];
        }
        $result['platfeeamount'] = $platfeeamount;
        //實收總金額
        $revenueamount = 0;
        foreach ($shopReport as $value) {
            $revenueamount = $revenueamount + $value['revenue'];
        }
        $result['revenueamount'] = $revenueamount;

        return $result;
    }

    /**
     * 編輯報表的尾所要放置的資訊
     * @return [string] $create_date [製表時間]
     */
    function createResultDataFoot() {
        //製表時間
        $result['create_date'] = date('Y-m-d H:i:s');
        return $result;
    }

}