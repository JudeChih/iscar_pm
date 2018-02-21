<?php

namespace App\Repositories;

use App\Models\ICR_ShopCouponData_m;
use DB;

class ICR_ShopCouponData_mRepository  {

    public function getAllData(){
        $results = ICR_ShopCouponData_m::where('isflag', '=', '1')
                        ->where('scm_enddate', '>', \Carbon\Carbon::now())
                        ->get();

        return $results;
    }

    /**
     * 透過sd_id抓取同店家不同商品，隨機三個
     * @param  [string] $sd_id [店家代碼]
     */
    public function getRandThreeDataBySdIdScmId($sd_id,$scm_id){
        try {
            if ($sd_id == null || strlen($sd_id) == 0 || $scm_id == null || strlen($scm_id) == 0) {
                return null;
            }

            $results = ICR_ShopCouponData_m::where('isflag', '=', '1')
                            ->where('sd_id', '=', $sd_id)
                            ->where('scm_id', '!=', $scm_id)
                            ->where('scm_poststatus', '=', '1')
                            ->where('scm_coupon_providetype', '!=', '1') // 排除特點兌換
                            ->where('scm_startdate', '<' ,\Carbon\Carbon::now())
                            ->where('scm_enddate', '>' ,\Carbon\Carbon::now())
                            ->orderByRaw("RAND()")
                            ->take(3)
                            ->get();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 透過sd_id&scm_coupon_providetype抓取該特約商底下的商品列表
     * @param [type] $sd_id [description]
     * @param [type] $scm_coupon_providetype  [description]
     */
    public function GetDataBy_SD_SCP($sd_id,$scm_coupon_providetype ) {
        try {

            $results = ICR_ShopCouponData_m::where('isflag', '=', '1')
                            ->where('scm_poststatus','=','1')
                            ->where('sd_id', '=', $sd_id)
                            ->where('scm_coupon_providetype', '=', $scm_coupon_providetype)
                            ->where('scm_startdate', '<' ,\Carbon\Carbon::now())
                            ->where('scm_enddate', '>' ,\Carbon\Carbon::now())
                            ->orderby('create_date', 'desc')
                            ->get();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 透過sd_id抓取該特約商底下的商品列表
     * @param [type] $sd_id [description]
     */
    public static function GetDataBy_SD_ID($sd_id) {
        try {
            if ($sd_id == null || strlen($sd_id) == 0) {
                return null;
            }

            $results = ICR_ShopCouponData_m::where('isflag', '=', '1')
                            ->where('sd_id', '=', $sd_id)
                            ->where('scm_startdate', '<' ,\Carbon\Carbon::now())
                            ->where('scm_enddate', '>' ,\Carbon\Carbon::now())
                            ->orderby('create_date', 'desc')
                            ->get();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 只抓10筆
     * 透過查詢條件，抓取符合的特約商資料
     * @param  string $skip_page       [跳過幾頁作查詢(一頁有10筆)]
     * @param  string $scm_category    [活動類別：0. 汽車美容 1.汽車維修 2.汽車百貨3.汽車零配件]
     * @param  string $scm_title       [優惠標題]
     * @param  string $sd_shopname     [商家名稱]
     * @param  string $scm_poststatus  [商品刊登狀態 0:停用 1:啟用]
     * @param  string $sort            [排序根據]
     * @param  string $order           [排序方式 DESC(倒序) ASC(正序)]
     */
    public function getShopCouponByQueryConditions($skip_page,$scm_category,$scm_title,$sd_shopname,$scm_poststatus,$sort,$order){
        $string = ICR_ShopCouponData_m::leftJoin('icr_shopdata','icr_shopcoupondata_m.sd_id','icr_shopdata.sd_id');

        if(!is_null($scm_category)){
            $string->where('scm_category',$scm_category);
        }
        if(!is_null($scm_title)){
            $string->where('scm_title', 'LIKE' ,'%'.$scm_title.'%');
        }
        if(!is_null($sd_shopname)){
            $string->where('sd_shopname', 'LIKE' ,'%'.$sd_shopname.'%');
        }
        if(!is_null($scm_poststatus)){
            $string->where('scm_poststatus',$scm_poststatus);
        }
        $string->orderBy($sort,$order);
        return $string->where('icr_shopcoupondata_m.isflag',1)->take(10)->skip($skip_page*10)->get();
    }

    /**
     * 透過查詢條件，抓取符合的特約商資料
     * @param  string $scm_category    [活動類別：0. 汽車美容 1.汽車維修 2.汽車百貨3.汽車零配件]
     * @param  string $scm_title       [優惠標題]
     * @param  string $sd_shopname     [商家名稱]
     * @param  string $scm_poststatus  [商品刊登狀態 0:停用 1:啟用]
     * @param  string $sort            [排序根據]
     * @param  string $order           [排序方式 DESC(倒序) ASC(正序)]
     */
    public function getShopCoupon($scm_category,$scm_title,$sd_shopname,$scm_poststatus,$sort,$order){
        $string = ICR_ShopCouponData_m::leftJoin('icr_shopdata','icr_shopcoupondata_m.sd_id','icr_shopdata.sd_id');

        if(!is_null($scm_category)){
            $string->where('scm_category',$scm_category);
        }
        if(!is_null($scm_title)){
            $string->where('scm_title', 'LIKE' ,'%'.$scm_title.'%');
        }
        if(!is_null($sd_shopname)){
            $string->where('sd_shopname', 'LIKE' ,'%'.$sd_shopname.'%');
        }
        if(!is_null($scm_poststatus)){
            $string->where('scm_poststatus',$scm_poststatus);
        }
        $string->orderBy($sort,$order);
        return $string->where('icr_shopcoupondata_m.isflag',1)->get();
    }
}
