var loginType;
var loginObj = {
    _dataUrl: {

        machine: 'http://' + stringObj.MEMBER_URL + ':' + stringObj.PORT + '/api/account/machineconnect',
        query_salt: 'http://' + stringObj.MEMBER_URL + '/api/vrf/query_salt'

    },
    _storage: {
        main: _main,
        fbInfo: 'fbInfo',
        userData: 'userData',
        loginTimes: 'loginTimes',
        fbLoginTimes: 'fbLoginTimes',
        binding: 'binding',
        api_token: 'api_token'
    },
    _templateSet: {},
    //呼叫wcf模組
    _wcfget: function(i) {

        var url = i.url;
        var data = JSON.stringify(JSON.stringify(i.para));
        $$.ajax({
            contentType: "application/json; charset=utf-8",
            dataType: 'json',
            type: 'POST',
            url: url,
            data: data,
            success: function(r) {
                if (i.success) {
                    i.success(r);
                }
            },
            error: function(r) {
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
            //get
            return JSON.parse(localStorage.getItem(name));
        } else if (obj === null) {
            //del
            localStorage.removeItem(name);
            return true;
        } else {
            //set
            localStorage.setItem(name, JSON.stringify(obj));
            return true;
        }
        return false;
    },
    //取得,刪除,新增Cookies
    _dataCookies: function(name, obj) {
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
            Cookies.set(name, JSON.stringify(obj));
            return true;
        }
        if (typeof obj === 'string') {
            //set
            Cookies.set(name, obj);
            return true;
        }
        return false;
    },
    template: function(name) {
        if (name in loginObj._templateSet) {
            return loginObj._templateSet[name];
        }
        //init
        var temp = ($$('#' + name).length) ? $$('#' + name).html() : '';
        var tempCompile = Template7.compile(temp);
        loginObj._templateSet[name] = tempCompile;
        return loginObj._templateSet[name];
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
    //初始化
    init: function() {
        var mainSg = loginObj._dataCookies(loginObj._storage.main) || {};
        if (Cookies.get('app_version') === undefined && mainSg.murId === undefined) {
            //若非APP，取機碼
            getMurId(function(mur) {
                setParameter(mur);
            });

        }

        if (mainSg.murId) {
            setParameter(mainSg.murId);
        }

        //取得鹽值
        loginObj.query_salt(stringObj.shop_b.moduleaccount, stringObj.shop_b.modulepassword);

        var dataAdj = {
            login: stringObj.text.login,
            aboutUs: stringObj.text.aboutUs,
            privacy: stringObj.text.privacy
        };

        //template
        var temp = loginObj.template('templateLoginBlock');
        var item = temp(dataAdj);
        $$('.page:not(.cached) .login-block').html(item);

        $$('.loginBtn').click(function() {
            window.location = 'http://' + stringObj.MEMBER_URL + '/transform?user_info=' + encodeURIComponent(JSON.stringify(mainSg)) + '&parameter=' + Cookies.get('parameter') + '&from=' + $(this).attr('from');
        });

        $$('.aboutLink').click(function() {
            myApp.popup('.popup-about');
            if (!isMobile.Android() && !isMobile.iOS()) {
                //美化scroll bar
                $(".popup").niceScroll({
                    cursorcolor: "rgba(100,100,100,.9)",
                    cursoropacitymin: .5,
                    cursorborder: "1px solid #000",
                    scrollspeed: 20
                });
            }
        });

        $$('.privacyLink').click(function() {
            myApp.popup('.popup-privacy');
            if (!isMobile.Android() && !isMobile.iOS()) {
                //美化scroll bar
                $(".popup").niceScroll({
                    cursorcolor: "rgba(100,100,100,.9)",
                    cursoropacitymin: .5,
                    cursorborder: "1px solid #000",
                    scrollspeed: 20
                });
            }
        });


        var strUrl = window.location.href;
        var getPara, ParaVal;
        var aryPara = [];

        if (strUrl.indexOf("?") != -1) {

            var getSearch = strUrl.split("?");
            getPara = getSearch[1].split("&");
            for (i = 0; i < getPara.length; i++) {
                ParaVal = getPara[i].split("=");
                aryPara.push(ParaVal[1]);
            }

            //console.log(decodeURIComponent(aryPara[0]));

            loginObj.getFBData(decodeURIComponent(aryPara[0]));
        }

    },
    //登入完成後
    _loginDirect: function() {

        var mainSg = loginObj._dataCookies(loginObj._storage.main) || {};


        window.location = "http://" + stringObj.WEB_URL + "/Shop/webend_admin/transform?user_info=" + encodeURIComponent(JSON.stringify(mainSg));


    },

    //取得FB用戶資訊
    getFBData: function(accessToken) {

        loginType = '0';

        $.ajax({
            contentType: "text/plain; charset=utf-8",
            url: 'https://graph.facebook.com/v2.8/me',
            type: 'GET',
            data: 'fields=id%2Cname%2Cemail%2Cfirst_name%2Clast_name%2Clocale%2Cgender%2Cbirthday%2Ctimezone&access_token=' + accessToken,
            dataType: 'json',
            success: function(r) {
                loginObj.jsonUrlDecode(r);
                r.accessToken = accessToken;
                //console.log(JSON.stringify(r));
                loginObj._userLogin(r);
            },
            error: function(r) {
                //console.log(JSON.stringify(r));
                noNetwork();
            }
        });
    },
    getMactionData: function() {

        var _m = {
            mur_uuid: Cookies.get('mur_uuid'),
            mur_gcmid: "no_gcmid",
            mur_apptype: "1",
            mur_systemtype: "0",
            mur_systeminfo: JSON.stringify({
                appCodeName: navigator.appCodeName,
                appName: navigator.appName,
                appVersion: navigator.appVersion,
                cookieEnabled: navigator.cookieEnabled,
                language: navigator.language,
                onLine: navigator.onLine,
                platform: navigator.platform,
                userAgent: navigator.userAgent
            })
        };

        //console.log('machine:' + JSON.stringify(_m));
        loginObj._wcfget({
            url: loginObj._dataUrl.machine,
            para: _m,
            success: function(r) {
                //console.log(JSON.stringify(r));
                if (r.machineconnectresult) {
                    var rObj = JSON.parse(JSON.stringify(r.machineconnectresult));
                    if (rObj.message_no === "000000000") {

                        var mainSg = loginObj._dataCookies(loginObj._storage.main) || {};
                        mainSg.murId = rObj.mur_id;
                        loginObj._dataStorage(loginObj._storage.main, mainSg);

                        setParameter(rObj.mur_id);

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
                //console.log("MachineConnectError  " + JSON.stringify(r));
                //noNetwork();
            }
        });
    },
    //即時鹽值查詢
    query_salt: function(modacc, modpsd) {
        var data = {
            modacc: modacc
        };
        //console.log(JSON.stringify(data));
        loginObj._wcfget({
            url: loginObj._dataUrl.query_salt,
            para: data,
            success: function(r) {
                loginObj.jsonUrlDecode(r);
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
                    }
                }


            },
            error: function(r) {
                //alert(JSON.stringify(r));
            }
        });
    }
};


// Initialize app
var myApp = new Framework7({
    swipeBackPage: false,
    pushState: true,
    pushStateNoAnimation: true,
    swipePanel: 'left',
    swipePanelActiveArea: -1,
    imagesLazyLoadPlaceholder: 'assets/themes/car/img/imgDefault.png',
    imagesLazyLoadThreshold: 150,
    animatePages: false,
    materialRipple: false,
    modalButtonOk: stringObj.text.confirm,
    modalButtonCancel: stringObj.text.cancel
});


// If we need to use custom DOM library, let's save it to $$ variable:
var $$ = Dom7;

var mainView = myApp.addView('.view-main', {
    dynamicNavbar: true
});



var exSwiper = new Swiper('.explanation-block', {
    pagination: '.swiper-pagination',
    paginationClickable: true,
    centeredSlides: true,
    autoplay: 3500,
    autoplayDisableOnInteraction: false,
    loop: true,
    effect: 'fade'
});


var toast = myApp.toast('message', '<i class="fa fa-exclamation-triangle"></i>', {});



//無網路時的彈出訊息
noNetwork = function() {
    toast.show(stringObj.text.noNetwork);
    $('.toast-container').css('color', '#F26531');
    $('.toast-container').css('top', '50%');
    $('.toast-container').css('left', '45%');
    $('.toast-container').css('width', '40%');
    $('.toast-container').css('background-color', 'rgba(30,30,30,.85)');
};


//document start
$(document).ready(function() {
    loginObj.init();
});

//建立parameter
setParameter = function(mur) {
    if (_region === 'tw') {
        var parameter = {
            mur: mur,
            modacc: stringObj.shop_b.moduleaccount,
            modvrf: CryptoJS.SHA256(stringObj.shop_b.moduleaccount + stringObj.shop_b.modulepassword).toString(),
            redirect_uri: 'http://' + stringObj.WEB_URL + '/pm_b-transform'
        };
    } else {
        var parameter = {
            mur: mur,
            modacc: _region + '_' + stringObj.shop_b.moduleaccount,
            modvrf: CryptoJS.SHA256(_region + '_' + stringObj.shop_b.moduleaccount + stringObj.shop_b.modulepassword).toString(),
            redirect_uri: 'http://' + stringObj.WEB_URL + '/pm_b-transform'
        };
    }
    Cookies.set('parameter', encodeURIComponent(btoa(JSON.stringify(parameter))));
};