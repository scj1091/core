<?php
/**
 * Permission helper class.
 *
 * @copyright     Copyright 2010, *ROCK*HARBOR
 * @link          http://rockharbor.org *ROCK*HARBOR
 * @package       core
 * @subpackage    core.app.views.helpers
 */

/**
 * Permission Helper
 *
 * Stores permissions from the controller for access in the views. No,
 * PermissionHelper::check() is not very MVC but it makes the most sense to
 * place it here.
 *
 * @package       core
 * @subpackage    core.app.views.helpers
 */
class PermissionHelper extends AppHelper {

/**
 * Stored app controller for PermissionHelper::check()
 *
 * @var Controller
 */
	var $AppController = null;

/**
 * The user to check in PermissionHelper::check()
 *
 * @var array
 */
	var $activeUser = array();

/**
 * Additional helpers needed by this helper
 *
 * @var array
 */
	var $helpers = array(
		'Html',
		'Js'
	);

/**
 * Grabs permissions set in the controller
 *
 * {{{
 * $this->set('_canSeeThisThing', true);
 * }}}
 *
 * Then you get the permission using
 *
 * {{{
 * $this->Permission->can("seeThisThing");
 * }}}
 *
 * Automatically denies permission for missing permissions
 *
 * @param string $name The name of the missing permission
 * @return false
 */
	function can($name) {
		$prop = '_can'.Inflector::camelize($name);
		if (isset($this->{$prop})) {
			return $this->{$prop};
		}
		CakeLog::write('Auth', 'Missing permission check for "'.$name.'"');
		return false;
	}

/**
 * Takes all vars named _can{DoSomething} set on the view and saves them as a
 * permission and removes them from the view vars
 */
	function beforeRender() {
		$view =& ClassRegistry::getObject('view');
		if ($view === false) {
			return;
		}
		foreach ($view->viewVars as $varName => $value) {
			if (strpos($varName, '_can') !== false) {
				$this->{$varName} = $value;
				unset($view->viewVars[$varName]);
			}
		}
	}

/**
 * Creates a link if the user is authorized to access it. Tries to determine
 * if it should be an HTML or JavaScript link
 *
 * @param string $title The link title
 * @param array $url Only accepts cake-based url and NEEDS controller defined
 * @param array $options Options to pass to link
 * @param string $confirmMessage A javascript confirm message
 * @return string The link
 */
	function link($title, $url = null, $options = array(), $confirmMessage = false) {
		if (is_string($url)) {
			$url = Router::parse($url);
		}
		if (!isset($url['action'])) {
			$url['action'] = 'index';
		}
		if ($this->check($url)) {
			$helper = 'Html';
			$hasJs = array_intersect(array('update', 'success', 'complete', 'beforeSend', 'error'), array_keys($options));
			if (!empty($hasJs)) {
				$helper = 'Js';
			}
			return $this->{$helper}->link($title, $url, $options, $confirmMessage);
		}
		return null;
	}

/**
 * Checks if the logged in user has access to a controller/action path
 *
 * @param array $path The url to check
 * @param array $user The user to check
 * @return boolean
 * @see AppController::isAuthorized()
 */
	function check($path = '') {
		if (empty($path)) {
			return false;
		}
		$params = array();
		if (is_array($path)) {
			$params = array_diff_key($path, array('plugin' => null, 'controller' => null, 'action' => null));
			$path = Router::url($path);
		}
		$view =& ClassRegistry::getObject('view');
		if (!$this->AppController) {
			App::import('Controller', 'App');
			$this->AppController = new AppController();
			$this->AppController->constructClasses();
		}
		$this->AppController->activeUser = $view->viewVars['activeUser'];
		return $this->AppController->isAuthorized($path, $params, $this->activeUser);
	}
}