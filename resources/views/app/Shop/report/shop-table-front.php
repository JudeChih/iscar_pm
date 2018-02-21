<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding shop-table-front-left">
                <a href="shop-salesoverview" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">汽車特店報表</div>
            <div class="right print"><i class="fa fa-print" aria-hidden="true"></i></div>
            <!-- Sub navbar -->
            <!-- <div class="subnavbar">
                <div class="buttons-row">
                    <a href=".shop-report-tab" class="button tab-link active shop-report">
                        銷貨對帳表
                    </a>
                    <a href=".shop-detail-report-tab" class="button tab-link sales-details" style="border-right: 0;">
                        銷貨明細表
                    </a>
                </div>
            </div> -->
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="shop-table-front">
            <div class="tabs-animated-wrap">
                <div class="tabs">
                    <div class="page-content tab animated fadeIn shop-report-tab">
                        <div class="content-block">
                            <!-- <div class="search-block">日期區間：<input type="text" class="query_start" readonly /> ~ <input type="text" class="query_end" readonly /></div> -->
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
                        <div class="data-table-title name"><b>{{report_result.sd_shopname}}</b></div>
                        <div class="data-table-title">銷貨對帳表</div>
                        <div class="row">
                            <div class="col-50">銷售總金額：<span class="data-table-totalamount">NT {{report_result.totalamount}}</span></div>
                            <div class="col-50">金流總手續費：<span class="data-table-flowfeeamount">NT {{report_result.flowfeeamount}}</span></div>
                            <div class="col-50">應收總金額：<span class="data-table-revenueamount">NT {{report_result.revenueamount}}</span></div>
                            <div class="col-50">平台總手續費：<span class="data-table-platfeeamount">NT {{report_result.platfeeamount}}</span></div>
                            <div class="col-65">期間：<span class="data-table-daterange">{{report_result.query_start}} ~ {{report_result.query_end}}</span></div>
                            <div class="col-35">訂單數量：<span class="data-table-totalcount">{{report_result.totalcount}}</span></div>
                        </div>
                    </div>
                    <div class="card-content">
                        <table>
                            <thead>
                                <tr>
                                    <th class="label-cell sortable-cell sortable-active scg_id">訂單編號</th>
                                    <th class="sortable-cell scm_title">商品名稱</th>
                                    <!-- <th class="numeric-cell sortable-cell scg_buyprice">售價</th>
                                    <th class="numeric-cell sortable-cell scg_buyamount">數量</th> -->
                                    <th class="sortable-cell scg_totalamount">訂單金額</th>
                                    <th class="sortable-cell service_fee">服務費</th>
                                    <!-- <th class="sortable-cell flow_fee">金流手續費</th>
                                    <th class="sortable-cell plat_fee">平台手續費(10%)</th> -->
                                    <th class="sortable-cell revenue">應收金額</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#each report_record}}
                                <tr>
                                    <td class="label-cell">{{scg_id}}</td>
                                    <td class="">{{scm_title}}</td>
                                    <!-- <td class="numeric-cell">{{scg_buyprice}}</td>
                                    <td class="numeric-cell">{{scg_buyamount}}</td> -->
                                    <td class="numeric-cell">{{scg_totalamount}}</td>
                                    <td class="numeric-cell">{{service_fee}}</td>
                                    <!-- <td class="numeric-cell">{{flow_fee}}</td>
                                    <td class="numeric-cell">{{plat_fee}}</td> -->
                                    <td class="numeric-cell">{{revenue}}</td>
                                </tr>
                                {{/each}}
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-50"></div>
                            <div class="col-50">製表日期：<span class="data-table-create_date">{{report_result.create_date}}</span></div>
                        </div>
                    </div>
                </div>
                </script>
                <script type="text/template7" id="templateShopReportItems">
                {{#each report_record}}
                <tr>
                    <td class="label-cell">{{scg_id}}</td>
                    <td class="">{{scm_title}}</td>
                    <!-- <td class="numeric-cell">{{scg_buyprice}}</td>
                    <td class="numeric-cell">{{scg_buyamount}}</td> -->
                    <td class="numeric-cell">{{scg_totalamount}}</td>
                    <td class="numeric-cell">{{service_fee}}</td>
                    <!-- <td class="numeric-cell">{{flow_fee}}</td>
                    <td class="numeric-cell">{{plat_fee}}</td> -->
                    <td class="numeric-cell">{{revenue}}</td>
                </tr>
                {{/each}}
                </script>
            </div>
        </div>
    </div>