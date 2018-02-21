<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding">
                <a href="shopcoupon-main" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">暫停預約管理</div>
            <div class="right">
                <!-- <div class="iscar_member_login iscar_member_icon" from="Shop"></div> -->
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="appointment-management">
            <!-- 內容 -->
            <div class="page-content animated fadeIn">
                <div class="list-block media-list reservationpaused_list">
                    <ul>
                        <!-- <li class="item-content">
                            <div class="item-inner">
                                <div class="item-subtitle">
                                    <div class="row no-gutter">
                                        <div class="col-40">
                                            <div class="row">
                                                <div class="col-100">2018-07-12 (五)</div>
                                                <div class="col-100 time">09:00</div>
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                        </div>
                                        <div class="col-40">
                                            <div class="row">
                                                <div class="col-100">2018-07-12 (五)</div>
                                                <div class="col-100 time">16:30</div>
                                            </div>
                                        </div>
                                        <div class="col-10 del">
                                            <i class="fa fa-times-circle" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li> -->
                    </ul>
                </div>
            </div>
            <div class="toolbar toolbar-bottom tabbar add_service_paused animated fadeInUp">
                <div class="toolbar-inner">
                    <a href="#" class="link">新增暫停時間</a>
                </div>
            </div>
            <div class="popup popup-service-paused">
                <div class="close-btn">
                    <a href="#" class="close-popup">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="page-content animated fadeIn">
                    <div class="title">設置暫停服務時間</div>
                    <div class="list-block">
                        <ul>
                            <li>
                                <div class="item-content item-input">
                                    <div class="item-inner">
                                        <div class="item-input-wrap">
                                            <input type="text" readonly="readonly" class="rp_start_date"/>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item-content item-input">
                                    <div class="item-inner">
                                        <div class="item-input-wrap">
                                            <input type="text" readonly="readonly" class="rp_start_time"/>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="arrow"><i class="fa fa-arrow-down" aria-hidden="true"></i></div>
                            </li>
                            <li>
                                <div class="item-content item-input">
                                    <div class="item-inner">
                                        <div class="item-input-wrap">
                                            <input type="text" readonly="readonly" class="rp_end_date" />
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item-content item-input">
                                    <div class="item-inner">
                                        <div class="item-input-wrap">
                                            <input type="text" readonly="readonly" class="rp_end_time" />
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="toolbar toolbar-bottom add_service_paused animated fadeInUp">
                    <div class="toolbar-inner">
                        <a href="#" class="link">新增</a>
                    </div>
                </div>
            </div>
            <script type="text/template7" id="templateServicePausedList">
            {{#each reservationpaused_list}}
            <li class="item-content animated flipInX">
                <div class="item-inner">
                    <div class="item-subtitle">
                        <div class="row no-gutter">
                            <div class="col-40">
                                <div class="row">
                                    <div class="col-100">{{rp_start_date}}</div>
                                    <div class="col-100 time">{{rp_start_time}}</div>
                                </div>
                            </div>
                            <div class="col-10">
                                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                            </div>
                            <div class="col-40">
                                <div class="row">
                                    <div class="col-100">{{rp_end_date}}</div>
                                    <div class="col-100 time">{{rp_end_time}}</div>
                                </div>
                            </div>
                            <div class="col-10 del" rp_serno="{{rp_serno}}">
                                <i class="fa fa-times-circle" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            {{/each}}
            </script>
            <script type="text/template7" id="templateServicePausedNull">
            <div class="content-null">
                <h1><i class="fa fa-file-text-o" aria-hidden="true"></i></h1>
                <br>
                <h3>{{text}}</h3>
            </div>
            </script>
        </div>
    </div>
</div>