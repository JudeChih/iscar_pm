<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */
  

// Route::get('/test','ViewControllers\ShopDataController@test');

Route::get('getdatetime', function() {
    echo 'Use Route<br>';
    date_default_timezone_set("Asia/Taipei");
    echo date("Y/m/d h:i:s");
});



//Route::get('/', function () {
//    return redirect('Shop');
//});

Route::get('/', function () {
    return redirect('pm');
});


/* --------------------------------------------------------------------------

                                 特約商

-------------------------------------------------------------------------- */
/* 票券訂單列表 */
Route::get('/shop-salesoverview', function () {
    return view('app/Shop/report/shop-salesoverview');
});
/* 特約商報表 *//* 阿志新增20171214 */ //前台的
Route::get('/shop-table-front', function () {
    return view('app/Shop/report/shop-table-front');
});
/* 報表列印 *//* 阿志新增20171214 */ //前台的
Route::get('/printShopTableFront', function () {
    return view('app/Shop/report/printShopTableFront');
});
/* 特約商報表 *//* 阿志新增20171011 */ //後台的
Route::get('/Shop/shop-table', function () {
    return view('app/Shop/report/shop-table');
});
/* 報表列印 *//* 阿志新增20171011 */ //後台的
Route::get('/Shop/printShopTable', function () {
    return view('app/Shop/report/printShopTable');
});
/* 商品細節頁 *//* 阿志新增20171031 */
Route::get('/pm/shopcoupon-info','ViewControllers\ShopCouponController@shopCouponDetail');
/* 特約商細節頁 *//* 阿志新增20171106 */
Route::get('/pm/branch-info','ViewControllers\ShopDataController@shopDataDetail');
/* 商品列表 *//* 阿志新增20171107 */
Route::get('/pm/shopcoupon-list','ViewControllers\ShopCouponController@shopCouponList');
/* 特約商細節頁 *//* 阿志新增20171110 */
Route::get('/pm/branch-cooperative','ViewControllers\ShopDataController@branchCooperative');
Route::post('/pm/branch-cooperative','ViewControllers\ShopDataController@getNestTenBranch');
/* 特約商評論頁 *//* 阿志新增20171123 */
Route::get('/pm/shopdata-comment','ViewControllers\ShopDataController@shopDataComment');
/* 立即購買按鈕導頁 *//* 阿志新增20171110 */
Route::get('/pm/shop-buy-data','ViewControllers\ShopCouponController@shopBuyData');

/*阿志新增20171103*******************************************/
/* sitemap *//* 阿志新增20171103 */
Route::get('/sitemap', 'ViewControllers\SitemapController@index');
/* 測試sitemap */
Route::get('/google663ac77c0dc47f66.html', function(){
    return view('google663ac77c0dc47f66');
});
Route::get('/sitemap/xml-sitemap.xsl',function(){
    return view('sitemap/xml-sitemap');
});
/************************************************************/


Route::get('/pm','ViewControllers\ShopDataController@branchCooperative');
Route::post('/pm','ViewControllers\ShopDataController@getNestTenBranch');

Route::get('/Shop', function () {
    return view('app/Shop/index');
});

/* 轉址接值頁 */
Route::get('/Shop/transform', function () {
    return view('app/Shop/transform');
});

/* 特約商列表 */
//Route::get('/branch-cooperative', function () {
//    return view('app/Shop/branch-cooperative');
//});

/* 特約商內容 */
//Route::get('/branch-info', function () {
//    return view('app/Shop/branch-info');
//});

/* 我的店家列表 */
Route::get('/myBranchs', function () {
    return view('app/Shop/myBranchs');
});

/* 我的店家列表 */
Route::get('/shop/myBranchs', function () {
    return view('app/Shop/myBranchs');
});

/* 商家今日預約排隊 */
Route::get('/shop/branch-main', function () {
    return view('app/Shop/branch-main');
});

/* 商家資訊設置 */
Route::get('/shop-data-config', function () {
    return view('app/Shop/shop-data-config');
});

/* 商家資訊編輯 */
Route::get('/shop/branch-info-edit', function () {
    return view('app/Shop/branch-info-edit');
});




/* 預約服務紀錄 */
Route::get('/branch-reservation-record', function () {
    return view('app/Shop/branch-reservation-record');
});

/* 未回覆預約清單 */
Route::get('/no-reply-reservation', function () {
    return view('app/Shop/no-reply-reservation');
});

/* 排隊紀錄 */
Route::get('/service-record', function () {
    return view('app/Shop/service-record');
});

/* 金流設置 */
Route::get('/pay-setting', function () {
    return view('app/Shop/pay-setting');
});

/* 銷售/服務紀錄 */
Route::get('/shop-records', function () {
    return view('app/Shop/shop-records');
});

/* 帳務結算紀錄 */
Route::get('/settle-accounts', function () {
    return view('app/Shop/settle-accounts');
});

/* 帳務結算一覽 */
Route::get('/settle-accounts-content', function () {
    return view('app/Shop/settle-accounts-content');
});

/* 銷售對帳表 */
Route::get('/settle-accounts-table', function () {
    return view('app/Shop/settle-accounts-table');
});

/* 票券訂單列表 */
Route::get('/coupon-order-forms', function () {
    return view('app/Shop/coupon-order-forms');
});

/* 商品訂單管理 */
Route::get('/order-form-management', function () {
    return view('app/Shop/order-form-management');
});

/* 商品訂單列表 */
Route::get('/order-forms', function () {
    return view('app/Shop/order-forms');
});

/* 商品訂單內容 */
Route::get('/order-form-info', function () {
    return view('app/Shop/order-form-info');
});

/* 出貨單列印 */
Route::get('/print-order-form', function () {
    return view('app/Shop/print-order-form');
});

/* 檢貨回報 */
Route::get('/pick-report', function () {
    return view('app/Shop/pick-report');
});

/* 物流資訊 */
Route::get('/shop-logistics', function () {
    return view('app/Shop/shop-logistics');
});

/* 購買資訊 */
Route::get('/shop-buy-data', function () {
    return view('app/Shop/shop-buy-data');
});



/* 店員管理 */
Route::get('/shop/staff-management', function () {
    return view('app/Shop/staff-management');
});

/* 排隊管理 */
Route::get('/shop/shopservice-management', function () {
    return view('app/Shop/shopservice-management');
});

/* 商品/預約管理 */
Route::get('/shopcoupon-main', function () {
    return view('app/Shop/shopcoupon-main');
});

/* 商品管理 */
Route::get('/shop/shopcoupon-management', function () {
    return view('app/Shop/shopcoupon-management');
});

/* 暫停預約管理 */
Route::get('/appointment-management', function () {
    return view('app/Shop/appointment-management');
});

/* 評價管理 */
Route::get('/shop/questionnaire-management', function () {
    return view('app/Shop/questionnaire-management');
});

/* 服務紀錄 */
Route::get('/shop/shop-record', function () {
    return view('app/Shop/shop-record');
});

/* 會員列表 */
Route::get('/shop/client-list', function () {
    return view('app/Shop/client-list');
});

/* 紅利紀錄 */
Route::get('/bonus-record', function () {
    return view('app/Shop/bonus-record');
});

/* 消費紀錄 */
Route::get('/coupon-record', function () {
    return view('app/Shop/coupon-record');
});

/* 我要開店 */
Route::get('/application-shop', function () {
    return view('app/Shop/application-shop');
});

/* 排隊資訊 */
Route::get('/shop/shopservice-info', function () {
    return view('app/Shop/shopservice-info');
});

/* 排隊資訊編輯 */
Route::get('/shop/shopservice-edit', function () {
    return view('app/Shop/shopservice-edit');
});

/* 排隊服務設置 */
Route::get('/shop/shopservice-setting', function () {
    return view('app/Shop/shopservice-setting');
});

/* 今日過號 */
Route::get('/shop/shopservice-pass', function () {
    return view('app/Shop/shopservice-pass');
});

/* 折扣券資訊 */
//Route::get('/shop/shopcoupon-info', function () {
//    return view('app/Shop/shopcoupon-info');
//});

/* 新增折扣券 */
Route::get('/shop/add-shopcoupon', function () {
    return view('app/Shop/add-shopcoupon');
});

/* 折扣券編輯 */
Route::get('/shop/shopcoupon-edit', function () {
    return view('app/Shop/shopcoupon-edit');
});

/* 活動預約 */
Route::get('/shop/shopcoupon-reservation', function () {
    return view('app/Shop/shopcoupon-reservation');
});

/* 我的最愛 */
Route::get('/shop/favorite', function () {
    return view('app/Shop/favorite');
});

/* 進階查詢 */
Route::get('/branch-region-search', function () {
    return view('app/Shop/branch-region-search');
});

/* 查詢結果 */
Route::get('/shop-search-result', function () {
    return view('app/Shop/shop-search-result');
});

/* 周邊商家 */
Route::get('/around-search-result', function () {
    return view('app/Shop/around-search-result');
});


/* 活動評論 */
Route::get('/shop-questionnaire', function () {
    return view('app/Shop/shop-questionnaire');
});

/* 紅利管理 */
Route::get('/shop/bonus-management', function () {
    return view('app/Shop/bonus-management');
});

/* 紅利編輯 */
Route::get('/shop/bonus-edit', function () {
    return view('app/Shop/bonus-edit');
});




/* 優惠推送 */
Route::get('/message_push/message-main', function () {
    return view('app/Shop/message_push/message-main');
});

/* 推送資訊內容 */
Route::get('/message_push/message-info', function () {
    return view('app/Shop/message_push/message-info');
});

/* 推送紀錄 */
Route::get('/message_push/push-record', function () {
    return view('app/Shop/message_push/push-record');
});

/* 篩選推送對象 */
Route::get('/message_push/search-member', function () {
    return view('app/Shop/message_push/search-member');
});

/* 選擇推送對象 */
Route::get('/message_push/select-member', function () {
    return view('app/Shop/message_push/select-member');
});

/* 快選訊息管理 */
Route::get('/quick-msg-management', function () {
    return view('app/Shop/quick-msg-management');
});




/* 宮廟內容 */
Route::get('/temple-info', function () {
    return view('app/Shop/temple-info');
});

/* 大甲鎮瀾宮 */
Route::get('/temple-info-2', function () {
    return view('app/Shop/temple-info-2');
});

/* 鹿港天后宮 */
Route::get('/temple-info-3', function () {
    return view('app/Shop/temple-info-3');
});


/* 支付內容 */
Route::get('/pay-info', function () {
    return view('app/Shop/pay-info');
});

/* 支付完成 */
Route::get('/pay-success', function () {
    return view('app/Shop/pay-success');
});

/* 支付完成 */
Route::post('/pay-success', function () {
    return redirect('/#!/pay-success');
});

/* 親屬清單 */
Route::get('/relatives-list', function () {
    return view('app/Shop/relatives-list');
});

/* 新增親屬資料 */
Route::get('/add-relatives', function () {
    return view('app/Shop/add-relatives');
});


/* 圖片上傳 */
Route::post('/upload', function () {
    return view('app/Shop/upload');
});


/* 商家綁定 */
Route::get('/branch-binding', function () {
    return view('app/Shop/branch-binding');
});


/* 祈福報表 */
Route::get('/temple-table', function () {
    return view('app/Shop/temple-table');
});


/* 特約商地圖 */
Route::get('/map', function () {
    return view('app/Shop/map');
});

/* --------------------------------------------------------------------------

                                特約商後台

-------------------------------------------------------------------------- */

/* 後台登入 */
Route::get('/Shop/webend_admin/login', function () {
    return view('app/Shop/webend_admin/login/login');
});

/* 轉址接值頁 */
Route::get('/Shop/webend_admin/transform', function () {
    return view('app/Shop/webend_admin/transform');
});

/* 後台主頁 */
Route::get('/Shop/webend_admin', function () {
    return view('app/Shop/webend_admin/index');
});

/* 特約商列表 */
Route::get('/Shop/branch-cooperative', function () {
    return view('app/Shop/webend_admin/branch-cooperative');
});

/* 特約商內容 */
Route::get('/Shop/branch-info', function () {
    return view('app/Shop/webend_admin/branch-info');
});

/* 我的店家列表 */
Route::get('/Shop/myBranchs', function () {
    return view('app/Shop/webend_admin/myBranchs');
});

/* 我的店家列表 */
Route::get('/Shop/shop/myBranchs', function () {
    return view('app/Shop/webend_admin/myBranchs');
});

/* 商家今日預約排隊 */
Route::get('/Shop/shop/branch-main', function () {
    return view('app/Shop/webend_admin/branch-main');
});

/* 商家資訊設置 */
Route::get('/Shop/shop-data-config', function () {
    return view('app/Shop/webend_admin/shop-data-config');
});

/* 商家資訊編輯 */
Route::get('/Shop/shop/branch-info-edit', function () {
    return view('app/Shop/webend_admin/branch-info-edit');
});


/* 預約服務紀錄 */
Route::get('/Shop/branch-reservation-record', function () {
    return view('app/Shop/webend_admin/branch-reservation-record');
});

/* 未回覆預約清單 */
Route::get('/Shop/no-reply-reservation', function () {
    return view('app/Shop/webend_admin/no-reply-reservation');
});

/* 排隊紀錄 */
Route::get('/Shop/service-record', function () {
    return view('app/Shop/webend_admin/service-record');
});

/* 金流設置 */
Route::get('/Shop/pay-setting', function () {
    return view('app/Shop/webend_admin/pay-setting');
});

/* 銷售/服務紀錄 */
Route::get('/Shop/shop-records', function () {
    return view('app/Shop/webend_admin/shop-records');
});

/* 帳務結算紀錄 */
Route::get('/Shop/settle-accounts', function () {
    return view('app/Shop/webend_admin/settle-accounts');
});

/* 帳務結算一覽 */
Route::get('/Shop/settle-accounts-content', function () {
    return view('app/Shop/webend_admin/settle-accounts-content');
});

/* 銷售對帳表 */
Route::get('/Shop/settle-accounts-table', function () {
    return view('app/Shop/webend_admin/settle-accounts-table');
});

/* 商品訂單管理 */
Route::get('/Shop/order-form-management', function () {
    return view('app/Shop/webend_admin/order-form-management');
});

/* 票券訂單列表 */
Route::get('/Shop/coupon-order-forms', function () {
    return view('app/Shop/webend_admin/coupon-order-forms');
});

/* 商品訂單列表 */
Route::get('/Shop/order-forms', function () {
    return view('app/Shop/webend_admin/order-forms');
});

/* 商品訂單內容 */
Route::get('/Shop/order-form-info', function () {
    return view('app/Shop/webend_admin/order-form-info');
});

/* 出貨單列印 */
Route::get('/Shop/print-order-form', function () {
    return view('app/Shop/webend_admin/print-order-form');
});

/* 檢貨回報 */
Route::get('/Shop/pick-report', function () {
    return view('app/Shop/webend_admin/pick-report');
});




/* 店員管理 */
Route::get('/Shop/shop/staff-management', function () {
    return view('app/Shop/webend_admin/staff-management');
});

/* 預約管理 */
Route::get('/Shop/shop/shopservice-management', function () {
    return view('app/Shop/webend_admin/shopservice-management');
});


/* 商品/預約管理 */
Route::get('/Shop/shopcoupon-main', function () {
    return view('app/Shop/webend_admin/shopcoupon-main');
});

/* 商品管理 */
Route::get('/Shop/shop/shopcoupon-management', function () {
    return view('app/Shop/webend_admin/shopcoupon-management');
});

/* 暫停預約管理 */
Route::get('/Shop/appointment-management', function () {
    return view('app/Shop/webend_admin/appointment-management');
});

/* 評價管理 */
Route::get('/Shop/shop/questionnaire-management', function () {
    return view('app/Shop/webend_admin/questionnaire-management');
});

/* 服務紀錄 */
Route::get('/Shop/shop/shop-record', function () {
    return view('app/Shop/webend_admin/shop-record');
});

/* 會員列表 */
Route::get('/Shop/shop/client-list', function () {
    return view('app/Shop/webend_admin/client-list');
});

/* 紅利紀錄 */
Route::get('/Shop/bonus-record', function () {
    return view('app/Shop/webend_admin/bonus-record');
});

/* 消費紀錄 */
Route::get('/Shop/coupon-record', function () {
    return view('app/Shop/webend_admin/coupon-record');
});

/* 我要開店 */
Route::get('/Shop/application-shop', function () {
    return view('app/Shop/webend_admin/application-shop');
});

/* 排隊資訊 */
Route::get('/Shop/shop/shopservice-info', function () {
    return view('app/Shop/webend_admin/shopservice-info');
});

/* 排隊資訊編輯 */
Route::get('/Shop/shop/shopservice-edit', function () {
    return view('app/Shop/webend_admin/shopservice-edit');
});

/* 排隊服務設置 */
Route::get('/Shop/shop/shopservice-setting', function () {
    return view('app/Shop/webend_admin/shopservice-setting');
});

/* 今日過號 */
Route::get('/Shop/shop/shopservice-pass', function () {
    return view('app/Shop/webend_admin/shopservice-pass');
});

/* 折扣券資訊 */
Route::get('/Shop/shop/shopcoupon-info', function () {
    return view('app/Shop/webend_admin/shopcoupon-info');
});

/* 新增折扣券 */
Route::get('/Shop/shop/add-shopcoupon', function () {
    return view('app/Shop/webend_admin/add-shopcoupon');
});

/* 折扣券編輯 */
Route::get('/Shop/shop/shopcoupon-edit', function () {
    return view('app/Shop/webend_admin/shopcoupon-edit');
});

/* 活動預約 */
Route::get('/Shop/shop/shopcoupon-reservation', function () {
    return view('app/Shop/webend_admin/shopcoupon-reservation');
});

/* 我的最愛 */
Route::get('/Shop/shop/favorite', function () {
    return view('app/Shop/webend_admin/favorite');
});

/* 進階查詢 */
Route::get('/Shop/branch-region-search', function () {
    return view('app/Shop/webend_admin/branch-region-search');
});

/* 查詢結果 */
Route::get('/Shop/shop-search-result', function () {
    return view('app/Shop/webend_admin/shop-search-result');
});

/* 周邊商家 */
Route::get('/Shop/around-search-result', function () {
    return view('app/Shop/webend_admin/around-search-result');
});


/* 紅利管理 */
Route::get('/Shop/shop/bonus-management', function () {
    return view('app/Shop/webend_admin/bonus-management');
});

/* 紅利編輯 */
Route::get('/Shop/shop/bonus-edit', function () {
    return view('app/Shop/webend_admin/bonus-edit');
});


/* 優惠推送 */
Route::get('/Shop/message_push/message-main', function () {
    return view('app/Shop/webend_admin/message_push/message-main');
});

/* 推送資訊內容 */
Route::get('/Shop/message_push/message-info', function () {
    return view('app/Shop/webend_admin/message_push/message-info');
});

/* 推送紀錄 */
Route::get('/Shop/message_push/push-record', function () {
    return view('app/Shop/webend_admin/message_push/push-record');
});

/* 篩選推送對象 */
Route::get('/Shop/message_push/search-member', function () {
    return view('app/Shop/webend_admin/message_push/search-member');
});

/* 選擇推送對象 */
Route::get('/Shop/message_push/select-member', function () {
    return view('app/Shop/webend_admin/message_push/select-member');
});

/* 快選訊息管理 */
Route::get('/Shop/quick-msg-management', function () {
    return view('app/Shop/webend_admin/quick-msg-management');
});


/* 圖片上傳 */
Route::post('/Shop/upload', function () {
    return view('app/Shop/webend_admin/upload');
});


/* 祈福報表 */
Route::get('/Shop/temple-table', function () {
    return view('app/Shop/webend_admin/temple-table');
});

/* 報表列印 */
Route::get('/Shop/printTable', function () {
    return view('app/Shop/webend_admin/printTable');
});


/* --------------------------------------------------------------------------

                             登入接收器

-------------------------------------------------------------------------- */

/*前台*/
Route::get('/pm-transform', function () {
    return view('app/pm-transform');
});

/*後台*/
Route::get('/pm_b-transform', function () {
    return view('app/pm_b-transform');
});

