<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <!-- <div class="left">
                <a href="#" class="link icon-only open-popup" data-popup=".shop-menu">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div> -->
            <div class="left">
                <a class="link icon-only map-left">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding menu-title">汽車特店地圖</div>
            <div class="right">
                <div class="iscar_member_login iscar_member_icon" from="Shop"></div>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="map">
            <!-- 內容 -->
            <div class="page-content map-content">
                <div class="type-block row no-gutter">
                    <!-- <div class="col-25 markerType markerType1" onclick="setMapMarker('1')"><i class="fa fa-map-marker" style="color:#740ee6;"></i>&nbsp;類別A</div> -->
                    <!-- <div class="col-25 markerType active" type=2><i class="fa fa-map-marker" style="color:#740ee6;"></i>&nbsp;{{name}}</div>
                    <div class="col-25 markerType" type=3><i class="fa fa-map-marker" style="color:#0db418;"></i>&nbsp;類別B</div>
                    <div class="col-25 markerType" type=4><i class="fa fa-map-marker" style="color:#00b7e9;"></i>&nbsp;類別C</div>
                    <div class="col-25 markerType" type=5><i class="fa fa-map-marker" style="color:#F26531; border-right: 0px;"></i>&nbsp;全部</div> -->
                </div>
                <div class="search-btn animated fadeInDown"><a>搜尋此區域</a></div>
                <div id="full_map" class="full_map"></div>
                <div class="set_location"><i class="fa fa-dot-circle-o" aria-hidden="true"></i></div>
            </div>
            <script type="text/template7" id="templateMapType">
            {{#each list}}
            <div class="col-50 markerType" type={{spm_serno}}>{{name}}</div>
            {{/each}}
            </script>
        </div>
    </div>
</div>