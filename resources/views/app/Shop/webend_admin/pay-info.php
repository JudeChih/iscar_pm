<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding pay-info-left">
                <a class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">支付內容</div>
            <div class="right"></div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="pay-info">
            <div class="page-content pay-info-content">
                <div class="pay-info-block">
                </div>
            </div>
            <div class="toolbar toolbar-bottom tabbar pay animated fadeInUp">
                <div class="toolbar-inner">
                    <a href="#" class="link">立即支付</a>
                </div>
            </div>
            <script type="text/template7" id="templateEsafeInfoForm">
            <form class="scg_form" name="scg_form" action="http://test.esafe.com.tw/Service/Etopm.aspx" method="POST">
                <input type="hidden" name="web" value="{{web}}" /> <!-- 1.商店代號-->
                <input type="hidden" name="MN" value="{{MN}}" /> <!--2.*交易金額-->
                <input type="hidden" name="OrderInfo" value="{{OrderInfo}}" /> <!--3.交易內容-->
                <input type="hidden" name="Td" value="{{Td}}" /> <!--4.商家訂單編號-->
                <input type="hidden" name="sna" value="{{sna}}" /> <!--5.消費者姓名-->
                <input type="hidden" name="sdt" value="{{sdt}}" /> <!--6.消費者電話-->
                <input type="hidden" name="email" value="" /> <!--7.消費者Email-->
                <input type="hidden" name="note1" value="{{note1}}" /> <!--8.備註-->
                <input type="hidden" name="note2" value="{{note2}}" /> <!--9.備註-->
                <input type="hidden" name="Card_Type" value="{{Card_Type}}" /> <!--10.交易類別-->
                <input type="hidden" name="Country_Type" value="{{Country_Type}}" /> <!--11.語言類別-->
                <input type="hidden" name="Term" value="{{Term}}" /> <!--12.分期期數-->
                <input type="hidden" name="ChkValue" value="{{ChkValue}}" /> <!--13.交易檢查碼-->
            </form>
            </script>
            <script type="text/template7" id="templateEcpayInfoForm">
            <form class="scg_form" name="scg_form" action="https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5" method="POST">
                <input type="hidden" name="ChoosePayment" value="{{ChoosePayment}}" />
                <input type="hidden" name="CustomField1" value="{{CustomField1}}" />
                <input type="hidden" name="CustomField2" value="{{CustomField2}}" />
                <input type="hidden" name="CustomField3" value="{{CustomField3}}" />
                <input type="hidden" name="CustomField4" value="{{CustomField4}}" />
                <input type="hidden" name="DeviceSource" value="{{DeviceSource}}" />
                <input type="hidden" name="EncryptType" value="{{EncryptType}}" />
                <input type="hidden" name="HoldTradeAMT" value="{{HoldTradeAMT}}" />
                <input type="hidden" name="IgnorePayment" value="{{IgnorePayment}}" />
                <input type="hidden" name="ItemName" value="{{ItemName}}" />
                <input type="hidden" name="MerchantID" value="{{MerchantID}}" />
                <input type="hidden" name="MerchantTradeDate" value="{{MerchantTradeDate}}" />
                <input type="hidden" name="MerchantTradeNo" value="{{MerchantTradeNo}}" />
                <input type="hidden" name="OrderResultURL" value="{{OrderResultURL}}" />
                <input type="hidden" name="PaymentType" value="{{PaymentType}}" />
                <input type="hidden" name="PlatformID" value="{{PlatformID}}" />
                <input type="hidden" name="Remark" value="{{Remark}}" />
                <input type="hidden" name="ReturnURL" value="{{ReturnURL}}" />
                <input type="hidden" name="StoreID" value="{{StoreID}}" />
                <input type="hidden" name="TotalAmount" value="{{TotalAmount}}" />
                <input type="hidden" name="TradeDesc" value="{{TradeDesc}}" />
                <input type="hidden" name="CheckMacValue" value="{{CheckMacValue}}" />
            </form>
            </script>
            <script type="text/template7" id="templatePayInfo">
            <div class="list-block animated fadeIn">
                <ul>
                    <li class="item-content item-head" style="background-image: url('{{scm_pic}}');">
                        <div class="item-inner">
                            <!-- <div class="title">{{scm_item}}</div> -->
                        </div>
                    </li>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="row">
                                <div class="col-33">商品名稱：</div>
                                <div class="col-66 amount-block">{{scm_item}}</div>
                            </div>
                        </div>
                    </li>
                    <!-- <li class="item-content">
                        <div class="item-inner">
                            <div class="row">
                                <div class="col-33">小計：</div>
                                <div class="col-66"><span class="price">{{scm_item_sum}}</span></div>
                            </div>
                        </div>
                    </li> -->
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="row">
                                <div class="col-33">數量：</div>
                                <div class="col-66 amount-block">{{scl_amount}}</div>
                            </div>
                        </div>
                    </li>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="row">
                                <div class="col-33">交易金額：</div>
                                <div class="col-66 price-block"><span class="price">{{scm_item_sum}}</span></div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            {{#if scl_receivername}}
            <div class="receiver-block">
                <div class="subtitle">---------- 收件人 ----------</div>
                <div class="list-block pay-items animated fadeIn">
                    <ul>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="row">
                                    <div class="col-33">收件人：</div>
                                    <div class="col-66 item-title">{{scl_receivername}}</div>
                                </div>
                            </div>
                        </li>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="row">
                                    <div class="col-33">手機：</div>
                                    <div class="col-66 item-title">{{scl_receivermobile}}</div>
                                </div>
                            </div>
                        </li>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="row">
                                    <div class="col-33">電話：</div>
                                    <div class="col-66 item-title">{{scl_receiverphone}}</div>
                                </div>
                            </div>
                        </li>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="row">
                                    <div class="col-33">Email：</div>
                                    <div class="col-66 item-title">{{scl_email}}</div>
                                </div>
                            </div>
                        </li>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="row">
                                    <div class="col-33">地址：</div>
                                    <div class="col-66 item-title">{{scl_city}}{{scl_district}}{{scl_receiveaddress}}</div>
                                </div>
                            </div>
                        </li>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="row">
                                    <div class="col-33">到貨時段：</div>
                                    <div class="col-66 item-title">{{scl_delivery_time}}</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            {{/if}}
            <div class="buyer-block">
                <div class="subtitle">---------- 付款人 ----------</div>
                <div class="list-block pay-items animated fadeIn">
                    <ul>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="row">
                                    <div class="col-33">付款人：</div>
                                    <div class="col-66 item-title">{{scg_buyername}}</div>
                                </div>
                            </div>
                        </li>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="row">
                                    <div class="col-33">手機：</div>
                                    <div class="col-66 item-title">{{scg_buyermobile}}</div>
                                </div>
                            </div>
                        </li>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="row">
                                    <div class="col-33">電話：</div>
                                    <div class="col-66 item-title">{{scg_buyerphone}}</div>
                                </div>
                            </div>
                        </li>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="row">
                                    <div class="col-33">Email：</div>
                                    <div class="col-66 item-title">{{scg_buyeremail}}</div>
                                </div>
                            </div>
                        </li>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="row">
                                    <div class="col-33">留言：</div>
                                    <div class="col-66 item-title">{{scg_buyermessage}}</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            </script>
        </div>
    </div>
</div>