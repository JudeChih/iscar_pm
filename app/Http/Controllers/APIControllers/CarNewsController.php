<?php

namespace App\Http\Controllers\APIControllers;

use App\Http\Controllers\Controller;

class CarNewsController extends Controller {

        /** queryusedcarlist	列表顯示二手車刊登項目查詢結果 * */
        function queryusedcarlist() {
               $QueryUsedCarList = new CarNews\QueryUsedCarList;
               return $QueryUsedCarList->queryusedcarlist();
        }
        /** queryusedcarcontent	按傳入ID取用車輛內容回傳 * */
        function queryusedcarcontent() {
               $QueryUsedcarContent = new CarNews\QueryUsedcarContent;
               return $QueryUsedcarContent->queryusedcarcontent();
        }
        /** postusedcardata	新增車輛資料* */
        function postusedcardata() {
               $PostUsedCarData = new CarNews\PostUsedCarData;
               return $PostUsedCarData->postusedcardata();
        }
        /** payusedcarpost	刊登物件付費扣點功能* */
        function payusedcarpost() {
               $PayUsedCarPost = new CarNews\PayUsedCarPost;
               return $PayUsedCarPost->payusedcarpost();
        }
        /** postshopdata	開店申請 * */
        function postshopdata() {
               $PostShopData = new CarNews\PostShopData;
               return $PostShopData->postshopdata();
        }
        /** putcarreservationans	新增車輛約看回覆敲定記錄 * */
        function putcarreservationans() {
               $PutCarreservationAns = new CarNews\PutCarreservationAns;
               return $PutCarreservationAns->putcarreservationans();
        }
        /** 查詢車輛約看詢問記錄 **/
        function querycarreservationask() {
               $QueryCarreservationAsk = new CarNews\QueryCarreservationAsk;
               return $QueryCarreservationAsk->querycarreservationask();
        }
        /** 新增車輛約看詢問記錄 **/
        function postcarreservationask() {
               $PostCarreservationAsk = new CarNews\PostCarreservationAsk;
               return $PostCarreservationAsk->postcarreservationask();
        }
        /** 賣方查詢買方車輛約看詢問記錄 **/
         function querycarreservationfullinfo() {
               $QueryCarreservationFullinfo = new CarNews\QueryCarreservationFullinfo;
               return $QueryCarreservationFullinfo->querycarreservationfullinfo();
        }
         /** 查詢用戶所有約看記錄 **/
         function recorvercarresvation() {
               $RecorverCarresvation = new CarNews\RecorverCarresvation;
               return $RecorverCarresvation->recorvercarresvation();
         }
}
