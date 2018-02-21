@extends('app/pm/index')
@section("put_script")
<script type="text/javascript" src="/app/js/pm/shopcoupon_list.js"></script>
@endsection
@section("view_main")
@if(isset($getdata['shoppoint']))
    <input type="hidden" name="shoppoint" value="{{$getdata['shoppoint']}}">
@endif
<div class="view view-main" id="shopcoupon-list">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner" style="padding-right: 0px;">
            <div class="subnavbar" style="padding-right: 0px;">
            <!-- Sub navbar -->
                <div style="width:100%;height:100%;">
                    <div class="swiper-container shop-scroll swiper-container-horizontal">
                        <div class="swiper-wrapper" id="sw_width">
                            <a class="swiper-slide swiper_num"></a>
                            @if(isset($getdata['shoppoint']))
                                <a class="swiper-slide swiper_num">已有特點：{{$getdata['shoppoint']}}點</a>
                            @else
                                <a class="swiper-slide swiper_num"></a>
                            @endif
                            @if($getdata['providetype'] == 0)
                                <a class="providetype0 swiper-slide swiper_num active" onclick="javascript:location.replace('/pm/shopcoupon-list?sd_id={{$shopdata['sd_id']}}&providetype=0')" href="javascript:void(0)">商品專區</a>
                                <a class="providetype1 swiper-slide swiper_num" onclick="javascript:location.replace('/pm/shopcoupon-list?sd_id={{$shopdata['sd_id']}}&providetype=1')" href="javascript:void(0)">兌換專區</a>
                            @elseif($getdata['providetype'] == 1)
                                <a class="providetype0 swiper-slide swiper_num" onclick="javascript:location.replace('/pm/shopcoupon-list?sd_id={{$shopdata['sd_id']}}&providetype=0')" href="javascript:void(0)">商品專區</a>
                                <a class="providetype1 swiper-slide swiper_num active" onclick="javascript:location.replace('/pm/shopcoupon-list?sd_id={{$shopdata['sd_id']}}&providetype=1')" href="javascript:void(0)">兌換專區</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="back-btn1 shopcoupon-list-left left">
                <a class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page height">
        {{-- <div class="page"> --}}
            {{-- 一開始加載的時候，所顯示的加載符號 --}}
            {{-- <div class="pm_indicator">
                <span style="width:42px; height:42px" class="preloader preloader-white"></span>
            </div>
            <div class="modal-overlay modal-overlay-visible pm_overlay" style="background-color: #000; "></div> --}}
            <div class="lazy_indicator">
                <span style="width:42px; height:42px" class="preloader preloader-white"></span>
            </div>
            <div class="modal-overlay modal-overlay-visible lazy_overlay" style="background-color: #000; "></div>
{{--             @if(isset($getdata['shoppoint']))
                <div class="back-btn2 shopcoupon-list-left">
                    <a class="link icon-only">
                        <span class="icon-chevron-left">{{$getdata['shoppoint']}}</span>
                    </a>
                </div>
            @endif --}}
            <div class="tabs">
                <!-- Tab 4 -->
                <div class="page-content branch-preferential-content tab infinite-scroll pull-to-refresh-content hide-bars-on-scroll animated fadeIn active" data-distance="300">
                    <!-- 下拉刷新符 -->
                    <div class="pull-to-refresh-layer">
                        <div class="preloader preloader-white"></div>
                        <div class="pull-to-refresh-arrow"></div>
                    </div>
                    <!-- 列表 -->
                    <div class="list-block mt-0 blog-box shopcoupon-list-block">
                        @if(count($shopcouponlist) == 0)
                        <div class="content-null">
                            <h1><i class="fa fa-shopping-cart" aria-hidden="true"></i></h1>
                            <br>
                            <h3>暫無優惠活動</h3>
                        </div>
                        @else
                        <ul class="shopcoupon-list-container">
                            <div class="row shopcoupon-block">
                            @foreach ($shopcouponlist as $val)
                                <li class="swipeout animated fadeIn">
                                    <div class="swipeout-content">
                                        <div class="item-content no-padding">
                                            <div class="item-inner blog-list">
                                                @if($val['scm_bonus_giveafteruse'] == 1)
                                                    <div class="givepointtag">
                                                        {{-- <img src="../app/image/bouns.png" style="width: 50px;"> --}}
                                                        <i class="fa fa-gift" aria-hidden="true"></i>
                                                    </div>
                                                @endif
                                                <div class="image">
                                                    <a type="button" onclick="javascript:location.href='/pm/shopcoupon-info?scm_id={{$val['scm_id']}}'">
                                                        <img alt="{{$val['scm_mainpic']}}" data-src="{{config('global.ShopData_FTP_Img_Path')}}/{{$val['scm_mainpic']}}" class="lazy" />
                                                    </a>
                                                </div>
                                                <div class="col-10 favorite favorite-{{$val['scm_id']}}" onclick="addFavorite('{{$val['scm_id']}}', '{{$val['scm_mainpic']}}', '{{$val['scm_title']}}', '{{$val['scm_category']}}', '{{$val['scm_reservationtag']}}', '{{$val['scm_startdate']}} ~ {{$val['scm_enddate']}}','3')""><i class="fa fa-star-o"></i>
                                                </div>
                                                <div class="text" onclick="javascript:location.href='/pm/shopcoupon-info?scm_id={{$val['scm_id']}}'">
                                                    <h4 class="title mt-5 mb-0">
                                                        <a type="button" onclick="javascript:location.href='/pm/shopcoupon-info?scm_id={{$val['scm_id']}}'">{{$val['scm_title']}}</a>
                                                    </h4>
                                                    <div class="row no-gutter info">
                                                        <div class="col-100">
                                                            <p class="row no-gutter">
                                                                @if($val['scm_coupon_providetype'] == 0)
                                                                    <span class="col-100 scm-date"><span class="note">商品售價：</span><span class="branch_time" style="color: red;font-size: 1.5em;font-weight: bold;">$ {{$val['scm_price']}}</span></span>
                                                                @elseif($val['scm_coupon_providetype'] == 1)
                                                                    <span class="col-100 scm-date"><span class="note">點數兌換：</span><span class="branch_time" style="color: red;font-size: 1.5em;font-weight: bold;">{{$val['scm_bonus_payamount']}}點</span></span>
                                                                @endif
                                                            </p>
                                                            <p class="row no-gutter">
                                                                <span class="col-100 scm-date"><span class="note">活動日期 :</span><span class="dateMark">{{$val['scm_startdate']}}</span> ~ <span class="dateMark">{{$val['scm_enddate']}}</span></span>
                                                            </p>
                                                        </div>
                                                        {{-- <div class="col-100">
                                                            <p class="row no-gutter">
                                                                <span class="col-100 scm-date"><span class="note">活動日期 :</span><span class="dateMark">{{$val['scm_startdate']}}</span> ~ <span class="dateMark">{{$val['scm_enddate']}}</span></span>
                                                            </p>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                            </div>
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
            <div class="toolbar toolbar-bottom tabbar animated fadeInUp">
                <div class="toolbar-inner">
                    <div class="row no-gutter">
                        <div class="col-33"><a onclick="javascript:location.replace('/pm/branch-info?sd_id={{$shopdata['sd_id']}}')" href="javascript:void(0)" class="info tab-link">主頁</a></div>
                        <div class="col-33"><a onclick="javascript:location.replace('/pm/shopdata-comment?sd_id={{$shopdata['sd_id']}}')" href="javascript:void(0)" class="evaluate-browse tab-link">評論</a></div>
                        <div class="col-33"><a onclick="javascript:location.replace('/pm/shopcoupon-list?sd_id={{$shopdata['sd_id']}}')" href="javascript:void(0)" class="preferential tab-link active" style="border:0;">優惠</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var sd_havebind = '{{$shopdata['sd_havebind']}}';



</script>
@endsection