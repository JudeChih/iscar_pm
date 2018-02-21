var app_version = ''; //App版本號
var _prelogin_key; //模組保護碼
var md_id; //會員編號
var fType = "branch"; //書籤類別
var nowPage; //當前所在頁面名稱

var mCounty; //當前城市名稱
var mDistrict; //當前鄉鎮區名稱
var regionIndex; //當前地區序列值
var zipArray; //當前郵遞區號

var isNetwork = true; //是否有網路
var loginType = ""; //登入類別

var my_marker; //用戶位置標記

var scan_from; //掃描來源

var browser_width = $(window).width(); //取得裝置寬度
var browser_height = $(window).height(); //取得裝置高度

var nowlat;
var nowlng;


//custom index obj
var indexObj = {
    _initDate: "2010/01/01 00:00:00",
    _dataUrl: {
        machine: 'http://' + stringObj.MEMBER_URL + ':' + stringObj.PORT + '/api/account/machineconnect',
        userdatacollect: 'http://' + stringObj.APP_URL + ':' + stringObj.PORT + '/account/userdatacollect',
        userbookmarkrecorver: 'http://' + stringObj.WEB_URL + ':' + stringObj.PORT + '/account/userbookmarkrecorver',
        userbookmarkupdate: 'http://' + stringObj.WEB_URL + ':' + stringObj.PORT + '/account/userbookmarkupdate',
        usermessagerecorver: 'http://' + stringObj.APP_URL + ':' + stringObj.PORT + '/account/usermessagerecorver',
        usermessageupdate: 'http://' + stringObj.APP_URL + ':' + stringObj.PORT + '/account/usermessageupdate',
        salesagentlogin: 'http://' + stringObj.APP_URL + ':' + stringObj.PORT + '/shop/salesagentlogin',

        verify_sat: 'http://' + stringObj.MEMBER_URL + '/api/vrf/verify_sat',
        query_salt: 'http://' + stringObj.MEMBER_URL + '/api/vrf/query_salt',
        querymembershopinfo: 'http://' + stringObj.WEB_URL + '/shopmanage/querymembershopinfo',

        modify_member_data: 'http://' + stringObj.WEB_URL + '/modify_member_data',

        post_member_introducer: 'http://' + stringObj.APP_URL + '/system/post_member_introducer',

        verify_memberseccode: 'http://' + stringObj.MEMBER_URL + ':' + stringObj.PORT + '/api/vrf/verify_memberseccode',
        modify_memberseccode: 'http://' + stringObj.MEMBER_URL + ':' + stringObj.PORT + '/api/modify_memberseccode'

    },
    _storage: {
        main: _main,
        booking: 'booking',
        branchData: 'branchData',
        questionnaire: 'questionnaire',
        questionLog: 'questionLog',
        fbInfo: 'fbInfo',
        userData: 'userData',
        share: 'share',
        favorite: 'myFavorite',
        mailList: 'mailList',
        newsIdList: 'newsIdList',
        loginTimes: 'loginTimes',
        fbLoginTimes: 'fbLoginTimes',
        couponRecord: 'couponRecord',
        shopCouponRecord: 'shopCouponRecord',
        shopServiceRecord: 'shopServiceRecord',
        businessIsLogin: 'businessIsLogin',
        businessData: 'businessData',
        binding: 'binding',
        api_token: 'api_token',
        myshopdata: 'myshopdata',
        search_result: 'search_result',
        reservationbook: 'reservationbook'
    },
    listLoading: false,
    listId: null,
    _templateSet: {},

    init: function() {
        //init
        var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};

        app_version = Cookies.get('app_version') || '';

        //        if (isMobile.Android()) {
        //            try {
        //                // 需要測試的程式碼
        //                app_version = Android.getVersionName(); // 可拋出例外的函數
        //                Cookies.set('app_version', app_version);
        //            } catch (e) {
        //                app_version = "";
        //                //console.log(e); // 把例外物件印出
        //            }
        //        } else if (isMobile.iOS()) {
        //            iPhone.getVersionName();
        //        }



        if (Cookies.get('app_version') === undefined && mainSg.murId === undefined) {
            //若非APP，取機碼
            getMurId(function(mur) {
                setParameter(mur);
            });

        } else {
            setParameter(mainSg.murId);
        }

        //indexObj._dataStorage(indexObj._storage.search_result, null); //清除查詢條件

        if (mainSg.sat) {
            //檢查sat效力
            indexObj.verify_sat();
            md_id = mainSg.mdId;
        } else {
            md_id = 'Guest';
            loginType = "";
            if (mainSg && mainSg.murId) {

                var murId = mainSg.murId;

                var salt_no = localStorage.getItem('salt_no');
                var salt = localStorage.getItem('salt');
                var listtype = localStorage.getItem('listtype');
                var cate = localStorage.getItem('cate');
                if (localStorage.getItem(indexObj._storage.search_result)) {
                    var search_result = localStorage.getItem(indexObj._storage.search_result);
                }
                if (localStorage.getItem('pageInit')) {
                    var pageInit = localStorage.getItem('pageInit');
                }
                //                if (localStorage.getItem('link_to')) {
                //                    var link_to = localStorage.getItem('link_to');
                //                }

                localStorage.clear();
                Cookies.remove('branchData');

                indexObj._dataCookies(indexObj._storage.main, {
                    murId: murId
                }, 'iscarmg.com');
                localStorage.setItem('salt_no', salt_no);
                localStorage.setItem('salt', salt);
                localStorage.setItem('listtype', listtype);
                localStorage.setItem('cate', cate);
                localStorage.setItem(indexObj._storage.search_result, search_result);
                localStorage.setItem('pageInit', pageInit);
                //                if (localStorage.getItem('link_to')) {
                //                    localStorage.setItem('link_to', link_to);
                //                }

            }
            //            if (localStorage.getItem('link_to')) {
            //                mainView.router.load({
            //                    url: localStorage.getItem('link_to')
            //                });
            //                localStorage.removeItem('link_to');
            //            } else if (nowPage === undefined) {
            //                mainView.router.load({
            //                    url: 'branch-cooperative?menu=' + branch_menu + '&cate=' + branch_cate,
            //                    reload: true
            //                });
            //            }
            if (nowPage === undefined) {
                if (localStorage.getItem('pageInit') !== 'false') {
                    mainView.router.load({
                        url: localStorage.getItem('pageInit'),
                        reload: true
                    });
                } else {
                    window.location = 'pm/branch-cooperative?cate=' + branch_cate + '&listtype=' + nowBranchType;
                }
                //                mainView.router.load({
                //                    url: 'branch-cooperative?menu=' + branch_menu + '&cate=' + branch_cate,
                //                    reload: true
                //                });
            }
            localStorage.removeItem('pageInit');
        }



        //更新書籤
        if (mainSg.mdId) {
            branchObj.favoriteUpdate();
        }

    },

    jsonUrlDecode: function(obj) {
        for (var index in obj) {
            if (typeof obj[index] == 'object') {
                this.jsonUrlDecode(obj[index]);
            } else {
                obj[index] = decodeURIComponent(obj[index]);
            }
        }
    },

    jsonUrlEncode: function(obj) {
        for (var index in obj) {
            if (typeof obj[index] == 'object') {
                this.jsonUrlEncode(obj[index]);
            } else {
                obj[index] = encodeURIComponent(obj[index]);
            }
        }
    },
    //呼叫wcf模組
    _wcfget: function(i) {

        var url = i.url;
        var data = JSON.stringify(JSON.stringify(i.para));
        //console.log(data);
        $$.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: 'json',
            type: 'POST',
            url: url,
            data: data,
            success: function(r) {
                //console.log(JSON.stringify(r));
                if (i.success) {
                    i.success(r);
                }
            },
            error: function(r) {
                //console.log(JSON.stringify(r));
                if (i.error) {
                    i.error(r);
                }
            },
            complete: function(r) {
                if (i.finish) {
                    i.finish(r);
                }
            },
            beforeSend: function(r) {
                if (i.progress) {
                    i.progress(r);
                }
            },
        });
    },
    //取得,刪除,新增localStorage模組
    _dataStorage: function(name, obj) {
        if (obj === undefined) {
            if (localStorage.getItem(name) !== 'undefined') {
                //get
                return JSON.parse(localStorage.getItem(name));
            } else {
                return '';
            }
        }
        if (obj === null) {
            //del
            localStorage.removeItem(name);
            return true;
        }
        if (typeof obj === 'object') {
            //set
            localStorage.setItem(name, JSON.stringify(obj));
            return true;
        }
        return false;
    },
    //取得,刪除,新增Cookies
    _dataCookies: function(name, obj, _domain) {
        if (obj === undefined) {
            if (Cookies.get(name) !== undefined) {
                //get
                return JSON.parse(Cookies.get(name));
            } else {
                return false;
            }
        }
        if (obj === null) {
            //del
            Cookies.remove(name);
            return true;
        }
        if (typeof obj === 'object') {
            //set
            if (_domain === undefined) {
                Cookies.set(name, JSON.stringify(obj));
            } else {
                Cookies.set(name, JSON.stringify(obj), {
                    domain: _domain
                });
            }
            return true;
        }
        if (typeof obj === 'string') {
            //set
            if (_domain === undefined) {
                Cookies.set(name, obj);
            } else {
                Cookies.set(name, obj, {
                    domain: _domain
                });
            }
            return true;
        }
        return false;
    },
    template: function(name) {
        if (name in indexObj._templateSet) {
            return indexObj._templateSet[name];
        }
        //init
        var temp = ($$('#' + name).length) ? $$('#' + name).html() : '';
        var tempCompile = Template7.compile(temp);
        indexObj._templateSet[name] = tempCompile;
        return indexObj._templateSet[name];
    },
    //isCar錢包
    bonusInquireInit: function(page) {
        var mainSg = indexObj._dataCookies(indexObj._storage.main);
        var data = {
            modacc: stringObj.shop.moduleaccount,
            modvrf: Cookies.get('modvrf'),
            sat: mainSg.sat,
        };
        //console.log(JSON.stringify(data));
        indexObj._wcfget({
            url: indexObj._dataUrl.coininfoquery,
            para: data,
            success: function(r) {
                blogObj.jsonUrlDecode(r);
                //console.log(JSON.stringify(r));
                if (r.coininfoqueryresult) {
                    var rObj = JSON.parse(JSON.stringify(r.coininfoqueryresult));
                    if (rObj.message_no === "000000000") {

                        var coinObj = {};
                        coinObj.cos_end_coin = rObj.cos_end_coin || "0";
                        coinObj.cos_end_giftpoint = rObj.cos_end_giftpoint || "0";

                        coinObj.factorybonus = [];
                        var factorybonusitem = {};
                        for (var j in rObj.factorybonus) {
                            var factoryObj = JSON.parse(localStorage.getItem("fd_" + rObj.factorybonus[j].fd_id));
                            factorybonusitem.fd_cname = factoryObj.fd_cname;
                            factorybonusitem.cos_end_fdbonus = rObj.factorybonus[j].cos_end_fdbonus;
                            coinObj.factorybonus.push(factorybonusitem);
                        }

                        var temp = indexObj.template('templateWallet');
                        var item = temp(coinObj);
                        $$('.bonusInquireBlock').html(item);
                        myApp.initImagesLazyLoad('.page-content');

                        var options = {
                            useEasing: true,
                            useGrouping: true,
                            separator: ',',
                            decimal: '.',
                            prefix: '',
                            suffix: ''
                        };
                        var coinNum = new CountUp("#coin-num", 0, parseInt(rObj.cos_end_coin), 0, 1, options);
                        coinNum.start();
                        var countNum = new CountUp("#count-num", 0, parseInt(rObj.cos_end_giftpoint), 0, 1, options);
                        countNum.start();


                    } else {
                        stringObj.return_header(rObj.message_no);
                        if (_tip) {
                            myApp.alert(_tip + '( ' + rObj.message_no + ' )', stringObj.text.warn);
                            _tip = null;
                        }
                    }
                    myApp.hidePreloader();
                }

            },
            error: function(r) {
                //alert(JSON.stringify(r));
            }
        });
    },

    //汽車特店選單
    shopMenuInit: function(page) {
        var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
        if (mainSg.md_clienttype === '1') {
            stringObj.shop.isShop = true;
            $('.left a').attr('href', 'shop/branch-main');
        } else {
            stringObj.shop.isShop = false;
            $('.left a').attr('href', 'branch-cooperative');
        }
        if (mainSg.sat) {
            stringObj.shop.signed_in = true
        }
        var temp = indexObj.template('templateShopMenu');
        var item = temp(stringObj.shop);
        $$('.contentBlock').html(item);

    },

    //使用紀錄初始化
    couponRecordInit: function(page) {

        var q = page.query;

        if (q.tab) {
            myApp.showTab('#' + q.tab);

            var temp = indexObj.template('templateCouponRecordList');
            var item = temp('');
            $$('#' + q.tab + ' .content-block').html(item);
            if (q.tab == 'shop-coupon') {
                branchObj.shopcouponrecorver();
            } else if (q.tab == 'queue') {
                branchObj.shopservicequerecorver();
            }
            myApp.accordionOpen('.available-block');

        } else {
            var temp = indexObj.template('templateCouponRecordList');
            var item = temp('');
            $$('#queue .content-block').html(item);
            branchObj.shopservicequerecorver();
            myApp.accordionOpen('.available-block');
        }



        $$('#shop-coupon').on('show', function() {
            var temp = indexObj.template('templateCouponRecordList');
            var item = temp('');
            $$('#shop-coupon .content-block').html(item);
            branchObj.shopcouponrecorver();
            myApp.accordionOpen('.available-block');
        });

        $$('#queue').on('show', function() {
            var temp = indexObj.template('templateCouponRecordList');
            var item = temp('');
            $$('#queue .content-block').html(item);
            branchObj.shopservicequerecorver();
            myApp.accordionOpen('.available-block');
        });



        $$('.available-block').on('opened', function() {
            if (isMobile.Android()) {
                webview.nowEvent('Qrmenu-And', 'useable', md_id);
            } else if (isMobile.iOS()) {
                webview.nowEvent('Qrmenu-iOS', 'useable', md_id);
            }
        });
        $$('.finish-block').on('opened', function() {
            if (isMobile.Android()) {
                webview.nowEvent('Qrmenu-And', 'used', md_id);
            } else if (isMobile.iOS()) {
                webview.nowEvent('Qrmenu-iOS', 'used', md_id);
            }
        });
        $$('.invalid-block').on('opened', function() {
            if (isMobile.Android()) {
                webview.nowEvent('Qrmenu-And', 'expire', md_id);
            } else if (isMobile.iOS()) {
                webview.nowEvent('Qrmenu-iOS', 'expire', md_id);
            }
        });



        window.intervalParam = window.setInterval(function() {
            myApp.initImagesLazyLoad('.page-content'); //LazyLoad初始化
        }, 2000);


    },

    //會員登出
    _userLogout: function() {
        var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
        window.location = 'http://' + stringObj.MEMBER_URL + '/logout?parameter=' + Cookies.get('parameter') + '&sat=' + mainSg.sat;
    },
    //查詢用戶商家資料
    querymembershopinfo: function() {
        myApp.showIndicator();
        if (indexObj.listLoading)
            return;
        indexObj.listLoading = true;
        var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
        var data = {
            modacc: stringObj.shop.moduleaccount,
            modvrf: Cookies.get('modvrf'),
            sat: mainSg.sat,
            queryamount: 20,
            sd_id: indexObj.listId
        };
        //console.log(JSON.stringify(data));
        indexObj._wcfget({
            url: indexObj._dataUrl.querymembershopinfo,
            para: data,
            success: function(r) {
                myApp.hideIndicator();
                indexObj.jsonUrlDecode(r);
                //console.log(JSON.stringify(r));
                if (r.querymembershopinforesult) {
                    var rObj = JSON.parse(JSON.stringify(r.querymembershopinforesult));

                    if (rObj.message_no === "000000000") {
                        var myshopdata = indexObj._dataStorage(indexObj._storage.myshopdata) || [];
                        //mainSg.shopdata_array = rObj.shopdata_array;
                        //indexObj._dataStorage(indexObj._storage.main, mainSg);
                        if (nowPage === 'myBranchs') {
                            for (var i in rObj.shopdata_array) {
                                if (rObj.shopdata_array[i].sd_shopphotopath) {
                                    rObj.shopdata_array[i].sd_shopphotopath = stringObj.text.branch_img_path + rObj.shopdata_array[i].sd_shopphotopath;
                                } else {
                                    rObj.shopdata_array[i].sd_shopphotopath = '../app/image/imgDefault.png';
                                }
                                myshopdata.push(rObj.shopdata_array[i]);
                                indexObj._dataStorage(indexObj._storage.myshopdata, myshopdata);
                            }
                            if (rObj.shopdata_array.length > 0) {
                                indexObj.listId = rObj.shopdata_array[(rObj.shopdata_array.length - 1)].sd_id;
                            }
                            //template
                            var temp = indexObj.template('templateMyBranchs');
                            var item = temp(rObj);
                            $$('.myBranchsList').append(item);
                            myApp.initImagesLazyLoad('.page-content');

                            $$('.infinite-scroll-preloader').hide();
                            indexObj.listLoading = false;

                            var branchData = indexObj._dataStorage(indexObj._storage.branchData) || {};

                            if (branchData.sd_id) {

                                $('.myBranchs-left').css('visibility', 'visible');
                                $('.myBranchs-right').css('visibility', 'visible');
                                $('.floating-button').css('display', 'flex');
                                $('.item-' + branchData.sd_id).css('background', 'rgba(80, 80, 80, .6)');
                                $('.item-' + branchData.sd_id).css('color', '#fff');

                                //我要開店
                                $('.open-store').click(function() {
                                    if (mainSg.sat) {
                                        var applicationData = indexObj._dataStorage(branchObj.storage.applicationData) || '';
                                        if (applicationData) {
                                            myApp.modal({
                                                title: stringObj.text.application_data_record,
                                                text: '<div class="row"><div class="col-28">店名：</div><div class="col-72">' + applicationData.sd_shopname + '</div></div>' +
                                                    '<div class="row"><div class="col-28">狀態：</div><div class="col-72">' + stringObj.text.not_finish + '</div></div>',
                                                buttons: [{
                                                    text: stringObj.text._delete,
                                                    onClick: function() {
                                                        indexObj._dataStorage(branchObj.storage.applicationData, null);
                                                    }
                                                }, {
                                                    text: stringObj.text._continue,
                                                    onClick: function() {
                                                        if (applicationData.operation_type == '0') {
                                                            mainView.router.load({
                                                                url: 'application-shop',
                                                                reload: true
                                                            });
                                                        } else if (applicationData.operation_type == '1') {
                                                            mainView.router.load({
                                                                url: 'application-usedcar',
                                                                reload: true
                                                            });
                                                        }

                                                    }
                                                }]
                                            });

                                            $('.modal-title + .modal-text').css('text-align', 'left');
                                            $('.modal-title + .modal-text').css('margin', '5% 3%');
                                            $('.modal-title + .modal-text .row').css('margin', '3% 0');
                                            $('.modal-title + .modal-text .row .col-28').css('width', '28%');
                                            $('.modal-title + .modal-text .row .col-28').css('color', 'darkslategray');
                                            $('.modal-title + .modal-text .row .col-72').css('width', '72%');

                                        } else {
                                            mainView.router.load({
                                                url: 'application-shop'
                                            });
                                        }
                                    } else {
                                        myApp.modal({
                                            title: stringObj.text.warn,
                                            text: stringObj.text.notLogin,
                                            buttons: [{
                                                text: stringObj.text.cancel,
                                                onClick: function() {}
                                            }, {
                                                text: stringObj.text.login,
                                                onClick: function() {
                                                    webview.loginPage();
                                                }
                                            }, ]
                                        });
                                    }

                                });
                            } else {
                                $('.myBranchs-right').css('visibility', 'visible');
                                $('.floating-button').css('display', 'none');
                            }
                        } else {
                            indexObj.init();
                        }
                    } else {
                        indexObj.init();
                        myApp.detachInfiniteScroll($$('.infinite-scroll'));
                        $$('.infinite-scroll-preloader').remove();
                        stringObj.return_header(rObj.message_no);
                        if (_tip) {
                            myApp.alert(_tip + '( ' + rObj.message_no + ' )', stringObj.text.warn);
                            _tip = null;
                        }
                    }

                }


            },
            error: function(r) {
                myApp.detachInfiniteScroll($$('.infinite-scroll'));
                $$('.infinite-scroll-preloader').remove();
                myApp.hideIndicator();
                //alert(JSON.stringify(r));
            }
        });

    },
    //APP會員資料同步
    modify_member_data: function(user_info) {
        /*var rl_city_code = user_info.rl_city_code;
        var rl_zip = user_info.rl_zip;
        if (rl_city_code !== 0) {
            if (rl_zip !== 0) {
                rl_zip = stringObj.region[rl_city_code][1][stringObj.region[rl_city_code][0].indexOf(rl_zip)];
            }
            rl_city_code = stringObj.counties.indexOf(user_info.rl_city_code) + 1;
        }*/
        var data = {
            modacc: stringObj.shop.moduleaccount,
            modvrf: Cookies.get('modvrf'),
            sat: user_info.sat,
            md_id: user_info.mdId,
            md_cname: user_info.md_cname,
            md_picturepath: user_info.md_picturepath,
            md_logintype: user_info.loginType,
            md_clienttype: user_info.md_clienttype,
            md_clubjoinstatus: user_info.md_clubjoinstatus,
            md_city: user_info.md_city,
            rl_city_code: user_info.rl_city_code,
            rl_zip: user_info.rl_zip,
            md_country: user_info.md_country,
            md_birthday: user_info.md_birthday,
            md_fbgender: user_info.md_fbgender
        };
        //console.log(JSON.stringify(data));
        indexObj._wcfget({
            url: indexObj._dataUrl.modify_member_data,
            para: data,
            success: function(r) {
                indexObj.jsonUrlDecode(r);
                //console.log(JSON.stringify(r));
                if (r.modify_member_dataresult) {
                    var rObj = JSON.parse(JSON.stringify(r.modify_member_dataresult));

                    if (rObj.message_no === "000000000") {

                    } else {
                        stringObj.return_header(rObj.message_no);
                        if (_tip) {
                            myApp.alert(_tip + '( ' + rObj.message_no + ' )', stringObj.text.warn);
                            _tip = null;
                        }
                    }

                }


            },
            error: function(r) {
                //alert(JSON.stringify(r));
            }
        });
    },
    //即時鹽值查詢
    query_salt: function(modacc, modpsd) {
        myApp.showIndicator();
        var data = {
            modacc: modacc
        };
        //console.log(JSON.stringify(data));
        indexObj._wcfget({
            url: indexObj._dataUrl.query_salt,
            para: data,
            success: function(r) {
                indexObj.jsonUrlDecode(r);
                //console.log(JSON.stringify(r));
                if (r.query_saltresult) {
                    var rObj = JSON.parse(JSON.stringify(r.query_saltresult));

                    if (rObj.message_no === "000000000") {
                        var no_salt = atob(decodeURIComponent(rObj.salt));
                        var salt_no = no_salt.substring(0, no_salt.indexOf("_"));
                        var salt = no_salt.substring(no_salt.indexOf("_") + 1);
                        Cookies.set('salt_no', salt_no);
                        Cookies.set('salt', salt);
                        Cookies.set('modvrf', encodeURIComponent(btoa(salt_no + '_' + CryptoJS.SHA256(modacc + modpsd + salt))));
                        localStorage.setItem('salt_no', salt_no);
                        localStorage.setItem('salt', salt);

                        loginInit(function(user_info) {
                            if (user_info.mdId) {
                                myApp.hideIndicator();
                                indexObj.modify_member_data(user_info);
                                if (user_info.md_clienttype === '1') {
                                    indexObj.querymembershopinfo();
                                } else {
                                    indexObj.init();
                                }
                            } else {
                                myApp.hideIndicator();
                                indexObj.init();
                            }
                        });
                    } else {
                        stringObj.return_header(rObj.message_no);
                        if (_tip) {
                            myApp.alert(_tip + '( ' + rObj.message_no + ' )', stringObj.text.warn);
                            _tip = null;
                        }
                    }

                }


            },
            error: function(r) {
                myApp.hideIndicator();
                //alert(JSON.stringify(r));
            }
        });
    },
    //sat效力檢查
    verify_sat: function() {
        myApp.showIndicator();
        var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
        var data = {
            modacc: stringObj.shop.moduleaccount,
            modvrf: Cookies.get('modvrf'),
            sat: mainSg.sat
        };
        //console.log(JSON.stringify(data));
        indexObj._wcfget({
            url: indexObj._dataUrl.verify_sat,
            para: data,
            success: function(r) {
                myApp.hideIndicator();
                indexObj.jsonUrlDecode(r);
                //console.log(JSON.stringify(r));
                if (r.verify_satresult) {
                    var rObj = JSON.parse(JSON.stringify(r.verify_satresult));

                    if (rObj.message_no === "010110001") {
                        var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};

                        var strUrl = location.search;
                        var getPara, ParaVal;
                        var aryPara = [];

                        if (strUrl.indexOf("sd_id") != -1) {
                            var getSearch = strUrl.split("?");
                            getPara = getSearch[1].split("&");
                            for (i = 0; i < getPara.length; i++) {
                                ParaVal = getPara[i].split("=");
                                aryPara.push(ParaVal[1]);
                            }

                            //                            mainView.router.load({
                            //                                url: 'branch-info?sd_id=' + aryPara[0],
                            //                                reload: true
                            //                            });
                            //localStorage.setItem('pageInit', 'branch-cooperative');
                            window.location = 'pm/branch-info?sd_id=' + aryPara[0];

                        } else if (strUrl.indexOf("scm_id") != -1) {
                            var getSearch = strUrl.split("?");
                            getPara = getSearch[1].split("&");
                            for (i = 0; i < getPara.length; i++) {
                                ParaVal = getPara[i].split("=");
                                aryPara.push(ParaVal[1]);
                            }

                            //                            mainView.router.load({
                            //                                url: 'shop/shopcoupon-info?scm_id=' + aryPara[0],
                            //                                reload: true
                            //                            });
                            window.location = 'pm/shopcoupon-info?scm_id=' + aryPara[0];

                        } else if (strUrl.indexOf("upload_back") != -1) {
                            mainView.router.load({
                                url: localStorage.getItem('back_url').substring(localStorage.getItem('back_url').indexOf("#!/") + 3)
                            });
                            localStorage.removeItem('back_url');
                        } else {
                            if (nowPage === undefined) {
                                if (localStorage.getItem('scg_id') === null && localStorage.getItem('pageInit') === null) {
                                    //商家/一般會員起始頁
                                    if (mainSg.md_clienttype == '1' || mainSg.md_clienttype == '2') {
                                        var branchData = indexObj._dataStorage(branchObj.storage.branchData) || {};
                                        var myshopdata = indexObj._dataStorage(indexObj._storage.myshopdata) || '';
                                        if (JSON.stringify(myshopdata).match(branchData.sd_id) && branchData.sd_type === 'shop') {
                                            mainView.router.load({
                                                url: 'shop/branch-main?sd_id=' + branchData.sd_id
                                            });
                                        } else {
                                            indexObj._dataStorage(branchObj.storage.branchData, null);
                                            mainView.router.load({
                                                url: 'shop/myBranchs',
                                                reload: true
                                            });
                                        }
                                    } else {

                                        indexObj._dataStorage(branchObj.storage.branchData, null);
                                        window.location = 'pm/branch-cooperative?cate=' + branch_cate + '&listtype=' + nowBranchType;
                                        //                                        mainView.router.load({
                                        //                                            url: 'branch-cooperative?menu=' + branch_menu + '&cate=' + branch_cate,
                                        //                                            reload: true
                                        //                                        });

                                    }
                                } else if (localStorage.getItem('pageInit') !== 'false') {
                                    mainView.router.load({
                                        url: localStorage.getItem('pageInit'),
                                        reload: true
                                    });
                                }
                            }
                            localStorage.removeItem('pageInit');
                            //                            localStorage.removeItem('scg_id');
                            //                            localStorage.removeItem('scm_id');
                            //                            indexObj._dataStorage('shop_item', null);
                            //                            indexObj._dataStorage('shop_buy_data', null);
                            //                            indexObj._dataStorage('logistics_data', null);
                        }

                    } else {

                        window.location = 'pm/branch-cooperative?cate=' + branch_cate + '&listtype=' + nowBranchType;

                        //                        mainView.router.load({
                        //                            url: 'branch-cooperative?menu=' + branch_menu + '&cate=' + branch_cate,
                        //                            reload: true
                        //                        });

                        indexObj._dataStorage(indexObj._storage.branchData, null);
                        indexObj._dataStorage(indexObj._storage.favorite, null);
                        indexObj._dataStorage(indexObj._storage.mailList, null);
                        indexObj._dataStorage(indexObj._storage.couponRecord, null);
                        indexObj._dataStorage(indexObj._storage.shopCouponRecord, null);
                        indexObj._dataStorage(indexObj._storage.shopServiceRecord, null);
                        loginType = "";
                        var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
                        if (mainSg && mainSg.murId) {

                            if (mainSg.loginType === '0') {
                                var accessToken = mainSg.sso_token;
                            }

                            indexObj._dataCookies(indexObj._storage.main, {
                                murId: mainSg.murId
                            }, 'iscarmg.com');

                        }

                        stringObj.return_header(rObj.message_no);
                        if (_tip) {
                            myApp.alert(_tip + '( ' + rObj.message_no + ' )', stringObj.text.warn);
                            _tip = null;
                        }
                        //登入頭像初始化
                        userAvatarInit();
                    }

                }


            },
            error: function(r) {
                myApp.hideIndicator();
                //console.log(JSON.stringify(r));
            }
        });
    },
    //設定用戶安全碼
    modify_memberseccode: function(old_md_securitycode, new_md_securitycode) {
        myApp.showIndicator();
        var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
        var data = {
            modacc: stringObj.shop.moduleaccount,
            modvrf: Cookies.get('modvrf'),
            md_id: mainSg.mdId,
            old_md_securitycode: old_md_securitycode,
            new_md_securitycode: new_md_securitycode
        };
        if (!old_md_securitycode) {
            data.old_md_securitycode = '';
        }
        //console.log(JSON.stringify(data));
        indexObj._wcfget({
            url: indexObj._dataUrl.modify_memberseccode,
            para: data,
            success: function(r) {
                myApp.hideIndicator();
                indexObj.jsonUrlDecode(r);
                //console.log(JSON.stringify(r));
                if (r.modify_memberseccoderesult) {
                    var rObj = JSON.parse(JSON.stringify(r.modify_memberseccoderesult));
                    if (rObj.message_no === "000000000") {

                        var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
                        mainSg.md_seccode_created = '1';
                        Cookies.set(indexObj._storage.main, JSON.stringify(mainSg), {
                            domain: 'iscarmg.com'
                        });

                        myApp.toast(stringObj.text.settingFinish, '', {}).show();
                        myApp.closeModal();


                    } else {
                        stringObj.return_header(rObj.message_no);
                        if (_tip) {
                            myApp.alert(_tip + '( ' + rObj.message_no + ' )', stringObj.text.warn);
                            _tip = null;
                        }
                    }
                }

            },
            error: function(r) {
                //console.log('error:' + JSON.stringify(r));
                myApp.hideIndicator();
            }
        });
    },
    //驗證用戶安全碼
    verify_memberseccode: function(md_securitycode) {
        myApp.showIndicator();
        var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
        var data = {
            modacc: stringObj.shop.moduleaccount,
            modvrf: Cookies.get('modvrf'),
            md_id: mainSg.mdId,
            md_securitycode: md_securitycode
        };
        //console.log(JSON.stringify(data));
        indexObj._wcfget({
            url: indexObj._dataUrl.verify_memberseccode,
            para: data,
            success: function(r) {
                myApp.hideIndicator();
                indexObj.jsonUrlDecode(r);
                //console.log(JSON.stringify(r));
                if (r.verify_memberseccoderesult) {
                    var rObj = JSON.parse(JSON.stringify(r.verify_memberseccoderesult));
                    if (rObj.message_no === "000000000") {
                        myApp.closeModal();

                        switch (nowPage) {
                            case 'pay-info':

                                if (Cookies.get('createpaymentflow') === undefined) {
                                    var logistics_data = indexObj._dataStorage('logistics_data') || {};
                                    var shop_item = indexObj._dataStorage('shop_item') || {};
                                    branchObj.shopCouponGet(shop_item.scm_id, logistics_data.from, md_securitycode);
                                } else {
                                    indexObj._dataStorage(indexObj._storage.reservationbook, null);
                                    localStorage.removeItem('scg_id');
                                    localStorage.removeItem('scm_id');
                                    indexObj._dataStorage('shop_item', null);
                                    indexObj._dataStorage('shop_buy_data', null);
                                    indexObj._dataStorage('logistics_data', null);
                                    myApp.modal({
                                        title: stringObj.text.warn,
                                        text: stringObj.text.repay_context,
                                        buttons: [{
                                            text: stringObj.text.cancel,
                                            onClick: function() {
                                                window.location = 'pm/branch-cooperative?cate=' + branch_cate + '&listtype=' + nowBranchType;
                                            }
                                        }, {
                                            text: stringObj.text.goto,
                                            onClick: function() {
                                                window.location = 'http://' + stringObj.APP_URL + '/ShoppingRecord?from=pay_info';
                                            }
                                        }, ]
                                    });
                                }

                                break;
                            case 'application-shop':

                                var popupHTML = '<div class="popup authorization-popup">' +
                                    '<div class="content-block">' +
                                    '<div class="title">' + stringObj.shop_cooperation_agreement_title + '</div>' +
                                    '<div class="authorization-content">' +
                                    '<div class="main_content_text">' +
                                    stringObj.shop_cooperation_agreement +
                                    '</div>' +
                                    '<div class="row btns"><div class="col-45 refuse close-popup">' + stringObj.text.refuse + '</div><div class="col-10"></div><div class="col-45 agree close-popup">' + stringObj.text.agree + '</div></div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                                myApp.popup(popupHTML);
                                $('.authorization-popup .sd_shopname').html($('#shop_name').val());
                                $('.agree').click(function(event) {
                                    var postshopdata = indexObj._dataCookies('postshopdata') || {};
                                    postshopdata.md_securitycode = md_securitycode;
                                    //開店申請
                                    branchObj.postshopdata(postshopdata);
                                    indexObj._dataCookies('postshopdata', null);
                                });

                                break;
                            case 'shopcoupon-management':
                                var shopcouponmanager_data = indexObj._dataCookies('shopcouponmanager_data') || {};
                                if (shopcouponmanager_data.scm_poststatus === '0') {
                                    shopcouponmanager_data.md_securitycode = md_securitycode;
                                    branchObj.shopcouponmanager(shopcouponmanager_data);
                                    indexObj._dataCookies('shopcouponmanager_data', null);
                                } else if (shopcouponmanager_data.scm_poststatus === '1') {

                                    myApp.modal({
                                        title: stringObj.shop.authorization_title,
                                        text: stringObj.shop.agree_tips + '<br><input id="shopcouponcheck" type="checkbox" style="width: 15px;height: 15px;"> 我同意',
                                        buttons: [{
                                            text: stringObj.text.send_to,
                                            onClick: function() {
                                                if ($('#shopcouponcheck').prop("checked")) {
                                                    shopcouponmanager_data.md_securitycode = md_securitycode;
                                                    branchObj.shopcouponmanager(shopcouponmanager_data);
                                                    indexObj._dataCookies('shopcouponmanager_data', null);
                                                } else {
                                                    myApp.toast(stringObj.text.disagree, '', {}).show();
                                                }
                                            }
                                        }, ]
                                    });
                                    $('.agree_tips_context').css('text-align', 'left');

                                }

                                break;

                        }


                    } else {
                        stringObj.return_header(rObj.message_no);
                        if (_tip) {
                            myApp.alert(_tip + '( ' + rObj.message_no + ' )', stringObj.text.warn);
                            _tip = null;
                        }
                    }
                }

            },
            error: function(r) {
                //console.log('error:' + JSON.stringify(r));
                myApp.hideIndicator();
            }
        });
    },
    //介紹用戶加入isCar app
    post_member_introducer: function(introducer_type, introducer_id, bindobject_id) {
        myApp.showIndicator();
        var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
        var data = {
            modacc: stringObj.shop.moduleaccount,
            modvrf: Cookies.get('modvrf'),
            sat: mainSg.sat,
            introducer_type: introducer_type,
            introducer_id: introducer_id,
            bindobject_id: bindobject_id
        };
        //console.log(JSON.stringify(data));
        indexObj._wcfget({
            url: indexObj._dataUrl.post_member_introducer,
            para: data,
            success: function(r) {
                myApp.hideIndicator();
                indexObj.jsonUrlDecode(r);
                //console.log(JSON.stringify(r));
                if (r.post_member_introducerresult) {
                    var rObj = JSON.parse(JSON.stringify(r.post_member_introducerresult));
                    if (rObj.message_no === "000000000") {

                        myApp.alert(stringObj.text.introduce_completed, stringObj.text.warn);

                    } else {
                        stringObj.return_header(rObj.message_no);
                        if (_tip) {
                            myApp.alert(_tip + '( ' + rObj.message_no + ' )', stringObj.text.warn);
                            _tip = null;
                        }
                    }
                }

            },
            error: function(r) {
                //console.log('error:' + JSON.stringify(r));
                myApp.hideIndicator();
            }
        });
    }


};


/**
 * 刪除陣列中指定Key與value該筆序列資料
 * @param array 欲刪除陣列
 * @param property Key名稱
 * @param value value值
 */
findAndRemove = function(array, property, value) {
    array.forEach(function(result, index) {
        if (result[property] === value) {
            //Remove from array
            array.splice(index, 1);
        }
    });
};


/**
 * 查詢陣列中指定Key與value該筆序列值
 * @param array 欲查詢陣列
 * @param property Key名稱
 * @param value value值
 */
findArrayItem = function(array, property, value) {
    array.forEach(function(result, index) {
        if (result[property] === value) {
            //Remove from array
            nowIndex = index;
        }
    });
};


/**
 * 開啟子頁
 * @param url 子頁路徑
 */
hrefTo = function(url) {
    mainView.router.load({
        url: url
    });
};


/**
 * iOS Web更新(已棄用)
 * @param data 更新版本號與更新檔路徑
 */
iOnJSHTMLUpdate = function(data) {
    if (isMobile.iOS()) {
        var obj = jQuery.parseJSON(data);
        myApp.modal({
            title: stringObj.text.warn,
            text: stringObj.text.iosWebUpdateContext,
            buttons: [{
                text: stringObj.text.confirm,
                onClick: function() {
                    iPhone.ConfirmJSHTMLUpdate(obj.version, obj.url)
                }
            }, ]
        });

    }
};


/**
 * iOS版本號
 * @param version 底層版本號
 */
getVersionName = function(version) {
    app_version = version;
    Cookies.set('app_version', app_version);
    $$(".versionName").html(version);
};

/**
 * iOS開啟掃描器
 */
StartQR = function() {
    if (isMobile.iOS()) {
        iPhone.StartQR();
    }
};


setQRCode = function(QR) {
    if (isMobile.iOS()) {
        myApp.alert(QR, "QR Code");
    }
};


/**
 * 無網路時的彈出訊息
 */
noNetwork = function() {
    toast.show(stringObj.text.noNetwork);
    $('.toast-container').css('color', '#F26531');
    $('.toast-container').css('top', '50%');
    $('.toast-container').css('left', '45%');
    $('.toast-container').css('background-color', 'rgba(30,30,30,.85)');
    if (browser_width < 992) {
        $('.toast-container').css('width', '40%');
    } else {
        $('.toast-container').css('width', '18%');
    }

};


/**
 * 廠商QR碼掃描
 * @param type 掃描類型
 */
scan = function(type) {
    scan_from = type;
    var businessIsLogin = localStorage.getItem(indexObj._storage.businessIsLogin);
    var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
    if (mainSg.sat) {
        if (isMobile.Android() && type == 'business' && businessIsLogin == 'Yes') {
            Android.qrScan();
        } else if (isMobile.iOS() && type == 'business' && businessIsLogin == 'Yes') {
            iPhone.StartQR();
        } else if (isMobile.Android() && type == 'branch') {
            Android.qrScan();
        } else if (isMobile.iOS() && type == 'branch') {
            iPhone.StartQR();
        } else if (isMobile.Android() && type === 'add-staff') {
            Android.qrScan();
        } else if (isMobile.iOS() && type == 'add-staff') {
            iPhone.StartQR();
        } else {
            webview.scan();
        }
        // } else if (type == 'business') {
        //     myApp.modal({
        //         title: stringObj.text.warn,
        //         text: stringObj.text.notLogin,
        //         buttons: [{
        //             text: stringObj.text.cancel,
        //             onClick: function() {}
        //         }, {
        //             text: stringObj.text.login,
        //             onClick: function() {
        //                 mainView.router.load({
        //                     url: 'business-login'
        //                 });
        //             }
        //         }, ]
        //     });
        // } else {
        //     myApp.modal({
        //         title: stringObj.text.warn,
        //         text: stringObj.text.notLogin,
        //         buttons: [{
        //             text: stringObj.text.cancel,
        //             onClick: function() {}
        //         }, {
        //             text: stringObj.text.login,
        //             onClick: function() {
        //                 webview.loginPage();
        //             }
        //         }, ]
        //     });

        // }
    } else {
        myApp.modal({
            title: stringObj.text.warn,
            text: stringObj.text.notLogin,
            buttons: [{
                text: stringObj.text.cancel,
                onClick: function() {}
            }, {
                text: stringObj.text.login,
                onClick: function() {
                    webview.loginPage();
                }
            }, ]
        });
    }

};


/**
 * iOS有收到推播時
 */
getMessage = function() {
    var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
    window.location = 'http://' + stringObj.WEB_URL + '/Mailbox/www/transform?user_info=' + encodeURIComponent(JSON.stringify(mainSg));
};


/**
 * iOS有收到預約提醒時
 * @param msg 訊息內容
 */
getAlarmMessage = function(msg) {
    var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
    if (msg.match('crn_id')) {
        var reservationRecord = indexObj._dataStorage(usedCarObj.storage.reservationRecord);
        findArrayItem(reservationRecord.availableList, 'crn_id', msg.substring(6));
        if (mainSg.md_clienttype == '0') {
            startPage = 'used-car/reservation-info?crn_id=' + msg.substring(6) + '&cbi_id=' + reservationRecord.availableList[nowIndex].cbi_id + '&from=record-c';
        } else if (mainSg.md_clienttype == '1') {
            startPage = 'used-car/reservation-info?crn_id=' + msg.substring(6) + '&cbi_id=' + reservationRecord.availableList[nowIndex].cbi_id + '&from=record-b';
        }
    } else {
        var couponRecord = indexObj._dataStorage(indexObj._storage.couponRecord);
        findArrayItem(couponRecord.availableList, 'cdd_qr', msg);
        startPage = 'coupon-content?cdmId=' + couponRecord.availableList[nowIndex].cdm_id + '&type="available"&cdd_qr=' + msg;
    }
};


/**
 * onStop再點擊GCM訊息
 */
onRestart = function() {
    var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
    if (mainSg.sat) {
        window.location = 'http://' + stringObj.APP_URL + '/Mailbox/transform?user_info=' + encodeURIComponent(JSON.stringify(mainSg));
    }
};



/**
 * JSON陣列排序
 * @param  propertyName 欄位名稱
 * @param  sequence     正序/倒序(1/-1)
 * @return sortFun
 */
sortByProperty = function(propertyName, sequence) {

    var sortFun = function(obj1, obj2) {
        if (obj1[propertyName] > obj2[propertyName] && sequence === 1) {
            return 1;
        } else if (obj1[propertyName] < obj2[propertyName] && sequence === -1) {
            return 1;
        } else if (obj1[propertyName] == obj2[propertyName]) {
            return 0;
        } else {
            return -1;
        }
    }

    return sortFun;
};


/**
 * 綁定確認
 * @param type 綁定類別
 */
bindingCheck = function(type) {

    $$('.panel-right nav li').removeClass('active');
    $$('.panel-right nav li .item-title').each(function(index, el) {
        if ($$(el).text() == stringObj.menu.fbBinding) {
            $$(el).parents('li').addClass('active');
        }
    });

    switch (type) {
        case 'facebook':
            myApp.modal({
                title: stringObj.text.warn,
                text: stringObj.text.fbBindingContext,
                buttons: [{
                    text: stringObj.text.cancel,
                    onClick: function() {

                    }
                }, {
                    text: stringObj.text.binding,
                    onClick: function() {
                        fbEvent = 'binding';
                        webview.fbLogin();
                    }
                }]
            });
            break;
    }

};


/**
 * 掃描後
 * @param msg 掃描資訊內容
 */
scanMsg = function(msg) {
    //console.log(JSON.stringify(msg));
    if (scan_from == 'invitejoinclub') {
        if (msg.match('member_card_id')) {
            var jsonObj = JSON.parse(msg);
            var mainSg = indexObj._dataCookies(indexObj._storage.main);
            var data = {
                modacc: stringObj.shop.moduleaccount,
                modvrf: Cookies.get('modvrf'),
                sat: mainSg.sat,
                ccd_id: mainSg.clubdata.ccd_id,
                invitee_id: jsonObj.member_card_id
            };
            carClubObj.invitejoinclub(data);
        } else {
            myApp.toast(stringObj.text.scan_msg_error, '', {}).show();
            $('.toast-container').css('width', '40%');
            $('.toast-container').css('left', '45%');
        }
    } else if (msg.match('member_card_id')) {
        switch (scan_from) {
            case 'branch':
                var jsonObj = JSON.parse(msg);
                /*mainView.router.load({
                    url: 'shop/bonus-gift?md_id=' + jsonObj.member_card_id,
                    reload: true
                });*/
                myApp.modal({
                        title: stringObj.text.warn,
                        text: stringObj.shop.addClient,
                        buttons: [{
                            text: stringObj.text.cancel,
                            onClick: function() {}
                        }, {
                            text: stringObj.text.confirm,
                            onClick: function() {
                                var branchData = indexObj._dataStorage(branchObj.storage.branchData) || {};
                                indexObj.post_member_introducer(1, branchData.sd_id, jsonObj.member_card_id);
                                //addBookmarks(branchData.sd_id, '', '', '', '', '', 2);
                            }
                        }, ]
                    });
                break;
            case 'business':
                var jsonObj = JSON.parse(msg);
                var branch_md_id = jsonObj.member_card_id;
                mainView.router.load({
                    url: 'branch-binding?md_id=' + branch_md_id
                });
                break;
            case 'add-staff':
                var jsonObj = JSON.parse(msg);
                branchObj.querymemberidinfo(jsonObj.member_card_id);
                break;
        }
    } else if (scan_from === 'report') {
        var msgObj = JSON.parse(msg);
        branchObj.querysclid(msgObj.scl_id);
    } else if (msg.match('scg_id')) {
        var msgObj = JSON.parse(msg);
        branchObj.shopcouponscan(msgObj.scm_id, msgObj.scg_id);
    } else if (msg.match('ssqd_id')) {
        var msgObj = JSON.parse(msg);
        branchObj.shopservicescan(msgObj.ssqd_id, msgObj.ssqq_id, msgObj.ssqq_queserno);
    } else if (msg.match('cdm_id')) {
        mainView.router.load({
            url: 'scan-info?msg=' + msg
        });
    } else if (msg.match('temple_donate')) {
        var msgObj = JSON.parse(msg);
        switch (msgObj.temple_name) {
            case '行天宮':
                mainView.router.load({
                    url: 'temple-info-1?from=scan&tp_id=1'
                });
                break;
            case '大甲鎮瀾宮':
                mainView.router.load({
                    url: 'temple-info-2?from=scan&tp_id=2'
                });
                break;
            case '鹿港天后宮':
                mainView.router.load({
                    url: 'temple-info-3?from=scan&tp_id=3'
                });
                break;
        }

    } else {
        myApp.toast(stringObj.text.scan_msg_error, '', {}).show();
        $('.toast-container').css('width', '40%');
        $('.toast-container').css('left', '45%');
    }
};


/**
 * 加入我的最愛
 * @param id 新聞/活動/商家編號
 * @param imagePath 主圖路徑
 * @param title 標題
 * @param catName 分類名稱
 * @param author 作者
 * @param createDate 建立日期
 * @param type 書籤類別
 */
addFavorite = function(id, imagePath, title, catName, author, createDate, type) {

    indexObj.jsonUrlDecode(title);


    var mainInfo = indexObj._dataCookies(indexObj._storage.main) || {};

    if (mainInfo.sat !== undefined) {

        var favoriteList = indexObj._dataStorage(indexObj._storage.favorite) || {
            lastupdate: '',
            newsList: [],
            couponList: [],
            branchList: [],
            shopcouponList: []
        };

        var favoriteObj;
        switch (type) {
            case '0':
                //新聞
                favoriteObj = JSON.stringify(favoriteList.newsList);
                break;
            case '1':
                //旗艦館活動券
                favoriteObj = JSON.stringify(favoriteList.couponList);
                break;
            case '2':
                //商家
                favoriteObj = JSON.stringify(favoriteList.branchList);
                break;
            case '3':
                //商家活動券
                favoriteObj = JSON.stringify(favoriteList.shopcouponList);
                break;
        }

        //console.log(id);

        if (favoriteObj.match(id)) {
            myApp.modal({
                title: stringObj.text.warn,
                text: stringObj.text.marksRemoveCheck,
                buttons: [{
                    text: stringObj.text.cancel
                }, {
                    text: stringObj.text.confirm,
                    onClick: function() {

                        //移除放大動畫類別
                        $('.favorite-' + id).removeClass("expandOpen");

                        var mainSg = indexObj._dataCookies(indexObj._storage.main);
                        var data = {
                            modacc: stringObj.shop.moduleaccount,
                            modvrf: Cookies.get('modvrf'),
                            sat: mainSg.sat,
                            useroperate: '1',
                            ubm_objecttype: type,
                            ubm_objectid: id
                        };

                        myApp.showIndicator();
                        indexObj._wcfget({
                            url: indexObj._dataUrl.userbookmarkupdate,
                            para: data,
                            success: function(r) {
                                if (r.userbookmarkupdateresult) {
                                    var rObj = JSON.parse(JSON.stringify(r.userbookmarkupdateresult));
                                    if (rObj.message_no === "000000000") {

                                        if (type === '0') {
                                            findAndRemove(favoriteList.newsList, 'ubm_objectid', id);
                                            if (isMobile.Android()) {
                                                webview.nowEvent('nsdelmark-And', id, md_id);
                                            } else if (isMobile.iOS()) {
                                                webview.nowEvent('nsdelmark-iOS', id, md_id);
                                            }
                                        } else if (type === '1') {
                                            findAndRemove(favoriteList.couponList, 'ubm_objectid', id);
                                            if (isMobile.Android()) {
                                                webview.nowEvent('cpdelmark-And', id, md_id);
                                            } else if (isMobile.iOS()) {
                                                webview.nowEvent('cpdelmark-iOS', id, md_id);
                                            }
                                        } else if (type === '2') {
                                            findAndRemove(favoriteList.branchList, 'ubm_objectid', id);
                                        } else if (type === '3') {
                                            findAndRemove(favoriteList.shopcouponList, 'ubm_objectid', id);
                                        }

                                        if (nowPage === 'post' || nowPage === 'favorite-post' || nowPage === 'mail-post') {
                                            $$('.favorite-' + id + ' span').html('<i class="fa fa-star-o"></i>');
                                            $$('.favorite-' + id + ' span').css('color', '#fff');
                                        } else if (nowPage === 'branch-cooperative' || nowPage === 'branch-info' || nowPage === 'shop-search-result' || nowPage === 'around-search-result') {
                                            myApp.alert(stringObj.text.nowCoin + '：' + rObj.gpmr_point, stringObj.text.warn);
                                            $$('.favorite-' + id).html('<img src="../app/image/unsubscribe.png" onerror=\'this.src="app/image/imgDefault.png"\' />');
                                        } else {
                                            $$('.favorite-' + id).html('<i class="fa fa-star-o"></i>');
                                        }

                                        indexObj._dataStorage(indexObj._storage.favorite, favoriteList);


                                    } else {
                                        stringObj.return_header(rObj.message_no);
                                        if (_tip) {
                                            myApp.alert(_tip + '( ' + rObj.message_no + ' )', stringObj.text.warn);
                                            _tip = null;
                                        }
                                    }
                                    myApp.hideIndicator();
                                }
                            },
                            error: function(r) {
                                myApp.hideIndicator();
                                noNetwork();
                            }
                        });
                    }
                }, ]
            });
        } else {

            switch (type) {
                case '2':
                    //商家
                    myApp.modal({
                        title: stringObj.text.warn,
                        text: stringObj.shop.iscarpolicy,
                        buttons: [{
                            text: stringObj.text.refuse,
                            onClick: function() {}
                        }, {
                            text: stringObj.text.agree,
                            onClick: function() {
                                addBookmarks(id, imagePath, title, catName, author, createDate, type);
                            }
                        }, ]
                    });

                    $('.modal .query_iscarpolicy').click(function() {
                        var popupHTML = '<div class="popup popup-iscarpolicy">' +
                            '<div class="close-btn">' +
                            '<a href="#" class="close-popup">' +
                            '<i class="fa fa-times" aria-hidden="true"></i>' +
                            '</a>' +
                            '</div>' +
                            '<div class="content-block">' +
                            '<div class="title">' + '『就是行』汽車特店會員服務條款' + '</div>' +
                            '<div class="iscarpolicy-content">' +
                            '<div class="main_content_text">' +
                            stringObj.shop_service_contract +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';
                        myApp.popup(popupHTML);
                        $('.popup-iscarpolicy').css('z-index', '91000');
                    });

                    break;
                default:
                    addBookmarks(id, imagePath, title, catName, author, createDate, type);
                    break;
            }



        }


    } else {
        myApp.modal({
            title: stringObj.text.warn,
            text: stringObj.text.notLogin,
            buttons: [{
                text: stringObj.text.cancel,
                onClick: function() {}
            }, {
                text: stringObj.text.login,
                onClick: function() {
                    webview.loginPage();
                }
            }, ]
        });
    }

};


/**
 * 加入書籤
 * @param id 新聞/活動/商家編號
 * @param imagePath 主圖路徑
 * @param title 標題
 * @param catName 分類名稱
 * @param author 作者
 * @param createDate 建立日期
 * @param type 書籤類別
 */
addBookmarks = function(id, imagePath, title, catName, author, createDate, type) {
    var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
    var data = {
        modacc: stringObj.shop.moduleaccount,
        modvrf: Cookies.get('modvrf'),
        sat: mainSg.sat,
        useroperate: '0',
        ubm_objecttype: type,
        ubm_objectid: id
    };
    //console.log(JSON.stringify(data));
    myApp.showIndicator();
    indexObj._wcfget({
        url: indexObj._dataUrl.userbookmarkupdate,
        para: data,
        success: function(r) {
            //console.log(JSON.stringify(r));
            if (r.userbookmarkupdateresult) {
                var rObj = JSON.parse(JSON.stringify(r.userbookmarkupdateresult));
                if (rObj.message_no === "000000000" || rObj.message_no === "011601002") {
                    if (nowPage === 'post' || nowPage === 'favorite-post' || nowPage === 'mail-post') {
                        $$('.favorite-' + id + ' span').html('<i class="fa fa-star"></i>');
                        $$('.favorite-' + id + ' span').css('color', '#FFFF3B');
                    } else if (nowPage === 'branch-cooperative' || nowPage === 'branch-info' || nowPage === 'shop-search-result' || nowPage === 'around-search-result') {
                        if (rObj.message_no === "000000000") {
                            myApp.alert(stringObj.text.nowCoin + '：' + rObj.gpmr_point, stringObj.text.subscription_success);
                        } else {
                            stringObj.return_header(rObj.message_no);
                            if (_tip) {
                                myApp.alert(_tip, stringObj.text.subscription_success);
                                _tip = null;
                            }
                        }
                        //新增放大動畫類別
                        $('.favorite-' + id).addClass("expandOpen");
                        $('.favorite-' + id).html('<img src="../app/image/subscribe.png" onerror=\'this.src="app/image/imgDefault.png"\' />');
                    } else {

                        //新增放大動畫類別
                        $('.favorite-' + id).addClass("expandOpen");

                        $$('.favorite-' + id).html('<i class="fa fa-star"></i>');
                    }

                    var favoriteList = indexObj._dataStorage(indexObj._storage.favorite) || {
                        lastupdate: '',
                        newsList: [],
                        couponList: [],
                        branchList: [],
                        shopcouponList: []
                    };

                    var favoriteItem = {};
                    favoriteItem.ubm_objectid = id;
                    favoriteItem.ubm_picpath = imagePath;
                    favoriteItem.ubm_title = title;
                    favoriteItem.catName = catName;
                    favoriteItem.author = author;
                    if (type === '3') {
                        favoriteItem.create_date = createDate;
                    } else {
                        favoriteItem.create_date = createDate.substring(0, 10);
                    }
                    favoriteItem.isflag = '1';
                    favoriteItem.date = new Date(createDate) / (1000 * 60 * 60 * 24);

                    if (type === '0') {

                        favoriteList.newsList.push(favoriteItem);

                        if (isMobile.Android()) {
                            webview.nowEvent('nsaddmark-And', id, md_id);
                        } else if (isMobile.iOS()) {
                            webview.nowEvent('nsaddmark-iOS', id, md_id);
                        }
                    } else if (type === '1') {

                        favoriteList.couponList.push(favoriteItem);

                        if (isMobile.Android()) {
                            webview.nowEvent('cpaddmark-And', id, md_id);
                        } else if (isMobile.iOS()) {
                            webview.nowEvent('cpaddmark-iOS', id, md_id);
                        }
                    } else if (type === '2') {
                        favoriteList.branchList.push(favoriteItem);
                    } else if (type === '3') {
                        favoriteList.shopcouponList.push(favoriteItem);
                    }

                    indexObj._dataStorage(indexObj._storage.favorite, favoriteList);


                } else {
                    stringObj.return_header(rObj.message_no);
                    if (_tip) {
                        myApp.alert(_tip + '( ' + rObj.message_no + ' )', stringObj.text.warn);
                        _tip = null;
                    }
                }
                myApp.hideIndicator();
            }
        },
        error: function(r) {
            //console.log(JSON.stringify(r));
            myApp.hideIndicator();
            noNetwork();
        }
    });
};


/**
 * 刪除我的最愛
 * @param id 書籤編號
 * @param type 書籤類別
 */
removeFavorite = function(id, type) {
    $$('.swipeout-' + id).on('deleted', function() {
        var favoriteList = indexObj._dataStorage(indexObj._storage.favorite);
        var mainSg = indexObj._dataCookies(indexObj._storage.main);
        var data = {
            modacc: stringObj.shop.moduleaccount,
            modvrf: Cookies.get('modvrf'),
            sat: mainSg.sat,
            useroperate: '1',
            ubm_objecttype: type,
            ubm_objectid: id
        };

        myApp.showIndicator();
        indexObj._wcfget({
            url: indexObj._dataUrl.userbookmarkupdate,
            para: data,
            success: function(r) {
                if (r.userbookmarkupdateresult) {
                    var rObj = JSON.parse(JSON.stringify(r.userbookmarkupdateresult));
                    if (rObj.message_no === "000000000") {

                        if (type === '0') {
                            findAndRemove(favoriteList.newsList, 'ubm_objectid', id);
                            if (isMobile.Android()) {
                                webview.nowEvent('nsdelmark-And', id, md_id);
                            } else if (isMobile.iOS()) {
                                webview.nowEvent('nsdelmark-iOS', id, md_id);
                            }
                        } else if (type === '1') {
                            findAndRemove(favoriteList.couponList, 'ubm_objectid', id);
                            if (isMobile.Android()) {
                                webview.nowEvent('cpdelmark-And', id, md_id);
                            } else if (isMobile.iOS()) {
                                webview.nowEvent('cpdelmark-iOS', id, md_id);
                            }
                        } else {
                            findAndRemove(favoriteList.branchList, 'ubm_objectid', id);
                        }

                        if (nowPage === 'post' || nowPage === 'favorite-post' || nowPage === 'mail-post') {
                            $$('.favorite-' + id + ' span').html('<i class="fa fa-star-o"></i>');
                            $$('.favorite-' + id + ' span').css('color', '#fff');
                        } else if (nowPage === 'branch-info') {
                            $$('.track a').html('追蹤');
                            $$('.track a').css('background-color', '#F26531');
                        } else if (nowPage === 'branch-cooperative' || nowPage === 'branch-info' || nowPage === 'shop-search-result' || nowPage === 'around-search-result') {
                            myApp.alert(stringObj.text.nowCoin + '：' + rObj.gpmr_point, stringObj.text.warn);
                            $$('.favorite-' + id).html('<img src="../app/image/unsubscribe.png" onerror=\'this.src="app/image/imgDefault.png"\' />');
                        } else {
                            $$('.favorite-' + id).html('<i class="fa fa-star-o"></i>');
                        }

                        if (nowPage === 'favorite') {
                            myApp.alert(stringObj.text.nowCoin + '：' + rObj.gpmr_point, stringObj.text.warn);
                        }


                        indexObj._dataStorage(indexObj._storage.favorite, favoriteList);


                        if (favoriteList.newsList.length === 0) {
                            var newsTemp = indexObj.template('templateFavoriteListNull');
                            var newsItem = newsTemp('');
                            $$('.news').html(newsItem);
                        }

                        if (favoriteList.couponList.length === 0) {
                            var couponTemp = indexObj.template('templateFavoriteListNull');
                            var couponItem = couponTemp('');
                            $$('.couponList').html(couponItem);
                        }

                        if (favoriteList.branchList.length === 0) {
                            var branchTemp = indexObj.template('templateFavoriteListNull');
                            var branchItem = branchTemp('');
                            $$('.branchList').html(branchItem);
                        }

                        if (favoriteList.shopcouponList.length === 0) {
                            var shopTemp = indexObj.template('templateFavoriteListNull');
                            var shopItem = shopTemp('');
                            $$('.shopcouponList').html(shopItem);
                        }

                    } else {
                        stringObj.return_header(rObj.message_no);
                        if (_tip) {
                            myApp.alert(_tip + '( ' + rObj.message_no + ' )', stringObj.text.warn);
                            _tip = null;
                        }
                    }
                    myApp.hideIndicator();
                }
            },
            error: function(r) {
                myApp.hideIndicator();
                noNetwork();
            }
        });

    });

};


/**
 * 當前書籤類別
 * @param type 書籤類別
 */
favoriteType = function(type) {
    fType = type;
};

// Initialize app
var myApp = new Framework7({
    swipeBackPage: false,
    pushState: true,
    pushStateNoAnimation: true,
    swipePanel: 'left',
    swipePanelActiveArea: -1,
    imagesLazyLoadPlaceholder: '../app/image/imgDefault.png',
    imagesLazyLoadThreshold: 150,
    animatePages: false,
    materialRipple: false,
    modalButtonOk: stringObj.text.confirm,
    modalButtonCancel: stringObj.text.cancel
});


// Initialize Swiper
var swiper = new Swiper('.ad-block', {
    nextButton: '.swiper-button-next',
    prevButton: '.swiper-button-prev',
    centeredSlides: true,
    autoplay: 1500,
    autoplayDisableOnInteraction: false,
    loop: true
});

// If we need to use custom DOM library, let's save it to $$ variable:
var $$ = Dom7;

var mainView = myApp.addView('.view-main', {
    dynamicNavbar: true
});

//document start
$(document).ready(function() {
    indexObj.query_salt(stringObj.shop.moduleaccount, stringObj.shop.modulepassword);
    $$(document).on('open', '.modal', function(e) {
        if ($(this).find('h1#cdd-qrcode').length) {
            $(this).css('top', '150px');
        }
    });

});


$$('.panel-left').on('open', function() {
    $$('.left a').html('<i class="fa fa-angle-double-left"></i>');
    if (nowPage == 'branch-info') {
        var mainSg = indexObj._dataCookies(indexObj._storage.main);
        if (mainSg.md_clienttype === '1') {
            $('.back-btn a').html('<i class="fa fa-angle-double-left"></i>');
        }
    }
});
$$('.panel-left').on('close', function() {
    $$('.left a').html('<span class="kkicon icon-menu"></span>');
    if (nowPage == 'branch-info') {
        var mainSg = indexObj._dataCookies(indexObj._storage.main);
        if (mainSg.md_clienttype === '1') {
            $('.back-btn a').html('<span class="kkicon icon-menu"></span>');
        }
    }
});


//視窗開啟背景模糊設置
$$(document).on('open', '.modal', function(e) {
    $('.view').css('-webkit-filter', 'blur(5px)');
});
$$(document).on('close', '.modal', function(e) {
    $('.view').css('-webkit-filter', 'blur(0px)');
});



//汽車特店選單開啟時
$$('.shop-menu').on('open', function() {
    //stringObj.shop.isBusiness = true;
    var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
    if (mainSg.md_clienttype === '1') {
        stringObj.shop.isShop = true;
    } else if (mainSg.md_clienttype == '100') {
        stringObj.shop.isBusiness = true;
    } else {
        stringObj.shop.isShop = false;
    }
    if (mainSg.sat) {
        stringObj.shop.signed_in = true
    }
    var temp = indexObj.template('templateShopMenu');
    var item = temp(stringObj.shop);
    $$('.contentBlock').html(item);

    $('.shop_list').click(function() {
        window.location = 'pm/branch-cooperative';
    });

    $('.shop-menu a').click(function() {
        myApp.closeModal('.shop-menu');
        //        mainView.router.load({
        //            url: $(this).attr('href'),
        //            reload: true
        //        });
    });
});



var mySwiper;
var toast = myApp.toast('message', '<i class="fa fa-exclamation-triangle"></i>', {});

//var menuID = '0';
//var nowCate = '0';
//page start
// $$(document).on('pageInit', function(e) {
//     var page = e.detail.page;
//     //console.log(page.name);

//     if (browser_width > 992) {
//         //美化scroll bar
//         $(".page-content").niceScroll({
//             cursorcolor: "rgba(100,100,100,.9)",
//             cursoropacitymin: 0,
//             cursorborder: "1px solid #000",
//             scrollspeed: 20
//         });
//     }

//     $('.navbar .left').click(function() {
//         //取得iOS GPS
//         if (isMobile.iOS()) {
//             webview.getLocation();
//         }
//     });

//     nowPage = page.name;

//     switch (page.name) {
//         case 'shop-menu':
//             indexObj.shopMenuInit(page);
//             break;

//         case 'coupon-record':
//             indexObj.couponRecordInit(page);
//             userAvatarInit();
//             break;

//     }
//     myApp.params.swipePanelActiveArea = -1;
//     //indexObj._fbInit();
// });

//custom expansion
$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

jQuery.extend(jQuery.validator.defaults, {
    errorPlacement: function(error, element) {
        if (element.is(':radio') || element.is(':checkbox')) {
            element.parents('.form-row').append(error);
        } else {
            error.insertAfter(element);
        }
    }
});
jQuery.extend(jQuery.validator.messages, {
    required: stringObj.text.required,
    email: stringObj.text.emailFormatError
});
jQuery.validator.addMethod("phoneonly", function(value, element) {
    return this.optional(element) || /^[\d\-\+\#*]+$/i.test(value);
}, stringObj.text.telFormatError);
jQuery.validator.addMethod("usualword", function(value, element) {
    return this.optional(element) || !/[`~,.<>;':"\/\[\]\|{}()=_+-]/.test(value);
}, stringObj.text.textFormatError);

Date.prototype.yyyymmdd = function() {
    var yyyy = this.getFullYear().toString();
    var mm = (this.getMonth() + 1).toString(); // getMonth() is zero-based
    var dd = this.getDate().toString();
    return yyyy + '/' + (mm[1] ? mm : "0" + mm[0]) + '/' + (dd[1] ? dd : "0" + dd[0]); // padding
};
Date.prototype.yyyymmddhhmmss = function() {
    var hh = this.getHours().toString();
    var mm = this.getMinutes().toString(); // getMonth() is zero-based
    var ss = this.getSeconds().toString();
    return this.yyyymmdd() + ' ' + (hh[1] ? hh : "0" + hh[0]) + ':' + (mm[1] ? mm : "0" + mm[0]) + ':' + (ss[1] ? ss : "0" + ss[0]); // padding
};


/**
 * 開啟新Web畫面
 * @param url 網址路徑
 */
toWeb = function(url) {

    if (isMobile.Android()) {
        Android.toWeb(url);
    } else if (isMobile.iOS()) {
        iPhone.toWeb(url);
    }
};


/**
 * 無資料訊息
 * @param template template id名稱
 * @param text 文字訊息
 * @param where 指定class名稱
 */
dataNull = function(template, text, where) {
    var info = {
        text: text
    };
    var temp = indexObj.template(template);
    var item = temp(info);
    $$(where).html(item);
};


/**
 * 登出
 */
logout = function() {
    myApp.modal({
        title: stringObj.text.warn,
        text: stringObj.text.logoutCheck,
        buttons: [{
            text: stringObj.text.cancel,
            onClick: function() {}
        }, {
            text: stringObj.text.confirm,
            onClick: function() {
                indexObj._userLogout();
            }
        }]
    });
};


/**
 * 登入狀態確認
 * @param page 頁面名稱
 */
loginStatus = function(page) {
    var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
    if (mainSg.mdId) {
        switch (page) {
            case 'activity_preferential':

                //活動優惠頁面

                break;
            case 'bonus_record':
                //紅利管理頁面
                mainView.router.load({
                    url: 'shop/bonus-record',
                    reload: true
                });
                break;
            case 'shop_favorite':
                //商家書籤
                mainView.router.load({
                    url: 'shop/favorite',
                    reload: true
                });
                break;
            case 'coupon_record':
                //消費紀錄
                mainView.router.load({
                    url: 'coupon-record',
                    reload: true
                });
                break;
            case 'open_store':
                //我要開店
                var applicationData = indexObj._dataStorage(branchObj.storage.applicationData) || '';
                if (applicationData) {
                    myApp.modal({
                        title: stringObj.text.application_data_record,
                        text: '<div class="row"><div class="col-28">店名：</div><div class="col-72">' + applicationData.sd_shopname + '</div></div>' +
                            '<div class="row"><div class="col-28">狀態：</div><div class="col-72">' + stringObj.text.not_finish + '</div></div>',
                        buttons: [{
                            text: stringObj.text._delete,
                            onClick: function() {
                                indexObj._dataStorage(branchObj.storage.applicationData, null);
                            }
                        }, {
                            text: stringObj.text._continue,
                            onClick: function() {
                                if (applicationData.operation_type == '0') {
                                    mainView.router.load({
                                        url: 'application-shop',
                                        reload: true
                                    });
                                } else if (applicationData.operation_type == '1') {
                                    mainView.router.load({
                                        url: 'application-usedcar',
                                        reload: true
                                    });
                                }

                            }
                        }]
                    });

                    $('.modal-title + .modal-text').css('text-align', 'left');
                    $('.modal-title + .modal-text').css('margin', '5% 3%');
                    $('.modal-title + .modal-text .row').css('margin', '3% 0');
                    $('.modal-title + .modal-text .row .col-28').css('width', '28%');
                    $('.modal-title + .modal-text .row .col-28').css('color', 'darkslategray');
                    $('.modal-title + .modal-text .row .col-72').css('width', '72%');

                } else {
                    mainView.router.load({
                        url: 'application-shop'
                    });
                }

                break;
        }


    } else {
        myApp.modal({
            title: stringObj.text.warn,
            text: stringObj.text.notLogin,
            buttons: [{
                text: stringObj.text.cancel,
                onClick: function() {}
            }, {
                text: stringObj.text.login,
                onClick: function() {
                    localStorage.setItem('from', 'Shop');
                    webview.loginPage();
                }
            }, ]
        });
    }

};


/**
 * 活動服務內容查看-會員身分檢查
 * @param id 編號
 * @param type 項目類別
 * @param qr QR編號
 */
loginCheck = function(id, type, qr) {

    var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
    if (mainSg.loginType === '0' || mainSg.loginType === '3') {

        switch (nowPage) {
            case 'coupon-record':
                if (type == 'shop_available' || type == 'shop_finish' || type == 'shop_invalid' || type == 'reserved') {
                    //                    mainView.router.load({
                    //                        url: 'shop/shopcoupon-info?scm_id=' + id + '&type=' + type + '&scg_id=' + qr
                    //                    });
                    window.location = 'pm/shopcoupon-info?scm_id=' + id + '&type=' + type + '&scg_id=' + qr;
                } else if (type == 'service_available' || type == 'service_finish' || type == 'service_invalid') {
                    mainView.router.load({
                        url: 'shop/shopservice-info?ssqd_id=' + id + '&type=' + type + '&ssqq_id=' + qr
                    });
                }
                break;
            case 'favorite':
                mainView.router.load({
                    url: 'favorite-coupon-content?cdmId=' + id + '&type=' + type + '&cdd_qr=' + qr
                });
                break;
        }

    } else {
        myApp.modal({
            title: stringObj.text.warn,
            text: stringObj.text.notLogin,
            buttons: [{
                text: stringObj.text.cancel,
                onClick: function() {}
            }, {
                text: stringObj.text.login,
                onClick: function() {
                    webview.loginPage();
                }
            }, ]
        });
    }
};



/**
 * 建立code39視窗
 * @param code 銷貨編號
 */
code39 = function(code) {
    if (code != ' ') {
        if (nowPage == 'server-main' && isMobile.Android()) {
            //myApp.alert(r);
        }

        myApp.modal({
            title: '',
            text: '<div class="member-block">' +
                '<div class="code39-title">' + stringObj.text.code39_title + '</div><br>' +
                '<div class="barcode39" style="width:200px; height:80px; margin: 0% auto;">' +
                code +
                '</div>' +
                '<p>' + code + '</p>' +
                '</div>',
            buttons: [{
                text: stringObj.text.confirm,
                bold: true
            }, ]
        })

        $('.barcode39').barcode({
            code: 'code39'
        });
    }

};


//建立parameter
setParameter = function(mur) {
    if (_region === 'tw') {
        var parameter = {
            mur: mur,
            modacc: stringObj.shop.moduleaccount,
            modvrf: CryptoJS.SHA256(stringObj.shop.moduleaccount + stringObj.shop.modulepassword).toString(),
            redirect_uri: 'http://' + stringObj.WEB_URL + '/pm-transform'
        };
    } else {
        var parameter = {
            mur: mur,
            modacc: _region + '_' + stringObj.shop.moduleaccount,
            modvrf: CryptoJS.SHA256(_region + '_' + stringObj.shop.moduleaccount + stringObj.shop.modulepassword).toString(),
            redirect_uri: 'http://' + stringObj.WEB_URL + '/pm-transform'
        };
    }
    Cookies.set('parameter', encodeURIComponent(btoa(JSON.stringify(parameter))));
};


//至頂
toTop = function() {

    var _scroll;
    var _height_limint = 0;
    $$('.to-top').hide();
    $$('.infinite-scroll').on('scroll', function(event) {
        var _me = this;
        clearTimeout(_scroll);
        _scroll = setTimeout(function() {
            var _height = $$(_me).scrollTop();
            if (_height > 200) {
                if (_height > _height_limint) {
                    _height_limint = _height;
                    $$('.to-top').hide();
                } else {
                    if ((_height_limint - _height) > 200) {
                        $('.to-top').show();
                        setTimeout(function() {
                            $$('.to-top').hide();
                        }, 3000);
                    }
                    _height_limint = _height;
                }
            }
        }, 200);
    });

    $$('.to-top').click(function(e) {
        _height_limint = 0;
        $$(this).hide();
        $$(this).parent('.page').children('.infinite-scroll').scrollTop(0, 500);
    });

};

//未取得GPS視窗
noGpsModal = function() {
    myApp.modal({
        title: stringObj.text.warn,
        text: stringObj.shop.no_GPS,
        verticalButtons: true,
        buttons: [{
            text: stringObj.text.setting,
            onClick: function() {
                webview.openSettingGPS();
            }
        }, {
            text: stringObj.text.default_GPS,
            onClick: function() {
                if (nowPage === 'map' || nowMode === 'map') {
                    full_map.setCenter(new google.maps.LatLng(stringObj.text.default_lat, stringObj.text.default_lng));
                    Cookies.set('sd_lat', stringObj.text.default_lat);
                    Cookies.set('sd_lng', stringObj.text.default_lng);
                    for (var i = 0; i < markers.length; i++) {
                        markers[i].setMap(null);
                    }
                    markers = [];
                    branchObj.branchListGet('');
                    if (nowPage === 'map') {
                        $('.search-btn').css('display', 'none');
                    }

                    if (my_marker) {
                        my_marker.setMap(null);
                    }

                    //map marker icon
                    var image = {
                        url: 'app/image/user-marker.png'
                    };

                    //將目前位置新增至地圖
                    my_marker = new google.maps.Marker({
                        position: new google.maps.LatLng(nowlat, nowlng),
                        map: full_map,
                        title: 'Me',
                        icon: image
                    });
                }
                if (nowPage === 'branch-cooperative') {
                    nowlat = stringObj.text.default_lat;
                    nowlng = stringObj.text.default_lng;
                    var search_result = {};
                    search_result.spm_serno = 2;
                    search_result.sd_lat = stringObj.text.default_lat;
                    search_result.sd_lng = stringObj.text.default_lng;
                    search_result.sd_shopname = '';
                    search_result.sd_country = '';
                    indexObj._dataStorage(indexObj._storage.search_result, search_result);
                    mainView.router.load({
                        url: 'around-search-result'
                    });
                }
            }
        }, {
            text: stringObj.text.cancel,
            onClick: function() {}
        }]
    });
};

//用戶安全碼
memberseccode = function(type, old) {

    myApp.popup('.memberseccode');

    var nums = [];
    $('.seccode-block .col-16').html('');
    $('.num-block .col-33.num').unbind('click');
    $('.num-block .col-33.num').click(function(event) {
        if (nums.length < 6) {
            nums.push($(this).text());
            for (var i in nums) {
                $('.seccode-block .col-16').eq(i).html('·');
            }
        }
    });
    $('.num-block .col-33.del').unbind('click');
    $('.num-block .col-33.del').click(function(event) {
        if (nums.length > 0) {
            nums.splice(nums.length - 1, 1);
            $('.seccode-block .col-16').html('');
            for (var i in nums) {
                $('.seccode-block .col-16').eq(i).html('·');
            }
        }
    });
    $('.num-block .close-popup').unbind('click');
    $('.num-block .close-popup').click(function(event) {
        nums = [];
        $('.seccode-block .col-16').html('');
    });

    $$('.memberseccode').on('popup:opened', function(e, popup) {
        switch (type) {
            case 'set':
                $('.memberseccode .title').html(stringObj.text.set_memberseccode);
                $('.memberseccode .confirm a').html(stringObj.text._set);
                $('.memberseccode .confirm').unbind('click');
                $('.memberseccode .confirm').click(function(event) {
                    if (nums.length === 6) {
                        indexObj.modify_memberseccode('', CryptoJS.SHA256(nums.join('')).toString());
                    }
                });
                break;
            case 'old':
                $('.memberseccode .title').html(stringObj.text.input_old_memberseccode);
                $('.memberseccode .confirm a').html(stringObj.text.next);
                $('.memberseccode .confirm').unbind('click');
                $('.memberseccode .confirm').click(function(event) {
                    if (nums.length === 6) {
                        myApp.closeModal('.memberseccode');
                        memberseccode('new', CryptoJS.SHA256(nums.join('')).toString());
                    }
                });
                break;
            case 'new':
                $('.memberseccode .title').html(stringObj.text.input_new_memberseccode);
                $('.memberseccode .confirm a').html(stringObj.text.edit);
                $('.memberseccode .confirm').unbind('click');
                $('.memberseccode .confirm').click(function(event) {
                    if (nums.length === 6 && old !== CryptoJS.SHA256(nums.join('')).toString()) {
                        indexObj.modify_memberseccode(old, CryptoJS.SHA256(nums.join('')).toString());
                    } else if (nums.length === 6 && old === CryptoJS.SHA256(nums.join('')).toString()) {
                        myApp.alert(stringObj.text.memberseccode_same, stringObj.text.warn);
                    }
                });
                break;
            default:
                $('.memberseccode .title').html(stringObj.text.input_memberseccode);
                $('.memberseccode .confirm a').html(stringObj.text.confirm);
                $('.memberseccode .confirm').unbind('click');
                $('.memberseccode .confirm').click(function(event) {
                    if (nums.length === 6) {
                        indexObj.verify_memberseccode(CryptoJS.SHA256(nums.join('')).toString());
                    }
                });
                break;
        }
    });

    switch (type) {
        case 'set':
            $('.memberseccode .title').html(stringObj.text.set_memberseccode);
            $('.memberseccode .confirm a').html(stringObj.text._set);
            $('.memberseccode .confirm').unbind('click');
            $('.memberseccode .confirm').click(function(event) {
                if (nums.length === 6) {
                    indexObj.modify_memberseccode('', CryptoJS.SHA256(nums.join('')).toString());
                }
            });
            break;
        case 'old':
            $('.memberseccode .title').html(stringObj.text.input_old_memberseccode);
            $('.memberseccode .confirm a').html(stringObj.text.next);
            $('.memberseccode .confirm').unbind('click');
            $('.memberseccode .confirm').click(function(event) {
                if (nums.length === 6) {
                    myApp.closeModal('.memberseccode');
                    memberseccode('new', CryptoJS.SHA256(nums.join('')).toString());
                }
            });
            break;
        case 'new':
            $('.memberseccode .title').html(stringObj.text.input_new_memberseccode);
            $('.memberseccode .confirm a').html(stringObj.text.edit);
            $('.memberseccode .confirm').unbind('click');
            $('.memberseccode .confirm').click(function(event) {
                if (nums.length === 6 && old !== CryptoJS.SHA256(nums.join('')).toString()) {
                    indexObj.modify_memberseccode(old, CryptoJS.SHA256(nums.join('')).toString());
                } else if (nums.length === 6 && old === CryptoJS.SHA256(nums.join('')).toString()) {
                    myApp.alert(stringObj.text.memberseccode_same, stringObj.text.warn);
                }
            });
            break;
        default:
            $('.memberseccode .title').html(stringObj.text.input_memberseccode);
            $('.memberseccode .confirm a').html(stringObj.text.confirm);
            $('.memberseccode .confirm').unbind('click');
            $('.memberseccode .confirm').click(function(event) {
                if (nums.length === 6) {
                    indexObj.verify_memberseccode(CryptoJS.SHA256(nums.join('')).toString());
                }
            });
            break;
    }


};