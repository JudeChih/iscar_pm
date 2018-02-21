<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <!--<div class="left sliding branch-info-left">
                <a href="branch-cooperative.html" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding branch-info-title"></div>-->
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="temple-info">
            <div class="back-btn">
                <a href="#">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="tabs">
                <!-- 簡介 -->
                <div class="page-content hide-toolbar-on-scroll temple-info-content tab active animated fadeIn">
                    <!-- 加载提示符 -->
                    <div class="mPreloader">
                        <i class="fa fa-spinner fa-pulse" style="color:#777; font-size: 50pt; margin-left:41%; margin-top: 60%;"></i>
                    </div>
                </div>
                <!-- 線上捐獻 -->
                <div class="page-content temple-donate tab animated fadeIn">
                    <img data-src="app/image/donate.jpg" class="lazy temple-img" onerror='this.src="app/image/imgDefault.png"' />
                    <div class="temple-subTitle">申請人</div>
                    <div class="list-block">
                        <ul>
                            <!-- Text inputs -->
                            <li class="align-top tps-invoice-name-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-user" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">姓名</div>
                                        <div class="item-input">
                                            <input id="tps-invoice-name" class="tps-invoice-name" type="text" placeholder="請輸入真實姓名" readonly>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top tps-invoice-tel-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-phone"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">電話</div>
                                        <div class="item-input">
                                            <input id="tps-invoice-tel" class="tps-invoice-tel" type="number" placeholder="請輸入聯絡號碼" onkeyup="value=value.replace(/[^-0-9]/g,'')" onkeydown="value=value.replace(/[^-0-9]/g,'')" readonly>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top tps-invoice-area-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-globe" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">地區</div>
                                        <div class="item-input">
                                            <input name="tps-invoice-area" class="tps-invoice-area" type="text" placeholder="請選擇地區" readonly>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top tps-invoice-address-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-globe" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">地址</div>
                                        <div class="item-input">
                                            <textarea id="tps-invoice-address" class="resizable tps-invoice-address" placeholder="請填寫地址" readonly></textarea>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top tps-amount-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-usd" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">金額</div>
                                        <div class="item-input">
                                            <input id="tps-amount" class="tps-amount" type="number" placeholder="請輸入捐獻金額" onkeydown="input_limit(this, 6);" onkeyup="input_limit(this, 6);" readonly>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top iscarpolicy">
                                <div class="item-content">
                                    <div class="item-inner">
                                        <div class="item-input">
                                            <input type="checkbox">&nbsp;&nbsp;我已明瞭「 <a class="query_iscarpolicy">服務條款</a>」所載內容及其意義，並同意該條款規定
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <br>
                    <a href="#" class="button button-big button-fill donate-btn">隨意樂捐</a>
                    <br>
                </div>
                <!-- 祈福點燈 -->
                <div class="page-content temple-bright-light tab animated fadeIn">
                    <img data-src="app/image/bright-light.jpg" class="lazy temple-img" onerror='this.src="app/image/imgDefault.png"' />
                    <div class="temple-subTitle">申請人</div>
                    <div class="list-block">
                        <ul>
                            <!-- Text inputs -->
                            <li class="align-top tps_invoice_name-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-user" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">姓名</div>
                                        <div class="item-input">
                                            <input id="tps_invoice_name" class="tps_invoice_name" type="text" placeholder="請輸入真實姓名" readonly>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top tps_invoice_tel-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-phone"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">電話</div>
                                        <div class="item-input">
                                            <input id="tps_invoice_tel" class="tps_invoice_tel" type="number" placeholder="請輸入聯絡號碼" onkeyup="value=value.replace(/[^-0-9]/g,'')" onkeydown="value=value.replace(/[^-0-9]/g,'')" readonly>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top tps_invoice_area-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-globe" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">地區</div>
                                        <div class="item-input">
                                            <input name="tps_invoice_area" class="tps_invoice_area" type="text" placeholder="請選擇地區" readonly>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top tps_invoice_address-block">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-globe" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">地址</div>
                                        <div class="item-input">
                                            <textarea id="tps_invoice_address" class="resizable tps_invoice_address" placeholder="請填寫地址" readonly></textarea>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top iscarpolicy">
                                <div class="item-content">
                                    <div class="item-inner">
                                        <div class="item-input">
                                            <input type="checkbox">&nbsp;&nbsp;我已明瞭「 <a class="query_iscarpolicy">服務條款</a>」所載內容及其意義，並同意該條款規定
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="blessed-block">
                    </div>
                    <div class="add-blessed">添加被祈福者</div>
                    <br>
                    <a href="#" class="button button-big button-fill bright-btn">申請點燈</a>
                    <br>
                </div>
            </div>
            <div class="toolbar toolbar-bottom tabbar animated fadeInUp">
                <div class="toolbar-inner">
                    <div class="row no-gutter">
                        <div class="col-33"><a href=".temple-info-content" class="info active tab-link">簡介</a></div>
                        <div class="col-33"><a href=".temple-donate" class="donate tab-link">線上捐獻</a></div>
                        <div class="col-33"><a href=".temple-bright-light" class="bright-light tab-link" style="border:0;">點燈祈福</a></div>
                    </div>
                </div>
            </div>
            <script type="text/template7" id="templateTempleInfo">
            <img data-src="{{sd_shopphotopath}}" class="lazy temple-img main-img" onerror='this.src="app/image/imgDefault.png"'/>
            <div class="temple-info-block">
                <div class="row no-gutter">
                    <div class="col-90">
                        <div class="temple_name">{{sd_shopname}}</div>
                    </div>
                    <!--<div class="col-10 track favorite-{{sd_id}}"><i class="fa fa-star-o"></i></div>-->
                </div>
                <div class="temple-content">
                    <span class="temple_tel_title">電話：</span>
                    <span class="temple_tel" style="color:burlywood;">{{sd_shoptel}}</span>
                    <br>
                    <span class="temple_date_title">開放日期：</span>
                    <span class="temple_date" style="color:burlywood;">{{#if sd_weeklystart}}星期{{sd_weeklystart}}~星期{{sd_weeklyend}}{{/if}}</span>
                    <br>
                    <span class="temple_date_title">開放時間：</span>
                    <span class="temple_date" style="color:burlywood;">{{#if sd_dailystart}}{{js "this.sd_dailystart.slice(0,5)"}}~{{js "this.sd_dailyend.slice(0,5)"}}{{/if}}</span>
                    <br>
                    <span class="temple_address_title">地址：</span>
                    <span class="temple_address" style="color:burlywood;">{{sd_shopaddress}}</span>
                </div>
                <div class="temple-subTitle">簡介</div>
                <div class="temple-content">{{sd_introtext}}</div>
                {{#if sd_advancedata}}
                <div class="temple-details" id="sd_advancedata">
                    {{#each sd_advancedata}}
                    {{#if content_img}}
                    <img data-src="{{content_img}}" class="lazy" onerror='this.src="app/image/imgDefault.png"'/>
                    {{/if}}
                    {{#if content_text}}
                    <div class="context">{{content_text}}</div>
                    {{/if}}
                    {{/each}}
                </div>
                {{/if}}
            </div>
            </script>
            <script type="text/template7" id="templateBlessed">
            <div class="temple-subTitle">被祈福者</div>
            <div class="list-block">
                <ul>
                    {{#each blessedlist}}
                    <li class="light-types light-{{tpr_serno}}">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-fire" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label" style="width: 35%;">燈別(可複選)</div>
                                <div class="item-input">
                                    <!-- {{#each tplproducts}}
                                    <label class="label-checkbox item-content">
                                        <input type="checkbox" name="light" value="{{tpp_id}}" tpp_price="{{tpp_price}}" tpp_name="{{tpp_name}}">
                                        <div class="item-media">
                                            <i class="icon icon-form-checkbox"></i>
                                        </div>
                                        <div class="item-inner">
                                            <div class="item-title">{{tpp_name}}</div>
                                        </div>
                                    </label>
                                    {{/each}} -->
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="align-top">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-user" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">姓名</div>
                                <div class="item-input">
                                    <input id="branch-name" type="text" placeholder="請輸入申請人姓名"  value="{{tpr_name}}" readonly>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="align-top">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-user-o" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">關係</div>
                                <div class="item-input">
                                    <input id="tpr_title" type="text" placeholder="關係"  value="{{tpr_title}}" readonly>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="align-top branch-tel">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">西元生日</div>
                                <div class="item-input">
                                    <input id="tpr_title" type="text" placeholder="西元生日"  value="{{tpr_birthday}}" readonly>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="align-top branch-tel">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-clock-o" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">出生時辰</div>
                                <div class="item-input">
                                    <input id="tpr_title" type="text" placeholder="出生時辰"  value="{{tpr_birthdaytime}}" readonly>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- Textarea -->
                    <li class="align-top">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-map-marker"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">地址</div>
                                <div class="item-input">
                                    <textarea id="tpr_address" class="resizable" placeholder="請填寫完整地址" readonly>{{tpr_address}}</textarea>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="noUse" style="display:none;">
                    </li>
                    {{/each}}
                </ul>
            </div>
            </script>

            <script type="text/template7" id="templateTplProducts">
            {{#each tplproducts}}
            <label class="label-checkbox item-content">
                <!-- Checked by default -->
                <input type="checkbox" name="light" value="{{tpp_serno}}" tpp_price="{{tpp_price}}" tpp_name="{{tpp_name}}">
                <div class="item-media">
                    <i class="icon icon-form-checkbox"></i>
                </div>
                <div class="item-inner">
                    <div class="item-title">{{tpp_name}}</div>
                </div>
            </label>
            {{/each}}
            </script>
        </div>
    </div>
</div>