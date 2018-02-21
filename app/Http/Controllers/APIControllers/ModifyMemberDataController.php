<?php

namespace App\Http\Controllers\APIControllers;
use  App\Library\Commontools;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class ModifyMemberDataController extends Controller {

    function modifyMemberData() {
        $functionName = 'modifyMemberData';
        $inputString = Input::All();
        $inputData =  Commontools::ConvertStringToArray($inputString);
        if (!is_null($inputString) && count($inputString) != 0 && is_array($inputString)) {
            $inputString = $inputString[0];
        }
        $resultString;
        $resultData = null;
        $messageCode = null;

        try {
            if(!$this->CheckInput($inputData)){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
            //檢查身份模組驗證
             $memService = new \App\Services\MemberService;
             if ( !$memService->checkModuleAccount($inputData['modacc'], $inputData['modvrf'], $messageCode)) {
              //模組身份驗證失敗
              $messageCode = '999999961';
              throw new \Exception($messageCode);
            }
            if ( !$memService->checkServiceAccessToken($inputData['sat'], $md_id, $messageCode)) {
              //模組身份驗證失敗
              throw new \Exception($messageCode);
            }
            /*if ( !$memService->queryMemberInfo($inputData['sat'], $mur=null, $basicMemData, $messageCode) ) {
              //會員授權憑證失效
              //$messageCode =  '999999960';
              throw new \Exception($messageCode);
            }*/
           $basicMemData = [
                'md_id'                   => $inputData['md_id'] ,
                'md_cname'            => $inputData['md_cname'] ,
                'md_picturepath'      => $inputData['md_picturepath'] ,
                'md_logintype'         => $inputData['md_logintype'] ,
                'md_clienttype'        => $inputData['md_clienttype'] ,
                'md_clubjoinstatus'   => $inputData['md_clubjoinstatus'] ,
                'ssd_picturepath'     => $inputData['md_picturepath'],
                'md_city'                 => $inputData['md_city'],
                'md_country'          => $inputData['md_country'],
                'rl_city_code'           => $inputData['rl_city_code'],
                'rl_zip'                    => $inputData['rl_zip'],
                'ssd_fbgender'      => $inputData['md_fbgender'],
                'ssd_birthday'      => $inputData['md_birthday']
             ];
             if ( $md_id != $basicMemData['md_id']) {
                   throw new \Exception($messageCode);
             }
            if ( count(\App\Models\IsCarMemberData::GetData($md_id)) == 0 ) {
               if(!\App\Models\IsCarMemberData::InsertData($basicMemData, $md_id)) {
                   throw new \Exception($messageCode);
               }
            } else {
              if(!\App\Models\IsCarMemberData::UpdateData($basicMemData)) {
                   throw new \Exception($messageCode);
               }
            }
            $messageCode = '000000000';
        } catch (\Exception $e) {
            if ($messageCode == null) {
                \App\Models\ErrorLog::InsertData($e);
                $messageCode = '999999999';
            }
        }
        //回傳值
        $resultArray = \App\Library\Commontools::ResultProcess($messageCode, $resultData);
        \App\Library\Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);
        $result = [ $functionName . 'result' => $resultArray ];
        return $result;
    }
   

   /**
     * 檢查輸入值是否正確
     * @param type $value
     * @return boolean
     */
    function CheckInput(&$value) {
        if ($value == null) {
            return false;
        }
       if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'modacc', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'modvrf', 0, false, false)) {
            return false;
        }
        if (!\App\Library\Commontools::CheckRequestArrayValue($value, 'sat', 0, false, false)) {
            return false;
        }
        return true;
    }




}
