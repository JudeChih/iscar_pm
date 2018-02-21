<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家基本資料
 */
class ICR_SystemParameter extends Model {

//
    public $table = 'icr_systemparameter';
    public $primaryKey = 'sp_serno';
    public $timestamps = false;
    public $incrementing = false;
    


    public static function InsertData($arraydata){
      try {
             if (!Commontools::CheckArrayValue($arraydata, "sp_fitmodule") || !Commontools::CheckArrayValue($arraydata, "sp_modulename")
              || !Commontools::CheckArrayValue($arraydata, "sp_fitfunction")  || !Commontools::CheckArrayValue($arraydata, "sp_functionname")
              || !Commontools::CheckArrayValue($arraydata, "sp_parameterkey")  || !Commontools::CheckArrayValue($arraydata, "sp_paramatervalue")
              || !Commontools::CheckArrayValue($arraydata, "sp_paramatertype")  || !Commontools::CheckArrayValue($arraydata, "sp_paramaterdescribe")) {
                return false;
              }
              $savadata['sp_fitmodule'] = $arraydata['sp_fitmodule'];
              $savadata['sp_modulename'] = $arraydata['sp_modulename'];
              $savadata['sp_fitfunction'] = $arraydata['sp_fitfunction'];
              $savadata['sp_functionname'] = $arraydata['sp_functionname'];
              $savadata['sp_parameterkey'] = $arraydata['sp_parameterkey'];
              $savadata['sp_paramatervalue'] = $arraydata['sp_paramatervalue'];
              $savadata['sp_paramatertype'] = $arraydata['sp_paramatertype'];
              $savadata['sp_paramaterdescribe'] = $arraydata['sp_paramaterdescribe'];
            
              if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savadata['isflag'] = $arraydata['isflag'];
              } else {
                $savadata['isflag'] = '1';
              }
              $savadata['create_user'] = 'webapi';
              $savadata['last_update_user'] = 'webapi';
              DB::table('icr_systemparameter')->insert($savadata);
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
            if (!Commontools::CheckArrayValue($arraydata, 'sp_serno')) {
                return false;
            }

            $savedata['sp_serno'] = $arraydata['sp_serno'];

            if (Commontools::CheckArrayValue($arraydata, 'sp_fitmodule')) {
                $savedata['sp_fitmodule'] = $arraydata['sp_fitmodule'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sp_modulename')) {
                $savedata['sp_modulename'] = $arraydata['sp_modulename'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sp_fitfunction')) {
                $savedata['sp_fitfunction'] = $arraydata['sp_fitfunction'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sp_functionname')) {
                $savedata['sp_functionname'] = $arraydata['sp_functionname'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sp_parameterkey')) {
                $savedata['sp_parameterkey'] = $arraydata['sp_parameterkey'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sp_paramatervalue')) {
                $savedata['sp_paramatervalue'] = $arraydata['sp_paramatervalue'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sp_paramatertype')) {
                $savedata['sp_paramatertype'] = $arraydata['sp_paramatertype'];
            }
            if (Commontools::CheckArrayValue($arraydata, 'sp_paramaterdescribe')) {
                $savedata['sp_paramaterdescribe'] = $arraydata['sp_paramaterdescribe'];
            }
           
            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            }

            $savedata['last_update_user'] = 'webapi';


            DB::table('icr_systemparameter')
                    ->where('sapd_serno', $savedata['sapd_serno'])
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }
    
    
    
    public static function GetFunctionParamater($sp_fitmodule, $sp_fitfunction) {
        try {
              if ( is_null($sp_fitmodule) || mb_strlen($sp_fitmodule) == 0 || is_null($sp_fitfunction) || mb_strlen($sp_fitfunction) == 0 ) {
                  return null;
              }
              $result = ICR_SystemParameter::where('icr_systemparameter.sp_fitmodule', '=', $sp_fitmodule)
                                           ->where('icr_systemparameter.sp_fitfunction','=',$sp_fitfunction)
                                           ->get()->toArray();
             return $result;
        } catch(\Exception $e) {
           ErrorLog::InsertData($e);
           return false;
        }
    } 

   


   public static function getSuntechBuySafe() {
    try {
            $result = ICR_SystemParameter::whereRaw("icr_systemparameter.sp_parameterkey like '%suntech_buysafe%' ")
                                           ->where('icr_systemparameter.isflag','=','1')
                                           ->get()->toArray();
             return $result;
        } catch(\Exception $e) {
           ErrorLog::InsertData($e);
           return false;
        }
   }

   public static function getSuntechSwipy() {
    try {
            $result = ICR_SystemParameter::whereRaw("icr_systemparameter.sp_parameterkey like '%suntech_swipy%' ")
                                           ->where('icr_systemparameter.isflag','=','1')
                                           ->get()->toArray();
             return $result;
        } catch(\Exception $e) {
           ErrorLog::InsertData($e);
           return false;
        }
   }
   
   public static function getSharerForGiftpointProportion() {
    try {
            $result = ICR_SystemParameter::whereRaw("icr_systemparameter.sp_parameterkey like '%sharer_forgiftpoint_proportion%' ")
                                           ->where('icr_systemparameter.isflag','=','1')
                                           ->get()->toArray();
             return $result;
        } catch(\Exception $e) {
           ErrorLog::InsertData($e);
           return false;
        }
   }
   
   public static function getGPMaxUseRate() {
    try {
            $result = ICR_SystemParameter::whereRaw("icr_systemparameter.sp_parameterkey like '%GPMaxUseRate%' ")
                                           ->where('icr_systemparameter.isflag','=','1')
                                           ->get()->toArray();
             return $result;
        } catch(\Exception $e) {
           ErrorLog::InsertData($e);
           return false;
        }
   }
   
   public static function getGPExchangeCashRate() {
    try {
            $result = ICR_SystemParameter::whereRaw("icr_systemparameter.sp_parameterkey like '%GPExchangeCashRate%' ")
                                           ->where('icr_systemparameter.isflag','=','1')
                                           ->get()->toArray();
             return $result;
        } catch(\Exception $e) {
           ErrorLog::InsertData($e);
           return false;
        }
   }

   

   public static function getPayGiftpointProportion() {
    try {
            $result = ICR_SystemParameter::whereRaw("icr_systemparameter.sp_parameterkey like '%PayGiftpointAsCash%' ")
                                           ->where('icr_systemparameter.isflag','=','1')
                                           ->get()->toArray();
             return $result;
        } catch(\Exception $e) {
           ErrorLog::InsertData($e);
           return false;
        }
   }
   
   public static function getSecretaryEmail() {
    try {
            $result = ICR_SystemParameter::whereRaw("icr_systemparameter.sp_parameterkey like '%secretary_email%' ")
                                           ->where('icr_systemparameter.isflag','=','1')
                                           ->get()->toArray();
             return $result;
        } catch(\Exception $e) {
           ErrorLog::InsertData($e);
           return false;
        }
   }
   
   public static function getFormfee() {
    try {
            $result = ICR_SystemParameter::whereRaw("icr_systemparameter.sp_parameterkey like '%formfee%' ")
                                           ->where('icr_systemparameter.isflag','=','1')
                                           ->get()->toArray();
             return $result[0]['sp_paramatervalue'];
        } catch(\Exception $e) {
           ErrorLog::InsertData($e);
           return false;
        }
   }
   
   
    public static function getFlowfee() {
    try {
            $result = ICR_SystemParameter::whereRaw("icr_systemparameter.sp_parameterkey like '%flowfee%' ")
                                           ->where('icr_systemparameter.isflag','=','1')
                                           ->get()->toArray();
             return $result[0]['sp_paramatervalue'];
        } catch(\Exception $e) {
           ErrorLog::InsertData($e);
           return false;
        }
   }
   
}
