$(function(){
//branchInfoInit
var shoppoint = $('input[name=shoppoint]').val();
if(shoppoint != null && shoppoint != undefined){
    commonTools._dataStorage('shoppoint',shoppoint);
}
if (sd_havebind === '1') {
    $('.toolbar-bottom.tabbar').css('display', 'block');
} else {
    $('.toolbar-bottom.tabbar').css('display', 'none');
}

if (sd_questiontotalaverage == '' || sd_questiontotalaverage == '0.0') {
    $('.average').html('暫無評分');
    $('.fa.fa-star').remove();
    $('.count').html(0);
}else{
    sd_questiontotalaverage = sd_questiontotalaverage.replace(/&quot;/g, '\"');
    sd_questiontotalaverage = JSON.parse(sd_questiontotalaverage);
    $('.average').html(sd_questiontotalaverage.questiontotalaverage.average);
    $('.count').html(sd_questiontotalaverage.questiontotalaverage.count);

    //數字累加特效
    var options = {
        useEasing: true,
        useGrouping: true,
        separator: ',',
        decimal: '.',
        prefix: '',
        suffix: ''
    };
    var average = new CountUp(".average", 0, parseFloat(sd_questiontotalaverage.questiontotalaverage.average), 0, 1, options);
    average.start();
    var count = new CountUp(".count", 0, parseInt(sd_questiontotalaverage.questiontotalaverage.count), 0, 1, options);
    count.start();

}

var category = [];
var avg = [];

if (sd_questionnaireresult) {
    sd_questionnaireresult = sd_questionnaireresult.replace(/&quot;/g, '\"');
    sd_questionnaireresult = JSON.parse(sd_questionnaireresult);

    for (var i in sd_questionnaireresult.questionnaireresult) {
        category.push(stringObj.shop.sd_question_category_array[sd_questionnaireresult.questionnaireresult[i].sd_question_category]);
        avg.push(parseFloat(sd_questionnaireresult.questionnaireresult[i].avg) * 20);
    }
} else {
    category = ["", "", "", "", "", "", "", ""];
    avg = ["", "", "", "", "", "", "", ""];
}

var radarChartData = {
    labels: category, //["服務", "品質", "專業度", "準確性", "互動性", "整體滿意度", "再次消費", "願意推薦"],
    datasets: [{
            label: "TestTest",
            fillColor: "rgba(20,120,220,0.4)",
            strokeColor: "rgba(20,120,220,1)",
            pointColor: "rgba(20,120,220,1)",
            pointStrokeColor: "rgba(255,255,255,.5)",
            pointHighlightFill: "#000",
            pointHighlightStroke: "rgba(20,20,220,1)",
            data: avg //[85, 89, 70, 31, 86, 66, 76, 46]
        }]
};
window.myRadar = new Chart(document.getElementById("canvas").getContext("2d")).Radar(radarChartData, {
    responsive: false,
    angleLineColor: "rgba(200,200,200,.7)",
    pointLabelFontColor: "green",
    pointLabelFontFamily: "'Microsoft JhengHei'",
    pointLabelFontSize: 18,
    scaleLineColor: "rgba(200, 200, 200, 0.9)"
});

if (browser_width < 992) {
    $('.page:not(.cached) #canvas').css('width', '70%');
    $('.page:not(.cached) #canvas').css('margin-left', '15%');
} else {
    $('.page:not(.cached) #canvas').css('width', '35%');
    $('.page:not(.cached) #canvas').css('margin-left', '32.5%');
}

// //監聽裝置畫面大小
$(window).on("resize", function () {
    browser_width = $(window).width();
    if (browser_width < 992) {
        $('.page:not(.cached) #canvas').css('width', '70%');
        $('.page:not(.cached) #canvas').css('margin-left', '15%');
    } else {
        $('.page:not(.cached) #canvas').css('width', '35%');
        $('.page:not(.cached) #canvas').css('margin-left', '32.5%');
    }
});
$('.page:not(.cached) #canvas').css('height', 'auto');









// 左上角按鈕設定
$('.shopdata-comment-left a').on('click',function(){
    history.go(-1);
    // window.location = document.referrer;
    // console.log(document.referrer.pathname);
})

// toolbar a連結設定
$('.toolbar a').on('click',function(){
    if($(this).data('href')){
        location.replace($(this).data('href'));
    }
})

$(window).load(function() {
    changeDateMark();
	Lazy();
    //美化scroll bar
    if ($(window).width() >= 992){
        $(".page-content").niceScroll({
            cursorcolor: "rgba(100,100,100,.9)",
            cursoropacitymin: 0,
            cursorborder: "1px solid #000"
        });
    }
});

// 修改活動時間日期的顯示格式 - 換成 /
function changeDateMark(){
    $('.dateMark').each(function(){
        var date = $(this).text();
        date = date.replace(/-/ig, '/');
        $(this).text(date);
    })
}

// 圖片懶加載
function Lazy(){
    if($('.lazy').length == 0){
        // $('.overlay_setting').css('display','none');
    }else{
        $('.lazy').Lazy({
            bind: "event",
            delay: 0,
            onFinishedAll: function() {
                // $('.overlay_setting').css('display','none');
            },
            afterLoad: function(element) {
            },
            beforeLoad: function(element) {
                if(element.prop('data-src') == ''){
                    element.prop('data-src','../app/image/general_user.png');
                }
            },
            onError: function(element) {
                element.prop('src','../app/image/general_user.png');
            }
        });
    }
}

})

