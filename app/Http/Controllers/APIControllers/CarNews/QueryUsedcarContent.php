<?php

namespace App\Http\Controllers\APIControllers\CarNews  ;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** queryusedcarcontent	按傳入ID取用車輛內容回傳 * */
class QueryUsedcarContent {

    function queryusedcarcontent() {
        $functionName = 'queryusedcarcontent';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;

        try {
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!QueryUsedcarContent::CheckInput($inputData)) {
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
                $datenow = date("Y-m-d");
                $querydata['carbasicinfo'] = \App\Models\ICR_CarBasicInfo::GetData_ByCBIID($inputData['cbi_id'],$datenow);
            } else if ($inputData['operation_type'] == 1) {
                $querydata['carbasicinfo'] =\App\Models\ICR_CarBasicInfo::GetData_ByCBIID($inputData['cbi_id'],null);
            } else {
                //無效的操作動作，請重新輸入
                $messageCode = '011101002';
                throw new \Exception($messageCode);
            }
            $guaranteeId = explode(",",$querydata['carbasicinfo'][0]['cbi_guaranteeitems']);
            $querydata['carguaranteeitems'] =\App\Models\ICR_CarGuaranteeItems::GetData($guaranteeId); 
            $equiptmentsId = explode(",",$querydata['carbasicinfo'][0]['cbi_carequiptments']);
            $querydata['carequiptments'] = \App\Models\ICR_CarEquiptments::GetData($equiptmentsId);
            if (!QueryUsedcarContent::CreateResultData($querydata, $resultData)) {
                throw new \Exception($messageCode);
            }
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
        
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'operation_type', 1, false, false)) {
            return false;
        }

        return true;
    }
    
    function CreateResultData($querydata, &$resultData) {
        try {
            
            $resultData = [ 'cbi_id'                 => $querydata['carbasicinfo'][0]['cbi_id']
                           ,'cbi_postownertype'      => $querydata['carbasicinfo'][0]['cbi_postownertype']
                           ,'cbi_owner_id'           => $querydata['carbasicinfo'][0]['cbi_owner_id']
                           ,'cbi_advertisementtitle' => $querydata['carbasicinfo'][0]['cbi_advertisementtitle']
                           ,'cbi_webaddress'         => $querydata['carbasicinfo'][0]['cbi_webaddress']
                           ,'cbi_caryearstyle'       => $querydata['carbasicinfo'][0]['cbi_caryearstyle']
                           ,'cbi_manufactoryyear'    => $querydata['carbasicinfo'][0]['cbi_manufactoryyear']
                           ,'cbi_manufactorymonth'   => $querydata['carbasicinfo'][0]['cbi_manufactorymonth'] 
                           ,'cbi_licensingyear'      => $querydata['carbasicinfo'][0]['cbi_licensingyear']
                           ,'cbi_licensingmonth'     => $querydata['carbasicinfo'][0]['cbi_licensingmonth']
                           ,'cbi_carbrand'           => $querydata['carbasicinfo'][0]['cbi_carbrand']
                           ,'cbi_brandmodel'         => $querydata['carbasicinfo'][0]['cbi_modelstyle']
                           ,'cbi_carbodytype'        => $querydata['carbasicinfo'][0]['cbi_carbodytype']
                           ,'cbi_carsource'          => $querydata['carbasicinfo'][0]['cbi_carsource']
                           ,'cbi_carlocation'        => $querydata['carbasicinfo'][0]['cbi_carlocation']
                           ,'cbi_saleprice'          => $querydata['carbasicinfo'][0]['cbi_saleprice']
                           ,'cbi_carbodycolor'       => $querydata['carbasicinfo'][0]['cbi_carbodycolor']
                           ,'cbi_carinteriorcolor'   => $querydata['carbasicinfo'][0]['cbi_carinteriorcolor']
                           ,'cbi_licensestatus'      => $querydata['carbasicinfo'][0]['cbi_licensestatus']
                           ,'cbi_mileage'            => $querydata['carbasicinfo'][0]['cbi_mileage']
                           ,'cbi_everrepair'         => $querydata['carbasicinfo'][0]['cbi_everrepair']
                           ,'cbi_carvin'             => $querydata['carbasicinfo'][0]['cbi_carvin']
                           ,'cbi_displacement'       => $querydata['carbasicinfo'][0]['cbi_displacement']
                           ,'cbi_fueltype'           => $querydata['carbasicinfo'][0]['cbi_fueltype']
                           ,'cbi_transmissionsystem' => $querydata['carbasicinfo'][0]['cbi_transmissionsystem']
                           ,'cbi_drivemode'          => $querydata['carbasicinfo'][0]['cbi_drivemode']
                           ,'cbi_carseats'           => $querydata['carbasicinfo'][0]['cbi_carseats']
                           ,'cbi_cardoors'           => $querydata['carbasicinfo'][0]['cbi_cardoors']
                           ,'cbi_guaranteeitems'     => $querydata['carbasicinfo'][0]['cbi_guaranteeitems']
                           ,'cbi_carequiptments'     => $querydata['carbasicinfo'][0]['cbi_carequiptments']
                           ,'cbi_htmldescript'       => $querydata['carbasicinfo'][0]['cbi_htmldescript']
                           ,'cbi_carevideolink'      => $querydata['carbasicinfo'][0]['cbi_carevideolink']
                           ,'cbi_salestatus'         => $querydata['carbasicinfo'][0]['cbi_salestatus']
                           ,'cbl_fullname'           => $querydata['carbasicinfo'][0]['cbl_fullname']
                           ,'cbm_fullname'           => $querydata['carbasicinfo'][0]['cbm_fullname']
                           ,'cms_fullname'           => $querydata['carbasicinfo'][0]['cms_fullname']
                           ,'cbt_fullname'           => $querydata['carbasicinfo'][0]['cbt_fullname']
                           ,'cse_sourcename'         => $querydata['carbasicinfo'][0]['cse_sourcename']
                           ,'cbc_colorname'          => $querydata['carbasicinfo'][0]['cbc_colorname']
                           ,'cbi_salestatus'         => $querydata['carbasicinfo'][0]['cbi_salestatus']
                           ,'cic_colorname'          => $querydata['carbasicinfo'][0]['cic_colorname']
                           ,'cln_cityname'           => $querydata['carbasicinfo'][0]['cln_cityname']
                          ];
                          
                          foreach ($querydata['carbasicinfo'] as $row) {
                              $resultData['car_photo'][] = ['cps_picscategory' => $row['cps_picscategory'],
                                                            'cps_picpath'      => $row['cps_picpath']];
                          }
                           
                          foreach ($querydata['carguaranteeitems'] as $row) {
                              $resultData['cgi_itemname'][] = ['cgi_itemname' => $row['cgi_itemname'],
                                                               'cgi_listorder'=> $row['cgi_listorder']];
                          }
                          
                          foreach ($querydata['carequiptments'] as $row) {
                              $resultData['ces_ietmesname'][] = ['ces_category'  => $row['ces_category'],
                                                                 'ces_ietmesname'=> $row['ces_ietmesname'],
                                                                 'ces_listorder' => $row['ces_listorder']];
                          }
                          
                          
                          
            return true;                
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
}
