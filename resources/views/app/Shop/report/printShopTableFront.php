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
		.data-table-totalcount,
		.data-table-flowfeeamount,
		.data-table-platfeeamount,
		.data-table-revenueamount,
		.data-table-buyamount {
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
			background: black;
			color: white;
		}
		tbody {
			font-size: 1.15em;

		}
		.shop-detail-report th:nth-child(3){
			width:15%;
		}
		.shop-detail-report th:nth-child(4),th:nth-child(6){
			width:14%;
		}
		.shop-detail-report th:nth-child(5){
			width:7%;
		}
		.shop-report th:nth-child(1){
			width:13%;
		}

		.shop-report th:nth-child(2),.shop-report th:nth-child(3),.shop-report th:nth-child(4),.shop-report th:nth-child(5){
			width:17%;
		}
		td,th{
			padding: 0;
    		position: relative;
    		padding: 5px;
		}
/*		tr:nth-child(4n-1) {
	      	background: linen;
	    }
	    tr:nth-child(4n) {
	      	background: linen;
	    }*/
	    tr:nth-child(even) td:before{
	      	background-color: rgba(0,0,0,0);
	    }
		.numeric-cell.text-right{
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
		<div class="print-block" data-page="shop-table">

		</div>

		<script type="text/javascript" src="../app/libs/jquery/dist/jquery-1.11.3.min.js"></script>
		<script>
			if (localStorage.getItem('queryshopsalesoverview')) {
				var queryshopsalesoverview = JSON.parse(localStorage.getItem('queryshopsalesoverview'));

				var size = 16;
				var ran = queryshopsalesoverview.report_record.length % size;
				if(ran == 0){
					ran = queryshopsalesoverview.report_record.length / size;
				}else{
					ran = Math.floor(queryshopsalesoverview.report_record.length / size) +1;
				}
				for (var i = 0; i < ran; i++) {
					$('.print-block').append('<div class="data-table-block shop-report table-'+i+'">' +
						'<div class="data-table data-table-init card">' +
						'<div class="card-header">' +
						'<div class="data-table-title name"><b></b></div>' +
						'<div class="data-table-title">銷貨對帳表</div>' +
						'<div class="row">' +
						'<div class="col-50">銷售總金額：<span class="data-table-totalamount"></span></div>' +
						'<div class="col-50">金流總手續費：<span class="data-table-flowfeeamount"></span></div>' +
						'<div class="col-50">應收總金額：<span class="data-table-revenueamount"></span></div>' +
						'<div class="col-50">平台總手續費：<span class="data-table-platfeeamount"></span></div>' +
						'<div class="col-65 data-table-date">期間：<span></span></div>' +
						'<div class="col-35">訂單數量：<span class="data-table-totalcount"></span></div>' +
						'</div>' +
						'</div>' +
						'<div class="card-content">' +
						'<table>' +
						'<thead>' +
						'<tr>' +
						'<th class="label-cell sortable-cell sortable-active scg_id">訂單編號</th>' +
						'<th class="numeric-cell sortable-cell scm_title">商品名稱</th>' +
						// '<th class="numeric-cell sortable-cell scg_buyprice">售價</th>' +
						// '<th class="numeric-cell sortable-cell scg_buyamount">數量</th>' +
						'<th class="numeric-cell sortable-cell scg_totalamount">訂單金額</th>' +
						'<th class="numeric-cell sortable-cell service_fee">服務費</th>' +
						// '<th class="numeric-cell sortable-cell flow_fee">金流手續費</th>' +
						// '<th class="numeric-cell sortable-cell plat_fee">平台手續費(10%)</th>' +
						'<th class="numeric-cell sortable-cell revenue">應收金額</th>' +
						'</tr>' +
						'</thead>' +
						'<tbody>' +
						'</tbody>' +
						'</table>' +
						'</div>' +
						'<div class="card-footer">' +
						'<div class="row">' +
						'<div class="col-50"></div>' +
						'<div class="col-50">製表日期：<span class="data-table-create_date"></span></div>' +
						'</div>' +
						'</div>' +
						'</div>' +
						'<div class="page-num">'+ (i+1) + '/' + (ran) +'</div>'+
						'</div>');

					//表身
					for (var j = size * i; j < size * (i + 1); j++) {
						if (j > queryshopsalesoverview.report_record.length-1) {
							break;
						}
						$('.table-'+ i + ' tbody').append('<tr>' +
						'<td class="label-cell">' + queryshopsalesoverview.report_record[j].scg_id + '</td>' +
						'<td class="numeric-cell">' + queryshopsalesoverview.report_record[j].scm_title + '</td>' +
						// '<td class="numeric-cell text-right">' + queryshopsalesoverview.report_record[j].scg_buyprice + '</td>' +
						// '<td class="numeric-cell text-right">' + queryshopsalesoverview.report_record[j].scg_buyamount + '</td>' +
						'<td class="numeric-cell text-right">' + queryshopsalesoverview.report_record[j].scg_totalamount + '</td>' +
						'<td class="numeric-cell text-right">' + queryshopsalesoverview.report_record[j].service_fee + '</td>' +
						// '<td class="numeric-cell text-right">' + queryshopsalesoverview.report_record[j].flow_fee + '</td>' +
						// '<td class="numeric-cell text-right">' + queryshopsalesoverview.report_record[j].plat_fee + '</td>' +
						'<td class="numeric-cell text-right">' + queryshopsalesoverview.report_record[j].revenue + '</td>' +
						'</tr>');
					}
				}

				//表頭
				$('.data-table-title b').html(queryshopsalesoverview.report_result.sd_shopname);
				$('.data-table-totalamount').html('NT ' + queryshopsalesoverview.report_result.totalamount);
				$('.data-table-flowfeeamount').html('NT ' + queryshopsalesoverview.report_result.flowfeeamount);
				$('.data-table-revenueamount').html('NT ' + queryshopsalesoverview.report_result.revenueamount);
				$('.data-table-platfeeamount').html('NT ' + queryshopsalesoverview.report_result.platfeeamount);
				$('.data-table-totalcount').html(queryshopsalesoverview.report_result.totalcount);
				$('.data-table-date span').html(queryshopsalesoverview.report_result.query_start + ' ~ ' + queryshopsalesoverview.report_result.query_end);

				//表尾
				$('.data-table-create_date').html(queryshopsalesoverview.report_result.create_date);

				window.print();

			}
		</script>

	</body>
</html>