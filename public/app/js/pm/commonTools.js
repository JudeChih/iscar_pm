var app_version = '';
var scan_from; //掃描來源
var commonTools = {
    api: {
        verify_sat: 'http://' + stringObj.MEMBER_URL + '/api/vrf/verify_sat',
        query_salt: 'http://' + stringObj.MEMBER_URL + '/api/vrf/query_salt',
        modify_member_data: 'http://' + stringObj.WEB_URL + '/modify_member_data',
        querymembershopinfo: 'http://' + stringObj.WEB_URL + '/shopmanage/querymembershopinfo',
        userbookmarkrecorver: 'http://' + stringObj.WEB_URL + '/account/userbookmarkrecorver',
        userbookmarkupdate: 'http://' + stringObj.WEB_URL + '/account/userbookmarkupdate',
        shopcouponexec: 'http://' + stringObj.API_IP + '/shopcoupon/shopcouponexec',
    },
    storage: {
        businessIsLogin: 'businessIsLogin',
        main: _main,
        search_result: 'search_result',
        branchData: 'branchData',
        myshopdata: 'myshopdata',
        listtype: 'listtype',
        cate: 'cate',
        shop_item: 'shop_item',
        favorite: 'myFavorite',
    },
    //建立parameter
    setParameter: function(mur) {
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
    },
    //頁面初始化
    init: function() {
        //init
        var main = commonTools._dataCookies(commonTools.storage.main) || {};

        // if (isMobile.Android()) {
        // Cookies.set('app_version', app_version);
        // } else if (isMobile.iOS()) {
        //     iPhone.getVersionName();
        // }
        if (Cookies.get('app_version') === undefined && main.murId === undefined) {
            //若非APP，取機碼
            getMurId(function(mur) {
                commonTools.setParameter(mur);
            });

        } else {
            // alert('沒有喔');
            commonTools.setParameter(main.murId);
        }

        //清除查詢條件
        // commonTools._dataStorage(commonTools.storage.search_result,null);

        if (main.sat) {
            //檢查sat效力
            commonTools.verify_sat();
            md_id = main.mdId;
        } else {
            commonTools._dataCookies('branchData',null);
            md_id = 'Guest';
            loginType = "";
            if (main && main.murId) {

                var murId = main.murId;

                var salt_no = localStorage.getItem('salt_no');
                var salt = localStorage.getItem('salt');
                var listtype = localStorage.getItem('listtype');
                var cate = localStorage.getItem('cate');

                var test = localStorage.getItem('test');

                if (localStorage.getItem(commonTools.storage.search_result)) {
                    var search_result = localStorage.getItem(commonTools.storage.search_result);
                }
                if (localStorage.getItem('pageInit')) {
                    var pageInit = localStorage.getItem('pageInit');
                }
                //                if (localStorage.getItem('link_to')) {
                //                    var link_to = localStorage.getItem('link_to');
                //                }

                localStorage.clear();
                commonTools._dataCookies(commonTools.storage.main,{
                    murId: murId
                }, 'iscarmg.com');
                commonTools._dataStorage('salt_no', salt_no);
                commonTools._dataStorage('salt', salt);
                commonTools._dataStorage('search_result', search_result);
                commonTools._dataStorage('listtype', listtype);
                commonTools._dataStorage('cate', cate);
                commonTools._dataStorage('pageInit', pageInit);

                commonTools._dataStorage('test', test);
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
            //         if (nowPage === undefined) {
            //             window.location = 'pm/branch-cooperative?cate=' + branch_cate + '&listtype=' + nowBranchType;
            // //                mainView.router.load({
            // //                    url: 'branch-cooperative?menu=' + branch_menu + '&cate=' + branch_cate,
            // //                    reload: true
            // //                });
            //         }
        }

        //更新書籤
        if (main.mdId) {
            commonTools.favoriteUpdate();
        }

    },
    //解碼URL中傳輸的字串
    jsonUrlDecode: function(obj) {
        for (var index in obj) {
            if (typeof obj[index] == 'object') {
                this.jsonUrlDecode(obj[index]);
            } else {
                obj[index] = decodeURIComponent(obj[index]);
            }
        }
    },
    //編譯URL中傳輸的字串
    jsonUrlEncode: function(obj) {
        for (var index in obj) {
            if (typeof obj[index] == 'object') {
                this.jsonUrlEncode(obj[index]);
            } else {
                obj[index] = encodeURIComponent(obj[index]);
            }
        }
    },
    //會員登出
    _userLogout: function() {
        var main = commonTools._dataCookies(commonTools.storage.main) || {};
        Cookies.remove('coupon_scm_id');
        Cookies.remove('coupon_md_id');
        Cookies.remove('branchData');
        window.location = 'http://' + stringObj.MEMBER_URL + '/logout?parameter=' + Cookies.get('parameter') + '&sat=' + main.sat;
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
            if(_domain === undefined){
                Cookies.set(name, JSON.stringify(obj));
            } else {
                Cookies.set(name, JSON.stringify(obj), { domain: _domain });
            }
            return true;
        }
        if (typeof obj === 'string') {
            //set
            if(_domain === undefined){
                Cookies.set(name, obj);
            } else {
                Cookies.set(name, obj, { domain: _domain });
            }
            return true;
        }
        return false;
    },
    //取得,刪除,新增localStorage
    _dataStorage: function(name, obj) {
        if (obj === undefined) {
            //get
            return JSON.parse(localStorage.getItem(name));
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
        if (typeof obj === 'string') {
            //set
            localStorage.setItem(name, obj);
            return true;
        }
        return false;
    },
    //呼叫wcf模組
    _wcfget: function(i) {

        var url = i.url;
        var data = JSON.stringify(JSON.stringify(i.para));
        //console.log(data);
        $.ajax({
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
    //APP會員資料同步
    modify_member_data: function(user_info) {
        // var rl_city_code = user_info.rl_city_code;
        // var rl_zip = user_info.rl_zip;
        // if (rl_city_code !== 0) {
        //     if (rl_zip !== 0) {
        //         rl_zip = stringObj.region[rl_city_code][1][stringObj.region[rl_city_code][0].indexOf(rl_zip)];
        //     }
        //     rl_city_code = stringObj.counties.indexOf(user_info.rl_city_code) + 1;
        // }
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
        showIndicator();
        commonTools._wcfget({
            url: commonTools.api.modify_member_data,
            para: data,
            success: function(r) {
                commonTools.jsonUrlDecode(r);
                //console.log(JSON.stringify(r));
                if (r.modify_member_dataresult) {
                    var rObj = JSON.parse(JSON.stringify(r.modify_member_dataresult));

                    if (rObj.message_no === "000000000") {

                    } else {
                        // stringObj.return_header(rObj.message_no);
                        if (_tip) {
                            // myApp.alert(_tip, stringObj.text.warn);
                            _tip = null;
                        }
                    }

                }
                hideIndicator();

            },
            error: function(r) {
                //alert(JSON.stringify(r));
                hideIndicator();
            }
        });
    },
    //查詢用戶商家資料
    querymembershopinfo: function() {
        var main = commonTools._dataCookies(commonTools.storage.main) || {};
        var data = {
            modacc: stringObj.shop.moduleaccount,
            modvrf: Cookies.get('modvrf'),
            sat: main.sat
        };
        //console.log(JSON.stringify(data));
        showIndicator();
        commonTools._wcfget({
            url: commonTools.api.querymembershopinfo,
            para: data,
            success: function(r) {
                commonTools.jsonUrlDecode(r);
                //console.log(JSON.stringify(r));
                if (r.querymembershopinforesult) {
                    var rObj = JSON.parse(JSON.stringify(r.querymembershopinforesult));

                    if (rObj.message_no === "000000000") {
                        commonTools._dataStorage(commonTools.storage.myshopdata, rObj.shopdata_array);
                        commonTools.init();
                    } else {
                        commonTools.init();
                        // stringObj.return_header(rObj.message_no);
                        if (_tip) {
                            // myApp.alert(_tip, stringObj.text.warn);
                            _tip = null;
                        }
                    }
                }
                hideIndicator();
            },
            error: function(r) {
                //alert(JSON.stringify(r));
                hideIndicator();
            }
        });

    },
    //即時鹽值查詢
    query_salt: function(modacc, modpsd) {
        var data = {
            modacc: modacc
        };
        //console.log(JSON.stringify(data));
        showIndicator();
        commonTools._wcfget({
            url: commonTools.api.query_salt,
            para: data,
            success: function(r) {
                commonTools.jsonUrlDecode(r);
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
                        commonTools._dataStorage('salt_no', salt_no);
                        commonTools._dataStorage('salt', salt);
                        loginInit(function(user_info) {
                            // console.log(user_info);
                            if (user_info.mdId) {
                                commonTools.modify_member_data(user_info);
                                if (user_info.md_clienttype === '1') {
                                    commonTools.querymembershopinfo();
                                } else {
                                    commonTools.init();
                                }
                            } else {
                                hideIndicator();
                                commonTools.init();
                            }
                        });
                    } else {
                        // stringObj.return_header(rObj.message_no);
                        if (_tip) {
                            // myApp.alert(_tip, stringObj.text.warn);
                            _tip = null;
                        }
                    }

                }

                hideIndicator();
            },
            error: function(r) {
                hideIndicator();
                //alert(JSON.stringify(r));
            }
        });
    },
    //sat效力檢查
    verify_sat: function() {
        var main = commonTools._dataCookies(commonTools.storage.main) || {};
        var data = {
            modacc: stringObj.shop.moduleaccount,
            modvrf: Cookies.get('modvrf'),
            sat: main.sat
        };
        //console.log(JSON.stringify(data));
        showIndicator();
        commonTools._wcfget({
            url: commonTools.api.verify_sat,
            para: data,
            success: function(r) {
                commonTools.jsonUrlDecode(r);
                //console.log(JSON.stringify(r));
                if (r.verify_satresult) {
                    var rObj = JSON.parse(JSON.stringify(r.verify_satresult));

                    if (rObj.message_no === "010110001") {
                        var main = commonTools._dataCookies(commonTools.storage.main) || {};

                        var strUrl = location.search;
                        var getPara, ParaVal;
                        var aryPara = [];

                        // if (strUrl.indexOf("sd_id") != -1) {
                        //     var getSearch = strUrl.split("?");
                        //     getPara = getSearch[1].split("&");
                        //     for (i = 0; i < getPara.length; i++) {
                        //         ParaVal = getPara[i].split("=");
                        //         aryPara.push(ParaVal[1]);
                        //     }
                        //     window.location.href = "/pm/branch-info?sd_id=" + aryPara[0];

                        // } else if (strUrl.indexOf("scm_id") != -1) {
                        //     var getSearch = strUrl.split("?");
                        //     getPara = getSearch[1].split("&");
                        //     for (i = 0; i < getPara.length; i++) {
                        //         ParaVal = getPara[i].split("=");
                        //         aryPara.push(ParaVal[1]);
                        //     }
                        //     window.location.href = "/pm/shopcoupon-info?scm_id=" + aryPara[0];

                        // } else if (strUrl.indexOf("upload_back") != -1) {

                        //     window.location.href = localStorage.getItem('back_url').substring(localStorage.getItem('back_url').indexOf("#!/") + 3);
                        //     localStorage.removeItem('back_url');

                        // } else {
                        //     // if (nowPage === undefined) {

                        //     // }
                        //     localStorage.removeItem('pageInit');
                        //     localStorage.removeItem('scg_id');
                        //     localStorage.removeItem('scm_id');
                        //     commonTools._dataStorage('shop_item', null);
                        //     commonTools._dataStorage('shop_buy_data', null);
                        //     commonTools._dataStorage('logistics_data', null);
                        // }

                    } else {

                        // window.location = '/pm/branch-cooperative';

                        //                        mainView.router.load({
                        //                            url: 'branch-cooperative?menu=' + branch_menu + '&cate=' + branch_cate,
                        //                            reload: true
                        //                        });

                        commonTools._dataStorage(commonTools.storage.branchData, null);
                        commonTools._dataStorage(commonTools.storage.favorite, null);
                        commonTools._dataStorage(commonTools.storage.mailList, null);
                        commonTools._dataStorage(commonTools.storage.couponRecord, null);
                        commonTools._dataStorage(commonTools.storage.shopCouponRecord, null);
                        commonTools._dataStorage(commonTools.storage.shopServiceRecord, null);
                        Cookies.set('shoplist', 0);
                        loginType = "";
                        var main = commonTools._dataCookies(commonTools.storage.main) || {};
                        if (main && main.murId) {

                            if (main.loginType === '0') {
                                var accessToken = main.sso_token;
                            }

                            commonTools._dataCookies(commonTools.storage.main, {
                                murId: main.murId
                            },'iscarmg.com');

                        }

                        // stringObj.return_header(rObj.message_no);
                        if (_tip) {
                            // myApp.alert(_tip, stringObj.text.warn);
                            _tip = null;
                        }

                        // 登出後清掉所有已訂閱的圖示
                        if ($('.view.view-main').prop('id') == 'branch-cooperative') {
                            for (var i = 0; i < $('.favorite').length; i++) {
                                $('.favorite img').eq(i).prop('src', '../app/image/unsubscribe.png');
                            }
                        }
                        //登入頭像初始化
                        userAvatarInit();
                    }

                }

                hideIndicator();
            },
            error: function(r) {
                hideIndicator();
                //console.log(JSON.stringify(r));
            }
        });
    },
    //書籤更新
    favoriteUpdate: function() {

        var favoriteList = {
            lastupdate: '',
            newsList: [],
            couponList: [],
            branchList: [],
            shopcouponList: []
        };

        var mainSg = commonTools._dataCookies(commonTools.storage.main) || {};
        var data = {
            modacc: stringObj.shop.moduleaccount,
            modvrf: Cookies.get('modvrf'),
            sat: mainSg.sat,
            lastupdate: '' //favoriteList.lastupdate
        };


        //console.log(JSON.stringify(data));
        //userdatacollectresult
        showIndicator();
        commonTools._wcfget({
            url: commonTools.api.userbookmarkrecorver,
            para: data,
            success: function(r) {
                commonTools.jsonUrlDecode(r);
                //console.log(JSON.stringify(r));
                if (r.userbookmarkrecorverresult) {
                    var rObj = JSON.parse(JSON.stringify(r.userbookmarkrecorverresult));

                    if (rObj.message_no === "000000000") {
                        var favoriteObj = JSON.stringify(favoriteList);

                        //alert(JSON.stringify(rObj));

                        for (var i in rObj.userbookmark) {

                            if (favoriteObj.match(rObj.userbookmark[i].ubm_objectid)) {

                                findAndRemove(favoriteList.newsList, 'ubm_objectid', rObj.userbookmark[i].ubm_objectid);

                                findAndRemove(favoriteList.couponList, 'ubm_objectid', rObj.userbookmark[i].ubm_objectid);

                                findAndRemove(favoriteList.branchList, 'ubm_objectid', rObj.userbookmark[i].ubm_objectid);

                                findAndRemove(favoriteList.shopcouponList, 'ubm_objectid', rObj.userbookmark[i].ubm_objectid);

                                var favoriteItem = {};
                                favoriteItem.ubm_objectid = rObj.userbookmark[i].ubm_objectid;
                                if (rObj.userbookmark[i].ubm_picpath) {
                                    favoriteItem.ubm_picpath = stringObj.text.branch_img_path + rObj.userbookmark[i].ubm_picpath;
                                } else {
                                    favoriteItem.ubm_picpath = '../app/image/imgDefault.png';
                                }
                                favoriteItem.ubm_title = rObj.userbookmark[i].ubm_title;
                                favoriteItem.create_date = rObj.userbookmark[i].create_date.substring(0, 10);
                                favoriteItem.isflag = rObj.userbookmark[i].isflag;
                                favoriteItem.date = new Date(rObj.userbookmark[i].create_date) / (1000 * 60 * 60 * 24);
                                if (rObj.userbookmark[i].ubm_objecttype === '0' && rObj.userbookmark[i].isflag === '1') {
                                    favoriteList.newsList.push(favoriteItem);
                                } else if (rObj.userbookmark[i].ubm_objecttype === '1' && rObj.userbookmark[i].isflag === '1') {
                                    favoriteList.couponList.push(favoriteItem);
                                } else if (rObj.userbookmark[i].ubm_objecttype === '2' && rObj.userbookmark[i].isflag === '1') {
                                    favoriteList.branchList.push(favoriteItem);
                                } else if (rObj.userbookmark[i].ubm_objecttype === '3' && rObj.userbookmark[i].isflag === '1') {
                                    favoriteList.shopcouponList.push(favoriteItem);
                                }

                            } else {

                                var favoriteItem1 = {};
                                favoriteItem1.ubm_objectid = rObj.userbookmark[i].ubm_objectid;
                                if (rObj.userbookmark[i].ubm_picpath) {
                                    favoriteItem1.ubm_picpath = stringObj.text.branch_img_path + rObj.userbookmark[i].ubm_picpath;
                                } else {
                                    favoriteItem1.ubm_picpath = '../app/image/imgDefault.png';
                                }
                                favoriteItem1.ubm_title = rObj.userbookmark[i].ubm_title;
                                favoriteItem1.create_date = rObj.userbookmark[i].create_date.substring(0, 10);
                                favoriteItem1.isflag = rObj.userbookmark[i].isflag;
                                favoriteItem1.date = new Date(rObj.userbookmark[i].create_date) / (1000 * 60 * 60 * 24);
                                if (rObj.userbookmark[i].ubm_objecttype === '0' && rObj.userbookmark[i].isflag === '1') {
                                    favoriteList.newsList.push(favoriteItem1);
                                } else if (rObj.userbookmark[i].ubm_objecttype === '1' && rObj.userbookmark[i].isflag === '1') {
                                    favoriteList.couponList.push(favoriteItem1);
                                } else if (rObj.userbookmark[i].ubm_objecttype === '2' && rObj.userbookmark[i].isflag === '1') {
                                    favoriteList.branchList.push(favoriteItem1);
                                } else if (rObj.userbookmark[i].ubm_objecttype === '3' && rObj.userbookmark[i].isflag === '1') {
                                    favoriteList.shopcouponList.push(favoriteItem1);
                                }
                            }

                        }


                        favoriteList.lastupdate = rObj.lastupdate;

                        //排序
                        favoriteList.branchList.sort(sortByProperty('date', -1));
                        favoriteList.shopcouponList.sort(sortByProperty('date', -1));

                        commonTools._dataStorage(commonTools.storage.favorite, favoriteList);

                        if ($('.view.view-main').prop('id') == 'branch-cooperative') {
                            if (favoriteList) {
                                for (var i in favoriteList.branchList) {
                                    $('.favorite-' + favoriteList.branchList[i].ubm_objectid).html('<img src="../app/image/subscribe.png" />');
                                }
                            }
                        }else if ($('.view.view-main').prop('id') == 'shopcoupon-info') {
                            if (favoriteList) {
                                for (var i in favoriteList.shopcouponList) {
                                    $('.favorite-' + favoriteList.shopcouponList[i].ubm_objectid).html('<i class="fa fa-star"></i>');
                                }
                            }
                        }

                    } else {
                        // stringObj.return_header(rObj.message_no);
                        if (_tip) {
                            // myApp.alert(_tip, stringObj.text.warn);
                            _tip = null;
                        }
                    }
                    // $$('.favorite-preloader').html('');
                }
                hideIndicator();
            },
            error: function(r) {
                hideIndicator();
                // $$('.favorite-preloader').html('');
                // noNetwork();
            }
        });

    },
    //商家服務
    shopcouponexec: function(scm_id, scg_id, scm_title, scm_enddate) {
        var mainSg = commonTools._dataCookies(commonTools.storage.main) || {};
        var branchData = commonTools._dataStorage(commonTools.storage.branchData);
        // console.log(JSON.stringify({
        //                 modacc: stringObj.shop.moduleaccount,
        //                 modvrf: Cookies.get('modvrf'),
        //                 sat: mainSg.sat,
        //                 sd_id: branchData.sd_id,
        //                 scm_id: scm_id,
        //                 scg_id: scg_id,
        //                 coupon_operation: '1'
        //             }));
        showIndicator();
        commonTools._wcfget({
            url: commonTools.api.shopcouponexec,
            para: {
                modacc: stringObj.shop.moduleaccount,
                modvrf: Cookies.get('modvrf'),
                sat: mainSg.sat,
                sd_id: branchData.sd_id,
                scm_id: scm_id,
                scg_id: scg_id,
                coupon_operation: '1'
            },
            success: function(r) {
                commonTools.jsonUrlDecode(r);
                // alert(JSON.stringify(r));
                if (r.shopcouponexecresult) {
                    var rObj = JSON.parse(JSON.stringify(r.shopcouponexecresult));
                    if (rObj.message_no === "010911000") {

                        // var date = new Date();
                        // var month = (date.getMonth() + 1);
                        // var dates = date.getDate();
                        // var hours = date.getHours();
                        // var min = date.getMinutes();
                        // var sec = date.getSeconds();
                        // if (month < 10) {
                        //     month = '0' + month;
                        // }
                        // if (dates < 10) {
                        //     dates = '0' + dates;
                        // }
                        // if (hours < 10) {
                        //     hours = '0' + hours;
                        // }
                        // if (min < 10) {
                        //     min = '0' + min;
                        // }
                        // if (sec < 10) {
                        //     sec = '0' + sec;
                        // }


                        // var branchScanRecord = commonTools._dataStorage(branchObj.storage.branchScanRecord) || {
                        //     scanArray: []
                        // };
                        // var scanItem = {};
                        // if (JSON.stringify(branchScanRecord).match(scm_id)) {
                        //     var today = '' + date.getFullYear() + month + dates;
                        //     findArrayItem(branchScanRecord.scanArray, 'scm_id', scm_id);
                        //     if (today == branchScanRecord.scanArray[nowIndex].scanDate.substring(0, 8)) {
                        //         scanItem.scanNum = branchScanRecord.scanArray[nowIndex].scanNum + 1;
                        //         findAndRemove(branchScanRecord.scanArray, 'scm_id', scm_id);
                        //     } else {
                        //         scanItem.scanNum = 1;
                        //     }
                        // } else {
                        //     scanItem.scanNum = 1;
                        // }

                        // scanItem.scm_id = scm_id;
                        // scanItem.scm_title = scm_title;
                        // scanItem.scm_balanceno = rObj.scm_balanceno || ' ';
                        // scanItem.scm_enddate = scm_enddate;
                        // scanItem.scanDate = '' + date.getFullYear() + month + dates + hours + min + sec;

                        // branchScanRecord.scanArray.push(scanItem);

                        // //排序
                        // branchScanRecord.scanArray.sort(sortByProperty('scanDate', -1));

                        // indexObj._dataStorage(branchObj.storage.branchScanRecord, branchScanRecord);

                        // alert('scm_balanceno::'+rObj.scm_balanceno);
                        if (rObj.scm_balanceno === '') {
                            // alert('row-712');
                            commonTools.setPopBox({
                                title: stringObj.text.warn,
                                text: stringObj.text.serving_success,
                                buttons: [{
                                    text: stringObj.text.confirm,
                                    onClick: function() {
                                        removeSomething();
                                        location.href = '/Shop#!/shop/branch-main';
                                    }
                                }]
                            });
                            hideIndicator();
                        } else {
                            // alert('row-726');
                            code39(rObj.scm_balanceno);
                            hideIndicator();
                        }

                    } else {
                        // alert(rObj.message_no);
                        stringObj.return_header(rObj.message_no);
                        if (_tip) {
                            // alert('row-735');
                            commonTools.setPopBox({
                                title: stringObj.text.warn,
                                text: _tip,
                                buttons: [{
                                    text: stringObj.text.confirm,
                                    onClick: function() {
                                        removeSomething();
                                    }
                                }]
                            });
                            _tip = null;
                            hideIndicator();
                        }
                    }
                }
            },
            error: function(r) {
                // alert('row-753');
                // console.log(JSON.stringify(r));
                hideIndicator();
                // noNetwork();
            }
        });
    },
    //回上一頁的設定
    backToPrePage: function() {
        if(window.location.href.match('Welcome') != -1){
            window.location = "/pm";
        }else{
            if (Cookies.get('back') || window.location.hostname != document.referrer.split('/')[2] || document.referrer.split('/')[2] == undefined) {
                Cookies.remove('back');
                window.location = "/pm";
            } else {
                history.back();
            }
        }
        
    },
    // 生成彈出提示框
    setPopBox: function(data) {
        var button_num = data.buttons.length;
        var button_string = '';
        var button_class = '';
        if (data.button_class) {
            button_class = data.button_class;
        }
        // 插入彈出視窗的外框
        $('body').append('<div class="modal modal-in pop_box" style="display: block;margin-top: -63px;"></div>');
        // 插入彈出視窗的內框
        $('.pop_box').append('<div class="modal-inner"></div>');
        // 在內框插入title跟text
        $('.pop_box .modal-inner').append(function() {
                var title = '<div class="modal-title">' + data.title + '</div>';
                var text = '<div class="modal-text">' + data.text + '</div>';
                return title + text;
            })
            // if(data.other){

        // }
        // 插入按鈕區塊的外框
        $('.pop_box').append('<div class="modal-buttons modal-buttons-' + button_num + ' ' + button_class + '"></div>');
        // 插入按鈕
        $('.pop_box .modal-buttons').append(function() {
            for (var i = 0; i < button_num; i++) {
                button_string = button_string + '<span class="modal-button">' + data.buttons[i].text + '</span>';
            }
            return button_string;
        });
        // 設定按鈕功能
        for (var j = 0; j < button_num; j++) {
            $('.pop_box .modal-button').eq(j).on('click', data.buttons[j].onClick);
        };
        // 新增灰階背景
        $('body').append('<div class="modal-overlay modal-overlay-visible"></div>');
        // 將背景變模糊
        $('.views').addClass('blur');
    },
    // 將傳遞的function轉成字串
    decodefunction: function(obj) {
        JSON.stringify(obj, function() {
            if (typeof obj === "function") {
                return "/Function(" + obj.toString() + ")/";
            }
            return json;
        });
    },
    // 將字串轉回function並且去掉function的字樣，直接取裡面的事件
    encodefunction: function(json) {
        JSON.parse(json, function() {
            if (typeof json === "string" &&
                json.startsWith("/Function(") &&
                json.endsWith(")/")) {
                json = json.substring(10, json.length - 2);
                return eval("(" + json + ")");
            }
            return json;
        });
    },
    /**
     * 廠商QR碼掃描
     * @param type 掃描類型
     */
    scan: function(type) {
        scan_from = type;
        // var businessIsLogin = localStorage.getItem(commonTools.storage.businessIsLogin);
        var mainSg = commonTools._dataCookies(commonTools.storage.main) || {};
        if (mainSg.sat) {
            // if (isMobile.Android() && type == 'business' && businessIsLogin == 'Yes') {
            //     Android.qrScan();
            // } else if (isMobile.iOS() && type == 'business' && businessIsLogin == 'Yes') {
            //     iPhone.StartQR();
            // } else {
            webview.scan();
            // }
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
            commonTools.setPopBox({
                title: stringObj.text.warn,
                text: stringObj.text.notLogin,
                buttons: [{
                    text: stringObj.text.cancel,
                    onClick: function() {
                        removeSomething();
                    }
                }, {
                    text: stringObj.text.confirm,
                    onClick: function() {
                        removeSomething();
                        webview.loginPage();
                    }
                }]
            });
        }

    }
};
// myApp.modal({
//     title: stringObj.text.warn,
//     text: stringObj.shop.iscarpolicy,
//     buttons: [{
//             text: stringObj.text.refuse,
//             onClick: function () {}
//         }, {
//             text: stringObj.text.agree,
//             onClick: function () {
//                 addBookmarks(id, imagePath, title, catName, author, createDate, type);
//             }
//         }, ]
// });

/**
 * 掃描後
 * @param msg 掃描資訊內容
 */
scanMsg = function(msg) {
    //console.log(JSON.stringify(msg));
    if (msg.match('member_card_id')) {
        switch (scan_from) {
            case 'branch':
                var jsonObj = JSON.parse(msg);
                mainView.router.load({
                    url: 'shop/bonus-gift?md_id=' + jsonObj.member_card_id,
                    reload: true
                });
                break;
            case 'business':
                var jsonObj = JSON.parse(msg);
                var branch_md_id = jsonObj.member_card_id;
                window.location.href = "/Shop#!/branch-binding?md_id=" + branch_md_id;
                break;
            case 'add-staff':
                var jsonObj = JSON.parse(msg);
                branchObj.querymemberidinfo(jsonObj.member_card_id);
                break;
        }
    } else if (scan_from === 'report') {
        // var msgObj = JSON.parse(msg);
        // branchObj.querysclid(msgObj.scl_id);
    } else if (msg.match('scg_id')) {
        // var msgObj = JSON.parse(msg);
        // branchObj.shopcouponscan(msgObj.scm_id, msgObj.scg_id);
    } else if (msg.match('ssqd_id')) {
        // var msgObj = JSON.parse(msg);
        // branchObj.shopservicescan(msgObj.ssqd_id, msgObj.ssqq_id, msgObj.ssqq_queserno);
    } else if (msg.match('cdm_id')) {
        // mainView.router.load({
        //     url: 'scan-info?msg=' + msg
        // });
    } else {
        //條碼格式有誤
        commonTools.setPopBox({
            title: stringObj.text.warn,
            text: stringObj.text.scan_msg_error,
            buttons: [{
                text: stringObj.text.cancel,
                onClick: function() {
                    removeSomething();
                }
            }]
        });
    }
};



// 登出按鈕
logout = function() {
    commonTools.setPopBox({
        title: stringObj.text.warn,
        text: stringObj.text.logoutCheck,
        buttons: [{
            text: stringObj.text.cancel,
            onClick: function() {
                removeSomething();
            }
        }, {
            text: stringObj.text.confirm,
            onClick: function() {
                removeSomething();
                commonTools._userLogout();
            }
        }]
    });
};
/**
 * 利用IP取得目前所在經緯度
 */
ipLocation = function() {
    $.getJSON("http://ip-api.com/json/?callback=?", function(data) {
        //console.log(JSON.stringify(data));
        nowLocation(data.lon, data.lat);
    });
};
/**
 * 目前所在經緯度
 * @param longitude 經度
 * @param latitude 緯度
 */
nowLocation = function(longitude, latitude) {

    // var page_from = localStorage.getItem('page_from');
    var page_from = commonTools._dataStorage('page_from') || '';

    // Cookies.set('latitude',latitude);
    // Cookies.set('longitude',longitude);
    // Cookies.set('coordinate',JSON.stringify(setting));

    if (longitude === undefined || longitude === 0 || longitude === '') {
        commonTools.setPopBox({
            title: stringObj.text.warn,
            text: stringObj.shop.no_GPS,
            buttons: [{
                text: stringObj.text.default_setting,
                onClick: function() {
                    removeSomething();
                    if (page_from == 'map') {
                        Cookies.set('sd_lat', stringObj.text.default_lat);
                        Cookies.set('sd_lng', stringObj.text.default_lng);
                        window.location.href = "/Shop#!/map";
                    } else if (page_from == 'around_search') {
                        var search_result = {};
                        search_result.spm_serno = 2;
                        search_result.sd_lat = stringObj.text.default_lat;
                        search_result.sd_lng = stringObj.text.default_lng;
                        search_result.sd_shopname = '';
                        search_result.sd_country = '';
                        commonTools._dataStorage(commonTools.storage.search_result, search_result);
                        window.location.href = "/Shop#!/around-search-result?from=around_search";
                    }
                }
            }, {
                text: stringObj.text.go_setting,
                onClick: function() {
                    removeSomething();
                    webview.openSettingGPS();
                }
            }, {
                text: stringObj.text.cancel,
                onClick: function() {
                    removeSomething();
                }
            }],
            button_class: 'modal-buttons-vertical',
        });
        // $('body').append('<div class="modal modal-in" style="display: block;margin-top: -63px;"><div class="modal-inner"><div class="modal-title">提醒</div><div class="modal-text">'+stringObj.shop.no_GPS+'</div></div><div class="modal-buttons modal-buttons-3 modal-buttons-vertical"><span class="modal-button go_setting">前往設定</span><span class="modal-button default_setting">使用預設座標</span><span class="modal-button cancel">取消</span></div></div><div class="modal-overlay modal-overlay-visible"></div>');
        // $('.views').addClass('blur');
        // $('.go_setting').on('click',function(){
        //     webview.openSettingGPS();
        // })
        // $('.default_setting').on('click',function(){

        //     // nowlat = stringObj.text.default_lat;
        //     // nowlng = stringObj.text.default_lng;
        //     if(page_from == 'map'){
        //         Cookies.set('sd_lat',stringObj.text.default_lat);
        //         Cookies.set('sd_lng',stringObj.text.default_lng);
        //         window.location.href = "/Shop#!/map";
        //     }else if(page_from == 'around_search'){
        //         var search_result = {};
        //         search_result.spm_serno = 2;
        //         search_result.sd_lat = stringObj.text.default_lat;
        //         search_result.sd_lng = stringObj.text.default_lng;
        //         search_result.sd_shopname = '';
        //         search_result.sd_country = '';
        //         commonTools._dataStorage(commonTools.storage.search_result,search_result);
        //         window.location.href = "/Shop#!/around-search-result?from=around_search";
        //     }
        // })

        // $('.cancel').on('click',function(){
        //     $('.modal.modal-in').remove();
        //     $('.modal-overlay.modal-overlay-visible').remove();
        //     $('.views').removeClass('blur');
        // });
    } else {
        if (page_from == 'around_search') {
            var search_result = {};
            search_result.spm_serno = 2;
            search_result.sd_lat = latitude || '';
            search_result.sd_lng = longitude || '';
            search_result.sd_shopname = '';
            search_result.sd_country = '';
            commonTools._dataStorage(commonTools.storage.search_result, search_result);
            window.location.href = "/Shop#!/around-search-result?from=around_search";
        }
        // else{
        //     getNestData(longitude,latitude);
        // }
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
    commonTools.jsonUrlDecode(title);

    var main = commonTools._dataCookies(commonTools.storage.main) || {};
    $('.favorite-' + id).removeClass("expandOpen");
    if (main.sat !== undefined) {

        var favoriteList = commonTools._dataStorage(commonTools.storage.favorite) || {
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
            commonTools.setPopBox({
                title: stringObj.text.warn,
                text: stringObj.text.marksRemoveCheck,
                buttons: [{
                        text: stringObj.text.cancel,
                        onClick: function () {
                            removeSomething();
                        }
                    }, {
                        text: stringObj.text.confirm,
                        onClick: function () {
                            removeSomething();
                            showIndicator();
                            //移除放大動畫類別
                            $('.favorite-' + id).removeClass("expandOpen");
                            var mainSg = commonTools._dataCookies(commonTools.storage.main) || {};
                            var data = {
                                modacc: stringObj.shop.moduleaccount,
                                modvrf: Cookies.get('modvrf'),
                                sat: mainSg.sat,
                                useroperate: '1',
                                ubm_objecttype: type,
                                ubm_objectid: id
                            };
                            // console.log(data);
                            commonTools._wcfget({
                                url: commonTools.api.userbookmarkupdate,
                                para: data,
                                success: function(r) {
                                    if (r.userbookmarkupdateresult) {
                                        var rObj = JSON.parse(JSON.stringify(r.userbookmarkupdateresult));
                                        if (rObj.message_no === "000000000") {

                                            if (type === '0') {
                                                findAndRemove(favoriteList.newsList, 'ubm_objectid', id);
                                                // if (Cookies.get('app_version') !== undefined){
                                                //     if (isMobile.Android()) {
                                                //         webview.nowEvent('nsdelmark-And', id, md_id);
                                                //     } else if (isMobile.iOS()) {
                                                //         webview.nowEvent('nsdelmark-iOS', id, md_id);
                                                //     }
                                                // }
                                            } else if (type === '1') {
                                                findAndRemove(favoriteList.couponList, 'ubm_objectid', id);
                                                // if (Cookies.get('app_version') !== undefined){
                                                //     if (isMobile.Android()) {
                                                //         webview.nowEvent('cpdelmark-And', id, md_id);
                                                //     } else if (isMobile.iOS()) {
                                                //         webview.nowEvent('cpdelmark-iOS', id, md_id);
                                                //     }
                                                // }
                                            } else if (type === '2') {
                                                findAndRemove(favoriteList.branchList, 'ubm_objectid', id);
                                            } else if (type === '3') {
                                                findAndRemove(favoriteList.shopcouponList, 'ubm_objectid', id);
                                            }

                                            if ($('.view.view-main').prop('id') == 'branch-cooperative' || $('.view.view-main').prop('id') == 'branch-info') {
                                                $('.favorite-' + id).addClass("expandOpen");
                                                $('.favorite-' + id).html('<img src="../app/image/unsubscribe.png" onerror=\'this.src="../app/image/imgDefault.png"\' />');
                                            } else {
                                                $('.favorite-' + id).addClass("expandOpen");
                                                $('.favorite-' + id).html('<i class="fa fa-star-o"></i>');
                                            }

                                            // if (nowPage === 'post' || nowPage === 'favorite-post' || nowPage === 'mail-post') {
                                            //     $$('.favorite-' + id + ' span').html('<i class="fa fa-star-o"></i>');
                                            //     $$('.favorite-' + id + ' span').css('color', '#fff');
                                            // } else if (nowPage === 'branch-cooperative' || nowPage === 'branch-info') {
                                            //     $$('.favorite-' + id).html('<img src="../app/image/unsubscribe.png" onerror=\'this.src="app/image/imgDefault.png"\' />');
                                            // } else {
                                            //     $$('.favorite-' + id).html('<i class="fa fa-star-o"></i>');
                                            // }

                                            commonTools._dataStorage(commonTools.storage.favorite, favoriteList);
                                            if (type === '0') {

                                            } else if (type === '1') {

                                            } else if (type === '2') {
                                                commonTools.setPopBox({
                                                    title: stringObj.text.cancel_subscription,
                                                    text: stringObj.text.nowCoin + '：' + rObj.gpmr_point,
                                                    buttons: [{
                                                        text: stringObj.text.confirm,
                                                        onClick: function() {
                                                            removeSomething();
                                                        }
                                                    }]
                                                });
                                            } else if (type === '3') {

                                            }
                                            hideIndicator();
                                        } else {
                                            // stringObj.return_header(rObj.message_no);
                                            if (_tip) {
                                                // myApp.alert(_tip, stringObj.text.warn);
                                                _tip = null;
                                            }
                                            hideIndicator();
                                        }
                                    }
                                },
                                error: function(r) {
                                    hideIndicator();
                                    // noNetwork();
                                }
                            });
                        }
                    }
                ]
            })
        } else {
            showIndicator();
            switch (type) {
                case '2':
                    //商家
                    commonTools.setPopBox({
                        title: stringObj.text.warn,
                        text: stringObj.shop.iscarpolicy,
                        buttons: [{
                            text: stringObj.text.refuse,
                            onClick: function() {
                                removeSomething();
                            }
                        }, {
                            text: stringObj.text.agree,
                            onClick: function() {
                                removeSomething();
                                addBookmarks(id, imagePath, title, catName, author, createDate, type);
                            }
                        }]
                    });
                    $('.modal .query_iscarpolicy').unbind('click');
                    $('.modal .query_iscarpolicy').click(function() {
                        var popupHTML = '<div class="popup popup-iscarpolicy" style="display: block;">' +
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
                        $('body').append(popupHTML);
                        $('.popup.popup-iscarpolicy').addClass('modal-in');
                        $('.views').addClass('blur');
                        $('body').append('<div class="popup-overlay modal-overlay-visible"></div>');
                        $('.popup-iscarpolicy').css('z-index', '91000');
                        $('.popup-iscarpolicy .close-btn').on('click', function() {
                            $('.popup.popup-iscarpolicy').addClass('modal-out', function() {
                                $('.popup.popup-iscarpolicy').removeClass('modal-in', function() {
                                    $('.popup.popup-iscarpolicy').remove();
                                });
                            });
                            $('.popup-overlay.modal-overlay-visible').remove();
                            $('.views').removeClass('blur');
                        })
                    });
                    hideIndicator();

                    break;
                    // case '3':
                    //     //商品
                    //     commonTools.setPopBox({
                    //         title: stringObj.text.warn,
                    //         text: stringObj.shop.iscarpolicy,
                    //         buttons: [{
                    //                 text: stringObj.text.refuse,
                    //                 onClick: function () {
                    //                     removeSomething();
                    //                 }
                    //             }, {
                    //                 text: stringObj.text.agree,
                    //                 onClick: function () {
                    //                     removeSomething();
                    //                     addBookmarks(id, imagePath, title, catName, author, createDate, type);
                    //                 }
                    //             }]
                    //     });
                    //     $('.modal .query_iscarpolicy').unbind('click');
                    //     $('.modal .query_iscarpolicy').click(function () {
                    //         var popupHTML = '<div class="popup popup-iscarpolicy" style="display: block;">' +
                    //                 '<div class="close-btn">' +
                    //                 '<a href="#" class="close-popup">' +
                    //                 '<i class="fa fa-times" aria-hidden="true"></i>' +
                    //                 '</a>' +
                    //                 '</div>' +
                    //                 '<div class="content-block">' +
                    //                 '<div class="title">' + '『就是行』汽車特店會員服務條款' + '</div>' +
                    //                 '<div class="iscarpolicy-content">' +
                    //                 '<div class="main_content_text">' +
                    //                 stringObj.shop_service_contract +
                    //                 '</div>' +
                    //                 '</div>' +
                    //                 '</div>' +
                    //                 '</div>';
                    //         $('body').append(popupHTML);
                    //         $('.popup.popup-iscarpolicy').addClass('modal-in');
                    //         $('.views').addClass('blur');
                    //         $('body').append('<div class="popup-overlay modal-overlay-visible"></div>');
                    //         $('.popup-iscarpolicy').css('z-index', '91000');
                    //         $('.popup-iscarpolicy .close-btn').on('click',function(){
                    //             $('.popup.popup-iscarpolicy').addClass('modal-out',function(){
                    //                 $('.popup.popup-iscarpolicy').removeClass('modal-in',function(){
                    //                     $('.popup.popup-iscarpolicy').remove();
                    //                 });
                    //             });
                    //             $('.popup-overlay.modal-overlay-visible').remove();
                    //             $('.views').removeClass('blur');
                    //         })
                    //     });
                    //     break;
                default:
                    addBookmarks(id, imagePath, title, catName, author, createDate, type);
                    break;
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
        hideIndicator();
    }
};
// 顯示loading圖示
showIndicator = function() {
        // if ($('.view.view-main').prop('id') == 'shopcoupon-info' || $('.view.view-main').prop('id') == 'shopcoupon-list') {
            $('body').append('<div class="pm_indicator"><span style="width:42px; height:42px" class="preloader preloader-white"></span></div><div class="modal-overlay modal-overlay-visible pm_overlay"></div>');
        // } else {
        //     $('.list-block').append('<div class="pm_indicator"><span style="width:42px; height:42px" class="preloader preloader-white"></span></div><div class="modal-overlay modal-overlay-visible pm_overlay"></div>');
        // }
    }
    // 隱藏loading圖示
hideIndicator = function() {
        $('.pm_indicator').remove();
        $('.pm_overlay').remove();
    }
    /**
     * 建立code39視窗
     * @param code 銷貨編號
     */
code39 = function(code) {
    if (code != ' ') {
        // commonTools.setPopBox({
        //     title: stringObj.text.warn,
        //     text: stringObj.text.code39_title,
        //     buttons: [{
        //             text: stringObj.text.confirm,
        //             onClick: function () {
        //                 removeSomething();
        //                 location.href = '/Shop#!/shop/branch-main';
        //             }
        //         }],
        //     other: '<br><div class="barcode39" style="width:200px; height:80px; margin: 0% auto;">'+code+'</div><p>'+code+'</p>'

        // });
        $('body').append('<div class="modal modal-in" style="display: block;margin-top: -150px;"><div class="modal-inner"><div class="modal-title">' + stringObj.text.warn + '</div><div class="modal-text"><div class="member-block"><div class="code39-title">' + stringObj.text.code39_title + '</div><br><div class="barcode39" style="width:200px; height:80px; margin: 0% auto;">' + code + '</div><p>' + code + '</p></div></div></div><div class="modal-buttons modal-buttons-1 "><span class="modal-button confirm">' + stringObj.text.confirm + '</span></div></div><div class="modal-overlay modal-overlay-visible"></div>');
        $('.views').addClass('blur');
        $('.confirm').on('click', function() {
            $('.modal.modal-in').remove();
            $('.modal-overlay.modal-overlay-visible').remove();
            $('.views').removeClass('blur');
            location.href = '/Shop#!/shop/branch-main';
        })
        $('.barcode39').barcode({
            code: 'code39'
        });
    }
};
// 設置"卷軸的樣式"
setScrollBar = function (){
    if($('.view.view-main').prop('id') == 'branch_cooperative'){
        $(".content").niceScroll({
            cursorcolor: "rgba(100,100,100,.9)",
            cursoropacitymin: 0,
            cursorborder: "1px solid #000",
            scrollspeed:10,
            mousescrollstep:60
        });
    }
    $(".page-content").niceScroll({
        cursorcolor: "rgba(100,100,100,.9)",
        cursoropacitymin: 0,
        cursorborder: "1px solid #000",
        scrollspeed:10,
        mousescrollstep:60
    });
}
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
    showIndicator();
    var mainSg = commonTools._dataCookies(commonTools.storage.main) || {};
    var data = {
        modacc: stringObj.shop.moduleaccount,
        modvrf: Cookies.get('modvrf'),
        sat: mainSg.sat,
        useroperate: '0',
        ubm_objecttype: type,
        ubm_objectid: id
    };
    //console.log(JSON.stringify(data));
    commonTools._wcfget({
        url: commonTools.api.userbookmarkupdate,
        para: data,
        success: function(r) {
            //console.log(JSON.stringify(r));
            if (r.userbookmarkupdateresult) {
                var rObj = JSON.parse(JSON.stringify(r.userbookmarkupdateresult));
                if (rObj.message_no === "000000000" || rObj.message_no === "011601002") {

                    if ($('.view.view-main').prop('id') == 'branch-cooperative' || $('.view.view-main').prop('id') == 'branch-info') {
                        //新增放大動畫類別
                        $('.favorite-' + id).addClass("expandOpen");
                        $('.favorite-' + id).html('<img src="../app/image/subscribe.png" onerror=\'this.src="../app/image/imgDefault.png"\' />');
                    } else {

                        //新增放大動畫類別
                        $('.favorite-' + id).addClass("expandOpen");
                        $('.favorite-' + id).html('<i class="fa fa-star"></i>');
                    }

                    var favoriteList = commonTools._dataStorage(commonTools.storage.favorite) || {
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

                        // if (isMobile.Android()) {
                        //     webview.nowEvent('nsaddmark-And', id, md_id);
                        // } else if (isMobile.iOS()) {
                        //     webview.nowEvent('nsaddmark-iOS', id, md_id);
                        // }
                    } else if (type === '1') {

                        favoriteList.couponList.push(favoriteItem);

                        // if (isMobile.Android()) {
                        //     webview.nowEvent('cpaddmark-And', id, md_id);
                        // } else if (isMobile.iOS()) {
                        //     webview.nowEvent('cpaddmark-iOS', id, md_id);
                        // }
                    } else if (type === '2') {
                        favoriteList.branchList.push(favoriteItem);
                    } else if (type === '3') {
                        favoriteList.shopcouponList.push(favoriteItem);
                    }

                    commonTools._dataStorage(commonTools.storage.favorite, favoriteList);

                    if ($('.view.view-main').prop('id') == 'branch-cooperative' || $('.view.view-main').prop('id') == 'branch-info') {
                        if (rObj.message_no === "000000000") {
                            commonTools.setPopBox({
                                title: stringObj.text.subscription_success,
                                text: stringObj.text.nowCoin + '：' + rObj.gpmr_point,
                                buttons: [{
                                    text: stringObj.text.confirm,
                                    onClick: function() {
                                        removeSomething();
                                    }
                                }]
                            });
                            hideIndicator();
                        } else {
                            stringObj.return_header(rObj.message_no);
                            if (_tip) {
                                commonTools.setPopBox({
                                    title: stringObj.text.subscription_success,
                                    text: _tip,
                                    buttons: [{
                                        text: stringObj.text.confirm,
                                        onClick: function() {
                                            removeSomething();
                                        }
                                    }]
                                });
                                _tip = null;
                            }
                            hideIndicator();
                        }
                    }else{
                        hideIndicator();
                    }


                } else {
                    stringObj.return_header(rObj.message_no);
                    if (_tip) {
                        commonTools.setPopBox({
                            title: stringObj.text.warn,
                            text: _tip,
                            buttons: [{
                                text: stringObj.text.confirm,
                                onClick: function() {
                                    removeSomething();
                                }
                            }]
                        });
                        _tip = null;
                    }
                    hideIndicator();
                }
                // hideIndicator();
            }
        },
        error: function(r) {
            //console.log(JSON.stringify(r));
            hideIndicator();
            // noNetwork();
        }
    });
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

// 關閉彈出框時該做的事情
removeSomething = function() {
    $('.pop_box').remove();
    $('.modal-overlay.modal-overlay-visible').remove();
    $('.views').removeClass('blur');
}


//document start
$(document).ready(function() {
    commonTools.query_salt(stringObj.shop.moduleaccount, stringObj.shop.modulepassword);
});
