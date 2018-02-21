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
// 左上角按鈕設定
$('.shopcoupon-list-left a').on('click',function(){
    commonTools.backToPrePage();
})

// toolbar a連結設定
$('.toolbar a').on('click',function(){
    if($(this).data('href')){
        location.replace($(this).data('href'));
    }
})

$(window).resize(function() {
    chengeWidth();
});

$(window).load(function() {
    chengeWidth();
    changeDateMark();
    setFavoriteImage();
	Lazy();
    //奇怪
    //美化scroll bar
    if ($(window).width() >= 992){
        setScrollBar();
    }
});

// 動態變更sw_width底下a的寬度
chengeWidth = function (){
    var wdth = $('#sw_width').width();
    var wdth1 = wdth-44;
    $('.swiper_num').each(function(){
        if($(this).index() == 0){
            $(this).css('width','44');
        }else if($(this).index() == 1){
            $(this).css('width',wdth1-(wdth/2));
        }else{
            $(this).css('width',wdth/4);
        }
    })
}

// 圖片懶加載
Lazy = function (){

    if($('.lazy').length == 0){
        $('.lazy_indicator').remove();
        $('.lazy_overlay').remove();
    }else{
        $('.lazy').Lazy({
                bind: "event",
                delay: 0
            ,
            onFinishedAll: function() {
                $('.lazy_indicator').remove();
                $('.lazy_overlay').remove();
            },
            afterLoad: function(element) {

            },
            onError: function(element) {
                element.prop('src','../app/image/imgDefault.png');
            }
        });
    }
}

// 修改活動時間日期的顯示格式 - 換成 /
changeDateMark = function (){
    $('.dateMark').each(function(){
        var date = $(this).text();
        date = date.replace(/-/ig, '/');
        $(this).text(date);
    })
}

// 設置訂閱圖片
setFavoriteImage = function (){
    var favoriteList = commonTools._dataStorage(commonTools.storage.favorite);
    if (favoriteList) {
        for (var i in favoriteList.shopcouponList) {
            $('.favorite-' + favoriteList.shopcouponList[i].ubm_objectid).html('<i class="fa fa-star"></i>');
        }
    }
}

})

