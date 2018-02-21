<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="shopservice-management" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding">今日過號</div>
            <div class="right noUse">
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="shopservice-pass">

            <!-- 內容 -->
            <div class="page-content animated fadeIn">

                <div class="list-block">
                    <ul>

                    </ul>
                </div>

            </div>

            <script type="text/template7" id="templateQueuePassedList">
                {{#each queue_array}} {{#if ssqq_usestatus}}
                <li class="item-content list-item swipeout animated flipInX active">
                    <div class="swipeout-content item-content">
                        <div class="item-media">
                            <img data-src="{{ssd_picturepath}}" class="lazy" width="80" onerror='this.src="../app/image/general_user.png"'>
                        </div>
                        <!--<a href="#" class="item-info">
                            <p class="title">{{ssqd_title}}</p>
                            <p><span class="note">姓名 :</span>{{md_cname}}</p>
                            <p><span class="note">車款 :</span>{{car_model}}</p>
                        </a>-->
                        <div class="item-inner item-info">
                            <div class="row no-gutter">

                                <div class="col-80">
                                    <div class="row no-gutter">
                                        <div class="col-100 title">{{ssqd_title}}</div>
                                        <div class="col-100 note">姓名：<span>{{md_cname}}</span></div>
                                        <div class="col-100 note">車款：<span>{{car_model}}</span></div>
                                    </div>
                                </div>

                                <div class="col-20">
                                    <div class="call-num" onclick="serviceCall('overCall','{{ssqq_id}}')">叫號</div>                                    
                                </div>


                            </div>
                            
                            <div class="queserno">{{ssqq_queserno}}</div>
                        </div>
                        
                    </div>
                    <!--<div class="swipeout-actions-right">
                        <a href="#" class="call-num" onclick="serviceCall('overCall','{{ssqq_id}}')">叫號</a>
                    </div>-->
                </li>
                {{/if}} {{/each}}
            </script>

            <script type="text/template7" id="templateQueuePassedNull">
                <div class="content-null">
                    <h1><i class="fa fa-file-text-o"></i></h1>
                    <br>
                    <h3>暫無記錄</h3>
                </div>
            </script>

        </div>
    </div>
</div>