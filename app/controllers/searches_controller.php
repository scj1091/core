<?php/** * Search controller class. * * @copyright     Copyright 2010, *ROCK*HARBOR * @link          http://rockharbor.org *ROCK*HARBOR * @package       core * @subpackage    core.app.controllers *//** * Searches Controller * * @package       core * @subpackage    core.app.controllers */class SearchesController extends AppController {/** * List of models this controller uses * * @var array */	var $uses = array('User','Ministry','Involvement');/** * Extra helpers for this controller * * @var array */	var $helpers = array('Formatting', 'Text', 'MultiSelect.MultiSelect', 'SelectOptions', 'Media.Media');/** * Extra components for this controller * * @var array */	var $components = array('FilterPagination', 'MultiSelect.MultiSelect');/** * Model::beforeFilter() callback * * Used to override Acl permissions for this controller. * * @access private */	function beforeFilter() {		parent::beforeFilter();	}/** * Performs a simple search on Users, Ministries and Involvements * * ### Passed args: * - string $model The model to search. If none, searches all */	function index() {		$ministries = array();		$users = array();		$involvements = array();		$query = '';		if (isset($this->passedArgs['model'])) {			$restrictModel = $this->passedArgs['model'];		} else {			$restrictModel = false;		}		if (!empty($this->data)) {			$query = explode(' ', $this->data['Search']['query']);			foreach ($query as &$word) {				$word .= '*';			}			$query = implode(' ', $query);			$foreign_key = $this->activeUser['Group']['id'];			$model = 'Group';			// check access to results based on access to actions			$path = $this->Auth->actionPath.$this->name.'/user';			if ((!$restrictModel || $restrictModel == 'User') && $this->Acl->check(compact('model', 'foreign_key'), $path)) {				$users = $this->User->find('all', array(					'conditions' => array(						'or' => array(							$this->User->parseCriteria(array('simple' => $query)),							$this->User->Profile->parseCriteria(array('simple' => $query)),						)					),					'contain' => array(						'Profile'					)				));			}			$path = $this->Auth->actionPath.$this->name.'/ministry';			if ((!$restrictModel || $restrictModel == 'Ministry') && $this->Acl->check(compact('model', 'foreign_key'), $path)) {				$ministries = $this->Ministry->find('all', array(					'conditions' =>  $this->Ministry->parseCriteria(array('simple' => $query)),				));			}			$path = $this->Auth->actionPath.$this->name.'/involvement';			if ((!$restrictModel || $restrictModel == 'Involvement') && $this->Acl->check(compact('model', 'foreign_key'), $path)) {				$involvements = $this->Involvement->find('all', array(					'conditions' => $this->Involvement->parseCriteria(array('simple' => $query)),					'contain' => array(						'InvolvementType',						'Date'					)				));			}		}		$this->set(compact('ministries', 'involvements', 'users', 'query'));	}	function simple($model = null, $filter = '') {		$results = array();		$searchRan = false;		if (!empty($this->data)) {			// create conditions and contain			$options = array(				'conditions' => $this->postConditions($this->data, 'LIKE'),				'link' => $this->{$model}->postContains($this->data)			);			if (!empty($filter) && isset($this->{$model}->searchFilter[$filter])) {				/**				 * Recursively runs an array through String::insert				 *				 * @param array $input The array				 * @param array $args The insert values				 * @return array				 * @see String::insert()				 */				$string_insert_recursive = function ($input, $args) use (&$string_insert_recursive) {					foreach ($input as &$value) {						if (is_array($value)) {							$value = $string_insert_recursive($value, $args);						} elseif ($value !== null) {							$value = String::insert($value, $args, array('after' => ':'));						}					}					return $input;				};				$filters = $string_insert_recursive($this->{$model}->searchFilter[$filter], array_slice(func_get_args(), 2));				$options = Set::merge($options, $filters);			}			$this->paginate = $options;			$searchRan = true;		}		$results = $this->FilterPagination->paginate($model);		// remove pagination info from action list		$actions = array_diff_key($this->params['named'], array('page'=>array(),'sort'=>array(),'direction'=>array()));		$this->set(compact('results', 'searchRan', 'actions', 'model'));	}/** * Performs an advanced search on Involvements */	function involvement() {		$results = array();		// at the very least, we want:		$contain = array('Ministry', 'InvolvementType');		$this->paginate = compact('contain');		if (!empty($this->data)) {			$operator = $this->data['Search']['operator'];			unset($this->data['Search']);			// remove blanks			$this->data = array_map('Set::filter', $this->data);			$contain = array_merge($contain, $this->Involvement->postContains($this->data));			$conditions = $this->postConditions($this->data, 'LIKE', $operator);			$this->data['Search']['operator'] = $operator;			$this->paginate = compact('conditions', 'contain', 'limit');		}		$results = $this->FilterPagination->paginate('Involvement');		$involvementTypes = $this->Involvement->InvolvementType->find('list');		$this->set(compact('results', 'involvementTypes'));		// pagination request		if (!empty($this->data) || isset($this->params['named']['page'])) {			// just render the results			$this->autoRender = false;			$this->viewPath = 'elements';			$this->render('search'.DS.'involvement_results');		}	}/** * Performs an advanced search on Ministries */	function ministry() {		$results = array();		// at the very least, we want:		$contain = array('Campus');		$this->paginate = compact('contain');		if (!empty($this->data)) {			$operator = $this->data['Search']['operator'];			unset($this->data['Search']);			// remove blanks			$this->data = array_map('Set::filter', $this->data);			$contain = array_merge($contain, $this->Ministry->postContains($this->data));			$conditions = $this->postConditions($this->data, 'LIKE', $operator);			$this->data['Search']['operator'] = $operator;			$this->paginate = compact('conditions', 'contain', 'limit');		}		$results = $this->FilterPagination->paginate('Ministry');		$campuses = $this->Ministry->Campus->find('list');		$this->set(compact('results', 'campuses'));		// pagination request		if (!empty($this->data) || isset($this->params['named']['page'])) {			// just render the results			$this->autoRender = false;			$this->viewPath = 'elements';			$this->render('search'.DS.'ministry_results');		}	}/** * Performs an advanced search on Users */	function user() {		$results = array();		// at the very least, we want:		$contain = array(			'Group',			'Address',			'Profile',			'HouseholdMember' => array(				'Household' => array(					'HouseholdContact'				)			),			'Image'		);		if (!empty($this->data)) {			$options = $this->User->prepareSearch($this, $this->data);			// merge contains with defaults and just get ids (since this is just the filter stage)			foreach ($options['link'] as &$linkedModel) {				$linkedModel['fields'] = array('id');			}			$this->paginate = $options;			// first, search based on the linked parameters (which will filter)			$filteredUsers = $this->paginate();			// reset pagination			$this->paginate = array('contain' => $contain, 'conditions' => array('User.id' => Set::extract('/User/id', $filteredUsers)));			$this->MultiSelect->saveSearch($this->paginate);		}		$results = $this->FilterPagination->paginate();		$publications = $this->User->Publication->find('list');		$campuses = $this->User->Profile->Campus->find('list');		$regions = $this->User->Address->Zipcode->Region->find('list');		$classifications = $this->User->Profile->Classification->find('list');		$this->set('elementarySchools', $this->User->Profile->ElementarySchool->find('list'));		$this->set('middleSchools', $this->User->Profile->MiddleSchool->find('list'));		$this->set('highSchools', $this->User->Profile->HighSchool->find('list'));		$this->set('colleges', $this->User->Profile->College->find('list'));		$this->set(compact('results', 'publications', 'regions', 'classifications', 'campuses'));		// pagination request		if (!empty($this->data) || isset($this->params['named']['page'])) {			// just render the results			$this->autoRender = false;			$this->viewPath = 'elements';			$this->render('search'.DS.'user_results');		}	}}?>