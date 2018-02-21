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
        <div class="page" data-page="bonus-management">
            <!-- 內容 -->
            <div class="page-content animated fadeIn">

                <div class="list-block media-list bonus-list">
                    <ul>

                    </ul>
                </div>

            </div>


            <!-- Floating Action Button -->
            <a href="shop/bonus-edit" class="floating-button animated zoomIn">
                +
            </a>



            <script type="text/template7" id="templateBonusList">
                <li class="item-content">
                    <div class="item-inner">
                        <div class="item-subtitle">
                            <div class="row no-gutter">
                                <div class="col-85">
                                    <div class="row">
                                        <div class="col-100">{{sbgi_itemname}}</div>
                                        <div class="col-40">狀態：<span>{{sbgi_effective}}</span></div>
                                        <div class="col-60">點數：<span>{{sbgi_itemamount}}P</span></div>
                                    </div>
                                </div>
                                <div class="col-15" sbgi_id="{{sbgi_id}}">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </li>
            </script>


            <script type="text/template7" id="templateBonusListNull">
                <div class="content-null">
                    <h1><i class="fa fa-file-text-o" aria-hidden="true"></i></h1>
                    <br>
                    <h3>{{text}}</h3>
                </div>
            </script>

        </div>
    </div>
</div>