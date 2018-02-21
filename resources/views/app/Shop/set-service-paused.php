<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding">
                <a href="appointment-management" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">設定暫停服務時間</div>
            <div class="right">
                <!-- <div class="iscar_member_login iscar_member_icon" from="Shop"></div> -->
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="set-service-paused">
            <!-- 內容 -->
            <div class="page-content animated fadeIn">
                <div class="list-block media-list">
                    <ul>
                        <li>
                            <div class="item-content item-input">
                                <div class="item-inner">
                                    <div class="item-input-wrap">
                                        <input type="text" placeholder="選擇開始時間" readonly="readonly" id="rp_start_datetime"/>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item-content item-input">
                                <div class="item-inner">
                                    <div class="item-input-wrap">
                                        <input type="text" placeholder="選擇結束時間" readonly="readonly" id="rp_end_datetime"/>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="toolbar toolbar-bottom tabbar add_service_paused animated fadeInUp">
                <div class="toolbar-inner">
                    <a href="#" class="link">新增</a>
                </div>
            </div>
            <script type="text/template7" id="templateBonusList">
            <li class="item-content">
                <div class="item-inner">
                    <div class="item-subtitle">
                        <div class="row no-gutter">
                            <div class="col-45">
                                <div class="row">
                                    <div class="col-100">2018-07-12</div>
                                    <div class="col-100">09:00</div>
                                </div>
                            </div>
                            <div class="col-45">
                                <div class="row">
                                    <div class="col-100">2018-07-12</div>
                                    <div class="col-100">09:00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            </script>
            <script type="text/template7" id="templateBonusListNull">
            <div class="content-null">
                <h1><i class="fa fa-file-text-o" aria-hidden="true"></i></h1>
                <br>
                <h3>{{text}}</h3>
            </div>
            </script>
        </div>
    </div>
</div>