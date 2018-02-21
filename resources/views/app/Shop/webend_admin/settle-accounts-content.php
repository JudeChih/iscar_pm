<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding settle-accounts-content-left">
                <a href="settle-accounts" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding settle-accounts-content-center">2018-01-11 銷售結算</div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="settle-accounts-content">
            <!-- 內容 -->
            <div class="page-content settle-accounts-content">
                <div class="settle-accounts-content-block">
                    <!-- <div class="title">就是特店有限公司</div> -->
                    <!-- <div class="subtitle">本期銷售一覽</div>
                    <div class="row no-gutter">
                        <div class="col-100"><span class="note">結算週期：&nbsp;</span>2018-01-01 ~ 2018-01-10</div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-100"><span class="note">帳務結算日：&nbsp;</span>2018-01-11</div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-100"><span class="note">預期支付日：&nbsp;</span>2018-01-18</div>
                    </div>
                    <div class="subtitle" style="border: 0;"></div>
                    <div class="row no-gutter">
                        <div class="col-100"><span class="note">本期交易筆數：&nbsp;</span>25筆</div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-100"><span class="note">一般交易筆數：&nbsp;</span>20筆</div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-100"><span class="note">特點交易筆數：&nbsp;</span>5筆</div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-100"><span class="note">總計交易特點：&nbsp;</span>15000 pt</div>
                    </div>
                    <div class="subtitle" style="border: 0;"></div>
                    <div class="row no-gutter">
                        <div class="col-100"><span class="note">總計交易金額：&nbsp;</span>NTD 30,000</div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-100"><span class="note">平台手續費：&nbsp;</span>NTD 3,750</div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-100"><span class="note">本期應收金額：&nbsp;</span><span class="price">NTD 26,250</span></div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-100"><span class="note">確認截止日：&nbsp;</span>2017-01-17</div>
                    </div>
                    <div class="row no-gutter">
                        <div class="col-100"><span class="note">出款狀態：&nbsp;</span>未確認</div>
                    </div>
                    <a href="settle-accounts-table" class="row no-gutter">
                        <div class="col-80">本期交易明細</div>
                        <div class="col-20">查看 <span class="icon-chevron-right"></span></div>
                    </a>
                    <div class="remark">備註訊息<br>若帳務有問題，請點擊帳務有誤鈕，通知服務人員處理。</div> -->
                </div>
            </div>
            <div class="toolbar toolbar-bottom tabbar animated fadeInUp">
                <div class="toolbar-inner">
                    <div class="row no-gutter">
                        <div class="col-50 error"><a href="#" class="link">帳務有誤</a></div>
                        <div class="col-50 request"><a href="#" class="link">確認請款</a></div>
                    </div>
                </div>
            </div>
            <script type="text/template7" id="templateSettleAccountsContent">
            <div class="subtitle">本期銷售一覽</div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">結算週期：&nbsp;</span>{{ssrm_billingcycle_start}} ~ {{ssrm_billingcycle_end}}</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">帳務結算日：&nbsp;</span>{{ssrm_settledate}}</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">預期支付日：&nbsp;</span>{{ssrm_billpaymentday}}</div>
            </div>
            <div class="subtitle" style="border: 0;"></div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">本期交易筆數：&nbsp;</span>{{ssrm_totaltransatctioncount}}筆</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">一般交易筆數：&nbsp;</span>{{salecount}}筆</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">特點交易筆數：&nbsp;</span>{{ssrm_salebypp}}筆</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">總計交易特點：&nbsp;</span>{{ssrm_totalppconsume}} pt</div>
            </div>
            <div class="subtitle" style="border: 0;"></div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">總計交易金額：&nbsp;</span>NTD {{saleamount}}</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">服務費：&nbsp;</span>NTD {{ssrm_totalplatformfee}}</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">本期應收金額：&nbsp;</span><span class="price">NTD {{ssrm_settlementpayamount}}</span></div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">確認截止日：&nbsp;</span>{{settlementreview_deadine}}</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">出款狀態：&nbsp;</span>{{settlement_status}}</div>
            </div>
            <a href="settle-accounts-table?ssrm_id={{ssrm_id}}" class="row no-gutter">
                <div class="col-80">本期交易明細</div>
                <div class="col-20">查看 <span class="icon-chevron-right"></span></div>
            </a>
            <div class="remark">備註訊息<br>若帳務有問題，請點擊帳務有誤鈕，通知服務人員處理。</div>
            </script>
        </div>
    </div>
</div>