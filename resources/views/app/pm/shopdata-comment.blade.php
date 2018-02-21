@extends('app/pm/index')
@section("put_script")
<script type="text/javascript" src="/app/js/pm/shopdata_comment.js"></script>
@endsection
@section("view_main")
<div class="view view-main" id="shopdata-comment">
    <!-- Navbar -->
    <div class="navbar" style="display: none;">
        <div class="navbar-inner">
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        {{-- @if(isset($getdata['shoppoint'])) --}}
            {{-- <div class="page toolbar-fixed height"> --}}
        {{-- @else --}}
            <div class="page toolbar-fixed">
        {{-- @endif --}}
            {{-- 一開始加載的時候，所顯示的加載符號 --}}
            {{-- <div class="modal-overlay modal-overlay-visible overlay_setting">
                <div class="pm_indicator">
                    <span style="width:42px; height:42px" class="preloader preloader-white"></span>
                </div>
            </div> --}}
            {{-- @if(!isset($getdata['shoppoint'])) --}}
                <div class="back-btn2 shopdata-comment-left">
                    <a class="link icon-only">
                        <span class="icon-chevron-left"></span>
                    </a>
                </div>
            {{-- @endif --}}
            <div class="tabs">
                {{-- <div class="page-content branch-preferential-content tab infinite-scroll pull-to-refresh-content hide-bars-on-scroll animated fadeIn active" data-distance="300"> --}}
                <div class="page-content branch-evaluate-browse-content tab active animated fadeIn">
                    {{-- <div class="pm_indicator">
                        <span style="width:42px; height:42px" class="preloader preloader-white"></span>
                    </div>
                    <div class="modal-overlay modal-overlay-visible pm_overlay" style="background-color: #000; "></div> --}}
                    <!-- 下拉刷新符 -->
                    <div class="pull-to-refresh-layer">
                        <div class="preloader preloader-white"></div>
                        <div class="pull-to-refresh-arrow"></div>
                    </div>
                    <div class="canvas-block">
                        <canvas id="canvas" width="300" height="300" style="width:60%; margin-left:20%;"></canvas>
                        <div class="row evaluate-info">
                            <div class="col-25 note">綜合評價：</div>
                            <div class="col-25"><span class="average"></span>&nbsp;<i class="fa fa-star" style="color: goldenrod;"></i></div>
                            <div class="col-25 note">投票人數：</div>
                            <div class="col-25"><span class="count"></span>&nbsp;<span>人</span></div>
                        </div>
                    </div>
                    <div class="activemessage-list">
                        @if(count($getdata['activemessage']) != 0)
                        <div class="branch-subTitle">評論內容</div>
                        <div class="list-block comments-box">
                            <ul>
                                @foreach ($getdata['activemessage'] as $data)
                                    @if($data['sqna_message'] != '')
                                        <li>
                                            <div>
                                                <div class="item-content">
                                                    <div class="item-inner comments-list">
                                                        <div class="image">
                                                            <span class="ava">
                                                                <img data-src="{{$data['ssd_picturepath']}}" class="lazy">
                                                            </span>
                                                        </div>
                                                        <div class="text">
                                                            <div class="info">
                                                                <span class="nick">{{$data['md_cname']}}</span>
                                                                <span class="data dateMark">{{substr($data['sqna_last_update'],0,10)}}</span>
                                                            </div>
                                                            <div class="comment">{{$data['sqna_message']}}</div>
                                                            @if(!is_null($data['sqnr_responsemessage']))
                                                            <div class="branch-reply-block">
                                                                <div class="branch-reply-title">
                                                                    <div><span class="branch-name">商家</span>的回覆(<span class="dateMark">{{substr($data['sqnr_last_update'],0,10)}}</span>)</div>
                                                                </div>
                                                                <div class="branch-reply-context">{{$data['sqnr_responsemessage']}}</div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        @else
                            <div class="list-null" style="margin-top:25%;">暫無評論</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="toolbar toolbar-bottom tabbar animated fadeInUp">
                <div class="toolbar-inner">
                    <div class="row no-gutter">
                        <div class="col-33"><a onclick="javascript:location.replace('/pm/branch-info?sd_id={{$getdata['sd_id']}}')" href="javascript:void(0)" class="info tab-link">主頁</a></div>
                        <div class="col-33"><a onclick="javascript:location.replace('/pm/shopdata-comment?sd_id={{$getdata['sd_id']}}')" href="javascript:void(0)" class="evaluate-browse tab-link active">評論</a></div>
                        <div class="col-33"><a onclick="javascript:location.replace('/pm/shopcoupon-list?sd_id={{$getdata['sd_id']}}')" href="javascript:void(0)" class="preferential tab-link" style="border:0;">優惠</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var sd_havebind = '{{$getdata['sd_havebind']}}';
    var sd_questiontotalaverage = '{{$getdata['sd_questiontotalaverage']}}';
    var sd_questionnaireresult = '{{$getdata['sd_questionnaireresult']}}';




</script>
@endsection