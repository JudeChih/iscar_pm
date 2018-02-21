<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding">
                <a href="message_push/push-record" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">選擇推送對象</div>
            <div class="right">

            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="select-member">

            <!-- 內容 -->
            <div class="page-content select-member-content animated fadeIn">

                <!--<div class="top-block">
                    <div class="row no-gutter">
                        <div class="col-80">會員總數 5 人</div>
                        <div class="col-20">
                            <div class="clickAll">全選</div>
                        </div>
                    </div>
                </div>

                <div class="list-block">
                    <ul>
                        <li>
                            <label class="label-checkbox item-content">

                                <div class="item-inner">
                                    <div class="row no-gutter">
                                        <div class="col-15">
                                            <img data-src="{{#if md_picturepath}}{{md_picturepath}}{{else}}../../../image/general_user.png{{/if}}" width=60 class="lazy" onerror='this.src="../../../image/general_user.png"' />
                                        </div>
                                        <div class="col-85">
                                            <div>王大明</div>
                                        </div>
                                    </div>
                                </div>
                                <input type="checkbox" name="my-checkbox" value="Books">
                                <div class="item-media">
                                    <i class="icon icon-form-checkbox"></i>
                                </div>
                            </label>
                        </li>

                    </ul>
                </div>-->


            </div>


            <div class="toolbar toolbar-bottom edit animated fadeInUp">
                <div class="toolbar-inner">
                    <a href="#">訊息編輯</a>
                </div>
            </div>



            <script type="text/template7" id="templateSelectMember">
                <div class="top-block">
                    <div class="row no-gutter">
                        <div class="col-80 total_member">{{total_member}}</div>
                        <div class="col-20">
                            <div class="clickAll">{{select_all}}</div>
                        </div>
                    </div>
                </div>

                <div class="list-block">
                    <ul>

                    </ul>
                </div>
            </script>

            <script type="text/template7" id="templateSelectMemberList">
                {{#each shop_member_array}}
                <li>
                    <label class="label-checkbox item-content">

                        <div class="item-inner">
                            <div class="row no-gutter">
                                <div class="col-15">
                                    <img data-src="{{#if ssd_picturepath}}{{ssd_picturepath}}{{else}}../app/image/general_user.png{{/if}}" width=60 class="lazy" onerror='this.src="../app/image/general_user.png"' />
                                </div>
                                <div class="col-85">
                                    <div>{{md_cname}}</div>
                                </div>
                            </div>
                        </div>
                        <input type="checkbox" name="md-checkbox" value="{{md_id}}">
                        <div class="item-media">
                            <i class="icon icon-form-checkbox"></i>
                        </div>

                    </label>
                </li>
                {{/each}}
            </script>

            <script type="text/template7" id="templateSelectMemberNull">
                <div class="content-null">
                    <h1><i class="fa fa-users" aria-hidden="true"></i></h1>
                    <br>
                    <h3>{{text}}</h3>
                </div>
            </script>

        </div>
    </div>
</div>