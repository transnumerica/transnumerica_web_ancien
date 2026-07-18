<?php

	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

	    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']) && $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'POST') {
	        header('Access-Control-Allow-Origin: *');
	        header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
	    }
	    //exit;
	}

	header('Access-Control-Allow-Origin: *');

	App::uses('CakeText', 'Utility');
	App::uses('CakeTime', 'Utility');

	if (!function_exists('exif_read_data')) {
	  function exif_read_data($filename){
	  	$exif = array('Orientation' => 1);
	    
	    if (preg_match('@\x12\x01\x03\x00\x01\x00\x00\x00(.)\x00\x00\x00@', file_get_contents($filename), $matches)) {
	        $exif['Orientation'] = ord($matches[1]);
	    }

	    return $exif;
		}
	}


	if (!function_exists('__ste')) {

		function __ste($domain, $msg, $args = null) {
			if (!$msg) {
				return null;
			}
			App::uses('I18n', 'I18n');

			$domains = I18n::domains();
			$lang = array_keys($domains[$domain])[0];

			$translated = I18n::translate($msg, null, $domain);
			$arguments = func_get_args();
			$return = I18n::insertArgs($translated, array_slice($arguments, 2));

			if($msg === $return AND !isset($domains[$domain][$lang]['LC_MESSAGES'][$msg])) {
				$return = Op::translate($return);
			}

			return $return;
		}

	}


	App::import('Lib', 'My.Op', array('file' => 'Op.php'));

	$toSource = Op::language(); // Pour site multilangue, on detect la langue en avant 1ère

	if(!function_exists('mb_ucwords')){
		function mb_ucwords($str){
			return mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
		}
	}
