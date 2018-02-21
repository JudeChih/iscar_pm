<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家基本資料
 */
class ICR_ServiceAgentRecord extends Model {

    //
    public $table = 'icr_serviceagentrecord';
    public $primaryKey = 'sar_id';
    public $timestamps = false;
    public $incrementing = false;


    /**
     * 依「$sd_id」取得資料
     * @param type $sar_id
     * @return type
     */
    public static function GetData($sar_id) {
        try {

            if ($sar_id == null || strlen($sar_id) == 0) {
                return null;
            }

            $results = ICR_ServiceAgentRecord::where('isflag', '=', '1')
                            ->where('sar_id', '=', $sar_id)
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 依「$sar_account」取得資料
     * @param type $sar_account
     * @return type
     */
    public static function GetData_BySarAccount($sar_account) {
        try {
            if ($sar_account == null || strlen($sar_account) == 0) {
                return null;
            }

            $results = ICR_ServiceAgentRecord::where('isflag', '=', '1')
                            ->where('sar_account', '=', $sar_account)
                            ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 更新資料
     * @param type $modifydata
     * @return boolean
     */
    public static function UpdateData($modifydata) {
        try {
            if (!Commontools::CheckArrayValue($modifydata, "sar_id")) {
                return false;
            }
            $savedata['sar_id'] = $modifydata['sar_id'];

            if (Commontools::CheckArrayValue($modifydata, "sar_activecode")) {
                $savedata['sar_activecode'] = $modifydata['sar_activecode'];
            }
            if (Commontools::CheckArrayValue($modifydata, "sar_acountname")) {
                $savedata['sar_acountname'] = $modifydata['sar_acountname'];
            }
            if (Commontools::CheckArrayValue($modifydata, "sar_account")) {
                $savedata['sar_account'] = $modifydata['sar_account'];
            }
            if (Commontools::CheckArrayValue($modifydata, "sar_password")) {
                $savedata['sar_password'] = $modifydata['sar_password'];
            }
            if (Commontools::CheckArrayValue($modifydata, "sar_activestatus")) {
                $savedata['sar_activestatus'] = $modifydata['sar_activestatus'];
            }
            if (Commontools::CheckArrayValue($modifydata, "sar_creater")) {
                $savedata['sar_creater'] = $modifydata['sar_creater'];
            }
            if (Commontools::CheckArrayValue($modifydata, "sar_approval")) {
                $savedata['sar_approval'] = $modifydata['sar_approval'];
            }
            if (Commontools::CheckArrayValue($modifydata, "sar_approvaltime")) {
                $savedata['sar_approvaltime'] = $modifydata['sar_approvaltime'];
            }
            if (Commontools::CheckArrayValue($modifydata, "sar_loginerror")) {
                $savedata['sar_loginerror'] = $modifydata['sar_loginerror'];
            }

            if (Commontools::CheckArrayValue($modifydata, "isflag")) {
                $savedata['isflag'] = $modifydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
            }

            $savedata['last_update_user'] = 'webapi';

            DB::table('icr_serviceagentrecord')
                    ->where('sar_id', '=', $savedata['sar_id'])
                    ->update($savedata);

            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 取得帳號可再登入次數
     * @param type $sar_id
     * @return int
     */
    public static function Query_LoginCountDown($sar_id) {
        try {
            $querydata = ICR_ServiceAgentRecord::GetData($sar_id);
            if (count($querydata) == 0) {
                return 0;
            }

            return 5 - $querydata[0]['sar_loginerror'];
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return 0;
        }
    }

}
