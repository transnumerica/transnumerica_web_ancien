<?php

class GeoLocComponent extends Component {

  public $components = array();

  public function get($ip = null) { 

    if (empty($ip)) $ip = Router::getRequest()->clientIp();

    if (!is_string($ip) || strlen($ip) < 1 || $ip == '127.0.0.1' || $ip == 'localhost' || $ip == '::1' || substr($ip, 0, 11) == substr('192.168.137.17', 0, 11)) $ip = '197.157.210.49';


    $netimpact_keys = array();

    $netimpact_keys[] = '1426f38cc056c5ec';

/*
    $netimpact_keys[] = 'LuQrRCWXAbhY5DHL';
    $netimpact_keys[] = 'RQFD43tsAkguhCQA';
    $netimpact_keys[] = '2yXYTPsv2yQHVkmM';
    $netimpact_keys[] = 'MZXB3IiMemadszcX';
*/
    foreach ($netimpact_keys as $key) {
      $url = "https://api.kickfire.com/v2/ip2geo?ip=$ip&key=$key";             
      //$url = "http://api.netimpact.com/qv1.php?key=$key&qt=geoip&d=json&q=$ip";             
      $data = @json_decode(file_get_contents($url));
      $data = @get_object_vars($data->data);
      if ($data) {
        $GeoCount = count(array_values($data));

        if ($GeoCount == 7) {

          $data = array_combine(array('name','city','region','country_code','country','latitude','longitude'), array_values($data)); 
          return $data;

        }elseif ($GeoCount == 6) {

          $data = array_combine(array('name','city','region'/*,'country_code'*/,'country','latitude','longitude'), array_values($data)); 
          return $data;

        }


      }
    }
    

    // En local
    App::import('Vendor', 'GeoLoc.geoipcity', array('file' => 'geoloc'.DS.'geoipcity.inc'));
    App::import('Vendor', 'GeoLoc.geoipregionvars', array('file' => 'geoloc'.DS.'geoipregionvars.php'));

    $gi = geoip_open(APP.'Plugin'.DS.'GeoLoc'.DS.'webroot'.DS.'database'.DS.'GeoLiteCity.dat',GEOIP_STANDARD);

    $record = geoip_record_by_addr($gi,$ip);

    if ($record) {
      $record = get_object_vars($record);
      foreach ($record as $key => $val) $record[$key] = utf8_encode($val);

      geoip_close($gi);
      return $record;
    }

    return false;







    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));


    $ip_data = get_object_vars($ip_data);
    foreach ($ip_data as $key => $val) $ip_data[$key] = html_entity_decode($val,ENT_COMPAT,'UTF-8');

    debug($ip_data);




    $json_data = file_get_contents('http://ipinfo.io/'.$ip.'/json');
    $data = json_decode($json_data, true);
    debug($data);










    $location = json_decode(file_get_contents('http://freegeoip.net/json/'.$ip));
    debug($location);


    $data = file_get_contents("http://api.hostip.info/?ip=".$ip);
    debug($data);



    $json = file_get_contents('http://getcitydetails.geobytes.com/GetCityDetails?fqcn='. $ip); 
    $data = json_decode($json);
    debug($data);

  }

}