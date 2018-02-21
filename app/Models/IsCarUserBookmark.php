<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class IsCarUserBookmark extends Model {

    //
    public $table = 'iscaruserbookmark';
    public $primaryKey = 'ubm_serno';
    public $timestamps = false;

    /*     * 新增資料
     * 
     *
     * @param   $arraydata 
     * @return 	Boolean
     */

    public static function InsertData($arraydata) {

        try {
            if (
                    !Commontools::CheckArrayValue($arraydata, 'md_id') || !Commontools::CheckArrayValue($arraydata, 'ubm_objecttype') || !Commontools::CheckArrayValue($arraydata, 'ubm_objectid')
            ) {
                return false;
            }


            $savedata['md_id'] = $arraydata['md_id'];
            $savedata['ubm_objecttype'] = $arraydata['ubm_objecttype'];
            $savedata['ubm_objectid'] = $arraydata['ubm_objectid'];

            if (Commontools::CheckArrayValue($arraydata, 'isflag')) {
                $savedata['isflag'] = $arraydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
            }

            if (Commontools::CheckArrayValue($arraydata, 'create_user')) {
                $savedata['create_user'] = $arraydata['create_user'];
            } else {
                $savedata['create_user'] = 'webapi';
            }
            if (Commontools::CheckArrayValue($arraydata, 'last_update_user')) {
                $savedata['last_update_user'] = $arraydata['last_update_user'];
            } else {
                $savedata['last_update_user'] = 'webapi';
            }

            DB::table('iscaruserbookmark')->insert($savedata);
            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /*     * 修改資料
     * 
     *
     * @param   $mur_id 
     * @param   $arraydata 
     * @return 	Boolean
     */

    public static function UpdateData(array $arraydata) {
        try {
            if (
                    !Commontools::CheckArrayValue($arraydata, 'ubm_serno') || !Commontools::CheckArrayValue($arraydata, 'md_id') || !Commontools::CheckArrayValue($arraydata, 'ubm_objecttype') || !Commontools::CheckArrayValue($arraydata, 'ubm_objectid')
            ) {
                return false;
            }
            $savedata['ubm_serno'] = $arraydata['ubm_serno'];
            $savedata['md_id'] = $arraydata['md_id'];
            $savedata['ubm_objecttype'] = $arraydata['ubm_objecttype'];
            $savedata['ubm_objectid'] = $arraydata['ubm_objectid'];

            if (Commontools::CheckArrayValue($arraydata, 'isflag')) {
                $savedata['isflag'] = $arraydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
            }

            $savedata['last_update_user'] = 'webapi';

            DB::table('iscaruserbookmark')
                    ->where('ubm_serno', $savedata['ubm_serno'])
                    ->update($savedata);

            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    public static function UpdateDataToDelete($md_id, $ubm_objecttype) {

        try {

            $savedata['isflag'] = '0';
            $savedata['last_update_user'] = 'webapi';


            DB::table('iscaruserbookmark')
                    ->where('md_id', $md_id)
                    ->where('ubm_objecttype', $ubm_objecttype)
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /*     * 刪除資料
     * 
     *
     * @param   $mur_id 
     * @return 	Boolean
     */

    public static function DeleteData($ubm_serno) {
        
    }

    /*     * 取得資料，依「MD_ID」取得
     */

    public static function GetData($ubm_serno) {
        if ($ubm_serno == null || strlen($ubm_serno) == 0) {
            return null;
        }

        $results = IsCarUserBookmark::where('isflag', '1')
                ->where('ubm_serno', $ubm_serno)
                ->get()
                ->toArray();
        return $results;
    }

    /*     * 取得資料，依「MD_ID」取得
     */

    public static function GetDataByMD_ID($md_id) {
        if ($md_id == null || strlen($md_id) == 0) {
            return null;
        }

        $results = IsCarUserBookmark::where('isflag', '1')
                ->where('md_id', $md_id)
                ->get()
                ->toArray();

        return $results;
    }

    public static function GetDataByMD_ID_LastDate($md_id, $last_update_date) {
        if ($md_id == null || strlen($md_id) == 0 || $last_update_date == null || strlen($last_update_date) == 0) {
            return null;
        }

        $results = IsCarUserBookmark::where('md_id', $md_id)
                ->where('last_update_date', '>=', $last_update_date)
                ->get()
                ->toArray();

        return $results;
    }

    public static function GetDataByMD_ID_Object($md_id, $ubm_objecttype, $ubm_objectid) {
        if (
                $md_id == null || strlen($md_id) == 0 || $ubm_objecttype == null || strlen($ubm_objecttype) == 0 || $ubm_objectid == null || strlen($ubm_objectid) == 0
        ) {
            return null;
        }

        $results = IsCarUserBookmark::where('md_id', $md_id)
                ->where('ubm_objecttype', $ubm_objecttype)
                ->where('ubm_objectid', $ubm_objectid)
                ->get()
                ->toArray();

        return $results;
    }
    
    public static function GetMemberBySD_ID($sd_id) {
       if (is_null($sd_id)  || strlen($sd_id) == 0 ) {
            return null;
       }
       try {
         $results = IsCarUserBookmark::where('ubm_objecttype', '2')
                ->where('ubm_objectid', $sd_id)
                ->where('iscaruserbookmark.isflag','1')
                ->leftJoin('iscarmemberdata', function($leftJoin)
                          {
                              $leftJoin->on('iscaruserbookmark.md_id','=','iscarmemberdata.md_id')
                                       ->where('iscarmemberdata.isflag', '=','1');   
                          })
                ->select( 'iscaruserbookmark.md_id'
                        , 'iscarmemberdata.md_cname'
                        , 'iscarmemberdata.ssd_picturepath' 
                        )
                ->get()
                ->toArray();
                
          return $results;
       } catch(\Exception $e) {
           \App\models\ErrorLog::InsertData($e);
           return null;
       }       
    }
    
    
     public static function GetDataByMD_ID_SD_ID($md_id,$sd_id) {
       if (is_null($sd_id)  || strlen($sd_id) == 0 ) {
            return null;
       }
       try {
         $results = IsCarUserBookmark::where('ubm_objecttype', '2')
                ->where('ubm_objectid', $sd_id)
                ->where('iscaruserbookmark.isflag','1')
               ->where('iscaruserbookmark.md_id',$md_id)
                ->select( 'iscaruserbookmark.md_id'
                        )
                ->get()
                ->toArray();
                
          return $results;
       } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return null;
       }       
    }

}
