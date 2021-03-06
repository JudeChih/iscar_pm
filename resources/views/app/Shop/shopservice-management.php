<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="#" class="link icon-only open-popup" data-popup=".shop-menu">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div>
            <div class="center sliding">排隊管理</div>
            <div class="right">
                <div class="iscar_member_login iscar_member_icon" from="Shop"></div>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="shopservice-management">

            <!-- 內容 -->
            <div class="page-content hide-bars-on-scroll animated fadeIn">
                <div class="row no-gutter">
                    <div class="col-50">排隊總數：&nbsp;
                        <span class="queue-sum">0</span>
                    </div>
                    <div class="col-50">服務總數：&nbsp;<span class="serving-sum">0</span></div>
                </div>

                <div class="row no-gutter">
                    <div class="col-40">開放排隊</div>
                    <div class="col-20">關</div>
                    <div class="col-20 item-input">
                        <label class="label-switch">
                            <input class="service-checkbox" type="checkbox">
                            <div class="checkbox"></div>
                        </label>
                    </div>
                    <div class="col-20">開</div>
                </div>

                <div class="subTitle">服務中項目</div>

                <div class="serving-list">
                    <div class="list-block">
                        <ul>
                            
                        </ul>
                    </div>
                </div>


            </div>



            <!-- 選單 -->
            <div class="toolbar toolbar-bottom animated fadeInUp">
                <div class="toolbar-inner">
                    <div class="row no-gutter">
                        <div class="col-33"><a href="shop/shopservice-setting" class="link">服務設置</a></div>
                        <div class="col-33"><a href="#" class="link" onclick="scan('branch')">條碼掃描</a></div>
                        <div class="col-33"><a href="shop/shopservice-pass" class="link">今日過號</a></div>
                    </div>
                </div>
            </div>



            <script type="text/template7" id="templateShopServiceList">
                {{#each servicelist}}
                {{#if effectivity}}
                <li class="item-content list-item swipeout swipeout-{{ssqd_id}} animated flipInX active">
                    <div class="swipeout-content item-content">
                        <div class="item-media">
                            <img data-src={{ssqd_mainpic}} class="lazy" width=120 onerror='this.src="app/image/imgDefault.png"' />
                        </div>
                        <a href="#" class="item-info">
                            <span class="title">{{ssqd_title}}</span>
                            <span class="row no-gutter">
                            <span class="col-100">下個號碼：<span class="note next-num"> 無</span></span>
                            </span>
                            <span class="row no-gutter">
                            <span class="col-100">呼叫時間：<span class="note call-time"> {{call_time}}</span></span>
                            </span>
                        </a>
                    </div>
                    <div class="swipeout-actions-right">
                        <a href="#" class="call-num" onclick="serviceCall('call','{{ssqd_id}}')">叫號</a>
                        <a href="#" class="pass-num" onclick="serviceCall('pass','{{ssqd_id}}')">過號</a>
                    </div>
                </li>
                {{/if}}
                {{/each}}
            </script>

            <script type="text/template7" id="templateShopServiceListNull">
                <div class="content-null">
                    <h3>{{text}}</h3>
                </div>
            </script>

        </div>
    </div>
</div>