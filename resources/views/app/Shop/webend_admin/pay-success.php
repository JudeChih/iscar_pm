<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding">
                <!--<a href="branch-cooperative.html" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>-->
            </div>
            <div class="center sliding">支付詳情</div>
            <div class="right"></div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="pay-success">

            <div class="page-content pay-success-content">

                <div class="success-block animated fadeInRight">
                    <i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;支付完成
                </div>

                <div class="pay-success-block animated fadeIn">


                </div>

            </div>

            <div class="toolbar toolbar-bottom tabbar confirm animated fadeInUp">
                <div class="toolbar-inner">
                    <a href="#" class="link">完成</a>
                </div>
            </div>


            <script type="text/template7" id="templatePaySuccess">
                <div class="title-block">
                    {{sd_shopname}}
                </div>

                <div class="info-block">
                    <div class="list-block">
                        <ul>

                            <li class="item-content">
                                <div class="item-inner">
                                    <div class="row">
                                        <div class="col-30">購買項目</div>
                                        <div class="col-70">{{scm_title}}</div>
                                    </div>
                                </div>
                            </li>
                            <li class="item-content">
                                <div class="item-inner">
                                    <div class="row">
                                        <div class="col-30">交易單號</div>
                                        <div class="col-70">{{scg_id}}</div>
                                    </div>
                                </div>
                            </li>
                            <li class="item-content">
                                <div class="item-inner">
                                    <div class="row">
                                        <div class="col-30">交易時間</div>
                                        <div class="col-70">{{pay_time}}</div>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="connection-block">
                    <div class="list-block">
                        <ul>

                            <li class="item-content">
                                <div class="item-inner">
                                    <div class="row">
                                        <div class="col-30">聯絡號碼</div>
                                        <div class="col-70">{{sd_shoptel}}</div>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="price-block">
                    <i class="fa fa-usd" aria-hidden="true"></i>&nbsp;{{scg_buyamount}}
                </div>
            </script>

        </div>
    </div>
</div>
