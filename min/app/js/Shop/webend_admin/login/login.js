var loginType,loginObj={_dataUrl:{machine:"http://"+stringObj.MEMBER_URL+":"+stringObj.PORT+"/api/account/machineconnect",query_salt:"http://"+stringObj.MEMBER_URL+"/api/vrf/query_salt"},_storage:{main:_main,fbInfo:"fbInfo",userData:"userData",loginTimes:"loginTimes",fbLoginTimes:"fbLoginTimes",binding:"binding",api_token:"api_token"},_templateSet:{},_wcfget:function(o){var e=o.url,t=JSON.stringify(JSON.stringify(o.para));$$.ajax({contentType:"application/json; charset=utf-8",dataType:"json",type:"POST",url:e,data:t,success:function(e){o.success&&o.success(e)},error:function(e){o.error&&o.error(e)},complete:function(e){o.finish&&o.finish(e)},beforeSend:function(e){o.progress&&o.progress(e)}})},_dataStorage:function(o,e){return void 0===e?JSON.parse(localStorage.getItem(o)):null===e?(localStorage.removeItem(o),!0):(localStorage.setItem(o,JSON.stringify(e)),!0)},_dataCookies:function(o,e){return void 0===e?void 0!==Cookies.get(o)&&JSON.parse(Cookies.get(o)):null===e?(Cookies.remove(o),!0):"object"==typeof e?(Cookies.set(o,JSON.stringify(e)),!0):"string"==typeof e&&(Cookies.set(o,e),!0)},template:function(o){if(o in loginObj._templateSet)return loginObj._templateSet[o];var e=$$("#"+o).length?$$("#"+o).html():"",t=Template7.compile(e);return loginObj._templateSet[o]=t,loginObj._templateSet[o]},jsonUrlDecode:function(o){for(var e in o)"object"==typeof o[e]?this.jsonUrlDecode(o[e]):o[e]=decodeURIComponent(o[e])},jsonUrlEncode:function(o){for(var e in o)"object"==typeof o[e]?this.jsonUrlEncode(o[e]):o[e]=encodeURIComponent(o[e])},init:function(){var o=loginObj._dataCookies(loginObj._storage.main)||{};void 0===Cookies.get("app_version")&&void 0===o.murId&&getMurId(function(o){setParameter(o)}),o.murId&&setParameter(o.murId),loginObj.query_salt(stringObj.shop_b.moduleaccount,stringObj.shop_b.modulepassword);var e={login:stringObj.text.login,aboutUs:stringObj.text.aboutUs,privacy:stringObj.text.privacy},t=loginObj.template("templateLoginBlock")(e);$$(".page:not(.cached) .login-block").html(t),$$(".loginBtn").click(function(){window.location="http://"+stringObj.MEMBER_URL+"/transform?user_info="+encodeURIComponent(JSON.stringify(o))+"&parameter="+Cookies.get("parameter")+"&from="+$(this).attr("from")}),$$(".aboutLink").click(function(){myApp.popup(".popup-about"),isMobile.Android()||isMobile.iOS()||$(".popup").niceScroll({cursorcolor:"rgba(100,100,100,.9)",cursoropacitymin:.5,cursorborder:"1px solid #000",scrollspeed:20})}),$$(".privacyLink").click(function(){myApp.popup(".popup-privacy"),isMobile.Android()||isMobile.iOS()||$(".popup").niceScroll({cursorcolor:"rgba(100,100,100,.9)",cursoropacitymin:.5,cursorborder:"1px solid #000",scrollspeed:20})});var n,a,r=window.location.href,s=[];if(-1!=r.indexOf("?")){for(n=r.split("?")[1].split("&"),i=0;i<n.length;i++)a=n[i].split("="),s.push(a[1]);loginObj.getFBData(decodeURIComponent(s[0]))}},_loginDirect:function(){var o=loginObj._dataCookies(loginObj._storage.main)||{};window.location="http://"+stringObj.WEB_URL+"/Shop/webend_admin/transform?user_info="+encodeURIComponent(JSON.stringify(o))},getFBData:function(o){loginType="0",$.ajax({contentType:"text/plain; charset=utf-8",url:"https://graph.facebook.com/v2.8/me",type:"GET",data:"fields=id%2Cname%2Cemail%2Cfirst_name%2Clast_name%2Clocale%2Cgender%2Cbirthday%2Ctimezone&access_token="+o,dataType:"json",success:function(e){loginObj.jsonUrlDecode(e),e.accessToken=o,loginObj._userLogin(e)},error:function(o){noNetwork()}})},getMactionData:function(){var o={mur_uuid:Cookies.get("mur_uuid"),mur_gcmid:"no_gcmid",mur_apptype:"1",mur_systemtype:"0",mur_systeminfo:JSON.stringify({appCodeName:navigator.appCodeName,appName:navigator.appName,appVersion:navigator.appVersion,cookieEnabled:navigator.cookieEnabled,language:navigator.language,onLine:navigator.onLine,platform:navigator.platform,userAgent:navigator.userAgent})};loginObj._wcfget({url:loginObj._dataUrl.machine,para:o,success:function(o){if(o.machineconnectresult){var e=JSON.parse(JSON.stringify(o.machineconnectresult));if("000000000"===e.message_no){var t=loginObj._dataCookies(loginObj._storage.main)||{};t.murId=e.mur_id,loginObj._dataStorage(loginObj._storage.main,t),setParameter(e.mur_id)}else stringObj.return_header(e.message_no),_tip&&(myApp.alert(_tip+"( "+e.message_no+" )",stringObj.text.warn),_tip=null)}},error:function(o){}})},query_salt:function(o,e){var t={modacc:o};loginObj._wcfget({url:loginObj._dataUrl.query_salt,para:t,success:function(t){if(loginObj.jsonUrlDecode(t),t.query_saltresult){var n=JSON.parse(JSON.stringify(t.query_saltresult));if("000000000"===n.message_no){var i=atob(decodeURIComponent(n.salt)),a=i.substring(0,i.indexOf("_")),r=i.substring(i.indexOf("_")+1);Cookies.set("salt_no",a),Cookies.set("salt",r),Cookies.set("modvrf",encodeURIComponent(btoa(a+"_"+CryptoJS.SHA256(o+e+r))))}}},error:function(o){}})}},myApp=new Framework7({swipeBackPage:!1,pushState:!0,pushStateNoAnimation:!0,swipePanel:"left",swipePanelActiveArea:-1,imagesLazyLoadPlaceholder:"assets/themes/car/img/imgDefault.png",imagesLazyLoadThreshold:150,animatePages:!1,materialRipple:!1,modalButtonOk:stringObj.text.confirm,modalButtonCancel:stringObj.text.cancel}),$$=Dom7,mainView=myApp.addView(".view-main",{dynamicNavbar:!0}),exSwiper=new Swiper(".explanation-block",{pagination:".swiper-pagination",paginationClickable:!0,centeredSlides:!0,autoplay:3500,autoplayDisableOnInteraction:!1,loop:!0,effect:"fade"}),toast=myApp.toast("message",'<i class="fa fa-exclamation-triangle"></i>',{});noNetwork=function(){toast.show(stringObj.text.noNetwork),$(".toast-container").css("color","#F26531"),$(".toast-container").css("top","50%"),$(".toast-container").css("left","45%"),$(".toast-container").css("width","40%"),$(".toast-container").css("background-color","rgba(30,30,30,.85)")},$(document).ready(function(){loginObj.init()}),setParameter=function(o){if("tw"===_region)e={mur:o,modacc:stringObj.shop_b.moduleaccount,modvrf:CryptoJS.SHA256(stringObj.shop_b.moduleaccount+stringObj.shop_b.modulepassword).toString(),redirect_uri:"http://"+stringObj.WEB_URL+"/pm_b-transform"};else var e={mur:o,modacc:_region+"_"+stringObj.shop_b.moduleaccount,modvrf:CryptoJS.SHA256(_region+"_"+stringObj.shop_b.moduleaccount+stringObj.shop_b.modulepassword).toString(),redirect_uri:"http://"+stringObj.WEB_URL+"/pm_b-transform"};Cookies.set("parameter",encodeURIComponent(btoa(JSON.stringify(e))))};