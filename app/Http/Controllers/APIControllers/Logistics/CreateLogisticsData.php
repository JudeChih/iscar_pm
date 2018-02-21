<?php

namespace App\Http\Controllers\APIControllers\Logistics;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** createlogisticsdata	scl，scg更新付款狀態 改以付款 * */
class CreateLogisticsData {
   function createlogisticsdata() {
        $functionName = 'createlogisticsdata';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            if($inputData == null){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //輸入值
            if(!CreateLogisticsData::CheckInput($inputData)){
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
             //檢查活動券狀態
            if (!CreateLogisticsData::CheckCouponStatus($inputData['scm_id'], $sd_id,$messageCode)) {
                throw new \Exception($messageCode);
            }
           //檢查是否需新增物流狀態紀錄
            if ( !CreateLogisticsData::CheckCouponDataLogistics($inputData, $inputData['scm_id'], $inputData['scg_id'], $sd_id, $md_id, $scl_id) ) {
                $messageCode = '999999999';
                throw new \Exception($messageCode);
            }
            $resultData['scl_id'] = $scl_id;
            $messageCode = '000000000';
       } catch(\Exception $e) {
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sat', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_id', 32, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scg_id', 0, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scl_receivername', 0, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scl_receivermobile', 0, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scl_receiverphone', 0, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scl_email', 0, true, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scl_postcode', 0, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scl_city', 0, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scl_district', 0, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scl_receiveaddress', 0, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'scl_delivery_time', 0, false, false)) {
            return false;
        }
        return true;
    }

    /**
     * 檢查活動券狀態
     * @param type $scm_id
     * @param type $sd_id
     * @param type $messageCode
     * @return boolean
     */
    function CheckCouponStatus($scm_id, &$sd_id, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_m::GetData($scm_id);

            if (count($querydata) == 0) {
                //010904001 查無商品項目，請確認後重發
                $messageCode = '010904001';
                return false;
            }
            if (strtotime('now') < strtotime($querydata[0]['scm_startdate'])) {
                //010904002 該活動尚未開始，請選用其他活動券
                $messageCode = '010904002';
                return false;
            }
            if (strtotime('now') > strtotime($querydata[0]['scm_enddate'])) {
                //010904003 該活動已截止，請選用其他活動券
                $messageCode = '010904003';
                \App\Models\ICR_ShopCouponData_m::UpdateData_PostStatus($scm_id, 3);
                return false;
            }
            if ($querydata[0]['scm_poststatus'] != 1) {
                //010904004 該活動已停刊，請選用其他活動券或稍後再試
                $messageCode = '010904004';
                //\App\Models\ICR_ShopCouponData_m::UpdateData_PostStatus($scm_id, 3);
                return false;
            }
            $sd_id =  $querydata[0]['sd_id'];
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
        }
    }

    public function CheckCouponDataLogistics($arraydata, $scm_id, $scg_id, $sd_id, $md_id, &$scl_id) {
        try {
             $querydata = \App\Models\ICR_ShopCouponData_m::GetData($scm_id);
            //如果不是商品，不用物流，故結束。
            if ($querydata[0]['scm_producttype'] != 2 ) {
               return true;
            }
            $scl_id = Commontools::NewGUIDWithoutDash();
            $savedata = [
                       'scl_id' => $scl_id,
                       'scg_id' => $scg_id,
                       'scm_id' => $scm_id,
                       'sd_id' => $sd_id,
                       'md_id' => $md_id,
                       'scl_receivername' => $arraydata['scl_receivername'],
                       'scl_receivermobile' => $arraydata['scl_receivermobile'],
                       'scl_receiverphone' => $arraydata['scl_receiverphone'],
                       'scl_email' => $arraydata['scl_email'],
                       'scl_postcode' => $arraydata['scl_postcode'],
                       'scl_city' => $arraydata['scl_city'],
                       'scl_district' => $arraydata['scl_district'],
                       'scl_receiveaddress' => $arraydata['scl_receiveaddress'],
                       'scl_delivery_time' => $arraydata['scl_delivery_time']
            ];
            $LogisticsRepo = new \App\Repositories\ICR_SCLRepository;
            if ( ! $LogisticsRepo -> InsertData($savedata) ) {
                 return false;
            }
            return true;
         } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }


}