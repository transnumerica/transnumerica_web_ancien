<?php
	App::uses('OpRoute', 'Routing/Route');

	Router::$_routeClass = 'OpRoute';

	Configure::write('Routing.prefixes', array('admin', 'manager'));

	// Route administrateur
	Router::connect('/admin/commandes/:id', array('plugin' => 'admin', 'controller' => 'commandes', 'action' => 'gerer'), array('id'=>'[a-zA-Z0-9]+', 'pass' => array('id')));
	Router::connect('/admin/article/edit/:id', array('plugin' => 'admin', 'controller' => 'article', 'action' => 'edit'), array('id'=>'[0-9]+', 'pass' => array('id')));
	Router::connect('/admin/recettes/edit/:id', array('plugin' => 'admin', 'controller' => 'recettes', 'action' => 'edit'), array('id'=>'[0-9]+', 'pass' => array('id')));


	//Route Vers Manager
	Router::connect('/download/*', array('plugin' => 'brownie', 'controller' => 'downloads', 'action' => 'get'));
	Router::connect('/img/*', array('plugin' => 'brownie', 'controller' => 'thumbs', 'action' => 'view'));
	Router::connect('/thumbs/*', array('plugin' => 'brownie', 'controller' => 'thumbs', 'action' => 'generate'));
	Router::connect('/manager', array('plugin' => 'brownie', 'controller' => 'brownie', 'action' => 'login'));
	Router::connect('/manager/:controller/:action/*', array('plugin' => 'brownie'));

	// Route Normal
	Router::connect('/', array('controller' => 'home', 'action' => 'index'));
	Router::connect('/test', array('controller' => 'test', 'action' => 'index'));
	


	

	Router::connect('/search/pesa', array('controller' => 'search', 'action' => 'pesa'), array());
	Router::connect('/search/:md5', array('controller' => 'search', 'action' => 'index'), array('pass' => array('md5')));








	$slug = 'profil';
	$controller = 'profil';

	Router::connect('/'.$slug, array('plugin' => false,'controller' => $controller, 'action' => 'index'));
	Router::connect('/'.$slug."/:slug/:id", array('plugin' => false, 'controller' => $controller, 'action' => 'view'), array('pass' => array('slug', 'id')));

	Router::connect('/'.$slug."/:slug/:id/:action", array('plugin' => false, 'controller' => $controller), array('pass' => array('slug', 'id', 'action')));





	$slug = 'messages';
	$controller = 'messages';

	Router::connect('/'.$slug, array('plugin' => false,'controller' => $controller, 'action' => 'index'));
	Router::connect('/'.$slug."/:slug/:id", array('plugin' => false, 'controller' => $controller, 'action' => 'view'), array('pass' => array('slug', 'id')));

	Router::connect('/'.$slug."/:slug/:id/:action", array('plugin' => false, 'controller' => $controller), array('pass' => array('slug', 'id', 'action')));




	Router::connect('/cgv', array('controller' => 'about', 'action' => 'cgv'));




	CakePlugin::routes();

	require CAKE . 'Config' . DS . 'routes.php';
