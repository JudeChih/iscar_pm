<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="#" class="link icon-only open-popup" data-popup=".shop-menu">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div>
            <div class="center sliding">評價管理</div>
            <div class="right">
                <div class="iscar_member_icon" from="Shop"></div>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="questionnaire-management">

            <!-- 內容 -->
            <div class="page-content animated fadeIn">
                <div class="canvas-block">
                    <canvas id="mCanvas" width="300" height="300"></canvas>

                    <div class="row evaluate-info">
                        <div class="col-25 note">綜合評價：</div>
                        <div class="col-25"><span class="average"></span>&nbsp;<i class="fa fa-star" style="color: goldenrod;"></i></div>
                        <div class="col-25 note">投票人數：</div>
                        <div class="col-25"><span class="count"></span>&nbsp;<span>人</span></div>
                    </div>
                </div>
                <div class="questionnaire-list">
                    <div class="list-null"></div>
                </div>

            </div>

            <script type="text/template7" id="templateQuestionnaireList">
                <div class="subtitle">
                    評論內容
                </div>
                <div class="list-block">
                    <ul>
                        {{#each activemessage}}
                        <li class="item-content list-item swipeout animated flipInX">
                            <div class="swipeout-content item-content">
                                <div class="item-media">
                                    <img data-src="{{ssd_picturepath}}" class="lazy" width=80 onerror='this.src="../app/image/general_user.png"' />
                                </div>
                                <div class="item-inner item-info">
                                    <div class="row no-gutter">
                                        <div class="col-85">
                                            {{sqna_message}}
                                        </div>
                                        <div class="col-15">
                                            {{#if sqnr_id}} {{else}}
                                            <a href="#" class="reply" onclick="branchReply('{{sqna_id}}','{{sqnr_id}}','{{sqnr_responsemessage}}', '0')">回覆</a> {{/if}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        {{#if sqnr_id}}
                        <li class="item-content list-item animated flipInX swipeout-{{sqnr_id}}">
                            <div class="item-content swipeout-reply">
                                <div class="item-media">
                                    <img data-src="../app/image/branch_user.png" class="lazy" width=80 onerror='this.src="../app/image/branch_user.png"' />
                                </div>
                                <div class="item-inner item-info">
                                    <div class="row no-gutter">
                                        <div class="col-70">
                                            {{sqnr_responsemessage}}
                                        </div>
                                        <div class="col-30">
                                            <div class="row no-gutter">
                                                <div class="col-50">
                                                    <a href="#" class="reply_update" onclick="branchReply('{{sqna_id}}','{{sqnr_id}}','{{sqnr_responsemessage}}', '1')">更新</a>
                                                </div>
                                                <div class="col-50">
                                                    <a href="#" class="swipeout-delete" onclick="branchReply('{{sqna_id}}','{{sqnr_id}}','{{sqnr_responsemessage}}', '2')">刪除</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        {{/if}} {{/each}}
                    </ul>
                </div>
            </script>

        </div>
    </div>
</div>