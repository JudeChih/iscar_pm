<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding">
                <a href="business-block" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">廠商綁定</div>
            <div class="right sliding">

            </div>

            <!-- Sub navbar -->
            <div class="subnavbar">
                <!-- Sub navbar -->
                <div style="width:100%;">
                    <div class="search-block">
                        <input type="text" name="branch_name" placeholder="店家名稱">
                        <i class="fa fa-search" style="float:right; margin-right: 2.5%; padding-top: 1.2%; font-size:16pt;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="branch-binding">

            <!-- 內容 -->
            <div class="page-content infinite-scroll pull-to-refresh-content hide-bars-on-scroll animated fadeIn" data-distance="300">

                <!-- 下拉刷新符 -->
                <div class="pull-to-refresh-layer">
                    <div class="preloader preloader-white"></div>
                    <div class="pull-to-refresh-arrow"></div>
                </div>

                <div class="list-block">
                    <ul class="branch-list-container">
                        
                    </ul>
                </div>

            </div>

            <div class="toolbar toolbar-bottom tabbar binding animated fadeInUp">
                <div class="toolbar-inner">
                    <a href="#" class="link">綁定</a>
                </div>
            </div>


            <script type="text/template7" id="templateBranchBindList">
                {{#each list}}
                <li>
                    <label class="label-radio item-content">
                        <div class="item-media">
                            <img data-src="{{sd_shopphotopath}}" class="lazy" width=130 onerror='this.src="../app/image/imgDefault.png"' />
                        </div>
                        <!-- Checked by default -->
                        <input type="radio" name="branch-radio" value="{{sd_id}}">
                        <div class="item-inner">
                            <div class="item-title shopname">{{sd_shopname}}</div>
                        </div>
                    </label>
                </li>
                {{/each}}
            </script>

            <script type="text/template7" id="templateBranchListNull">
                <div class="content-null">
                    <h1><i class="fa fa-building" aria-hidden="true"></i></h1>
                    <br>
                    <h3>請先在上方輸入店名查詢</h3>
                </div>
            </script>

        </div>


    </div>
</div>