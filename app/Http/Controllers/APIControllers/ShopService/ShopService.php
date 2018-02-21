<?php

namespace App\Http\Controllers\APIControllers\ShopService;

use App\Library\Commontools;

/** ShopService共用Function * */
class ShopService {

    /**
     * 查詢該店家下一個排隊號碼
     * @param type $sd_id
     * @return type
     * @throws \Exception
     */
    function query_NextService($sd_id, $ssqd_id, &$servicedNO, &$nextServiceNO, &$nextClientID, &$nextServiceQueID) {
        $servicedNO = null;
        $nextServiceNO = null;
        $nextClientID = null;
        $nextServiceQueID = null;
        try {
            $querydata = \App\Models\ICR_ShopServiceQue_q::query_QueData($sd_id, $ssqd_id);
            if (count($querydata) == 0) {
//              \App\Models\ErrorLog::InsertLog('01');
                return false;
            }

            $filderdata = array_filter($querydata, function($item) {
                return $item['ssqq_usestatus'] == '2';
            });
            if (count($filderdata) == 0) {
                $servicedNO = 0;
            } else {
                $servicedNO = max(array_column($filderdata, 'ssqq_queserno'));
            }

            $filderdata = array_filter($querydata, function($item) {
                return $item['ssqq_usestatus'] == '1';
            });
            if (count($filderdata) == 0) {
                $nextserviceNO = 0;
            } else {
                $nextserviceNO = min(array_column($filderdata, 'ssqq_queserno'));
                $reduce = array_reduce($filderdata, function( $carry, $obj ) use ( $nextserviceNO ) {
                    if ($obj['ssqq_queserno'] == $nextserviceNO) {
                        $temp['ssqq_id'] = $obj['ssqq_id'];
                        $temp['ssqq_queserno'] = $obj['ssqq_queserno'];
                        $temp['md_id'] = $obj['md_id'];
                        $carry[] = $temp;
                    }
                    return $carry;
                });

                if (count($reduce) == 1) {
                    $nextServiceNO = $reduce[0]['ssqq_queserno'];
                    $nextClientID = $reduce[0]['md_id'];
                    $nextServiceQueID = $reduce[0]['ssqq_id'];
                }
            }
            return true;
        } catch (\Exception $ex) {
            if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($ex);
                return false;
            }
        }
    }

    function query_WaitingTime($sd_id, $ssqd_workhour) {
        try {
            \App\models\ICR_ShopServiceQue_q::Query_QueCount($sd_id, $quecount, $serviced);

            return $ssqd_workhour * ($quecount - $serviced);
        } catch (Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return -1;
        }
    }

}
