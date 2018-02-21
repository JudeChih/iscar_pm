<?php

namespace App\Http\Controllers\ViewControllers;

use Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\View;
use DB;

define('SDFIP', config('global.ShopData_FTP_Img_Path'));
define('KEY', config('global.Google_Api_Key'));
define('MODACCWEB', config('global.MODACCWEB'));
define('MODPWDWEB', config('global.MODPWDWEB'));
define('IPAPI', config('global.IPAPI'));
define('MAIN',config('global.main'));

class ShopDataController extends Controller {

    /**
     * 導到[特約商列表]頁面
     */
    public function branchCooperative(){

        $searchdata = Request::all();
        $getdata = null;
        $main = array();
        try {
            if(isset($_COOKIE[MAIN])){
                $main = json_decode($_COOKIE[MAIN],true);
            }
            if(isset($main["sat"])){
                if(!\App\Services\MemberService::checkServiceAccessToken($main["sat"],$md_id,$messageCode)){
                    $getdata['md_clienttype'] = null;
                }
                $memberdata = \App\Models\IsCarMemberData::GetData($md_id);
                if(count($memberdata) > 0 ){
                    $memberdata = $memberdata[0];
                    $getdata['md_clienttype'] = $memberdata['md_clienttype'];
                }else{
                    $getdata['md_clienttype'] = null;
                }
            }else{
                $getdata['md_clienttype'] = null;
            }
            if(!isset($searchdata['cate'])){
                $searchdata['cate'] = '2';
                $getdata['cate'] = $searchdata['cate'];
            }else{
                $getdata['cate'] = $searchdata['cate'];
            }
            if(!isset($searchdata['listtype'])){
                $searchdata['listtype'] = 0;
                $getdata['listtype'] = $searchdata['listtype'];
            }else{
                $getdata['listtype'] = $searchdata['listtype'];
            }
            if(!isset($searchdata['sd_country'])){
                $searchdata['sd_country'] = '';
            }
            if(!isset($searchdata['sd_shopname'])){
                $searchdata['sd_shopname'] = '';
            }
            if(!isset($searchdata['startid'])){
                $searchdata['startid'] = '';
            }
            if(isset($_COOKIE["offset"])){
                $offset = json_decode($_COOKIE["offset"],true);
                if(isset($offset['amount'])){
                    $searchdata['queryamount'] = $offset['amount'];
                }else{
                    $searchdata['queryamount'] = 20;
                }
            }else{
                $searchdata['queryamount'] = 20;
            }

            if(isset($_COOKIE["latitude"]) && isset($_COOKIE["longitude"])){
                if($_COOKIE["latitude"] != '' && $_COOKIE["longitude"] != ''){
                    $searchdata['sd_lng'] = $_COOKIE["longitude"];
                    $searchdata['sd_lat'] = $_COOKIE["latitude"];
                }else{
                    if (!empty($_SERVER["HTTP_CLIENT_IP"])){
                        $ipaddress = $_SERVER["HTTP_CLIENT_IP"];
                    } else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
                        $ipaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
                    } else if(!empty($_SERVER["HTTP_X_FORWARDED"])){
                        $ipaddress = $_SERVER["HTTP_X_FORWARDED"];
                    } else if(!empty($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"])){
                        $ipaddress = $_SERVER["HTTP_X_CLUSTER_CLIENT_IP"];
                    } else if(!empty($_SERVER["HTTP_FORWARDED_FOR"])){
                        $ipaddress = $_SERVER["HTTP_FORWARDED_FOR"];
                    } else if(!empty($_SERVER["HTTP_FORWARDED"])){
                        $ipaddress = $_SERVER["HTTP_FORWARDED"];
                    } else {
                        $ipaddress = $_SERVER["REMOTE_ADDR"];
                    }
                    $curl = curl_init();

                    curl_setopt($curl, CURLOPT_URL, IPAPI.$ipaddress);
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

                    $strResponse = unserialize(curl_exec($curl));
                    curl_close($curl);
                    if(isset($strResponse['lat']) && isset($strResponse['lon'])){
                        $searchdata['sd_lng'] = round($strResponse["lon"],4);
                        $searchdata['sd_lat'] = round($strResponse["lat"],4);
                        setcookie('longitude',$searchdata['sd_lng'],0,'/','.iscarmg.com');
                        setcookie('latitude',$searchdata['sd_lat'],0,'/','.iscarmg.com');
                    }
                    else{
                        $searchdata['sd_lng'] = "121.517115";
                        $searchdata['sd_lat'] = "25.047908";
                    }
                }
            }else{
                if (!empty($_SERVER["HTTP_CLIENT_IP"])){
                    $ipaddress = $_SERVER["HTTP_CLIENT_IP"];
                } else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
                    $ipaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
                } else if(!empty($_SERVER["HTTP_X_FORWARDED"])){
                    $ipaddress = $_SERVER["HTTP_X_FORWARDED"];
                } else if(!empty($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"])){
                    $ipaddress = $_SERVER["HTTP_X_CLUSTER_CLIENT_IP"];
                } else if(!empty($_SERVER["HTTP_FORWARDED_FOR"])){
                    $ipaddress = $_SERVER["HTTP_FORWARDED_FOR"];
                } else if(!empty($_SERVER["HTTP_FORWARDED"])){
                    $ipaddress = $_SERVER["HTTP_FORWARDED"];
                } else {
                    $ipaddress = $_SERVER["REMOTE_ADDR"];
                }
                $curl = curl_init();

                curl_setopt($curl, CURLOPT_URL, IPAPI.$ipaddress);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

                $strResponse = unserialize(curl_exec($curl));
                curl_close($curl);

                if(isset($strResponse['lat']) && isset($strResponse['lon'])){
                    $searchdata['sd_lng'] = round($strResponse["lon"],4);
                    $searchdata['sd_lat'] = round($strResponse["lat"],4);
                    setcookie('longitude',$searchdata['sd_lng'],0,'/','.iscarmg.com');
                    setcookie('latitude',$searchdata['sd_lat'],0,'/','.iscarmg.com');
                }
                else{
                    $searchdata['sd_lng'] = "121.517115";
                    $searchdata['sd_lat'] = "25.047908";
                }
            }

            if(!isset($searchdata['distance'])){
                $searchdata['distance'] = 0;
                setcookie('distance',$searchdata['distance'],time()+3600,'/');
            }
            $shopdatalist = \App\Models\ICR_ShopData::GetShopDataListWithoutLimit($searchdata['cate'], $searchdata['sd_country'], $searchdata['sd_shopname'], $searchdata['startid'], $searchdata['queryamount'], $searchdata['sd_lat'], $searchdata['sd_lng'],$searchdata['distance']) ;
            if(count($shopdatalist) != 0){
                $shopdatalist = $this->TransDataToShopListArray($shopdatalist);
                setcookie('distance',$shopdatalist[count($shopdatalist)-1]['distance'],time()+3600,'/');
                // 計算使用者座標離每家特店的直線距離
                foreach ($shopdatalist as $key => $data) {
                    $dis = round($this->getdistance($searchdata['sd_lng'],$searchdata['sd_lat'],$data['sd_lng'],$data['sd_lat']),1);
                    $shopdatalist[$key]['dis'] = $dis;
                    if(!is_null($data['sd_shopphotopath'])){
                        $shopphotopath = $data['sd_shopphotopath'];
                        if(substr($shopphotopath,0,1) == '/'){
                            $shopdatalist[$key]['sd_shopphotopath'] = substr($shopphotopath,1);
                        }
                    }
                }
                $shopdatalist = $this->my_sort($shopdatalist,'dis');
            }


            $getdata['google'] = true;

/////////////// 設定meta值 ///////////////////////////////////////////////////////////////////////////////////////////////
            $metadata['author'] = 'isCar就是行';
            $metadata['description'] = '汽車特店家查詢、預約真便利。點數紅利錢包超方便。線上支付好安全。個資安全有保障';
            $metadata['og_title'] = 'isCar就是行-特約商列表';
            $metadata['og_url'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $metadata['og_image'] = 'http://'.$_SERVER['HTTP_HOST'].'/app/image/banner0929-03.jpg';
            $metadata['og_image_alt'] = 'banner0929-03.jpg';
            $metadata['og_description'] = '汽車特店家查詢、預約真便利。點數紅利錢包超方便。線上支付好安全。個資安全有保障';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // 可以用SQL的case when then
            $catelist = array(
                    ['sd_type'=>2,'sd_type_text'=>'洗車鍍膜'],
                    ['sd_type'=>3,'sd_type_text'=>'保養維修'],
                    // ['sd_type'=>4,'sd_type_text'=>'輪胎避震'],
                    // ['sd_type'=>5,'sd_type_text'=>'周邊配備'],
                );
            // 導頁功能
            if(isset($main["sat"]) && isset($_COOKIE["returnUrl"])){
                $returnUrl = $_COOKIE["returnUrl"];
                setcookie('returnUrl','',time()-3600);
                setcookie('back','/',time()+3600);
                return redirect($returnUrl);
            }
            if($getdata['md_clienttype'] == 1 || $getdata['md_clienttype'] == 2){
                if($_SERVER['REQUEST_URI'] == '/pm'){
                    if(isset($_COOKIE["branchData"])){
                        $this->saveModvrfToCookie();
                        return redirect('/Shop#!/shop/branch-main?sd_id='.$_COOKIE["branchData"]);
                    }else{
                        $this->saveModvrfToCookie();
                        return redirect('/Shop#!/shop/myBranchs');
                    }
                    if(isset($_COOKIE["shoplist"])){
                        if($_COOKIE["shoplist"] == 1){
                            return View::make('/app/pm/branch-cooperative',compact('shopdatalist','metadata','catelist','getdata'));
                        }else{
                            if(isset($_COOKIE["branchData"])){
                                return redirect('/Shop#!/shop/branch-main?sd_id='.$_COOKIE["branchData"]);
                            }else{
                                $this->saveModvrfToCookie();
                                return redirect('/Shop#!/shop/myBranchs');
                            }
                        }
                    }else{
                        $this->saveModvrfToCookie();
                        return redirect('/Shop#!/shop/myBranchs');
                    }
                }else{
                    return View::make('/app/pm/branch-cooperative',compact('shopdatalist','metadata','catelist','getdata'));
                }
            }else{
                return View::make('/app/pm/branch-cooperative',compact('shopdatalist','metadata','catelist','getdata'));
            }
        } catch (Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }

    /**
     * 導到[特約商細節]頁面
     */
    public function shopDataDetail() {
        $searchdata = Request::all();
        $getdata = null;
        $main = array();
        try {
            if(isset($_COOKIE[MAIN])){
                $main = json_decode($_COOKIE[MAIN],true);
            }
            if(isset($searchdata['sd_id'])){
                if(!$shopdata = \App\Models\ICR_ShopData::GetData($searchdata['sd_id'])){
                    // 要導到什麼頁面
                    return redirect('/');
                }
                if(isset($searchdata['tab'])){
                    $getdata['tab'] = $searchdata['tab'];
                }
                $shopdata = $shopdata[0];
                if(!is_null($shopdata['sd_advancedata'])){
                    $shopdata['sd_advancedata'] = json_decode($shopdata['sd_advancedata'],true);
                }

/////////////// 設定meta值 /////////////////////////////////////////////////////////////////////////////////////////////
                $getdata['google'] = true;
                $metadata['author'] = $shopdata['sd_shopname'];

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

                $metadata['og_url'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                if($shopdata['sd_shopphotopath'] != null && $shopdata['sd_shopphotopath'] != '' ){
                    $metadata['og_image'] = SDFIP.$shopdata['sd_shopphotopath'];
                    $metadata['og_image_alt'] = $shopdata['sd_shopphotopath'];
                }else{
                    $metadata['og_image'] = 'http://'.$_SERVER['HTTP_HOST'].'/app/image/imgDefault.png';
                    $metadata['og_image_alt'] = 'imgDefault.png';
                }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                if(isset($main["sat"])){
                    if(!\App\Services\MemberService::checkServiceAccessToken($main["sat"],$md_id,$messageCode)){
                        $longUrl = $metadata['og_url'];
                    }else{
                        $longUrl = $metadata['og_url'].'&md_id='.$md_id;
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
                return View::make('/app/pm/branch-info',compact('shopdata','getdata','metadata'));
            }

            return redirect('/');
        } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }

    /**
     * 導到[特約商評論]頁面
     */
    public function shopDataComment(){
        $searchdata = Request::all();
        $getdata = null;
        $main = array();
        try {
            if(isset($_COOKIE[MAIN])){
                $main = json_decode($_COOKIE[MAIN],true);
            }
            if(isset($searchdata['sd_id'])){
                if(!$shopdata = \App\Models\ICR_ShopData::GetData($searchdata['sd_id'])){
                    // 要導到什麼頁面
                    return redirect('/');
                }

                $shopdata = $shopdata[0];
                if(!is_null($shopdata['sd_advancedata'])){
                    $shopdata['sd_advancedata'] = json_decode($shopdata['sd_advancedata'],true);
                }

                //查詢店家問卷分數
                $getdata['sd_questionnaireresult'] = $shopdata['sd_questionnaireresult'];
                $getdata['sd_questiontotalaverage'] = $shopdata['sd_questiontotalaverage'];
                $querydata = \App\Models\ICR_ShopQuestionnaire_a::Query_QuestionnaireData($searchdata['sd_id']);
                if (($datacount = count($querydata)) == 0) {
                    $getdata['activemessage'] = null;
                }else{
                    $getdata['activemessage'] = $this->TransToActiveMessageList($querydata);
                    foreach ($getdata['activemessage'] as $key => $data) {
                        if($data['ssd_picturepath'] == '' || $data['ssd_picturepath'] == null){
                            $getdata['activemessage'][$key]['ssd_picturepath'] = '../app/image/general_user.png';
                        }
                    }
                }

                $getdata['datacount'] = $datacount;
                $getdata['sd_id'] = $shopdata['sd_id'];
                $getdata['sd_havebind'] = $shopdata['sd_havebind'];

/////////////// 設定meta值 /////////////////////////////////////////////////////////////////////////////////////////////
                $metadata['author'] = $shopdata['sd_shopname'];
                $metadata['og_title'] = $shopdata['sd_shopname'];
                $metadata['og_url'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                if($shopdata['sd_shopphotopath'] != null && $shopdata['sd_shopphotopath'] != '' ){
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
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                if(isset($main["sat"])){
                    if(!\App\Services\MemberService::checkServiceAccessToken($main["sat"],$md_id,$messageCode)){
                        throw new \Exception($messageCode);
                    }
                }


                return View::make('/app/pm/shopdata-comment',compact('getdata','metadata'));
            }

            return redirect('/');
        } catch (Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }

    /**
     * 無限加載call的function
     * @return [type] [下10筆資料]
     */
    public function getNestTenBranch(){
        $searchdata = Request::all();
        $shopdatalist = array();
        try {
            if(!isset($searchdata['cate'])){
                $searchdata['cate'] = '';
            }
            if(!isset($searchdata['sd_country'])){
                $searchdata['sd_country'] = '';
            }
            if(!isset($searchdata['sd_shopname'])){
                $searchdata['sd_shopname'] = '';
            }
            if(!isset($searchdata['startid'])){
                $searchdata['startid'] = '';
            }
            if(!isset($searchdata['queryamount'])){
                $searchdata['queryamount'] = 20;
            }
            if(!isset($searchdata['sd_lat'])){
                $searchdata['sd_lat'] = '';
            }
            if(!isset($searchdata['sd_lng'])){
                $searchdata['sd_lat'] = '';
            }
            if(!isset($searchdata['distance'])){
                $searchdata['distance'] = 0;
            }
            $shopdatalist = \App\Models\ICR_ShopData::GetShopDataListWithoutLimit($searchdata['cate'], $searchdata['sd_country'], $searchdata['sd_shopname'], $searchdata['startid'], $searchdata['queryamount'], $searchdata['sd_lat'], $searchdata['sd_lng'],$searchdata['distance']) ;

            if(count($shopdatalist) != 0){
                if($shopdatalist[0]['sd_id'] == $searchdata['startid']){
                    $datalist = array();
                    return $datalist;
                }
                $shopdatalist = $this->TransDataToShopListArray($shopdatalist);
                setcookie('distance',$shopdatalist[count($shopdatalist)-1]['distance'],time()+3600,'/');
                foreach ($shopdatalist as $key => $data) {
                    $dis = round($this->getdistance($searchdata['sd_lng'],$searchdata['sd_lat'],$data['sd_lng'],$data['sd_lat']),1);
                    $shopdatalist[$key]['dis'] = $dis;
                    if(!is_null($data['sd_shopphotopath'])){
                        $shopphotopath = $data['sd_shopphotopath'];
                        if(substr($shopphotopath,0,1) == '/'){
                            $shopdatalist[$key]['sd_shopphotopath'] = substr($shopphotopath,1);
                        }
                    }
                }
                $shopdatalist = $this->my_sort($shopdatalist,'dis');
                return $shopdatalist;
            }
            return $shopdatalist;

        } catch (Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }

/////////////////////////// 功能區 ////////////////////////////////////////////////////////

    /**
     * 將抓取到的特約商資料導成陣列並輸出
     * @param [type] $arraydata [description]
     */
    public function TransDataToShopListArray ($arraydata) {
      try {
        $post_expiredate = null;
        $group = array();
        foreach ( $arraydata as $row ) {
           $group[$row['sd_id']][] = $row;
        }
       //  foreach ($group as $rowlevel1) {
       //    $bool = false;
       //    foreach ($rowlevel1 as $rowlevel2) {
       //      if(is_null($rowlevel2['dbir_expiredate'])) {
       //          $bool = true;
       //          break;
       //      }
       //      //檢查是否刊登有效，有效的話才會顯現
       //      if($rowlevel2['ffbl_functiontype'] == 0 && $rowlevel2['ffbl_functionvalue'] == 1) {
       //         $bool = true;
       //      }
       //    }
       //    if ($bool == false) {
       //          unset($group[$count]);
       //    }
       //    $count =  $count + 1;
       // }
        foreach ($group as $rowlevels) {
           foreach ($rowlevels as $rowlevel2) {
              // $itemnameArray[] = ["dcil_itemname "     => $rowlevel2['dcil_itemname']];
              // $functionArray[] = ["ffbl_functiontype"  => $rowlevel2['ffbl_functiontype'],
              //                     "ffbl_functionvalue" => $rowlevel2['ffbl_functionvalue']];
              // if ((is_null($post_expiredate) && !is_null($rowlevel2['dbir_expiredate'])) || $post_expiredate < $rowlevel2['dbir_expiredate'] &&
              //     ($rowlevel2['ffbl_functiontype'] == 0 && $rowlevel2['ffbl_functionvalue'] == 1 )) {
              //    $post_expiredate = $rowlevel2['dbir_expiredate'];
              // }
              $sd_id = $rowlevel2['sd_id'];
              $sd_shopname = $rowlevel2['sd_shopname'];
              $star_sd_shopphotopath = SDFIP.$rowlevel2['sd_shopphotopath'];
              $sd_shoptel = $rowlevel2['sd_shoptel'];
              $sd_shopaddress = $rowlevel2['sd_shopaddress'];
              $sd_shopphotopath = $rowlevel2['sd_shopphotopath'];
              $sd_questiontotalaverage = $rowlevel2['sd_questiontotalaverage'];
              $sd_lat = $rowlevel2['sd_lat'];
              $sd_lng = $rowlevel2['sd_lng'];
              $sd_weeklystart = $rowlevel2['sd_weeklystart'];
              $sd_weeklyend = $rowlevel2['sd_weeklyend'];
              $sd_dailystart = $rowlevel2['sd_dailystart'];
              $sd_dailyend = $rowlevel2['sd_dailyend'];
              $sd_havebind = $rowlevel2['sd_havebind'];
              $distance = $rowlevel2['distance'];
           }

           $resultData[] = [
                            'sd_id'                   => $sd_id,
                            'sd_shopname'             => $sd_shopname,
                            'star_sd_shopphotopath'   => $star_sd_shopphotopath,
                            'sd_shoptel'              => $sd_shoptel,
                            'sd_shopaddress'          => $sd_shopaddress,
                            'sd_shopphotopath'        => $sd_shopphotopath,
                            'sd_questiontotalaverage' => $sd_questiontotalaverage,
                            'sd_lat' => $sd_lat,
                            'sd_lng' => $sd_lng,
                            'sd_weeklystart' => $sd_weeklystart,
                            'sd_weeklyend' => $sd_weeklyend,
                            'sd_dailystart' => $sd_dailystart,
                            'sd_dailyend' => $sd_dailyend,
                            'distance'             => $distance,
                            // 'post_expiredate'         => $post_expiredate,
                            // 'dcil_itemname'           => $itemnameArray,
                            // 'promote_function'        => $functionArray,
                            'sd_havebind' => $sd_havebind
                           ];
           // $itemnameArray = array();
           // $functionArray = array();
           // $post_expiredate = null;
        }
        return $resultData;
      } catch(\Exception $e) {
        \App\Models\ErrorLog::InsertData($e);
        return null;
      }
    }

    /**
     * 轉換問卷資料成陣列
     * @param type $questionnairedata
     */
    public function TransToActiveMessageList($questionnairedata) {

        if (count($questionnairedata) == 0) {
            return null;
        }

        foreach ($questionnairedata as $rowdata) {
            $resultdata[] = [
                'sqna_id' => $rowdata['sqna_id']
                , 'md_cname' => $rowdata['md_cname']
                , 'ssd_picturepath' => $rowdata['ssd_picturepath']
                , 'activetitle' => $this->QueryActiveTitle($rowdata['event_type'], $rowdata['event_id'])
                , 'sqna_message' => $rowdata['sqna_message']
                , 'ci_serno' => $rowdata['ci_serno']
                , 'sqnr_id' => $rowdata['sqnr_id']
                , 'sqnr_responsemessage' => $rowdata['sqnr_responsemessage']
                , 'sqna_last_update' => $rowdata['sqna_last_update']
                , 'sqnr_last_update' => $rowdata['sqnr_last_update']
            ];
        }

        return $resultdata;
    }

    /**
     * 查詢 問卷記錄所對應名稱
     * @param type $event_type
     * @param type $event_id
     */
    public function QueryActiveTitle($event_type, $event_id) {
        try {
            if ($event_type == '0') {
                return $this->QueryActiveTitle_0($event_id);
            } else if ($event_type == '1') {
                return $this->QueryActiveTitle_1($event_id);
            } else {
                return null;
            }
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 查詢 問卷記記錄所對應〔０：優惠券〕所對應的名稱
     * @param type $event_id 事件代碼
     */
    public function QueryActiveTitle_0($event_id) {
        try {
            $querydata = \App\Models\ICR_ShopCouponData_g::GetData_Event($event_id);
            if (count($querydata) == 0) {
                return null;
            }

            return $querydata[0]['scm_title'];
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 查詢 問卷記記錄所對應〔１：服務排隊〕所對應的名稱
     * @param type $event_id 事件代碼
     */
    private function QueryActiveTitle_1($event_id) {
        try {
            $querydata = \App\Models\ICR_ShopServiceQue_q::GetData_Event($event_id);
            if (count($querydata) == 0) {
                return null;
            }

            return $querydata[0]['ssqd_title'];
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 將模組驗證碼存到cookie
     */
    public function saveModvrfToCookie(){
        try {
            $memService = new \App\Services\MemberService;
            if (! $memService->query_salt($salt_no, $salt)) {
                throw new \Exception($messageCode);
            }
            $modvrf = urlencode(base64_encode($salt_no.'_'.hash('sha256', MODACCWEB.MODPWDWEB.$salt)));
            setcookie('modvrf',$modvrf,0,'/');
            return true;
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
                )
            );
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

    /**
     * 根據傳入的$arrays裡面的某個欄位$sort_key做排列
     * @param  [array]  $arrays     [需要排序的陣列]
     * @param  [string] $sort_key   [排序依據]
     * @param  [string] $sort_order [ASC or DESC]
     * @param  [string] $sort_type  [字串或是數字]
     */
    public function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){
        if(is_array($arrays)){
            foreach ($arrays as $array){
                if(is_array($array )){
                    $key_arrays[] = $array[$sort_key];
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
        return $arrays;
    }

    /**
     * 計畫兩個座標的直線距離
     * @param  [string] $lng1 [使用者所在位置經度]
     * @param  [string] $lat1 [使用者所在位置緯度]
     * @param  [string] $lng2 [附近店家經度]
     * @param  [string] $lat2 [附近店家緯度]
     * @return [string]       [兩點距離]
     */
    public function getdistance($lng1,$lat1,$lng2,$lat2){
        //將角度轉換為弧度
        $radLat1=deg2rad($lat1);
        $radLat2=deg2rad($lat2);
        $radLng1=deg2rad($lng1);
        $radLng2=deg2rad($lng2);
        $a=$radLat1-$radLat2;
        $b=$radLng1-$radLng2;
        $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137;//6378.137為地球的半徑
        return $s;
    }

}
