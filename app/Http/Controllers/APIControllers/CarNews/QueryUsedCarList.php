<?php

namespace App\Http\Controllers\APIControllers\CarNews;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** queryusedcarlist	列表顯示二手車刊登項目查詢結果 * */
class QueryUsedCarList {
   function queryusedcarlist() {
        $functionName = 'queryusedcarlist';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //輸入值
            if(!QueryUsedCarList::CheckInput($inputData)){
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
            if ($inputData['operation_type'] == 0) {
              if (!QueryUsedCarList::UsedCarList($inputData, $messageCode, $querydata)) {
                 throw new \Exception($messageCode);
              } 
            } else if ($inputData['operation_type'] == 1) {
              if (!QueryUsedCarList::UsedCarManageList($inputData, $messageCode, $querydata)) {
                 throw new \Exception($messageCode);
              }
            } else if ($inputData['operation_type'] == 2) {
              if (!QueryUsedCarList::UsedCarQuickSearch($inputData, $messageCode, $querydata)) {
                 throw new \Exception($messageCode);
              }
            } else {
               //無效的操作動作，請重新輸入
               $messageCode = '011101002';
               throw new \Exception($messageCode);
            }
            if (!QueryUsedCarList::RecreateResultData($querydata, $inputData['operation_type'], $resultData)) {
                throw new \Exception($messageCode);  
            }
            
            $messageCode ='011101000';
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

        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'operation_type', 1, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'orderby_createdate', 20, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'queryamount', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_owner_id', 36, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carbrand', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carbodytype', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_saleprice_low', 40, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_saleprice_high', 40, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_brandmodel', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_modelstyle', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carsource', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carlocation', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carbodycolor', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carinteriorcolor', 0, true, false)) {
            return false;
        }
        /*if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_mileage_low', 10, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_mileage_high', 10, true, false)) {
            return false;
        }  */
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_displacement', 0, true, false)) {
            return false;
        } 
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_fueltype', 0, true, false)) {
            return false;
        } 
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_transmissionsystem', 0, true, false)) {
            return false;
        } 
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_drivemode', 0, true, false)) {
            return false;
        } 
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carseats', 0, true, false)) {
            return false;
        } 
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_cardoors', 0, true, false)) {
            return false;
        } 
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_manufactoryyear', 0, true, false)) {
            return false;
        } 
        return true;
    }
    
    function Check_InputValues01($inputData, &$messageCode) {
        if($inputData['cbi_owner_id'] == null ) {
           //查詢條件有誤，請重新輸入
           $messageCode = '011101001';
           return false; 
        }
    
        return true;
    }
    
    /**
     * 檢查輸入值， 確認有無值。
     *
     *
     */
    function Check_InputValues02($inputData, &$messageCode) {
        if($inputData['cbi_owner_id'] != null) {
           //查詢條件有誤，請重新輸入
           $messageCode = '011101001';
           return false; 
        }
        if(($inputData['queryamount']) == null && ($inputData['orderby_createdate']) == null &&
           ($inputData['cbi_carbrand']) == null &&  ($inputData['cbi_carbodytype']) == null &&
           ($inputData['cbi_saleprice_low'])== null && ($inputData['cbi_saleprice_high'])== null &&
           ($inputData['cbi_brandmodel'])== null && ($inputData['cbi_modelstyle'])== null &&
           ($inputData['cbi_carsource'])== null && ($inputData['cbi_carlocation']) == null&&
           ($inputData['cbi_carbodycolor'])== null && ($inputData['cbi_carinteriorcolor'])== null &&
           ($inputData['cbi_displacement'])== null && ($inputData['cbi_fueltype'])== null &&
           ($inputData['cbi_transmissionsystem'])== null && $inputData['cbi_drivemode']== null &&
           ($inputData['cbi_carseats'])== null && ($inputData['cbi_cardoors'])== null && 
           ($inputData['cbi_manufactoryyear'])== null ) {
              //查詢條件有誤，請重新輸入
              $messageCode = '011101001';
              return false; 
        } 
        
        return true;
    }
    
    /**
     * 商家列表(operation_type == 0)
     * @param type $inputData
     * @param type $messageCode
     * @param type $resultData
     */
    function UsedCarList($inputData, &$messageCode, &$querydata) {
        $datenow = date("Y-m-d");
        try {
            if(!QueryUsedCarList::Check_InputValues01($inputData, $messageCode)) {
              return false; 
            }
            $querydata = \App\Models\ICR_CarBasicInfo::GetCarDataList($inputData, $datenow);
            return true;
        } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
         return false;
        }
    }
    /**
     * 商家管理列表 (operation_type == 1)
     * @param type $inputData
     * @param type $messageCode
     * @param type $resultData
     * @return boolean
     */
    function UsedCarManageList($inputData, &$messageCode, &$querydata) {
        //$datenow = date("Y-m-d");
        try {
            if(!QueryUsedCarList::Check_InputValues01($inputData, $messageCode)) {
              return false; 
            }
            $querydata = \App\Models\ICR_CarBasicInfo::GetCarDataList($inputData, null);
            return true;
        } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
         return false;
        }
    }
    /**
     * 快速搜詢 (operation_type == 2)
     * @param type $inputData
     * @param type $messageCode
     * @param type $resultData
     */
    function UsedCarQuickSearch($inputData, &$messageCode, &$querydata) {
        $datenow = date("Y-m-d");
        try {
            if(!QueryUsedCarList::Check_InputValues02($inputData, $messageCode)) {
              return false; 
            }
            $inputData['cbi_salestatus'] = 0;
            $querydata = \App\Models\ICR_CarBasicInfo::GetCarDataList($inputData, $datenow);
            return true;
        } catch(\Exception $e) {
         \App\Models\ErrorLog::InsertData($e);
         return false;
        }
    }
  
    
     /**
      * 重組回傳參數。
      * @param type $arraydata
      * @param type $type
      * @param type $resultData
      */
    private function RecreateResultData ($arraydata, $type, &$resultData) {
      try {
        $createdateMin = null;
        $post_expiredate = null;
        $group = array();
        foreach ( $arraydata as $row ) {
           $group[$row['cbi_id']][] = $row;
        }
        if(in_array($type,array(0,2))) {
            $count = 0;
            foreach ($group as $rowlevel1) {
              $bool = false;
              foreach ($rowlevel1 as $rowlevel2) {
                  if($rowlevel2['ffbl_functiontype'] == 0 && $rowlevel2['ffbl_functionvalue'] == 1) {
                     $bool = true;
                  }
              }
              if ($bool == false) {
                  unset($group[$count]);
              }
              $count =  $count + 1;
           }
        }
       
        foreach ($group as $rowlevels) {
           foreach ($rowlevels as $rowlevel2) {
              $itemnameArray[] = ["dcil_itemname "    => $rowlevel2['dcil_itemname']];
              $functionArray[] = ["ffbl_functiontype" => $rowlevel2['ffbl_functiontype'],
                                  "ffbl_functionvalue"=> $rowlevel2['ffbl_functionvalue']];
              if (is_null($createdateMin) || $createdateMin > $rowlevel2['create_date']) {
                  $createdateMin = $rowlevel2['create_date'];
              }
              if ((is_null($post_expiredate) && !is_null($rowlevel2['dbir_expiredate'])) || $post_expiredate < $rowlevel2['dbir_expiredate'] &&
                  ($rowlevel2['ffbl_functiontype'] == 0 && $rowlevel2['ffbl_functionvalue'] == 1 )) {
                 $post_expiredate = $rowlevel2['dbir_expiredate'];
              } 
              $cbi_id = $rowlevel2['cbi_id'];
              $cbi_advertisementtitle = $rowlevel2['cbi_advertisementtitle'];
              $cbi_saleprice = $rowlevel2['cbi_saleprice'];
              $cbl_fullname = $rowlevel2['cbl_fullname'];
              $cbm_fullname = $rowlevel2['cbm_fullname'];
              $cms_fullname = $rowlevel2['cms_fullname'];
              $cbi_manufactoryyear = $rowlevel2['cbi_manufactoryyear'];
              $cps_picpath = $rowlevel2['cps_picpath'];
              $cbi_salestatus = $rowlevel2['cbi_salestatus'];
               
           }
           $resultData['queryresult'][] = ['cbi_id'                 => $cbi_id,
                                           'cbi_advertisementtitle' => $cbi_advertisementtitle,
                                           'cbi_saleprice'          => $cbi_saleprice,
                                           'cbl_fullname'           => $cbl_fullname,
                                           'cbm_fullname'           => $cbm_fullname,
                                           'cms_fullname'           => $cms_fullname,
                                           'cbi_manufactoryyear'    => $cbi_manufactoryyear,
                                           'cps_picpath'            => $cps_picpath,
                                           'cbi_salestatus'         => $cbi_salestatus,
                                           'post_expiredate'        => $post_expiredate,
                                           'dcil_itemname'          => $itemnameArray,
                                           'promote_function'       => $functionArray];
           $itemnameArray = array();
           $functionArray = array();
           $post_expiredate = null;
        }
        if(count($arraydata) == 0) {
           $resultData['queryresult'] = null;
        }
        $resultData['createdate_min'] = $createdateMin;
        return true;
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return false;
      }
    }
    
    
    
    
    
}