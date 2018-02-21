$(function(){
var main = commonTools._dataCookies(commonTools.storage.main) || {};
var bc_boolean = true;

var a = document.referrer;
if(a.indexOf('branch-info') == -1 && a.indexOf('shopdata-comment') == -1 && a.indexOf('shopcoupon-list') == -1){
    Cookies.remove('offset');
}

//設置storage
commonTools._dataStorage(commonTools.storage.listtype,listtype);
commonTools._dataStorage(commonTools.storage.cate,cate);
commonTools._dataStorage('page_from',null);

// 列表資料由GPS遠近作排列，需要先取到座標
// 並且如果是手機瀏覽的話，左側選單要拿掉登出的按鈕
if(Cookies.get('app_version')){
    webview.getLocation();
}else{
    ipLocation();
}
// 當前頁active設定
$('.subnavbar .blog-cate a').each(function (){
    var cate_num = $(this).data('cate');
    var cate_page = $('.branch-list-container').data('cate');
    if(cate_num == cate_page){
        $(this).addClass('active');
    }
})

////按鈕的設定
/////////////////////////////////////////////////////////////////////
$('.goToBranch').on('click',function(){
    $('.goToBranch').unbind('click');
    showIndicator();
    Cookies.remove('offset');
    var parent = $(this).parents('.start_id');
    var sd_id = parent.data('id');
    var setting = {
        "top": parent.position().top,
        "href": location.href,
        "leng": parent.index()+1,
        "amount": Math.ceil((parent.index()+1)/20) * 20
    }
    Cookies.set('offset',JSON.stringify(setting));
    location.href = "/pm/branch-info?sd_id="+sd_id;
})
$('.swiper_num').on('click',function(){
    commonTools._dataStorage(commonTools.storage.cate,$(this).data('cate'));
})
// 進階搜尋
$('.advanced_search').on('click',function(){
    var searchItem = {"spm_serno":1,"sd_shopname":"","sd_lat":"","sd_lng":"","sd_country":""}
    commonTools._dataStorage(commonTools.storage.search_result,searchItem);
    window.location.href = "/Shop#!/shop-search-result";
})
// 我的最愛
$('.btn_favorite').on('click',function(){
    if(main.sat){
        window.location.href = "/Shop#!/shop/favorite";
    }else{
        commonTools.setPopBox({
            title: stringObj.text.warn,
            text: stringObj.text.notLogin,
            buttons: [{
                    text: stringObj.text.cancel,
                    onClick: function () {
                        removeSomething();
                    }
                }, {
                    text: stringObj.text.login,
                    onClick: function () {
                        removeSomething();
                        webview.loginPage();
                    }
                }]
        });
    }
})
// 我要開店
$('#btn_openstore').on('click',function(){
    if(main.sat){
        window.location.href = "/Shop#!/application-shop";
    }else{
        commonTools.setPopBox({
            title: stringObj.text.warn,
            text: stringObj.text.notLogin,
            buttons: [{
                    text: stringObj.text.cancel,
                    onClick: function () {
                        removeSomething();
                    }
                }, {
                    text: stringObj.text.login,
                    onClick: function () {
                        removeSomething();
                        webview.loginPage();
                    }
                }]
        });
    }
    closeLeftPopup();
    $('.views').removeClass('blur');
})
// 左上三明治
$('.left .open-popup').on('click',function(){
    $('.popup.shop-menu').addClass('modal-in');
    $('.views').addClass('blur');
    $('body').append('<div class="popup-overlay modal-overlay-visible"></div>');
    // 增加監聽事件，點擊視窗以外的地方就關閉視窗
    $(document).mouseup(function(e){
        var _con1 = $('.popup.shop-menu');
        if(!_con1.is(e.target) && _con1.has(e.target).length === 0){
            closeLeftPopup();
            $('.views').removeClass('blur');
        }
    });
});
// 左上選單的地圖搜索按鈕
$('.btn_map').on('click',function(){
    window.location.href = "/Shop#!/map";
})
// 左上選單的插插圖示
$('.close-btn .close-popup').on('click',function(){
    closeLeftPopup();
    $('.views').removeClass('blur');
})
// 周邊商家
$('.around_search').unbind('click');
$('.around_search').on('click',function(){
    commonTools._dataStorage(commonTools.storage.search_result,null);
    commonTools._dataStorage('page_from','around_search');
    var search_result = {};
    var nowlat = '25.047908';
    var nowlng = '121.517115';
    if (Cookies.get('app_version') !== undefined) {
        webview.getLocation();
    } else {
        $.getJSON("http://ip-api.com/json/?callback=?", function(data) {
                search_result.spm_serno = 2;
                search_result.sd_lat = data.lat || '';
                search_result.sd_lng = data.lon || '';
                search_result.sd_shopname = '';
                search_result.sd_country = '';
                commonTools._dataStorage(commonTools.storage.search_result,search_result);
                window.location.href = "/Shop#!/around-search-result?from=around_search";
        });
    }
})
//////////////////////////////////按鈕end///////////////////////////////////////
// 頁面載入完畢以後要做的事情
window.onload = function(){
    setHotKey();
    setFavoriteImage();
    swiper();
    Lazy();

    //美化scroll bar
    if ($(window).width() >= 992){
        setScrollBar();
    }
}

// 監聽頁面大小
$(window).resize(function() {
    browser_width = $(window).width();
    browser_height = $(window).height();
    if (browser_width < 992) {
        $('.draggable-box').css('width', browser_width);
        $('.draggable-box').css('height', browser_height);
    } else {
        $('.draggable-box').css('width', $('body').width());
        $('.draggable-box').css('height', browser_height);

    }

});

// 設置"回主頁"的按鈕
setHotKey = function (){

    $('body').prepend('<div class="draggable-box"></div>');

    if (browser_width < 992) {
        $('.draggable-box').css('width', browser_width);
        $('.draggable-box').css('height', browser_height);
    } else {
        $('.draggable-box').css('width', $('body').width());
        $('.draggable-box').css('height', browser_height);
        // $('.draggable-box').css('margin', '0 auto');

    }
    // 移除掉user.js新增的區塊
    $('.draggable-block').remove();
    // 快捷鈕圖示
    $('#test-key').html('回主頁');
    // 快捷鈕設置可拖動
    if (isMobile.Android()) {
        $('.bc_box').draggable({
            containment: '.draggable-box',
            distance: 25,
            scroll: false,
            zIndex: 5500
        });
        var timeout, longtouch;
        longtouch = false;
        $('#test-key').bind('touchstart', function() {
            timeout = setTimeout(function() {
                longtouch = true;
            }, 200);
        }).bind('touchend', function() {
            if (!longtouch) {
                var mainSg = JSON.parse(Cookies.get(commonTools.storage.main)) || {};
                window.location = 'http://' + WEB_URL + '/Shortcut-menu/transform?user_info=' + encodeURIComponent(JSON.stringify(mainSg));
            }
            longtouch = false;
            clearTimeout(timeout);
        });
    }else{
        $('#test-key').click(function() {
            var mainSg = JSON.parse(Cookies.get(commonTools.storage.main)) || {};
            window.location = 'http://' + WEB_URL + '/Shortcut-menu/transform?user_info=' + encodeURIComponent(JSON.stringify(mainSg));
        });
        $('.bc_box').draggable({
            containment: '.draggable-box',
            distance: 25,
            scroll: false,
            zIndex: 5500
        });

    }
}

// 監聽scroll事件、並且下載下一批的資料
listenScroll = function (height){
    $('.page-content').scroll(function(){

        var tool_hei = $('#branch-cooperative .toolbar').height();
        var nav_hei = $('#branch-cooperative .navbar').height();
        var sub_wid = $('#branch-cooperative .subnavbar').height();
        var total_hei = height - $(this).scrollTop() + tool_hei + nav_hei + sub_wid - $(window).height();
        if(total_hei <= 10){
            //先解除滾動監聽事件
            $('.page-content').unbind('scroll');
            if(bc_boolean){
                bc_boolean = false;
                getNestData();
            }
        }else{
            bc_boolean = true;
        }
    })
}

// 關閉左側彈出框
closeLeftPopup = function (){
    $('.popup.shop-menu').addClass('modal-out',function(){
        $('.popup.shop-menu').removeClass('modal-in',function(){
            $('.popup.shop-menu').removeClass('modal-out');
        });
    });
    $('.popup-overlay.modal-overlay-visible').remove();
    $(document).unbind('mouseup');
}

// 圖片懶加載
Lazy = function (){
    if($('.lazy').length == 0){
        $('.lazy_indicator').remove();
        $('.lazy_overlay').remove();
    }else{
        $('.lazy').Lazy({
            bind: "event",
            delay: 0,
            onFinishedAll: function() {
                // var tool_hei = $('#branch-cooperative .toolbar').height();
                // var nav_hei = $('#branch-cooperative .navbar').height();
                // var sub_wid = $('#branch-cooperative .subnavbar').height();
                // var total_hei = $('.get_cate').height() + tool_hei + nav_hei + sub_wid;
                // if(total_hei < $(window).height()){
                //     getNestData();
                // }else{
                    listenScroll($('.get_cate').height());
                    if(Cookies.get('offset')){
                        var offset = JSON.parse(Cookies.get('offset'));
                        $('.page-content').animate({ scrollTop : offset.top });
                        Cookies.remove('offset');
                    }
                    $('.lazy_indicator').remove();
                    $('.lazy_overlay').remove();
                    bc_boolean = true;
                // }
            },
            afterLoad: function(element) {

            },
            onError: function(element) {
                element.prop('src','../app/image/imgDefault.png');
            }
        });
    }
}

// 設定navbar
swiper = function (){
    var num = $('.subnavbar .swiper_num').length;
    var mySwiper = new Swiper('.blog-cate.swiper-container', {
        spaceBetween: 0,
        slidesPerView: num
    });
}

// 無限加載20筆
getNestData = function (){
    showIndicator();
    $('.page-content').unbind('scroll');
    var longitude = Cookies.get('longitude');
    var latitude = Cookies.get('latitude');
    var token = $("#token").prop('content');
    var cate_1 = $('.branch-list-container').data('cate');
    var distance = Cookies.get('distance');
    var queryamount = 20;
    var startid = $('.start_id').last().data('id');
    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: '/pm/branch-cooperative',
        data: {_token: token,cate: cate_1,distance: distance,sd_lng:longitude,sd_lat:latitude,queryamount:queryamount,startid:startid},
        beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
        success: function(r) {
            // console.log(JSON.stringify(r));
            if(r.length > 0){
                appendToList(r,longitude,latitude);
                setFavoriteImage();
                Lazy();
                $('.goToBranch').on('click',function(){
                    $('.goToBranch').unbind('click');
                    showIndicator();
                    Cookies.remove('offset');
                    var parent = $(this).parents('.start_id');
                    var sd_id = parent.data('id');
                    var setting = {
                        "top": parent.position().top,
                        "href": location.href,
                        "leng": parent.index()+1,
                        "amount": Math.ceil((parent.index()+1)/20) * 20
                    }
                    Cookies.set('offset',JSON.stringify(setting));
                    location.href = "/pm/branch-info?sd_id="+sd_id;
                })
            }
            hideIndicator();
        },
        error: function(r) {
            // console.log(JSON.stringify(r));
            hideIndicator();
        }
    });
}

// 傳入資料並呈現在特店首頁
appendToList = function (data,longitude,latitude){
    // for(var i=0;i<data.length;i++){
    //     var sd_distance = (google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(parseFloat(latitude), parseFloat(longitude)), new google.maps.LatLng(parseFloat(data[i].sd_lat), parseFloat(data[i].sd_lng))) / 1000).toFixed(1);
    //     data[i].sd_distance = sd_distance;
    // }
    //針對距離做一次排序
    // Cookies.set('distance',data[data.length-1]['distance']);
    // data.sort(sortByDistance('sd_distance', 1));
    if(listtype == 0){
        for(var i=0;i<data.length;i++){
            var string = '<li class="swipeout animated fadeIn start_id" data-id="'+data[i]['sd_id']+'"><div class="swipeout-content"><div class="item-content no-padding"><div class="blog-list">';
            if(data[i]['sd_havebind'] == 1){
                string = string + '<div class="authenticate_icon goToBranch"><img alt="authenticate.png" class="lazy" data-src="/app/image/authenticate.png"/></div>';
            }
            string = string + '<div class="image goToBranch"><a href="javascript:void(0)">';
            var photopath = data[i]['sd_shopphotopath'];
            if(photopath != null){
                if(photopath.substr(0,1) == '/'){
                    photopath = photopath.substr(1);
                }
            }
            if(data[i]['sd_shopphotopath'] != '' && data[i]['sd_shopphotopath'] != null){
                string = string + '<img alt="'+photopath+'" class="lazy" data-src="'+stringObj.text.branch_img_path+photopath+'"/>'
            }else{
                string = string + '<img alt="imgDefault.png" class="lazy" data-src="/app/image/imgDefault.png"/>';
            }
            string = string + '</a></div><div class="col-10 favorite favorite-'+data[i]['sd_id']+'" onclick="addFavorite(\''+data[i]['sd_id']+'\', \''+data[i]['star_sd_shopphotopath']+'\', \''+data[i]['sd_shopname']+'\', \''+data[i]['sd_shopaddress']+'\', \''+data[i]['sd_shoptel']+'\', \'\',\'2\')"><img class="lazy" data-src="/app/image/unsubscribe.png" /></div><div class="text goToBranch"><h4 class="title mt-5 mb-0"><a href="javascript:void(0)">'+data[i]['sd_shopname']+'</a></h4><div class="row no-gutter info"><div class="col-100"><p class="row no-gutter"><span class="col-100 createdate"><span style="color:aqua;">距'+data[i]['dis']+' km</span><span class="note"> 地址 :</span>'+data[i]['sd_shopaddress']+'</span></p></div></div></div></div></div></div></li>';
            $('.branch-list-container').append(string);

        }
    }else if(listtype == 2){
        for(var i=0;i<data.length;i++){
            var string = '<div class="branch-item animated zoomIn start_id" data-id="'+data[i]['sd_id']+'" style="background:rgba(100%,100%,100%,.7);"><div class="image listtype2 goToBranch">';
            if(data[i]['sd_havebind'] == 1){
                string = string + '<div class="authenticate_icon goToBranch"><img alt="authenticate.png" src="/app/image/authenticate.png"/></div>';
            }
            // string = string + '<a href="javascript:void(0)" class="goToBranch">';
            var photopath = data[i]['sd_shopphotopath'];
            if(photopath != null){
                if(photopath.substr(0,1) == '/'){
                    photopath = photopath.substr(1);
                }
            }
            if(data[i]['sd_shopphotopath'] != '' && data[i]['sd_shopphotopath'] != null){
                string = string + '<img alt="'+photopath+'" class="lazy" data-src="'+stringObj.text.branch_img_path+photopath+'"/>'
            }else{
                string = string + '<img alt="imgDefault.png" class="lazy" data-src="/app/image/imgDefault.png"/>';
            }
            string = string + '</div><div class="text"><div class="title goToBranch"><span>'+data[i]['sd_shopname']+'</span></div><div class="row no-gutter"><div class="col-100 goToBranch"><p class="row no-gutter"><span class="col-90"><span style="color:aqua;">距'+data[i]['dis']+' km</span><span class="note"> 地址 :</span>'+data[i]['sd_shopaddress']+'</span></p></div><div class="col-40 favorite favorite-'+data[i]['sd_id']+'" onclick="addFavorite(\''+data[i]['sd_id']+'\', \''+data[i]['star_sd_shopphotopath']+'\', \''+data[i]['sd_shopname']+'\', \''+data[i]['sd_shopaddress']+'\', \''+data[i]['sd_shoptel']+'\', \'\',\'2\')"><img class="lazy" data-src="/app/image/unsubscribe.png" /></div></div></div></div>';
            $('.row.branch-block').append(string);
        }
    }
}

// 排序取得的資料
sortByDistance = function (propertyName, sequence) {

    var sortFun = function (obj1, obj2) {
        if (parseFloat(obj1[propertyName]) > parseFloat(obj2[propertyName]) && sequence === 1) {
            return 1;
        } else if (parseFloat(obj1[propertyName]) < parseFloat(obj2[propertyName]) && sequence === -1) {
            return 1;
        } else if (parseFloat(obj1[propertyName]) == parseFloat(obj2[propertyName])) {
            return 0;
        } else {
            return -1;
        }
    }
    return sortFun;
};


// 設置訂閱圖片
setFavoriteImage = function (){
    var favoriteList = commonTools._dataStorage(commonTools.storage.favorite);
    if (favoriteList) {
        for (var i in favoriteList.branchList) {
            $('.favorite-' + favoriteList.branchList[i].ubm_objectid).html('<img class="lazy" data-src="../app/image/subscribe.png" />');
        }
    }
}

})

