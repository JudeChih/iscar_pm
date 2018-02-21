<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家基本資料
 */
class ICR_ShopData extends Model {

//
    public $table = 'icr_shopdata';
    public $primaryKey = 'sd_id';
    public $timestamps = false;
    public $incrementing = false;



    public static function InsertData($arraydata){
      try {
             if (!Commontools::CheckArrayValue($arraydata, "sd_id") || !Commontools::CheckArrayValue($arraydata, "sd_shopname")) {
                return false;
              }
              $savedata['sd_id'] = $arraydata['sd_id'];
              $savedata['sd_shopname'] = $arraydata['sd_shopname'];

              if (Commontools::CheckArrayValue($arraydata, "sd_shoptel")) {
                $savedata['sd_shoptel'] = $arraydata['sd_shoptel'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_zipcode")) {
                $savedata['sd_zipcode'] = $arraydata['sd_zipcode'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_shopaddress")) {
                $savedata['sd_shopaddress'] = $arraydata['sd_shopaddress'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_lat")) {
                $savedata['sd_lat'] = $arraydata['sd_lat'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_lng")) {
                $savedata['sd_lng'] = $arraydata['sd_lng'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_weeklystart")) {
                $savedata['sd_weeklystart'] = $arraydata['sd_weeklystart'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_weeklyend")) {
                $savedata['sd_weeklyend'] = $arraydata['sd_weeklyend'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_dailystart")) {
                $savedata['sd_dailystart'] = $arraydata['sd_dailystart'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_dailystart")) {
                $savedata['sd_dailystart'] = $arraydata['sd_dailystart'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_dailyend")) {
                $savedata['sd_dailyend'] = $arraydata['sd_dailyend'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_shopphotopath")) {
                $savedata['sd_shopphotopath'] = $arraydata['sd_shopphotopath'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_introtext")) {
                $savedata['sd_introtext'] = $arraydata['sd_introtext'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_contact_person")) {
                $savedata['sd_contact_person'] = $arraydata['sd_contact_person'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_contact_tel")) {
                $savedata['sd_contact_tel'] = $arraydata['sd_contact_tel'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_contact_mobile")) {
                $savedata['sd_contact_mobile'] = $arraydata['sd_contact_mobile'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_contact_address")) {
                $savedata['sd_contact_address'] = $arraydata['sd_contact_address'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_contact_email")) {
                $savedata['sd_contact_email'] = $arraydata['sd_contact_email'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_activestatus")) {
                $savedata['sd_activestatus'] = $arraydata['sd_activestatus'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_paymentflow")) {
                $savedata['sd_paymentflow'] = $arraydata['sd_paymentflow'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_paymentflowdata")) {
                $savedata['sd_paymentflowdata'] = $arraydata['sd_paymentflowdata'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_havebind")) {
                $savedata['sd_havebind'] = $arraydata['sd_havebind'];
              } else {
                  $savedata['sd_havebind'] =0;
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_paymentflowagreement")) {
                $savedata['sd_paymentflowagreement'] = $arraydata['sd_paymentflowagreement'];
              } else {
                 $savedata['sd_paymentflowagreement'] =0;
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_payment_sign_date")) {
                 $savedata['sd_payment_sign_date'] = $arraydata['sd_payment_sign_date'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_uniformnumbers")) {
                 $savedata['sd_uniformnumbers'] = $arraydata['sd_uniformnumbers'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_cooperationagreement")) {
                 $savedata['sd_cooperationagreement'] = $arraydata['sd_cooperationagreement'];
              } else {
                  $savedata['sd_cooperationagreement'] =0;
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_cooperat_sign_mdid")) {
                 $savedata['sd_cooperat_sign_mdid'] = $arraydata['sd_cooperat_sign_mdid'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_cooperat_sign_date")) {
                 $savedata['sd_cooperat_sign_date'] = $arraydata['sd_cooperat_sign_date'];
              }
              if (Commontools::CheckArrayValue($arraydata, "rl_city_code")) {
                 $savedata['rl_city_code'] = $arraydata['rl_city_code'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sd_inttype")) {
                 $savedata['sd_inttype'] = $arraydata['sd_inttype'];
              }
              if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                 $savedata['isflag'] = $arraydata['isflag'];
              } else {
                 $savedata['isflag'] = '1';
              }
              $savedata['create_user'] = 'webapi';
              $savedata['last_update_user'] = 'webapi';
              DB::table('icr_shopdata')->insert($savedata);
              return true;
       } catch(Exception $e) {
            ErrorLog::Insert($e);
            return false;
       }
    }


    /**
     * 依「$sd_id」取得資料
     * @param type $sd_id
     * @return type
     */
    public static function GetData($sd_id) {
        try {

            if ($sd_id == null || strlen($sd_id) == 0) {
                return null;
            }

            $results = ICR_ShopData::where('isflag', '=', '1')
                            ->where('sd_id', '=', $sd_id)
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 取得「商家名稱」與〔$sd_shopname〕相似，且「商家代碼」大於〔$startid〕的資料
     * @param type $sd_shopname
     * @param type $startid
     * @param type $queryamount
     * @return type
     */
    public static function GetData_ByShopName($sd_shopname, $startid, $queryamount) {
        try {
            if ($sd_shopname == null || strlen($sd_shopname) == 0 || $queryamount == null || strlen($queryamount) == 0) {
                return null;
            }

            $query = ICR_ShopData::where('isflag', '=', '1')
                    ->where('sd_activestatus', '=', '1')
                    ->where('sd_shopname', 'LIKE', '%' . $sd_shopname . '%');

            if ($startid != null || strlen($startid) != 0) {
                $query = $query->where('sd_id', '>', $startid);
            }



            $results = $query
                            ->orderBy('sd_id', 'asc')
                            ->take($queryamount)
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 取得「商家類別」為〔$sd_type〕，且「商家代碼」大於〔$startid〕的資料
     * @param type $sd_type
     * @param type $startid
     * @param type $queryamount
     * @return type
     */
    public static function GetData_BySd_Type($sd_type, $startid, $queryamount) {
        try {
            if ($sd_type == null || strlen($sd_type) == 0 || $queryamount == null || strlen($queryamount) == 0) {
                return null;
            }

            $query = ICR_ShopData::where('isflag', '=', '1')
                    ->where('sd_activestatus', '=', '1')
                    ->where('sd_type', '=', $sd_type);

            if ($startid != null && strlen($startid) != 0) {
                $query = $query->where('sd_id', '>', $startid);
            }

            $results = $query
                            ->orderBy('sd_id', 'asc')
                            ->take($queryamount)
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    public static function GetData_ByMD_ID($md_id, $lastupdate) {
        try {
            if ($md_id == null || strlen($md_id) == 0) {
                return null;
            }

            $query = ICR_ShopData::
                    join('icr_shopcoupondata_m', 'icr_shopdata.sd_id', '=', 'icr_shopcoupondata_m.sd_id')
                    ->join('icr_shopcoupondata_g', 'icr_shopcoupondata_m.scm_id', '=', 'icr_shopcoupondata_g.scm_id')
                    ->where('icr_shopdata.isflag', '=', '1')
//->where('icr_shopcoupondata_m.isflag', '=', '1')
                    ->where('icr_shopcoupondata_g.isflag', '=', '1')
                    ->where('icr_shopcoupondata_g.md_id', '=', $md_id);

            if (!is_null($lastupdate) && strlen($lastupdate) != 0) {
                $query->where('icr_shopcoupondata_g.last_update_date', '>', $lastupdate);
            }

            $results = $query->select('icr_shopdata.sd_id'
                            , 'icr_shopdata.sd_shopname'
                            , 'icr_shopdata.sd_shopaddress'
                            , 'icr_shopdata.sd_shoptel'
                            , 'icr_shopdata.sd_lat'
                            , 'icr_shopdata.sd_lng'
                            , 'icr_shopdata.sd_weeklystart'
                            , 'icr_shopdata.sd_weeklyend'
                            , 'icr_shopdata.sd_dailystart'
                            , 'icr_shopdata.sd_dailyend')->distinct()->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 修改資料
     * @param array $arraydata 要更新的資料
     * @return boolean
     */
    public static function UpdateData(array $arraydata) {
        try {
            if (!Commontools::CheckArrayValue($arraydata, 'sd_id')) {
                return false;
            }

            $savedata['sd_id'] = $arraydata['sd_id'];

            if (Commontools::CheckArrayValue($arraydata, 'sd_type')) {
                $savedata['sd_type'] = $arraydata['sd_type'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_shopname')) {
                $savedata['sd_shopname'] = $arraydata['sd_shopname'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_shoptel')) {
                $savedata['sd_shoptel'] = $arraydata['sd_shoptel'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_zipcode')) {
                $savedata['sd_zipcode'] = $arraydata['sd_zipcode'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_shopaddress')) {
                $savedata['sd_shopaddress'] = $arraydata['sd_shopaddress'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_lat')) {
                $savedata['sd_lat'] = $arraydata['sd_lat'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_lng')) {
                $savedata['sd_lng'] = $arraydata['sd_lng'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_weeklystart')) {
                $savedata['sd_weeklystart'] = $arraydata['sd_weeklystart'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_weeklyend')) {
                $savedata['sd_weeklyend'] = $arraydata['sd_weeklyend'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_dailystart')) {
                $savedata['sd_dailystart'] = $arraydata['sd_dailystart'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_dailyend')) {
                $savedata['sd_dailyend'] = $arraydata['sd_dailyend'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_shopphotopath')) {
                $savedata['sd_shopphotopath'] = $arraydata['sd_shopphotopath'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_introtext')) {
                $savedata['sd_introtext'] = $arraydata['sd_introtext'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_contact_person')) {
                $savedata['sd_contact_person'] = $arraydata['sd_contact_person'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_contact_tel')) {
                $savedata['sd_contact_tel'] = $arraydata['sd_contact_tel'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_contact_mobile')) {
                $savedata['sd_contact_mobile'] = $arraydata['sd_contact_mobile'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_contact_address')) {
                $savedata['sd_contact_address'] = $arraydata['sd_contact_address'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_contact_email')) {
                $savedata['sd_contact_email'] = $arraydata['sd_contact_email'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_activestatus')) {
                $savedata['sd_activestatus'] = $arraydata['sd_activestatus'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_advancedata')) {
                $savedata['sd_advancedata'] = $arraydata['sd_advancedata'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_questionnaireresult')) {
                $savedata['sd_questionnaireresult'] = $arraydata['sd_questionnaireresult'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_questiontotalaverage')) {
                $savedata['sd_questiontotalaverage'] = $arraydata['sd_questiontotalaverage'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_paymentflow')) {
                $savedata['sd_paymentflow'] = $arraydata['sd_paymentflow'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_paymentflowdata')) {
                $savedata['sd_paymentflowdata'] = $arraydata['sd_paymentflowdata'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_havebind')) {
                $savedata['sd_havebind'] = $arraydata['sd_havebind'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'rl_city_code')) {
                $savedata['rl_city_code'] = $arraydata['rl_city_code'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_salescode')) {
              if($arraydata['sd_salescode'] != ''){
                $savedata['sd_salescode'] = $arraydata['sd_salescode'];
              }
            }
            if (Commontools::CheckArrayValue($arraydata, 'sd_salesbind')) {
              if($arraydata['sd_salesbind'] != ''){
                $savedata['sd_salesbind'] = $arraydata['sd_salesbind'];
              }
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_paymentflowagreement")) {
                $savedata['sd_paymentflowagreement'] = $arraydata['sd_paymentflowagreement'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_payment_sign_date")) {
                $savedata['sd_payment_sign_date'] = $arraydata['sd_payment_sign_date'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_uniformnumbers")) {
                $savedata['sd_uniformnumbers'] = $arraydata['sd_uniformnumbers'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_cooperationagreement")) {
                $savedata['sd_cooperationagreement'] = $arraydata['sd_cooperationagreement'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_cooperat_sign_mdid")) {
                $savedata['sd_cooperat_sign_mdid'] = $arraydata['sd_cooperat_sign_mdid'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_cooperat_sign_date")) {
                $savedata['sd_cooperat_sign_date'] = $arraydata['sd_cooperat_sign_date'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_seo_keywords")) {
                $savedata['sd_seo_keywords'] = $arraydata['sd_seo_keywords'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_seo_description")) {
                $savedata['sd_seo_description'] = $arraydata['sd_seo_description'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_seo_title")) {
                $savedata['sd_seo_title'] = $arraydata['sd_seo_title'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_inttype")) {
                $savedata['sd_inttype'] = $arraydata['sd_inttype'];
            }

            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            }

            $savedata['last_update_user'] = 'webapi';
            // $savedata['last_update_date'] = \Carbon\Carbon::now();

            // \App\Models\ErrorLog::InsertLog(json_encode($savedata));
            DB::table('icr_shopdata')
                    ->where('sd_id', $savedata['sd_id'])
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 查詢「服務排程資料」
     * @param type $sd_id
     * @return type
     */
    public static function Query_ServiceQueData($sd_id) {
        try {
            if ($sd_id == null || strlen($sd_id) == 0) {
                return null;
            }
            $results = ICR_ShopData::where('icr_shopdata.isflag', '=', '1')
                            ->leftJoin('icr_shopserviceque_d', 'icr_shopserviceque_d.sd_id', '=', 'icr_shopdata.sd_id')
                            ->leftJoin('icr_shopserviceque_m', 'icr_shopserviceque_m.sd_id', '=', 'icr_shopdata.sd_id')
                            ->where('icr_shopdata.sd_id', '=', $sd_id)
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    public static function Query_ShopData_ByARRAY($sd_id_array) {
       try {
            if (is_null($sd_id_array) || !is_array($sd_id_array)) {
                return false;
            }
            $result = ICR_ShopData::select(
                            'icr_shopdata.sd_id',
                            'icr_shopdata.sd_shopname',
                            'icr_shopdata.sd_shopaddress',
                            'icr_shopdata.sd_shoptel',
                            'icr_shopdata.sd_lat',
                            'icr_shopdata.sd_lng',
                            'icr_shopdata.sd_weeklystart',
                            'icr_shopdata.sd_weeklyend',
                            'icr_shopdata.sd_dailystart',
                            'icr_shopdata.sd_dailyend'
                            )
                            ->where('icr_shopdata.isflag', '=', '1')
                            ->whereIn('icr_shopdata.sd_id',$sd_id_array)
                            ->get()->toArray();
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($ex);
          return null;
        }
    }

    public static function GetShopDataList($spm_serno, $sd_country, /*$sd_zipcode,*/ $sd_shopname, $startid, $queryamount, $sd_lat , $sd_lng, $distance) {
       try {
            $query = ICR_ShopData::leftJoin('icr_shoptag_xref', 'icr_shopdata.sd_id', '=', 'icr_shoptag_xref.stx_sd_id')
                                 ->leftJoin('icr_shopmenutag_l', 'icr_shoptag_xref.stx_tag_id', '=', 'icr_shopmenutag_l.stx_tag_id')
                                 ->leftJoin('icr_depositbuyitmerec', 'icr_shopdata.sd_id', '=', 'icr_depositbuyitmerec.dbir_object_id')
                                 ->leftJoin('icr_depositcostitemlist', 'icr_depositbuyitmerec.dcil_id', '=', 'icr_depositcostitemlist.dcil_id')
                                 ->leftJoin('icr_costitemlinkfunctionrec', 'icr_depositbuyitmerec.dcil_id', '=', 'icr_costitemlinkfunctionrec.dcil_id')
                                 ->leftJoin('icr_functionforbuylist', 'icr_costitemlinkfunctionrec.ffbl_id', '=', 'icr_functionforbuylist.ffbl_id')
                                 ->where('icr_shopdata.isflag', '=', '1')
                                 ->where('icr_shoptag_xref.isflag', '=', '1')
                                 ->whereRaw('(icr_depositbuyitmerec.dbir_expiredate is null or icr_depositbuyitmerec.dbir_expiredate > now())')
                                 ->where('icr_shopdata.sd_activestatus','=','1')
                                ->where('icr_shopmenutag_l.isflag','=','1')
                                 ->whereIn('icr_shopmenutag_l.spm_serno', [2, 3]);  // 12/18暫時排除 tag 4 5

            if (!is_null($spm_serno) && strlen($spm_serno) != 0) {
                $query->where('icr_shopmenutag_l.spm_serno', '=', "$spm_serno");
            }
            /*if (!is_null($sd_zipcode) && strlen($sd_zipcode) != 0) {
                $zipcode = explode(",",$sd_zipcode);
                $query->whereIn('icr_shopdata.sd_zipcode',$zipcode);
            }*/
            if (!is_null($sd_country) && strlen($sd_country) != 0) {
                $shopaddress = explode(",",$sd_country);
                $query->whereIn("icr_shopdata.rl_city_code", $shopaddress);
            }
            if (!is_null($sd_shopname) && strlen($sd_shopname) != 0) {
                $query->whereRaw("icr_shopdata.sd_shopname like '%$sd_shopname%'");
            }

            if (!is_null($startid) && strlen($startid) != 0) {
                $query->where("icr_shopdata.sd_id", ">" ,$startid);
            }

            if ( (!is_null($sd_lat) && strlen($sd_lat) != 0) && ( !is_null($sd_lng) && strlen($sd_lng) != 0 ) ) {
                $minSd_Lat = $sd_lat - 0.0225; $maxSd_Lat = $sd_lat + 0.0225;
                $minSd_Lng = $sd_lng - 0.0171; $maxSd_Lng = $sd_lng + 0.0171;

                $query->whereBetween('sd_lat', array( $minSd_Lat,  $maxSd_Lat));
                $query->whereBetween('sd_lng', array( $minSd_Lng,  $maxSd_Lng));
                $query->whereRaw("TRUNCATE(sqrt(POW((`icr_shopdata`.`sd_lat`-$sd_lat),2)+POW((`icr_shopdata`.`sd_lng`-$sd_lng),2)),12 )> $distance")
                          ->orderBy('distance' ,'asc');
            } else {
              $query->orderBy('icr_shopdata.sd_id' ,'asc')
                        ->orderBy('icr_depositbuyitmerec.dbir_expiredate','desc');
            }



            $results = $query->select('icr_shopdata.sd_id'
                            , 'icr_shopdata.sd_shopname'
                            , 'icr_shopdata.sd_shopaddress'
                            , 'icr_shopdata.sd_shoptel'
                            , 'icr_shopdata.sd_shopphotopath'
                            , 'icr_shopdata.sd_questiontotalaverage'
                            , 'icr_shopdata.sd_lat'
                            , 'icr_shopdata.sd_lng'
                            , 'icr_shopdata.sd_weeklystart'
                            , 'icr_shopdata.sd_weeklyend'
                            , 'icr_shopdata.sd_dailystart'
                            , 'icr_shopdata.sd_dailyend'
                            , 'icr_shopdata.sd_havebind'
                            , 'icr_depositcostitemlist.dcil_id'
                            , 'icr_depositcostitemlist.dcil_itemname'
                            , 'icr_depositbuyitmerec.dbir_expiredate'
                            , 'icr_functionforbuylist.ffbl_functionvalue'
                            , 'icr_functionforbuylist.ffbl_functiontype'
                            , 'icr_shopmenutag_l.spm_serno'
                            ,DB::raw("TRUNCATE(sqrt(POW((`icr_shopdata`.`sd_lat`-'$sd_lat'),2)+POW((`icr_shopdata`.`sd_lng`-'$sd_lng'),2)),12)as distance"))
                            ->take($queryamount)->get()->toArray();
            return $results;

       } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null;
       }
    }


    public static function GetShopDataListWithoutLimit($spm_serno, $sd_country, /*$sd_zipcode,*/ $sd_shopname, $startid, $queryamount, $sd_lat , $sd_lng, $distance) {
       try {
            $query = ICR_ShopData::leftJoin('icr_shoptag_xref', 'icr_shopdata.sd_id', '=', 'icr_shoptag_xref.stx_sd_id')
                                 ->leftJoin('icr_shopmenutag_l', 'icr_shoptag_xref.stx_tag_id', '=', 'icr_shopmenutag_l.stx_tag_id')
                                 ->leftJoin('icr_depositbuyitmerec', 'icr_shopdata.sd_id', '=', 'icr_depositbuyitmerec.dbir_object_id')
                                 ->leftJoin('icr_depositcostitemlist', 'icr_depositbuyitmerec.dcil_id', '=', 'icr_depositcostitemlist.dcil_id')
                                 ->leftJoin('icr_costitemlinkfunctionrec', 'icr_depositbuyitmerec.dcil_id', '=', 'icr_costitemlinkfunctionrec.dcil_id')
                                 ->leftJoin('icr_functionforbuylist', 'icr_costitemlinkfunctionrec.ffbl_id', '=', 'icr_functionforbuylist.ffbl_id')
                                 ->where('icr_shopdata.isflag', '=', '1')
                                 ->where('icr_shoptag_xref.isflag', '=', '1')
                                 ->whereRaw('(icr_depositbuyitmerec.dbir_expiredate is null or icr_depositbuyitmerec.dbir_expiredate > now())')
                                 ->where('icr_shopdata.sd_activestatus','=','1')
                                ->where('icr_shopmenutag_l.isflag','=','1');
                                 // ->whereIn('icr_shopmenutag_l.spm_serno', [2, 3]);  // 12/18暫時排除 tag 4 5

            if (!is_null($spm_serno) && strlen($spm_serno) != 0) {
                $query->where('icr_shopmenutag_l.spm_serno', '=', "$spm_serno");
            }
            /*if (!is_null($sd_zipcode) && strlen($sd_zipcode) != 0) {
                $zipcode = explode(",",$sd_zipcode);
                $query->whereIn('icr_shopdata.sd_zipcode',$zipcode);
            }*/
            if (!is_null($sd_country) && strlen($sd_country) != 0) {
                $shopaddress = explode(",",$sd_country);
                $query->whereIn("icr_shopdata.rl_city_code", $shopaddress);
            }
            if (!is_null($sd_shopname) && strlen($sd_shopname) != 0) {
                $query->whereRaw("icr_shopdata.sd_shopname like '%$sd_shopname%'");
            }

            // if (!is_null($startid) && strlen($startid) != 0) {
            //     $query->where("icr_shopdata.sd_id", ">" ,$startid);
            // }

            if ( (!is_null($sd_lat) && strlen($sd_lat) != 0) && ( !is_null($sd_lng) && strlen($sd_lng) != 0 ) ) {
                // $minSd_Lat = $sd_lat - 0.0225; $maxSd_Lat = $sd_lat + 0.0225;
                // $minSd_Lng = $sd_lng - 0.0171; $maxSd_Lng = $sd_lng + 0.0171;

                // $query->whereBetween('sd_lat', array( $minSd_Lat,  $maxSd_Lat));
                // $query->whereBetween('sd_lng', array( $minSd_Lng,  $maxSd_Lng));
                $query->whereRaw("TRUNCATE(sqrt(POW((`icr_shopdata`.`sd_lat`-$sd_lat),2)+POW((`icr_shopdata`.`sd_lng`-$sd_lng),2)),12 )> $distance")
                          ->orderBy('distance' ,'asc');
            } else {
              $query->orderBy('icr_shopdata.sd_id' ,'asc')
                        ->orderBy('icr_depositbuyitmerec.dbir_expiredate','desc');
            }



            $results = $query->select('icr_shopdata.sd_id'
                            , 'icr_shopdata.sd_shopname'
                            , 'icr_shopdata.sd_shopaddress'
                            , 'icr_shopdata.sd_shoptel'
                            , 'icr_shopdata.sd_shopphotopath'
                            , 'icr_shopdata.sd_questiontotalaverage'
                            , 'icr_shopdata.sd_lat'
                            , 'icr_shopdata.sd_lng'
                            , 'icr_shopdata.sd_weeklystart'
                            , 'icr_shopdata.sd_weeklyend'
                            , 'icr_shopdata.sd_dailystart'
                            , 'icr_shopdata.sd_dailyend'
                            , 'icr_shopdata.sd_havebind'
                            ,DB::raw("TRUNCATE(sqrt(POW((`icr_shopdata`.`sd_lat`-'$sd_lat'),2)+POW((`icr_shopdata`.`sd_lng`-'$sd_lng'),2)),12)as distance"))
                            ->groupBy('icr_shopdata.sd_id')
                            ->take($queryamount)->get()->toArray();
            return $results;

       } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null;
       }
    }
    
    
    public static function getAllShopDataForSettleMent ($settle_day) {
          try {
            if ($settle_day == null || strlen($settle_day) == 0) {
                return null;
            }
            $settle_day = date("Y-m-d",strtotime("$settle_day"));
            $query = ICR_ShopData::where('icr_shopdata.isflag', '=', '1')
                            ->where('icr_shopdata.sd_activestatus', '=', '1')
                            ->where('icr_shopdata.sd_paymentflowagreement', '=', '1')
                                ->where('icr_shopdata.sd_havebind', '=', '1')
                            ->whereRaw('icr_shopsettlementrec_m.ssrm_settledate IS NULL')
                            ->leftjoin('icr_shopsettlementrec_m', function($query ) use ($settle_day) {
                                    $query->on('icr_shopdata.sd_id', '=', 'icr_shopsettlementrec_m.sd_id')
                                                ->where('icr_shopsettlementrec_m.ssrm_settledate', '>=', $settle_day);
                             });
               $results = $query->select('icr_shopdata.sd_id')
                            ->distinct()->get()->toArray();
            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

}
