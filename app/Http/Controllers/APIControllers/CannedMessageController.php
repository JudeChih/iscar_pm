<?php

namespace App\Http\Controllers\APIControllers;

use App\Http\Controllers\Controller;

class CannedMessageController extends Controller {

        /** querycannedmessagelist  查詢罐頭訊息清單 * */
        function querycannedmessagelist() {
               $QueryCannedMessageList = new CannedMessage\QueryCannedMessageList;
               return $QueryCannedMessageList->querycannedmessagelist();
        }
        
         /** modifycannedmessage  異動罐頭訊息資料 * */
         function modifycannedmessage() {
               $ModifyCannedMessage = new CannedMessage\ModifyCannedMessage;
               return $ModifyCannedMessage->modifycannedmessage();
        }

        
}
