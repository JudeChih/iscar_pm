<?php
// 阿志做的
namespace App\Http\Controllers\APIControllers\CarNews  ;
use Request;
use App\Library\Commontools;
use DB;

/** createshopdata	新增特約商資料 * */
class CreateShopData {

    function createshopdata() {
        $functionName = 'createshopdata';
        $inputString = Request::all();

        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        // \App\Models\ErrorLog::InsertLog();
        try {
            // 轉換成陣列並檢查
            $inputData = $this->convertAndCheckApiInput($inputString);
            if (!is_array($inputData)) {
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
            $savedata['modacc'] = $inputData['modacc'];
            $savedata['modvrf'] = $inputData['modvrf'];
            $shopdataarray = $inputData['shopdata'];
            DB::beginTransaction();
            //建立特約商資料，並回傳PK值
            if(!$tagIdArray = $this->insertShopData($shopdataarray)){
                DB::rollback();
                // 特約商新增失敗
                $messageCode = '020100001';
                throw new \Exception($messageCode);
            }
            //透過特約商的PK以及sd_type建立商家服務類別與問卷問題連結
            if(!$this->insertShopTag($tagIdArray)){
                DB::rollback();
                // 建立商家服務類別與問卷問題連結失敗
                $messageCode = '020100002';
                throw new \Exception($messageCode);
            }
            DB::commit();

            $messageCode = '000000000';
        } catch (\Exception $e) {
            if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($e);
            }
        }
        //回傳值
        $resultArray = Commontools::ResultProcess($messageCode, $resultData);
        Commontools::WriteExecuteLog($functionName, json_encode($savedata), json_encode($resultArray), $messageCode);
        $result = [$functionName . 'result' => $resultArray];
        return $result;
    }

    /**
     * 檢查輸入值是否正確
     * @param type $value
     * @return boolean
     */
    public function convertAndCheckApiInput($inputString) {
        $inputData = \App\Library\Commontools::ConvertStringToArray($inputString);

        if ($inputData == null) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($inputData, 'modacc', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($inputData, 'modvrf', 0, false, false)) {
            return false;
        }
        return $inputData;
    }

    /**
     * 新增特約商
     * @param  [array] $arraydata [特約商資料陣列]
     */
    public function insertShopData($arraydata){
        $sd_r = new \App\Repositories\ICR_ShopDataRepository;
        try {
            DB::beginTransaction();
            foreach($arraydata as $data){
                if(!empty($data)){
                    $savedata['sd_type'] = $data['sd_type'];
                    $savedata['sd_shopname'] = $data['sd_shopname'];
                    $savedata['sd_shoptel'] = $data['sd_shoptel'];
                    $savedata['sd_zipcode'] = $data['sd_zipcode'];
                    $savedata['rl_city_code'] = $this->getCityCode($data['sd_zipcode']);
                    $savedata['sd_shopaddress'] = $data['sd_shopaddress'];
                    $savedata['sd_lat'] = $data['sd_lat'];
                    $savedata['sd_lng'] = $data['sd_lng'];
                    $savedata['sd_weeklystart'] = $data['sd_weeklystart'];
                    $savedata['sd_weeklyend'] = $data['sd_weeklyend'];
                    $savedata['sd_dailystart'] = $data['sd_dailystart'];
                    $savedata['sd_dailyend'] = $data['sd_dailyend'];
                    $savedata['sd_shopphotopath'] = $data['sd_shopphotopath'];
                    $savedata['sd_introtext'] = $data['sd_introtext'];
                    $savedata['sd_contact_person'] = $data['sd_contact_person'];
                    $savedata['sd_contact_tel'] = $data['sd_contact_tel'];
                    $savedata['sd_contact_mobile'] = $data['sd_contact_mobile'];
                    $savedata['sd_contact_address'] = $data['sd_contact_address'];
                    $savedata['sd_contact_email'] = $data['sd_contact_email'];
                    $savedata['sd_id'] = \App\Library\Commontools::NewGUIDWithoutDash();
                    if(!$sd_r->InsertData($savedata)){
                        DB::rollback();
                        return false;
                    }
                    $tagdata['sd_id'] = $savedata['sd_id'];
                    $tagdata['sd_type'] = $savedata['sd_type'];
                    $tagIdArray[] =$tagdata;
                }
            }
            DB::commit();
            return $tagIdArray;
        } catch (Exception $e) {
            DB::rollback();
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }

    /**
     * 新增商家服務類別與問卷問題連結
     * @param  [array] $arraydata [商家資料陣列]
     */
    public function insertShopTag($arraydata){
        try {
            DB::beginTransaction();
            foreach($arraydata as $data){
                $savedata['stx_tag_type'] = 0;
                $savedata['stx_tag_id'] = 'stg'.$data['sd_type'];
                $savedata['stx_sd_id'] = $data['sd_id'];
                if(!\App\Models\ICR_ShopTag_xref::InsertData($savedata)){
                    DB::rollback();
                    return false;
                }
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }

    /**
     * 透過郵寄區號取得所在縣市號碼
     * @param  [string] $sd_zipcode [郵寄區號]
     */
    public function getCityCode($sd_zipcode){
        try {
            $rl_r = new \App\Repositories\IsCarRegionListRepository;
            $rldata = $rl_r->getCityCode($sd_zipcode);
            if(count($rldata) == 1){
                $rldata = $rldata[0];
                return $rldata['rl_city_code'];
            }else{
                // 預設給0
                return 0;
            }
        } catch (Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }
}
