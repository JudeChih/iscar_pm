<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding shop-buy-data-left">
                <a class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding shop-buy-data-center"></div>
            <div class="right">
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="shop-buy-data">
            <!-- 內容 -->
            <div class="page-content shop-buy-data-content animated fadeIn">
                <div class="order-block">
                    <div class="subtitle">訂購資訊</div>
                    <div class="list-block">
                        <ul>
                            <li class="align-top scm_name-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-cube" aria-hidden="true"></i></div>
                                    <div class="item-inner readonly">
                                        <div class="item-title label">商品名稱</div>
                                        <div class="item-input">
                                            <input class="scm_name" type="text" placeholder="名稱" readonly>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top scm_type-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-tag" aria-hidden="true"></i></div>
                                    <div class="item-inner readonly">
                                        <div class="item-title label">商品類別</div>
                                        <div class="item-input">
                                            <input class="scm_type" type="text" placeholder="類別" readonly>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top scm_price-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-usd" aria-hidden="true"></i></div>
                                    <div class="item-inner readonly">
                                        <div class="item-title label">商品單價</div>
                                        <div class="item-input">
                                            <input class="scm_price" type="text" placeholder="單價" readonly>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top scg_buyamount-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-cart-plus" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">購買數量</div>
                                        <div class="item-input">
                                            <input class="scg_buyamount" type="number" placeholder="數量" readonly>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top scl_sum-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-usd" aria-hidden="true"></i></div>
                                    <div class="item-inner readonly">
                                        <div class="item-title label">應付金額</div>
                                        <div class="item-input">
                                            <!-- <input class="scl_sum" type="text" placeholder="請填寫購買數量" readonly style="color: red; font-weight: bold;"> -->
                                            <div class="input scl_sum" style="color: red; font-weight: bold;"></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top scg_bonus-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-usd" aria-hidden="true"></i></div>
                                    <div class="item-inner readonly">
                                        <div class="item-title label">扣除特點</div>
                                        <div class="item-input">
                                            <input class="scg_bonus" type="number" placeholder="請填寫購買數量" readonly style="color: red; font-weight: bold;">
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="gift_discount-block">
                    <div class="subtitle">禮點折扣</div>
                    <div class="list-block">
                        <ul>
                            <li class="align-top md_gift-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-gift" aria-hidden="true"></i></div>
                                    <div class="item-inner readonly">
                                        <div class="item-title label">持有禮點</div>
                                        <div class="item-input">
                                            <!-- <input class="md_gift" type="text" readonly style="color: orange; font-weight: bold;"> -->
                                            <div class="input"><span class="md_gift"></span><span class="input-after ntd"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top max_discount-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-usd" aria-hidden="true"></i></div>
                                    <div class="item-inner readonly">
                                        <div class="item-title label">最大折抵</div>
                                        <div class="item-input">
                                            <!-- <input class="max_discount" type="text" readonly> -->
                                            <div class="input"><span class="max_discount"></span><span class="input-after gift-point"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top discount-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-usd" aria-hidden="true"></i></div>
                                    <div class="item-inner readonly">
                                        <div class="item-title label">欲折抵</div>
                                        <div class="item-input">
                                            <div class="input">NTD&nbsp;<input class="discount" type="number" value="0" style="color: #111;"><span class="input-after gift-point">(0&nbsp;p)</span></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="invoice_code-block">
                    <div class="subtitle">電子發票</div>
                    <div class="list-block">
                        <ul>
                            <li class="align-top invoice_type-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-tags" aria-hidden="true"></i></div>
                                    <div class="item-inner readonly">
                                        <div class="item-title label">發票類別</div>
                                        <div class="item-input">
                                            <input class="invoice_type" type="text" placeholder="請選擇發票類別" value="個人電子發票" readonly>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top tax_title-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-building" aria-hidden="true"></i></div>
                                    <div class="item-inner readonly">
                                        <div class="item-title label">抬頭</div>
                                        <div class="item-input">
                                            <input class="tax_title" type="text" placeholder="請輸入抬頭">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top tax_id-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-barcode" aria-hidden="true"></i></div>
                                    <div class="item-inner readonly">
                                        <div class="item-title label">統一編號</div>
                                        <div class="item-input">
                                            <input class="tax_id" type="number" placeholder="請輸入統一編號" onkeydown="input_limit(this, 8);" onkeyup="input_limit(this, 8);">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top send_addr-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-map-signs" aria-hidden="true"></i></div>
                                    <div class="item-inner readonly">
                                        <div class="item-title label">寄送地址</div>
                                        <div class="item-input">
                                            <textarea class="resizable send_addr" placeholder="請輸入詳細地址"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tips">* 電子發票資訊會寄至購買資訊所指定之e-mail信箱</div>
                </div>
                <div class="recipient-block">
                    <div class="subtitle">購買人資訊</div>
                    <div class="list-block">
                        <ul>
                            <li class="align-top scg_buyername-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-user" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">姓名</div>
                                        <div class="item-input">
                                            <input class="scg_buyername" type="text" placeholder="ex: 王大明">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top scg_buyermobile-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-mobile" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">手機</div>
                                        <div class="item-input">
                                            <input class="scg_buyermobile" type="text" placeholder="ex: 0988888888">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top scg_buyerphone-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-phone" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">電話</div>
                                        <div class="item-input">
                                            <input class="scg_buyerphone" type="text" placeholder="ex: 0800888888">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top scg_buyeremail-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">Email</div>
                                        <div class="item-input">
                                            <input class="scg_buyeremail" type="text" placeholder="ex: demo@iscar.com">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top scg_cartype-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-car" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">車款</div>
                                        <div class="item-input">
                                            <input class="scg_cartype" type="text" placeholder="請選擇車款" readonly>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top scg_buyermessage-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-commenting" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">留言</div>
                                        <div class="item-input">
                                            <textarea class="resizable scg_buyermessage" placeholder="註明事項或其他要求"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- <li class="align-top scl_country-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-globe" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">地區</div>
                                        <div class="item-input">
                                            <input class="scl_country" type="text" placeholder="ex: 台北市 中山區" readonly>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top scl_receiveaddress-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-globe" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">地址</div>
                                        <div class="item-input">
                                            <textarea class="resizable scl_receiveaddress" placeholder="ex: 八德路二段260號2樓"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top scl_delivery_time-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-clock-o" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label" style="width: 40%;">指定到貨時段</div>
                                        <div class="item-input">
                                            <input class="scl_delivery_time" type="text" placeholder="ex: 未指定" readonly>
                                        </div>
                                    </div>
                                </div>
                            </li> -->
                            <li class="align-top noUse">
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="same_address"><u>同會員通訊地址</u></div>
            </div>
            <div class="toolbar toolbar-bottom next animated fadeInUp">
                <div class="toolbar-inner">
                    <a>下一步</a>
                </div>
            </div>
        </div>
    </div>
</div>