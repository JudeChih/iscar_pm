<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <!--<div class="left">
                <a href="#" class="link icon-only open-panel">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div>-->
            <div class="left">
                <a href="branch-cooperative" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding"></div>
            <div class="right noUse">

            </div>
            <!-- Sub navbar -->
            <div class="subnavbar">
                <div class="buttons-row">
                    <!--<a href="#" class="button tab-news tab-link" onclick="favoriteType('news')">
                        isCar新聞
                    </a>
                    <a href="#" class="button tab-coupon tab-link" onclick="favoriteType('coupon')">
                        isCar活動
                    </a>-->
                    <a href="#" class="button tab-branch tab-link active" onclick="favoriteType('branch')">
                        isCar店家
                    </a>
                    <a href="#" class="button tab-shopcoupon tab-link" onclick="favoriteType('shopcoupon')" style="border-right: 0;">
                        商家活動
                    </a>
                    <!--<a href="#" class="button tab-link deleteAll" style="width: 31%; border-right: 0;">
                        <span><i class="fa fa-trash-o"></i></span>
                    </a>-->
                </div>
            </div>
        </div>
    </div>

    <!-- Pages -->
    <div class="pages">

        <div class="page with-subnavbar" data-page="favorite">

            <!-- 浮動按鈕-至頂 -->
            <a href="#" class="floating-button btn-blog-top">
                <i class="icon icon-chevron-up"></i>
            </a>

                <div class="tabs-animated-wrap tab-block">
                    <div class="tabs">
                        <div class="tab branch active animated fadeIn">
                            <div class="content-block">
                                <div class="list-block favorite-block">
                                    <ul class="branchList">

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab shopcoupon animated fadeIn">
                            <div class="content-block">
                                <div class="list-block favorite-block">
                                    <ul class="shopcouponList">

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            
            <!-- Floating Action Button -->
            <a href="#" class="floating-button deleteAll animated zoomIn">
                <i class="fa fa-trash-o"></i>
            </a>

            <!--template7-->
            <script type="text/template7" id="templateFavoriteNewsList">
                {{#each newsList}}
                <li class="swipeout item-content animated flipInX list-item swipeout-{{ubm_objectid}}">
                    <div class="swipeout-content item-content">
                        <div class="item-media">
                            <img data-src="{{ubm_picpath}}" class="lazy" width=130 onerror='this.src="../app/image/imgDefault.png"' />
                        </div>

                        <a href="favorite-post?id={{ubm_objectid}}" class="news-inner">
                            <!--<p class="title">{{js "this.ubm_title.slice(0,27)"}}...</p>-->
                            <p class="title">{{ubm_title}}</p>
                            <p class="row no-gutter" style="margin-top: 3%;">
                                <!--<span class="col-50"><span class="note">類別 :</span>{{catName}}</span>-->
                                <span class="col-90"><span class="note">日期 :</span>{{create_date}}</span>
                                <!--<span class="col-90"><span class="note">作者 :</span>{{author}}</span>-->
                            </p>
                        </a>
                </div>
                    <div class="swipeout-actions-right">
                        <a href="#" class="swipeout-delete" data-confirm="確定要刪除?" data-confirm-title="提醒" data-close-on-cancel="true" onclick="removeFavorite('{{ubm_objectid}}','0')">刪除</a>
                        </a>
                    </div>
                </li>
                {{/each}}
            </script>
            
            <script type="text/template7" id="templateFavoriteCouponList">
                {{#each couponList}}
                <li class="swipeout item-content list-item swipeout-{{ubm_objectid}}">
                    <div class="swipeout-content item-content">
                        <div class="item-media">
                            <img data-src="http://123.51.218.177:8084/images/coupon/active_banner/{{ubm_picpath}}" class="lazy" width=130 onerror='this.src="../app/image/imgDefault.png"' />
                        </div>

                        <a href="favorite-coupon-content?cdmId={{ubm_objectid}}&type=0" class="news-inner">
                            <p class="title">{{ubm_title}}</p>
                            <p class="row no-gutter" style="margin-top: 3%;">
                                <!--<span class="col-50"><span class="note">類別 :</span>{{catName}}</span>-->
                                <span class="col-90"><span class="note">日期 :</span>{{create_date}}</span>
                                <!--<span class="col-90"><span class="note">作者 :</span>{{author}}</span>-->
                            </p>
                        </a>
                </div>
                    <div class="swipeout-actions-right">
                        <a href="#" class="swipeout-delete" data-confirm="確定要刪除?" data-confirm-title="提醒" data-close-on-cancel="true" onclick="removeFavorite('{{ubm_objectid}}','1')">刪除</a>
                        </a>
                    </div>
                </li>
                {{/each}}
            </script>            
            
            <script type="text/template7" id="templateFavoriteBranchList">
                {{#each branchList}}
                <li class="swipeout item-content list-item swipeout-{{ubm_objectid}}">
                    <div class="swipeout-content item-content">
                        <div class="item-media">
                            <img data-src="http://123.51.218.177:8084/shopdata/{{ubm_picpath}}" class="lazy" width=130 onerror='this.src="../app/image/imgDefault.png"' />
                        </div>

                        <a href="branch-info?sd_id={{ubm_objectid}}&from=favorite" class="news-inner">
                            <p class="title">{{ubm_title}}</p>
                            <p class="row no-gutter" style="margin-top: 3%;">
                                <!--<span class="col-50"><span class="note">類別 :</span>{{catName}}</span>-->
                                <span class="col-90"><span class="note">日期 :</span>{{create_date}}</span>
                                <!--<span class="col-90"><span class="note">作者 :</span>{{author}}</span>-->
                            </p>
                        </a>
                </div>
                    <div class="swipeout-actions-right">
                        <a href="#" class="swipeout-delete" data-confirm="確定要刪除?" data-confirm-title="提醒" data-close-on-cancel="true" onclick="removeFavorite('{{ubm_objectid}}','2')">刪除</a>
                        </a>
                    </div>
                </li>
                {{/each}}
            </script>
            
            <script type="text/template7" id="templateFavoriteShopCouponList">
                {{#each shopcouponList}}
                <li class="swipeout item-content list-item swipeout-{{ubm_objectid}}">
                    <div class="swipeout-content item-content">
                        <div class="item-media">
                            <img data-src="http://123.51.218.177:8084/shopdata/{{ubm_picpath}}" class="lazy" width=130 onerror='this.src="../app/image/imgDefault.png"' />
                        </div>

                        <a href="shop/shopcoupon-info?scm_id={{ubm_objectid}}&type=favorite" class="news-inner">
                            <p class="title">{{ubm_title}}</p>
                            <p class="row no-gutter" style="margin-top: 3%;">
                                <span class="col-90"><span class="note">日期 :</span>{{create_date}}</span>
                            </p>
                        </a>
                </div>
                    <div class="swipeout-actions-right">
                        <a href="#" class="swipeout-delete" data-confirm="確定要刪除?" data-confirm-title="提醒" data-close-on-cancel="true" onclick="removeFavorite('{{ubm_objectid}}','3')">刪除</a>
                        </a>
                    </div>
                </li>
                {{/each}}
            </script>

            <script type="text/template7" id="templateFavoriteListNull">
                <div class="content-null">
                    <h1><i class="fa fa-star-o"></i></h1>
                    <h3>無收藏</h3>
                    <span>按下在列表旁的星星即可加入收藏</span>
                </div>
            </script>
        </div>
    </div>
</div>