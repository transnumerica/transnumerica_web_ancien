<?php

class EquityBCDC{
    private const MERCHANT_ID = "TEST_ECOM339";
    private const URL_REQUEST = "https://acs2test.quipugmbh.com:6443/Exec";
    private const APPROVE_URL = "https://www.maishapay.net/marchand/pay/suucess.php";
    private const CANCEL_URL = "https://www.maishapay.net/marchand/pay/echec.php";
    private const DECLINE_URL = "https://www.maishapay.net/marchand/pay/echec.php";
    private $operation;
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
    public function __construct($amount = null, $currency = null){
        $this->setLanguage("FR");
        $this->setFee("");
        $this->setDescription("Paiement par Maihapay");
        $this->setAmount($amount);
        $this->setCurrency($currency);
    }

    ####################################################################################################################
    ############################################### PUBLIC CLASS METHODS ###############################################
    ####################################################################################################################

    /**
     * @param $url
     * @param $xml
     * @return bool|string
     * @description function to post generated xml with cUrl
     */
    private function sendOverPost($url,$xml){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //for xml, change the content-type
        curl_setopt($ch, CURLOPT_SSLKEY, __DIR__ . "/SSL/key.pem");
        curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . "/SSL/ca.pem");
        curl_setopt($ch, CURLOPT_SSLCERT, __DIR__ . "/SSL/cert.pem");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml", "Access-Control-Allow-Origin: *")); //  multipart/form-data
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return results
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        //send and return data to caller
        $result = curl_exec($ch);

        if(curl_errno($ch)) {
            print curl_errno($ch);
            print curl_error($ch);
        } else {
            curl_close($ch);
        }
        return $result;
    }
    
    /**
     * @throws Exception
     * @description CREATE ORDER
     */
    public function createOrder(): void{
        //if(verification_code_pin($this->getTelephone(), $this->getCodePin())){
            //xml for create order
            $xw = xmlwriter_open_memory();
            xmlwriter_set_indent($xw, 1);

            xmlwriter_start_document($xw, '1.0', 'UTF-8');

            // A first element
            xmlwriter_start_element($xw, 'TKKPG'); // open xml tag

            // Start a child element
            xmlwriter_start_element($xw, 'Request'); // open xml tag

            xmlwriter_start_element($xw, 'Operation'); //open operation xml element
            xmlwriter_text($xw, "CreateOrder"); //xml operation (data from variables)
            xmlwriter_end_element($xw); // Operation close xml tag

            xmlwriter_start_element($xw, 'Language'); // open language xml element
            xmlwriter_text($xw, $this->getLanguage()); //xml language (data from variables)
            xmlwriter_end_element($xw); // Language close xml tag

            xmlwriter_start_element($xw, 'Order'); // open xml tag

            xmlwriter_start_element($xw, 'Merchant'); // open xml tag
            xmlwriter_text($xw, self::MERCHANT_ID); //xml merchant (data from variables)
            xmlwriter_end_element($xw); // Merchant close xml tag

            xmlwriter_start_element($xw, 'Amount'); // open xml tag
            xmlwriter_text($xw, $this->getAmount()); //xml amount
            xmlwriter_end_element($xw); // Amount close xml tag

            xmlwriter_start_element($xw, 'Currency'); // open xml tag
            xmlwriter_text($xw, $this->getCurrency()); //xml currency (data from variables)
            xmlwriter_end_element($xw); // Currency close xml tag

            xmlwriter_start_element($xw, 'Description'); // open xml tag
            xmlwriter_text($xw, $this->getDescription());// xml description (data from variables)
            xmlwriter_end_element($xw); // Description close xml tag

            xmlwriter_start_element($xw, 'ApproveURL'); // open xml tag
            xmlwriter_text($xw, self::APPROVE_URL); //(data from variables)
            xmlwriter_end_element($xw); // ApproveURL close xml tag

            xmlwriter_start_element($xw, 'CancelURL'); // open xml tag
            xmlwriter_text($xw, self::CANCEL_URL); //(data from variables)
            xmlwriter_end_element($xw); // CancelURL close xml tag

            xmlwriter_start_element($xw, 'DeclineURL'); // open xml tag
            xmlwriter_text($xw, self::DECLINE_URL); //(data from variables)
            xmlwriter_end_element($xw); // DeclineURL close xml tag

            xmlwriter_start_element($xw, 'OrderType'); // open xml tag
            xmlwriter_text($xw, "Purchase"); //(data from variables)
            xmlwriter_end_element($xw);  //close xml tag

            xmlwriter_start_element($xw, 'Fee'); // open xml tag
            xmlwriter_text($xw, $this->getFee()); //(data from variables)
            xmlwriter_end_element($xw);  //close xml tag

            xmlwriter_end_element($xw); // Order close xml tag

            xmlwriter_end_element($xw); // Request close xml tag

            xmlwriter_end_element($xw); // TKKPG close xml tag

            xmlwriter_end_document($xw);

            $xmlData = xmlwriter_output_memory($xw);

            $urlReq = self::URL_REQUEST;

            $response = $this->sendOverPost($urlReq, $xmlData);// response from server with url and xml

            $xmlRes = new SimpleXMLElement($response); //parse xml response

            $data = array(
                "Operation" => (string)$xmlRes->Response[0]->Operation,
                "Status" => (string)$xmlRes->Response[0]->Status,
                "Order" => [
                    "OrderID" => (string)$xmlRes->Response[0]->Order[0]->OrderID, //getting orderID from response
                    "SessionID" => (string)$xmlRes->Response[0]->Order[0]->SessionID, //getting sessionID from response
                    "Url" => (string)$xmlRes->Response[0]->Order[0]->URL //getting URL from response
                ]
            );

            if($data["Status"] === "00"){
                //generate url to redirect browser
                $redirect = $data["Order"]["Url"]."?ORDERID=".$data["Order"]["OrderID"]."&SESSIONID=".$data["Order"]["SessionID"];
                $data["Order"]["Url"] = $redirect;
                http_response_code(200);
                echo json_encode([
                    "message" => $this->statusCodeErrorDescriptionFrench($data["Status"]),
                    "data" => $data
                    ]);
            }else{
                http_response_code(401);
                echo json_encode([
                    "message" => $this->statusCodeErrorDescriptionFrench($data["Status"]),
                    "data" => []
                    ]);
            }
        /*}else {
            http_response_401("Vous n'êtes pas autorisé à effectuer cette transaction !");
        }*/
    }

    /**
     * @return bool|string
     * @description GET ORDER STATUS
     * @throws Exception
     */
    public function getOrderStatus(): void{
        //xml for get order status
        $xw1 = xmlwriter_open_memory();
        xmlwriter_set_indent($xw1, 1);

        xmlwriter_start_document($xw1, '1.0', 'UTF-8');

        // A first element
        xmlwriter_start_element($xw1, 'TKKPG'); // open xml tag

        // Start a child element
        xmlwriter_start_element($xw1, 'Request'); // open xml tag

        xmlwriter_start_element($xw1, 'Operation'); //open operation xml element
        xmlwriter_text($xw1, "GetOrderStatus"); //xml operation
        xmlwriter_end_element($xw1); // Operation close element

        xmlwriter_start_element($xw1, 'Language'); // open language xml element
        xmlwriter_text($xw1, $this->getLanguage()); //xml language
        xmlwriter_end_element($xw1); // Language close element

        xmlwriter_start_element($xw1, 'Order'); // open xml tag

        xmlwriter_start_element($xw1, 'Merchant'); // open xml tag
        xmlwriter_text($xw1, self::MERCHANT_ID); //xml merchant
        xmlwriter_end_element($xw1); // Merchant

        xmlwriter_start_element($xw1, 'OrderID'); // open xml tag
        xmlwriter_text($xw1, $this->getOrderID());  //xml orderID
        xmlwriter_end_element($xw1); // OrderID

        xmlwriter_end_element($xw1); // Order

        xmlwriter_start_element($xw1, 'SessionID'); // open xml tag
        xmlwriter_text($xw1, $this->getSessionID()); //xml sessionID
        xmlwriter_end_element($xw1); // SessionID

        xmlwriter_end_element($xw1); // Request

        xmlwriter_end_element($xw1); // TKKPG

        xmlwriter_end_document($xw1);

        $xmlData1 = xmlwriter_output_memory($xw1);


        //order status
        $urlReq = "https://acs2test.quipugmbh.com:6443/Exec";
        // response from server with url and xml
        $response = $this->sendOverPost($urlReq, $xmlData1);

        $xmlRes = new SimpleXMLElement($response); //parse xml response

        $data = array(
            "Operation" => (string)$xmlRes->Response[0]->Operation,
            "Status" => (string)$xmlRes->Response[0]->Status,
            "Order" => [
                "OrderID" => (string)$xmlRes->Response[0]->Order[0]->OrderID, //getting orderID from response
                "OrderStatus" => (string)$xmlRes->Response[0]->Order[0]->OrderStatus, //getting OrderStatus from response
            ]
        );

        if($data["Status"] === "00"){
            http_response_code(200);
            echo json_encode([
                "message" => $this->statusCodeErrorDescriptionFrench($data["Status"]),
                "data" => $data
            ]);
        }else{
            http_response_code(401);
            echo json_encode([
                "message" => $this->statusCodeErrorDescriptionFrench($data["Status"]),
                "data" => []
            ]);
        }
    }

    //STOP AUTO RECUR PAYMENT
    // accepts 5 variables
    public function stopAutoRecur($operation,$language,$merchant,$orderid,$sessionid){

        //xml for get order status
        $xw2 = xmlwriter_open_memory();
        xmlwriter_set_indent($xw2, 1);
        $res2 = xmlwriter_set_indent_string($xw2, ' ');

        xmlwriter_start_document($xw2, '1.0', 'UTF-8');

        // A first element
        xmlwriter_start_element($xw2, 'TKKPG');

        // Start a child element
        xmlwriter_start_element($xw2, 'Request');

        xmlwriter_start_element($xw2, 'Operation'); //open operation xml element
        xmlwriter_text($xw2, $operation); //xml operation
        xmlwriter_end_element($xw2); // Operation close element

        xmlwriter_start_element($xw2, 'Language'); // open language xml element
        xmlwriter_text($xw2, $language); //xml language
        xmlwriter_end_element($xw2); // Language close element

        xmlwriter_start_element($xw2, 'Order');

        xmlwriter_start_element($xw2, 'Merchant');
        xmlwriter_text($xw2, $merchant); //xml merchant
        xmlwriter_end_element($xw2); // Merchant

        xmlwriter_start_element($xw2, 'OrderID');
        xmlwriter_text($xw2, $orderid);  //xml orderID
        xmlwriter_end_element($xw2); // OrderID

        xmlwriter_end_element($xw2); // Order

        xmlwriter_start_element($xw2, 'SessionID');
        xmlwriter_text($xw2, $sessionid); //xml sessionID
        xmlwriter_end_element($xw2); // SessionID

        xmlwriter_end_element($xw2); // Request

        xmlwriter_end_element($xw2); // TKKPG

        xmlwriter_end_document($xw2);

        $xmlData2 = xmlwriter_output_memory($xw2);


        //order status
        $urlReq = "https://acs2test.quipugmbh.com:6443/Exec";
        $stoAutoRecurStatus = sendOverPost($urlReq, $xmlData2);// response from server with url and xml
        return $stoAutoRecurStatus;
    }

    // CREATE RECUR PAYMENT
    // accepts 15 variables
    public function createRecurPayment($operation = null, $language = null, $merchant = null, $amount = null, $currency = null, $description = null, $approveURL = null, $cancelURL = null, $declineURL = null, $recurFrequency = null, $recurEndRecur = null, $recurPeriod = null, $recurRemoveOnDecline = null, $orderType = null, $fee = null){

        if (is_array($operation)) {
            extract($operation);
        }

        //xml for create order
        $xw3 = xmlwriter_open_memory();
        xmlwriter_set_indent($xw3, 1);
        $res3 = xmlwriter_set_indent_string($xw3, ' ');

        xmlwriter_start_document($xw3, '1.0', 'UTF-8');

        // A first element
        xmlwriter_start_element($xw3, 'TKKPG');

        // Start a child element
        xmlwriter_start_element($xw3, 'Request');

        xmlwriter_start_element($xw3, 'Operation'); //open operation xml element
        xmlwriter_text($xw3, $operation); //xml operation (data from variables)
        xmlwriter_end_element($xw3); // Operation close element

        xmlwriter_start_element($xw3, 'Language'); // open language xml element
        xmlwriter_text($xw3, $language); //xml language (data from variables)
        xmlwriter_end_element($xw3); // Language close element

        xmlwriter_start_element($xw3, 'Order');

        xmlwriter_start_element($xw3, 'Merchant');
        xmlwriter_text($xw3, $merchant); //xml merchant (data from variables)
        xmlwriter_end_element($xw3); // Merchant

        xmlwriter_start_element($xw3, 'Amount');
        xmlwriter_text($xw3, $amount*100); //xml amount
        xmlwriter_end_element($xw3); // Amount

        xmlwriter_start_element($xw3, 'Currency');
        xmlwriter_text($xw3, $currency); //xml currency (data from variables)
        xmlwriter_end_element($xw3); // Currency

        xmlwriter_start_element($xw3, 'Description');
        xmlwriter_text($xw3, $description);// xml description (data from variables)
        xmlwriter_end_element($xw3); // Description

        xmlwriter_start_element($xw3, 'ApproveURL');
        xmlwriter_text($xw3, $approveURL); //(data from variables)
        xmlwriter_end_element($xw3); // ApproveURL

        xmlwriter_start_element($xw3, 'CancelURL');
        xmlwriter_text($xw3, $cancelURL); //(data from variables)
        xmlwriter_end_element($xw3); // CancelURL

        xmlwriter_start_element($xw3, 'DeclineURL');
        xmlwriter_text($xw3, $declineURL); //(data from variables)
        xmlwriter_end_element($xw3); // DeclineURL

        xmlwriter_start_element($xw3, 'OrderType');
        xmlwriter_text($xw3, $orderType); //(data from variables)
        xmlwriter_end_element($xw3);

        xmlwriter_start_element($xw3, 'AddParams');

        xmlwriter_start_element($xw3, 'Purchase.Recur.frequency');
        xmlwriter_text($xw3, $recurFrequency);
        xmlwriter_end_element($xw3);

        xmlwriter_start_element($xw3, 'Purchase.Recur.endRecur');
        xmlwriter_text($xw3, $recurEndRecur);
        xmlwriter_end_element($xw3);

        xmlwriter_start_element($xw3, 'Purchase.Recur.period');
        xmlwriter_text($xw3, $recurPeriod);
        xmlwriter_end_element($xw3);

        xmlwriter_start_element($xw3, 'Purchase.Recur.removeOnDecline');
        xmlwriter_text($xw3, $recurRemoveOnDecline);
        xmlwriter_end_element($xw3);

        xmlwriter_end_element($xw3); // AddParams

        xmlwriter_end_element($xw3); // Order

        xmlwriter_end_element($xw3); // Request

        xmlwriter_end_element($xw3); // TKKPG

        xmlwriter_end_document($xw3);

        $xmlData3 = xmlwriter_output_memory($xw3);
        //echo $xmlData3;
        $urlReq = "https://acs2test.quipugmbh.com:6443/Exec";
        $result = $this->sendOverPost($urlReq, $xmlData3);// response from server with url and xml
        //echo $result;

        $xmlRes = new SimpleXMLElement($result); //parse xml response

        $orderid = $xmlRes->Response[0]->Order[0]->OrderID; //getting orderID from response
        $sessionid = $xmlRes->Response[0]->Order[0]->SessionID; //getting sessionID from response
        $url = $xmlRes->Response[0]->Order[0]->URL; //getting URL from response


        //generate url to redirect browser
        $redirect = $url . "?ORDERID=" . $orderid . "&SESSIONID=" . $sessionid;

        header('Location: '.$redirect);
        return;
    }

    /**
     * @param $statusCode
     * @return string
     */
    public function statusCodeErrorDescriptionEnglish($statusCode): string{
        switch ($statusCode){
            case "00":
                return "Successfully";
            case "30":
                return "Message invalid format (no mandatory fields and etc.)";
            case "10":
                return "Internet shop has no access to the CreateOrder operation (or the internet shop is not registered)";
            case "54":
                return "Invalid operation";
            case "96":
                return "System error";
            default:
                return "Unexpected error from maishapay system";
        }
    }

    /**
     * @param $statusCode
     * @return string
     */
    public function statusCodeErrorDescriptionFrench($statusCode): string{
        switch ($statusCode){
            case "00":
                return "Requête aboutie avec succès";
            case "30":
                return "Format de message invalide (pas de champs obligatoires et etc.)";
            case "10":
                return "La boutique internet n'a pas accès à l'opération CreateOrder (ou la boutique Internet n'est pas enregistrée)";
            case "54":
                return "Opération invalide";
            case "96":
                return "Erreur système";
            default:
                return "Erreur inattendue du système maishapay";
        }
    }

    ####################################################################################################################
    ############################################## PRIVATE CLASS METHODS ###############################################
    ####################################################################################################################

    /**
     * @param $text
     * @return array|false|string
     */
    private function format_text($text){
        if(is_array($text)){
            foreach ($text as $key => $value){
                $text[$key] = htmlentities(utf8_decode(htmlspecialchars($value)));
            }
            return $text;
        }
        return htmlentities(utf8_decode(htmlspecialchars($text)));
    }

    ####################################################################################################################
    ############################################### PUBLIC CLASS SETTERS ###############################################
    ####################################################################################################################

    /**
     * @param mixed $language
     * @return EquityBCDC
     */
    public function setLanguage($language){
        $this->language = $this->format_text($language);
        return $this;
    }

    /**
     * @param mixed $amount
     * @return EquityBCDC
     */
    public function setAmount($amount){
        $this->amount = $this->format_text($amount);
        return $this;
    }

    /**
     * @param mixed $currency
     * @return EquityBCDC
     */
    public function setCurrency($currency){
        $this->currency = $this->format_text($currency);
        return $this;
    }

    /**
     * @param mixed $description
     * @return EquityBCDC
     */
    public function setDescription($description){
        $this->description = $this->format_text($description);
        return $this;
    }

    /**
     * @param mixed $orderType
     * @return EquityBCDC
     */
    public function setOrderType($orderType){
        $this->orderType = $this->format_text($orderType);
        return $this;
    }

    /**
     * @param mixed $code_pin
     * @return EquityBCDC
     */
    public function setCodePin($code_pin){
        $this->code_pin = $this->format_text($code_pin);
        return $this;
    }

    /**
     * @param mixed $telephone
     * @return EquityBCDC
     */
    public function setTelephone($telephone){
        $this->telephone = $this->format_text($telephone);
        return $this;
    }

    /**
     * @param mixed $email
     * @return EquityBCDC
     */
    public function setEmail($email){
        $this->email = $this->format_text($email);
        return $this;
    }

    /**
     * @param mixed $fee
     * @return EquityBCDC
     */
    public function setFee($fee){
        $this->fee = $this->format_text($fee);
        return $this;
    }

    /**
     * @param mixed $status
     * @return EquityBCDC
     */
    public function setStatus($status){
        $this->status = $this->format_text($status);
        return $this;
    }

    /**
     * @param mixed $orderID
     * @return EquityBCDC
     */
    public function setOrderID($orderID){
        $this->orderID = $this->format_text($orderID);
        return $this;
    }

    /**
     * @param mixed $sessionID
     * @return EquityBCDC
     */
    public function setSessionID($sessionID){
        $this->sessionID = $this->format_text($sessionID);
        return $this;
    }

    /**
     * @param mixed $url
     * @return EquityBCDC
     */
    public function setUrl($url){
        $this->url = $this->format_text($url);
        return $this;
    }

    /**
     * @param mixed $operation
     * @return EquityBCDC
     */
    public function setOperation($operation){
        $this->operation = $this->format_text($operation);
        return $this;
    }

    ####################################################################################################################
    ############################################### PUBLIC CLASS GETTERS ###############################################
    ####################################################################################################################

    /**
     * @return mixed
     */
    public function getLanguage(){
        return $this->language;
    }

    /**
     * @return mixed
     */
    public function getAmount(){
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getCurrency(){
        return $this->currency;
    }

    /**
     * @return mixed
     */
    public function getDescription(){
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getOrderType(){
        return $this->orderType;
    }

    /**
     * @return mixed
     */
    public function getTelephone(){
        return $this->telephone;
    }

    /**
     * @return mixed
     */
    public function getCodePin(){
        return $this->code_pin;
    }

    /**
     * @return mixed
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getFee(){
        return $this->fee;
    }

    /**
     * @return mixed
     */
    public function getStatus(){
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getOrderID(){
        return $this->orderID;
    }

    /**
     * @return mixed
     */
    public function getSessionID(){
        return $this->sessionID;
    }

    /**
     * @return mixed
     */
    public function getUrl(){
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getOperation(){
        return $this->operation;
    }

    ####################################################################################################################
    ############################################### PUBLIC CLASS SETTERS AND GETTERS ###################################
    ####################################################################################################################

}
