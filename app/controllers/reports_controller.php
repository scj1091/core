<?php
/**
 * Report controller class.
 *
 * @copyright     Copyright 2010, *ROCK*HARBOR
 * @link          http://rockharbor.org *ROCK*HARBOR
 * @package       core
 * @subpackage    core.app.controllers
 */

/**
 * Reports Controller
 *
 * @package       core
 * @subpackage    core.app.controllers
 */
class ReportsController extends AppController {

/**
 * The name of the controller
 *
 * @var string
 */
	var $name = 'Reports';

/**
 * List of models this controller uses
 *
 * @var string
 */
	var $uses = array('User', 'Roster', 'Ministry', 'Involvement', 'Campus', 'Payment');

/**
 * Extra helpers for this controller
 *
 * @var array
 */
	var $helpers = array(
		'GoogleMap',
		'Media.Media',
		'Report',
		'Charts.Charts' => array('Charts.GoogleStatic')
	);

/**
 * Extra components for this controller
 *
 * @var array
 */
	var $components = array('MultiSelect.MultiSelect');
	
/**
 * Model::beforeFilter() callback
 *
 * Used to override Acl permissions for this controller.
 *
 * @access private
 */ 
	function beforeFilter() {
		parent::beforeFilter();
	}

/**
 * Reports home page
 */
	function index() {
		$campuses = $this->Campus->find('list');
		$ministries = $this->Ministry->generatetreelist();

		$conditions = array();
		$involvedUsers = array();
		if (!empty($this->data)) {
			if (!empty($this->data['Ministry']['campus_id'])) {
				// campus takes precedence
				$involvedUsers = $this->Campus->getInvolved($this->data['Ministry']['campus_id'], true);
				$conditions = array(
					'Ministry.campus_id' => $this->data['Ministry']['campus_id']
				);
				$this->data['Ministry']['id'] = null;
			} else if (!empty($this->data['Ministry']['id'])) {
				$involvedUsers = $this->Ministry->getInvolved($this->data['Ministry']['id'], true);
				$conditions = array(
					'or' => array(
						'Ministry.id' => $this->data['Ministry']['id'],
						'Ministry.parent_id' => $this->data['Ministry']['id']
					)
				);
			} else {
				// blank search
				$this->data = array();
			}
		}
		if (empty($this->data)) {
			foreach ($campuses as $id => $campus) {
				$involvedUsers = array_merge($involvedUsers, $this->Campus->getInvolved($id, true));
			}
		}

		$ministryCounts = array();
		$filteredMinistries = $this->Ministry->find('list', array(
			'conditions' => $conditions
		));
		$ministryCounts['total'] = count($filteredMinistries);
		$ministryCounts['active'] = $this->Ministry->find('count', array(
			'conditions' => array_merge($conditions, array('Ministry.active' => true))
		));
		$ministryCounts['private'] = $this->Ministry->find('count', array(
			'conditions' => array_merge($conditions, array('Ministry.private' => true, 'Ministry.active' => true))
		));

		$userCounts = array();
		$userCounts['total'] = $this->User->find('count');
		$activeUsers = $this->User->find('list', array(
			'conditions' => array(
				'User.active' => true
			)
		));
		$userCounts['active'] = count($activeUsers);
		
		$userCounts['involved'] = count($involvedUsers);
		$userCounts['logged_in'] = $this->User->find('count', array(
			'conditions' => array(
				'User.last_logged_in >' => date('Y-m-d 00:00:00', strtotime('now'))
			),
		));

		$filteredMinistries = array_flip($filteredMinistries);
		$involvementTypes = $this->Involvement->InvolvementType->find('list');
		$involvementCounts = array();
		foreach ($involvementTypes as $id => $type) {
			$involvementCounts[$type]['total'] = $this->Involvement->find('count', array(
				'conditions' => array(
					'Involvement.involvement_type_id' => $id,
					'Involvement.ministry_id' => $filteredMinistries
				)
			));
			$involvementCounts[$type]['active'] = $this->Involvement->find('count', array(
				'conditions' => array(
					'Involvement.active' => true,
					'Involvement.involvement_type_id' => $id,
					'Involvement.ministry_id' => $filteredMinistries
				)
			));
			$involvementCounts[$type]['leaders'] = $this->Involvement->Leader->find('count', array(
				'conditions' => array(
					'Involvement.active' => true,
					'Involvement.involvement_type_id' => $id,
					'Involvement.ministry_id' => $filteredMinistries
				),
				'contain' => array(
					'Involvement'
				)
			));
			$involved = $this->Roster->find('all', array(
				'fields' => array(
					'Roster.id'
				),
				'conditions' => array(
					'Involvement.involvement_type_id' => $id,
					'Involvement.ministry_id' => $filteredMinistries
				),
				'group' => 'Roster.user_id',
				'contain' => array(
					'Involvement'
				)
			));
			$involvementCounts[$type]['involved'] = count($involved);
		}

		$this->set(compact('campuses', 'ministries', 'involvementTypes', 'userCounts', 'ministryCounts', 'involvementCounts'));
	}
	
	
	
/**
 * Exports a saved search (from MultiSelectComponent) as a report
 *
 * If the extension is 'csv', set View::title_for_layout to set the name of the
 * csv. Data should be sent in an `Export` array formatted based on the
 * current model's contain format.
 *
 * @param string $model The model we're searching / exporting data from
 * @param string $uid The MultiSelect cache key to get results from
 * @see MultiSelectComponent::getSearch();
 */ 
	function export($model, $uid) {
		if (!empty($this->data)) {
			$options = array();
			if ($this->data['Export']['type'] == 'csv') {
				$this->set('title_for_layout', strtolower($model).'-search-export');
				$options['attachment'] = $this->viewVars['title_for_layout'].'.csv';
			}
			// set render path (which sets response type)
			$this->RequestHandler->renderAs($this, $this->data['Export']['type'], $options);
			$aliases = $this->data['Export']['header_aliases'];
			unset($this->data['Export']['type']);
			unset($this->data['Export']['header_aliases']);
			
			$search = $this->MultiSelect->getSearch($uid);
			$selected = $this->MultiSelect->getSelected($uid);
			// assume they want all if they didn't select any
			if (!empty($selected)) {
				$search['conditions'][$model.'.id'] = $selected;
			}
			
			$results = $this->{$model}->find('all', $search);
			
			$this->set('models', $this->data['Export']);
			$this->set(compact('results', 'aliases'));
		}
		
		$this->set(compact('uid', 'model'));
	}

/**
 * Shows a map from a list of results. You can also pass a model's id to map a
 * single model
 *
 * ### Passed args:
 * - $model The model name as the key, the id as the value to show a single model
 *
 * @param string $model The name of the model to search
 * @param string $uid The MultiSelect cache key to get results from
 */
	function map($model, $uid = null) {
		$search = $this->MultiSelect->getSearch($uid);
		$selected = $this->MultiSelect->getSelected($uid);
		if (isset($this->passedArgs[$model])) {
			$search = array();
			$selected = $this->passedArgs[$model];
		}
		// assume they want all if they didn't select any
		if (!empty($selected)) {
			$search['conditions'][$model.'.id'] = $selected;
		}
		
		// only need name, picture and address
		$search['contain'] = array();
		$contain = array(
			'Address' => array(
				'conditions' => array(
					'Address.primary' => true,
					'Address.model' => $model
				)
			)
		);
		if ($model !== 'User') {
			$search['contain'] = $contain;
			$results = $this->{$model}->find('all', $search);
		} else {
			$search['contain'] = array_merge($contain, array(
				'Profile' => array(
					'fields' => array('name')
				),
				'Image'
			));
			$results = $this->User->find('all', $search);
		}
		$this->set(compact('results', 'model'));
	}
}
?>