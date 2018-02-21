<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding shopcoupon-info-left">
                <a href="branch-info" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding shopcoupon-info-title"></div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="shopcoupon-info">

            <!-- 內容 -->
            <div class="page-content animated fadeIn">

                <!-- Swiper -->
                <div class="shopcoupon-imgs">
                    <div class="swiper-wrapper">
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>
                    <div class="reservationtag">預約限定</div>
                    <div class="coupontag">活動服務</div>
                    <div class="commoditytag">實體商品</div>
                </div>

                <div class="shopcoupon-info-block">
                    <!-- 加载提示符 -->
                    <!-- <div class="mPreloader">
                        <i class="fa fa-spinner fa-pulse" style="color:#777; font-size: 50pt; margin-left:41%; margin-top: 60%;"></i>
                    </div> -->
                </div>

            </div>

            <div class="toolbar toolbar-bottom get animated fadeInUp">
                <div class="toolbar-inner">
                    <a>立即購買</a>
                </div>
            </div>

            <div class="toolbar toolbar-bottom showQR animated fadeInUp">
                <div class="toolbar-inner">
                    <a>顯示QR碼</a>
                </div>
            </div>

            <div class="toolbar toolbar-bottom serving animated fadeInUp">
                <div class="toolbar-inner">
                    <a>開始服務</a>
                </div>
            </div>

            <div class="toolbar toolbar-bottom reserved animated fadeInUp">
                <div class="toolbar-inner">
                    <div class="row no-gutter">
                        <div class="col-50 editDate"><a>更改時間</a></div>
                        <div class="col-50 qrCode" style="border:0;"><a>顯示QR碼</a></div>
                    </div>
                </div>
            </div>

            <script type="text/template7" id="templateShopCouponInfo">
                <div class="row no-gutter">
                    <div class="col-90">
                        <div class="shopcoupon_name">{{scm_title}}</div>
                    </div>
                    <div class="col-10 favorite favorite-{{scm_id}}" onclick="addFavorite('{{scm_id}}', '{{scm_mainpic}}', '{{scm_title}}', '{{scm_category}}', '{{scm_reservationtag}}', '{{scm_startdate}} ~ {{scm_enddate}}','3')"><i class="fa fa-star-o"></i></div>
                </div>
                <div class="shopcoupon-content">
                    <!-- <span class="branch_tel_title">活動類別：</span>
                    <span class="branch_tel" style="color:burlywood;">{{scm_type}}</span>
                    <br> -->
                    <span class="branch_date_title">活動日期：</span>
                    <span class="branch_date" style="color:burlywood;">{{scm_startdate}} ~ {{scm_enddate}}</span>
                    <br>
                    <span class="branch_time_title">費用：</span>
                    <span class="branch_time" style="color: red;font-size: 1.5em;font-weight: bold;">{{scm_price}}</span>
                    <br>
                    <!-- <span class="inventory_title">庫存：</span>
                    <span class="inventory" style="font-weight: bold;">{{inventory}}</span>
                    <br> -->
                    <span class="branch_time_title">活動說明：</span>
                    <span class="branch_time" style="color:burlywood;">{{scm_fulldescript}}</span>
                </div>
                {{#if scm_advancedescribe}}
                <div class="shopcoupon-subTitle">更多資訊</div>
                <div class="shopcoupon-details" id="scm_advancedescribe">
                    {{#each scm_advancedescribe}}
                    {{#if content_img}}
                    <img data-src="{{content_img}}" class="lazy" onerror='this.src="app/image/imgDefault.png"'/>
                    {{/if}}
                    {{#if content_text}}
                    <div class="context">{{content_text}}</div>
                    {{/if}}
                    {{/each}}
                </div>
                {{/if}}
                <div class="shopcoupon-subTitle">商家資訊</div>
                <div class="shopcoupon-content branch-info">
                    <span class="branch_title">店名：</span>
                    <span class="branch_tel" style="color:burlywood;">{{sd_shopname}}</span>
                    <br>
                    <span class="branch_tel_title">電話：</span>
                    <span class="branch_tel" style="color:burlywood;">{{sd_shoptel}}</span>
                    <br>
                    <span class="branch_date_title">營業日期：</span>
                    <span class="branch_date" style="color:burlywood;">星期{{sd_weeklystart}} ~ 星期{{sd_weeklyend}}</span>
                    <br>
                    <span class="branch_time_title">營業時間：</span>
                    <span class="branch_time" style="color:burlywood;">{{js "this.sd_dailystart.slice(0,5)"}} ~ {{js "this.sd_dailyend.slice(0,5)"}}</span>
                    <br>
                    <span class="branch_address_title">地址：</span>
                    <span class="branch_address" style="color:burlywood;">{{sd_shopaddress}}</span>
                </div>

                <!--<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3614.4918568665407!2d121.55633441537451!3d25.051313143750278!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjXCsDAzJzA0LjciTiAxMjHCsDMzJzMwLjciRQ!5e0!3m2!1szh-TW!2stw!4v1465375042464" width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>

                    <iframe width="100%" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=25.0513131437,121.5563344&hl=zh-tw;z=14&amp;output=embed"></iframe>-->

                <div id="shopcoupon-map"></div>
            </script>

        </div>
    </div>
</div>