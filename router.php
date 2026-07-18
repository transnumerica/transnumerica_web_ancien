<?php
// router.php à la racine du projet
$root = __DIR__;
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Si le fichier existe physiquement dans le webroot, on le sert directement (CSS, JS, images)
if ($uri !== '/' && file_exists($root . '/app/webroot' . $uri)) {
    return false;
}

// Sinon, on passe la main à l'index de CakePHP
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once $root . '/app/webroot/index.php';
