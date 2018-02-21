<?php

namespace App\Http\Controllers\APIControllers\CarNews; 
use DB;
use Illuminate\Support\Facades\Input;
/** payusedcarpost	刊登物件付費扣點功能 * */
class PayUsedCarPost {

     function payusedcarpost() {
        $functionName = 'payusedcarpost';
        $inputString =  Input::All();
        $inputData = \App\Library\Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        DB::beginTransaction(); //Transaction，紀錄開始。
        try {
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!$this->CheckInput($inputData)) {
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
               //$messageCode = '999999962';
               throw new \Exception($messageCode);
            }
            //檢查額逾
            $CarNews = new CarNews;
            if (!$CarNews->QueryCosEnd($md_id, $inputData['dcil_id'], $querydata, $messageCode)) {
                throw new \Exception($messageCode);
            }
             
            if ($inputData['md_clienttype'] == 0 ) {
                $cbi_owner_id = $md_id ;
                $cbi_postownertype = 0;
            } else if($inputData['md_clienttype'] == 2 ) {
                 //檢查「店家」、「管理員」權限
                if (!\App\Library\Commontools::CheckShopUserIdentity( $inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                   throw new \Exception($messageCode);
                }
                $cbi_owner_id = $inputData['sd_id'];
                $cbi_postownertype = 0;
            } else {
                $messageCode = '999999989';
                throw new \Exception($messageCode);
            }
            //判斷，資料是否有無。
            $carbasicdata = \App\Models\ICR_CarBasicInfo::GetDataByCBIID_OWNERID_OWNERTYPE($inputData['cbi_id'], $cbi_owner_id, $cbi_postownertype);
            if (is_null($carbasicdata) || count($carbasicdata) == 0) {
                $messageCode = '011103001';
                throw new \Exception($messageCode);
            }
            
            if (is_null($carbasicdata[0]['dbir_expiredate']) || $carbasicdata[0]['dbir_expiredate'] < date('Y-m-d')) {
                if (!PayUsedCarPost::CreateActiveDays(null, $querydata['dcil_availabledays'], $savadata)){ 
                    throw new \Exception($messageCode);
                }
            } else {
                if (!$this->CreateActiveDays($carbasicdata[0]['dbir_expiredate'], $querydata['dcil_availabledays'], $savadata)) { 
                    throw new \Exception($messageCode);
                }
            }
            
            if (!$this->CreateNeedInsertData($md_id, $inputData['dcil_id'], $inputData['cbi_id'], $savadata)) {
                throw new \Exception($messageCode);
            }

             if (!$this->InsertDBIR($savadata)) {
                throw new \Exception($messageCode);
            }*/
             //取得現在庫存資料
            $bankService = new \App\Services\BankService;
           /* if (!$bankService->getMemBuyPointQuery(null, $md_id, 1, $pointData, $messageCode)) {
              throw new \Exception($messageCode);
            }*/
            if ( !$bankService->modifyMemBuyPoint($md_id, 2, $querydata['dcil_depositamount'], $dbir_id, 1, null, $add = false, $messageCode)
                                                  /*memPointModify($inputData['sat'], $inputData['modacc'], $inputData['modvrf'], 8, $dbir_id, $querydata['dcil_depositamount'], $stockPoint, $messageCode)*/) {
              throw new \Exception($messageCode);
            }
            $messageCode = '000000000';
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($e);
            }
        }

        //回傳值
        $resultArray = \App\Library\Commontools::ResultProcess($messageCode, $resultData);
        \App\Library\Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sat', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'md_clienttype', 1, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'dcil_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, true, false)) {
            return false;
        }
        return true;
    }
    /**
     * 建立刊登日期，及有效日期
     * @param type $dbir_expiredate
     * @param type $availabledays
     * @param type $insertdata 
     * @return boolean
     */
     function CreateActiveDays($dbir_expiredate, $availabledays, &$savadata) {
        try {
            if (is_null($dbir_expiredate)) {
                $datenow = new \Datetime();
                $savadata['dbir_activatedate'] = $datenow-> format('Y-m-d H:i:s');
                $savadata['dbir_expiredate'] = $datenow-> modify("+$availabledays day") -> format('Y-m-d H:i:s');
            } else {
                $expiredate = new \Datetime();
                $savadata['dbir_activatedate'] = $dbir_expiredate;
                $savadata['dbir_expiredate'] =$expiredate->createFromFormat('Y-m-d H:i:s', $dbir_expiredate) -> modify("+$availabledays day") -> format('Y-m-d H:i:s'); 
            }
            return true;
        } catch (\Exception $e) {
             \App\Models\ErrorLog::InsertData($e);
            return false;
        }
      
    }
     /**
      * 產生需新增，更新的，Array
      * @param type $md_id
      * @param type $dcil_id
      * @param type $cdi_id
      * @param type $savadata
      * @return boolean
      */
     function CreateNeedInsertData ($md_id, $dcil_id, $cdi_id, &$savadata) {
        try {
            if (is_null($md_id) || is_null($dcil_id)  ) {
                return false;
              }
            $dbir_id =  \App\Library\Commontools::NewGUIDWithoutDash();
            $savadata = array_add($savadata, 'md_id', $md_id);       $savadata = array_add($savadata, 'dbir_id', $dbir_id); 
            $savadata = array_add($savadata, 'dbir_object_type', 1); $savadata = array_add($savadata, 'dbir_object_id', $cdi_id);
            $savadata = array_add($savadata, 'dcil_id', $dcil_id);  
            return true;
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
     /**
      * 新增icr_depositbuyitmerec
      * @param type $arraydata 
      * @return boolean
      */
    private  function InsertDBIR($arrarydata) {
        try {
            $insertdata['dbir_id'] = $arrarydata['dbir_id'];
            $insertdata['md_id'] = $arrarydata['md_id'];
            $insertdata['dbir_object_type'] = $arrarydata['dbir_object_type'];
            $insertdata['dbir_object_id'] = $arrarydata['dbir_object_id'];
            $insertdata['dcil_id'] = $arrarydata['dcil_id'];
            $insertdata['dbir_activatedate'] = $arrarydata['dbir_activatedate'];
            $insertdata['dbir_expiredate'] = $arrarydata['dbir_expiredate'];

            return   \App\Models\ICR_DepositBuyItmErec::InsertData($insertdata);
        } catch (\Exception $e) {
              \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
   
}
