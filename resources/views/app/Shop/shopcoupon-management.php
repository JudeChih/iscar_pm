<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <!-- <div class="left">
                <a href="#" class="link icon-only open-popup" data-popup=".shop-menu">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div> -->
            <div class="left sliding">
                <a href="shopcoupon-main" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">商品管理</div>
            <div class="right">
                <!-- <div class="iscar_member_login iscar_member_icon" from="Shop"></div> -->
            </div>
            <!-- Sub navbar -->
            <div class="subnavbar">
                <div class="row no-gutter">
                    <div class="col-50"><a href=".shopcoupon-now" class="active tab-link now">當前</a></div>
                    <div class="col-50" style="border:0;"><a href=".shopcoupon-fail" class="tab-link fail">失效</a></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="shopcoupon-management">
            <div class="tabs">
                <!-- Tab 1, active by default -->
                <div id="shopcoupon-now" class="shopcoupon-now page-content tab infinite-scroll pull-to-refresh-content hide-bars-on-scroll active animated fadeIn" data-distance="300">
                    <!-- 下拉刷新符 -->
                    <div class="pull-to-refresh-layer">
                        <div class="preloader preloader-white"></div>
                        <div class="pull-to-refresh-arrow"></div>
                    </div>
                    <div class="list-block shopcoupon-list">
                    </div>
                </div>
                <!-- Tab 2 -->
                <div id="shopcoupon-fail" class="shopcoupon-fail page-content tab infinite-scroll pull-to-refresh-content hide-bars-on-scroll animated fadeIn" data-distance="300">
                    <!-- 下拉刷新符 -->
                    <div class="pull-to-refresh-layer">
                        <div class="preloader preloader-white"></div>
                        <div class="pull-to-refresh-arrow"></div>
                    </div>
                    <div class="list-block shopcoupon-list">
                    </div>
                </div>
            </div>
            <!-- Floating Action Button -->
            <a href="shop/add-shopcoupon" class="floating-button animated zoomIn">
                +
            </a>
            <script type="text/template7" id="templateShopCoupons">
            <ul>
                {{#each list}}
                <li class="item-content list-item swipeout animated flipInX">
                    <!-- <div class="swipeout-content item-content">
                        <div class="item-media">
                            <img data-src="{{scm_mainpic}}" width=120 class="lazy" onerror='this.src="app/image/imgDefault.png"' />
                        </div>
                        <a scm_id="{{scm_id}}" class="item-info to_info">
                            <p class="title">{{#if reservationtag}}<span>預約</span>{{else}}{{#if coupontag}}<span class="coupontag">服務</span>{{/if}}{{#if commoditytag}}<span class="commoditytag">商品</span>{{/if}}{{/if}}{{scm_title}}</p>
                            <p class="row">
                                <span class="col-40"><span class="note">索取數 :</span>{{sendamount}}</span>
                                <span class="col-60"><span class="note">狀態 :</span>{{scm_poststatus}}</span>
                            </p>
                        </a>
                    </div>
                    <div class="swipeout-actions-right">
                        {{#if poststatus}}
                        <a href="#" class="setStatus stop" onclick="changeStatus('{{scm_id}}',{{poststatus}})">停刊</a>
                        <a href="#" class="edit check">修改</a> {{else}}
                        <a href="#" class="setStatus" onclick="changeStatus('{{scm_id}}',{{poststatus}})">啟用</a>
                        <a href="shop/shopcoupon-edit?scm_id={{scm_id}}" class="edit">修改</a> {{/if}}
                    </div> -->
                    <div class="card">
                        <div class="card-content">
                        <div class="swipeout-content item-content">
                            <div class="item-media">
                                <img data-src="{{scm_mainpic}}" width=120 class="lazy" onerror='this.src="app/image/imgDefault.png"' />
                            </div>
                            <a scm_id="{{scm_id}}" class="item-info to_info">
                                <p class="title">{{#if reservationtag}}<span>預約</span>{{else}}{{#if coupontag}}<span class="coupontag">服務</span>{{/if}}{{#if commoditytag}}<span class="commoditytag">商品</span>{{/if}}{{/if}}{{scm_title}}</p>
                                <p class="row no-gutter">
                                    <span class="col-55"><span class="note">已取 :</span>{{sendamount}}</span>
                                    <span class="col-45"><span class="note">狀態 :</span>{{scm_poststatus}}</span>
                                </p>
                            </a>
                        </div>
                        </div>
                        <div class="card-footer">
                            {{#if poststatus}}
                            <a href="#" class="setStatus stop" onclick="changeStatus('{{scm_id}}',{{poststatus}})">停刊</a>
                            <a href="#" class="edit check">修改</a> {{else}}
                            <a href="#" class="setStatus" onclick="changeStatus('{{scm_id}}',{{poststatus}})">啟用</a>
                            <a href="shop/shopcoupon-edit?scm_id={{scm_id}}" class="edit">修改</a> {{/if}}
                        </div>
                    </div>
                </li>
                {{/each}}
            </ul>
            </script>
            <script type="text/template7" id="templateShopCouponListNull">
            <div class="content-null">
                <h1><i class="fa fa-file-text-o" aria-hidden="true"></i></h1>
                <br>
                <h3>{{text}}</h3>
            </div>
            </script>
        </div>
    </div>
</div>