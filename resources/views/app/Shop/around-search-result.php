<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a class="link icon-only around-search-result-left">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">周邊商家</div>
            <div class="right">
                <!-- <div class="iscar_member_login iscar_member_icon" from="Shop"></div> -->
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
        <div class="page" data-page="around-search-result">
            <!-- 內容 -->
            <div class="page-content branch-cooperative-content infinite-scroll animated fadeIn">
                <!-- 列表 -->
                <div class="list-block mt-0 blog-box">
                    <ul class="branch-list-container">
                        <div class="row branch-block">
                        </div>
                    </ul>
                </div>
                <div id="full_map" class="full_map"></div>
                <div class="search-btn animated fadeInUp"><a>查詢更多</a></div>
            </div>
            <!-- 版型選單 -->
            <div class="toolbar toolbar-bottom mode animated fadeInUp">
                <div class="toolbar-inner">
                    <div class="row no-gutter">
                        <div class="col-100">
                            <a><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;&nbsp;地圖模式</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Cate -->
            <script type="text/template7" id="templateAroundSearchCate">
            <div class="swiper-wrapper">
                {{#each list}}
                <a class="swiper-slide {{#if active}}swiper-slide-active active{{/if}}" cate='{{@index}}'><span>{{name}}</span></a> {{/each}}
            </div>
            </script>
            <script type="text/template7" id="templateBranchList">
            {{#each list}}
            <li class="swipeout animated fadeIn">
                <div class="swipeout-content">
                    <div class="item-content no-padding">
                        <div class="item-inner blog-list">
                            {{#if sd_havebind}}<div class="authenticate_icon"><img src="{{authenticate_icon}}" onerror='this.src="app/image/imgDefault.png"' /></div>{{/if}}
                            <div class="image">
                                <a class="to_info" sd_id="{{sd_id}}">
                                    <img data-src="{{sd_shopphotopath}}" class="lazy" onerror='this.src="app/image/imgDefault.png"' />
                                </a>
                            </div>
                            <!-- <div class="col-10 favorite favorite-{{sd_id}}" onclick="addFavorite('{{sd_id}}', '{{sd_shopphotopath}}', '{{star_sd_shopname}}', '{{sd_shopaddress}}', '{{sd_shoptel}}', '','2')"><i class="fa fa-star-o"></i></div> -->
                            <div class="col-10 favorite favorite-{{sd_id}}" onclick="addFavorite('{{sd_id}}', '{{sd_shopphotopath}}', '{{star_sd_shopname}}', '{{sd_shopaddress}}', '{{sd_shoptel}}', '','2')"><img src="{{subscription_icon}}" onerror='this.src="app/image/imgDefault.png"' /></div>
                            <div class="text to_info" sd_id="{{sd_id}}">
                                <h4 class="title mt-5 mb-0">
                                <a class="to_info" sd_id="{{sd_id}}">{{sd_shopname}}</a>
                                </h4>
                                <div class="row no-gutter info">
                                    <div class="col-100">
                                        <p class="row no-gutter">
                                            <span class="col-100 createdate"><span class="note">地址 :</span>{{sd_shopaddress}}</span>
                                        </p>
                                    </div>
                                </div>
                                <!-- <div class="sd_distance">距 {{sd_distance}} km</div> -->
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