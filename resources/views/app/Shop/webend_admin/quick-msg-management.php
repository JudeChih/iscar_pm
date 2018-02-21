<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding">
                <a href="message_push/message-main" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">快選訊息管理</div>
            <div class="right noUse">
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="quick-msg-management">
            <!-- 內容 -->
            <div class="page-content quick-msg-management-content animated fadeIn">
                <div class="grid">
                    <div class="grid-sizer grid-item">
                    </div>
                    <!-- <div class="grid-item">
                        <div class="card animated zoomIn">
                            <div class="card-content">
                                <div class="card-content-inner">1.Card with header and footer. Card header is used to display card title and footer for some additional information or for custom actions.</div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-50 edit-msg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></div>
                                    <div class="col-50 del-msg"><i class="fa fa-trash" aria-hidden="true"></i></div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="toolbar toolbar-bottom add-msg animated fadeInUp">
                <div class="toolbar-inner">
                    <a>新增</a>
                </div>
            </div>
            <script type="text/template7" id="templateQuickMsgList">
            {{#each canned_message_list}}
            <div class="grid-item animated zoomIn">
            <div class="card">
                <div class="card-content">
                    <div class="card-content-inner" cmsg_serno="{{cmsg_serno}}">{{cmsg_content_show}}<textarea style="display:none;">{{cmsg_content}}</textarea></div>
                </div>
                <div class="card-footer">
                    <div class="row no-gutter">
                        <div class="col-50 edit-msg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></div>
                        <div class="col-50 del-msg"><i class="fa fa-trash" aria-hidden="true"></i></div>
                    </div>
                </div>
            </div>
            </div>
            {{/each}}
            </script>
            <script type="text/template7" id="templateQuickMsgItem">
            <div class="grid-item animated zoomIn">
            <div class="card">
                <div class="card-content">
                    <div class="card-content-inner" cmsg_serno="{{cmsg_serno}}">{{cmsg_content_show}}<textarea style="display:none;">{{cmsg_content}}</textarea></div>
                </div>
                <div class="card-footer">
                    <div class="row no-gutter">
                        <div class="col-50 edit-msg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></div>
                        <div class="col-50 del-msg"><i class="fa fa-trash" aria-hidden="true"></i></div>
                    </div>
                </div>
            </div>
            </div>
            </script>
            <script type="text/template7" id="templateQuickMsgListNull">
            <div class="content-null">
                <h1><i class="fa fa-file-text-o" aria-hidden="true"></i></h1>
                <br>
                <h3>{{text}}</h3>
            </div>
            </script>
        </div>
    </div>
</div>