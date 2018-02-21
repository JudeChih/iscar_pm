<?php

namespace App\Http\Controllers\APIControllers\ShopPush;

use App\Library\Commontools;

/** ShopPush * */
class ShopPush {

   function QueryShopPushFee($serno, $servicetype ,&$messageCode) {
     try {
          $pushfeeData = \App\Models\ICR_ShopServicefee_Item::GetShopPushFee($serno, $servicetype);
          if (is_null($pushfeeData) || count($pushfeeData) == 0 ) {
              $messageCode = '999999973';
              return null;
          }
          return $pushfeeData;
     } catch (\Exception $e) {
          \App\Models\ErrorLOg::InsertData($e);
          return null;
     }
   }
   
   
   function QueryPushObject($fbgender, $agemin, $agemax, $citysArray, $sd_id ,$objectamount, &$messageCode) {
     try {
          if(!is_null($fbgender) && mb_strlen($fbgender) != 0) {
            $fbgender = ($fbgender == 0)? 'male' : 'female';
          }
          $objectData = \App\Models\IsCarMemberData::GetPushMd_id($fbgender, $agemin, $agemax, $citysArray, $sd_id);
          $random_keys = null;
          $result = array();
          foreach($objectData as $key => $value) {
               $checkData = \App\Models\IsCarUserBookmark::GetDataByMD_ID_SD_ID($value['md_id'],$sd_id);
               if (count($checkData) > 0 ) {
                    unset($objectData[$key]);
               }
          }
          if (is_null($objectData)|| count($objectData) == 0 ) {
              $messageCode= '000000003';
              return null;
          } else if ( !is_null($objectamount) && is_numeric($objectamount) ) {
              if ($objectamount > count($objectData)) {
                  $messageCode= '999999978';
                  return null; 
              }
              $random_keys = array_rand($objectData, $objectamount);
          }
          $basevalues = (!is_null($random_keys)) ? $random_keys : array_rand($objectData,count($objectData));
          $basevalues = ($basevalues == 0) ? array('0') : $basevalues ;
          foreach(( $basevalues ) as $rowkey) {
              array_push($result,$objectData[$rowkey]['md_id']);                
          }
          return $result; 
     } catch (\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return null;
     }
   }
    
}
