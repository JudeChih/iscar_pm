<?php

namespace App\Http\Controllers\APIControllers;
use App\Http\Controllers\Controller;

class ShopServiceController extends Controller {

    /** shopservicelistquery	商家服務排隊項目列表查詢 * */
    function shopservicelistquery() {
        $ShopServiceListQuery = new ShopService\ShopServiceListQuery;
        return $ShopServiceListQuery -> shopservicelistquery();
    }

    /** shopservicecontentquery	商家服務排隊項目內容查詢 * */
    function shopservicecontentquery() {
        $ShopServiceContentQuery = new ShopService\ShopServiceContentQuery;
        return $ShopServiceContentQuery -> shopservicecontentquery();
    }

    /** shopservicequeask	用戶選擇服務項目進行排隊 * */
    function shopservicequeask() {
        $ShopServiceQueAsk = new ShopService\ShopServiceQueAsk;
        return $ShopServiceQueAsk -> shopservicequeask();
    }

    /** shopservicemanage	商家管理自有供排隊服務項目 * */
    function shopservicemanage() {
        $ShopServiceManage = new ShopService\ShopServiceManage;
        return $ShopServiceManage -> shopservicemanage();
    }

    /** shopservicefunctionadjust	商家調整服務排隊相關基本數據 * */
    function shopservicefunctionadjust() {
        $ShopServiceFunctionAdjust = new ShopService\ShopServiceFunctionAdjust;
        return $ShopServiceFunctionAdjust -> shopservicefunctionadjust();
    }

    /** shopservicequequery	查詢指定商家當前已排隊狀況 * */
    function shopservicequequery() {
        $ShopServiceQueQuery = new ShopService\ShopServiceQueQuery;
        return $ShopServiceQueQuery -> shopservicequequery();
    }

    /** shopservicescan	商家掃描用戶QR憑證 * */
    function shopservicescan() {
        $ShopServiceScan = new ShopService\ShopServiceScan;
        return $ShopServiceScan -> shopservicescan();
    }

    /** shopserviceexec	商家掃描QR後開始服務 * */
    function shopserviceexec() {
        $ShopServiceExec = new ShopService\ShopServiceExec;
        return $ShopServiceExec -> shopserviceexec();
    }

    /** shopqueuenoshow	商家設置未到用戶為過號用戶 * */
    function shopqueuenoshow() {
        $ShopQueueNoShow = new ShopService\ShopQueueNoShow;
        return $ShopQueueNoShow -> shopqueuenoshow();
    }

    /** shopservicecallup	商家呼叫到號用戶開始服務 * */
    function shopservicecallup() {
        $ShopServiceCallup = new ShopService\ShopServiceCallUp;
        return $ShopServiceCallup -> shopservicecallup();
    }

    /** shoponoffoperation	商家通知伺服器當日服務已終止 * */
    function shoponoffoperation() {
        $ShopOnOffOperation = new ShopService\ShopOnOffOperation;
        return $ShopOnOffOperation -> shoponoffoperation();
    }

    /** shopqueueovercall	商家呼叫過號用戶前往接受服務 * */
    function shopqueueovercall() {
        $ShopQueueOverCall = new ShopService\ShopQueueOverCall;
        return $ShopQueueOverCall -> shopqueueovercall();
    }

    /** shopservicequerecorver	用戶取得所有排隊記錄 * */
    function shopservicequerecorver() {
        $ShopServiceQueRecorver = new ShopService\ShopServiceQueRecorver;
        return $ShopServiceQueRecorver -> shopservicequerecorver();
    }

    /** shopservicequeabandom	用戶放棄排隊 * */
    function shopservicequeabandom() {
        $ShopServiceQueAbandom = new ShopService\ShopServiceQueAbandom;
        return $ShopServiceQueAbandom -> shopservicequeabandom();
    }
    /** shopserviceclientreply 被叫號用戶回覆前往狀況 * */
    function shopserviceclientreply() {
        $ShopServiceClientReply = new ShopService\ShopServiceClientReply;
        return $ShopServiceClientReply -> shopserviceclientreply();
    }
}
