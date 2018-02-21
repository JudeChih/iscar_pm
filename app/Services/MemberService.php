<?php
namespace App\Services;

define('MODACC', config('global.MODACC'));
define('MODPWD', config('global.MODPWD'));
define('MEMSERVICE_URL', config('global.MEMSERVICE_URL'));
class MemberService {
    /**
     * 呼叫「MemberAPI」檢查SAT的狀態，驗證SAT有效性
     * @param type string $sat 用戶登入存取憑證
     * @param type string $md_id 回傳 會員代碼
     * @param type string $messageCode 回傳 訊息代碼
     * @return boolean
     */
    public static function checkServiceAccessToken($sat, &$md_id, &$messageCode) {
        try {
           if (! MemberService::query_salt($salt_no, $salt) ) {
            throw new \Exception($messageCode);
           }
           $modvrf = //公司機密已刪除;
           $post = [
                       'modacc' => MODACC,
                       'modvrf'     => $modvrf,
                       'sat'           => $sat,
                    ];

           $route = 'api/vrf/verify_sat';
           if (is_null($response = MemberService::curlModule($post, $route))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
            }
            $k = array_keys($response);
            if ($response[$k[0]]['message_no'] != '010110001') {
                $messageCode = $response[$k[0]]['message_no'];
                throw new \Exception($messageCode);
            }
            $md_id = $response[$k[0]]['md_id'];
            return true;
        } catch (\Exception $ex) {
            if ( is_null($messageCode) ) {
                 \App\Models\ErrorLog::InsertData($ex);
                  $messageCode = '999999999';
           }
            return false;
        }
    }

       /**
     * 呼叫「MemberAPI」驗證模組身份，驗證跨模無SAT時,呼叫方有效性
     * @param type string $modacc 跨模組帳號
     * @param type string $modvrf 跨模組驗證碼
     * @param type string $messageCode 回傳 訊息代碼
     * @return boolean
     */
    public static function checkModuleAccount($modacc, $modvrf, &$messageCode) {
        try {
            if (!MemberService::query_salt($salt_no, $salt)) {
                 throw new \Exception($messageCode);
           }
           $this_modvrf = //公司機密已刪除;
           $post = [
                       'modacc'       => MODACC,
                       'modvrf'           => $this_modvrf ,
                       'from_modacc' => $modacc,
                       'from_modvrf'   => $modvrf
                    ];
            $route = 'api/vrf/verify_apifrom';
            if (is_null($response = MemberService::curlModule($post, $route))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
            }
            $k = array_keys($response);
            if ($response[$k[0]]['message_no'] != '000000000') {
                $messageCode = $response[$k[0]]['message_no'];
                throw new \Exception($messageCode);
            }
            return true;
        } catch (\Exception $ex) {
            if ( is_null($messageCode) ) {
                \App\Models\ErrorLog::InsertData($ex);
                $messageCode = '999999999';
            }
            return false;
        }
    }

    /**
     * 呼叫「MemberAPI」取得Token所對應到的會員資料
     * @param type string $sat 用戶登入存取憑證
     * @param type string $mur 用戶運行環境編號
     * @param type array $basicMemData 回傳 基本會員資料
     * @param type string $messageCode 回傳 訊息代碼
     * @return boolean
     */
    public static function  queryMemberInfo($sat, $mur, &$basicMemData, &$messageCode) {
        try {
            $post = [
                       'sat'       => $sat,
                       'mur'      => $mur,
                    ];
             $route = 'api/query_member_basicinfo';
             if (is_null($response=  MemberService::curlModule($post, $route))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
             }
             $k = array_keys($response);
             if ($response[$k[0]]['message_no'] != '000000000') {
                $messageCode = $response[$k[0]]['message_no'];
                throw new \Exception($messageCode);
             }
             $basicMemData = [
                'md_id'                   => $response[$k[0]]['md_id'] ,
                'md_cname'            => $response[$k[0]]['md_cname'] ,
                'md_picturepath'      => $response[$k[0]]['md_picturepath'] ,
                'md_logintype'         => $response[$k[0]]['md_logintype'] ,
                'md_clienttype'        => $response[$k[0]]['md_clienttype'] ,
                'md_clubjoinstatus'   => $response[$k[0]]['md_clubjoinstatus'] ,
             ];
             return true;
        } catch (\Exception $ex) {
            if ( is_null($messageCode) ) {
                 \App\Models\ErrorLog::InsertData($ex);
                $messageCode = '999999999';
            }
            return false;
        }
    }

     /**
     * 呼叫「MemberAPI」取得salt的員資料
     * @param type string $salt_no 回傳 鹽值序號
     * @param type string $salt 回傳 鹽值序號
     * @return boolean
     */
    public static function  query_salt(&$salt_no, &$salt) {
        try {
           $post = [ 'modacc' => MODACC ];
           $route = 'api/vrf/query_salt';
           $response =  MemberService::curlModule($post, $route);
           //\App\Models\ErrorLog::InsertLog(json_encode($response));
           if (is_null($response)) {
                $messageCode = '999999999';
                 throw new \Exception($messageCode);
           }
           $k = array_keys($response);
           if ($response[$k[0]]['message_no'] != '000000000') {
               $messageCode = $response[$k[0]]['message_no'];
               throw new \Exception($messageCode);
           }
           $saltData  = $response[$k[0]]['salt'] ;$stringSalt =  base64_decode(urldecode($saltData));
           $saltArray = explode("_",$stringSalt);
           $salt_no = $saltArray[0];
           $salt = $saltArray[1];
           return true;
        } catch (\Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }
    /**
     * 呼叫「MemberAPI」取得salt的員資料
     * @param type string $sat 用戶登入存取憑證
     * @param type array $md_id_array  需要推播的會員代碼
     * @return boolean
     */
    public static function  push_notification($sat, $md_id_array, $message = null, $title = null, $iscar_push, $target) {
        try {
           if (! MemberService::query_salt($salt_no, $salt) ) {
             throw new \Exception($messageCode);
           }
           $modvrf = //公司機密已刪除;
           $post = [
                       'modacc'   => MODACC,
                       'modvrf'     => $modvrf,
                       'sat'          => $sat,
                       'md_id_array'    => $md_id_array,
                       'message' => $message,
                       'title' => $title,
                       'iscar_push' => $iscar_push,
                       'target' => $target
                    ];
           $route = 'api/push_notification';
           if (is_null($response =  MemberService::curlModule($post, $route))) {
                $messageCode = '999999999';
                 throw new \Exception($messageCode);
           }
           $k = array_keys($response);
           if ($response[$k[0]]['message_no'] != '000000000') {
               $messageCode = $response[$k[0]]['message_no'];
               throw new \Exception($messageCode);
           }
           return true;
        } catch (\Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

  /**
     * 呼叫「MemberAPI」依md_id修改會員基本資料
     * @param type string $sat 用戶登入存取憑證
     * @param type string $messageCode 回傳 訊息代碼
     * @return boolean
     */
  public static function modify_member_clienttype($md_id, $sat, $clienttype, &$messageCode) {
      try {
           //  \App\Models\ErrorLog::InsertLog('03');
            if (!MemberService::query_salt($salt_no, $salt)) {
                  throw new \Exception($messageCode);
            }
            $modvrf = //公司機密已刪除;
            $post = [
                       'modacc'         => MODACC,
                       'modvrf'           => $modvrf,
                       'sat'                 => $sat,
                       'clienttype'        => $clienttype,
                       'md_id'             => $md_id
                    ];
             $route = 'api/modify_member_clienttype';
               //\App\Models\ErrorLog::InsertLog('0123');
             if (is_null($response=  MemberService::curlModule($post, $route))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
             }
             $k = array_keys($response);
             if ($response[$k[0]]['message_no'] != '000000000') {
                $messageCode = $response[$k[0]]['message_no'];
                throw new \Exception($messageCode);
             }
             return true;
        } catch (\Exception $ex) {
            if ( is_null($messageCode) ) {
                 \App\Models\ErrorLog::InsertData($ex);
                $messageCode = '999999999';
            }
            return false;
        }
  }

    /**
     * 呼叫「MemberAPI」依md_id修改會員基本資料
     * @param type string $sat 用戶登入存取憑證
     * @param type string $messageCode 回傳 訊息代碼
     * @return boolean
     */
  public static function verity_Sales($sat, &$md_id, &$messageCode) {
      try {
           //  \App\Models\ErrorLog::InsertLog('03');
            if (!MemberService::query_salt($salt_no, $salt)) {
                  throw new \Exception($messageCode);
            }
            $modvrf = //公司機密已刪除;
            $post = [
                       'modacc'         => MODACC,
                       'modvrf'           => $modvrf,
                       'sat'                 => $sat
                    ];
             $route = 'api/vrf/verify_sales';
              //   \App\Models\ErrorLog::InsertLog('01');
             if (is_null($response=  MemberService::curlModule($post, $route))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
             }
             //  \App\Models\ErrorLog::InsertLog('02');
             $k = array_keys($response);
             if ($response[$k[0]]['message_no'] != '000000000') {
                $messageCode = $response[$k[0]]['message_no'];
                throw new \Exception($messageCode);
             }
             $md_id = $response[$k[0]]['md_id'];
             return true;
        } catch (\Exception $ex) {
            if ( is_null($messageCode) ) {
                 \App\Models\ErrorLog::InsertData($ex);
                $messageCode = '999999999';
            }
            return false;
        }
  }
  
   /**
     * 呼叫「MemberAPI」驗證安全碼
     * @param  [string] $md_id           [會員代碼]
     * @param  [string] $md_securitycode [用戶安全碼] 經過
     * @return boolean
     */
        public static function verify_memberseccode($md_id, $md_securitycode,  &$messageCode) {
      try {
           //  \App\Models\ErrorLog::InsertLog('03');
            if (!MemberService::query_salt($salt_no, $salt)) {
                  throw new \Exception($messageCode);
            }
            $modvrf = //公司機密已刪除;
            $post = [
                       'modacc'         => MODACC,
                       'modvrf'           => $modvrf,
                       'md_id'                 => $md_id,
                       'md_securitycode' => $md_securitycode
                    ];
             $route = '/api/vrf/verify_memberseccode';
              //   \App\Models\ErrorLog::InsertLog('01');
             if (is_null($response=  MemberService::curlModule($post, $route))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
             }
             //  \App\Models\ErrorLog::InsertLog('02');
             $k = array_keys($response);
             if ($response[$k[0]]['message_no'] != '000000000') {
                $messageCode = $response[$k[0]]['message_no'];
                throw new \Exception($messageCode);
             }
             return true;
        } catch (\Exception $ex) {
            if ( is_null($messageCode) ) {
                 \App\Models\ErrorLog::InsertData($ex);
                $messageCode = '999999999';
            }
            return false;
        }
  }


     /**
     * Curl模組化使用
     * @param type array $post 傳送資料
     * @param type string $route 傳送route
     * @return array or null
     */
    private static function curlModule ($post, $route){
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => MEMSERVICE_URL.$route,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode(json_encode($post)),
                CURLOPT_HTTPHEADER => array(
                  "cache-control: no-cache",
                  "content-type: application/json",
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
           // \App\Models\ErrorLog::InsertLog($err.'   curl_error-123456');
            curl_close($curl);
            if ( $err ) {
                //throw new \Exception($err);
            } else {
                return \App\Library\Commontools::ConvertStringToArray($response);
            }
        } catch(\Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

}
