<?php

namespace App\Http\Controllers\APIControllers\Account;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Library\Commontools;


class UserBookmarkRecorver  {

    public  function GetData_UserBookMarkData($md_id, $lastupdate, &$maxdate) {

        if (is_null($lastupdate) || strlen($lastupdate) == 0) {
            //「無」則取得該「md_id」且「isflag」=1的值
            $querydata = \App\Models\IsCarUserBookmark::GetDataByMD_ID($md_id);
        } else {
            //「有」則取得大於「lastupdate」所有的值
            $querydata = \App\Models\IsCarUserBookmark::GetDataByMD_ID_LastDate($md_id, $lastupdate);
        }

        return UserBookmarkRecorver::TransDataToUserBookmarkArray($querydata, $maxdate);
    }

    //將資料轉為「BookmarkArray」陣列
    private  function TransDataToUserBookmarkArray($data, &$maxdate) {
        if (is_null($data) || count($data) == 0) {
            return null;
        }
        $maxdate = null;
        foreach ($data as $rowdata) {

            if ($rowdata['last_update_date'] > $maxdate) {
                $maxdate = $rowdata['last_update_date'];
            }

            $ubm_title = '';
            $ubm_time = '';
            $ubm_picpath = '';
            if ($rowdata['ubm_objecttype'] == 1) {
                UserBookmarkRecorver::GetBookmarkData_Coupon($rowdata['ubm_objectid'], $ubm_title, $ubm_time, $ubm_picpath);
            } else if ($rowdata['ubm_objecttype'] == 2) {
                UserBookmarkRecorver::GetBookmarkData_ShopData($rowdata['ubm_objectid'], $ubm_title, $ubm_time, $ubm_picpath);
            } else if ($rowdata['ubm_objecttype'] == 3) {
                UserBookmarkRecorver::GetBookmarkData_ShopCoupon($rowdata['ubm_objectid'], $ubm_title, $ubm_time, $ubm_picpath);
            }
            $resultdata[] = array(
                'ubm_objecttype' => ($rowdata['ubm_objecttype'])
                , 'ubm_objectid' => ($rowdata['ubm_objectid'])
                , 'isflag' => ($rowdata['isflag'])
                , 'ubm_title' => ($ubm_title)
                //, 'ubm_time' => ($ubm_time)
                , 'ubm_picpath' => ($ubm_picpath)
                , 'create_date' => ($rowdata['create_date'])
            );
        }
        return $resultdata;
    }

    /**
     * 取得回傳書籤資料  「活動券」
     * @param type $itemid
     * @param type $ubm_title
     * @param type $ubm_time
     * @param type $ubm_picpath
     * @return type
     */
    private  function GetBookmarkData_Coupon($itemid, &$ubm_title, &$ubm_time, &$ubm_picpath) {
        $querydata = \App\Models\IsCarCouponData_m::GetData($itemid);

        if (is_null($querydata) || count($querydata) == 0) {
            return;
        }

        $ubm_title = $querydata[0]['cdm_cname'];
        $ubm_time = $querydata[0]['create_date'];
        $ubm_picpath = $querydata[0]['cdm_active_pic'];
    }

    /**
     * 取得回傳書籤資料  「店家」
     * @param type $itemid
     * @param type $ubm_title
     * @param type $ubm_time
     * @param type $ubm_picpath
     * @return type
     */
    private  function GetBookmarkData_ShopData($itemid, &$ubm_title, &$ubm_time, &$ubm_picpath) {
        $querydata = \App\Models\ICR_ShopData::GetData($itemid);

        if (is_null($querydata) || count($querydata) == 0) {
            return;
        }

        $ubm_title = $querydata[0]['sd_shopname'];
        $ubm_time = $querydata[0]['create_date'];
        $ubm_picpath = $querydata[0]['sd_shopphotopath'];
    }

    /**
     * 取得回傳書籤資料  「店家」
     * @param type $itemid
     * @param type $ubm_title
     * @param type $ubm_time
     * @param type $ubm_picpath
     * @return type
     */
    private  function GetBookmarkData_ShopCoupon($itemid, &$ubm_title, &$ubm_time, &$ubm_picpath) {
        $querydata = \App\Models\ICR_ShopCouponData_m::GetData($itemid);

        if (is_null($querydata) || count($querydata) == 0) {
            return;
        }

        $ubm_title = $querydata[0]['scm_title'];
        $ubm_time = $querydata[0]['create_date'];
        $ubm_picpath = $querydata[0]['scm_mainpic'];
    }

    /**
     * 檢查輸入值是否正確
     * @param type $value
     * @return boolean
     */
    public  function CheckInput(&$value) {

        if ($value == null) {
            return false;
        }

       if (!\App\library\Commontools::CheckRequestArrayValue($value, 'modacc', 0, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'modvrf', 0, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'sat', 0, false, false)) {
            return false;
        }
        if (!\App\library\Commontools::CheckRequestArrayValue($value, 'lastupdate', 20, true, true)) {
            return false;
        }

        if (!array_key_exists('lastupdate', $value)) {
            $value['lastupdate'] = null;
        }


        return true;
    }

}
