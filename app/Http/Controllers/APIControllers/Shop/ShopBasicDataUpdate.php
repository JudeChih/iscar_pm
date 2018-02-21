<?php

namespace App\Http\Controllers\APIControllers\Shop;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class ShopBasicDataUpdate {

   function shopbasicdataupdate() {
        $functionName = 'shopbasicdataupdate';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;

        try {
            if ($inputData == null) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            //檢查輸入值
            if (!ShopBasicDataUpdate::CheckInput($inputData)) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            $md_id = null;
            //檢查身份模組驗證
            $memService = new \App\Services\MemberService;
            if ( !$memService->checkModuleAccount($inputData['modacc'], $inputData['modvrf'], $messageCode)) {
              //模組身份驗證失敗
              $messageCode = '999999961';
              throw new \Exception($messageCode);
            }
            if (!$memService->checkServiceAccessToken($inputData['sat'], $md_id, $messageCode)) {
              //呼叫「MemberAPI」檢查SAT的狀態，驗證SAT有效性
               //$messageCode = '999999962';
               throw new \Exception($messageCode);
            }
            //檢查「店家」、「管理員」權限
            if (!Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }
            //更新店家資料
            if (!ShopBasicDataUpdate::Update_ShopData($inputData, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }

            $messageCode = '000000000';
            //
        } catch (\Exception $e) {
            if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($e);
            }
        }

        //回傳值
        $resultArray = Commontools::ResultProcess($messageCode, $resultData);
        Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray];

        return $result;
    }


    /**
     * 檢查輸入值是否正確
     * @param type $value
     * @return boolean
     */
    function CheckInput(&$value) {
        if ($value == null) {
            return false;
        }

         if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'modacc', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'modvrf', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sat', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 36, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_shopname', 50, false, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_shoptel', 20, false, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_zipcode', 3, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_shopaddress', 100, false, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_weeklystart', 3, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_weeklyend', 3, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_dailystart', 20, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_dailyend', 20, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_shopphotopath', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_introtext', 150, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_contact_person', 20, false, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_contact_tel', 20, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_contact_mobile', 20, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_contact_address', 100, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_contact_email', 50, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'rl_city_code', 11, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_uniformnumbers', 0, true, false)) {
            return false;
        }
       /* if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_paymentflow', 1, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_paymentflowdata', 0, true, false)) {
            return false;
        }*/


        if (!array_key_exists('sd_zipcode', $value)) {
            $value['sd_zipcode'] = null;
        }
        if (!array_key_exists('sd_weeklystart', $value)) {
            $value['sd_weeklystart'] = null;
        }
        if (!array_key_exists('sd_weeklyend', $value)) {
            $value['sd_weeklyend'] = null;
        }
        if (!array_key_exists('sd_dailystart', $value)) {
            $value['sd_dailystart'] = null;
        }
        if (!array_key_exists('sd_dailyend', $value)) {
            $value['sd_dailyend'] = null;
        }
        if (!array_key_exists('sd_introtext', $value)) {
            $value['sd_introtext'] = null;
        }
        if (!array_key_exists('sd_contact_tel', $value)) {
            $value['sd_contact_tel'] = null;
        }
        if (!array_key_exists('sd_contact_mobile', $value)) {
            $value['sd_contact_mobile'] = null;
        }
        if (!array_key_exists('sd_contact_address', $value)) {
            $value['sd_contact_address'] = null;
        }
        if (!array_key_exists('sd_contact_email', $value)) {
            $value['sd_contact_email'] = null;
        }

        return true;
    }

    /**
     * 檢查「店家」、「管理員」權限
     * @param type $sd_id
     * @param type $md_id
     * @param type $messageCode
     * @return boolean
     */
    function Check_ShopUserIdentity($sd_id, $md_id, &$shopdata, &$messageCode) {
        try {

            if (!ShopBasicDataUpdate::Check_ShopData($sd_id, $shopdata, $messageCode)) {
                return false;
            }

            return ShopBasicDataUpdate::Check_UserIdentity($sat, $sd_id, $md_id, $messageCode);
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
    private function Check_ShopData($sd_id, &$shopdata, &$messageCode) {
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
    private function Check_UserIdentity($sat, $sd_id, $md_id, &$messageCode) {
        $querydata = \App\Models\ICR_SdmdBind::GetData_By_SDID_MDID($sd_id, $md_id, false);
        $memService = new \App\Services\MemberService;
        if (count($querydata) == 0) {
            $querydata = \App\Models\ICR_SdmdBind::GetData_ByMd_ID($md_id, true);
            if (count($querydata) == 0) {
                //取消管理員資格
                ShopBasicDataUpdate::Cancel_UserManager($md_id);
                $memService->modify_member_clienttype('',$sat, $md_clienttype, $messageCode);
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
                ShopBasicDataUpdate::Set_sdmdbind_Expired($rowdata['smb_serno']);
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
    private function Cancel_UserManager($md_id) {
        $modifydata['md_id'] = $md_id;
        $modifydata['md_clienttype'] = 0;

        \App\Models\IsCarMemberData::UpdateData($modifydata);
    }

    /**
     * 設定 對應資料 為 已過期
     * @param type $smb_serno
     */
    private function Set_sdmdbind_Expired($smb_serno) {
        $modifydata['smb_serno'] = $smb_serno;
        $modifydata['smb_activestatus'] = 2;

        \App\Models\ICR_SdmdBind::UpdateData($modifydata);
    }

    /**
     * 更新店家資料
     * @param type $inputData 輸入資料
     * @param type $shopdata 修改前店家資料
     * @param type $messageCode
     * @return boolean
     */
    public function Update_ShopData($inputData, $shopdata, &$messageCode) {
        if (count($shopdata) == 0) {
            //010901002	無此商家編號，請重新輸入
            $messageCode = '010901002';
            return false;
        }

        //$shopdata['sd_type'] = $inputData['QQQQQ'];
        $shopdata[0]['sd_shopname'] = $inputData['sd_shopname'];
        $shopdata[0]['sd_shoptel'] = $inputData['sd_shoptel'];
        $shopdata[0]['sd_zipcode'] = $inputData['sd_zipcode'];

        $shopdata[0]['sd_shopaddress'] = $inputData['sd_shopaddress'];
        $shopdata[0]['sd_lat'] = null;
        $shopdata[0]['sd_lng'] = null;
        if (!is_null($inputData['sd_shopaddress']) && strlen($inputData['sd_shopaddress']) != 0) {

            if (!\App\Library\Commontools::Query_GeoCodeByGoogle($inputData['sd_shopaddress'], $longitude, $latitude)) {
                //010902004	商家地址無法轉換有效經緯度坐標，請確認後重新輸入
                $messageCode = '010902004';
                return false;
            }
            $shopdata[0]['sd_lat'] = $latitude;
            $shopdata[0]['sd_lng'] = $longitude;
        }
        $shopdata[0]['sd_weeklystart'] = $inputData['sd_weeklystart'];
        $shopdata[0]['sd_weeklyend'] = $inputData['sd_weeklyend'];
        $shopdata[0]['sd_dailystart'] = $inputData['sd_dailystart'];
        $shopdata[0]['sd_dailyend'] = $inputData['sd_dailyend'];
        $shopdata[0]['sd_shopphotopath'] = $inputData['sd_shopphotopath'];
        $shopdata[0]['sd_introtext'] = $inputData['sd_introtext'];
        $shopdata[0]['sd_contact_person'] = $inputData['sd_contact_person'];
        $shopdata[0]['sd_contact_tel'] = $inputData['sd_contact_tel'];
        $shopdata[0]['sd_contact_mobile'] = $inputData['sd_contact_mobile'];
        $shopdata[0]['sd_contact_address'] = $inputData['sd_contact_address'];
        $shopdata[0]['sd_contact_email'] = $inputData['sd_contact_email'];
        $shopdata[0]['rl_city_code'] = $inputData['rl_city_code'];
        $shopdata[0]['sd_uniformnumbers'] = $inputData['sd_uniformnumbers'];
        //$shopdata[0]['sd_paymentflowdata'] = $inputData['sd_paymentflowdata'];

        if (!\App\Models\ICR_ShopData::UpdateData($shopdata[0])) {
            //999999999	未知錯誤失敗，請稍候再試
            $messageCode = '9999999991';
            return false;
        }
        return true;
    }

}
