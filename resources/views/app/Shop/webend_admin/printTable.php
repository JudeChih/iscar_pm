<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="icon" type="image/png" href="../app/image/iscar_icon.png">
		<meta name="theme-color" content="#ffffff">
		<title>isCar就是行</title>
		<style type="text/css">
		html,body {
			position: initial;
			width: 100%;
		height: 100%;
		margin: 0;
		padding: 0;
		background-color: #FAFAFA;
		font-family: Microsoft JhengHei !important;
		}

		.data-table-block {
			position: relative;
        width: 210mm;
        min-height: 297mm;
    }
		.data-table {
		padding: 5%;
		}
		.data-table.card .card-header {
		display: block;
		height: 100%;
		font-size: 1.6em;

		}
		.data-table-title {
		font-size: 1.4em;
		margin-bottom: 5%;
		text-align: center;
		}
		.name{
			margin-bottom: 0;
		}
		.row {
		margin-bottom: 3%;
		display: -webkit-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    -webkit-justify-content: space-between;
    justify-content: space-between;
    -webkit-box-lines: multiple;
    -moz-box-lines: multiple;
    -webkit-flex-wrap: wrap;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-box-align: start;
    -ms-flex-align: start;
    -webkit-align-items: flex-start;
    align-items: flex-start;

		}
		.data-table-totalamount,
		.data-table-totalcount {
		color: firebrick;
		font-weight: bold;
		}
		.data-table-date {
		margin-bottom: 3%;
		}
		table{
			width:100%;
		}
		thead {
		font-size: 1.15em;
		background: steelblue;
		color: snow;

		}
		tbody {
		font-size: 1.15em;

		}
		td,th{
			padding: 0;
    position: relative;
    /* padding-left: 15px;
    padding-right: 15px;
    height: 44px; */
    padding: 5px;
		}
		tr:nth-child(even) {
		background: linen;
		}
		.numeric-cell.price,
		.numeric-cell.create_date{
			text-align: right;
		}
		.card-footer {
		display: block;
		height: 100%;
		font-size: 1.4em;
		margin-top: 3%;
		}
		.row {
		margin-top: 2%;

		}
		.col-50:nth-child(2) {
		text-align: right;
		}

		* {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    .page-num{
    	    position: absolute;
    bottom: 3%;
    left: 48%;
    }

    @page {
        size: A4;
        margin: 0;
    }

		@media print {
    html, body {
        width: 210mm;
            height: 297mm;
        -webkit-print-color-adjust: exact;
    }



    .data-table-block {
        page-break-after: always;
    }
}
		</style>
	</head>
	<body>
		<div class="print-block" data-page="temple-table">

		</div>

		<script type="text/javascript" src="../app/libs/jquery/dist/jquery-1.11.3.min.js"></script>
		<script>
		var nowTab = localStorage.getItem('nowTab');
		if (nowTab === 'reconciliation') {
			if (localStorage.getItem('createblessreport')) {
				var createblessreport = JSON.parse(localStorage.getItem('createblessreport'));

				for (var i = 0; i < Math.floor(createblessreport.tps_record.length / 14) + 1; i++) {
					$('.print-block').append('<div class="data-table-block reconciliation table-'+i+'">' +
						'<div class="data-table data-table-init card">' +
						'<div class="card-header">' +
						'<div class="data-table-title name"><b></b></div>' +
						'<div class="data-table-title">金流對帳報表</div>' +
						'<div class="row">' +
						'<div class="col-50">款項類型：<span class="data-table-tpp_type"></span></div>' +
						'<div class="col-50">銷售總金額：<span class="data-table-totalamount"></span></div>' +
						'</div>' +
						'<div class="data-table-date">期間：<span></span></div>' +
						'</div>' +
						'<div class="card-content">' +
						'<table>' +
						'<thead>' +
						'<tr>' +
						'<th class="label-cell sortable-cell sortable-active tps_id">銷售編號</th>' +
						'<th class="numeric-cell tps_invoice_name">付款人</th>' +
						'<th class="numeric-cell sortable-cell tps_amount">付款金額</th>' +
						'<th class="numeric-cell sortable-cell create_date">建立日期</th>' +
						'</tr>' +
						'</thead>' +
						'<tbody>' +
						'</tbody>' +
						'</table>' +
						'</div>' +
						'<div class="card-footer">' +
						'<div class="row">' +
						'<div class="col-50">紀錄筆數：<span class="data-table-totalcount"></span></div>' +
						'<div class="col-50">製表日期：<span class="data-table-query_date"></span></div>' +
						'</div>' +
						'</div>' +
						'</div>' +
						'<div class="page-num">'+ (i+1) + '/' + (Math.floor(createblessreport.tps_record.length / 19) + 1) +'</div>'+
						'</div>');

					//表身
					for (var j = 19 * i; j < 19 * (i + 1); j++) {
						if (j > createblessreport.tps_record.length-1) {
							break;
						}
						$('.table-'+ i + ' tbody').append('<tr>' +
						'<td class="label-cell">' + createblessreport.tps_record[j].tps_id + '</td>' +
						'<td class="numeric-cell tps_invoice_name">' + createblessreport.tps_record[j].tps_invoice_name + '</td>' +
						'<td class="numeric-cell price">' + createblessreport.tps_record[j].tps_amount + '</td>' +
						'<td class="numeric-cell create_date">' + createblessreport.tps_record[j].create_date + '</td>' +
						'</tr>');
					}
				}

				//表頭
				$('.data-table-title b').html(createblessreport.report_head.sd_shopname);
				$('.data-table-tpp_type').html(createblessreport.report_head.tpp_type);
				$('.data-table-totalamount').html('NT ' + createblessreport.report_head.totalamount);
				$('.data-table-date span').html(createblessreport.report_head.query_start + ' ~ ' + createblessreport.report_head.query_end);

				//表尾
				$('.data-table-totalcount').html(createblessreport.report_foot.totalcount);
				$('.data-table-query_date').html(createblessreport.report_foot.query_date);

				window.print();

			}
		} else if (nowTab === 'sales-details') {
			if (localStorage.getItem('createblesslightreport')) {
				var createblesslightreport = JSON.parse(localStorage.getItem('createblesslightreport'));

				for (var i = 0; i < Math.floor(createblesslightreport.tps_record.length / 20) + 1; i++) {
					$('.print-block').append('<div class="data-table-block reconciliation table-'+i+'">' +
						'<div class="data-table data-table-init card">' +
						'<div class="card-header">' +
						'<div class="data-table-title name"><b></b></div>' +
						'<div class="data-table-title">點燈用銷售明細報表</div>' +
						'<div class="row">' +
						'<div class="col-65">期間：<span class="data-table-daterange"></span></div>' +
						'<div class="col-35">點燈總筆數：<span class="data-table-totalcount"></span></div>' +
						'</div>' +
						'</div>' +
						'<div class="card-content">' +
						'<table>' +
						'<thead>' +
						'<tr>' +
						'<th class="label-cell sortable-cell sortable-active tps_serno">銷售編號</th>'+
                                '<th class="numeric-cell sortable-cell tpsd_serno">銷售明細編號</th>'+
                                '<th class="numeric-cell tpr_name">被點燈人姓名</th>'+
                                '<th class="numeric-cell sortable-cell tpp_name">產品名稱</th>'+
                                '<th class="numeric-cell sortable-cell create_date">建立日期</th>'+
						'</tr>' +
						'</thead>' +
						'<tbody>' +
						'</tbody>' +
						'</table>' +
						'</div>' +
						'<div class="card-footer">' +
						'<div class="row">' +
						'<div class="col-50"></div>' +
						'<div class="col-50">製表日期：<span class="data-table-query_date"></span></div>' +
						'</div>' +
						'</div>' +
						'</div>' +
						'<div class="page-num">'+ (i+1) + '/' + (Math.floor(createblesslightreport.tps_record.length / 20) + 1) +'</div>'+
						'</div>');

					//表身
					for (var j = 20 * i; j < 20 * (i + 1); j++) {
						if (j > createblesslightreport.tps_record.length-1) {
							break;
						}
						$('.table-'+ i + ' tbody').append('<tr>' +
						'<td class="label-cell">' + createblesslightreport.tps_record[j].tps_serno + '</td>' +
						'<td class="numeric-cell">' + createblesslightreport.tps_record[j].tpsd_serno + '</td>' +
						'<td class="numeric-cell tpr_name">' + createblesslightreport.tps_record[j].tpr_name + '</td>' +
						'<td class="numeric-cell">' + createblesslightreport.tps_record[j].tpp_name + '</td>' +
						'<td class="numeric-cell create_date">' + createblesslightreport.tps_record[j].create_date + '</td>' +
						'</tr>');
					}
				}

				//表頭
				$('.data-table-title b').html(createblesslightreport.report_head.sd_shopname);
				$('.data-table-totalcount').html(createblesslightreport.report_head.totalcount);
				$('.data-table-daterange').html(createblesslightreport.report_head.query_start + ' ~ ' + createblesslightreport.report_head.query_end);

				//表尾
				$('.data-table-query_date').html(createblesslightreport.report_foot.query_date);

				window.print();
			}
		}
		</script>

	</body>
</html>