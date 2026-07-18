<?php


class Url {

    public static function encrypt($string) {
      $key = Configure::read('Security.cipherSeed'); //key to encrypt and decrypts.
      $result = '';
      $test = "";
       for($i=0; $i<strlen($string); $i++) {
         $char = substr($string, $i, 1);
         $keychar = substr($key, ($i % strlen($key))-1, 1);
         $char = chr(ord($char)+ord($keychar));

         $test[$char]= ord($char)+ord($keychar);
         $result.=$char;
       }

       return urlencode(base64_encode($result));
    }

    public static function decrypt($string, $query = true) {
        $key = Configure::read('Security.cipherSeed'); //key to encrypt and decrypts.
        $result = '';
        if (!empty($query)) $string = urlencode($string);
        $string = base64_decode(urldecode($string));
       for($i=0; $i<strlen($string); $i++) {
         $char = substr($string, $i, 1);
         $keychar = substr($key, ($i % strlen($key))-1, 1);
         $char = chr(ord($char)-ord($keychar));
         $result.=$char;
       }
        if(!preg_match('/^[A-Za-z0-9+&@#\/%?=~_|!:,.;]*$/', $result)) return null;
       return $result;
    }
	
}