<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="shopservice-management" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">服務設置</div>
            <div class="right noUse">
            </div>
            
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="shopservice-setting">
            <!-- Sub navbar -->
            <div class="subnavbar">
                <div class="row no-gutter">
                    <div class="col-50"><a href=".shopservice-list" class="active tab-link list">服務項目</a></div>
                    <div class="col-50" style="border:0;"><a href=".shopservice-set" class="tab-link set">排隊設置</a></div>
                </div>
            </div>
            <div class="tabs">
                <!-- Tab 1, active by default -->
                <div class="shopservice-list page-content tab infinite-scroll pull-to-refresh-content hide-bars-on-scroll active animated fadeIn" data-distance="300">
                    <!-- 下拉刷新符 -->
                    <div class="pull-to-refresh-layer">
                        <div class="preloader preloader-white"></div>
                        <div class="pull-to-refresh-arrow"></div>
                    </div>

                    <div class="list-block shopservice-list-content">
                        <ul>

                        </ul>

                    </div>

                </div>

                <!-- Tab 2 -->
                <div class="shopservice-set page-content tab animated fadeIn">


                </div>
            </div>

            <!-- Floating Action Button -->
            <a href="shop/shopservice-edit?type=add" class="floating-button animated zoomIn">
                +
            </a>

            <div class="toolbar toolbar-bottom animated fadeInUp">
                <div class="toolbar-inner">

                </div>
            </div>


            <script type="text/template7" id="templateShopService">
                {{#each servicelist}}
                <li class="item-content list-item swipeout animated flipInX">
                    <div class="swipeout-content item-content">
                        <div class="item-media">
                            <img data-src={{ssqd_mainpic}} class="lazy" width=160 onerror='this.src="../app/image/imgDefault.png"' />
                        </div>
                        <div class="item-inner item-info">
                            <div class="row no-gutter">
                                <div class="col-60" onclick="hrefTo('shop/shopservice-info?from=setting&ssqd_id={{ssqd_id}}')">
                                    <p class="title">{{ssqd_title}}</p>
                                    <p class="row">
                                        <span class="col-60"><span class="note">狀態 :</span>{{ssqd_effectivity}}</span>
                                    </p>
                                </div>
                                <div class="col-40">
                                    <div class="row no-gutter">
                                        {{#if effectivity}}
                                        <div class="col-50">
                                            <a href="#" class="setStatus stop" onclick="changeStatus('{{ssqd_id}}',{{effectivity}})">停用</a>
                                        </div>
                                        <div class="col-50">
                                            <a href="#" class="editData check">修改</a>
                                        </div>
                                        {{else}}
                                        <div class="col-50">
                                            <a href="#" class="setStatus" onclick="changeStatus('{{ssqd_id}}',{{effectivity}})">啟用</a>
                                        </div>
                                        <div class="col-50">
                                            <a href="shop/shopservice-edit?type=edit&ssqd_id={{ssqd_id}}" class="editData">修改</a>
                                        </div>
                                        {{/if}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="swipeout-actions-right">
                        {{#if effectivity}}
                        <a href="#" class="setStatus stop" onclick="changeStatus('{{ssqd_id}}',{{effectivity}})">停用</a>
                        <a href="#" class="editData check">修改</a> {{else}}
                        <a href="#" class="setStatus" onclick="changeStatus('{{ssqd_id}}',{{effectivity}})">啟用</a>
                        <a href="shop/shopservice-edit?type=edit&ssqd_id={{ssqd_id}}" class="editData">修改</a> {{/if}}
                    </div>-->
                </li>
                {{/each}}
            </script>

            <script type="text/template7" id="templateShopServiceNull">
                <div class="content-null">
                    <h1><i class="fa fa-file-text-o" aria-hidden="true"></i></h1>
                    <br>
                    <h3>暫無資訊</h3>
                </div>
            </script>

            <script type="text/template7" id="templateShopServiceSet">
                <div class="list-block">
                    <ul>
                        <li class="service-date">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-calendar"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">每週服務日</div>
                                    <div class="item-input">
                                        <input type="text" placeholder="星期一 ~ 星期日" readonly id="service-date">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="service-time">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-clock-o"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">每日服務時間</div>
                                    <div class="item-input">
                                        <input type="text" placeholder="08:00 ~ 23:00" readonly id="service-time">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li style="padding-bottom:0;">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-calendar-check-o" aria-hidden="true"></i></div>
                                <div class="item-inner holidays">
                                    <div class="item-title label">公休日設置</div>
                                    <div class="item-input">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="noUse" style="display:none;">

                        </li>
                    </ul>
                </div>
                <div class="calendar-holidays">
                    <div id="calendar-holidays"></div>
                </div>
            </script>

            <script type="text/template7" id="templateShopServiceEditBtn">
                <a class="edit">修改</a>
            </script>

        </div>
    </div>
</div>