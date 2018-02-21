<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家基本資料
 */
class ICR_ShopAdpush_M extends Model {

//
    public $table = 'icr_shopadpush_m';
    public $primaryKey = 'sapm_id';
    public $timestamps = false;
    public $incrementing = false;
    


    public static function InsertData($arraydata){
      try {
             if (!Commontools::CheckArrayValue($arraydata, "sapm_id") || !Commontools::CheckArrayValue($arraydata, "sd_id")
              || !Commontools::CheckArrayValue($arraydata, "md_id")   || !Commontools::CheckArrayValue($arraydata, "sapm_pushcontent")) {
                return false;
              }
              $savadata['sd_id'] = $arraydata['sd_id'];
              $savadata['sapm_id'] = $arraydata['sapm_id'];
              $savadata['md_id'] = $arraydata['md_id'];
              $savadata['sapm_pushcontent'] = $arraydata['sapm_pushcontent'];
                
              if (Commontools::CheckArrayValue($arraydata, "sapm_objecttype")) {
                $savadata['sapm_objecttype'] = $arraydata['sapm_objecttype'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sapm_pushpic")) {
                $savadata['sapm_pushpic'] = $arraydata['sapm_pushpic'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sapm_designatefunction")) {
                $savadata['sapm_designatefunction'] = $arraydata['sapm_designatefunction'];
              } 
              if (Commontools::CheckArrayValue($arraydata, "sapm_objectfilter")) {
                $savadata['sapm_objectfilter'] = $arraydata['sapm_objectfilter'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sapm_objectamount")) {
                $savadata['sapm_objectamount'] = $arraydata['sapm_objectamount'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sapm_pushfee")) {
                $savadata['sapm_pushfee'] = $arraydata['sapm_pushfee'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sapm_feeitem")) {
                $savadata['sapm_feeitem'] = $arraydata['sapm_feeitem'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sapm_discountitem")) {
                $savadata['sapm_discountitem'] = $arraydata['sapm_discountitem'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sapm_pushresult")) {
                $savadata['sapm_pushresult'] = $arraydata['sapm_pushresult'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sapm_approvaltag")) {
                $savadata['sapm_approvaltag'] = $arraydata['sapm_approvaltag'];
              }
              if (Commontools::CheckArrayValue($arraydata, "sapm_approval_admin")) {
                $savadata['sapm_approval_admin'] = $arraydata['sapm_approval_admin'];
              } 
              
              if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savadata['isflag'] = $arraydata['isflag'];
              } else {
                $savadata['isflag'] = '1';
              }
              $savadata['create_user'] = 'webapi';
              $savadata['last_update_user'] = 'webapi';
              DB::table('icr_shopadpush_m')->insert($savadata);
              return true;
       } catch(Exception $e) {
            ErrorLog::Insert($ex);
            return false;
       }
    }


  
    /**
     * 修改資料
     * @param array $arraydata 要更新的資料
     * @return boolean
     */
    public static function UpdateData(array $arraydata) {
        try {
            if (!Commontools::CheckArrayValue($arraydata, 'sapm_id')) {
                return false;
            }

            $savedata['sapm_id'] = $arraydata['sapm_id'];

            if (Commontools::CheckArrayValue($arraydata, 'sd_id')) {
                $savedata['sd_id'] = $arraydata['sd_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'md_id')) {
                $savedata['md_id'] = $arraydata['md_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sapm_objecttype')) {
                $savedata['sapm_objecttype'] = $arraydata['sapm_objecttype'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sapm_pushpic')) {
                $savedata['sapm_pushpic'] = $arraydata['sapm_pushpic'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sapm_pushcontent')) {
                $savedata['sapm_pushcontent'] = $arraydata['sapm_pushcontent'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sapm_designatefunction')) {
                $savedata['sapm_designatefunction'] = $arraydata['sapm_designatefunction'];
            }
            
            if (Commontools::CheckArrayValue($arraydata, 'sapm_objectfilter')) {
                $savedata['sapm_objectfilter'] = $arraydata['sapm_objectfilter'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sapm_objectamount')) {
                $savedata['sapm_objectamount'] = $arraydata['sapm_objectamount'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sapm_pushfee')) {
                $savedata['sapm_pushfee'] = $arraydata['sapm_pushfee'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sapm_pushpic')) {
                $savedata['sapm_pushpic'] = $arraydata['sapm_pushpic'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sapm_feeitem')) {
                $savedata['sapm_feeitem'] = $arraydata['sapm_feeitem'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sapm_discountitem')) {
                $savedata['sapm_discountitem'] = $arraydata['sapm_discountitem'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sapm_pushresult')) {
                $savedata['sapm_pushresult'] = $arraydata['sapm_pushresult'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sapm_approvaltag')) {
                $savedata['sapm_approvaltag'] = $arraydata['sapm_approvaltag'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sapm_approval_admin')) {
                $savedata['sapm_approval_admin'] = $arraydata['sapm_approval_admin'];
            }
           
            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            }

            $savedata['last_update_user'] = 'webapi';


            DB::table('icr_shopadpush_m')
                    ->where('sapm_id', $savedata['sapm_id'])
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }
    
     public static function GetPushDataList($arraydata) {
       try {
            if (!Commontools::CheckArrayValue($arraydata, "sd_id") || !Commontools::CheckArrayValue($arraydata, "sapm_objecttype") ) {
                return null;
            }
            
            $query = ICR_ShopAdpush_M::where('icr_shopadpush_m.isflag', '=', '1')
                                 ->where('icr_shopadpush_m.sd_id', '=',$arraydata['sd_id']) 
                                 ->where('icr_shopadpush_m.sapm_objecttype', '=',$arraydata['sapm_objecttype'])
                                 ->orderBy('icr_shopadpush_m.create_date','desc');

             
             if (Commontools::CheckArrayValue($arraydata, "create_date")) {
                 $query->where('icr_shopadpush_m.create_date','<',$arraydata['create_date']);
             } 
             if(Commontools::CheckArrayValue($arraydata, "queryamount")) {
                 $query->take($arraydata['queryamount'])->skip(0);
             } else {
                 $query->take(100)->skip(0);
             }

            $results = $query->get()->toArray();

            return $results;
            
       } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null;
       }
    }
    
    
      public static function GetPushContent($sapm_id) {
       try {
            
            $query = ICR_ShopAdpush_M::where('icr_shopadpush_m.sapm_id', '=', $sapm_id)
                            ->leftJoin('icr_shopadpush_d', 'icr_shopadpush_m.sapm_id', '=', 'icr_shopadpush_d.sapm_id')
                            ->leftJoin('iscarmemberdata', 'icr_shopadpush_m.md_id', '=', 'iscarmemberdata.md_id');
                            //->leftJoin('iscarusermessagelog', 'icr_shopadpush_d.uml_id', '=', 'iscarusermessagelog.uml_id');
             $result = $query->select('icr_shopadpush_m.*'
                                      ,'iscarmemberdata.md_cname'
                                      ,'icr_shopadpush_d.sapd_serno')
                                      //,'iscarusermessagelog.uml_status')
                                      ->get()->toArray();
             return $result;
       } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null;
       }
    }

   
}
