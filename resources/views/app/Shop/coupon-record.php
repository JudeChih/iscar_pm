<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="#" class="link icon-only open-popup" data-popup=".shop-menu">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div>
            <div class="center sliding coupon-record-title">排隊紀錄</div>
            <div class="right">
                <div class="iscar_member_login iscar_member_icon" from="Shop"></div>
            </div>
            <!-- Sub navbar -->
            <!-- <div class="subnavbar">
                <div class="buttons-row">
                    <a href="#queue" class="button tab-link active">
                        汽車特店排隊
                    </a>
                    <a href="#shop-coupon" class="button tab-link" style="border-right: 0;">
                        汽車特店優惠
                    </a>
                </div>
            </div> -->
        </div>
    </div>

    <!-- Pages -->
    <div class="pages">

        <div class="page" data-page="coupon-record"><!-- with-subnavbar -->

            <!-- 浮動按鈕-至頂 
            <a href="#" class="floating-button btn-blog-top">
                <i class="icon icon-chevron-up"></i>
            </a>-->

                <div class="tabs">
                    <div id="queue" class="page-content tab active animated fadeIn">
                        <div class="content-block">
                            <!-- 加载提示符 -->
                            <div class="coupon-record-preloader">
                                <i class="fa fa-spinner fa-pulse" style="color:#777; font-size: 50pt; margin-left:41%; margin-top: 50%;"></i>
                            </div>
                        </div>
                    </div>

                    <div id="shop-coupon" class="page-content tab animated fadeIn">
                        <div class="content-block">

                            <!-- 加载提示符 -->
                            <div class="coupon-record-preloader">
                                <i class="fa fa-spinner fa-pulse" style="color:#777; font-size: 50pt; margin-left:41%; margin-top: 50%;"></i>
                            </div>

                        </div>
                    </div>
                </div>


            <!--template7-->
            <script type="text/template7" id="templateCouponRecordList">
                <div class="list-block accordion-list">
                    <ul>
                        <li class="accordion-item available-block">
                            <a href="#" class="item-content item-link accordion-item-link">
                                <div class="item-inner">
                                    <div class="item-title">可用</div>
                                </div>
                            </a>
                            <div class="accordion-item-content">
                                <div class="content-block available-content">
                                    <ul class="couponAvailableList">

                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="accordion-item finish-block">
                            <a href="#" class="item-content item-link accordion-item-link">
                                <div class="item-inner">
                                    <div class="item-title">用畢</div>
                                </div>
                            </a>
                            <div class="accordion-item-content">
                                <div class="content-block finish-content">
                                    <ul class="couponFinishList">

                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="accordion-item invalid-block">
                            <a href="#" class="item-content item-link accordion-item-link">
                                <div class="item-inner">
                                    <div class="item-title">失效</div>
                                </div>
                            </a>
                            <div class="accordion-item-content">
                                <div class="content-block invalid-content">
                                    <ul class="couponInvalidList">

                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </script>
            <script type="text/template7" id="templateShopCouponAvailableList">
                {{#each availableList}}
                {{#if is_product}}
                {{else}}
                <li class="item-content list-item swipeout animated flipInX swipeout-{{scg_id}}">
                    <div class="swipeout-content item-content" onclick="loginCheck('{{scm_id}}',{{#if reservationdatetime}}'reserved'{{else}}'shop_available'{{/if}},'{{scg_id}}')">
                        <div class="item-media">
                            <img data-src="{{scm_mainpic}}" class="lazy" width=120 onerror='this.src="app/image/imgDefault.png"' />
                        </div>

                        <a href="#" class="coupon-inner">
                            <p class="title">{{scm_title}}</p>
                            <p class="row no-gutter" style="margin-top: 3%;">
                                {{#if reservationdatetime}}
                                <span class="col-100"><span class="note" style="color:#ff3b30;">預約日 :</span>{{js "this.reservationdatetime.slice(0,16)"}}</span>
                                {{else}}
                                <span class="col-100"><span class="note">截止日 :</span>{{js "this.scm_enddate.slice(0,10)"}}</span>
                                {{/if}}
                            </p>
                        </a>
                    </div>
                    <div class="swipeout-actions-right">
                        {{#if alarmCheck}}
                        <a href="#" class="setAlarm" onclick="setAlarm('{{scm_id}}','{{scg_id}}','{{scm_title}}','{{js "this.reservationdatetime.slice(0,16)"}}','shop-coupon')">提醒</a> {{/if}}
                        {{#if reservationCheck}}
                        {{#if reservationdatetime}}
                        {{else}}
                        <a href="shop/shopcoupon-reservation?scm_id={{scm_id}}&scm_title={{scm_title}}&scm_enddate={{scm_enddate}}&scg_id={{scg_id}}&from=record&booktype=0" class="reservation">預約</a> {{/if}}{{/if}}
                        <a href="#" class="swipeout-delete" data-confirm="確定要棄用?" data-confirm-title="提醒" data-close-on-cancel="true" onclick="shopCouponAbandon('{{scm_id}}','{{scg_id}}')">棄用</a>
                        </a>
                    </div>
                </li>
                {{/if}}
                {{/each}}
            </script>

            <script type="text/template7" id="templateShopCouponFinishList">
                {{#each finishList}}
                <li class="item-content list-item swipeout swipeout-{{scg_id}}">
                    <div class="swipeout-content item-content" onclick="loginCheck('{{scm_id}}','shop_finish','{{scg_id}}')">
                        <div class="item-media">
                            <img data-src="{{scm_mainpic}}" class="lazy" width=120 onerror='this.src="app/image/imgDefault.png"' />
                        </div>

                        <a href="#" class="coupon-inner">
                            <p class="title">{{scm_title}}</p>
                            <p class="row no-gutter" style="margin-top: 3%;">
                                <span class="col-100"><span class="note">截止日 :</span>{{js "this.scm_enddate.slice(0,10)"}}</span>
                            </p>
                        </a>
                    </div>
                    <!--<div class="swipeout-actions-right">
                        <a href="#" class="swipeout-delete" data-confirm="確定要棄用?" data-confirm-title="提醒" data-close-on-cancel="true">棄用</a>
                        </a>
                    </div>-->
                </li>
                {{/each}}
            </script>

            <script type="text/template7" id="templateShopCouponInvalidList">
                {{#each invalidList}}
                <li class="item-content list-item swipeout swipeout-{{scg_id}}">
                    <div class="swipeout-content item-content" onclick="loginCheck('{{scm_id}}','shop_invalid','{{scg_id}}')">
                        <div class="item-media">
                            <img data-src="{{scm_mainpic}}" class="lazy" width=120 onerror='this.src="app/image/imgDefault.png"' />
                        </div>

                        <a href="#" class="coupon-inner">
                            <p class="title">{{scm_title}}</p>
                            <p class="row no-gutter" style="margin-top: 3%;">
                                <span class="col-100"><span class="note">截止日 :</span>{{js "this.scm_enddate.slice(0,10)"}}</span>
                            </p>
                        </a>
                    </div>
                    <!--<div class="swipeout-actions-right">
                        <a href="#" class="swipeout-delete" data-confirm="確定要棄用?" data-confirm-title="提醒" data-close-on-cancel="true">棄用</a>
                        </a>
                    </div>-->
                </li>
                {{/each}}
            </script>
            
            
            <script type="text/template7" id="templateShopServiceAvailableList">
                {{#each availableList}}
                <li class="item-content list-item swipeout animated flipInX swipeout-{{ssqq_id}}">
                    <div class="swipeout-content item-content" onclick="loginCheck('{{ssqd_id}}','service_available','{{ssqq_id}}')">
                        <div class="item-media">
                            <img data-src="{{ssqd_mainpic}}" class="lazy" width=120 onerror='this.src="app/image/imgDefault.png"' />
                        </div>

                        <a href="#" class="coupon-inner">
                            <p class="title">{{ssqd_title}}</p>
                            <p class="row no-gutter" style="margin-top: 3%;">
                                <span class="col-50"><span class="note">號碼 :</span>{{ssqq_queserno}}</span>
                                <span class="col-50"><span class="note">費用 :</span>{{ssqd_serviceprice}}</span>
                                <span class="col-100"><span class="note">排隊日期 :</span>{{ssqq_questarttime}}</span>
                            </p>
                        </a>
                    </div>
                    <div class="swipeout-actions-right">                        
                        <a href="#" class="swipeout-delete" data-confirm="確定要棄用?" data-confirm-title="提醒" data-close-on-cancel="true" onclick="shopServiceAbandon('{{ssqd_id}}','{{ssqq_id}}')">棄用</a>
                        </a>
                    </div>
                </li>
                {{/each}}
            </script>

            <script type="text/template7" id="templateShopServiceFinishList">
                {{#each finishList}}
                <li class="item-content list-item swipeout swipeout-{{ssqq_id}}">
                    <div class="swipeout-content item-content" onclick="loginCheck('{{ssqd_id}}','service_finish','{{ssqq_id}}')">
                        <div class="item-media">
                            <img data-src="{{ssqd_mainpic}}" class="lazy" width=120 onerror='this.src="app/image/imgDefault.png"' />
                        </div>

                        <a href="#" class="coupon-inner">
                            <p class="title">{{ssqd_title}}</p>
                            <p class="row no-gutter" style="margin-top: 3%;">
                                <span class="col-50"><span class="note">號碼 :</span>{{ssqq_queserno}}</span>
                                <span class="col-50"><span class="note">費用 :</span>{{ssqd_serviceprice}}</span>
                                <span class="col-100"><span class="note">排隊日期 :</span>{{ssqq_questarttime}}</span>
                            </p>
                        </a>
                    </div>
                    <!--<div class="swipeout-actions-right">
                        <a href="#" class="swipeout-delete" data-confirm="確定要棄用?" data-confirm-title="提醒" data-close-on-cancel="true">棄用</a>
                        </a>
                    </div>-->
                </li>
                {{/each}}
            </script>

            <script type="text/template7" id="templateShopServiceInvalidList">
                {{#each invalidList}}
                <li class="item-content list-item swipeout swipeout-{{ssqq_id}}">
                    <div class="swipeout-content item-content" onclick="loginCheck('{{ssqd_id}}','service_invalid','{{ssqq_id}}')">
                        <div class="item-media">
                            <img data-src="{{ssqd_mainpic}}" class="lazy" width=120 onerror='this.src="app/image/imgDefault.png"' />
                        </div>

                        <a href="#" class="coupon-inner">
                            <p class="title">{{ssqd_title}}</p>
                            <p class="row no-gutter" style="margin-top: 3%;">
                                <span class="col-50"><span class="note">號碼 :</span>{{ssqq_queserno}}</span>
                                <span class="col-50"><span class="note">費用 :</span>{{ssqd_serviceprice}}</span>
                                <span class="col-100"><span class="note">排隊日期 :</span>{{ssqq_questarttime}}</span>
                            </p>
                        </a>
                    </div>
                    <!--<div class="swipeout-actions-right">
                        <a href="#" class="swipeout-delete" data-confirm="確定要棄用?" data-confirm-title="提醒" data-close-on-cancel="true">棄用</a>
                        </a>
                    </div>-->
                </li>
                {{/each}}
            </script>

            <script type="text/template7" id="templateCouponListNull">
                <div class="content-null">
                    <h1><i class="fa fa-shopping-basket"></i></h1>
                    <br>
                    <h3>無紀錄</h3>
                </div>
            </script>
        </div>
    </div>
</div>