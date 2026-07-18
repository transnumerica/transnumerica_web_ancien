<?php

class Zip {
  
  public static function create($destination = '', $files = array(), $overwrite = false) {

    if ($ZIP_OPERATION = file_exists($destination) && !$overwrite) {
      return 'exist';
    }

    $validFiles = array();
    if (is_array($files)) {
      foreach ($files as $file) {
        if (file_exists($file) AND !is_dir($file)) {
          $validFiles[] = $file;
        }
      }
    }

    if (count($validFiles) < 1) {
      return false;
    }

    $zip = new ZipArchive();
    $type = $ZIP_OPERATION ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE;
    if ($zip->open($destination, $type) !== true) {
      return false;
    }

    $dest = str_replace('.zip', '', basename($destination));
    foreach ($validFiles as $file) {
      $zip->addFile($file, /*$dest . DS . */basename($file));
    }
    $zip->close();

    return file_exists($destination);
  }


}