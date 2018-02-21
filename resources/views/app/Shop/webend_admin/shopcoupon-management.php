<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="#" class="link icon-only open-popup" data-popup=".shop-menu">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div>
            <div class="center sliding">商品管理</div>
            <div class="right">
                <div class="iscar_member_icon" from="Shop"></div>
            </div>
            <!-- Sub navbar 
            <div class="subnavbar">
                <div class="row no-gutter">
                    <div class="col-50"><a href=".shopcoupon-now" class="active tab-link now">當前</a></div>
                    <div class="col-50" style="border:0;"><a href=".shopcoupon-fail" class="tab-link fail">失效</a></div>
                </div>
            </div>-->
        </div>        
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="shopcoupon-management">
            
            <!-- Sub navbar -->
            <div class="subnavbar">
                <div class="row no-gutter">
                    <div class="col-50"><a href=".shopcoupon-now" class="active tab-link now">當前</a></div>
                    <div class="col-50" style="border:0;"><a href=".shopcoupon-fail" class="tab-link fail">失效</a></div>
                </div>
            </div>

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
                        <div class="swipeout-content item-content">
                            <div class="item-media">
                                <img data-src="{{scm_mainpic}}" width=160 class="lazy" onerror='this.src="../app/image/imgDefault.png"' />
                            </div>
                            <div class="item-inner item-info">
                                <div class="row no-gutter">

                                    <div class="col-60" onclick="hrefTo('shop/shopcoupon-info?scm_id={{scm_id}}&type=management')">
                                        <p class="title">{{#if reservationtag}}<span>預約</span>{{/if}}{{scm_title}}</p>
                                        <p class="row">
                                            <span class="col-40"><span class="note">索取數 :</span>{{sendamount}}</span>
                                            <span class="col-60"><span class="note">狀態 :</span>{{scm_poststatus}}</span>
                                        </p>
                                    </div>


                                    <div class="col-40">
                                        <div class="row no-gutter">
                                            {{#if poststatus}}
                                            <div class="col-50 setStatus stop" onclick="changeStatus('{{scm_id}}',{{poststatus}})">停刊</div>
                                            <div class="col-50 edit check">修改</div> {{else}}
                                            <div class="col-50 setStatus" onclick="changeStatus('{{scm_id}}',{{poststatus}})">啟用</div>
                                            <a href="shop/shopcoupon-edit?scm_id={{scm_id}}" class="col-50 edit">修改</a> {{/if}}
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <!--<div class="swipeout-actions-right">
                            {{#if poststatus}}
                            <a href="#" class="setStatus stop" onclick="changeStatus('{{scm_id}}',{{poststatus}})">停刊</a>
                            <a href="#" class="edit check">修改</a> {{else}}
                            <a href="#" class="setStatus" onclick="changeStatus('{{scm_id}}',{{poststatus}})">啟用</a>
                            <a href="shop/shopcoupon-edit?scm_id={{scm_id}}" class="edit">修改</a> {{/if}}
                        </div>-->
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