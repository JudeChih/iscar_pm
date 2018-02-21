<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding no-reply-reservation-left">
                <a href="shop-records" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding menu-title">未回覆預約清單</div>
            <div class="right">
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="no-reply-reservation">
            <!-- 內容 -->
            <div class="page-content animated fadeIn no-reply-content">
            </div>
            <script type="text/template7" id="templateNoReplyReservationBlock">
            {{#each reservationinfo}}
            <div class="subTitle">
                {{reservationdate}}
            </div>
            <div class="list-block media-list">
                <ul class="block-{{reservationdate}}">
                </ul>
            </div>
            {{/each}}
            </script>
            <script type="text/template7" id="templateNoReplyReservationList">
            <li class="item-content animated fadeIn">
                <div class="item-media"><img data-src="{{ssd_picturepath}}" class="lazy" width="60" onerror='this.src="app/image/general_user.png"'></div>
                <div class="item-inner">
                    <div class="row">
                        <div class="col-75">
                            <div class="item-title-row">
                                <div class="item-title">{{scm_title}}</div>
                            </div>
                            <div class="item-subtitle">
                                <div class="row">
                                    <div class="col-100">預約人：<span>{{md_cname}}</span></div>
                                    <div class="col-100">時間：<span>{{js "this.scr_rvtime.slice(0,5)"}}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-25 reply" scg_id="{{scg_id}}">
                            <a class="button">回覆</a>
                        </div>
                    </div>
                </div>
            </li>
            </script>
            <script type="text/template7" id="templateNoReplyReservationNull">
            <div class="content-null">
                <h1><i class="fa fa-file-text-o"></i></h1>
                <br>
                <h3>無預約記錄</h3>
            </div>
            </script>
        </div>
    </div>
</div>