<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="icon" type="image/png" href="app/image/iscar_icon.png">
        <meta name="theme-color" content="#ffffff">
        <title>isCar就是行</title>
<style type="text/css">
html, body {
    position: initial;
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
    font-family: Microsoft JhengHei !important;
}

.print-block {
    width: 210mm;
    min-height: 297mm;
}

.order-form {
    position: relative;
    width: 210mm;
    min-height: 297mm;
}

/* .scg_id, .print-date {
    text-align: right;
} */

.order-form-head {
    text-align: center;
    font-size: 1.6em;
    padding: 5% 0 3% 0;
}

.order-form-head span {
    border-bottom-style: double;
}

.order-form-body {
    padding: 0 5%;
    line-height: 2;
}

.qr-code {
    float: right;
}

.numeric-cell {
    text-align: right;
}

.label-cell {
    text-align: left;
}

.row {
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

.col-65 {
    width: 65%;
}

.col-60 {
    width: 60%;
}

.col-50 {
    width: 50%;
}

.col-45 {
    width: 45%;
}

.col-40 {
    width: 40%;
}

.col-35 {
    width: 35%;
}

.col-5 {
    width: 5%;
}

/* .col-50:nth-child(2) {
text-align: right;
} */
.commodity-details table {
    width: 100%;
    border-collapse: collapse;
}

table th {
    padding: 1% 2%;
    font-weight: normal;
    max-width: 150px;
}
.min40 {
    min-width: 40px;
}
.min60 {
    min-width: 60px;
}
.min80 {
    min-width: 80px;
}
.min100 {
    min-width: 100px;
}
.min120 {
    min-width: 100px;
}
.min130 {
    min-width: 130px;
}

table thead {
    border-top: 1px solid #000;
    border-bottom: 1px solid #000;
}

table thead th {
    font-weight: bold;
}

table tbody {
    border-bottom-style: double;
}

table tfoot span {
    float: right;
}

.order-form-footer {
    position: absolute;
    bottom: 8%;
    padding: 0 5%;
}

.shop-data-block {
    padding-bottom: 3%;
    line-height: 1.5;
}

.shop_name {
    font-size: 2em;
    font-weight: bold;
}

.scg_totalamount {
    text-align: right;
    padding: 0 2%;
}

.company-block img {
    position: absolute;
    bottom: 0;
    right: 5%;
}

/* * {
box-sizing: border-box;
-moz-box-sizing: border-box;
} */
.page-num {
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

    .order-form {
        page-break-after: always;
    }

}

</style>
    </head>
    <body onload="window.print()">
        <div class="print-block">

        </div>
        <script type="text/javascript" src="app/libs/jquery/dist/jquery-1.11.3.min.js"></script>
        <script src="app/libs/jquery/dist/jquery.qrcode-0.12.0.js"></script>
        <script>
        setCharAt = function(str, index, chr) {
            var string = '';
            if (index > str.length - 1) return str;
            if (str.length > 3) {
                for (var i = 0; i < str.length - 2; i++) {
                    string = string + chr;
                }
                return str.substr(0, 1) + string + str.substr(str.length - 1);
            }
            return str.substr(0, index) + chr + str.substr(index + 1);
        };
        if (localStorage.getItem('printdeliverorder')) {
    var _date = new Date();
    var month = _date.getMonth() + 1;
    if (month < 10) {
        month = '0' + month;
    }
    var date = _date.getDate();
    if (date < 10) {
        date = '0' + date;
    }
    var hours = _date.getHours();
    if (hours < 10) {
        hours = '0' + hours;
    }
    var minutes = _date.getMinutes();
    if (minutes < 10) {
        minutes = '0' + minutes;
    }
    var seconds = _date.getSeconds();
    if (seconds < 10) {
        seconds = '0' + seconds;
    }
    var print_date = _date.getFullYear() + "/" + month + "/" + date + ' ' + hours + ':' + minutes + ':' + seconds;
            var printdeliverorder = JSON.parse(localStorage.getItem('printdeliverorder'));
            for (var i = 0 in printdeliverorder) {
                printdeliverorder[i].scl_receivername = setCharAt(printdeliverorder[i].scl_receivername, 1, '*');
                printdeliverorder[i].scl_receivermobile = printdeliverorder[i].scl_receivermobile.substr(0, 4) + '****' + printdeliverorder[i].scl_receivermobile.substr(8, 10);
                printdeliverorder[i].scl_receiverphone = printdeliverorder[i].scl_receiverphone.substr(0, printdeliverorder[i].scl_receiverphone.length - 4) + '****';
                $('.print-block').append('<div class="order-form">' +
                    '<div class="order-form-head"><span>出貨單</span></div>' +
                    '<div class="order-form-body">' +
                    '<div class="row">' +
                    '<div class="col-65">' +
                    '</div>' +
                    '<div class="col-35">' +
                    '<div class="scg_id">銷帳單號： ' + printdeliverorder[i].scg_id.substr(0,17) + '</div>' +
                    '<div class="print-date">列印時間： ' + print_date + '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="row">' +
                    '<div class="col-65">' +
                    '<div class="scl_receivername">收件人姓名： ' + printdeliverorder[i].scl_receivername + '</div>' +
                    '<div class="scl_receivermobile">收件人手機： ' + printdeliverorder[i].scl_receivermobile + '</div>' +
                    '<div class="scl_receiverphone">收件人市話： ' + printdeliverorder[i].scl_receiverphone + '</div>' +
                    '<div class="scl_receiveaddress">收件人地址： ' + printdeliverorder[i].scl_city + printdeliverorder[i].scl_district + printdeliverorder[i].scl_receiveaddress + '</div>' +
                    '</div>' +
                    '<div class="col-35">' +
                    '<div class="qr-code'+i+'"></div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="commodity-details">' +
                    '<table>' +
                    '<thead>' +
                    '<tr>' +
                    '<th class="numeric-cell min60">項次</th>' +
                    '<th class="label-cell min80">產品編號</th>' +
                    '<th class="label-cell min80">產品名稱</th>' +
                    '<th class="numeric-cell min60">數量</th>' +
                    '<th class="numeric-cell min100">產品單價</th>' +
                    '<th class="numeric-cell min120">金額</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>' +
                    '<tr>' +
                    '<th class="numeric-cell">1</th>' +
                    '<th class="label-cell">' + printdeliverorder[i].scm_id.substr(0,17) + '</th>' +
                    '<th class="label-cell">' + printdeliverorder[i].scm_title + '</th>' +
                    '<th class="numeric-cell">' + printdeliverorder[i].scg_buyamount + '</th>' +
                    '<th class="numeric-cell">' + printdeliverorder[i].scm_price + '</th>' +
                    '<th class="numeric-cell">' + parseInt(printdeliverorder[i].scm_price) * parseInt(printdeliverorder[i].scg_buyamount) + '</th>' +
                    '</tr>' +
                    '</tbody>' +
                    '<tfoot>' +
                    '<tr>' +
                    '<th class="label-cell" colspan="2">合計</th>' +
                    '<th class="numeric-cell min100" colspan="2">總數：<span>' + printdeliverorder[i].scg_buyamount + '</span></th>' +
                    '<th class="numeric-cell min120" colspan="2">總額：<span>' + parseInt(printdeliverorder[i].scm_price) * parseInt(printdeliverorder[i].scg_buyamount) + '</span></th>' +
                    '</tr>' +
                    '</tfoot>' +
                    '</table>' +
                    '</div>' +
                    '</div>' +
                    '<div class="order-form-footer">' +
                    '<div class="row" style="border-top: 2px dashed #000; padding-top: 2%;">' +
                    '<div class="col-50">' +
                    '<div>退/換貨須知</div>' +
                    '<div>1.依照消費者保護法規定，消費者享有商品到貨7天鑑賞期（包含例假日）之權益，七日鑑賞期屬『考慮期』並非『試用期』，若商品如經拆  封、使用、以致缺乏完整性即失去再販售價值時，恕無法退貨。</div>' +
                    '<div>2.換貨僅限瑕疵商品及尺寸錯誤商品，限定同款同色換貨,每張訂單僅提供乙次免運費退/換貨服務，若需再退換貨請自行承擔商品運送費用。</div>' +
                    '<div>3.請注意，退/換貨的商品必須為全新狀態且包裝完整商品（保持退貨商品、發票、配件、吊牌、內外包裝、贈品等之完整性）。</div>' +
                    '</div>' +
                    '<div class="col-5">' +
                    '</div>' +
                    '<div class="col-45">' +
                    '<div class="shop-data-block">' +
                    '<div class="shop_name">' + printdeliverorder[i].sd_shopname + '</div>' +
                    '<div class="shop_tel">TEL： ' + printdeliverorder[i].sd_contact_tel + '</div>' +
                    '<div class="shop_address">地址： 台北市中山區八德路二段260號2樓</div>' +
                    '</div>' +
                    '<div class="company-block">' +
                    '<img src="app/image/iscar_logo_orange.png">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="page-num">' + (parseInt(i) + 1) + '/' + printdeliverorder.length + '</div>' +
                    '</div>' +

                    '</div>');
                $('.qr-code'+i).qrcode({
                    "render": "div",
                    "size": 125,
                    "background": "#fff",
                    "text": '{"scl_id":"' + printdeliverorder[i].scl_id + '"}'
                });
            }
            window.print();
        }
        </script>
    </body>
</html>