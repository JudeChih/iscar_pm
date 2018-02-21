<?php

namespace App\Http\Controllers\APIControllers\ShopSettleMentrec;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;
/** query_shopsettlementrec_m_list	查詢「商家銷售結算主表清單」列表 * */
class Query_ShopSettleMentrec_M_List {
   function query_shopsettlementrec_m_list() {
        $functionName = 'query_shopsettlementrec_m_list';
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
            if (!Commontools::CheckShopUserIdentity( $inputData['sat'], $inputData['sd_id'], $md_id, $shopdata , $messageCode)) {
                throw new \Exception($messageCode);
            }
            $resultData['shopsettlementrec_m_list'] = $this->createResultData($inputData['sd_id']);
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
       if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'modacc', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'modvrf', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sat', 0, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }
        
        return true;
    }
    
    
    
    
    function createResultData($sd_id) {
        try {
            $ssrmRepo = new \App\Repositories\ICR_ShopSettleMentrec_mRepository;
            $ssrmData = $ssrmRepo -> GetDataBySdId($sd_id);
            $SsrmData = array();
            foreach($ssrmData as $rowData) {
                $SsrmData[] = ['sd_id' =>$rowData->sd_id,
                                         'ssrm_id'=>$rowData->ssrm_id,
                                         'ssrm_settledate' =>$rowData->ssrm_settledate,
                                         'ssrm_billingcycle_start'=>$rowData->ssrm_billingcycle_start,
                                         'ssrm_billingcycle_end'=>$rowData->ssrm_billingcycle_end,
                                         'ssrm_billpaymentday'=>$rowData->ssrm_billpaymentday,
                                         'ssrm_actualbillpaymentday'=>$rowData->ssrm_actualbillpaymentday,
                                         'ssrm_totaltransatctioncount'=>$rowData->ssrm_totaltransatctioncount,
                                         'ssrm_salebypp'=>$rowData->ssrm_salebypp,
                                         'salecount'=>$rowData->salecount,
                                         'saleamount'=>$rowData->saleamount,
                                         'ssrm_totalppconsume'=>$rowData->ssrm_totalppconsume,
                                         'ssrm_totaliscarplatformfee'=>$rowData->ssrm_totaliscarplatformfee,
                                         'ssrm_totalpaymentflowfee'=>$rowData->ssrm_totalpaymentflowfee,
                                         'ssrm_settlementpayamount'=>$rowData->ssrm_settlementpayamount,
                                         'ssrm_settlementcomplete'=>$rowData->ssrm_settlementcomplete,
                                         'ssrm_settlementreview'=>$rowData->ssrm_settlementreview,
                                         'ssrm_settlementpayment'=>$rowData->ssrm_settlementpayment,
                                         'settlementreview_deadine'=>$rowData->settlementreview_deadine];
            }
            foreach($SsrmData as $row) {
               if( $row['ssrm_settlementpayment'] != 0) {
                   switch ($row['ssrm_settlementpayment'] ) {
                       case 1:
                          $row['settlement_status'] = '已出款、調帳中，暫不出款';
                           break;
                       default:
                          break;
                  }
                } else if( $row['ssrm_settlementreview'] != 0){
                    switch ($row['ssrm_settlementreview']) {
                      case 1:
                          $row['settlement_status'] = '已覆核';
                          break;
                      case 2:
                          $row['settlement_status'] = '帳務有誤';
                          break;
                      case 3:
                          $row['settlement_status'] = '已覆核﹙系統﹚';
                          break;
                      default:
                          break;
                  }
              } /*else {
                   switch ($row['ssrm_settlementcomplete'] ) {
                      case 0:
                          $row['settlement_status'] = '未完成';
                          break;
                       case 1:
                          $row['settlement_status'] = '完成';
                          break;
                       default:
                          break;
                  }
             } */   
            }
            
           return $SsrmData;
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
        }
        
    }
    
    
    
}