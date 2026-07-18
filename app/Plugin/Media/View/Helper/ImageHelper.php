<?php

App::uses('AppHelper', 'View/Helper');


class ImageHelper extends AppHelper{

    public $helpers = array('Html','Form');

    /**
     * Generate an image with a specific size
     * @param  string   $image   Path of the image (from the webroot directory)
     * @param  int      $width
     * @param  int      $height
     * @param  array    $options Options (same that HtmlHelper::image)
     * @param  int      $quality
     * @return string   <img> tag
     */
    public function resize($image, $width, $height, $quality = 100, $options = array()){
        $options['width'] = $width;
        $options['height'] = $height;
        return $this->Html->image($this->resizedUrl($image, $width, $height, $quality), $options);
    }


    /**
     * Create an image with a specific size
     * @param  string   $file   Path of the image (from the webroot directory)
     * @param  int      $width
     * @param  int      $height
     * @param  array    $options Options (same that HtmlHelper::image)
     * @param  int      $quality
     * @return string   image path
     */



    function remote_file_exists1($url){
      ini_set('allow_url_fopen', '1');
      if (@fclose(@fopen($url, 'r'))) {
          return true;
      } else {
          return false;
      }
    }


    function remote_file_exists($path){
	    $file_headers = @get_headers($path);
	    if($file_headers === false  OR in_array($file_headers[0], array('HTTP/1.0 404 Not Found','HTTP/1.1 404 Not Found'))) return false;
	    return true;
    }


    function remote_file_exists2($path){
		$url=getimagesize($path);
		if(!is_array($url)) return false;
		return true;
    }


function brw_image_fix_orientation(&$image, $filename) {
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

    public function resizedUrl($files, $options = array()) {
    	if (empty($files)) return '/';
      
      if (is_array($options)) $options += array('width' => null, 'height' => null,'quality' => 100, 'space' => true, 'max-width' => null, 'max-height' => null);
      extract($options);


      # We define the image dir include Theme support
      $imageDir = WWW_ROOT;

      # Nous verifions la confiuration sur l'image

      $pathinfo   = pathinfo(trim($files, DS));
      
      # On corige certains bug des liens pour certains hebergeur Linux
      if (isset($pathinfo['dirname'])){
        $pathinfo['dirname'] = '/'.$pathinfo['dirname'];
        $pathinfo['dirname'] = str_replace('//', '/', $pathinfo['dirname']);
      }else{
        $pathinfo['dirname'] = '/';
      }
      //

      // On verifie que l'image n'est pas au dossier temp
      $dossier_thumb = '/img/temp/thumb/';

      $output = $dossier_thumb . md5($files) . '_';
      if (!empty($options['width'])) $output .= $options['width'] . 'x';
      if (!empty($options['height'])) $output .= $options['height'] . '';
      if (!empty($options['max-width'])) $output .= 'm'.$options['max-width'] . 'x';
      if (!empty($options['max-height'])) $output .= 'm'.$options['max-height']. '';
      $output .= '.' . $pathinfo['extension'];

      if (file_exists($imageDir . $output)) {
        return $output;
      }


      $file = $imageDir . trim($files, DS);

      //On verifie si l'image est bien présent dans le site
      if (is_file($file) OR $remote = $this->remote_file_exists($files)) {

        if (!empty($remote)) {
          $dossier_thumb = '/img/temp/thumb/';
          $file = trim($files, DS);
          $pathinfo['filename'] = md5($files); // On achete le nom de l'image en rapport avec le lien pour eviter les ecrasages non desirés
          //$imageDir = '';
        }else{
          $dossier_thumb = $pathinfo['dirname'] .'/thumb/';
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
        $output = $dossier_thumb.$md5File.'.' . $pathinfo['extension'];






        if (!file_exists($imageDir . $output)) {

          # Setting defaults and meta
          $info                         = getimagesize($file);
          list($width_old, $height_old) = $info;

          # Create image ressource
          switch ( $info[2] ) {
              case IMAGETYPE_GIF:   $image = imagecreatefromgif($file);   break;
              case IMAGETYPE_JPEG:  $image = imagecreatefromjpeg($file);  
              if ($this->brw_image_fix_orientation($image, $file)) {
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


          # On enregistre l'image par rapport à sa qualité
          switch ( $info[2] ) {
            case IMAGETYPE_GIF:   imagegif($image_crop, $imageDir . $output, $quality);    break;
            case IMAGETYPE_JPEG:  imagejpeg($image_crop, $imageDir . $output, $quality);   break;
            case IMAGETYPE_PNG:   imagepng($image_crop, $imageDir . $output, 9); $this->compress_png($imageDir . $output, $quality);    break;
            default: return false;
          }
        }
      }else{
        $output = '/';
      }

      //$output =  str_replace(DS,'/',$output);
      return $output;
    }


  public function compress_png($path_to_png_file, $max_quality = 40) {

    if (ini_get('safe_mode') OR in_array('shell_exec', str_replace(' ', '', explode(',', ini_get('disable_functions'))))) {
      // On retourne à false au cas où shell_exec sera desactiver
      return false;
    }

    if (!file_exists($path_to_png_file)) {
        debug("File does not exist: $path_to_png_file");
    }

    // guarantee that quality won't be worse than that.
    $min_quality = 0;

    // '-' makes it use stdout, required to save to $statut variable
    // '<' makes it read from the given file path
    // escapeshellarg() makes this safe to use with any path


    // On verifie le systeme du server pour determiner l'environement de la commande à utiliser
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
      $PHP_OS = 'win';
    } elseif (strtoupper(substr(PHP_OS, 0, 5)) === 'LINUX') {
      $PHP_OS = 'linux';
      if (PHP_INT_SIZE == 8) {
        $PHP_OS .= DS.'x64';
      }else{
        $PHP_OS .= DS.'x86';
      }
    }
    $statut = shell_exec(APP.'Plugin'.DS.'Media'.DS.'webroot'.DS.'files'.DS.'pngquant'.DS.$PHP_OS.DS."pngquant --force --skip-if-larger --verbose --ext=.png --speed=1  --quality=$min_quality-$max_quality ".escapeshellarg($path_to_png_file).' 2>&1');

    if (stripos($statut, 'No errors detected') === false) {
        debug("Conversion to compressed PNG failed. Is pngquant 1.8+ installed on the server?");
    }

    return $statut;
  }

}
