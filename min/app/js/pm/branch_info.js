$(function(){function o(){0==$(".lazy").length?($(".lazy_indicator").remove(),$(".lazy_overlay").remove()):$(".lazy").Lazy({bind:"event",delay:0,onFinishedAll:function(){$(".lazy_indicator").remove(),$(".lazy_overlay").remove()},afterLoad:function(o){},onError:function(o){o.prop("src","../app/image/imgDefault.png")}})}function t(){var o=new google.maps.LatLng(parseFloat(lat),parseFloat(lng)),t={zoom:15,center:o,mapTypeId:google.maps.MapTypeId.ROADMAP,mapTypeControl:!1,streetViewControl:!1,scrollwheel:!1},n=new google.maps.Map(document.getElementById("shop-map"),t);e(o,sd_shopname,n)}function e(o,t,e){var n=new google.maps.Marker({position:o,title:t,map:e}),a=new google.maps.InfoWindow;a.setContent('<b style="color:#000;">'+t+"</b>"),a.open(e,n),google.maps.event.addListener(n,"click",function(){a.open(e,n)})}Cookies.set("sd_id",sd_id);var n=$("input[name=shoppoint]").val();null!=n&&void 0!=n&&commonTools._dataStorage("shoppoint",n),"1"===sd_havebind?$(".toolbar-bottom.tabbar").css("display","block"):$(".toolbar-bottom.tabbar").css("display","none"),$(".track").click(function(){addFavorite(sd_id,stringObj.text.branch_img_path+sd_shopphotopath,sd_shopname,sd_shopaddress,sd_shoptel,"","2")});var a=commonTools._dataStorage(commonTools.storage.favorite)||{lastupdate:"",newsList:[],couponList:[],branchList:[],shopcouponList:[]};JSON.stringify(a.branchList).match(sd_id)&&$(".track").html('<img src="../app/image/subscribe.png" onerror=\'this.src="../app/image/imgDefault.png"\' />'),$(".branch-info-left a").on("click",function(){if(Cookies.remove("back"),Cookies.get("offset")){var o=JSON.parse(Cookies.get("offset"));window.location=o.href}else window.location="/pm"}),$(".btn_share").click(function(){commonTools.setPopBox({title:stringObj.text.share,text:'<input class="clone_input" type="text" value="'+url+'"/>',buttons:[{text:stringObj.text.cancel,onClick:function(){removeSomething()}},{text:stringObj.text.copy,onClick:function(){void 0!=Cookies.get("app_version")?(webview.copyText($(".clone_input").val()),removeSomething()):($(".clone_input").select(),document.execCommand("Copy"),removeSomething(),commonTools.setPopBox({title:stringObj.text.warn,text:stringObj.text.already_copy,buttons:[{text:stringObj.text.confirm,onClick:function(){removeSomething()}}]}))}}]})}),$(".toolbar a").on("click",function(){$(this).data("href")&&location.replace($(this).data("href"))}),$(window).load(function(){t(),o(),$(window).width()>=992&&setScrollBar()})});