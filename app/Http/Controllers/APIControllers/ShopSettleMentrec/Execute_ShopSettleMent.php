<?php

namespace App\Http\Controllers\APIControllers\ShopSettleMentrec;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** execute_shopsettlement	執行店家結算功能 * */
class Execute_ShopSettleMent {
   function execute_shopsettlement() {
        $functionName = 'execute_shopsettlement';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            if($inputData == null){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //輸入值
            if(!$this->CheckInput($inputData)){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //檢查身份模組驗證
          /*  $memService = new \App\Services\MemberService;
            if ( !$memService->checkModuleAccount($inputData['modacc'], $inputData['modvrf'], $messageCode)) {
              //模組身份驗證失敗
              $messageCode = '999999961';
              throw new \Exception($messageCode);
            }
            if (!$memService->checkServiceAccessToken($inputData['sat'], $md_id, $messageCode)) {
              //呼叫「MemberAPI」檢查SAT的狀態，驗證SAT有效性
               //$messageCode = '999999962';
               throw new \Exception($messageCode);
            }*/
            
            
            if(!$this->checkSettle_Day($inputData['settle_day'], $settle_start_day, $settle_end_day, $billpaymentday)) {
                 $messageCode = '011801001';
                  throw new \Exception($messageCode);
            }
            $shopdata = \App\Models\ICR_ShopData::getAllShopDataForSettleMent ($inputData['settle_day']);
            
            if (!$this-> createSettlement($shopdata, $inputData['settle_day'], $settle_start_day, $settle_end_day, $billpaymentday)) {
                throw new \Exception($messageCode);
            }
            
            $messageCode = '000000000';
         } catch(\Exception $e){
           if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($e);
            }
        }
         $resultArray = Commontools::ResultProcess($messageCode, $resultData);
         Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
         $result = [$functionName . 'result' => $resultArray];
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
       /*if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'modacc', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'modvrf', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sat', 0, false, false)) {
            return false;
        }*/
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'settle_day', 0, false, false)) {
            return false;
        }
        return true;
    }
    
    
    
    function checkSettle_Day($settle_day, &$settle_start_day, &$settle_end_day,&$billpaymentday) {
        try {
            $day = date("d",strtotime("$settle_day"));
            if ( $day != 1 && $day != 11 && $day != 21 ) {
                return false;
            } else if ($day == 1) {
                $settle_start_day = date("Y-m-21 00:00:00",strtotime("$settle_day -1 months"));
                $settle_end_day = date("Y-m-t 23:59:59",strtotime("$settle_start_day"));
            } else if ($day == 11) {
                $settle_start_day = date("Y-m-01 00:00:00",strtotime("$settle_day"));
                $settle_end_day = date("Y-m-10 23:59:59",strtotime("$settle_day"));
            } else if ($day == 21)  {
                 $settle_start_day = date("Y-m-11 00:00:00",strtotime("$settle_day"));
                 $settle_end_day = date("Y-m-20 23:59:59",strtotime("$settle_day"));
            }
            $billpaymentday = date("Y-m-d 23:59:59",strtotime("$settle_day +7 day"));
            return true;
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
        } 
    }
    
    
    function createSettlement($shopData, $settle_day, $settle_start_day, $settle_end_day, $billpaymentday) {
        try{
            $ssrmRepo = new \App\Repositories\ICR_ShopSettleMentrec_mRepository;
            $ssrdRepo = new \App\Repositories\ICR_ShopSettleMentrec_dRepository;
            foreach($shopData as $row1Data) {
            
                $ssrm_id = null;
                $totalCount = 0; //這期關帳比數
                $totalAmount = 0;//未折扣的交易總金額
                $subtract_totalamount = 0; //折扣後總計金額
                $normalSaleCount = 0; //總紀一般消費總比數
                $normalSaleAmount = 0; //總紀一般消費總金額
                $ppCount = 0; //總紀使用特點總比數
                $ppAmount = 0; //消費的特點總點數額
                $gpAmount = 0;//消費的禮點點數額
                $gpCount = 0;//總紀使用禮點折抵總比數
                $gpChangeMoney = 0;//禮點折抵金額
                $totaliscarplatformfee = 0;//平台總金額
                $totalpaymentflowfee = 0;//金流總金額
                $save_m_data = array();
                $save_d_data = array();
                $bool_insertD = true;     
                $formfee =  \App\Models\ICR_SystemParameter::getFormfee();
                $flowfee =  \App\Models\ICR_SystemParameter::getFlowfee();
                
                $scgData = \App\Models\ICR_ShopCouponData_g::getNeedShopSettleMentDataBySdId($row1Data['sd_id'], $settle_start_day ,$settle_end_day);
                if (count($scgData) == 0 ) {
                    continue;
                } else {
                    if ( ! $this->InsertSSrmData($row1Data, $save_m_data, $settle_day, $settle_start_day, $settle_end_day, $billpaymentday, $ssrmRepo, $ssrm_id) ) {
                        continue;
                    }
                }
                foreach ($scgData as $row2Data) {
                    $billingtype = 0 ;//結帳方式 0:X 1:一般結帳 2:使用禮點折抵 3:使用特點兌換
                    
                    $totalCount ++;
                    $totalAmount += $row2Data['scg_totalamount'] ;
                    $totaliscarplatformfee += $row2Data['scg_totalamount'] * $formfee;
                    $totalpaymentflowfee += $row2Data['scg_totalamount'] * $flowfee;
                    $this->checkPayType($row2Data, $ppCount, $ppAmount, $gpAmount, $gpCount,
                            $gpChangeMoney, $subtract_totalamount, $normalSaleCount, $normalSaleAmount, $ssrd_billingtype);
                   $this->createNeedSaveSsrdData($save_d_data,$ssrm_id, $ssrd_billingtype, $row2Data, $formfee, $flowfee);
                    if (count($save_d_data) == 100 ) {
                        if (! $this->InsertSsrdData($save_d_data, $ssrdRepo)) {
                           $bool_insertD = false; 
                           break;
                        } else {
                            $save_d_data = array(null);
                            $bool_insertD = true;
                        }
                    }
                }
                if (! $this->InsertSsrdData($save_d_data, $ssrdRepo)) {
                     $bool_insertD = false;  
                }
                if ($bool_insertD == true) {
                   $this->UpdateSsrmData($ssrm_id, $totalCount, $normalSaleCount, $gpCount, $ppCount, $normalSaleAmount, $ppAmount,
                                                            $gpChangeMoney, $gpAmount, $totalAmount,$totaliscarplatformfee, $totalpaymentflowfee);
                } else {
                    $this->UpdateSsrm_Isflag($ssrm_id);
                    continue;
                }
               $this->UpdateScgData($scgData);
            }
            return true;
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
        }
    }
    
    public function InsertSSrmData($row1Data, $save_m_data, $settle_day, $settle_start_day, $settle_end_day, $billpaymentday, $ssrmRepo, &$ssrm_id) {
        try {
              $save_m_data =  [   'sd_id' => $row1Data['sd_id'],
                                               'ssrm_settledate' => substr($settle_day,0,10),
                                               'ssrm_execute_day' => date('Y-m-d'),
                                               'ssrm_billingcycle_start' => substr($settle_start_day,0 , 10),
                                               'ssrm_billingcycle_end' => substr($settle_end_day,0 , 10),
                                               'ssrm_billpaymentday' => substr($billpaymentday,0 , 10) ] ;
               if ( ! $ssrmRepo->InsertDataGetId($save_m_data, $ssrm_id) || is_null($ssrm_id) ) {
                    return false;
               }
               return true;
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
        }
    }
    
    public function checkPayType($row2Data, &$ppCount, &$ppAmount, &$gpAmount, &$gpCount, &$gpChangeMoney, &$subtract_totalamount, &$normalSaleCount, &$normalSaleAmount, &$ssrd_billingtype) {
         try {
              //特點兌換
              if ($row2Data['scm_coupon_providetype'] == 1) {
                  $ppCount ++;
                  $ssrd_billingtype = 3;
                  $ppAmount  +=$row2Data['scm_bonus_payamount'] ;
                  
               } /*禮點消費*/else if ( ! is_null($row2Data['scg_gp_subtract_amount']) &&$row2Data['scg_gp_subtract_amount'] != 0  ) {
                  $gpCount ++;
                  $ssrd_billingtype = 2;
                  $gpAmount +=$row2Data['scg_gp_subtract_amount'];
                  $gpChangeMoney += $row2Data['scg_subtract_price'];
                  $subtract_totalamount += $row2Data['scg_subtract_totalamount'];
                  $normalSaleAmount += $row2Data['scg_totalamount'] ;
                  
                }/* 一般消費*/ else {
                   $normalSaleCount ++;
                   $ssrd_billingtype = 1;
                   $normalSaleAmount += $row2Data['scg_totalamount'] ;
                   
                }
                return true;
         } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
        }
    }
    
    public function createNeedSaveSsrdData(&$save_d_data, $ssrm_id, $ssrd_billingtype, $row2Data, $formfee, $flowfee) {
        try {
            $save_d_data[] = [
                        'ssrm_id' => $ssrm_id,
                        'scg_id'  => $row2Data['scg_id'],
                        'ssrd_billingtype' => $ssrd_billingtype,
                        'ssrm_saleamountnodiscount' =>$row2Data['scg_totalamount'],
                        'ssrm_spconsume' =>$row2Data['scm_bonus_payamount'],
                        'ssrm_amountafterdiscount' => $row2Data['scg_subtract_totalamount'],
                        'ssrm_gpconsume' => $row2Data['scg_gp_subtract_amount'],
                        'ssrm_totalgpexchangetomoney' => $row2Data['scg_subtract_price'],
                        'ssrm_totaliscarplatformfee' =>$row2Data['scg_totalamount'] * $formfee,
                        'ssrm_totalpaymentflowfee' =>$row2Data['scg_totalamount'] * $flowfee,
                        'ssrd_salerecorder' => json_encode($row2Data) 
                    ];
            return true;
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
        }
    }
    
    public function InsertSsrdData($save_d_data, $ssrdRepo) {
        try {
            if (! $ssrdRepo->InsertData($save_d_data)) {
                return false;
            }
            return true;
         } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
        }
    }
    
    public function UpdateSsrmData($ssrm_id, $totalCount, $normalSaleCount, $gpCount, $ppCount, $normalSaleAmount, $ppAmount,
                                                            $gpChangeMoney, $gpAmount, $totalAmount,$totaliscarplatformfee, $totalpaymentflowfee) {
        try {
            $ssrmRepo = new \App\Repositories\ICR_ShopSettleMentrec_mRepository;
             $save_m_data = [
                        'ssrm_id' =>$ssrm_id ,
                        'ssrm_totaltransatctioncount' =>$totalCount,
                        'ssrm_salewithoutdiscount' => $normalSaleCount,
                        'ssrm_salewithgp' => $gpCount,
                        'ssrm_salebypp' =>$ppCount,
                        'ssrm_saleamountnodiscount' =>$normalSaleAmount,
                        'ssrm_totalppconsume' =>$ppAmount,
                        'ssrm_amountafterdiscount' =>$normalSaleAmount - $gpChangeMoney,
                        'ssrm_totalgpconsume' => $gpAmount,
                        'ssrm_totalgpexchangetomoney' =>$gpChangeMoney,
                        'ssrm_totaliscarplatformfee' =>$totalAmount * 0.1,
                        'ssrm_totalpaymentflowfee' =>$totalAmount * 0.025,
                        'ssrm_settlementpayamount' => $totalAmount - $totaliscarplatformfee -$totalpaymentflowfee,
                        'ssrm_settlementreview' => 0,
                        'ssrm_settlementpayment' => 0,
                        'ssrm_settlementcomplete' => 1
                    ];
                    $ssrmRepo->UpdateData($save_m_data);
            
        }  catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
        }
    }
    
    public function UpdateSsrm_Isflag($ssrm_id) {
         try {
             $ssrmRepo = new \App\Repositories\ICR_ShopSettleMentrec_mRepository;
             $save_m_data = [
                        'ssrm_id' =>$ssrm_id ,
                        'isflag' => 0
              ];
              $ssrmRepo->UpdateData($save_m_data);
              return true;
         }  catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
        }
    }
    
    public function UpdateScgData($scgData) {
        try {
            foreach($scgData as $row) {
                    $data = ['scg_id' =>$row['scg_id'], 'scg_settlement_type' => 1];
                    \App\Models\ICR_ShopCouponData_g:: UpdateData($data);
             }
             return true;
        }  catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
        } 
    }
    
}