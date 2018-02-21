<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="#" class="link icon-only open-popup" data-popup=".shop-menu">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div>
            <div class="center sliding"></div>
            <div class="right">
                <div class="iscar_member_login iscar_member_icon" from="Shop"></div>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="bonus-record">
            <!-- 內容 -->
            <div class="page-content animated fadeIn">

                <div class="list-block media-list bonus-record-list">
                    <ul>

                    </ul>
                </div>

            </div>



            <script type="text/template7" id="templateBonusRecordList">
            {{#each shopbonus}}
                <li class="item-content">
                    <div class="item-media">
                        <img data-src="{{sd_shopphotopath}}" width=100 class="lazy" onerror='this.src="app/image/imgDefault.png"' />
                    </div>
                    <div class="item-inner">
                        <div class="item-subtitle">
                            <!--<div class="row no-gutter">-->
                                <!--<div class="col-85">-->
                                    <div class="row">
                                        <div class="col-100">{{sd_shopname}}</div>
                                        <div class="col-60">{{point}}：<span>{{sbs_end}}P</span></div>
                                    </div>
                                <!--</div>-->
                                <!--<div class="col-15" sd_id="{{sd_id}}">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </div>-->
                            <!--</div>-->

                        </div>
                    </div>
                </li>
                {{/each}}
            </script>


            <script type="text/template7" id="templateBonusRecordNull">
                <div class="content-null">
                    <h1><i class="fa fa-file-text-o" aria-hidden="true"></i></h1>
                    <br>
                    <h3>{{text}}</h3>
                </div>
            </script>

        </div>
    </div>
</div>