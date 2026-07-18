<?php

class Unipesa{
    private $public_id = "a953dee5ba57d3826ddb0c1e2a965a35c96498d2";
    private $merchant_id = "d07a0e58ee7049afccfe88b10bd2aa4295ef3a7b";
    private $secretKey = "727bace26d269c9631f3c2dc2ccc2f091f51f2e0h808725af01a1dc6521bc81fb8316a64771bf34c566wc7088f664e068b5e4552e63e1bd1162b3fc8bf5f4jbq";

    private $C2B_URL;

    //private const URL_REQUEST = "https://acs2test.quipugmbh.com:6443/Exec";
    //private const APPROVE_URL = "https://www.maishapay.net/marchand/pay/suucess.php";
    //private const CANCEL_URL = "https://www.maishapay.net/marchand/pay/echec.php";
    //private const DECLINE_URL = "https://www.maishapay.net/marchand/pay/echec.php";




    private $language;
    private $amount;
    private $currency;
    private $description;
    private $orderType;
    private $code_pin;
    private $telephone;
    private $email;
    private $fee;
    private $status;
    private $orderID;
    private $sessionID;
    private $url;

    ################################################################################################################
    ################################################## CONSTRUCTOR #################################################
    ################################################################################################################

    /**
     * EquityBCDC constructor.
     */
    public function __construct($options = array()){

        if ($this->public_id) {
            $this->updateEndPoint();
            
        }

        foreach ($options as $keyOption => $option) {
            $this->{$keyOption} = $option;
        }

    }


    public function updateEndPoint($public_id = null){

        if ($public_id) {
            $this->public_id = $public_id;
        }else{
            $public_id = $this->public_id;
        }

        if ($public_id) {
            $PostMethods = array('C2B_URL' => 'payment_c2b', 'STATUS_URL' => 'status', 'B2C_URL' => 'payment_b2c');
            foreach ($PostMethods as $keyURL => $Method) {
                $this->{$keyURL} = 'https://api.unipesa.com/'.$public_id.'/'.$Method;
            }
        }
    }

    public function calculateSignature(array $data, string $secretKey, string $currentParamPrefix = '', int $depth = 16, int $currentRecursionLevel = 0 ): string
    {
        if ($currentRecursionLevel >= $depth) {
            throw new Exception('Recursion level exceeded');
        }

        $stringForSignature = '';
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                    $stringForSignature .= $this->calculateSignature(
                    $value,
                    $secretKey,
                    "$currentParamPrefix$key.", 
                        $depth,
                    $currentRecursionLevel + 1
                );
          } else if ($key !== 'signature') {
                    $stringForSignature .= "$currentParamPrefix$key" . $value;
          }
       }

        if ($currentRecursionLevel == 0) {
          return strtolower(hash_hmac('sha512', $stringForSignature, $secretKey));
        } else {
          return $stringForSignature;
        }
     }


    /**
     * @param $url
     * @param $xml
     * @return bool|string
     * @description function to post generated xml with cUrl
     */
    private function sendOverPost($url,$postData){

        $postData = array(
            'merchant_id' => $this->merchant_id,
        ) + $postData;

        $signature = $this->calculateSignature($postData, $this->secretKey);
        $postData['signature'] = $signature;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //for xml, change the content-type
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/json", "Access-Control-Allow-Origin: *")); //  multipart/form-data
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return results
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        //send and return data to caller
        $result = curl_exec($ch);
        $result = (array) @json_decode($result, true);

        if(curl_errno($ch)) {
            $error[] = curl_errno($ch);
            $error[] =  curl_error($ch);
            $error = implode($error, ', ');

            $result['success'] = false;
            $result['error'] = $error;

        } else {
            $result['success'] = true;

            curl_close($ch);
        }

        return $result;
    }


    private function updateProvider($options = null) {

        if ($options AND is_string($options)) {
            $options['customer_id'] = $options;
            
        }

        if (empty($options['customer_id'])) {
            return array();
        }

        // Check Operateur Mobile
        $phone = str_replace(array(' '), '', $options['customer_id']);

        $countryMobileCode = 243;
        $countryMobileLength = 10;

        if(strpos($phone, (string) $countryMobileCode) === 0){
            $phone = '+'.$phone;
        }

        $phone = str_replace(array('+'.$countryMobileCode , '00'.$countryMobileCode), '0', $phone);

        $phonePrefix = substr($phone, 0, 3);
        $fullphone = substr_replace($phone, $countryMobileCode, 0, 1);

        $fullphoneplus = '+'.$fullphone;

        if ($phone) {
            $options['customer_id'] = $phone;
        }


        $options['provider_id'] = 14; // Vodacom
        if (in_array($phonePrefix, array('081', '082', '083'))) {
            $options['provider_id'] = 9; // Vodacom
        }elseif (in_array($phonePrefix, array('080', '084', '085', '089'))) {
            $options['provider_id'] = 10; // Orange
        }elseif (in_array($phonePrefix, array('097', '098', '099'))) {
            $options['customer_id'] = substr($phone, 1);
            $options['provider_id'] = 17; // Airtel DRC
        }elseif (in_array(substr($phonePrefix, 0, 2), array('97', '98', '99'))) {
            $options['provider_id'] = 17; // Airtel DRC
            $phone = '0'.$phone; // Airtel DRC
        }elseif (in_array($phonePrefix, array('090', '091'))) {
            $options['provider_id'] = 19; // Africell
        }

        /*
        11: Ecocash
        12: Safaricom
        15 : Unipesa Payment Gate (Test)
        16: Airtel Kenya
        */

        if (strlen($phone) ==  $countryMobileLength) {
            return $options;
        }

        return array();

    }

    public function generateToken($start = null, $length = 16, $possible = null) {

        if (!$possible) $possible = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $count = true;
        while ($count) {

            $token = "";
            $i = 0;

            while ($i < $length) {
                $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
                $token .= $char;
                $i++;
            }

            $token = $start.$token;

            $count = false;

        }

        return $token;
    }

    public function updateOrderID($options = array()) {

        $order_id = @$options['order_id'];

        if ($order_id) {
            $order_id = $order_id.'_';
        }

        $options['order_id'] = $this->generateToken($order_id,8);

        return $options;
    }

    public function createOrder($options){

        $options = $this->updateProvider($options) + $options;
        $options = $this->updateOrderID($options) + $options;

        $urlReq = $this->C2B_URL;
        $response = $this->sendOverPost($urlReq, $options);// response from server

        $response = $response + $options;

        return $response;

    }


    public function status($options){

        $urlReq = $this->STATUS_URL;
        $response = $this->sendOverPost($urlReq, $options);// response from server with url and xml

        $response = $response + $options;

        return $response;

    }



    public function B2C($options){

        $options = $this->updateProvider($options) + $options;
        $options = $this->updateOrderID($options) + $options;

        $urlReq = $this->B2C_URL;
        $response = $this->sendOverPost($urlReq, $options);// response from server

        $response = $response + $options;

        return $response;

    }




}
