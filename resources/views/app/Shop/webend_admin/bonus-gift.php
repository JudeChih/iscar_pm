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
        <div class="page" data-page="bonus-gift">

            <!-- 內容 -->
            <div class="page-content animated fadeIn">
                
                <div class="user-info">
                    
                </div>

                <div class="list-block bonus-gift-block">
                    <ul>
                    
                    
                    
                    </ul>
                </div>

            </div>


            <div class="toolbar toolbar-bottom send animated fadeInUp">
                <div class="toolbar-inner">
                    <a>贈送</a>
                </div>
            </div>
            
            
            <script type="text/template7" id="templateShopUseInfo">
                <img data-src="{{#if ssd_picturepath}}{{md_picturepath}}{{else}}../app/image/general_user.png{{/if}}" width=120 class="user-icon lazy" onerror='this.src="http://125.227.129.115/app/user_icon/general_user.png"' />
                <div class="user-name">{{md_cname}}</div>
            </script>


            <script type="text/template7" id="templateBonusGift">
                <li class="cost-block">
                    <div class="item-content">
                        <!--<div class="item-media"><i class="fa fa-tag" aria-hidden="true"></i></div>-->
                        <div class="item-inner">
                            <div class="item-title label">{{cost}}</div>
                            <div class="item-input">
                                <input class="cost" type="number" placeholder="{{input_cost}}" maxlength="8" readonly>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="bouns-point">
                    <div class="item-content">
                        <!--<div class="item-media"><i class="fa fa-money" aria-hidden="true"></i></div>-->
                        <div class="item-inner">
                            <div class="item-title label">{{bonus_point}}</div>
                            <div class="item-input">
                                <input class="bouns_point" type="number" placeholder="{{input_bonus_point}}" maxlength="8" readonly>
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