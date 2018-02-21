var _tip;
var stringObj = {

    API_IP: server_type + _region + '-pm.iscarmg.com', //'ga.iscarmg.com',
    WEB_URL: server_type + _region + '-pm.iscarmg.com', //tw.shop.stage.iscarmg.com
    APP_URL: server_type + _region + '-app.iscarmg.com',
    MEMBER_URL: server_type + _region + '-member.iscarmg.com',
    BANK_URL: server_type + _region + '-bank.iscarmg.com',
    IMAGE_URL: server_type + _region + '-media.iscarmg.com',
    SUNTECH_URL: 'http://test.esafe.com.tw/Service/Etopm.aspx',
    FTP_HOST: server_type + _region + '-upload.iscarmg.com', //'13.114.203.132'
    BANK_PASS: 'password',
    call_update_module_id: '1',
    PORT: '',
    return_header: function(msg) {
        switch (msg) {
            case '000000002':
                _tip = '該條碼機帳號已被停用，請聯絡註冊碼提供者重新申請';
                break;
            case '000000004':
                _tip = '該條碼機帳號未放行，請聯絡註冊碼提供者進行放行';
                break;
            case '000000005':
                _tip = '註冊程序有誤，請聯絡iscar進行協助';
                break;

            case '999999982':
                _tip = '查無伺服端功能模組連線憑證記錄';
                break;
            case '999999983':
                _tip = '伺服端功能模組連線憑證逾期失效';
                break;
            case '999999984':
                _tip = '伺服端功能模組連線憑證簽章有誤';
                break;
            case '999999985':
                _tip = '伺服端功能模組連線憑證無法解譯';
                break;

            case '999999990':
                _tip = '條碼機所屬分店不符合記錄，請聯絡管理者進行確認';
                break;
            case '999999991':
                _tip = '條碼機編號不符合記錄，請重新登入APP取得正確編號';
                break;
            case '999999992':
                if (window.location.href.match('webend_admin')) {

                    myApp.modal({
                        title: stringObj.text.warn,
                        text: '無效的伺服端連線憑證，請重新登入取用',
                        buttons: [{
                            text: stringObj.text.confirm,
                            onClick: function() {
                                indexObj._dataStorage(indexObj._storage.main, null);
                                webview.loginPage();
                            }
                        }]
                    });

                } else {

                    myApp.modal({
                        title: stringObj.text.warn,
                        text: '無效的伺服端連線憑證，請重新登入取用',
                        buttons: [{
                            text: stringObj.text.cancel,
                            onClick: function() {
                                indexObj._userLogout();
                            }
                        }, {
                            text: stringObj.text.reLogin,
                            onClick: function() {
                                indexObj._userLogout();
                                webview.loginPage();
                            }
                        }]
                    });

                }

                break;
            case '999999993':
                myApp.modal({
                    title: stringObj.text.warn,
                    text: '伺服端連線憑證逾期，請重新登入取用',
                    buttons: [{
                        text: stringObj.text.cancel,
                        onClick: function() {
                            indexObj._userLogout();
                        }
                    }, {
                        text: stringObj.text.reLogin,
                        onClick: function() {
                            indexObj._userLogout();
                            webview.loginPage();
                        }
                    }]
                });
                break;
            case '999999994':
                _tip = '服務呼叫者身份無法驗證，請重新安裝APP';
                break;
            case '999999995':
                _tip = '輸入之json內容無法解析，請重新呼叫';
                break;
            case '999999996':
                myApp.modal({
                    title: stringObj.text.warn,
                    text: '查無使用者記錄，請重新登錄',
                    buttons: [{
                        text: stringObj.text.cancel,
                        onClick: function() {
                            indexObj._userLogout();
                        }
                    }, {
                        text: stringObj.text.reLogin,
                        onClick: function() {
                            indexObj._userLogout();
                            webview.loginPage();
                        }
                    }]
                });
                break;
            case '999999997':
                _tip = '記錄已存在，無需再次更新';
                break;
            case '999999998':
                _tip = '尚未更新完成，請接續更新';
                break;
            case '999999999':
                _tip = '未知錯誤失敗，請稍候再試';
                break;
            case '010101001':
                myApp.modal({
                    title: stringObj.text.warn,
                    text: '呼叫者登入訊息驗證失敗，請重新登入',
                    buttons: [{
                        text: stringObj.text.cancel,
                        onClick: function() {
                            indexObj._userLogout();
                        }
                    }, {
                        text: stringObj.text.reLogin,
                        onClick: function() {
                            indexObj._userLogout();
                            webview.loginPage();
                        }
                    }]
                });
                break;
            case '010101002':
                _tip = '行動裝置無法辨識，請重新安裝APP';
                break;
            case '010101003':
                _tip = '行動裝置記錄無效，請重新安裝APP';
                break;
            case '010101004':
                _tip = 'APP類型辨識錯誤，請更新APP版本';
                break;

            case '010102000':
                _tip = '綁定完成，後續請改用FB登入，感謝您的使用';
                break;
            case '010102001':
                _tip = '查無使用者帳號記錄，請確認是否已完成註冊';
                break;
            case '010102002':
                _tip = '使用者帳號記錄大於一筆，請聯絡isCar進行處理';
                break;
            case '010102003':
                _tip = '該帳號非單機用戶，無法進行綁定';
                break;
            case '010102004':
                _tip = '本帳號已綁定完成，無需重新綁定，請改用FB登入';
                break;
            case '010102005':
                _tip = '該FB帳號使用，請選用其他帳號進行綁定';
                break;

            case '010103001':
                _tip = '公鑰格式有誤，請重新呼叫';
                break;
            case '010104000':
                _tip = '金鑰驗證成功更新作業完成';
                break;
            case '010107002':
                _tip = '此更新操作已重覆執行，請先進行同步作業';
                break;
            case '010107003':
                _tip = '書籤更新物件不存在，請確認後再發送';
                break;
            case '010107004':
                _tip = '書籤更新操作無效，請確認後再發送';
                break;
            case '010301001':
                _tip = '無更多商品，請參考瀏覽其他類別商品';
                break;
            case '010301002':
                _tip = '該選單編號無商品項目，請重新選擇';
                break;
            case '010301003':
                _tip = '該選單編號無效，請重新確認';
                break;
            case '010302001':
                _tip = '無法查看，請先換購瀏覽權限';
                break;
            case '010302002':
                _tip = '查無商品項目，請確認後重發';
                break;
            case '010302003':
                _tip = '該活動已截止，請選用其他活動券';
                break;
            case '010302004':
                myApp.modal({
                    title: stringObj.text.warn,
                    text: '該活動券已領畢，請選用其他活動券',
                    buttons: [{
                        text: stringObj.text.confirm,
                        bold: true,
                        onClick: function() {
                            mainView.router.load({
                                url: 'coupon-main.html',
                                reload: true
                            });
                        }
                    }]
                });
                break;
            case '010302005':
                _tip = '該活動尚未開始，請選用其他活動券';
                break;
            case '010303001':
                _tip = '查無系統記錄，請重新安裝APP';
                break;
            case '010303002':
                _tip = '該活動券已索取完畢，無法再索取';
                break;
            case '010303003':
                _tip = '該活動已截止，請選用其他活動券';
                break;
            case '010303004':
                _tip = '代幣不足，無法索取';
                break;
            case '010303005':
                _tip = '該活動必須指定使用開市，請重新索取';
                break;
            case '010303006':
                _tip = '該活動一人限索取一次，無法重覆索取';
                break;
            case '010303007':
                _tip = '預約之時段已額滿，請重新選擇';
                break;
            case '010303008':
                _tip = '未設定預約時段，請重新選擇';
                break;
            case '010303009':
                _tip = '無法索取，請先換購索取權限';
                break;
            case '010303010':
                _tip = '活動使用門市指定錯誤，請重新索取';
                break;
            case '010303011':
                _tip = '該活動券未用畢前不可再次索取';
                break;
            case '010304000':
                _tip = '棄用完成，歡迎選用其他活動券';
                break;
            case '010304001':
                _tip = '查無記錄，請重新確認';
                break;
            case '010304002':
                _tip = '該券已用畢，請重啟APP以更新活動券持有記錄';
                break;
            case '010304003':
                _tip = '該券已棄用，請重啟APP以更新活動券持有記錄';
                break;
            case '010305001':
                _tip = '查無記錄，請確認是否已使用';
                break;
            case '010305002':
                _tip = '你已對該活動券評分，請勿重新評分';
                break;
            case '010306001':
                _tip = '查無上傳記錄 請重新傳送';
                break;
            case '010306002':
                _tip = '上傳記錄大於100組，重新傳送';
                break;
            case '010307000':
                _tip = '預約取消完成';
                break;
            case '010307001':
                _tip = '查無索取記錄，請重新發送';
                break;
            case '010307002':
                _tip = '查無預約記錄，請重新發送';
                break;
            case '010307003':
                _tip = '該索取記錄，已取消預約';
                break;
            case '010307004':
                _tip = '該預約記錄已逾期，無法取消';
                break;
            case '010308001':
                _tip = '查無該筆商品資料，請重新發送';
                break;
            case '010308002':
                _tip = '傳入值有誤，請重新發送';
                break;
            case '010309001':
                _tip = '無法索取，請先換購索取權限';
                break;
            case '010309002':
                _tip = '該活動需設定使用門市';
                break;
            case '010309003':
                _tip = '該活動需設定預約時間與門市';
                break;
            case '010402001':
                _tip = '查詢資料錯誤，請重新發送';
                break;
            case '010501000':
                _tip = '交易記錄驗證成功，代幣已存入';
                break;
            case '010501001':
                _tip = '交易記錄無法驗證，請向平台商確認交易完成度';
                break;
            case '010501002':
                _tip = '電子發票時間異常，請重新發送';
                break;
            case '010501003':
                _tip = '交易商品不符合電子發票內容，請確認後再發送';
                break;
            case '010502000':
                _tip = '權限購買成功';
                break;
            case '010502001':
                _tip = '代幣餘額不足，請先購入代幣';
                break;
            case '010502002':
                _tip = '紅利餘額不足，歡迎使用者更積極參與iscar互動活動，贏得更多紅利';
                break;
            case '010602001':
                _tip = '查無該項活動，請重新呼叫';
                break;
            case '010602002':
                _tip = '該項活動已取消，請參閱其他活動';
                break;
            case '010602003':
                _tip = '該項活動已逾期，請參閱其他活動';
                break;
            case '010603000':
                _tip = '活動預約完成，請按時前往參加';
                break;
            case '010603001':
                _tip = '查無該項活動記錄，請重新選擇預約項目';
                break;
            case '010603002':
                _tip = '該項活動無法預約，請重新選擇預約項目';
                break;
            case '010603003':
                _tip = '該項活動已預約額滿，請選擇其他項目';
                break;
            case '010603004':
                _tip = '使用者已預約該項活動，請勿重複預約';
                break;
            case '010605000':
                _tip = '該問卷可用，歡迎填寫問卷';
                break;
            case '010605001':
                _tip = '查無該項問卷記錄，請重新選擇問卷';
                break;
            case '010605002':
                _tip = '該問卷已停用，請重新選擇問卷';
                break;
            case '010605003':
                _tip = '該問卷已發放完畢，請重新選擇問卷';
                break;
            case '010605004':
                _tip = '該問卷有效期限已過，請重新選擇問卷';
                break;
            case '010605005':
                _tip = '您已填過該問卷，請選擇其他問卷';
                break;
            case '010606000':
                _tip = '感謝您的填寫，使至活動會場掃描Qrcode對換獎品';
                break;
            case '010606001':
                _tip = '該問卷回收額滿，請選用其他問卷';
                break;
            case '010606002':
                _tip = '該問卷已停用，請重新選擇問卷';
                break;
            case '010606003':
                _tip = '該問卷有效期限已過，請重新選擇問卷';
                break;
            case '010606004':
                _tip = '您已填過該問卷，請選擇其他問卷';
                break;
            case '010701001':
                _tip = '記錄寫入失敗，請稍後重新發送';
                break;
            case '010702001':
                _tip = '查無分享項目編號，請重新發送';
                break;
            case '010702002':
                _tip = '該項目已過有效期限，無法分享，請重新選擇其他項目';
                break;
            case '010702003':
                _tip = '該項目已停用，無法分享，請重新選擇其他項目';
                break;
            case '010703001':
                _tip = '查無該評論項目，請重新操作';
                break;
            case '010801001':
                _tip = '無此選單編號，請重新輸入';
                break;
            case '010801002':
                _tip = '無此新聞編號，請重新輸入';
                break;
            case '010801003':
                _tip = '選單編號輸入格式錯誤，請重新輸入';
                break;
            case '010801004':
                _tip = '起始新聞編號輸入格式錯誤，請重新輸入';
                break;
            case '010801005':
                _tip = '索取數輸入格式錯誤，請重新輸入';
                break;
            case '010801006':
                _tip = '傳入值格式內容格式有誤，請重新輸入';
                break;
            case '010802001':
                _tip = '無此新聞ID，請重新輸入';
                break;
            case '010802002':
                _tip = '新聞ID輸入格式錯誤，請重新輸入';
                break;
            case '010802003':
                _tip = '呼叫內容有誤，無法辨識';
                break;
            case '010803001':
                _tip = '無此新聞ID，請重新輸入';
                break;
            case '010803002':
                _tip = '新聞ID輸入格式錯誤，請重新輸入';
                break;
            case '010803003':
                _tip = '呼叫內容有誤，無法辨識';
                break;
            case '010803004':
                _tip = '該新聞項目，尚無評論記錄';
                break;

            case '020203002':
                _tip = '行動裝置無法辨識，請重新安裝APP';
                break;
            case '020203003':
                _tip = '行動裝置記錄無效，請重新安裝APP';
                break;
            case '020203004':
                _tip = '該分店已停用，請與註冊碼提供者確認相關訊息';
                break;
            case '020203005':
                _tip = '該廠商已停用，請與註冊碼提供者確認相關訊息';
                break;
            case '020203006':
                _tip = '使用者註冊記錄大於一筆，無法辨識身份，請聯絡系統管理者進行確認';
                break;
            case '020203007':
                _tip = '無此帳號，請重新登入';
                break;
            case '020203008':
                _tip = '密碼錯誤超過五次，登入鎖定，請聯絡通路管理者重置';
                break;
            case '020203009':
                _tip = '帳號已停用，請聯絡通路管理者確認';
                break;
            case '020203010':
                _tip = '密碼錯誤，請重新登入';
                break;
            case '020203011':
                _tip = '帳號未放行，請聯絡管理者確認';
                break;

            case '020301002':
                _tip = '查無該券編號，請提醒消費者重新索取';
                break;
            case '020301003':
                _tip = '該券活動已逾期，不可再使用';
                break;
            case '020301004':
                _tip = '該券於索取時已指定使用門市，請提醒使用者至指定門市使用';
                break;
            case '020301005':
                _tip = '該券已使用完畢，請提醒消費者進行狀態更新';
                break;
            case '020301006':
                _tip = '該券已放棄使用，請提醒消費者進行狀態更新';
                break;
            case '020301007':
                _tip = '該券預約使用時間不符，請確認是否使用';
                break;
            case '020301008':
                _tip = '該活動券預約使用分店不符，請確認是否使用';
                break;
            case '020301009':
                _tip = '該券為預約客戶，可正常使用';
                break;
            case '020301010':
                _tip = '該券取用記錄有誤，請告知客戶重新取用活動券';
                break;

            case '020403001':
                _tip = '尚無預約記錄';
                break;


            case '010303012':
                _tip = '紅利點數不足，無法取用';
                break;

            case '010901001':
                _tip = '無此商家類別編號，請重新輸入';
                break;
            case '010901002':
                _tip = '查無排隊記錄';
                break;
            case '010901003':
                _tip = '選單編號輸入格式錯誤，請重新輸入';
                break;
            case '010901004':
                _tip = '起始商家編號輸入格式錯誤，請重新輸入';
                break;
            case '010901005':
                _tip = '索取數輸入格式錯誤，請重新輸入';
                break;
            case '010901006':
                _tip = '傳入值格式內容格式有誤，請重新輸入';
                break;
            case '010901007':
                _tip = '輸入之商家名稱，查詢無結果';
                break;
            case '010901008':
                _tip = '輸入之地區，查詢無結果';
                break;

            case '010902001':
                _tip = '該商家記錄未有有效管理者，請確認管理效期是否仍有效';
                break;
            case '010902002':
                _tip = '會員非本商家管理者，請確認後再試';
                break;
            case '010902003':
                _tip = '會員之管理權限已失效，請確認後再試';
                break;
            case '010902004':
                _tip = '商家地址無法轉換有效經緯度坐標，請確認後重新輸入';
                break;


            case '020303001':
                _tip = '記錄完成，客戶服務程序開始';
                break;
            case '020303002':
                _tip = '錯誤的作業內容，請重新選擇';
                break;
            case '020303003':
                _tip = '該券服務內容已執行完畢，請勿重複作業';
                break;
            case '020303004':
                _tip = '該券服務內容已執行中，請注意勿重複作業';
                break;
            case '020303005':
                _tip = '該券設有攝影記錄需求，核準前請先行攝影記錄';
                break;
            case '020304001':
                _tip = '查無該券編號，請確認後重新發送';
                break;
            case '020304002':
                _tip = '該券非使用完畢狀態，無法新增相片';
                break;

            case '030101002':
                _tip = '使用者註冊記錄大於一筆，無法辨識身份，請聯絡系統管理者進行確認';
                break;
            case '030101003':
                _tip = '無此帳號，請重新登入';
                break;
            case '030101004':
                _tip = '密碼錯誤超過五次，登入鎖定，請聯絡通路管理者重置密碼';
                break;
            case '030101005':
                _tip = '帳號已停用，請聯絡通路管理者確認';
                break;
                /*case '030101006':
                    _tip = '密碼錯誤，請重新登入';
                    break;*/
            case '030101007':
                _tip = '帳號未放行，請聯絡管理者確認';
                break;
            case '030101008':
                _tip = '行動裝置無法辨識，請重新安裝APP';
                break;
            case '030101009':
                _tip = '行動裝置記錄無效，請重新安裝APP';
                break;

            case '030102001':
                _tip = '商家代號有誤，請確認後重發';
                break;
            case '030102002':
                _tip = '商家代號對應記錄大於一筆，請聯絡系統管理員進行處理';
                break;
            case '030102003':
                _tip = '會員代號有誤，請確認後重發';
                break;
            case '030102004':
                _tip = '會員代號對應記錄大於一筆，請聯絡系統管理員進行處理';
                break;
            case '030102005':
                _tip = '儲值點消費項目有誤，請確認後重發';
                break;
            case '030102006':
                _tip = '儲值點消費項目對應記錄大於一筆，請聯絡系統管理員進行處理';
                break;


            case '010904001':
                _tip = '查無商品項目，請確認後重發';
                break;
            case '010904002':
                _tip = '該活動尚未開始，請選用其他活動券';
                break;
            case '010904003':
                _tip = '該活動已截止，請選用其他活動券';
                break;
            case '010904004':
                _tip = '該活動已停刊，請選用其他活動券或稍後再試';
                break;

            case '010905001':
                _tip = '為避免無效取用，請會員先完成FB登入綁定';
                break;
            case '010905002':
                _tip = '優惠券索取完成，請先預約後使用';
                break;
            case '010905003':
                _tip = '該券預約額滿，已排入候補，請隨時關注可預約時間';
                break;
            case '010905004':
                _tip = '該券設有索取數限制，無法再索取';
                break;

            case '010906001':
                _tip = '查無索取記錄，請重新索取優惠券';
                break;
            case '010906002':
                _tip = '所選預約時段已被預約，請重新選取';
                break;
            case '010906003':
                _tip = '原有預約時段即將到期，無法變更預約時間';
                break;

            case '010909001':
                _tip = '查無記錄，請重新確認';
                break;
            case '010909002':
                _tip = '所選預約時段已被預約，請重新選取';
                break;
            case '010909003':
                _tip = '該券已棄用或逾期，請重啟APP以更新活動券持有記錄';
                break;


            case '010907003':
                _tip = '為避免消費糾紛，仍有訂單未執行，無法更新活動內容，待訂單完成，即可修改內容。';
                break;
            case '010907004':
                _tip = '操作項目無法辨視，請重新發送';
                break;
            case '010907005':
                _tip = '欄位未填,無法作業';
                break;
            case '010907006':
                _tip = '該活動券非預約類型，無法更新預約時間';
                break;
            case '010907007':
                _tip = '超出活動最大有效日期，無法更新';
                break;
            case '010907008':
                _tip = '未啟用金流，無法使用該功能';
                break;

            case '010910001':
                _tip = '查無該券編號，請提醒消費者重新索取';
                break;
            case '010910002':
                _tip = '該券活動已逾期，不可再使用';
                break;
            case '010910004':
                _tip = '該券已使用完畢，請提醒消費者進行狀態更新';
                break;
            case '010910005':
                _tip = '該券已放棄使用，請提醒消費者進行狀態更新';
                break;
            case '010910007':
                _tip = '該券為預約客戶，可正常使用';
                break;
            case '010910008':
                _tip = '該券取用記錄有誤，請告知客戶重新取用活動券';
                break;
            case '010909009':
                _tip = '該券非貴司發行，請告知客戶前往正確商家使用';
                break;
            case '010910010':
                _tip = '該券尚未付款完成';
                break;
            case '010910011':
                _tip = '該劵已申請退款，請告知客戶重新索取';
                break;

            case '010911000':
                _tip = '服務完成，請提示客戶該券已用畢';
                break;
            case '010911001':
                _tip = '錯誤的作業內容，請重新選擇';
                break;

            case '011001001':
                _tip = '輸入之活動類別有誤，請確認後重發';
                break;
            case '011001002':
                _tip = '查無取用記錄，請確認後重發';
                break;
            case '011001003':
                _tip = '查無使用完畢記錄，請確認後重發';
                break;
            case '011002001':
                _tip = '您已完成該問卷，感謝你的填寫';
                break;


            case '010912001':
                _tip = '查無欲回覆項目，請更新留言記錄';
                break;
            case '010912002':
                _tip = '記錄有誤，無法留言回覆，請聯絡isCar人員進行處理';
                break;
            case '010912003':
                _tip = '該項目已回覆，無法再添加回覆，請改用修改功能';
                break;
            case '010912004':
                _tip = '該項目未有回覆，無法更新，請改用新增功能';
                break;

            case '010913001':
                _tip = '服務編號有誤，請確認後重發';
                break;

            case '010914001':
                _tip = '該商家已暫停排隊服務，請隨時關注該商家近況';
                break;
            case '010914002':
                _tip = '該服務項目已停止提供，請隨時關注該商家近況';
                break;
            case '010914003':
                _tip = '非服務時間，請於商家服務時間，點選排隊';
                break;
            case '010914004':
                _tip = '當前隊列人數已滿，請稍候再試';
                break;

            case '010915001':
                _tip = '已有記錄，請使用修改功能進行更新';
                break;
            case '010915002':
                _tip = '無啟用記錄，請使用新增功能先行新增';
                break;

            case '010916001':
                _tip = '憑證號碼無效，請重新輸入';
                break;
            case '010916002':
                _tip = '非貴司所發行之服務，請通知用戶前往正確商家使用';
                break;
            case '010916003':
                _tip = '用戶已棄用，由商家自行決定是否提供服務';
                break;
            case '010916004':
                _tip = '用戶未到號，請用戶稍候';
                break;
            case '010916005':
                _tip = '用戶已棄用，由商家自行決定是否提供服務';
                break;
            case '010916006':
                _tip = '用戶已服務完成，無法再次使用';
                break;
            case '010916007':
                _tip = '用戶已過號，由於商家自行決定是否提供服務';
                break;


            case '010917002':
                _tip = '正式服務時間尚未開始，停止自動叫號';
                break;
            case '010917003':
                _tip = '當前用戶為過號用戶，暫停呼叫次一號用戶';
                break;
            case '010917004':
                _tip = '暫無次一隊列用戶，請等候新用戶選用服務';
                break;

            case '010918001':
                _tip = '無效的排隊編號，請確認後重發';
                break;
            case '010918002':
                _tip = '非貴司所發行之服務，無法操作過號設置';
                break;
            case '010918003':
                _tip = '該用戶已服務完畢，無法設置過號';
                break;
            case '010918004':
                _tip = '非排隊狀態，無法設置過號';
                break;
            case '010918005':
                _tip = '叫號未達兩次以上，無法設置過號';
                break;
            case '010918006':
                _tip = '叫號後未達10分鐘以上，無法設置過號';
                break;

            case '010919001':
                _tip = '目前無排隊中用戶，請等候新用戶加入';
                break;
            case '010919002':
                _tip = '非貴司所發行之服務，無法操作叫號';
                break;
            case '010919003':
                _tip = '該用戶已服務完畢，無法設置叫號';
                break;
            case '010919004':
                _tip = '非排隊狀態，無法設置叫號';
                break;
            case '010919005':
                _tip = '該用戶已棄用，系統將自動呼叫次一服務號';
                break;
            case '010919006':
                _tip = '距前次叫號未達五分鐘，請稍候再叫號';
                break;

            case '010920001':
                _tip = '服務狀態已變更，無需重新操作，請更新狀態';
                break;
            case '010920002':
                _tip = '排隊服務已設置暫停，若需繼續叫號請先啟動服務';
                break;
            case '010920003':
                _tip = '今日排隊服務已終止，次日請先啟動服務';
                break;
            case '010920004':
                _tip = '今日排隊服務已終止，次日啟動服務後將自動叫號';
                break;

            case '010921001':
                _tip = '非過號隊列用戶無法進行呼叫';
                break;

            case '010922001':
                _tip = '查無記錄，請重新確認';
                break;
            case '010922002':
                _tip = '該記錄已用畢，請重啟APP以更新服務排隊狀態';
                break;
            case '010922003':
                _tip = '該記錄已棄用或失約，請重啟APP以更新活動券持有記錄';
                break;

            case '010923001':
                _tip = '已通知商家即將前往，請注意安全';
                break;
            case '010923002':
                _tip = '已通知商家無法前往，並設置為過號';
                break;
            case '010923003':
                _tip = '無效的操作，請確認後重發';
                break;

            case '011101001':
                _tip = '查詢條件有誤，請重新輸入';
                break;
            case '011101002':
                _tip = '無效的操作動作，請重新輸入';
                break;
            case '011101003':
                _tip = '新增時，主索引不可賦值';
                break;
            case '011101004':
                _tip = '無法辨視索引值';
                break;


            case '999999989':
                _tip = '無效的操作動作，請重新輸入';
                break;
            case '011105001':
                _tip = '查無約看記錄，請確認後重發';
                break;
            case '011106001':
                _tip = '敲定之約看時間，不符合買家提出之項目';
                break;
            case '011106002':
                _tip = '記錄編號查無資料，請確認後重發';
                break;


            case '011103001':
                _tip = '刊登項目有誤，無法完成作業，請確認後重發';
                break;
            case '011103002':
                _tip = '餘額不足，請先完成儲值動作';
                break;

            case '011104001':
                _tip = '查無該車輛，請確認後重發';
                break;
            case '011104002':
                _tip = '車輛記錄大於一筆，請聯絡isCar管理員進行處理';
                break;
            case '011104003':
                _tip = '約看詢問，發送失敗，請稍後再試';
                break;


            case '011202001':
                _tip = '用戶未加入車團，請先加入車團';
                break;


            case '011203001':
                _tip = '用戶已加入車團，無法重複加入不同車團';
                break;
            case '011203002':
                _tip = '查無對應車團資訊，請確認後重送';
                break;
            case '011203003':
                _tip = '所選車團已解散，無法加入';
                break;
            case '011203004':
                _tip = '所選車團為非公開車團，無法申請加入';
                break;
            case '011203005':
                _tip = '所選車團已滿額，無法申請加入';
                break;
            case '011203006':
                _tip = '用戶已申請加入該社團.記錄失效前,無法再申請';
                break;
            case '011203007':
                _tip = '申請發送失敗，請稍後再試';
                break;


            case '011204001':
                _tip = '邀請人所屬車團不符，請確認後重發';
                break;
            case '011204002':
                _tip = '查無會員資料，請確認後重發';
                break;
            case '011204003':
                _tip = '未完成FB綁定程序，無法加入車團';
                break;
            case '011204004':
                _tip = '邀請對象已加入其他社團，無法重複加入';
                break;
            case '011204005':
                _tip = '邀請失敗，請稍後再試';
                break;


            case '011205001':
                _tip = '用戶已有車團成員身份，無法成立車團';
                break;
            case '011205002':
                _tip = '用戶等級不足，無法成立車團';
                break;
            case '011205003':
                _tip = '用戶所選車團名稱重複，無法成立車團';
                break;

            case '011206001':
                _tip = '查無邀請記錄，請確認後重發';
                break;
            case '011206002':
                _tip = '被邀請人記錄不符，請確認後重發';
                break;
            case '011206003':
                _tip = '邀請記錄已逾期失效';
                break;
            case '011206004':
                _tip = '該邀請記錄已回覆，無法再使用';
                break;
            case '011206005':
                _tip = '邀請記錄拒絕作業已完成';
                break;

            case '999999988':
                _tip = '資料庫存取異常,請稍候再試';
                break;

            case '011207001':
                _tip = '查無車團參與記錄，請確認後重發';
                break;
            case '011207002':
                _tip = '車團成員等級不足，無法執行作業';
                break;

            case '011209001':
                _tip = '當前無可用之申請加入記錄，請稍候再試';
                break;

            case '011208001':
                _tip = '查無記錄，請確認後重發';
                break;
            case '011208002':
                _tip = '申請記錄之車團編號與管理者不同，無法操作';
                break;
            case '011208003':
                _tip = '該申請記錄已被審核，無法操作';
                break;
            case '011208004':
                _tip = '該申請記錄已失效，無法操作';
                break;
            case '011208005':
                _tip = '申請人已加入車團，無法操作';
                break;
            case '011208006':
                _tip = '車團人數已滿，無法加入新成員';
                break;

            case '011210001':
                _tip = '指派對象不存在，請確認後重發';
                break;
            case '011210002':
                _tip = '指派對象非車團成員，請確認後重發';
                break;
            case '011210003':
                _tip = '指派對象權級相同，無法重複指派';
                break;
            case '011210004':
                _tip = '無法經此方式指派團員為團長';
                break;
            case '011210005':
                _tip = '副團長人數已達車團等級限制，無法指派更多副團長';
                break;

            case '011202002':
                _tip = '加入記錄大於一筆，無法使用，請聯繫系統管理員進行處理';
                break;

            case '011211001':
                _tip = '查無對應表決記錄，請確認後重發';
                break;
            case '011211002':
                _tip = '查無應表決人對應記錄，請確認後重發';
                break;
            case '011211003':
                _tip = '非所屬車團表決案，無法執行作業';
                break;
            case '011211004':
                _tip = '您已表決完成，無法法執行作業';
                break;
            case '011211005':
                _tip = '應表決人數異常，管理員正在處理中，請稍候';
                break;
            case '011211006':
                _tip = '該表案已完成，無法執行作業';
                break;
            case '011211007':
                _tip = '該表決案可投票時間已過，無法執行作業，請等候系統結算';
                break;

            case '011212001':
                _tip = '無效的留言編號，請確認後重發';
                break;
            case '011212002':
                _tip = '該留言編號非所屬車團，無法進行操作';
                break;
            case '011212003':
                _tip = '留言狀態已符合設置選項，無法進行操作';
                break;
            case '011212004':
                _tip = '置頂設置已完成，請注意置頂目僅顯示最新三筆記錄';
                break;

            case '011213001':
                _tip = '未指派接任人選，無法退出車團';
                break;
            case '011213002':
                _tip = '車團尚有成員，請先指派繼任團長';
                break;
            case '011213003':
                _tip = '無法指派他團成員為繼任者，請確認後重發';
                break;
            case '011213004':
                _tip = '退團完成，無繼任人選車團同步解散';
                break;

            case '999999986':
                _tip = '記錄編號重複，系統修正中，請稍候再試';
                break;

            case '011214001':
                _tip = '未設置副團長，無法進行表決';
                break;

            case '011300001':
                _tip = '會員名下無此車輛記錄，是否已刪除';
                break;
            case '011300002':
                _tip = '該車輛記錄已被刪除';
                break;
            case '011300003':
                _tip = '查無所選車款對應記錄，請確認後重發';
                break;

            case '011301001':
                _tip = '車輛持有狀態有誤，請確認後重發';
                break;
            case '011301002':
                _tip = '用戶個資未授權使用，系統無法登載車輛資料';
                break;
            case '011301003':
                _tip = '上傳圖片數不符限制，請確認後重發';
                break;
            case '011301004':
                _tip = '封面圖片數不符限制，請確認後重發';
                break;

            case '011302001':
                _tip = '車輛狀態指定有誤，請重新輸入';
                break;

            case '040100001':
                _tip = '傳輸碼編號無效，請重新取用';
                break;
            case '000000008':
                _tip = '密碼無法辨識，請重新登入';
                break;

            case '040101001':
                _tip = '交易驗證碼不符，請重新輸入';
                break;

            case '999999972':
                _tip = '代幣餘額不足，請先購入代幣';
                break;
            case '999999973':
                _tip = '查無對應汽車特店服務費用項目，請重新輸入';
                break;
            case '999999975':
                _tip = '交易驗證碼不符，請重新輸入';
                break;
            case '999999976':
                _tip = '傳輸碼編號無效，請重新取用';
                break;
            case '999999977':
                _tip = '索引值比對不符，請重新輸入';
                break;

            case '010110002':
                _tip = '未知的錯誤';
                break;
            case '010110004':
                myApp.modal({
                    title: stringObj.text.warn,
                    text: '伺服端連線憑證逾期，請重新登入取用',
                    buttons: [{
                        text: stringObj.text.cancel,
                        onClick: function() {
                            indexObj._userLogout();
                        }
                    }, {
                        text: stringObj.text.reLogin,
                        onClick: function() {
                            indexObj._userLogout();
                            webview.loginPage();
                        }
                    }]
                });
                break;

            case '010170002':
                _tip = '該親屬已經存在';
                break;

            case '011405001':
                _tip = '店家資料不正確';
                break;

            case '011401001':
                _tip = '訂單格式不正確';
                break;
            case '011401002':
                _tip = '建立訂單失敗';
                break;

            case '010202001':
                _tip = '點數不足';
                break;

            case '010923006':
                _tip = '已有相同的快選訊息內容';
                break;
            case '010923007':
                _tip = '快選訊息內容不得超過200字';
                break;

            case '011501001':
                _tip = '此物流單號不存在';
                break;

            case '011502001':
                _tip = '此訂單尚未付款';
                break;
            case '011502002':
                _tip = '此訂單已使用完畢';
                break;
            case '011502003':
                _tip = '退款最大時限不得大於60日';
                break;


            case '011601001':
                _tip = '車輛詳情留言評論贈點，已達每日上限';
                break;
            case '011601002':
                _tip = '收藏特店為最愛贈點，已達每日上限';
                break;
            case '011601003':
                _tip = '特店排隊評論贈點，已達每日上限';
                break;
            case '011601004':
                _tip = '特店優惠劵評論贈點，已達每日上限';
                break;
            case '011601005':
                _tip = '購買特店實體商品贈點，已達每日上限';
                break;
            case '011601006':
                _tip = '祈福專區線上捐獻贈點，已達每日上限';
                break;
            case '011601007':
                _tip = '祈福專區點燈祈福贈點，已達每日上限';
                break;

            case '011701001':
                _tip = '資料輸入錯誤，請重新輸入';
                break;
            case '011701002':
                _tip = '該筆資料無法異動';
                break;

            case '011702001':
                _tip = '傳入日期區間錯誤，請重新輸入';
                break;
            case '011702002':
                _tip = '該時段中已有暫停預約記錄，請重新設定';
                break;
            case '011702003':
                _tip = '該店家無需要預約的服務';
                break;
            case '011702004':
                _tip = '該時段已有預約資料，無法暫停';
                break;

            case '010200001':
                _tip = '會員資料異常';
                break;
            case '010200002':
                _tip = '會員已設定用戶安全碼';
                break;
            case '010200003':
                _tip = '新舊用戶安全碼相同';
                break;
            case '010200004':
                _tip = '用戶安全碼更新失敗';
                break;
            case '000000010':
                _tip = '用戶安全碼錯誤';
                break;

            case '011103003':
                _tip = '地址未填寫完整';
                break;

            case '011801001':
                _tip = '傳入日期錯誤';
                break;
            case '011802001':
                _tip = '資料輸入錯誤，請重新輸入';
                break;

            case '010904005':
                _tip = '用戶並無追蹤此商家，請先訂閱後，使用特點消費。';
                break;
            case '010905006':
                _tip = '此商品需用特點消費，用戶特點不足。';
                break;


            case '010190002':
                _tip = '該會員已被介紹過。';
                break;



        }
    },
    text: {

        go_setting: '前往設定',
        default_setting: '使用預設座標',
        subscription: '訂閱',
        addNotYet: '尚未訂閱此店家，無法兌換，是否立即訂閱？',
        already_copy: '連結已複製',
        copy: '複製',
        appInit: '初始化...',
        appDataUpdate: '資料更新...',
        appUpdateTitle: '更新公告',
        appUpdateContext_And: '請至Google Play更新至最新版本',
        appUpdateContext_iOS: '請至App Store更新至最新版本',
        iosWebUpdateContext: '偵測到新版本，將進行更新動作',
        offlineTitle: '連線失敗',
        offlineContext: '未能與伺服器連線,請檢查連線狀態',
        version_check: '檢查版本更新...',
        warn: '提醒',
        login: '登入',
        loginFB: 'Facebook 登入',
        logining: '登入中',
        notLogin: '尚未登入',
        notLoginFB: '尚未登入Facebook，<br><span style="color:red;">此登入非綁定FB</span>',
        mailDeleteAll: '<span>還有<span style="color:red;">未讀</span>信件，是否全部移除</span>',
        newsDeleteAll: '<span>是否移除所有<span style="color:red;">新聞書籤</span></span>',
        couponDeleteAll: '<span>是否移除所有<span style="color:red;">活動書籤</span></span>',
        branchDeleteAll: '<span>是否移除所有<span style="color:red;">店家書籤</span></span>',
        shopcouponDeleteAll: '<span>是否移除所有<span style="color:red;">商家活動書籤</span></span>',
        deleteAll: '是否全部移除',
        cancel: '取消',
        confirm: '確認',
        error: '錯誤',
        reLogin: '請重新登入',
        fbCancelTitle: '登入取消',
        fbCancelContext: '請登入Facebook才能做之後的操作',
        fbFailTitle: '登入失敗',
        fbFailContext: '請重新登入Facebook才能做之後的操作',
        bugReportCheck: '<span>確定要送出?</span>',
        result: '結果',
        bugReportResult: '發送完成',
        coin: '禮點',
        coinProcessing: '禮點處理中',
        processing: '處理中',
        nowCoin: '目前禮點數為',
        shareFinish: '分享完成',
        fbLikeSuccess: '對此文章按讚成功',
        fbLikeAlready: '已經對此文章按讚',
        pleaseWait: '請稍後再試',
        noNetwork: '未能與伺服器連線,請稍後再試',
        textMessage: '文字訊息',
        systemMessage: '系統訊息',
        goToWebCheck: '是否前往該頁面',
        required: '必填',
        emailFormatError: '信箱格式錯誤',
        telFormatError: '不屬於電話格式',
        textFormatError: '請輸入中英文或數字',
        password: '27270625',
        inputPassword: '請輸入密碼',
        wrongPassword: '密碼錯誤',
        catName: '類別',
        createDate: '日期',
        createdBy: '作者',
        like: '讚',
        share: '分享',
        relatedNews: '相關新聞',
        comment_btn: '發佈評論',
        comment: '評論',
        fbComment: '是否同步發佈到臉書動態牆',
        comment_submit_btn: '發佈評論',
        commentCheck: '發佈評論為社群討論功能，需經FB帳號授權使用，請先綁定FB帳號',
        comment_finish: '評論完成',
        noNews: '暫無新聞資訊',
        choseShare: '請選擇分享工具',
        lineShare: 'LINE 分享',
        bookmarks: '書籤',
        releasing: '發佈中',
        marksRemoveCheck: '是否移除收藏',
        appShareContext: 'isCar！最新好車新聞，跟我一起挑戰下一個彎道吧！  http://tw.iscarmg.com/appshare.html',
        user_name: '姓名',
        input_name_placeholder: '請輸入名稱',
        input_Email_placeholder: '電子郵件',
        input_question: '問題描述',
        input_question_placeholder: '請填入問題內容',
        send: '發送訊息',
        send_to: '送出',
        reservationTime: '預約時間',
        choseBranch: '請先選擇指定店家',
        noBranch: '未選擇店家',
        welcomeTitle: '歡迎使用 isCar',
        welcomeContext: '註冊完成後可於會員選單中進行FB綁定',
        fbLoginContext: '此功能僅供註冊新用戶或已綁定FB之用戶登入使用，<br><span style="color:red;">未綁定FB用戶無法經此綁定</span>',
        shopLoginContext: '此功能僅供註冊完成之<span style="color:red;">汽車特店用戶</span>登入使用，普通用戶或未完成註冊汽車特店無法經此註冊',
        fbBindingTitle: '請注意!',
        fbBindingContext: '每個FB帳號僅可作<span style="color:red;">一次綁定</span>，完成後請改用Facebook登入isCar',
        binding: '綁定',
        binding_success: '綁定完成',
        registered: '註冊',
        member_registered: '會員註冊',
        register_login: '註冊/登入',
        registeredBtn1: '快速註冊',
        registeredBtn2: '手機登入',
        fbLoginBtn: 'Facebook 登入',
        aboutUs: '關於我們',
        privacy: '隱私權',
        selectArea: '選擇區域',
        noSelectArea: '縣市地區未填寫完整',
        edit: '修改',
        editFinish: '修改完成',
        special_offer: '折扣特價',
        prize: '獎品贈送',
        cash: '現金抵用',
        remainder_times: '剩餘次數',
        qr_title: '優惠條碼',
        qr_subtitle: '使用方式',
        qr_context: '憑本券至指定商家，出示此QR碼給商家掃描確認後，即可享有優惠!',
        branch: '通路商',
        address: '地址',
        input_address_placeholder: '請輸入地址',
        success_get: '索取成功',
        monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
        dayNames: ['日', '一', '二', '三', '四', '五', '六'],
        abandon_reason: ["沒有原因", "地址不便", "時間太短", "暫無需求"],
        service_abandon_reason: ["無需求", "距離太遠", "不符需求", "等候過久", "超過服務時間", "沒有原因"],
        coupon_explanation: '活動說明',
        available_times: '可用次數',
        coupon_end: '截止日',
        coupon_content: '活動內容',
        coupon_limit: '活動限制',
        coupon_img_path: 'http://' + server_type + _region + '-media.iscarmg.com/images/coupon/active_banner/',
        branch_img_path: 'http://' + server_type + _region + '-media.iscarmg.com/iscar_app/shopdata/',
        usedcar_img_path: 'http://' + server_type + _region + '-media.iscarmg.com/usedcar/',
        mycar_img_path: 'http://' + server_type + _region + '-media.iscarmg.com/iscar_app/membercars/',
        branch_img_temporary_path: 'http://' + server_type + _region + '-media.iscarmg.com/iscar_app/shopdata/temporary/',
        car_club_img_path: 'http://' + server_type + _region + '-media.iscarmg.com/car_club/',
        car_club_img_temporary_path: 'http://' + server_type + _region + '-media.iscarmg.com/car_club/temporary/',
        couponGetCheck: '為確保活動券發行有效性，取用前需完成身份驗證，請先執行FB綁定功能',
        upload_success: '上傳完成',
        upload_fail: '上傳失敗，請重新上傳',
        delete_success: '刪除完成',
        delete_fail: '刪除失敗',
        check_delete: '確定要刪除?',
        settle_accounts: '結帳',
        set_alarm_time: '設定提醒時間',
        hour_ago: '一小時前',
        date_ago: '一天前',
        no_use: '未使用',
        completed: '已完成',
        cancelled: '已取消',
        overdue: '逾期未至',
        no_input_name_or_pass: '未輸入帳號密碼',
        no_input_pass: '未輸入密碼',
        no_input_data: '尚未輸入訊息',
        pay: '結帳',
        serving: '服務',
        not_input_complete: '未輸入完整',
        old_pass_error: '舊密碼錯誤',
        new_pass_check_error: '新密碼確認錯誤',
        finished: '完成',
        week_array: ["星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"],
        code39_title: '銷帳條碼',
        no_code39: '此活動未設置銷帳條碼',
        name_or_pass_error: '帳號或密碼錯誤',
        reLoginTimes: '可再嚐試登入次數：',
        bindingType: '請選擇綁定類別',
        noBinding: '尚未綁定社群帳號',
        front_cover: '封面',
        pic: '圖',
        edit_success: '更改完成',
        serving_success: '服務完成',
        add: '新增',
        add_success: '新增完成',
        not_reservation_time: '該券預約使用時間不符，請確認是否使用',
        use_finish: '使用完畢',
        renounce_use: '放棄使用',
        no_come: '失約未至',
        sqna_message: '留言',
        data_not_complete: '資料未填寫完整',
        input_limit70: '請輸入評論(限70字以內)',
        reply_success: '回覆完成',
        search: '查詢',
        no_comment: '暫無評論',
        close: '關閉',
        start_use: '啟用',
        stop_use: '停用',
        shopcoupon_edit_check: '<div class="alert-text">請先將活動停刊，再進行修改</div>',
        shopservice_edit_check: '<div class="alert-text">請先將服務停用，再進行修改</div>',
        service_queue: '服務排隊',
        queuing: '排隊中',
        servicing: '已服務',
        abandoned: '棄用',
        missed: '失約',
        missed_serviced: '失約仍到訪服務',
        abandoned_serviced: '棄用後仍到訪服務',
        passed: '過號',
        passed_serviced: '過號已服務',
        queue_success: '排隊完成',
        call_success: '叫號完成，請等候用戶前往',
        passed_success: '過號完成，已自動呼叫下一號',
        service_qr_title: '服務條碼',
        service_qr_context: '憑本券至指定商家，出示此ＱＲ碼給商家掃描確認後，即可接受服務',
        _010916003: '用戶已棄用，由商家自行決定是否提供服務',
        _010916005: '用戶已設置失約，由商家自行決定是否提供服務',
        _010916007: '用戶已過號，由於商家自行決定是否提供服務',
        _010919007: '<div class="alert-text">叫號完成，目前叫號已達兩次，可於十分鐘後設置過號</div>',
        will_go: '即將抵達',
        not_go: '無法前往',
        reply_branch: '已通知商家',
        spacesError: '*勿含空白字元',
        lengthError50: '*勿超過50字',
        lengthError15: '*勿超過15字',
        lengthError10: '*勿超過10字',
        branch_name: '店名',
        tel: '電話',
        type: '類別',
        branch_region: '郵遞區號',
        pay_list: '方案項目',
        now_coin: '現有點數',
        sd_type: '商家類別',
        sd_type_array: ['洗車鍍膜', '保養維修'],
        application_data_record: '前次紀錄',
        _delete: '刪除',
        not_finish: '未完成',
        _continue: '繼續',
        application_success: '申請成功,請重新登入',
        publish_success: '刊登完成',
        shop_type: '商家類別',
        shop_type_array: ['汽車特店', '廟宇'],
        dcil_list: [{
                "dcil_id": "c37268c360e0463aa6d095fd318d9ade",
                "dcil_category": 3,
                "dcil_itemname": "無限期",
                "dcil_depositamount": 0,
                "dcil_availabledays": 999999,
                "dcil_iconpath": null,
                "dcil_itemdescript": "付費於汽車特店內刊登商家資訊,並使用對應功能"
            }
            /*, {
                            "dcil_id": "c37268c360e0463aa6d095fd318d9ade",
                            "dcil_category": 1,
                            "dcil_itemname": "無限期",
                            "dcil_depositamount": 0,
                            "dcil_availabledays": 90,
                            "dcil_iconpath": null,
                            "dcil_itemdescript": "付費於isCar合作社內刊登商家資訊,並使用對應功能"
                        }*/
            /*, {
                        "dcil_id": "78e112898a334b02bd8ecf601c864b1b",
                        "dcil_category": 1,
                        "dcil_itemname": "車輛販售廣告刊登90天",
                        "dcil_depositamount": 0,
                        "dcil_availabledays": 90,
                        "dcil_iconpath": null,
                        "dcil_itemdescript": "付費於isCar車賣場內刊登您的車輛訊息90天"
                    }, {
                        "dcil_id": "8195e11ca2d14b06985c7873bfcb77a8",
                        "dcil_category": 2,
                        "dcil_itemname": "贈送試用期30天",
                        "dcil_depositamount": 0,
                        "dcil_availabledays": 30,
                        "dcil_iconpath": null,
                        "dcil_itemdescript": null
                    }, {
                        "dcil_id": "9ae7609723c34efb9bad7f248e07f5f8",
                        "dcil_category": 1,
                        "dcil_itemname": "列表項目背色強化顯示",
                        "dcil_depositamount": 0,
                        "dcil_availabledays": 90,
                        "dcil_iconpath": null,
                        "dcil_itemdescript": "列表搜尋結果顯示背色為紅,強化顯示項目"
                    }, {
                        "dcil_id": "f1756980ce694a9a8d6a68a2f3a4d29b",
                        "dcil_category": 2,
                        "dcil_itemname": "無贈送試用期",
                        "dcil_depositamount": 0,
                        "dcil_availabledays": 0,
                        "dcil_iconpath": null,
                        "dcil_itemdescript": null
                    }*/
        ],
        no_dcil_id_or_type: '尚未選擇方案或類別',
        no_shop: '尚未選擇店家',
        no_input_name: '尚未輸入名稱',
        no_reason: '尚未填寫原因',
        _010917001: '服務完成後將超過今日服務時間，將停止自動叫號，請問是否結束今日排隊服務',
        _010917004: '暫無次一隊列用戶，請等候新用戶選用服務',
        max_5: '最多設定5組',
        fbBindingCheck: '此功能需經FB帳號授權使用，請先綁定FB帳號',
        already_send: '已送出',
        yes: '是',
        no: '否',
        context: '內容',
        no_introduce: '否,介紹相似車款',
        set_remind_two_hr: '是否設置前兩小時提醒',
        setting_success: '已設置提醒',
        apply_join: '申請加入',
        establish: '創立',
        establish_club: '創立車團',
        club_name: '車團名稱',
        isPublic: '是否對外開放',
        input_name: '請輸入名稱',
        invitation_to_join: '邀請加入',
        join_check: '入團申請審核',
        club_info_edit: '車團資料變更',
        club_vote_event: '車團表決事件申請',
        message_management: '留言板管理',
        exit_culd: '退出車團',
        announcement_context: '公告內容',
        description_context: '簡介內容',
        user_info: '用戶資訊',
        vote_item: '表決項目',
        reason: '原因',
        establish_success: '創立完成',
        apply_success: '申請完成',
        agreed: '已同意',
        refused: '已拒絕',
        chose_level: '請選擇職等',
        club_levels: ['副團長', '高級團員', '一般團員'],
        assign_success: '指派完成',
        quit_club_success: '退團完成',
        quit_club_check: '確定要退出車團？',
        assign_commander: '請指派接任團長',
        agree: '同意',
        oppose: '反對',
        refuse: '拒絕',
        vote_success: '投票完成，請等候系統結算',
        cmsr_operationtype_1: '被允許加入',
        cmsr_operationtype_2: '同意邀請後加入',
        cmsr_operationtype_3: '遭驅逐出團',
        cmsr_operationtype_4: '退出社團',
        cmsr_operationtype_5: '權級調整',
        invite_success: '邀請完成',
        agree_invite: '已同意邀請，請重新登入',
        refuse_invite: '已拒絕邀請',
        club_invite: '車團邀請',
        dismiss_club: '解散車團',
        _011209001: '當前無可用之申請加入記錄，請稍候再試',
        today: '今天',
        no_member: '無團員',
        scan_msg_error: '條碼格式有誤',
        no_club: '暫無車團',
        no_message: '暫無留言',
        no_log: '暫無紀錄',
        no_data: '暫無資訊',
        no_shopcoupon: '暫無優惠活動',
        no_service: '暫無服務',
        _011213004: '退團完成，無繼任人選車團同步解散',
        club_search: '車團查詢',
        input_club_name: '請輸入車團名稱',
        re_select: '重選',
        remark: '備註',
        logoutCheck: '確定要登出?',
        gender: '性別',
        gender_array: ['男', '女'],
        age: '年齡',
        male: '男',
        female: '女',
        region: '地區',
        input_gender: '請選擇性別',
        input_age: '請選擇年齡',
        input_region: '請選擇地區',
        search_result: '查詢結果',
        any_condition: '至少填寫任一條件',
        search_null: '查無資料',
        select_img: '選擇圖片',
        no_select: '尚未選擇對象',
        now_registered: '立即註冊',
        member_login: '會員登入',
        member_password: '密碼',
        forget_password: '忘記密碼',
        other_login: '其他方式登入',
        re_send: '重新發送',
        mobile_check: '手機驗證',
        verification_code: '驗證碼',
        mobile: '手機',
        nickname: '暱稱',
        select_region: '先選擇地區',
        mobile_set: '手機設定',
        original_email: '請輸入原註冊之電子信箱',
        tpr_title: '稱謂',
        tpr_title_array: ['父', '母', '子', '女', '祖父母', '外祖父母', '兄弟', '姊妹', '孫', '孫女', '外孫', '外孫女', '曾祖父母', '外曾祖父母', '伯叔', '姑', '舅父', '姨母', '姪男', '姪女', '外甥', '外甥女', '曾孫', '曾孫女', '外曾孫', '外曾孫女', '夫', '妻', '男朋友', '女朋友'],
        tpr_birthdaytime_array: ['吉時', '子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥'],
        add_relatives: '新增親屬資料',
        edit_relatives: '編輯親屬資料',
        relatives_list: '親屬清單',
        unselected_relatives: '尚未選擇親屬',
        temple_donate: '香油錢捐獻',
        temple_light: '祈福點燈',
        _clear: '清除',
        input_end_date: '請選擇結束日期',
        no_coin: '點數不足',
        not_more_than_31days: '日期間距不能大於31天',
        tpr_birthdaytime: '出生時辰',
        myinfo_incomplete: '個人資料未建立完全，請先至會員選單 > 我的資訊 > 車友資料進行填寫',
        self: '自己',
        not_del_self: '無法刪除自己',
        no_blessed: '尚未添加被祈福者',
        no_light: '尚有被祈福者未選擇燈別',
        limit100: '勿超過100字',
        max100: '上限100字',
        no_quick_msg: '尚未設置快選訊息資料',
        to_set: '前往設置',
        set_success: '設置完成',
        refunds_note: '* 交易完成後隔天(交易當天00:00:00即為隔天計)，若用戶欲申請退款，將收取原交易金額15%，為退款服務手續費。<br>* 若用戶於交易完成當日(交易當天23:59:59前)即申請退款，則免收取退款服務手續費。',
        setting: '前往設定',
        default_GPS: '使用預設座標',
        default_lat: 25.047908,
        default_lng: 121.517115,
        repay_context: '訂單已建立，請至會員選單 > 消費紀錄進行補付款',
        goto: '前往',
        subscription_success: '訂閱完成',
        cancel_subscription: '取消訂閱',
        discount: '折抵數額',
        input_discount: '請輸入欲折抵數額',
        disagree: '尚未同意',
        no_set_memberseccode: '尚未設置用戶安全碼',
        settingFinish: '設置完成',
        set_memberseccode: '設置用戶安全碼',
        input_old_memberseccode: '請輸入舊用戶安全碼',
        input_new_memberseccode: '請輸入新用戶安全碼',
        input_memberseccode: '請輸入用戶安全碼',
        memberseccode_same: '新舊安全碼不能相同',
        _set: '設置',
        next: '下一步',
        select_year: '選擇年份',
        select_month: '選擇月份',
        introduce_completed: '介紹完成'


    },
    used_car: {
        cbi_carbrand: '廠牌',
        cbi_carbodytype: '車種',
        cbi_saleprice: '售價',
        cbi_brandmodel: '車系',
        cbi_modelstyle: '車款',
        cbi_carsource: '來源',
        cbi_carlocation: '所在地',
        cbi_carbodycolor: '外觀色',
        cbi_carinteriorcolor: '內裝色',
        cbi_displacement: '排氣量',
        cbi_fueltype: '引擎燃料',
        cbi_transmissionsystem: '變速系統',
        cbi_drivemode: '驅動方式',
        cbi_carseats: '乘客數',
        cbi_cardoors: '車門數',
        cbi_manufactoryyear: '出廠年份',
        cbi_manufactorymonth: '出廠月份',
        cbi_caryearstyle: '年式',
        cbi_licensestatus: '牌照狀態',
        cbi_licensingyear: '領牌年份',
        cbi_licensingmonth: '領牌月份',
        cbi_everrepair: '是否維修',
        cbi_carbrand_json: [{
            "cbl_id": 1,
            "cbl_fullname": "BENZ",
            "cbl_nickname": "賓士",
            "cbl_hotitemtag": 0,
            "cbl_shortname": "BENZ",
            "cbl_listorder": null,
            "cbl_iconpath": null
        }, {
            "cbl_id": 2,
            "cbl_fullname": "BMW",
            "cbl_nickname": "BMW",
            "cbl_hotitemtag": 0,
            "cbl_shortname": "BMW",
            "cbl_listorder": null,
            "cbl_iconpath": null
        }, {
            "cbl_id": 3,
            "cbl_fullname": "Ford",
            "cbl_nickname": "Ford",
            "cbl_hotitemtag": 0,
            "cbl_shortname": "Ford",
            "cbl_listorder": null,
            "cbl_iconpath": null
        }, {
            "cbl_id": 4,
            "cbl_fullname": "Honda",
            "cbl_nickname": "Honda",
            "cbl_hotitemtag": 0,
            "cbl_shortname": "Honda",
            "cbl_listorder": null,
            "cbl_iconpath": null
        }, {
            "cbl_id": 5,
            "cbl_fullname": "Toyota",
            "cbl_nickname": "Toyota",
            "cbl_hotitemtag": 0,
            "cbl_shortname": "Toyota",
            "cbl_listorder": null,
            "cbl_iconpath": null
        }],
        cbi_brandmodel_json: [{
            "cbm_id": 1,
            "cbl_id": 1,
            "cbm_fullname": "A-CLASS",
            "cbm_nickname": "A-CLASS",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "A-CLASS",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 2,
            "cbl_id": 1,
            "cbm_fullname": "B-Class",
            "cbm_nickname": "B-Class",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "B-Class",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 3,
            "cbl_id": 1,
            "cbm_fullname": "C-Class",
            "cbm_nickname": "C-Class",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "C-Class",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 6,
            "cbl_id": 2,
            "cbm_fullname": "1-Series",
            "cbm_nickname": "1-Series",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "1-Series",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 7,
            "cbl_id": 2,
            "cbm_fullname": "3-Series Sedan",
            "cbm_nickname": "3-Series Sedan",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "3-Series Sedan",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 8,
            "cbl_id": 2,
            "cbm_fullname": "5-Series",
            "cbm_nickname": "5-Series",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "5-Series",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 9,
            "cbl_id": 3,
            "cbm_fullname": "EcoSport",
            "cbm_nickname": "EcoSport",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "EcoSport",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 10,
            "cbl_id": 3,
            "cbm_fullname": "Kuga",
            "cbm_nickname": "Kuga",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "Kuga",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 11,
            "cbl_id": 3,
            "cbm_fullname": "Escape",
            "cbm_nickname": "Escape",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "Escape",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 12,
            "cbl_id": 4,
            "cbm_fullname": "Accord",
            "cbm_nickname": "Accord",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "Accord",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 13,
            "cbl_id": 4,
            "cbm_fullname": "CR-V",
            "cbm_nickname": "CR-V",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "CR-V",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 14,
            "cbl_id": 4,
            "cbm_fullname": "City",
            "cbm_nickname": "City",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "City",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 15,
            "cbl_id": 5,
            "cbm_fullname": "86",
            "cbm_nickname": "86",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "86",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 16,
            "cbl_id": 5,
            "cbm_fullname": "Alphard",
            "cbm_nickname": "Alphard",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "Alphard",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }, {
            "cbm_id": 17,
            "cbl_id": 5,
            "cbm_fullname": "COROLLA ALTIS X",
            "cbm_nickname": "COROLLA ALTIS X",
            "cbm_hotitemtag": 0,
            "cbm_shortname": "COROLLA ALTIS X",
            "cbm_listorder": null,
            "cbm_iconpath": null
        }],
        cbi_modelstyle_json: [{
            "cms_id": 1,
            "cbl_id": 1,
            "cbm_id": 1,
            "cms_fullname": "190 E",
            "cms_nickname": "190 E",
            "cms_hotitemtag": 0,
            "cms_shortname": "190 E",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 2,
            "cbl_id": 1,
            "cbm_id": 1,
            "cms_fullname": "A160",
            "cms_nickname": "A160",
            "cms_hotitemtag": 0,
            "cms_shortname": "A160",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 3,
            "cbl_id": 1,
            "cbm_id": 2,
            "cms_fullname": "B180",
            "cms_nickname": "B180",
            "cms_hotitemtag": 0,
            "cms_shortname": "B180",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 4,
            "cbl_id": 1,
            "cbm_id": 2,
            "cms_fullname": "B200",
            "cms_nickname": "B200",
            "cms_hotitemtag": 0,
            "cms_shortname": "B200",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 5,
            "cbl_id": 1,
            "cbm_id": 3,
            "cms_fullname": "C200K Classic",
            "cms_nickname": "C200K Classic",
            "cms_hotitemtag": 0,
            "cms_shortname": "C200K Classic",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 6,
            "cbl_id": 1,
            "cbm_id": 3,
            "cms_fullname": "C200K T",
            "cms_nickname": "C200K T",
            "cms_hotitemtag": 0,
            "cms_shortname": "C200K T",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 7,
            "cbl_id": 2,
            "cbm_id": 6,
            "cms_fullname": "118i",
            "cms_nickname": "118i",
            "cms_hotitemtag": 0,
            "cms_shortname": "118i",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 8,
            "cbl_id": 2,
            "cbm_id": 6,
            "cms_fullname": "120i",
            "cms_nickname": "120i",
            "cms_hotitemtag": 0,
            "cms_shortname": "120i",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 9,
            "cbl_id": 2,
            "cbm_id": 7,
            "cms_fullname": "220i M Sport",
            "cms_nickname": "220i M Sport",
            "cms_hotitemtag": 0,
            "cms_shortname": "220i M Sport",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 10,
            "cbl_id": 2,
            "cbm_id": 7,
            "cms_fullname": "220i Sport Line",
            "cms_nickname": "220i Sport Line",
            "cms_hotitemtag": 0,
            "cms_shortname": "220i Sport Line",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 11,
            "cbl_id": 2,
            "cbm_id": 8,
            "cms_fullname": "528i M Sport Package",
            "cms_nickname": "528i M Sport Package",
            "cms_hotitemtag": 0,
            "cms_shortname": "528i M Sport Package",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 12,
            "cbl_id": 2,
            "cbm_id": 8,
            "cms_fullname": "520i",
            "cms_nickname": "520i",
            "cms_hotitemtag": 0,
            "cms_shortname": "520i",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 13,
            "cbl_id": 3,
            "cbm_id": 9,
            "cms_fullname": "1.5L",
            "cms_nickname": "1.5L",
            "cms_hotitemtag": 0,
            "cms_shortname": "1.5L",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 14,
            "cbl_id": 3,
            "cbm_id": 10,
            "cms_fullname": "1.5L",
            "cms_nickname": "1.5L",
            "cms_hotitemtag": 0,
            "cms_shortname": "1.5L",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 15,
            "cbl_id": 2,
            "cbm_id": 10,
            "cms_fullname": "2.0L d",
            "cms_nickname": "2.0L d",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.0L d",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 16,
            "cbl_id": 3,
            "cbm_id": 11,
            "cms_fullname": "2.3 2WD XLT",
            "cms_nickname": "2.3 2WD XLT",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.3 2WD XLT",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 17,
            "cbl_id": 2,
            "cbm_id": 11,
            "cms_fullname": "2.3 4WD",
            "cms_nickname": "2.3 4WD",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.3 4WD",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 18,
            "cbl_id": 4,
            "cbm_id": 12,
            "cms_fullname": "2.4 VTi-S Exclusive",
            "cms_nickname": "2.4 VTi-S Exclusive",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.4 VTi-S Exclusive",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 19,
            "cbl_id": 2,
            "cbm_id": 12,
            "cms_fullname": "2.4 VTi Luxury",
            "cms_nickname": "2.4 VTi Luxury",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.4 VTi Luxury",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 20,
            "cbl_id": 4,
            "cbm_id": 13,
            "cms_fullname": "2.0 VTi",
            "cms_nickname": "2.0 VTi",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.0 VTi",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 21,
            "cbl_id": 2,
            "cbm_id": 13,
            "cms_fullname": "2.4 VTi-S",
            "cms_nickname": "2.4 VTi-S",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.4 VTi-S",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 22,
            "cbl_id": 4,
            "cbm_id": 14,
            "cms_fullname": "1.5 VTi",
            "cms_nickname": "1.5 VTi",
            "cms_hotitemtag": 0,
            "cms_shortname": "1.5 VTi",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 23,
            "cbl_id": 5,
            "cbm_id": 15,
            "cms_fullname": "86 Aero",
            "cms_nickname": "86 Aero",
            "cms_hotitemtag": 0,
            "cms_shortname": "86 Aero",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 24,
            "cbl_id": 5,
            "cbm_id": 15,
            "cms_fullname": "86 Limited 6AT",
            "cms_nickname": "86 Limited 6AT",
            "cms_hotitemtag": 0,
            "cms_shortname": "86 Limited 6AT",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 25,
            "cbl_id": 5,
            "cbm_id": 16,
            "cms_fullname": "2.4",
            "cms_nickname": "2.4",
            "cms_hotitemtag": 0,
            "cms_shortname": "2.4",
            "cms_listorder": null,
            "cms_iconpath": null
        }, {
            "cms_id": 26,
            "cbl_id": 5,
            "cbm_id": 16,
            "cms_fullname": "3.5",
            "cms_nickname": "3.5",
            "cms_hotitemtag": 0,
            "cms_shortname": "3.5",
            "cms_listorder": null,
            "cms_iconpath": null
        }],
        cbi_carbodytype_json: [{
            "cbt_id": 1,
            "cbt_fullname": "四門轎車",
            "cbt_nickname": "四門轎車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "四門轎車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 2,
            "cbt_fullname": "掀背車",
            "cbt_nickname": "掀背車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "掀背車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 3,
            "cbt_fullname": "雙門跑車",
            "cbt_nickname": "雙門跑車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "雙門跑車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 4,
            "cbt_fullname": "敞篷車",
            "cbt_nickname": "敞篷車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "敞篷車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 5,
            "cbt_fullname": "休旅車",
            "cbt_nickname": "休旅車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "休旅車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 6,
            "cbt_fullname": "轎式休旅車",
            "cbt_nickname": "轎式休旅車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "轎式休旅車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 7,
            "cbt_fullname": "高頂休旅車",
            "cbt_nickname": "高頂休旅車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "高頂休旅車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 8,
            "cbt_fullname": "五門旅行車",
            "cbt_nickname": "五門旅行車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "五門旅行車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }, {
            "cbt_id": 9,
            "cbt_fullname": "貨卡車",
            "cbt_nickname": "貨卡車",
            "cbt_hotitemtag": 0,
            "cbt_shortname": "貨卡車",
            "cbt_listorder": null,
            "cbt_iconpath": null
        }],
        cbi_carlocation_json: [{
            "cln_id": 1,
            "cln_cityname": "台北市",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 2,
            "cln_cityname": "基隆市",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 3,
            "cln_cityname": "新北市",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 4,
            "cln_cityname": "連江縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 5,
            "cln_cityname": "宜蘭縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 6,
            "cln_cityname": "新竹市",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 7,
            "cln_cityname": "新竹縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 8,
            "cln_cityname": "桃園縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 9,
            "cln_cityname": "苗栗縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 10,
            "cln_cityname": "彰化縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 11,
            "cln_cityname": "南投縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 12,
            "cln_cityname": "嘉義市",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 13,
            "cln_cityname": "嘉義縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 14,
            "cln_cityname": "雲林縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 15,
            "cln_cityname": "台南市",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 16,
            "cln_cityname": "高雄市",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 17,
            "cln_cityname": "澎湖縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 18,
            "cln_cityname": "金門縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 19,
            "cln_cityname": "屏東縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 20,
            "cln_cityname": "台東縣",
            "cln_districtname": null,
            "cln_listorder": null
        }, {
            "cln_id": 21,
            "cln_cityname": "花蓮縣",
            "cln_districtname": null,
            "cln_listorder": null
        }],
        cbi_carbodycolor_json: [{
            "cbc_id": 1,
            "cbc_colorname": "白"
        }, {
            "cbc_id": 2,
            "cbc_colorname": "黑"
        }, {
            "cbc_id": 3,
            "cbc_colorname": "紅"
        }, {
            "cbc_id": 4,
            "cbc_colorname": "藍"
        }],
        cbi_carinteriorcolor_json: [{
            "cic_id": 1,
            "cic_colorname": "白"
        }, {
            "cic_id": 2,
            "cic_colorname": "黑"
        }, {
            "cic_id": 3,
            "cic_colorname": "銀灰"
        }, {
            "cic_id": 4,
            "cic_colorname": "藍"
        }],
        cbi_carsource_json: [{
            "cse_id": 1,
            "cse_sourcename": "國產車",
            "cse_listorder": null
        }, {
            "cse_id": 2,
            "cse_sourcename": "歐洲車",
            "cse_listorder": null
        }, {
            "cse_id": 3,
            "cse_sourcename": "美國車",
            "cse_listorder": null
        }, {
            "cse_id": 4,
            "cse_sourcename": "日本車/亞裔美規車",
            "cse_listorder": null
        }, {
            "cse_id": 5,
            "cse_sourcename": "韓國車",
            "cse_listorder": null
        }, {
            "cse_id": 6,
            "cse_sourcename": "中國車",
            "cse_listorder": null
        }],
        cbi_transmissionsystem_array: ['手自排', '自手排', '自排', '手排'],
        cbi_fueltype_array: ['汽油車', '柴油車', 'HyBrid混合動力車', '瓦斯車', '電動車'],
        cbi_drivemode_array: ['前輪驅動', '後輪驅動', '四輪驅動'],
        cbi_carseats_array: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
        cbi_cardoors_array: [2, 3, 4, 5],
        cbi_carequiptments_json: [{
            "ces_id": 1,
            "ces_category": 0,
            "ces_ietmesname": "HID氙氣頭燈",
            "ces_listorder": null
        }, {
            "ces_id": 2,
            "ces_category": 0,
            "ces_ietmesname": "安全氣囊",
            "ces_listorder": null
        }, {
            "ces_id": 3,
            "ces_category": 1,
            "ces_ietmesname": "天窗",
            "ces_listorder": null
        }, {
            "ces_id": 4,
            "ces_category": 1,
            "ces_ietmesname": "全景天窗",
            "ces_listorder": null
        }, {
            "ces_id": 5,
            "ces_category": 2,
            "ces_ietmesname": "電折後視鏡",
            "ces_listorder": null
        }, {
            "ces_id": 6,
            "ces_category": 2,
            "ces_ietmesname": "備胎架",
            "ces_listorder": null
        }, {
            "ces_id": 7,
            "ces_category": 3,
            "ces_ietmesname": "渦輪增壓",
            "ces_listorder": null
        }, {
            "ces_id": 8,
            "ces_category": 3,
            "ces_ietmesname": "機械增壓",
            "ces_listorder": null
        }],
        cbi_guaranteeitems_json: [{
            "cgi_id": 1,
            "cgi_itemname": "里程保證",
            "cgi_listorder": null
        }, {
            "cgi_id": 2,
            "cgi_itemname": "實車",
            "cgi_listorder": null
        }, {
            "cgi_id": 3,
            "cgi_itemname": "鑑定書",
            "cgi_listorder": null
        }, {
            "cgi_id": 4,
            "cgi_itemname": "非泡水車",
            "cgi_listorder": null
        }, {
            "cgi_id": 5,
            "cgi_itemname": "非營業車",
            "cgi_listorder": null
        }, {
            "cgi_id": 6,
            "cgi_itemname": "非贓車",
            "cgi_listorder": null
        }, {
            "cgi_id": 7,
            "cgi_itemname": "非失竊尋回車",
            "cgi_listorder": null
        }],
        cbi_licensestatus_array: ['已領牌', '未領牌', '停用/註銷', '全新車'],
        cbi_everrepair_array: ['是', '否'],
        cbi_salestatus_array: ['出售中', '已收訂', '賀成交'],
        dcil_list_json0: [{
            "dcil_id": "c37268c360e0463aa6d095fd318d9ade",
            "dcil_category": 3,
            "dcil_itemname": "無使用期限",
            "dcil_depositamount": 0,
            "dcil_availabledays": 999999,
            "dcil_iconpath": '',
            "dcil_itemdescript": "付費於汽車特店內刊登商家資訊,並使用對應功能"
        }],
        dcil_list_json1: [{
                "dcil_id": "78e112898a334b02bd8ecf601c864b1b",
                "dcil_category": 1,
                "dcil_itemname": "車輛販售廣告刊登90天",
                "dcil_depositamount": 0,
                "dcil_availabledays": 90,
                "dcil_iconpath": '',
                "dcil_itemdescript": "付費於isCar車賣場內刊登您的車輛訊息90天"
            }, {
                "dcil_id": "78e112898a334b02bd8ecf601c864b1b",
                "dcil_category": 1,
                "dcil_itemname": "車輛販售廣告刊登90天",
                "dcil_depositamount": 0,
                "dcil_availabledays": 90,
                "dcil_iconpath": '',
                "dcil_itemdescript": "付費於isCar車賣場內刊登您的車輛訊息90天"
            }, {
                "dcil_id": "78e112898a334b02bd8ecf601c864b1b",
                "dcil_category": 1,
                "dcil_itemname": "車輛販售廣告刊登90天",
                "dcil_depositamount": 0,
                "dcil_availabledays": 90,
                "dcil_iconpath": '',
                "dcil_itemdescript": "付費於isCar車賣場內刊登您的車輛訊息90天"
            }

        ]
    },
    my_car: {
        input_brand: '請選擇廠牌',
        input_brand_f: '請先選擇廠牌',
        input_car_modal: '請選擇車系',
        input_car_modal_f: '請先選擇車系',
        input_car_style: '請選擇車款',
        select_car: '挑選車款',
        car_brand: '廠牌',
        car_modal: '車系',
        car_style: '車款',
        car_yearstyle: '年式',
        check_yearstyle: '確認年式',

        moc_purchasedate: '購買年月',
        moc_carbodycolor: '車色',
        moc_ownstatus: '持有狀態',
        moc_enginenumber: '引擎編號',
        moc_vin: '車身號碼',
        input_carbodycolor: '請輸入車色',
        input_enginenumber: '請輸入引擎編號',
        input_vin: '請輸入車身號碼',
        moc_cartypecode_json: [{
            id: '01',
            type: '重型機器腳踏車'
        }, {
            id: '02',
            type: '輕型機器腳踏車'
        }, {
            id: '03',
            type: '自用小客車'
        }, {
            id: '04',
            type: '自用小貨車'
        }, {
            id: '05',
            type: '自用大客車'
        }, {
            id: '06',
            type: '自用大貨車'
        }, {
            id: '07',
            type: '營業小客車'
        }, {
            id: '08',
            type: '營業小貨車'
        }, {
            id: '09',
            type: '營業大客車'
        }, {
            id: '10',
            type: '營業大貨車'
        }, {
            id: '11',
            type: '自用小型特種車'
        }, {
            id: '12',
            type: '自用大型特種車'
        }, {
            id: '13',
            type: '營業一般貨運曳引車'
        }, {
            id: '14',
            type: '租賃小客車'
        }, {
            id: '15',
            type: '個人計程車'
        }, {
            id: '16',
            type: '營業小型特種車'
        }, {
            id: '17',
            type: '營業大型特種車'
        }, {
            id: '18',
            type: '自用一般貨運曳引車'
        }, {
            id: '19',
            type: '公司行號自用小貨車'
        }, {
            id: '20',
            type: '公司行號自用大貨車'
        }, {
            id: '21',
            type: '長期租賃小客車'
        }, {
            id: '22',
            type: '客貨兩用車'
        }, {
            id: '23',
            type: '租賃大客車'
        }, {
            id: '24',
            type: '長期租賃大客車'
        }, {
            id: '25',
            type: '軍用行政車輛'
        }, {
            id: '26',
            type: '軍用戰鬥車輛'
        }, {
            id: '27',
            type: '動力機械'
        }, {
            id: '28',
            type: '臨時牌照車輛'
        }, {
            id: '29',
            type: '試車牌照車輛'
        }, {
            id: '30',
            type: '營業貨櫃貨運曳引車'
        }, {
            id: '31',
            type: '自用貨櫃貨運曳引車'
        }, {
            id: '32',
            type: '超重型機器腳踏車'
        }, {
            id: '33',
            type: '農耕機械車'
        }, {
            id: '34',
            type: '電動車'
        }],
        moc_ownstatus_array: ['已持有', '已售出', '已報銷']
    },
    menu: {
        mailbox: '收件夾',
        myBookmarks: '我的書籤',
        memberInfo: '會員資料',
        gallery: '圖庫',
        bugReport: '問題回報',
        logout: '登出',
        appShare: 'App分享',
        testBlcok: '業務專區',
        fbBinding: '綁定Facebook',
        bonusInquire: 'isCar錢包',
        isCarNews: 'isCar新聞',
        reservationRecord: '預約紀錄',
        scanRecord: '掃描記錄',
        coupon_main: '旗艦館',
        coupon_record: '使用紀錄',
        modify_password: '修改密碼',
        server_index: '首頁',
        member_card: '會員名片',
        branch_cooperative: 'isCar合作社',
        branch_info_edit: '廠商資訊修改',
        used_car_market: '車賣場'
    },
    blogMenu: {
        index: '頭條精選',

        weekly_fun_facts: 'isCar週遊趣',
        legal_class: '鴻毅有辦法',
        good_choise: '不難要怎樣',
        david_power: '大衛行勢力',
        car_camp: '開車去露營',
        famous_vip: '藝名菁人',
        car_travel: '開車去旅行',

        top_news: '新聞我最快',
        new_car: '新車快訊',
        home_abroad: '國外新聞',
        taiwan_carnews: '國內新聞',
        upgrade: '周邊升級',
        strengthen: '性能強化',
        race: '賽事動態',
        intel: '車廠情報',

        best_buy: '購車我最行',
        roadtest: '試車報告',
        top_sales: '銷售資訊',
        used_car: '中古情報',

        love_life: '汽車玩很大',
        love_car: '汽車生活',
        love_travel: '旅遊樂活',
        love_fun: '新奇逗趣',
        love_knowledge: '玩車尚智',
        menschannel: '男人不要看',

        hottest: '今日我最夯',
        hot_brand: '最夯品牌',
        hot_people: '最夯人物',
        hot_talk: '最夯話題',
        hot_event: '最夯活動',

        autoshow: '2016世界新車大展',
        autoshow_info: '車展情報站',
        autoshow_brand: '參展品牌',
        eye_catching: '超吸睛推薦',
        autoshow_gallery: 'isCar圖輯隊',

        moduleaccount: 'iscarnews',
        modulepassword: '2wsx0okm'

    },
    shop: {
        moduleaccount: 'iscarshop',
        modulepassword: '4rfv5tgb',
        index: '編輯商家首頁',
        shop_data_config: '商家資料設置',
        pay_setting: '金流設置',
        sd_paymentflow: '金流廠商',
        sd_paymentflow_array: ['紅陽', '綠界'],
        sd_question_category_array: ["服務", "品質", "專業度", "準確性", "互動性", "整體滿意度", "再次消費", "願意推薦"],
        shop_list: '汽車特店列表',
        client_list: '會員列表',
        sell_management: '行銷管理',
        code_scan: '條碼掃描',
        bonus_management: '紅利管理',
        reservation_management: '排隊管理',
        shopcoupon_main: '商品/預約管理',
        shopcoupon_management: '商品管理',
        appointment_management: '暫停預約管理',
        activity_preferential: '活動優惠',
        queue_reservation: '排隊預約',
        open_store: '我要開店',
        evaluation_management: '評價管理',
        shop_record: '服務紀錄',
        shop_records: '銷售/服務紀錄',
        map: '地圖搜索',
        no_GPS: '未取得GPS定位，無法查詢周邊店家，預設座標：台北車站',
        favorite: '我的最愛',
        sd_contact_person: '聯絡人名稱',
        sd_uniformnumbers: '統一編號',
        order_form_management: '商品訂單管理',
        coupon_order_forms: '票券訂單列表',
        order_forms: '商品訂單列表',
        coupon_order_form_info: '票券訂單內容',
        order_form_info: '商品訂單內容',
        shop_logistics: '物流資訊',
        shop_buy_data: '購買資訊',
        scl_delivery_time: '指定到貨時段',
        scl_delivery_time_array: ['不指定', '上午', '下午'],
        scm_producttype: '商品型式',
        scm_producttype_array: ['電子服務券', '實體商品'],
        scm_coupon_providetype: '提供類型',
        scm_coupon_providetype_array: ['付費索取', '特點兌換'], //['付費索取', '特點兌換', '寶箱任務', '寶箱獎項'],
        invoice_type: '發票類別',
        invoice_type_array: ['個人電子發票', '公司戶發票'],
        scg_usestatus: '訂單狀態',
        coupon_scg_usestatus_array: ['全部', '未付款', '使用完畢', '放棄使用', '活動截止失效', '已付款', '退款中', '已取消'],
        scg_usestatus_array: ['全部', '未付款', '使用完畢', '放棄使用', '活動截止失效', '已付款', '已印單', '已分裝', '已出貨', '已到貨', '退款中', '已取消'],
        print_order: '印單',
        pick_order: '揀貨',
        picked_order: '已揀貨完成',
        sent_order: '寄件',
        pick_report: '揀貨回報',
        report_success: '回報完成',
        report_sentlogistics: '物流回報',
        _scl_id: '物流編號',
        scl_tracenum: '物流追踪編號',
        chose_page: '選擇頁數',
        coupon_record: '排隊紀錄',
        bonus_record: '紅利紀錄',
        change_branch: '商家切換',
        staff_management: '店員管理',
        message_push: '優惠推送',
        quick_msg: '快選訊息',
        quick_msg_management: '快選訊息管理',
        shop_bookmarks: '商家書籤',
        bonus_item: '紅利項目',
        item_name: '項目名稱',
        bonus_point: '紅利數額',
        bonus_status: '生效狀態',
        no_inventory: '暫無庫存',
        no_image: '尚未上傳圖片',
        pc_print: '請使用PC後台進行列印',
        status_on: '啟用',
        status_off: '停用',
        expired: '已過期',
        illegal: '違規下架',
        status_off_edit: '<div class="alert-text">請先將活動停刊，再進行修改</div>',
        bonus_gift: '紅利贈送',
        cost: '消費金額',
        input_cost: '請輸入金額',
        input_bonus_point: '請輸入數額',
        add_shop: '新增汽車特店書籤將同步成為該汽車特店會員接收最新優惠消息',
        gift_success: '贈送成功',
        duties: '職務',
        manager: '店長',
        employee: '店員',
        add_employee: '新增店員',
        isLeaving: '確定要將此店員設置離職？',
        point: '點數',
        select_member: '選擇推送對象',
        seach_member: '篩選推送對象',
        set_message: '訊息編輯',
        push_check: '推送確認',
        pushed_info: '推送內容',
        pushed_num: '推送數',
        pushed_date: '日期',
        now_coin: '當前購點： <span>0</span> 點',
        today_pushed: '今日推送： <span>0</span> 次',
        push_record: '推送紀錄',
        member: '專屬會員',
        not_member: '非會員',
        push_type: '推送類型',
        shop_items: '選擇商品',
        check_cars: '選擇車款',
        push_item: '推送項目',
        push_type_array: ['商家訊息', '商品訊息', '一般訊息'],
        spm_serno: '商家類別',
        spm_serno_array: ['所有類別', '洗車鍍膜', '保養維修'],
        around_me: '離我最近',
        member_push: '專屬會員推送',
        not_member_push: '非會員推送',
        push_coin: '一則 <span>1</span> 點',
        total_member: '會員總數 <span>0</span> 人',
        select_all: '全選',
        push_num: '推送人數 <span></span> 人',
        set_push_num: '設定本次推送<input type="number"/>人',
        consumption_coin: '消費購點',
        readed_num: '已讀人數',
        push_success: '推送完成',
        no_member: '尚無會員',
        _push_check: '確定要推送？',
        no_push_num: '尚未設定人數',
        shopMessage: '汽車特店訊息',
        quick_msg_context: '快選訊息內容',
        toShop: '前往商家',
        no_queue: '<div class="alert-text">尚無用戶排隊</div>',
        iscarpolicy: '我已明瞭「<a class="query_iscarpolicy">服務條款</a>」所載內容及其意義，並同意該條款規定',
        temple_scan: '祈福掃描',
        business_scan: '業務掃描',
        agree_tips: '<div class="agree_tips_context">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;台端一經啟用商品上架，代表台端同意遵守就是行行銷科技平台相關規定，台端與就是行國際科技有限公司同意以上架之商品實際交易售價百分之87.5(含稅)做為台端銷售商品或勞務給就是行國際科技有限公司之銷售金額。(本條款為就是行行銷科技平台特約店商合作協議補充)。</div>',
        authorization_title: '服務條款',
        authorization_context: '<p>本服務同現場點燈，皆遵循傳統習俗於鹿港天后宮各廳堂廟宇內舉行正式儀式，為信眾完成安奉作業，以免除信眾舟車勞頓之苦。</p><br>' +
            '<h3>祈福燈種：</h3>' +
            '<p>祈福燈種包括(1)安太歲、(2)光明燈、(3)文昌燈、(4)拜斗燈、(5)月老姻緣簿、(6)闔家平安斗。</p><br>' +
            '<h3>安奉費用：</h3>' +
            '<p>除闔家平安斗為每組3,600元，其餘燈種每盞燈安奉費用為600元，各項服務特色請參考本網站相關說明。</p><br>' +
            '<h3>付費方式：</h3>' +
            '<p>(1)線上刷卡、(2)網路ATM、(3)7-ELEVEN ibon代碼繳費。</p><br>' +
            '<h3>申請資格：</h3>' +
            '<p>不須加入會員，不限本人，都可以享受點燈安太歲服務。</p><br>' +
            '<h3>申請流程：</h3>' +
            '<p>' +
            '1. 依步驟填寫相關資料以供廟方進行資料確認及安奉作業。<br>' +
            '2. 完成申請作業後，系統即提供一組訂單編號，供信眾查詢用。<br>' +
            '3. 廟方將於收到申請資料後15天內完成安奉作業並以平信寄送通知函與贈品予民眾。</p><br>' +
            '<h3>其他須知：</h3>' +
            '<p>    1.  所有國曆生辰會被由廟方轉為農曆生辰進行安奉。<br>' +
            '2. 本年度線上點燈之贈品為平安符、通知函及贈品將依申請人所填寫之寄送地址寄送。<br>' +
            '3. 民眾所提供之各項身份資料應正確無誤，若因申請人未提供正確寄送地址而無法送達物品，廟方將不負責查詢地址、重新寄發或其他相關事務。<br>' +
            '4. 農曆年前之訂單，需至該年度農曆正月15日前，由廟方擇良辰吉日完成該年度第一次安奉作業。<br>' +
            '5. 本年度申請之各燈種請將統一於農曆12月24日送神後拆除。<br>' +
            '6. 透過線上點燈之民眾不得要求將本年度之贈品折換現金、兌換券或其他物品。<br>' +
            '7. 本服務說明與贈品得由天后宮於各年度視情況而調整。</p><br>' +
            '<h3>繳費提醒：</h3>' +
            '<p>    1.  進入付款頁面後，請勿重新整理網頁，以免重複扣款。<br>' +
            '2. 若出現扣款失敗訊息，請稍後再申請。<br>' +
            '3. 使用網路ATM付款，免收轉帳手續費。</p><br>' +
            '<h3>客服專線：</h3>' +
            '<p>    若有任何申請或付費問題，請於三個工作天內洽網路訂單客服電話0800-888853。<br>' +
            '(服務時間：週一~週五10:00~18:00，遇國定假日休息)。</p><br>',
        addClient:'是否要將該客戶新增為專屬會員？',
        ssrm_settlementreview: ['未覆核', '已覆核', '帳務有誤', '已覆核(系統)'],
        ssrm_settlementpayment: ['未出款', '已出款', '調帳中'],
        accounts_error: '帳務有誤',
        accounts_error_context: '確認本次帳務有誤，需要isCar站務人員協助確認。',
        accounts_request: '確認請款',
        accounts_request_context: '<div>若確認本次帳務無誤，isCar站務人員將開始執行出款業務，請特店管理人開立發票以便請款。</div><br><div>發票開立金額：NTD <b><span class="price"></span></b></div><br><div style="color:red";>* 個人商家將按個人戶方式出款</div>'
    },
    shop_b: {
        moduleaccount: 'iscarshop_b',
        modulepassword: '4rfv5tgb',
    },
    branchMenu: {
        today_service: '今日服務',
        iscar_news: 'isCar新聞',
        index: '我的首頁',
        index_management: '首頁管理',
        shopcoupon_management: '活動管理',
        reservation_record: '預約紀錄',
        scan_record: '掃描記錄',
        service_queue: '服務排隊',
        evaluation_management: '評價管理',
        shop_record: '紀錄查看',
        fans_management: '粉絲管理',
        change_branch: '商家切換',
        publish_management: '刊登管理',

        branch_cooperative: 'isCar合作社',
        used_car: '二手車商',
        car_cosmetology: '洗車鍍膜',
        car_repair: '保養維修',
        car_tire: '輪胎避震',
        car_department: '周邊配備',
        blessing_block: '祈福專區'
    },
    counties: [
        '臺北市', '基隆市', '新北市', '宜蘭縣', '新竹市', '新竹縣', '桃園市', '苗栗縣', '臺中市',
        '彰化縣', '南投縣', '嘉義市', '嘉義縣', '雲林縣', '臺南市', '高雄市', '澎湖縣', '金門縣',
        '屏東縣', '臺東縣', '花蓮縣', '連江縣'
    ],
    region: {

        臺北市: [
            ['中正區', '大同區', '中山區', '松山區', '大安區', '萬華區', '信義區', '士林區',
                '北投區', '內湖區', '南港區', '文山區'
            ],
            ['100', '103', '104', '105', '106', '108', '110', '111', '112', '114', '115', '116']
        ],
        基隆市: [
            ['仁愛區', '信義區', '中正區', '中山區', '安樂區', '暖暖區', '七堵區'],
            ['200', '201', '202', '203', '204', '205', '206']
        ],
        新北市: [
            ['萬里區', '金山區', '板橋區', '汐止區', '深坑區', '石碇區', '瑞芳區', '平溪區',
                '雙溪區', '貢寮區', '新店區', '坪林區', '烏來區', '永和區', '中和區', '土城區',
                '三峽區', '樹林區', '鶯歌區', '三重區', '新莊區', '泰山區', '林口區', '蘆洲區',
                '五股區', '八里區', '淡水區', '三芝區', '石門區'
            ],
            ['207', '208', '220', '221', '222', '223', '224', '226', '227', '228',
                '231', '232', '233', '234', '235', '236', '237', '238', '239', '241',
                '242', '243', '244', '247', '248', '249', '251', '252', '253'
            ]
        ],
        宜蘭縣: [
            ['宜蘭市', '頭城鎮', '礁溪鄉', '壯圍鄉', '員山鄉', '羅東鎮', '三星鄉', '大同鄉',
                '五結鄉', '冬山鄉', '蘇澳鎮', '南澳鄉', '釣魚臺列嶼'
            ],
            ['260', '261', '262', '263', '264', '265', '266', '267', '268', '269',
                '270', '272', '290'
            ]
        ],
        新竹市: [
            ['新竹市'],
            ['300']
        ],
        新竹縣: [
            ['竹北市', '湖口鄉', '新豐鄉', '新埔鎮', '關西鎮', '芎林鄉', '寶山鄉',
                '竹東鎮', '五峰鄉', '橫山鄉', '尖石鄉', '北埔鄉', '峨眉鄉'
            ],
            ['302', '303', '304', '305', '306', '307', '308', '310', '311',
                '312', '313', '314', '315'
            ]
        ],
        桃園市: [
            ['中壢區', '平鎮區', '龍潭區', '楊梅區', '新屋區', '觀音區', '桃園區', '龜山區',
                '八德區', '大溪區', '復興區', '大園區', '蘆竹區'
            ],
            ['320', '324', '325', '326', '327', '328', '330', '333', '334', '335',
                '336', '337', '338'
            ]
        ],
        苗栗縣: [
            ['竹南鎮', '頭份市', '三灣鄉', '南庄鄉', '獅潭鄉', '後龍鎮', '通霄鎮', '苑裡鎮',
                '苗栗市', '造橋鄉', '頭屋鄉', '公館鄉', '大湖鄉', '泰安鄉',
                '銅鑼鄉', '三義鄉', '西湖鄉', '卓蘭鎮'
            ],
            ['350', '351', '352', '353', '354', '356', '357', '358', '360', '361',
                '362', '363', '364', '365', '366', '367', '368', '369'
            ]
        ],
        臺中市: [
            ['中區', '東區', '南區', '西區', '北區', '北屯區', '西屯區', '南屯區', '太平區',
                '大里區', '霧峰區', '烏日區', '豐原區', '后里區', '石岡區', '東勢區', '和平區',
                '新社區', '潭子區', '大雅區', '神岡區', '大肚區', '沙鹿區', '龍井區', '梧棲區',
                '清水區', '大甲區', '外埔區', '大安區'
            ],
            ['400', '401', '402', '403', '404', '406', '407', '408', '411', '412',
                '413', '414', '420', '421', '422', '423', '424', '426', '427', '428',
                '429', '432', '433', '434', '435', '436', '437', '438', '439'
            ]
        ],
        彰化縣: [
            ['彰化市', '芬園鄉', '花壇鄉', '秀水鄉', '鹿港鎮', '福興鄉', '線西鄉', '和美鎮',
                '伸港鄉', '員林市', '社頭鄉', '永靖鄉', '埔心鄉', '溪湖鎮', '大村鄉', '埔鹽鄉',
                '田中鎮', '北斗鎮', '田尾鄉', '埤頭鄉', '溪州鄉', '竹塘鄉', '二林鎮', '大城鄉',
                '芳苑鄉', '二水鄉'
            ],
            ['500', '502', '503', '504', '505', '506', '507', '508', '509', '510',
                '511', '512', '513', '514', '515', '516', '520', '521', '522', '523',
                '524', '525', '526', '527', '528', '530'
            ]
        ],
        南投縣: [
            ['南投市', '中寮鄉', '草屯鎮', '國姓鄉', '埔里鎮', '仁愛鄉', '名間鄉', '集集鎮',
                '水里鄉', '魚池鄉', '信義鄉', '竹山鎮', '鹿谷鄉'
            ],
            ['540', '541', '542', '544', '545', '546', '551', '552', '553', '555',
                '556', '557', '558'
            ]
        ],
        嘉義市: [
            ['嘉義市'],
            ['600']
        ],
        嘉義縣: [
            ['番路鄉', '梅山鄉', '竹崎鄉', '阿里山', '中埔鄉', '大埔鄉', '水上鄉',
                '鹿草鄉', '太保市', '朴子市', '東石鄉', '六腳鄉', '新港鄉', '民雄鄉', '大林鎮',
                '溪口鄉', '義竹鄉', '布袋鎮'
            ],
            ['602', '603', '604', '605', '606', '607', '608', '611', '612',
                '613', '614', '615', '616', '621', '622', '623', '624', '625'
            ]
        ],
        雲林縣: [
            ['斗南鎮', '大埤鄉', '虎尾鎮', '土庫鎮', '褒忠鄉', '東勢鄉', '台西鄉', '崙背鄉',
                '麥寮鄉', '斗六市', '林內鄉', '古坑鄉', '莿桐鄉', '西螺鎮', '二崙鄉', '北港鎮',
                '水林鄉', '口湖鄉', '四湖鄉', '元長鄉'
            ],
            ['630', '631', '632', '633', '634', '635', '636', '637', '638', '640', '643',
                '646', '647', '648', '649', '651', '652', '653', '654', '655'
            ]
        ],
        臺南市: [
            ['中西區', '東區', '南區', '北區', '安平區', '安南區', '永康區', '歸仁區', '新化區',
                '左鎮區', '玉井區', '楠西區', '南化區', '仁德區', '關廟區', '龍崎區', '官田區',
                '麻豆區', '佳里區', '西港區', '七股區', '將軍區', '學甲區', '北門區', '新營區',
                '後壁區', '白河區', '東山區', '六甲區', '下營區', '柳營區', '鹽水區', '善化區',
                '大內區', '山上區', '新市區', '安定區'
            ],
            ['700', '701', '702', '704', '708', '709', '710', '711', '712', '713', '714',
                '715', '716', '717', '718', '719', '720', '721', '722', '723', '724', '725',
                '726', '727', '730', '731', '732', '733', '734', '735', '736', '737', '741',
                '742', '743', '744', '745'
            ]
        ],
        高雄市: [
            ['新興區', '前金區', '苓雅區', '鹽埕區', '鼓山區', '旗津區', '前鎮區', '三民區',
                '楠梓區', '小港區', '左營區', '仁武區', '大社區', '東沙群島', '南沙群島', '岡山區', '路竹區', '阿蓮區',
                '田寮區', '燕巢區', '橋頭區', '梓官區', '彌陀區', '永安區', '湖內區', '鳳山區',
                '大寮區', '林園區', '鳥松區', '大樹區', '旗山區', '美濃區', '六龜區', '內門區',
                '杉林區', '甲仙區', '桃源區', '那瑪夏區', '茂林區', '茄萣區'
            ],
            ['800', '801', '802', '803', '804', '805', '806', '807', '811', '812', '813',
                '814', '815', '817', '819', '820', '821', '822', '823', '824', '825', '826', '827', '828',
                '829', '830', '831', '832', '833', '840', '842', '843', '844', '845', '846',
                '847', '848', '849', '851', '852',
            ]
        ],
        澎湖縣: [
            ['馬公市', '西嶼鄉', '望安鄉', '七美鄉', '白沙鄉', '湖西鄉'],
            ['880', '881', '882', '883', '884', '885']
        ],
        屏東縣: [
            ['屏東市', '三地門', '霧台鄉', '瑪家鄉', '九如鄉', '里港鄉', '高樹鄉', '鹽埔鄉',
                '長治鄉', '麟洛鄉', '竹田鄉', '內埔鄉', '萬丹鄉', '潮州鎮', '泰武鄉', '來義鄉',
                '萬巒鄉', '崁頂鄉', '新埤鄉', '南州鄉', '林邊鄉', '東港鎮', '琉球鄉', '佳冬鄉',
                '新園鄉', '枋寮鄉', '枋山鄉', '春日鄉', '獅子鄉', '車城鄉', '牡丹鄉', '恆春鎮',
                '滿州鄉'
            ],
            ['900', '901', '902', '903', '904', '905', '906', '907', '908', '909', '911',
                '912', '913', '920', '921', '922', '923', '924', '925', '926', '927', '928',
                '929', '931', '932', '940', '941', '942', '943', '944', '945', '946', '947'
            ]
        ],
        臺東縣: [
            ['臺東市', '綠島鄉', '蘭嶼鄉', '延平鄉', '卑南鄉', '鹿野鄉', '關山鎮', '海端鄉',
                '池上鄉', '東河鄉', '成功鎮', '長濱鄉', '太麻里', '金峰鄉', '大武鄉', '達仁鄉'
            ],
            ['950', '951', '952', '953', '954', '955', '956', '957', '958', '959', '961',
                '962', '963', '964', '965', '966'
            ]
        ],
        花蓮縣: [
            ['花蓮市', '新城鄉', '秀林鄉', '吉安鄉', '壽豐鄉', '鳳林鎮', '光復鄉', '豐濱鄉',
                '瑞穗鄉', '萬榮鄉', '玉里鎮', '卓溪鄉', '富里鄉'
            ],
            ['970', '971', '972', '973', '974', '975', '976', '977', '978', '979', '981',
                '982', '983'
            ]
        ],
        金門縣: [
            ['金沙鎮', '金湖鎮', '金寧鄉', '金城鎮', '烈嶼鄉', '烏坵鄉'],
            ['890', '891', '892', '893', '894', '896']
        ],
        連江縣: [
            ['南竿鄉', '北竿鄉', '莒光鄉', '東引鄉'],
            ['209', '210', '211', '212']
        ]
    },
    shop_service_contract: '<p>歡迎申請加入『就是行』會員之汽車特店/品牌商會員服務(下稱特約服務)，特約服務，是由『就是行國際科技有限公司』（下稱本公司）所建置提供。為了保護您以及所有使用者的利益，並為服務提供依據，請您詳細閱讀下列各項服務辦法及條款。</p>' +
        '<p>當您申請加入特約服務時，即表示已閱讀、瞭解並同意接受特約服務條款之所有內容，並完全接受特約服務現有與未來衍生的服務項目及內容。本公司有權於任何時間修改或變更本服務條款之內容，修改後的服務條款內容將發送到您的會員收件夾，建議您隨時注意相關調整與修改。您於本服務任何修改或變更後繼續使用時，視為您已閱讀、瞭解並同意接受該等修改或變更。若不同意上述的服務條款修訂或更新方式，或不接受本服務條款的其他任一約定，您應立即停止使用本服務，並通知本公司。</p>' +
        '<p>如您未滿二十歲，請您確認已取得您的監護人/法定代理人的同意，方得註冊為會員、使用或繼續使用特約服務。當您使用或繼續使用特約服務時，即視為您的監護人/法定代理人已閱讀、瞭解並同意接受本同意書及服務條款之所有內容及其後修改變更。</p>' +
        '<p>當您申請加入特約服務時，表示您同意『就是行』將您的會員資料提供給『就是行』之相關汽車特店/品牌商。特約服務僅提供汽車特店/品牌商相關商品/服務等訊息之資訊服務平台，『就是行』不保證汽車特店/品牌商訊息之真實性，亦不保證汽車特店/品牌商履約能力。會員與汽車特店/品牌商所發生之任何糾紛或損失，概由會員與汽車特店/品牌商自行依循正當法律途徑解決與『就是行』無涉，請您參與汽車特店/品牌商活動或交易時審慎評估。<br /> <br /> 隨著市場環境的改變本公司將會不定期修訂網站/APP政策。當我們在使用個人資料的規定上作出大修改時，我們將發送到您的會員收件夾，通知相關事項。您於本服務任何修改或變更後繼續使用時，視為您已閱讀、瞭解並同意接受該等修改或變更。若不同意上述的服務條款修訂或更新方式，或不接受本服務條款的其他任一約定，您應立即停止使用本服務，並通知本公司。</p>' +
        '<p>本同意書及服務條款以中文為準，其解釋、補充及適用均以中華民國法令為準據法(但涉外民事法律適用法，不在適用之列)。會員約定條款中任何條款之全部或一部份無效時，不影響其他約定之效力。<br />因特約服務條款所發生之訴訟，以台灣台北地方法院為第一審管轄法院。</p>',
    shop_cooperation_agreement_title: '就是行行銷科技平台特約店商合作協議',
    shop_cooperation_agreement: '<p>立約人：</p>' +
        '<p><b><span class="sd_shopname"></span></b>（以下稱「甲方」） <br /> 就是行國際科技有限公司 （以下稱「乙方」） <br /> <br /> 緣甲方擬提供商品/服務於乙方所開設之『就是行』網路平台(下稱『就是行』)進行銷售、推廣，雙方同意依下列條款進行合作：</p>' +
        '<p>第一條：合約生效 <br />一、 本合約自雙方簽署後立即生效，有效期間自西元（以下同） 年 月 日起至 年 月 日止，為期 年。本合約屆滿前一個月，任一方未以書面向他方為終止合作之意思表示者，本合約自動延展一年，其後亦同。 <br />二、 於本合約簽署後，本合約之所有內容即自動取代甲乙雙方之前所簽訂之任何書面合約或口頭所議定之內容。 </p>' +
        '<p>第二條：合作事項<br />一、 甲方提供符合乙方要求之商品/服務，由乙方銷售或推廣。合約有效期間除特殊個別商品/服務得另為協議排除本約相關條款外，其餘交易均依本約規定。<br />二、 報酬支付並依下列方式進行：<br />(一)每筆交易完成服務10天後，甲方依乙方確認之金額，提供之商品/服務所依法開立服務勞務費用或商品銷售費發票向乙方請款。乙方於結帳支付日支付報酬給甲方(結帳支付日:每月1～10日為前期結帳日，11～20日為中期結帳日，21～31日為後期結帳日,前期結帳支付日為當月份18日、中期結帳支付日為當月份28日、後期結帳支付日為下月份8日。)<br />(二)若遇消費者要求全部或部分退款時，乙方將直接以消費者退貨比例計算退款金額，從應於支付甲方之款項扣除。<br /> 三、 訂貨、交貨及退換貨<br />(一)乙方完成商品上檔確認後，甲方即不得任意以任何理由(包含但不限於貨量不足、生產不及、服務人手不足等)要求檔次臨時下架。<br />(二)甲方之商品/服務報價單或商品/服務上架資料所列之交易條件均包含所有稅捐、商品運送至乙方所指定物流中心或直接出貨至消費者手中之費用、商品可直接出貨之內外包裝費用及其他一切費用或負擔。<br />(三)甲方於出貨或服務完成後，有義務將所有相關顧客簽名之確認出貨/服務單繳回乙方，該筆訂單才會被認定為順利出貨完成。<br />(四)甲方除徵得乙方同意外，不得任意取消或變更已生效之訂單。乙方得受消費者之請求隨時取消訂單與退貨。<br />(五)甲方應負責提供商品/服務適當之售後服務，並履行其他於消費者購買商品/服務時承諾之所有服務與責任。</p>' +
        '<p>第三條：商品/服務保證 <br />一、甲方應保證其商品/服務（包含但不限於商品服務內容、產品配方、包裝標示、產地標示等提供乙方之一切資料）符合中華民國政府法令之一切規定，且商品具備正常品質及合理保存（保固）期限，非瑕疵品、仿冒品或不法商品，亦無侵害他人智慧財產權、專利權或其他權益。乙方如需之商標授權書、產品檢驗證明、中華民國政府核准證明及其他相關文件，由甲方負責提供。 <br />二、甲方保證提供之商品/服務規格、照片、說明等，應與其實際給予乙方及乙方之消費者之商品/服務相符，不得有誇大不實或明顯差異之情形，並應符合消費者保護法、公平交易法、商品標示法、商品檢驗法、個人資料處理法及其他政府相關法令規定。<br />三、甲方需保證擁有所有提供乙方使用之文案與照片之智慧財產權。 <br />四、若消費者因購買或使用甲方所提供之商品/服務，致消費爭議或有使消費者或乙方受有損害時，甲方應負責處理解決一切衍生之事宜，並承擔一切責任及損失。<br />五、甲方同意乙方(包括乙方之消費者)於?七日內可無條件將商品/服務退回。倘乙方客戶收受標的商品發現有瑕疵後，通知乙方另行更換無瑕疵商品，經乙方通知甲方後，甲方應於三日內至乙方客戶處更換無瑕疵商品，取回商品之風險及相關費用由甲方自行負擔。<br />六、甲方如有違反本合約、中華民國法律、國際貿易法律或規約、隱私權保護等相關規定之虞，乙方得終止合約並請求損害賠償。</p>' +
        '<p>第四條：會員個人資料保護 <br />一、『就是行』之會員個人資料，係由乙方進行蒐集、處理及利用，甲方僅得於商品/服務之提供、交易、溝通問答範圍內進行個人資料之利用，並應與自己之客戶資料獨立、分離，不得將會員個人資料為『就是行』以外之行銷行為或利用。 <br />二、甲方須遵守「個人資料保護法」及相關法令，對於乙方所提供之消費者個人資料，採取適當之安全措施，如有委託物流業者提供服務者，亦僅得於必要範圍內提供物流服務所需之個人資料，且應以契約要求物流業者須遵守「個人資料保護法」及相關法令，以及採取適當之安全措施，以維護個人資料之安全，避免違法蒐集、處理及利用個人資料之情事發生。甲方應自行定期進行個人資料安全維護管理之檢查，並留存相關記錄，如有必要，乙方得至甲方進行查核。 <br />三、本合約期間終止或屆滿後，甲方應依乙方之指示刪除所有消費者個人資料，不得以任何形式留存副本。如有售後服務之需求者，甲方同意以乙方所留存之交易資訊為準。 <br />四、若甲方違反「個人資料保護法」及相關法令或違反本合約者，致生個人資料爭議者，甲方應即時通報乙方，並由乙方協助進行處理，惟甲方應自行負擔相關民、刑事或行政責任。 </p>' +
        '<p>第五條：保密義務 <br />一、甲方因本合約執行所知悉或持有乙方之機密資訊，包括但不限於：本合約內容、消費者個人資料、財務資料、乙方網站後台系統或其他未經公開揭露之資訊，應盡善良管理人之注意義務進行保密，除依本合約規定為利用外，非經乙方事前書面同意，不得自行利用或洩漏予任何第三人。如有對其員工、顧問或其認為有必要之第三人揭露者，應使該等受揭露之對象受與本合約相當之保密義務。 <br />二、本條約定不因本合約之解除、終止或屆滿而失其效力。乙方得隨時以書面通知甲方刪除或返還乙方機密資訊。 </p>' +
        '<p>第六條：違約及終止 <br />一、甲方不得給予乙方人員任何佣金、回扣，如經乙方查覺甲方違反本項約定，除將該佣金、回扣視同甲方給予乙方等值之折讓予以追償，或由乙方自應付甲方之貨款中逕予以扣除外，違法部分另依法辦理。<br />二、除雙方因有約定者外，本合約之終止或屆滿，不影響雙方於終止或屆滿前已發生之權利義務。</p>' +
        '<p>第七條：合約書<br />凡因本契約所產生糾紛，乙方有最終解釋權。本合約如有未盡事宜，得經雙 方協議後以書面修訂之。本合約之條款，如一部無效或無法執行，不影響除去該部份仍可成立之其他條款之效力。本合約之所有事項，係經雙方完全合意，並自雙方均簽署時起生效。<br /> 本合約書以中華民國法律為準據法(但涉外民事法律適用法，不在適用之列)。倘有任何由本合約所生或與本合約有關之紛爭，一方應在收到他方通知解決該紛爭之翌日起三十日內，雙方應先以保密及誠信原則進行協商解決紛爭，如無法在前述期間內達成，應以訴訟解決之。雙方因本合約所引起或與本合約有關之紛爭，合意以台灣台北法院為第一審管轄法院。</p>'
}