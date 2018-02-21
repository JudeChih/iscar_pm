<?php

namespace App\Repositories;

use App\Models\PmCannedMessages;
use DB;

class PmCannedMessagesRepository  {

    //新增資料
    public function InsertData($arraydata) {
     try {
        if (
                !\App\Library\Commontools::CheckArrayValue($arraydata, 'sd_id') || !\App\Library\Commontools::CheckArrayValue($arraydata, 'cmsg_content')
        ) {
            return false;
        }
        $savedata['sd_id'] = $arraydata['sd_id'];
        $savedata['cmsg_content'] = $arraydata['cmsg_content'];

        if (\App\Library\Commontools::CheckArrayValue($arraydata, "isflag")) {
                $savedata['isflag'] = $arraydata['isflag'];
            } else {
                $savedata['isflag'] = '1';
        }
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "create_user")) {
                $savedata['create_user'] = $arraydata['create_user'];
            } else {
                $savedata['create_user'] = 'Pmapi';
        }
        if (\App\Library\Commontools::CheckArrayValue($arraydata, "last_update_user")) {
                $savedata['last_update_user'] = $arraydata['last_update_user'];
            } else {
                $savedata['last_update_user'] = 'Pmapi';
        }
        $savedata['create_date'] = date('Y-m-d H:i:s');
        $savedata['last_update_date'] = date('Y-m-d H:i:s');

        //新增資料並回傳「自動遞增KEY值」
         if (DB::table('pm_canned_messages')->insert($savedata)) {
            return true;
        } else {
            return false;
        }
     } catch (\Exception $e) {
               //DB::rollBack();
               \App\Models\ErrorLog::InsertData($e);
               return false;
     }
    }

      /**
     * 修改資料
     * @param array $arraydata 要更新的資料
     * @return boolean
     */
    public function UpdateData($arraydata) {
        try {

            if (!\App\Library\Commontools::CheckArrayValue($arraydata, 'cmsg_serno')) {
                return false;
            }

            $savedata['cmsg_serno'] = $arraydata['cmsg_serno'];

            if (\App\Library\Commontools::CheckArrayValue($arraydata, "sd_id")) {
                $savedata['sd_id'] = $arraydata['sd_id'];
            }
            if (\App\Library\Commontools::CheckArrayValue($arraydata, "cmsg_content")) {
                $savedata['cmsg_content'] = $arraydata['cmsg_content'];
            }
            $savedata['last_update_date'] = date('Y-m-d H:i:s');

            DB::table('pm_canned_messages')
                    ->where('cmsg_serno', $savedata['cmsg_serno'])
                    ->update($savedata);
            return true;
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
        }
    }

     /**
     * 刪除資料
     * @param $cmsg_serno 要刪除的資料
     * @return boolean
     */
    public function DeleteData($cmsg_serno) {
       try {
            if ($cmsg_serno == null || strlen($cmsg_serno) == 0) {
              return false;
            }
            DB::table('pm_canned_messages')
                   ->where('cmsg_serno', $cmsg_serno)
                   ->delete();
           return true;
       } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return false;
       }
    }



    public function  getDataBySdId ($sd_id) {
        if ($sd_id == null || strlen($sd_id) == 0)
            return null;

        $query = PmCannedMessages::where('sd_id', $sd_id) ;
        $result = $query->select( 'pm_canned_messages.cmsg_serno'
                                                 ,'pm_canned_messages.cmsg_content'
                                     )
                                    ->get()->toArray();
        return $result;
    }

}