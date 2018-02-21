<?php

namespace App\Http\Controllers\APIControllers\ShopCoupon;
use Illuminate\Support\Facades\Input;
use App\Library\Commontools;

/** shopcouponmanager	商家優惠活動券管理 * */
class ShopCouponManager {

    function shopcouponmanager() {
        $functionName = 'shopcouponmanager';
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
            if (!ShopCouponManager::CheckInput($inputData)) {
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
            $inputData['md_id'] = $md_id;
            $shopData = \App\Models\ICR_ShopData::GetData($inputData['sd_id']);
            /*if ($shopData[0]['sd_paymentflow'] != 1 ) {
                //未啟用金流，無法使用該功能。
               $messageCode = '010907008';
               throw new \Exception($messageCode);
            }*/
            //檢查「店家」、「管理員」權限
            if (!\App\Library\Commontools::CheckShopUserIdentity($inputData['sat'], $inputData['sd_id'], $md_id, $shopdata, $messageCode)) {
                throw new \Exception($messageCode);
            }

            if (!ShopCouponManager::CheckAndModify_Data($inputData['managetype'], $inputData, $messageCode)) {
                throw new \Exception($messageCode);
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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sd_id', 32, false, false)) {
            return false;
        }

        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'managetype', 1, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'md_securitycode', 0, true, false)) {
            return false;
        }
       

//允許空白的欄位
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_id', 32, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_title', 40, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_fulldescript', 500, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_category', 1, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_mainpic', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_activepics', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_price', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_startdate', 11, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_enddate', 11, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_reservationtag', 1, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_dailystart', 9, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_dailyend', 9, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_workhour', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_preparehour', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_includeweekend', 1, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_reservationavailable', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_poststatus', 1, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_member_limit', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_balanceno', 50, true, false)) {
            return false;
        }


        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_coupon_usetype', 0, true, false)) {
            return false;
        }
        /*if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_bonus_modify_type', 0, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_bonus_amount', 0, true, false)) {
            return false;
        }*/
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_producttype', 1, true, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_advancedescribe', 0, true, true)) {
            return false;
        }
        
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_coupon_providetype', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_bonus_giveafteruse', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_bonus_giveamount', 0, true, true)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'scm_bonus_payamount', 0, true, true)) {
            return false;
        }


        //檢查不存在的則建立
        if (!array_key_exists('scm_id', $value)) {
            $value['scm_id'] = null;
        }
        if (!array_key_exists('scm_title', $value)) {
            $value['scm_title'] = null;
        }
        if (!array_key_exists('scm_fulldescript', $value)) {
            $value['scm_fulldescript'] = null;
        }
        if (!array_key_exists('scm_category', $value)) {
            $value['scm_category'] = null;
        }
        if (!array_key_exists('scm_mainpic', $value)) {
            $value['scm_mainpic'] = null;
        }
        if (!array_key_exists('scm_activepics', $value)) {
            $value['scm_activepics'] = null;
        }
        if (!array_key_exists('scm_price', $value)) {
            $value['scm_price'] = null;
        }
        if (!array_key_exists('scm_startdate', $value)) {
            $value['scm_startdate'] = null;
        }
        if (!array_key_exists('scm_enddate', $value)) {
            $value['scm_enddate'] = null;
        }
        if (!array_key_exists('scm_reservationtag', $value)) {
            $value['scm_reservationtag'] = null;
        }
        if (!array_key_exists('scm_dailystart', $value)) {
            $value['scm_dailystart'] = null;
        }
        if (!array_key_exists('scm_dailyend', $value)) {
            $value['scm_dailyend'] = null;
        }
        if (!array_key_exists('scm_workhour', $value)) {
            $value['scm_workhour'] = null;
        }
        if (!array_key_exists('scm_preparehour', $value)) {
            $value['scm_preparehour'] = null;
        }
        if (!array_key_exists('scm_includeweekend', $value)) {
            $value['scm_includeweekend'] = null;
        }
        if (!array_key_exists('scm_reservationavailable', $value)) {
            $value['scm_reservationavailable'] = null;
        }
        if (!array_key_exists('scm_poststatus', $value)) {
            $value['scm_poststatus'] = null;
        }
        if (!array_key_exists('scm_member_limit', $value)) {
            $value['scm_member_limit'] = null;
        }
        if (!array_key_exists('scm_balanceno', $value)) {
            $value['scm_balanceno'] = null;
        }

        if (!array_key_exists('scm_coupon_usetype', $value)) {
            $value['scm_coupon_usetype'] = 0;
        }
      /*  if (!array_key_exists('scm_bonus_modify_type', $value)) {
            $value['scm_bonus_modify_type'] = 0;
        }
        if (!array_key_exists('scm_bonus_amount', $value)) {
            $value['scm_bonus_amount'] = 0;
        }*/
        if (!array_key_exists('scm_producttype', $value)) {
            $value['scm_producttype'] = null;
        }
        if (!array_key_exists('scm_advancedescribe', $value)) {
            $value['scm_advancedescribe'] = null;
        }
        
        
         if (!array_key_exists('scm_coupon_providetype', $value)) {
            $value['scm_coupon_providetype'] = 0;
        }
        if (!array_key_exists('scm_bonus_giveafteruse', $value)) {
            $value['scm_bonus_giveafteruse'] = 0;
        }
        if (!array_key_exists('scm_bonus_giveamount', $value)) {
            $value['scm_bonus_giveamount'] = 0;
        }
        if (!array_key_exists('scm_bonus_payamount', $value)) {
            $value['scm_bonus_payamount'] = 0;
        }
        
        return true;
    }

    /**
     * 檢查並建立需異動的資料
     * @param type $managetype
     * @param type $value
     * @param type $modifydata
     * @param string $messageCode
     * @return boolean
     */
    private function CheckAndModify_Data($managetype, $value, &$messageCode) {
        try {
            if ($managetype == 0) {
                //０：新增優惠券
                if (!ShopCouponManager::Create_ModifyData_0($value, $modifydata, $messageCode)) {
                    return false;
                }
                return ShopCouponManager::ExecuteModifyData_0($modifydata, $messageCode);
            } elseif ($managetype == 1) {
                //１：變更該券刊登狀態
                if (!ShopCouponManager::Create_ModifyData_1($value, $modifydata, $messageCode)) {
                    return false;
                }
                return ShopCouponManager::ExecuteModifyData_1($modifydata, $messageCode);
            } else if ($managetype == 2) {
                //２：更新優惠券內容
                if (!ShopCouponManager::Create_ModifyData_2($value, $modifydata, $messageCode)) {
                    return false;
                }
                return ShopCouponManager::ExecuteModifyData_2($modifydata, $messageCode);
            } else if ($managetype == 3) {
                //３：更新優惠券內容含預約
                if (!ShopCouponManager::Create_ModifyData_3($value, $modifydata, $messageCode)) {
                    return false;
                }
                return ShopCouponManager::ExecuteModifyData_3($modifydata, $messageCode);
            }
            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    private function Create_ModifyData_0($value, &$modifydata, &$messageCode) {
        try {
            if (strlen($value['sd_id']) == 0) {
                $messageCode = '010907005';
                return false;
            }

            if (strlen($value['scm_title']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_fulldescript']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_category']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_mainpic']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if ($value['scm_reservationavailable'] > $value['scm_member_limit']) {
                $messageCode = '010907005';
                return false;
            }
            /*if (strlen($value['scm_activepics']) == 0) {
                $messageCode = '010907005';
                return false;
            } */
            if ( is_null($value['scm_activepics'])/* || count($value['scm_activepics']) == 0*/ ) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_price']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_startdate']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_enddate']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_reservationtag']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_member_limit']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            
            //20171219欄位捨棄
            /* if ($value['scm_price']== 0) {
                if( $value['scm_bonus_modify_type'] == 2) {
                     if ($value[' '] <= 0 ) {
                         $messageCode = '010907005';
                         return false;
                     }
                 } else {
                     $messageCode = '010907005';
                      return false;
                 }
             }*/
             
             $providetypeArray = array(1,2,3,4);
             if(in_array($value['scm_coupon_providetype'], $providetypeArray)) {
                 if ($value['scm_price'] > 0) {
                     $messageCode = '010907005';
                     return false;
                 } else if ($value['scm_coupon_providetype'] == 2 ) {
                     if ($value['scm_bonus_payamount'] == 0 ) {
                         $messageCode = '010907005';
                         return false;
                     }
                 } 
             } else if ($value['scm_coupon_providetype'] == 0 ) {
                 if ($value['scm_price'] == 0) {
                     $messageCode = '010907005';
                     return false;
                 }
             }
             
           /*  if  ($value['scm_bonus_modify_type'] != 0 ) {
                 if($value['scm_bonus_amount'] == 0) {
                    $messageCode = '010907005';
                     return false;  
                 } 
             }*/
        
            if($value['scm_bonus_giveafteruse'] == 1) {
                if ($value['scm_bonus_giveamount'] <= 0 ) {
                   $messageCode = '010907005';
                   return false;
                }
             }

            if ($value['scm_reservationtag'] == '1') {
                //需檢查預約資料
                if (strlen($value['scm_reservationtag']) == 0) {
                    $messageCode = '010907005';
                    return false;
                }
                if (strlen($value['scm_dailystart']) == 0) {
                    $messageCode = '010907005';
                    return false;
                }
                if (strlen($value['scm_dailyend']) == 0) {
                    $messageCode = '010907005';
                    return false;
                }
                if (strlen($value['scm_workhour']) == 0) {
                    $messageCode = '010907005';
                    return false;
                }
                if (strlen($value['scm_preparehour']) == 0) {
                    $messageCode = '010907005';
                    return false;
                }
                if (strlen($value['scm_includeweekend']) == 0) {
                    $messageCode = '010907005';
                    return false;
                }
                if (strlen($value['scm_reservationavailable']) == 0) {
                    $messageCode = '010907005';
                    return false;
                }
                $modifydata['scm_reservationtag'] = $value['scm_reservationtag'];
                $modifydata['scm_dailystart'] = $value['scm_dailystart'];
                $modifydata['scm_dailyend'] = $value['scm_dailyend'];
                $modifydata['scm_workhour'] = $value['scm_workhour'];
                $modifydata['scm_preparehour'] = $value['scm_preparehour'];
                $modifydata['scm_includeweekend'] = $value['scm_includeweekend'];
                $modifydata['scm_reservationavailable'] = $value['scm_reservationavailable'];
            }

            $modifydata['sd_id'] = $value['sd_id'];
            $modifydata['scm_title'] = $value['scm_title'];
            $modifydata['scm_fulldescript'] = $value['scm_fulldescript'];
            $modifydata['scm_category'] = $value['scm_category'];
            $modifydata['scm_mainpic'] = $value['scm_mainpic'];
            $modifydata['scm_activepics'] = $value['scm_activepics'];
            $modifydata['scm_price'] = $value['scm_price'];
            $modifydata['scm_startdate'] = $value['scm_startdate'];
            $modifydata['scm_enddate'] = $value['scm_enddate'];
            $modifydata['scm_member_limit'] = $value['scm_member_limit'];
            $modifydata['scm_reservationtag'] = $value['scm_reservationtag'];
            $modifydata['scm_coupon_usetype'] = $value['scm_coupon_usetype'];
          //  $modifydata['scm_bonus_modify_type'] = $value['scm_bonus_modify_type'];
          //  $modifydata['scm_bonus_amount'] = $value['scm_bonus_amount'];

            $modifydata['scm_balanceno'] = $value['scm_balanceno'];

            $modifydata['scm_producttype'] = $value['scm_producttype'];
            $modifydata['scm_advancedescribe'] = $value['scm_advancedescribe'];
            
           $modifydata['scm_coupon_providetype'] = $value['scm_coupon_providetype'];
            $modifydata['scm_bonus_giveafteruse'] = $value['scm_bonus_giveafteruse'];
            $modifydata['scm_bonus_giveamount'] = $value['scm_bonus_giveamount'];
            $modifydata['scm_bonus_payamount'] = $value['scm_bonus_payamount'];
            
            $modifydata['scm_coupon_providetype'] = $value['scm_coupon_providetype'];
            $modifydata['scm_bonus_giveafteruse'] =$value['scm_bonus_giveafteruse'];
            $modifydata['scm_bonus_giveamount'] = $value['scm_bonus_giveamount'] ;
            $modifydata['scm_bonus_payamount'] = $value['scm_bonus_payamount'];
            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    private function Create_ModifyData_1($value, &$modifydata, &$messageCode) {
        try {
            $memService = new \App\Services\MemberService;
            if (!$memService->verify_memberseccode($value['md_id'], $value['md_securitycode'],  $messageCode)) {
                   throw new \Exception($messageCode);
            }   
            $shopData = \App\Models\ICR_ShopData::GetData($value['sd_id']);
            $scmData = \App\Models\ICR_ShopCouponData_m::GetData($value['scm_id']);
            if ($shopData[0]["sd_paymentflowagreement"] != 1 && $scmData[0]['scm_poststatus'] == 1 ) {
                //未啟用金流，無法使用該功能。
               $messageCode = '010907008';
               return false;
            }
            if (strlen($value['scm_id']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_poststatus']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if ($value['scm_reservationavailable'] > $value['scm_member_limit']) {
                $messageCode = '010907005';
                return false;
            }
            $modifydata['scm_id'] = $value['scm_id'];
            $modifydata['sd_id'] = $value['sd_id'];
            $modifydata['md_id'] = $value['md_id'];
            $modifydata['scm_poststatus'] = $value['scm_poststatus'];

            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    private function Create_ModifyData_2($value, &$modifydata, &$messageCode) {
        try {
            if (strlen($value['scm_title']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_fulldescript']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_category']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_mainpic']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if ($value['scm_reservationavailable'] > $value['scm_member_limit']) {
                $messageCode = '010907005';
                return false;
            }
            /*if (strlen($value['scm_activepics']) == 0) {
                $messageCode = '010907005';
                return false;
            }*/
            if ( is_null($value['scm_activepics']) || count($value['scm_activepics']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_price']) == 0) {
                $messageCode = '010907005';
                return false;
            }
//            if (strlen($value['scm_startdate']) == 0) {
//                $messageCode = '010907005';
//                return false;
//            }
//            if (strlen($value['scm_enddate']) == 0) {
//                $messageCode = '010907005';
//                return false;
//            }
            if (strlen($value['scm_reservationtag']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_member_limit']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            
            //20171209欄為捨棄
            /*if ($value['scm_bonus_modify_type'] == 0) {
                if ($value['scm_price'] < 0) {
                   $messageCode = '010907005'; 
                    return false;
                }
             }
        
            if($value['scm_bonus_modify_type'] == 2) {
                if ($value['scm_bonus_payamount'] > 0 ) {
                   $messageCode = '010907005';
                   return false;
                }
             }*/
            
             $providetypeArray = array(1,2,3,4);
             if(in_array($value['scm_coupon_providetype'], $providetypeArray)) {
                 if ($value['scm_price'] > 0) {
                     $messageCode = '010907005';
                     return false;
                 } else if ($value['scm_coupon_providetype'] == 2 ) {
                     if ($value['scm_bonus_payamount'] == 0 ) {
                         $messageCode = '010907005';
                         return false;
                     }
                 } 
             } else if ($value['scm_coupon_providetype'] == 0 ) {
                 if ($value['scm_price'] == 0) {
                     $messageCode = '010907005';
                     return false;
                 }
             }
             
           /*  if  ($value['scm_bonus_modify_type'] != 0 ) {
                 if($value['scm_bonus_amount'] == 0) {
                    $messageCode = '010907005';
                     return false;  
                 } 
             }*/
        
            if($value['scm_bonus_giveafteruse'] == 1) {
                if ($value['scm_bonus_giveamount'] <= 0 ) {
                   $messageCode = '010907005';
                   return false;
                }
             }
        


            $modifydata['scm_id'] = $value['scm_id'];
            $modifydata['scm_title'] = $value['scm_title'];
            $modifydata['scm_fulldescript'] = $value['scm_fulldescript'];
            $modifydata['scm_category'] = $value['scm_category'];
            $modifydata['scm_mainpic'] = $value['scm_mainpic'];
            $modifydata['scm_activepics'] = $value['scm_activepics'];
            $modifydata['scm_price'] = $value['scm_price'];
//            $modifydata['scm_startdate'] = $value['scm_startdate'];
//            $modifydata['scm_enddate'] = $value['scm_enddate'];
            $modifydata['scm_member_limit'] = $value['scm_member_limit'];
            $modifydata['scm_balanceno'] = $value['scm_balanceno'];
            $modifydata['scm_coupon_usetype'] = $value['scm_coupon_usetype'];
            //$modifydata['scm_bonus_modify_type'] = $value['scm_bonus_modify_type'];
            //$modifydata['scm_bonus_amount'] = $value['scm_bonus_amount'];
            $modifydata['scm_producttype'] = $value['scm_producttype'];
            $modifydata['scm_advancedescribe'] = $value['scm_advancedescribe'];
            
            $modifydata['scm_coupon_providetype'] = $value['scm_coupon_providetype'];
            $modifydata['scm_bonus_giveafteruse'] = $value['scm_bonus_giveafteruse'];
            $modifydata['scm_bonus_giveamount'] = $value['scm_bonus_giveamount'];
            $modifydata['scm_bonus_payamount'] = $value['scm_bonus_payamount'];
             $modifydata['scm_coupon_providetype'] = $value['scm_coupon_providetype'];
            $modifydata['scm_bonus_giveafteruse'] =$value['scm_bonus_giveafteruse'];
            $modifydata['scm_bonus_giveamount'] = $value['scm_bonus_giveamount'] ;
            $modifydata['scm_bonus_payamount'] = $value['scm_bonus_payamount'];
            
            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    private function Create_ModifyData_3($value, &$modifydata, &$messageCode) {
        try {
            if (strlen($value['scm_id']) == 0) {
                $messageCode = '010907005';
                return false;
            }

            if ($value['scm_reservationavailable'] > $value['scm_member_limit']) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_reservationtag']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if ($value['scm_reservationtag'] != 1) {
                $messageCode = '010907006';
                return false;
            }
            if (strlen($value['scm_startdate']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_enddate']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_dailystart']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_dailyend']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_workhour']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_preparehour']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_includeweekend']) == 0) {
                $messageCode = '010907005';
                return false;
            }
            if (strlen($value['scm_reservationavailable']) == 0) {
                $messageCode = '010907005';
                return false;
            }

            $modifydata['scm_id'] = $value['scm_id'];
            //$modifydata['scm_reservationtag'] = $value['scm_reservationtag'];
            $modifydata['scm_startdate'] = $value['scm_startdate'];
            $modifydata['scm_enddate'] = $value['scm_enddate'];
            $modifydata['scm_dailystart'] = $value['scm_dailystart'];
            $modifydata['scm_dailyend'] = $value['scm_dailyend'];
            $modifydata['scm_workhour'] = $value['scm_workhour'];
            $modifydata['scm_preparehour'] = $value['scm_preparehour'];
            $modifydata['scm_includeweekend'] = $value['scm_includeweekend'];
            $modifydata['scm_reservationavailable'] = $value['scm_reservationavailable'];
            $modifydata['scm_producttype'] = $value['scm_producttype'];
            $modifydata['scm_advancedescribe'] = $value['scm_advancedescribe'];

            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    private function ExecuteModifyData_0($modifydata, &$messageCode) {
        try {
            //新增資料
            \App\Models\ICR_ShopCouponData_m::InsertData($modifydata, $scm_id);
            //檢查是否需預約
            if ($modifydata['scm_reservationtag'] == '1') {
                //建立預約資料
                \App\Models\ICR_ShopCouponData_r::Create_ReservationData($scm_id, $modifydata['scm_startdate'], $modifydata['scm_enddate'], $modifydata['scm_dailystart'], $modifydata['scm_dailyend'], $modifydata['scm_workhour'] + $modifydata['scm_preparehour'], $modifydata['scm_includeweekend']);
            }
            //010907001	活動券新增完成
            $messageCode = '010907001';
            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    private function ExecuteModifyData_1($modifydata, &$messageCode) {
        try {
            \App\Models\ICR_ShopCouponData_m::UpdateData_PostStatus($modifydata['scm_id'], $modifydata['scm_poststatus']);
            $sdmrRepo = new \App\Repositories\ICR_ShopDataModifyRecordRepository;
            $saveData = [
                'sdmr_operationtype' => ($modifydata['scm_poststatus']==1)? 2 : 1 ,
                'sdmr_modifyitem' =>2 ,
                'sd_id' =>$modifydata['sd_id'],
                'scm_id' =>$modifydata['scm_id'],
                'sdmr_modifyuser' =>$modifydata['md_id'],
            ];
            $sdmrRepo->insertData($saveData);
            //010907002	更新完成
            $messageCode = '010907002';
            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    private function ExecuteModifyData_2($modifydata, &$messageCode) {
        try {
            //檢查 已領取未使用的活動券數量
            if (\App\Models\ICR_ShopCouponData_g::QueryUnUsedCount($modifydata['scm_id']) != 0) {
                //010907003	會員未用畢前，無法更新活動券內容，可先停刊本券
                $messageCode = '010907003';
                return false;
            }
            //更新資料
            \App\Models\ICR_ShopCouponData_m::UpdateData($modifydata);

            //010907002	更新完成
            $messageCode = '010907002';
            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    private function ExecuteModifyData_3($modifydata, &$messageCode) {
        try {
            //檢查 已領取未使用的活動券數量
            if (\App\Models\ICR_ShopCouponData_g::QueryUnUsedCount($modifydata['scm_id']) != 0) {
                //010907003	會員未用畢前，無法更新活動券內容，可先停刊本券
                $messageCode = '010907003';
                return false;
            }
            //更新資料
            \App\Models\ICR_ShopCouponData_m::UpdateData($modifydata);
            //停用可預約時段
            \App\Models\ICR_ShopCouponData_r::Update_SCR_Effective($modifydata['scm_id'], 0);


            $querydata = \App\Models\ICR_ShopCouponData_m::GetData($modifydata['scm_id']);
            if (count($querydata) == 0) {
                $messageCode = '999999999';
                return false;
            }

            //建立 可預約時段
            \App\Models\ICR_ShopCouponData_r::Create_ReservationData($modifydata['scm_id'], $querydata[0]['scm_startdate'], $querydata[0]['scm_enddate'], $modifydata['scm_dailystart'], $modifydata['scm_dailyend'], $modifydata['scm_workhour'] + $modifydata['scm_preparehour'], $modifydata['scm_includeweekend']);
            //010907002	更新完成
            $messageCode = '010907002';
            return true;
        } catch (Exception $ex) {
            $messageCode = '999999999';
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

}
