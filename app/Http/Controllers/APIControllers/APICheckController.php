<?php

namespace App\Http\Controllers\APIControllers;
use  App\Library\Commontools;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class APICheckController extends Controller {

    function APICheck() {
        $functionName = 'apicheck';
        $inputString = Input::All();
       /* $inputData =  Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }*/
        $resultString;
        $resultData = null;
        $messageCode = null;

        try {
            if (!empty($_SERVER["HTTP_CLIENT_IP"])){
                  $ip = $_SERVER["HTTP_CLIENT_IP"];
            } else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
                  $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else {
                  $ip = $_SERVER["REMOTE_ADDR"];
            }
              //檢查身份模組驗證
           /* $memService = new \App\Services\MemberService;
            if ( !$memService->checkServiceAccessToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJpc0NhciBNZW1iZXIiLCJleHAiOjE0OTg4OTgxOTcsImlhdCI6MTQ5NjMwNjE5NywiZGF0YSI6eyJtZF9pZCI6ImVjNzc4N2M4YzE2YTQ2NTk5NTcwN2UxMzBjMDA0OGYxIn19.yBFc7KquHHjDP1OjaK-cUBUwY987jvhXn0n1F11FWxTSatHZhs3-Ja2mn3bvED5sG8G1fmk0RLmDUrBHF5oUSQ', $md_id, $messageCode)) {
              //模組身份驗證失敗
              throw new \Exception($messageCode);
            }*/
            $datenow = new \Datetime();
            $stringdate =  $datenow-> format('m-d H:i');
            $resultData = array('ip' => $ip, 'date' => $stringdate);//,'md_id' => $md_id);
            $messageCode = '000000000';
        } catch (\Exception $e) {
            if ($messageCode == null) {
                \App\Models\ErrorLog::InsertData($e);
                $messageCode = '999999999';
            }
        }
        //回傳值
        $resultArray = \App\Library\Commontools::ResultProcess($messageCode, $resultData);
        \App\Library\Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray ];
        return $result;
    }
}
