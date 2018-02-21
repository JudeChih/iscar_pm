<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding">
                <a href="#" class="link icon-only open-popup" data-popup=".shop-menu">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div>
            <div class="center sliding about-title">我要開店</div>
            <div class="right">
                <div class="iscar_member_login iscar_member_icon" from="Shop"></div>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="application-shop">

            <div class="page-content animated fadeIn">
                <div class="subtitle">店家資訊</div>
                <div class="list-block">
                    <ul>
                        <!-- Text inputs -->
                        <li class="align-top shop_name sd_shopname">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-building"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">店名</div>
                                    <div class="item-input">
                                        <input id="shop_name" type="text" placeholder="請輸入名稱">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="operation_type">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-tags" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">類別</div>
                                    <div class="item-input">
                                        <input id="shop_type" type="text" placeholder="請選擇類別" readonly class="shop_type">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top sd_contact_person">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-user-circle-o" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">聯絡人</div>
                                    <div class="item-input">
                                        <input id="sd_contact_person" type="text" placeholder="請輸入聯絡人姓名">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top shop_tel sd_shoptel">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-phone"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">電話</div>
                                    <div class="item-input">
                                        <input id="shop_tel" type="text" placeholder="請輸入號碼" readonly onkeyup="value=value.replace(/[^-0-9]/g,'')">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="sd_zipcode">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-map-signs" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">郵遞區號</div>
                                    <div class="item-input">
                                        <input type="text" placeholder="郵遞區號" readonly class="shop_region">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- Textarea -->
                        <li class="align-top shop_address sd_shopaddress">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-map-signs" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">地址</div>
                                    <div class="item-input">
                                        <textarea id="shop_address" class="resizable" placeholder="請填寫完整地址"></textarea>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top sd_uniformnumbers">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-barcode" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">統一編號</div>
                                    <div class="item-input">
                                        <input id="sd_uniformnumbers" type="text" placeholder="請輸入統一編號">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="noUse" style="display:none;">

                        </li>
                    </ul>
                </div>

            </div>
            <div class="toolbar toolbar-bottom tabbar send animated fadeInUp">
                <div class="toolbar-inner">
                    <a href="#" class="link">送出</a>
                </div>
            </div>
        </div>
    </div>