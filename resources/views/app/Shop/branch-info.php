<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <!--<div class="left sliding branch-info-left">
                <a href="branch-cooperative" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding branch-info-title"></div>-->
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="branch-info">
            <div class="back-btn">
                <a href="#">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="tabs">
                <!-- Tab 1, active by default -->
                <div class="page-content hide-toolbar-on-scroll branch-info-content tab active animated fadeIn">
                    <!-- 加载提示符 -->
                    <!-- <div class="mPreloader">
                        <i class="fa fa-spinner fa-pulse" style="color:#777; font-size: 50pt; margin-left:41%; margin-top: 60%;"></i>
                    </div> -->
                </div>
                <!-- Tab 2 -->
                <div class="page-content branch-evaluate-browse-content tab animated fadeIn">
                    <div class="canvas-block">
                        <canvas id="canvas" width="300" height="300" style="width:60%; margin-left:20%;"></canvas>
                        <div class="row evaluate-info">
                            <div class="col-25 note">綜合評價：</div>
                            <div class="col-25"><span class="average"></span>&nbsp;<i class="fa fa-star" style="color: goldenrod;"></i></div>
                            <div class="col-25 note">投票人數：</div>
                            <div class="col-25"><span class="count"></span>&nbsp;<span>人</span></div>
                        </div>
                    </div>
                    <div class="activemessage-list">
                        <div class="list-null"></div>
                    </div>
                </div>
                <!-- Tab 3 -->
                <div class="page-content branch-queue-content tab animated fadeIn">
                    <div class="queue-num-block">
                        <h1>目前排隊號碼</h1>
                        <div class="now-queue-num">0</div>
                        <div class="queue-num-status"><span>今日總數：</span><span class="num queue-sum">0</span><span style="padding-left: 3%;">您的號碼：</span><span class="num user-num">0</span></div>
                    </div>
                    <div class="branch-subTitle">服務項目</div>
                    <div class="serving-list">
                        <div class="list-block">
                            <ul>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Tab 4 -->
                <div class="page-content branch-preferential-content tab infinite-scroll pull-to-refresh-content hide-bars-on-scroll animated fadeIn" data-distance="300">
                    <!-- 下拉刷新符 -->
                    <div class="pull-to-refresh-layer">
                        <div class="preloader preloader-white"></div>
                        <div class="pull-to-refresh-arrow"></div>
                    </div>
                    <!-- 列表 -->
                    <div class="list-block mt-0 blog-box shopcoupon-list-block">
                        <ul class="shopcoupon-list-container">
                            <div class="row shopcoupon-block">
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="toolbar toolbar-bottom tabbar animated fadeInUp">
                <div class="toolbar-inner">
                    <div class="row no-gutter">
                        <div class="col-33"><a href=".branch-info-content" class="info active tab-link">主頁</a></div>
                        <div class="col-33"><a href=".branch-evaluate-browse-content" class="evaluate-browse tab-link">評論</a></div>
                        <!-- <div class="col-25"><a href=".branch-queue-content" class="queue tab-link">排隊</a></div> -->
                        <div class="col-33"><a href=".branch-preferential-content" class="preferential tab-link" style="border:0;">優惠</a></div>
                    </div>
                </div>
            </div>








            
            <script type="text/template7" id="templateBranchInfo">
            <div class="image-blcok">
            <img data-src="{{sd_shopphotopath}}" class="lazy branch-img main-img" onerror='this.src="app/image/imgDefault.png"'/>
            {{#if sd_havebind}}<div class="authenticate_icon"><img src="{{authenticate_icon}}" onerror='this.src="app/image/imgDefault.png"' /></div>{{/if}}
            </div>
            <div class="branch-info-block">
                <div class="row no-gutter">
                    <div class="col-75">
                        <div class="branch_name">{{sd_shopname}}</div>
                    </div>
                    <div class="col-25 track favorite-{{sd_id}}"><img src="{{subscription_icon}}" onerror='this.src="app/image/imgDefault.png"' /></div>
                </div>
                <div class="branch-content">
                    <span class="branch_tel_title">電話：</span>
                    <span class="branch_tel" style="color:burlywood;">{{sd_shoptel}}</span>
                    <br>
                    <span class="branch_date_title">營業日期：</span>
                    <span class="branch_date" style="color:burlywood;">{{#if sd_weeklystart}}星期{{sd_weeklystart}}~星期{{sd_weeklyend}}{{/if}}</span>
                    <br>
                    <span class="branch_time_title">營業時間：</span>
                    <span class="branch_time" style="color:burlywood;">{{#if sd_dailystart}}{{js "this.sd_dailystart.slice(0,5)"}}~{{js "this.sd_dailyend.slice(0,5)"}}{{/if}}</span>
                    <br>
                    <span class="branch_address_title">地址：</span>
                    <span class="branch_address" style="color:burlywood;">{{sd_shopaddress}}</span>
                </div>
                {{#if sd_introtext}}
                <div class="branch-subTitle">服務內容</div>
                <div class="branch-content" id="cdm_description">{{sd_introtext}}</div>
                {{/if}}
                {{#if sd_advancedata}}
                <div class="branch-subTitle">更多資訊</div>
                <div class="branch-details" id="sd_advancedata">
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
            <script type="text/template7" id="templateBranchEvaluateBrowse">
            <div class="branch-subTitle">評論內容</div>
            <div class="list-block comments-box">
                <ul>
                    {{#each activemessage}}
                    <li>
                        <div>
                            <div class="item-content">
                                <div class="item-inner comments-list">
                                    <div class="image">
                                        <span class="ava">
                                            <img data-src="{{ssd_picturepath}}" class="lazy" onerror='this.src="app/image/general_user.png"'>
                                        </span>
                                    </div>
                                    <div class="text">
                                        <div class="info">
                                            <span class="nick">{{md_cname}}</span>
                                            <span class="data">{{js "this.sqna_last_update.slice(0,10)"}}</span>
                                        </div>
                                        <div class="comment">{{sqna_message}}</div>
                                        {{#if sqnr_responsemessage}}
                                        <div class="branch-reply-block">
                                            <div class="branch-reply-title">
                                                <div><span class="branch-name">商家</span>的回覆({{js "this.sqnr_last_update.slice(0,10)"}})</div>
                                            </div>
                                            <div class="branch-reply-context">{{sqnr_responsemessage}}</div>
                                        </div>
                                        {{/if}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    {{/each}}
                </ul>
            </div>
            </script>
            <script type="text/template7" id="templateBranchQueue">
            {{#each servicelist}}
            {{#if effectivity}}
            <li class="item-content list-item swipeout animated flipInX item-{{ssqd_id}}">
                <div class="swipeout-content item-content" onclick="setQueueStatus('{{ssqd_id}}','{{today_serviced}}');">
                    <div class="item-media">
                        <img data-src={{ssqd_mainpic}} class="lazy" width=120 onerror='this.src="app/image/imgDefault.png"' />
                    </div>
                    <a href="#" class="item-info">
                        <span class="title">{{ssqd_title}}</span>
                        <span class="row no-gutter" style="margin-top: 3%;">
                            <span class="col-25">費用：</span>
                            <span class="col-25 price">{{ssqd_serviceprice}}</span>
                            <span class="col-50"></span>
                        </span>
                    </a>
                </div>
                <div class="swipeout-actions-right">
                    <a href="shop/shopservice-info?from=client&ssqd_id={{ssqd_id}}" class="service-info">詳細</a>
                    <a href="#" class="queue" onclick="queue('{{ssqd_id}}')">排隊</a>
                </div>
            </li>
            {{/if}}
            {{/each}}
            </script>
            <script type="text/template7" id="templateShopCouponList">
            {{#each shopcoupon_list}} {{#if poststatus}} {{#if date_status}}
            <li class="swipeout animated fadeIn">
                <div class="swipeout-content">
                    <div class="item-content no-padding">
                        <div class="item-inner blog-list">
                            <div class="image">
                                {{#if reservationtag}}
                                <div class="reservationtag-tag">
                                    <div><span>預約限定</span></div>
                                </div>
                                {{else}}
                                {{#if coupontag}}
                                <div class="coupontag">
                                <div><span>活動服務</span></div>
                                </div>
                                {{/if}}
                                {{#if commoditytag}}
                                <div class="commoditytag">
                                <div><span>實體商品</span></div>
                                </div>
                                {{/if}}
                                {{/if}}
                                <a href="shop/shopcoupon-info?scm_id={{scm_id}}">
                                    <img data-src="{{scm_mainpic}}" class="lazy" onerror='this.src="app/image/imgDefault.png"' />
                                </a>
                            </div>
                            <div class="col-10 favorite favorite-{{scm_id}}" onclick="addFavorite('{{scm_id}}', '{{scm_mainpic}}', '{{scm_title}}', '{{scm_category}}', '{{scm_reservationtag}}', '{{scm_startdate}} ~ {{scm_enddate}}','3')"><i class="fa fa-star-o"></i></div>
                            <div class="text" onclick="hrefTo('shop/shopcoupon-info?scm_id={{scm_id}}')">
                                <h4 class="title mt-5 mb-0">
                                <a href="shop/shopcoupon-info?sd_id={{scm_id}}">{{scm_title}}</a>
                                </h4>
                                <div class="row no-gutter info">
                                    <div class="col-100">
                                        <p class="row no-gutter">
                                            <span class="col-100 scm-date"><span class="note">活動日期 :</span>{{scm_startdate}} ~ {{scm_enddate}}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            {{/if}} {{/if}} {{/each}}
            </script>
            <script type="text/template7" id="templateShopCouponNull">
            <div class="content-null">
                <h1><i class="fa fa-shopping-cart" aria-hidden="true"></i></h1>
                <br>
                <h3>暫無優惠活動</h3>
            </div>
            </script>
            <script type="text/template7" id="templateBranchQueueNull">
            <div class="content-null">
                <h3>暫無服務</h3>
            </div>
            </script>
        </div>
    </div>
</div>