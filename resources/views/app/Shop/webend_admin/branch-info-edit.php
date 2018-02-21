<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left blog-left">
                <a href="shop-data-config" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="branch-info-edit">
            <!-- 內容 -->
            <div class="page-content branch-info-edit-content animated fadeIn">
                <!-- 加载提示符 -->
                <!-- <div class="mPreloader">
                    <i class="fa fa-spinner fa-pulse" style="color:#777; font-size: 50pt; margin-left:41%; margin-top: 60%;"></i>
                </div> -->
            </div>
            <div class="toolbar toolbar-bottom tabbar animated fadeInUp">
                <div class="toolbar-inner">
                    <div class="row no-gutter">
                        <div class="col-50 edit"><a href="#" class="link">存檔</a></div>
                        <div class="col-50 preview"><a href="#" class="link">我的首頁</a></div>
                    </div>
                </div>
            </div>
            <script type="text/template7" id="templateBranchInfoEdit">
            <div class="branch-img-block" style="position: relative;">
                <img data-src="{{sd_shopphotopath}}" class="lazy branch-img" onerror='this.src="app/image/imgDefault.png"' />
                <div class="edit-img"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                <div class="noSetImg">*未設置圖片</div>
            </div>
            <div class="branch-info-block">
                <div class="subtitle">店家</div>
                <div class="list-block">
                    <ul>
                        <!-- Text inputs -->
                        <li class="align-top branch-name">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-building"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">店名</div>
                                    <div class="item-input">
                                        <input id="branch-name" type="text" placeholder="名稱" value="{{sd_shopname}}">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top branch-tel">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-phone"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">電話</div>
                                    <div class="item-input">
                                        <input id="branch-tel" type="text" placeholder="號碼" value="{{sd_shoptel}}" onkeyup="value=value.replace(/[^-0-9]/g,'')" onkeydown="value=value.replace(/[^-0-9]/g,'')">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="branch-date">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-calendar"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">營業日期</div>
                                    <div class="item-input">
                                        <input type="text" placeholder="星期一 ~ 星期日" readonly value="星期{{sd_weeklystart}} ~ 星期{{sd_weeklyend}}" class="branchDate">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="branch-time">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-clock-o"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">營業時間</div>
                                    <div class="item-input">
                                        <input type="text" placeholder="00:00 ~ 23:59" readonly value="{{js " this.sd_dailystart.slice(0,5) "}} ~ {{js "this.sd_dailyend.slice(0,5) "}}" class="branchTime">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="branch-region">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-map-marker"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">郵遞區號</div>
                                    <div class="item-input">
                                        <input type="text" placeholder="郵遞區號" readonly value="{{sd_zipcode}}" class="branchRegion">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- Textarea -->
                        <li class="align-top branch-address">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-map-marker"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">地址</div>
                                    <div class="item-input">
                                        <textarea id="branch-address" class="resizable" placeholder="請填寫完整地址">{{sd_shopaddress}}</textarea>
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
                                        <input id="sd_uniformnumbers" type="text" value="{{sd_uniformnumbers}}" placeholder="請輸入統一編號" maxlength="8" onkeydown="input_limit(this, 8);" onkeyup="input_limit(this, 8);">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top branch-info">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-file-text"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">服務內容</div>
                                    <div class="item-input">
                                        <textarea id="branch-info" class="resizable" placeholder="內容">{{sd_introtext}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="noUse" style="display:none;">
                        </li>
                    </ul>
                </div>
                <div class="list-block branch-details-block">
                    <ul>
                        <li class="align-top branch-details">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-file-text"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">更多資訊</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="list-block branch-details-block sortable">
                    <ul>
                        {{#each sd_advancedata}}
                        <li class="branch-details-item">
                            <div class="item-content">
                                <div class="item-media"><div class="move-up"><i class="fa fa-caret-up" aria-hidden="true"></i></div><div class="move-down"><i class="fa fa-caret-down" aria-hidden="true"></i></div></div>
                                <div class="item-inner">
                                    <div class="item-input">
                                        {{#if content_img}}
                                        <img data-src="{{img_path}}{{content_img}}" class="lazy details-img" onerror='this.src="app/image/imgDefault.png"'/>
                                        {{/if}}
                                        {{#if content_text}}
                                        <div class="details-text">{{content_text}}</div>
                                        {{/if}}
                                    </div>
                                </div>
                            </div>
                        </li>
                        {{/each}}
                    </ul>
                </div>
                <div class="row add-details-btns">
                    <div class="col-50">
                        <a href="#" class="button button-big button-fill add-details-image">添加圖片</a>
                    </div>
                    <div class="col-50">
                        <a href="#" class="button button-big button-fill add-details-text">添加文字</a>
                    </div>
                </div>
                <div class="subtitle">聯絡人</div>
                <div class="list-block">
                    <ul>
                        <!-- Text inputs -->
                        <li class="align-top user-name">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-user"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">姓名</div>
                                    <div class="item-input">
                                        <input type="text" placeholder="姓名" id="user-name" value="{{sd_contact_person}}">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top user-email">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-envelope"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">E-mail</div>
                                    <div class="item-input">
                                        <input id="user-email" type="email" placeholder="電子郵件" value="{{sd_contact_email}}">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- Select -->
                        <!-- Date -->
                        <li class="align-top user-tel">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-phone"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">市話</div>
                                    <div class="item-input">
                                        <!--<input id="birthday" type="date" placeholder="2001/01/01" value="2001-01-01">-->
                                        <div class="item-input">
                                            <input id="user-tel" type="text" placeholder="號碼" value="{{sd_contact_tel}}" onkeyup="value=value.replace(/[^-0-9]/g,'')" onkeydown="value=value.replace(/[^-0-9]/g,'')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top user-phone">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-mobile"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">手機</div>
                                    <div class="item-input">
                                        <input id="user-phone" type="text" placeholder="號碼" value="{{sd_contact_mobile}}" onkeyup="value=value.replace(/[^-0-9]/g,'')" onkeydown="value=value.replace(/[^-0-9]/g,'')">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- Textarea -->
                        <li class="align-top user-address">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-map-marker"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">地址</div>
                                    <div class="item-input">
                                        <textarea id="user-address" class="resizable" placeholder="地址">{{sd_contact_address}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="noUse">
                        </li>
                    </ul>
                </div>
            </div>
            </script>
            <script type="text/template7" id="templateShopDetails">
            <li class="branch-details-item">
                <div class="item-content">
                    <div class="item-media noUse"><i class="fa fa-file-text"></i></div>
                    <div class="item-inner">
                        <div class="item-input">
                            {{#if content_img}}
                            <img data-src="{{content_img}}" class="lazy details-img" onerror='this.src="app/image/imgDefault.png"'/>
                            {{/if}}
                            {{#if content_text}}
                            <div class="details-text">{{content_text}}</div>
                            {{/if}}
                        </div>
                    </div>
                </div>
            </li>
            </script>
        </div>
    </div>
</div>