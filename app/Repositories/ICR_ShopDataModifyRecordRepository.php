<?php

namespace App\Repositories;

use App\Models\ICR_ShopDataModifyRecord;
use App\Library\Commontools;
use DB;

class ICR_ShopDataModifyRecordRepository  {

    /**
     * 新增特約商異動資料
     * @param  [type] $arraydata [description]
     * @return [type]            [description]
     */
    public function insertData($arraydata){
        try {
            if ( !Commontools::CheckArrayValue($arraydata, "sd_id") || !Commontools::CheckArrayValue($arraydata, "sdmr_operationtype") || !Commontools::CheckArrayValue($arraydata, "sdmr_modifyitem")) {
                return false;
            }
            $savedata['sd_id'] = $arraydata['sd_id'];
            $savedata['sdmr_operationtype'] = $arraydata['sdmr_operationtype'];
            $savedata['sdmr_modifyitem'] = $arraydata['sdmr_modifyitem'];
            if (Commontools::CheckArrayValue($arraydata, "scm_id")) {
                $savedata['scm_id'] = $arraydata['scm_id'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sdmr_modifyuser")) {
                $savedata['sdmr_modifyuser'] = $arraydata['sdmr_modifyuser'];
            }
            if (Commontools::CheckArrayValue($arraydata, "sdmr_modifyreason")) {
                $savedata['sdmr_modifyreason'] = $arraydata['sdmr_modifyreason'];
            }
            $savedata['create_user'] = 'webapi';
            $savedata['last_update_user'] = 'webapi';
            $savedata['create_date'] = \Carbon\Carbon::now();
            $savedata['last_update_date'] = \Carbon\Carbon::now();
            DB::table('icr_shopdatamodifyrecord')->insert($savedata);
            return true;
        } catch (Exception $e) {
            \App\Models\ErrorLog::InsertData($e);
            return false;
        }
    }

}
