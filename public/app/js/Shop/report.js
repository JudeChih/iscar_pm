
var reportObj = {
    dataUrl: {
        queryshopreport: 'http://' + stringObj.API_IP + ':' + stringObj.PORT + '/shop/queryshopreport',
        queryshopdetailreport: 'http://' + stringObj.API_IP + ':' + stringObj.PORT + '/shop/queryshopdetailreport',
        queryshopsalesoverview: 'http://' + stringObj.API_IP + ':' + stringObj.PORT + '/shop/queryshopsalesoverview',
    },
    //前台銷貨對帳查詢
    queryshopreportfront: function(data) {
        myApp.showIndicator();
        var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
        var branchData = indexObj._dataStorage(branchObj.storage.branchData);

        var data = {
            modacc: stringObj.shop.moduleaccount,
            modvrf: Cookies.get('modvrf'),
            sat: mainSg.sat,
            query_start: data.query_start,
            query_end: data.query_end,
            sd_id: branchData.sd_id,
            scm_producttype: data.scm_producttype,
            usestatus: data.usestatus
        };

        indexObj._wcfget({
            url: reportObj.dataUrl.queryshopsalesoverview,
            para: data,
            success: function(r) {
                myApp.hideIndicator();
                indexObj.jsonUrlDecode(r);
                // console.log(JSON.stringify(r));
                if (r.queryshopsalesoverviewresult) {
                    var rObj = JSON.parse(JSON.stringify(r.queryshopsalesoverviewresult));
                    if (rObj.message_no === "000000000") {

                        rObj.report_result.query_start = rObj.report_result.query_start.substring(0, 10);
                        rObj.report_result.query_end = rObj.report_result.query_end.substring(0, 10);
                        rObj.report_result.query_start = rObj.report_result.query_start.replace(/-/ig, '/');
                        rObj.report_result.query_end = rObj.report_result.query_end.replace(/-/ig, '/');

                        //格式化數字加上千分數逗點
                        rObj.report_result.totalamount = rObj.report_result.totalamount.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        rObj.report_result.flowfeeamount = rObj.report_result.flowfeeamount.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        rObj.report_result.platfeeamount = rObj.report_result.platfeeamount.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        rObj.report_result.servicefeeamount = rObj.report_result.servicefeeamount.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        rObj.report_result.revenueamount = rObj.report_result.revenueamount.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                        if(data.usestatus){
                            rObj.report_record = rObj.report_Used;
                        }else{
                            rObj.report_record = rObj.report_Unused;
                        }
                        for (var i in rObj.report_record) {
                 
                            rObj.report_record[i].scg_id = rObj.report_record[i].scg_id.slice(0,2)+'***'+rObj.report_record[i].scg_id.slice(-5);

                            rObj.report_record[i].scg_totalamounts = parseInt(rObj.report_record[i].scg_totalamount);
                            rObj.report_record[i].scg_totalamount = rObj.report_record[i].scg_totalamount.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            rObj.report_record[i].service_fees = parseInt(rObj.report_record[i].service_fee);
                            rObj.report_record[i].service_fee = rObj.report_record[i].service_fee.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            // rObj.report_record[i].flow_fees = parseInt(rObj.report_record[i].flow_fee);
                            // rObj.report_record[i].flow_fee = rObj.report_record[i].flow_fee.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            // rObj.report_record[i].plat_fees = parseInt(rObj.report_record[i].plat_fee);
                            // rObj.report_record[i].plat_fee = rObj.report_record[i].plat_fee.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            rObj.report_record[i].revenues = parseInt(rObj.report_record[i].revenue);
                            rObj.report_record[i].revenue = rObj.report_record[i].revenue.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            // rObj.report_record[i].scg_totalamounts = parseInt(rObj.report_record[i].scg_totalamount);
                        }

                        var temp = indexObj.template('templateShopReport');
                        var item = temp(rObj);
                        $$('.shop-report-tab .data-table-block').html(item);
                        indexObj._dataStorage('queryshopsalesoverview', rObj); //將查詢資料暫存


                        $('.shop-report-tab .sortable-cell').click(function() {
                            $('.shop-report-tab .sortable-cell').removeClass('sortable-active');
                            $(this).addClass('sortable-active');
                            if ($(this).hasClass('sortable-asc')) {
                                $(this).removeClass('sortable-asc');
                                $(this).addClass('sortable-desc');
                                if ($(this).hasClass('scg_id')) {
                                    rObj.report_record.sort(sortByProperty('scg_id', -1));
                                } else if ($(this).hasClass('scg_totalamount')) {
                                    rObj.report_record.sort(sortByProperty('scg_totalamounts', -1));
                                } else if ($(this).hasClass('service_fee')) {
                                    rObj.report_record.sort(sortByProperty('service_fees', -1));
                                } else if ($(this).hasClass('revenue')) {
                                    rObj.report_record.sort(sortByProperty('revenues', -1));
                                } else if ($(this).hasClass('scm_title')) {
                                    rObj.report_record.sort(sortByProperty('scm_title', -1));
                                }
                            } else {
                                $(this).removeClass('sortable-desc');
                                $(this).addClass('sortable-asc');
                                if ($(this).hasClass('scg_id')) {
                                    rObj.report_record.sort(sortByProperty('scg_id', 1));
                                } else if ($(this).hasClass('scg_totalamount')) {
                                    rObj.report_record.sort(sortByProperty('scg_totalamounts', 1));
                                } else if ($(this).hasClass('service_fee')) {
                                    rObj.report_record.sort(sortByProperty('service_fees', 1));
                                } else if ($(this).hasClass('revenue')) {
                                    rObj.report_record.sort(sortByProperty('revenues', 1));
                                } else if ($(this).hasClass('scm_title')) {
                                    rObj.report_record.sort(sortByProperty('scm_title', 1));
                                } 
                            }
                            temp = indexObj.template('templateShopReportItems');
                            item = temp(rObj);
                            $$('.shop-report-tab .data-table-block .card-content tbody').html(item);

                            indexObj._dataStorage('queryshopsalesoverview', rObj); //將查詢資料暫存
                        });


                    } else if (rObj.message_no === "171011002") {
                        myApp.alert(stringObj.text.search_null, stringObj.text.warn);
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
                console.log('error:' + JSON.stringify(r));
                myApp.hideIndicator();
            }
        });
    },
    //銷貨對帳查詢
    queryshopreport: function(query_start, query_end) {
        myApp.showIndicator();
        var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
        var branchData = indexObj._dataStorage(branchObj.storage.branchData);

        var data = {
            modacc: stringObj.shop.moduleaccount,
            modvrf: Cookies.get('modvrf'),
            sat: mainSg.sat,
            query_start: query_start,
            query_end: query_end,
            sd_id: branchData.sd_id
        };

        indexObj._wcfget({
            url: reportObj.dataUrl.queryshopreport,
            para: data,
            success: function(r) {
                myApp.hideIndicator();
                indexObj.jsonUrlDecode(r);
                if (r.queryshopreportresult) {
                    var rObj = JSON.parse(JSON.stringify(r.queryshopreportresult));
                    if (rObj.message_no === "000000000") {

                        rObj.report_head.query_start = rObj.report_head.query_start.substring(0, 10);
                        rObj.report_head.query_end = rObj.report_head.query_end.substring(0, 10);

                        //格式化數字加上千分數逗點
                        rObj.report_head.totalamount = rObj.report_head.totalamount.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        rObj.report_head.flowfeeamount = rObj.report_head.flowfeeamount.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        rObj.report_head.platfeeamount = rObj.report_head.platfeeamount.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        rObj.report_head.revenueamount = rObj.report_head.revenueamount.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                        for (var i in rObj.report_record) {
                            rObj.report_record[i].scg_totalamounts = parseInt(rObj.report_record[i].scg_totalamount);
                            rObj.report_record[i].scg_totalamount = rObj.report_record[i].scg_totalamount.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            rObj.report_record[i].flow_fees = parseInt(rObj.report_record[i].flow_fee);
                            rObj.report_record[i].flow_fee = rObj.report_record[i].flow_fee.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            rObj.report_record[i].plat_fees = parseInt(rObj.report_record[i].plat_fee);
                            rObj.report_record[i].plat_fee = rObj.report_record[i].plat_fee.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            rObj.report_record[i].revenues = parseInt(rObj.report_record[i].revenue);
                            rObj.report_record[i].revenue = rObj.report_record[i].revenue.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }

                        var temp = indexObj.template('templateShopReport');
                        var item = temp(rObj);
                        $$('.shop-report-tab .data-table-block').html(item);

                        indexObj._dataStorage('queryshopreport', rObj); //將查詢資料暫存

                        $('.shop-report-tab .sortable-cell').click(function() {
                            $('.shop-report-tab .sortable-cell').removeClass('sortable-active');
                            $(this).addClass('sortable-active');
                            if ($(this).hasClass('sortable-asc')) {
                                $(this).removeClass('sortable-asc');
                                $(this).addClass('sortable-desc');
                                if ($(this).hasClass('scg_id')) {
                                    rObj.report_record.sort(sortByProperty('scg_id', -1));
                                } else if ($(this).hasClass('scg_totalamount')) {
                                    rObj.report_record.sort(sortByProperty('scg_totalamounts', -1));
                                } else if ($(this).hasClass('flow_fee')) {
                                    rObj.report_record.sort(sortByProperty('flow_fees', -1));
                                } else if ($(this).hasClass('plat_fee')) {
                                    rObj.report_record.sort(sortByProperty('plat_fees', -1));
                                } else if ($(this).hasClass('revenue')) {
                                    rObj.report_record.sort(sortByProperty('revenues', -1));
                                }
                            } else {
                                $(this).removeClass('sortable-desc');
                                $(this).addClass('sortable-asc');
                                if ($(this).hasClass('scg_id')) {
                                    rObj.report_record.sort(sortByProperty('scg_id', 1));
                                } else if ($(this).hasClass('scg_totalamount')) {
                                    rObj.report_record.sort(sortByProperty('scg_totalamounts', 1));
                                } else if ($(this).hasClass('flow_fee')) {
                                    rObj.report_record.sort(sortByProperty('flow_fee', 1));
                                } else if ($(this).hasClass('plat_fee')) {
                                    rObj.report_record.sort(sortByProperty('plat_fee', 1));
                                } else if ($(this).hasClass('revenue')) {
                                    rObj.report_record.sort(sortByProperty('revenue', 1));
                                }
                            }
                            temp = indexObj.template('templateShopReportItems');
                            item = temp(rObj);
                            $$('.shop-report-tab .data-table-block .card-content tbody').html(item);

                            indexObj._dataStorage('queryshopreport', rObj); //將查詢資料暫存
                        });


                    } else if (rObj.message_no === "171011002") {
                        myApp.alert(stringObj.text.search_null, stringObj.text.warn);
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
                console.log('error:' + JSON.stringify(r));
                myApp.hideIndicator();
            }
        });
    },
    //銷貨明細查詢
    queryshopdetailreport: function(query_start, query_end) {
        myApp.showIndicator();
        var mainSg = indexObj._dataCookies(indexObj._storage.main) || {};
        var branchData = indexObj._dataStorage(branchObj.storage.branchData);

        var data = {
            modacc: stringObj.shop.moduleaccount,
            modvrf: Cookies.get('modvrf'),
            sat: mainSg.sat,
            query_start: query_start,
            query_end: query_end,
            sd_id: branchData.sd_id
        };
        indexObj._wcfget({
            url: reportObj.dataUrl.queryshopdetailreport,
            para: data,
            success: function(r) {
                myApp.hideIndicator();
                indexObj.jsonUrlDecode(r);
                if (r.queryshopdetailreportresult) {
                    var rObj = JSON.parse(JSON.stringify(r.queryshopdetailreportresult));
                    if (rObj.message_no === "000000000") {

                        rObj.report_head.query_start = rObj.report_head.query_start.substring(0, 10);
                        rObj.report_head.query_end = rObj.report_head.query_end.substring(0, 10);

                        //格式化數字加上千分數逗點
                        rObj.report_head.totalamount = rObj.report_head.totalamount.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                        for (var i in rObj.report_record) {
                            rObj.report_record[i].scg_buyprices = parseInt(rObj.report_record[i].scg_buyprice);
                            rObj.report_record[i].scg_buyprice = rObj.report_record[i].scg_buyprice.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            rObj.report_record[i].scg_buyamounts = parseInt(rObj.report_record[i].scg_buyamount);
                            rObj.report_record[i].scg_buyamount = rObj.report_record[i].scg_buyamount.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                            rObj.report_record[i].scg_totalamounts = parseInt(rObj.report_record[i].scg_totalamount);
                            rObj.report_record[i].scg_totalamount = rObj.report_record[i].scg_totalamount.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }


                        var temp = indexObj.template('templateShopDetailReport');
                        var item = temp(rObj);
                        $$('.shop-detail-report-tab .data-table-block').html(item);

                        indexObj._dataStorage('queryshopdetailreport', rObj); //將查詢資料暫存

                        $('.shop-detail-report-tab .sortable-cell').click(function() {
                            $('.shop-detail-report-tab .sortable-cell').removeClass('sortable-active');
                            $(this).addClass('sortable-active');
                            if ($(this).hasClass('sortable-asc')) {
                                $(this).removeClass('sortable-asc');
                                $(this).addClass('sortable-desc');
                                if ($(this).hasClass('scg_id')) {
                                    rObj.report_record.sort(sortByProperty('scg_id', -1));
                                } else if ($(this).hasClass('scg_buyprice')) {
                                    rObj.report_record.sort(sortByProperty('scg_buyprices', -1));
                                } else if ($(this).hasClass('scg_buyamount')) {
                                    rObj.report_record.sort(sortByProperty('scg_buyamounts', -1));
                                } else if ($(this).hasClass('scg_totalamount')) {
                                    rObj.report_record.sort(sortByProperty('scg_totalamounts', -1));
                                }
                            } else {
                                $(this).removeClass('sortable-desc');
                                $(this).addClass('sortable-asc');
                                if ($(this).hasClass('scg_id')) {
                                    rObj.report_record.sort(sortByProperty('scg_id', 1));
                                } else if ($(this).hasClass('scg_buyprice')) {
                                    rObj.report_record.sort(sortByProperty('scg_buyprices', 1));
                                } else if ($(this).hasClass('scg_buyamount')) {
                                    rObj.report_record.sort(sortByProperty('scg_buyamounts', 1));
                                } else if ($(this).hasClass('scg_totalamount')) {
                                    rObj.report_record.sort(sortByProperty('scg_totalamounts', 1));
                                }
                            }
                            temp = indexObj.template('templateShopDetailReportItems');
                            item = temp(rObj);
                            $$('.shop-detail-report-tab .data-table-block .card-content tbody').html(item);

                            indexObj._dataStorage('queryshopdetailreport', rObj); //將查詢資料暫存
                        });


                    } else if (rObj.message_no === "171012002") {
                        myApp.alert(stringObj.text.search_null, stringObj.text.warn);
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
            }
        });
    },
    //汽車特店報表
    shopTableInit: function(page) {
        var q = page.query;

        if (!isMobile.Android() && !isMobile.iOS()) {
            //美化scroll bar
            $(".tabs .page-content").niceScroll({
                cursorcolor: "rgba(100,100,100,.9)",
                cursoropacitymin: 0,
                cursorborder: "1px solid #000"
            });
        }
        //日期區間
        var calendarStart = myApp.calendar({
            input: '.shop-report-tab .query_start',
            dateFormat: 'yyyy/mm/dd',
            monthNames: stringObj.text.monthNames
        });
        var startDate = new Date();
        $('.shop-report-tab .query_start').change(function() {
            calendarStart.close();
            $('.shop-report-tab .query_end').attr('placeholder', stringObj.text.input_end_date);
            startDate = new Date($(this).val());
            var calendarEnd = myApp.calendar({
                input: '.shop-report-tab .query_end',
                dateFormat: 'yyyy/mm/dd',
                monthNames: stringObj.text.monthNames,
                disabled: {
                    from: new Date('1911/01/01'),
                    to: new Date($(this).val()).setDate(startDate.getDate() - 1)
                }
            });
            $('.shop-report-tab .query_end').change(function() {
                calendarEnd.close();
            });
        });

        nowTab = 'shop-report';

        //銷貨對帳報表
        $$('.shop-report-tab').on('show', function() {
            nowTab = 'shop-report';
            $('.buttons-row a').removeClass('active');
            $('.shop-report').addClass('active');
        });

        //銷貨明細報表
        $$('.shop-detail-report-tab').on('show', function() {
            nowTab = 'shop-detail-report';
            $('.buttons-row a').removeClass('active');
            $('.shop-detail-report').addClass('active');

            //日期區間
            var _calendarStart = myApp.calendar({
                input: '.shop-detail-report-tab .query_start',
                dateFormat: 'yyyy/mm/dd',
                monthNames: stringObj.text.monthNames
            });
            startDate = new Date();
            $('.shop-detail-report-tab .query_start').change(function() {
                _calendarStart.close();
                $('.shop-detail-report-tab .query_end').attr('placeholder', stringObj.text.input_end_date);
                startDate = new Date($(this).val());
                var _calendarEnd = myApp.calendar({
                    input: '.shop-detail-report-tab .query_end',
                    dateFormat: 'yyyy/mm/dd',
                    monthNames: stringObj.text.monthNames,
                    disabled: {
                        from: new Date('1911/01/01'),
                        to: new Date($(this).val()).setDate(startDate.getDate() - 1)
                    }
                });
                $('.shop-detail-report-tab .query_end').change(function() {
                    _calendarEnd.close();
                });
            });
        });


        var query_start, query_end;

        //銷貨對帳查詢
        $('.shop-report-tab .search').click(function() {
            if ($('.shop-report-tab .query_start').val() === '' || $('.shop-report-tab .query_end').val() === '') {
                myApp.alert(stringObj.text.data_not_complete, stringObj.text.warn);
            } else {
                query_start = new Date($('.shop-report-tab .query_start').val()) / (1000 * 60 * 60 * 24); //起始日期
                query_end = new Date($('.shop-report-tab .query_end').val()) / (1000 * 60 * 60 * 24); //結束日期
                if (query_end - query_start > 31) {
                    myApp.alert(stringObj.text.not_more_than_31days, stringObj.text.warn);
                } else {
                    reportObj.queryshopreport($('.shop-report-tab .query_start').val(), $('.shop-report-tab .query_end').val());
                }
            }
        });

        //銷售明細查詢
        $('.shop-detail-report-tab .search').click(function() {
            if ($('.shop-detail-report-tab .query_start').val() === '' || $('.shop-detail-report-tab .query_end').val() === '') {
                myApp.alert(stringObj.text.data_not_complete, stringObj.text.warn);
            } else {
                query_start = new Date($('.shop-detail-report-tab .query_start').val()) / (1000 * 60 * 60 * 24); //起始日期
                query_end = new Date($('.shop-detail-report-tab .query_end').val()) / (1000 * 60 * 60 * 24); //結束日期
                if (query_end - query_start > 31) {
                    myApp.alert(stringObj.text.not_more_than_31days, stringObj.text.warn);
                } else {
                    reportObj.queryshopdetailreport($('.shop-detail-report-tab .query_start').val(), $('.shop-detail-report-tab .query_end').val());
                }
            }

        });

        //列印
        $('.print').click(function(event) {
            localStorage.setItem('nowTab', nowTab);
            if (nowTab === 'shop-report') {
                if (indexObj._dataStorage('queryshopreport')) {
                    window.open('http://' + stringObj.WEB_URL + '/Shop/printShopTable', '_blank');
                } else {
                    myApp.alert(stringObj.text.search_null, stringObj.text.warn);
                }
            } else if (nowTab === 'shop-detail-report') {
                if (indexObj._dataStorage('queryshopdetailreport')) {
                    window.open('http://' + stringObj.WEB_URL + '/Shop/printShopTable', '_blank');
                } else {
                    myApp.alert(stringObj.text.search_null, stringObj.text.warn);
                }
            }
        });
    },
    //前台汽車特店報表
    shopTableFrontInit: function(page) {
        var q = page.query;

        if (!isMobile.Android() && !isMobile.iOS()) {
            //美化scroll bar
            $(".tabs .page-content").niceScroll({
                cursorcolor: "rgba(100,100,100,.9)",
                cursoropacitymin: 0,
                cursorborder: "1px solid #000"
            });
        }
        //日期區間
        // var calendarStart = myApp.calendar({
        //     input: '.shop-report-tab .query_start',
        //     dateFormat: 'yyyy/mm/dd',
        //     monthNames: stringObj.text.monthNames
        // });
        // var startDate = new Date();
        // $('.shop-report-tab .query_start').change(function() {
        //     calendarStart.close();
        //     $('.shop-report-tab .query_end').attr('placeholder', stringObj.text.input_end_date);
        //     startDate = new Date($(this).val());
        //     var calendarEnd = myApp.calendar({
        //         input: '.shop-report-tab .query_end',
        //         dateFormat: 'yyyy/mm/dd',
        //         monthNames: stringObj.text.monthNames,
        //         disabled: {
        //             from: new Date('1911/01/01'),
        //             to: new Date($(this).val()).setDate(startDate.getDate() - 1)
        //         }
        //     });
        //     $('.shop-report-tab .query_end').change(function() {
        //         calendarEnd.close();
        //     });
        // });

        // nowTab = 'shop-report';

        // //銷貨對帳報表
        // $$('.shop-report-tab').on('show', function() {
        //     nowTab = 'shop-report';
        //     $('.buttons-row a').removeClass('active');
        //     $('.shop-report').addClass('active');
        // });

        // //銷貨明細報表
        // $$('.shop-detail-report-tab').on('show', function() {
        //     nowTab = 'shop-detail-report';
        //     $('.buttons-row a').removeClass('active');
        //     $('.shop-detail-report').addClass('active');

        //     //日期區間
        //     var _calendarStart = myApp.calendar({
        //         input: '.shop-detail-report-tab .query_start',
        //         dateFormat: 'yyyy/mm/dd',
        //         monthNames: stringObj.text.monthNames
        //     });
        //     startDate = new Date();
        //     $('.shop-detail-report-tab .query_start').change(function() {
        //         _calendarStart.close();
        //         $('.shop-detail-report-tab .query_end').attr('placeholder', stringObj.text.input_end_date);
        //         startDate = new Date($(this).val());
        //         var _calendarEnd = myApp.calendar({
        //             input: '.shop-detail-report-tab .query_end',
        //             dateFormat: 'yyyy/mm/dd',
        //             monthNames: stringObj.text.monthNames,
        //             disabled: {
        //                 from: new Date('1911/01/01'),
        //                 to: new Date($(this).val()).setDate(startDate.getDate() - 1)
        //             }
        //         });
        //         $('.shop-detail-report-tab .query_end').change(function() {
        //             _calendarEnd.close();
        //         });
        //     });
        // });
        var shopReport = indexObj._dataStorage(branchObj.storage.shop_report_data) || {};

        $('.search-block').find('.query_start').val(shopReport.query_start);
        $('.search-block').find('.query_end').val(shopReport.query_end);

        // console.log(shopReport);

        reportObj.queryshopreportfront(shopReport);


        //列印
        $('.print').click(function(event) {
                if (indexObj._dataStorage('queryshopsalesoverview')) {
                    window.open('http://' + stringObj.WEB_URL + '/printShopTableFront', '_blank');
                } else {
                    myApp.alert(stringObj.text.search_null, stringObj.text.warn);
                }
                // if (indexObj._dataStorage('queryshopdetailreport')) {
                //     window.open('http://' + stringObj.WEB_URL + '/Shop/printShopTable', '_blank');
                // } else {
                //     myApp.alert(stringObj.text.search_null, stringObj.text.warn);
                // }
        });
    }
};

//page start
$$(document).on('pageInit', function(e) {
    var page = e.detail.page;

    if (page.name !== 'shop-table') {
        localStorage.removeItem('nowTab');
        indexObj._dataStorage('queryshopreport', null);
        indexObj._dataStorage('queryshopdetailreport', null);
    }

    switch (page.name) {
        case 'shop-table':
            reportObj.shopTableInit(page);
            break;
        case 'shop-table-front':
            reportObj.shopTableFrontInit(page);
            break;
    }
});