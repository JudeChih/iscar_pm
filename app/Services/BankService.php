<?php
namespace App\Services;


define('BANKSERVICE_URL', config('global.BANKSERVICE_URL'));
define('BANKSERVICE_PASSWORD', config('global.BANKSERVICE_PASSWORD'));
class BankService {
    /**
     * 呼叫「BankAPI」，查詢會員點數
     * @param type string $sat 用戶登入存取憑證
     * @param type int $pointType 點數類別
     * @param type int $pointData 回傳 會員點數資料
     * @param type string $messageCode 回傳 訊息代碼
     * @return boolean
     */
    /*public function memPointQuery($sat, $pointtype, &$pointData,&$messageCode) {
        try {
           if (!  \App\Services\MemberService::query_salt($salt_no, $salt) ) {
            throw new \Exception($messageCode);
           }
           $modvrf = //公司機密已刪除
           $post = [
                       'modacc'   => MODACC,
                       'modvrf'     => $modvrf,
                       'sat'           => $sat,
                       'pointtype'  => $pointtype
                    ];
           $route = 'mem_point_query';
           if (is_null($response = $this->curlModule($post, $route))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
            }
            $k = array_keys($response);
            if ($response[$k[0]]['message_no'] != '000000000') {
                $messageCode = $response[$k[0]]['message_no'];
                throw new \Exception($messageCode);
            }
            $pointData = $response[$k[0]]['pointdata'];
            return true;
        } catch (\Exception $ex) {
            if ( is_null($messageCode) ) {
                 \App\Models\ErrorLog::InsertData($ex);
                  $messageCode = '999999999';
            } 
            return false;
        }
    }*/

     /**
     * 呼叫「BankAPI」，修改會員點數
     * @param type string $sat 用戶登入存取憑證
     * @param type string $modacc 跨模組帳號
     * @param type string $modvrf 跨模組驗證碼
     * @param type string $modifyType  異動型態     １：「IAP/IAB購買」，「＋」，異動點數類別： 「０」
     *                                                                              ２：「消費點數購買權限」，「－」，異動點數類別： 「０」
     *                                                                              ３：「APP事件執行紅利給與」，「＋」，異動點數類別：「３」
     *                                                                              ４：「使用活動券給予廠商紅利」，「－」，異動點數類別：「２」
     *                                                                              ５：「索取活動券扣除廠商紅利」，「－」，異動點數類別：「２」
     *                                                                              ６：「完成活動券評論給與iscar禮點」，「＋」，異動點數類別：「１」
     *                                                                              ７：「完成合作社活動用後評論給與iscar禮點」，「＋」，異動點數類別：「１」
     *                                                                              ８：「使用儲值點數刊登售車資訊或商家首頁」，「－」，異動點數類別：「０」
     *                                                                              ９：「寶箱模組消費後點數扣除」，「－」，異動點數類別：「０」
     *                                                                              １０：「寶箱模組使用禮點卡取得禮點」，「＋」，異動點數類別：「１」
     *                                                                              １１：「特約商推播廣告訊息扣除購點」，「－」，異動點數類別：「０」
     * @param type string $relation_id  關聯代碼。
     * @param type int $modifyPoint  要異動的點數
     * @param type int $stockPoint 回傳 庫存點數
     * @param type string $messageCode 回傳 訊息代碼
     * @return boolean
     */
  /*  public function memPointModify($sat, $modacc, $modvrf, $modifytype, $relation_id, $modifypoint, &$stockPoint, &$messageCode) {
        try {
            if (! \App\Services\MemberService::query_salt($salt_no, $salt)) {
                 throw new \Exception($messageCode);
           }
           $modvrf = //公司機密已刪除
           $post = [
                       'modacc'         => MODACC,
                       'modvrf'           => $modvrf ,
                       'sat'                => $sat,
                       'modifytype'    => $modifytype,
                       'relation_id'     => $relation_id,
                       'modifypoint'   => $modifypoint
                    ];
            $route = 'mem_point_modify';
            if (is_null($response = $this->curlModule($post, $route))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
            }
            $k = array_keys($response);
            if ($response[$k[0]]['message_no'] != '000000000') {
                $messageCode = $response[$k[0]]['message_no'];
                throw new \Exception($messageCode);
            }
            $stockPoint = $response[$k[0]]['stockpoint'];
            return true;
        } catch (\Exception $ex) {
            if ( is_null($messageCode) ) {
                \App\Models\ErrorLog::InsertData($ex);
                $messageCode = '999999999';
            }
            return false;
        }
    }*/

    //接原memPointQuery功能
     public function getMemBuyPointQuery($password = null, $md_id, $module_id, &$pointData, &$messageCode) {
        try {
          if (is_null($password))
                 $password = BANKSERVICE_PASSWORD;
          $data = [
                  'md_id'   => $md_id,
                  'call_update_module_id'   =>''.$module_id,
                  'chkvalue'   => sha1($module_id . $password . $md_id)
          ];
          // \App\Models\ErrorLog::InsertLog(json_encode($data));
          $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
          //base64加密
          $base64_json_data = base64_encode($json_data);
          $url = "iscar-api/buypoint/getdata";
          $chkvalue = $data['chkvalue'];
          $post_fields_data = ["chkvalue"=>$chkvalue, "data"=>$base64_json_data];
          //\App\Models\ErrorLog::InsertLog(json_encode($post_fields_data));
          // \App\Models\ErrorLog::InsertLog("BankService - Row 122");
           if (is_null($response = $this->curlModule($post_fields_data, $url))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
            }
          //  \App\Models\ErrorLog::InsertLog(json_encode($response));
            if ($response['code'] != '0') {
                $messageCode = $response['code'];
                throw new \Exception($messageCode);
            }
            $pointData = $response['data'];
            return true;
        } catch (\Exception $ex) {
            if ( is_null($messageCode) ) {
                 \App\Models\ErrorLog::InsertData($ex);
                  $messageCode = '999999999';
            } 
            return false;
        }
    }

    //接原memPointModify 功能
    /**
     *  @param type string $md_id 會員帳號
     *  @param type string $modifyType  異動型態     １：「特約商推播廣告訊息扣除購點」，「－」，異動點數類別：「０」
     *                                                                               ２：「使用儲值點數刊登售車資訊或商家首頁」，「－」，異動點數類別：「０」
     *                                                                               ３：「IAP/IAB購買」，「＋」，異動點數類別： 「０」
     *                                                                               ４：「消費點數購買權限」，「－」，異動點數類別： 「０」
     *                                                                               ５：「寶箱模組消費後點數扣除」，「－」，異動點數類別：「０」
     *                                                                               
     *  @param type int      $modifyPoint  要異動的點數
     *  @param type string $relation_id  關聯代碼。
     *  @param type string $note  註記。
     *  @param type string $messageCode 回傳 訊息代碼
     *  @return boolean
     **/
     public function modifyMemBuyPoint($md_id, $modifytype, $modifypoint, $relation_id, $module_id, $note = null, $add = true, &$messageCode) {
        try {
           if (!$add) {
              $modifypoint = (int)(-$modifypoint);
           }
           $password = BANKSERVICE_PASSWORD;
           $data = [
                 "md_id"                            => $md_id,
                 "bpmr_modify"                 => "".$modifytype,
                 "bpmr_point"                    => "".$modifypoint,
                 "bpmr_relation_id"           => $relation_id,
                 "call_update_module_id" => "".$module_id,
                 "bpmr_note"                     => $note,
                 "chkvalue"                        => sha1($module_id . $password . abs($modifypoint)),
           ];

          $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
          //base64加密
          $base64_json_data = base64_encode($json_data);
          $url = "iscar-api/buypoint/modifydata";
          $chkvalue = $data['chkvalue'];
          $post_fields_data = ["chkvalue"=>$chkvalue, "data"=>$base64_json_data];
            if (is_null($response = $this->curlModule($post_fields_data, $url))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
            }
            if ($response['code'] != '0') {
                $messageCode = $response['code'];
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

    public function getMemGiftPointQuery($password = null, $md_id, $module_id, &$pointData, &$messageCode) {
        try {
           if (is_null($password))
                 $password = BANKSERVICE_PASSWORD;
          $data = [
                  'md_id'   => $md_id,
                  'call_update_module_id'   => ''.$module_id,
                  'chkvalue'   => sha1($module_id . $password . $md_id)
          ];
          $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
          //base64加密
          $base64_json_data = base64_encode($json_data);
    
          $url = "iscar-api/giftpoint/getdata";
          $chkvalue = $data['chkvalue'];
          $post_fields_data = ["chkvalue"=>$chkvalue, "data"=>$base64_json_data];

           if (is_null($response = $this->curlModule($post_fields_data, $url))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
            }
            if ($response['code'] != '0') {
                $messageCode = $response['code'];
                throw new \Exception($messageCode);
            }
            $pointData = $response['data'];
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
     *  @param type string $md_id 會員帳號
     *  @param type string $modifyType  異動型態     １：「完成合作社活動用後評論給與iscar禮點」，「＋」，異動點數類別：「１」
     *                                                                               ２：「APP事件執行紅利給與」，「＋」，異動點數類別：「３」
     *                                                                               ３：「完成活動券評論給與iscar禮點」，「＋」，異動點數類別：「１」
     *                                                                               ４：「寶箱模組使用禮點卡取得禮點」，「＋」，異動點數類別：「１」
     *                                                                               
     *  @param type int      $modifyPoint  要異動的點數
     *  @param type string $relation_id  關聯代碼。
     *  @param type string $note  註記。
     *  @param type string $messageCode 回傳 訊息代碼
     *  @return boolean
     **/
     public function modifyMemGiftPoint($md_id, $modifytype, $modifypoint, $relation_id, $module_id, $note = null, $add = true, &$messageCode) {
        try {
           if (!$add) {
              $modifypoint = (int)(-$modifypoint);
           }
           $password = BANKSERVICE_PASSWORD;
           $data = [
                 "md_id"                            => $md_id,
                 "gpmr_modify"                 => ''.$modifytype,
                 "gpmr_point"                    => ''.$modifypoint,
                 "gpmr_relation_id"           => $relation_id,
                 "call_update_module_id" => ''.$module_id,
                 "gpmr_note"                     => $note,
                 "chkvalue"                        => sha1($module_id . $password . abs($modifypoint)),
           ];
          $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
          //base64加密
          $base64_json_data = base64_encode($json_data);
          $url = "iscar-api/giftpoint/modifydata";
          $chkvalue = $data['chkvalue'];
          $post_fields_data = ["chkvalue"=>$chkvalue, "data"=>$base64_json_data];
            if (is_null($response = $this->curlModule($post_fields_data, $url))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
            }
            if ($response['code'] != '0') {
                $messageCode = $response['code'];
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


    public function getMemPmPointQuery($password = null, $md_id, $sd_id,$module_id, &$pointData, &$messageCode) {
        try {
         if (is_null($password))
                 $password = BANKSERVICE_PASSWORD;
          $data = [
                  'md_id'   => $md_id,
                  'shop_id'  => $sd_id,
                  'call_update_module_id'   => $module_id,
                  'chkvalue'   => sha1($module_id . $password . $md_id . $sd_id)
          ];
          $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
          //base64加密
          $base64_json_data = base64_encode($json_data);
    
          $url = "iscar-api/shoppoint/getdata";
          $chkvalue = $data['chkvalue'];
          $post_fields_data = ["chkvalue"=>$chkvalue, "data"=>$base64_json_data];

           if (is_null($response = $this->curlModule($post_fields_data, $url))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
            }
            if ($response['code'] != '0') {
                $messageCode = $response['code'];
                throw new \Exception($messageCode);
            }
            $pointData = $response['data'];
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
     *  @param type string $md_id 會員帳號
     *  @param type string $sd_id  特約商帳號
     *  @param type string $modifyType  異動型態    １：「特約商行銷活動紅利贈與」，「＋」，異動點數類別： 「０」
     *                                                                              ２：「特約商優惠券使用贈與紅利」，「＋」，異動點數類別： 「０」
     *                                                                              ３：「特約商現場消費贈與紅利」，「＋」，異動點數類別： 「０」
     *                                                                              ４：「特約商紅利兌換優惠券扣除紅利」，「－」，異動點數類別：「０」
     *  @param type int      $modifyPoint  要異動的點數
     *  @param type string $relation_id  關聯代碼。
     *  @param type string $note  註記。
     *  @param type string $messageCode 回傳 訊息代碼
     *  @return boolean
     **/
     public function modifyMemPmPoint($md_id, $sd_id, $modifytype, $modifypoint, $relation_id, $module_id, $note = null, $add = true, &$messageCode) {
        try {
           if (!$add) {
              $modifypoint = (int)(-$modifypoint);
           }
           $password = BANKSERVICE_PASSWORD;
           $data = [
                 "md_id"                            => $md_id,
                 "shop_id"                         => $sd_id,
                 "spmr_modify"                 => $modifytype,
                 "spmr_point"                    => $modifypoint,
                 "spmr_relation_id"           => $relation_id,
                 "call_update_module_id" => $module_id,
                 "spmr_note"                     => $note,
                 "chkvalue"                        => sha1($module_id . $password . abs($modifypoint)),
           ];
          $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
          //base64加密
          $base64_json_data = base64_encode($json_data);
          $url = "iscar-api/shoppoint/modifydata";
          $chkvalue = $data['chkvalue'];
          $post_fields_data = ["chkvalue"=>$chkvalue, "data"=>$base64_json_data];
            if (is_null($response = $this->curlModule($post_fields_data, $url))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
            }
            if ($response['code'] != '0') {
                $messageCode = $response['code'];
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
     * 呼叫「BankAPI」取得特約商紅利
     * @param type string $sat 用戶登入存取憑證
     * @param type string $sd_id 特約商代碼
     * @param type int $bonustype 紅利類別
     * @param type int $BonusData 回傳  特約商紅利資料
     * @param type string $messageCode 回傳 訊息代碼
     * @return boolean
     */
   /* public function  pmBounsQuery($sat, $sd_id, $bonustype, &$BonusData, &$messageCode) {
        try {
            if ( \App\Services\MemberService::query_salt($salt_no, $salt)) {
                 throw new \Exception($messageCode);
           }
           $modvrf = //公司機密已刪除
           $post = [
                       'modacc'         => MODACC,
                       'modvrf'           => $modvrf ,
                       'sat'                => $sat,
                       'sd_id'             => $sd_id,
                       'bonustype'     => $bonustype,
                    ];
             $route = 'pm_bonus_query';
             if (is_null($response=  $this->curlModule($post, $route))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
             }
             $k = array_keys($response);
             if ($response[$k[0]]['message_no'] != '000000000') {
                $messageCode = $response[$k[0]]['message_no'];
                throw new \Exception($messageCode);
             }
             $BonusData = $response[$k[0]]['bonusstock'];
             return true;
        } catch (\Exception $ex) {
            if ( is_null($messageCode) ) {
                 \App\Models\ErrorLog::InsertData($ex);
                $messageCode = '999999999';
            }
            return false;
        }
    }*/

     /**
     * 呼叫「BankAPI」修改特約商紅利
     * @param type string $sat 用戶登入存取憑證
     * @param type string $sd_id 特約商代碼
     * @param type int $bonustype 紅利類別
     * @param type int $modifyBonus  要異動的點數
     * @param type int $stockBonus 回傳  特約商紅利
     * @param type string $messageCode 回傳 訊息代碼
     * @return boolean
     */
   /* public function  pmBonusModify($sat, $sd_id, $bonustype, $modifyBonus , &$stockBonus, &$messageCode) {
        try {
           if ( \App\Services\MemberService::query_salt($salt_no, $salt)) {
                 throw new \Exception($messageCode);
           }
           $modvrf = //公司機密已刪除
           $post = [
                       'modacc'         => MODACC,
                       'modvrf'           => $modvrf ,
                       'sat'                => $sat,
                       'sd_id'             => $sd_id,
                       'bonustype'     => $bonustype,
                       'modifyBonus' => $modifyBonus
                    ];
           $route = 'pm_bonus_modify';
           if (is_null($response =  $this->curlModule($post, $route))) {
                $messageCode = '999999999';
                 throw new \Exception($messageCode);
           }
           $k = array_keys($response);
           if ($response[$k[0]]['message_no'] != '000000000') {
               $messageCode = $response[$k[0]]['message_no'];
               throw new \Exception($messageCode);
           }
           $stockBonus = $response[$k[0]]['stockBonus'];
           return true;
        } catch (\Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }*/
     /**
     * Curl模組化使用
     * @param type array $post 傳送資料
     * @param type string $route 傳送route
     * @return array or null
     */
    private function curlModule ($post, $route){
        try {
            /*$curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => BANKSERVICE_URL.$route,
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

            */
            $ch = curl_init();  
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, BANKSERVICE_URL.$route);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
           $response = curl_exec($ch);
           $err = curl_error($ch);
           curl_close($ch);
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
