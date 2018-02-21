<?php

namespace App\Http\Controllers\APIControllers\CarNews;
use DB;
use App\Library\Commontools;
use Illuminate\Support\Facades\Input;
/** postusedcardata	新增車輛資料* */
class PostUsedCarData {

    function postusedcardata() {
        $functionName = 'postusedcardata';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        DB::beginTransaction();
        try {
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!PostUsedCarData::CheckInput($inputData)) {
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
            $cbi_id = \App\Library\Commontools::NewGUIDWithoutDash();
            if($inputData['operation_type'] == 0 ) {
               if (is_null($inputData['sd_id'])) {
                   //會員非本商家管理者，請確認後再試
                   $messageCode = '010902002';
                   throw new \Exception($messageCode);
               }
                //檢查「店家」、「管理員」權限
               if (!\App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                   throw new \Exception($messageCode);
               }
               $cbi_postownertype = 0;
               $cbi_owner_id = $inputData['sd_id']; 
               if (!PostUsedCarData::InsertBasicInfoData($inputData, $cbi_postownertype, $cbi_owner_id, $cbi_id)) {
                   $messageCode = '1111';
                   throw new \Exception($messageCode);
               }
            } else if ($inputData['operation_type'] == 1 ) {
               $cbi_postownertype = 1;
               $cbi_owner_id = $md_id;
               if (!PostUsedCarData::InsertBasicInfoData($inputData, $cbi_postownertype, $cbi_owner_id, $cbi_id)) {
                   $messageCode = '2222';
                   throw new \Exception($messageCode);
               }
            } else {
               //無效的操作動作，請重新輸入
               $messageCode = '999999989';
               throw new \Exception($messageCode);
            }
            if (!PostUsedCarData::InsertCarPicturesData($inputData['car_photo'], $cbi_id)) {
               $messageCode = '3333';
               throw new \Exception($messageCode);
            }
             //新增完成
            $messageCode = '011102000';
            $resultData['cbi_id'] = $cbi_id;
            DB::commit(); 
        } catch (\Exception $e) {
            DB::rollBack();
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sat', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'operation_type', 1, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_advertisementtitle', 50, false, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_webaddress', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_caryearstyle', 4, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_manufactoryyear', 4, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_manufactorymonth', 2, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_licensingyear', 4, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_licensingmonth', 2, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carbrand', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_brandmodel', 0, false, false)) {
            return false;
          }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_modelstyle', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carbodytype', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carsource', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carlocation', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_saleprice', 15, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carshopprice', 15, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carbodycolor', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carinteriorcolor', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_licensestatus', 1, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_mileage', 10, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_everrepair', 1, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carvin', 50, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_displacement', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_fueltype', 1, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_transmissionsystem', 1, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_drivemode', 1, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carseats', 1, true, false)) {
            return false;
        } 
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_cardoors', 1, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_guaranteeitems', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carequiptments', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_htmldescript', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_carevideolink', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_postmagazinetitle', 10, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'cbi_postmagazinecontent', 50, true, false)) {
            return false;
        }
        /*if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'car_photo', 0, false, false)) {
            return false;
        } */

        return true;
    }

   /**
    * 新增ICR_CarBasicInfo 資料
    *
    */                       
    private function InsertBasicInfoData($InsertData, $cbi_postownertype, $cbi_owner_id, $cbi_id) {
      try {
          $InsertData['cbi_postownertype'] = $cbi_postownertype ;
          $InsertData['cbi_owner_id'] = $cbi_owner_id ;
          $InsertData['cbi_id'] = $cbi_id ;
          return \App\Models\ICR_CarBasicInfo::InsertData($InsertData);
      } catch (\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return false;
      }
    }
     /**
    * ICR_CarPictures 資料
    *
    */
    private function InsertCarPicturesData($arraydata, $cbi_id) {
      try {
          foreach ($arraydata as $row) {
            $InsertData['cps_id'] = \App\Library\Commontools::NewGUIDWithoutDash();
            $InsertData['cbi_id'] = $cbi_id ;
            $InsertData['cps_picscategory'] = $row['cps_picscategory'] ;
            $InsertData['cps_picpath'] = $row['cps_picpath'] ;
            if (!\App\Models\ICR_CarPictures::InsertData($InsertData)) {
               return false;
            }
          }
          return true;
      } catch (\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return false;
      }
    }
}
