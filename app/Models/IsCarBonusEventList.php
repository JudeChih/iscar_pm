<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class IsCarBonusEventList extends Model {

    public $table = 'iscarbounuseventlist';
    public $primaryKey = 'bel_serno';
    public $timestamps = false;

    /*     * 取得資料，依「MD_ID」取得
     */

    public static function GetData($bel_serno) {
        if ($bel_serno == null || strlen($bel_serno) == 0) {
            return null;
        }

        $results = IsCarBonusEventList::where('iscarbounuseventlist.isflag', '1')
                ->join('iscarbounusamountlist', 'iscarbounuseventlist.bal_serno', '=', 'iscarbounusamountlist.bal_serno')
                ->where('bel_serno', $bel_serno)
                ->get()
                ->toArray();

        return $results;
    }

    /**
     * 取得「評論文章」所能取得的紅利點數
     * @return int
     */
    public static function GetBonus_NewsComment() {

        try {

            $querydata = IsCarBonusEventList::Getdata(\App\library\Commontools::$BEL_Serno_CommentNews);

            if (is_null($querydata) || count($querydata) == 0) {
                return 0;
            }

            return $querydata[0]['bal_bounusamount'];
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return 0;
        }
    }

    /**
     * 取得「留言」所能取得的紅利點數
     * @return int
     */
    public static function GetBonus_ShopQuestionnaire() {

        try {

            $querydata = IsCarBonusEventList::Getdata(\App\library\Commontools::$BEL_Serno_ShopQuestionnaire);

            if (is_null($querydata) || count($querydata) == 0) {
                return 0;
            }

            return $querydata[0]['bal_bounusamount'];
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($ex);
            return 0;
        }
    }

}
