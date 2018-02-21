<!DOCTYPE html>
<html lang="zh-Hant-TW">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, minimum-scale=1, user-scalable=yes, minimal-ui">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="theme-color" content="#ffffff">
        <meta name="rating" content="general" />
        {{-- @if($metadata['description'] != '') --}}
        <meta name="description" content="{{$metadata['description']}}..."/>
        {{-- @endif --}}
        <meta name="author" content="{{$metadata['author']}}">
        @if(isset($metadata['keywords']))
            @if($metadata['keywords'] != '')
                <meta name="keywords" content="{{$metadata['keywords']}}">
            @endif
        @endif
        <meta id="token" name="token" content="{{ csrf_token() }}">

        @if(isset($metadata['sd_title']))
            <title>{{$metadata['sd_title']}}</title>
        @elseif(isset($metadata['scm_title']))
            <title>{{$metadata['scm_title']}}</title>
        @else
            <title>isCar就是行-汽車特店</title>
        @endif

        <!-- Open Graph -->
        <meta property="og:locale" content="zh_TW"/>
        <meta property="og:title" content="{{$metadata['og_title']}}"/>
        <meta property="og:image" content="{{$metadata['og_image']}}"/>
        <meta property="og:image:alt" content="{{$metadata['og_image_alt']}}"/>
        <meta property="og:url" content="{{$metadata['og_url']}}"/>
        <meta property="og:type" content="website"/>
        <meta property="og:site_name" content="isCar就是行" />
        <meta property="fb:app_id" content="875839542533172" />
        {{-- @if($metadata['og_description'] != '') --}}
        <meta property="og:description" content="{{$metadata['og_description']}}..."/>
        {{-- @endif --}}


        <link rel="icon" type="image/png" href="../app/image/iscar_icon.png">
        <link href="{{ URL::asset('app/libs/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('app/libs/Framework7-1.6.4/dist/css/framework7.ios.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('app/libs/Framework7-1.6.4/dist/css/framework7.ios.colors.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('app/libs/swiper/dist/css/swiper.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('app/libs/jquery-ui.css') }}" rel="stylesheet">
        <script src="{{ URL::asset('app/js/config.js') }}"></script>
        <link href="{{config('global.MEMSERVICE_URL')}}app/css/user.css" rel="stylesheet">
        <link href="{{ URL::asset('app/css/style.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('app/css/pm/custom.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('app/css/animations.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('app/libs/animate.css') }}" rel="stylesheet">

    </head>
    <body class="framework7-root">
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
            {{-- <div class="view view-main"> --}}
                @yield('view_main')
            {{-- </div> --}}
        </div>
        <!-- menu -->
        @if(isset($shopdatalist))
        <div class="popup shop-menu" style="display: block;">
            <div class="close-btn">
                <a href="#" class="close-popup">
                    <i class="fa fa-times" aria-hidden="true"></i>
                    <!--<span class="icon-chevron-left"></span>-->
                </a>
            </div>
            <!-- 內容 -->
            <div class="content">
                <div class="contentBlock">
                    @if($getdata['md_clienttype'] == 1)
                        <div class="type-title">──── 汽車特店功能 ────</div>
                        <div class="content-col">
                            <a type="button" onclick="javascript:location.href='/Shop#!/shop-data-config'" class="btn">
                                <img class="item-icon" src="../app/image/shop-data-config.png">
                                <span>商家資料設置</span>
                            </a>
                            <a type="button" onclick="javascript:location.href = '/Shop#!/shop/branch-main'" class="btn">
                                <img class="item-icon" src="../app/image/branch-main.png">
                                <span>條碼掃描</span>
                            </a>
                            <a type="button" onclick="javascript:location.href = '/Shop#!/message_push/message-main'" class="btn">
                                <img class="item-icon" src="../app/image/message_push.png">
                                <span>優惠推送</span>
                            </a>
                        </div>
                        <div class="content-col">
                            <a type="button" onclick="javascript:location.href = '/Shop#!/shop-records'" class="btn">
                                <img class="item-icon" src="../app/image/shop-records.png">
                                <span>銷售/服務紀錄</span>
                            </a>
                            <a type="button" onclick="javascript:location.href = '/Shop#!/shop/shopcoupon-management'" class="btn">
                                <img class="item-icon" src="../app/image/shopcoupon-management.png">
                                <span>商品/預約管理</span>
                            </a>
                            <a type="button" onclick="javascript:location.href = '/Shop#!/shop/questionnaire-management'" class="btn">
                                <img class="item-icon" src="../app/image/questionnaire-management.png">
                                <span>評價管理</span>
                            </a>
                        </div>
                        <div class="content-col">
                            <a type="button" onclick="javascript:location.href = '/Shop#!/shop/client-list'" class="btn">
                                <img class="item-icon" src="../app/image/client-list.png">
                                <span>會員列表</span>
                            </a>
                            <a type="button" onclick="javascript:location.href = '/Shop#!/shop/myBranchs'" class="btn">
                                <img class="item-icon" src="../app/image/myBranchs.png">
                                <span>商家切換</span>
                            </a>
                            <a href="#" class="btn noUse">
                                <img class="item-icon">
                                <span></span>
                            </a>
                            {{-- <a type="button" onclick="javascript:location.href = '/Shop#!/quick-msg-management'" class="btn noUse">
                                <img class="item-icon" src="../app/image/quick-msg-management.png">
                                <span>快選訊息管理</span>
                            </a> --}}
                        </div>
                        <div class="type-title ">──── 通用功能 ────</div>
                        <div class="content-col">
                            <a type="button" onclick="javascript:location.href = '/pm/branch-cooperative'" class="btn">
                                <img class="item-icon" src="../app/image/shop_list.png">
                                <span>汽車特店列表</span>
                            </a>
                            <a type="button" id="btn_openstore" class="btn">
                                <img class="item-icon " src="../app/image/open_store.png ">
                                <span>我要開店</span>
                            </a>
                            <a href="#" class="btn noUse">
                                <img class="item-icon">
                                <span></span>
                            </a>
                            {{-- <a type="button" class="btn btn_map noUse">
                                <img class="item-icon" src="../app/image/map.png ">
                                <span>地圖搜尋</span>
                            </a>
                            <a type="button" id="btn_openstore" class="btn noUse">
                                <img class="item-icon " src="../app/image/open_store.png ">
                                <span>我要開店</span>
                            </a> --}}
                        </div>
                    @elseif($getdata['md_clienttype'] == 100)
                        <div class="type-title">──── 業務功能 ────</div>
                        <div class="content-col ">
                            <a type="button" onclick="commonTools.scan('business')" class="btn">
                                <img class="item-icon " src="../app/image/ic_search_black_48dp.png">
                                <span>業務掃描</span>
                            </a>
                            <a href="#" class="btn noUse">
                                <img class="item-icon">
                                <span></span>
                            </a>
                            <a href="#" class="btn noUse">
                                <img class="item-icon">
                                <span></span>
                            </a>
                            {{-- <a href="temple-table" class="btn noUse">
                                <img class="item-icon" src="../app/image/ic_pageview_black_48dp.png">
                                <span>祈福報表</span>
                            </a>
                            <a class="btn noUse" onclick="loginStatus( 'bonus_record') ">
                                <img class="item-icon " src="../app/image/ic_redeem_black_48dp.png ">
                                <span></span>
                            </a> --}}
                        </div>
                        <div class="type-title ">──── 通用功能 ────</div>
                        <div class="content-col">
                            <a type="button" onclick="javascript:location.href = '/pm/branch-cooperative'" class="btn">
                                <img class="item-icon" src="../app/image/shop_list.png">
                                <span>特約商列表</span>
                            </a>
                            <a type="button" id="btn_openstore" class="btn">
                                <img class="item-icon " src="../app/image/open_store.png ">
                                <span>我要開店</span>
                            </a>
                            <a href="#" class="btn noUse">
                                <img class="item-icon">
                                <span></span>
                            </a>
                            {{-- <a type="button" class="btn noUse">
                                <img class="item-icon" src="../app/image/map.png ">
                                <span>地圖搜尋</span>
                            </a>
                            <a type="button" id="btn_openstore" class="btn noUse">
                                <img class="item-icon " src="../app/image/open_store.png ">
                                <span>我要開店</span>
                            </a> --}}
                        </div>
                    @else
                        <div class="type-title ">──── 通用功能 ────</div>
                        <div class="content-col">
                            <a type="button" onclick="javascript:location.href = '/pm/branch-cooperative'" class="btn">
                                <img class="item-icon" src="../app/image/shop_list.png">
                                <span>特約商列表</span>
                            </a>
                            <a type="button" id="btn_openstore" class="btn">
                                <img class="item-icon " src="../app/image/open_store.png ">
                                <span>我要開店</span>
                            </a>
                            <a href="#" class="btn noUse">
                                <img class="item-icon">
                                <span></span>
                            </a>
                            {{-- <a type="button" class="btn noUse">
                                <img class="item-icon" src="../app/image/map.png ">
                                <span>地圖搜尋</span>
                            </a>
                            <a type="button" id="btn_openstore" class="btn noUse">
                                <img class="item-icon " src="../app/image/open_store.png ">
                                <span>我要開店</span>
                            </a> --}}
                        </div>
                    @endif
{{--                     <div class="content-col">
                        <a href="map" class="btn noUse">
                            <img class="item-icon" src="../app/image/map.png ">
                            <span></span>
                        </a>
                        <a href="map" class="btn noUse">
                            <img class="item-icon" src="../app/image/map.png ">
                            <span></span>
                        </a>
                        <a href="map" class="btn noUse">
                            <img class="item-icon" src="../app/image/map.png ">
                            <span></span>
                        </a>
                    </div> --}}
                </div>
            </div>
        </div>
        @endif
        {{-- <script src="{{ URL::asset('app/libs/swipebox/src/js/jquery.swipebox.min.js') }}"></script> --}}
        {{-- <script src="{{ URL::asset('app/libs/jquery-validate/dist/jquery.validate.min.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('app/libs/Tweetie/tweetie.min.js') }}"></script> --}}

{{-- <script src="{{ URL::asset('app/libs/dk-tw-citySelector-master/dk-tw-citySelector.min.js') }}"></script> --}}
{{-- <script src="{{ URL::asset('app/libs/vendor/jflickrfeed.min.js') }}"></script> --}}
<script src="{{ URL::asset('app/libs/jquery/dist/jquery-1.11.3.min.js') }}"></script>
<script src="{{ URL::asset('app/js/config.js') }}"></script>
<script src="{{ URL::asset('app/js/string.js') }}"></script>
<script src="{{ URL::asset('app/libs/chartjs/Chart.min.js') }}"></script>
<script src="{{ URL::asset('app/libs/vendor/sha256.js') }}"></script>
<script src="{{ URL::asset('app/libs/vendor/sha1-min.js') }}"></script>
<script src="{{ URL::asset('app/libs/vendor/enc-base64-min.js') }}"></script>
{{-- <script src="{{ URL::asset('app/libs/vendor/qrcode.min.js') }}"></script> --}}
<script src="{{ URL::asset('app/libs/swiper/dist/js/swiper.min.js') }}"></script>
{{-- <script src="{{ URL::asset('app/libs/slick-1.5.9/slick/slick.min.js') }}"></script> --}}
<script src="{{ URL::asset('app/libs/countUp.js') }}"></script>
{{-- <script src="{{ URL::asset('app/libs/jquery.ellipsis.min.js') }}"></script> --}}
{{-- <script src="{{ URL::asset('app/libs/jquery/dist/jquery.qrcode-0.12.0.js') }}"></script> --}}
<script src="{{ URL::asset('app/libs/jquery-barcode-0.3.js') }}"></script>
@if(isset($getdata['google']))
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB84rw84PJULdHjz80aQg05VjUGlxBw6a0&v=3.exp&language=zh_TW&libraries=geometry"></script>
@endif
<script src="{{ URL::asset('app/libs/jquery-ui.js') }}"></script>
<script src="{{ URL::asset('app/libs/jquery.ui.touch-punch.min.js') }}"></script>
<script src="{{ URL::asset('app/libs/jquery.nicescroll.min.js') }}"></script>
<script src="{{ URL::asset('app/libs/jquery.lazy.min.js') }}"></script>
{{-- <script src="{{ URL::asset('app/libs/Croppie/croppie.js') }}"></script> --}}
<script src="{{ URL::asset('app/libs/js-cookie/src/js.cookie.js') }}"></script>
<script src="{{ URL::asset('app/js/webview.js') }}"></script>
<script src="{{ URL::asset('app/js/iPhone.js') }}"></script>
<script type="text/javascript" src="{{config('global.MEMSERVICE_URL')}}app/js/user.js"></script>
<!-- 瀏覽器裝置機碼 -->
<script type="text/javascript" src="{{config('global.MEMSERVICE_URL')}}app/js/generate_murid.js"></script>

{{-- <script src="{{ URL::asset('app/js/Shop/app.js') }}"></script> --}}
{{-- <script src="{{ URL::asset('app/js/Shop/region.js') }}"></script> --}}
{{-- <script src="{{ URL::asset('app/js/Shop/branch.js') }}"></script> --}}
{{-- <script src="{{ URL::asset('app/libs/zoom-master/jquery.zoom.min.js') }}"></script> --}}
<!-- 裝置console -->
{{-- <script src="{{ URL::asset('app/libs/vConsole-dev/dist/vconsole.min.js') }}"></script> --}}
<script src="{{ URL::asset('app/js/pm/commonTools.js') }}"></script>
@yield('put_script')

</body>
</html>