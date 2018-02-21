<?php

namespace App\Http\Controllers\APIControllers\Shop;

use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class ShopListQuery {
    function shoplistquery() {

        $functionName = 'shoplistquery';
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

            if (!ShopListQuery::CheckInput($inputData)) {
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
            $resultData['shoplistarray'] = ShopListQuery::GetShopListData($inputData, $messageCode);
            if (is_null($messageCode) && strlen($messageCode) == 0) {
                $messageCode = '011101000';
            }
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'operation_type', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'spm_serno', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_zipcode', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_country', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_shopname', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_lat', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_lng', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'distance', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'startid', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'queryamount', 0, true, false)) {
            return false;
        }
        if (!array_key_exists('startid', $value)) {
            $value['startid'] = null;
        }
        if (!array_key_exists('queryamount', $value) || strlen($value['queryamount']) == 0) {
            $value['queryamount'] = 100;
        }
        if ($value['queryamount'] > 500) {
            $value['queryamount'] = 500;
        }
        if (!array_key_exists('distance', $value) || strlen($value['distance']) == 0) {
            $value['distance'] = 0;
        }
        return true;
    }

    /**
     * 取得店家資料
     * @param type $inputData
     * @param type $messageCode
     * @return type
     */
    function GetShopListData($inputData, &$messageCode) {
        try {
            if($inputData['operation_type'] == 0 ) {
              if(is_null($inputData['spm_serno'])) {
                 //選單編號輸入格式錯誤，請重新輸入
                 $messageCode = '010901003';
                 return null;
              }
              $querydata = \App\Models\ICR_ShopData::GetShopDataList($inputData['spm_serno'], $inputData['sd_country'],/*$inputData['sd_zipcode'],*/ $inputData['sd_shopname'], $inputData['startid'], $inputData['queryamount'], $inputData['sd_lat'], $inputData['sd_lng'], $inputData['distance']) ;
            } /* 停用此查詢 *//*else if ($inputData['operation_type'] == 1) {
               if(is_null($inputData['sd_zipcode'])) {
                 //選單編號輸入格式錯誤，請重新輸入
                 $messageCode = '010901003';
                 return null;
              }
              $querydata = \App\Models\ICR_ShopData::GetShopDataList(null, $inputData['sd_zipcode'], null, $inputData['startid'], $inputData['queryamount']) ;
            } */else if ($inputData['operation_type'] == 2) {
                /*if(is_null($inputData['sd_shopname'])) {
                 //選單編號輸入格式錯誤，請重新輸入
                 $messageCode = '010901003';
                 return null;
              }*/
              $querydata = \App\Models\ICR_ShopData::GetShopDataList($inputData['spm_serno'], $inputData['sd_country'], $inputData['sd_shopname'], $inputData['startid'], $inputData['queryamount'],$inputData['sd_lat'], $inputData['sd_lng'], $inputData['distance']) ;
            } else {
              //傳入值格式內容格式有誤，請重新輸入
              $messageCode = '010901006';
              return null;
            }
            if( is_null($querydata) || count($querydata) == 0 ) {
              //處理完成
              $messageCode = '000000000';
              return null;
            }
            return ShopListQuery::TransDataToShopListArray($querydata);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

   
   public function checkShopBind($sd_id) {
      try {
              $queryData = \App\Models\ICR_SdmdBind::GetData_BySD_ID($sd_id, true);
              if ( count($queryData) > 0 ) {
                return 1;
              } else {
                return 0;
              }
      } catch(\Exception $ex) {
         \App\Models\ErrorLog::InsertData($ex);
          return null;
      }
   }
    
    private function TransDataToShopListArray ($arraydata) {
      try {
        $post_expiredate = null;
        $group = array();
        foreach ( $arraydata as $row ) {
           $group[$row['sd_id']][] = $row;
        }
        $count = 0;
        foreach ($group as $rowlevel1) {
          $bool = false;
          foreach ($rowlevel1 as $rowlevel2) {
            if(is_null($rowlevel2['dbir_expiredate'])) {
               $bool = true;
               break;
            }
            if($rowlevel2['ffbl_functiontype'] == 0 && $rowlevel2['ffbl_functionvalue'] == 1) {
               $bool = true;
            }
          }
          if ($bool == false) {
              unset($group[$count]);
          }
          $count =  $count + 1;
       }
        foreach ($group as $rowlevels) {
           foreach ($rowlevels as $rowlevel2) {     
              $itemnameArray[] = ["dcil_itemname "     => $rowlevel2['dcil_itemname']];
              $functionArray[] = ["ffbl_functiontype"  => $rowlevel2['ffbl_functiontype'],
                                  "ffbl_functionvalue" => $rowlevel2['ffbl_functionvalue']];
              if ((is_null($post_expiredate) && !is_null($rowlevel2['dbir_expiredate'])) || $post_expiredate < $rowlevel2['dbir_expiredate'] && 
                  ($rowlevel2['ffbl_functiontype'] == 0 && $rowlevel2['ffbl_functionvalue'] == 1 )) {
                 $post_expiredate = $rowlevel2['dbir_expiredate'];
              } 
              $sd_id = $rowlevel2['sd_id'];
              $sd_shopname = $rowlevel2['sd_shopname'];
              $sd_shoptel = $rowlevel2['sd_shoptel'];
              $sd_shopaddress = $rowlevel2['sd_shopaddress'];
              $sd_shopphotopath = $rowlevel2['sd_shopphotopath'];
              $sd_questiontotalaverage = $rowlevel2['sd_questiontotalaverage'];
              $sd_lat = $rowlevel2['sd_lat'];
              $sd_lng = $rowlevel2['sd_lng'];
              $sd_weeklystart = $rowlevel2['sd_weeklystart'];
              $sd_weeklyend = $rowlevel2['sd_weeklyend'];
              $sd_dailystart = $rowlevel2['sd_dailystart'];
              $sd_dailyend = $rowlevel2['sd_dailyend'];
              $sd_havebind = $rowlevel2['sd_havebind'];
              $spm_serno = $rowlevel2['spm_serno'];
              $distance = $rowlevel2['distance'];
           }

           $resultData[] = [
                            'sd_id'                   => $sd_id,
                            'sd_shopname'             => $sd_shopname,
                            'sd_shoptel'              => $sd_shoptel,
                            'sd_shopaddress'          => $sd_shopaddress,
                            'sd_shopphotopath'        => $sd_shopphotopath,
                            'sd_questiontotalaverage' => $sd_questiontotalaverage,
                            'sd_lat' => $sd_lat,
                            'sd_lng' => $sd_lng,
                            'sd_weeklystart' => $sd_weeklystart,
                            'sd_weeklyend' => $sd_weeklyend,
                            'sd_dailystart' => $sd_dailystart,
                            'sd_dailyend' => $sd_dailyend,
                            'post_expiredate'         => $post_expiredate,
                            'sd_havebind' => $sd_havebind,
                            'spm_serno'   => $spm_serno,
                            'distance'             => $distance,
                            'dcil_itemname'           => $itemnameArray,
                            'promote_function'        => $functionArray,
                           ];
           $itemnameArray = array();
           $functionArray = array();
           $post_expiredate = null;
        } 
        return $resultData;
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return null;
      }
    }

}
