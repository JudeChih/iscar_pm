<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding branch-reservation-record-left">
                <a href="shop-records" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding menu-title">預約紀錄</div>
            <div class="right">
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="branch-reservation-record">
            <!-- 內容 -->
            <div class="page-content animated fadeIn reservation-record">
                <!-- <div class="date-search-block"><input type="text" class="date" readonly><a class="button">查詢</a></div> -->
                <div class="date-block"></div>
                <div class="list-block media-list">
                    <ul>
                    </ul>
                </div>
            </div>
            <script type="text/template7" id="templateReservationBlock">
            {{#each reservationinfo}}
            <div class="subTitle">
                {{reservationdate}}
            </div>
            <div class="list-block media-list severMainBlock">
                <ul class="block-{{reservationdate}}">
                </ul>
            </div>
            {{/each}}
            </script>
            <script type="text/template7" id="templateReserveList">
            {{#each reservelist}}
            <li class="item-content">
                <div class="item-media"><img data-src="{{ssd_picturepath}}" class="lazy" width="60" onerror='this.src="app/image/general_user.png"'></div>
                <div class="item-inner">
                    <div class="item-title-row">
                        <div class="item-title">{{scm_title}}</div>
                    </div>
                    <div class="item-subtitle">
                        <div class="row">
                            <div class="col-100">預約人：<span>{{md_cname}}</span></div>
                            <div class="col-45">時間：<span>{{js "this.scr_rvtime.slice(0,5)"}}</span></div>
                            <div class="col-55">狀態：<span>{{status}}</span></div>
                        </div>
                    </div>
                </div>
            </li>
            {{/each}}
            </script>
            <script type="text/template7" id="templateReservationNull">
            <div class="content-null">
                <h1><i class="fa fa-file-text-o"></i></h1>
                <br>
                <h3>無預約記錄</h3>
            </div>
            </script>
        </div>
    </div>
</div>