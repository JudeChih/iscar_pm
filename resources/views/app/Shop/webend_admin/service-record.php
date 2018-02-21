<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding service-record-left">
                <a href="shop-records" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding service-record-title">排隊紀錄</div>
            <div class="right">

            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="service-record">
            <!-- 內容 -->
            <div class="page-content animated fadeIn service-record">

            </div>


            <script type="text/template7" id="templateBranchServiceBlock">
                {{#each serviceque_array}}
                <div class="subTitle">
                    {{date}}
                </div>
                <div class="list-block media-list severMainBlock">
                    <ul class="block-{{date}}">
                    </ul>
                </div>
                {{/each}}
            </script>

            <script type="text/template7" id="templateBranchServiceList">
                {{#each queue_array}}
                <li class="item-content">
                    <div class="item-media"><img data-src="{{ssd_picturepath}}" class="lazy" width="60" onerror='this.src="app/image/general_user.png"'></div>
                    <div class="item-inner">
                        <div class="item-title-row">
                            <div class="item-title">{{ssqd_title}}</div>
                        </div>
                        <div class="item-subtitle">
                            <div class="row">
                                <div class="col-100">姓名：<span>{{md_cname}}</span></div>
                                <div class="col-100">車款：<span>{{car_model}}</span></div>
                                <div class="col-100">服務狀態：<span>{{status}}</span></div>
                            </div>
                        </div>
                    </div>
                </li>
                {{/each}}
            </script>

            <script type="text/template7" id="templateBranchServiceNull">
                <div class="content-null">
                    <h1><i class="fa fa-file-text-o"></i></h1>
                    <br>
                    <h3>無排隊記錄</h3>
                </div>
            </script>

        </div>
    </div>
</div>