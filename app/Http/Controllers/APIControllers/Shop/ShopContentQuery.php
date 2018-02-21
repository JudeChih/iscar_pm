<?php

namespace App\Http\Controllers\APIControllers\Shop;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class ShopContentQuery {

    // shopcontentquery	取用商家內容
    function shopcontentquery() {
        $functionName = 'shopcontentquery';
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

            if (!ShopContentQuery::CheckInput($inputData)) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
              //檢查身份模組驗證
            $memService = new \App\Services\MemberService;
            if ( !$memService->checkModuleAccount($inputData['modacc'], $inputData['modvrf'], $messageCode)) {
              //模組身份驗證失敗
              $messageCode = '999999961';
              throw new \Exception($messageCode);
            }
            $resultData = ShopContentQuery::GetShopData($inputData['sd_id'], $messageCode);
            if (is_null($messageCode) && strlen($messageCode) == 0) {
                $messageCode = '000000000';
            }
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 0, false, false)) {
            return false;
        }

        return true;
    }

    /**
     * 取得店家資料
     * @param type $sd_id
     * @param type $messageCode
     * @return type
     */
    function GetShopData($sd_id, &$messageCode) {
        try {
            $querydata = \App\Models\ICR_ShopData::GetData($sd_id);

            if (count($querydata) == 0) {
                //010901002	無此商家編號，請重新輸入
                $messageCode = '010901002';
                return null;
            }

            return ShopContentQuery::CreateResultData($querydata[0]);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    public function checkShopBind($sd_id) {
      try {
              $queryData = \App\Models\ICR_SdmdBind::GetData_BySD_ID($sd_id, true);
              if ( count($queryData) > 0 ) {
                return 1;
              } else {
                return 0;
              }
      } catch(\Exception $ex) {
         \App\Models\ErrorLog::InsertData($ex);
          return null;
      }
   }

    /**
     * 建立回傳資料格式
     * @param type $shopdata
     * @return type
     */
    private function CreateResultData($shopdata) {

        if (count($shopdata) == 0) {
            return null;
        }

        $result['sd_shopname'] = $shopdata['sd_shopname'];
        $result['sd_shoptel'] = $shopdata['sd_shoptel'];
        $result['sd_zipcode'] = $shopdata['sd_zipcode'];
        $result['sd_shopaddress'] = $shopdata['sd_shopaddress'];
        $result['sd_lat'] = $shopdata['sd_lat'];
        $result['sd_lng'] = $shopdata['sd_lng'];
        $result['sd_weeklystart'] = $shopdata['sd_weeklystart'];
        $result['sd_weeklyend'] = $shopdata['sd_weeklyend'];
        $result['sd_dailystart'] = $shopdata['sd_dailystart'];
        $result['sd_dailyend'] = $shopdata['sd_dailyend'];
        $result['sd_shopphotopath'] = $shopdata['sd_shopphotopath'];
        $result['sd_introtext'] = $shopdata['sd_introtext'];

        $result['shop_layout'] = null;
        $result['sd_contact_person'] = $shopdata['sd_contact_person'];
        $result['sd_contact_tel'] = $shopdata['sd_contact_tel'];
        $result['sd_contact_mobile'] = $shopdata['sd_contact_mobile'];
        $result['sd_contact_address'] = $shopdata['sd_contact_address'];
        $result['sd_contact_email'] = $shopdata['sd_contact_email'];
        $result['sd_advancedata'] = $shopdata['sd_advancedata'];
        $result['sd_questiontotalaverage'] = $shopdata['sd_questiontotalaverage'];
       
        $result['sd_paymentflow'] = $shopdata['sd_paymentflow'];
        $result['sd_paymentflowdata'] = $shopdata['sd_paymentflowdata'];
        $result['sd_havebind'] = $shopdata['sd_havebind'];
        $result['sd_uniformnumbers'] = $shopdata['sd_uniformnumbers'];


        return $result;
    }

}
