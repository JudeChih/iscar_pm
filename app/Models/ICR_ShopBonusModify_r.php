<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class ICR_ShopBonusModify_r extends Model {

    //
    public $table = 'icr_shopbonusmodify_r';
    public $primaryKey = 'sbmr_serno';
    public $timestamps = false;
    public $incrementing = true;

    /*     * 新增資料
     *
     *
     * @param   $arraydata
     * @return 	Boolean
     */

    public static function InsertData($arraydata, &$sbmr_serno) {

        if (
                !Commontools::CheckArrayValue($arraydata, 'sbmr_date') || !Commontools::CheckArrayValue($arraydata, 'md_id') || !Commontools::CheckArrayValue($arraydata, 'sbmr_point_before') || !Commontools::CheckArrayValue($arraydata, 'sbmr_point') || !Commontools::CheckArrayValue($arraydata, 'sbmr_point_after')
        ) {
            return false;
        }

        $savedata['sbmr_date'] = $arraydata['sbmr_date'];
        $savedata['md_id'] = $arraydata['md_id'];
        $savedata['sbmr_point_before'] = $arraydata['sbmr_point_before'];
        $savedata['sbmr_point'] = $arraydata['sbmr_point'];
        $savedata['sbmr_point_after'] = $arraydata['sbmr_point_after'];

        if (Commontools::CheckArrayValue($arraydata, 'sbmr_relation_id')) {
            $savedata['sbmr_relation_id'] = $arraydata["sbmr_relation_id"];
        }
        if (Commontools::CheckArrayValue($arraydata, 'sbmr_effective_date')) {
            $savedata['sbmr_effective_date'] = $arraydata["sbmr_effective_date"];
        }


        if (Commontools::CheckArrayValue($arraydata, 'sbmr_type')) {
            $savedata['sbmr_type'] = $arraydata["sbmr_type"];
        } else {
            $savedata['sbmr_type'] = '0';
        }
        if (Commontools::CheckArrayValue($arraydata, 'sbmr_modify')) {
            $savedata['sbmr_modify'] = $arraydata["sbmr_modify"];
        } else {
            $savedata['sbmr_modify'] = '1';
        }
        if (Commontools::CheckArrayValue($arraydata, 'sbmr_is_effective')) {
            $savedata['sbmr_is_effective'] = $arraydata["sbmr_is_effective"];
        } else {
            $savedata['sbmr_is_effective'] = '0';
        }



        if (Commontools::CheckArrayValue($arraydata, "isflag")) {
            $savedata['isflag'] = $arraydata['isflag'];
        } else {
            $savedata['isflag'] = '1';
        }

        if (Commontools::CheckArrayValue($arraydata, "create_user")) {
            $savedata['create_user'] = $arraydata['create_user'];
        } else {
            $savedata['create_user'] = 'webapi';
        }
        if (Commontools::CheckArrayValue($arraydata, "last_update_user")) {
            $savedata['last_update_user'] = $arraydata['last_update_user'];
        } else {
            $savedata['last_update_user'] = 'webapi';
        }


        //新增資料並回傳「自動遞增KEY值」
        $result = DB::table('icr_shopbonusmodify_r')->insertGetId($savedata);

        if (!is_null($result) && strlen($result) != 0) {
            $sbmr_serno = $result;

            return true;
        } else {
            return false;
        }
    }

    /**
     * 更新關聯代碼
     * @param type $pmr_serno
     * @param type $relationid
     * @return boolean
     */
    public static function UpdateData_RelationID($sbmr_serno, $relationid) {

        try {

            ICR_ShopBonusModify_r::where('isflag', '=', '1')
                    ->where('sbmr_serno', '=', $sbmr_serno)
                    ->update(array('sbmr_relation_id' => $relationid));

            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

}
