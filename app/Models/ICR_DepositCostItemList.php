<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家基本資料
 */
class ICR_DepositCostItemList extends Model {

    //
    public $table = 'icr_depositcostitemlist';
    public $primaryKey = 'dcil_id';
    public $timestamps = false;
    public $incrementing = false;

     /**
     * ██████████▍CREATE 建立資料
     */
     
    /**
     * ██████████▍READ 讀取資料
     */
     
      /**
     * 取得資料，使用「dcil_id」和「dcil_category」
     * @param type $dcil_id
     * @param type $dcil_category
     * @return type
     */
    public static function GetDataByDCILID_DCILCATEGORY($dcil_id, $dcil_category) {
        try {

            if ($dcil_id == null || strlen($dcil_id) == 0 || $dcil_category == null || strlen($dcil_category) == 0) {
                return null;
            }

            $querydata = ICR_DepositCostItemList::where('isflag', '1')
                    ->where('icr_depositcostitemlist.dcil_id','=', $dcil_id)
                    //->where('icr_depositcostitemlist.dcil_category', $dcil_category)
                    ->get()
                    ->toArray();

            return $querydata;
        } catch (\Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    public static function GetDataByDCILID($dcil_id) {
        try {

            if ($dcil_id == null || strlen($dcil_id) == 0 ) {
                return null;
            }

            $querydata = ICR_DepositCostItemList::where('isflag', '1')
                    ->where('icr_depositcostitemlist.dcil_id','=', $dcil_id)
                    ->get()
                    ->toArray();

            return $querydata;
        } catch (\Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    
    /**
     * ██████████▍UPDATE 更新資料
     */

   

    /**
     * ██████████▍DELETE 刪除資料
     */
    /**
     * ██████████▍CHECK 檢查資料
     */
}
