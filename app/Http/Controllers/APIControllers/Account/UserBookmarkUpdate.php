<?php

namespace App\Http\Controllers\APIControllers\Account;

use App\Library\Commontools;

class UserBookmarkUpdate {


   
    /**
     * 檢查輸入值是否正確
     * @param type $value
     * @return boolean
     */
    public  function CheckInput(&$value) {

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
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'lastupdate', 20, true, true)) {
            return false;
        }

        if (!array_key_exists('lastupdate', $value)) {
            $value['lastupdate'] = null;
        }

        return true;
    }

    //檢查書籤所對應的「項目」是否存在
    public  function CheckExistBookmarkItem($ubm_objecttype, $ubm_objectid) {

         if ($ubm_objecttype === '1') {
            $querydata = \App\Models\IsCarCouponData_m::getdata($ubm_objectid);
        } else if ($ubm_objecttype === '2') {
            $querydata = \App\Models\ICR_ShopData::GetData($ubm_objectid);
        }else if ($ubm_objecttype === '3') {
            $querydata = \App\Models\ICR_ShopCouponData_m::GetData($ubm_objectid);
        } else {
            return false;
        }

        if (is_null($querydata) || count($querydata) === 0) {
            //010107003	書籤更新物件不存在，請確認後再發送
            return false;
        }

        return true;
    }

    public  function UpdateUserBookmarkData($useroperate, $ubm_objecttype, $ubm_objectid, $md_id, &$messageCode) {
        //0:新增 1:刪除該筆 2:刪除全部
        if ($useroperate == '0') {
            $querydata = \App\Models\IsCarUserBookmark::GetDataByMD_ID_Object($md_id, $ubm_objecttype, $ubm_objectid);

            if (count($querydata) == 0) {

                $savedata['useroperate'] = $useroperate;
                $savedata['ubm_objecttype'] = $ubm_objecttype;
                $savedata['ubm_objectid'] = $ubm_objectid;
                $savedata['md_id'] = $md_id;

                if (!\App\Models\IsCarUserBookmark::InsertData($savedata)) {
                    $messageCode = '999999999';
                    throw new \Exception($messageCode);
                }
            } else {
                if ($querydata[0]['isflag'] == '1') {
                    //010107002	此更新操作已重覆執行，請先進行同步作業
                    $messageCode = '010107002';
                    throw new \Exception($messageCode);
                } else {
                    $querydata[0]['isflag'] = '1';
                    if (!\App\Models\IsCarUserBookmark::UpdateData($querydata[0])) {
                        $messageCode = '999999999';
                        throw new \Exception($messageCode);
                    }
                }
            }
            //追蹤 給與禮點
            if($ubm_objecttype != 3 ) {
                  $appService = new \App\Services\AppService;
                  $giftpoint = $appService->getGiftPointsAmount( 6 );
                  $appService->modifyGiftPoint($md_id, 6, $ubm_objectid, 1, true, $giftpoint);
            }
        } else if ($useroperate == '1') {

            $querydata = \App\Models\IsCarUserBookmark::GetDataByMD_ID_Object($md_id, $ubm_objecttype, $ubm_objectid);
            if (count($querydata) == 0) {
                //010107003	書籤更新物件不存在，請確認後再發送
                $messageCode = '010107003';
                return false;
            } else {
                if ($querydata[0]['isflag'] == '1') {
                    $querydata[0]['isflag'] = '0';
                    if (!\App\Models\IsCarUserBookmark::UpdateData($querydata[0])) {
                        $messageCode = '999999999';
                        return false;
                    }
                } else {
                    //010107002	此更新操作已重覆執行，請先進行同步作業
                    $messageCode = '010107002';
                    return false;
                }
            }
            //取消追蹤 扣除禮點
            if ( $ubm_objecttype != 3 ) {
                 $bankService = new \App\Services\BankService;
                 $bankService->getMemGiftPointQuery(null, $md_id, 0, $pointData, $messageCode);
                 if ($pointData['gpmr_point'] != 0 ) {
                     $appService = new \App\Services\AppService;
                     $giftpoint = $appService->getGiftPointsAmount( 7 );
                     $appService->modifyGiftPoint($md_id, 7, $ubm_objectid, 1, false, $giftpoint);   
                }
            }
        } else if ($useroperate == '2' && $ubm_objectid == 'all') {

            $querydata = \App\Models\IsCarUserBookmark::GetDataByMD_ID($md_id);
            if (count($querydata) == 0) {
                //010107004	書籤更新操作無效，請確認後再發送
                $messageCode = '010107004';
                return false;
            } else {
                //批量更新
                if (!\App\Models\IsCarUserBookmark::UpdateDataToDelete($md_id, $ubm_objecttype)) {
                    $messageCode = '999999999';
                    return false;
                }
            }
            
            if ($ubm_objecttype != 3) {
                 //取消追蹤 扣除禮點
                $bankService = new \App\Services\BankService;
                $bankService->getMemGiftPointQuery(null, $md_id, 0, $pointData, $messageCode);
                if ($pointData['gpmr_point'] != 0 ) {
                    $appService = new \App\Services\AppService;
                    $giftpoint = $appService->getGiftPointsAmount( 7 );
                    $appService->modifyGiftPoint($md_id, 7, $ubm_objectid, 1, false, $giftpoint * count($querydata));
                }
            }
        } else {
            $messageCode = '010107004';
            return false;
        }
        return true;
    }

}
