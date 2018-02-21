<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">

    <title>isCar就是行</title>

    <style>
        body {
            height: 100%;
            width: 100%;
            background: #151515;
            /*background-image: url(app/image/carbon_bg.jpg);
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            background-size: cover;*/
        }
    </style>

</head>

<body>

</body>

<script type="text/javascript" src="app/js/config.js"></script>
<script type="text/javascript" src="app/js/string.js"></script>
<script src="app/libs/js-cookie/src/js.cookie.js"></script>

<script>
    var strUrl = location.search;
    var getPara, ParaVal;
    var aryPara = [];

    if (strUrl.indexOf("?") != -1) {
        var getSearch = strUrl.split("?");
        getPara = getSearch[1].split("&");
        for (i = 0; i < getPara.length; i++) {
            ParaVal = getPara[i].split("=");
            aryPara.push(ParaVal[1]);
        }

        // var mainSg = JSON.parse(localStorage.getItem(_main)) || {};
        // mainSg.sat = aryPara[0];
        // localStorage.setItem(_main, JSON.stringify(mainSg));
        if (Cookies.get(_main) !== undefined) {
            //get
            var main = JSON.parse(Cookies.get(_main));
        } else {
            var main ={};
        }
        main.sat = aryPara[0];
        Cookies.set(_main,JSON.stringify(main), { domain: 'iscarmg.com' });
        localStorage.setItem('main', JSON.stringify(main));
        window.location = "http://" + stringObj.WEB_URL + "/Shop/webend_admin";//模組主頁路徑

    }
</script>

</html>