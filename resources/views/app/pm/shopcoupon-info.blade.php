@extends('app/pm/index')
@section("put_script")
<script type="text/javascript" src="/app/js/pm/shopcoupon_info.js"></script>
@endsection
@section("view_main")
<div class="view view-main" id='shopcoupon-info'>
    @if(isset($getdata['pmpoint']))
    <input type="hidden" name="pmpoint" value="{{$getdata['pmpoint']}}">
    @endif
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="subnavbar noUse">
                <!-- Sub navbar -->
                <div style="width:100%;height:100%;">
                    <div class="swiper-container shop-scroll swiper-container-horizontal">
                        <div class="swiper-wrapper" id="sw_width">
                            <a class="swiper-slide swiper_num"></a>
                            <a class="data_title_box swiper-slide swiper_num" href="#data_title_box">商品資訊</a>
                            @if(count($shopcoupondata['scm_advancedescribe'])>0)
                            <a class="detail_title_box swiper-slide swiper_num" href="#detail_title_box">商品詳情</a>
                            @endif
                            @if(count($shopcouponrandthree) > 0)
                            <a class="other_text_box swiper-slide swiper_num" href="#other_text_box">其他商品</a>
                            @endif
                            <a class="shopdata_text_box swiper-slide swiper_num" href="#shopdata_text_box">店家資訊</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="left sliding shopcoupon-info-left">
                <a class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page">

            {{-- 一開始加載的時候，所顯示的加載符號 --}}
            {{-- <div class="pm_indicator">
                <span style="width:42px; height:42px" class="preloader preloader-white"></span>
            </div>
            <div class="modal-overlay modal-overlay-visible pm_overlay" style="background-color: #000; "></div> --}}
            <div class="lazy_indicator">
                <span style="width:42px; height:42px" class="preloader preloader-white"></span>
            </div>
            <div class="modal-overlay modal-overlay-visible lazy_overlay" style="background-color: #000; "></div>

            <!-- 內容 -->
            <div class="page-content animated fadeIn">
            <div id="data_title_box" style="background: #151515;display: none;"></div>
                <!-- Swiper -->
                <div class="shopcoupon-imgs">
                    <div class="swiper-wrapper">
                    </div>
                    @if($shopcoupondata['scm_bonus_giveafteruse'] == 1)
                        <div class="givepointtag">
                            <i class="fa fa-gift" aria-hidden="true"></i>
                        </div>
                    @endif
                    <!-- Add Pagination -->
                    {{-- <div class="swiper-pagination"></div>
                    <div class="reservationtag">預約限定</div>
                    <div class="coupontag">活動服務</div>
                    <div class="commoditytag">實體商品</div> --}}
                </div>
                <div class="row no-gutter">
                    <div class="col-90">
                        <div class="shopcoupon_name">{{$shopcoupondata['scm_title']}} <i class="fa fa-share-alt btn_share" aria-hidden="true" style="color:white;"></i></div>
                    </div>
                    <div class="col-10 favorite favorite-{{$shopcoupondata['scm_id']}}" onclick="addFavorite('{{$shopcoupondata['scm_id']}}', '{{$shopcoupondata['scm_mainpic']}}', '{{$shopcoupondata['scm_title']}}', '{{$shopcoupondata['scm_category']}}', '{{$shopcoupondata['scm_reservationtag']}}', '{{$shopcoupondata['scm_startdate']}} ~ {{$shopcoupondata['scm_enddate']}}','3')"><i class="fa fa-star-o"></i></div>
                </div>

                <div class="shopcoupon-info-block">
                    <div class="shopcoupon-data" id="shopcoupon_data_title">商品資訊</div>
                    <div class="shopcoupon-content">
                        <span class="branch_date_title">活動日期：</span>
                        <span class="branch_date" style="color:burlywood;"><span id="scm_startdate">{{$shopcoupondata['scm_startdate']}}</span> ~ <span id='scm_enddate'>{{$shopcoupondata['scm_enddate']}}</span></span>
                        <br>
                        @if($shopcoupondata['scm_coupon_providetype'] == 0)
                            <span class="branch_time_title">商品售價：</span>
                            <span class="branch_time" style="color: red;font-size: 1.5em;font-weight: bold;">$ {{$shopcoupondata['scm_price']}}</span>
                        @elseif($shopcoupondata['scm_coupon_providetype'] == 1)
                            <span class="branch_time_title">點數兌換：</span>
                            <span class="branch_time" style="color: red;font-size: 1.5em;font-weight: bold;">{{$shopcoupondata['scm_bonus_payamount']}}點</span>
                        @endif
                        <br>
                        @if($shopcoupondata['scm_bonus_giveafteruse'] == 1)
                            <span class="branch_point_title">贈送特點：</span>
                            <span class="branch_point" style="color:#f26531; font-size: 1.5em;">{{$shopcoupondata['scm_bonus_giveamount']}}</span>點
                            <br>
                        @endif
                        <span class="branch_time_title">活動說明：</span>
                        <span class="branch_time" style="color:burlywood;">{{$shopcoupondata['scm_fulldescript']}}</span>
                        <br>
                        {{-- <i class="fa fa-share-square-o btn_share" aria-hidden="true">分享</i> --}}
                    </div>
                    @if($shopcoupondata['scm_coupon_providetype'] == 0)
                        @if(isset($getdata['giftpoint']))
                            <div class="shopcoupon-giftPointData" id="giftpoint_text">禮點資訊</div>
                            <div class="shopcoupon-content gift-point">
                                <span class="now_gift_title">持有禮點：</span>
                                <span class="now_gift" style="color:burlywood;">{{$getdata['giftpoint']}} P</span>{{-- <span class="input-after">(NTD {{$getdata['paygift']}})</span> --}}
                                <br>
                                <span class="pay_gift_title">可折抵金額：</span>
                                <span class="pay_gift" style="color:burlywood;">NTD {{$getdata['paygift']}}</span><span class="input-after">({{$getdata['paygift']*$getdata['GPExchangeCashRate']}} P)</span>
                                <br>
                            </div>
                        @endif
                    @endif
                    <div id="detail_title_box" style="background: #151515;display: none;"></div>
                    @if(count($shopcoupondata['scm_advancedescribe'])>0)
                        <div class="shopcoupon-subTitle" id="shopcoupon_detail_title">商品詳情</div>
                        <div class="shopcoupon-details" id="scm_advancedescribe">
                            @foreach ($shopcoupondata['scm_advancedescribe'] as $val)
                                @if($val['content_text'] != '')
                                    <div class="context">{{$val['content_text']}}</div>
                                @endif
                                @if($val['content_img'] != '')
                                    <img alt="{{ $val['content_img'] }}" class="lazy" data-src="{{config('global.ShopData_FTP_Img_Path')}}/{{ $val['content_img'] }}"  style="width:100%; border-radius: 5px;">
                                @endif
                            @endforeach
                        </div>
                    @endif
                    <div id="other_text_box" style="background: #151515;display: none;"></div>
                    @if(count($shopcouponrandthree) > 0)
                        <div class="shopcoupon-other" id='shopcoupon_other_text'>同店家其他商品</div>
                        <div class="shopcoupon-other-content row">
                            @foreach ($shopcouponrandthree as $val)
                                <div class="col-33">
                                    <a type="button" onclick="javascript:location.href = '/pm/shopcoupon-info?scm_id={{$val['scm_id']}}'">
                                        <img alt="{{$val['scm_mainpic']}}" data-src="{{config('global.ShopData_FTP_Img_Path')}}/{{$val['scm_mainpic']}}" class="lazy" />
                                        <span style="color: red;font-size: 1.5em;font-weight: bold;">$ {{$val['scm_price']}}</span>
                                        <span style="display: block;color:#ddd;">{{$val['scm_title']}}</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div id="shopdata_text_box" style="background: #151515;display: none;"></div>
                    <div class="shopcoupon-subTitle" id="shopdata_text">商家資訊</div>
                    <div class="shopcoupon-content branch-info">
                        <span class="branch_title">店名：</span>
                        <span class="branch_tel" style="color:burlywood;">{{$shopcoupondata['sd_shopname']}}</span>
                        <br>
                        <span class="branch_tel_title">電話：</span>
                        @if(!is_null($shopcoupondata['sd_shoptel']) && $shopcoupondata['sd_shoptel']!='')
                        <span class="branch_tel" style="color:burlywood;">{{$shopcoupondata['sd_shoptel']}}</span>
                        @else
                        <span class="branch_tel" style="color:burlywood;">店家暫無設定</span>
                        @endif
                        <br>
                        <span class="branch_date_title">營業日期：</span>
                        @if(!is_null($shopcoupondata['sd_weeklystart']) && !is_null($shopcoupondata['sd_weeklyend']) && $shopcoupondata['sd_weeklystart'] != '' && $shopcoupondata['sd_weeklyend'] != '')
                        <span class="branch_date" style="color:burlywood;">星期{{$shopcoupondata['sd_weeklystart']}} ~ 星期{{$shopcoupondata['sd_weeklyend']}}</span>
                        @else
                        <span class="branch_date" style="color:burlywood;">店家暫無設定</span>
                        @endif
                        <br>
                        <span class="branch_time_title">營業時間：</span>
                        @if(!is_null($shopcoupondata['sd_dailystart']) && !is_null($shopcoupondata['sd_dailyend']) && $shopcoupondata['sd_dailystart'] != '' && $shopcoupondata['sd_dailyend'] != '')
                        <span class="branch_time" style="color:burlywood;">{{substr($shopcoupondata['sd_dailystart'],0,5)}} ~ {{substr($shopcoupondata['sd_dailyend'],0,5)}}</span>
                        @else
                        <span class="branch_time" style="color:burlywood;">店家暫無設定</span>
                        @endif
                        <br>
                        <span class="branch_address_title">地址：</span>
                        @if(!is_null($shopcoupondata['sd_shopaddress']) && $shopcoupondata['sd_shopaddress'] != '')
                        <span class="branch_address" style="color:burlywood;">{{$shopcoupondata['sd_shopaddress']}}</span>
                        @else
                        <span class="branch_address" style="color:burlywood;">店家暫無設定</span>
                        @endif
                    </div>
                    <div id="shopcoupon-map"></div>
                </div>
            </div>
            @if($shopcoupondata['scm_poststatus'] == 1)
                @if($shopcoupondata['scm_coupon_providetype'] == 0)
                    <div class="toolbar toolbar-bottom get animated fadeInUp">
                        <div class="toolbar-inner">
                            <a><span>立即購買</span></a>
                        </div>
                    </div>
                @elseif($shopcoupondata['scm_coupon_providetype'] == 1)
                    @if(isset($getdata['pmpoint']))
                        @if(isset($getdata['enough']))
                            <div class="toolbar toolbar-bottom get animated fadeInUp">
                                <div class="toolbar-inner">
                                    <a><span>立即兌換(特點：{{$getdata['pmpoint']}}點)</span></a>
                                </div>
                            </div>
                        @else
                            <div class="toolbar toolbar-bottom cannotget animated fadeInUp">
                                <div class="toolbar-inner">
                                    <a><span>點數不足(特點：{{$getdata['pmpoint']}}點)</span></a>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="toolbar toolbar-bottom get animated fadeInUp">
                            <div class="toolbar-inner">
                                <a><span>立即兌換</span></a>
                            </div>
                        </div>
                    @endif
                @endif
                <div class="toolbar toolbar-bottom serving animated fadeInUp">
                    <div class="toolbar-inner">
                        <span>開始服務</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<script type="text/javascript">
    var sd_shopaddress = "{{$shopcoupondata['sd_shopaddress']}}";
    var sd_shopphotopath = "{{$shopcoupondata['sd_shopphotopath']}}";
    var scm_coupon_providetype = "{{$shopcoupondata['scm_coupon_providetype']}}";
    var scm_price = "{{$shopcoupondata['scm_price']}}";
    var lat = "{{$shopcoupondata['sd_lat']}}";
    var lng = "{{$shopcoupondata['sd_lng']}}";
    var shopname = "{{$shopcoupondata['sd_shopname']}}";
    var scm_mainpic = "{{$shopcoupondata['scm_mainpic']}}";
    var scm_producttype = "{{$shopcoupondata['scm_producttype']}}";
    var scm_id = "{{$shopcoupondata['scm_id']}}";
    var url = "{{$metadata['short_url']}}";
    var scg_id = "{{$getdata['scg_id']}}";
    var scm_title = "{{$shopcoupondata['scm_title']}}";
    var sci_type = "{{$getdata['type']}}";
    var scm_activepics = "{{$shopcoupondata['scm_activepics']}}";
    var inventory = "{{$shopcoupondata['inventory']}}";
    var scm_enddate = "{{$shopcoupondata['scm_enddate']}}";
    var sd_id = "{{$shopcoupondata['sd_id']}}";
    var sd_shoptel = "{{$shopcoupondata['sd_shoptel']}}";
    var scm_reservationtag = "{{$shopcoupondata['scm_reservationtag']}}";
    var scm_bonus_payamount = "{{$shopcoupondata['scm_bonus_payamount']}}";
    var PayGiftpointAsCash = "{{$getdata['PayGiftpointAsCash']}}"
    scm_activepics = scm_activepics.replace(/&quot;/g, '\"');
    scm_activepics = JSON.parse(scm_activepics);
</script>
@endsection