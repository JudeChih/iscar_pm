<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding shopcoupon-edit-left">
                <a href="shop/shopcoupon-management" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">活動商品修改</div>
            <div class="right">
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="shopcoupon-edit">
            <!-- 內容 -->
            <div class="page-content shopcoupon-edit-content animated fadeIn">
                <div class="image-block">
                    <div class="container">
                    </div>
                </div>
                <div class="noSetMainImg">* 未設定</div>
                <div class="shopcoupon-edit-block">
                <!-- 加载提示符 -->
                <!-- <div class="mPreloader">
                    <i class="fa fa-spinner fa-pulse" style="color:#777; font-size: 50pt; margin-left:41%; margin-top: 20%;"></i>
                </div> -->
                </div>
            </div>
            <div class="toolbar toolbar-bottom edit animated fadeInUp">
                <div class="toolbar-inner">
                    <a>修改</a>
                </div>
            </div>
            <script type="text/template7" id="templateShopCouponEdit">
            <div class="list-block">
                <ul>
                    <li class="shopcoupon-name">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-building"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">商品名稱</div>
                                <div class="item-input">
                                    <input id="shopcoupon-name" type="text" placeholder="名稱" readonly value="{{scm_title}}">
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- <li class="scm_producttype-block">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-tag" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">商品型式</div>
                                <div class="item-input">
                                    <input class="scm_producttype" readonly type="text" placeholder="請選擇型式" value="{{scm_producttype}}">
                                </div>
                            </div>
                        </div>
                    </li> -->
                    <li class="scm_coupon_providetype-block">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-hashtag" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">提供類型</div>
                                <div class="item-input">
                                    <input class="scm_coupon_providetype" readonly type="text" placeholder="請選擇類型" value="{{scm_coupon_providetype}}">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="scm_bonus_payamount">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-stop-circle" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">特點扣除</div>
                                <div class="item-input">
                                    <input id="scm_bonus_payamount" type="number" placeholder="數額（1~9999）" readonly value="{{scm_bonus_payamount}}">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="scm_bonus_giveafteruse">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-toggle-off" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">贈送特點</div>
                                <div class="item-input">
                                    <label class="label-switch">
                                        <input class="giveafteruse-checkbox" type="checkbox">
                                        <div class="checkbox"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="scm_bonus_giveamount">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-stop-circle" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">特點贈與</div>
                                <div class="item-input">
                                    <input id="scm_bonus_giveamount" type="number" placeholder="數額（1~9999）" readonly value="{{scm_bonus_giveamount}}">
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- <li class="scm_category">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-tags" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">活動類別</div>
                                <div class="item-input">
                                    <input id="shopcoupon-type" readonly type="text" placeholder="請選擇類別">
                                </div>
                            </div>
                        </div>
                    </li> -->
                    <li class="price">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-money" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">單價</div>
                                <div class="item-input">
                                    <input id="price" type="number" placeholder="金額" readonly value="{{scm_price}}">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="align-top scm_fulldescript">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-file-text"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">商品說明</div>
                                <div class="item-input">
                                    <textarea id="scm_fulldescript" class="resizable" placeholder="內容" rows="3" readonly>{{scm_fulldescript}}</textarea>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="limit">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-sticky-note" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">販售數量</div>
                                <div class="item-input">
                                    <input id="limit" type="number" placeholder="數量" readonly value="{{scm_member_limit}}">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="scm_balanceno">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-barcode" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">銷帳單號</div>
                                <div class="item-input">
                                    <input id="scm_balanceno" type="text" placeholder="單號" readonly value="{{scm_balanceno}}">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="branch-region">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-toggle-on" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">預約設置</div>
                                <div class="item-input">
                                    <label class="label-switch">
                                        <input class="reservation-checkbox" type="checkbox">
                                        <div class="checkbox"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="scm_date">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-calendar"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">銷售日期</div>
                                <div class="item-input">
                                    <input type="text" placeholder="日期區間" readonly id="calendar-range" value="{{scm_date}}">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="shopcoupon-time">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-clock-o"></i></div>
                            <div class="item-inner">
                                <div class="item-title label" style="width: 35%;">活動每日時間</div>
                                <div class="item-input">
                                    <input type="text" placeholder="08:00 ~ 23:00" readonly id="shopcoupon-time" value="{{scm_daily}}">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="weekend-radio">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-calendar-check-o" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label" style="width: 35%;">活動包含周六日</div>
                                <div class="item-input">
                                    <label class="label-radio item-content">
                                        <input type="radio" name="weekend-radio" value="1" class="includ" checked="checked">
                                        <div class="item-inner">
                                            <div class="item-title">含周六日</div>
                                        </div>
                                    </label>
                                    <label class="label-radio item-content">
                                        <input type="radio" name="weekend-radio" class="not_included" value="0">
                                        <div class="item-inner">
                                            <div class="item-title">不含周六日</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="require-time">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-clock-o"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">作業工時</div>
                                <div class="item-input">
                                    <input type="text" placeholder="01 小時 30 分鐘" readonly id="require-time" value="{{scm_workhour}}">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="prepare-time">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-clock-o"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">準備工時</div>
                                <div class="item-input">
                                    <input type="text" placeholder="01 小時 30 分鐘" readonly id="prepare-time" value="{{scm_preparehour}}">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="reserve-num">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-sticky-note" aria-hidden="true"></i></div>
                            <div class="item-inner">
                                <div class="item-title label" style="width: 50%;">同時段允許預約數</div>
                                <div class="item-input">
                                    <input id="reserve-num" type="number" placeholder="數目" readonly value="{{scm_reservationavailable}}">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="noUse" style="display:none;">
                    </li>
                </ul>
            </div>
            <div class="list-block commodity-details-block">
                <ul>
                    <li class="align-top commodity-details">
                        <div class="item-content">
                            <div class="item-media"><i class="fa fa-file-text"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">更多資訊</div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="list-block commodity-details-block sortable">
                <ul>
                    {{#each scm_advancedescribe}}
                    <li class="commodity-details-item">
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
            </script>
        </div>
    </div>
</div>