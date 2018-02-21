<?php

namespace App\Http\Controllers\ViewControllers;

use Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;
use DB;
use Session;

define('SDFIP', config('global.ShopData_FTP_Img_Path'));
define('KEY',config('global.Google_Api_Key'));
define('MAIN',config('global.main'));

class ShopCouponController extends Controller {

    /**
     * 導到[商品細節]頁面
     */
    public function shopCouponDetail() {
        $searchdata = Request::all();
        $scm_r = new \App\Repositories\ICR_ShopCouponData_mRepository;
        $sp_m = new \App\Models\ICR_SystemParameter;
        $main = array();
        try {
            if(isset($_COOKIE[MAIN])){
                $main = json_decode($_COOKIE[MAIN],true);
            }
            if(isset($searchdata['scm_id'])){
                if(isset($searchdata['md_id'])){
                    setcookie('coupon_scm_id',$searchdata['scm_id'],time()+3600,'/');
                    setcookie('coupon_md_id',$searchdata['md_id'],time()+3600,'/');
                }else{
                    setcookie('coupon_scm_id','',time()-3600);
                    setcookie('coupon_md_id','',time()-3600);
                }
                if(!$shopcoupondata = \App\Models\ICR_ShopCouponData_m::GetData($searchdata['scm_id'])){
                    // 抓不到值導回特店首頁
                    return redirect('/');
                }
                if(isset($searchdata['scm_title'])){
                    $getdata['scm_id'] = $searchdata['scm_id'];
                    $getdata['type'] = $searchdata['type'];
                    $getdata['scg_id'] = $searchdata['scg_id'];
                    $getdata['scm_title'] = $searchdata['scm_title'];
                    $getdata['scm_enddate'] = $searchdata['scm_enddate'];

                }elseif(isset($searchdata['type'])){
                    $getdata['scm_id'] = $searchdata['scm_id'];
                    $getdata['type'] = $searchdata['type'];
                    $getdata['scg_id'] = $searchdata['scg_id'];
                    $getdata['scm_title'] = '';
                    $getdata['scm_enddate'] = '';
                }else{
                    $getdata['scm_id'] = $searchdata['scm_id'];
                    $getdata['type'] = 0;
                    $getdata['scg_id'] = '';
                    $getdata['scm_title'] = '';
                    $getdata['scm_enddate'] = '';
                }
                $shopcoupondata = $shopcoupondata[0];
                $DataCount =  \App\Models\ICR_ShopCouponData_g::QueryGetCount($shopcoupondata['scm_id']);
                $inventory = $shopcoupondata['scm_member_limit'] - $DataCount;
                $shopcoupondata['inventory'] = $inventory;
                $shopcoupondata['scm_advancedescribe'] = json_decode($shopcoupondata['scm_advancedescribe'],true);
                if($shopcoupondata['scm_startdate'] != '' && $shopcoupondata['scm_enddate'] != ''){
                    $shopcoupondata['scm_startdate'] = str_replace("-","/",$shopcoupondata['scm_startdate']);
                    $shopcoupondata['scm_enddate'] = str_replace("-","/",$shopcoupondata['scm_enddate']);
                }



                // 抓取同店家不同商品，隨機三樣
                $shopcouponrandthree = $scm_r->getRandThreeDataBySdIdScmId($shopcoupondata['sd_id'],$searchdata['scm_id']);

/////////////// 設定meta值 /////////////////////////////////////////////////////////////////////////////////////////////
                $metadata['author'] = $shopcoupondata['sd_shopname'];
                if(mb_detect_encoding($shopcoupondata['scm_fulldescript']) == "UTF-8"){
                    $metadata['description'] = mb_substr($shopcoupondata['scm_fulldescript'],0,125,"utf-8");
                }else{
                    $metadata['description'] = substr($shopcoupondata['scm_fulldescript'],0,250);
                }

                $metadata['scm_title'] = $shopcoupondata['scm_title'];
                $metadata['og_title'] = $shopcoupondata['scm_title'];
                $metadata['og_url'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                if($shopcoupondata['scm_mainpic'] != null && $shopcoupondata['scm_mainpic'] != ''){
                    $metadata['og_image'] = SDFIP.$shopcoupondata['scm_mainpic'];
                    $metadata['og_image_alt'] = $shopcoupondata['scm_mainpic'];
                }else{
                    $metadata['og_image'] = 'http://'.$_SERVER['HTTP_HOST'].'/app/image/imgDefault.png';
                    $metadata['og_image_alt'] = 'imgDefault.png';
                }
                if(mb_detect_encoding($shopcoupondata['scm_fulldescript']) == "UTF-8"){
                    $metadata['og_description'] = mb_substr($shopcoupondata['scm_fulldescript'],0,125,"utf-8");
                }else{
                    $metadata['og_description'] = substr($shopcoupondata['scm_fulldescript'],0,250);
                }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                // 抓取系統參數，看能不能用禮點折抵現金
                if($PayGiftpointAsCash = $sp_m->getPayGiftpointProportion()){
                    $getdata['PayGiftpointAsCash'] = $PayGiftpointAsCash[0]['sp_paramatervalue'];
                }

                // 抓取系統參數，看幾點禮點可以折抵1元
                if($GPExchangeCashRate = $sp_m->getGPExchangeCashRate()){
                    $getdata['GPExchangeCashRate'] = $GPExchangeCashRate[0]['sp_paramatervalue'];
                }

                // 抓取系統參數，看該商品可以折抵的金額比例
                if($GPMaxUseRate = $sp_m->getGPMaxUseRate()){
                    $getdata['GPMaxUseRate'] = $GPMaxUseRate[0]['sp_paramatervalue'];
                }

                if(isset($main["sat"])){
                    if(!\App\Services\MemberService::checkServiceAccessToken($main["sat"],$md_id,$messageCode)){
                        $longUrl = $metadata['og_url'];
                    }else{
                        $longUrl = $metadata['og_url'].'&md_id='.$md_id;
                    }
                    // 抓取會員的特點點數跟禮點點數
                    if(isset($md_id)){
                        $bank_s = new \App\Services\BankService;
                        if(!$bank_s->getMemPmPointQuery(null,$md_id,$shopcoupondata['sd_id'],1,$pmPointData,$messageCode)){//特點
                            throw new \Exception($messageCode);
                        }elseif(!$bank_s->getMemGiftPointQuery(null,$md_id,1,$giftPointData,$messageCode)){//禮點
                            throw new \Exception($messageCode);
                        }else{
                            $getdata['giftpoint'] = $giftPointData['gpmr_point'];
                            // $getdata['giftpoint'] = 100000;
                            $getdata['pmpoint'] = $pmPointData['spmr_point'];
                            if($getdata['PayGiftpointAsCash'] && $getdata['GPExchangeCashRate']){
                                $getdata['paygift'] = $getdata['giftpoint'] / $getdata['GPExchangeCashRate'];
                                $c = pow(10, 0);
                                $getdata['paygift'] = floor($getdata['paygift']*$c)/$c;
                                if($shopcoupondata['scm_price']*$getdata['GPMaxUseRate']/100 < $getdata['paygift']){
                                    $getdata['paygift'] = floor($shopcoupondata['scm_price']*$getdata['GPMaxUseRate']/100);
                                }
                            }
                            if(($getdata['pmpoint'] - $shopcoupondata['scm_bonus_payamount']) >= 0){
                                $getdata['enough'] = '';
                            }
                        }
                    }
                }else{
                    $longUrl = $metadata['og_url'];
                }
                // 將長連結換成短連結
                // 要call的googleapi
                $url = 'https://www.googleapis.com/urlshortener/v1/url?key='.KEY;
                // 抓取這個頁面的完整網址
                $data['longUrl'] = $longUrl;
                // 透過curl call api獲取短連結
                $var = $this->curlModule($data,$url);
                if(isset($var['id'])){
                    $metadata['short_url'] = $var['id'];
                }else{
                    $metadata['short_url'] = $longUrl;
                }


                if(\Carbon\Carbon::now() > $shopcoupondata['scm_enddate']){
                    return redirect('/');
                }
                // $shopcoupondata['scm_activepics'] = json_decode($shopcoupondata['scm_activepics'],true);
                if(isset($main["sat"]) && isset($_COOKIE["returnUrl"])){
                    $returnUrl = $_COOKIE["returnUrl"];
                    setcookie('returnUrl','',time()-3600);
                    setcookie('back','/',time()+3600);
                    return redirect($returnUrl);
                }

                // 這頁面需要導入google map
                $getdata['google'] = true;


                return View::make('/app/pm/shopcoupon-info',compact('shopcoupondata','getdata','metadata','shopcouponrandthree'));
            }

            return redirect('/');
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }


    /**
     * 導到[商品列表]頁面
     */
    public function shopCouponList() {
        $searchdata = Request::all();
        $getdata = null;
        $metadata = null;
        $scm_r = new \App\Repositories\ICR_ShopCouponData_mRepository;
        $main = array();
        try {
            if(isset($_COOKIE[MAIN])){
                $main = json_decode($_COOKIE[MAIN],true);
            }
            if(isset($searchdata['sd_id'])){
                if(isset($searchdata['providetype'])){
                    $getdata['providetype'] = $searchdata['providetype'];
                }else{
                    $getdata['providetype'] = 0;
                }
                // 取得商品資訊
                if(!$shopcouponlist = $scm_r->GetDataBy_SD_SCP($searchdata['sd_id'],$getdata['providetype'])){
                    return redirect('/');
                }
                // if(!$shopcouponlist = $scm_r->GetDataBy_SD_ID($searchdata['sd_id'])){
                //     return redirect('/');
                // }
                // 取得店家資訊
                if(!$shopdata = \App\Models\ICR_ShopData::GetData($searchdata['sd_id'])){
                    return redirect('/');
                }
                $shopdata = $shopdata[0];

/////////////// 設定meta值 /////////////////////////////////////////////////////////////////////////////////////////////
                $metadata['author'] = $shopdata['sd_shopname'];
                $metadata['og_title'] = $shopdata['sd_shopname'];
                $metadata['og_url'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                if($shopdata['sd_shopphotopath'] != null && $shopdata['sd_shopphotopath'] != ''){
                    $metadata['og_image'] = SDFIP.$shopdata['sd_shopphotopath'];
                    $metadata['og_image_alt'] = $shopdata['sd_shopphotopath'];
                }else{
                    $metadata['og_image'] = 'http://'.$_SERVER['HTTP_HOST'].'/app/image/imgDefault.png';
                    $metadata['og_image_alt'] = 'imgDefault.png';
                }
                if(!is_null($shopdata['sd_seo_keywords'])){
                    $metadata['keywords'] = $shopdata['sd_seo_keywords'];
                }else{
                    $metadata['keywords'] = '';
                }
                if(!is_null($shopdata['sd_seo_description'])){
                    $metadata['description'] = $shopdata['sd_seo_description'];
                    $metadata['og_description'] = $shopdata['sd_seo_description'];
                }else{
                    if(mb_detect_encoding($shopdata['sd_introtext']) == "UTF-8"){
                        $metadata['description'] = mb_substr($shopdata['sd_introtext'],0,125,"utf-8");
                    }else{
                        $metadata['description'] = substr($shopdata['sd_introtext'],0,250);
                    }
                    if(mb_detect_encoding($shopdata['sd_introtext']) == "UTF-8"){
                        $metadata['og_description'] = mb_substr($shopdata['sd_introtext'],0,125,"utf-8");
                    }else{
                        $metadata['og_description'] = substr($shopdata['sd_introtext'],0,250);
                    }
                }
                if(!is_null($shopdata['sd_seo_title'])){
                    $metadata['og_title'] = $shopdata['sd_seo_title'];
                    $metadata['sd_title'] = $shopdata['sd_seo_title'];
                }else{
                    $metadata['og_title'] = $shopdata['sd_shopname'];
                    $metadata['sd_title'] = $shopdata['sd_shopname'];
                }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                if(isset($main["sat"])){
                    if(!\App\Services\MemberService::checkServiceAccessToken($main["sat"],$md_id,$messageCode)){
                        throw new \Exception($messageCode);
                    }
                    // 抓取會員的特點點數
                    if(isset($md_id)){
                        $bank_s = new \App\Services\BankService;
                        if(!$bank_s->getMemPmPointQuery(null,$md_id,$searchdata['sd_id'],1,$pointData,$messageCode)){
                            throw new \Exception($messageCode);
                        }else{
                            $getdata['shoppoint'] = $pointData['spmr_point'];
                        }
                    }
                }

                // 導頁功能
                if(isset($main["sat"]) && isset($_COOKIE["returnUrl"])){
                    $returnUrl = $_COOKIE["returnUrl"];
                    setcookie('returnUrl','',time()-3600);
                    setcookie('back','/',time()+3600);
                    return redirect($returnUrl);
                }
                return View::make('/app/pm/shopcoupon-list',compact('shopcouponlist','getdata','metadata','shopdata'));
            }

            return redirect('/');
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }

    public function shopBuyData(){
        $searchdata = Request::all();
        try {
            if(!isset($searchdata['scm_id'])){
                return redirect('/');
            }
            return redirect('/Shop#!/shop-buy-data?scm_id=' . $searchdata['scm_id'] . '&from='. $searchdata['from']);
        } catch (Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }

    /**
     * Curl模組化使用
     * @param type array $post 傳送資料
     * @param type string $route 傳送route
     * @return array or null
     */
    public function curlModule ($post, $route){
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_HEADER => 0,
                CURLOPT_REFERER => 'http://'.$_SERVER['HTTP_HOST'],
                CURLOPT_URL => $route,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($post),
                CURLOPT_HTTPHEADER => array(
                  "cache-control: no-cache",
                  "content-type: application/json",
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                throw new \Exception($err);
            } else {
                return \App\Library\Commontools::ConvertStringToArray($response);
            }
        } catch(\Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

}
