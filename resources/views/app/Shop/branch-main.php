<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="#" class="link icon-only open-popup" data-popup=".shop-menu">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div>
            <div class="center sliding branch-name">商家名稱</div>
            <div class="right">
                <div class="iscar_member_login iscar_member_icon" from="Shop"></div>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="branch-main">
           
            <!-- 快捷鈕 -->
            <div class="hot-key"></div>
            
            <!-- 內容 -->
            <div class="page-content animated fadeIn">
                <div class="row subtitle-block">
                    <div class="col-50 subtitle">今日預約</div>
                    <div class="col-50 nop reservation-num"><span>0</span>人</div>
                </div>

                <div class="list-block media-list reservation-list">
                    <ul>

                    </ul>
                </div>

                <!-- <div class="row subtitle-block">
                    <div class="col-50 subtitle">今日排隊</div>
                    <div class="col-50 nop queue-num"><span>0</span>人</div>
                </div>

                <div class="list-block media-list queue-list">
                    <ul>

                    </ul>
                </div> -->

            </div>

            <div class="toolbar toolbar-bottom animated fadeInUp">
                <div class="toolbar-inner">
                    <a href="#" class="link" onclick="scan('branch')"><i class="fa fa-search" style="color: #444;"></i>&nbsp;條碼掃描</a>
                </div>
            </div>


            <script type="text/template7" id="templateBranchMainReserveList">
                <li class="item-content" scm_id="{{scm_id}}" scg_id="{{scg_id}}" >
                    <div class="item-media"><img data-src="{{ssd_picturepath}}" class="lazy" width="60" onerror='this.src="app/image/general_user.png"'></div>
                    <div class="item-inner">
                        <div class="item-title-row">
                            <div class="item-title">{{scm_title}}</div>
                        </div>
                        <div class="item-subtitle">
                            <div class="row">
                                <div class="col-100">預約人：<span>{{md_cname}}</span></div>
                                <div class="col-40">時間：<span>{{js "this.scr_rvtime.slice(0,5)"}}</span></div>
                                <div class="col-60">預約狀態：<span>{{status}}</span></div>
                            </div>
                        </div>
                    </div>
                </li>
            </script>

            <script type="text/template7" id="templateBranchMainQueueList">
                {{#each queue_array}}
                {{#if ssqq_usestatus}}
                <li class="item-content">
                    <div class="queserno">{{ssqq_queserno}}</div>
                    <div class="item-media"><img data-src="{{ssd_picturepath}}" class="lazy" width="60" onerror='this.src="app/image/general_user.png"'></div>
                    <div class="item-inner">
                        <div class="item-title-row">
                            <div class="item-title">{{ssqd_title}}</div>
                        </div>
                        <div class="item-subtitle">
                            <div class="row">
                                <div class="col-100">姓名：<span>{{md_cname}}</span></div>
                                <div class="col-100">車款：<span>{{car_model}}</span></div>
                                <!--<div class="col-100">排隊號碼：<span>{{ssqq_queserno}}</span></div>-->
                            </div>
                        </div>
                    </div>
                </li>
                {{/if}}
                {{/each}}
            </script>

            <script type="text/template7" id="templateRecordNull">
                <div class="content-null">
                    <h1>暫無記錄</h1>
                </div>
            </script>

        </div>
    </div>
</div>