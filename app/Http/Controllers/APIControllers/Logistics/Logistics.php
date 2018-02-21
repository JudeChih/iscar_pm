<?php

namespace App\Http\Controllers\APIControllers\Logistics;
use DB;

/**Logistics * */
class Logistics {


       function Insert_MsLog_1104( $md_id, $scg_id, $amount ) {
       try {
            $uml_id = \App\Library\Commontools::NewGUIDWithoutDash();
            $appService = new \App\Services\AppService;
            $savadata['uml_id'] = $uml_id;
            $savadata['uml_type'] = 1104;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = '有一筆新訂單，訂單編號 : ' .$scg_id.' ，訂單金額 : ' . $amount . '，請前往查看處理。';
            //$savadata['uml_object'] = null;
            //$savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
            return  $appService->PostMessageLog($savadata);
       } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }

    function Insert_MsLog_1105( $md_id, $sd_shopname, $scm_title, $amount) {
       try {
            $uml_id = \App\Library\Commontools::NewGUIDWithoutDash();
            $appService = new \App\Services\AppService;
            $savadata['uml_id'] = $uml_id;
            $savadata['uml_type'] = 1105;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = '於'. $sd_shopname.' 購買'.$scm_title.'完成,本次消費金額'. $amount.'元。';
            //$savadata['uml_object'] = null;
            //$savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
            return  $appService->PostMessageLog($savadata);
       } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }

     function Insert_MsLog_1106( $md_id, $sd_shopname, $scm_title, $amount) {
       try {
            $uml_id = \App\Library\Commontools::NewGUIDWithoutDash();
            $appService = new \App\Services\AppService;
            $savadata['uml_id'] = $uml_id;
            $savadata['uml_type'] = 1106;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = '於'. $sd_shopname.' 購買'.$scm_title.'，已申請退款中,本次退款金額'. $amount.'元。';
            //$savadata['uml_object'] = null;
            //$savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
            return  $appService->PostMessageLog($savadata);
       } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }


     function Insert_MsLog_1107( $md_id, $sd_shopname, $scm_title) {
       try {
            $uml_id = \App\Library\Commontools::NewGUIDWithoutDash();
            $appService = new \App\Services\AppService;
            $savadata['uml_id'] = $uml_id;
            $savadata['uml_type'] = 1107;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = '於'. $sd_shopname.' 購買'.$scm_title.'，已取消付款。';
            //$savadata['uml_object'] = null;
            //$savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
            return  $appService->PostMessageLog($savadata);
       } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }

      function Insert_MsLog_1108( $md_id, $scg_id, $amount ) {
       try {
            $uml_id = \App\Library\Commontools::NewGUIDWithoutDash();
            $appService = new \App\Services\AppService;
            $savadata['uml_id'] = $uml_id;
            $savadata['uml_type'] = 1108;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = '訂單，訂單編號 : ' .$scg_id.' ，已申請退款，,本次退款金額'. $amount.'元。';
            //$savadata['uml_object'] = null;
            //$savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
            return  $appService->PostMessageLog($savadata);
       } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }

    function Insert_MsLog_1109( $md_id, $scg_id ) {
       try {
            $uml_id = \App\Library\Commontools::NewGUIDWithoutDash();
            $appService = new \App\Services\AppService;
            $savadata['uml_id'] = $uml_id;
            $savadata['uml_type'] = 1109;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = '訂單，訂單編號 : ' .$scg_id.' ，已取消付款。';
            //$savadata['uml_object'] = null;
            //$savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
            return  $appService->PostMessageLog($savadata);
       } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }
    
    
    function Insert_MsLog_1111( $md_id,  $scm_title ,$giftpoint) {
       try {
            $uml_id = \App\Library\Commontools::NewGUIDWithoutDash();
            $appService = new \App\Services\AppService;
            $savadata['uml_id'] = $uml_id;
            $savadata['uml_type'] = 1111;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = '因分享商品 '.$scm_title.' 完成交易,贈送禮點'.$giftpoint.'點 。' ;
            //$savadata['uml_object'] = null;
            //$savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
            return  $appService->PostMessageLog($savadata);
       } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }
    
     function Insert_MsLog_1112( $md_id, $scg_id, $amount ) {
       try {
            $uml_id = \App\Library\Commontools::NewGUIDWithoutDash();
            $appService = new \App\Services\AppService;
            $savadata['uml_id'] = $uml_id;
            $savadata['uml_type'] = 1112;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = '有一筆新訂單，訂單編號 : ' .$scg_id.' ，訂單特點數額 : ' . $amount . '，請前往查看處理。';
            //$savadata['uml_object'] = null;
            //$savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
            return  $appService->PostMessageLog($savadata);
       } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }

    function Insert_MsLog_1113( $md_id, $sd_shopname, $scm_title, $amount) {
       try {
            $uml_id = \App\Library\Commontools::NewGUIDWithoutDash();
            $appService = new \App\Services\AppService;
            $savadata['uml_id'] = $uml_id;
            $savadata['uml_type'] = 1113;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = '於'. $sd_shopname.' 購買'.$scm_title.'完成,本次消費特點數額'. $amount.'元。';
            //$savadata['uml_object'] = null;
            //$savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
            return  $appService->PostMessageLog($savadata);
       } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }
    
    
    function Insert_MsLog_1114( $md_id,  $scm_title ,$giftpoint) {
       try {
            $uml_id = \App\Library\Commontools::NewGUIDWithoutDash();
            $appService = new \App\Services\AppService;
            $savadata['uml_id'] = $uml_id;
            $savadata['uml_type'] = 1111;
            $savadata['md_id'] = $md_id;
            $savadata['uml_message'] = '因購買商品 '.$scm_title.' 完成交易,回饋禮點'.$giftpoint.'點 。' ;
            //$savadata['uml_object'] = null;
            //$savadata['uml_pic'] = null;
            $savadata['uml_status'] = 0;
            return  $appService->PostMessageLog($savadata);
       } catch (\Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
       }
    }
}
