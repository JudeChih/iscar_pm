<?php

namespace App\Http\Controllers\APIControllers;

use App\Http\Controllers\Controller;
use App\Library\Commontools;
use Mail;
use Illuminate\Support\Facades\Input;
class MailController extends Controller {

    function pay_end_sendMail($scg_id) {
       // $functionName = 'mail';
        /*$inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultString;
        $resultData = null;
        $messageCode = null;*/

        try {
            $secretary_email = \App\Models\ICR_SystemParameter::getSecretaryEmail();
            $user_email = $secretary_email[0]['sp_paramatervalue'];
            $scg_data = \App\Models\ICR_ShopCouponData_g::GetData_CouponDataM_LogisticsDetial($scg_id);
            $scg_data[0]['content'] = '已有一筆［交易完成］，就是行的特約商系統寄出。';
             Mail::send(['html' => 'secretary_email'], ['scg_data' => $scg_data], function($message) use($user_email)
             {
                $message->to($user_email)->subject('isCar 特約商，交易完成。');
             });
            /*$datenow = new \Datetime();
            $stringdate =  $datenow-> format('m-d H:i');
            $resultData = array('date' => $stringdate );
            if ($messageCode == null) {
                \App\Models\ErrorLog::InsertData($e);
                $messageCode = '999999999';
            }*/
             return true;
        } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
        //回傳值
       /* $resultArray = Commontools::ResultProcess($messageCode, $resultData);
        Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray ];
        return $result;*/
    }
    
    
    function refund_payment_sendMail($scg_id) {
        //$functionName = 'mail';
       
        try {
            $secretary_email = \App\Models\ICR_SystemParameter::getSecretaryEmail();
            $user_email = $secretary_email[0]['sp_paramatervalue'];
            $scg_data = \App\Models\ICR_ShopCouponData_g::GetData_CouponDataM_LogisticsDetial($scg_id);
            $scg_data[0]['content'] = '已有一筆［交易取消］，就是行的特約商系統寄出。';
             Mail::send(['html' => 'secretary_email'], ['scg_data' => $scg_data], function($message) use($user_email)
             {
                $message->to($user_email)->subject('isCar 特約商，取消訂單。');
             });
            /*$datenow = new \Datetime();
            $stringdate =  $datenow-> format('m-d H:i');
            $resultData = array('date' => $stringdate );
            if ($messageCode == null) {
                \App\Models\ErrorLog::InsertData($e);
                $messageCode = '999999999';
            }*/
             return true;
        } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
        
    }
    
    function reservation_datetime_sendMail($scg_id) {
        //$functionName = 'mail';
       
        try {
            $secretary_email = \App\Models\ICR_SystemParameter::getSecretaryEmail();
            $user_email = $secretary_email[0]['sp_paramatervalue'];
            $scg_data = \App\Models\ICR_ShopCouponData_g::GetData_CouponDataM_LogisticsDetial($scg_id);
            $scg_data[0]['content'] = '已有一筆［交易預約改時］，就是行的特約商系統寄出。';
             Mail::send(['html' => 'secretary_email'], ['scg_data' => $scg_data], function($message) use($user_email)
             {
                $message->to($user_email)->subject('isCar 特約商，預約改時。');
             });
            /*$datenow = new \Datetime();
            $stringdate =  $datenow-> format('m-d H:i');
            $resultData = array('date' => $stringdate );
            if ($messageCode == null) {
                \App\Models\ErrorLog::InsertData($e);
                $messageCode = '999999999';
            }*/
             return true;
        } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
        
    }
    
    
    function shopsettlementrec_sendMail($ssrm_id) {
        //$functionName = 'mail';
       
        try {
            $secretary_email = \App\Models\ICR_SystemParameter::getSecretaryEmail();
            $user_email = $secretary_email[0]['sp_paramatervalue'];
            $ssrmRepo = new \App\Repositories\ICR_ShopSettleMentrec_mRepository;
            $ssrmData = $ssrmRepo -> GetDataBySsrmIdForEmail($ssrm_id);
            $ssrmData[0]['content'] = '已有一筆［帳款回覆］，就是行的特約商系統寄出。';
             Mail::send(['html' => 'shopsettlementrec_email'], ['ssrm_data' => $ssrmData], function($message) use($user_email)
             {
                $message->to($user_email)->subject('isCar 特約商，帳款回覆。');
             });
            /*$datenow = new \Datetime();
            $stringdate =  $datenow-> format('m-d H:i');
            $resultData = array('date' => $stringdate );
            if ($messageCode == null) {
                \App\Models\ErrorLog::InsertData($e);
                $messageCode = '999999999';
            }*/
             return true;
        } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
        
    }
}
