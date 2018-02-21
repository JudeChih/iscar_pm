<?php

namespace App\Http\Controllers\APIControllers\ShopService;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** shopservicequequery	查詢指定商家近四日之排隊狀況 * */
class ShopServiceQueQuery {
    function shopservicequequery(){
        $functionName = 'shopservicequequery';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try {
              if($inputData == null){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //輸入值
            if(!ShopServiceQueQuery::CheckInput($inputData)){
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
             //檢查「店家」、「管理員」權限
            if (!\App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            if ($inputData['operation_type'] == 0 ) {
               $result = \App\Models\ICR_ShopServiceQue_q::Query_QueDataToday($inputData['sd_id']);
            } else if ($inputData['operation_type'] == 1) {
               $result = \App\Models\ICR_ShopServiceQue_q::Query_QueDataIncludeFourDay($inputData['sd_id']);
            }
            //檢查排隊紀錄
            if(count($result) == 0 || $result == null) {
                //無此商家編號，請重新輸入
                $messageCode = '010901002';
                throw new \Exception($messageCode);
            }
           // \App\Models\ErrorLog::InsertLog(json_encode(json_encode($result)));
            if ($inputData['operation_type'] == 0 ) {
                 if(!ShopServiceQueQuery::Create_ResultData_Today($result,$resultData)) {
                   throw new \Exception($messageCode);
                }  
            }
            if ($inputData['operation_type'] == 1 ) {
                if(!ShopServiceQueQuery::Create_ResultData($result,$resultData)) {
                   throw new \Exception($messageCode);
                } 
            }
            $messageCode = '000000000';
        } catch (\Exception $e) {
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'operation_type', 1, false, false)) {
            return false;
        }
        return true;
    }
    
    /**
     * 創建回傳值
     * @param type $result
     * @param type &$resultData
     * @return boolean
     */
    function Create_ResultData($result ,&$resultData) {
      try {
           $ssqq_questarttime = null;
           $date = null;
           $total_queue = 0;
           $total_serviced = 0;
           $last_service_no = 0;
           foreach($result as $rowdata) { 
              if (is_null($ssqq_questarttime)) {
                 $ssqq_questarttime = $rowdata['ssqq_questarttime'];
              }
              if ($ssqq_questarttime == $rowdata['ssqq_questarttime']) {
                if(!ShopServiceQueQuery::Count_ArrayBasicVaules($rowdata,$total_queue,$total_serviced,$last_service_no) ||
                   !ShopServiceQueQuery::Create_QueryArray($rowdata, $queue_array)) {
                     return false;
                }
              }  
              if ($ssqq_questarttime != $rowdata['ssqq_questarttime']) {
                if(!ShopServiceQueQuery::Creaet_Serviceque_Array($resultData, $ssqq_questarttime, $total_queue, $total_serviced, $last_service_no, $queue_array)){
                     return false;
                }
                $ssqq_questarttime = $rowdata['ssqq_questarttime'];
                if(!ShopServiceQueQuery::Count_ArrayBasicVaules($rowdata,$total_queue,$total_serviced,$last_service_no) ||
                   !ShopServiceQueQuery::Create_QueryArray($rowdata, $queue_array)) {
                     return false;
                }
              }
           }
           if(!ShopServiceQueQuery::Creaet_Serviceque_Array($resultData, $ssqq_questarttime, $total_queue, $total_serviced, $last_service_no, $queue_array)){
              return false;
           }
           return true;
      } catch (\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
      }
    }
    
    /**
     * 創建回傳值
     * @param type $result
     * @param type &$resultData
     * @return boolean
     */
    function Create_ResultData_Today($result ,&$resultData) {
      try {
           $ssqq_questarttime = null;
           $date = null;
           $total_queue = 0;
           $total_serviced = 0;
           $last_service_no = 0;
           //$now =  new \DateTime('now');
           $now = date("Y-m-d"); 
           foreach($result as $rowdata) { 
              if (is_null($ssqq_questarttime)) {
                 $ssqq_questarttime = $rowdata['ssqq_questarttime'];
              }
              if ($ssqq_questarttime == $rowdata['ssqq_questarttime'] &&  $rowdata['ssqq_questarttime'] == $now) {
                if(!ShopServiceQueQuery::Count_ArrayBasicVaules($rowdata,$total_queue,$total_serviced,$last_service_no) ||
                   !ShopServiceQueQuery::Create_QueryArray($rowdata, $queue_array)) {
                     return false;
                }
              } 
           }
           if(!ShopServiceQueQuery::Creaet_Serviceque_Array($resultData, $ssqq_questarttime , $total_queue, $total_serviced, $last_service_no, $queue_array)){
              return false;
           }
           return true;
      } catch (\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
      }
    }
    
    /**
     * 計算每日排隊，服務總數
     * @param type $values
     * @param type &$total_queue
     * @param type &$total_serviced
     * @param type &$last_service_no
     * @return boolean
     */
    private function Count_ArrayBasicVaules($values,&$total_queue,&$total_serviced,&$last_service_no) {
      try {
          $total_queue = $total_queue + 1;
          if($values['ssqq_usestatus'] == 2) {
             $total_serviced = $total_serviced + 1;
             if((int)$values['ssqq_queserno'] > (int)$last_service_no) {
                $last_service_no = $values['ssqq_queserno'];
             }
          }
          return true;
      } catch(\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
           return false;
      }
    }
   
    /**
     * 陣列，每日服務排隊
     * @param type $values
     * @param type &$total_queue
     * @param type &$total_serviced
     * @param type &$last_service_no
     * @return boolean
     */
    private function Create_QueryArray($values, &$queue_array) {
       try {
         $queue_array[] = [
                      'ssqq_id'         => $values['ssqq_id']
                    , 'ssqq_queserno'   => $values['ssqq_queserno']
                    , 'md_id'           => $values['md_id']
                    , 'md_cname'        => $values['md_cname']
                    , 'ssd_picturepath' => $values['ssd_picturepath']
                    , 'ssqd_id'         => $values['ssqd_id']
                    , 'ssqd_title'      => $values['ssqd_title']
                    , 'ssqq_usestatus'  => $values['ssqq_usestatus']
                    , 'create_date'     => $values['CREATE_DATE']
                    , 'ssqq_receivtime' => $values['ssqq_receivtime']
                    , 'ci_serno'        => $values['ci_serno']
                    , 'ssqq_receivtime' => $values['ssqq_receivtime']
                ];
         return true;
       } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
         return false; 
       }
    } 
    
    
    
    function Creaet_Serviceque_Array(&$resultData, $date, &$total_queue, &$total_serviced, &$last_service_no, &$queue_array){
       try {
         $resultData['serviceque_array'][] = [
                 'date'            => $date
                ,'total_queue'     => $total_queue
                ,'total_serviced'  => $total_serviced
                ,'last_service_no' => $last_service_no
                ,'queue_array'     => $queue_array
                ];
         $total_queue = 0;
         $total_serviced = 0;
         $last_service_no = 0;
         $queue_array = array();
         return true;
       } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
         return false; 
       } 
    }
    
    
    
    
    
    
    
    
}