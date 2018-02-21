<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class ICR_ShopQuestionnaire_a extends Model {

//
    public $table = 'icr_shopquestionnaire_a';
    public $primaryKey = 'sqna_id';
    public $timestamps = false;
    public $incrementing = false;

    /** ██████████▍CREATE 建立資料 */
    public static function InsertData($modifydata, &$primarykey) {
        try {

            $savedata['sqna_id'] = \App\library\Commontools::NewGUIDWithoutDash();

            if (Commontools::CheckArrayValue($modifydata, 'md_id')) {
                $savedata['md_id'] = $modifydata['md_id'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'sd_id')) {
                $savedata['sd_id'] = $modifydata['sd_id'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'event_type')) {
                $savedata['event_type'] = $modifydata['event_type'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'event_id')) {
                $savedata['event_id'] = $modifydata['event_id'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'sqna_answercontent')) {
                $savedata['sqna_answercontent'] = $modifydata['sqna_answercontent'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'ci_serno')) {
                $savedata['ci_serno'] = $modifydata['ci_serno'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'sqna_message')) {
                $savedata['sqna_message'] = $modifydata['sqna_message'];
            }
            if (Commontools::CheckArrayValue($modifydata, 'sqna_averagescore')) {
                $savedata['sqna_averagescore'] = $modifydata['sqna_averagescore'];
            }

            $savedata['isflag'] = '1';
            $savedata['create_user'] = 'webapi';
            $savedata['last_update_user'] = 'webapi';

            $result = DB::table('icr_shopquestionnaire_a')->insert($savedata);

            if (is_null($result) || strlen($result) == 0) {
                $scg_id = null;
                return false;
            }
            $primarykey = $savedata['sqna_id'];
            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            $scg_id = null;
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

            $results = ICR_ShopQuestionnaire_a::where('icr_shopquestionnaire_a.isflag', '=', '1')
                            ->leftjoin('icr_shopquestionnaire_r', function($query) {
                                       $query->on('icr_shopquestionnaire_r.sqna_id', '=', 'icr_shopquestionnaire_a.sqna_id')->where('icr_shopquestionnaire_r.isflag', '=', '1');
                             })
                            ->where('icr_shopquestionnaire_a.sqna_id', '=', $sqna_id)
                            ->get()->toArray();
            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    
     /**
     * 取得該「$sd_id」店家的問卷資料
     * @param type $sd_id
     * @return type
     */
    public static function Query_QuestionnaireData($sd_id) {
        try {
            if ($sd_id == null || strlen($sd_id) == 0) {
                return null;
            }

            $query = ICR_ShopQuestionnaire_a::where('icr_shopquestionnaire_a.isflag', '=', '1')
                    ->leftjoin('icr_shopquestionnaire_r', function($query) {
                        $query->on('icr_shopquestionnaire_r.sqna_id', '=', 'icr_shopquestionnaire_a.sqna_id')->where('icr_shopquestionnaire_r.isflag', '=', '1');
                    })
                    ->leftjoin('iscarmemberdata', function($query) {
                        $query->on('icr_shopquestionnaire_a.md_id', '=', 'iscarmemberdata.md_id')->where('iscarmemberdata.isflag', '=', '1');
                    })
//                    ->leftjoin('icr_shopdata', function($query) {
//                        $query->on('icr_shopquestionnaire_r.sd_id', '=', 'icr_shopdata.sd_id')->where('icr_shopdata.isflag', '=', '1');
//                    })
                    ->where('icr_shopquestionnaire_a.sd_id', '=', $sd_id);

            $results = $query->select(
                            'icr_shopquestionnaire_a.sqna_id'
                            , 'icr_shopquestionnaire_a.sqna_message'
                            , 'icr_shopquestionnaire_a.ci_serno'
                            , 'icr_shopquestionnaire_a.event_id'
                            , 'icr_shopquestionnaire_a.event_type'
                            , 'icr_shopquestionnaire_a.last_update_date as sqna_last_update'
                            , 'icr_shopquestionnaire_r.sqnr_id'
                            , 'icr_shopquestionnaire_r.sqnr_responsemessage'
                            , 'icr_shopquestionnaire_r.last_update_date as sqnr_last_update'
                            , 'iscarmemberdata.md_cname'
                            , 'iscarmemberdata.ssd_picturepath'
                    )->distinct()->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /** ██████████▍UPDATE 更新資料 */
    /** ██████████▍DELETE 刪除資料 */
    /** ██████████▍CHECK 檢查資料 */
    /** ██████████▍QUERY 查詢資料 */
}
