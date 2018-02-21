<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class ICR_Carreservation extends Model {

    public $table = 'icr_carreservation';
    public $primaryKey = 'crn_id';
    public $timestamps = false;

    /**
     * ██████████▍CREATE 建立資料
     */
     /**
     * InsertData
     * @param array $arraydata
     */
    public static function InsertData($arraydata) {
        try {
              if (!Commontools::CheckArrayValue($arraydata, "crn_id") || !Commontools::CheckArrayValue($arraydata, "cbi_id") || !Commontools::CheckArrayValue($arraydata, "crn_buyer_md_id")
                  ||!Commontools::CheckArrayValue($arraydata, "crn_buyer_realname")|| !Commontools::CheckArrayValue($arraydata, "crn_available_timearray") || !Commontools::CheckArrayValue($arraydata, "crn_owner_id")
                  ) {
                return false;
              } 
              $savadata['crn_id'] = $arraydata['crn_id'];
              $savadata['cbi_id'] = $arraydata['cbi_id'];
              $savadata['crn_buyer_md_id'] = $arraydata['crn_buyer_md_id'];
              $savadata['crn_buyer_realname'] = $arraydata['crn_buyer_realname'];
              $savadata['crn_available_timearray'] = $arraydata['crn_available_timearray'];
              $savadata['crn_owner_id'] = $arraydata['crn_owner_id'];
            
              
              if (Commontools::CheckArrayValue($arraydata, "crn_reservationtime")) {
                $savadata['crn_reservationtime'] = $arraydata['crn_reservationtime'];
              } 
              if (Commontools::CheckArrayValue($arraydata, "crn_reply_md_id")) {
                $savadata['crn_reply_md_id'] = $arraydata['crn_reply_md_id'];
              } 
              if (Commontools::CheckArrayValue($arraydata, "crn_buyer_ask_message")) {
                $savadata['crn_buyer_ask_message'] = $arraydata['crn_buyer_ask_message'];
              } 
              if (Commontools::CheckArrayValue($arraydata, "crn_carinstore_ask")) {
                $savadata['crn_carinstore_ask'] = $arraydata['crn_carinstore_ask'];
              } 
              if (Commontools::CheckArrayValue($arraydata, "crn_seller_reply_tag")) {
                $savadata['crn_seller_reply_tag'] = $arraydata['crn_seller_reply_tag'];
              } 
              if (Commontools::CheckArrayValue($arraydata, "crn_ownertype")) {
                $savadata['crn_ownertype'] = $arraydata["crn_ownertype"];
              }
              if (Commontools::CheckArrayValue($arraydata, "crn_carinstore_comfirm")) {
                $savadata['crn_carinstore_comfirm'] = $arraydata["crn_carinstore_comfirm"];
              }
              if (Commontools::CheckArrayValue($arraydata, "crn_buyerconfirm_tag")) {
                $savadata['crn_buyerconfirm_tag'] = $arraydata["crn_buyerconfirm_tag"];
              }
              if (Commontools::CheckArrayValue($arraydata, "crn_soldout_tag")) {
                $savadata['crn_soldout_tag'] = $arraydata["crn_soldout_tag"];
              }
              if (Commontools::CheckArrayValue($arraydata, "crn_cancel_tag")) {
                $savadata['crn_cancel_tag'] = $arraydata["crn_cancel_tag"];
              }
              if (Commontools::CheckArrayValue($arraydata, "crn_cancel_date")) {
                $savadata['crn_cancel_date'] = $arraydata["crn_cancel_date"];
              }
              if (Commontools::CheckArrayValue($arraydata, "crn_cancel_usertype")) {
                $savadata['crn_cancel_usertype'] = $arraydata["crn_cancel_usertype"];
              }
              if (Commontools::CheckArrayValue($arraydata, "crn_cancel_user_id")) {
                $savadata['crn_cancel_user_id'] = $arraydata["crn_cancel_user_id"];
              }
              if (Commontools::CheckArrayValue($arraydata, "crn_reservationexec_tag")) {
                $savadata['crn_reservationexec_tag'] = $arraydata["crn_reservationexec_tag"];
              }
              if (Commontools::CheckArrayValue($arraydata, 'published')) {
                $savadata['published'] = $arraydata['published'];
              } else {
                $savadata['published'] = '1';
              } 
              
              DB::table('icr_carreservation')->insert($savadata);  
              return true;
        } catch (Exception $e) {
            \App\models\ErrorLog::InsertData($e);
            return false;
        }
    }
    
    
      public static function GetData_ByCrnkId($crn_id) {

        if ($crn_id == null || strlen($crn_id) == 0) {
            return null;
        }

         $query = ICR_Carreservation::where('icr_carreservation.published','=' ,'1')
                ->where('icr_carreservation.crn_id','=',$crn_id)
                ->where('icr_carpictures.cps_picscategory','=','4')
                ->leftJoin('icr_carbasicinfo', 'icr_carreservation.cbi_id', '=', 'icr_carbasicinfo.cbi_id')
                ->leftJoin('icr_carpictures', 'icr_carbasicinfo.cbi_id', '=', 'icr_carpictures.cbi_id');
                
         $result = $query->select(
                                    'icr_carreservation.crn_ownertype'
                                   ,'icr_carreservation.crn_owner_id'
                                   ,'icr_carreservation.crn_buyer_md_id'
                                   ,'icr_carreservation.crn_available_timearray'
                                   ,'icr_carbasicinfo.cbi_id'
                                   ,'icr_carpictures.cps_picpath'
                                  )
                                   ->get()->toArray();       
                      
        return $result;
    }
    
     /**
     * 修改資料
     * @param array $arraydata 要更新的資料
     * @return boolean
     */
    public static function UpdateData($arraydata) {
        try {

            if (!Commontools::CheckArrayValue($arraydata, 'crn_id')) {
                return false;
            }

            $savedata['crn_id'] = $arraydata['crn_id'];

            if (Commontools::CheckArrayValue($arraydata, "cbi_id")) {
                $savedata['cbi_id'] = $arraydata['cbi_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, "crn_buyer_md_id")) {
                $savedata['crn_buyer_md_id'] = $arraydata['crn_buyer_md_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, "crn_buyer_realname")) {
                $savedata['crn_buyer_realname'] = $arraydata['crn_buyer_realname'];
            }
            if (Commontools::CheckArrayValue($arraydata, "crn_buyer_ask_message")) {
                $savedata['crn_buyer_ask_message'] = $arraydata['crn_buyer_ask_message'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'crn_available_timearray')) {
                $savedata['crn_available_timearray'] = $arraydata['crn_available_timearray'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'crn_carinstore_ask')) {
                $savedata['crn_carinstore_ask'] = $arraydata['crn_carinstore_ask'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'crn_seller_reply_tag')) {
                $savedata['crn_seller_reply_tag'] = $arraydata['crn_seller_reply_tag'];
            }
            if (Commontools::CheckArrayValue($arraydata, "crn_ownertype")) {
                $savedata['crn_ownertype'] = $arraydata['crn_ownertype'];
            }
            if (Commontools::CheckArrayValue($arraydata, "crn_owner_id")) {
                $savedata['crn_owner_id'] = $arraydata['crn_owner_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, "crn_reply_md_id")) {
                $savedata['crn_reply_md_id'] = $arraydata['crn_reply_md_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, "crn_ans_message")) {
                $savedata['crn_ans_message'] = $arraydata['crn_ans_message'];
            }
            if (Commontools::CheckArrayValue($arraydata, "crn_reservationtime")) {
                $savedata['crn_reservationtime'] = $arraydata['crn_reservationtime'];
            }
            if (Commontools::CheckArrayValue($arraydata, "crn_carinstore_comfirm")) {
                $savedata['crn_carinstore_comfirm'] = $arraydata['crn_carinstore_comfirm'];
            }
            if (Commontools::CheckArrayValue($arraydata, "crn_buyerconfirm_tag")) {
                $savedata['crn_buyerconfirm_tag'] = $arraydata['crn_buyerconfirm_tag'];
            }
            if (Commontools::CheckArrayValue($arraydata, "crn_soldout_tag")) {
                $savedata['crn_soldout_tag'] = $arraydata['crn_soldout_tag'];
            }
            if (Commontools::CheckArrayValue($arraydata, "crn_cancel_date")) {
                $savedata['crn_cancel_date'] = $arraydata['crn_cancel_date'];
            }
            if (Commontools::CheckArrayValue($arraydata, "crn_cancel_usertype")) {
                $savedata['crn_cancel_usertype'] = $arraydata['crn_cancel_usertype'];
            }
            if (Commontools::CheckArrayValue($arraydata, "crn_cancel_user_id")) {
                $savedata['crn_cancel_user_id'] = $arraydata['crn_cancel_user_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, "crn_reservationexec_tag")) {
                $savedata['crn_reservationexec_tag'] = $arraydata['crn_reservationexec_tag'];
            }
            if (Commontools::CheckArrayValue($arraydata, "published")) {
                $savedata['published'] = $arraydata['published'];
            }
            $savedata['last_update_date'] = date('Y-m-d H:i:s');

            DB::table('icr_carreservation')
                    ->where('crn_id', $savedata['crn_id'])
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }
   
    public static function GetData_ByCrnId($cbi_id,$crn_owner_id,$crn_id) {
      try {
            if ($cbi_id == null || strlen($cbi_id) == 0) {
                return null;
            }
            $query = ICR_Carreservation::where('icr_carreservation.cbi_id','=',$cbi_id)
                ->where('icr_carpictures.cps_picscategory','=','4')
                ->where('icr_carreservation.crn_owner_id','=',$crn_owner_id)
                ->where('icr_carreservation.crn_id','=',$crn_id)
                ->leftJoin('icr_carbasicinfo', 'icr_carreservation.cbi_id', '=', 'icr_carbasicinfo.cbi_id')
                ->leftJoin('icr_carbrandlist', 'icr_carbasicinfo.cbi_carbrand', '=', 'icr_carbrandlist.cbl_id')
                ->leftJoin('icr_carbrandmodel', 'icr_carbasicinfo.cbi_brandmodel', '=', 'icr_carbrandmodel.cbm_id')
                ->leftJoin('icr_carmodelstyle', 'icr_carbasicinfo.cbi_modelstyle', '=', 'icr_carmodelstyle.cms_id')
                ->leftJoin('icr_carpictures', 'icr_carbasicinfo.cbi_id', '=', 'icr_carpictures.cbi_id');
             $result = $query->select(
                                    'icr_carbasicinfo.cbi_advertisementtitle'
                                   ,'icr_carpictures.cps_picpath'
                                   ,'icr_carreservation.crn_buyer_realname'
                                   ,'icr_carreservation.crn_buyer_ask_message'
                                   ,'icr_carreservation.crn_available_timearray'
                                   ,'icr_carreservation.crn_carinstore_ask'
                                   ,'icr_carbrandlist.cbl_fullname'
                                   ,'icr_carbrandmodel.cbm_fullname'
                                   ,'icr_carmodelstyle.cms_fullname'
                                  )
                                   ->get()->toArray();       

             return $result;
         } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }
    
    public static function GetData_ByCrnid_Cbiid($cbi_id, $crn_id) {
      try {
            if ($cbi_id == null || strlen($cbi_id) == 0) {
                return null;
            }
            $query = ICR_Carreservation::where('icr_carreservation.published','=' ,'1')
                ->where('icr_carreservation.cbi_id','=',$cbi_id)
                ->where('icr_carpictures.cps_picscategory','=','4')
                ->where('crn_id','=',$crn_id)
                ->leftJoin('icr_carbasicinfo', 'icr_carreservation.cbi_id', '=', 'icr_carbasicinfo.cbi_id')
                ->leftJoin('icr_carbrandlist', 'icr_carbasicinfo.cbi_carbrand', '=', 'icr_carbrandlist.cbl_id')
                ->leftJoin('icr_carbrandmodel', 'icr_carbasicinfo.cbi_brandmodel', '=', 'icr_carbrandmodel.cbm_id')
                ->leftJoin('icr_carmodelstyle', 'icr_carbasicinfo.cbi_modelstyle', '=', 'icr_carmodelstyle.cms_id')
                ->leftJoin('icr_carpictures', 'icr_carbasicinfo.cbi_id', '=', 'icr_carpictures.cbi_id')
                ->leftJoin('icr_shopdata', 'icr_carbasicinfo.cbi_owner_id','=','icr_shopdata.sd_id')
                ->leftJoin('iscarmemberdata','icr_carbasicinfo.cbi_owner_id','=','iscarmemberdata.md_id')
                ->leftJoin(DB::raw('(SELECT iscarmemberdata.md_id as replyid,iscarmemberdata.md_cname as crn_reply_md_cname FROM iscarmemberdata) AS reply'), function($join) {
                               $join->on('icr_carreservation.crn_reply_md_id', '=', 'reply.replyid');
                          });
             $results = $query->select(
                                    'icr_carreservation.crn_buyer_realname'
                                   ,'icr_carreservation.crn_buyer_ask_message'
                                   ,'icr_carreservation.crn_carinstore_ask'
                                   ,'icr_carreservation.crn_seller_reply_tag'
                                   ,'icr_carreservation.crn_seller_ans_message'
                                   ,'icr_carreservation.crn_reservationtime'
                                   ,'icr_carreservation.crn_carinstore_comfirm'
                                   ,'icr_carreservation.crn_soldout_tag'
                                   ,'icr_carreservation.crn_cancel_tag'
                                   ,'icr_carreservation.crn_cancel_date'
                                   ,'icr_carreservation.crn_cancel_usertype'
                                   ,'icr_carreservation.crn_reservationexec_tag'
                                   ,'icr_carreservation.crn_buyer_md_id'
                                   ,'icr_carreservation.crn_owner_id'
                                   ,'icr_carreservation.crn_ownertype'
                                   ,'icr_carbasicinfo.cbi_advertisementtitle'
                                   ,'icr_carpictures.cps_picpath'
                                   ,'icr_carbrandlist.cbl_fullname'
                                   ,'icr_carbrandmodel.cbm_fullname'
                                   ,'icr_carmodelstyle.cms_fullname'
                                   ,'icr_shopdata.sd_shopname as shopname0'
                                   ,'icr_shopdata.sd_shopaddress as shopaddress0'
                                   ,'icr_shopdata.sd_shoptel as shoptel0'
                                   ,'iscarmemberdata.md_cname as shopname1'
                                   ,'iscarmemberdata.md_addr as shopaddress1'
                                   ,'iscarmemberdata.md_tel as shoptel1'
                                   ,'reply.crn_reply_md_cname'
                                  )
                                   ->get()->toArray();       

             return $results;
         } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
      
    }
   
   public static function GatData_ByOwnerid_OrBuyerid($published, $last_update_date, $crn_buyer_md_id, $crn_owner_id) {
        try {
              
            $query = ICR_Carreservation::where('icr_carreservation.published','=' ,$published);
            if (!is_null($last_update_date) && strlen($last_update_date) != 0) {
               $query ->where('icr_carreservation.last_update_date','>',$last_update_date);
            }
            if (!is_null($crn_buyer_md_id) && strlen($crn_buyer_md_id) != 0) {
               $query->where('icr_carreservation.crn_buyer_md_id','=',$crn_buyer_md_id);
            }
            if (!is_null($crn_owner_id) && strlen($crn_owner_id) != 0) {
                $query->where('icr_carreservation.crn_owner_id','=',$crn_owner_id);
            } 
             $results = $query->select(
                                    'icr_carreservation.crn_id as CRN_ID'
                                   ,'icr_carreservation.cbi_id' 
                                   ,'icr_carreservation.crn_buyer_md_id'
                                   ,'icr_carreservation.crn_buyer_realname'
                                   ,'icr_carreservation.crn_ownertype'
                                   ,'icr_carreservation.crn_owner_id'
                                   ,'icr_carreservation.crn_reservationtime' 
                                   ,'icr_carreservation.crn_seller_reply_tag'
                                   ,'icr_carreservation.crn_cancel_tag'
                                   ,'icr_carreservation.crn_cancel_date'
                                   ,'icr_carreservation.crn_reservationexec_tag'
                                   ,'icr_carreservation.last_update_date'
                                  )
                                   ->get()->toArray();       

             return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
   }
}
