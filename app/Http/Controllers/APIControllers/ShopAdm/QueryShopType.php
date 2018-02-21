<?php

namespace App\Http\Controllers\APIControllers\ShopAdm;

use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class QueryShopType {
    /**
     * 取得特約商各類別的數量(未綁定跟已綁定分開)
     * @param  [string] $modacc            [模組帳號]
     * @param  [string] $modvrf            [模組驗證碼]
     */
    function queryshoptype() {
        $functionName = 'queryshoptype';
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
            if (!QueryShopType::CheckInput($inputData)) {
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

            //透過傳入值抓取符合的特約商資料
            if(!$querydata = QueryShopType::getShopType()){
                $messageCode = '101707001';
                throw new \Exception($messageCode);
            }

            $resultData['shoptype'] = $querydata;
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
        return true;
    }

    /**
     * 取得特約商各類別的統計數
     */
    function getShopType() {
        $sd_r = new \App\Repositories\ICR_ShopDataRepository;
        try {
            
            //已綁定
            $bind_alltype = count($sd_r->getShopDataBySdType(null,1)) ;// 特約商總數
            $bind_type1 = count($sd_r->getShopDataBySdType(1,1));// 汽車美容
            $bind_type2 = count($sd_r->getShopDataBySdType(2,1));// 汽車維修
            $bind_type3 = count($sd_r->getShopDataBySdType(3,1));// 二手車商
            $bind_type4 = count($sd_r->getShopDataBySdType(4,1));// 汽車輪胎
            $bind_type5 = count($sd_r->getShopDataBySdType(5,1));// 汽車百貨
            $bind_type99 = count($sd_r->getShopDataBySdType(99,1));// 公廟
            $bind_notype = count($sd_r->getShopDataBySdType(0,1));// 未分類


            //未綁定
            $unbind_alltype = count($sd_r->getShopDataBySdType(null,0));// 特約商總數
            $unbind_type1 = count($sd_r->getShopDataBySdType(1,0));// 汽車美容
            $unbind_type2 = count($sd_r->getShopDataBySdType(2,0));// 汽車維修
            $unbind_type3 = count($sd_r->getShopDataBySdType(3,0));// 二手車商
            $unbind_type4 = count($sd_r->getShopDataBySdType(4,0));// 汽車輪胎
            $unbind_type5 = count($sd_r->getShopDataBySdType(5,0));// 汽車百貨
            $unbind_type99 = count($sd_r->getShopDataBySdType(99,0));// 公廟
            $unbind_notype = count($sd_r->getShopDataBySdType(0,0));// 未分類

            $alltype = array('特約商總數',$bind_alltype,$unbind_alltype);
            $type1 = array('汽車美容',$bind_type1,$unbind_type1);
            $type2 = array('汽車維修',$bind_type2,$unbind_type2);
            $type3 = array('二手車商',$bind_type3,$unbind_type3);
            $type4 = array('汽車輪胎',$bind_type4,$unbind_type4);
            $type5 = array('汽車百貨',$bind_type5,$unbind_type5);
            $type99 = array('公廟',$bind_type99,$unbind_type99);
            $notype = array('未分類',$bind_notype,$unbind_notype);

            $querydata['alltype'] = $alltype;
            $querydata['type1'] = $type1;
            $querydata['type2'] = $type2;
            $querydata['type3'] = $type3;
            $querydata['type4'] = $type4;
            $querydata['type5'] = $type5;
            $querydata['type99'] = $type99;
            $querydata['notype'] = $notype;


            return $querydata;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }
}
