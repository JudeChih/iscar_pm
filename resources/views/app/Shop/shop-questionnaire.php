<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left sliding">
                <a href="#" class="link icon-only">
                    <span class="icon-chevron-left"></span>
                </a>
            </div>
            <div class="center sliding coupon-score-title">活動評論</div>
            <div class="right sliding">

            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="shop-questionnaire">

            <!-- 內容 -->
            <div class="page-content animated fadeIn">
                               
                <img data-src="app/image/imgDefault.png" class="lazy shop-coupon-img" onerror='this.src="app/image/imgDefault.png"' /> 

                <div class="shopcoupon_name"></div>
                <div class="subTitle">
                    車輛款式
                </div>

                <div class="row no-gutter" style="padding: 5%;">
                    <div class="col-20">款式：</div>
                    <div class="col-80">
                        <input type="text" class="carModels" placeholder="請選擇廠牌型號" readonly/>
                    </div>
                </div>

                <div class="subTitle" style="margin-bottom: 0;">
                    評價
                </div>

                <div class="question-block">

                </div>


                <div class="subTitle">
                    留言
                </div>

                <div class="align-top">
                    <textarea class="sqna_message" rows="4" placeholder="請輸入評論(限70字以內)" onkeyup="this.value = this.value.slice(0, 70)"></textarea>
                </div>



            </div>


            <div class="toolbar toolbar-bottom send animated fadeInUp">
                <div class="toolbar-inner">
                    <a>發送評論</a>
                </div>
            </div>
            
            <script type="text/template7" id="templateQuestionnaire">                
                {{#each questionnaire_content}}
                <div class="question row">
                    <div class="col-5"><i class="fa fa-certificate" aria-hidden="true"></i></div>
                    <div class="col-95">{{sqn_question}}</div>
                </div>
                <div class="ans-block">
                    <i class="fa fa-thumbs-o-down"></i>&nbsp;&nbsp;
                    <input type="radio" name="point-{{sqn_serno}}" value='{"sqn_serno":"{{sqn_serno}}","sd_question_category":"{{sd_question_category}}","sqn_question":"{{sqn_question}}","answer":"1"}'>
                    <label>&nbsp;</label>
                    <input type="radio" name="point-{{sqn_serno}}" value='{"sqn_serno":"{{sqn_serno}}","sd_question_category":"{{sd_question_category}}","sqn_question":"{{sqn_question}}","answer":"2"}'>
                    <label>&nbsp;</label>
                    <input type="radio" name="point-{{sqn_serno}}" value='{"sqn_serno":"{{sqn_serno}}","sd_question_category":"{{sd_question_category}}","sqn_question":"{{sqn_question}}","answer":"3"}'>
                    <label>&nbsp;</label>
                    <input type="radio" name="point-{{sqn_serno}}" value='{"sqn_serno":"{{sqn_serno}}","sd_question_category":"{{sd_question_category}}","sqn_question":"{{sqn_question}}","answer":"4"}'>
                    <label>&nbsp;</label>
                    <input type="radio" name="point-{{sqn_serno}}" value='{"sqn_serno":"{{sqn_serno}}","sd_question_category":"{{sd_question_category}}","sqn_question":"{{sqn_question}}","answer":"5"}'>
                    <label>&nbsp;</label>
                    &nbsp;<i class="fa fa-thumbs-o-up"></i>
                </div>
                {{/each}}
            </script>


        </div>
    </div>
</div>