<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="#" class="link icon-only open-popup" data-popup=".shop-menu">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div>
            <div class="center sliding">汽車特店</div>
            <div class="right">
                <div class="iscar_member_login iscar_member_icon" from="Shop"></div>
            </div>
            <!-- Sub navbar -->
            <div class="subnavbar">
                <!-- Sub navbar -->
                <div style="width:100%;">
                    <div class="swiper-container blog-cate"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="branch-cooperative">

            <div class="hot-key"></div>

            <!-- 內容 -->
            <div class="page-content branch-cooperative-content infinite-scroll pull-to-refresh-content animated fadeIn" data-distance="300">

                <!-- 下拉刷新符 -->
                <div class="pull-to-refresh-layer">
                    <div class="preloader preloader-white"></div>
                    <div class="pull-to-refresh-arrow"></div>
                </div>

                <!-- 列表 -->
                <div class="list-block mt-0 blog-box branch-pager">
                    <ul class="branch-list-container">

                        <div class="row branch-block">

                        </div>

                    </ul>
                </div>

                <!-- 加载提示符 -->
                <!-- <div class="infinite-scroll-preloader">
                    <i class="fa fa-spinner fa-pulse" style="color:#777; font-size: 50pt; margin-top: 5%;"></i>
                </div> -->

            </div>

            <!-- 版型選單 -->
            <div class="toolbar toolbar-bottom branchType animated fadeInUp">
                <div class="toolbar-inner">

                </div>
            </div>


            <!-- Cate -->
            <script type="text/template7" id="templateBranchCate">
                <div class="swiper-wrapper">
                    {{#each list}}
                    <a href="branch-cooperative?menu={{../menu}}&cate={{@index}}" class="swiper-slide {{#if active}}swiper-slide-active active{{/if}}"><span>{{name}}</span></a> {{/each}}
                </div>
            </script>

            <!-- ListType -->
            <script type="text/template7" id="templateBranchListType">
                <div class="row no-gutter">
                <div class="col-25">
                    <a href="branch-cooperative?menu={{menu}}&cate={{cate}}&listType=0" class="listType0"><i class="fa fa-stop"></i></a>
                    <a href="branch-cooperative?menu={{menu}}&cate={{cate}}&listType=2" class="listType2"><i class="fa fa-th-large"></i></a>
                </div>
                <div class="col-25">
                    <a onclick="loginStatus('shop_favorite')"><i class="fa fa-heart" aria-hidden="true"></i></a>
                </div>
                <div class="col-25">
                    <a class="around_search"><i class="fa fa-street-view" aria-hidden="true"></i></a>
                </div>
                <div class="col-25">
                    <a href="shop-search-result" class="advanced_search" style="border: 0;"><i class="fa fa-search" aria-hidden="true"></i></a>
                </div>
                <!-- <a href="branch-cooperative?menu={{menu}}&cate={{cate}}&listType=2" class="link"><i class="fa fa-th-large"></i></a> -->
                <!-- <a href="branch-cooperative?menu={{menu}}&cate={{cate}}&listType=1" class="link"><i class="fa fa-th-list"></i></a> -->

                <!-- <a href="map" class="link"><i class="fa fa-map-marker" aria-hidden="true"></i></a> -->
                <!-- <a class="link" onclick="loginStatus('shop_favorite')"><i class="fa fa-heart" aria-hidden="true"></i></a>
                <a href="branch-region-search" class="link"><i class="fa fa-search" aria-hidden="true"></i></a> -->
                </div>
                <!-- <div class="noUse" style="width:20%;"></div> -->
                <!-- <a href="#" class="button button-fill color-pink button-round" onclick="loginStatus('shop_favorite')">我的最愛</a> -->
            </script>

            <script type="text/template7" id="templateBranchList">
                {{#each list}}
                <li class="swipeout animated fadeIn">
                    <div class="swipeout-content">
                        <div class="item-content no-padding">
                            <div class="item-inner blog-list">
                                {{#if sd_havebind}}<div class="authenticate_icon"><img src="{{authenticate_icon}}" onerror='this.src="app/image/imgDefault.png"' /></div>{{/if}}
                                <div class="image">
                                    <a href="{{#if isTemple}}temple-info{{else}}branch-info{{/if}}?sd_id={{sd_id}}">
                                        <img data-src="{{sd_shopphotopath}}" class="lazy" onerror='this.src="app/image/imgDefault.png"' />
                                    </a>
                                </div>

                                <!-- <div class="col-10 favorite favorite-{{sd_id}}" onclick="addFavorite('{{sd_id}}', '{{sd_shopphotopath}}', '{{star_sd_shopname}}', '{{sd_shopaddress}}', '{{sd_shoptel}}', '','2')"><i class="fa fa-star-o"></i></div> -->
                                <div class="col-10 favorite favorite-{{sd_id}}" onclick="addFavorite('{{sd_id}}', '{{sd_shopphotopath}}', '{{star_sd_shopname}}', '{{sd_shopaddress}}', '{{sd_shoptel}}', '','2')"><img src="{{subscription_icon}}" onerror='this.src="app/image/imgDefault.png"' /></div>

                                <div class="text" onclick="hrefTo('{{#if isTemple}}temple-info{{else}}branch-info{{/if}}?sd_id={{sd_id}}')">
                                    <h4 class="title mt-5 mb-0">
                                        <a href="{{#if isTemple}}temple-info{{else}}branch-info{{/if}}?sd_id={{sd_id}}">{{sd_shopname}}</a>
                                    </h4>
                                    <div class="row no-gutter info">
                                        <div class="col-100">
                                            <p class="row no-gutter">
                                                <span class="col-100 createdate"><span class="note">地址 :</span>{{sd_shopaddress}}</span>
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                {{/each}}
            </script>

            <script type="text/template7" id="templateBranchListType1">
                {{#each list}}
                <li class="swipeout item-content list-item type1-list-item animated flipInX">
                    <div class="swipeout-content item-content">
                        <div class="item-media">
                            <div class="image" onclick="hrefTo('{{#if isTemple}}temple-info{{else}}branch-info{{/if}}?sd_id={{sd_id}}')">
                                <div class="image-block">
                                {{#if sd_havebind}}<div class="authenticate_icon"><img src="{{authenticate_icon}}" onerror='this.src="app/image/imgDefault.png"' /></div>{{/if}}
                                    <img data-src="{{sd_shopphotopath}}" class="lazy" width=130 onerror='this.src="app/image/imgDefault.png"' />
                                </div>
                            </div>
                        </div>

                        <div class="coupon-inner">
                            <div class="type1Title">{{sd_shopname}}</div>
                            <div class="row no-gutter">
                                <div class="col-75" onclick="hrefTo('{{#if isTemple}}temple-info{{else}}branch-info{{/if}}?sd_id={{sd_id}}')">
                                    <p class="row no-gutter">
                                        <span class="col-90"><span class="note">地址 :</span>{{sd_shopaddress}}</span>
                                    </p>
                                </div>
                                <div class="col-25 favorite favorite-{{sd_id}}" onclick="addFavorite('{{sd_id}}', '{{sd_shopphotopath}}', '{{star_sd_shopname}}', '{{sd_shopaddress}}', '{{sd_shoptel}}', '','2')"><img src="{{subscription_icon}}" onerror='this.src="app/image/imgDefault.png"' /></div>
                            </div>
                        </div>
                    </div>
                </li>
                {{/each}}
            </script>

            <script type="text/template7" id="templateBranchListType2">
                {{#each list}}
                <div class="branch-item animated zoomIn" style="background:rgba(100%,100%,100%,.7);">

                    <div class="image" onclick="hrefTo('{{#if isTemple}}temple-info{{else}}branch-info{{/if}}?sd_id={{sd_id}}')">
                    {{#if sd_havebind}}<div class="authenticate_icon"><img src="{{authenticate_icon}}" onerror='this.src="app/image/imgDefault.png"' /></div>{{/if}}
                        <a href="#">
                            <img src="{{sd_shopphotopath}}" onerror='this.src="app/image/imgDefault.png"' />
                        </a>
                    </div>
                    <div class="text">
                        <div class="title">
                            <span>{{sd_shopname}}</span>
                        </div>
                        <div class="row no-gutter">
                            <div class="col-100" onclick="hrefTo('{{#if isTemple}}temple-info{{else}}branch-info{{/if}}?sd_id={{sd_id}}')">
                                <p class="row no-gutter">
                                    <span class="col-90"><span class="note">地址 :</span>{{sd_shopaddress}}</span>
                                </p>
                            </div>
                            {{#if isTemple}} {{else}}
                            <div class="col-40 favorite favorite-{{sd_id}}" onclick="addFavorite('{{sd_id}}', '{{sd_shopphotopath}}', '{{star_sd_shopname}}', '{{sd_shopaddress}}', '{{sd_shoptel}}', '','2')"><img data-src="{{subscription_icon}}" class="lazy" onerror='this.src="app/image/imgDefault.png"' /></div>
                            {{/if}}
                        </div>
                    </div>
                </div>
                {{/each}}
            </script>

            <script type="text/template7" id="templateCooperativeListNull">
                <div class="content-null">
                    <h1><i class="fa fa-building" aria-hidden="true"></i></h1>
                    <br>
                    <h3>暫無店家資訊</h3>
                </div>
            </script>


        </div>
    </div>
</div>
