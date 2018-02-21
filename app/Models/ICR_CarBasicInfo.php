<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class ICR_CarBasicInfo extends Model {

    //
    public $table = 'icr_carbasicinfo';
    public $primaryKey = 'cbi_id';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * ██████████▍CREATE 建立資料
     */
     /**
     * InsertData
     * @param array $arraydata
     */
    public static function InsertData($arraydata) {
        try {
              if (!Commontools::CheckArrayValue($arraydata, "cbi_id") || !Commontools::CheckArrayValue($arraydata, "cbi_postownertype") || !Commontools::CheckArrayValue($arraydata, "cbi_owner_id")
                  ||!Commontools::CheckArrayValue($arraydata, "cbi_advertisementtitle")|| !Commontools::CheckArrayValue($arraydata, "cbi_manufactoryyear") || !Commontools::CheckArrayValue($arraydata, "cbi_manufactorymonth")
                  ||!Commontools::CheckArrayValue($arraydata, "cbi_licensingyear") ||!Commontools::CheckArrayValue($arraydata, "cbi_licensingmonth") ||!Commontools::CheckArrayValue($arraydata, "cbi_carbrand")
                  ||!Commontools::CheckArrayValue($arraydata, "cbi_brandmodel")  ||!Commontools::CheckArrayValue($arraydata, "cbi_modelstyle")||!Commontools::CheckArrayValue($arraydata, "cbi_carbodytype")
                  ||!Commontools::CheckArrayValue($arraydata, "cbi_saleprice") ||!Commontools::CheckArrayValue($arraydata, "cbi_licensestatus")||!Commontools::CheckArrayValue($arraydata, "cbi_mileage")
                  ||!Commontools::CheckArrayValue($arraydata, "cbi_everrepair")||!Commontools::CheckArrayValue($arraydata, "cbi_carvin")||!Commontools::CheckArrayValue($arraydata, "cbi_displacement")
                  ||!Commontools::CheckArrayValue($arraydata, "cbi_fueltype") ||!Commontools::CheckArrayValue($arraydata, "cbi_transmissionsystem") ||!Commontools::CheckArrayValue($arraydata, "cbi_drivemode") 
                  ||!Commontools::CheckArrayValue($arraydata, "cbi_carseats") ||!Commontools::CheckArrayValue($arraydata, "cbi_cardoors") ||!Commontools::CheckArrayValue($arraydata, "cbi_displacement")  
                  ) {
                return false;
              } 
              $savadata['cbi_id'] = $arraydata['cbi_id'];
              $savadata['cbi_postownertype'] = $arraydata['cbi_postownertype'];
              $savadata['cbi_owner_id'] = $arraydata['cbi_owner_id'];
              $savadata['cbi_advertisementtitle'] = $arraydata['cbi_advertisementtitle'];
              $savadata['cbi_manufactoryyear'] = $arraydata['cbi_manufactoryyear'];
              $savadata['cbi_manufactorymonth'] = $arraydata['cbi_manufactorymonth'];
              $savadata['cbi_licensingyear'] = $arraydata['cbi_licensingyear'];
              $savadata['cbi_licensingmonth'] = $arraydata['cbi_licensingmonth'];
              $savadata['cbi_carbrand'] = $arraydata['cbi_carbrand'];
              $savadata['cbi_brandmodel'] = $arraydata['cbi_brandmodel'];
              $savadata['cbi_modelstyle'] = $arraydata['cbi_modelstyle'];
              $savadata['cbi_carbodytype'] = $arraydata['cbi_carbodytype'];
              $savadata['cbi_saleprice'] = $arraydata['cbi_saleprice'];
              $savadata['cbi_licensestatus'] = $arraydata['cbi_licensestatus'];
              $savadata['cbi_mileage'] = $arraydata['cbi_mileage'];
              $savadata['cbi_everrepair'] = $arraydata['cbi_everrepair'];
              $savadata['cbi_carvin'] = $arraydata['cbi_carvin'];
              $savadata['cbi_displacement'] = $arraydata['cbi_displacement'];
              $savadata['cbi_fueltype'] = $arraydata['cbi_fueltype'];
              $savadata['cbi_transmissionsystem'] = $arraydata['cbi_transmissionsystem'];
              $savadata['cbi_drivemode'] = $arraydata['cbi_drivemode'];
              $savadata['cbi_carseats'] = $arraydata['cbi_carseats'];
              $savadata['cbi_cardoors'] = $arraydata['cbi_cardoors'];
              $savadata['cbi_displacement'] = $arraydata['cbi_displacement'];
             
           
              if (Commontools::CheckArrayValue($arraydata, "cbi_webaddress")) {
                $savadata['cbi_webaddress'] = $arraydata['cbi_webaddress'];
              } 
              if (Commontools::CheckArrayValue($arraydata, "cbi_caryearstyle")) {
                $savadata['cbi_caryearstyle'] = $arraydata['cbi_caryearstyle'];
              } 
              if (Commontools::CheckArrayValue($arraydata, "cbi_carsource")) {
                $savadata['cbi_carsource'] = $arraydata['cbi_carsource'];
              } 
              if (Commontools::CheckArrayValue($arraydata, "cbi_carlocation")) {
                $savadata['cbi_carlocation'] = $arraydata["cbi_carlocation"];
              }
              if (Commontools::CheckArrayValue($arraydata, "cbi_carshopprice")) {
                $savadata['cbi_carshopprice'] = $arraydata["cbi_carshopprice"];
              }
              if (Commontools::CheckArrayValue($arraydata, "cbi_carbodycolor")) {
                $savadata['cbi_carbodycolor'] = $arraydata["cbi_carbodycolor"];
              }
              if (Commontools::CheckArrayValue($arraydata, "cbi_carinteriorcolor")) {
                $savadata['cbi_carinteriorcolor'] = $arraydata["cbi_carinteriorcolor"];
              }
              if (Commontools::CheckArrayValue($arraydata, "cbi_guaranteeitems")) {
                $savadata['cbi_guaranteeitems'] = $arraydata["cbi_guaranteeitems"];
              }
              if (Commontools::CheckArrayValue($arraydata, "cbi_carequiptments")) {
                $savadata['cbi_carequiptments'] = $arraydata["cbi_carequiptments"];
              }
              if (Commontools::CheckArrayValue($arraydata, "cbi_htmldescript")) {
                $savadata['cbi_htmldescript'] = $arraydata["cbi_htmldescript"];
              }
              if (Commontools::CheckArrayValue($arraydata, "cbi_carevideolink")) {
                $savadata['cbi_carevideolink'] = $arraydata["cbi_carevideolink"];
              }
              if (Commontools::CheckArrayValue($arraydata, "cbi_postmagazinetitle")) {
                $savadata['cbi_postmagazinetitle'] = $arraydata["cbi_postmagazinetitle"];
              }
              if (Commontools::CheckArrayValue($arraydata, "cbi_postmagazinecontent")) {
                $savadata['cbi_postmagazinecontent'] = $arraydata["cbi_postmagazinecontent"];
              }
              if (Commontools::CheckArrayValue($arraydata, 'published')) {
                $savadata['published'] = $arraydata['published'];
              } else {
                $savadata['published'] = '1';
              } 
              
              DB::table('icr_carbasicinfo')->insert($savadata);  
              return true;
        } catch (Exception $e) {
            \App\models\ErrorLog::InsertData($e);
            return false;
        }
    }
    
          

    /**
     * ██████████▍READ 讀取資料
     */

     public static function GetCarDataList($arraydata, $datetime) {
        try {
             $query = ICR_CarBasicInfo::where('icr_carpictures.cps_picscategory', '=', '4')
                            ->leftJoin('icr_carbrandlist', 'icr_carbasicinfo.cbi_carbrand', '=', 'icr_carbrandlist.cbl_id')
                            ->leftJoin('icr_carbrandmodel', 'icr_carbasicinfo.cbi_brandmodel', '=', 'icr_carbrandmodel.cbm_id')
                            ->leftJoin('icr_carmodelstyle', 'icr_carbasicinfo.cbi_modelstyle', '=', 'icr_carmodelstyle.cms_id')
                            ->leftJoin('icr_carpictures', 'icr_carbasicinfo.cbi_id', '=', 'icr_carpictures.cbi_id')
                            //->leftJoin('icr_carpostdata', 'icr_carbasicinfo.cbi_id', '=', 'icr_carpostdata.cbi_id')
                            ->leftJoin('icr_depositbuyitmerec','icr_carbasicinfo.cbi_id','=','icr_depositbuyitmerec.dbir_object_id')
                            ->leftJoin('icr_depositcostitemlist','icr_depositbuyitmerec.dcil_id','=','icr_depositcostitemlist.dcil_id')
                            ->leftJoin('icr_costitemlinkfunctionrec' ,'icr_depositcostitemlist.dcil_id','=','icr_costitemlinkfunctionrec.dcil_id')
                            ->leftJoin('icr_functionforbuylist' ,'icr_costitemlinkfunctionrec.ffbl_id','=','icr_functionforbuylist.ffbl_id')
                            ->orderBy('icr_carbasicinfo.create_date', 'desc') ;
             if ($datetime != null) {
                 $query->where('icr_depositbuyitmerec.dbir_expiredate','>',$datetime);
             }            
             if (Commontools::CheckArrayValue($arraydata, "cpd_poststatus")) {
                 $query->where('icr_carpostdata.cpd_poststatus','=',$arraydata['cpd_poststatus']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_owner_id")) {
                 $query->where('icr_carbasicinfo.cbi_owner_id','=',$arraydata['cbi_owner_id']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_carbrand")) {
                 $query->where('icr_carbasicinfo.cbi_carbrand','=',$arraydata['cbi_carbrand']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_carbodytype")) {
                 $query->where('icr_carbasicinfo.cbi_carbodytype','=',$arraydata['cbi_carbodytype']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_saleprice_low")) {
                 $query->whereBetween('icr_carbasicinfo.cbi_saleprice', array($arraydata['cbi_saleprice_low'], $arraydata['cbi_saleprice_high']));
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_brandmodel")) {
                 $query->where('icr_carbasicinfo.cbi_brandmodel','=',$arraydata['cbi_brandmodel']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_modelstyle")) {
                 $query->where('icr_carbasicinfo.cbi_modelstyle','=',$arraydata['cbi_modelstyle']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_carsource")) {
                 $query->where('icr_carbasicinfo.cbi_carsource','=',$arraydata['cbi_carsource']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_carlocation")) {
                 $carlocation = explode(",",$arraydata['cbi_carlocation']);
                 $query->whereIn('icr_carbasicinfo.cbi_carlocation',$carlocation);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_carbodycolor")) {
                 $query->where('icr_carbasicinfo.cbi_carbodycolor','=',$arraydata['cbi_carbodycolor']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_carinteriorcolor")) {
                 $query->where('icr_carbasicinfo.cbi_carinteriorcolor','=',$arraydata['cbi_carinteriorcolor']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_mileage_low")) {
                 $query->whereBetween('icr_carbasicinfo.cbi_mileage', array($arraydata['cbi_mileage_low'], $arraydata['cbi_mileage_high']));
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_displacement")) {
                 $query->where('icr_carbasicinfo.cbi_displacement','=',$arraydata['cbi_displacement']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_fueltype")) {
                 $query->where('icr_carbasicinfo.cbi_fueltype','=',$arraydata['cbi_fueltype']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_transmissionsystem")) {
                 $query->where('icr_carbasicinfo.cbi_transmissionsystem','=',$arraydata['cbi_transmissionsystem']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_drivemode")) {
                 $query->where('icr_carbasicinfo.cbi_drivemode','=',$arraydata['cbi_drivemode']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_carseats")) {
                 $query->where('icr_carbasicinfo.cbi_carseats','=',$arraydata['cbi_carseats']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_cardoors")) {
                 $query->where('icr_carbasicinfo.cbi_cardoors','=',$arraydata['cbi_cardoors']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_salestatus")) {
                 $query->where('icr_carbasicinfo.cbi_salestatus','=',$arraydata['cbi_salestatus']);
             }
             if (Commontools::CheckArrayValue($arraydata, "cbi_manufactoryyear")) {
                 $query->where('icr_carbasicinfo.cbi_manufactoryyear','=',$arraydata['cbi_manufactoryyear']);
             }
             if (Commontools::CheckArrayValue($arraydata, "orderby_createdate")) {
                 $query->where('icr_carbasicinfo.create_date','<',$arraydata['orderby_createdate']);
             } 
             if(Commontools::CheckArrayValue($arraydata, "queryamount")) {
                 $query->take($arraydata['queryamount'])->skip(0);
             } else {
                 $query->take(100)->skip(0);
             }
                                                       
             $result = $query->select('icr_carbasicinfo.cbi_id'
                                      ,'icr_carbasicinfo.cbi_advertisementtitle'
                                      ,'icr_carbasicinfo.cbi_saleprice'
                                      ,'icr_carbasicinfo.cbi_manufactoryyear'
                                      ,'icr_carbasicinfo.cbi_salestatus'
                                      ,'icr_carbasicinfo.create_date'
                                      ,'icr_carpictures.cps_picpath'
                                      ,'icr_carbrandlist.cbl_fullname'
                                      ,'icr_carbrandmodel.cbm_fullname'
                                      ,'icr_carmodelstyle.cms_fullname'
                                      ,'icr_depositcostitemlist.dcil_itemname'
                                      ,'icr_functionforbuylist.ffbl_functiontype'
                                      ,'icr_functionforbuylist.ffbl_functionvalue'
                                      ,'icr_depositbuyitmerec.dbir_expiredate')
                                      //->raw("IFNULL(icr_depositbuyitmerec.dbir_expiredate,'unpay') as dbir_expiredate")
                                      ->get()->toArray();
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($ex);
          return null; 
        }
     }
     
     
     public static function GetData_ByCBIID($cbi_id,$date) {
        try {
             if ($cbi_id == null || strlen($cbi_id) == 0) {
                return null;
             }
             $query = ICR_CarBasicInfo::where('icr_carbasicinfo.cbi_id', '=', $cbi_id)
                            ->leftJoin('icr_carbodytype', 'icr_carbasicinfo.cbi_carbodytype', '=', 'icr_carbodytype.cbt_id')
                            ->leftJoin('icr_carbrandlist', 'icr_carbasicinfo.cbi_carbrand', '=', 'icr_carbrandlist.cbl_id')
                            ->leftJoin('icr_carbrandmodel', 'icr_carbasicinfo.cbi_brandmodel', '=', 'icr_carbrandmodel.cbm_id')
                            ->leftJoin('icr_carmodelstyle', 'icr_carbasicinfo.cbi_modelstyle', '=', 'icr_carmodelstyle.cms_id')
                            ->leftJoin('icr_carsource', 'icr_carbasicinfo.cbi_carsource', '=', 'icr_carsource.cse_id')
                            ->leftJoin('icr_carlocation', 'icr_carbasicinfo.cbi_carlocation', '=', 'icr_carlocation.cln_id')
                            ->leftJoin('icr_carbodycolor', 'icr_carbasicinfo.cbi_carbodycolor', '=', 'icr_carbodycolor.cbc_id')
                            ->leftJoin('icr_carinteriorcolor', 'icr_carbasicinfo.cbi_carinteriorcolor', '=', 'icr_carinteriorcolor.cic_id')
                            ->leftJoin('icr_carpictures', 'icr_carbasicinfo.cbi_id', '=', 'icr_carpictures.cbi_id');
                            
                                       
             $result = $query->select('icr_carbasicinfo.*'
                                      ,'icr_carbodytype.cbt_fullname'
                                      ,'icr_carbrandlist.cbl_fullname'
                                      ,'icr_carbrandmodel.cbm_fullname'
                                      ,'icr_carmodelstyle.cms_fullname'
                                      ,'icr_carsource.cse_sourcename'
                                      ,'icr_carlocation.cln_cityname'
                                      ,'icr_carbodycolor.cbc_colorname'
                                      ,'icr_carinteriorcolor.cic_colorname'
                                      ,'icr_carpictures.cps_maincovertag'
                                      ,'icr_carpictures.cps_picpath'
                                      ,'icr_carpictures.cps_picfilename'
                                      ,'icr_carpictures.cps_picscategory')
                                      ->raw("(case icr_carbasicinfo.cbi_saleprice when 0 then '面議' end) as cbi_saleprice")
                                      ->raw("(case icr_carbasicinfo.cbi_licensestatus when 0 then'已領牌' when 1 then'未領牌' when 2 then'停用/註銷' when 3 then'全新車' else '' end) as cbi_licensestatus")
                                      ->raw("(case icr_carbasicinfo.cbi_everrepair when 0 then'是' when 1 then'否' else '' end) as cbi_everrepair")
                                      ->raw("(case icr_carbasicinfo.cbi_fueltype when 0 then'汽油車' when 1 then'柴油車' when 2 then'HyBrid混合動力車' when 3 then'瓦斯車' when 4 then'電動車' else '' end) as cbi_fueltype")
                                      ->raw("(case icr_carbasicinfo.cbi_transmissionsystem when 0 then'手自排' when 1 then'自手排' when 2 then'自排' when 3 then'手排' else '' end) as cbi_transmissionsystem ")
                                      ->raw("(case icr_carbasicinfo.cbi_drivemode when 0 then'前輪驅動' when 1 then'後輪驅動' when 2 then'四輪驅動' else '' end) as cbi_drivemode")
                                      ->get()->toArray();
             return $result;
                            
             if (Commontools::CheckArrayValue($arraydata, "cpd_poststatus")) {
                 $query->where('icr_carpostdata.cpd_poststatus','=',$arraydata['cpd_poststatus']);
             }
        } catch(Exception $e) {
          ErrorLog::InsertData($ex);
          return null;
        }
     }
     
     public static function GetDataByCBIID_OWNERID_OWNERTYPE ($cbi_id, $cbi_owner_id, $cbi_postownertype) {
        try {
             if ($cbi_owner_id == null || strlen($cbi_owner_id) == 0 || is_null($cbi_postownertype) || strlen($cbi_postownertype) == 0) {
                return null;
             }
             $query = ICR_CarBasicInfo::where('icr_carbasicinfo.published','=','1')
                                      ->where('icr_carbasicinfo.cbi_id','=',$cbi_id)
                                      ->where('icr_carbasicinfo.cbi_owner_id','=',$cbi_owner_id)
                                      ->where('icr_carbasicinfo.cbi_postownertype','=',$cbi_postownertype)
                                      ->leftJoin('icr_depositbuyitmerec','icr_carbasicinfo.cbi_id','=','icr_depositbuyitmerec.dbir_object_id');
             $result = $query->select('icr_carbasicinfo.cbi_postownertype'
                                     ,'icr_carbasicinfo.cbi_owner_id'
                                     ,'icr_depositbuyitmerec.dbir_expiredate'
                                     ,'icr_carbasicinfo.cbi_salestatus')
                                     ->get()->toArray();
             return $result;
        } catch (\Exception $e) {
          ErrorLog::InsertData($e);
          return null;
        }
     } 
     
     public static function GetDataByCbiid ($cbi_id) {
        try {
             if ($cbi_id == null || strlen($cbi_id) == 0 ) {
                return null;
             }
             $query = ICR_CarBasicInfo::where('icr_carbasicinfo.published','=','1')
                                      ->where('icr_carbasicinfo.cbi_id','=',$cbi_id)
                                      ->where('icr_carpictures.cps_picscategory','=','4')
                                      ->leftJoin('icr_carpictures','icr_carbasicinfo.cbi_id','=','icr_carpictures.cbi_id');
             $result = $query->select('icr_carbasicinfo.cbi_postownertype'
                                     ,'icr_carbasicinfo.cbi_owner_id'
                                     ,'icr_carpictures.cps_picpath')
                                     ->get()->toArray();
               return $result;
        } catch (\Exception $e) {
          ErrorLog::InsertData($e);
          return null;
        }
     }
     
     public static function GetData_ByCbiIdArray($cbi_id_array) {
         try {
              $query = ICR_CarBasicInfo::whereIn('icr_carbasicinfo.cbi_id',$cbi_id_array)
                                      ->where('icr_carpictures.cps_picscategory','=','4')
                                      ->leftJoin('icr_carbrandlist', 'icr_carbasicinfo.cbi_carbrand', '=', 'icr_carbrandlist.cbl_id')
                                      ->leftJoin('icr_carbrandmodel', 'icr_carbasicinfo.cbi_brandmodel', '=', 'icr_carbrandmodel.cbm_id')
                                      ->leftJoin('icr_carmodelstyle', 'icr_carbasicinfo.cbi_modelstyle', '=', 'icr_carmodelstyle.cms_id')
                                      ->leftJoin('icr_shopdata','icr_carbasicinfo.cbi_owner_id','=','icr_shopdata.sd_id')
                                      ->leftjoin('iscarmemberdata','icr_carbasicinfo.cbi_owner_id','=','iscarmemberdata.md_id')
                                      ->leftJoin('icr_carpictures','icr_carbasicinfo.cbi_id','=','icr_carpictures.cbi_id');
             $result = $query->select('icr_carbasicinfo.cbi_id'
                                     ,'icr_carbrandlist.cbl_fullname'
                                     ,'icr_carbrandmodel.cbm_fullname'
                                     ,'icr_carbasicinfo.cbi_postownertype'
                                     ,'icr_shopdata.sd_shopname'
                                     ,'iscarmemberdata.md_cname'
                                     ,'icr_shopdata.sd_shopaddress'
                                     ,'icr_carmodelstyle.cms_fullname'
                                     ,'icr_carpictures.cps_picpath'
                                     ,'icr_carbasicinfo.cbi_advertisementtitle')
                                     ->get()->toArray();
              return $result;            
         } catch (\Exception $e) {
          ErrorLog::InsertData($e);
          return null;
        }
     }
     
     public static function GetDataByNcbiId($ncbi_id) {
         try {
               $query = ICR_CarBasicInfo::where('icr_carbasicinfo.cbi_id','=',$ncbi_id); 
               $result = $query->select('icr_carbasicinfo.cbi_id')->get()->toArray();
               return $result; 
         } catch (\ExceptIon $e) {
          ErrorLog::InsertData($e);
          return null;
         }
     }
     
    /**
     * ██████████▍UPDATE 更新資料
     */

   

    /**
     * ██████████▍DELETE 刪除資料
     */
    /**
     * ██████████▍CHECK 檢查資料
     */
}
