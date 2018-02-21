<?php

namespace App\library;

use Illuminate\Database\Eloquent\Model;
use App\Models\IsCarJsonioRec;
use App\Models\IsCarProcessStatus;
use App\Models\IsCarServiceAccessToken;
use App\Library\Commontools;
use DB;
class Commontools {
    /*
      public static $BonusComment = "3";
      public static $BonusShareNews = "1";
      public static $BonusShareCoupon = "2";
     */


    //----------------------------------------------公用變數 ----------------------------------------------

    /**
     * 評論文章贈送點數用的〔紅利事件代碼〕
     * @var type 紅利事件代碼
     */
    public static $BEL_Serno_CommentNews = '3';

    /**
     * 「分享文章」贈送點數用的〔紅利事件代碼〕
     * @var type 紅利事件代碼
     */
    public static $BEL_Serno_ShareNews = '1';

    /**
     * 「分享活動券」贈送點數用的〔紅利事件代碼〕
     * @var type 紅利事件代碼
     */
    public static $BEL_Serno_ShareCoupon = '2';

    /**
     * 「留言」贈送點數用的〔紅利事件代碼〕
     * @var type 紅利事件代碼
     */
    public static $BEL_Serno_ShopQuestionnaire = '5';

    /**
     * Facebook App ID
     * @var type
     */
    private static $FB_AppID = '875839542533172';

    /**
     * Facebook App Secret
     * @var type
     */
    private static $FB_AppSecret = 'e5def9309b844b03c4e7698be49d93d3';


    /**
     * Android API_KEY
     * @var type
     */
    private static $Android_Api_Key = 'AIzaSyB4_-ejYyyy-Pt6nogNmaINg5NnAH4mVG4';

    /**
     * 活動圖片路徑
     * @var type
     */
    public static $Coupon_BannerURL = 'http://ga.iscarmg.com:8084/images/coupon/active_banner/';

     /**
     * 商家活動圖片路徑
     * @var type
     */
     public static $ShopCoupon_BannerURL = 'http://tw-media.iscarmg.com/shopdata/';

     /**
     * 管理者md_id(推播，錯誤訊息)
     * @var type
     */
     public static $Managers_MdId = [];

    /**
     *特約商註冊郵件 超連結路徑
     *正式為 http://ga.iscarmg.com:8081/shopmanage/verifyshopmailbind
     *測試為 http://ga.iscarmg.com:8082/shopmanage/verifyshopmailbind
     *@var type
     */
     public static $VerifyShopMailBind = 'http://ga.iscarmg.com:8082/shopmanage/verifyshopmailbind';

    // ----------------------------------------------公用 Function ----------------------------------------------

    /**
     * 建立回傳訊息
     * @param type $ProcessCode 訊息代碼
     * @param type $ArrayData 回傳資料
     * @return type Array
     */
    public static function ResultProcess($ProcessCode, $ArrayData) {
        $value = IsCarProcessStatus::where('ps_id', '=', $ProcessCode)->first();
        //$value = IsCarProcessStatus::find($ProcessCode);
        $content01 = "";
        $content02 = "";
        $content03 = "";

        if ($value != null && $value->count() != 0) {
            $content01 = $value->content01;
            $content02 = $value->content02;
            $content03 = $value->content03;
        }

        //$data = array("message_no" => $ProcessCode, "content01" => rawurlencode($content01), "content02" => rawurlencode($content02), "content03" => rawurlencode($content03));
        $data = array("message_no" => $ProcessCode, "content01" => ($content01), "content02" => ($content02), "content03" => ($content03));
        if ($ArrayData != null) {
            $data = array_merge($data, $ArrayData);
        }

        //$result = urlencode($data);
        //$result = array_map('urlencode', $data);

        return Commontools::UrlEncodeArray($data);
        //return $data;
        //return (json_encode($data));
    }

    /**
     * 對〔$data〕陣列中所有值作「rawurlencode」
     * @param type $data 陣列值
     * @return type 「rawurlencode」後的資料
     */
    private static function UrlEncodeArray($data) {

        //若不為〔陣列〕則直接作「rawurlencode」後回傳
        if (!is_array($data)) {
            //return $data;
            return rawurlencode($data);
        }
        //迴圈：「rawurlencode」所有$value
        foreach ($data as $name => $value) {
            //遞迴：呼叫原本 Function 以跑遍所有「陣列」中的「陣列」
            $data[$name] = Commontools::UrlEncodeArray($value);
        }

        return $data;
    }

    /**
     * 建立「WebAPI 執行記錄」到資料庫中
     * @param type $functionname 執行的功能名稱
     * @param type $input 接收到的值
     * @param type $result 回傳的值
     * @param type $messagecode 訊息代碼
     * @return boolean 執行結果
     */
    public static function WriteExecuteLog($functionname, $input, $result, $messagecode) {
        $arraydata = array("jio_receive" => json_encode($input), "jio_return" => $result, "jio_wcffunction" => $functionname, "ps_id" => $messagecode);
        if (IsCarJsonioRec::InsertData($arraydata)) {
            return true;
        } else {
            return false;
        }
    }
    public static function WriteExecuteLog_NotificationToManagers($functionname, $input, $result, $messagecode, $message) {
        $arraydata = array("jio_receive" => json_encode($input), "jio_return" => $result, "jio_wcffunction" => $functionname, "ps_id" => $messagecode);
        if (IsCarJsonioRec::InsertDataGetId($arraydata, $jio_id)) {
            $message = $message.$jio_id;
            return true;//Commontools::PushNotificationToManagers($message);
        } else {
            return false;
        }
        return true;

    }
    public static function WriteExecuteLogGetId($functionname, $input, $result, $messagecode, &$jio_id) {
        $arraydata = array("jio_receive" => json_encode($input), "jio_return" => $result, "jio_wcffunction" => $functionname, "ps_id" => $messagecode);
        if (IsCarJsonioRec::InsertDataGetId($arraydata, $jio_id)) {
            return true;
        } else {
            return false;
        }
        return true;

    }

    /**
     * 取得隨機GUID字串
     * @return type GUID字串﹙包含Dash﹚
     */
    public static function NewGUID() {
        return Commontools::CreateGUID(true);
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                // 16 bits for "time_mid"
                mt_rand(0, 0xffff),
                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand(0, 0x0fff) | 0x4000,
                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand(0, 0x3fff) | 0x8000,
                // 48 bits for "node"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * 取得隨機GUID字串不包含Dash
     * @return type GUID字串﹙不包含Dash﹚
     */
    public static function NewGUIDWithoutDash() {
        return Commontools::CreateGUID(false);
    }

    /**
     * 取得隨機GUID字串, 依「$havedash」決定是否包含Dash
     * @param type $havedash 是否包含Dash
     * @return type GUID字串
     */
    private static function CreateGUID($havedash) {

        if ($havedash) {
            $formatstring = '%04x%04x-%04x-%04x-%04x-%04x%04x%04x';
        } else {
            $formatstring = '%04x%04x%04x%04x%04x%04x%04x%04x';
        }

        return sprintf($formatstring,
                // 32 bits for "time_low"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                // 16 bits for "time_mid"
                mt_rand(0, 0xffff),
                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand(0, 0x0fff) | 0x4000,
                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand(0, 0x3fff) | 0x8000,
                // 48 bits for "node"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public static function ShortGUIDWithoutDash($length) {
        $random = '';
        for ($i = 0; $i < $length; $i++) {
             $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
        }
        return $random;
    }

    /**
     * 將Json格式的字串 轉換為 PHP Array
     * @param type $inputstring WebAPI接收到的「JSON」格式字串
     * @return type PHP陣列
     */
    public static function ConvertStringToArray($inputstring) {
        try {
            $input = $inputstring;

            if (is_array($input)) {
                $inputjson = json_decode($input[0], true);
            } else {
                $inputjson = json_decode($input, true);
            }

            return $inputjson;
        } catch (\Exception $e) {

            \App\Models\ErrorLog::InsertData($e);
            return null;
        }
    }

    /**
     * 檢查「keyname」是否存在於「arraydata」中，並檢查是否有填值
     * @param type $arraydata
     * @param type $keyname
     * @return boolean
     */
    public static function CheckArrayValue($arraydata, $keyname) {
        try {
            /*
              if(!array_key_exists($keyname, $arraydata)) {
              return 1;
              }
              if( ($arraydata[$keyname] === null)){
              return 2;
              }
              if(strlen($arraydata[$keyname]) === 0){
              return 3;
              }
             */
            if (
                    !array_key_exists($keyname, $arraydata) || is_null($arraydata[$keyname]) || mb_strlen($arraydata[$keyname]) == 0
            ) {
                return false;
            }

            return true;
        } catch (\Exception $e) {

            \App\Models\ErrorLog::InsertData($e);

            return false;
        }
    }

    /**
     * 檢查「$keyname」是否存在於「$arraydata」中，並檢查其他條件
     * @param type $arraydata   要檢查的陣列
     * @param type $keyname    要檢查的參數名稱
     * @param type $maxlength 最大長度限制，若輸入「0」則為不限制
     * @param type $canempty 是否可為「空值」
     * @param type $canspace 是否可包含「空白」
     * @return boolean 是否符合條件
     */
    public static function CheckRequestArrayValue($arraydata, $keyname, $maxlength, $canempty, $canspace) {
        try {

            if (array_key_exists($keyname, $arraydata)) {
                $QQ = $arraydata[$keyname];
                if (is_array($QQ)) {
                    $QQ = implode(" ", $QQ);
                }
            } else {

                $QQ = null;
            }

            if ((!array_key_exists($keyname, $arraydata) || ( mb_strlen($QQ) == 0)) && $canempty) {
                return true;
            }
            if (!array_key_exists($keyname, $arraydata) || ( mb_strlen($QQ) == 0)) {
                //不存在
                return false;
            }

            if ($maxlength != 0 && mb_strlen($QQ) > $maxlength) {
                //長度太長
                return false;
            }
            if (!$canspace) {
                //檢查是否可包含空白
                if (preg_match('/\s/', $QQ) === 1) {
                    return false;
                }
            }
            return true;
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }


    /**
     * 檢查「店家」、「管理員」權限
     * @param type $sd_id
     * @param type $md_id
     * @param type $messageCode
     * @return boolean
     */
    public static function CheckShopUserIdentity($sat, $sd_id, $md_id, &$shopdata, &$messageCode) {

        try {

            if (!Commontools::Check_ShopData($sd_id, $shopdata, $messageCode)) {
                return false;
            }

            return Commontools::Check_UserIdentity($sat, $sd_id, $md_id, $messageCode);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            $messageCode = '999999999';
            return false;
        }
    }

    /**
     * 檢查店家資料
     * @param type $sd_id
     * @param type $messageCode
     * @return boolean
     */
    private static function Check_ShopData($sd_id, &$shopdata, &$messageCode) {
        $shopdata = \App\Models\ICR_ShopData::GetData($sd_id);

        if (count($shopdata) == 0) {
            //010901002	無此商家編號，請重新輸入
            $messageCode = '010901002';
            return false;
        }
        $querydata = \App\Models\ICR_SdmdBind::GetData_BySD_ID($sd_id, false);
        if (count($querydata) == 0) {
            //010902001	該商家記錄未有有效管理者，請確認管理效期是否仍有效
            $messageCode = '010902001';
            return false;
        }
        return true;
    }

    /**
     * 檢查使用者是否有管理店家權限
     * @param type $sd_id
     * @param type $md_id
     * @param type $messageCode
     * @return boolean
     */
    private static function Check_UserIdentity($sat, $sd_id, $md_id, &$messageCode) {
        $querydata = \App\Models\ICR_SdmdBind::GetData_By_SDID_MDID($sd_id, $md_id, false);
        $memService = new \App\Services\MemberService;
        if (count($querydata) == 0) {
            $querydata = \App\Models\ICR_SdmdBind::GetData_ByMd_ID($md_id, true);
            if (count($querydata) == 0) {
                //取消管理員資格
                Commontools::Cancel_UserManager($md_id);
                $memService->modify_member_clienttype('', $sat, 0, $messageCode);
            }
            //010902002	會員非本商家管理者，請確認後再試
            $messageCode = '010902002';
            return false;
        }
        //有值
        $have_validity = false;
        foreach ($querydata as $rowdata) {
            if (strtotime('now') > strtotime($rowdata['smb_validity'])) {
                //更新記錄 為 「２：逾期」
                Commontools::Set_sdmdbind_Expired($rowdata['smb_serno']);
            } else {
                $have_validity = true;
            }
        }
        if ($have_validity) {
            return true;
        }
        //010902003	會員之管理權限已失效，請確認後再試
        $messageCode = '010902003';
        return false;
    }

    /**
     * 取消 使用者 管理員權限
     * @param type $md_id
     */
    private static function Cancel_UserManager($md_id) {
        $modifydata['md_id'] = $md_id;
        $modifydata['md_clienttype'] = 0;

        \App\Models\IsCarMemberData::UpdateData($modifydata);
    }

    /**
     * 設定 對應資料 為 已過期
     * @param type $smb_serno
     */
    private static function Set_sdmdbind_Expired($smb_serno) {
        $modifydata['smb_serno'] = $smb_serno;
        $modifydata['smb_activestatus'] = 2;

        \App\Models\ICR_SdmdBind::UpdateData($modifydata);
    }



    /**
     * 從「圖片陣列」中取得尺寸「$size」的「圖片路徑」
     * @param type $imgarray 圖片陣列
     * @param type $size 要取得的圖片尺寸。〔XS、S、M、L、XL〕
     * @return string 圖片路徑
     */
    public static function GetOneImageFromImageArray($imgarray, $size) {
        try {
            //字串由「;」分割成陣列
            $imagearray = explode(';', $imgarray);
            $search_text = '_' . $size;

            $pic_m = array_filter($imagearray, function($el) use ($search_text) {
                return ( strpos($el, $search_text) !== false );
            });

            $pickey = array_keys($pic_m);
            if (is_null($pickey) || count($pickey) == 0) {
                $picture = $imagearray[0];
            } else {
                $picture = $pic_m[$pickey[0]];
            }

            return $picture;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return '';
        }
    }


    /**
     * 檢查業務登入資料
     * @param type $sar_id
     * @param type $login_pass
     * @return boolean
     */
    public static function Check_SaleLogin($sar_id, $login_pass, &$messageCode) {
        try {

            $querydata = \App\Models\ICR_ServiceAgentRecord::GetData($sar_id);

            if ($querydata == null || count($querydata) == 0) {
                //030101003	無此帳號，請重新登入
                $messageCode = '030101003';
                return false;
            }
            if (count($querydata) > 1) {
                //030101002	使用者註冊記錄大於一筆，無法辨識身份，請聯絡系統管理者進行確認
                $messageCode = '030101002';
                return false;
            }
            if ($querydata[0]['sar_loginerror'] > 4) {
                //030101004	密碼錯誤超過五次，登入鎖定，請聯絡通路管理者重置密碼
                $messageCode = '030101004';
                return false;
            }
            if ($querydata[0]['sar_activestatus'] == 3) {
                //030101005	帳號已停用，請聯絡通路管理者確認
                $messageCode = '030101005';
                return false;
            }
            if ($querydata[0]['sar_activestatus'] != 2) {
                //030101007	帳號未放行，請聯絡管理者確認
                $messageCode = '030101007';
                return false;
            }
            //檢查密碼
            $origenalstring = $querydata[0]['sar_account'] . $querydata[0]['sar_password'];
            $hashstring = base64_encode(hash('sha256', $origenalstring, True));
            //\App\Models\ErrorLog::InsertLog($hashstring);
            if ($hashstring != $login_pass) {
                //030101006	密碼錯誤，請重新登入
                $messageCode = '030101006';
                Commontools::Update_LoginError($querydata[0]['sar_id'], $querydata[0]['sar_loginerror'] + 1);
                $resultdata['login_countdown'] = \App\Models\ICR_ServiceAgentRecord::Query_LoginCountDown($querydata[0]['sar_id']);

                return false;
            }

            // 登入成功
            Commontools::Update_LoginError($querydata[0]['sar_id'], 0);
            return true;
        } catch (Exception $ex) {

        }
    }

    /**
     * 更新登入失敗次數
     * @param type $sar_id
     * @param type $errorcount
     * @return type
     */
    private static function Update_LoginError($sar_id, $errorcount) {
        $savedata['sar_id'] = $sar_id;
        $savedata['sar_loginerror'] = $errorcount;

        return \App\Models\ICR_ServiceAgentRecord::UpdateData($savedata);
    }

    /**
     * 使用Google API 查詢〔地理編碼〕
     * @param type $address 地址
     * @param type $longitude 經度
     * @param type $latitude 緯度
     * @return boolean
     */
    public static function Query_GeoCodeByGoogle($address, &$longitude, &$latitude) {
        try {
            if (is_null($address) || strlen($address) == 0) {
                return false;
            }

            $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=" . urlencode($address) . "&sensor=false&language=zh-TW");
            $json = json_decode($json);

            if (is_null($json) || count($json) == 0) {
                return false;
            }

            if (strtolower($json->status) != 'ok') {
                return false;
            }

            $latitude  = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
            $longitude = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 使用Google API 查詢〔地址〕
     * @param type $longitude 經度
     * @param type $latitude 緯度
     * @param type $address 地址
     * @return boolean
     */
    public static function Query_Address_ByGoogle($longitude, $latitude, &$address) {
        try {
            if (is_null($longitude) || strlen($longitude) == 0 || is_null($latitude) || strlen($latitude) == 0) {
                return false;
            }

            $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?latlng=" . urlencode(trim($latitude)) . ',' . urlencode(trim($longitude)) . "&sensor=false&language=zh-TW");
            $json = json_decode($json);

            if (is_null($json) || count($json) == 0) {
                return false;
            }

            if (strtolower($json->status) != 'ok') {
                return false;
            }

            $address = $json->results[0]->formatted_address;

            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

     /**
     * 過濾二維陣列，刪除重複數據
     * @param type $array2D 二維陣列
     * @param type $stkeep 是否保留原一維主鍵  false則為[0],[1],[3](重複刪除會自動過號)
     * @param type $ndformat  是否保留原二維主鍵 false則為[0],[1],[3](重複刪除會自動過號)
     */
    public static function unique_arr($array2D,$stkeep=false,$ndformat=true) {
        //判斷保留一级數組鍵 (可以為非數字)
        if($stkeep) $stArr = array_keys($array2D);

        // 判斷是否保留二级組鍵 (所有二级組鍵必須相同)
        if($ndformat) $ndArr = array_keys(end($array2D));

        //降维,也可以用implode,將一维數组轉換為用逗號連接的字串
        foreach ($array2D as $v){
          $v = join(",",$v);
          $temp[] = $v;
        }

        //去掉重複的字串,也就是重複的一维數组
        $temp = array_unique($temp);

        //再將拆開的數组重新组装
        foreach ($temp as $k => $v) {
          if($stkeep) $k = $stArr[$k];
          if($ndformat) {
            $tempArr = explode(",",$v);
            foreach($tempArr as $ndkey => $ndval) $output[$k][$ndArr[$ndkey]] = $ndval;
          } else $output[$k] = explode(",",$v);
        }
        return $output;
    }

}

