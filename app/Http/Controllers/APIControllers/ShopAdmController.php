<?php

namespace App\Http\Controllers\APIControllers;
use App\Http\Controllers\Controller;
class ShopAdmController extends Controller {

    /**
     * queryshoplist 取用對應類別商家資料列表回覆
     */
    function queryshoplist() {
        $QueryShopList = new ShopAdm\QueryShopList;
        return $QueryShopList->queryshoplist();
    }

    /**
     * queryshopcontent 取用商家內容
     */
    function queryshopcontent() {
        $QueryShopContent = new ShopAdm\QueryShopContent;
        return $QueryShopContent->queryshopcontent();
    }

    /**
     * modifyshopdata 停用/啟用/刪除特約商
     */
    function modifyshopdata() {
        $ModifyShopData = new ShopAdm\ModifyShopData;
        return $ModifyShopData->modifyshopdata();
    }

    /**
     * queryshoptype 取得特約商各類別的統計數(未綁定跟已綁定分開)
     */
    function queryshoptype() {
        $QueryShopType = new ShopAdm\QueryShopType;
        return $QueryShopType->queryshoptype();
    }

}
