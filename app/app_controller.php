<?php
/**
 * App controller class.
 *
 * @copyright     Copyright 2010, *ROCK*HARBOR
 * @link          http://rockharbor.org *ROCK*HARBOR
 * @package       core
 * @subpackage    core.app
 */

/**
 * App Controller
 *
 * All controllers within the CORE app should extend this class.
 *
 * @package       core
 * @subpackage    core.app
 */
class AppController extends Controller {

/**
 * CORE's version
 *
 * @var string
 * @access public
 */		
	var $_version = '2.0.0-alpha';

/**
 * Stored global CORE settings
 *
 * @var array
 * @access public
 */		
	var $CORE = null;
	
	var $components = array(
		'Session',
		'Email',
		'DebugKit.Toolbar' => array(
			'panels' => array(
				'CoreDebugPanels.errors',
				'CoreDebugPanels.visitHistory',
				'CoreDebugPanels.auth',
				'log' => false,
				'history' => false
			)
		),
		'RequestHandler',
		'Acl',
		'Auth' => array(
			'authorize' => 'controller',
			'actionPath' => 'controllers/',
			'allowedActions' => array(
				'logout'
			),
			'authError' => 'Please login to continue.',
			'autoRedirect' => false,
			'loginAction' => array(
				'controller' => 'users', 
				'action' => 'login',
				'plugin' => null
			),
			'logoutRedirect' => array(
				'controller' => 'users', 
				'action' => 'login'
			),
			'loginRedirect' => array(
				'controller' => 'pages', 
				'action' =>'display', 
				'home'
			),
			'userScope' => array('User.active' => true)
		),
		'Referee.Whistle',
		'Notifier' => array(
			'saveData' => array(
				'type' => 'default'
			)
		),
		'QueueEmail'
	);

/**
 * Application-wide helpers
 *
 * @var array
 */
	var $helpers = array(
		'Js' => array('Jquery'),
		'Session',
		'Text'
	);

/**
 * Default callbacks for ajax submit buttons
 *
 * @var array
 * @access public
 */	
	var $defaultSubmitOptions = array(
		'before' => 'CORE.beforeForm(event, XMLHttpRequest);',
		'complete' => 'CORE.completeForm(event, XMLHttpRequest, textStatus)',
		'success' => 'CORE.successForm(event, data, textStatus)',
		'error' => 'CORE.errorForm(event, XMLHttpRequest, textStatus, errorThrown)',
		'evalScripts' =>  true
	);
	
/**
 * Empty page as Cake url
 *
 * @var array
 * @access public
 */	
	var $emptyPage = array(
		'controller' => 'pages',
		'action' => 'display',
		'empty'
	);
	
/**
 * Failed authorization page as Cake url
 *
 * @var array
 * @access public
 */	
	var $authPage = array(
		'controller' => 'pages',
		'action' => 'display',
		'auth_fail'
	);

/**
 * Active user data
 *
 * @var array
 * @access public
 */		
	var $activeUser = null;
	
/**
 * Controller::beforeFilter() callback
 *
 * Handles global configuration, such as app and auth settings. Also
 * does some RequestHandler magic.
 *
 * @see Cake docs
 */
	function beforeFilter() {
		// pull app settings
		$appSettings = Cache::read('core_app_settings');
		if (empty($appSettings)) {
			$appSettings = ClassRegistry::init('AppSetting')->find('all');
			// add tagless versions of the html tagged ones
			$tagless = array();
			foreach ($appSettings as $appSetting) {
				if ($appSetting['AppSetting']['html']) {
					$tagless[] = array(
						'AppSetting' => array(
							'name' => $appSetting['AppSetting']['name'].'_tagless',
							'value' => strip_tags($appSetting['AppSetting']['value'])
						)
					);							
				}
			}
			$appSettings = array_merge($appSettings, $tagless);
			$appSettings = Set::combine($appSettings, '{n}.AppSetting.name', '{n}.AppSetting.value');
			Cache::write('core_app_settings', $appSettings);
		}
		
		Configure::write('CORE.settings', $appSettings);
		$this->CORE = array(
			'version' => $this->_version,
			'settings' => $appSettings
		);
		
		$User = ClassRegistry::init('User');

		$this->Notifier->notification = $User->Notification;

		if ($this->Auth->user()) {
			// keep user available
			$this->activeUser = array_merge($this->Auth->user(), $this->Session->read('User'));
			
			// get latest alert
			$userGroups = Set::extract('/Group/id', $this->activeUser);
			$Alert = ClassRegistry::init('Alert');
						
			// get notifications count
			$newNotifications = $User->Notification->find('count', array(
				'conditions' => array(
					'Notification.user_id' => $this->Auth->user('id'),
					'Notification.read' => false
				),
				'contain' => false
			));
			
			$unread = $Alert->getUnreadAlerts($this->activeUser['User']['id'], $userGroups, false);
			
			$lastUnreadAlert = $Alert->find('first', array(
				'conditions' => array(
					'Alert.id' => $unread
				),
				'order' => 'Alert.created DESC'
			));
			if ($lastUnreadAlert) {
				$this->activeUser = array_merge($lastUnreadAlert, $this->activeUser);
			}
			$this->activeUser['User']['new_notifications'] = $newNotifications;
			$this->activeUser['User']['new_alerts'] = count($unread);
			
			// global allowed actions
			$this->Auth->allow('display');
		} else {
			$this->layout = 'public';
		}
		
		// use custom authentication (password encrypt/decrypt)
		$this->Auth->authenticate = $User;
		
		/* 
		json breaks if there's the time comment that debug adds (<!-- 0.0325 sec -->),
		not to mention the debugging info. you can still see debug info by going to it 
		directly (that is, not using ajax and using the default layout instead)
		*/
		if (($this->RequestHandler->isAjax() && $this->RequestHandler->ext == 'json') || $this->RequestHandler->ext == 'csv') {				
			Configure::write('debug', 0);
		}
		
		// set to log using this user (see LogBehavior)
		if (!$this->params['plugin'] && sizeof($this->uses) && $this->{$this->modelClass}->Behaviors->attached('Logable')) { 
			$this->{$this->modelClass}->setUserData($this->activeUser); 
		}
	}

/**
 * Authorizes a user to access an action based on ACLs
 *
 * @return boolean True if user can continue.
 */ 
	function isAuthorized($action = '') {
		if (!$this->activeUser) {
			return false;
		}

		if (empty($action)) {
			$action = $this->Auth->action();
		}
		
		$this->_setConditionalGroups();
		
		$model = 'Group';
		$foreign_key = $this->activeUser['Group']['id'];

		// main group
		$mainAccess = $this->Acl->check(compact('model', 'foreign_key'), $action);
		
		$condAccess = false;
		// check for conditional group
		if (isset($this->activeUser['ConditionalGroup'])) {
			$foreign_key = $this->activeUser['ConditionalGroup']['id'];
			$condAccess = $this->Acl->check(compact('model', 'foreign_key'), $action);
		}
		
		return $mainAccess || $condAccess;
	}

/**
 * Controller::beforeRender() callback
 *
 * Sets globally needed variables for the views.
 *
 * @see Controller::beforeRender()
 */	
	function beforeRender() {
		$this->set('CORE', $this->CORE);	
		$this->set('activeUser', $this->activeUser);	
		$this->set('defaultSubmitOptions', $this->defaultSubmitOptions);
	}
	
/**
 * Converts POST'ed form data to a model conditions array, suitable for use in a Model::find() call.
 *
 * @param array $data POST'ed data organized by model and field
 * @param mixed $op A string containing an SQL comparison operator, or an array matching operators
 *        to fields
 * @param string $bool SQL boolean operator: AND, OR, XOR, etc.
 * @param boolean $exclusive If true, and $op is an array, fields not included in $op will not be
 *        included in the returned conditions
 * @return array An array of model conditions
 * @access public
 * @link http://book.cakephp.org/view/989/postConditions
 */
	function postConditions($data = array(), $op = null, $bool = 'AND', $exclusive = false) {
		if (!is_array($data) || empty($data)) {
			if (!empty($this->data)) {
				$data = $this->data;
			} else {
				return null;
			}
		}
		$registered = ClassRegistry::keys();
		$bools = array('and', 'or', 'not', 'and not', 'or not', 'xor', '||', '&&');
		$cond = array();

		if ($op === null) {
			$op = '';
		}
		
		$arrayOp = is_array($op);
		foreach ($data as $model => $fields) {
			if (is_array($fields)) {
				foreach ($fields as $field => $value) {
					if (is_array($value) && in_array(strtolower($field), $registered)) {
						$cond += (array)self::postConditions(array($field=>$value), $op, $bool, $exclusive);
					} else {
						// check for boolean keys
						if (in_array(strtolower($model), $bools)) {
							$key = $field;
						} else {
							$key = $model.'.'.$field;
						}
						
						// check for habtm [Publication][Publication][0] = 1
						if ($model == $field) {
							// should get PK
							$key = $model.'.id';
						}
						
						$fieldOp = $op;
						
						if ($arrayOp) {
							if (array_key_exists($key, $op)) {
								$fieldOp = $op[$key];
							} elseif (array_key_exists($field, $op)) {
								$fieldOp = $op[$field];
							} else {
								$fieldOp = false;
							}
						}
						if ($exclusive && $fieldOp === false) {
							continue;
						}
						$fieldOp = strtoupper(trim($fieldOp));
						if (is_array($value) || is_numeric($value)) {
							$fieldOp = '=';					
						}
						if ($fieldOp === 'LIKE') {
							$key = $key.' LIKE';
							$value = '%'.$value.'%';
						} elseif ($fieldOp && $fieldOp != '=') {
							$key = $key.' '.$fieldOp;
						}
						
						$cond[$key] = $value;
					}
				}
			}
		}
		if ($bool != null && strtoupper($bool) != 'AND') {
			$cond = array($bool => $cond);
		}
		
		return $cond;
	}

/**
 * Creates a conditional group, if appropriate
 *
 * Conditional groups are things like Owner, Household Contact, Leader, etc. They are
 * created on a case by case basis depending on if the user qualifies. For example, if the
 * active user owns the record they are trying to edit, they are added to the Owner
 * conditional group. These groups are not persistent.
 *
 * @return void
 * @access protected
 */ 
	function _setConditionalGroups() {
		unset($this->activeUser['ConditionalGroup']);

		$Group = ClassRegistry::init('Group');
		$Group->recursive = -1;

		if (isset($this->passedArgs['User'])) {
			$User = ClassRegistry::init('User');
			
			// check household contact
			if ($User->HouseholdMember->Household->isContactFor($this->activeUser['User']['id'], $this->passedArgs['User'])) {
				$this->activeUser['ConditionalGroup'] = reset($Group->findByName('Household Contact'));
			}
		
			// check owner
			if ($User->ownedBy($this->activeUser['User']['id'], $this->passedArgs['User'])) {
				$this->activeUser['ConditionalGroup'] = reset($Group->findByName('Owner'));
			}
		}
		
		// check leader
		if (isset($this->passedArgs['Involvement'])) {
			$Involvement = ClassRegistry::init('Involvement');
			if ($Involvement->isLeader($this->activeUser['User']['id'], $this->passedArgs['Involvement'])) {
				$this->activeUser['ConditionalGroup'] = reset($Group->findByName('Involvement Leader'));
			}
		}
		
		// check ministry manager
		if (isset($this->passedArgs['Ministry'])) {
			$Ministry = ClassRegistry::init('Ministry');
			if ($Ministry->isManager($this->activeUser['User']['id'], $this->passedArgs['Ministry'])) {
				$this->activeUser['ConditionalGroup'] = reset($Group->findByName('Ministry Manager'));
			}
		}
		
		// check campus manager
		if (isset($this->passedArgs['Campus'])) {
			$Campus = ClassRegistry::init('Campus');
			if ($Campus->isManager($this->activeUser['User']['id'], $this->passedArgs['Campus'])) {
				$this->activeUser['ConditionalGroup'] = reset($Group->findByName('Campus Manager'));
			}
		}
	}

/**
 * Auto-sets User named parameter for specific actions (passed as argument list)
 *
 * @return void
 * @access protected
 */ 
	function _editSelf() {
		$actions = func_get_args();
		
		if (in_array($this->action, $actions)) {
			if (!isset($this->passedArgs['User'])) {
				$this->passedArgs['User'] = $this->activeUser['User']['id'];
				$this->params['named']['User'] = $this->activeUser['User']['id'];
			}
		}		
	}
}
?>