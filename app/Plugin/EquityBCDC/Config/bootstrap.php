<?php

	if ($_SERVER) {

      if(empty($_SERVER['HTTP_COOKIE']) AND $_SERVER['REQUEST_METHOD'] == 'POST' AND in_array('HTTP_ORIGIN', array_keys($_SERVER)) AND in_array($_SERVER['HTTP_ORIGIN'], array('null', 'https://acs2test.quipugmbh.com:9601', 'http://acs2test.quipugmbh.com:9601')) AND @$_SERVER['CONTENT_TYPE'] == 'application/x-www-form-urlencoded' AND $_SERVER['HTTP_SEC_FETCH_SITE'] == 'cross-site'){
        header("Refresh:0");
        exit();
      }

    }

    App::import('Vendor', 'EquityBCDC.EquityBCDC', array('file' => 'EquityBCDC.php'));
