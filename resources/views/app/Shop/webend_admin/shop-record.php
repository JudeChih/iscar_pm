<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="#" class="link icon-only open-popup" data-popup=".shop-menu">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div>
            <div class="center sliding">紀錄查看</div>
            <div class="right">
                <div class="iscar_member_icon" from="Shop"></div>
            </div>
            
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="shop-record">
            <!-- Sub navbar -->
            <div class="subnavbar">
                <div class="row no-gutter">
                    <div class="col-33"><a href=".reservation-record" class="active tab-link">預約</a></div>
                    <div class="col-33"><a href=".service-record" class="tab-link">服務</a></div>
                    <div class="col-33" style="border:0;"><a href=".scan-record" class="tab-link">掃描</a></div>
                </div>
            </div>
            <!-- Tabs, tabs wrapper -->
            <div class="tabs">
                <!-- Tab 1, active by default -->
                <div class="reservation-record page-content tab active animated fadeIn">


                </div>

                <!-- Tab 2 -->
                <div class="service-record page-content tab animated fadeIn">


                </div>

                <!-- Tab 3 -->
                <div class="scan-record page-content tab animated fadeIn">
                    <div class="sever-subTitle date1">
                    </div>
                    <div class="record-list date1-block">

                    </div>

                    <div class="sever-subTitle date2">
                    </div>
                    <div class="record-list date2-block">

                    </div>


                    <div class="sever-subTitle date3">
                    </div>

                    <div class="record-list date3-block">

                    </div>
                </div>
            </div>


            <script type="text/template7" id="templateBranchReservationBlock">
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

            <script type="text/template7" id="templateBranchReserveList">
                <li class="item-content">
                    <div class="item-media"><img data-src="{{ssd_picturepath}}" class="lazy" width="60" onerror='this.src="../app/image/general_user.png"'></div>
                    <div class="item-inner">
                        <div class="item-title-row">
                            <div class="item-title">{{scm_title}}</div>
                        </div>
                        <div class="item-subtitle">
                            <div class="row">
                                <div class="col-100">預約人：<span>{{md_cname}}</span></div>
                                <div class="col-40">時間：<span>{{js "this.scr_rvtime.slice(0,5)"}}</span></div>
                                <div class="col-60">預約狀態：<span>{{status}}</span></div>
                            </div>
                        </div>
                    </div>
                </li>
            </script>

            <script type="text/template7" id="templateBranchReservationNull">
                <div class="content-null">
                    <h1><i class="fa fa-file-text-o"></i></h1>
                    <br>
                    <h3>無預約記錄</h3>
                </div>
            </script>




            <script type="text/template7" id="templateBranchServiceBlock">
                {{#each serviceque_array}}
                <div class="subTitle">
                    {{date}}
                </div>
                <div class="list-block media-list severMainBlock">
                    <ul class="block-{{date}}">
                    </ul>
                </div>
                {{/each}}
            </script>

            <script type="text/template7" id="templateBranchServiceList">
                {{#each queue_array}}
                <li class="item-content">
                    <div class="item-media"><img data-src="{{ssd_picturepath}}" class="lazy" width="60" onerror='this.src="../app/image/general_user.png"'></div>
                    <div class="item-inner">
                        <div class="item-title-row">
                            <div class="item-title">{{ssqd_title}}</div>
                        </div>
                        <div class="item-subtitle">
                            <div class="row">
                                <div class="col-100">姓名：<span>{{md_cname}}</span></div>
                                <div class="col-100">車款：<span>{{car_model}}</span></div>
                                <div class="col-100">服務狀態：<span>{{status}}</span></div>
                            </div>
                        </div>
                    </div>
                </li>
                {{/each}}
            </script>

            <script type="text/template7" id="templateBranchServiceNull">
                <div class="content-null">
                    <h1><i class="fa fa-file-text-o"></i></h1>
                    <br>
                    <h3>無排隊記錄</h3>
                </div>
            </script>




            <script type="text/template7" id="templateBranchScanRecordItem">
                <div class="card" {{#if scm_balanceno}}onclick="code39('{{scm_balanceno}}')" {{/if}}>
                    <div class="card-header">{{scm_title}}</div>
                    <div class="card-content">
                        <div class="card-content-inner">

                            <div class="row">
                                <span class="col-60"><span class="note">活動期限：</span>{{scm_enddate}}</span>
                                <span class="col-40"><span class="note">收取數：</span>{{scanNum}}</span>
                            </div>

                            {{#if scm_balanceno}}<span><span class="note">銷帳條碼：</span>{{scm_balanceno}}</span>{{/if}}

                        </div>
                    </div>
                </div>
            </script>

            <script type="text/template7" id="templateBranchScanRecordNull">
                <div class="content-null">
                    <h1><i class="fa fa-search"></i></h1>
                    <br>
                    <h3>無掃描記錄</h3>
                </div>
            </script>

        </div>
    </div>
</div>