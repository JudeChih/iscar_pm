<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Commontools;
use DB;

class ICR_ShopServiceQue_q extends Model {

//
    public $table = 'icr_shopserviceque_q';
    public $primaryKey = 'ssqq_id';
    public $timestamps = false;
    public $incrementing = false;

    /** ██████████▍CREATE 建立資料 */
    /** ██████████▍READ 讀取資料 */

    /**
     * 依「$ssqq_id」取得資料
     * @param type $ssqq_id
     * @return type
     */
    public static function GetData_Read($ssqq_id) {
        try {
            if ($ssqq_id == null || strlen($ssqq_id) == 0) {
                return null;
            }

             $query = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->leftJoin('icr_shopserviceque_d', 'icr_shopserviceque_q.ssqd_id', '=', 'icr_shopserviceque_d.ssqd_id')
                            ->leftJoin('icr_shopdata','icr_shopserviceque_q.sd_id','=','icr_shopdata.sd_id')
                            //->leftJoin('icr_shopquestionnaire_a','icr_shopquestionnaire_a.event_id','=','icr_shopserviceque_q.ssqd_id')
                            ->where('icr_shopserviceque_q.ssqq_id', '=', $ssqq_id);
              $results = $query -> select(
                                          'icr_shopserviceque_q.*',
                                          'icr_shopserviceque_d.*',
                                          'icr_shopdata.*',
                                          'icr_shopserviceque_q.md_id as MD_ID')
                                ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
     public static function GetData_Event($ssqq_id) {
        try {
            if ($ssqq_id == null || strlen($ssqq_id) == 0) {
                return null;
            }

            $query = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->leftJoin('icr_shopserviceque_d', 'icr_shopserviceque_q.ssqd_id', '=', 'icr_shopserviceque_d.ssqd_id')
                            ->leftJoin('icr_shopdata','icr_shopserviceque_q.sd_id','=','icr_shopdata.sd_id')
                            ->leftJoin('icr_shopquestionnaire_a','icr_shopquestionnaire_a.event_id','=','icr_shopserviceque_q.ssqd_id')
                            ->where('icr_shopserviceque_q.ssqq_id', '=', $ssqq_id) ;
            $results = $query -> select(
                                        'icr_shopserviceque_q.*',
                                        'icr_shopdata.*',
                                        'icr_shopquestionnaire_a.*',
                                        'icr_shopserviceque_d.*',
                                        'icr_shopserviceque_q.md_id as MD_ID')               
                                        ->get()->toArray();

            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    
      /**
     * InsertData
     * @param array $arraydata
     */
    public static function InsertData($arraydata) {
        try {
              if (!Commontools::CheckArrayValue($arraydata, "md_id") || !Commontools::CheckArrayValue($arraydata, "sd_id") || !Commontools::CheckArrayValue($arraydata, "ssqd_id")
                  || !Commontools::CheckArrayValue($arraydata, "ssqq_questarttime")|| !Commontools::CheckArrayValue($arraydata, "ci_serno") || !Commontools::CheckArrayValue($arraydata, "ssqq_id")) {
                return false;
              }
              $savadata['ssqq_id'] = $arraydata['ssqq_id'];
              $savadata['md_id'] = $arraydata['md_id'];
              $savadata['sd_id'] = $arraydata['sd_id'];
              $savadata['ssqd_id'] = $arraydata['ssqd_id'];
              $savadata['ci_serno'] = $arraydata['ci_serno'];
              $savadata['ssqq_questarttime'] = $arraydata['ssqq_questarttime'];
              
              if (Commontools::CheckArrayValue($arraydata, "ssqq_usestatus")) {
                $savadata['ssqq_usestatus'] = $arraydata['ssqq_usestatus'];
              } else {
                $savadata['ssqq_usestatus'] = 0;
              }
              if (Commontools::CheckArrayValue($arraydata, "ssqq_calltimes")) {
                $savadata['ssqq_calltimes'] = $arraydata['ssqq_calltimes'];
              } else {
                $savadata['ssqq_calltimes'] = 0;
              }
              if (Commontools::CheckArrayValue($arraydata, "ssqq_overquecalltimes")) {
                $savadata['ssqq_overquecalltimes'] = $arraydata['ssqq_overquecalltimes'];
              } else {
                $savadata['ssqq_overquecalltimes'] = 0;
              }
              
              if (Commontools::CheckArrayValue($arraydata, "ssqq_queserno")) {
                $savadata['ssqq_queserno'] = $arraydata["ssqq_queserno"];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssqq_receivtime")) {
                $savadata['ssqq_receivtime'] = $arraydata["ssqq_receivtime"];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssqq_receiver")) {
                $savadata['ssqq_receiver'] = $arraydata["ssqq_receiver"];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssqq_abandomreason")) {
                $savadata['ssqq_abandomreason'] = $arraydata["ssqq_abandomreason"];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssqq_calltimes")) {
                $savadata['ssqq_calltimes'] = $arraydata["ssqq_calltimes"];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssqq_callingtime")) {
                $savadata['ssqq_callingtime'] = $arraydata["ssqq_callingtime"];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssqq_overquecalltimes")) {
                $savadata['ssqq_overquecalltimes'] = $arraydata["ssqq_overquecalltimes"];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssqq_overquecallingtime")) {
                $savadata['ssqq_overquecallingtime'] = $arraydata["ssqq_overquecallingtime"];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssqq_orignalquetime")) {
                $savadata['ssqq_orignalquetime'] = $arraydata["ssqq_orignalquetime"];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssqq_orignalqueserno")) {
                $savadata['ssqq_orignalqueserno'] = $arraydata["ssqq_orignalqueserno"];
              }
              if (Commontools::CheckArrayValue($arraydata, "ssqq_movetimes")) {
                $savadata['ssqq_movetimes'] = $arraydata["ssqq_movetimes"];
              }
              if (Commontools::CheckArrayValue($arraydata, 'ssqq_queremark')) {
                $savadata['ssqq_queremark'] = $arraydata['ssqq_queremark'];
              }
              
              if (Commontools::CheckArrayValue($arraydata, 'isflag')) {
                $savadata['isflag'] = $arraydata['isflag'];
              } else {
                $savadata['isflag'] = '1';
              }
              if (Commontools::CheckArrayValue($arraydata, "create_user")) {
                $savadata['create_user'] = $arraydata['create_user'];
              } else {
                $savadata['create_user'] = 'webapi';
              }
              if (Commontools::CheckArrayValue($arraydata, "last_update_user")) {
                $savadata['last_update_user'] = $arraydata['last_update_user'];
              } else {
                $savadata['last_update_user'] = 'webapi';
              }
              
              DB::table('icr_shopserviceque_q')->insert($savadata);
              return true;
        } catch (Exception $ex) {
            \App\models\ErrorLog::InsertData($e);
            return false;
        }
    }

    /** ██████████▍UPDATE 更新資料 */
    /** ██████████▍DELETE 刪除資料 */
    /** ██████████▍CHECK 檢查資料 */
    /** ██████████▍QUERY 查詢資料 */

    public static function UpdateData($modifydata) {
       try {
              if (!Commontools::CheckArrayValue($modifydata, "ssqq_id") ) {
                return false;
              }
              $savedata['ssqq_id'] = $modifydata['ssqq_id']; 
              if (Commontools::CheckArrayValue($modifydata, "ssqq_queserno")) {
                $savedata['ssqq_queserno'] = $modifydata['ssqq_queserno'];
              }
              if (Commontools::CheckArrayValue($modifydata, "md_id")) {
                $savedata['md_id'] = $modifydata['md_id'];
              }
              if (Commontools::CheckArrayValue($modifydata, "sd_id")) {
                $savedata['sd_id'] = $modifydata['sd_id'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqd_id")) {
                $savedata['ssqd_id'] = $modifydata['ssqd_id'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqq_questarttime")) {
                $savedata['ssqq_questarttime'] = $modifydata['ssqq_questarttime'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqq_receivtime")) {
                $savedata['ssqq_receivtime'] = $modifydata['ssqq_receivtime'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqq_usestatus")) {
                $savedata['ssqq_usestatus'] = $modifydata['ssqq_usestatus'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqq_receiver")) {
                $savedata['ssqq_receiver'] = $modifydata['ssqq_receiver'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqq_abandomreason")) {
                $savedata['ssqq_abandomreason'] = $modifydata['ssqq_abandomreason'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqq_calltimes")) {
                $savedata['ssqq_calltimes'] = $modifydata['ssqq_calltimes'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqq_callingtime")) {
                $savedata['ssqq_callingtime'] = $modifydata['ssqq_callingtime'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqq_overquecalltimes")) {
                $savedata['ssqq_overquecalltimes'] = $modifydata['ssqq_overquecalltimes'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqq_overquecallingtime")) {
                $savedata['ssqq_overquecallingtime'] = $modifydata['ssqq_overquecallingtime'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqq_orignalquetime")) {
                $savedata['ssqq_orignalquetime'] = $modifydata['ssqq_orignalquetime'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqq_orignalqueserno")) {
                $savedata['ssqq_orignalqueserno'] = $modifydata['ssqq_orignalqueserno'];
              }
              if (Commontools::CheckArrayValue($modifydata, "ssqq_movetimes")) {
                $savedata['ssqq_movetimes'] = $modifydata['ssqq_movetimes'];
              }  
              if (Commontools::CheckArrayValue($modifydata, "isflag")) {
                $savedata['isflag'] = $modifydata['isflag'];
              } else {
                $savedata['isflag'] = '1';
              }
              $savedata['last_update_user'] = 'webapi';
              $savedata['last_update_date'] = date("Y-m-d H:i:s");
              DB::table('icr_shopserviceque_q')
                    ->where('ssqq_id', '=', $savedata['ssqq_id'])
                    ->update($savedata);

             return true;
       } catch (Exception $e) {
           ErrorLog::Insert($e);
           return false;
       }
       
    }

    public static function Update_BySdId($modifydata) {
    try {
          if (!Commontools::CheckArrayValue($modifydata, "sd_id") ) {
                return false;
          }
          $savedata['sd_id'] = $modifydata['sd_id'];
          if (Commontools::CheckArrayValue($modifydata, "ssqq_usestatus")) {
                $savedata['ssqq_usestatus'] = $modifydata['ssqq_usestatus'];
          }
          if (Commontools::CheckArrayValue($modifydata, "ssqq_questarttime")) {
                $savedata['ssqq_questarttime'] = $modifydata['ssqq_questarttime'];
          }
          if (Commontools::CheckArrayValue($modifydata, "ssqq_usestatus")) {
                $savedata['ssqq_usestatus'] = $modifydata['ssqq_usestatus'];
          }
           $savadata['last_update_user'] = 'webapi';
              DB::table('icr_shopserviceque_q')
                    ->where('sd_id', '=', $savedata['sd_id'])
                    ->where('ssqq_questarttime','=',$savedata['ssqq_questarttime'])
                    ->where('ssqq_usestatus','=',$savedata['ssqq_usestatus'])
                    ->update($savedata);

             return true;
      } catch (Exception $e) {
           ErrorLog::Insert($e);
           return false;
      }
             
    }

    /**
     * 查詢該「店家﹙$sd_id﹚」當天已預約數量
     * @param type $sd_id
     * @param type $quecount 已預約數量
     * @param type $serviced 已結束數量
     * @return type
     */
    public static function Query_QueCount($sd_id, &$quecount, &$serviced) {
        try {
            $quecount = 0;
            $serviced = 0;
            if ($sd_id == null || strlen($sd_id) == 0) {
                return false;
            }
            $results = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->where('icr_shopserviceque_q.sd_id', '=', $sd_id)
                            ->where("icr_shopserviceque_q.ssqq_questarttime", "=", date("Y-m-d"))
                            ->get()->toArray();
            $quecount = count($results);

            $filderdata = array_filter($results, function($item) {
                return $item['ssqq_usestatus'] == '2';
            });
            $serviced = count($filderdata);

            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }
  
    /**
     * 查詢該「店家﹙$sd_id﹚」當天已預約資料
     * @param type $sd_id
     * @param type $ssqd_id
     * @return type
     */
    public static function query_QueData($sd_id, $ssqd_id) {
        try {
            if ($sd_id == null || strlen($sd_id) == 0) {
                return false;
            }
            $results = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->where('icr_shopserviceque_q.sd_id', '=', $sd_id)
                            ->where('icr_shopserviceque_q.ssqd_id', '=',$ssqd_id )
                            ->where("icr_shopserviceque_q.ssqq_questarttime", "=", date("Y-m-d"))
                            ->get()->toArray();
            return $results;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
      /**
     * 查詢該「店家﹙$sd_id﹚」當天正在排隊數量
     * @param type $sd_id
     * @param type $quecount 已預約數量
     * @param type $serviced 已結束數量
     * @return type
     */
    public static function Query_QueueCountData($sd_id, $ssqd_id, &$quecount, &$serviced) {
        try {
            $quecount = 0;
            $serviced = 0;
            if ($sd_id == null || strlen($sd_id) == 0) {
                return false;
            }
            $results = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->where('icr_shopserviceque_q.sd_id', '=', $sd_id)
                            ->where('icr_shopserviceque_q.ssqd_id', '=', $ssqd_id)
                            ->where("icr_shopserviceque_q.ssqq_questarttime", "=",date("Y-m-d") )
                            ->get()->toArray();
            $quecount = count($results);

            $filderdata = array_filter($results, function($item) {
                return $item['ssqq_usestatus'] == '1';
            });
            $serviced = count($filderdata);

            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }
   /**
     * 查詢該「店家﹙$sd_id﹚」當天正在排隊數量
     * @param type $sd_id
     * @param type $quecount 已預約數量
     * @param type $serviced 已結束數量
     * @return type
     */
    public static function Query_QueueCount($sd_id, $ssqd_id, &$quecount, &$serviced) {
        try {
            $quecount = 0;
            $serviced = 0;
            if ($sd_id == null || strlen($sd_id) == 0) {
                return false;
            }
            $results = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->where('icr_shopserviceque_q.sd_id', '=', $sd_id)
                            ->where('icr_shopserviceque_q.ssqd_id', '=', $ssqd_id)
                            ->where("icr_shopserviceque_q.ssqq_questarttime", "=",date("Y-m-d"))
                            ->where('icr_shopserviceque_q.ssqq_usestatus','=','1')
                            ->orderBy('icr_shopserviceque_q.ssqq_queserno', 'asc')
                            ->get()->toArray();
            $quecount = count($results);

           // $filderdata = array_filter($results, function($item) {
           //     return $item['ssqq_usestatus'] == '1';
           // });
            if ($quecount != 0 && strlen($results[0]['ssqq_queserno']) != 0) {
                $serviced = $results[0]['ssqq_queserno'];
            } else {
                $serviced = 0; 
            }
            return true;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return false;
        }
    }

    /**
     *
     * @param type $ssqq_id
     * @param type $ssqd_id
     * @return boolean
     */
    public static function Query_ShopServiceQueData($ssqq_id, $ssqd_id) {
        try {
            if ($ssqq_id == null || strlen($ssqq_id) == 0 || $ssqd_id == null || strlen($ssqd_id) == 0) {
                return false;
            }
            $results = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->leftJoin('icr_shopserviceque_d', 'icr_shopserviceque_d.ssqd_id', '=', 'icr_shopserviceque_q.ssqd_id')
                            ->leftJoin('iscarmemberdata', 'iscarmemberdata.md_id', '=', 'icr_shopserviceque_q.md_id')
                            ->where('icr_shopserviceque_q.ssqq_id', '=', $ssqq_id)
                            //->where('icr_shopserviceque_q.ssqd_id', '=', $ssqd_id)
                            ->get()->toArray();
            $quecount = $results;

            return $quecount;
        } catch (Exception $ex) {
            ErrorLog::InsertData($ex);
            return null;
        }
    }
    /**
     * 查詢該「店家﹙$sd_id﹚」四天內已排隊資料
     * @param type $sd_id
     * @return type
     */
    public static function Query_QueDataIncludeFourDay($sd_id) {
        try {
            if (is_null($sd_id)) {
                return false;
            }
            $datenow = new \DateTime('now');
            //$date5DaysLess = date('Y-m-d', strtotime("$datenow -5 days"));
            $query = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->leftJoin('icr_shopserviceque_d', 'icr_shopserviceque_q.ssqd_id', '=', 'icr_shopserviceque_d.ssqd_id')
                            ->leftJoin('iscarmemberdata', 'icr_shopserviceque_q.md_id', '=', 'iscarmemberdata.md_id')
                            ->whereRaw("icr_shopserviceque_q.ssqq_questarttime > (NOW()-INTERVAL 5 DAY)")
                            ->where('icr_shopserviceque_q.sd_id', '=', $sd_id)
                            ->orderBy('icr_shopserviceque_q.ssqq_questarttime', 'desc');
             $result = $query->select(
                               'icr_shopserviceque_q.*',
                               'icr_shopserviceque_d.*',
                               'iscarmemberdata.*',
                               'icr_shopserviceque_q.create_date as CREATE_DATE'
                            )->get()->toArray();
                            
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($ex);
          return null; 
        }
    }
     /**
     * 查詢該「店家﹙$sd_id﹚」今天內已排隊資料
     * @param type $sd_id
     * @return type
     */
    public static function Query_QueDataToday($sd_id) {
        try {
            if (is_null($sd_id)) {
                return false;
            }
            $datenow = new \DateTime('now');
            //$date5DaysLess = date('Y-m-d', strtotime("$datenow -5 days"));
            $query = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->leftJoin('icr_shopserviceque_d', 'icr_shopserviceque_q.ssqd_id', '=', 'icr_shopserviceque_d.ssqd_id')
                            ->leftJoin('iscarmemberdata', 'icr_shopserviceque_q.md_id', '=', 'iscarmemberdata.md_id')
                            
                             ->where("icr_shopserviceque_q.ssqq_questarttime", "=" , date("Y-m-d"))                           
                             ->where('icr_shopserviceque_q.sd_id', '=', $sd_id);
             $result = $query->select(
                               'icr_shopserviceque_q.*',
                               'icr_shopserviceque_d.*',
                               'iscarmemberdata.*',
                               'icr_shopserviceque_q.create_date as CREATE_DATE'
                            )->get()->toArray();
                            
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($ex);
          return null; 
        }
    }
    
    
     /**
     * 查詢該「店家﹙$ssqq_id﹚」
     * @param type $ssqq_id
     * @return type
     */
    public static function GetData_BYSSQQID($ssqq_id) {
        try {
            if (is_null($ssqq_id)) {
                return false;
            }
            $result = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->leftJoin('icr_shopserviceque_d', 'icr_shopserviceque_q.ssqd_id', '=', 'icr_shopserviceque_d.ssqd_id')
                            ->leftJoin('icr_shopserviceque_m','icr_shopserviceque_q.sd_id','=','icr_shopserviceque_m.sd_id')
                            ->leftJoin('iscarmemberdata', 'icr_shopserviceque_q.md_id', '=', 'iscarmemberdata.md_id')
                            ->where('icr_shopserviceque_q.ssqq_id', '=', $ssqq_id)
                            ->get()->toArray();
                            
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null; 
        }
    }
    
    /**
     * 查詢今日有服務排隊「服務排隊編號﹙$sd_id﹚」
     * @param type $sd_id
     * @return type
     */
    public static function Query_FindTodayShopService($sd_id) {
        try {
            if (is_null($sd_id)) {
                return false;
            }
            $query = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->where('icr_shopserviceque_q.sd_id', '=', $sd_id)
                            ->where('icr_shopserviceque_q.ssqq_usestatus','=', '1' )
                            ->where("icr_shopserviceque_q.ssqq_questarttime", "=", date("Y-m-d"));
            $result = $query ->select('icr_shopserviceque_q.ssqd_id')
                             ->get()->toArray();
                            
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null; 
        }
    }
    /**
     * 查詢該「店家﹙$md_id﹚」
     * @param type $md_id
     * @return type
     */
     public static function Query_BYMDID($md_id) {
        try {
            if (is_null($md_id)) {
                return false;
            }
            $result = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->where('icr_shopserviceque_q.md_id', '=', $md_id)
                            ->get()->toArray();
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null; 
        }
    }
   
    
     /**
     * 查詢該「服務排隊編號﹙$ssqq_id﹚」
     * @param type $ssqq_id
     * @return type
     */ 
    public static function Query_BYSSQQID($ssqq_id) {
        try {
            if (is_null($ssqq_id)) {
                return false;
            }
            $result = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->leftJoin('icr_shopserviceque_d', 'icr_shopserviceque_q.ssqd_id', '=', 'icr_shopserviceque_d.ssqd_id')
                            ->where('icr_shopserviceque_q.ssqq_id', '=', $ssqq_id)
                            ->get()->toArray();
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null; 
        }
    }
    
    /**
     * 查詢該啟動的服務排隊「服務排隊編號﹙$ssqq_id﹚」
     * @param type $ssqq_id
     * @return type
     */ 
    public static function Query_SatrtCallUp($ssqq_id) {
        try {
            if (is_null($ssqq_id)) {
                return false;
            }
            $result = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->leftJoin('icr_shopserviceque_d', 'icr_shopserviceque_q.ssqd_id', '=', 'icr_shopserviceque_d.ssqd_id')
                            ->where('icr_shopserviceque_q.ssqq_id', '=', $ssqq_id)
                            ->where('icr_shopserviceque_q.ssqq_usestatus','=','1')
                            ->get()->toArray();
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null; 
        }
    }
    
    /**
     * 查詢該暫停的服務排隊「店家﹙$sd_id﹚」
     * @param type $sd_id
     * @return type
     */ 
    public static function Query_StopShopService($sd_id) {
        try {
            if (is_null($sd_id)) {
                return false;
            }
             $query = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->leftJoin('icr_shopserviceque_d', 'icr_shopserviceque_q.ssqd_id', '=', 'icr_shopserviceque_d.ssqd_id')
                            ->where('icr_shopserviceque_q.sd_id', '=', $sd_id)
                            ->where('icr_shopserviceque_q.ssqq_usestatus','=','1')
                            ->where("icr_shopserviceque_q.ssqq_questarttime", "=", date("Y-m-d"));
                            
             $result = $query->select('icr_shopserviceque_q.*', 'icr_shopserviceque_d.ssqd_title')
                             ->orderBy('icr_shopserviceque_q.ssqd_id', 'asc')
                             ->orderBy('icr_shopserviceque_q.ssqq_queserno', 'asc') 
                             ->get()->toArray();
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null; 
        }
    }
    /**
     * 查詢已完成的服務排隊「店家﹙$sd_id﹚」
     * @param type $sd_id
     * @return type
     */ 
    public static function Query_FindCompleteService($sd_id) {
        try {
            if (is_null($sd_id)) {
                return false;
            }
            $result = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->leftJoin('icr_shopserviceque_d', 'icr_shopserviceque_q.ssqd_id', '=', 'icr_shopserviceque_d.ssqd_id')
                            ->where('icr_shopserviceque_q.sd_id', '=', $sd_id)
                            ->where("icr_shopserviceque_q.ssqq_questarttime", "=",date("Y-m-d"))
                            ->whereIn('icr_shopserviceque_q.ssqq_usestatus',array(2, 5, 6, 8))
                            ->get()->toArray();
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null; 
        }
    }
    /**
     * 查詢用戶所有的服務排隊「服務排隊編號﹙$ssqq_id﹚」
     * @param type $ssqq_id
     * @return type
     */ 
    public static function Query_FindAllClientShopService($md_id,$last_update_date) {
        try {
            if (is_null($md_id)) {
                return false;
            }
            $query = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->where('icr_shopserviceque_q.md_id', '=', $md_id);
             if(!is_null($last_update_date) && strlen($last_update_date) != 0) {
               $query->where('icr_shopserviceque_q.last_update_date', '>', $last_update_date);
             }
            $result = $query->select('icr_shopserviceque_q.ssqq_id'
                                     ,'icr_shopserviceque_q.sd_id'
                                     ,'icr_shopserviceque_q.ssqq_queserno'
                                     ,'icr_shopserviceque_q.ssqd_id'
                                     ,'icr_shopserviceque_q.ssqq_questarttime'
                                     ,'icr_shopserviceque_q.ssqq_receivtime'
                                     ,'icr_shopserviceque_q.ssqq_usestatus'
                                     ,'icr_shopserviceque_q.last_update_date'
                                     )->orderBy('icr_shopserviceque_q.last_update_date', 'desc')->get()->toArray();
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null; 
        }
    }
    /**
     * 查詢用戶需要取消服務排隊「服務排隊編號﹙$ssqq_id﹚」
     * @param type $ssqq_id
     * @param type $ssqd_id
     * @return type
     */ 
    public static function Query_FindNeedAbandomService($ssqq_id) {
        try {
            if (is_null($ssqq_id)) {
                return false;
            }
            $result = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->leftJoin('icr_shopserviceque_d', 'icr_shopserviceque_q.ssqd_id', '=', 'icr_shopserviceque_d.ssqd_id')
                            ->where('icr_shopserviceque_q.ssqq_id', '=', $ssqq_id)
                            ->get()->toArray();
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null; 
        }
    }
    
    public static function Query_FindData_BySSQQID($ssqq_id) {
        try {
            if (is_null($ssqq_id)) {
                return false;
            }
            $result = ICR_ShopServiceQue_q::where('icr_shopserviceque_q.isflag', '=', '1')
                            ->where('icr_shopserviceque_q.ssqq_id', '=', $ssqq_id) 
                            ->get()->toArray();
            return $result;
        } catch(Exception $e) {
          ErrorLog::InsertData($e);
          return null; 
        }
    }
}
