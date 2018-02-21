<?php

namespace App\Http\Controllers\APIControllers\ShopManage;

use App\Library\Commontools;

/** ShopManage共用Function * */
class ShopManage {

     /**檢查會原存在與否
     * @param type $md_id
     * @param type $messageCode
     * @return boolean 檢查結果
     */
    function CheckMdId($md_id, &$messageCode) {
       try {
           $queryData = \App\Models\IsCarMemberData::GetData($md_id);
           if (is_null($queryData) || count($queryData) == 0 ) {
              $messageCode= '999999980';
              return false;
           }
           return true;
       } catch(\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return false;
       }
    }
    
    function CheckValidityDate($md_id, &$arraydata, &$md_clienttype, &$messageCode) {
       try {
          $queryData = \App\Models\ICR_SdmdBind::GetDataJoinShopData($md_id);
          $datenow = date("Y-m-d");
          $arraydata = null;
          $md_clienttype = 0;
          if (is_null($queryData) || count($queryData) == 0 ) {
              $messageCode = '000000003';
              return false;
          }
          
          foreach ( $queryData as $rowdata ) {
              if (date_create_from_format("Y-m-d",$rowdata['smb_validity']) > $datenow ) {
                  $arraydata[] = [
                                    'sd_id'            => $rowdata['sd_id'],
                                    'sd_shopname'      => $rowdata['sd_shopname'],
                                    'sd_shopphotopath' => $rowdata['sd_shopphotopath'],
                                    'smb_shoptype'     => $rowdata['smb_shoptype'],
                                    'smb_activestatus' => $rowdata['smb_activestatus'],
                                    'smb_bindlevel'    => $rowdata['smb_bindlevel'],
                                 ];
              } else {
                \App\Models\ICR_SdmdBind::UpdateData(array('smb_serno'=> $rowData['smb_serno'],'smb_activestatus'=> 2));
              }
          }
          return true;
       } catch(\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return false;
       }
    }
    
    
    function CheckValidityDateByAmount($md_id, $queryamount, $sd_id = null, &$arraydata, &$md_clienttype, &$messageCode) {
       try {
          $queryData = \App\Models\ICR_SdmdBind::GetDataJoinShopDataByAmount($md_id, $queryamount, $sd_id);
          $datenow = date("Y-m-d");
          $arraydata = null;
          $md_clienttype = 0;
          if (is_null($queryData) || count($queryData) == 0 ) {
              $messageCode = '000000003';
              return false;
          }
          
          foreach ( $queryData as $rowdata ) {
              if (date_create_from_format("Y-m-d",$rowdata['smb_validity']) > $datenow ) {
                  $arraydata[] = [
                                    'sd_id'            => $rowdata['sd_id'],
                                    'sd_shopname'      => $rowdata['sd_shopname'],
                                    'sd_shopphotopath' => $rowdata['sd_shopphotopath'],
                                    'smb_shoptype'     => $rowdata['smb_shoptype'],
                                    'smb_activestatus' => $rowdata['smb_activestatus'],
                                    'smb_bindlevel'    => $rowdata['smb_bindlevel'],
                                 ];
              } else {
                \App\Models\ICR_SdmdBind::UpdateData(array('smb_serno'=> $rowData['smb_serno'],'smb_activestatus'=> 2));
              }
          }
          return true;
       } catch(\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return false;
       }
    }
    
}
