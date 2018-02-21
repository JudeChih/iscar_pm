<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

/**
 * 商家管理會員綁定表
 */
class ICR_ShopTag_xref extends Model {

    //
    public $table = 'icr_shoptag_xref';
    public $primaryKey = 'stx_serno';
    public $timestamps = false;


     /**
     * ██████████▍CREATE 建立資料
     */
     /**
     * InsertData
     * @param array $arraydata
     */
    public static function InsertData($arraydata) {
        try {
              if (!Commontools::CheckArrayValue($arraydata, "stx_tag_type") || !Commontools::CheckArrayValue($arraydata, "stx_tag_id") || !Commontools::CheckArrayValue($arraydata, "stx_sd_id")) {
                return false;
              }
              $savadata['stx_tag_type'] = $arraydata['stx_tag_type'];
              $savadata['stx_tag_id'] = $arraydata['stx_tag_id'];
              $savadata['stx_sd_id'] = $arraydata['stx_sd_id'];

              if (Commontools::CheckArrayValue($arraydata, 'create_user')) {
                $savadata['create_user'] = $arraydata['create_user'];
              } else {
                $savadata['create_user'] = 'webapi';
              }
              if (Commontools::CheckArrayValue($arraydata, 'last_update_user')) {
                $savadata['last_update_user'] = $arraydata['last_update_user'];
              } else {
                $savadata['last_update_user'] = 'webapi';
              }
              if (Commontools::CheckArrayValue($arraydata, 'isflag')) {
                $savadata['isflag'] = $arraydata['isflag'];
              } else {
                $savadata['isflag'] = '1';
              }

              DB::table('icr_shoptag_xref')->insert($savadata);
              return true;
        } catch (Exception $e) {
            \App\models\ErrorLog::InsertData($e);
            return false;
        }
    }

}
