<?php

namespace App\Http\Controllers\PaymentMethod;  

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\PaymentLog;
use App\Http\Controllers\PaymentMethod\PaymentController;
use App\Http\Controllers\PaymentMethod\PayTm\PaytmChecksum as PaytmChecksum;

class PaymentWithPaytm extends Controller
{
   public function getTransactionToken(Request $request){

    $generatedOrderID = "PYTM_BLINK_".time();
    
    $amount = "1.00";
    $callbackUrl = '';

    $paytmParams["body"] = array(
                                "requestType"   => "Payment",
                                "mid"           => $request->paytm_mid,
                                "websiteName"   => $request->paytm_website,
                                "orderId"       => $generatedOrderID,
                                "callbackUrl"   => $callbackUrl,
                                "txnAmount"     => array(
                                                        "value" => $amount,
                                                        "currency" => "INR",
                                                    ),
                                "userInfo"      => array(
                                                    "custId" => "CUST_".time(),
                                                ),
                            );

        $generateSignature = PaytmChecksum::generateSignature(json_encode($paytmParams['body'], JSON_UNESCAPED_SLASHES), $request->paytm_merchant_key);

        $paytmParams["head"] = array(
                                "signature" => $generateSignature
                            );

        $url = $request->paytm_environment."/theia/api/v1/initiateTransaction?mid=". $request->paytm_mid ."&orderId=". $generatedOrderID;

        $getcURLResponse = $this->getcURLRequest($url, $paytmParams);

        if(!empty($getcURLResponse['body']['resultInfo']['resultStatus']) && $getcURLResponse['body']['resultInfo']['resultStatus'] == 'S'){
            $result = array('success' => true, 'orderId' => $generatedOrderID, 'txnToken' => $getcURLResponse['body']['txnToken'], 'amount' => $amount, 'message' => $getcURLResponse['body']['resultInfo']['resultMsg']);
        }else{
            $result = array('success' => false, 'orderId' => $generatedOrderID, 'txnToken' => '', 'amount' => $amount, 'message' => $getcURLResponse['body']['resultInfo']['resultMsg']);
        }
        return $result;
    
    }


    public function getcURLRequest($url , $postData = array(), $headers = array("Content-Type: application/json")){

        $post_data_string = json_encode($postData, JSON_UNESCAPED_SLASHES);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
        $response = curl_exec($ch);
        return json_decode($response,true);
    }

    public function getSiteURL(){
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        return str_replace('config.php', '', $actual_link);
    }

    public function transactionStatus(Request $request)
    {
        /* initialize an array */
        $paytmParams = array();

        /* body parameters */
        $paytmParams["body"] = array(
            "mid" => PAYTM_MID,
            /* Enter your order id which needs to be check status for */
            "orderId" => "YOUR_ORDERId_Here",
        );
        $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), PAYTM_MERCHANT_KEY);

        /* head parameters */
        $paytmParams["head"] = array(
            "signature" => $checksum
        );

        /* prepare JSON string for request */
        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

        $url = PAYTM_ENVIRONMENT."/v3/order/status";

        $getcURLResponse = getcURLRequest($url, $paytmParams);
        return $getcURLResponse;
    }

    public function ipn(Request $request)
    {
        // code...
    }
}
