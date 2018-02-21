<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding settle-accounts-table-left">
                <a class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding settle-accounts-table-center">銷售對帳表</div>
            <div class="right noUse">
                <a href="#" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
        </div>
    </div>
    <div class="pages">
        <div class="page" data-page="settle-accounts-table">
            <div class="page-content settle-accounts-table-content">

            </div>
            <script type="text/template7" id="templateSettleAccountsTableBlock">
            <div class="row no-gutter">
                    <div class="col-100"><span class="note">結算週期：&nbsp;</span>{{ssrm_billingcycle_start}} ~ {{ssrm_billingcycle_end}}</div>
                </div>
                <div class="row no-gutter">
                    <div class="col-100"><span class="note">本期交易筆數：&nbsp;</span>{{ssrm_totaltransatctioncount}}筆</div>
                </div>
                <div class="row no-gutter">
                    <div class="col-100"><span class="note">本期應收金額：&nbsp;</span><span class="price">NTD {{ssrm_settlementpayamount}}</span></div>
                </div>
                <div class="content-block">
                    <!-- Buttons row as tabs controller -->
                    <div class="buttons-row">
                        <!-- Link to 1st tab, active -->
                        <a class="tab-link billingtype1 button" billingtype="1">一般交易</a>
                        <!-- Link to 2nd tab -->
                        <a class="tab-link billingtype3 button" billingtype="3">特點交易</a>
                    </div>
                </div>
                <!-- Swiper -->
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <!-- <div class="swiper-slide">
                            <div class="data-table card">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="label-cell">訂單編號</th>
                                            <th class="label-cell">交易時間</th>
                                            <th class="numeric-cell">實收金額</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div> -->
                    </div>
                    <!-- Add Pagination -->
                    <!-- <div class="swiper-pagination"></div> -->
                </div>
                <div class="pagination">
                    <div class="pagination-content"><span class="previous-page"><i class="fa fa-chevron-left" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><input class="now-page" type="number" value="1" readonly />&nbsp;&nbsp;/&nbsp;&nbsp;<span class="sum-pages"></span><span class="next-page">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-chevron-right" aria-hidden="true"></i></span></div>
                </div>
            </script>
            <script type="text/template7" id="templateSettleAccountsTable1">
            {{#each query_shopsettlementrec_d_list}}
            <tr>
                <th class="label-cell">{{scg_id}}</th>
                <th class="label-cell">{{scg_paid_time}}</th>
                <th class="numeric-cell">{{receive_amount}}</th>
            </tr>
            {{/each}}
            </script>
            <script type="text/template7" id="templateSettleAccountsTable3">
            {{#each query_shopsettlementrec_d_list}}
            <tr>
                <th class="label-cell">{{scg_id}}</th>
                <th class="label-cell">{{scg_paid_time}}</th>
                <th class="numeric-cell">{{sale_amount}}</th>
            </tr>
            {{/each}}
            </script>
        </div>
    </div>
</div>