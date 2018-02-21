<?php
namespace App\Services;

define('ADMINSERVICE_URL', config('global.ADMINSERVICE_URL'));

class AdminService {
  
    
    
     public  function CreateInvoice ($arraydata, &$response, &$post, &$messageCode) {
       try {
            $memService = new \App\Services\MemberService;
            if (! $memService->query_salt($salt_no, $salt)) {
                throw new \Exception($messageCode);
            }

            $modvrf = urlencode(base64_encode($salt_no.'_'.hash('sha256', MODACC.MODPWD.$salt)));
            $post = $this->CreateInvoicePostData($arraydata,$modvrf);
            if (is_null($post)) {
                throw new \Exception($messageCode);
            }
            $route = 'createinvoice';
           if (is_null($response = $this->curlModule($post, $route))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
            }
            $k = array_keys($response);
            if ($response[$k[0]]['message_no'] != '000000000') {
                $messageCode = $response[$k[0]]['message_no'];
                throw new \Exception($messageCode);
            }
            return true;
      } catch(\Exception $ex) {
           \App\Models\ErrorLog::InsertData($ex);
           return false;
      }
    }
    
    
    public function CreateInvoicePostData($arraydata,$modvrf) {
        try {
              $post = [
                            'modacc' => MODACC,
                            'modvrf'     => $modvrf,
                            "ril_shopid"=>$arraydata['ril_shopid'], //ex:pm的SD_id
                            "md_id"=>$arraydata['md_id'], 
                            "ril_ordernumber"=>$arraydata['ril_ordernumber'], // 交易單號 EX:pm的SCG_id
                            "ril_ordercreatedate"=>$arraydata['ril_ordercreatedate'],
                            "ril_orderpaydate"=>$arraydata['ril_orderpaydate'],
                            "ril_customeridentifier"=>$arraydata['ril_customeridentifier'],
                            "ril_customeraddr"=>$arraydata['ril_customeraddr'],
                            "ril_customerphone"=>$arraydata['ril_customerphone'],
                            "ril_customeremail"=>$arraydata['ril_customeremail'],
                            "ril_issuerequest"=>[
                                      "CheckMacValue" =>'',
                                      "CustomerName"=>$arraydata['CustomerName'],// 客戶姓名
                                      "CustomerAddr"=>$arraydata['ril_customeraddr'],// 客戶發票地址,預設為收件地址
                                      "CustomerPhone"=>$arraydata['ril_customerphone'],
                                      "CustomerEmail"=>$arraydata['ril_customeremail'],//必填,urlencode
                                      "CustomerAddr"=>$arraydata['ril_customeraddr'],
                                      "CustomerPhone"=>$arraydata['ril_customerphone'],
                                      "CustomerEmail"=>$arraydata['ril_customeremail'],
                                      "Print"=>"0", //當列印註記為 1(列印)時，"CustomerIdentifier","CustomerAddr"必須有值。
                                      "Donation"=>"0",
                                      'RelateNumber' =>$arraydata['RelateNumber'],
                                      "TaxType"=>"1",//填設填1
                                      "TimeStamp" =>time(),
                                      "SalesAmount"=>$arraydata['SalesAmount'],//參照綠界文件 , p.7
                                      "InvType" => "07",
                                      "InvoiceRemark"=>$arraydata['InvoiceRemark'],//參照綠界文件 , p.7
                                      "ItemName"=>$arraydata['ItemName'],//參照綠界文件 , p.7
                                      "ItemCount"=>$arraydata['ItemCount'],//參照綠界文件 , p.7
                                      "ItemWord"=>"份",//參照綠界文件 , p.7
                                      "ItemPrice"=>$arraydata['ItemPrice'],//參照綠界文件 , p.7
                                      "ItemTaxType"=>'1',//參照綠界文件 , p.7
                                      "ItemAmount"=>$arraydata['ItemAmount'],//參照綠界文件 , p.8
                                       "vat"=>"1"
                                ]  
                ];
              return $post;
        } catch(\Exception $e) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
    }
    
    public  function InvalidInvoice ($arraydata, &$response, &$post) {
       try {
            $memService = new \App\Services\MemberService;
            if (! $memService->query_salt($salt_no, $salt)) {
                throw new \Exception($messageCode);
            }

            $modvrf = urlencode(base64_encode($salt_no.'_'.hash('sha256', MODACC.MODPWD.$salt)));
            $post = [
                            'modacc' => MODACC,
                            'modvrf'     => $modvrf,
                            'ril_shopid' =>$arraydata['ril_shopid'],
                            'ril_ordernumber' =>$arraydata['ril_ordernumber'],
                            'ril_voidreason' =>$arraydata['ril_voidreason'],
                            'ril_receiptnumber' =>$arraydata['ril_receiptnumber']
                ];
            $route = 'invalidinvoice';
           if (is_null($response = $this->curlModule($post, $route))) {
                 $messageCode = '999999999';
                 throw new \Exception($messageCode);
            }
            $k = array_keys($response);
            if ($response[$k[0]]['message_no'] != '000000000') {
                $messageCode = $response[$k[0]]['message_no'];
                throw new \Exception($messageCode);
            }
            return true;
      } catch(\Exception $ex) {
           \App\Models\ErrorLog::InsertData($ex);
           return false;
      }
    }

     /**
     * Curl模組化使用
     * @param type array $post 傳送資料
     * @param type string $route 傳送route
     * @return array or null
     */
      function curlModule ($post, $route) {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => ADMINSERVICE_URL.$route,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode(json_encode($post)),
                CURLOPT_HTTPHEADER => array(
                  "cache-control: no-cache",
                  "content-type: application/json",
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                throw new \Exception($err);
            } else {
                return \App\Library\Commontools::ConvertStringToArray($response);
            }
        } catch(\Exception $ex) {
            \App\Models\ErrorLog::InsertData($ex);
            return null;
        }
  }

}
