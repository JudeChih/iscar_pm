<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding coupon-order-forms-left">
                <a href="shop-salesoverview" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding coupon-order-forms-center">票券訂單列表</div>
            <div class="right noUse">
                <a href="#" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
        </div>
    </div>
    <div class="pages">
        <div class="page" data-page="coupon-order-forms">
            <div class="page-content coupon-order-forms-content">
                <!-- <div class="row">
                    <div class="col-50"><span>起 </span><input class="start_date" type="text" readonly /></div>
                    <div class="col-50"><span>迄 </span><input class="end_date" type="text" readonly /></div>
                </div> -->
                <div class="row search-block">
                    <div class="col-70"><span>訂單狀態 </span><input class="scg_usestatus" type="text" placeholder="請選擇狀態" readonly /></div>
                    <div class="col-30"><a class="button search-btn">查詢</a></div>
                </div>
                <!-- Swiper -->
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                    </div>
                    <!-- Add Pagination -->
                    <!-- <div class="swiper-pagination"></div> -->
                </div>

                <div class="pagination">
                    <div class="pagination-content"><span class="previous-page"><i class="fa fa-chevron-left" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><input class="now-page" type="number" value="1" readonly />&nbsp;&nbsp;/&nbsp;&nbsp;<span class="sum-pages"></span><span class="next-page">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-chevron-right" aria-hidden="true"></i></span></div>
                </div>
            </div>
            <script type="text/template7" id="templateCouponOrderFormsItems">
            {{#each order_list}}
            <tr scg_id="{{scg_id}}" scl_id="{{scl_id}}">
                <th class="label-cell">{{show_scg_id}}</th>
                <th class="label-cell">{{scg_buyername}}</th>
                <th class="label-cell">{{_scg_usestatus}}</th>
            </tr>
            {{/each}}
            </script>
        </div>
    </div>
</div>