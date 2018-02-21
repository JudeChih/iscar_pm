<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding shop-salesoverview-left">
                <a href="shop-records" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding shop-salesoverview-center">票劵銷售總覽</div>
            <div class="right"></div>
            <!-- Sub navbar -->
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="shop-salesoverview">
            <!-- <div class="tabs-animated-wrap">
                <div class="tabs">
                    <div class="page-content tab active animated fadeIn shop-report-tab">
                        <div class="content-block">
                            <div class="search-block">日期區間：<input type="text" class="query_start" placeholder="請選擇起始日期" readonly /> ~ <input type="text" class="query_end" placeholder="請先選擇起始日" readonly /><a href="#" class="button button-big button-fill search">查詢</a></div>
                            <div class="data-table-block data-table-block-0" id="data-table-block"></div>
                        </div>
                    </div>
                    <div class="page-content tab animated fadeIn shop-detail-report-tab">
                        <div class="content-block">
                            <div class="search-block">日期區間：<input type="text" class="query_start" placeholder="請選擇起始日期" readonly /> ~ <input type="text" class="query_end" placeholder="請先選擇起始日" readonly /><a href="#" class="button button-big button-fill search">查詢</a></div>
                            <div class="data-table-block"></div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="tabs-animated-wrap">
                <div class="tabs">
                    <div class="page-content tab animated fadeIn shop-sales-overview-tab">
                        <!-- <div class="content-block"> -->
                            <div class="row search-block">
                                <div class="col-70">
                                    <span>結算日期：</span>
                                    <div>
                                        <input class="select-date" type="text" placeholder="請選擇日期" readonly /></div>
                                    </div>
                                <div class="col-30"><a class="button search-btn">查詢</a></div>
                                <input type="hidden" name="date">
                            </div>
                            <div class="shop-sales-overview-block">
                                
                            </div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
                <!-- <div class="toolbar toolbar-bottom tabbar confirm animated fadeInUp">
                    <div class="toolbar-inner">
                        <a href="#" class="link">完成</a>
                    </div>
                </div> -->
            <script type="text/template7" id="templateSalesOverview">
                <div class="row no-gutter main_title">
                    <div class="col-50"><div class="title_text">{{report_head.sd_shopname}}</div><span class="">票劵銷售一覽</span></div>
                    <div class="col-50 show_date">
                        <span class="note">結算周期：&nbsp;{{report_head.close_start}} ~ {{report_head.close_end}}</span>
                        <span class="note">帳務結算日：&nbsp;{{report_head.query_end}}</span>
                        <span class="note">款項支付日：&nbsp;{{report_head.pay_day}}</span>
                    </div>
                </div>
                <!-- <div class="row no-gutter">
                    <div class="col-100 show_date">
                        <span class="note">結算周期：&nbsp;</span><span class="">{{report_head.close_start}} ~ {{report_head.close_end}}</span>
                    </div>
                    <div class="col-100 show_date">
                        <span class="note">帳務結算日：&nbsp;</span><span class="">{{report_head.query_end}}</span>
                    </div>
                    <div class="col-100 show_date">
                        <span class="note">款項支付日：&nbsp;</span><span class="">{{report_head.pay_day}}</span>
                    </div>
                </div> -->
                <div class="row no-gutter title">
                    <div class="col-50"><span class="">本期應收紀錄</span></div>
                </div>
                <div class="row no-gutter">
                    <div class="col-70"><span class="note">交易筆數：&nbsp;</span><span class="">{{report_head.totalcount}}</span>筆</div>
                    <a href="shop-table-front" class="col-30 href" data-usestatus='2'><span class="">查看詳細記錄</span></a>
                </div>
                <div class="row no-gutter">
                    <div class="col-100"><span class="note">總計金額：&nbsp;</span>NT <span class="money">{{report_head.totalamount}}</span></div>
                </div>
                <div class="row no-gutter">
                    <div class="col-100"><span class="note">平台手續費：&nbsp;</span>NT <span class="money">{{report_head.platfeeamount}}</span></div>
                </div>
                <div class="row no-gutter">
                    <div class="col-100"><span class="note">金流手續費：&nbsp;</span>NT <span class="money">{{report_head.flowfeeamount}}</span></div>
                </div>
                <div class="row no-gutter">
                    <div class="col-100"><span class="note">實收金額：&nbsp;</span>NT <span class="money">{{report_head.revenueamount}}</span></div>
                </div>


                <div class="row no-gutter title">
                    <div class="col-50"><span class="">未結算項目</span></div>
                </div>
                <div class="row no-gutter">
                    <div class="col-70"><span class="note">未施作筆數：&nbsp;</span><span class="">{{report_foot.totalcount}}</span>筆</div>
                    <a href="shop-table-front" class="col-30 href" data-usestatus='0'><span class="">查看詳細記錄</span></a>
                </div>
                <div class="row no-gutter">
                    <div class="col-100"><span class="note">總計金額：&nbsp;</span>NT <span class="money">{{report_foot.totalamount}}</span></div>
                </div>
                <div class="row no-gutter">
                    <div class="col-100"><span class="note">預約筆數：&nbsp;</span><span class="">{{report_foot.reData}}</span>筆</div>
                </div>
                <div class="row no-gutter">
                    <div class="col-100"><span class="note">非預約筆數：&nbsp;</span><span class="">{{report_foot.unreData}}</span>筆</div>
                </div>


            </script>
        </div>
    </div>
</div>