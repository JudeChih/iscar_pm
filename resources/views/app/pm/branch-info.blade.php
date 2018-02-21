@extends('app/pm/index')
@section("put_script")
<script type="text/javascript" src="/app/js/pm/branch_info.js"></script>
@endsection
@section("view_main")
<div class="view view-main" id="branch-info">
    <!-- Navbar -->
    @if(isset($getdata['shoppoint']))
        <input type="hidden" name="shoppoint" value="{{$getdata['shoppoint']}}">
        <div class="navbar">
            <div class="navbar-inner">
                <div class="back-btn1 branch-info-left left">
                    <a class="link icon-only">
                        <span class="icon-chevron-left"></span>
                    </a>
                </div>
                <div class="right">
                    特點：<span>{{$getdata['shoppoint']}}</span>點
                </div>
            </div>
        </div>
    @else
        <div class="navbar" style="display: none;">
            <div class="navbar-inner">
            </div>
        </div>
    @endif
    <!-- Pages -->
    <div class="pages">
        @if(isset($getdata['shoppoint']))
            <div class="page height">
        @else
            <div class="page">
        @endif
            {{-- 一開始加載的時候，所顯示的加載符號 --}}
            {{-- <div class="pm_indicator">
                <span style="width:42px; height:42px" class="preloader preloader-white"></span>
            </div>
            <div class="modal-overlay modal-overlay-visible pm_overlay" style="background-color: #000; "></div> --}}
            <div class="lazy_indicator">
                <span style="width:42px; height:42px" class="preloader preloader-white"></span>
            </div>
            <div class="modal-overlay modal-overlay-visible lazy_overlay" style="background-color: #000; "></div>
            {{-- <div class="modal-overlay modal-overlay-visible overlay_setting">
                <div class="pm_indicator">
                    <span style="width:42px; height:42px" class="preloader preloader-white"></span>
                </div>
            </div> --}}
            @if(!isset($getdata['shoppoint']))
                <div class="back-btn2 branch-info-left">
                    <a class="link icon-only">
                        <span class="icon-chevron-left"></span>
                    </a>
                </div>
            @endif
            <div class="tabs">
                @if($shopdata['sd_havebind'] == 1)
                    <div class="page-content hide-toolbar-on-scroll branch-info-content tab active animated fadeIn" style="padding-bottom: 44px;">
                @else
                    <div class="page-content hide-toolbar-on-scroll branch-info-content tab active animated fadeIn">
                @endif
                    <div class="image-blcok">
                        @if($shopdata['sd_shopphotopath'] != '')
                            <img alt="{{ $shopdata['sd_shopphotopath'] }}" class="lazy branch-img main-img" data-src="{{config('global.ShopData_FTP_Img_Path')}}/{{ $shopdata['sd_shopphotopath'] }}"/>
                        @else
                            <img alt="imgDefault.png" class="lazy" data-src="/app/image/imgDefault.png"/>
                        @endif
                        {{-- <img alt='{{$shopdata['sd_shopphotopath']}}' data-src="{{config('global.ShopData_FTP_Img_Path')}}/{{$shopdata['sd_shopphotopath']}}" class="lazy branch-img main-img"/> --}}
                    @if($shopdata['sd_havebind'] == 1)
                        <div class="authenticate_icon"><img alt="imgDefault.png" class="lazy" data-src="/app/image/authenticate.png" /></div>
                    @endif
                    </div>
                    <div class="branch-info-block">
                        <div class="row no-gutter">
                            <div class="col-75">
                                <div class="branch_name">{{$shopdata['sd_shopname']}} <i class="fa fa-share-alt btn_share" aria-hidden="true" style="color:white;"></i></div>
                            </div>
                            <div class="col-25 track addfavorite favorite-{{$shopdata['sd_id']}}"><img src="/app/image/unsubscribe.png" onerror='this.src="app/image/imgDefault.png"' /></div>
                        </div>
                        <div class="branch-content">
                            <span class="branch_tel_title">電話：</span>
                            <span class="branch_tel" style="color:burlywood;">{{$shopdata['sd_shoptel']}}</span>
                            <br>
                            <span class="branch_date_title">營業日期：</span>
                            @if(!is_null($shopdata['sd_weeklystart']) && !is_null($shopdata['sd_weeklyend']) && $shopdata['sd_weeklystart'] != '' && $shopdata['sd_weeklyend'] != '')
                            <span class="branch_date" style="color:burlywood;">星期{{$shopdata['sd_weeklystart']}} ~ 星期{{$shopdata['sd_weeklyend']}}</span>
                            @else
                            <span class="branch_date" style="color:burlywood;">店家暫無設定</span>
                            @endif
                            <br>
                            <span class="branch_time_title">營業時間：</span>
                            @if(!is_null($shopdata['sd_dailystart']) && !is_null($shopdata['sd_dailyend']) && $shopdata['sd_dailystart'] != '' && $shopdata['sd_dailyend'] != '')
                            <span class="branch_time" style="color:burlywood;">{{substr($shopdata['sd_dailystart'],0,5)}} ~ {{substr($shopdata['sd_dailyend'],0,5)}}</span>
                            @else
                            <span class="branch_time" style="color:burlywood;">店家暫無設定</span>
                            @endif
                            <br>
                            <span class="branch_address_title">地址：</span>
                            @if(!is_null($shopdata['sd_shopaddress']) && $shopdata['sd_shopaddress'] != '')
                            <span class="branch_address" style="color:burlywood;">{{$shopdata['sd_shopaddress']}}</span>
                            @else
                            <span class="branch_address" style="color:burlywood;">店家暫無設定</span>
                            @endif
                            <br>

                            {{-- <i class="fa fa-share-square-o btn_share" aria-hidden="true">分享</i> --}}
                        </div>
                        @if(isset($getdata['shoppoint']))
                            <div class="branch-subTitle">卡友特點</div>
                            <div class="branch-content" id="shoppoint">已有點數：<span style="color:burlywood;">{{$getdata['shoppoint']}}</span>點</div>
                        @endif
                        @if(isset($shopdata['sd_introtext']) && $shopdata['sd_introtext'] != '')
                            <div class="branch-subTitle">服務內容</div>
                            <div class="branch-content" id="cdm_description">{!!$shopdata['sd_introtext']!!}</div>
                        @endif
                        @if(count($shopdata['sd_advancedata'])>0)
                            <div class="branch-subTitle">更多資訊</div>
                            <div class="branch-details" id="sd_advancedata">
                                @foreach ($shopdata['sd_advancedata'] as $val)
                                    @if(isset($val['content_text']) && $val['content_text'] != '')
                                        <div class="context">{!!$val['content_text']!!}</div>
                                    @endif
                                    @if(isset($val['content_img']) && $val['content_img'] != '')
                                        <img alt="{{ $val['content_img'] }}" class="lazy" data-src="{{config('global.ShopData_FTP_Img_Path')}}/{{ $val['content_img'] }}"{{--  style="width:100%; border-radius: 5px;" --}}>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        <div class="shopmap-data" id="shopmap_text">地圖資訊</div>
                        <div id="shop-map"></div>
                    </div>
                </div>
            </div>
            <div class="toolbar toolbar-bottom tabbar animated fadeInUp">
                <div class="toolbar-inner">
                    <div class="row no-gutter">
                        <div class="col-33"><a onclick="javascript:location.replace('/pm/branch-info?sd_id={{$shopdata['sd_id']}}')" href="javascript:void(0)" class="info tab-link active">主頁</a></div>
                        <div class="col-33"><a onclick="javascript:location.replace('/pm/shopdata-comment?sd_id={{$shopdata['sd_id']}}')" href="javascript:void(0)" class="evaluate-browse tab-link">評論</a></div>
                        <div class="col-33"><a onclick="javascript:location.replace('/pm/shopcoupon-list?sd_id={{$shopdata['sd_id']}}')" href="javascript:void(0)" class="preferential tab-link" style="border:0;">優惠</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    {{-- var sd_introtext = '{{$shopdata['sd_introtext']}}'; --}}
    var sd_havebind = '{{$shopdata['sd_havebind']}}';
    var sd_id = '{{$shopdata['sd_id']}}';
    var sd_shopphotopath = '{{$shopdata['sd_shopphotopath']}}';
    var sd_shopname = '{{$shopdata['sd_shopname']}}';
    var sd_shopaddress = '{{$shopdata['sd_shopaddress']}}';
    var sd_shoptel = '{{$shopdata['sd_shoptel']}}';
    var url = "{{$metadata['short_url']}}";
    var lat = "{{$shopdata['sd_lat']}}";
    var lng = "{{$shopdata['sd_lng']}}";

</script>
@endsection