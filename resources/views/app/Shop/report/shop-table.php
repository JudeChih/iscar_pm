<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding shop-table-left">
                <a href="order-form-management" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">汽車特店報表</div>
            <div class="right print"><i class="fa fa-print" aria-hidden="true"></i></div>
            <!-- Sub navbar -->
            <div class="subnavbar">
                <div class="buttons-row">
                    <a href=".shop-report-tab" class="button tab-link active shop-report">
                        銷貨對帳表
                    </a>
                    <a href=".shop-detail-report-tab" class="button tab-link sales-details" style="border-right: 0;">
                        銷貨明細表
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page with-subnavbar" data-page="shop-table">
            <div class="tabs-animated-wrap">
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
            </div>
                <!-- <div class="toolbar toolbar-bottom tabbar confirm animated fadeInUp">
                    <div class="toolbar-inner">
                        <a href="#" class="link">完成</a>
                    </div>
                </div> -->
                <script type="text/template7" id="templateShopReport">
                <div class="data-table data-table-init card">
                    <div class="card-header">
                        <div class="data-table-title name"><b>{{report_head.sd_shopname}}</b></div>
                        <div class="data-table-title">銷貨對帳表</div>
                        <div class="row">
                            <div class="col-50">金流總手續費：<span class="data-table-flowfeeamount">NT {{report_head.flowfeeamount}}</span></div>
                            <div class="col-50">銷售總金額：<span class="data-table-totalamount">NT {{report_head.totalamount}}</span></div>
                            <div class="col-50">平台總手續費：<span class="data-table-platfeeamount">NT {{report_head.platfeeamount}}</span></div>
                            <div class="col-50">實收總金額：<span class="data-table-revenueamount">NT {{report_head.revenueamount}}</span></div>
                            <div class="col-65">期間：<span class="data-table-daterange">{{report_head.query_start}} ~ {{report_head.query_end}}</span></div>
                            <div class="col-35">訂單數量：<span class="data-table-totalcount">{{report_head.totalcount}}</span></div>
                        </div>
                    </div>
                    <div class="card-content">
                        <table>
                            <thead>
                                <tr>
                                    <th class="label-cell sortable-cell sortable-active scg_id">銷售編號</th>
                                    <th class="numeric-cell sortable-cell scg_totalamount">銷售金額</th>
                                    <th class="numeric-cell sortable-cell flow_fee">金流手續費</th>
                                    <th class="numeric-cell sortable-cell plat_fee">平台手續費(10%)</th>
                                    <th class="numeric-cell sortable-cell revenue">實收金額</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each report_record}}
                                <tr>
                                    <td class="label-cell">{{scg_id}}</td>
                                    <td class="numeric-cell">{{scg_totalamount}}</td>
                                    <td class="numeric-cell">{{flow_fee}}</td>
                                    <td class="numeric-cell">{{plat_fee}}</td>
                                    <td class="numeric-cell">{{revenue}}</td>
                                </tr>
                                {{/each}}
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-50"></div>
                            <div class="col-50">製表日期：<span class="data-table-create_date">{{report_foot.create_date}}</span></div>
                        </div>
                    </div>
                </div>
                </script>
                <script type="text/template7" id="templateShopReportItems">
                {{#each report_record}}
                <tr>
                    <td class="label-cell">{{scg_id}}</td>
                    <td class="numeric-cell">{{scg_totalamount}}</td>
                    <td class="numeric-cell">{{flow_fee}}</td>
                    <td class="numeric-cell">{{plat_fee}}</td>
                    <td class="numeric-cell">{{revenue}}</td>
                </tr>
                {{/each}}
                </script>
                <script type="text/template7" id="templateShopDetailReport">
                <div class="data-table data-table-init card">
                    <div class="card-header">
                        <div class="data-table-title name"><b>{{report_head.sd_shopname}}</b></div>
                        <div class="data-table-title">銷貨明細表</div>
                        <div class="row">
                            <div class="col-50">銷售總數量：<span class="data-table-buyamount">{{report_head.buyamount}}</span></div>
                            <div class="col-50">銷售總金額：<span class="data-table-totalamount">NT {{report_head.totalamount}}</span></div>
                            <div class="col-65">期間：<span class="data-table-daterange">{{report_head.query_start}} ~ {{report_head.query_end}}</span></div>
                            <div class="col-35">訂單數量：<span class="data-table-totalcount">{{report_head.totalcount}}</span></div>
                        </div>
                    </div>
                    <div class="card-content">
                        <table>
                            <thead>
                                <tr>
                                    <th class="label-cell sortable-cell sortable-active scg_id">銷售編號</th>
                                    <th class="label-cell scm_title text-left">商品名稱</th>
                                    <th class="label-cell scm_producttype text-left">商品類型</th>
                                    <th class="numeric-cell sortable-cell scg_buyprice">售價</th>
                                    <th class="numeric-cell sortable-cell scg_buyamount">數量</th>
                                    <th class="numeric-cell sortable-cell scg_totalamount">小計</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each report_record}}
                                <tr>
                                    <td class="label-cell">{{scg_id}}</td>
                                    <td class="label-cell text-left">{{scm_title}}</td>
                                    <td class="label-cell text-left">{{scm_producttype}}</td>
                                    <td class="numeric-cell">{{scg_buyprice}}</td>
                                    <td class="numeric-cell">{{scg_buyamount}}</td>
                                    <td class="numeric-cell">{{scg_totalamount}}</td>
                                </tr>
                                {{/each}}
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-50"></div>
                            <div class="col-50">製表日期：<span class="data-table-create_date">{{report_foot.create_date}}</span></div>
                        </div>
                    </div>
                </div>
                </script>
                <script type="text/template7" id="templateShopDetailReportItems">
                {{#each report_record}}
                <tr>
                    <td class="label-cell">{{scg_id}}</td>
                    <td class="label-cell text-left">{{scm_title}}</td>
                    <td class="label-cell text-left">{{scm_producttype}}</td>
                    <td class="numeric-cell">{{scg_buyprice}}</td>
                    <td class="numeric-cell">{{scg_buyamount}}</td>
                    <td class="numeric-cell">{{scg_totalamount}}</td>
                </tr>
                {{/each}}
                </script>
            </div>
        </div>
    </div>