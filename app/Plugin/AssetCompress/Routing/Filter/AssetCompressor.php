<?php

App::uses('DispatcherFilter', 'Routing');
App::uses('AssetConfig', 'AssetCompress.Lib');
App::uses('AssetCompiler', 'AssetCompress.Lib');
App::uses('AssetCache', 'AssetCompress.Lib');
App::uses('Folder', 'Utility');

class AssetCompressor extends DispatcherFilter {

/**
 * Filter priority, we need it to run before router
 *
 * @var integer
 */
	public $priority = 9;

/**
 * Object containing configuration settings for asset compressor
 *
 * @var AssetConfig
 */
	protected $_Config;

/**
 * Checks if request is for a compiled asset, otherwise skip any operation
 *
 * @param CakeEvent $event containing the request and response object
 * @throws NotFoundException
 * @return CakeResponse if the client is requesting a recognized asset, null otherwise
 */
	public function beforeDispatch(CakeEvent $event) {

		$request = $event->data['request'];
		$response = $event->data['response'];
		$url = $request->url;
		$config = $this->_getConfig();
		$production = !Configure::read('debug');
		if ($production && !$config->general('alwaysEnableController')) {
			return;
		}

		$build = $this->_getBuild($url);
		if ($build === false) {
			return;
		}

		if (isset($request->query['theme'])) {
			$config->theme($event->data['request']->query['theme']);
		}

		// Dynamically defined build file. Disabled in production for
		// hopefully obvious reasons.
		if ($config->files($build) === array()) {
			$files = array();
			if (isset($request->query['file'])) {
				$files = $request->query['file'];
			}
			$config->files($build, $files);
		}

		// Use the TMP dir for dev builds.
		// This is to avoid permissions issues with the configured paths.
		$cachePath = CACHE . 'asset_compress' . DS;
		$folder = new Folder($cachePath, true, 0777);

		$ext = $config->getExt($build);
		$config->cachePath($ext, $cachePath);
		$config->set("$ext.timestamp", false);

		try {
			$compiler = new AssetCompiler($config);
			$cache = new AssetCache($config);
			if ($cache->isFresh($build)) {
				$contents = file_get_contents($cachePath . $cache->buildFileName($build));
			} else {
				$contents = $compiler->generate($build);
				$cache->write($build, $contents);
			}
		} catch (Exception $e) {
			throw new NotFoundException($e->getMessage());
		}

		// On force le cache du navigateur a mettre en cache les assetcompress
		$pathinfo = pathinfo(trim($url, DS));

		$assets = array('js' => 'text/javascript', 'css' => 'text/css', 'gif' => 'image/gif', 'jpg' => 'image/jpeg', 'png' => 'image/png');
		$ext = $pathinfo['extension'];

      	$assetFile = APP.'tmp'.DS.'cache'.DS.'asset_compress'.DS.$pathinfo['basename'];

		$fileModified = filemtime($assetFile);
		header("Date: " . date("D, j M Y G:i:s ", $fileModified) . 'GMT');
		header('Content-type: ' . $assets[$ext]);
		header("Expires: " . gmdate("D, j M Y H:i:s", time() + (2*MONTH)) . " GMT");
		header("Cache-Control: cache");
		header("Pragma: cache");


		$response->type($config->getExt($build));
		$response->body($contents);
		$event->stopPropagation();
		return $response;
	}

/**
 * Returns the build name for a requested asset
 *
 * @return boolean|string false if no build can be parsed from URL
 * with url path otherwise
 */
	protected function _getBuild($url) {
		$parts = explode('.', $url);
		if (count($parts) < 2) {
			return false;
		}

		$path = $this->_getConfig()->cachePath($parts[(count($parts) - 1)]);
		if (empty($path)) {
			return false;
		}

		$path = str_replace(WWW_ROOT, '', $path);
		if (strpos($url, $path) !== 0) {
			return false;
		}
		return str_replace($path, '', $url);
	}

/**
 * Config setter, used for testing the filter.
 */
	protected function _getConfig() {
		if (empty($this->_Config)) {
			$this->_Config = AssetConfig::buildFromIniFile();
		}
		return $this->_Config;
	}

}
