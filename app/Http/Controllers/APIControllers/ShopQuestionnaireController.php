<?php

namespace App\Http\Controllers\APIControllers;
use App\Http\Controllers\Controller;

class ShopQuestionnaireController extends Controller {

    /** shopquestionnaire_read	合作社問卷內容讀取 * */
    function shopquestionnaire_read() {
        $ShopQuestionnaire_Read = new ShopQuestionnaire\ShopQuestionnaire_Read;
        return $ShopQuestionnaire_Read -> shopquestionnaire_read();
    }

    /** shopquestionnaire_ans	合作社問卷答覆接收 * */
    function shopquestionnaire_ans() {
        $ShopQuestionnaire_Ans = new ShopQuestionnaire\ShopQuestionnaire_Ans;
        return $ShopQuestionnaire_Ans -> shopquestionnaire_ans();
    }

    /** shopquestionnaire_result	合作社問卷答覆結果查看 * */
    function shopquestionnaire_result() {
        $ShopQuestionnaire_Result = new ShopQuestionnaire\ShopQuestionnaire_Result;
        return $ShopQuestionnaire_Result -> shopquestionnaire_result();
    }

    /** shopquestionnaire_response	合作社商家回覆問卷留言 * */
    function shopquestionnaire_response() {
        $ShopQuestionnaire_Response = new ShopQuestionnaire\ShopQuestionnaire_Response;
        return $ShopQuestionnaire_Response -> shopquestionnaire_response();
    }

}
