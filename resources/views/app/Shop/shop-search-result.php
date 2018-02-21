<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a class="link icon-only shop-search-result-left">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">查詢結果</div>
            <div class="right">
                <!-- <div class="iscar_member_login iscar_member_icon" from="Shop"></div> -->
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="shop-search-result">
            <!-- 浮動按鈕-至頂 -->
            <a href="#" class="floating-button to-top">
                <!--<i class="icon icon-chevron-up"></i>-->
                <i class="fa fa-angle-up" aria-hidden="true"></i>
            </a>
            <!-- 內容 -->
            <div class="page-content branch-cooperative-content infinite-scroll animated fadeIn">
                <div class="subtitle">查詢條件</div>
                <div class="row no-gutter condition-row">
                    <div class="col-85">
                        <div class="content-block condition-block">
                        </div>
                    </div>
                    <div class="col-15">
                        <a class="button more-condition"><i class="fa fa-search" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="subtitle result-title"><div>查詢結果</div><!-- <div class="mode"><i class="fa fa-map-marker" aria-hidden="true"></i></div> --></div>
                <!-- 列表 -->
                <div class="list-block mt-0 blog-box">
                    <ul class="branch-list-container">
                        <div class="row branch-block">
                        </div>
                    </ul>
                </div>
                <div id="full_map" class="full_map"></div>
            </div>
            <script type="text/template7" id="templateConditionBlock">
            {{#each condition_list}}
            {{#if condition}}
            <div class="chip">
                <div class="chip-label">{{condition}}</div>
            </div>
            {{/if}}
            {{/each}}
            </script>
            <script type="text/template7" id="templateBranchSearchList">
            {{#each list}}
            <li class="swipeout animated fadeIn">
                <div class="swipeout-content">
                    <div class="item-content no-padding">
                        <div class="item-inner blog-list">
                            {{#if sd_havebind}}<div class="authenticate_icon"><img src="{{authenticate_icon}}" onerror='this.src="app/image/imgDefault.png"' /></div>{{/if}}
                            <div class="image">
                                {{#if sd_type}}
                                <div class="shop-type-tag shop-type-{{spm_serno}}">
                                    <div><span>{{sd_type}}</span></div>
                                </div>
                                {{/if}}
                                 <!--  <a href="branch-info?sd_id={{sd_id}}&from=search"> -->
                                <a class="to_info" sd_id="{{sd_id}}">
                                    <img data-src="{{sd_shopphotopath}}" class="lazy" onerror='this.src="app/image/imgDefault.png"' />
                                </a>
                            </div>
                            <!-- <div class="col-10 favorite favorite-{{sd_id}}" onclick="addFavorite('{{sd_id}}', '{{sd_shopphotopath}}', '{{star_sd_shopname}}', '{{sd_shopaddress}}', '{{sd_shoptel}}', '','2')"><i class="fa fa-star-o"></i></div> -->
                            <div class="col-10 favorite favorite-{{sd_id}}" onclick="addFavorite('{{sd_id}}', '{{sd_shopphotopath}}', '{{star_sd_shopname}}', '{{sd_shopaddress}}', '{{sd_shoptel}}', '','2')"><img src="{{subscription_icon}}" onerror='this.src="app/image/imgDefault.png"' /></div>
                            <div class="text to_info"sd_id="{{sd_id}}">
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
                        <div class="image" onclick="hrefTo('{{#if isTemple}}temple-info{{else}}branch-info{{/if}}?sd_id={{sd_id}}&from=search')">
                            <div class="image-block">
                                {{#if sd_havebind}}<div class="authenticate_icon"><img src="{{authenticate_icon}}" onerror='this.src="app/image/imgDefault.png"' /></div>{{/if}}
                                <img data-src="{{sd_shopphotopath}}" class="lazy" width=130 onerror='this.src="app/image/imgDefault.png"' />
                            </div>
                        </div>
                    </div>
                    <div class="coupon-inner">
                        <div class="type1Title">{{sd_shopname}}</div>
                        <div class="row no-gutter">
                            <div class="col-75" onclick="hrefTo('{{#if isTemple}}temple-info{{else}}branch-info{{/if}}?sd_id={{sd_id}}&from=search')">
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
                <div class="image" onclick="hrefTo('{{#if isTemple}}temple-info{{else}}branch-info{{/if}}?sd_id={{sd_id}}&from=search')">
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
                        <div class="col-100" onclick="hrefTo('{{#if isTemple}}temple-info{{else}}branch-info{{/if}}?sd_id={{sd_id}}&from=search')">
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
            <script type="text/template7" id="templateShopSearchNull">
            <div class="content-null">
                <h1><i class="fa fa-building" aria-hidden="true"></i></h1>
                <br>
                <h3>查無店家資訊</h3>
            </div>
            </script>
            <script type="text/template7" id="templateBranchRegionAccordion">
            {{#each rlList}}
            <li class="accordion-item region-item accordion-{{rl_city_ename}}">
                <a href="#" class="item-content item-link region-item-content">
                    <div class="item-inner">
                        <div class="item-title">{{rl_city}}</div>
                    </div>
                </a>
                <div class="accordion-item-content">
                    <div class="content-block">
                        <div class="block-list row {{rl_city_ename}}" style="padding-left: 5%;">
                        </div>
                    </div>
                </div>
            </li>
            {{/each}}
            </script>
            <script type="text/template7" id="templateBranchRegionList">
            <div class="checkbox-item col-25">
                <input type="checkbox" class="r{{rl_no}}" id="r{{rl_no}}" name="region" value="{{rl_no}}" />
                <label for="r{{rl_no}}"><span></span>{{rl_city}}</label>
            </div>
            </script>
            <!-- Condition Popup -->
            <div class="popup popup-condition">
                <div class="close-btn">
                    <a href="#" class="close-popup">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="content-block animated fadeIn">
                    <div class="subTitle">店名查詢</div>
                    <div class="row">
                        <div class="col-25">店名：</div>
                        <div class="col-75"><input class="sd_shopname" type="text" placeholder="請輸入關鍵字"/></div>
                    </div>
                    <div class="subTitle">類別查詢</div>
                    <div class="row">
                        <div class="col-25">類別：</div>
                        <div class="col-75"><input class="spm_serno" type="text" placeholder="請選擇類別" value="所有類別" readonly /></div>
                    </div>
                    <div class="subTitle">地區查詢</div>
                    <!-- <div class="my_location row">
                        <div class="checkbox-item col-100">
                            <input type="checkbox" class="my_location" id="my_location" name="my_location" value="1" />
                            <label for="my_location"><span></span>離我最近</label>
                        </div>
                    </div> -->
                    <!-- 若每次只限制單一展開加上accordion-list class名稱  -->
                    <!-- <div class="list-block">
                        <ul class="regionList">
                        </ul>
                    </div> -->
                    <div class="block-list row" style="padding-left: 6%;">
                    </div>
                </div>
                <div class="toolbar toolbar-bottom search animated fadeInUp">
                    <div class="toolbar-inner">
                        <span><i class="fa fa-search"></i>&nbsp;&nbsp;查詢</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>