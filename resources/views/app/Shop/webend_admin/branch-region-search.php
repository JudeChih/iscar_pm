<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="branch-cooperative" class="branch-search-left link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">進階查詢</div>
            <div class="right">
                <a href="#" class="link icon-only open-login-screen">
                    <span class="kkicon icon-user"></span>
                </a>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="branch-region-search">

            <!-- 內容 -->
            <div class="page-content animated fadeIn">

                <div class="subTitle">店名查詢</div>
                <div class="row">
                    <div class="col-20">店名：</div>
                    <div class="col-80"><input class="sd_shopname" type="text" placeholder="請輸入關鍵字"/></div>
                </div>

                <div class="subTitle">類別查詢</div>
                <div class="row">
                    <div class="col-20">類別：</div>
                    <div class="col-80"><input class="spm_serno" type="text" placeholder="請選擇類別" readonly /></div>
                </div>

                <div class="subTitle">地區查詢(可複選)</div>
                <div class="list-block"><!-- 若每次只限制單一展開加上accordion-list class名稱  -->
                    <ul class="regionList">

                    </ul>
                </div>
            </div>

            <div class="toolbar toolbar-bottom search animated fadeInUp">
                <div class="toolbar-inner">
                    <span><i class="fa fa-search"></i>&nbsp;&nbsp;查詢</span>
                </div>
            </div>

            <script type="text/template7" id="templateBranchRegionAccordion">
                {{#each rlList}}
                <li class="accordion-item region-item accordion-{{rl_city_ename}}">
                    <a href="#" class="item-content item-link region-item-content">
                        <div class="item-inner">
                            <div class="item-title">{{rl_city}}</div>
                        </div>
                    </a>
                    <div class="accordion-item-content">
                        <div class="content-block">
                            <div class="block-list row {{rl_city_ename}}" style="padding-left: 5%;">

                            </div>
                        </div>
                    </div>
                </li>
                {{/each}}
            </script>

            <script type="text/template7" id="templateBranchRegionList">
                <div class="checkbox-item col-25">
                    <input type="checkbox" class="r{{rl_zip}}" id="r{{rl_serno}}" name="region" value="{{rl_zip}}" />
                    <label for="r{{rl_serno}}"><span></span>{{rl_district}}</label>
                </div>
            </script>


        </div>
    </div>
</div>