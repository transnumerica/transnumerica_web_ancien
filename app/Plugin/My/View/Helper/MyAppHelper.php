<?php

App::uses('UrlCacheAppHelper', 'UrlCache.View/Helper');

class MyAppHelper extends UrlCacheAppHelper  {

    public function url($url = null, $full = false) {

        // Au càs où il y a un Querystring qui finie par '=', on le supprime
        // SecurityComponent à decocher
/*
        if (is_string($url)) {
            if (count(explode('?', $url)) > 1) {
                $url_strlen = strlen($url);
                if(substr($url, $url_strlen-1, 1) == '='){
                    $url = substr_replace($url, '', $url_strlen-1, 1);
                }
            }
        }
*/        

        if (is_array($url)) {
            $url = array_merge(
                array(
                    'controller' => $this->request->controller,
                    'plugin' => $this->request->plugin,
                    'action' => $this->action
                ),
            $url);

            // On fix et on corrige les erreurs des liens dans les numerotions paginées
            if ((isset($url['?']['page']) OR isset($url['page']) OR (isset($url['?']) AND $this->request->controller = $url['controller'] AND $this->request->action = $url['action'])) AND !empty($this->request->params['paging'])) {
                foreach ($this->request->params as $params_key => $params) {
                    if (is_string($params)) {
                        if (!in_array($params_key, array('action', 'controller'))) {
                            $url_key = array_search($params, $this->request->pass);
                            if (is_int($url_key)) unset($url[$url_key]);
                        }

                        $add_params = array($params_key => $params);
                    }
                }
                $url = array_merge($add_params, $url);
            }


            ksort($url);


            // On remplace les underscore"_" par des tirets "-"
            foreach ($url as $key => $value) {
                if (in_array($key, array('controller','action'))) {
                    //$url[$key] = str_replace('_', '-', $value);
                }
            }

        }

    /*
        //Cache des urls
        Cache::config('router', array(
            'engine' => 'Memcached', // Or Xcache, or Apc
            'prefix' => 'routes_',
            'duration' => '+7 days'
        ));

        $key = md5(serialize($url).$full);
        if (($link = Cache::read($key, 'router')) === false)
        {
            $link = parent::url($url, $full);
            Cache::write($key, $link, 'router');
        }
        return $link;
        */
        return parent::url($url, $full);
    }

}
