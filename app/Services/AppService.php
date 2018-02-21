<?php
namespace App\Services;

define('APPSERVICE_URL', config('global.APPSERVICE_URL'));

class AppService {
   /**
     * 向App模組，傳送會員信件(MessageLog)
     * @param type $arraydata
     * @return boolean
     */
    public  function PostMessageLog ($arraydata) {
       try {
            $memService = new \App\Services\MemberService;
            if (! $memService->query_salt($salt_no, $salt)) {
                throw new \Exception($messageCode);
            }

            $modvrf = //公司機密已刪除
            $post = [
                            'modacc' => MODACC,
                            'modvrf'     => $modvrf,
                            'md_id' => $arraydata['md_id'],
                            'uml_message' => $arraydata['uml_message']   ];

            if(array_key_exists('uml_type',$arraydata)) {
                $post = array_add($post,'uml_type',$arraydata['uml_type']);
            }

            if (array_key_exists('uml_object',$arraydata)) {
                $post = array_add($post,'uml_object',$arraydata['uml_object']);
            }

            if (array_key_exists('uml_pic',$arraydata)) {
                $post = array_add($post,'uml_pic',$arraydata['uml_pic']);
            }
            if (array_key_exists('uml_status',$arraydata)) {
               $post = array_add($post,'uml_status',$arraydata['uml_status']);
            }
            if (array_key_exists('uml_releationid',$arraydata)) {
               $post = array_add($post,'uml_releationid',$arraydata['uml_releationid']);
            }
            
           //\App\Models\ErrorLog::InsertLog(json_encode(json_encode($post)));
            $route = 'system/postumlmessage';
           if (is_null($response = AppService::curlModule($post, $route))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
            }
            $k = array_keys($response);
            if ($response[$k[0]]['message_no'] != '000000000') {
                $messageCode = $response[$k[0]]['message_no'];
                throw new \Exception($messageCode);
            }
            return true;
      } catch(\Exception $ex) {
           \App\Models\ErrorLog::InsertData($ex);
           return false;
      }
    }
     /**
     * 向App模組，取得信件已讀數(MessageLog)
     * @param type $uml_releationid
     * @return boolean
     */
     public function getUmlMessageLogReadCount($uml_releationid) {
         try {

                $memService = new \App\Services\MemberService;
                if (! $memService->query_salt($salt_no, $salt)) {
                   throw new \Exception($messageCode);
                }

                $modvrf = //公司機密已刪除
                $post = [
                            'modacc' => MODACC,
                            'modvrf'     => $modvrf,
                            'uml_releationid' => $uml_releationid ];
                 $route = 'system/getUmlMessageLogReadCount';
                 //\App\Models\ErrorLog::InsertLog( json_encode(json_encode($post)));
                 if (is_null($response = AppService::curlModule($post, $route))) {
                     $messageCode = '999999999';
                     throw new \Exception($messageCode);
                 }
                 $k = array_keys($response);
                 if ($response[$k[0]]['message_no'] != '000000000') {
                     $messageCode = $response[$k[0]]['message_no'];
                     throw new \Exception($messageCode);
                 }
                 return $response[$k[0]]['readed_count'];
         } catch(\Exception $ex) {
             \App\Models\ErrorLog::InsertData($ex);
             return null;
         }
     }
     /**
     * 向App模組，取得禮點應扣加點數(modifyGiftPoint)
     * @param type int $modifytype  異動型態            １：「下載APP註冊會員贈點」，「＋」
     *                                                                               ２：「CP車輛詳情留言評論贈點」，「＋」
     *                                                                               ３：「isCar新聞評論贈點」，「＋」
     *                                                                               ４：「isCar新聞FB分享贈點」，「＋」
     *                                                                                5：「isCar新聞LINE分享至動態消息贈點」，「＋」
     *                                                                                6：「收藏特約商為最愛贈點」，「＋」
     *                                                                                7：「取消收藏特約商為最愛贈點」，「-」
     *                                                                                8：「特約商排隊評論贈點」，「＋」
     *                                                                                9：「特約商優惠劵評論贈點」，「＋」
     *                                                                              10：「祈福專區線上捐獻贈點」，「＋」
     *                                                                              11：「isCar新聞LINE分享至動態消息贈點」，「＋」
     *                                                                              12：「祈福專區點燈祈福贈點」，「＋」
     *                                                                              13 :   「分享者贈送禮點比例」，「＋」
     * @return boolean
     */
     public function getGiftPointsAmount( $modifytype) {
         try {

                $memService = new \App\Services\MemberService;
                if (! $memService->query_salt($salt_no, $salt)) {
                   throw new \Exception($messageCode);
                }

                $modvrf = //公司機密已刪除
                $post = [
                            'modacc' => MODACC,
                            'modvrf'     => $modvrf,
                            'modifytype' => $modifytype ];
                 $route = 'system/getGiftPointsAmount';
                 if (is_null($response = AppService::curlModule($post, $route))) {
                     $messageCode = '999999999';
                     throw new \Exception($messageCode);
                 }
                 $k = array_keys($response);
                 if ($response[$k[0]]['message_no'] != '000000000') {
                     $messageCode = $response[$k[0]]['message_no'];
                     throw new \Exception($messageCode);
                 }
                 return $response[$k[0]]['bal_bounusamount'];
         } catch(\Exception $ex) {
             \App\Models\ErrorLog::InsertData($ex);
             return null;
         }
     }
     /**
     * 向App模組，進行禮點扣加點(modifyGiftPoint)
     * @param type string $md_id 會員編號
     * @param type int $modifytype  異動型態            １：「下載APP註冊會員贈點」，「＋」
     *                                                                               ２：「CP車輛詳情留言評論贈點」，「＋」
     *                                                                               ３：「isCar新聞評論贈點」，「＋」
     *                                                                               ４：「isCar新聞FB分享贈點」，「＋」
     *                                                                                5：「isCar新聞LINE分享至動態消息贈點」，「＋」
     *                                                                                6：「收藏特約商為最愛贈點」，「＋」
     *                                                                                7：「取消收藏特約商為最愛贈點」，「-」
     *                                                                                8：「特約商排隊評論贈點」，「＋」
     *                                                                                9：「特約商優惠劵評論贈點」，「＋」
     *                                                                              10：「祈福專區線上捐獻贈點」，「＋」
     *                                                                              11：「isCar新聞LINE分享至動態消息贈點」，「＋」
     *                                                                              12：「祈福專區點燈祈福贈點」，「＋」
     *                                                                              13 :   「分享者贈送禮點比例」，「＋」
     * @param type string $relation_id  關聯代碼
     * @param type int  $module_id  模組代碼
     * @param type boolean $boolean_add 加點與否
     * @param type int $modifypoint
     * @return boolean
     */
     public function modifyGiftPoint($md_id, $modifytype, $relation_id, $module_id, $boolean_add,$modifypoint) {
         try {

                $memService = new \App\Services\MemberService;
                if (! $memService->query_salt($salt_no, $salt)) {
                   throw new \Exception($messageCode);
                }

                $modvrf = //公司機密已刪除
                $post = [
                            'modacc' => MODACC,
                            'modvrf'     => $modvrf,
                            'md_id' => $md_id,
                            'modifytype' => $modifytype,
                            'relation_id' => $relation_id,
                            'module_id' =>$module_id,
                            'boolean_add'=>$boolean_add,
                            'modifypoint' =>$modifypoint];
                 $route = 'system/modifyGiftPoint';
                 if (is_null($response = AppService::curlModule($post, $route))) {
                     $messageCode = '999999999';
                     throw new \Exception($messageCode);
                 }
                 $k = array_keys($response);
                 if ($response[$k[0]]['message_no'] != '000000000') {
                     $messageCode = $response[$k[0]]['message_no'];
                     throw new \Exception($messageCode);
                 }
                 return $response[$k[0]]['gpmr_point'];
         } catch(\Exception $ex) {
             \App\Models\ErrorLog::InsertData($ex);
             return null;
         }
     }
     
     
     
     public function GetGiftPointDayLimit($md_id, $modifytype) {
         try {

                $memService = new \App\Services\MemberService;
                if (! $memService->query_salt($salt_no, $salt)) {
                   throw new \Exception($messageCode);
                }

                $modvrf = //公司機密已刪除
                $post = [
                            'modacc' => MODACC,
                            'modvrf'     => $modvrf,
                            'md_id' => $md_id,
                            'modifytype' => $modifytype];
                 $route = 'system/getgiftpointdaylimit';
                 if (is_null($response = AppService::curlModule($post, $route))) {
                     $messageCode = '999999999';
                     throw new \Exception($messageCode);
                 }
                 $k = array_keys($response);
                 return $response[$k[0]]['message_no'];
         } catch(\Exception $ex) {
             \App\Models\ErrorLog::InsertData($ex);
             return null;
         }
     }
     
     
     

     /**
     * Curl模組化使用
     * @param type array $post 傳送資料
     * @param type string $route 傳送route
     * @return array or null
     */
      function curlModule ($post, $route) {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => APPSERVICE_URL.$route,
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
            curl_close($curl);
            if ($err) {
                throw new \Exception($err);
            } else {
                return \App\Library\Commontools::ConvertStringToArray($response);
            }
        } catch(\Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
  }

}
