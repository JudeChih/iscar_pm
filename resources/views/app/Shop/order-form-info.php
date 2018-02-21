<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding order-form-info-left">
                <a href="#" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding order-form-info-center">商品訂單內容</div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="order-form-info">
            <!-- 內容 -->
            <div class="page-content order-form-info-content">
                <div class="order-form-info-block">
                </div>
            </div>
            <!-- 選單 -->
            <div class="toolbar toolbar-bottom animated fadeInUp">
                <div class="toolbar-inner">
                    <a></a>
                </div>
            </div>
            <script type="text/template7" id="templateOrderFormInfo">
            <div class="row no-gutter">
                <div class="col-40"><img data-src="{{scm_mainpic}}" class="lazy" onerror='this.src="app/image/imgDefault.png"' /></div>
                <div class="col-60 title">{{scm_title}}</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">訂單編號：&nbsp;</span>{{scg_id}}</div>
            </div>
            <div class="row no-gutter">
                <div class="col-50"><span class="note">買家姓名：&nbsp;</span>{{scg_buyername}}</div>
                <div class="col-50"><span class="note">購買數量：&nbsp;</span>{{scg_buyamount}}</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">結帳金額：&nbsp;</span><span class="price">NT {{scg_price}}</span></div>
            </div>
            <div class="row no-gutter">
                <div class="col-100">
                    <div class="note">買家留言：</div>
                    <div class="remark-content">{{scg_buyermessage}}</div>
                </div>
            </div>
            {{#if isCoupon}}
            <div class="row no-gutter">
                <div class="col-100"><span class="note">下單時間：&nbsp;</span>{{scg_create_date}}</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">付款時間：&nbsp;</span>{{scg_paid_time}}</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">預約時間：&nbsp;</span>{{scr_rvdate_time}}</div>
            </div>
            {{else}}
            <div class="row no-gutter">
                <div class="col-100"><span class="note">印單時間：&nbsp;</span>{{scl_orderprinttime}}</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">分裝時間：&nbsp;</span>{{scl_cargopicktime}}</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">物流時間：&nbsp;</span>{{scl_senddeliverytime}}</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100"><span class="note">到貨時間：&nbsp;</span>{{scl_cargoarrivetime}}</div>
            </div>
            <div class="row no-gutter">
                <div class="col-100">
                    <div class="note">分裝照片：</div>
                    <div class="remark-content"><!-- <img src="app/image/service.jpg" /> --><img data-src="{{scl_cargopack_pic}}" class="lazy" onerror='this.src="app/image/imgDefault.png"' /></div>
                </div>
            </div>
            {{/if}}
            </script>
        </div>
    </div>
</div>