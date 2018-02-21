$(function(){
Cookies.set('sd_id',sd_id);
var shoppoint = $('input[name=shoppoint]').val();
if(shoppoint != null && shoppoint != undefined){
    commonTools._dataStorage('shoppoint',shoppoint);
}
//branchInfoInit
// 顯示已認證的圖片
if (sd_havebind === '1') {
    $('.toolbar-bottom.tabbar').css('display', 'block');
} else {
    $('.toolbar-bottom.tabbar').css('display', 'none');
}

$('.track').click(function () {
    addFavorite(sd_id, stringObj.text.branch_img_path + sd_shopphotopath, sd_shopname, sd_shopaddress, sd_shoptel, '', '2');
});


var favoriteList = commonTools._dataStorage(commonTools.storage.favorite) || {
    lastupdate: '',
    newsList: [],
    couponList: [],
    branchList: [],
    shopcouponList: []
};

var favoriteObj = JSON.stringify(favoriteList.branchList);

if (favoriteObj.match(sd_id)) {
    $('.track').html('<img src="../app/image/subscribe.png" onerror=\'this.src="../app/image/imgDefault.png"\' />');
}


////////////////// 按鈕設定 ///////////////////////////////
// 左上角按鈕設定
$('.branch-info-left a').on('click',function(){
    Cookies.remove('back');
    if(Cookies.get('offset')){
        var offset = JSON.parse(Cookies.get('offset'));
        window.location = offset.href;
    }else{
        window.location = "/pm";
    }
    // commonTools.backToPrePage();
});
//分享
$('.btn_share').click(function(){
    commonTools.setPopBox({
        title: stringObj.text.share,
        text: '<input class="clone_input" type="text" value="'+url+'"/>',
        buttons: [{
                text: stringObj.text.cancel,
                onClick: function () {
                    removeSomething();
                }
            }, {
                text: stringObj.text.copy,
                onClick: function () {
                    if (Cookies.get('app_version') != undefined ) {
                        webview.copyText($(".clone_input").val());
                        removeSomething();
                    } else {
                        $(".clone_input").select();
                        document.execCommand("Copy");
                        removeSomething();
                        commonTools.setPopBox({
                            title: stringObj.text.warn,
                            text: stringObj.text.already_copy,
                            buttons: [{
                                    text: stringObj.text.confirm,
                                    onClick: function () {
                                        removeSomething();
                                    }
                                }]
                        });
                    }
                }
            }]
    });
})
// toolbar a連結設定
$('.toolbar a').on('click',function(){
    if($(this).data('href')){
        location.replace($(this).data('href'));
    }
})
//////////////////////////// 按鈕設定 end ///////////////////////////////

$(window).load(function() {
    googlemap(); // 設定要顯示的地圖
    Lazy();
    //美化scroll bar
    if ($(window).width() >= 992){
        setScrollBar();
    }
});

// $('.context').each(function(){
//     var text = $(this).text();
//     $(this).html(text);
// })
// $('#cdm_description').each(function(){
//     var text = $(this).text();
//     $(this).html(text);
// })


// 圖片懶加載
function Lazy(){

    if($('.lazy').length == 0){
        // $('.overlay_setting').css('display','none');
        $('.lazy_indicator').remove();
        $('.lazy_overlay').remove();
    }else{
        $('.lazy').Lazy({
                bind: "event",
                delay: 0
            ,
            onFinishedAll: function() {
                // $('.overlay_setting').css('display','none');
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

// 設定要顯示的地圖
    function googlemap() {
        //定義經緯度位置
        var latlng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));

        //設定地圖參數
        var mapOptions = {
            zoom: 15, //初始放大倍數
            center: latlng, //預設中心經緯度
            mapTypeId: google.maps.MapTypeId.ROADMAP, //正常2D道路模式
            mapTypeControl: false, //切換地圖檢視類型的面板
            streetViewControl: false, //街景服務的面板
            scrollwheel: false //禁止滾動放大縮小的功能
        };

        //在指定DOM元素中嵌入地圖
        var shop_map = new google.maps.Map(document.getElementById("shop-map"), mapOptions);

        //加入標示點(Marker)
        addMarker(latlng, sd_shopname, shop_map);


    }

    // 地圖上的小指標跟顯示文字
    function addMarker(location, title, shop_map) {

        var marker = new google.maps.Marker({
            position: location,
            title: title,
            map: shop_map
        });

        var infowindow = new google.maps.InfoWindow(); //地圖上的訊息視窗
        infowindow.setContent('<b style="color:#000;">' + title + '</b>'); //設定訊息內容

        infowindow.open(shop_map, marker); //顯示訊息視窗

        google.maps.event.addListener(marker, 'click', function() { //監聽地圖點擊,此為標示點點擊事件
            infowindow.open(shop_map, marker); //顯示訊息視窗
        });
    }

})

