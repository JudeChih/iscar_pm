<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding reservation-left">
                <a href="#" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding post-title">活動預約</div>

        </div>
    </div>

    <!-- Pages -->
    <div class="pages navbar-fixed">
        <div data-page="shopcoupon-reservation" class="page">

            <div class="page-content animated fadeIn">
                
                <div class="note">活動說明</div>

                <div class="text-block">
                
                </div>

                <div class="calendar-block">
                    <div class="note">選擇時間</div>
                    <div class="content-block">
                        <div style="padding:0; margin-right:-15px; width:auto" class="content-block-inner">
                            <div id="calendar-inline-container"></div>
                        </div>
                    </div>
                </div>



            </div>

            <!--template7-->
            <script type="text/template7" id="templateShopCouponReservation">
                <div>活動名稱：<span class="scm_title" style="color:#F26531;">{{scm_title}}</span></div>
                <div>截止日：<span style="color:burlywood;">{{scm_enddate}}</span></div>
                <!--<div>可預約時段：<span style="color:burlywood;">{{scr_rvtime}}</span></div>-->
            </script>



        </div>
    </div>
</div>