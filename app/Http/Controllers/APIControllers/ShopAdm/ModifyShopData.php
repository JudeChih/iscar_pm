<?php

namespace App\Http\Controllers\APIControllers\ShopAdm;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
use DB;

class ModifyShopData {

    /**
     * 修改特約商
     * @return [type] [description]
     */
    function modifyshopdata() {
        $functionName = 'modifyshopdata';
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
            if (!ModifyShopData::CheckInput($inputData)) {
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

            //檢查「店家」、「管理員」權限
            // if (!Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
            //     throw new \Exception($messageCode);
            // }
            DB::beginTransaction();
            //異動特約商
            if (!ModifyShopData::UpdataShopData($inputData, $messageCode)) {
                DB::rollback();
                throw new \Exception($messageCode);
            }
            //儲存異動的資訊
            if (!ModifyShopData::createModifyRecord($inputData, $messageCode)) {
                DB::rollback();
                throw new \Exception($messageCode);
            }
            DB::commit();
            $messageCode = '000000000';
        } catch (\Exception $e) {
            DB::rollback();
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
     * 異動特約商
     * @param [type] $inputData    [description]
     * @param [type] &$messageCode [description]
     */
    function UpdataShopData($inputData, &$messageCode) {
        $sd_r = new \App\Repositories\ICR_ShopDataRepository;
        try {
            DB::beginTransaction();
            if($inputData['modify_type'] == 1){ // 停/啟用
                if(is_array($inputData['sd_id'])){
                    foreach ($inputData['sd_id'] as $val) {
                        $arraydata['sd_id'] = $val;
                        $arraydata['sd_activestatus'] = $inputData['sd_activestatus'];
                        if(!\App\Models\ICR_ShopData::UpdateData($arraydata)){
                            DB::rollback();
                            $messageCode = '101701001';
                            return false;
                        }
                    }
                }else{
                    $arraydata['sd_id'] = $val;
                    $arraydata['sd_activestatus'] = $inputData['sd_activestatus'];
                    if(!\App\Models\ICR_ShopData::UpdateData($arraydata)){
                        DB::rollback();
                        $messageCode = '101701001';
                        return false;
                    }
                }
            }elseif($inputData['modify_type'] == 2){ // 刪除
                $arraydata['sd_id'] = $inputData['sd_id'];
                $arraydata['isflag'] = 0;
                if(!\App\Models\ICR_ShopData::UpdateData($arraydata)){
                    DB::rollback();
                    $messageCode = '101701002';
                    return false;
                }
            }elseif($inputData['modify_type'] == 3){ // 綁定/解綁
                if(is_array($inputData['sd_id'])){
                    foreach ($inputData['sd_id'] as $val) {
                        $arraydata['sd_id'] = $val;
                        $arraydata['sd_havebind'] = $inputData['sd_havebind'];
                        if(!\App\Models\ICR_ShopData::UpdateData($arraydata)){
                            DB::rollback();
                            $messageCode = '101701004';
                            return false;
                        }
                    }
                }else{
                    $arraydata['sd_id'] = $inputData['sd_id'];
                    $arraydata['sd_havebind'] = $inputData['sd_havebind'];
                    if(!\App\Models\ICR_ShopData::UpdateData($arraydata)){
                        DB::rollback();
                        $messageCode = '101701004';
                        return false;
                    }
                }
            }elseif($inputData['modify_type'] == 4){ // 設置特店代號
                $arraydata['sd_id'] = $inputData['sd_id'];
                $arraydata['sd_salescode'] = $inputData['sd_salescode'];
                $arraydata['sd_salesbind'] = $inputData['sd_salesbind'];
                // 查詢該代號是否已經被使用
                if($inputData['sd_salescode'] != ''){
                    $shopdata = $sd_r->getShopDataBySdSalescode($inputData['sd_salescode']);
                if(count($shopdata) > 0){
                    DB::rollback();
                    $messageCode = '101701006';
                        return false;
                    }
                }
                if(!\App\Models\ICR_ShopData::UpdateData($arraydata)){
                    DB::rollback();
                    // 設置代號失敗
                    $messageCode = '101701005';
                    return false;
                }
            }elseif($inputData['modify_type'] == 5){
                $arraydata['sd_id'] = $inputData['sd_id'];
                $arraydata['sd_seo_keywords'] = $inputData['sd_seo_keywords'];
                $arraydata['sd_seo_description'] = $inputData['sd_seo_description'];
                $arraydata['sd_seo_title'] = $inputData['sd_seo_title'];
                if(!\App\Models\ICR_ShopData::UpdateData($arraydata)){
                    DB::rollback();
                    // 設置metatag失敗
                    $messageCode = '101701007';
                    return false;
                }
            }elseif($inputData['modify_type'] == 6){
                $arraydata['sd_id'] = $inputData['sd_id'];
                $arraydata['sd_paymentflowagreement'] = $inputData['sd_paymentflowagreement'];
                if(!\App\Models\ICR_ShopData::UpdateData($arraydata)){
                    DB::rollback();
                    // 設置金流協議狀態失敗
                    $messageCode = '101701005';
                    return false;
                }
            }

            DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollback();
            \App\Models\ErrorLog::InsertData($ex);
            $messageCode = '999999999';
        }
    }

    /**
     * 建立特約商異動的紀錄
     * @param  [type] $inputData  [description]
     */
    function createModifyRecord($inputData,&$messageCode){
        $sdmr_r = new \App\Repositories\ICR_ShopDataModifyRecordRepository;
        try {
            DB::beginTransaction();
            if(is_array($inputData['sd_id'])){
                foreach ($inputData['sd_id'] as $val) {
                    $arraydata['sd_id'] = $val;
                    $arraydata['sdmr_operationtype'] = $inputData['sdmr_operationtype'];
                    $arraydata['sdmr_modifyitem'] = 1;
                    $arraydata['sdmr_modifyuser'] = $inputData['modacc'];
                    if(isset($inputData['sdmr_modifyreason'])){
                        $arraydata['sdmr_modifyreason'] = $inputData['sdmr_modifyreason'];
                    }
                    if(!$sdmr_r->insertData($arraydata)){
                        DB::rollback();
                        $messageCode = '101701003';
                        return false;
                    }
                }
            }else{
                $arraydata['sd_id'] = $inputData['sd_id'];
                $arraydata['sdmr_operationtype'] = $inputData['sdmr_operationtype'];
                $arraydata['sdmr_modifyitem'] = 1;
                $arraydata['sdmr_modifyuser'] = $inputData['modacc'];
                if(isset($inputData['sdmr_modifyreason'])){
                    $arraydata['sdmr_modifyreason'] = $inputData['sdmr_modifyreason'];
                }
                if(!$sdmr_r->insertData($arraydata)){
                    DB::rollback();
                    $messageCode = '101701003';
                    return false;
                }
            }
            DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollback();
            \App\Models\ErrorLog::InsertData($ex);
            $messageCode = '999999999';
        }
    }

}
