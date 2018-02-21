<?php

namespace App\Http\Controllers\APIControllers;
use App\Http\Controllers\Controller;
class ShopController extends Controller {

    /**
     * shoplistquery	取用對應類別商家資料列表回覆
     */
    function shoplistquery() {
         $ShopListQuery = new Shop\ShopListQuery;
         return $ShopListQuery->shoplistquery();
    }

    /**
     * shopcontentquery	取用商家內容
     */
    function shopcontentquery() {
         $ShopContentQuery = new Shop\ShopContentQuery;
         return $ShopContentQuery->shopcontentquery();
    }

    /**
     * salesagentlogin	iscar業務註冊功能
     */
    function salesagentlogin() {
         $SalesAgentLogin = new Shop\SalesAgentLogin;
         return $SalesAgentLogin->salesagentlogin();
    }

    /**
     * shopdatabind	商家用戶認證功能
     */
    function shopdatabind() {
         $ShopDataBind = new Shop\ShopDataBind;
         return $ShopDataBind->shopdatabind();
    }

    /**
     * shopbasicdataupdate	商家資料修改功能
     */
    function shopbasicdataupdate() {
         $ShopBasicDataUpdate = new Shop\ShopBasicDataUpdate;
         return $ShopBasicDataUpdate->shopbasicdataupdate();
    }

    /**
     * shopadvanceupdate	更新商家進階功能內容
     * @return type
     * @throws \Exception
     */
    function shopadvanceupdate() {
         $ShopAdvanceUpdate = new Shop\ShopAdvanceUpdate;
         return $ShopAdvanceUpdate->shopadvanceupdate();  
    }

   /* function shopcouponlistquery() {
        $functionName = 'shopcouponlistquery';
        $inputString = \Input::All();
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
            if (!Shop\ShopCouponListQuery::CheckInput($inputData)) {
                $messageCode = '999999995';
                throw new \Exception($messageCode);
            }
            $querydata = Shop\ShopCouponListQuery::GetShopCouponData($inputData['sd_id'], $inputData['scm_category'], $inputData['sd_zipcode'], $inputData['queryamount'], $inputData['create_date'], $messageCode);

            if (!is_null($messageCode) && strlen($messageCode) != 0) {
                throw new \Exception($messageCode);
            }

            $messageCode = '000000000';
        } catch (\Exception $e) {
            if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\models\ErrorLog::InsertData($e);
            }
        }

        //回傳值
        $resultArray = Commontools::ResultProcess($messageCode, $resultData);
        Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray];

        return $result;
    } */
    
    /* query_shopmember  查詢商家所有會員資料 */
    function queryshopmember() {
         $QueryShopMember = new Shop\QueryShopMember;
         return $QueryShopMember->queryshopmember();
    }

}
