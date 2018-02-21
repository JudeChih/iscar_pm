<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="icon" type="image/png" href="app/image/iscar_icon.png">
        <meta name="theme-color" content="#ffffff">
        <title>isCar就是行-汽車特店</title>
        <link rel="stylesheet" href="app/libs/font-awesome-4.7.0/css/font-awesome.min.css">
        <!-- <link rel="stylesheet" href="app/libs/fontawesome-free-5.0.6/on-server/css/fontawesome-all.min.css"> -->
        <link rel="stylesheet" href="app/libs/Framework7-1.6.4/dist/css/framework7.ios.min.css">
        <link rel="stylesheet" href="app/libs/Framework7-1.6.4/dist/css/framework7.ios.colors.min.css">
        <link rel="stylesheet" href="app/libs/swipebox/src/css/swipebox.css">
        <link rel="stylesheet" href="app/libs/Toast-for-Framework7-master/toast.css">
        <link rel="stylesheet" href="app/libs/slick-1.5.9/slick/slick.css">
        <link rel="stylesheet" href="app/libs/slick-1.5.9/slick/slick-theme.css">
        <link rel="stylesheet" href="app/libs/swiper/dist/css/swiper.min.css">
        <link rel="stylesheet" href="app/libs/jquery-ui.css">
        <link rel="stylesheet" href="app/libs/Croppie/croppie.css">
        <script type="text/javascript" src="app/js/config.js"></script>
        <script type="text/javascript">
        document.write('<link rel="stylesheet" href="http://' + server_type + _region + '-member.iscarmg.com/app/css/user.css">');
        </script>
        <link rel="stylesheet" href="app/css/style.css" id="theme-style">
        <link rel="stylesheet" href="app/css/custom.css" id="theme-style">
        <link rel="stylesheet" href="app/css/animations.css">
        <link rel="stylesheet" href="app/libs/animate.css">
        <link rel="stylesheet" href="app/css/Shop/webend_admin/report/report.css">
    </head>
    <body>
        <div class="statusbar-overlay"></div>
        <div class="panel-overlay"></div>
        <!-- Left panel -->
        <div class="panel panel-left panel-reveal leftPanel">
        </div>
        <!-- Right panel -->
        <div class="panel panel-right panel-reveal user-info">
        </div>
        <!-- Views -->
        <div class="views">
            <div class="view view-main">
                <div class="navbar">
                    <div class="navbar-inner"></div>
                </div>
                <div class="pages navbar-fixed toolbar-fixed">
                    <div data-page="index" class="page no-navbar">
                    </div>
                </div>
            </div>
        </div>
        <!-- menu -->
        <div class="popup shop-menu">
            <div class="close-btn">
                <a href="#" class="close-popup">
                    <i class="fa fa-times" aria-hidden="true"></i>
                    <!--<span class="icon-chevron-left"></span>-->
                </a>
            </div>
            <!-- 內容 -->
            <div class="content">
                <div class="contentBlock">
                </div>
            </div>
            <script type="text/template7" id="templateShopMenu">
            {{#if isShop}}
            <div class="type-title">──── 汽車特店功能 ────</div>
            <div class="content-col">
                <a href="shop-data-config" class="btn">
                    <img class="item-icon" src="app/image/shop-data-config.png">
                    <span>{{shop_data_config}}</span>
                </a>
                <a href="shop/branch-main" class="btn">
                    <img class="item-icon" src="app/image/branch-main.png">
                    <span>{{code_scan}}</span>
                </a>
                <!--<a href="#" class="btn">
                    <img class="item-icon" src="app/image/ic_assignment_black_48dp.png">
                    <span>{{sell_management}}</span>
                </a>-->
                <a href="message_push/message-main" class="btn">
                    <img class="item-icon" src="app/image/message_push.png">
                    <span>{{message_push}}</span>
                </a>
            </div>
            <div class="content-col">
                <!-- <a href="shop/shopservice-management" class="btn">
                    <img class="item-icon" src="app/image/shopservice-management.png">
                    <span>{{reservation_management}}</span>
                </a> -->
                <!-- <a href="quick-msg-management" class="btn">
                    <img class="item-icon" src="app/image/quick-msg-management.png">
                    <span>{{quick_msg_management}}</span>
                </a> -->
                <a href="shop-records" class="btn">
                    <img class="item-icon" src="app/image/shop-records.png">
                    <span>{{shop_records}}</span>
                </a>
                <a href="shopcoupon-main" class="btn">
                    <img class="item-icon" src="app/image/shopcoupon-management.png">
                    <span>{{shopcoupon_main}}</span>
                </a>
                <!--<a href="shop/bonus-management" class="btn">
                    <img class="item-icon" src="app/image/ic_redeem_black_48dp.png">
                    <span>{{bonus_management}}</span>
                </a>-->
                <a href="shop/questionnaire-management" class="btn">
                    <img class="item-icon" src="app/image/questionnaire-management.png">
                    <span>{{evaluation_management}}</span>
                </a>
            </div>
            <div class="content-col">
                <!--<a href="shop/questionnaire-management" class="btn">
                    <img class="item-icon" src="app/image/ic_description_black_48dp.png">
                    <span>{{evaluation_management}}</span>
                </a>-->
                <a href="shop/client-list" class="btn">
                    <img class="item-icon" src="app/image/client-list.png">
                    <span>{{client_list}}</span>
                </a>
                <a href="shop/myBranchs" class="btn">
                    <img class="item-icon" src="app/image/myBranchs.png">
                    <span>{{change_branch}}</span>
                </a>
                <a href="shop/staff-management" class="btn noUse">
                    <img class="item-icon" src="app/image/ic_account_circle_black_48dp.png">
                    <span>{{staff_management}}</span>
                </a>
            </div>
            <!-- <div class="content-col">
                <a href="shop/myBranchs" class="btn noUse">
                    <img class="item-icon" src="app/image/ic_view_carousel_black_48dp.png">
                    <span>{{change_branch}}</span>
                </a>
                <a href="shop/myBranchs" class="btn noUse">
                    <img class="item-icon" src="app/image/ic_view_carousel_black_48dp.png">
                    <span>{{change_branch}}</span>
                </a>
                <a href="shop/myBranchs" class="btn noUse">
                    <img class="item-icon" src="app/image/ic_view_carousel_black_48dp.png">
                    <span>{{change_branch}}</span>
                </a>
            </div> -->
            <div class="type-title">──── 通用功能 ────</div>
            <div class="content-col">
                <a class="btn shop_list">
                    <img class="item-icon" src="app/image/shop_list.png">
                    <span>{{shop_list}}</span>
                </a>
                <a class="btn" onclick="loginStatus('open_store')">
                    <img class="item-icon " src="app/image/open_store.png ">
                    <span>{{open_store}}</span>
                </a>
                <a href="map" class="btn noUse">
                    <img class="item-icon" src="app/image/map.png ">
                    <span>{{map}}</span>
                </a>
            </div>
            <!-- <div class="content-col">
                <a class="btn noUse" onclick="loginStatus('shop_favorite')">
                    <img class="item-icon" src="app/image/favorite.png ">
                    <span>{{favorite}}</span>
                </a>
                <a href="#" class="btn noUse" onclick="loginStatus('coupon_record')">
                    <img class="item-icon" src="app/image/record.png">
                    <span>{{coupon_record}}</span>
                </a>
                <a href="map" class="btn noUse">
                    <img class="item-icon" src="app/image/map.png ">
                    <span>{{map}}</span>
                </a>
            </div> -->
            <!-- <div class="content-col">
                <a href="#" class="btn" onclick="loginStatus('bonus_record')">
                    <img class="item-icon" src="app/image/ic_redeem_black_48dp.png">
                    <span>{{bonus_record}}</span>
                </a>
                <a class="btn" onclick="webview.scan()">
                    <img class="item-icon " src="app/image/ic_pageview_black_48dp.png ">
                    <span>{{temple_scan}}</span>
                </a>
                <a class="btn noUse" onclick="loginStatus( 'shop_bookmarks') ">
                    <img class="item-icon " src="app/image/ic_loyalty_black_48dp.png ">
                    <span>{{shop_bookmarks}}</span>
                </a>
            </div> -->
            {{else}}
            {{#if isTemple}}
            <div class="type-title">──── 廟宇功能 ────</div>
            <div class="content-col">
                <a href="shop/branch-info-edit" class="btn">
                    <img class="item-icon" src="app/image/ic_store_black_48dp.png">
                    <span>{{index}}</span>
                </a>
                <a href="shop/myBranchs" class="btn">
                    <img class="item-icon" src="app/image/ic_view_carousel_black_48dp.png">
                    <span>{{change_branch}}</span>
                </a>
                <a href="temple-table" class="btn noUse">
                    <img class="item-icon" src="app/image/ic_pageview_black_48dp.png">
                    <span>祈福報表</span>
                </a>
            </div>
            <div class="type-title">──── 通用功能 ────</div>
            <div class="content-col">
                <a class="btn shop_list">
                    <img class="item-icon" src="app/image/shop_list.png">
                    <span>{{shop_list}}</span>
                </a>
                <a class="btn" onclick="loginStatus('open_store')">
                    <img class="item-icon " src="app/image/open_store.png ">
                    <span>{{open_store}}</span>
                </a>
                <a href="map" class="btn noUse">
                    <img class="item-icon" src="app/image/map.png ">
                    <span>{{map}}</span>
                </a>
            </div>
            <!-- <div class="content-col">
                <a class="btn noUse" onclick="loginStatus('shop_favorite')">
                    <img class="item-icon" src="app/image/favorite.png ">
                    <span>{{favorite}}</span>
                </a>
                <a href="#" class="btn noUse" onclick="loginStatus('coupon_record')">
                    <img class="item-icon" src="app/image/record.png">
                    <span>{{coupon_record}}</span>
                </a>
                <a href="map" class="btn noUse">
                    <img class="item-icon" src="app/image/map.png ">
                    <span>{{map}}</span>
                </a>
            </div> -->
            {{else}}
            <div class="type-title ">──── 通用功能 ────</div>
            <div class="content-col">
                <a class="btn shop_list">
                    <img class="item-icon" src="app/image/shop_list.png">
                    <span>{{shop_list}}</span>
                </a>
                <a class="btn" onclick="loginStatus('open_store')">
                    <img class="item-icon " src="app/image/open_store.png ">
                    <span>{{open_store}}</span>
                </a>
                <a href="map" class="btn noUse">
                    <img class="item-icon" src="app/image/map.png ">
                    <span>{{map}}</span>
                </a>
            </div>
            <!-- <div class="content-col">
                <a class="btn noUse" onclick="loginStatus('shop_favorite')">
                    <img class="item-icon" src="app/image/favorite.png ">
                    <span>{{favorite}}</span>
                </a>
                <a href="#" class="btn noUse" onclick="loginStatus('coupon_record')">
                    <img class="item-icon" src="app/image/record.png">
                    <span>{{coupon_record}}</span>
                </a>
                <a href="map" class="btn noUse">
                    <img class="item-icon" src="app/image/map.png ">
                    <span>{{map}}</span>
                </a>
            </div> -->
            {{#if isBusiness}}
            <div class="content-col ">
                <a class="btn" onclick="scan('business')">
                    <img class="item-icon " src="app/image/ic_search_black_48dp.png ">
                    <span>{{business_scan}}</span>
                </a>
                <!-- <a class="btn" href="branch-binding">
                    <img class="item-icon" src="app/image/ic_search_black_48dp.png ">
                    <span>{{business_scan}}</span>
                </a> -->
                <a href="temple-table" class="btn noUse">
                    <img class="item-icon" src="app/image/ic_pageview_black_48dp.png">
                    <span>祈福報表</span>
                </a>
                <a class="btn noUse" onclick="loginStatus( 'bonus_record') ">
                    <img class="item-icon " src="app/image/ic_redeem_black_48dp.png ">
                    <span>{{bonus_record}}</span>
                </a>
                <!-- <a class="btn noUse" onclick="webview.isCamera()">
                    <img class="item-icon" src="app/image/icon_camera.png">
                    <span>就是拍</span>
                </a> -->
                <!-- <a class="btn noUse" onclick="webview.scan()">
                    <img class="item-icon " src="app/image/ic_pageview_black_48dp.png ">
                    <span>{{temple_scan}}</span>
                </a> -->
            </div>
            {{/if}}
            <div class="content-col">
                <a class="btn noUse" onclick="scan('business')">
                    <img class="item-icon " src="app/image/ic_search_black_48dp.png ">
                    <span>{{business_scan}}</span>
                </a>
                <!-- <a class="btn" href="branch-binding">
                    <img class="item-icon" src="app/image/ic_search_black_48dp.png ">
                    <span>{{business_scan}}</span>
                </a> -->
                <a class="btn noUse" onclick="loginStatus( 'bonus_record') ">
                    <img class="item-icon " src="app/image/ic_redeem_black_48dp.png ">
                    <span>{{bonus_record}}</span>
                </a>
                <!-- <a class="btn noUse" onclick="webview.isCamera()">
                    <img class="item-icon" src="app/image/icon_camera.png">
                    <span>就是拍</span>
                </a> -->
                <!-- <a class="btn noUse" onclick="webview.scan()">
                    <img class="item-icon " src="app/image/ic_pageview_black_48dp.png ">
                    <span>{{temple_scan}}</span>
                </a> -->
            </div>
            {{/if}}
            {{/if}}
            <!-- {{#if signed_in}}
            <div class="logout" onclick="logout()"><i class="fa fa-sign-out" aria-hidden="true"></i> 登出</div>
            {{/if}} -->
            </script>
        </div>
        <div class="popup memberseccode">
            <!-- 內容 -->
            <div class="content">
                <div class="title">請輸入會員安全碼</div>
                <div class="row seccode-block">
                    <div class="col-16"></div>
                    <div class="col-16"></div>
                    <div class="col-16"></div>
                    <div class="col-16"></div>
                    <div class="col-16"></div>
                    <div class="col-16"></div>
                </div>
                <div class="num-block">
                    <div class="row">
                        <div class="col-33 num">1</div>
                        <div class="col-33 num">2</div>
                        <div class="col-33 num">3</div>
                    </div>
                    <div class="row">
                        <div class="col-33 num">4</div>
                        <div class="col-33 num">5</div>
                        <div class="col-33 num">6</div>
                    </div>
                    <div class="row">
                        <div class="col-33 num">7</div>
                        <div class="col-33 num">8</div>
                        <div class="col-33 num">9</div>
                    </div>
                    <div class="row">
                        <div class="col-33 close-popup">返回</div>
                        <div class="col-33 num">0</div>
                        <div class="col-33 del">C</div>
                    </div>
                </div>
            </div>
            <div class="toolbar toolbar-bottom confirm animated fadeInUp">
                <div class="toolbar-inner">
                    <a href="#">確定</a>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="app/libs/jquery/dist/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="app/libs/Framework7-1.6.4/dist/js/framework7.min.js"></script>
        <script type="text/javascript" src="app/libs/swipebox/src/js/jquery.swipebox.min.js"></script>
        <script type="text/javascript" src="app/libs/jquery-validate/dist/jquery.validate.min.js"></script>
        <script type="text/javascript" src="app/libs/Tweetie/tweetie.min.js"></script>
        <script type="text/javascript" src="app/libs/chartjs/Chart.min.js"></script>
        <script type="text/javascript" src="app/libs/dk-tw-citySelector-master/dk-tw-citySelector.min.js"></script>
        <script type="text/javascript" src="app/libs/vendor/jflickrfeed.min.js"></script>
        <script type="text/javascript" src="app/libs/vendor/sha256.js"></script>
        <script type="text/javascript" src="app/libs/vendor/sha1-min.js"></script>
        <script type="text/javascript" src="app/libs/vendor/enc-base64-min.js"></script>
        <script type="text/javascript" src="app/libs/vendor/qrcode.min.js"></script>
        <script src="app/libs/Toast-for-Framework7-master/toast.js"></script>
        <script src="app/libs/swiper/dist/js/swiper.min.js"></script>
        <script src="app/libs/slick-1.5.9/slick/slick.min.js"></script>
        <script src="app/libs/countUp.js"></script>
        <script src="app/libs/jquery.ellipsis.min.js"></script>
        <script src="app/libs/jquery/dist/jquery.qrcode-0.12.0.js"></script>
        <script src="app/libs/jquery-barcode-0.3.js"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB84rw84PJULdHjz80aQg05VjUGlxBw6a0&v=3.exp&language=zh_TW&libraries=geometry"></script>
        <script src="app/libs/jquery-ui.js"></script>
        <script src="app/libs/jquery.ui.touch-punch.min.js"></script>
        <!-- Masonry瀑布流排版 JS -->
        <script src="app/libs/masonry.pkgd.min.js"></script>
        <script src="app/libs/jquery.nicescroll.min.js"></script>
        <script type="text/javascript" src="app/libs/Croppie/croppie.js"></script>
        <!-- JS-cookie -->
        <script src="app/libs/js-cookie/src/js.cookie.js"></script>

        <!-- <script src="app/libs/fontawesome-free-5.0.6/on-server/js/fontawesome-all.min.js"></script> -->
        <!-- <script src="app/libs/fontawesome-free-5.0.6/on-server/js/fa-v4-shims.min.js"></script> -->

        <script type="text/javascript" src="app/js/iPhone.js"></script>
        <script type="text/javascript" src="app/js/webview.js"></script>
        <script type="text/javascript" src="app/js/string.js"></script>
        <script type="text/javascript">
        document.write('<script type="text/javascript" src="http://' + server_type + _region + '-member.iscarmg.com/app/js/user.js"><\/script>');
        </script>
        <!-- 瀏覽器裝置機碼 -->
        <script type="text/javascript">
        document.write('<script type="text/javascript" src="http://' + server_type + _region + '-member.iscarmg.com/app/js/generate_murid.js"><\/script>');
        </script>
        <script type="text/javascript" src="app/js/Shop/app.js"></script>
        <script type="text/javascript" src="app/js/Shop/region.js"></script>
        <script type="text/javascript" src="app/js/car-models.js"></script>
        <script type="text/javascript" src="app/js/Shop/branch.js"></script>
        <script type="text/javascript" src="app/js/Shop/report.js"></script>
        <!-- 圖片放大滑動檢視特效 -->
        <script src='app/libs/zoom-master/jquery.zoom.min.js'></script>
        <!-- 裝置console -->
        <!-- <script src="app/libs/vConsole-dev/dist/vconsole.min.js"></script> -->
    </body>
</html>