<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding order-forms-left">
                <a href="order-form-management" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding order-forms-center">商品訂單列表</div>
            <div class="right noUse">
                <a href="#" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
        </div>
    </div>
    <div class="pages">
        <div class="page" data-page="order-forms">
            <div class="page-content order-forms-content">
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
                        <!-- <div class="swiper-slide">
                            <div class="data-table card">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="label-cell">訂單編號</th>
                                            <th class="label-cell">收件人</th>
                                            <th class="label-cell">訂單狀態</th>
                                            <th class="label-cell">回報</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div> -->
                    </div>
                    <!-- Add Pagination -->
                    <!-- <div class="swiper-pagination"></div> -->
                </div>

                <div class="pagination">
                    <div class="pagination-content"><span class="previous-page"><i class="fa fa-chevron-left" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><input class="now-page" type="number" value="1" readonly />&nbsp;&nbsp;/&nbsp;&nbsp;<span class="sum-pages"></span><span class="next-page">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-chevron-right" aria-hidden="true"></i></span></div>
                </div>
            </div>
            <script type="text/template7" id="templateOrderFormsItems">
            {{#each order_list}}
            <tr scg_id="{{scg_id}}" scl_id="{{scl_id}}">
                <th class="label-cell">{{show_scg_id}}</th>
                <th class="label-cell">{{scl_receivername}}</th>
                <th class="label-cell">{{_scg_usestatus}}</th>
                <th class="numeric-cell">{{#if report_btn_text}}<a class="button report" scm_id="{{scm_id}}" scg_id="{{scg_id}}" scl_id="{{scl_id}}" scg_usestatus="{{scg_usestatus}}" style="background: {{color}};">{{report_btn_text}}</a>{{/if}}</th>
            </tr>
            {{/each}}
            </script>
        </div>
    </div>
</div>