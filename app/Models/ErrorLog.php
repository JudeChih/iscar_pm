<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class ErrorLog extends Model {

    //
    public $table = 'errorlog';
    public $primaryKey = 'log_id';
    public $timestamps = false;

    //新增資料
    public static function InsertData($ex) {

        try {
            $savedata['log_code'] = $ex->getCode();
            $savedata['log_message'] = $ex->getMessage();
            $savedata['log_previous'] = $ex->getPrevious();
            $savedata['log_file'] = $ex->getFile();
            $savedata['log_line'] = $ex->getLine();
            //$savedata['log_trace'] = $ex->getTrace();
            $savedata['log_traceasstring'] = $ex->getTraceAsString();

            DB::table('errorlog')->insert($savedata);

            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    public static function InsertLog($logmessage) {

        try {
            $savedata['log_code'] = '';
            $savedata['log_message'] = $logmessage;
            $savedata['log_previous'] = '';
            $savedata['log_file'] = '';
            $savedata['log_line'] = '';
            //$savedata['log_trace'] = $e->getTrace();
            $savedata['log_traceasstring'] = '';

            DB::table('errorlog')->insert($savedata);

            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

}
