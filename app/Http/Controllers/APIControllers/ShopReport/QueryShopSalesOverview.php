<?php

namespace App\Http\Controllers\APIControllers\ShopReport;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class QueryShopSalesOverview {

    /**
     * 查詢店家的優惠卷銷售總覽
     * @param  [string] $modacc          [模組帳號]
     * @param  [string] $modvrf          [模組驗證碼]
     * @param  [string] $sat             [用戶服務存取憑證]
     * @param  [string] $query_start     [查詢區間起始日]
     * @param  [string] $query_end       [查詢區間截止日]
     * @param  [string] $sd_id           [商家代碼]
     * @param  [string] $scm_producttype [商品型式]
     * @param  [boolean]$usestatus       [true:已付款已使用 false:已付款未使用]
     */
    function queryshopsalesoverview() {
        $functionName = 'queryshopsalesoverview';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //輸入值
            if(!QueryShopSalesOverview::CheckInput($inputData)){
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
                $messageCode = '171211001';
                throw new \Exception($messageCode);
            }else{
                $shopData = $shopData[0];
            }
            // 將日期換成結算周期
            $date = \Carbon\Carbon::parse($inputData['query_end']);
            if($date->day == 15){
                $date->day = 1;
                $date->subDay();
                $inputData['close_end'] = $date->toDateString();
                $date->day = 16;
                $inputData['close_start'] = $date->toDateString();
            }else{
                $date->day = 15;
                $inputData['close_end'] = $date->toDateString();
                $date->day = 1;
                $inputData['close_start'] = $date->toDateString();
            }
            // 抓取符合的資料
            // 抓已付款已使用
            $shopUsed = $sd_r->getShopSalesBySdId($inputData['sd_id'],$inputData['close_start'],$inputData['close_end'],$inputData['scm_producttype'],true,null);
            // 抓已付款未使用
            $shopUnused = $sd_r->getShopSalesBySdId($inputData['sd_id'],$inputData['close_start'],$inputData['close_end'],$inputData['scm_producttype'],false,null);
            // 檢查是否有至少一筆的資料
            // if ( is_null($shopReport) || count($shopReport) == 0 ) {
            //     $messageCode = '171211002';
            //     throw new \Exception($messageCode);
            // }
            $shopUsed = QueryShopSalesOverview::createResultDataRecord($shopUsed);
            $shopUnused = QueryShopSalesOverview::createResultDataRecord($shopUnused);
            $resultData['report_Used'] = $shopUsed;
            $resultData['report_Unused'] = $shopUnused;
            if(isset($inputData['usestatus'])){
                if($inputData['usestatus']){
                    $resultData['report_result'] = QueryShopSalesOverview::createResultData($shopData,$inputData,$shopUsed);
                }else{
                    $resultData['report_result'] = QueryShopSalesOverview::createResultData($shopData,$inputData,$shopUnused);
                }
            }

            $resultData['report_head'] = QueryShopSalesOverview::createResultDataHeader($shopData,$inputData,$shopUsed);
            $resultData['report_foot'] = QueryShopSalesOverview::createResultDataFoot($shopUnused,$inputData);
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_producttype', 1, false, false)) {
            return false;
        }
        // if (mb_strlen($value['query_start']) == 10 ) {
        //    $value['query_start'] = $value['query_start'] . ' 00:00:00' ;
        // }
        //  if (mb_strlen($value['query_end']) == 10 ) {
        //    $value['query_end'] = $value['query_end'] . ' 23:59:59' ;
        // }
        return true;
    }

    /**
     * 編輯每筆交易的資訊
     * @param  [json] $data
     */
    public function createResultDataRecord($data){
        $result = array();
        $scg_id = null;
        if(count($data) > 1){
            foreach ($data as $value) {
                if($scg_id != $value['scg_id']){
                    //訂單編號
                    $scg_id = $value['scg_id'];
                    //商品名稱
                    $scm_title = $value['scm_title'];
                    // //售價
                    // $scg_buyprice = $value['scg_buyprice'];
                    // //數量
                    // $scg_buyamount = $value['scg_buyamount'];
                    //銷售總金額
                    $scg_totalamount = $value['scg_totalamount'];
                    //金流手續
                    $flow_fee = round(($value['scg_totalamount'] * $value['sd_paymentflowfeepct'])/100 );
                    //平台手續
                    $plat_fee = round($value['scg_totalamount'] * 0.1);
                    //服務費
                    $service_fee = round(($value['scg_totalamount'] * $value['sd_paymentflowfeepct'])/100 ) + round($value['scg_totalamount'] * 0.1);
                    //實收金額
                    $revenue = $scg_totalamount - $service_fee;
                    $result[] = [
                           'scg_id' =>$scg_id,
                           'scm_title' =>$scm_title,
                           'scg_totalamount' =>$scg_totalamount,
                           'flow_fee' =>$flow_fee,
                           'plat_fee' =>$plat_fee,
                           'revenue' =>$revenue,
                           'service_fee' =>$service_fee,
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * 編輯本期應收紀錄的資訊
     * @param  [json]   $shopData      [店家資訊]
     * @param  [json]   $inputData     [Call此API所傳入的值]
     * @param  [json]   $shopUsed      [銷貨資訊]
     * @return [string] $sd_shopname   [店家名稱]
     * @return [string] $query_start   [搜尋起始日]
     * @return [string] $query_end     [搜尋結束日]
     * @return [string] $totalcount    [訂單數量]
     * @return [string] $totalamount   [銷售總金額]
     * @return [string] $flowfeeamount [金流總手續費]
     * @return [string] $platfeeamount [平台總手續費]
     * @return [string] $revenueamount [實收總金額]
     * @return [string] $servicefeeamount [服務費總金額]
     */
    function createResultDataHeader($shopData,$inputData,$shopUsed) {
        $result = [
                'sd_shopname' =>$shopData['sd_shopname'],
                'query_start' =>$inputData['query_start'],
                'query_end' =>$inputData['query_end']
                ];
        //結算周期日(end)
        $result['close_end'] = $inputData['close_end'];

        //結算周期日(start)
        $result['close_start'] = $inputData['close_start'];

        //款項支付日(多10天)
        $date = \Carbon\Carbon::parse($inputData['query_end']);
        $date->addDays(10);
        $result['pay_day'] = $date->toDateString();
        //訂單數量
        $result['totalcount'] = count($shopUsed);
        //銷售總金額
        $totalamount = 0;
        foreach ($shopUsed as $value) {
            $totalamount = $totalamount + $value['scg_totalamount'];
        }
        $result['totalamount'] = $totalamount;
        //金流總手續費
        $flowfeeamount = 0;
        foreach ($shopUsed as $value) {
            $flowfeeamount = $flowfeeamount + $value['flow_fee'];
        }
        $result['flowfeeamount'] = $flowfeeamount;
        //平台總手續費
        $platfeeamount = 0;
        foreach ($shopUsed as $value) {
            $platfeeamount = $platfeeamount + $value['plat_fee'];
        }
        $result['platfeeamount'] = $platfeeamount;
        //實收總金額
        $revenueamount = 0;
        foreach ($shopUsed as $value) {
            $revenueamount = $revenueamount + $value['revenue'];
        }
        $result['revenueamount'] = $revenueamount;

        return $result;
    }

    /**
     * 編輯本期尚未施作紀錄的資訊
     * @param  [json]   $inputData     [Call此API所傳入的值]
     * @param  [json]   $shopUnused    [銷貨資訊]
     * @return [string] $totalcount    [已結帳數量]
     * @return [string] $totalamount   [銷售總金額]
     * @return [string] $reData        [預約筆數]
     * @return [string] $unreData      [非預約筆數]
     */
    public function createResultDataFoot($shopUnused,$inputData){
        $sd_r = new \App\Repositories\ICR_ShopDataRepository;
        $result = array();
        // 總筆數
        $result['totalcount'] = count($shopUnused);
        // 總金額
        $totalamount = 0;
        foreach ($shopUnused as $value) {
            $totalamount = $totalamount + $value['scg_totalamount'];
        }
        $result['totalamount'] = $totalamount;
        // 預約筆數
        $aa = $sd_r->getShopSalesBySdId($inputData['sd_id'],$inputData['query_start'],$inputData['query_end'],$inputData['scm_producttype'],false,1);
        $result['reData'] = count($aa);
        // 非預約筆數
        $bb = $sd_r->getShopSalesBySdId($inputData['sd_id'],$inputData['query_start'],$inputData['query_end'],$inputData['scm_producttype'],false,0);
        $result['unreData'] = count($bb);

        return $result;
    }

    /**
     * 編輯報表所要放置的資訊
     * @param  [type]   $shopData      [店家資訊]
     * @param  [type]   $inputData     [Call此API所傳入的值]
     * @param  [type]   $data          [銷貨資訊]
     * @return [string] $sd_shopname   [店家名稱]
     * @return [string] $query_start   [搜尋起始日]
     * @return [string] $query_end     [搜尋結束日]
     * @return [string] $totalcount    [訂單數量]
     * @return [string] $totalamount   [銷售總金額]
     * @return [string] $flowfeeamount [金流總手續費]
     * @return [string] $platfeeamount [平台總手續費]
     * @return [string] $revenueamount [實收總金額]
     * @return [string] $create_date   [製表時間]
     */
    public function createResultData($shopData,$inputData,$data){
        $result = [
                'sd_shopname' =>$shopData['sd_shopname'],
                'query_start' =>$inputData['query_start'],
                'query_end' =>$inputData['query_end']
                ];
        //訂單數量
        $result['totalcount'] = count($data);
        //銷售總金額
        $totalamount = 0;
        foreach ($data as $value) {
            $totalamount = $totalamount + $value['scg_totalamount'];
        }
        $result['totalamount'] = $totalamount;
        //總服務費
        $servicefeeamount = 0;
        foreach ($data as $value) {
            $servicefeeamount = $servicefeeamount + $value['service_fee'];
        }
        $result['servicefeeamount'] = $servicefeeamount;
        //金流總手續費
        $flowfeeamount = 0;
        foreach ($data as $value) {
            $flowfeeamount = $flowfeeamount + $value['flow_fee'];
        }
        $result['flowfeeamount'] = $flowfeeamount;
        //平台總手續費
        $platfeeamount = 0;
        foreach ($data as $value) {
            $platfeeamount = $platfeeamount + $value['plat_fee'];
        }
        $result['platfeeamount'] = $platfeeamount;
        //實收總金額
        $revenueamount = 0;
        foreach ($data as $value) {
            $revenueamount = $revenueamount + $value['revenue'];
        }
        $result['revenueamount'] = $revenueamount;
        //製表時間
        $result['create_date'] = date('Y-m-d H:i:s');
        return $result;
    }

}