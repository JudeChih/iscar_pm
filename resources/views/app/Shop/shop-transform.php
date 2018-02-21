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
            background-image: url(app/image/carbon_bg.jpg);
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            background-size: cover;
        }
    </style>

</head>

<body>

</body>

<script type="text/javascript" src="app/js/string.js"></script>

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

        document.cookie = 'sat = '+ aryPara[0];
        window.location = "http://" + stringObj.WEB_URL + "/Shop";

    }
</script>

</html>