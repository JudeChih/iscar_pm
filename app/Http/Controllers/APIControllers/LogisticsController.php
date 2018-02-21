<?php

namespace App\Http\Controllers\APIControllers;
use App\Http\Controllers\Controller;
class LogisticsController extends Controller {

    /** printdeliverorder	供商家批量列印時間區間內所有未執行之物流單據 * */
    function printdeliverorder() {
         $PrintDeliverOrder = new Logistics\PrintDeliverOrder;
         return $PrintDeliverOrder->printdeliverorder();
    }

    /** queryordercontent	供商家批量列印時間區間內所有未執行之物流單據 * */
    function queryordercontent() {
         $QueryOrderContent = new Logistics\QueryOrderContent;
         return $QueryOrderContent->queryordercontent();
    }

    /** queryorderlist	供商家查看所有訂單資料列表 **/
    function queryorderlist() {
         $QueryOrderlist = new Logistics\QueryOrderlist;
         return $QueryOrderlist->queryorderlist();
    }

    /** querysclId	 接收傳入之物流ID是否存在及仍有效 scl_iD **/
    function querysclId() {
         $QuerySclId = new Logistics\QuerySclId;
         return $QuerySclId->querysclId();
    }

    /** reportcargoarrive	接收QR內容,驗證SCG及物流單號無誤,更新訂單狀態為已到貨 **/
    function reportcargoarrive() {
         $ReportCarGoArrive = new Logistics\ReportCarGoArrive;
         return $ReportCarGoArrive->reportcargoarrive();
    }

    /** reportcargopack	供商家用戶回報實體商品出貨狀態 **/
    function reportcargopack() {
         $ReportCarGoPack = new Logistics\ReportCarGoPack;
         return $ReportCarGoPack->reportcargopack();
    }

    /** reportsentlogistics 供商家回報商品出貨之物流單號 **/
    function reportsentlogistics() {
         $ReportSentLogistics = new Logistics\ReportSentLogistics;
         return $ReportSentLogistics->reportsentlogistics();
    }

    /** updatescgpayment	scl，scg更新付款狀態 改以付款 **/
    function updatescgpayment() {
         $UpdateScgPayment = new Logistics\UpdateScgPayment;
         return $UpdateScgPayment->updatescgpayment();
    }
/** updateshopdatapayment  修改商家的綁定金流資料* */
     function updateshopdatapayment() {
         $UpdateShopDataPayment = new Logistics\UpdateShopDataPayment;
         return $UpdateShopDataPayment->updateshopdatapayment();
    }
/** createlogisticsdata  建立物流資料 * */
    function createlogisticsdata() {
         $CreateLogisticsData = new Logistics\CreateLogisticsData;
         return $CreateLogisticsData->createlogisticsdata();
    }
/** createpaymentflow  建立呼叫金流JSON資料 * */
    function createpaymentflow() {
         $CreatePaymentFlow = new Logistics\CreatePaymentFlow;
         return $CreatePaymentFlow->createpaymentflow();
    }
/** getpaymentrespone  接收並處理由iscar_app的redirectpaymentdata API 回傳的金流資料 * */
    function getpaymentrespone($value) {
         $GetPaymentRespone = new Logistics\GetPaymentRespone;
         return $GetPaymentRespone->getpaymentrespone($value);
    }
/** paysuccesstopush  成功建立金流資料後 推播給商家跟消費者* */
     function paysuccesstopush() {
         $PaySuccessToPush = new Logistics\PaySuccessToPush;
         return $PaySuccessToPush->paysuccesstopush();
    }
/** refundpayment  退費* */
    function refundpayment() {
         $RefundPayment = new Logistics\RefundPayment;
         return $RefundPayment->refundpayment();
    }
/** paymentcancel  取消付款* */
    function paymentcancel() {
         $PaymentCancel = new Logistics\PaymentCancel;
         return $PaymentCancel->paymentcancel();
    }

}
