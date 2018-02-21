<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head></head>
    <body>
        <p>您好! 特約商 : {!! $scg_data[0]['sd_shopname'] !!}</p>
        <p>{!! $scg_data[0]['content'] !!}</p>
        <p></p>
        <p>特店代碼 :  {!! $scg_data[0]['sd_salescode'] !!}</p>
        <p>商品名稱 :  {!! $scg_data[0]['scm_title'] !!}</p>
        <p>店家電話 :  {!! $scg_data[0]['sd_shoptel'] !!}</p>
        <p>訂單編號 :  {!! $scg_data[0]['scg_id'] !!}</p>
        <p>購買數量 :  {!! $scg_data[0]['scg_buyamount'] !!}</p>
        <p>結帳金額 :  {!! $scg_data[0]['scg_totalamount'] !!}</p>
        <p>預約時間 :  {!! $scg_data[0]['scr_rvdate_time'] !!}</p>
        <p></p>
        <p>※ 如果您不曾提出isCar 就是行的特約商註冊申請，請您直接刪除本信，抱歉造成您的困擾！</p><br />
        <p></p>
        <p>isCar 就是行 服務團隊 敬上</p>
        <br/>
        <br>────────────────</br>
        <br>isCar 就是行</br>
        <br><a href='http://www.sunwai.com'target='blank'>http://www.sunwai.com</a></br>
        <br>翔偉資安</br>
        <br>────────────────</br>
        <img src="{{ $message->embed('img/iscar_logo_orange.png') }}" />
    </body>
</html>