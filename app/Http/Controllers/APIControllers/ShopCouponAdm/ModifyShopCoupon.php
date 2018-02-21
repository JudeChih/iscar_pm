<?php

namespace App\Http\Controllers\APIControllers\ShopCouponAdm;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
use DB;


class ModifyShopCoupon {

    /**
     * 商家優惠活動券管理
     * @return [type] [description]
     */
    function modifyshopcoupon() {
        $functionName = 'modifyshopcoupon';
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
            if (!ModifyShopCoupon::CheckInput($inputData)) {
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
            // if (!\App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
            //     throw new \Exception($messageCode);
            // }

            DB::beginTransaction();
            //異動特約商
            if (!ModifyShopCoupon::UpdateShopCoupon($inputData, $messageCode)) {
                DB::rollback();
                throw new \Exception($messageCode);
            }

            //儲存異動的資訊
            if (!ModifyShopCoupon::createModifyRecord($inputData, $messageCode)) {
                DB::rollback();
                throw new \Exception($messageCode);
            }

            DB::commit();
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

        return true;
    }

    /**
     * 檢查並建立需異動的資料
     * @param type $inputData
     * @param type $modifydata
     * @param string $messageCode
     * @return boolean
     */
    private function UpdateShopCoupon($inputData, &$messageCode) {
        try {
            DB::beginTransaction();
            if(is_array($inputData['scm_id'])){
                foreach ($inputData['scm_id'] as $val) {
                    if(!\App\Models\ICR_ShopCouponData_m::UpdateData_PostStatus($val,$inputData['scm_poststatus'])){
                        DB::rollback();
                        $messageCode = '101704001';
                        return false;
                    }
                }
            }else{
                if(!\App\Models\ICR_ShopCouponData_m::UpdateData_PostStatus($inputData['scm_id'],$inputData['scm_poststatus'])){
                    DB::rollback();
                    $messageCode = '101704001';
                    return false;
                }
            }
            DB::commit();
            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 建立優惠卷異動的紀錄
     * @param  [type] $inputData    [description]
     */
    function createModifyRecord($inputData,&$messageCode){
        $sdmr_r = new \App\Repositories\ICR_ShopDataModifyRecordRepository;
        try {
            DB::beginTransaction();
            foreach ($inputData['sd_id'] as $key => $val) {
                $arraydata['sd_id'] = $val;
                $arraydata['scm_id'] = $inputData['scm_id'][$key];
                $arraydata['sdmr_operationtype'] = $inputData['sdmr_operationtype'];
                $arraydata['sdmr_modifyitem'] = 2;
                $arraydata['sdmr_modifyuser'] = $inputData['modacc'];
                $arraydata['sdmr_modifyreason'] = $inputData['sdmr_modifyreason'];
                if(!$sdmr_r->insertData($arraydata)){
                    DB::rollback();
                    $messageCode = '101704002';
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
