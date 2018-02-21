<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding relatives-list-left">
                <a class="link icon-only">
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
        <div class="page" data-page="relatives-list">

            <!-- 內容 -->
            <div class="page-content relatives-list-content animated fadeIn">




            </div>


            <!-- Floating Action Button -->
            <!-- <a href="#" class="floating-button animated zoomIn add">
                +
            </a> -->

            <div class="toolbar toolbar-bottom animated fadeInUp">
                <div class="toolbar-inner">
                    <div class="row no-gutter">
                        <div class="col-50 add-blessed-list"><a href="" class="link">加入被祈福清單</a></div>
                        <div class="col-50 delete-relatives" style="border: 0;"><a href="" class="link">刪除</a></div>
                    </div>
                </div>
            </div>



            <script type="text/template7" id="templateRelative">
                <div class="top-block">
                    <div class="row no-gutter">
                        <div class="col-80 relative_sum">親屬總數 <span>{{relative_sum}}</span> 人</div>
                        <div class="col-20">
                            <div class="clickAll">全選</div>
                        </div>
                    </div>
                </div>

                <div class="list-block relative-list">
                    <ul>

                    </ul>
                </div>

                <div class="add-relatives">添加親屬</div>
            </script>

            <script type="text/template7" id="templateRelativeList">
                {{#each relativelist}}
                <li>
                            <label class="label-checkbox item-content">

                                <div class="item-inner">
                                    <div class="row no-gutter">
                                        <div class="col-15">
                                            <div>姓名:</div>
                                        </div>
                                        <div class="col-35">
                                            <div>{{tpr_name}}</div>
                                        </div>
                                        <div class="col-15">
                                            <div>關係:</div>
                                        </div>
                                        <div class="col-25">
                                            <div>{{tpr_title}}</div>
                                        </div>
                                        <div class="col-10 edit-relative" tpr_serno="{{tpr_serno}}">
                                            <div><i class="fa fa-pencil" aria-hidden="true"></i></div>
                                        </div>
                                    </div>
                                </div>
                                {{#if tpr_show}}
                                <input type="checkbox" name="relative-checkbox" value="{{tpr_serno}}">
                                {{else}}
                                <input type="checkbox" name="relative-checkbox" checked="true" value="{{tpr_serno}}">
                                {{/if}}
                                <div class="item-media">
                                    <i class="icon icon-form-checkbox"></i>
                                </div>
                            </label>
                        </li>

                {{/each}}
            </script>

            <script type="text/template7" id="templateRelativeNull">
                <div class="content-null">
                    <h1><i class="fa fa-users" aria-hidden="true"></i></h1>
                    <br>
                    <h3>{{text}}</h3>
                </div>
                <div class="add-relatives">添加親屬</div>
            </script>

        </div>
    </div>
</div>