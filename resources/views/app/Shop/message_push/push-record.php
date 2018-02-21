<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding">
                <a href="message_push/message-main" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">推送紀錄</div>
            <div class="right noUse">
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="push-record">

            <!-- 內容 -->
            <div class="page-content push-record-content push-record-block animated fadeIn">


            </div>


            <div class="toolbar toolbar-bottom add animated fadeInUp">
                <div class="toolbar-inner">
                    <a>新增</a>
                </div>
            </div>


            <script type="text/template7" id="templatePushRecord">
                <div class="content-block">
                    <div class="row">
                        <div class="col-50 now_coin">{{now_coin}}</div>
                        <div class="col-50 today_pushed">{{today_pushed}}</div>
                    </div>
                </div>

                <div class="content-block">
                    <!-- Buttons row as tabs controller -->
                    <div class="buttons-row">
                        <!-- Link to 1st tab, active -->
                        <a href="#member" class="tab-link active button">{{member}}</a>
                        <!-- Link to 2nd tab -->
                        <a href="#not_member" class="tab-link button">{{not_member}}</a>
                    </div>
                </div>

                <!-- Tabs animated wrapper, required to switch tabs with transition -->
                <div class="tabs-animated-wrap">

                    <!-- Tabs, tabs wrapper -->
                    <div class="tabs">
                        <!-- Tab 1, active by default -->
                        <div id="member" class="tab infinite-scroll active animated fadeIn">
                            <div class="list-block">
                                <ul>



                                </ul>
                            </div>
                        </div>

                        <!-- Tab 2 -->
                        <div id="not_member" class="tab infinite-scroll animated fadeIn">
                            <div class="list-block">
                                <ul>



                                </ul>
                            </div>
                        </div>

                    </div>

                </div>
            </script>


            <script type="text/template7" id="templatePushRecordData">
                {{#each pushhistory_array}}
                <li class="item-content list-item animated flipInX active">
                    <div class="item-content">
                        <div class="item-media">
                            <img data-src="{{sapm_pushpic}}" class="lazy" width=120 onerror='this.src="../app/image/imgDefault.png"' />
                        </div>

                        <a href="message_push/message-info?type=info&sapm_id={{sapm_id}}" class="item-info">
                                <p><span class="note">內容 :</span><span class="title">{{sapm_pushcontent}}</span></p>
                                <p><span class="note">數量 :</span>{{sapm_objectamount}}</p>
                                <p><span class="note">日期 :</span>{{create_date}}</p>
                            </a>
                    </div>
                </li>
                {{/each}}
            </script>

            <script type="text/template7" id="templatePushRecordNull">
                <div class="content-null">
                    <h1><i class="fa fa-file-text-o" aria-hidden="true"></i></h1>
                    <br>
                    <h3>{{text}}</h3>
                </div>
            </script>

        </div>
    </div>
</div>