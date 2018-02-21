<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class ICR_ShopBonusStock extends Model {

    protected $table = 'icr_shopbonusstock';
    public $timestamps = false;                 
    public $incrementing = false;

    /**
     * 新增資料
     * @param   $arraydata
     * @return 	Boolean
     */
    public static function InsertData($arraydata) {

        try {

            if (!Commontools::CheckArrayValue($arraydata, "sbs_yymm") || !Commontools::CheckArrayValue($arraydata, "md_id") || !Commontools::CheckArrayValue($arraydata, "sbs_type")) {
                return false;
            }       
            
            $savedata['sbs_yymm'] = $arraydata['sbs_yymm'];
            $savedata['md_id'] = $arraydata['md_id'];
            $savedata['sbs_type'] = $arraydata['sbs_type'];

            if (Commontools::CheckArrayValue($arraydata, "sbs_sd_id_link")) {
                $savedata['sbs_sd_id_link'] = $arraydata["sbs_sd_id_link"];
            } else {
                $savedata['sbs_sd_id_link'] = 0;
            }

            if (Commontools::CheckArrayValue($arraydata, "sbs_begin")) {
                $savedata['sbs_begin'] = $arraydata["sbs_begin"];
            } else {
                $savedata['sbs_begin'] = 0;
            }
            if (Commontools::CheckArrayValue($arraydata, "sbs_add")) {
                $savedata['sbs_add'] = $arraydata["sbs_add"];
            } else {
                $savedata['sbs_add'] = 0;
            }
            if (Commontools::CheckArrayValue($arraydata, "sbs_use")) {
                $savedata['sbs_use'] = $arraydata["sbs_use"];
            } else {
                $savedata['sbs_use'] = 0;
            }
            if (Commontools::CheckArrayValue($arraydata, "sbs_return")) {
                $savedata['sbs_return'] = $arraydata["sbs_return"];
            } else {
                $savedata['sbs_return'] = 0;
            }
            if (Commontools::CheckArrayValue($arraydata, "sbs_end")) {
                $savedata['sbs_end'] = $arraydata["sbs_end"];
            } else {
                $savedata['sbs_end'] = 0;
            }

            if (Commontools::CheckArrayValue($arraydata, "sbs_add_date")) {
                $savedata['sbs_add_date'] = $arraydata["sbs_add_date"];
            }
            if (Commontools::CheckArrayValue($arraydata, "sbs_use_date")) {
                $savedata['sbs_use_date'] = $arraydata["sbs_use_date"];
            }
            if (Commontools::CheckArrayValue($arraydata, "sbs_begin_date")) {
                $savedata['sbs_begin_date'] = $arraydata["sbs_begin_date"];
            }
            if (Commontools::CheckArrayValue($arraydata, "sbs_end_date")) {
                $savedata['sbs_end_date'] = $arraydata["sbs_end_date"];
            }


            if (Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
            }

            if (Commontools::CheckArrayValue($arraydata, "create_user")) {
                $savedata['create_user'] = $arraydata['create_user'];
            } else {
                $savedata['create_user'] = 'webapi';
            }
            if (Commontools::CheckArrayValue($arraydata, "last_update_user")) {
                $savedata['last_update_user'] = $arraydata['last_update_user'];
            } else {
                $savedata['last_update_user'] = 'webapi';
            }

            DB::table('icr_shopbonusstock')->insert($savedata);
            return true;
        } catch (Exception $e) {
            \App\models\ErrorLog::InsertData($e);
            return false;
        }
    }

    /**
     * 修改資料
     * @param type $arraydata
     * @return boolean
     */
    public static function UpdateData($arraydata) {

        try {
        if (!Commontools::CheckArrayValue($arraydata, "sbs_yymm") || !Commontools::CheckArrayValue($arraydata, "md_id") || !Commontools::CheckArrayValue($arraydata, "sbs_type")
        /* || !Commontools::CheckArrayValue($arraydata, "cos_fdid_link") */
        ) {
            return false;
        }

        $savedata['sbs_yymm'] = $arraydata['sbs_yymm'];
        $savedata['md_id'] = $arraydata['md_id'];
        $savedata['sbs_type'] = $arraydata['sbs_type'];
        

        if (Commontools::CheckArrayValue($arraydata, "sbs_begin")) {
            $savedata['sbs_begin'] = $arraydata["sbs_begin"];
        }
        if (Commontools::CheckArrayValue($arraydata, "sbs_add")) {
            $savedata['sbs_add'] = $arraydata["sbs_add"];
        }
        if (Commontools::CheckArrayValue($arraydata, "sbs_use")) {
            $savedata['sbs_use'] = $arraydata["sbs_use"];
        }
        if (Commontools::CheckArrayValue($arraydata, "sbs_return")) {
            $savedata['sbs_return'] = $arraydata["sbs_return"];
        }
        if (Commontools::CheckArrayValue($arraydata, "sbs_end")) {
            $savedata['sbs_end'] = $arraydata["sbs_end"];
        }

        if (Commontools::CheckArrayValue($arraydata, "sbs_add_date")) {
            $savedata['sbs_add_date'] = $arraydata["sbs_add_date"];
        }
        if (Commontools::CheckArrayValue($arraydata, "sbs_use_date")) {
            $savedata['sbs_use_date'] = $arraydata["sbs_use_date"];
        }
        if (Commontools::CheckArrayValue($arraydata, "sbs_begin_date")) {
            $savedata['sbs_begin_date'] = $arraydata["sbs_begin_date"];
        }
        if (Commontools::CheckArrayValue($arraydata, "sbs_end_date")) {
            $savedata['sbs_end_date'] = $arraydata["sbs_end_date"];
        }
        if(Commontools::CheckArrayValue($arraydata, "sbs_sd_id_link")) {
            $savedata['sbs_sd_id_link'] = $arraydata['sbs_sd_id_link'];
        } else {
                $savedata['sbs_sd_id_link'] = 0;
        }


        if (Commontools::CheckArrayValue($arraydata, "isflag")) {
            $savedata['isflag'] = $arraydata['isflag'];
        }

        $savedata['last_update_user'] = 'webapi';
         
        return DB::table('icr_shopbonusstock')
                        ->where('sbs_yymm', $savedata['sbs_yymm'])
                        ->where('md_id', $savedata['md_id'])
                        ->where('sbs_sd_id_link', $savedata['sbs_sd_id_link'])
                        ->where('sbs_type', $savedata['sbs_type'])
                        ->update($savedata); 
        } catch (\Exception $e) {
          ErrorLog::InsertData($e);
          return false;
        }
    }

    /**
     * 取得資料，使用「md_id」
     * @param type $md_id
     * @return type
     */
    public static function GetData($md_id) {
        if ($md_id == null || strlen($md_id) == 0) {
            return null;
        }

        ICR_ShopBonusStock::CheckAndCreateStockData($md_id);

        $querydata = ICR_ShopBonusStock::where('isflag', '1')
                ->where('md_id', $md_id)
                ->where('sbs_yymm', date('Ym'))
                ->get()
                ->toArray();

        return $querydata;
    }

    /**
     * 取得資料，使用「md_id」和「cos_type」
     * @param type $md_id
     * @param type $cos_type
     * @return type
     */
    public static function GetStockByMDID_COSType($md_id, $sbs_type) {
        try {

            if ($md_id == null || strlen($md_id) == 0 /*|| $cos_type == null || strlen($cos_type) == 0*/) {
                return null;
            }

            ICR_ShopBonusStock::CheckAndCreateStockDataQ($md_id);

            $querydata = ICR_ShopBonusStock::where('isflag', '1')
                    ->where('md_id', $md_id)
                    ->where('sbs_type', $sbs_type)
                    ->where('sbs_yymm', date('Ym'))
                    ->get()
                    ->toArray();

            return $querydata;
        } catch (\Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 取得資料
     * @param type $md_id
     * @param type $fd_id
     * @return type
     */
    public static function GetStockFactoryByMDID_FDID($md_id, $sd_id) {
        try {
            if ($md_id == null || strlen($md_id) == 0 || $sd_id == null || strlen($sd_id) == 0) {
                return null;
            }

            ICR_ShopBonusStock::CheckAndCreateStockData($md_id);

            $querydata = ICR_ShopBonusStock::where('isflag', '1')
                    ->where('md_id', $md_id)
                    ->where('sbs_type', '2')
                    ->where('sbs_sd_id_link', $sd_id)
                    ->where('sbs_yymm', date('Ym'))
                    ->get()
                    ->toArray();

            return $querydata;
        } catch (\Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 依「MD_ID」取得當下庫存資料
     * @param   $arraydata
     * @return Boolean
     */
    public static function GetStockByMDID($md_id, &$coin, &$bonus) {
        try {

            $querydata = ICR_ShopBonusStock::GetStockByMDID_COSType($md_id, '0');
            if (!is_null($querydata) || count($querydata) != 0) {
                $coin = $querydata[0]['sbs_end'];
            }

            $querydata = ICR_ShopBonusStock::GetStockByMDID_COSType($md_id, '1');
            if (!is_null($querydata) || count($querydata) != 0) {
                $bonus = $querydata[0]['sbs_end'];
            }

            return true;
        } catch (Exception $ex) {
            $coin = 0;
            $bonus = 0;
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 更新庫存資料
     * @param type $md_id 會員代碼
     * @param type $cos_type 庫存類別：0＝一般代幣 coin、1＝活動紅利 bonus
     * @param type $modifytype   異動類別 ：'add'、'use'、'return'、
     * @param type $modifycoid 異動點數
     * @param type $cos_end  異動後庫存點數
     * @return boolean
     */
    public static function UpdateStock($md_id, $sbs_type, $modifytype, $modifycoid, &$cos_end) {

        if (is_null($md_id) || count($md_id) == 0 || is_null($sbs_type) || count($sbs_type) == 0 || is_null($modifytype) || count($modifytype) == 0 || is_null($modifycoid) || count($modifycoid) == 0) {
            return false;
        }
        //取得庫 存資料
        $querydata = ICR_ShopBonusStock::GetStockByMDID_COSType($md_id, $sbs_type);

        if (is_null($querydata) || count($querydata) == 0) {
            return false;
        }  

         if (strcmp($modifytype, 'add') == 0 ) {

            $querydata[0]['sbs_add'] = $querydata[0]['sbs_add'] + $modifycoid;
            $querydata[0]['sbs_add_date'] = date('Y-m-d H:i:s');
        } else if (strcmp($modifytype, 'use') == 0 ) {

            $querydata[0]['sbs_use'] = $querydata[0]['sbs_use'] + $modifycoid;
            $querydata[0]['sbs_use_date'] = date('Y-m-d H:i:s');
        } else if (strcmp($modifytype, 'return') == 0 ) {

            $querydata[0]['sbs_return'] = $querydata[0]['sbs_return'] + $modifycoid;
            $querydata[0]['sbs_add_date'] = date('Y-m-d H:i:s');
        } else {
            return false;
        }

        $querydata[0]['sbs_end_date'] = date('Y-m-d H:i:s');
        $querydata[0]['sbs_end'] = $querydata[0]['sbs_begin'] + $querydata[0]['sbs_add'] - $querydata[0]['sbs_use'] + $querydata[0]['sbs_return'];
        $cos_end = $querydata[0]['sbs_end'];
        return ICR_ShopBonusStock::UpdateData($querydata[0]); 
    }

    /**
     * 更新庫存資料
     * @param type $md_id 會員代碼
     * @param type $fd_id 廠商代號
     * @param type $modifytype   異動類別 ：'add'、'use'、'return'、
     * @param type $modifycoid 異動點數
     * @param type $cos_end  [ 回傳 ] 異動後庫存點數
     * @return boolean
     */
    public static function UpdateStockFactory($md_id, $fd_id, $modifytype, $modifycoid, &$sbs_end) {

        try {

            if (is_null($md_id) || count($md_id) == 0 || is_null($fd_id) || count($fd_id) == 0 || is_null($modifytype) || count($modifytype) == 0 || is_null($modifycoid) || count($modifycoid) == 0) {
                return false;
            }
            //取得庫 存資料
            $querydata = ICR_ShopBonusStock::GetStockFactoryByMDID_FDID($md_id, $fd_id);

            if (is_null($querydata) || count($querydata) == 0) {
                return false;
            }
            if (strcmp($modifytype, 'add') == 0) {

                $querydata[0]['sbs_add'] = $querydata[0]['sbs_add'] + $modifycoid;
                $querydata[0]['sbs_add_date'] = date('Y-m-d H:i:s');
            } else if (strcmp($modifytype, 'use') == 0) {
                $querydata[0]['sbs_use'] = $querydata[0]['sbs_use'] + $modifycoid;
                $querydata[0]['sbs_use_date'] = date('Y-m-d H:i:s');
            } else if (strcmp($modifytype, 'return') == 0) {

                $querydata[0]['sbs_return'] = $querydata[0]['sbs_return'] + $modifycoid;
                $querydata[0]['sbs_add_date'] = date('Y-m-d H:i:s');
            } else {
                return false;
            }

            $querydata[0]['sbs_end_date'] = date('Y-m-d H:i:s');
            $querydata[0]['sbs_end'] = $querydata[0]['sbs_begin'] + $querydata[0]['sbs_add'] - $querydata[0]['sbs_use'] + $querydata[0]['sbs_return'];
            $sbs_end = $querydata[0]['sbs_end'];
            ICR_ShopBonusStock::UpdateData($querydata[0]);
            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 更新期初庫存資料
     * @param $md_id    會員代碼
     * @param $cos_type 庫存類別
     * @param $cos_begin  期初庫存數
     *
     * @return Boolean 執行結果
     */
    private static function CreateStock($md_id, $sbs_type, $sbs_begin) {

        if (is_null($md_id) || strlen($md_id) == 0 || is_null($sbs_type) || strlen($sbs_type) == 0 || is_null($sbs_begin) || strlen($sbs_begin) == 0) {
            return false;
        }
        $month = date('Ym');

        $arraydata = array('sbs_yymm' => $month, 'md_id' => $md_id, 'sbs_type' => $sbs_type, 'sbs_begin' => $sbs_begin
            , 'sbs_add' => '0', 'sbs_use' => '0', 'sbs_return' => '0', 'sbs_end' => $sbs_begin, 'sbs_begin_date' => date("Y-m-d H:i:s"));

        return ICR_ShopBonusStock::InsertData($arraydata);
    }

    /**
     * 更新期初庫存資料
     * @param $md_id    會員代碼
     * @param $cos_type 庫存類別
     * @param $cos_begin  期初庫存數
     *
     * @return Boolean 執行結果
     */
    private static function CreateStockFactory($md_id, $sbs_type, $sd_id, $sbs_begin) {

        if (is_null($md_id) || strlen($md_id) == 0 || is_null($sbs_type) || strlen($sbs_type) == 0 || is_null($sbs_begin) || strlen($sbs_begin) == 0) {
            return false;
        }
        $month = date('Ym');

        $arraydata = array('sbs_yymm' => $month, 'md_id' => $md_id, 'sbs_type' => $sbs_type, 'sbs_sd_id_link' => $sd_id, 'sbs_begin' => $sbs_begin
            , 'sbs_add' => '0', 'sbs_use' => '0', 'sbs_return' => '0', 'sbs_end' => $sbs_begin, 'sbs_begin_date' => date("Y-m-d H:i:s"));

        return ICR_ShopBonusStock::InsertData($arraydata);
    }

    /**
     * 取得該用戶庫存最後一筆記錄
     * @param $md_id    會員代碼
     * @param $cos_type 庫存類別
     * @return Object 查詢後的結果
     */
    public static function GetLastStock($md_id, $sbs_type) {

        if (is_null($md_id) || strlen($md_id) == 0 || is_null($sbs_type) || strlen($sbs_type) == 0) {
            return false;
        }

        $results = ICR_ShopBonusStock::where('isflag', '1')
                ->where('md_id', $md_id)
                ->where('sbs_type', $sbs_type)
                ->orderBy('sbs_yymm', 'desc')
                ->first();

        //->get();

        return $results;
    }

    /**
     * 取得該用戶庫存最後一筆記錄
     * @param $md_id    會員代碼
     * @param $cos_type 庫存類別
     * @return Object 查詢後的結果
     */
    public static function GetLastStockFactory($md_id) {
        try {


            if (is_null($md_id) || strlen($md_id) == 0) {
                return false;
            }

            /* $results = IsCarCoinStock::where('iscarcoinstock.isflag', '1')
              ->where('iscarcoinstock.md_id', $md_id)
              ->where('iscarcoinstock.cos_type', '2')
              ->join(DB::raw("(
              SELECT MAX(S1.cos_yymm)AS cos_yymm ,S1.cos_fdid_link ,S1.md_id
              FROM iscarcoinstock S1
              WHERE S1.cos_type = '2'
              GROUP BY S1.cos_fdid_link ,S1.md_id
              )S"), function($join) {
              $join->on('iscarcoinstock.cos_yymm', '=', 's.cos_yymm')->where('iscarcoinstock.cos_fdid_link', '=', 's.cos_fdid_link')->where('iscarcoinstock.md_id', '=', 's.md_id');
              }) */
            $results = \App\models\ICR_ShopBonusStock::where('icr_shopbonusstock.isflag', '1')
                    ->where('icr_shopbonusstock.md_id', $md_id)
                    ->where('icr_shopbonusstock.sbs_type', '2')
                    ->join(DB::raw("(select max(s1.sbs_yymm)as sbs_yymm ,s1.sbs_sd_id_link ,s1.md_id from icr_shopbonusstock s1 where s1.sbs_type = '2' group by s1.sbs_sd_id_link ,s1.md_id)s")
                            , function($join) {
                        $join->on('icr_shopbonusstock.sbs_yymm', '=', 's.sbs_yymm');
                        $join->on('icr_shopbonusstock.sbs_sd_id_link', '=', 's.sbs_sd_id_link');
                        $join->on('icr_shopbonusstock.md_id', '=', 's.md_id');
                    })
                    ->get()
                    ->toArray();

            //->get();

            return $results;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
        }
    }

    /**
     * 檢查並建立「本月庫存」
     * @param $md_id    會員代碼
     * @return Boolean 執行結果
     */
    private static function CheckAndCreateStockData($md_id) {

        try {

            //檢查並建立「0：代幣點數 」庫存
            $querydata = ICR_ShopBonusStock::GetLastStock($md_id, '0');

            if (is_null($querydata) || count($querydata) == 0) {
                if (!ICR_ShopBonusStock::CreateStock($md_id, '0', '0')) {
                    return false;
                }
            } else if ($querydata['sbs_yymm'] != date('Ym')) {
                if (!ICR_ShopBonusStock::CreateStock($md_id, '0', $querydata['sbs_end'])) {
                    return false;
                }
            }

            //檢查並建立「 1：APP禮點」庫存
            $querydata = ICR_ShopBonusStock::GetLastStock($md_id, '1');
            if (is_null($querydata) || count($querydata) == 0) {
                if (!ICR_ShopBonusStock::CreateStock($md_id, '1', '0')) {
                    return false;
                }
            } else if ($querydata['sbs_yymm'] != date('Ym')) {
                if (!ICR_ShopBonusStock::CreateStock($md_id, '1', $querydata['sbs_end'])) {
                    return false;
                }
            }

            //檢查並建立「2：活動券紅利」庫存
            $querydata = ICR_ShopBonusStock::GetLastStockFactory($md_id, '2');
            if (is_null($querydata) || count($querydata) == 0) {

            } else {
                foreach ($querydata as $rowdata) {
                    if ($rowdata['sbs_yymm'] != date('Ym')) {

                        if (!ICR_ShopBonusStock::CreateStockFactory($md_id, '2', $rowdata['sbs_sd_id_link'], $rowdata['sbs_end'])) {

                        }
                    }
                }
            }
            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    //
    //
    //
    /**
     * 取得資料，使用「md_id」和「cos_type」
     * @param type $md_id
     * @param type $cos_type
     * @param type $fd_id
     * @return type
     */
    public static function GetStockByMDID_COSTypeQ($md_id, $sbs_type, $sd_id) {
        try {

            if (is_null($md_id) || strlen($md_id) == 0 || is_null($sbs_type) || strlen($sbs_type) == 0 || is_null($sd_id) || strlen($sd_id) == 0) {
                return null;
            }

            ICR_ShopBonusStock::CheckAndCreateStockDataQ($md_id);

            $querydata = ICR_ShopBonusStock::where('isflag', '1')
                    ->where('md_id', $md_id)
                    ->where('sbs_type', $sbs_type)
                    ->where('sbs_sd_id_link', $sd_id)
                    ->where('sbs_yymm', date('Ym'))
                    ->get()
                    ->toArray();


            return $querydata;
        } catch (\Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 更新庫存資料
     * @param type $md_id 會員代碼
     * @param type $cos_type 點數類別。 0：代幣點數 1：APP禮點 2：活動券紅利                                           
     * @param type $fd_id 廠商代號
     * @param type $modifytype   異動類別 ：'add'、'use'、'return'、
     * @param type $modifycoid 異動點數
     * @param type $cos_end  [ 回傳 ] 異動後庫存點數
     * @return boolean
     */
    public static function UpdateStockQ($md_id, $sbs_type, $sd_id, $modifytype, $modifycoid, &$sbs_end) {
       try {
        if (is_null($md_id) || count($md_id) == 0 || is_null($sbs_type) || count($sbs_type) == 0 || is_null($modifytype) || count($modifytype) == 0 || is_null($modifycoid) || count($modifycoid) == 0) {
           return false;
        } 
        //取得庫 存資料
        $querydata = ICR_ShopBonusStock::GetStockByMDID_COSType($md_id, $sbs_type);

        if (is_null($querydata) || count($querydata) == 0) {
            return false;
        }
                                                                 
        if ( strcmp($modifytype, 'add') == 0 ) {

            $querydata[0]['sbs_add'] = $querydata[0]['sbs_add'] + $modifycoid;
            $querydata[0]['sbs_add_date'] = date('Y-m-d H:i:s');    
        } else if ( strcmp($modifytype, 'use') == 0 ) {

            $querydata[0]['sbs_use'] = $querydata[0]['sbs_use'] + $modifycoid;
            $querydata[0]['sbs_use_date'] = date('Y-m-d H:i:s');
        } else if ( strcmp($modifytype, 'return') == 0 ) {

            $querydata[0]['sbs_return'] = $querydata[0]['sbs_return'] + $modifycoid;
            $querydata[0]['sbs_add_date'] = date('Y-m-d H:i:s');
        } else {
            return false;
        }

        $querydata[0]['sbs_end_date'] = date('Y-m-d H:i:s');
        $querydata[0]['sbs_end'] = $querydata[0]['sbs_begin'] + $querydata[0]['sbs_add'] - $querydata[0]['sbs_use'] + $querydata[0]['sbs_return'];
        $sbs_end = $querydata[0]['sbs_end'];
        
        return ICR_ShopBonusStock::UpdateData($querydata[0]);
        } catch(\Exception $e) {
           ErrorLog::InsertData($e);
           return false;
        }
    }

    /**
     * 檢查並建立「本月庫存」
     * @param $md_id    會員代碼
     * @return Boolean 執行結果
     */
    private static function CheckAndCreateStockDataQ($md_id) {

        try {

            //檢查並建立「0：代幣點數 」庫存
            /*$querydata = ICR_ShopBonusStock::GetLastStockQ($md_id, '0');

            if (is_null($querydata) || count($querydata) == 0) {
                if (!ICR_ShopBonusStock::CreateStockQ($md_id, '0', '0', '0')) {
                    return false;
                }
            } else if ($querydata['sbs_yymm'] != date('Ym')) {
                if (!ICR_ShopBonusStock::CreateStockQ($md_id, '0', '0', $querydata['sbs_end'])) {
                    return false;
                }
            } */

          /*  //檢查並建立「 1：APP禮點」庫存
            $querydata = ICR_ShopBonusStock::GetLastStockQ($md_id, '1');
            if (is_null($querydata) || count($querydata) == 0) {
                if (!ICR_ShopBonusStock::CreateStockQ($md_id, '1', '0', '0')) {
                    throw new \Exception('ROW641');//return false;
                }
            } else if ($querydata['sbs_yymm'] != date('Ym')) {
                if (!ICR_ShopBonusStock::CreateStockQ($md_id, '1', '0', $querydata['sbs_end'])) {
                    throw new \Exception('ROW645');//return false;
                }
            } */

            //檢查並建立「2：活動券紅利」庫存
            $querydata = ICR_ShopBonusStock::GetLastStockQ($md_id, '0');
            if (is_null($querydata) || count($querydata) == 0) {
                return true;
            }

            foreach ($querydata as $rowdata) {
                if ($rowdata['sbs_yymm'] == date('Ym')) {
                    continue;
                }
                if (!ICR_ShopBonusStock::CreateStockQ($md_id, '0', $rowdata['sbs_sd_id_link'], $rowdata['sbs_end'])) {
                    throw new \Exception('ROW660');//return false;
                }
            } 
            return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     * 取得該用戶庫存最後一筆記錄
     * @param $md_id    會員代碼
     * @param $cos_type 點數類別。 0：代幣點數 1：APP禮點 2：活動券紅利
     * @return Object 查詢後的結果
     */
    public static function GetLastStockQ($md_id, $sbs_type) {

        try {
            if (is_null($md_id) || strlen($md_id) == 0 || is_null($sbs_type) || strlen($sbs_type) == 0) {
                return false;
            }
                /*$results = ICR_ShopBonusStock::where('isflag', '1')
                        ->where('md_id', $md_id)
                        ->where('sbs_type', $sbs_type)
                        ->orderBy('sbs_yymm', 'desc')
                        ->first(); */
              $results = \App\models\IsCarCoinStock::where('icr_shopbonusstock.isflag', '1')
                        ->where('icr_shopbonusstock.md_id', $md_id)
                        ->where('icr_shopbonusstock.sbs_type', $sbs_type)
                        ->join(DB::raw("(select max(s1.sbs_yymm)as sbs_yymm ,s1.sbs_sd_id_link ,s1.md_id from icr_shopbonusstock s1 where s1.sbs_type = '0' group by s1.sbs_sd_id_link ,s1.md_id)s")
                                , function($join) {
                            $join->on('icr_shopbonusstock.sbs_yymm', '=', 's.sbs_yymm');
                            $join->on('icr_shopbonusstock.sbs_sd_id_link', '=', 's.sbs_sd_id_link');
                            $join->on('icr_shopbonusstock.md_id', '=', 's.md_id');
                        })
                        ->get()
                        ->toArray();           
            return $results;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return null;
        }
    }

    /**
     * 更新期初庫存資料
     * @param $md_id    會員代碼
     * @param $cos_type 庫存類別
     * @param $fd_id  廠商代號
     * @param $cos_begin  期初庫存數
     * @return Boolean 執行結果
     */
    public static function CreateStockQ($md_id, $sbs_type, $sd_id, $sbs_begin) {

        if (is_null($md_id) || strlen($md_id) == 0 || is_null($sbs_type) || strlen($sbs_type) == 0 || is_null($sd_id) || strlen($sd_id) == 0 || is_null($sbs_begin) || strlen($sbs_begin) == 0) {
            return false;
        }
        $month = date('Ym');
        $arraydata = [
                        'sbs_yymm'        => $month, 
                        'md_id'           => $md_id, 
                        'sbs_type'        => $sbs_type, 
                        'sbs_sd_id_link'  => $sd_id, 
                        'sbs_begin'       => $sbs_begin, 
                        'sbs_add'         => '0', 
                        'sbs_use'         => '0', 
                        'sbs_return'      => '0', 
                        'sbs_end'         => $sbs_begin, 
                        'sbs_begin_date'  => date("Y-m-d H:i:s")
                     ];
        return ICR_ShopBonusStock::InsertData($arraydata);
    }
    
    
    
    public static function GetMemberShopBouns($md_id, $sd_id) {
      try {
        
            $query = ICR_ShopBonusStock:: leftJoin('icr_shopdata', function($leftjoin) {
                        $leftjoin->on('icr_shopbonusstock.sbs_sd_id_link', '=', 'icr_shopdata.sd_id')
                        ->where('icr_shopdata.isflag', '=', '1');
                    })
                    ->where('icr_shopbonusstock.sbs_yymm', '=', date('Ym'))
                    ->where('icr_shopbonusstock.isflag', '=', '1')
                    ->where('icr_shopbonusstock.md_id', '=', $md_id);

            if (!is_null($sd_id) && mb_strlen($sd_id) != 0) {
                $query->where('icr_shopbonusstock.sbs_sd_id_link', '=', $sd_id);
            }

            $results = $query->select('icr_shopbonusstock.sbs_sd_id_link'
                            , 'icr_shopbonusstock.sbs_end'
                            , 'icr_shopdata.sd_shopname'
                            , 'icr_shopdata.sd_shopphotopath'
                            )->get()->toArray();

            return $results; 
      } catch (\Exception $e) {
         ErrorLog::InsertData($e);
         return null;
      }
    }

}
