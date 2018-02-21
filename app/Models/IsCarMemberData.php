<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class IsCarMemberData extends Model {

    //
    public $table = 'iscarmemberdata';
    public $primaryKey = 'md_id';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * 新增資料
     * @param   $arraydata 
     * @return  Boolean
     */
    public static function InsertData($arraydata, &$md_id) {

        //取得新的代碼
        if (is_null($md_id)) {
            $md_id = Commontools::NewGUIDWithoutDash();
        }
        $savedata['md_id'] = $md_id;

         /*if (
               !Commontools::CheckArrayValue($arraydata, "md_logintype") || !Commontools::CheckArrayValue($arraydata, "ssd_onlinestatus")//1
                || !Commontools::CheckArrayValue($arraydata, "md_rightstatus") ||
              !Commontools::CheckArrayValue($arraydata, "ssd_accountid") || !Commontools::CheckArrayValue($arraydata, "ssd_accesstoken") || !Commontools::CheckArrayValue($arraydata, "ssd_longtermtoken")
        ) {
            return false;
        }

        $savedata['md_logintype'] = $arraydata['md_logintype'];
        $savedata['ssd_onlinestatus'] = $arraydata['ssd_onlinestatus'];
        $savedata['md_rightstatus'] = $arraydata['md_rightstatus'];
        $savedata['ssd_accountid'] = $arraydata['ssd_accountid'];
        $savedata['ssd_accesstoken'] = $arraydata['ssd_accesstoken'];
        $savedata['ssd_longtermtoken'] = $arraydata['ssd_longtermtoken'];*/

        if (Commontools::CheckArrayValue($arraydata, "md_logintype")) {
            $savedata['md_logintype'] = $arraydata['md_logintype'];
        }
         if (Commontools::CheckArrayValue($arraydata, "ssd_onlinestatus")) {
            $savedata['ssd_onlinestatus'] = $arraydata['ssd_onlinestatus'];
        }
         if (Commontools::CheckArrayValue($arraydata, "md_rightstatus")) {
            $savedata['md_rightstatus'] = $arraydata['md_rightstatus'];
        }
         if (Commontools::CheckArrayValue($arraydata, "ssd_accountid")) {
            $savedata['ssd_accountid'] = $arraydata['ssd_accountid'];
        }
         if (Commontools::CheckArrayValue($arraydata, "ssd_accesstoken")) {
            $savedata['ssd_accesstoken'] = $arraydata['ssd_accesstoken'];
        }
         if (Commontools::CheckArrayValue($arraydata, "ssd_longtermtoken")) {
            $savedata['ssd_longtermtoken'] = $arraydata['ssd_longtermtoken'];
        }

        if (Commontools::CheckArrayValue($arraydata, "ssd_accountmail")) {
            $savedata['ssd_accountmail'] = $arraydata['ssd_accountmail'];
        }

        if (Commontools::CheckArrayValue($arraydata, "ssd_accountname")) {
            $savedata['ssd_accountname'] = $arraydata['ssd_accountname'];
        }

        if (Commontools::CheckArrayValue($arraydata, "ssd_fbfirstname")) {
            $savedata['ssd_fbfirstname'] = $arraydata['ssd_fbfirstname'];
        }

        if (Commontools::CheckArrayValue($arraydata, "ssd_fblastname")) {
            $savedata['ssd_fblastname'] = $arraydata['ssd_fblastname'];
        }

        if (Commontools::CheckArrayValue($arraydata, "ssd_fblocallanguage")) {
            $savedata['ssd_fblocallanguage'] = $arraydata['ssd_fblocallanguage'];
        }

        if (Commontools::CheckArrayValue($arraydata, "ssd_fbgender")) {
            $savedata['ssd_fbgender'] = $arraydata['ssd_fbgender'];
        }

        if (Commontools::CheckArrayValue($arraydata, "ssd_birthday")) {
            $savedata['ssd_birthday'] = $arraydata['ssd_birthday'];
        }

        if (Commontools::CheckArrayValue($arraydata, "ssd_timezone")) {
            $savedata['ssd_timezone'] = $arraydata['ssd_timezone'];
        }

        if (Commontools::CheckArrayValue($arraydata, "ssd_picturepath")) {
            $savedata['ssd_picturepath'] = $arraydata['ssd_picturepath'];
        }

        if (Commontools::CheckArrayValue($arraydata, "rl_sn")) {
            $savedata['rl_sn'] = $arraydata['rl_sn'];
        } else {
            $savedata['rl_sn'] = "1";
        }

        if (Commontools::CheckArrayValue($arraydata, "md_cname")) {
            $savedata['md_cname'] = $arraydata['md_cname'];
        }

        if (Commontools::CheckArrayValue($arraydata, "md_ename")) {
            $savedata['md_ename'] = $arraydata['md_ename'];
        }

        if (Commontools::CheckArrayValue($arraydata, "md_tel")) {
            $savedata['md_tel'] = $arraydata['md_tel'];
        }

        if (Commontools::CheckArrayValue($arraydata, "md_mobile")) {
            $savedata['md_mobile'] = $arraydata['md_mobile'];
        }

        if (Commontools::CheckArrayValue($arraydata, "md_addr")) {
            $savedata['md_addr'] = $arraydata['md_addr'];
        }

        if (Commontools::CheckArrayValue($arraydata, "md_contactmail")) {
            $savedata['md_contactmail'] = $arraydata['md_contactmail'];
        }

        if (Commontools::CheckArrayValue($arraydata, "md_first_login")) {
            $savedata['md_first_login'] = $arraydata['md_first_login'];
        } else {
            $savedata['md_first_login'] = date('Y-m-d H:i:s');
        }

        if (Commontools::CheckArrayValue($arraydata, "md_last_login")) {
            $savedata['md_last_login'] = $arraydata['md_last_login'];
        } else {
            $savedata['md_last_login'] = date('Y-m-d H:i:s');
        }

        if (Commontools::CheckArrayValue($arraydata, "md_city")) {
            $savedata['md_city'] = $arraydata['md_city'];
        } 

        if (Commontools::CheckArrayValue($arraydata, "md_country")) {
            $savedata['md_country'] = $arraydata['md_country'];
        } 
        
        if (Commontools::CheckArrayValue($arraydata, "rl_city_code")) {
            $savedata['rl_city_code'] = $arraydata['rl_city_code'];
        } 
        if (Commontools::CheckArrayValue($arraydata, "rl_zip")) {
             $savedata['rl_zip'] = $arraydata['rl_zip'];
         } 

        if (Commontools::CheckArrayValue($arraydata, "isflag")) {
            $savedata['isflag'] = $arraydata['isflag'];
        } else {
            $savedata['isflag'] = '1';
        }

        if (Commontools::CheckArrayValue($arraydata, "create_user")) {
            $savedata['create_user'] = $arraydata['create_user'];
        } else {
            $savedata['create_user'] = 'Pmapi';
        }
        if (Commontools::CheckArrayValue($arraydata, "last_update_user")) {
            $savedata['last_update_user'] = $arraydata['last_update_user'];
        } else {
            $savedata['last_update_user'] = 'webapi';
        }
        if (Commontools::CheckArrayValue($arraydata, "md_clubjoinstatus")) {
            $savedata['md_clubjoinstatus'] = $arraydata['md_clubjoinstatus'];
        }
        if (Commontools::CheckArrayValue($arraydata, "md_picturepath")) {
            $savedata['md_picturepath'] = $arraydata['md_picturepath'];
        }

        if (DB::table('iscarmemberdata')->insert($savedata)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 修改資料
     * @param   $mur_id 
     * @param   $arraydata 
     * @return  Boolean
     */
    public static function UpdateData(array $arraydata) {

        try {
            if (
                    !Commontools::CheckArrayValue($arraydata, "md_id")
            ) {
                return false;
            }

            $savedata['md_id'] = $arraydata['md_id'];
          
            if (Commontools::CheckArrayValue($arraydata, "md_logintype")) {
                $savedata['md_logintype'] = $arraydata['md_logintype'];
            }
            if (Commontools::CheckArrayValue($arraydata, "ssd_onlinestatus")) {
                $savedata['ssd_onlinestatus'] = $arraydata['ssd_onlinestatus'];
            }
            if (Commontools::CheckArrayValue($arraydata, "md_rightstatus")) {
                $savedata['md_rightstatus'] = $arraydata['md_rightstatus'];
            }
            if (Commontools::CheckArrayValue($arraydata, "ssd_accountid")) {
                $savedata['ssd_accountid'] = $arraydata['ssd_accountid'];
            }
            if (Commontools::CheckArrayValue($arraydata, "ssd_accesstoken")) {
                $savedata['ssd_accesstoken'] = $arraydata['ssd_accesstoken'];
            }
            if (Commontools::CheckArrayValue($arraydata, "ssd_longtermtoken")) {
                $savedata['ssd_longtermtoken'] = $arraydata['ssd_longtermtoken'];
            }


            if (Commontools::CheckArrayValue($arraydata, "ssd_accountmail")) {
                $savedata['ssd_accountmail'] = $arraydata['ssd_accountmail'];
            }

            if (Commontools::CheckArrayValue($arraydata, "ssd_accountname")) {
                $savedata['ssd_accountname'] = $arraydata['ssd_accountname'];
            }

            if (Commontools::CheckArrayValue($arraydata, "ssd_fbfirstname")) {
                $savedata['ssd_fbfirstname'] = $arraydata['ssd_fbfirstname'];
            }

            if (Commontools::CheckArrayValue($arraydata, "ssd_fblastname")) {
                $savedata['ssd_fblastname'] = $arraydata['ssd_fblastname'];
            }

            if (Commontools::CheckArrayValue($arraydata, "ssd_fblocallanguage")) {
                $savedata['ssd_fblocallanguage'] = $arraydata['ssd_fblocallanguage'];
            }

            if (Commontools::CheckArrayValue($arraydata, "ssd_fbgender")) {
                $savedata['ssd_fbgender'] = $arraydata['ssd_fbgender'];
            }

            if (Commontools::CheckArrayValue($arraydata, "ssd_birthday")) {
                $savedata['ssd_birthday'] = $arraydata['ssd_birthday'];
            }

            if (Commontools::CheckArrayValue($arraydata, "ssd_timezone")) {
                $savedata['ssd_timezone'] = $arraydata['ssd_timezone'];
            }

            if (Commontools::CheckArrayValue($arraydata, "ssd_picturepath")) {
                $savedata['ssd_picturepath'] = $arraydata['ssd_picturepath'];
            }

            if (Commontools::CheckArrayValue($arraydata, "rl_sn")) {
                $savedata['rl_sn'] = $arraydata['rl_sn'];
            }

            if (Commontools::CheckArrayValue($arraydata, "md_cname")) {
                $savedata['md_cname'] = $arraydata['md_cname'];
            }

            if (Commontools::CheckArrayValue($arraydata, "md_ename")) {
                $savedata['md_ename'] = $arraydata['md_ename'];
            }
            if (Commontools::CheckArrayValue($arraydata, "md_tel")) {
                $savedata['md_tel'] = $arraydata['md_tel'];
            }

            if (Commontools::CheckArrayValue($arraydata, "md_mobile")) {
                $savedata['md_mobile'] = $arraydata['md_mobile'];
            }

            if (Commontools::CheckArrayValue($arraydata, "md_addr")) {
                $savedata['md_addr'] = $arraydata['md_addr'];
            }

            if (Commontools::CheckArrayValue($arraydata, "md_contactmail")) {
                $savedata['md_contactmail'] = $arraydata['md_contactmail'];
            }

            if (Commontools::CheckArrayValue($arraydata, "md_first_login")) {
                $savedata['md_first_login'] = $arraydata['md_first_login'];
            }

            if (Commontools::CheckArrayValue($arraydata, "md_last_login")) {
                $savedata['md_last_login'] = $arraydata['md_last_login'];
            }
            
            if (Commontools::CheckArrayValue($arraydata, "md_clienttype")) {
                $savedata['md_clienttype'] = $arraydata['md_clienttype'];
            }
            if (Commontools::CheckArrayValue($arraydata, "md_clubjoinstatus")) {
                $savedata['md_clubjoinstatus'] = $arraydata['md_clubjoinstatus'];
            }

             if (Commontools::CheckArrayValue($arraydata, "md_city")) {
                $savedata['md_city'] = $arraydata['md_city'];
             } 

            if (Commontools::CheckArrayValue($arraydata, "md_country")) {
               $savedata['md_country'] = $arraydata['md_country'];
             } 
             
             if (Commontools::CheckArrayValue($arraydata, "rl_city_code")) {
               $savedata['rl_city_code'] = $arraydata['rl_city_code'];
              } 
             
               if (Commontools::CheckArrayValue($arraydata, "rl_zip")) {
                $savedata['rl_zip'] = $arraydata['rl_zip'];
               } 

            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
            }

            $savedata['last_update_user'] = 'webapi';
            $savedata['last_update_date'] = date('Y-m-d H:i:s');
            DB::table('iscarmemberdata')
                    ->where('md_id', '=', $savedata['md_id'])
                    ->update($savedata);
          
            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 更新 用戶端運行類別
     * @param type $md_id
     * @param type $md_clienttype
     * @return boolean
     */
    public static function UpdateData_ClientType($md_id, $md_clienttype) {
        try {
            if ($md_id == null || strlen($md_id) == 0 || $md_clienttype == null || strlen($md_clienttype) == 0) {
                return null;
            }

            DB::table('iscarmemberdata')
                    ->where('md_id', '=', $md_id)
                    ->update(array('md_clienttype' => $md_clienttype, 'last_update_user' => 'Pmapi' , 'last_update_date' => date('Y-m-d H:i:s')));
            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 
     * @param type $md_id
     * @return boolean
     */
    public static function CreateOriginalMember(&$md_id) {

        try {
            //取得新的代碼
            $md_id = Commontools::NewGUIDWithoutDash();
            $qqq = $md_id;
            $savedata['md_id'] = $md_id;
            $savedata['md_logintype'] = '2';
            $savedata['md_cname'] = mb_substr($qqq, 0, 8, 'utf8') . '@iscar';

            $savedata['create_user'] = 'webapi';
            $savedata['last_update_user'] = 'webapi';

            DB::table('iscarmemberdata')->insert($savedata);
            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

     /**
     * 
     * @param type $md_id
     * @return boolean
     */
    public static function CreateMemberResgister(&$md_id) {

        try {
            //取得新的代碼
            $md_id = Commontools::NewGUIDWithoutDash();
            $qqq = $md_id;
            $savedata['md_id'] = $md_id;
            $savedata['md_logintype'] = '2';
            $savedata['md_cname'] = mb_substr($qqq, 0, 8, 'utf8') . '@iscar';

            $savedata['create_user'] = 'webapi';
            $savedata['last_update_user'] = 'webapi';

            DB::table('iscarmemberdata')->insert($savedata);
            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }


    /**
     * 取得資料，依「MD_ID」取得
     * @param type $md_id
     * @return type
     */
    public static function GetData($md_id) {
        if ($md_id == null || strlen($md_id) == 0) {
            return null;
        }

        $results = IsCarMemberData::where('isflag', '1')
                ->where('md_id', $md_id)
                ->get()
                ->toArray();
        return $results;
    }

    /**
     * 依「$accountid」取得資料
     * @param type $accountid
     * @return type
     */
    public static function GetDataByAccountID($accountid) {
        if ($accountid == null || strlen($accountid) == 0) {
            return null;
        }

        $results = IsCarMemberData::where('isflag', '1')
                ->where('ssd_accountid', $accountid)
                ->get()
                ->toArray();

        return $results;
    }

    /**
     * 依「$mur_id」取得資料
     * @param type $mur_id
     * @return type
     */
    public static function GetDataByMUR_ID($mur_id) {
        if (is_null($mur_id) || strlen($mur_id) == 0) {
            return null;
        }

        $results = IsCarMemberData::leftJoin('iscarmembermobilelink', 'iscarmemberdata.md_id', '=', 'iscarmembermobilelink.md_id')
                ->where('iscarmembermobilelink.isflag', '=', '1')
                ->where('iscarmemberdata.isflag', '1')
                ->where('iscarmembermobilelink.mur_id', '=', $mur_id)
                ->where('iscarmemberdata.md_logintype', '2')
                ->select('iscarmemberdata.md_id'
                        , 'iscarmemberdata.md_logintype'
                        , 'iscarmemberdata.ssd_onlinestatus'
                        , 'iscarmemberdata.md_rightstatus'
                        , 'iscarmemberdata.ssd_accountid'
                        , 'iscarmemberdata.ssd_accountmail'
                        , 'iscarmemberdata.ssd_accountname'
                        , 'iscarmemberdata.ssd_fbfirstname'
                        , 'iscarmemberdata.ssd_fblastname'
                        , 'iscarmemberdata.ssd_fblocallanguage'
                        , 'iscarmemberdata.ssd_fbgender'
                        , 'iscarmemberdata.ssd_birthday'
                        , 'iscarmemberdata.ssd_timezone'
                        , 'iscarmemberdata.ssd_picturepath'
                        , 'iscarmemberdata.ssd_accesstoken'
                        , 'iscarmemberdata.ssd_longtermtoken'
                        , 'iscarmemberdata.rl_sn'
                        , 'iscarmemberdata.md_cname'
                        , 'iscarmemberdata.md_ename'
                        , 'iscarmemberdata.md_tel'
                        , 'iscarmemberdata.md_mobile'
                        , 'iscarmemberdata.md_addr'
                        , 'iscarmemberdata.md_contactmail'
                        , 'iscarmemberdata.md_first_login'
                        , 'iscarmemberdata.md_last_login'
                        , 'iscarmemberdata.isflag'
                        , 'iscarmemberdata.create_user'
                        , 'iscarmemberdata.create_date'
                        , 'iscarmemberdata.last_update_user'
                        , 'iscarmemberdata.last_update_date')
                ->get()
                ->toArray();

        return $results;
    }
                    
    public static function GetData_ByMDID($md_id) {
      try {
           if (is_null($md_id) || strlen($md_id) == 0) {
            return null;
          }
            $query  = IsCarMemberData::where('iscarmemberdata.isflag','=', '1')
                ->where('iscarmemberdata.md_id', '=', $md_id)
                ->leftJoin('iscarserviceaccesstoken','iscarmemberdata.md_id','=','iscarserviceaccesstoken.md_id')
                ->leftJoin('iscarmobileunitrec','iscarserviceaccesstoken.mur_id','=','iscarmobileunitrec.mur_id')
                ->orderBy('iscarserviceaccesstoken.last_update_date', 'desc');
               
            $results = $query->select('iscarmobileunitrec.mur_gcmid'
                                  ,'iscarmobileunitrec.mur_systemtype'
                                 )->get()->toArray();
        return $results;
      
      } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

     public static function QueryMemberClubJoinSataus($md_id) {
        try {
          $query = IsCarMemberData::where('iscarmemberdata.md_id','=',$md_id)
                            ->leftJoin('icr_clubmemberrecord', function($leftJoin)
                              {
                                 $leftJoin->on('icr_clubmemberrecord.md_id','=','iscarmemberdata.md_id')
                                           ->where('icr_clubmemberrecord.isflag', '=','1')
                                           ->where('icr_clubmemberrecord.cmr_joinstatus','=','1');
                              })
                              ->leftJoin('icr_carclubdata', function($leftJoin)
                              {
                                 $leftJoin->on('icr_carclubdata.ccd_id','=','icr_clubmemberrecord.ccd_id')
                                           ->where('icr_carclubdata.isflag', '=','1')
                                           ->where('icr_carclubdata.ccd_dismiss_tag','=','0');
                              })
                              ->leftJoin('icr_memberclublevel_set as Club_mcls', function($leftJoin)
                              {
                                 $leftJoin->on('Club_mcls.mcls_serno','=','icr_carclubdata.mcls_serno')
                                           ->where('Club_mcls.isflag', '=','1');
                              })
                              ->leftJoin('icr_memberclublevel_set as mem_mcls', function($leftJoin)
                              {
                                 $leftJoin->on('mem_mcls.mcls_serno','=', 'iscarmemberdata.mcls_serno')
                                           ->where('mem_mcls.isflag', '=','1');
                              });
          $result = $query->select( 'iscarmemberdata.md_id'
                                   ,'iscarmemberdata.md_logintype'
                                   ,'iscarmemberdata.mcls_serno'
                                   ,'iscarmemberdata.md_clubjoinstatus'
                                   ,'icr_clubmemberrecord.cmr_id'
                                   ,'icr_clubmemberrecord.ccd_id'
                                   ,'icr_clubmemberrecord.cmr_joinstatus'
                                   ,'icr_clubmemberrecord.cmr_membergrade'
                                   ,'icr_carclubdata.ccd_id'
                                   ,'icr_carclubdata.ccd_clubname'
                                   ,'icr_carclubdata.ccd_clubbadge'
                                   ,'icr_carclubdata.ccd_public_tag'
                                   ,'icr_carclubdata.mcls_serno'
                                   ,'icr_carclubdata.ccd_dismiss_tag'
                                   ,'mem_mcls.mcls_levelweight'
                                   ,'mem_mcls.mcls_nextlevelexp'
                                   ,'Club_mcls.mcls_gradename as Club_mcls_gradename'
                                   ,'Club_mcls.mcls_gradeicon as Club_mcls_gradeicon'
                                   ,'mem_mcls.mcls_gradename as mem_mcls_gradename'
                                   ,'mem_mcls.mcls_gradeicon as mem_mcls_gradeicon'
                                   ,'Club_mcls.mcls_functioncontent'
                                  )
                                  ->get()->toArray();
          return $result;  
        } catch(\Exception $e) {
          ErrorLog::InsertData($e);
          return null;
        }
     }
     
     public static function GetPushMd_id($fbgender, $agemin, $agemax, $citysArray, $sd_id) {
         try {
           $query = IsCarMemberData::where('iscarmemberdata.isflag','1');
                         /* ->leftJoin('iscaruserbookmark', function($leftJoin) use ($sd_id)
                              {
                                 $leftJoin->on('iscarmemberdata.md_id','=','iscaruserbookmark.md_id')
                                           ->where('iscaruserbookmark.ubm_objectid', '!=', $sd_id)
                                           ->where('iscaruserbookmark.isflag','=','1');
                              });*/
                           // ->whereIn("iscarmemberdata.md_logintype", array(0,1));
           if(!is_null($fbgender) && mb_strlen($fbgender) != 0 ) {
               $query->where('iscarmemberdata.ssd_fbgender','=',"$fbgender");
           }
           if (!is_null($agemin) && !is_null($agemax) && mb_strlen($agemin) != 0 && mb_strlen($agemax) != 0) {
               $query->whereRaw("year(from_days(DATEDIFF(now(),iscarmemberdata.ssd_birthday))) between $agemin and $agemax");
           }
           if (!is_null($citysArray) && mb_strlen($citysArray) != 0 ) {
               $arrayCitys =  explode(",",$citysArray);
               //$query->whereRaw("iscarmemberdata.rl_sn in (select rl.rl_serno from iscarregionlist as rl where rl.rl_city in ("."'".implode("','",$arrayCitys)."'"."))");
               $query->whereIn("iscarmemberdata.rl_city_code",$arrayCitys);
           }
           /*$result = $query->select('iscarmemberdata.md_id')->distinct()->toSql();
           \App\Models\ErrorLog::InsertLog($result);
           \App\Models\ErrorLog::InsertLog($citysArray);*/
           $result = $query->select('iscarmemberdata.md_id')->distinct()->get()->toArray();
           return $result;

         } catch(\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
           return null;
         }
     }

}