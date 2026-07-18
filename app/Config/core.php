<?php
Configure::write('Time', microtime(true));

$localhost = array('127.0.0.1', "::1");
if(in_array($_SERVER['REMOTE_ADDR'], $localhost) OR (stripos($_SERVER['REMOTE_ADDR'], '192.168.') === 0) OR $_SERVER['SERVER_NAME'] == 'ste-pc') {
	Configure::write('debug', 2);
	Configure::write('App.localhost', true);
}else {
	Configure::write('debug', 2);
    Configure::write('App.localhost', false);

    App::uses('CakeRequest', 'Network');
    $request = new CakeRequest;
    
    // NOUVEAU CODE : On ne force le cookie_domain que si ce n'est pas une IP brute
    $domain = $request->domain();
    if (!filter_var($domain, FILTER_VALIDATE_IP)) {
        ini_set('session.cookie_domain', $domain);
    }

}

$engine = 'File';
if (((extension_loaded('apc') && function_exists('apc_dec')) OR (extension_loaded('apcu') && function_exists('apcu_dec'))) && (php_sapi_name() !== 'cli' || ini_get('apc.enable_cli'))) {
    $engine = 'Apc';
}elseif (extension_loaded('xcache')) {
    $engine = 'Xcache';
}elseif (extension_loaded('memcached') && php_sapi_name() !== 'cli') {
	$engine = 'Memcached';
}elseif (extension_loaded('memcache') && php_sapi_name() !== 'cli') {
	$engine = 'Memcache';
}

Configure::write('Session', array(
    'defaults' => 'php',//'database',
   	'timeout' => 4320, //3 days
    'Session.autoRegenerate' => true,
	'cookie' => 'transexpress',
    'ini' => array(
        //'cookie_domain' => 'proteamrdc.com'
    ),
	'checkAgent' => true,
    'handler' => array(
        'config' => 'session',
        'engine' => 'ComboSession',
        'model' => 'Session',
        'cache' => 'default'
    )
));

$prefix = 'transexpress_';

// On configure un cache par default et un cache pour les requetes SQL
Cache::config('default', array('engine' => $engine, 'duration'=>'60 min', 'prefix' => $prefix . 'default_'));
Cache::config('query', array('engine' => $engine, 'duration'=>'60 min', 'prefix' => $prefix . 'query_', 'lock' => false, 'mask' => 0666));
Cache::config('paypal', array('engine' => $engine, 'duration'=>'360 days', 'prefix' => $prefix . 'paypal_'));
Cache::config('url', array('engine' => $engine, 'duration'=>'60 min', 'prefix' => $prefix . 'url_', 'serialize' => false));
// Numero aleatoire du triage de la vitrine
Cache::config('random_float', array('engine' => $engine, 'duration'=>'+1 days', 'prefix' => $prefix . 'random_float_'));
//debug(Cache::settings());



Configure::write(
    'Mail', array(

    	'noreply' => array(
	    	'Business' => array(
	    		'config' => 'transnumerica',
	    		'username' => 'info@tnsarl.com',
	    		'password' => 'TNSARL@2024',
	    		'email' => array('info@tnsarl.com' => 'TransNumerica'),
	    	), 
    	),

    	'contact' => array(
	    	'Business' => array(
	    		'config' => 'transnumerica',
	    		'username' => 'info@tnsarl.com',
	    		'password' => 'TNSARL@2024',
	    		'email' => array('info@tnsarl.com' => 'TransNumerica'),
	    	), 
    	),

    	'manager' => array(
	    	'Business' => array(
	    		'config' => 'transnumerica',
	    		'username' => 'info@tnsarl.com',
	    		'password' => 'TNSARL@2024',
	    		'email' => array('info@tnsarl.com' => 'TransNumerica'),
	    	), 
    	),
    )
);


// In development mode, caches should expire quickly.
$duration = '+1 min';
if (Configure::read('debug') > 0) {
	$duration = '+10 seconds';
}

// Prefix each application on the same server with a different string, to avoid Memcache and APC conflicts.

/**
 * Configure the cache used for general framework caching. Path information,
 * object listings, and translation cache files are stored with this configuration.
 */
Cache::config('_cake_core_', array(
	'engine' => $engine,
	'prefix' => $prefix . 'cake_core_',
	'path' => CACHE . 'persistent' . DS,
	'serialize' => ($engine === 'File'),
	'duration' => $duration
));

/**
 * Configure the cache for model and datasource caches. This cache configuration
 * is used to store schema descriptions, and table listings in connections.
 */
Cache::config('_cake_model_', array(
	'engine' => $engine,
	'prefix' => $prefix . 'model_',
	'path' => CACHE . 'models' . DS,
	'serialize' => ($engine === 'File'),
	'duration' => $duration
));

/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */
 Inflector::rules('plural', array(
      //'rules' => array('/^(inflect)ors$/i' => '\1ables'),
      //'uninflected' => array('dontinflectme'),
      'irregular' => array('journal' => 'journaux')
 ));
/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 */
 // Loads all plugins at once
 // CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 
 

/**
 * To prefer app translation over plugin translation, you can set
 *
 * Configure::write('I18n.preferApp', true);
 */

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyCacheFilter' => array('prefix' => 'my_cache_'), //  will use MyCacheFilter class from the Routing/Filter package in your app with settings array.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 *		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
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