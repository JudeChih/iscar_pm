<?php

namespace App\Http\Controllers\APIControllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Library\Commontools;
use App\Models\IsCarMobileUnitRec;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AccountController extends Controller {

    //userbookmarkrecorver	檢查傳入之會員書籤更新日期，同步會員app端訊息記錄
    //function userbookmarkrecorver() {
    //      $userBMR = new \App\Http\Controllers\Account\UserBookmarkRecorver;
    //      $userBMR ->userbookmarkrecorver();
    //}
     //userbookmarkrecorver   檢查傳入之會員書籤更新日期，同步會員app端訊息記錄
    function userbookmarkrecorver() {
        $functionName = 'userbookmarkrecorver';
        $inputString = Input::All();
        $inputData = \App\Library\Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        $userBMR = new \App\Http\Controllers\APIControllers\Account\UserBookmarkRecorver;
        try {
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!$userBMR->CheckInput($inputData)) {
                //輸入值有問題
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
             //檢查身份模組驗證
            $memService = new \App\Services\MemberService;
            if ( !$memService->checkModuleAccount($inputData['modacc'], $inputData['modvrf'], $messageCode)) {
              //模組身份驗證失敗
              //$messageCode = '999999961';
              throw new \Exception($messageCode);
            }
            if (!$memService->checkServiceAccessToken($inputData['sat'], $md_id, $messageCode)) {
               //$messageCode = '999999962';
               throw new \Exception($messageCode);
            }
            $lastdate = null;
            $bookmarkarray = $userBMR->GetData_UserBookMarkData($md_id, $inputData['lastupdate'], $lastdate);
            $resultData = array('lastupdate' => $lastdate, 'userbookmark' => $bookmarkarray);

            $messageCode = '000000000';
        } catch (\Exception $e) {
            if (!isset($messageCode) || is_null($messageCode)) {
                \App\Models\ErrorLog::InsertData($e);
                $messageCode = '999999999';
            }
        }
        //回傳值
        $resultArray = \App\Library\Commontools::ResultProcess($messageCode, $resultData);
        \App\Library\Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray];
        return $result;
    }

    //userbookmarkupdate	接收會員書籤更新記錄，回存伺服DB
    //function userbookmarkupdate() {
    //     $userBMU = new \App\Http\Controllers\Account\UserBookmarkUpdate;
    //     $userBMU->userbookmarkupdate();
    //}
     //userbookmarkupdate    接收會員書籤更新記錄，回存伺服DB
    function userbookmarkupdate() {
        $functionName = 'userbookmarkupdate';
        $inputString = Input::All();
        $inputData =\App\Library\Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        //$resultString;
        //$resultData = null;
        //$messageCode = '';
        $userBMU = new \App\Http\Controllers\APIControllers\Account\UserBookmarkUpdate;
        try {
            //\App\Models\ErrorLog::InsertLog(json_encode($inputData));
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!$userBMU->CheckInput($inputData)) {
                //輸入值有問題
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            $md_id = null;
            //檢查身份模組驗證
            $memService = new \App\Services\MemberService;
            if ( !$memService->checkModuleAccount($inputData['modacc'], $inputData['modvrf'], $messageCode)) {
              //模組身份驗證失敗
              //$messageCode = '999999961';
              throw new \Exception($messageCode);
            }
            if (!$memService->checkServiceAccessToken($inputData['sat'], $md_id, $messageCode)) {
               //$messageCode = '999999961';
               throw new \Exception($messageCode);
            }
            if (( $inputData['useroperate'] == '2' && $inputData['ubm_objectid'] != 'all' ) || ( $inputData['useroperate'] != '2' && $inputData['ubm_objectid'] == 'all' )) {
                //010107004 書籤更新操作無效，請確認後再發送
                $messageCode = '010107004';
                throw new \Exception($messageCode);
            }
            if (!( $inputData['useroperate'] == '2' && $inputData['ubm_objectid'] == 'all' )) {
                if (!$userBMU->CheckExistBookmarkItem($inputData['ubm_objecttype'], $inputData['ubm_objectid'])) {
                    //010107003 書籤更新物件不存在，請確認後再發送
                    $messageCode = '010107003';
                    throw new \Exception($messageCode);
                }
            }
            $appService = new \App\Services\AppService;
            $bankService = new \App\Services\BankService;
            $modifytype =  ($inputData['useroperate'] == 0 ) ? 6 : 7;
            if ($modifytype!= 7) {
                $messageCode = $appService->GetGiftPointDayLimit($md_id, $modifytype);
            }
            if(isset($inputData['md_id'])) {
              $md_id = $inputData['md_id'];
            }
            if (!$userBMU->UpdateUserBookmarkData($inputData['useroperate'], $inputData['ubm_objecttype'], $inputData['ubm_objectid'], $md_id, $messageCode)) {
                throw new \Exception($messageCode);
            }
            $bankService->getMemGiftPointQuery( null, $md_id, 1, $pointData, $messageCode);
            $pointData['gpmr_point'] =  (strlen($pointData['gpmr_point']) == 0 ) ? 0 : $pointData['gpmr_point'];
            $resultData['gpmr_point'] = $pointData['gpmr_point'];
             if ($messageCode != '000000000' && !is_null($messageCode) ) {
                 throw new \Exception($messageCode);
             }

            $messageCode = '000000000';
        } catch (\Exception $e) {

            if (!isset($messageCode) || is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($e);
            }
        }
        if (!isset($resultData)) {
            $resultData = null;
        }

        //回傳值
        $resultArray = \App\Library\Commontools::ResultProcess($messageCode, $resultData);
        \App\Library\Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray];

        return $result;
    }

}
