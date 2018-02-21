<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class ICR_ShopQuestionnaire_r extends Model {

//
    public $table = 'icr_shopquestionnaire_r';
    public $primaryKey = 'sqnr_id';
    public $timestamps = false;
    public $incrementing = false;

    /** ██████████▍CREATE 建立資料 */
    public static function InsertData($modifydata, &$primarykey) {
        try {
            $savedata['sqnr_id'] = \App\library\Commontools::NewGUIDWithoutDash();

            if (Commontools::CheckArrayValue($modifydata, 'sd_id')) {
                $savedata['sd_id'] = $modifydata['sd_id'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'md_id')) {
                $savedata['md_id'] = $modifydata['md_id'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'sqna_id')) {
                $savedata['sqna_id'] = $modifydata['sqna_id'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'sqnr_responsemessage')) {
                $savedata['sqnr_responsemessage'] = $modifydata['sqnr_responsemessage'];
            }

            $savedata['isflag'] = '1';
            $savedata['create_user'] = 'webapi';
            $savedata['last_update_user'] = 'webapi';

            $result = DB::table('icr_shopquestionnaire_r')->insert($savedata);

            if (is_null($result) || strlen($result) == 0) {
                $scg_id = null;
                return false;
            }
            $primarykey = $savedata['sqnr_id'];
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            $primarykey = null;
            return false;
        }
    }

    /** ██████████▍READ 讀取資料 */

    /**
     * 依「$ssqq_id」取得資料
     * @param type $ssqq_id
     * @return type
     */
    public static function GetData($sqna_id) {
        try {
            if ($sqna_id == null || strlen($sqna_id) == 0) {
                return null;
            }

            $results = ICR_ShopQuestionnaire_a::where('isflag', '=', '1')
                            ->leftJoin('icr_shopserviceque_r', 'icr_shopserviceque_r.sqna_id', '=', 'icr_shopserviceque_a.sqna_id')
                            ->where('sqna_id', '=', $sqna_id)
                            ->get()->toArray();
            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /** ██████████▍UPDATE 更新資料 */
    public static function UpdateData($modifydata) {
        try {

            if (!Commontools::CheckArrayValue($modifydata, "sqnr_id")) {
                return false;
            }
            $savedata['sqnr_id'] = $modifydata['sqnr_id'];

            if (Commontools::CheckArrayValue($modifydata, 'sd_id')) {
                $savedata['sd_id'] = $modifydata['sd_id'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'md_id')) {
                $savedata['md_id'] = $modifydata['md_id'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'sqna_id')) {
                $savedata['sqna_id'] = $modifydata['sqna_id'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'sqnr_responsemessage')) {
                $savedata['sqnr_responsemessage'] = $modifydata['sqnr_responsemessage'];
            }

            if (Commontools::CheckArrayValue($modifydata, 'isflag')) {
                $savedata['isflag'] = $modifydata['isflag'];
            }

            $savedata['create_user'] = 'webapi';
            $savedata['last_update_user'] = 'webapi';

            $result = DB::table('icr_shopquestionnaire_r')
                    ->where('icr_shopquestionnaire_r.sqnr_id', $modifydata['sqnr_id'])
                    ->update($savedata);
           // \App\Models\ErrorLog::InsertLog('ROW107');
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /** ██████████▍DELETE 刪除資料 */
    /** ██████████▍CHECK 檢查資料 */
    /** ██████████▍QUERY 查詢資料 */
}
