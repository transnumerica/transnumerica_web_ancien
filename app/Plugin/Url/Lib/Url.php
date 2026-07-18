<?php


class Url {

    protected static $key = 'qSI242342432qs*&sXOw!adre@34SasdadAWQEAv!@*(XSL#$%)asGb$@11~_+!@#HKis~#^';

    public static function encrypt($string, $security = false) {

      if ($security) {
        $string = Security::rijndael($string, static::$key, 'encrypt');
      }

      $key = Configure::read('Security.cipherSeed'); //key to encrypt and decrypts.
      $result = '';
      $test = array();
       for($i=0; $i<strlen($string); $i++) {
         $char = substr($string, $i, 1);
         $keychar = substr($key, ($i % strlen($key))-1, 1);
         $char = chr(ord($char)+ord($keychar));

         $test[$char]= ord($char)+ord($keychar);
         $result.=$char;
       }

       return urlencode(base64_encode($result));
    }

    public static function decrypt($string) {
        $key = Configure::read('Security.cipherSeed'); //key to encrypt and decrypts.
        $result = '';

        if (!static::isEncoded($string)) $string = urlencode($string);
        $string = base64_decode(urldecode($string));
       for($i=0; $i<strlen($string); $i++) {
         $char = substr($string, $i, 1);
         $keychar = substr($key, ($i % strlen($key))-1, 1);
         $char = chr(ord($char)-ord($keychar));
         if ($char !== null) $result.=$char;
       }

        if (mb_detect_encoding($result, 'UTF-8', true) == false) {
          $result = Security::rijndael($result, static::$key, 'decrypt');
        }

        //if(!preg_match('/^[A-Za-z0-9+&@#\/%?=~_|!:,.;-]*$/', $result)) return null;

       return $result;
    }
	
  public static function isEncoded($string){
    return preg_match('~%[0-9A-F]{2}~i', $string);
  }

}