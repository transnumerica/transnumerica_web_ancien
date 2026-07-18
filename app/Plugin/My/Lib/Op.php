<?php

use GeoIp2\Database\Reader;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

function melange($array) {
  $val = array();
  $keys = array_keys($array);
  shuffle($keys);
    foreach($keys as $key) $val[] = $array[$key];
  return $val;
}


if (!function_exists('exit2')) {

  function exit2($message = null) {
    if (CakeSession::read('cordova')) {
      $request = Router::getRequest();
      if (!$request->is('json') AND !$request->is('ajax')) {
        exit('<html class="siteload">'.$message.'</html>');
      }
    }
    exit($message);
  }
  
}




if (!function_exists('imagecreatefrom')) {
  function imagecreatefrom($base3) {
    $infoImage3 = getimagesize($base3);

    switch ( $infoImage3[2] ) {
        case IMAGETYPE_GIF:   return imagecreatefromgif($base3); break;
        case IMAGETYPE_JPEG:  return imagecreatefromjpeg($base3);  
        if (Op::brw_image_fix_orientation($image_3, $base3)) {
          $tmp = $width_old; 
          $width_old = $height_old; 
          $height_old = $tmp;
        }

        break;
        case IMAGETYPE_PNG:   return imagecreatefrompng($base3);   break;
        case IMAGETYPE_GIF:   return imagecreatefromgif($base3);   break;
        //default: return false;
    }
  }
}


App::uses('CakeSession', 'Model/Datasource');
App::uses('L10n', 'I18n');

class Op {

  protected static $key = 'qSI242342432qs*&sXOw!adre@34SasdadAWQEAv!@*(XSL#$%)asGb$@11~_+!@#HKis~#^';

  public static $engine = 'Yandex';

  public static function isWeekend($date) {
    $weekDay = date('w', strtotime($date));
    return ($weekDay == 0 || $weekDay == 6);
  }

  public static function translate($text, $language  = '-', $options = array()) {
    $options += array('cache' => true, 'html' => preg_match('/>(\\s(?:\\s*))?([^<]+)(\\s(?:\s*))?</', $text), 'engine' => 'yandex');
    
    if(is_array($language)) {
      $options = array_merge($language, $options);
      $language = '-';
    }

    extract($options);

    $toSource = Op::language();

    $languages = explode('-', mb_strtolower($language));
    if (is_array($languages)) {

      if (empty($from) AND empty($to) AND isset($languages[1])) {
        list($from, $to) = explode('-', $language);
      }elseif(empty($to)) {
        $to = $languages[0];
      }

      $l10n = new L10n();
      $maps = $l10n->map();

      //Pour les langues locale (essayer de traduire)
      if(!empty($from) AND mb_strlen($from) === 2 && array_search($from, $maps)){

      }elseif (!empty($from) AND mb_strlen($from) !== 2 && isset($maps[$from])) {
        $from = $maps[$from];
      }else{
        unset($from);
      }

      if(!empty($to) AND mb_strlen($to) === 2 && array_search($to, $maps)){

      }elseif (!empty($to) AND mb_strlen($to) !== 2 && isset($maps[$to])) {
        $to = $maps[$to];
      }else{
        unset($to);
      }


      if (empty($to)) {
        $to = $toSource[0];
      }

    }else{
      exit('Paramattre language from et to non correct');
    }

    if (!empty($options['cache'])) {

      // On initialise le Cache
      if(!Cache::settings('translation')) {

        $prefix = Inflector::slug(mb_strtolower(Configure::read('Company.name')));
        $dCacheSettings = Cache::settings();
        
        Cache::config('translation', array('engine' => 'File', 'duration'=>'+12 month', 'prefix' => $prefix . 'translation_'));
      }

      $args['text'] = $text;
      if(!empty($from)) $args = array_merge($args, array('from' => $from));
      if(!empty($to)) $args = array_merge($args, array('to' => $to));
      if(!empty($options)) $args = array_merge($args, $options);

      $cacheName = md5(json_encode($args));
      $tText = cache::read($cacheName, 'translation');
      if($tText){
        return $tText;
      }
      //exit();
    }

    unset($options['html']);
    unset($options['cache']);
    unset($options['engine']);

    if (!empty($from) AND !empty($to) AND $from === $to) {
      return $text;
    }

    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    // On verifie le translator pour configuer les paramettres
    if($engine == 'yandex'){

      App::import('Vendor/Translate/Yandex', 'My.Translator', array('file' => 'Translator.php'));
      App::import('Vendor/Translate/Yandex', 'My.Translation', array('file' => 'Translation.php'));

      $YandexKeys = array();
      $YandexKeys[] = "trnsl.1.1.20170331T021116Z.53a353fef9d94e08.9617ecfdaf7fbe170972c4107aac8780e53dd21f";
      $YandexKeys[] = "trnsl.1.1.20200224T093531Z.6050c4093f2b07a9.b56ffc4224373926400f1bb89d4eda9eaf94f312";
      $YandexKeys[] = "trnsl.1.1.20200303T101511Z.e9d1faf776ee17c3.fd175323f2dfd323db57d89f09d898c3617e9eb9";
      $YandexKeys[] = "trnsl.1.1.20170330T122259Z.ced3f5aaba1fb0cc.7b2060a11b18f42c68fbce10987c7538976f9c4d";
      $YandexKeys[] = "trnsl.1.1.20130818T214524Z.9983e6055ae94787.74d98f42e84f8abac4a19cb1333dfecb7427165d";
      $YandexKeys[] = "trnsl.1.1.20140213T084832Z.2e599cd43e74002d.d935bc98da1909103ec212bc9ce71adeaac9de4a";
      $YandexKeys[] = "trnsl.1.1.20140218T130206Z.7923fc89a1c1bd16.aba4c3d98792c68c157c59f5a385339a03f0d736";
      $YandexKeys[] = "trnsl.1.1.20130806T140102Z.d80a7ce00b1a3c38.30de98386f6f6d594993fa93c0907aac5996aab9";
      $YandexKeys[] = "trnsl.1.1.20140602T085017Z.ea6ecac642816084.4df3b3711b35ed773720c92acdcebb11e793b8ab";
      $YandexKeys[] = "trnsl.1.1.20140911T223045Z.34ca44edca3e6ebd.f6807b1158e50190a2ae10c40d8a31a426647e98";
      //$YandexKeys[] = "trnsl.1.1.20140731T144606Z.639639bc480e82b7.7cb97a33d3b2e88fa363e789fe03e2426478c53a";
      $YandexKeys[] = "trnsl.1.1.20141206T164332Z.8497d97d7ca05dc2.411e9116711b1860da655ffba56840469a3b2af1";
      $YandexKeys[] = "trnsl.1.1.20180527T091305Z.7f33f9fb3f66f0bb.d573f1d9a6336a981504916600c45f49255938b3";

      //$YandexKeys[] = "trnsl.1.1.20150216T113622Z.2ccb71d878ea0b58.5ac57322acecfa2d9001aad077fdcff21c206a43";

      $breakYandex = false;
      while ($breakYandex == false) {

        if (!$YandexKeys) {
          break;
        }

        $YKey = array_rand($YandexKeys);
        $YandexApiKey = $YandexKeys[$YKey];

        $translator = new Translator($YandexApiKey);

        $language = '';
        if (!empty($from)) {
          $language .= $from.'-';
        }

        if (!empty($to)) {
          $language .= $to;
        }

        try {
          $translation = $translator->translate($text, $language, $html);
          $tText =$translation->getResult(true);
          $breakYandex = true;
          break;

        } catch (Exception $e) {

          debug($e->getCode().' - '.$e->getMessage());

          if ($YandexKeys AND $e->getCode() == 408) {
            unset($YandexKeys[$YKey]);
          }else{
            $breakYandex = true;
            break;

            $optionNews = $options;
            $optionNews['engine'] = 'sdl';

            //debug(Op::translate($text,$language,$optionNews));

            $tText =$text;

          }

        }

      }




    }elseif($engine == 'sdl'){

      $text = urlencode($text);

      //https://languagecloud.sdl.com/translation-toolkit/api-documentation
      $headers = array();
      $headers[] = 'Content-type:application/json';
      $headers[] = 'Authorization:LC apiKey=bPz%2BBGF%2FY8EYgSVQnBHMdg%3D%3D';

      $data = array("text" => $text);
      $data_string = json_encode($data);

      if (!empty($from)){
       $data['from'] = array_search($to, $maps);
      }else{

        // Si la langue d'origine n'est pas definie, on la detect puis on fait la sauvegarde
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://lc-api.sdl.com/detect-language");

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);  //Post Fields
        $server_output = curl_exec ($ch);

        curl_close ($ch);

        $server_output = json_decode($server_output, true);
        $data['from'] = $server_output['result'][0]['language']['threeLetterCode'];

      }

      //On produit la traduction
      if (!empty($to)) $data['to'] = array_search($to, $maps);
      $data['inputFormat'] =  ($html ? 'html' : 'plain');

      $data_string = json_encode($data);
      debug($data);

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,"https://lc-api.sdl.com/translate");

      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);  //Post Fields
      $server_output = json_decode(curl_exec ($ch), true);

      curl_close ($ch);

      $tText = $server_output['translation'];

    }


    if (!empty($cache) AND !empty($tText)) {
        if (is_string($cache)) Cache::set('duration', $cache, 'translation');
        cache::write($cacheName, $tText, 'translation');
    } 

    $devMode = Configure::read('debug') > 0;

    if ($tText OR !$devMode) {
      return $tText;
    }else{
      $error = __d('my', "Erreur de traduction, vérifier votre connexion Internet...");
      if($html) $error = '<div style="color: crimson;">'.$error.'</div>';
      return $error;
    }

  }

  public static function lessenumber($amount, $options = array()) {
    $options += array(
      'min' => 0,
    );
    extract($options);

    $amountnode = str_replace(',', '.', $amount);
    list($amountnode, $amountnodedec) = explode('.', $amountnode);
    $strlen = strlen($amountnode);
    $mille = explode('.', str_replace(',', '.', ($strlen-1)/3))[0];

    if ($min > $mille) {
      $mille = 0;
    }

    if ($mille == 1) {
      $last = 'k';
    }elseif ($mille == 2) {
      $last = 'M';
    }elseif ($mille >= 3) {
      $last = 'Md';
      $mille = 3;
    }


    $strlmille = $mille*3;

    if ($mille) {
      $amountnodelen = substr($amountnode, 0, $strlen-$strlmille);
      if (strlen($amountnodelen) === 1) {
        $amountnodelen = $amountnodelen.'.'.substr($amountnode, $strlen-$strlmille, 1);
      }
      $amountnodelen = $amountnodelen.' '.$last;
    }else{
      $amountnodelen = $amount;
    }

    return $amountnodelen;
  }

  public static function language() {

    $l10n = new L10n();
    $maps = $l10n->map();

    // On format les langues dans un format Standard
    $langs = Configure::read('Config.languages');
    if (empty($langs)) $langs = array();
    foreach ($langs as $key => $cl) {
      $catalog = $l10n->catalog($cl); 
      if ($catalog) {
        $langs[$key] = $catalog['localeFallback'];
      }else{
        unset($langs[$key]);
      }
    }

    // On configure la langue selon les preferences de l'utilisateur
    if(empty($lang) AND $locale = CakeSession::read('Config.language')) {
      $catalog = $l10n->catalog($locale);      
      if ($catalog AND (!$langs OR in_array($catalog['localeFallback'], $langs))) {
        $lang = $catalog['localeFallback'];
        $ln = $maps[$lang];
      }
    }

    //On configure selon le language de base configurer par le systeme
    if(empty($lang) AND $locale = Configure::read('Config.language')) {
      $catalog = $l10n->catalog($locale);      
      if ($catalog AND (!$langs OR in_array($catalog['localeFallback'], $langs))) {
        $lang = $catalog['localeFallback'];
        $ln = $maps[$lang];
      }
    }

    // On configure la langue selon la preference lingistique de sa machine
    if(empty($lang) AND $locale = $l10n->get()) {
      $catalog = $l10n->catalog($locale);      
      if ($catalog AND (!$langs OR in_array($catalog['localeFallback'], $langs))) {
        $lang = $catalog['localeFallback'];
        $ln = $maps[$lang];
      }
    }

    //On configure selon le premier language accepté
    if(empty($lang) AND $locale = array_values(Configure::read('Config.languages'))[0]) {
      $catalog = $l10n->catalog($locale);      
      if ($catalog AND (!$langs OR in_array($catalog['localeFallback'], $langs))) {
        $lang = $catalog['localeFallback'];
        $ln = $maps[$lang];
      }
    }

    if ($langs AND !in_array($lang, $langs)) {
      Configure::write('Config.language', $locale);
    }

    //On met les setLocal, princpalement pour convertir les DATETIME

    setlocale(LC_ALL, $locale, $lang,  $ln, $locale.'.UTF8', $lang.'.UTF-8', $ln.'.UTF-8');

    $return = array($ln, $lang, $locale);

    return $return;
  }


    public static function MinifyHtml($html) {

        App::import('Vendor', 'MinifyHtml.Minify_HTML', array('file' => 'html.php'));

        App::import('Vendor', 'AssetCompress.Minifier', array('file' => 'jshrink/Minifier.php'));
        App::import('Vendor', 'AssetCompress.cssmin', array('file' => 'cssmin/CssMin.php'));

        $options = array(
            "cssMinifier" => array("CssMin", "minify"),
            "jsMinifier" => array("JShrink\Minifier", "minify"),
            'xhtml' => false
        );

        return Minify_HTML::minify($html, $options);

    }

    public static function HtmlToText($line = null) {

        $replace = array(
        '/ {2,}/' => '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s',
        '/!\s+!/' => ' ',
        '/ {2,}/' => ' ',
        '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s' => '',
        );

        $line = preg_replace(array_keys($replace), array_values($replace), $line);

        $line = str_replace(array(' "=""', "<br", "/p>"), array('',"\n<br", "/p>\n") , $line);
        $line = strip_tags($line);
        $line = html_entity_decode($line);
        //$line = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $line);
        //$line = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $line);
        $line = preg_replace_callback('~&#([0-9]+);~', function($m) { return chr($m[1]); }, $line);


        $line = preg_replace('(\n|\r|\t)',' ',$line);
        if(Op::isEncoded($line)) $line = preg_replace('/\s\s+/', ' ', $line); 

        $line = preg_replace('/\s{2,}/u', '', preg_replace('/[\n\r\t]+/', '', $line));
        while (strpos($line, ' ') === 0) $line = substr_replace($line, '', 0, 1);
        if (!mb_detect_encoding($line, 'UTF-8', true)) $line = utf8_decode($line);


        return $line;
    }


  public static function gender($textm, $textf, $gender = 'm'){
    if($gender == 'f'){
      return $textf;
    }
    return $textm;
  }


  public static function minutesToTime($minutes){
    $d = floor($minutes/1440);
    $h = floor(($minutes-$d*1440)/60);
    $m = $minutes - ($d*1440) - ($h*60);
    return array('d' => $d, 'h' => $h, 'm' => $m);
  }


  public static function isEncoded($string){
    return preg_match('~%[0-9A-F]{2}~i', $string);
  }



    public static function sendMail($options = array(), $pass = array()) {

        $request = Router::getRequest();

        if (empty($options['to'])) {
            trigger_error(__d('cake_dev', "Vous n'avez pas specifier de(s) destionataire(s)."), E_USER_WARNING);
            return false;
        }

        App::uses('CakeEmail', 'Network/Email');

        // On universalise les emails pour eviter les erreurs ultérieurs
        if (is_string($options['to'])) $options['to'] = array($options['to']);
        elseif (is_array($options['to'])) {
            foreach ($options['to'] as $mail => $name) {
                if (!is_int($mail)) {
                    unset($options['to'][$mail]);
                    $options['to'][] = array($mail => $name);
                    
                }
            }
        }

        $errors = '';
        foreach ($options['to'] as $mail) {
            if (is_string($mail)) $email = $mail;
            else $email = array_keys($mail)[0];
            if (!Validation::email($email)) $errors .= $email.', ';
        }

        if (!$request->is('json') AND !empty($errors)) {
            trigger_error(__d('cake_dev', "Ces emails sont incorrects : ".$errors), E_USER_WARNING);
            return false;
        }


        $defaults = array(
            'config' => Configure::read('Company.defaultemailConfig'),
            'subject' => '',
            'message' => '',
            'attachments' => array(),
            'layout' => 'default',
            'template' => 'default',
            'emailFormat' => 'html',
            'debug' => false,
            'X-Mailer' => Configure::read('Company.fullname'),
        );

        $options = array_merge($defaults, $options);
        $debug = ($options['debug'] AND Configure::read('debug'));

        $Email = new CakeEmail($options['config']);
        if (!empty($options['from'])) $Email->from($options['from']);
        if (!empty($options['sender'])) $Email->sender($options['sender']);
        if (!empty($options['replyTo'])) $Email->replyTo($options['replyTo']);
        if (!empty($options['viewVars'])) $Email->viewVars($options['viewVars']);

        $Email
                ->addHeaders(array('X-Mailer' => 'X-'.$options['X-Mailer']))
                ->emailFormat($options['emailFormat'])
                ->subject(h($options['subject']))
                ->template($options['template'], $options['layout'])
                ->viewVars($pass);

        if (!$request->is('json')) $Email->helpers('MinifyHtml.MinifyHtml');


        // Au càs où on est en debug, on change le transports et on desactives les pièces jountes momentanéement
        if ($debug) $Email->transport('Debug');
        else $Email->attachments($options['attachments']);

        $i = 0;
        while ($i < count($options['to'])) {

            $to_key = array_keys($options['to'])[$i];

            $Message = $Email
                ->to($options['to'][$to_key])
                ->cc((@$options['to'][$cc_key]) ? $options['to'][$cc_key] : array())
                ->bcc((@$options['to'][$bcc_key]) ? $options['to'][$bcc_key] : array())
                ->send($options['message']);

            $i++;
        }

        // On affiche l'email si l'utilisateur est en debug
        if (!$request->is('json') AND $debug) {
            foreach (explode(chr(10), $Message['headers']) as $head) {

                if (strstr($head, ': ')) {
                  list($objet, $text) = explode(': ', $head);
                  if (in_array($objet, array('Subject'))) {
                      echo '<p><b>'.$objet.'</b> : '.html_entity_decode($text).'</p>';
                  }
                }

            }

            echo '<div>'.$Message['message'].'</div>';
            echo ($options['attachments'] ? '<div><b>'.count($options['attachments']).' Pièce(s) jointe(s)</b></div>' : '');
        }

        return true;
        
    }








    public static function remote_file_exists($path){
      $file_headers = @get_headers($path);
      if($file_headers === false  OR in_array($file_headers[0], array('HTTP/1.0 404 Not Found','HTTP/1.1 404 Not Found'))) return false;
      return true;
    }





    public static function resizedUrl($files, $options = array()) {

      $emptypic = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

      if (empty($files) OR $files == '/' OR $files == $emptypic) return $emptypic;

      if (is_array($options)) $options += array('width' => null, 'height' => null,'quality' => 100, 'space' => true, 'max-width' => null, 'max-height' => null, 'blur' => false, 'compress' => true);
      extract($options);


      # We define the image dir include Theme support
      $imageDir = WWW_ROOT;

      # Nous verifions la confiuration sur l'image

      $pathinfo   = pathinfo(trim($files, DS));
      
      # On corige certains bug des liens pour certains hebergeur Linux
      if (isset($pathinfo['dirname'])){
        if ($pathinfo['dirname'] == DS) {
          $pathinfo['dirname'] = '/';
        }

        $pathinfo['dirname'] = '/'.$pathinfo['dirname'];
        $pathinfo['dirname'] = str_replace('//', '/', $pathinfo['dirname']);
      }else{
        $pathinfo['dirname'] = '/';
      }
      //

      // On verifie que l'image n'est pas au dossier temp
      $dossier_thumb = '/img/temp/thumb/';
      if (!empty($options['dir'])) {
        $dossier_thumb = $options['dir'];
      }

      $output = $dossier_thumb . md5($files) . '_';
      if (!empty($options['width'])) $output .= $options['width'] . 'x';
      if (!empty($options['height'])) $output .= $options['height'] . '';
      if (!empty($options['max-width'])) $output .= 'm'.$options['max-width'] . 'x';
      if (!empty($options['max-height'])) $output .= 'm'.$options['max-height']. '';
      
      if (!empty($pathinfo['extension'])) $output .= '.' . $pathinfo['extension'];
      else return $emptypic;

      if (file_exists($imageDir . $output)) {
        return $output;
      }


      $file = $imageDir . trim($files, DS);

      //On verifie si l'image est bien présent dans le site
      if (is_file($file) OR $remote = Op::remote_file_exists($files)) {

        if (!empty($remote)) {
          $dossier_thumb = '/img/temp/thumb/';
          $file = trim($files, DS);
          $pathinfo['filename'] = md5($files); // On achete le nom de l'image en rapport avec le lien pour eviter les ecrasages non desirés
          //$imageDir = '';
        }else{
          $dossier_thumb = $pathinfo['dirname'] .'/thumb/';
          $dossier_thumb = str_replace('//', '/', $dossier_thumb);
          $dossier_thumb = str_replace('thumb/thumb', 'thumb', $dossier_thumb);
        }


        if (!empty($options['dir'])) {
          $dossier_thumb = $options['dir'];
        }

        //On verifie si le dossier thumb est disponiible sur le disque dur sinon on le crée        
        if(!is_dir('./'.$dossier_thumb)){
            mkdir('./'.$dossier_thumb,0777,true);
        }

        // Chemin destination
        $output = $dossier_thumb . $pathinfo['filename'] . '_';
        if (!empty($options['width'])) $output .= $options['width'] . 'x';
        if (!empty($options['height'])) $output .= $options['height'] . '';
        if (!empty($options['max-width'])) $output .= 'm'.$options['max-width'] . 'x';
        if (!empty($options['max-height'])) $output .= 'm'.$options['max-height']. '';
        $output .= '.' . $pathinfo['extension'];
        


        //debug($file);
        $info = getimagesize($file);

        $md5Data = md5(file_get_contents($file));
        $md5File = md5(json_encode(array_merge(array($md5Data), $info, $options)));
        $output = $dossier_thumb.$md5File.'.' . explode('?', $pathinfo['extension'])[0];




        if (/*$space == 'blur' OR */!file_exists($imageDir . $output)) {

          # Setting defaults and meta
          $info                         = getimagesize($file);
          list($width_old, $height_old) = $info;

          # Create image ressource
          switch ( $info[2] ) {
              case IMAGETYPE_GIF:   $image = imagecreatefromgif($file);   break;
              case IMAGETYPE_JPEG:  $image = imagecreatefromjpeg($file);  
              if (Op::brw_image_fix_orientation($image, $file)) {
                $tmp = $width_old; 
                $width_old = $height_old; 
                $height_old = $tmp;
              }

              break;
              case IMAGETYPE_PNG:   $image = imagecreatefrompng($file);   break;
              case IMAGETYPE_GIF:   $image = imagecreatefromgif($file);   break;
              default: return false;
          }

          if (!empty($options['max-width']) AND (empty($options['max-height']) OR  ($height_old*$options['max-width']/$width_old <= $options['max-height']))) {
            $width = $options['max-width'];
            unset($options['max-height']);
          }elseif (!empty($options['max-height']) AND (empty($options['max-width']) OR  ($width_old*$options['max-height']/$height_old <= $options['max-width']))) {
            $height = $options['max-height'];
            unset($options['max-width']);
          }


          if (empty($options['max-width']) AND empty($options['max-height']) AND  empty($options['width']) AND empty($options['
            height'])) {
            $dividende = 1;
          }

          # On cherche la bonne configuration rognage et redimentionnement
          if (empty($height) OR (empty($width) AND !empty($options['max-height']) AND $width_old*$height < $height_old*$width) OR (empty($options['max-height']) AND $width_old*$height > $height_old*$width AND $space == true) OR (empty($options['max-height']) AND $width_old*$height < $height_old*$width AND $space == false)) {
            
            if (!empty($width)) {
              $dividende = $width_old/$width;
            }  

            if ((!empty($options['max-width']) AND !empty($options['max-height'])) AND ($width_old/$options['max-width'] < $height_old/$options['max-height'])){
              $dividende = $height_old/$options['max-height'];
            }elseif ((!empty($options['max-height'])) AND ($width_old >= $height_old)) {
              $dividende = $height_old/$options['max-height'];
            }
            
            $width_crop = $width_old/$dividende; 
            $height_crop = $height_old/$dividende;


            if ((!empty($options['max-width']) AND !empty($options['max-height'])) AND ($width_old/$options['max-width'] < $height_old/$options['max-height'])) $width = $width_crop;

            $xpos = "0"; 
            $ypos = ($height - $height_crop)/2;

          }else{

            if (!empty($height)) {
              $dividende = $height_old/$height;
            }  

            if ((!empty($options['max-width']) AND !empty($options['max-height'])) AND ($width_old/$options['max-width'] > $height_old/$options['max-height'])) {
              $dividende = $width_old/$options['max-width'];
            }elseif ((!empty($options['max-width'])) AND ($height_old >= $width_old)) {
              $dividende = $width_old/$options['max-width'];
            }

            $width_crop = $width_old/$dividende; 
            $height_crop = $height_old/$dividende;

            if ((!empty($options['max-width']) AND !empty($options['max-height'])) AND ($width_old/$options['max-width'] > $height_old/$options['max-height'])) $height = $height_crop;

            $xpos = ($width - $width_crop)/2; 
            $ypos = "0";
          }

          if (empty($height) OR empty($width)) {
            $width = $width_crop;
            $height = $height_crop;
            $xpos = 0;
            $ypos = 0;
          }






            if ($space === 'blur') {

              $buymd5 = $md5File;


              if ($xpos) {
                $maxWhat = 'height';
              }elseif ($ypos) {
                $maxWhat = 'width';
              }

              $spaceblur = $blur;
              if (!$spaceblur) {
                $spaceblur = true;
              }


              $FileType = strtolower(pathinfo($files,PATHINFO_EXTENSION));
              if(!in_array($FileType, array('png'))) {

                $base1Unfull = $dossier_thumb.'base1'.md5($files).'.png';
                $base1 = $imageDir.$base1Unfull;

                $image_93 = imagecreatefromstring(file_get_contents($imageDir.$files));
                imagepng($image_93, $base1, 9);
                imagedestroy($image_93); 

                $files = $base1Unfull;

              }

              $base2 = $imageDir.Op::resizedURL($files, array_merge($options, array('max-height' => null, 'max-width' => null), array('max-'.$maxWhat => $options[$maxWhat], 'height' => null, 'width' => null, 'space' => null, 'blur' => null, 'compress' => false))); // Image en avant

              $fileb4 = Op::resizedURL($files, array_merge($options, array('width' => $options['width']/10, 'height' => $options['height']/10, 'space' => false, 'blur' => $spaceblur, 'compress' => false))); // Image Flou


              $base3 = $imageDir.Op::resizedURL($fileb4, array_merge($options, array('space' => false, 'blur' => false, 'compress' => false))); // Image Flou

              unlink($imageDir.$fileb4);

              if (!empty($base1)) {
                unlink($base1);
              }

              $final = $imageDir . $output;
              $unfullfinal = $output;


              $image_1 = imagecreatefrompng($base3);
              unlink($base3);
              $image_2 = imagecreatefrompng($base2);
              unlink($base2);


              $image_width = imagesx($image_1);  
              $image_height = imagesy($image_1);

              // Get your Text Width and Height
              $text_width = imagesx($image_2);
              $text_height = imagesy($image_2);


              $widthTrunc = 0*$text_width/100;
              $heightTrunc = 0*$text_height/100;

              $xt = ($image_width/2) - ($text_width/2);
              $yt = ($image_height/2) - ($text_height/2);


              $image_4 = imagecreatetruecolor( $image_width, $image_height );


              imagealphablending($image_4, FALSE);
              imagesavealpha ($image_4 , true);


              imagecopy($image_4, $image_1, 0, 0,  0, 0, $image_width, $image_height);
              imagedestroy($image_1); 


              imagecopy($image_4, $image_2, $xt, $yt, $widthTrunc, $heightTrunc, $text_width-($widthTrunc*2), $text_height-($heightTrunc*2));
              imagedestroy($image_2); 

              imagepng($image_4, $final, 0);
              imagedestroy($image_4); 

            }else{


              # The two image ressources needed (image resized with the good aspect ratio, and the one with the exact good dimensions)
              $image_crop = imagecreatetruecolor( $width, $height );

              # On convertit le fond rogner en Blanc ou en transparant
              $background_color = '255,255,255,127';
              if (!empty($options['background'])) $background_color = $options['background'];
              @list($x_c, $y_c, $z_c, $t_c) = explode(',', $background_color);
              $whiteBackground = imagecolorallocatealpha($image_crop, $x_c, $y_c, $z_c, ($t_c ? $t_c : 1)); 

              imagefill($image_crop,0,0,$whiteBackground);
              imagecolortransparent($image_crop, $whiteBackground); //rendre le blanc transparent

              imagealphablending($image_crop, FALSE);
              imagesavealpha ($image_crop , true);
                           
              # On redimensionne et on rogne
              imagecopyresampled($image_crop, $image, $xpos, $ypos, 0, 0, $width_crop, $height_crop, $width_old, $height_old);

              if (!empty($blur)) {

                if($blur === true){
                  $blur = 10; 
                }

                for ($x=1; $x<=$blur; $x++){

                  if ($x % 10 == 0) {//each 10th time apply 'IMG_FILTER_SMOOTH' with 'level of smoothness' set to -7
                      //imagefilter($image_crop, IMG_FILTER_SMOOTH, -7);
                  }

                  imagefilter($image_crop, IMG_FILTER_GAUSSIAN_BLUR,999);
                  //$gaussian = [[1, 2, 1], [2, 4, 2], [1, 2, 1]];
                  //imageconvolution($image_crop, $gaussian, 16, 0);
                } 

                if (!function_exists('blurazkl')) {

                function blurazkl($img, $radius=10){

                  if ($radius>100) $radius=100; //max radius
                  if ($radius<0) $radius=0; //nin radius

                  $radius=$radius*4;
                  $alphaStep=round(100/$radius)*1.7;
                  $width=imagesx($img);
                  $height=imagesy($img);
                  $beginX=floor($radius/2);
                  $beginY=floor($radius/2);


                  //make clean imahe sample for multiply
                  $cleanImageSample=imagecreatetruecolor($width, $height);
                  imagecopy($cleanImageSample, $img, 0, 0, 0, 0, $width, $height);


                  //make h blur
                  for($i = 1; $i < $radius+1; $i++)
                  {
                  $xPoint=($beginX*-1)+$i-1;
                  imagecopymerge($img, $cleanImageSample, $xPoint, 0, 0, 0, $width, $height, $alphaStep);
                  }
                  //make v blur
                  imagecopy($cleanImageSample, $img, 0, 0, 0, 0, $width, $height);
                  for($i = 1; $i < $radius+1; $i++)
                  {
                  $yPoint=($beginY*-1)+$i-1;
                  imagecopymerge($img, $cleanImageSample, 0, $yPoint, 0, 0, $width, $height, $alphaStep);
                  }
                  //finish
                  return $img;
                  imagedestroy($cleanImageSample); 
                  }

                }



                //$image_crop = blurazkl($image_crop, $blur);

                imagefilter($image_crop, IMG_FILTER_SMOOTH,99);
                imagefilter($image_crop, IMG_FILTER_BRIGHTNESS, 10);
              }

              # On enregistre l'image par rapport à sa qualité
              switch ( $info[2] ) {
                case IMAGETYPE_GIF:   imagegif($image_crop, $imageDir . $output, $quality);    break;
                case IMAGETYPE_JPEG:  imagejpeg($image_crop, $imageDir . $output, $quality);   break;
                case IMAGETYPE_PNG:   imagepng($image_crop, $imageDir . $output, ($compress ? 9 : 0)); if ($compress) Op::compress_png($output, $quality);    break;
                default: return false;
              }
              imagedestroy($image_crop); 

          }

        }
      }else{
        $output = $emptypic;
      }

      //$output =  str_replace(DS,'/',$output);
      return $output;
    }



  public static function compress_png($path_to_png_file, $max_quality = 40) {

    if (is_array($max_quality) AND !empty($max_quality['quality'])) {
      $max_quality = $max_quality['quality'];
    }

    $vps = false;


    if (file_exists($path_to_png_file)) {
      $localFile = $path_to_png_file;
    }elseif (file_exists(WWW_ROOT.$path_to_png_file)) {
      $localFile = WWW_ROOT.$path_to_png_file;
    }else{
        debug("File does not exist: $path_to_png_file");
    }

    // guarantee that quality won't be worse than that.
    $min_quality = 0;

    // '-' makes it use stdout, required to save to $statut variable
    // '<' makes it read from the given file path
    // escapeshellarg() makes this safe to use with any path



    // On retourne essaie au cas où le Shell est activé
    if (!ini_get('safe_mode') AND !in_array('shell_exec', str_replace(' ', '', explode(',', ini_get('disable_functions'))))) {
      $vps = true;

      // On verifie le systeme du server pour determiner l'environement de la commande à utiliser
      if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $PHP_OS = 'win';
        $vps = false;
      } elseif (strtoupper(substr(PHP_OS, 0, 5)) === 'LINUX') {
        $PHP_OS = 'linux';
        if (PHP_INT_SIZE == 8) {
          $PHP_OS .= DS.'x64';
        }else{
          $PHP_OS .= DS.'x86';
        }
      }
      $statut = shell_exec(APP.'Plugin'.DS.'Media'.DS.'webroot'.DS.'files'.DS.'pngquant'.DS.$PHP_OS.DS."pngquant --force --skip-if-larger --verbose --ext=.png --speed=1  --quality=$min_quality-$max_quality ".escapeshellarg(WWW_ROOT.$path_to_png_file)." 2>&1");

    }else{
      $vps = true;
    }

    if (stripos($statut, 'No errors detected') === false) {
      //$vps = true;
    }


      $vps = false;
    // On execute via le Serveur VPS au cas où
    if($vps){

      App::uses('HttpSocket', 'Network/Http');

      $HttpSocket = new HttpSocket();

      $remoteServeur = '213.202.222.78';
      //$remoteServeur = 'localhost';

      $url = 'http://'.$remoteServeur.'/vps/pngquant/';

      $headers = array();


      $md5File = pathinfo($path_to_png_file) ['basename'];

      $query = array(
        'source' => Router::url($path_to_png_file, true),
        'min_quality' => $min_quality,
        'max_quality' => $max_quality,
        'md5File' => $md5File
      );
      
      $response = $HttpSocket->post($url.'compress_png.php', $query, array('header' => $headers));

      $remote = fopen(Router::url($url.$md5File, true), 'r');
      $local = fopen($localFile, 'w');

      stream_copy_to_stream($remote, $local);

      // On supprime le fichier créer dans le VPS après que la copie ait reussi
      $query = array(
        'md5File' => $md5File
      );
      $response = $HttpSocket->post($url.'delete.php', $query, array('header' => $headers));

      $statut = true;
    }


    return $statut;
  }


    public static function brw_image_fix_orientation(&$image, $filename) {
      $exif = exif_read_data($filename);
      if (!empty($exif['Orientation'])) {
        switch ($exif['Orientation']) {
          case 3: $image = imagerotate($image, 180, 0); break;
          case 6: $image = imagerotate($image, -90, 0); break;
          case 8: $image = imagerotate($image, 90, 0); break;
        }
        if (in_array($exif['Orientation'], array(6, 8))) {
          return true;
        }
      }
      return false;
    }





  public static function geoip($ip = null) { 

    if (empty($ip)) $ip = Op::clientIp();

    if (!is_string($ip) || strlen($ip) < 1 || $ip == '127.0.0.1' || $ip == 'localhost' || $ip == '::1' || substr($ip, 0, 11) == substr('192.168.137.17', 0, 11)) $ip = '192.230.35.68';

    try {
      App::pluginPath('MyGeo');
    } catch (Exception $e) {
        trigger_error('Veuillez charger le Plugin MyGeo');
    }

    App::import('Vendor', 'MyGeo.GeoIp2', array('file' => 'GeoIp2'.DS.'autoload.php'));
    App::import('Vendor', 'MyGeo.MaxMind', array('file' => 'MaxMind'.DS.'autoload.php'));

    // This creates the Reader object, which should be reused across
    // lookups.
    $reader = new Reader(App::pluginPath('MyGeo').'Vendor'.DS.'GeoIp2'.DS.'maxmind-db'.DS.'GeoLite2-City.mmdb');

    // Replace "city" with the appropriate method for your database, e.g.,
    // "country".

    try {

    $record = $reader->city($ip);

    $data = array(
        'city' => $record->city->name,
        'region_code' => $record->mostSpecificSubdivision->isoCode,
        //'region' => ,
        'region' => (isset($record->mostSpecificSubdivision->names['fr']) ? $record->mostSpecificSubdivision->names['fr'] : $record->mostSpecificSubdivision->name),
        //'region_lang' => $record->mostSpecificSubdivision->names,
        'country_code' => $record->country->isoCode,
        //'country' => $record->country->name,
        'country' => $record->country->names['fr'],
        //'country_lang' => $record->country->names,
        'continent_code' => $record->continent->code,
        //'continent' => $record->continent->name,
        'continent' => $record->continent->names['fr'],
        //'continent_lang' => $record->continent->names,
        'postal_code' => $record->postal->code,
        'latitude' => $record->location->latitude,
        'longitude' => $record->location->longitude,
        'accuracy_radius' => $record->location->accuracyRadius,
        'ip' => $record->traits->ipAddress,
      ); 
    } catch (Exception $e) {
      $data = array();
    }

    $reader = new Reader(App::pluginPath('MyGeo').'Vendor'.DS.'GeoIp2'.DS.'maxmind-db'.DS.'GeoIP2-ISP.mmdb');

    try {

      $ispRecord = $reader->isp($ip);

      $data['isp_name'] = $ispRecord->isp; // 'University of Minnesota'
      $data['isp']['asid'] = $ispRecord->autonomousSystemNumber; // 217
      $data['isp']['as'] = $ispRecord->autonomousSystemOrganization; // 'University of Minnesota'
      $data['isp']['name'] = $ispRecord->isp; // 'University of Minnesota'
      $data['isp']['organization'] = $ispRecord->organization; // 'University of Minnesota'

    } catch (Exception $e) {
      $data['isp'] = array();
    }

    if ($data) {
      return $data;
    }
    return false;


    $pageContent = file_get_contents('http://ip-api.com/json/' . $ip);
    $parsedJson  = json_decode($pageContent);
    debug($parsedJson);

    
    $json_data = file_get_contents('http://ipinfo.io/'.$ip.'/json');
    $data = json_decode($json_data, true);
    debug($data);



    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));


    $ip_data = get_object_vars($ip_data);
    foreach ($ip_data as $key => $val) $ip_data[$key] = html_entity_decode($val,ENT_COMPAT,'UTF-8');
    debug($ip_data);


    $tags=json_decode(file_get_contents('http://gd.geobytes.com/GetCityDetails?fqcn='. $ip), true);
    debug($tags);

    exit();

  }


    public static function random_float($options = array()) {
        $options += array('round' => 10);
        $options += array('min' => 0);
        $options += array('max' => 100);
        extract($options);

        if (!empty($options['session'])) {
            $options['cache'] = $options['session'];
            $options['session'] = CakeSession::id();
        }

        if (!empty($options['cache'])) {
          $cacheName = md5(json_encode($options));
          $randomfloat = cache::read($cacheName, 'random_float');
        }

        if (empty($randomfloat)) {
            $randomfloat = $min + mt_rand() / mt_getrandmax() * ($max - $min);
            if($round > 0) $randomfloat = round($randomfloat,$round);
            if($round === 0 OR $round === '0') $randomfloat = intval($randomfloat);

            if (!empty($options['cache'])) {
                if (is_string($options['cache'])) Cache::set('duration', $options['cache'], 'random_float');
                cache::write($cacheName, $randomfloat, 'random_float');
            }
        }

        return str_replace(',', '.', sprintf("%.f", $randomfloat));
    }


  public static function userAgent($userAgent = null) { 

    $request = Router::getRequest();

    if (empty($userAgent)) {
      if ($request) {
        $userAgent = $request->header('User-Agent'); //      
      }else{
        $userAgent = $_SERVER['HTTP_USER_AGENT']; //      
      }
    }

    App::import('Vendor', 'My.Spyc', array('file' => 'spyc'.DS.'Spyc.php'));
    App::import('Vendor', 'My.DeviceDetector', array('file' => 'DeviceDetector'.DS.'autoload.php'));
    
    DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);

    $dd = new DeviceDetector($userAgent);

    // OPTIONAL: If called, getBot() will only return true if a bot was detected  (speeds up detection a bit)
    // $dd->discardBotInformation();

    // OPTIONAL: If called, bot detection will completely be skipped (bots will be detected as regular devices then)
    // $dd->skipBotDetection();

    $dd->parse();

    if ($dd->isBot()) {
      // handle bots,spiders,crawlers,...
      $botInfo = $dd->getBot();
      return false;
    } else {
      $data['device'] = $device = $dd->getDeviceName();
      $data['brand'] = $brand = $dd->getBrandName();
      $data['model'] = $model = $dd->getModel();
      $data['client'] = $clientInfo = $dd->getClient(); // holds information about browser, feed reader, media player, ...
      $data['os'] = $osInfo = $dd->getOs();

      $l10n = new L10n();
      $locale = $l10n->get();
      $catalog = $l10n->catalog($locale);      
      
      $language = Op::language();

      $data['language'] = $catalog;

      $data['userAgent'] = $userAgent;


      if ($userAgent AND !$device AND !$brand AND !$model) {
        return false;
      }


      return $data;

    }


  }

  public static function clientIp($defaultIP = '127.0.0.1') {
    $ipaddr = null;

    if (getenv('HTTP_CLIENT_IP')){
      $ipaddr = getenv('HTTP_CLIENT_IP');
    }else if(getenv('HTTP_FORWARDED')){
      $ipaddr = str_replace('for=', '', getenv('HTTP_FORWARDED'));
    }else if(getenv('HTTP_X_FORWARDED_FOR')){
      $ipaddr = getenv('HTTP_X_FORWARDED_FOR');
    }else if(getenv('HTTP_X_FORWARDED')){
      $ipaddr = getenv('HTTP_X_FORWARDED');
    }else if(getenv('HTTP_FORWARDED_FOR')){
      $ipaddr = getenv('HTTP_FORWARDED_FOR');
    }else if(getenv('HTTP_FORWARDED')){
      $ipaddr = getenv('HTTP_FORWARDED');
    }else if(getenv('REMOTE_ADDR')){
      $ipaddr = getenv('REMOTE_ADDR');
    }else{
      $ipaddr = 'UNKNOWN';
    }

    $ipaddr = trim($ipaddr);
    if ($ipaddr == '::1') {
        $ipaddr = $defaultIP;
    }
    return $ipaddr;
  }


  public static function addTwoTimes($times = array()){

    if ($times AND !is_array($times)) {
        $times = func_get_args();
    }

    $total = 0;
    foreach ($times as $key => $time) {

      list($h, $m, $s) = explode (":", $time);
      $seconds = 0;
      $seconds += (intval($h) * 3600);
      $seconds += (intval($m) * 60);
      $seconds += (intval($s));

      $total = $total + $seconds;
    }

    return sprintf('%02d:%02d:%02d', floor($total / 3600), floor($total / 60 % 60), floor($total % 60));
  }


  public function whereamI($tree = array(), $params = array('start' => false, 'separator' => '»', 'div' => false, 'tag' => false, 'class' => false, 'last' => false)){

    App::uses('HtmlHelper', 'View/Helper');
    $HtmlHelper = new HtmlHelper(new View());

    $whereamI = null;

    if (!empty($params['id']) AND empty($params['div'])) $params['div'] = true;   


    if ($params['div']) {

      if ($params['div'] === true) $params['div'] = 'div';

      $whereamI .= '<'.$params['div'].'';

      if (!empty($params['id'])) $whereamI .= ' id="'.$params['id'].'"'; 

      $whereamI .= '>';
    }


    foreach ($tree as $name => $link) {
      reset($tree);
      $firstkey = key($tree);
      if ($name === $firstkey) {
        if (!empty($params['start'])) $whereamI .= ' '.$params['start'].' ';
      }

      $paramClass = array('escape' => false); 
      if (!empty($params['class'])) $paramClass['class'] = $params['class']; 

      $activeClass = 'active';
      if ($params['class']) {
        $activeClass .= ' '.$params['class'];
      }


      end($tree);
      $lastkey = key($tree);
      if ($params['last'] OR $name !== $lastkey) {

        $iam = $HtmlHelper->link($name, $link, $paramClass);

        if (!empty($params['tag'])) {
          $iam = $HtmlHelper->tag($params['tag'], $iam, $paramClass);
        }

        $whereamI .= $iam;

        $whereamI .= ' '.$params['separator'].' ';
      }elseif (!is_int($name)) {

        if (empty($name) OR empty($params['tag'])) {
          $params['tag']= 'span';
        }

        $whereamI .= $HtmlHelper->tag($params['tag'], $name, array('class' => $activeClass));

      }else{

        if (empty($name)) {
          $params['tag']= 'span';
        }

        $whereamI .= $HtmlHelper->tag($params['tag'], $link, array('class' => $activeClass));
      }
    }

    if (!empty($params['div'])) {
      $whereamI .= '</'.$params['div'].'>';
    }

    return $whereamI;
  }

  public static function daydiff($first, $end){

    $tDeb = explode("-", CakeTime::format('Y-m-d', $first));
    $tFin = explode("-", CakeTime::format('Y-m-d', $end));

    $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - 
            mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
    
    return(($diff / 86400)+1);

  }

  public static function daysofweek($auj = null){

    //setlocale(LC_TIME, 'fr_FR', 'french', 'fre', 'fra');
    if (empty($auj)) {
      $auj = date('Y-m-d');    
    }

    $auj = date_create($auj);
    //date_sub($auj, date_interval_create_from_date_string('-3 days'));
    $auj = date_format($auj, 'Y-m-d');    

    $t_auj = strtotime($auj);
    $p_auj = date('N', $t_auj);
    if($p_auj == 1){
      $deb = $t_auj;
      $fin = strtotime($auj.' + 6 day');
    }
    else if($p_auj == 7){
      $deb = strtotime($auj.' - 6 day');
      $fin = $t_auj;
    }
    else{
      $deb = strtotime($auj.' - '.(6-(7-$p_auj)).' day');
      $fin = strtotime($auj.' + '.(7-$p_auj).' day');
    }
    while($deb <= $fin){
      $days[] = strftime('%Y-%m-%d', $deb);
      //echo strftime('%A %d %B %Y', $deb).'<br />';
      $deb += 86400;
    }
    return  $days;
  }


  public static function numberofweek($ddate = null){
    if (empty($ddate)) {
      $ddate = date('Y-m-d');    
    }

    $ddate = date_create($ddate);
    //date_sub($ddate, date_interval_create_from_date_string('-3 days'));
    $ddate = date_format($ddate, 'Y-m-d');    

    $date = new DateTime($ddate);
    $week = $date->format("W");
    return $week;
  }



  public static function ocr2($file = null){
    App::import('Vendor', 'My.Guzzle', array('file' => 'guzzle'.DS.'autoload.php'));

    $fileData = fopen($file, 'r');

    $client = new \GuzzleHttp\Client();
    try {

      $filesize = filesize($file) / 1024;
      $cacheName = md5(json_encode(array($filesize, getimagesize($file))));

      Cache::set('duration', '+6 month');
      $cache = Cache::read($cacheName);

      if ($cache !== false) {
        $response = unserialize(gzinflate($cache));
      }else{


        $r = $client->request('POST', 'https://api.ocr.space/parse/image',[
            'headers' => ['apiKey' => '4c85cf9aaa88957'],
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => $fileData
                ]
            ]
        ], ['file' => $fileData]);
        $response =  json_decode($r->getBody(),true);

        if (empty($response['ErrorMessage'])) {
          Cache::set('duration', '+6 month');
          Cache::write($cacheName, gzdeflate(serialize($response)));
        }
      }

      if (!empty($file) AND is_file($file) AND !is_dir($file)) {
        @fclose($fileData);
      }

      if(empty($response['ErrorMessage'])) {

        $texte = array();
        foreach($response['ParsedResults'] as $pareValue) {
          $texte[] = $pareValue['ParsedText'];
        }

        return implode('', $texte);

      } else {
        //header('HTTP/1.0 400 Forbidden');
        echo implode('', $response['ErrorMessage']);
        return false;
      }
    } catch(Exception $err) {
      //header('HTTP/1.0 403 Forbidden');
      echo $err->getMessage();
      return false;
    }

  }



  public static function ocr($file = null){

    if($file) {
        $target_dir = "/ocr/thumb/";
        $uploadOk = 1;
        $FileType = strtolower(pathinfo($file["name"],PATHINFO_EXTENSION));
        $dir_file = $target_dir . md5(implode('', $file));
        $target_file = $dir_file .'.'.$FileType;
        // Check file size
        if ($file["size"] > 5000000) {
            header('HTTP/1.0 403 Forbidden');
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        if(!in_array($FileType, array("pdf", "png", "jpg", "jpeg"))) {
            header('HTTP/1.0 403 Forbidden');
            echo "Format .".$FileType." non supporté";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {

            if(!file_exists(WWW_ROOT . $target_dir)){
              mkdir(WWW_ROOT . $target_dir, 0777, true);
            }

            chmod(WWW_ROOT . $target_dir, 0777);

            if (copy($file["tmp_name"], WWW_ROOT . $target_file)) {

               $filesize = filesize(WWW_ROOT . $target_file) / 1024;


                $quality = 100;
                while ($filesize > 1024) {

                  $quality = $quality - 5;

                  $newImg = Op::resizedUrl($target_file, array('quality' => $quality, 'dir' => $target_dir));
                  $newsize = filesize(WWW_ROOT . $newImg) / 1024;

                  $filesize = $newsize;
                  if ($filesize <= 1024) {
                    $oldpath = $target_file;
                    $target_file = $newImg;
                  }else{
                    unlink(WWW_ROOT . $newImg);
                  }

                }


                $fileData = fopen(WWW_ROOT . $target_file, 'r');

                $client = new \GuzzleHttp\Client();
                try {

                  $cacheName = md5(json_encode(array($filesize, getimagesize(WWW_ROOT . $target_file))));

                  Cache::set('duration', '+6 month');
                  $cache = Cache::read($cacheName);

                  if ($cache !== false) {
                    $response = unserialize(gzinflate($cache));
                  }else{


                    $r = $client->request('POST', 'https://api.ocr.space/parse/image',[
                        'headers' => ['apiKey' => '4c85cf9aaa88957'],
                        'multipart' => [
                            [
                                'name' => 'file',
                                'contents' => $fileData
                            ]
                        ]
                    ], ['file' => $fileData]);
                    $response =  json_decode($r->getBody(),true);

                    if (empty($response['ErrorMessage'])) {
                      Cache::set('duration', '+6 month');
                      Cache::write($cacheName, gzdeflate(serialize($response)));
                    }
                  }

                  if (!empty($target_file) AND is_file(WWW_ROOT . $target_file) AND !is_dir(WWW_ROOT . $target_file)) {
                    @fclose($fileData);
                    unlink(WWW_ROOT . $target_file);
                  }
                  if (!empty($oldpath) AND is_file(WWW_ROOT . $oldpath) AND !is_dir(WWW_ROOT . $oldpath)) {
                    unlink(WWW_ROOT . $oldpath);
                  }

                  if(empty($response['ErrorMessage'])) {

                    $texte = array();
                    foreach($response['ParsedResults'] as $pareValue) {
                      $texte[] = $pareValue['ParsedText'];
                    }

                    return implode('', $texte);

                  } else {
                    //header('HTTP/1.0 400 Forbidden');
                    echo implode('', $response['ErrorMessage']);
                    return false;
                  }
                } catch(Exception $err) {
                  //header('HTTP/1.0 403 Forbidden');
                  echo $err->getMessage();
                  return false;
                }

            } else {
              //header('HTTP/1.0 403 Forbidden');
              echo "Sorry, there was an error uploading your file.";
              return false;
            }
        }

    } else {
      //header('HTTP/1.0 403 Forbidden');
      echo "Sorry, please upload a pdf file";
      exit('aaaa');

    }
  }


  public static function navigateur() { 
    $u_agent = @$_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";
    $ub = null;

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
        if (preg_match('/NT 6.2/i', $u_agent)) { $platform .= ' 8'; }
            elseif (preg_match('/NT 10.0/i', $u_agent)) { $platform .= ' 10'; }
            elseif (preg_match('/NT 6.3/i', $u_agent)) { $platform .= ' 8.1'; }
            elseif (preg_match('/NT 6.1/i', $u_agent)) { $platform .= ' 7'; }
            elseif (preg_match('/NT 6.0/i', $u_agent)) { $platform .= ' Vista'; }
            elseif (preg_match('/NT 5.1/i', $u_agent)) { $platform .= ' XP'; }
            elseif (preg_match('/NT 5.0/i', $u_agent)) { $platform .= ' 2000'; }
        if (preg_match('/WOW64/i', $u_agent) || preg_match('/x64/i', $u_agent)) { $platform .= ' (x64)'; }
    }    
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/IEMobile/i',$u_agent)){
        $bname = 'Internet Explorer Mobile'; 
        $ub = "IEMobile"; 
    }elseif(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    }elseif(preg_match('/Trident/i',$u_agent)  && !preg_match('/Opera/i',$u_agent)){
        $bname = 'Internet Explorer'; 
        $ub = "Trident"; 
    }elseif(preg_match('/Firefox/i',$u_agent)){
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    }elseif(preg_match('/OPR/i',$u_agent)){ 
        $bname = 'Opera'; 
        $ub = "OPR"; 
    }elseif(preg_match('/Chrome/i',$u_agent)){ 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    }elseif(preg_match('/Safari/i',$u_agent)){ 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    }elseif(preg_match('/Opera/i',$u_agent)){ 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    }elseif(preg_match('/Netscape/i',$u_agent)){ 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {

        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }elseif (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= @$matches['version'][1];
        }

    }elseif ($matches['browser'][0] == 'Trident'){
      preg_match('/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $version);
      $version= @$version[1];
    }
    else {
        $version= $matches['version'][0];
    }
    
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    
    return array(
        'userAgent' => $u_agent,
        'nom'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}


  public static function url_query_add() {
    foreach (func_get_args() as $key => $arg) {   
        if ($key!== 0 AND stripos($arg, '?') !== 0) $arg = substr_replace($arg, '?', 0, 0);
      $url_parsed = parse_url($arg);

      if ($key === 0) {
        $url = $arg;

        $new_url = '';

        if (isset($url_parsed['scheme'])) {
          $new_url .= $url_parsed['scheme'];
          $new_url .= '://';  
        }
        
        if (isset($url_parsed['host'])) $new_url .= $url_parsed['host'];

        $new_url .= $url_parsed['path'];

      }else{
        $qs[] = $arg;
      }

    if (isset($url_parsed['query'])) $args[] = $url_parsed['query'];

    }

    $new_url .= '?';
    $new_url .= implode('&', $args);
      return $new_url;
  }


    public static function apostrophe($nom, $a, $b) {

        if ($a === $b) return $a.' ';

        $pre = mb_strtolower($nom);
        $pre = Inflector::slug($pre);
        $pre = substr($pre, 0, 1);

        if (in_array($pre, array('a', 'e', 'h', 'i', 'o', 'u'))) {
          return $b;
        }elseif (in_array($pre, array('y'))) {
            $pre2 = mb_strtolower($nom);
            $pre2 = Inflector::slug($pre2);
            $pre2 = substr($pre2, 1, 1);
            if (!in_array($pre2, array('a', 'e', 'h', 'i', 'o', 'u'))) {
                return $b;
            }
        }
        return $a.' ';
        
    }

    public static function decode($data){  
        if(is_object($data) || is_array($data)){
            foreach($data as &$value)
                $value = op::decode($value);
        }
        else $data = utf8_encode($data);

        return $data;
    }

    public static function noaccent($str, $charset='UTF-8') {
        $map =  array('/À|Á|Â|Ã|Å|Ǻ|Ā|Ă|Ą|Ǎ/' => 'A',  '/Æ|Ǽ/' => 'AE',  '/Ä/' => 'Ae',  '/Ç|Ć|Ĉ|Ċ|Č/' => 'C',  '/Ð|Ď|Đ/' => 'D',  '/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě/' => 'E',  '/Ĝ|Ğ|Ġ|Ģ|Ґ/' => 'G',  '/Ĥ|Ħ/' => 'H',  '/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ|І/' => 'I',  '/Ĳ/' => 'IJ',  '/Ĵ/' => 'J',  '/Ķ/' => 'K',  '/Ĺ|Ļ|Ľ|Ŀ|Ł/' => 'L',  '/Ñ|Ń|Ņ|Ň/' => 'N',  '/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ/' => 'O',  '/Œ/' => 'OE',  '/Ö/' => 'Oe',  '/Ŕ|Ŗ|Ř/' => 'R',  '/Ś|Ŝ|Ş|Ș|Š/' => 'S',  '/ẞ/' => 'SS',  '/Ţ|Ț|Ť|Ŧ/' => 'T',  '/Þ/' => 'TH',  '/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ/' => 'U',  '/Ü/' => 'Ue',  '/Ŵ/' => 'W',  '/Ý|Ÿ|Ŷ/' => 'Y',  '/Є/' => 'Ye',  '/Ї/' => 'Yi',  '/Ź|Ż|Ž/' => 'Z',  '/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª/' => 'a',  '/ä|æ|ǽ/' => 'ae',  '/ç|ć|ĉ|ċ|č/' => 'c',  '/ð|ď|đ/' => 'd',  '/è|é|ê|ë|ē|ĕ|ė|ę|ě/' => 'e',  '/ƒ/' => 'f',  '/ĝ|ğ|ġ|ģ|ґ/' => 'g',  '/ĥ|ħ/' => 'h',  '/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı|і/' => 'i',  '/ĳ/' => 'ij',  '/ĵ/' => 'j',  '/ķ/' => 'k',  '/ĺ|ļ|ľ|ŀ|ł/' => 'l',  '/ñ|ń|ņ|ň|ŉ/' => 'n',  '/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º/' => 'o',  '/ö|œ/' => 'oe',  '/ŕ|ŗ|ř/' => 'r',  '/ś|ŝ|ş|ș|š|ſ/' => 's',  '/ß/' => 'ss',  '/ţ|ț|ť|ŧ/' => 't',  '/þ/' => 'th',  '/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ/' => 'u',  '/ü/' => 'ue',  '/ŵ/' => 'w',  '/ý|ÿ|ŷ/' => 'y',  '/є/' => 'ye',  '/ї/' => 'yi',  '/ź|ż|ž/' => 'z');

        $str = preg_replace(array_keys($map), array_values($map), $str);
        
        return $str;
    }

    public static function rangement($array) {
        $array2 = $array;
        $i = 0;
        foreach ($array2 as $key => $value) {
            $i ++;
            $array3[$i] = $value;
        }
        return $array3;
    }


    public static function dateperiod($from, $to) {

      $foreplaces = array(' %B %Y', ' %Y');

      $fromstartdate = Caketime::format($from, '%d %B %Y');
      $toenddate = Caketime::format($to, '%d %B %Y');
      foreach ($foreplaces as $key => $foreplace) {
        $fromstartdate = str_replace(Caketime::format($to, $foreplace), '', $fromstartdate);
      }


      $datePeriod = array();
      if ($fromstartdate) {
        $datePeriod[0] = $fromstartdate;
      }

      if ($toenddate) {
        $datePeriod[1] = $toenddate;
      }

      if (!empty($datePeriod[0]) AND !empty($datePeriod[1])) {
        $datePeriod = 'du '.implode(__(' au '), $datePeriod);
      }elseif ($datePeriod[0]) {
        $datePeriod = "le ".$datePeriod[0];
      }elseif ($datePeriod[1]) {
        $datePeriod = "jusqu'au ".$datePeriod[1];
      }

      return $datePeriod;

    }

    public static function ishtml($string) {
      // Check if string contains any html tags.
      return preg_match('/<\s?[^\>]*\/?\s?>/i', $string);
    }


    public static function mysql_escape_mimic($inp) {
      if(is_array($inp))
          return array_map(__METHOD__, $inp);

      if(!empty($inp) && is_string($inp)) {
          return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
      }

      return $inp;
    }



    public static function str_replace_all($values, $replace, $string) {

      $string = json_encode($string);
      $string = str_replace($values, $replace, $string);
      $string = json_decode($string, true);

      return $string;

    }



    public static function compareName($name1, $name2) {

      $name1 = str_replace(array('_'), ' ', Inflector::slug($name1));
      $name2 = str_replace(array('_'), ' ', Inflector::slug($name2));


      $nams1 = explode(' ', $name1);
      $nams2 = explode(' ', $name2);

      foreach ($nams2 as $nam2) {

        foreach ($nams1 as $nam1) {
          if (!isset($levenshteins[$nam2])) {
            $levenshteins[$nam2] = 99;
          }

          $leven = levenshtein($nam1, $nam2);

          if ($leven < $levenshteins[$nam2]) {
            $levenshteins[$nam2] = $leven;
          }

        }

      }

      $count = 0;
      foreach ($levenshteins as $c) {
        $count = $count + $c;
      }

      $count = $count/count($levenshteins);

      return $count;

    }


    public static function compress($data) {
      $output = rtrim(strtr(base64_encode(gzdeflate(json_encode($data), 9)), '+/', '-_'), '=');
      return $output;
    }

    public static function decompress($data) {
      $output = json_decode(gzinflate(base64_decode(strtr($data, '-_', '+/'))), true);
      return $output;
    }

    public static function mediaTmp($last = false) {

      if ($last) {
        $md5 = cache::read('mediaTmp');

        if ($md5) {
          return $md5;
        }
      }

      $md5new = md5(date('Y-m-d H:i:s'));

      if (cache::write('mediaTmp', $md5new)) {
        return $md5new;
      }

    }




  public static function watermark($img, $watermark) {
    
    $emptypic = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
    if (empty($img) OR $img == '/' OR $img == $emptypic) return $emptypic;

    //debug($img);
    //debug($watermark);

    $base1 = WWW_ROOT.$img; // La photo est la destination
    $imgInfo = getimagesize($base1);
    list($imgWidth, $imgHeight) = $imgInfo;



    $unfullbase3 = $watermark; // La photo est la destination
    $base3 = str_replace(DS.'/', DS, WWW_ROOT.$unfullbase3);
    $watermarkInfo = getimagesize($base3);
    list($watermarkWidth, $watermarkHeight) = $watermarkInfo;



    $watermarkMd5 = md5(json_encode(array_merge(array(md5(file_get_contents($base1)), md5(file_get_contents($base3)), $imgInfo, $watermarkInfo))));


    $unfullfolder = '/watermark/gen/';
    $unfullfinal = $unfullfolder.$watermarkMd5.'.png';
    $final = str_replace(DS.'/', DS, WWW_ROOT.$unfullfinal);


    if (!file_exists($final)) {

      $image_1 = imagecreatefrom($base1);
      $image_3 = imagecreatefrom($base3);


      if(!file_exists(WWW_ROOT . $unfullfolder)){
        mkdir(WWW_ROOT . $unfullfolder, 0777, true);
        chmod(WWW_ROOT . $unfullfolder, 0777);
      }


      imagealphablending($image_1, true);
      imagesavealpha($image_1, true);



      $image_photo_width = $watermarkWidth;
      $image_photo_heigth = $watermarkHeight;

      $src_x = $src_y = 0;

      if($imgWidth*$watermarkHeight > $imgHeight*$watermarkWidth){
        $image_photo_heigth = $imgHeight/1.5;
        $image_photo_width = $image_photo_heigth*$watermarkWidth/$watermarkHeight;
      }else{
        $image_photo_width = $imgWidth/1.5;
        $image_photo_heigth = $image_photo_width*$watermarkHeight/$watermarkWidth;
      }

      $xt = ($imgWidth/2) - ($image_photo_width/2);
      $yt = /*215 + */($imgHeight/2) - ($image_photo_heigth/2);

      imagecopyresized($image_1, $image_3, $xt, $yt, $src_x, $src_y, $image_photo_width, $image_photo_heigth, $watermarkWidth, $watermarkHeight);


      imagepng($image_1, $final);

      //$aUrl = Router::url($unfullfinal);

    }

    return $unfullfinal; 

    //echo '<img src="'.Router::url($unfullfinal).'"/>';


  }



  public static function fontFormUpdate($fontForm, $police) {
    $return = $police['regular'];

    $pos['bolditalic'] = array('b', 'i');
    $pos['bold'] = array('b');
    $pos['italic'] = array('i');

    foreach ($pos as $poKey => $po) {
      if(array_intersect($po, $fontForm) AND !array_diff($po, $fontForm)) {
        return $police[$poKey];
      }
    }

    return $return;

  }













  public static function fontBox($graphParts, $options = array()) {
    $options += array('angle' => 0, 'image' => false);
    extract($options);


    if ($image) {
      $white = imagecolorallocate($image, 255, 255, 255);
      $grey = imagecolorallocate($image, 128, 128, 128);
      $black = imagecolorallocate($image, 0, 0, 0);
      $red = imagecolorallocate($image, 128, 0, 0);
      //imagefilledrectangle($image, 0, 0, 399, 29, $white);

      $imageX = imagesx($image);
      $imageY = imagesy($image);

      $text_width = $imageX-($xStart*2);

    }

    $x = 0;              
    $y = 0;              
    if ($image) {
      $x = $xStart;
      $y = $yStart;              
    }


        foreach ($graphParts[1] as $keyGraphPart => $graphPart) {
            $oldGraphPart = $graphPart;

            $graphPart = str_replace(array('<br>', '<br/>'), "!ste!sta!", $graphPart);

            $graphPart = Op::HtmlToText($graphPart);

            $graphPart = str_replace("!ste!sta!", "\n", $graphPart);

            $miniParts = explode("\n", $graphPart);
            foreach ($miniParts as $miniPartKey => $miniPart) {
              if (!$miniPart) {
                unset($miniParts[$miniPartKey]);
              }
            }

            foreach ($miniParts as $miniPart) {

              $parts = mb_str_split($miniPart, 1, 'UTF8'); // Ancien

              $visitWidth = $x;

              $zWhere = 0;

              $fontForm = [];
              $font_ocrb = Op::fontFormUpdate($fontForm, $polices);


              $jumpBeforeText = $font_size*23/12;
              $miniJumpBeforeTextnoParagraph = $font_size*42/32;

              $TrueParts = $TruePartCaches = array();
              $TruePartLevel = 0;

              $TrueI = 0;

              $TrueLastKey = array_keys($parts);
              $TrueLastKey = end($TrueLastKey);
              $lastSpacekeyTruePar = false;
              $fontOcrbCache = array();

              while ($TrueI <= $TrueLastKey) {

                  //if (!empty($parts[$TrueI])) {
                    $part = $parts[$TrueI];
                  //}else{
                    //$TrueI ++;
                    //continue;
                  //}

                    
                  if ($visitWidth-100.25 > $imageX-($xStart*2)) {

                    $visitWidth = $x;

                    if(!in_array($part, array(''/*,' '*/))) {

                      foreach ($TrueParts[$TruePartLevel] as $keyTruePar => $TruePar) {
                        if ($TruePar['part'] == ' ') {
                          $lastSpacekeyTruePar = $keyTruePar;
                        }
                      }

                      foreach ($TrueParts[$TruePartLevel] as $keyTruePar => $TruePar) {
                        if ($keyTruePar < $lastSpacekeyTruePar) {
                          unset($parts[$keyTruePar]);
                          continue;
                        }
                        unset($TrueParts[$TruePartLevel][$keyTruePar]);
                      }

                      $TrueI = $lastSpacekeyTruePar + 1;

                      $TruePartLevel ++;

                      continue;

                    }                  

                  }

                  //Ne pas considerer les espaces en debut de paragraphe
                  if($visitWidth == $x){
                    if(in_array($part, array(''/*,' '*/))) {
                      //continue; // Remettre urgent
                    }                  
                  }
                  // Fin


                  $passPart = false;

                  $rt = 0;
                  //debug($part);
                  $zAdd = 0;
                  while (!$passPart) {

                    if (in_array($TrueI, array_keys($TruePartCaches))) {

                      //debug($TrueI);
                      //debug($TrueParts);

                      $TrueParts[$TruePartLevel][$TrueI] = $TruePartCaches[$TrueI];
                      //debug($TruePartCaches[$TrueI]);

                      break;
                    }


                    if ($TrueI == 18) {
                      //debug($TruePartCaches);
                      //debug($zWhere.' - '.$part.' - '.mb_substr($graphParts[1][$keyGraphPart], $zWhere+$zAdd).' - '.($zWhere+$zAdd));
                    }

                    if ($zWhere+$zAdd > strlen($graphParts[1][$keyGraphPart])) {
                      break;
                    }

                    $partTwo = mb_substr($graphParts[1][$keyGraphPart], $zWhere+$zAdd, 1);
                    //debug($part.' - '.$partTwo.' - '.$zWhere+$zAdd);



                      if ($part == $partTwo) {
                          //debug('Passe Ok');
                          $passPart = true;
                      }elseif (mb_substr($graphParts[1][$keyGraphPart], $zWhere+$zAdd, 1) == '<') {
                          $closeBalPost = strpos(mb_substr($graphParts[1][$keyGraphPart], $zWhere+$zAdd), '>');


                          if ($closeBalPost) {

                              $balType = explode(' ', mb_substr($graphParts[1][$keyGraphPart], $zWhere+$zAdd+1, $closeBalPost-1))[0];

                      if ($zWhere > 35) {
                        //debug($part.' - '.mb_substr($graphParts[1][$keyGraphPart], $zWhere+$zAdd).' - '.$zWhere.' - '.$zAdd);
                        //debug($balType);

                      }

                              if(in_array($balType, array('b', 'strong'))) {
                                  $fontForm['b'] = 'b';
                                  //debug('majuscule');
                              }elseif(in_array($balType, array('/b', '/strong'))) {
                                  unset($fontForm['b']);
                                  //debug('fin majuscule');
                              }elseif(in_array($balType, array('i', 'em'))) {
                                  $fontForm['i'] = 'i';
                                  //debug('italique');
                              }elseif(in_array($balType, array('/i', '/em'))) {
                                  unset($fontForm['i']);
                                  //debug('fin italique');
                              }

                              $font_ocrb = Op::fontFormUpdate($fontForm, $polices);

                              $zWhere = $zWhere + $closeBalPost;

                              //debug($balType);

                          }


                      }else{

                        //debug($zWhere);
                        
                        //$passPart = true;
                        //debug($zWhere);
                        //continue;
                        //debug(mb_substr($graphParts[1][$keyGraphPart], $zWhere));
                        //debug($zWhere);
                        //exit;
                      }

                      $rt ++;
                      $zAdd ++;



                      if ($rt == 1000) {
                        exit;
                      }

                  }



                  if (!in_array($TrueI, array_keys($TruePartCaches))) {
                    $zWhere = $zWhere+$zAdd;
                  }


                  $mini_text_box1 = imagettfbbox($font_size,$angle,$font_ocrb,$part);



                  if (!empty($TruePartCaches[$TrueI])) {
                    $TrueParts[$TruePartLevel][$TrueI] = $TruePartCaches[$TrueI];
                  }else{
                    $TruePartCaches[$TrueI] = $TrueParts[$TruePartLevel][$TrueI] = array('part' => $part, 'font_size' => $font_size, 'angle' => $angle, 'color' => ($image ? $white : false), 'font_ocrb' => $font_ocrb);
                  }


                  $visitWidth = $visitWidth + ($mini_text_box1[4]+1.05);
                  


                  //exit;

                  
                  if ($image) {
                    //debug($part.' - '.$TrueI.' - '.$lastSpacekeyTruePar);
                    //debug($parts);
                  }




                $TrueI ++;
              } // End While ($TrueI < $TrueLastKey)


              foreach ($TrueParts as $key => $miniTruePart) {

                $visitWidth = $x;


                //$miniTruePart = array_values($miniTruePart)[0];
                //();
                $miniTrueBox = imagettfbbox($font_size,$angle, Op::fontFormUpdate(array(), $polices),'R');
                //debug($miniTrueBox);
                $yTrueBoxPostByFontSize = abs($miniTrueBox[7]);
                //debug($y);



                $miniTruePartHeight = 0;

                foreach ($miniTruePart as $keyTruePart => $TruePart) {

                  $mini_text_box1 = $mini_text_boxs[$keyTruePart] = imagettfbbox($TruePart['font_size'], $TruePart['angle'], $TruePart['font_ocrb'], $TruePart['part']);

                  $miniTruePartHeight = $miniTruePartHeight + ($mini_text_box1[4]+1.05);
                }


                foreach ($miniTruePart as $keyTruePart => $TruePart) {

                  $mini_text_box1 = $mini_text_boxs[$keyTruePart];

                  if ($image) {

                    $Xalign = $visitWidth; //left
                    if($align == 1){
                      $Xalign = ($text_width/2) - ($miniTruePartHeight/2) + $visitWidth; // Center
                    }elseif($align == 2){
                      $Xalign = $text_width - $miniTruePartHeight + $visitWidth; // Right
                    }

                    imagettftext($image, $TruePart['font_size'], $TruePart['angle'], $Xalign, $y+$yTrueBoxPostByFontSize, $TruePart['color'], $TruePart['font_ocrb'], $TruePart['part']);
                  }

                  $visitWidth = $visitWidth + ($mini_text_box1[4]+1.05);

                }

                if (end($TrueParts) != $miniTruePart){
                  $y = $y + $miniJumpBeforeTextnoParagraph; // A la petite ligne pour les paragraphes coupés par la largeur
                }

              }

              if (end($miniParts) != $miniPart){
                $y = $y + $miniJumpBeforeTextnoParagraph; // A la petite ligne pour les paragraphes coupés par Shift Entrée
              }
            
            } // (foreach ($miniParts as $miniPart))

            if ($image) {
              //exit;
            }
            

            if (end($graphParts[1]) != $oldGraphPart){
              $y = $y + $jumpBeforeText;
            }

        }

    if (!$image) {
      return array('heightBox' => $y+$yTrueBoxPostByFontSize, 'jumpBeforeText' => $jumpBeforeText);
    }
    //imagettftext($image, $TruePart['font_size'], $TruePart['angle'], $visitWidth, $y, $TruePart['color'], $TruePart['font_ocrb'], 'Ste');

  }




  public static function fontAutoSize($graphParts, $options = array()) {
    $options += array('angle' => 0, 'image' => false);
    extract($options);

    $fontForm = [];
    $font_ocrb = Op::fontFormUpdate($fontForm, $polices);

    $wrapTest = false;
    while (!$wrapTest) {
        $heightBox = ($font_size/8);
        $wrapTest = true;

        $yPostByFontSize = abs($font_size/1.12);
        //debug($yPostByFontSize);
        $mini_text_box = imagettfbbox($font_size,$angle,$font_ocrb,'R');
        $yPostByFontSize = $mini_text_box[4];
        $yPostByFontSize = 0;
        //debug($yPostByFontSize);


        $offsetY = 0;
        $offsetY = 130; //80 ancien

        if ($maxHeight) {
          $textHeigtMax = $maxHeight-$yPostByFontSize;
        }else{
          $textHeigtMax = $imageY-$yStart-$yPostByFontSize-$offsetY;
        }






        $options['imageX'] = imagesx($image);
        $options['imageY'] = imagesy($image);
        $options['image'] = false;
        $options['heightBox'] = $heightBox;

        $optionsFontBox = Op::fontBox($graphParts, array('operation' => 'write', 'font_size' => $font_size) + $options);
        extract($optionsFontBox);
        

        if($heightBox/*-$jumpBeforeText*/ > ($textHeigtMax/*-$yPostByFontSize*/)){
            $font_size = $font_size-1;
            $wrapTest = false;
        }
    }

    return $font_size;

  }











  public static function imageTextWrite($image_1, $text, $options = array()) {
    $options += array('maxfontsize' => 130, 'angle' => 0);
    extract($options);




    $fontForm = [];
    $font_ocrb = Op::fontFormUpdate($fontForm, $polices);

    $font_size = $maxfontsize;


    preg_match_all( '#<p[^>]*>(.+?)</p>#', Op::MinifyHtml($text), $graphParts);
    //debug($text);
    //debug($graphParts);
    //exit;



    //if ($maxfontsize AND !$font_size) {
      $font_size = Op::fontAutoSize($graphParts, array('operation' => 'write', 'font_size' => $font_size, 'image' => $image_1) + $options);
    //}


    $mini_text_box = imagettfbbox($font_size,$angle,$font_ocrb,'R');
    $yPostByFontSize = $mini_text_box[4];
    $y = $yStart+$yPostByFontSize;




    Op::fontBox($graphParts, array('operation' => 'write', 'font_size' => $font_size, 'image' => $image_1) + $options);


    return $image_1;

  }

  public static function RandomString($length = 10, $characters = array('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')) {
    return substr(str_shuffle($characters), 0, $length);
  }


}