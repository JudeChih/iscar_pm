<?php

namespace App\Http\Controllers\APIControllers;
use App\Http\Controllers\Controller;
class SettleMentrecController extends Controller {

   /** query_shopsettlementrec_d_list   取得該店家銷售結算子表清單 * */
    function query_shopsettlementrec_d_list() {
         $Query_ShopSettleMentrec_D_List = new ShopSettleMentrec\Query_ShopSettleMentrec_D_List;
         return $Query_ShopSettleMentrec_D_List->query_shopsettlementrec_d_list();
    }

    /** query_shopsettlementrec_m	查詢「商家銷售結算主表」列表 * */
    function query_shopsettlementrec_m() {
         $Query_ShopSettleMentrec_M = new ShopSettleMentrec\Query_ShopSettleMentrec_M;
         return $Query_ShopSettleMentrec_M->query_shopsettlementrec_m();
    }

    /** query_shopsettlementrec_m_list	查詢「商家銷售結算主表清單」列表 * */
    function query_shopsettlementrec_m_list() {
         $Query_ShopSettleMentrec_M_List = new ShopSettleMentrec\Query_ShopSettleMentrec_M_List;
         return $Query_ShopSettleMentrec_M_List->query_shopsettlementrec_m_list();
    }

   /** update_settlementview	更新店家覆核狀態 * */
    function update_settlementview() {
         $Update_SettleMentView= new ShopSettleMentrec\Update_SettleMentView;
         return $Update_SettleMentView->update_settlementview();
    }

    /** execute_shopsettlement   執行店家結算功能 * */
    function execute_shopsettlement() {
         $Execute_ShopSettleMent= new ShopSettleMentrec\Execute_ShopSettleMent;
         return $Execute_ShopSettleMent->execute_shopsettlement();
    }

 


}
