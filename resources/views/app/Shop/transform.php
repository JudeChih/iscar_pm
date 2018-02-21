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
            /*background-image: url(../app/image/carbon_bg.jpg);
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            background-size: cover;*/
        }
    </style>

</head>

<body>

</body>

<script type="text/javascript" src="../app/js/config.js"></script>
<script type="text/javascript" src="../app/js/string.js"></script>
<script src="../app/libs/js-cookie/src/js.cookie.js"></script>

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
        if (aryPara.length > 1) {
            if (aryPara[1] === 'Welcome') {
                var para = JSON.parse(decodeURIComponent(aryPara[0]));
                if (Cookies.get(_main) !== undefined) {
                    //get
                    var mainSg = JSON.parse(Cookies.get(_main));
                } else {
                    var mainSg = {};
                }
                mainSg.murId = para.murId;
                /*Cookies.set(_main, JSON.stringify(mainSg), {
                    domain: 'iscarmg.com'
                });*/
                localStorage.setItem('main', JSON.stringify(mainSg));
                window.location = "http://" + stringObj.WEB_URL + "/pm"; //轉址路徑
            } else if (aryPara[2] === 'shop-item') {
                //Cookies.set(_main,decodeURIComponent(aryPara[0]), { domain: 'iscarmg.com' });
                localStorage.setItem('main', decodeURIComponent(aryPara[0]));
                window.location = "http://" + stringObj.WEB_URL + "/pm/shopcoupon-info?scm_id=" + aryPara[1] + "&sd_id=" + aryPara[3]; //轉址路徑
            } else {
                //Cookies.set(_main,decodeURIComponent(aryPara[0]), { domain: 'iscarmg.com' });
                localStorage.setItem('main', decodeURIComponent(aryPara[0]));
                window.location = "http://" + stringObj.WEB_URL + "/pm/branch-info?sd_id=" + aryPara[1]; //轉址路徑
            }
        } else {
            /*var para = JSON.parse(decodeURIComponent(aryPara[0]));
            var mainSg = JSON.parse(localStorage.getItem(_main)) || {};
            mainSg.murId = para.murId;
            mainSg.sat = para.sat;
            if (para.sat) {
                Cookies.set('sat', para.sat);
                Cookies.set('shoplist', 0);
            } else {
                Cookies.remove('sat');
                Cookies.set('shoplist', 0);
            }*/
            // localStorage.setItem(_main, JSON.stringify(mainSg));
            Cookies.set('shoplist', 0);
            Cookies.set(_main,decodeURIComponent(aryPara[0]), { domain: 'iscarmg.com' });
            localStorage.setItem('main', decodeURIComponent(aryPara[0]));
            window.location = "http://" + stringObj.WEB_URL + "/pm"; //轉址路徑
        }

    }
</script>

</html>