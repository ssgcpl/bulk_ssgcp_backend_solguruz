<?php

namespace App\Models\Helpers;

use Illuminate\Support\Facades\Storage;
use DB;
use Redirect;
use App\Models\Setting;
use App\Models\Payment;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Carbon\Carbon;




trait PaymentHelper
{
  //CCAVENUE Encrypt Request
  public function encrypt($plainText,$key)
  {
    $key = $this->hextobin(md5($key));
    $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
    $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
    $encryptedText = bin2hex($openMode);
    return $encryptedText;
  }

  /*
  * @param1 : Encrypted String
  * @param2 : Working key provided by CCAvenue
  * @return : Plain String
  */
  public function decrypt($encryptedText,$key)
  {
    $key = $this->hextobin(md5($key));
    $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
    $encryptedText = $this->hextobin($encryptedText);
    $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
    return $decryptedText;
  }

  public function hextobin($hexString) 
  { 
    $length = strlen($hexString); 
    $binString="";   
    $count=0; 
    while($count<$length) 
    {       
        $subString =substr($hexString,$count,2);           
        $packedString = pack("H*",$subString); 
        if ($count==0)
        {
        $binString=$packedString;
        } 
        
        else 
        {
        $binString.=$packedString;
        } 
        
        $count+=2; 
    } 
          return $binString; 
  } 

  public function refund_process($order_id) {

    $payment = Payment::where('order_id',$order_id)->where('status','paid')->latest()->first();
    if(!$payment) {
      return false;
    }
    $settings = Setting::pluck('value','name')->all();
    if($payment->payment_type == 'payu') {
        //curl -X POST "https://test.payu.in/merchant/postservice?form=2 -H "accept: application/json" -H "Content-Type: application/x-www-form-urlencoded" -d "key=JP***g&command=cancel_refund_transaction&var1=403993715521937565&var2=20201105secrettokenaturend&hash=10"

        if($settings['payu_mode'] == 'sandbox'){
          $gateway_url = $settings['payu_sandbox_url'].'merchant/postservice?form=2';
          $key = $settings['payu_sandbox_key'];
          $salt = $settings['payu_sandbox_salt'];
        }else{
          $gateway_url = $settings['payu_production_url'].'merchant/postservice?form=2';
          $key = $settings['payu_live_key'];
          $salt = $settings['payu_live_salt'];
        }
        $command = 'cancel_refund_transaction';
        //Genrate Hash Token
        $hash_string = $key.'|'.$command.'|'.$payment->tran_ref.'|'.$salt;
        $hash = hash('sha512', $hash_string);



        $url = $gateway_url;            
        $header = array("accept: application/json",
            "content-type: application/x-www-form-urlencoded");    

        /*$data = array();
        $data['key'] = $key;
        $data['command'] = $command;
        $data['var1'] = $payment->tran_ref;
        $data['var2'] = \Str::random(10);
        $data['hash'] = $hash;*/
        $amount = $payment->amount;
        $data =  "key=".$key."&command=".$command."&var1=".$payment->tran_ref."&var2=".\Str::random(10)."&hash=".$hash."&var3=".$amount."";

        // $postdata = json_encode($data);

        // print_r($data);die;
        // echo $postdata; die;

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        $result2 = $this->getCurlResult($result);
        // print_r($result);die;

        $payment->refund_tran_ref = @$result2['mihpayid'];
        $payment->refund_api_response = @$result;
        $payment->refund_status = 'initiated';
        $payment->save(); 
        // close handle to release resources
        curl_close($ch);
    }

    if($payment->payment_type == 'ccavenue') {
        
    }
  }

  public function getCurlResult($response) {
    $p1 = str_replace(['{','}','"'], '', $response);
    $p2 = explode(',', $p1);
    // return $p2;
    $data = [];
    foreach ($p2 as $key => $value) {
      $v = explode(':', $value);
      $data[$v[0]] = $v[1];
    }
    return $data;
  }

    
}
