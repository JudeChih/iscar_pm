<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding">
                <a href="#" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">推送內容</div>
            <div class="right">

            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="message-info">

            <!-- 內容 -->
            <div class="page-content message-info-content animated fadeIn">

                
            </div>

            <div class="toolbar toolbar-bottom confirm animated fadeInUp">
                <div class="toolbar-inner">
                    <a>確認</a>
                </div>
            </div>
            
            <div class="toolbar toolbar-bottom tabbar animated fadeInUp">
                <div class="toolbar-inner">
                    <div class="row no-gutter">
                        <div class="col-50 cancel"><a href="message_push/push-record" class="link">放棄</a></div>
                        <div class="col-50 send"><a href="#" class="link">送出</a></div>
                    </div>
                </div>
            </div>


            <script type="text/template7" id="templateMessageInfo">
                
                <div class="push-img-block">
                    <img data-src="{{push_image}}" class="lazy" onerror='this.src="../app/image/imgDefault.png"' />
                    <div class="edit-img"><i class="fa fa-pencil" aria-hidden="true"></i></div>
                    <div class="noSetImg">*未設置圖片</div>
                </div>

                <div class="list-block">
                    <ul>
                        <li class="align-top push-message">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-commenting-o" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">{{pushed_info}}</div>
                                    <div class="item-input">
                                        <textarea id="push-message" class="resizable" placeholder="請輸入內容(上限100字)" rows="5" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top save_quick_msg">
                            <div class="item-content">
                                <div class="item-media noUse"></div>
                                <div class="item-inner">
                                    <!-- <div class="item-title label noUse"></div> -->
                                    <div class="item-input">
                                        <input type="checkbox"> &nbsp;將此次內容儲存為快選訊息
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top push-type-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-paper-plane" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">{{push_type}}</div>
                                    <div class="item-input">
                                        <input class="push-type" type="text" value="一般訊息" placeholder="請選擇推送類型" readonly>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top push-item-block">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-paper-plane" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">{{push_item}}</div>
                                    <div class="item-input">
                                        <input class="push-item" type="text" readonly>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top push-num">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-share" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">{{pushed_num}}</div>
                                    <div class="item-input">
                                        <input id="push-num" type="text" value="0" readonly>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top push-coin">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-usd" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">{{consumption_coin}}</div>
                                    <div class="item-input">
                                        <input id="push-coin" type="text" value="0" readonly>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="align-top push-readed">
                            <div class="item-content">
                                <div class="item-media"><i class="fa fa-eye" aria-hidden="true"></i></div>
                                <div class="item-inner">
                                    <div class="item-title label">{{readed_num}}</div>
                                    <div class="item-input">
                                        <input id="push-readed" type="text" value="0" readonly>
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