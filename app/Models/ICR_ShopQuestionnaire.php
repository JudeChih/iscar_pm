<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class ICR_ShopQuestionnaire extends Model {

//
    public $table = 'icr_shopquestionnaire';
    public $primaryKey = 'sqn_serno';
    public $timestamps = false;
    public $incrementing = true;

    /** ██████████▍CREATE 建立資料 */
    /** ██████████▍READ 讀取資料 */

    /**
     * 依「$sqn_serno」取得資料
     * @param type $sqn_serno
     * @return type
     */
    public static function GetData($sqn_serno) {
        try {
            if ($sqn_serno == null || strlen($sqn_serno) == 0) {
                return null;
            }

            $results = ICR_ShopQuestionnaire::where('icr_shopquestionnaire.isflag', '=', '1')
                            ->where('icr_shopquestionnaire.sqn_serno', '=', $sqn_serno)
                            ->get()->toArray();

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

    /**
     * 取得最新的10筆且〔有效﹙sqn_effective = 1﹚〕的問巻項目
     * @param type $scm_id
     * @return int
     */
    public static function QueryTop10EffectiveItem() {
        try {
            $results = ICR_ShopQuestionnaire::where('isflag', '=', '1')
                            ->where('sqn_effective', '=', '1')
                            ->orderBy('create_date', 'desc')
                            ->take(10)
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return 0;
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
                        $query->on('icr_shopquestionnaire_r.md_id', '=', 'iscarmemberdata.md_id')->where('iscarmemberdata.isflag', '=', '1');
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

}
