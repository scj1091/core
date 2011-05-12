<?php
/**
 * Routes file
 *
 * @copyright     Copyright 2010, *ROCK*HARBOR
 * @link          http://rockharbor.org *ROCK*HARBOR
 * @package       core
 * @subpackage    core.config
 */

/**
  * Extensions to redirect views/layouts
  */
	Router::parseExtensions('json', 'csv', 'print');
 
/**
 * Bring in custom routing libraries
 */
	App::import('Lib', array('Slugger.routes/SluggableRoute'));
 
/**
 * Static routes
 */
	Router::connect('/', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));
	
/**
 * Custom routes
 */
	Router::connectNamed(array('User', 'Ministry', 'Involvement', 'Campus', 'model'), array('defaults' => true));
	Router::connect('/:controller/:action/*',
	   array(),
		array(
			'routeClass' => 'SluggableRoute',
			'models' => array('User', 'Ministry', 'Involvement', 'Campus')
		)
	);
	Router::connect('/pages/phrase/*', array('controller' => 'pages', 'action' => 'phrase'));

/*
 * Asset Compress
 */
	Router::connect('/css_cache/*', array('plugin' => 'asset_compress', 'controller' => 'css_files', 'action' => 'get'));
	Router::connect('/js_cache/*', array('plugin' => 'asset_compress', 'controller' => 'js_files', 'action' => 'get'));
 
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
?>