<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding settle-accounts-left">
                <a href="shop-records" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding settle-accounts-center">帳務結算紀錄</div>
            <div class="right noUse">
                <div class="iscar_member_login iscar_member_icon" from="Shop"></div>
            </div>
        </div>
    </div>
    <div class="pages">
        <div class="page" data-page="settle-accounts">
            <div class="page-content">
                <div class="list-head">
                    <div class="row">
                        <div class="col-55">結算日期</div>
                        <div class="col-25">狀態</div>
                        <div class="col-20"></div>
                    </div>
                </div>
                <div class="list-block settle-accounts-list">
                    <ul>
                        <!-- <li>
                            <div class="item-content">
                                <div class="item-inner">
                                    <a href="settle-accounts-content">
                                        <div class="row">
                                            <div class="col-55">2018-01-11</div>
                                            <div class="col-25 not_reviewed">未覆核</div>
                                            <div class="col-20">查看 <span class="icon-chevron-right"></span></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item-content">
                                <div class="item-inner">
                                    <a href="settle-accounts-content">
                                        <div class="row">
                                            <div class="col-55">2018-01-11</div>
                                            <div class="col-25 adjust">調帳中</div>
                                            <div class="col-20">查看 <span class="icon-chevron-right"></span></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item-content">
                                <div class="item-inner">
                                    <a href="settle-accounts-content">
                                        <div class="row">
                                            <div class="col-55">2018-01-11</div>
                                            <div class="col-25 reviewed">已覆核</div>
                                            <div class="col-20">查看 <span class="icon-chevron-right"></span></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item-content">
                                <div class="item-inner">
                                    <a href="settle-accounts-content">
                                        <div class="row">
                                            <div class="col-55">2018-01-11</div>
                                            <div class="col-25">已出款</div>
                                            <div class="col-20">查看 <span class="icon-chevron-right"></span></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="noUse">
                        </li> -->
                    </ul>
                </div>
            </div>
            <script type="text/template7" id="templateSettleAccounts">
            {{#each shopsettlementrec_m_list}}
            <li>
                <div class="item-content">
                    <div class="item-inner">
                        <a href="settle-accounts-content?ssrm_id={{ssrm_id}}">
                            <div class="row">
                                <div class="col-55">{{ssrm_settledate}}</div>
                                <div class="col-25">{{settlement_status}}</div>
                                <div class="col-20">查看 <span class="icon-chevron-right"></span></div>
                            </div>
                        </a>
                    </div>
                </div>
            </li>
            {{/each}}
            </script>
            <script type="text/template7" id="templateSettleAccountsNull">
                <div class="content-null">
                    <h1><i class="fa fa-file-text-o"></i></h1>
                    <br>
                    <h3>暫無記錄</h3>
                </div>
            </script>
        </div>
    </div>
</div>