<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding temple-table-left">
                <a href="#" class="link icon-only open-popup" data-popup=".shop-menu">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div>
            <div class="center sliding">祈福報表</div>
            <div class="right print"><i class="fa fa-print" aria-hidden="true"></i></div>
            <!-- Sub navbar -->
            <div class="subnavbar">
                <div class="buttons-row">
                    <a href=".reconciliation-tab" class="button tab-link active reconciliation">
                        金流對帳報表
                    </a>
                    <a href=".sales-details-tab" class="button tab-link sales-details" style="border-right: 0;">
                        點燈用銷售明細
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page with-subnavbar" data-page="temple-table">
            <div class="tabs-animated-wrap">
                <div class="tabs">
                    <div class="page-content tab active animated fadeIn reconciliation-tab">
                        <div class="content-block">
                            <div class="search-block">日期區間：<input type="text" class="query_start" placeholder="請選擇起始日期" readonly /> ~ <input type="text" class="query_end" placeholder="請先選擇起始日" readonly />&nbsp;&nbsp;款項類型：
                            <select name="tpp_type" class="tpp_type_select">
                            <option value="0">請選擇</option>
                            <option value="1">祈福點燈</option>
                            <option value="2">香油錢捐獻</option>
                            </select><a href="#" class="button button-big button-fill search">查詢</a></div>
                            <div class="data-table-block data-table-block-0" id="data-table-block"></div>
                        </div>
                    </div>
                    <div class="page-content tab animated fadeIn sales-details-tab">
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
            <script type="text/template7" id="templateReconciliation">
            <div class="data-table data-table-init card">
                <div class="card-header">
                    <div class="data-table-title name"><b>{{report_head.sd_shopname}}</b></div>
                    <div class="data-table-title">金流對帳報表</div>
                    <div class="row">
                        <div class="col-50">款項類型：<span class="data-table-tpp_type">{{report_head.tpp_type}}</span></div>
                        <div class="col-50">銷售總金額：<span class="data-table-totalamount">NT {{report_head.totalamount}}</span></div>
                    </div>
                    <div class="data-table-date">期間：{{report_head.query_start}} ~ {{report_head.query_end}}</div>
                </div>
                <div class="card-content">
                    <table>
                        <thead>
                            <tr>
                                <th class="label-cell sortable-cell sortable-active tps_id">銷售編號</th>
                                <th class="numeric-cell tps_invoice_name">付款人</th>
                                <th class="numeric-cell sortable-cell tps_amount">付款金額</th>
                                <th class="numeric-cell sortable-cell create_date">建立日期</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{#each tps_record}}
                            <tr>
                                <td class="label-cell">{{tps_id}}</td>
                                <td class="numeric-cell tps_invoice_name">{{tps_invoice_name}}</td>
                                <td class="numeric-cell">{{tps_amount}}</td>
                                <td class="numeric-cell">{{create_date}}</td>
                            </tr>
                            {{/each}}
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-50">紀錄筆數：<span class="data-table-totalcount">{{report_foot.totalcount}}</span></div>
                        <div class="col-50">製表日期：<span class="data-table-query_date">{{report_foot.query_date}}</span></div>
                    </div>
                </div>
            </div>
            </script>
            <script type="text/template7" id="templateReconciliationItems">
            {{#each tps_record}}
            <tr>
                <td class="label-cell">{{tps_id}}</td>
                <td class="numeric-cell tps_invoice_name">{{tps_invoice_name}}</td>
                <td class="numeric-cell">{{tps_amount}}</td>
                <td class="numeric-cell">{{create_date}}</td>
            </tr>
            {{/each}}
            </script>
            <script type="text/template7" id="templateSalesDetails">
            <div class="data-table data-table-init card">
                <div class="card-header">
                    <div class="data-table-title name"><b>{{report_head.sd_shopname}}</b></div>
                    <div class="data-table-title">點燈用銷售明細報表</div>
                    <div class="row">
                        <div class="col-65">期間：<span class="data-table-daterange">{{report_head.query_start}} ~ {{report_head.query_end}}</span></div>
                        <div class="col-35">點燈總筆數：<span class="data-table-totalcount">{{report_head.totalcount}}</span></div>
                    </div>
                </div>
                <div class="card-content">
                    <table>
                        <thead>
                            <tr>
                                <th class="label-cell sortable-cell sortable-active tps_id">銷售編號</th>
                                <!-- <th class="numeric-cell sortable-cell tpsd_serno">銷售明細編號</th> -->
                                <th class="numeric-cell tpr_name">被點燈人姓名</th>
                                <th class="numeric-cell sortable-cell tpp_name">產品名稱</th>
                                <th class="numeric-cell sortable-cell create_date">建立日期</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{#each tps_record}}
                            <tr>
                                <td class="label-cell">{{tps_id}}</td>
                                <!-- <td class="numeric-cell">{{tpsd_serno}}</td> -->
                                <td class="numeric-cell tpr_name">{{tpr_name}}</td>
                                <td class="numeric-cell">{{tpp_name}}</td>
                                <td class="numeric-cell">{{create_date}}</td>
                            </tr>
                            {{/each}}
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-50"></div>
                        <div class="col-50">製表日期：<span class="data-table-query_date">{{report_foot.query_date}}</span></div>
                    </div>
                </div>
            </div>
            </script>
            <script type="text/template7" id="templateSalesDetailsItems">
            {{#each tps_record}}
            <tr>
                <td class="label-cell">{{tps_id}}</td>
                <!-- <td class="numeric-cell">{{tpsd_serno}}</td> -->
                <td class="numeric-cell tpr_name">{{tpr_name}}</td>
                <td class="numeric-cell">{{tpp_name}}</td>
                <td class="numeric-cell">{{create_date}}</td>
            </tr>
            {{/each}}
            </script>
        </div>
    </div>
</div>