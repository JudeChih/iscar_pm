<?php

namespace App\Repositories;

use App\Models\ICR_Shopcoupondata_Logistics;
use DB;

class ICR_SCLRepository  {

    //新增資料
    public function InsertData( $arraydata ) {
     try {
        if (
                !\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_id') || !\App\Library\Commontools::CheckArrayValue($arraydata, 'scg_id')
              || !\App\Library\Commontools::CheckArrayValue($arraydata, 'scm_id')  || !\App\Library\Commontools::CheckArrayValue($arraydata, 'sd_id')
              || !\App\Library\Commontools::CheckArrayValue($arraydata, 'md_id')  || !\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_receivername')
              || !\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_receivermobile')  || !\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_postcode')
              || !\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_city')  || !\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_district')
              || !\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_receiveaddress') || !\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_email')
        ) {
            return false;
        }
        $savedata['scl_id'] = $arraydata['scl_id'];
        $savedata['scg_id'] = $arraydata['scg_id'];
        $savedata['scm_id'] = $arraydata['scm_id'];
        $savedata['md_id'] = $arraydata['md_id'];
        $savedata['sd_id'] = $arraydata['sd_id'];
        $savedata['scl_receivername'] = $arraydata['scl_receivername'];
        $savedata['scl_receivermobile'] = $arraydata['scl_receivermobile'];
        $savedata['scl_postcode'] = $arraydata['scl_postcode'];
        $savedata['scl_city'] = $arraydata['scl_city'];
        $savedata['scl_district'] = $arraydata['scl_district'];
        $savedata['scl_receiveaddress'] = $arraydata['scl_receiveaddress'];
        $savedata['scl_email'] = $arraydata['scl_email'];

        if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_delivery_time')) {
            $savedata['scl_delivery_time'] = $arraydata["scl_delivery_time"];
        }
        if (\App\Library\Commontools::CheckArrayValue($arraydata, 'lsl_serno')) {
            $savedata['lsl_serno'] = $arraydata["lsl_serno"];
        }

        if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_tracenum')) {
            $savedata['scl_tracenum'] = $arraydata["scl_tracenum"];
        }

        if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_deliverstatus')) {
            $savedata['scl_deliverstatus'] = $arraydata["scl_deliverstatus"];
        }

        if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_receiverphone')) {
            $savedata['scl_receiverphone'] = $arraydata["scl_receiverphone"];
        }

        if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_orderprinttime')) {
            $savedata['scl_orderprinttime'] = $arraydata["scl_orderprinttime"];
        }

        if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_cargopicktime')) {
            $savedata['scl_cargopicktime'] = $arraydata["scl_cargopicktime"];
        }

        if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_senddeliverytime')) {
            $savedata['scl_senddeliverytime'] = $arraydata["scl_senddeliverytime"];
        }

        if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_cargoarrivetime')) {
            $savedata['scl_cargoarrivetime'] = $arraydata["scl_cargoarrivetime"];
        }

        if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_cargopack_pic')) {
            $savedata['scl_cargopack_pic'] = $arraydata["scl_cargopack_pic"];
        }

        if (\App\Library\Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
        }
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "create_user")) {
                $savedata['create_user'] = $arraydata['create_user'];
            } else {
                $savedata['create_user'] = 'Pmapi';
        }
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "last_update_user")) {
                $savedata['last_update_user'] = $arraydata['last_update_user'];
            } else {
                $savedata['last_update_user'] = 'Pmapi';
        }

        //新增資料並回傳「自動遞增KEY值」
        if (DB::table('icr_shopcoupondata_logistics')->insert($savedata)) {
            return true;
        } else {
            return false;
        }

     } catch (\Exception $e) {
               //DB::rollBack();
               \App\Models\ErrorLog::InsertData($e);
               return false;
     }
    }

      /**
     * 修改資料
     * @param array $arraydata 要更新的資料
     * @return boolean
     */
    public function UpdateData($arraydata) {
        try {

            if (!\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_id')) {
                return false;
            }

            $savedata['scl_id'] = $arraydata['scl_id'];

            if (\App\Library\Commontools::CheckArrayValue($arraydata, "scg_id")) {
                $savedata['scg_id'] = $arraydata['scg_id'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "scm_id")) {
                $savedata['scm_id'] = $arraydata['scm_id'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "sd_id")) {
                $savedata['sd_id'] = $arraydata['sd_id'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "md_id")) {
                $savedata['md_id'] = $arraydata['md_id'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_receivername')) {
                $savedata['scl_receivername'] = $arraydata['scl_receivername'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_receivermobile')) {
                $savedata['scl_receivermobile'] = $arraydata['scl_receivermobile'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_receiverphone')) {
                $savedata['scl_receiverphone'] = $arraydata['scl_receiverphone'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "scl_email")) {
                $savedata['scl_email'] = $arraydata['scl_email'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "scl_postcode")) {
                $savedata['scl_postcode'] = $arraydata['scl_postcode'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "scl_city")) {
                $savedata['scl_city'] = $arraydata['scl_city'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "scl_district")) {
                $savedata['scl_district'] = $arraydata['scl_district'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_receiveaddress')) {
                $savedata['scl_receiveaddress'] = $arraydata['scl_receiveaddress'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_delivery_time')) {
                $savedata['scl_delivery_time'] = $arraydata['scl_delivery_time'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, 'lsl_serno')) {
                $savedata['lsl_serno'] = $arraydata['lsl_serno'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "scl_tracenum")) {
                $savedata['scl_tracenum'] = $arraydata['scl_tracenum'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_deliverstatus')) {
                $savedata['scl_deliverstatus'] = $arraydata['scl_deliverstatus'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_orderprinttime')) {
                $savedata['scl_orderprinttime'] = $arraydata['scl_orderprinttime'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_cargopicktime')) {
                $savedata['scl_cargopicktime'] = $arraydata['scl_cargopicktime'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "scl_senddeliverytime")) {
                $savedata['scl_senddeliverytime'] = $arraydata['scl_senddeliverytime'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_cargoarrivetime')) {
                $savedata['scl_cargoarrivetime'] = $arraydata['scl_cargoarrivetime'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, 'scl_cargopack_pic')) {
                $savedata['scl_cargopack_pic'] = $arraydata['scl_cargopack_pic'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, 'isflag')) {
                $savedata['isflag'] = $arraydata['isflag'];
            }


            //$savedata['last_update_date'] = date('Y-m-d H:i:s');

            DB::table('icr_shopcoupondata_logistics')
                    ->where('scl_id', $savedata['scl_id'])
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }



    public function getPrintDataAndUpdate($arrayScl_Id, $sd_id) {
      try {
          if ($sd_id == null &&  $arrayScl_Id == null ) {
            return null;
          }
         $query = ICR_Shopcoupondata_Logistics::where('scl_deliverstatus',0)
                                                ->where('icr_shopcoupondata_logistics.isflag','1')
                                                ->where('icr_shopcoupondata_logistics.sd_id',$sd_id)
                                                ->join('icr_shopcoupondata_g', function($query) {
                                                      $query->on('icr_shopcoupondata_logistics.scg_id', '=', 'icr_shopcoupondata_g.scg_id')
                                                                 ->where('icr_shopcoupondata_g.scg_paymentstatus','=','1')
                                                                 ->where('icr_shopcoupondata_g.scg_usestatus','=','5');
                                                })
                                                ->join('icr_shopcoupondata_m', 'icr_shopcoupondata_logistics.scm_id', '=', 'icr_shopcoupondata_m.scm_id')
                                                ->join('icr_shopdata','icr_shopcoupondata_logistics.sd_id','=','icr_shopdata.sd_id');
         if (is_array($arrayScl_Id) && count($arrayScl_Id) != 0 ) {
          $query->whereIn('icr_shopcoupondata_logistics.scl_id', $arrayScl_Id);
         }
        $result = $query->select(
                                   'icr_shopcoupondata_logistics.*'
                                  ,'icr_shopcoupondata_m.scm_title'
                                  ,'icr_shopcoupondata_m.scm_producttype'
                                  ,'icr_shopcoupondata_m.scm_price'
                                  ,'icr_shopcoupondata_g.scg_paymentstatus'
                                  ,'icr_shopcoupondata_g.scg_buyamount'
                                  ,'icr_shopcoupondata_g.scg_totalamount'
                                  ,'icr_shopcoupondata_g.scg_buyermessage'
                                  ,'icr_shopdata.sd_shopname'
                                  ,'icr_shopdata.sd_contact_address'
                                  ,'icr_shopdata.sd_contact_tel'
                                  ,'icr_shopcoupondata_logistics.scl_id as Scl_id'
                                  )->get()->toArray();
        //\App\Models\ErrorLog::InsertLog($result[0]['Scl_id']);
         foreach ($result as $rowData) {
               $arrayData = ['scl_id' => $rowData['Scl_id'] ,'scl_deliverstatus' =>1 , 'scl_orderprinttime'=>date('Y-m-d H:i:s')];
               if(! ICR_SCLRepository::UpdateData($arrayData) ) {
                   throw new \Exception();
               }
               $arraydata = [ 'scg_id' =>  $rowData['scg_id'],
                                      'scg_usestatus' => 6 ];
              if ( ! \App\Models\ICR_ShopCouponData_g::UpdateData($arraydata) ) {
                   throw new \Exception();
              }
         }

      return $result;

      } catch (Exception $ex) {
           \App\Models\ErrorLog::InsertData($ex);
           return false;
      }
    }

    public function getDataBySciId($scl_id) {
      try {
            $query = ICR_Shopcoupondata_Logistics:://where('scl_deliverstatus',0)
                                                where('icr_shopcoupondata_logistics.isflag','1')
                                                ->leftjoin('icr_shopcoupondata_g', function($query) {
                                                      $query->on('icr_shopcoupondata_logistics.scg_id', '=', 'icr_shopcoupondata_g.scg_id');
                                                                 //->where('icr_shopcoupondata_g.scg_usestatus', '=', 6);
                                                });
              if ( is_array($scl_id) && count($scl_id) != 0 ) {
                     $query ->whereIn('icr_shopcoupondata_logistics.scl_id',$scl_id);
              }
              $result =  $query->select(
                                            'icr_shopcoupondata_logistics.*',
                                            'icr_shopcoupondata_g.*'
                                           )->get()->toArray();
             return $result;
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return null;
      }
    }

   /**
    * 取得需要回報物流單據
    * @param  string $sci_id      物流單據
    * @param  [type] $scl_deliverstatus 物流狀態 0:已下訂  1:已印單 2:已分裝 3:已物流 4:已到貨
    * @param  [type] $scg_usestatus    使用狀況  0:未取用 1:已取用 2：使用完畢　3：放棄使用 4:活動截止失效 5:已付款  6:已印單 7:已分裝 8:已出貨 9:已到貨
    * @return   ArrayData
    */
    public function getNeedReportLogistic($scl_id, $scl_deliverstatus, $scg_usestatus) {
      try {
            $query = ICR_Shopcoupondata_Logistics::where('scl_deliverstatus', $scl_deliverstatus)
                                                ->where('icr_shopcoupondata_logistics.isflag','1')
                                                ->leftjoin('icr_shopcoupondata_g', function($query) {
                                                      $query->on('icr_shopcoupondata_logistics.scg_id', '=', 'icr_shopcoupondata_g.scg_id')
                                                                 ->where('icr_shopcoupondata_g.scg_paymentstatus','=','1');
                                                }) ->where('icr_shopcoupondata_g.scg_usestatus', '=', $scg_usestatus)
                                                ->where('icr_shopcoupondata_logistics.scl_id',$scl_id);
            $result = $query->select( 
                                                     'icr_shopcoupondata_logistics.*',
                                                     'icr_shopcoupondata_logistics.scl_id as SCLID',
                                                     'icr_shopcoupondata_g.scg_usestatus'
                                                    )->get()->toArray();
            //\App\Models\ErrorLog::InsertLog($result);
            return $result;
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return null;
      }
    }

}
