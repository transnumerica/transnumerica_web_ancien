<?php
App::uses('ClassRegistry', 'Utility');

Configure::write('Config.languages', array('Français' => 'fra'/*,'Anglais' => 'eng','Swahili' => 'swh','Coréen' => 'kor'*/));
Configure::write('Config.language', 'fra');

App::uses('CakeText', 'Utility');

CakePlugin::loadAll(array(
	'ignoreMissing' => true,
	'bootstrap' => true,
	'routes' => true
));
CakePlugin::load('UrlCache', array('bootstrap' => false, 'routes' => false));



// Paramettre generale sur l'entreprise et les procedures d'amission
Configure::write(
    'Company', array(
    	'first' => '', 
    	'name' => 'TransNumerica', 
    	'type' => '', 
    	'fullname' => 'TransNumerica', 
    	'slogan' => "Transport",
    	//'url' => 'www.bone-tv.com',
    	'disabledRegistration' => false, //Desactiver l'enregistrement des clients
    	'defaultemailConfig' => 'gmail', // Specifier une configuration Email à utiliser (default par default)
    	//'defaultEmail' => array('contact@guc.com' => 'GENERAL AFRICAN CONSULTING SARL'), // L'adresse email de l'entreprise et peud
    	'emailRegister' => true, // Activier la confirmation de l'email lors de l'enregistrement
    	'tokenLife' => '24 hours', // Durée de vie max d'un jeton de verification ou de réinitialisation
    	'autoLoginRegister' => true, // Auto login après une inscriptopn ou une confirmation d'inscription
    	'defaultRegisterUsername' => 'email', // Champ username principale
    )
);

Configure::write('maintenance', false);


//Liste des backEnd Office
Configure::write('backend', array('admin', 'brownie'));

Configure::write('mode', ''); // white - dark
//Configure::write('mode', 'dark'); // white - dark

// Configuration par default des URL de suivie
Configure::write(
    'urlSchema', array(
			//'start' => '>>',
			'separator' => '',
			'id' => 'lieu',
			'class' => 'breadcrumb-item',
	)
);

// On reglès les tailles maximum de poste et de fichier uploader que PHP peut gerer mais c'est mieux dans le HTACESS
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '50M');

//Définit le décalage horaire par défaut de toutes les fonctions date/heure  
date_default_timezone_set("Africa/Kinshasa");


//Definit le fuseau horaire que le site affichera à l'utilisateur
//Configure::write('Config.timezone', 'Africa/Kinshasa'); // Ne mettre surtout pas à false, decommander la ligne pour mettre le fuseau par defaut de la machine

/* Configure le language de script */
setlocale(LC_ALL,'fr','fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');
//setlocale(LC_NUMERIC, 'fr');//Decimal virgule
setlocale(LC_NUMERIC, 'C');//Decimale point

// Specifier une configuration Email à utiliser (default par default)
Configure::write('emailConfig', 'gmail');


//Valeurs des nombres d'article à afficher
Configure::write('pageLimit', array(
		'pardefaut' => '16',
		'valeur' =>array('8','12','16','32','48','60'))
);

// Redacteur principale (ckeditor,tinymce et redactor)
Configure::write('redacteur', 'ckeditor');

//Taux du dollar et taux de la taxe
Configure::write(
	'Achat', array(
		'taxe' => '0', //En pourcentage
		'taux' => array(
			'$' =>  '940', //La valeur de 1 Dollar en franc Congolais
			'€' =>  '1140' //La valeur de 1 Euro en franc Congolais
		)
	)
);


Configure::write(// Paramettre Mobile Banking
	'Pesa', array(
		'Vodacom' => array(
			'label' => 'M-Pesa',
			'slogan' => 'Facilitons-nous la vie',
			'pub' => array('https://vimeo.com/54440894'),
			'tel' => '0818119186',
			'color' => 'blue',
		),
		'Airtel' => array(
			'label' => 'Airtel Money',
			'slogan' => '',
			'pub' => array('https://www.youtube.com/watch?v=p4WswHbJ5TU','https://vimeo.com/73517432'),
			'tel' => '0995474835',
			'color' => 'red',
		),

	)
);


//Liste des backEnd Office
Configure::write('backend', array('admin', 'brownie'));


Configure::write('Error', array(
		'handler' => 'ErrorHandler::handleError',
		'level' => E_ALL & ~E_DEPRECATED,
		'trace' => true
	));

/**
 * Configure the Exception handler used for uncaught exceptions. By default,
 * ErrorHandler::handleException() is used. It will display a HTML page for the exception, and
 * while debug > 0, framework errors like Missing Controller will be displayed. When debug = 0,
 * framework errors will be coerced into generic HTTP errors.
 *
 * Options:
 *
 * - `handler` - callback - The callback to handle exceptions. You can set this to any callback type,
 *   including anonymous functions.
 *   Make sure you add App::uses('MyHandler', 'Error'); when using a custom handler class
 * - `renderer` - string - The class responsible for rendering uncaught exceptions. If you choose a custom class you
 *   should place the file for that class in app/Lib/Error. This class needs to implement a render method.
 * - `log` - boolean - Should Exceptions be logged?
 * - `skipLog` - array - list of exceptions to skip for logging. Exceptions that
 *   extend one of the listed exceptions will also be skipped for logging.
 *   Example: `'skipLog' => array('NotFoundException', 'UnauthorizedException')`
 *
 * @see ErrorHandler for more information on exception handling and configuration.
 */
	Configure::write('Exception', array(
		'handler' => 'ErrorHandler::handleException',
		'renderer' => 'ExceptionRenderer',
		'log' => true
	));

/**
 * Application wide charset encoding
 */
	Configure::write('App.encoding', 'UTF-8');

/**
 * To configure CakePHP *not* to use mod_rewrite and to
 * use CakePHP pretty URLs, remove these .htaccess
 * files:
 *
 * /.htaccess
 * /app/.htaccess
 * /app/webroot/.htaccess
 *
 * And uncomment the App.baseUrl below. But keep in mind
 * that plugin assets such as images, CSS and JavaScript files
 * will not work without URL rewriting!
 * To work around this issue you should either symlink or copy
 * the plugin assets into you app's webroot directory. This is
 * recommended even when you are using mod_rewrite. Handling static
 * assets through the Dispatcher is incredibly inefficient and
 * included primarily as a development convenience - and
 * thus not recommended for production applications.
 */
	//Configure::write('App.baseUrl', env('SCRIPT_NAME'));

/**
 * To configure CakePHP to use a particular domain URL
 * for any URL generation inside the application, set the following
 * configuration variable to the http(s) address to your domain. This
 * will override the automatic detection of full base URL and can be
 * useful when generating links from the CLI (e.g. sending emails)
 */
	//Configure::write('App.fullBaseUrl', 'http://example.com');

/**
 * Web path to the public images directory under webroot.
 * If not set defaults to 'img/'
 */
	//Configure::write('App.imageBaseUrl', 'img/');

/**
 * Web path to the CSS files directory under webroot.
 * If not set defaults to 'css/'
 */
	//Configure::write('App.cssBaseUrl', 'css/');

/**
 * Web path to the js files directory under webroot.
 * If not set defaults to 'js/'
 */
	//Configure::write('App.jsBaseUrl', 'js/');

/**
 * Uncomment the define below to use CakePHP prefix routes.
 *
 * The value of the define determines the names of the routes
 * and their associated controller actions:
 *
 * Set to an array of prefixes you want to use in your application. Use for
 * admin or other prefixed routes.
 *
 * 	Routing.prefixes = array('admin', 'manager');
 *
 * Enables:
 *	`admin_index()` and `/admin/controller/index`
 *	`manager_index()` and `/manager/controller/index`
 *
 */
//	Configure::write('Routing.prefixes', array('admin'));

/**
 * Turn off all caching application-wide.
 *
 */
	//Configure::write('Cache.disable', true);

/**
 * Enable cache checking.
 *
 * If set to true, for view caching you must still use the controller
 * public $cacheAction inside your controllers to define caching settings.
 * You can either set it controller-wide by setting public $cacheAction = true,
 * or in each action using $this->cacheAction = true.
 *
 */
	//Configure::write('Cache.check', true);

/**
 * Enable cache view prefixes.
 *
 * If set it will be prepended to the cache name for view file caching. This is
 * helpful if you deploy the same application via multiple subdomains and languages,
 * for instance. Each version can then have its own view cache namespace.
 * Note: The final cache file name will then be `prefix_cachefilename`.
 */
	//Configure::write('Cache.viewPrefix', 'prefix');


	Configure::write('Security.key', 'qSI242342432qs*&sXOw!adre@34SasdadAWQEAv!@*(XSL#$%)asGb$@11~_+!@#HKis~#^');

/*Une chaîne aléatoire utilisée dans les méthodes de hachage de sécurité
	(A ne pas modifier sesson vos utilisateurs ne sera plus en mesure de se connecté). */
	Configure::write('Security.salt', '24k354skjhfzZ35457s');


//Une chaîne numérique aléatoire (chiffres uniquement) utilisé pour crypter / décrypter les chaînes.
	Configure::write('Security.cipherSeed', '913557425734');

/**
 * Apply timestamps with the last modified time to static assets (js, css, images).
 * Will append a query string parameter containing the time the file was modified. This is
 * useful for invalidating browser caches.
 *
 * Set to `true` to apply timestamps when debug > 0. Set to 'force' to always enable
 * timestamping regardless of debug value.
 */
	Configure::write('Asset.timestamp', 'force');

/**
 * Compress CSS output by removing comments, whitespace, repeating tags, etc.
 * This requires a/var/cache directory to be writable by the web server for caching.
 * and /vendors/csspp/csspp.php
 *
 * To use, prefix the CSS link URL with '/ccss/' instead of '/css/' or use HtmlHelper::css().
 */
	//Configure::write('Asset.filter.css', 'mini.php');

/**
 * Plug in your own custom JavaScript compressor by dropping a script in your webroot to handle the
 * output, and setting the config below to the name of the script.
 *
 * To use, prefix your JavaScript link URLs with '/cjs/' instead of '/js/' or use JsHelper::link().
 */
	//Configure::write('Asset.filter.js', 'custom_javascript_output_filter.php');

/**
 * The class name and database used in CakePHP's
 * access control lists.
 */
	Configure::write('Acl.classname', 'DbAcl');
	Configure::write('Acl.database', 'default');

/**
 * Uncomment this line and correct your server timezone to fix
 * any date & time related errors.
 */
	//date_default_timezone_set('UTC');

/**
 * `Config.timezone` is available in which you can set users' timezone string.
 * If a method of CakeTime class is called with $timezone parameter as null and `Config.timezone` is set,
 * then the value of `Config.timezone` will be used. This feature allows you to set users' timezone just
 * once instead of passing it each time in function calls.
 */
	//Configure::write('Config.timezone', 'Europe/Paris');

/**
 * Cache Engine Configuration
 * Default settings provided below
 *
 * File storage engine.
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'File', //[required]
 *		'duration' => 3600, //[optional]
 *		'probability' => 100, //[optional]
 * 		'path' => CACHE, //[optional] use system tmp directory - remember to use absolute path
 * 		'prefix' => 'cake_', //[optional]  prefix every cache file with this string
 * 		'lock' => false, //[optional]  use file locking
 * 		'serialize' => true, //[optional]
 * 		'mask' => 0664, //[optional]
 *	));
 *
 * APC (http://pecl.php.net/package/APC)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Apc', //[required]
 *		'duration' => 3600, //[optional]
 *		'probability' => 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *	));
 *
 * Xcache (http://xcache.lighttpd.net/)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Xcache', //[required]
 *		'duration' => 3600, //[optional]
 *		'probability' => 100, //[optional]
 *		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional] prefix every cache file with this string
 *		'user' => 'user', //user from xcache.admin.user settings
 *		'password' => 'password', //plaintext password (xcache.admin.pass)
 *	));
 *
 * Memcached (http://www.danga.com/memcached/)
 *
 * Uses the memcached extension. See http://php.net/memcached
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Memcached', //[required]
 *		'duration' => 3600, //[optional]
 *		'probability' => 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * 		'servers' => array(
 * 			'127.0.0.1:11211' // localhost, default port 11211
 * 		), //[optional]
 * 		'persistent' => 'my_connection', // [optional] The name of the persistent connection.
 * 		'compress' => false, // [optional] compress data in Memcached (slower, but uses less memory)
 *	));
 *
 *  Wincache (http://php.net/wincache)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Wincache', //[required]
 *		'duration' => 3600, //[optional]
 *		'probability' => 100, //[optional]
 *		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *	));
 */

/**
 * Configure the cache handlers that CakePHP will use for internal
 * metadata like class maps, and model schema.
 *
 * By default File is used, but for improved performance you should use APC.
 *
 * Note: 'default' and other application caches should be configured in app/Config/bootstrap.php.
 *       Please check the comments in bootstrap.php for more info on the cache engines available
 *       and their settings.
 */
