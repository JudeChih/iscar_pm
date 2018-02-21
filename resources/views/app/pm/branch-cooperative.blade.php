@extends('app/pm/index')
@section("put_script")
<script type="text/javascript" src="/app/js/pm/branch_cooperative.js"></script>
@endsection
@section("view_main")
<div class="view view-main" id="branch-cooperative">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="#" class="link icon-only open-popup" data-popup=".shop-menu">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div>
            <div class="center sliding">汽車特店</div>
            <div class="right">
                <div class="iscar_member_login iscar_member_icon" from="Shop"></div>
            </div>
            <!-- Sub navbar -->
            <div class="subnavbar">
                <!-- Sub navbar -->
                <div style="width:100%;">
                    <div class="swiper-container blog-cate">
                        <div class="swiper-wrapper">
                        @if(count($catelist) > 0)
                            @foreach ($catelist as $val)
                                <a type="button" onclick="javascript:location.href = '/pm/branch-cooperative?cate={{$val['sd_type']}}&listtype={{$getdata['listtype']}}'" href="javascript:void(0)" data-cate="{{$val['sd_type']}}" class="swiper-slide swiper_num swiper-slide-active"><span>{{$val['sd_type_text']}}</span></a>
                            @endforeach
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pages -->
    {{-- <div class="hot-key"></div> --}}
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
            
            <div class="bc_box"><div id="test-key"></div></div>

            <!-- 內容 -->
            <div class="page-content branch-cooperative-content infinite-scroll pull-to-refresh-content animated fadeIn" data-distance="300">

                <!-- 下拉刷新符 -->
                {{-- <div class="pull-to-refresh-layer">
                    <div class="preloader preloader-white"></div>
                    <div class="pull-to-refresh-arrow"></div>
                </div> --}}

                <!-- 列表 -->
                <div class="list-block mt-0 blog-box branch-pager">
                    @if(count($shopdatalist) == 0)
                    <div class="content-null">
                        <h1><i class="fa fa-building" aria-hidden="true"></i></h1>
                        <br>
                        <h3>暫無店家資訊</h3>
                    </div>
                    @else
                    <ul class="branch-list-container get_cate" data-cate="{{$getdata['cate']}}">
                        @if($getdata['listtype'] == 2)
                            <div class="row branch-block">
                                @foreach ($shopdatalist as $data)
                                <div class="branch-item animated zoomIn start_id" style="background:rgba(100%,100%,100%,.7);" data-lon="{{$data['sd_lng']}}" data-lat="{{$data['sd_lat']}}" data-id="{{$data['sd_id']}}">
                                    <div class="image listtype2 goToBranch">
                                        @if($data['sd_havebind'] == 1)
                                            <div class="authenticate_icon"><img alt="imgDefault.png" class="lazy" data-src="/app/image/authenticate.png" /></div>
                                        @endif
                                        {{-- <a href="javascript:void(0)"> --}}
                                            @if($data['sd_shopphotopath'] != '')
                                                <img alt="{{ $data['sd_shopphotopath'] }}" class="lazy" data-src="{{config('global.ShopData_FTP_Img_Path')}}/{{ $data['sd_shopphotopath'] }}"/>
                                            @else
                                                <img alt="imgDefault.png" class="lazy" data-src="/app/image/imgDefault.png"/>
                                            @endif
                                        {{-- </a> --}}
                                    </div>
                                    <div class="text">
                                        <div class="title goToBranch">
                                            <span>{{$data['sd_shopname']}}</span>
                                        </div>
                                        <div class="row no-gutter">
                                            <div class="col-100 goToBranch">
                                                <p class="row no-gutter">
                                                    <span class="col-90">
                                                        <span class="dis_km" style="color:aqua;">距 {{$data['dis']}} 公里</span>
                                                        <span class="note">地址 :</span>{{$data['sd_shopaddress']}}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="col-40 favorite favorite-{{$data['sd_id']}}" onclick="addFavorite('{{$data['sd_id']}}', '{{$data['star_sd_shopphotopath']}}', '{{$data['sd_shopname']}}', '{{$data['sd_shopaddress']}}', '{{$data['sd_shoptel']}}', '','2')"><img src="../app/image/unsubscribe.png"/></div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @elseif($getdata['listtype'] == 0)
                            @foreach ($shopdatalist as $data)
                                <li class="swipeout animated fadeIn start_id" data-id="{{$data['sd_id']}}">
                                    <div class="swipeout-content">
                                        <div class="item-content no-padding">
                                            <div class="blog-list">
                                                @if($data['sd_havebind'] == 1)
                                                    <div class="authenticate_icon goToBranch"><img alt="authenticate.png" data-src="/app/image/authenticate.png" class="lazy" /></div>
                                                @endif
                                                <div class="image goToBranch">
                                                    <a href="javascript:void(0)">
                                                        @if($data['sd_shopphotopath'] != '')
                                                            <img alt="{{ $data['sd_shopphotopath'] }}" class="lazy" data-src="{{config('global.ShopData_FTP_Img_Path')}}/{{ $data['sd_shopphotopath'] }}"/>
                                                        @else
                                                            <img alt="imgDefault.png" class="lazy" data-src="/app/image/imgDefault.png"/>
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="col-10 favorite favorite-{{$data['sd_id']}}" onclick="addFavorite('{{$data['sd_id']}}', '{{$data['star_sd_shopphotopath']}}', '{{$data['sd_shopname']}}', '{{$data['sd_shopaddress']}}', '{{$data['sd_shoptel']}}', '','2')"><img class="lazy" data-src="../app/image/unsubscribe.png"/></div>

                                                <div class="text goToBranch">
                                                    <h4 class="title mt-5 mb-0">
                                                        <a href="javaxcript:void(0)">{{$data['sd_shopname']}}</a>
                                                    </h4>
                                                    <div class="row no-gutter info">
                                                        <div class="col-100">
                                                            <p class="row no-gutter">
                                                                <span class="col-100 createdate">
                                                                    <span class="dis_km" style="color:aqua;">距 {{$data['dis']}} 公里</span>
                                                                    <span class="note">地址 :</span>{{$data['sd_shopaddress']}}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    @endif
                </div>

                <!-- 加载提示符 -->
                {{-- <div class="infinite-scroll-preloader">
                    <i class="fa fa-spinner fa-pulse" style="color:#777; font-size: 50pt; margin-top: 5%;"></i>
                </div> --}}

            </div>

            <!-- 版型選單 -->
            <div class="toolbar toolbar-bottom branchType animated fadeInUp">
                <div class="toolbar-inner">
                    <div class="row no-gutter">
                        <div class="col-25">
                            @if(isset($getdata['listtype']))
                                @if($getdata['listtype'] == 0)
                                    <a onclick="javascript:location.href = '/pm/branch-cooperative?cate={{$getdata['cate']}}&listtype=2'" href="javascript:void(0)" class="listtype2"><i class="fa fa-th-large"></i></a>
                                @elseif($getdata['listtype'] == 2)
                                    <a onclick="javascript:location.href = '/pm/branch-cooperative?cate={{$getdata['cate']}}&listtype=0'" href="javascript:void(0)" class="listType0"><i class="fa fa-stop"></i></a>
                                @endif
                            @else
                                <a onclick="javascript:location.href = '/pm/branch-cooperative?cate={{$getdata['cate']}}&listtype=2'" href="javascript:void(0)" class="listtype2"><i class="fa fa-th-large"></i></a>
                            @endif
                        </div>
                        <div class="col-25">
                            <a href="javascript:void(0)" class="btn_favorite"><i class="fa fa-heart" aria-hidden="true"></i></a>
                        </div>
                        <div class="col-25">
                            <a href="javascript:void(0)" class="btn_map" id="btn_map"><i class="fa fa-map-marker" aria-hidden="true"></i></a>
                        </div>
                        <div class="col-25">
                            <a href="javascript:void(0)" class="advanced_search" style="border: 0;"><i class="fa fa-search" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var listtype = '{{$getdata['listtype']}}';
    var cate = '{{$getdata['cate']}}';
</script>
@endsection

