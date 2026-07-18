<?php
class OpRoute extends CakeRoute {
 
     function parse($url) {

        // On instancie et/ ou on charge la liste des prefixes
        $prefixes = Cache::read('prefixes_list');
        if ($prefixes === false) {  
            $plugins = App::objects('plugin');
            foreach ($plugins as $key => $plugin) $plugins[$key] = mb_strtolower($plugin);
            $prefixes = Configure::read('Routing.prefixes');
            if ($prefixes === null) $prefixes = array();
            $prefixes = Hash::merge($prefixes, $plugins);
            Cache::write('prefixes_list', $prefixes);  
        }

     	$url = explode('/', $url);
        
        // On verifie si l'adresse est prefixer ou pas
        $P = 0;
        if (isset($url[1]) AND in_array(mb_strtolower($url[1]), $prefixes)) {
            $P = 1;
            $url[1] = mb_strtolower($url[1]);
        }

        if (isset($url[1+$P])) $url[1+$P] = mb_strtolower($url[1+$P]);
        // On remplace les tirets "-" par des underscore"_" 
        $urlO = implode('/', $url);
     	if (isset($url[2+$P])) $url[2+$P] = str_replace('-', '_', $url[2+$P]);
     	$url = implode('/', $url);

        $params = parent::parse($url);
        //debug($params);
        $paramsO = parent::parse($urlO);
        //debug($paramsO);

        if (empty($params) AND !empty($paramsO)) {
            $params = $paramsO;
        }elseif (!empty($params) AND !empty($paramsO) AND $params['action'] === $paramsO['action']) {
            $params = $paramsO;
        }elseif (!empty($params) AND empty($paramsO)) {
            $params = null;
        }

        
        if (empty($params)) {
            return false;
        }
        // On rend en miniscule les controllers et les actions pour assurer la non sensiblité à la case
        foreach ($params as $key => $param) {
            if (in_array($key, array('controller','action'))) {
                $params[$key] = mb_strtolower($param);
            }
        }

        return $params;
    }
 
}