<?php

namespace App\Http\Controllers\APIControllers\Shop;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

class ShopDataBind {

    function shopdatabind() {
        $functionName = 'shopdatabind';
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
            if (!ShopDataBind::CheckInput($inputData)) {
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
            //$sales_md_id = '2d6c526f244a4e298b1d6f40769afcd8';
            if (!$memService->verity_Sales($inputData['sls_sat'], $sales_md_id, $messageCode)) {
              throw new \Exception($messageCode);
            }
            //檢查業務登入
            /*if (!\App\Library\Commontools::Check_SaleLogin($inputData['sar_id'], $inputData['login_pass'], $messageCode)) {
                throw new \Exception($messageCode);
            }*/
            //檢查「店家資料」
            if (!ShopDataBind::CheckExist_ShopData($inputData['sd_id'], $messageCode)) {
                throw new \Exception($messageCode);
            }

            //檢查「會員資料」
            if (!ShopDataBind::CheckExist_MemberData($inputData['md_id'], $messageCode)) {
                throw new \Exception($messageCode);
            }
            //檢查消費項目
            if (!ShopDataBind::Check_Depositcostitemlist($inputData['dcil_id'], $availabledays, $smb_validity, $messageCode)) {
                throw new \Exception($messageCode);
            }
           \DB::beginTransaction();
            //檢查或建立對應資料
            if (!ShopDataBind::CreateOrUpdate_ICR_sdmdbind($inputData['sls_sat'] , $sales_md_id, $inputData['sd_id'], $inputData['md_id'], $inputData['dcil_id'], $availabledays, $smb_validity, $inputData['shop_type'])) {
                $messageCode = '999999999';
                throw new \Exception($messageCode);
            } else {
                //更新「iscarmemberdata」的〔md_clienttype〕為〈１〉
                //\App\Models\IsCarMemberData::UpdateData_ClientType($inputData['md_id'], '1');
                // $memService->modify_member_clienttype('',$sat, '1', $messageCode)
            }
            \DB::commit();
            $messageCode = '000000000';
            
        } catch (\Exception $e) {
            \DB::rollBack();
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sls_sat', 0, false, false)) {
            return false;
        }

       /* if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sar_id', 36, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'login_pass', 0, false, false)) {
            return false;
        }*/
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'md_id', 36, false, false)) {
            return false;
        }
       /* if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'validity_days', 20, false, false)) {
            return false;
        }*/
         if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'dcil_id', 32, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'shop_type', 2, false, false)) {
            return false;
        }

        return true;
    }

    /**
     * 檢查「店家資料」是否存在
     * @param type $sd_id
     * @param type $messageCode
     * @return boolean
     */
    function CheckExist_ShopData($sd_id, &$messageCode) {
        try {

            $querydata = \App\Models\ICR_ShopData::GetData($sd_id);

            if (count($querydata) == 0) {
                //030102001	商家代號有誤，請確認後重發
                $messageCode = '030102001';
                return false;
            }
            if (count($querydata) > 1) {
                //030102002	商家代號對應記錄大於一筆，請聯絡系統管理員進行處理
                $messageCode = '030102002';
                return false;
            }
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 檢查「會員資料」是否存在
     * @param type $md_id
     * @param type $messageCode
     * @return boolean
     */
    function CheckExist_MemberData($md_id, &$messageCode) {
        try {

            $querydata = \App\Models\IsCarMemberData::GetData($md_id);

            if (count($querydata) == 0) {
                //030102003	會員代號有誤，請確認後重發
                $messageCode = '030102003';
                return false;
            }
            if (count($querydata) > 1) {
                //030102004	會員代號對應記錄大於一筆，請聯絡系統管理員進行處理
                $messageCode = '030102004';
                return false;
            }

            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }


    function Check_Depositcostitemlist($dcil_id, &$availabledays, &$smb_validity, &$messageCode) {
        try {
           $querydata = \App\Models\ICR_DepositCostItemList::GetDataByDCILID($dcil_id);
           
           if (count($querydata) == 0) {
              $messageCode = '030102005';
              return false;
           }
           if (count($querydata) > 1) {
              $messageCode = '030102006';
              return false;
           }
           $datenow = new \Datetime();
           $availabledays = $querydata[0]['dcil_availabledays'];
           $smb_validity =  $datenow-> modify("+$availabledays day") -> format('Y-m-d H:i:s');
           return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }  
    }

    /**
     * 建立或更新對應資料
     * @param type $sar_id
     * @param type $sd_id
     * @param type $md_id
     * @param type $validity_days
     * @return boolean
     */
    function CreateOrUpdate_ICR_sdmdbind($sat, $sales_md_id, $sd_id, $md_id, $dcil_id, $availabledays, $validity_days, $shop_type) {
        try {
            $querydata = \App\Models\ICR_SdmdBind::GetData_By_SDID_MDID($sd_id, $md_id, true);
            $dbir_id = \App\Library\Commontools::NewGUIDWithoutDash();
            $memService = new \App\Services\MemberService;
            if (count($querydata) == 0) {
                //新增 icr_sdmdbind
               if(ShopDataBind::Create_ICR_SdMdBing($sales_md_id, $sd_id, $md_id, $validity_days, $shop_type, $releation_id, $smb_serno) 
                 /* ShopDataBind::Create_ICR_AgentVisitRecord ($releation_id ,$sales_md_id, $sd_id, $md_id, $dbir_id, $smb_serno) && */ 
                  && ShopDataBind::InsertDepositBuyItmEreData($dbir_id, $dcil_id, $validity_days, $md_id, $sd_id) 
                  && \App\Models\IsCarMemberData::UpdateData_ClientType($md_id, $shop_type) 
                  && $memService->modify_member_clienttype($md_id, $sat, $shop_type, $ms) ) {
                    return true;
                }
            }
            /*if (count($querydata) == 1) {
                //更新
                return ShopDataBind::Update_ICR_SdMdBing($querydata[0]['smb_serno'], $sar_id, $validity_days);
            } */
            if (count($querydata) >= 1) {
                //設定 isflag = '0' ，新增 icr_sdmdbind
                ShopDataBind::Update_ICR_SdMdBing_DELETE($querydata);
                if(ShopDataBind::Create_ICR_SdMdBing($sales_md_id, $sd_id, $md_id, $validity_days, $shop_type, $releation_id, $smb_serno) 
                  /*ShopDataBind::Create_ICR_AgentVisitRecord ($releation_id ,$sales_md_id, $sd_id, $md_id, $dbir_id, $smb_serno) && */ 
                  && ShopDataBind::InsertDepositBuyItmEreData($dbir_id, $dcil_id, $validity_days, $md_id, $sd_id) 
                  && \App\Models\IsCarMemberData::UpdateData_ClientType($md_id, $shop_type) 
                  && $memService->modify_member_clienttype($md_id, $sat, $shop_type, $ms) ) {
                    return true;
                }
            }
            return false;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    private function Create_ICR_SdMdBing($sales_md_id, $sd_id, $md_id, $validity_days, $shop_type, &$releation_id, &$smb_serno) {

        try {
            $releation_id = $sales_md_id;//\App\Library\Commontools::NewGUIDWithoutDash();
            $modifydata['smb_sd_id'] = $sd_id;
            $modifydata['smb_md_id'] = $md_id;

            $date = new \DateTime('now');
            $modifydata['smb_validity'] = $validity_days;
            $modifydata['smb_activestatus'] = 1; //0: 未生效 1:有效 2:逾期 3:停用
            $modifydata['smb_sar_id'] = $sales_md_id;
            $modifydata['smb_activestatus'] = 1;
            $modifydata['smb_releation_id'] = $releation_id;
            $modifydata['smb_bindway'] = 0;
            $modifydata['smb_bindlevel'] = 0;
            $modifydata['smb_shoptype'] = $shop_type;
            return \App\Models\ICR_SdmdBind::InsertData($modifydata, $smb_serno);
        } catch (Exception $ex) {-
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    private function Update_ICR_SdMdBing($smb_serno, $sales_md_id, $validity_days) {

        try {
           
            $modifydata['smb_serno'] = $smb_serno;
            $date = new \DateTime('now');
            $modifydata['smb_validity'] = $date->add(new \DateInterval('P' . $validity_days . 'D'))->format('Y-m-d');
            $modifydata['smb_activestatus'] = 1; //0: 未生效 1:有效 2:逾期 3:停用
            $modifydata['smb_sar_id'] = $sales_md_id;

            return \App\Models\ICR_SdmdBind::UpdateData($modifydata);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    private function Update_ICR_SdMdBing_DELETE($binddata) {

        try {
            foreach ($binddata as $rowdata) {

                $modifydata['smb_serno'] = $rowdata['smb_serno'];
                $modifydata['isflag'] = 0;

                \App\Models\ICR_SdmdBind::UpdateData($modifydata);
            }

            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }
    
   /* private function Create_ICR_AgentVisitRecord ($avr_id ,$sales_md_id, $sd_id, $md_id, &$dbir_id, $smb_serno) {
        try {
            $dbir_id = \App\Library\Commontools::NewGUIDWithoutDash();
            $savadata = [
                          'avr_id'    => $avr_id,
                          'sar_id'    => $sales_md_id,
                          'sd_id'     => $sd_id,
                          'md_id'     => $md_id,
                          'dbir_id'   => $dbir_id,
                          'smb_serno' => $smb_serno
                        ];
            return \App\Models\ICR_AgentVisitRecord::InsertData($savadata);
        } catch(Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }*/
    
    /**
     * 新增車子基本資料
     * @param type $md_id
     * @param type $inpdata
     * @param type $cosdata
     * @return boolean 
     */
    private function InsertDepositBuyItmEreData($dbir_id, $dcil_id, $dbir_expiredate, $md_id, $sd_id) { 
       try {
           $insertdata = [
                          'dbir_id'           => $dbir_id,
                          'md_id'             => $md_id,
                          'dbir_object_id'    => $sd_id,
                          'dcil_id'           => $dcil_id,
                          'dbir_activatedate' => new \Datetime(),
                          'dbir_expiredate'   => $dbir_expiredate,
                          'dbir_object_type'  => '1'
                         ];
           return \App\Models\ICR_DepositBuyItmErec::InsertData($insertdata);
       } catch(\Exception $e) {
           \App\Models\ErrorLog::InsertData($e);
           return false;
       }
    }

}
