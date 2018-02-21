<?php

namespace App\Repositories;

use App\Models\ICR_ShopData;
use App\Library\Commontools;
use DB;

class ICR_ShopDataRepository  {

    public function getAllData(){
        $results = ICR_ShopData::where('isflag', '=', '1')
                        ->get();

        return $results;
    }

    /**
     * 透過特店代號抓取符合的特店
     * @param  [type] $sd_salescode [description]
     * @return [type]               [description]
     */
    public function getShopDataBySdSalescode($sd_salescode){
        $results = ICR_ShopData::where('isflag', '=', '1')
                        ->where('sd_salescode','=',$sd_salescode)
                        ->get();

        return $results;
    }

    /**
     * 抓取未刪除未停用的店家
     * @return [type] [description]
     */
    public function getDataByActiveStatus(){
        $results = ICR_ShopData::where('isflag', '=', '1')
                        ->where('sd_activestatus','=','1')
                        ->get();

        return $results;
    }

    public function InsertData($arraydata){
      try {
             if (!Commontools::CheckArrayValue($arraydata, "sd_id") || !Commontools::CheckArrayValue($arraydata, "sd_shopname")) {
                return false;
            }
            $savadata['sd_id'] = $arraydata['sd_id'];
            $savadata['sd_shopname'] = $arraydata['sd_shopname'];

            if (Commontools::CheckArrayValue($arraydata, "sd_type")) {
                $savadata['sd_type'] = $arraydata['sd_type'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_shoptel")) {
                $savadata['sd_shoptel'] = $arraydata['sd_shoptel'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_zipcode")) {
                $savadata['sd_zipcode'] = $arraydata['sd_zipcode'];
            }
            if (Commontools::CheckArrayValue($arraydata, "rl_city_code")) {
                $savadata['rl_city_code'] = $arraydata['rl_city_code'];
            }else{
                $savadata['rl_city_code'] = 0;
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_shopaddress")) {
                $savadata['sd_shopaddress'] = $arraydata['sd_shopaddress'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_lat")) {
                $savadata['sd_lat'] = $arraydata['sd_lat'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_lng")) {
                $savadata['sd_lng'] = $arraydata['sd_lng'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_weeklystart")) {
                $savadata['sd_weeklystart'] = $arraydata['sd_weeklystart'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_weeklyend")) {
                $savadata['sd_weeklyend'] = $arraydata['sd_weeklyend'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_dailystart")) {
                $savadata['sd_dailystart'] = $arraydata['sd_dailystart'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_dailystart")) {
                $savadata['sd_dailystart'] = $arraydata['sd_dailystart'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_dailyend")) {
                $savadata['sd_dailyend'] = $arraydata['sd_dailyend'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_shopphotopath")) {
                $savadata['sd_shopphotopath'] = $arraydata['sd_shopphotopath'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_introtext")) {
                $savadata['sd_introtext'] = $arraydata['sd_introtext'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_contact_person")) {
                $savadata['sd_contact_person'] = $arraydata['sd_contact_person'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_contact_tel")) {
                $savadata['sd_contact_tel'] = $arraydata['sd_contact_tel'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_contact_mobile")) {
                $savadata['sd_contact_mobile'] = $arraydata['sd_contact_mobile'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_contact_address")) {
                $savadata['sd_contact_address'] = $arraydata['sd_contact_address'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_contact_email")) {
                $savadata['sd_contact_email'] = $arraydata['sd_contact_email'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_activestatus")) {
                $savadata['sd_activestatus'] = $arraydata['sd_activestatus'];
            }else{
                $savadata['sd_activestatus'] = 1;
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_paymentflow")) {
                $savadata['sd_paymentflow'] = $arraydata['sd_paymentflow'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_advancedata")) {
                $savadata['sd_advancedata'] = $arraydata['sd_advancedata'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_paymentflowdata")) {
                $savadata['sd_paymentflowdata'] = $arraydata['sd_paymentflowdata'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_paymentflowfeepct")) {
                $savadata['sd_paymentflowfeepct'] = $arraydata['sd_paymentflowfeepct'];
            }else{
                $savadata['sd_paymentflowfeepct'] = 0;
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_havebind")) {
                $savadata['sd_havebind'] = $arraydata['sd_havebind'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_questionnaireresult")) {
                $savadata['sd_questionnaireresult'] = $arraydata['sd_questionnaireresult'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sd_questiontotalaverage")) {
                $savadata['sd_questiontotalaverage'] = $arraydata['sd_questiontotalaverage'];
            }
            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savadata['isflag'] = $arraydata['isflag'];
            } else {
                $savadata['isflag'] = '1';
            }
            $savadata['create_user'] = 'webapi';
            $savadata['last_update_user'] = 'webapi';
            DB::table('icr_shopdata')->insert($savadata);
            return true;
       } catch(Exception $ex) {
            \App\Models\ErrorLog::Insert($ex);
            return false;
       }
    }

    public function getShopDataBySdType($sd_type,$sd_havebind){
        $string = DB::table('icr_shopdata');
        if(!is_null($sd_type)){
            $string->where('sd_type',$sd_type);
        }
        if(!is_null($sd_havebind)){
            $string->where('sd_havebind',$sd_havebind);
        }
        $string->where('isflag',1);
        return $string->get();
    }

    /**
     * 依「$sd_id」取得資料
     * @param type $sd_id
     * @return type
     */
    public function GetData($sd_id) {
        try {
            if ($sd_id == null || strlen($sd_id) == 0) {
                return null;
            }
            $results = ICR_ShopData::where('isflag', '=', '1')->where('sd_id', '=', $sd_id)->get();
            return $results;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 透過sd_id 跟 時間區間 以及 商品類型 抓取符合的商品資料
     * @param  [type]    $sd_id           [店家代碼]
     * @param  [type]    $query_start     [起始時間]
     * @param  [type]    $query_end       [結束時間]
     * @param  [type]    $scm_producttype [商品類型]
     * @param  [boolean] $use             [true:已付款已用畢 false:已付款未使用]
     */
    public function getShopSalesBySdId($sd_id,$query_start,$query_end,$scm_producttype,$use,$scm_reservationtag){
        $data = ICR_ShopData::leftjoin('icr_shopcoupondata_m','icr_shopcoupondata_m.sd_id','icr_shopdata.sd_id')
                           ->leftjoin('icr_shopcoupondata_g','icr_shopcoupondata_g.scm_id','icr_shopcoupondata_m.scm_id')
                           ->where('icr_shopdata.sd_id',$sd_id)
                           ->where('icr_shopcoupondata_g.scg_paymentstatus',1);
        if($use){
            $data->where('icr_shopcoupondata_g.scg_usestatus',2);
            $data->where('icr_shopcoupondata_g.scg_usedate', '>=' ,$query_start);
            $data->where('icr_shopcoupondata_g.scg_usedate', '<=',$query_end);
            // $data->whereRaw('(DATE_ADD(`icr_shopcoupondata_g`.`scg_usedate`, INTERVAL 15 DAY) >= "'.$query_start.'")');
            // $data->whereRaw('(DATE_ADD(`icr_shopcoupondata_g`.`scg_usedate`, INTERVAL 15 DAY) <= "'.$query_end.'")');
        }else{
            $data->where('icr_shopcoupondata_g.scg_usestatus','!=',2);
            $data->where('icr_shopcoupondata_g.scg_paid_time', '>=' ,$query_start);
            $data->where('icr_shopcoupondata_g.scg_paid_time', '<=',$query_end);
            // $data->whereRaw('(DATE_ADD(`icr_shopcoupondata_g`.`scg_paid_time`, INTERVAL 15 DAY) >= "'.$query_start.'")');
            // $data->whereRaw('(DATE_ADD(`icr_shopcoupondata_g`.`scg_paid_time`, INTERVAL 15 DAY) <= "'.$query_end.'")');
        }
        // $data->where('icr_shopcoupondata_g.last_update_date', '>' ,$query_start)
        // ->where('icr_shopcoupondata_g.last_update_date', '<',$query_end);

        // $data->whereRaw('(DATE_ADD(`icr_shopcoupondata_g`.`scg_usedate`, INTERVAL 15 DAY) > "'.$query_start.'")');
        // $data->whereRaw('(DATE_ADD(`icr_shopcoupondata_g`.`scg_usedate`, INTERVAL 15 DAY) < "'.$query_end.'")');
        if(!is_null($scm_reservationtag)){
            $data->where('icr_shopcoupondata_m.scm_reservationtag',$scm_reservationtag);
        }
        // $data->select('icr_shopcoupondata_g.scg_id','icr_shopcoupondata_m.scm_title','icr_shopcoupondata_m.scm_producttype','icr_shopcoupondata_g.scg_buyprice','icr_shopcoupondata_g.scg_buyamount','icr_shopcoupondata_g.scg_totalamount','icr_shopdata.sd_paymentflowfeepct','icr_shopcoupondata_m.scm_reservationtag')
        return $data->get();
    }

    /**
     * 透過sd_id 跟 時間區間 抓取已付款的銷貨資訊
     * @param  [string] $sd_id       [店家代碼]
     * @param  [string] $query_start [起始時間]
     * @param  [string] $query_end   [結束時間]
     */
    public function getShopReportBySdIdDate($sd_id,$query_start,$query_end){
        return ICR_ShopData::leftjoin('icr_shopcoupondata_m','icr_shopcoupondata_m.sd_id','icr_shopdata.sd_id')
                           ->leftjoin('icr_shopcoupondata_g','icr_shopcoupondata_g.scm_id','icr_shopcoupondata_m.scm_id')
                           ->where('icr_shopdata.sd_id',$sd_id)
                           ->where('icr_shopcoupondata_g.scg_paymentstatus',1)
                           ->where('icr_shopcoupondata_g.last_update_date', '>' ,$query_start)
                           ->where('icr_shopcoupondata_g.last_update_date', '<',$query_end)
                           ->select('icr_shopcoupondata_g.scg_id','icr_shopcoupondata_m.scm_title','icr_shopcoupondata_m.scm_producttype','icr_shopcoupondata_g.scg_buyprice','icr_shopcoupondata_g.scg_buyamount','icr_shopcoupondata_g.scg_totalamount','icr_shopdata.sd_paymentflowfeepct')
                           ->get();
    }

    /**
     * 透過sd_id 抓取店家資訊
     * @param  [string] $sd_id [店家代碼]
     */
    public function getShopDataBySdId($sd_id){
        return ICR_ShopData::where('sd_id',$sd_id)->where('isflag',1)->get();
    }

    /**
     * 只抓10筆
     * 透過查詢條件，抓取符合的特約商資料，只抓10筆
     * @param  [string] $sd_type           [商家類別]
     * @param  [string] $sd_zipcode        [商家郵遞區號]
     * @param  [string] $sd_shopname       [商家名稱]
     * @param  [string] $skip_page         [跳過幾頁作查詢(一頁有10筆)]
     * @param  [string] $sd_havebind       [綁定狀態 0:未綁定 1:已綁定]
     * @param  [string] $sd_activestatus   [商家有效狀態]
     * @param  [string] $sd_contact_person [商家聯絡人]
     * @param  [string] $sort              [排序根據]
     * @param  [string] $order             [排序方式 DESC(倒序) ASC(正序)]
     */
    public function getShopDataByQueryConditions($sd_type,$sd_zipcode,$sd_shopname,$skip_page,$sd_havebind,$sd_activestatus,$sd_contact_person,$sort,$order){
        $string = DB::table('icr_shopdata');

        if(!is_null($sd_type)){
            $string->where('sd_type',$sd_type);
        }
        if(!is_null($sd_zipcode)){
            $string->where('sd_zipcode',$sd_zipcode);
        }
        if(!is_null($sd_activestatus)){
            $string->where('sd_activestatus',$sd_activestatus);
        }
        if(!is_null($sd_havebind)){
            $string->where('sd_havebind',$sd_havebind);
        }
        if(!is_null($sd_shopname)){
            $string->where('sd_shopname', 'LIKE' ,'%'.$sd_shopname.'%');
        }
        if(!is_null($sd_contact_person)){
            $string->where('sd_contact_person', 'LIKE' ,'%'.$sd_contact_person.'%');
        }
        $string->where('isflag',1);
        $string->orderBy($sort,$order);
        return $string->take(10)->skip($skip_page*10)->get();
    }

    /**
     * 透過查詢條件，抓取符合的特約商資料
     * @param  [string] $sd_type           [商家類別]
     * @param  [string] $sd_zipcode        [商家郵遞區號]
     * @param  [string] $sd_shopname       [商家名稱]
     * @param  [string] $skip_page         [跳過幾頁作查詢(一頁有10筆)]
     * @param  [string] $sd_havebind       [綁定狀態 0:未綁定 1:已綁定]
     * @param  [string] $sd_activestatus   [商家有效狀態]
     * @param  [string] $sd_contact_person [商家聯絡人]
     * @param  [string] $sort              [排序根據]
     * @param  [string] $order             [排序方式 DESC(倒序) ASC(正序)]
     */
    public function getShopData($sd_type,$sd_zipcode,$sd_shopname,$skip_page,$sd_havebind,$sd_activestatus,$sd_contact_person,$sort,$order){
        $string = DB::table('icr_shopdata');

        if(!is_null($sd_type)){
            $string->where('sd_type',$sd_type);
        }
        if(!is_null($sd_zipcode)){
            $string->where('sd_zipcode',$sd_zipcode);
        }
        if(!is_null($sd_activestatus)){
            $string->where('sd_activestatus',$sd_activestatus);
        }
        if(!is_null($sd_havebind)){
            $string->where('sd_havebind',$sd_havebind);
        }
        if(!is_null($sd_shopname)){
            $string->where('sd_shopname', 'LIKE' ,'%'.$sd_shopname.'%');
        }
        if(!is_null($sd_contact_person)){
            $string->where('sd_contact_person', 'LIKE' ,'%'.$sd_contact_person.'%');
        }
        $string->where('isflag',1);
        $string->orderBy($sort,$order);
        return $string->get();
    }
}
