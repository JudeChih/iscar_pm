<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding shopcoupon-info-left">
                <a href="#" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding"></div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="shopservice-info">

            <!-- 內容 -->
            <div class="page-content shopservice-info-content animated fadeIn">


            </div>

            <div class="toolbar toolbar-bottom queue animated fadeInUp">
                <div class="toolbar-inner">
                    <a>排隊</a>
                </div>
            </div>
            
            <div class="toolbar toolbar-bottom showQR animated fadeInUp">
                <div class="toolbar-inner">
                    <a>顯示服務條碼</a>
                </div>
            </div>
            
            <div class="toolbar toolbar-bottom serving animated fadeInUp">
                <div class="toolbar-inner">
                    <a>執行服務</a>
                </div>
            </div>
            
            
            <script type="text/template7" id="templateShopServiceInfo">
                <div class="shopservice-img">
                    <img data-src="{{ssqd_mainpic}}" class="lazy" onerror='this.src="../app/image/imgDefault.png"' />
                </div>
                
                <div class="shopservice-info-block">
                <div class="row no-gutter">
                    <div class="col-90">
                        <div class="shopservice_name">{{ssqd_title}}</div>
                    </div>
                    <!--<div class="col-10 favorite favorite-{{scm_id}}" onclick="addFavorite('{{scm_id}}', '{{scm_mainpic}}', '{{scm_title}}', '{{scm_category}}', '{{scm_reservationtag}}', '{{scm_startdate}} ~ {{scm_enddate}}','3')"><i class="fa fa-star-o"></i></div>-->
                </div>
                <div class="shopservice-content">
                    <span class="branch_tel_title">所需工時：</span>
                    <span class="branch_tel" style="color:burlywood;">{{ssqd_workhour}}</span>
                    <br>
                    <span class="branch_date_title">費用：</span>
                    <span class="branch_date" style="color:burlywood;">{{ssqd_serviceprice}}</span>
                    <br>
                    <span class="branch_time_title">最大排隊數：</span>
                    <span class="branch_time" style="color:burlywood;">{{ssqd_maxqueueamount}}</span>
                    <br>
                    <span class="branch_time_title">簡介內容：</span>
                    <span class="branch_time" style="color:burlywood;">{{ssqd_content}}</span>
                </div>
                </div>
                
                
                <div class="subTitle">客戶資訊</div>
                
                <div class="client-info">
                <div class="row">
                <div class="col-30"><img data-src="../app/image/general_user.png" class="lazy client-pic" onerror='this.src="../app/image/general_user.png"' /></div>
                <div class="col-70">
                <div>姓名：<span class="client-name"></span></div>
                <div>號碼：<span class="client-num"></span></div>
                <div>排隊日期：<span class="client-date"></span></div>
                </div>
                </div>
                </div>
                
                
            </script>

        </div>
    </div>
</div>