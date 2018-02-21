<?php

namespace App\Http\Controllers\APIControllers\ShopManage;
use Hash;
use App\Library\Commontools;
use Illuminate\Support\Facades\Input;
/** loginwebendadmin	���ҰӮa���U�H�c�����ҽX */
class LoginWebendAdmin {
   function loginwebendadmin() {
        $functionName = 'loginwebendadmin';
        $inputString = Input::All();
        $inputData = Commontools::ConvertStringToArray($inputString);
        if(!is_null($inputString) && count($inputString) != 0 && is_array($inputString)){
           $inputString = $inputString[0];
        }
        $resultData = null;
        $messageCode = null;
        try{
            //��J��
            if(!LoginWebendAdmin::CheckInput($inputData)){
               $messageCode = '999999995';
               throw new \Exception($messageCode);
            }
           //�ˬd�����Ҳ�����
            $memService = new \App\Services\MemberService;
            if ( !$memService->checkModuleAccount($inputData['modacc'], $inputData['modvrf'], $messageCode)) {
              //�Ҳը������ҥ���
              $messageCode = '999999961';
              throw new \Exception($messageCode);
            }
            if (!$memService->checkServiceAccessToken($inputData['sat'], $md_id, $messageCode)) {
              //�I�s�uMemberAPI�v�ˬdSAT�����A�A����SAT���ĩ�
               //$messageCode = '999999962';
               throw new \Exception($messageCode);
            }
            //�ˬd�n�J�覡
            if ( $inputData['login_type'] != 0 ) {
               $messageCode = '999999989';
               throw new \Exception($messageCode);
            }
            //�ˬd�uFacebook�b���v�ñN�uAccess Token�v�󴫦����ī�
            if ( !LoginWebendAdmin::CheckFaceBookAccount($inputData['ssd_accesstoken'], $inputData['ssd_accountid'], $token)) {
               $messageCode = '010101001';
               throw new \Exception($messageCode);
            }
            
            if ( !LoginWebendAdmin::CheckMemberDataByAccountid($inputData['ssd_accountid'], $md_id, $messageCode)) {
               throw new \Exception($messageCode); 
            }
            //�ˬd�uIsCarMemberMobileLink�v�O�_�w�s�b�A�Y���b�h�إ�
            $mur_id = 'shopwebendadmin00000000';
            if ( !LoginWebendAdmin::CheckExistAndCreate_MemberMobileLink(5, $md_id, $mur_id)) {
               throw new \Exception($messageCode); 
            }
            //�ˬd�uIsCarServiceAccessToken�v�O�_�s�b�A�Y���s�b�h�إߡA�Y�s�b�h�ˬd�O�_�w�L��
            if ( !LoginWebendAdmin::CreateOrUpdate_ServiceAccessToken(5, $md_id, $mur_id, $servicetoken)) {
               throw new \Exception($messageCode);  
            }
            
            if (!LoginWebendAdmin::Check_is_ICR($inpuData['sat'], $md_id, $shopdata)) {
               $messageCode = '010902002';
               throw new \Exception($messageCode);
            }   
            
            $udc = base64_encode(hash('sha256', $md_id.$mur_id, True));
            $resultData = LoginWebendAdmin::CreateResultData($udc, $servicetoken, $shopdata, $md_id);
            $messageCode ='010101000';
         } catch(\Exception $e){
           if (is_null($messageCode)) {
                $messageCode = '999999999';
                \App\Models\ErrorLog::InsertData($e);
            }
        }
        $resultArray = Commontools::ResultProcess($messageCode, $resultData);
        Commontools::WriteExecuteLog($functionName, $inputString, json_encode($resultArray), $messageCode);   
        $result = [$functionName . 'result' => $resultArray];
        return $result;
   }
    /**
     * �ˬd��J�ȬO�_���T
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
        if (!Commontools::CheckRequestArrayValue($value, 'login_type', 1, false, false)) {
            return false;
        } 
        if (!Commontools::CheckRequestArrayValue($value, 'ssd_accountid', 0, true, false)) {
            return false;
        } 
        if (!Commontools::CheckRequestArrayValue($value, 'ssd_accesstoken', 0, true, true)) {
            return false;
        } 
        return true;
    }
   
   
   /**
     * �ˬd�uFacebook�b���v�ñN�uAccess Token�v�󴫦����ī�
     * @param type $accesstoken
     * @param type $accountid
     * @param type $token
     * @return boolean
     */
    function CheckFaceBookAccount($accesstoken, $accountid, &$token) {
        try {
            $acc_id = Commontools::GetFacebookAccountID($accesstoken);

            if ($acc_id == null || $acc_id != $accountid) {
                return false;
            }

            $token = $accesstoken;
            /*
              $token = Commontools::GetFacebookLongLivedAccessToken($accesstoken);

              if ($token == null) {
              return false;
              }
             */
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    
    function CheckMemberDataByAccountid($accountid, &$md_id, &$messageCode) {
        try {
          $memberData = \App\Models\IsCarMemberData::GetDataByAccountID($accountid);
          if( count($memberData) > 1 ) {
            $messageCode = '999999987';
            return false;
          } else if ( is_null($memberData) || count($memberData) == 0 ) {
            $messageCode = '999999996';
            return false;
          } else if ( $memberData[0]['md_clienttype'] != 1 ) {
            $messageCode = '010902003';
            return false;
          }
          $md_id = $memberData[0]['md_id'];
          return true;
        } catch(\Exception $e) {
          \App\Models\ErrorLog::InsertData($e);
          return false;
        }
    }
    
    
     /**
     * �ˬd�uIsCarMemberMobileLink�v�O�_�w�s�b�A�Y���b�h�إ�
     * @param type $mml_apptype
     * @param type $md_id
     * @param type $mur_id
     * @return boolean
     */
    function CheckExistAndCreate_MemberMobileLink($mml_apptype, $md_id, $mur_id) {
        try {
        
            $selectdata = \App\Models\IsCarMemberMobileLink::GetDataByMDID_MURID($mml_apptype, $md_id, $mur_id);

            if (count($selectdata) == 0) {
                if (!\App\Models\IsCarMemberMobileLink::InsertData(array('mml_apptype' => $mml_apptype, 'md_id' => $md_id, 'mur_id' => $mur_id), $mml_serno)) {
                   return false;
                }
            }
            return true;
        } catch (\Exception $e) { 
            return false;
        }
    }
    
     /**
     * �ˬd�uIsCarServiceAccessToken�v�O�_�s�b�A�Y���s�b�h�إߡA�Y�s�b�h�ˬd�O�_�w�L��
     * @param type $sat_apptype
     * @param type $md_id
     * @param type $mur_id
     * @param type $servicetoken
     * @return boolean
     */
    function CreateOrUpdate_ServiceAccessToken($sat_apptype, $md_id, $mur_id, &$servicetoken) {
        if ($sat_apptype == null || strlen($sat_apptype) == 0 || $md_id == null || strlen($md_id) == 0 || $mur_id == null || strlen($mur_id) == 0) {
            return false;
        }
        //�ˬd�uIsCarServiceAccessToken�v���
        $selectdata = \App\Models\IsCarServiceAccessToken::GetDataByMDID_MURID($sat_apptype, $md_id, $mur_id);
        if (count($selectdata) != 0) {
            //�����
            $expiredate = new \DateTime($selectdata[0]['sat_expiredate']);
            $datenow = new \DateTime('now');
            if ($expiredate > $datenow) {
                //���L��
                $servicetoken = $selectdata[0]['sat_token'];
                return true;
            } else {
                //�w�L���A��s��Ƭ��L��
                $selectdata[0]['sat_effective'] = '2';
                if (!\App\Models\IsCarServiceAccessToken::UpdateData($selectdata[0])) {
                    return false;
                }
            }
        }
        if (\App\Models\IsCarServiceAccessToken::InsertData(array('sat_apptype' => $sat_apptype, 'md_id' => $md_id, 'mur_id' => $mur_id), $sat_serno)) {
            $querydata = \App\Models\IsCarServiceAccessToken::GetData($sat_serno);
            if (count($querydata) == 0) {
                return false;
            }
            $servicetoken = $querydata[0]['sat_token'];
            return true;
        }
        return false;
    }
    
    
    private function Get_ShopData($sd_id, $smb_shoptype, $smb_validity, $smb_activestatus, $smb_bindlevel) {

        $querydata = \App\Models\ICR_ShopData::Getdata($sd_id);

        if (count($querydata) == 0) {
            return null;
        }

        $shopdata =  [
                      'sd_id'            => $querydata[0]['sd_id'],
                      'sd_shopname'      => $querydata[0]['sd_shopname'],
                      'sd_shopphotopath' => $querydata[0]['sd_shopphotopath'],
                      'smb_shoptype'     => $smb_shoptype,
                      'smb_validity'     => $smb_validity,
                      'smb_activestatus' => $smb_activestatus,
                      'smb_bindlevel'    => $smb_bindlevel
                     ];
        
        return $shopdata;
    }
    
    
     public function Check_is_ICR( $sat, $md_id, &$shopdata) {

        $shopdata = null;
        $memberdata = \App\Models\IsCarMemberData::GetData($md_id);
        $memService = new \App\Services\MemberService;
        if (count($memberdata) == 0) {
            return false;
        }
        if ($memberdata[0]['md_clienttype'] == 0) {
            return false;
        }

        $querydata = \App\Models\ICR_SdmdBind::GetData_ByMd_ID($md_id, false);

        if (count($querydata) == 0) {
            //��s �uiscarmembertdata . md_clienttype �v �� �q0�G�@��Τ�ݡr
            \App\Models\IsCarMemberData::UpdateData_ClientType($md_id, '0');
            $memService->modify_member_clienttype('', $sat, 0, $messageCode);
            $servicetoken = null;
            return false;
        }

        $have_expired_data = false;
        foreach ($querydata as $rowdata) {
            if (strtotime('now') > strtotime($rowdata['smb_validity'])) {
                //�w�L���A��s�uicr_sdmdbind.smb_activestatus = '2'�v
                LoginWebendAdmin::Update_ICR_SdmdBind_Activestatus($rowdata['smb_serno'], '2');
                $have_expired_data = true;
            } else {
                $shopdata[] = LoginWebendAdmin::GetShopData(($rowdata['smb_sd_id']),($rowdata['smb_shoptype']),($rowdata['smb_validity']),($rowdata['smb_activestatus']),($rowdata['smb_bindlevel']));
            }
        }
        if ($have_expired_data) {
            return LoginWebendAdmin::Check_is_ICR($md_id, $shopdata);
        } else {

            return true;
        }
    }
    
    
    /**
     * �إߦ^�ǭ�
     * @return type
     */
    public function CreateResultData($udc, $accesstoken, $shopdata, $md_id) {
        $resultdata = [
                         'servicetoken'   => $accesstoken ,
                         'udc'            => $udc ,
                         'md_id'          => $md_id,
                         'shopdata_array' => $shopdata ,
                      ];
        return $resultdata;
    }

     public function GetShopData($sd_id, $smb_shoptype, $smb_validity, $smb_activestatus, $smb_bindlevel) {

        $querydata = \App\Models\ICR_ShopData::Getdata($sd_id);

        if (count($querydata) == 0) {
            return null;
        }

        $shopdata =  [
                      'sd_id'            => $querydata[0]['sd_id'],
                      'sd_shopname'      => $querydata[0]['sd_shopname'],
                      'sd_shopphotopath' => $querydata[0]['sd_shopphotopath'],
                      'smb_shoptype'     => $smb_shoptype,
                      'smb_validity'     => $smb_validity,
                      'smb_activestatus' => $smb_activestatus,
                      'smb_bindlevel'    => $smb_bindlevel
                     ];
        
        return $shopdata;
    }  
    
    public function Update_ICR_SdmdBind_Activestatus($smb_serno, $smb_activestatus) {

        $modifydata['smb_serno'] = $smb_serno;
        $modifydata['smb_activestatus'] = $smb_activestatus;

        return \App\Models\ICR_SdmdBind::UpdateData($modifydata);
    } 
 
}