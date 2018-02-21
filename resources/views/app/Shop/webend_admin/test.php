<!doctype html>
<html>

<head>
    <title>Proper Title</title>
    <style>
        #selectedFiles img {
            max-width: 200px;
            max-height: 200px;
            float: left;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <!--<form enctype="multipart/form-data" action="doAction.php" id="myForm" method="post">
        Files:
        <input type="file" id="files" name="files" multiple accept="image/*">
        <br/>
        <div id="selectedFiles"></div>
        <input type="submit">
    </form>-->


    <!--<form method="post" id="myForm" action="doAction.php" enctype="multipart/form-data">
        檔案名稱:
        <input type="file" name="file" id="file" accept="image/*"/>
        <br />
        <input type="submit" name="submit" value="上傳檔案" />
    </form>-->
    
    <iframe src="upload.php"></iframe>


</body>

</html>


<?php

// 設置基本FTP連線 ftp_connect(host,port,timeout);
$conn_id = ftp_connect('123.51.218.177');

//登入 FTP, 帳號是 USERNAME, 密碼是 PASSWORD
$login_result = ftp_login($conn_id, 'iscar_app', '1qaz@WSX#EDC');

// 切換成被動模式(true) turn passive mode on
ftp_pasv($conn_id, true);

// 切換目錄
ftp_chdir($conn_id, '/iscarapp/shopdata/temporary/');

// 顯示當前目錄 current directory
echo '當前目錄:' . ftp_pwd($conn_id); // /public_html
echo '<br>';


$file = 'test.jpg'; //上傳的檔案名稱

/*
 *  FTP 上傳檔案(非同步上傳asynchronously)
 */

$fp = fopen($_FILES['file']['tmp_name'], 'r');

// ftp_nb_fput() 異步上傳文件(asynchronously)
// Initate the upload
/*$ret = ftp_nb_fput($conn_id, $file, $fp, FTP_ASCII);
while ($ret == FTP_MOREDATA) {
   // Do whatever you want
   echo ".";
   // Continue upload...
   $ret = ftp_nb_continue($conn_id);
}
if ($ret != FTP_FINISHED) {
   echo "上傳過程中發生錯誤";
   exit(1);
}

fclose($fp); // 關閉檔案*/

/*
 *  FTP 上傳檔案(同步上傳)
 */
// ftp_fput() 上傳文件
if (ftp_fput($conn_id, $file, $fp, FTP_BINARY)) {//FTP_ASCII
    echo "成功上傳 $file\n";
echo '<br>';
} else {
    echo "上傳檔案 $file 失敗\n";
	echo '<br>';
}

fclose($fp); // 關閉檔案



echo '檔案名稱: ' . $_FILES['file']["name"]."<br/>";
echo '檔案類型: ' . $_FILES['file']["type"]."<br/>";
echo '檔案大小: ' . ($_FILES['file']['size'] / 1024)." Kb<br />";
echo '暫存名稱: ' . $_FILES['file']['tmp_name'];

//move_uploaded_file($_FILES["file"]["tmp_name"],"http://app.iscarmg.com/image/".$_FILES["file"]["name"]);





?>

<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

<script>
    var selDiv = "";
    var storedFiles = [];

    $(document).ready(function () {
        /*$("#files").on("change", handleFileSelect);

        selDiv = $("#selectedFiles");
        $("#myForm").on("submit", handleForm);

        $("body").on("click", ".selFile", removeFile);*/
        
    });

    function handleFileSelect(e) {
        var files = e.target.files;
        var filesArr = Array.prototype.slice.call(files);
        filesArr.forEach(function (f) {

            if (!f.type.match("image.*")) {
                return;
            }
            storedFiles.push(f);

            var reader = new FileReader();
            reader.onload = function (e) {
                var html = "<div><img src=\"" + e.target.result + "\" data-file='" + f.name + "' class='selFile' title='Click to remove'>" + f.name + "<br clear=\"left\"/></div>";
                selDiv.append(html);
                console.log(e.target.result);
            }
            reader.readAsDataURL(f);
        });

    }

    function handleForm(e) {
        e.preventDefault();
        var data = new FormData();

        for (var i = 0, len = storedFiles.length; i < len; i++) {
            data.append('files', storedFiles[i]);
            console.log(storedFiles[i]);
        }

        /*var xhr = new XMLHttpRequest();
        xhr.open('POST', '../../../../image/abc.jpg', true);

        xhr.onload = function (e) {
            if (this.status == 200) {
                console.log(e.currentTarget.responseText);
                //alert(e.currentTarget.responseText + ' items uploaded.');
            }
        }

        xhr.send(data);*/

        //var file_path = app.activeDocument.fullName
        //var file = new File("/d/project/test_file.psd");



        /*var ftp = new FtpConnection("ftp://192.168.30.102/home/iscar/public_html/app/isCar/image");
        ftp.login("root", "2wsx0okm");

        ftp.cd("project")
        ftp.put(data, "test.jpg");

        ftp.close();
        data.close();*/


    }


    function removeFile(e) {
        var file = $(this).data("file");
        for (var i = 0; i < storedFiles.length; i++) {
            if (storedFiles[i].name === file) {
                storedFiles.splice(i, 1);
                break;
            }
        }
        $(this).parent().remove();
    }
</script>-->