var reportObj={dataUrl:{queryshopreport:"http://"+stringObj.API_IP+":"+stringObj.PORT+"/shop/queryshopreport",queryshopdetailreport:"http://"+stringObj.API_IP+":"+stringObj.PORT+"/shop/queryshopdetailreport",queryshopsalesoverview:"http://"+stringObj.API_IP+":"+stringObj.PORT+"/shop/queryshopsalesoverview"},queryshopreportfront:function(r){myApp.showIndicator();var e=indexObj._dataCookies(indexObj._storage.main)||{},t=indexObj._dataStorage(branchObj.storage.branchData),r={modacc:stringObj.shop.moduleaccount,modvrf:Cookies.get("modvrf"),sat:e.sat,query_start:r.query_start,query_end:r.query_end,sd_id:t.sd_id,scm_producttype:r.scm_producttype,usestatus:r.usestatus};indexObj._wcfget({url:reportObj.dataUrl.queryshopsalesoverview,para:r,success:function(e){if(myApp.hideIndicator(),indexObj.jsonUrlDecode(e),e.queryshopsalesoverviewresult){var t=JSON.parse(JSON.stringify(e.queryshopsalesoverviewresult));if("000000000"===t.message_no){t.report_result.query_start=t.report_result.query_start.substring(0,10),t.report_result.query_end=t.report_result.query_end.substring(0,10),t.report_result.query_start=t.report_result.query_start.replace(/-/gi,"/"),t.report_result.query_end=t.report_result.query_end.replace(/-/gi,"/"),t.report_result.totalamount=t.report_result.totalamount.replace(/\B(?=(\d{3})+(?!\d))/g,","),t.report_result.flowfeeamount=t.report_result.flowfeeamount.replace(/\B(?=(\d{3})+(?!\d))/g,","),t.report_result.platfeeamount=t.report_result.platfeeamount.replace(/\B(?=(\d{3})+(?!\d))/g,","),t.report_result.servicefeeamount=t.report_result.servicefeeamount.replace(/\B(?=(\d{3})+(?!\d))/g,","),t.report_result.revenueamount=t.report_result.revenueamount.replace(/\B(?=(\d{3})+(?!\d))/g,","),r.usestatus?t.report_record=t.report_Used:t.report_record=t.report_Unused;for(var o in t.report_record)t.report_record[o].scg_id=t.report_record[o].scg_id.slice(0,2)+"***"+t.report_record[o].scg_id.slice(-5),t.report_record[o].scg_totalamounts=parseInt(t.report_record[o].scg_totalamount),t.report_record[o].scg_totalamount=t.report_record[o].scg_totalamount.replace(/\B(?=(\d{3})+(?!\d))/g,","),t.report_record[o].service_fees=parseInt(t.report_record[o].service_fee),t.report_record[o].service_fee=t.report_record[o].service_fee.replace(/\B(?=(\d{3})+(?!\d))/g,","),t.report_record[o].revenues=parseInt(t.report_record[o].revenue),t.report_record[o].revenue=t.report_record[o].revenue.replace(/\B(?=(\d{3})+(?!\d))/g,",");var s=indexObj.template("templateShopReport"),a=s(t);$$(".shop-report-tab .data-table-block").html(a),indexObj._dataStorage("queryshopsalesoverview",t),$(".shop-report-tab .sortable-cell").click(function(){$(".shop-report-tab .sortable-cell").removeClass("sortable-active"),$(this).addClass("sortable-active"),$(this).hasClass("sortable-asc")?($(this).removeClass("sortable-asc"),$(this).addClass("sortable-desc"),$(this).hasClass("scg_id")?t.report_record.sort(sortByProperty("scg_id",-1)):$(this).hasClass("scg_totalamount")?t.report_record.sort(sortByProperty("scg_totalamounts",-1)):$(this).hasClass("service_fee")?t.report_record.sort(sortByProperty("service_fees",-1)):$(this).hasClass("revenue")?t.report_record.sort(sortByProperty("revenues",-1)):$(this).hasClass("scm_title")&&t.report_record.sort(sortByProperty("scm_title",-1))):($(this).removeClass("sortable-desc"),$(this).addClass("sortable-asc"),$(this).hasClass("scg_id")?t.report_record.sort(sortByProperty("scg_id",1)):$(this).hasClass("scg_totalamount")?t.report_record.sort(sortByProperty("scg_totalamounts",1)):$(this).hasClass("service_fee")?t.report_record.sort(sortByProperty("service_fees",1)):$(this).hasClass("revenue")?t.report_record.sort(sortByProperty("revenues",1)):$(this).hasClass("scm_title")&&t.report_record.sort(sortByProperty("scm_title",1))),s=indexObj.template("templateShopReportItems"),a=s(t),$$(".shop-report-tab .data-table-block .card-content tbody").html(a),indexObj._dataStorage("queryshopsalesoverview",t)})}else"171011002"===t.message_no?myApp.alert(stringObj.text.search_null,stringObj.text.warn):(stringObj.return_header(t.message_no),_tip&&(myApp.alert(_tip+"( "+t.message_no+" )",stringObj.text.warn),_tip=null))}},error:function(r){console.log("error:"+JSON.stringify(r)),myApp.hideIndicator()}})},queryshopreport:function(r,e){myApp.showIndicator();var t=indexObj._dataCookies(indexObj._storage.main)||{},o=indexObj._dataStorage(branchObj.storage.branchData),s={modacc:stringObj.shop.moduleaccount,modvrf:Cookies.get("modvrf"),sat:t.sat,query_start:r,query_end:e,sd_id:o.sd_id};indexObj._wcfget({url:reportObj.dataUrl.queryshopreport,para:s,success:function(r){if(myApp.hideIndicator(),indexObj.jsonUrlDecode(r),r.queryshopreportresult){var e=JSON.parse(JSON.stringify(r.queryshopreportresult));if("000000000"===e.message_no){e.report_head.query_start=e.report_head.query_start.substring(0,10),e.report_head.query_end=e.report_head.query_end.substring(0,10),e.report_head.totalamount=e.report_head.totalamount.replace(/\B(?=(\d{3})+(?!\d))/g,","),e.report_head.flowfeeamount=e.report_head.flowfeeamount.replace(/\B(?=(\d{3})+(?!\d))/g,","),e.report_head.platfeeamount=e.report_head.platfeeamount.replace(/\B(?=(\d{3})+(?!\d))/g,","),e.report_head.revenueamount=e.report_head.revenueamount.replace(/\B(?=(\d{3})+(?!\d))/g,",");for(var t in e.report_record)e.report_record[t].scg_totalamounts=parseInt(e.report_record[t].scg_totalamount),e.report_record[t].scg_totalamount=e.report_record[t].scg_totalamount.replace(/\B(?=(\d{3})+(?!\d))/g,","),e.report_record[t].flow_fees=parseInt(e.report_record[t].flow_fee),e.report_record[t].flow_fee=e.report_record[t].flow_fee.replace(/\B(?=(\d{3})+(?!\d))/g,","),e.report_record[t].plat_fees=parseInt(e.report_record[t].plat_fee),e.report_record[t].plat_fee=e.report_record[t].plat_fee.replace(/\B(?=(\d{3})+(?!\d))/g,","),e.report_record[t].revenues=parseInt(e.report_record[t].revenue),e.report_record[t].revenue=e.report_record[t].revenue.replace(/\B(?=(\d{3})+(?!\d))/g,",");var o=indexObj.template("templateShopReport"),s=o(e);$$(".shop-report-tab .data-table-block").html(s),indexObj._dataStorage("queryshopreport",e),$(".shop-report-tab .sortable-cell").click(function(){$(".shop-report-tab .sortable-cell").removeClass("sortable-active"),$(this).addClass("sortable-active"),$(this).hasClass("sortable-asc")?($(this).removeClass("sortable-asc"),$(this).addClass("sortable-desc"),$(this).hasClass("scg_id")?e.report_record.sort(sortByProperty("scg_id",-1)):$(this).hasClass("scg_totalamount")?e.report_record.sort(sortByProperty("scg_totalamounts",-1)):$(this).hasClass("flow_fee")?e.report_record.sort(sortByProperty("flow_fees",-1)):$(this).hasClass("plat_fee")?e.report_record.sort(sortByProperty("plat_fees",-1)):$(this).hasClass("revenue")&&e.report_record.sort(sortByProperty("revenues",-1))):($(this).removeClass("sortable-desc"),$(this).addClass("sortable-asc"),$(this).hasClass("scg_id")?e.report_record.sort(sortByProperty("scg_id",1)):$(this).hasClass("scg_totalamount")?e.report_record.sort(sortByProperty("scg_totalamounts",1)):$(this).hasClass("flow_fee")?e.report_record.sort(sortByProperty("flow_fee",1)):$(this).hasClass("plat_fee")?e.report_record.sort(sortByProperty("plat_fee",1)):$(this).hasClass("revenue")&&e.report_record.sort(sortByProperty("revenue",1))),o=indexObj.template("templateShopReportItems"),s=o(e),$$(".shop-report-tab .data-table-block .card-content tbody").html(s),indexObj._dataStorage("queryshopreport",e)})}else"171011002"===e.message_no?myApp.alert(stringObj.text.search_null,stringObj.text.warn):(stringObj.return_header(e.message_no),_tip&&(myApp.alert(_tip+"( "+e.message_no+" )",stringObj.text.warn),_tip=null))}},error:function(r){console.log("error:"+JSON.stringify(r)),myApp.hideIndicator()}})},queryshopdetailreport:function(r,e){myApp.showIndicator();var t=indexObj._dataCookies(indexObj._storage.main)||{},o=indexObj._dataStorage(branchObj.storage.branchData),s={modacc:stringObj.shop.moduleaccount,modvrf:Cookies.get("modvrf"),sat:t.sat,query_start:r,query_end:e,sd_id:o.sd_id};indexObj._wcfget({url:reportObj.dataUrl.queryshopdetailreport,para:s,success:function(r){if(myApp.hideIndicator(),indexObj.jsonUrlDecode(r),r.queryshopdetailreportresult){var e=JSON.parse(JSON.stringify(r.queryshopdetailreportresult));if("000000000"===e.message_no){e.report_head.query_start=e.report_head.query_start.substring(0,10),e.report_head.query_end=e.report_head.query_end.substring(0,10),e.report_head.totalamount=e.report_head.totalamount.replace(/\B(?=(\d{3})+(?!\d))/g,",");for(var t in e.report_record)e.report_record[t].scg_buyprices=parseInt(e.report_record[t].scg_buyprice),e.report_record[t].scg_buyprice=e.report_record[t].scg_buyprice.replace(/\B(?=(\d{3})+(?!\d))/g,","),e.report_record[t].scg_buyamounts=parseInt(e.report_record[t].scg_buyamount),e.report_record[t].scg_buyamount=e.report_record[t].scg_buyamount.replace(/\B(?=(\d{3})+(?!\d))/g,","),e.report_record[t].scg_totalamounts=parseInt(e.report_record[t].scg_totalamount),e.report_record[t].scg_totalamount=e.report_record[t].scg_totalamount.replace(/\B(?=(\d{3})+(?!\d))/g,",");var o=indexObj.template("templateShopDetailReport"),s=o(e);$$(".shop-detail-report-tab .data-table-block").html(s),indexObj._dataStorage("queryshopdetailreport",e),$(".shop-detail-report-tab .sortable-cell").click(function(){$(".shop-detail-report-tab .sortable-cell").removeClass("sortable-active"),$(this).addClass("sortable-active"),$(this).hasClass("sortable-asc")?($(this).removeClass("sortable-asc"),$(this).addClass("sortable-desc"),$(this).hasClass("scg_id")?e.report_record.sort(sortByProperty("scg_id",-1)):$(this).hasClass("scg_buyprice")?e.report_record.sort(sortByProperty("scg_buyprices",-1)):$(this).hasClass("scg_buyamount")?e.report_record.sort(sortByProperty("scg_buyamounts",-1)):$(this).hasClass("scg_totalamount")&&e.report_record.sort(sortByProperty("scg_totalamounts",-1))):($(this).removeClass("sortable-desc"),$(this).addClass("sortable-asc"),$(this).hasClass("scg_id")?e.report_record.sort(sortByProperty("scg_id",1)):$(this).hasClass("scg_buyprice")?e.report_record.sort(sortByProperty("scg_buyprices",1)):$(this).hasClass("scg_buyamount")?e.report_record.sort(sortByProperty("scg_buyamounts",1)):$(this).hasClass("scg_totalamount")&&e.report_record.sort(sortByProperty("scg_totalamounts",1))),o=indexObj.template("templateShopDetailReportItems"),s=o(e),$$(".shop-detail-report-tab .data-table-block .card-content tbody").html(s),indexObj._dataStorage("queryshopdetailreport",e)})}else"171012002"===e.message_no?myApp.alert(stringObj.text.search_null,stringObj.text.warn):(stringObj.return_header(e.message_no),_tip&&(myApp.alert(_tip+"( "+e.message_no+" )",stringObj.text.warn),_tip=null))}},error:function(r){myApp.hideIndicator()}})},shopTableInit:function(r){r.query;isMobile.Android()||isMobile.iOS()||$(".tabs .page-content").niceScroll({cursorcolor:"rgba(100,100,100,.9)",cursoropacitymin:0,cursorborder:"1px solid #000"});var e=myApp.calendar({input:".shop-report-tab .query_start",dateFormat:"yyyy/mm/dd",monthNames:stringObj.text.monthNames}),t=new Date;$(".shop-report-tab .query_start").change(function(){e.close(),$(".shop-report-tab .query_end").attr("placeholder",stringObj.text.input_end_date),t=new Date($(this).val());var r=myApp.calendar({input:".shop-report-tab .query_end",dateFormat:"yyyy/mm/dd",monthNames:stringObj.text.monthNames,disabled:{from:new Date("1911/01/01"),to:new Date($(this).val()).setDate(t.getDate()-1)}});$(".shop-report-tab .query_end").change(function(){r.close()})}),nowTab="shop-report",$$(".shop-report-tab").on("show",function(){nowTab="shop-report",$(".buttons-row a").removeClass("active"),$(".shop-report").addClass("active")}),$$(".shop-detail-report-tab").on("show",function(){nowTab="shop-detail-report",$(".buttons-row a").removeClass("active"),$(".shop-detail-report").addClass("active");var r=myApp.calendar({input:".shop-detail-report-tab .query_start",dateFormat:"yyyy/mm/dd",monthNames:stringObj.text.monthNames});t=new Date,$(".shop-detail-report-tab .query_start").change(function(){r.close(),$(".shop-detail-report-tab .query_end").attr("placeholder",stringObj.text.input_end_date),t=new Date($(this).val());var e=myApp.calendar({input:".shop-detail-report-tab .query_end",dateFormat:"yyyy/mm/dd",monthNames:stringObj.text.monthNames,disabled:{from:new Date("1911/01/01"),to:new Date($(this).val()).setDate(t.getDate()-1)}});$(".shop-detail-report-tab .query_end").change(function(){e.close()})})});var o,s;$(".shop-report-tab .search").click(function(){""===$(".shop-report-tab .query_start").val()||""===$(".shop-report-tab .query_end").val()?myApp.alert(stringObj.text.data_not_complete,stringObj.text.warn):(o=new Date($(".shop-report-tab .query_start").val())/864e5,(s=new Date($(".shop-report-tab .query_end").val())/864e5)-o>31?myApp.alert(stringObj.text.not_more_than_31days,stringObj.text.warn):reportObj.queryshopreport($(".shop-report-tab .query_start").val(),$(".shop-report-tab .query_end").val()))}),$(".shop-detail-report-tab .search").click(function(){""===$(".shop-detail-report-tab .query_start").val()||""===$(".shop-detail-report-tab .query_end").val()?myApp.alert(stringObj.text.data_not_complete,stringObj.text.warn):(o=new Date($(".shop-detail-report-tab .query_start").val())/864e5,(s=new Date($(".shop-detail-report-tab .query_end").val())/864e5)-o>31?myApp.alert(stringObj.text.not_more_than_31days,stringObj.text.warn):reportObj.queryshopdetailreport($(".shop-detail-report-tab .query_start").val(),$(".shop-detail-report-tab .query_end").val()))}),$(".print").click(function(r){localStorage.setItem("nowTab",nowTab),"shop-report"===nowTab?indexObj._dataStorage("queryshopreport")?window.open("http://"+stringObj.WEB_URL+"/Shop/printShopTable","_blank"):myApp.alert(stringObj.text.search_null,stringObj.text.warn):"shop-detail-report"===nowTab&&(indexObj._dataStorage("queryshopdetailreport")?window.open("http://"+stringObj.WEB_URL+"/Shop/printShopTable","_blank"):myApp.alert(stringObj.text.search_null,stringObj.text.warn))})},shopTableFrontInit:function(r){r.query;isMobile.Android()||isMobile.iOS()||$(".tabs .page-content").niceScroll({cursorcolor:"rgba(100,100,100,.9)",cursoropacitymin:0,cursorborder:"1px solid #000"});var e=indexObj._dataStorage(branchObj.storage.shop_report_data)||{};$(".search-block").find(".query_start").val(e.query_start),$(".search-block").find(".query_end").val(e.query_end),reportObj.queryshopreportfront(e),$(".print").click(function(r){indexObj._dataStorage("queryshopsalesoverview")?window.open("http://"+stringObj.WEB_URL+"/printShopTableFront","_blank"):myApp.alert(stringObj.text.search_null,stringObj.text.warn)})}};$$(document).on("pageInit",function(r){var e=r.detail.page;switch("shop-table"!==e.name&&(localStorage.removeItem("nowTab"),indexObj._dataStorage("queryshopreport",null),indexObj._dataStorage("queryshopdetailreport",null)),e.name){case"shop-table":reportObj.shopTableInit(e);break;case"shop-table-front":reportObj.shopTableFrontInit(e)}});