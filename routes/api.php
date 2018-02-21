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







/* * *APICheck** */
//APICheck 曾測API是否正常運作。
Route::post('api/apicheck', 'APIControllers\APICheckController@APICheck');

/* * *Account** */
//userbookmarkrecorver  檢查傳入之會員書籤更新日期，同步會員app端訊息記錄
Route::post('account/userbookmarkrecorver', 'APIControllers\AccountController@userbookmarkrecorver');
//userbookmarkupdate  接收會員書籤更新記錄，回存伺服DB
Route::post('account/userbookmarkupdate', 'APIControllers\AccountController@userbookmarkupdate');

/**
 * SHOP
 */
/* shoplistquery  取用對應類別商家資料列表回覆 */
Route::post('shop/shoplistquery', 'APIControllers\ShopController@shoplistquery');
/* shopcontentquery 取用商家內容 */
Route::post('shop/shopcontentquery', 'APIControllers\ShopController@shopcontentquery');
/* shopdatabind 商家用戶認證功能 */
Route::post('shop/shopdatabind', 'APIControllers\ShopController@shopdatabind');
/* shopbasicdataupdate  商家資料修改功能 */
Route::post('shop/shopbasicdataupdate', 'APIControllers\ShopController@shopbasicdataupdate');
/* shopadvanceupdate  更新商家進階功能內容 */
Route::post('shop/shopadvanceupdate', 'APIControllers\ShopController@shopadvanceupdate');
/* query_shopmember  查詢商家所有會員資料 */
Route::post('shop/query_shopmember', 'APIControllers\ShopController@queryshopmember');
/* salesagentlogin  iscar業務註冊功能 */
Route::post('shop/salesagentlogin', 'APIControllers\ShopController@salesagentlogin');

/* * SHOP Coupon */
/* shopcouponlistquery  商家優惠活動券列表查詢 */
Route::post('shopcoupon/shopcouponlistquery', 'APIControllers\ShopCouponController@shopcouponlistquery');
/* shopcouponcontentquery 商家優惠活動券內容查詢 */
Route::post('shopcoupon/shopcouponcontentquery', 'APIControllers\ShopCouponController@shopcouponcontentquery');
/* shopcouponget  商家優惠活動券取用 */
Route::post('shopcoupon/shopcouponget', 'APIControllers\ShopCouponController@shopcouponget');
/* shopcouponreservationinfo  商家優惠活動券預約時段查詢 */
Route::post('shopcoupon/shopcouponreservationinfo', 'APIControllers\ShopCouponController@shopcouponreservationinfo');
/* shopcouponreservationbook  商家優惠活動券預約 */
Route::post('shopcoupon/shopcouponreservationbook', 'APIControllers\ShopCouponController@shopcouponreservationbook');
/* shopcouponmanager  商家優惠活動券管理 */
Route::post('shopcoupon/shopcouponmanager', 'APIControllers\ShopCouponController@shopcouponmanager');
/* shopcouponreservationquery 商家優惠活動券已預約項目查詢 */
Route::post('shopcoupon/shopcouponreservationquery', 'APIControllers\ShopCouponController@shopcouponreservationquery');
/* shopcouponrecorver 用戶已索取之商家優惠券項目回復 */
Route::post('shopcoupon/shopcouponrecorver', 'APIControllers\ShopCouponController@shopcouponrecorver');
/* shopcouponabandon  用戶棄用已已索取之商家優惠券 */
Route::post('shopcoupon/shopcouponabandon', 'APIControllers\ShopCouponController@shopcouponabandon');
/* shopcouponscan 商家掃描優惠券條碼查核內容 */
Route::post('shopcoupon/shopcouponscan', 'APIControllers\ShopCouponController@shopcouponscan');
/* shopcouponexec 商家執行優惠券內容 */
Route::post('shopcoupon/shopcouponexec', 'APIControllers\ShopCouponController@shopcouponexec');
/* ** query_reservationreplystatus	商家優惠活動券已預約未回覆項目查詢 * */
Route::post('shopcoupon/query_reservationreplystatus', 'APIControllers\ShopCouponController@query_reservationreplystatus');
/** update_couponreplystatus	更新「預約回覆狀態」 * */
Route::post('shopcoupon/update_couponreplystatus', 'APIControllers\ShopCouponController@update_couponreplystatus');

/** ShopQuestionnaire */
/** shopquestionnaire_read  合作社問卷內容讀取 */
Route::post('shopquestionnaire/shopquestionnaire_read', 'APIControllers\ShopQuestionnaireController@shopquestionnaire_read');
/** shopquestionnaire_ans 合作社問卷答覆接收 */
Route::post('shopquestionnaire/shopquestionnaire_ans', 'APIControllers\ShopQuestionnaireController@shopquestionnaire_ans');
/** shopquestionnaire_result  合作社問卷答覆結果查看 */
Route::post('shopquestionnaire/shopquestionnaire_result', 'APIControllers\ShopQuestionnaireController@shopquestionnaire_result');
/** shopquestionnaire_response  合作社商家回覆問卷留言 */
Route::post('shopquestionnaire/shopquestionnaire_response', 'APIControllers\ShopQuestionnaireController@shopquestionnaire_response');

/** ShopService */
/** shopservicelistquery  商家服務排隊項目列表查詢 */
Route::post('shopservice/shopservicelistquery', 'APIControllers\ShopServiceController@shopservicelistquery');
/** shopservicecontentquery 商家服務排隊項目內容查詢 */
Route::post('shopservice/shopservicecontentquery', 'APIControllers\ShopServiceController@shopservicecontentquery');
/** shopservicequeask 用戶選擇服務項目進行排隊 */
Route::post('shopservice/shopservicequeask', 'APIControllers\ShopServiceController@shopservicequeask');
/** shopservicemanage 商家管理自有供排隊服務項目 */
Route::post('shopservice/shopservicemanage', 'APIControllers\ShopServiceController@shopservicemanage');
/** shopservicefunctionadjust 商家調整服務排隊相關基本數據 */
Route::post('shopservice/shopservicefunctionadjust', 'APIControllers\ShopServiceController@shopservicefunctionadjust');
/** shopservicequequery 查詢指定商家近四日之排隊狀況 */
Route::post('shopservice/shopservicequequery', 'APIControllers\ShopServiceController@shopservicequequery');
/** shopservicescan 商家掃描用戶QR憑證 */
Route::post('shopservice/shopservicescan', 'APIControllers\ShopServiceController@shopservicescan');
/** shopserviceexec 商家掃描QR後開始服務 */
Route::post('shopservice/shopserviceexec', 'APIControllers\ShopServiceController@shopserviceexec');
/** shopqueuenoshow 商家設置未到用戶為過號用戶 */
Route::post('shopservice/shopqueuenoshow', 'APIControllers\ShopServiceController@shopqueuenoshow');
/** shopservicecallup 商家呼叫到號用戶開始服務 */
Route::post('shopservice/shopservicecallup', 'APIControllers\ShopServiceController@shopservicecallup');
/** shoponoffoperation  商家通知伺服器當日服務已終止 */
Route::post('shopservice/shoponoffoperation', 'APIControllers\ShopServiceController@shoponoffoperation');
/** shopqueueovercall 商家呼叫過號用戶前往接受服務 */
Route::post('shopservice/shopqueueovercall', 'APIControllers\ShopServiceController@shopqueueovercall');
/** shopservicequerecorver  用戶取得所有排隊記錄 */
Route::post('shopservice/shopservicequerecorver', 'APIControllers\ShopServiceController@shopservicequerecorver');
/** shopservice 用戶放棄排隊 */
Route::post('shopservice/shopservicequeabandom', 'APIControllers\ShopServiceController@shopservicequeabandom');
/** shopserviceclientreply  被叫號用戶回覆前往狀況 */
Route::post('shopservice/shopserviceclientreply', 'APIControllers\ShopServiceController@shopserviceclientreply');

/**
 *ShopManage
 */
/** post_shopactivegift_give  行銷活動贈與 * */
Route::post('shopmanage/postshopactivegiftgive', 'APIControllers\ShopManageController@postshopactivegiftgive');
/** query_shopbonus_item 紅利項目查詢 **/
Route::post('shopmanage/queryshopbonusitem', 'APIControllers\ShopManageController@queryshopbonusitem');
/** post_shopbonus_mamager  紅利管理設置 **/
Route::post('shopmanage/postshopbonusmamager', 'APIControllers\ShopManageController@postshopbonusmamager');
/** post_shopcomsume_bonus  現場消費紅利贈與 **/
Route::post('shopmanage/postshopcomsumebonus', 'APIControllers\ShopManageController@postshopcomsumebonus');
/** post_shopclerk_manager  商家店員管理 **/
Route::post('shopmanage/postshopclerkmanager', 'APIControllers\ShopManageController@postshopclerkmanager');
/** query_shopclerk_list  查詢商家店員資料 **/
Route::post('shopmanage/queryshopclerklist', 'APIControllers\ShopManageController@queryshopclerklist');
/** query_member_shopinfo 查詢會員已綁定之商家資料 **/
Route::post('shopmanage/querymembershopinfo', 'APIControllers\ShopManageController@querymembershopinfo');
/** login_shop_backend  特約商後台登入 **/
Route::post('shopmanage/loginshopbackend', 'APIControllers\ShopManageController@loginshopbackend');
/** verifyshopmailbind  驗證商家註冊信箱的驗證碼 */
Route::get('shopmanage/verifyshopmailbind', 'APIControllers\ShopManageController@verifyshopmailbind');

/**
  *ShopPush
  */
/** query_shoppush_content 推播項目內容查詢 **/
Route::post('shoppush/queryshoppushcontent', 'APIControllers\ShopPushController@queryshoppushcontent');
//** query_shoppush_record 推播記錄列表查詢 **/
Route::post('shoppush/queryshoppushrecord', 'APIControllers\ShopPushController@queryshoppushrecord');
/**push_shopad2member 特約商會員優惠訊息推播 **/
Route::post('shoppush/pushshopad2member', 'APIControllers\ShopPushController@pushshopad2member');
/**push_shopad2nonmember 特約商會員優惠訊息推播 **/
Route::post('shoppush/pushshopad2nonmember', 'APIControllers\ShopPushController@pushshopad2nonmember');
/** query_shopservice_fee  特約商服務費用項目 **/
Route::post('shoppush/queryshopservicefee', 'APIControllers\ShopPushController@queryshopservicefee');
/** query_push_nonmember  特約商推播非會員對象前查詢可推播總數 **/
Route::post('shoppush/querypushnonmember', 'APIControllers\ShopPushController@querypushnonmember');

/** CarNews */
/** queryusedcarlist  列表顯示二手車刊登項目查詢結果 * */
Route::post('carnews/queryusedcarlist', 'APIControllers\CarNewsController@queryusedcarlist');
/** queryusedcarcontent 按傳入ID取用車輛內容回傳 * */
Route::post('carnews/queryusedcarcontent', 'APIControllers\CarNewsController@queryusedcarcontent');
/** postusedcardata 新增車輛資料* */
Route::post('carnews/postusedcardata', 'APIControllers\CarNewsController@postusedcardata');
/** payusedcarpost  刊登物件付費扣點功能* */
Route::post('carnews/payusedcarpost', 'APIControllers\CarNewsController@payusedcarpost');
/** postshopdata  開店申請 **/
Route::post('carnews/postshopdata', 'APIControllers\CarNewsController@postshopdata');
/** putcarreservationans  新增車輛約看回覆敲定記錄 * */
Route::post('carnews/putcarreservationans', 'APIControllers\CarNewsController@putcarreservationans');
/** 查詢車輛約看詢問記錄 **/
Route::post('carnews/querycarreservationask', 'APIControllers\CarNewsController@querycarreservationask');
/** querycarreservationask 新增車輛約看詢問記錄 **/
Route::post('carnews/postcarreservationask', 'APIControllers\CarNewsController@postcarreservationask');
/** 賣方查詢買方車輛約看詢問記錄 **/
Route::post('carnews/querycarreservationfullinfo', 'APIControllers\CarNewsController@querycarreservationfullinfo');
 /** 查詢用戶所有約看記錄 **/
Route::post('carnews/recorvercarresvation', 'APIControllers\CarNewsController@recorvercarresvation');

/*
   temple 已模組化
Route::post('blesstemple/createtplsales', 'APIControllers\BlessTempleController@createtplsales');
Route::post('blesstemple/modifytplsales', 'APIControllers\BlessTempleController@modifytplsales');
Route::post('blesstemple/querytplsales', 'APIControllers\BlessTempleController@querytplsales');
Route::post('blesstemple/querytplsalesdetail', 'APIControllers\BlessTempleController@querytplsalesdetail');
Route::post('blesstemple/querytplproduct', 'APIControllers\BlessTempleController@querytplproduct');
Route::post('blesstemple/createblessreport', 'APIControllers\BlessTempleController@createblessreport');
Route::post('blesstemple/createblesslightreport', 'APIControllers\BlessTempleController@createblesslightreport');*/

/**  CannedMessage 罐頭訊息 **/
/** querycannedmessagelist  查詢罐頭訊息清單 * */
Route::post('cannedmessage/querycannedmessagelist', 'APIControllers\CannedMessageController@querycannedmessagelist');
/** modifycannedmessage 異動罐頭訊息資料 * */
Route::post('cannedmessage/modifycannedmessage', 'APIControllers\CannedMessageController@modifycannedmessage');


/*修改會員資料 與mem同步*/
Route::post('modify_member_data', 'APIControllers\ModifyMemberDataController@modifyMemberData');


/**  logistics 物流 **/
 /** printdeliverorder  供商家批量列印時間區間內所有未執行之物流單據 * */
Route::post('logistics/printdeliverorder', 'APIControllers\LogisticsController@printdeliverorder');
/** queryordercontent 供商家批量列印時間區間內所有未執行之物流單據 * */
Route::post('logistics/queryordercontent', 'APIControllers\LogisticsController@queryordercontent');
 /** queryorderlist 供商家查看所有訂單資料列表 * */
Route::post('logistics/queryorderlist', 'APIControllers\LogisticsController@queryorderlist');
/** querysclId   接收傳入之物流ID是否存在及仍有效 scl_iD * */
Route::post('logistics/querysclid', 'APIControllers\LogisticsController@querysclId');
/** reportcargoarrive 接收QR內容,驗證SCG及物流單號無誤,更新訂單狀態為已到貨 * */
Route::post('logistics/reportcargoarrive', 'APIControllers\LogisticsController@reportcargoarrive');
/** reportcargopack 供商家用戶回報實體商品出貨狀態 **/
Route::post('logistics/reportcargopack', 'APIControllers\LogisticsController@reportcargopack');
/** reportsentlogistics 供商家回報商品出貨之物流單號 * */
Route::post('logistics/reportsentlogistics', 'APIControllers\LogisticsController@reportsentlogistics');
/** updatescgpayment  scl，scg更新付款狀態 改以付款 * */
Route::post('logistics/updatescgpayment', 'APIControllers\LogisticsController@updatescgpayment');
/** createlogisticsdata  建立物流資料 * */
Route::post('logistics/createlogisticsdata', 'APIControllers\LogisticsController@createlogisticsdata');
/** createpaymentflow  建立呼叫金流JSON資料 * */
Route::post('logistics/createpaymentflow', 'APIControllers\LogisticsController@createpaymentflow');
/** getpaymentrespone  接收並處理由iscar_app的redirectpaymentdata API 回傳的金流資料 * */
Route::post('logistics/getpaymentrespone/{value}', 'APIControllers\LogisticsController@getpaymentrespone');
/** updateshopdatapayment  修改商家的綁定金流資料* */
Route::post('logistics/updateshopdatapayment', 'APIControllers\LogisticsController@updateshopdatapayment');
/** paysuccesstopush  成功建立金流資料後 推播給商家跟消費者* */
Route::post('logistics/paysuccesstopush', 'APIControllers\LogisticsController@paysuccesstopush');
/** refundpayment  退費* */
Route::post('logistics/refundpayment', 'APIControllers\LogisticsController@refundpayment');
/** paymentcancel  取消付款* */
Route::post('logistics/paymentcancel', 'APIControllers\LogisticsController@paymentcancel');



/** queryshopdata抓取特約商資料 **/
Route::post('queryshopdata', 'APIControllers\CarNews\QueryShopData@queryshopdata');
/** createshopdata抓取特約商資料 **/
Route::post('createshopdata', 'APIControllers\CarNews\CreateShopData@createshopdata');

/** 特約商報表 **/
Route::post('shop/queryshopreport', 'APIControllers\ShopReport\QueryShopReport@queryshopreport');
Route::post('shop/queryshopdetailreport', 'APIControllers\ShopReport\QueryShopDetailReport@queryshopdetailreport');
Route::post('shop/queryshopsalesoverview', 'APIControllers\ShopReport\QueryShopSalesOverview@queryshopsalesoverview');


/** iscar_admin 所用api **/
//shop特約商
Route::post('shopadm/queryshoplist', 'APIControllers\ShopAdmController@queryshoplist');
Route::post('shopadm/queryshopcontent', 'APIControllers\ShopAdmController@queryshopcontent');
Route::post('shopadm/modifyshopdata', 'APIControllers\ShopAdmController@modifyshopdata');
Route::post('shopadm/queryshoptype', 'APIControllers\ShopAdmController@queryshoptype');
//shopcoupon商品
Route::post('shopcouponadm/queryshopcouponlist', 'APIControllers\ShopCouponAdmController@queryshopcouponlist');
Route::post('shopcouponadm/queryshopcouponcontent', 'APIControllers\ShopCouponAdmController@queryshopcouponcontent');
Route::post('shopcouponadm/modifyshopcoupon', 'APIControllers\ShopCouponAdmController@modifyshopcoupon');



//reservationpaused 暫停預約
 /** query_reservationpausedlist   查詢「暫停預約資料」列表  */
Route::post('reservationpaused/query_reservationpausedlist', 'APIControllers\ReservationPausedController@query_reservationpausedlist');
/** delete_reservationpaused 刪除「暫停預約資料」資料 */
Route::post('reservationpaused/delete_reservationpaused', 'APIControllers\ReservationPausedController@delete_reservationpaused');
 /** create_reservationpaused   建立「暫停預約資料」資料*/
Route::post('reservationpaused/create_reservationpaused', 'APIControllers\ReservationPausedController@create_reservationpaused');
Route::post('reservationpaused/query_couponreservation_year', 'APIControllers\ReservationPausedController@query_couponreservation_year');



//SettleMentrec 關帳
  /** query_shopsettlementrec_d_list   取得該店家銷售結算子表清單 * */
Route::post('settlementrec/query_shopsettlementrec_d_list', 'APIControllers\SettleMentrecController@query_shopsettlementrec_d_list');
/** query_shopsettlementrec_m	查詢「商家銷售結算主表」列表 * */
Route::post('settlementrec/query_shopsettlementrec_m', 'APIControllers\SettleMentrecController@query_shopsettlementrec_m');
 /** query_shopsettlementrec_m_list	查詢「商家銷售結算主表清單」列表 * */
Route::post('settlementrec/query_shopsettlementrec_m_list', 'APIControllers\SettleMentrecController@query_shopsettlementrec_m_list');
 /** update_settlementview	更新店家覆核狀態 * */
Route::post('settlementrec/update_settlementview', 'APIControllers\SettleMentrecController@update_settlementview');
 /** execute_shopsettlement   執行店家結算功能 * */
Route::post('settlementrec/execute_shopsettlement', 'APIControllers\SettleMentrecController@execute_shopsettlement');
