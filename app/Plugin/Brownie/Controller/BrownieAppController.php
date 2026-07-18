<?php
Configure::write('debug', 1);
$defaultSettings = array(
	'css' => array(
		'/brownie/css/brownie',
		'/brownie/css/fancybox/jquery.fancybox-1.3.1',
		'/brownie/css/themes/jquery-ui-1.8.16.custom',
		'/brownie/css/jquery.multiselect',
	),
	'js' => array(
		'/brownie/js/jquery-1.11.3.min',
		'/brownie/js/jquery-ui-1.8.16.custom.min',
		'/brownie/js/jquery.fancybox-1.3.1.pack',
		'/brownie/js/jquery.selso',
		'/brownie/js/jquery.comboselect',
		'/brownie/js/jquery.jDoubleSelect',
		'/brownie/js/jquery.multiselect.min',
		'/brownie/js/jquery.multiselect.filter.min',
		'/brownie/js/brownie',
	),
	'customHome' => false,
	'userModels' => array('BrwUser'),
	'uploadsPath' => './uploads',
	'dateFormat' => 'Y-m-d',
	'formDateFormat' => 'DMY',
	'datetimeFormat' => 'Y-m-d H:i:s',
	'timeFormat' => '24',
	'defaultExportType' => 'csv',
	'defaultPermissionPerAuthModel' => 'none',
	'defaultImageQuality' => '95',
);
if (file_exists(WWW_ROOT . 'css' . DS . 'brownie.css')) {
	$defaultSettings['css'][] = 'brownie';
}
if (file_exists(WWW_ROOT . 'js' . DS . 'brownie.js')) {
	$defaultSettings['js'][] = 'brownie';
}
if (file_exists(WWW_ROOT . 'js' . DS . 'tiny_mce' . DS . 'jquery.tinymce.js')) {
	$defaultSettings['js'][] = 'tiny_mce/jquery.tinymce';
} elseif (file_exists(WWW_ROOT . 'js' . DS . 'fckeditor' . DS . 'fckeditor.js')) {
	$defaultSettings['js'][] = 'fckeditor/fckeditor';
} elseif (file_exists(WWW_ROOT . 'js' . DS . 'ckeditor' . DS . 'ckeditor.js')) {
	$defaultSettings['js'][] = 'ckeditor/ckeditor';
}

Configure::write('brwSettings', Set::merge($defaultSettings, (array)Configure::read('brwSettings')));

Configure::write('brwAuthConfig', array(
	'authenticate' => array('Brownie.Brw' => array('fields' => array('username' => 'email'))),
	'loginAction' => array('controller' => 'brownie', 'action' => 'login', 'plugin' => 'brownie', 'brw' => false),
	'loginRedirect' => array('controller' => 'brownie', 'action' => 'index', 'plugin' => 'brownie', 'brw' => false),
	'authError' => __d('brownie', 'Please provide a valid username and password'),
	'flash' => array('element' => 'error','key' => 'auth')

));


//Afficher erreur

/*
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));
	Configure::write('Error', array(
		'handler' => 'ErrorHandler::handleError',
		'level' => E_ALL & ~E_DEPRECATED,
		'trace' => true
	));
Configure::write('Exception', array(
		'handler' => 'ErrorHandler::handleException',
		'renderer' => 'ExceptionRenderer',
		'log' => true
	));
*/

class BrownieAppController extends AppController {


	public $uses = array('BrwUser');
	public $layout = 'brownie';
	public $components = array(
		'Security',
		'Auth' => array(
			'logoutRedirect' => array('plugin' => 'brownie', 'controller' => 'brownie', 'action' => 'login'),
		'flash' => array(
      'element' => 'authrequis',
      'key' => 'auth',
      'params' => array(
        'class' => 'echec',
      )),
			),
		'Brownie.BrwPanel', 'Flash');


	public function beforeFilter() {
  	}

	public function __construct($request, $response) {
		$this->components['Auth'] = Configure::read('brwAuthConfig');
		parent::__construct($request, $response);
	}


	public function _brwCheckPermissions($model, $action = 'read', $id = null) {
		$Model = ClassRegistry::getObject($model);
		if (!$Model) {
			return false;
		}
		//really bad patch, fix with proper permissions
		if ($action == 'read') {
			return true;
		}
		if ($action == 'js_edit') {
			return true;
		}
		if (in_array($action, array('reorder', 'edit_upload', 'delete_upload','edit_id','delete_file'))) {
			$action = 'edit';
		}
		if ($action == 'filter') {
			$action = 'index';
		}
		if (in_array($action, array('delete_multiple'))) {
			$action = 'delete';
		}
		if (!in_array($action, array('index', 'add', 'view', 'delete', 'edit', 'import', 'export'))) {
			return false;
		}
		$Model->Behaviors->attach('Brownie.BrwPanel');
		if (!empty($this->Content)) {
			$actions = $Model->brwConfig['actions'];
			if (!$actions[$action]) {
				return false;
			}
		}
		return true;
	}


	public function arrayPermissions($model) {
		$ret = array(
			'view' => false,
			'add' => false,
			'view' => false,
			'edit' => false,
			'delete' => false,
			'import' => false,
			'index' => false,
		);
		foreach ($ret as $action => $value) {
			$ret[$action] = $this->_brwCheckPermissions($model, $action);
		}

		return $ret;
	}


}