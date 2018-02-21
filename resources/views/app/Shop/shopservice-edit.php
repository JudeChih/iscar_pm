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
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="shopservice-edit">

            <!-- 內容 -->
            <div class="page-content animated fadeIn">
                
                <div class="shopservice-img-block" style="position: relative;">
                    <img data-src="app/image/imgDefault.png" class="lazy shopservice-img" onerror='this.src="app/image/imgDefault.png"' />
                    <div class="edit-img"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                </div>
                
                <div class="noSetImg">* 未設置圖片</div>

                <div class="shopservice-info">
                    
                    <div class="list-block">
                        <ul>
                            <!-- Text inputs -->
                            <li class="align-top shopservice-name">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-circle" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">服務名稱</div>
                                        <div class="item-input">
                                            <input id="shopservice-name" type="text" placeholder="名稱">
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="shopservice-require-time">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-clock-o"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">所需工時</div>
                                        <div class="item-input">
                                            <input type="text" placeholder="01 小時 30 分鐘" readonly id="shopservice-require-time">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top shopservice-price">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-money"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">費用(NTD)</div>
                                        <div class="item-input">
                                            <input id="shopservice-price" type="number" placeholder="金額">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top shopservice-maxnum">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-users" aria-hidden="true"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">最大排隊數</div>
                                        <div class="item-input">
                                            <input id="shopservice-maxnum" type="number" placeholder="人數">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="align-top shopservice-context">
                                <div class="item-content">
                                    <div class="item-media"><i class="fa fa-file-text"></i></div>
                                    <div class="item-inner">
                                        <div class="item-title label">簡介內容</div>
                                        <div class="item-input">
                                            <textarea id="shopservice-context" class="resizable" placeholder="內容"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="noUse" style="display:none;">

                            </li>
                        </ul>
                    </div>



                </div>

            </div>


            <div class="toolbar toolbar-bottom add animated fadeInUp">
                <div class="toolbar-inner">
                    <a href="#">新增</a>
                </div>
            </div>
            
            <div class="toolbar toolbar-bottom edit animated fadeInUp">
                <div class="toolbar-inner">
                    <a href="#">修改</a>
                </div>
            </div>



            <script type="text/template7" id="templateShopServiceEdit">

            </script>

        </div>
    </div>
</div>