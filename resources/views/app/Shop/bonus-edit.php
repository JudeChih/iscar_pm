<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding">
                <a href="#" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding"></div>
            <div class="right">

            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="bonus-edit">

            <!-- 內容 -->
            <div class="page-content animated fadeIn">

                <div class="list-block bonus-edit-block">
                    <ul>
                    
                    </ul>
                </div>


            </div>


            <div class="toolbar toolbar-bottom send animated fadeInUp">
                <div class="toolbar-inner">
                    <a>修改</a>
                </div>
            </div>


            <script type="text/template7" id="templateBonusEdit">
                <li class="bouns-name">
                    <div class="item-content">
                        <div class="item-media"><i class="fa fa-tag" aria-hidden="true"></i></div>
                        <div class="item-inner">
                            <div class="item-title label">{{item_name}}</div>
                            <div class="item-input">
                                <input id="bouns-name" class="bouns_name" type="text" placeholder="{{item_name}}" maxlength="20">
                            </div>
                        </div>
                    </div>
                </li>
                <li class="bouns-point">
                    <div class="item-content">
                        <div class="item-media"><i class="fa fa-money" aria-hidden="true"></i></div>
                        <div class="item-inner">
                            <div class="item-title label">{{bonus_point}}</div>
                            <div class="item-input">
                                <input id="bouns-point" class="bouns_point" type="number" placeholder="{{bonus_point}}" maxlength="7">
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item-content">
                        <div class="item-media"><i class="fa fa-toggle-on" aria-hidden="true"></i></div>
                        <div class="item-inner">
                            <div class="item-title label">{{bonus_status}}</div>
                            <div class="item-input">
                                <label class="label-switch">
                                    <input class="bouns-status" type="checkbox">
                                    <div class="checkbox"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="noUse" style="display:none;">

                </li>
            </script>

        </div>
    </div>
</div>