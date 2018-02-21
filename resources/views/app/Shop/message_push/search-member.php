<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding">
                <a href="message_push/push-record" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">篩選推送對象</div>
            <div class="right">

            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="search-member">

            <!-- 內容 -->
            <div class="page-content search-member-content animated fadeIn">

                <!--<div class="list-block">
                    <ul>
                        <li class="push-gender">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-venus-mars" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">性別</div>
                                    <div class="item-input">
                                        <input type="text" placeholder="請選擇性別" readonly class="gender">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="push-age">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-child" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">年齡</div>
                                    <div class="item-input">
                                        <input type="text" placeholder="請輸入年齡" readonly class="age">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="push-region">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-map-marker"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">所在區域</div>
                                    <div class="item-input">
                                        <input type="text" placeholder="請選擇地區" readonly class="location">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="noUse">

                        </li>
                    </ul>
                </div>-->
            </div>

            <div class="toolbar toolbar-bottom search animated fadeInUp">
                <div class="toolbar-inner">
                    <a href="#">查詢</a>
                </div>
            </div>


            <script type="text/template7" id="templateSearchMember">
                <div class="list-block">
                    <ul>
                        <li class="push-gender">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-venus-mars" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">{{gender}}</div>
                                    <div class="item-input">
                                        <input type="text" placeholder="{{input_gender}}" readonly class="gender">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="push-age">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-child" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">{{age}}</div>
                                    <div class="item-input">
                                        <input type="text" placeholder="{{input_age}}" readonly class="age">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="push-region">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-map-marker"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">{{region}}</div>
                                    <div class="item-input">
                                        <textarea type="text" placeholder="{{input_region}}" readonly class="location"></textarea>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="noUse">

                        </li>
                    </ul>
                </div>
            </script>

        </div>
    </div>
</div>