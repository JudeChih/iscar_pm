$(function() {
    var shoppoint = $('input[name=shoppoint]').val();
    if (shoppoint != null && shoppoint != undefined) {
        commonTools._dataStorage('shoppoint', shoppoint);
    }
    var strUrl = location.search;
    var getPara, ParaVal;
    var aryPara = [];
    if (strUrl.indexOf("?") != -1) {
        var getSearch = strUrl.split("?");
        getPara = getSearch[1].split("&");
        for (i = 0; i < getPara.length; i++) {
            ParaVal = getPara[i].split("=");
            aryPara.push([ParaVal[0],ParaVal[1]]);
        }
    }




    var main = commonTools._dataCookies(commonTools.storage.main) || {};
    var listItem = {
        "sd_id": sd_id,
        "scm_id": scm_id,
        "sd_shopname": shopname,
        "scm_reservationtag": scm_reservationtag,
        "scm_producttype": scm_producttype,
        "scm_price": scm_price,
        "scm_title": scm_title,
        "scm_mainpic": scm_mainpic,
        "sd_shoptel": sd_shoptel,
        "scm_enddate": scm_enddate,
        "scm_coupon_providetype": scm_coupon_providetype,
        "scm_bonus_payamount": scm_bonus_payamount,
        "PayGiftpointAsCash": PayGiftpointAsCash,
    };
    if (Cookies.get('coupon_md_id') && Cookies.get('coupon_scm_id')) {
        if (scm_id == Cookies.get('coupon_scm_id')) {
            listItem.coupon_scm_id = Cookies.get('coupon_scm_id');
            listItem.coupon_md_id = Cookies.get('coupon_md_id');
        }
    }
    commonTools._dataStorage(commonTools.storage.shop_item, listItem);

    // 設定是否已經加為最愛
    var favoriteList = commonTools._dataStorage(commonTools.storage.favorite) || {
        lastupdate: '',
        newsList: [],
        couponList: [],
        branchList: [],
        shopcouponList: []
    };

    var favoriteObj = JSON.stringify(favoriteList.shopcouponList);

    if (favoriteObj.match(scm_id)) {
        $('.favorite-' + scm_id).html('<i class="fa fa-star"></i>');
    }

    var x = $('#shopcoupon_data_title').offset().top;

    $(".page-content").scroll(function() {
        x = $('#shopcoupon_data_title').offset().top;
        if ($(this).scrollTop() >= x) {
            $('.subnavbar').removeClass('noUse');
            // $('.shopcoupon-info-left').css('top',"100%");
        } else {
            $('.subnavbar').addClass('noUse');
            $('.shopcoupon-info-left').css('top', 0);
        }
        if ($('#data_title_box').offset().top < -1 || $('#data_title_box').offset().top > 1) {
            $('#data_title_box').css('display', 'none');
        }
        if ($('#shopdata_text_box').offset().top < -1 || $('#shopdata_text_box').offset().top > 1) {
            $('#shopdata_text_box').css('display', 'none');
        }
        if ($('#other_text_box').offset().top < -1 || $('#other_text_box').offset().top > 1) {
            $('#other_text_box').css('display', 'none');
        }
        if ($('#detail_title_box').offset().top < -1 || $('#detail_title_box').offset().top > 1) {
            $('#detail_title_box').css('display', 'none');
        }
    });

    // navbar按鈕的設定
    $('.data_title_box').on('click', function() {
        $('#data_title_box').css('display', 'block');
        location.replace($(this).prop('href'));
    });
    $('.shopdata_text_box').on('click', function() {
        $('#shopdata_text_box').css('display', 'block');
        if ($('#shopdata_text_box').offset().top != 0) {
            $('#shopdata_text_box').css('height', 44);
        }
        location.replace($(this).prop('href'));
    })
    $('.other_text_box').on('click', function() {
        $('#other_text_box').css('display', 'block');
        if ($('#other_text_box').offset().top != 0) {
            $('#other_text_box').css('height', 44);
        }
        location.replace($(this).prop('href'));
    })
    $('.detail_title_box').on('click', function() {
        $('#detail_title_box').css('display', 'block');
        if ($('#detail_title_box').offset().top != 0) {
            $('#detail_title_box').css('height', 44);
        }
        location.replace($(this).prop('href'));
    })

    // 根據不同的頁面導入顯示不同的按鈕於最下方
    if (sci_type === 'scan') {
        $('.serving').css('display', 'inherit');
        $('.favorite').css('display', 'none');
        $('.shopcoupon-subTitle').css('display', 'none');
        $('.branch-info').css('display', 'none');
        $('#shopcoupon-map').css('display', 'none');
        $('.shopcoupon-info-block').css('height', 'auto');
        $('#shopcoupon_other_text').css('display', 'none');
        $('.shopcoupon-other-content').css('display', 'none');
        $('#giftpoint_text').css('display', 'none');
        $('.gift-point').css('display', 'none');
    } else if (sci_type === 'shop_available' || sci_type == 'shop_finish' || sci_type == 'shop_invalid' || sci_type == 'reserved' || sci_type == 'favorite') {
        if (sci_type === 'shop_available') {
            $('.showQR').css('display', 'inherit');
        } else if (sci_type == 'reserved') {
            $('.reserved').css('display', 'inherit');
        } else if (sci_type === 'favorite') {
            $('.get').css('display', 'inherit');
            $('.cannotget').css('display', 'inherit');
        } else {

        }
    } else if (sci_type === 'management') {

    } else {
        $('.get').css('display', 'inherit');
        $('.cannotget').css('display', 'inherit');
    }


    ///////// 按鈕的設定 ///////////////////////////////////////////
    //回上一頁
    $('.shopcoupon-info-left a').on('click', function() {
        var sd_id = '';
        for(var i in aryPara){
            if(aryPara[i][0] == 'sd_id'){
                sd_id = aryPara[i][1];
            }
        }
        if(sd_id != ''){
            window.location = "/pm/branch-info?sd_id="+sd_id;
        }else{
            commonTools.backToPrePage();
        }
        
    })
        //分享
    $('.btn_share').click(function() {
        commonTools.setPopBox({
            title: stringObj.text.share,
            text: '<input class="clone_input" type="text" value="' + url + '"/>',
            buttons: [{
                text: stringObj.text.cancel,
                onClick: function() {
                    removeSomething();
                }
            }, {
                text: stringObj.text.copy,
                onClick: function() {
                    if (Cookies.get('app_version') != undefined) {
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
                                onClick: function() {
                                    removeSomething();
                                }
                            }]
                        });
                    }
                }
            }]
        });
    })
    //立即兌換/立即購買
    $('.get').click(function() {
        Cookies.remove('createpaymentflow');
        if (main.sat) {
            // 當他是兌換商品時。判斷是否已經訂閱此店家
            if (scm_coupon_providetype == 1) {
                var f_boolean = true;
                var favoriteList = commonTools._dataStorage(commonTools.storage.favorite) || {
                    lastupdate: '',
                    newsList: [],
                    couponList: [],
                    branchList: [],
                    shopcouponList: []
                };
                if (favoriteList) {
                    for (var i in favoriteList.branchList) {
                        if (favoriteList.branchList[i].ubm_objectid == sd_id) {
                            f_boolean = false;
                        }
                    }
                }
                if (f_boolean) {
                    commonTools.setPopBox({
                        title: stringObj.text.warn,
                        text: stringObj.text.addNotYet,
                        buttons: [{
                            text: stringObj.text.cancel,
                            onClick: function() {
                                removeSomething();
                            }
                        }, {
                            text: stringObj.text.subscription,
                            onClick: function() {
                                removeSomething();
                                addFavorite(sd_id, stringObj.text.branch_img_path + sd_shopphotopath, shopname, sd_shopaddress, sd_shoptel, '', '2');
                            }
                        }]
                    });
                } else {
                    if (parseInt(inventory) > 0) {
                        localStorage.setItem('scm_id', scm_id);
                        if (scm_reservationtag === '1') {
                            //預約
                            window.location = "/Shop#!/shop-buy-data?scm_id=" + scm_id + "&from=reservation";
                        } else if (scm_producttype === '1') {
                            //服務券
                            window.location = "/Shop#!/shop-buy-data?scm_id=" + scm_id + "&from=coupon";
                        } else if (scm_producttype === '2') {
                            //商品
                            window.location = "/Shop#!/shop-buy-data?scm_id=" + scm_id + "&from=commodity";
                        }
                    } else {
                        commonTools.setPopBox({
                            title: stringObj.text.warn,
                            text: stringObj.text.no_inventory,
                            buttons: [{
                                text: stringObj.text.cancel,
                                onClick: function() {
                                    removeSomething();
                                }
                            }]
                        });
                    }
                }
            } else {
                if (parseInt(inventory) > 0) {
                    localStorage.setItem('scm_id', scm_id);
                    if (scm_reservationtag === '1') {
                        //預約
                        window.location = "/Shop#!/shop-buy-data?scm_id=" + scm_id + "&from=reservation";
                    } else if (scm_producttype === '1') {
                        //服務券
                        window.location = "/Shop#!/shop-buy-data?scm_id=" + scm_id + "&from=coupon";
                    } else if (scm_producttype === '2') {
                        //商品
                        window.location = "/Shop#!/shop-buy-data?scm_id=" + scm_id + "&from=commodity";
                    }
                } else {
                    commonTools.setPopBox({
                        title: stringObj.text.warn,
                        text: stringObj.text.no_inventory,
                        buttons: [{
                            text: stringObj.text.cancel,
                            onClick: function() {
                                removeSomething();
                            }
                        }]
                    });
                }
            }
        } else {
            commonTools.setPopBox({
                title: stringObj.text.warn,
                text: stringObj.text.notLogin,
                buttons: [{
                    text: stringObj.text.cancel,
                    onClick: function() {
                        removeSomething();
                    }
                }, {
                    text: stringObj.text.login,
                    onClick: function() {
                        removeSomething();
                        webview.loginPage();
                        Cookies.set('returnUrl', location.href);
                    }
                }]
            });
        }
    });
    // 開始服務
    $('.serving').click(function() {
        commonTools.shopcouponexec(scm_id, scg_id, scm_title, scm_enddate);
    });

    ////////////////////// 按鈕 end ////////////////////////////////



    $(window).load(function() {
        // setTagDisplay();// 修改頁面右上方的顯示標籤

        googlemap(); // 設定要顯示的地圖
        Lazy(); // 圖片懶加載
        chengeWidth(); // 動態變更sw_width底下a的寬度
        //美化scroll bar
        if ($(window).width() >= 992) {
            setScrollBar();
        }
        shopswiper(); // 最上方的圖片slide
    });

    $(window).resize(function() {
        chengeWidth();
    });

    // 動態變更sw_width底下a的寬度
    function chengeWidth() {
        var num = $('.swiper_num').length;
        var wdth = $('#sw_width').width();
        wdth = wdth - 44;
        $('.swiper_num').each(function() {
            if ($(this).index() == 0) {
                $(this).css('width', '44');
            } else {
                $(this).css('width', wdth / (num - 1));
            }
        })
    }

    // 圖片懶加載
    function Lazy() {

        if ($('.lazy').length == 0) {
            $('.lazy_indicator').remove();
            $('.lazy_overlay').remove();
        } else {
            $('.lazy').Lazy({
                bind: "event",
                delay: 0,
                onFinishedAll: function() {
                    $('.lazy_indicator').remove();
                    $('.lazy_overlay').remove();
                },
                afterLoad: function(element) {

                },
                onError: function(element) {
                    element.prop('src', '../app/image/imgDefault.png');
                }
            });
        }
    }

    // 修改活動時間日期的顯示格式 - 換成 /
    function changeDateMark() {
        var startdate = $('#scm_startdate').text();
        var enddate = $('#scm_enddate').text();
        startdate = startdate.replace(/-/ig, '/');
        enddate = enddate.replace(/-/ig, '/');
        $('#scm_startdate').text(startdate);
        $('#scm_enddate').text(enddate);
    }

    // 最上方的圖片slide
    function shopswiper() {
        var shopcouponSwiper = new Swiper('.shopcoupon-imgs', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            centeredSlides: true,
            autoplay: 3000,
            autoplayDisableOnInteraction: false,
            slidesPerView: 'auto',
            loop: false,
            loopedSlides: 3,

            // effect: 'fade'
        });
        //顯示主圖
        shopcouponSwiper.appendSlide('<div class="swiper-slide shop-item" id="shop-main"><img src="' + stringObj.text.branch_img_path + scm_mainpic + '" onerror=\'this.src="../app/image/imgDefault.png"\'/></div>');

        //顯示子圖
        for (var i in scm_activepics) {
            shopcouponSwiper.appendSlide('<div class="swiper-slide shop-item" id="shop-img' + i + '"><img src="' + stringObj.text.branch_img_path + scm_activepics[i] + '" onerror=\'this.src="../app/image/imgDefault.png"\'/></div>');
        }
        //預設先顯示第一張主圖

        shopcouponSwiper.slideTo(0, 0, true);
        shopcouponSwiper.update();
        // var num = $('.swiper_num').length;
        // var mySwiper = new Swiper('.shop-scroll.swiper-container', {
        //     spaceBetween: 0,
        //     slidesPerView: num
        // });
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
        var shopcoupon_map = new google.maps.Map(document.getElementById("shopcoupon-map"), mapOptions);

        //加入標示點(Marker)
        addMarker(latlng, shopname, shopcoupon_map);

    }

    // 地圖上的小指標跟顯示文字
    function addMarker(location, title, shopcoupon_map) {

        var marker = new google.maps.Marker({
            position: location,
            title: title,
            map: shopcoupon_map
        });

        var infowindow = new google.maps.InfoWindow(); //地圖上的訊息視窗
        infowindow.setContent('<b style="color:#000;">' + title + '</b>'); //設定訊息內容

        infowindow.open(shopcoupon_map, marker); //顯示訊息視窗

        google.maps.event.addListener(marker, 'click', function() { //監聽地圖點擊,此為標示點點擊事件
            infowindow.open(shopcoupon_map, marker); //顯示訊息視窗
        });
    }


})