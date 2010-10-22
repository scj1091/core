<?php

class DocumentTask extends MigratorTask {

	var $_documentTypeMap = array(
		'EVENT' => 'Involvement',
		'TEAM' => 'Involvement',
		'GROUP' => 'Involvement',
		'PERSON' => 'User'
	);

	var $_oldTable = 'documents';
	var $_oldPk = 'document_id';
	var $_newModel = 'Attachment';

/**
 * Migrates data using the subtask's definitions
 *
 * @param integer $limit
 */
	function migrate($limit = null) {
		$this->_initModels();
		$this->{$this->_newModel}->model = 'Document';
		/**
		 * Person
		 */
		$this->_oldPkMapping =array(
			'type_id' => array('person' => 'User')
		);
		$oldData = $this->findData($limit, 'PERSON');
		$this->_migrate($oldData);

		/**
		 * Event
		 */
		$this->_oldPkMapping =array(
			'type_id' => array('events' => 'Involvement')
		);
		$oldData = $this->findData($limit, 'EVENT');
		$this->_migrate($oldData);

		/**
		 * Team
		 */
		$this->_oldPkMapping =array(
			'type_id' => array('teams' => 'Involvement')
		);
		$oldData = $this->findData($limit, 'TEAM');
		$this->_migrate($oldData);

		/**
		 * Group
		 */
		$this->_oldPkMapping =array(
			'type_id' => array('groups' => 'Involvement')
		);
		$oldData = $this->findData($limit, 'GROUP');
		$this->_migrate($oldData);

		if (!empty($this->orphans)) {
			$this->out("The following $this->_oldTable records are considered orphaned:");
			$this->out(implode(',', $this->orphans));
			if ($this->in('Continue with migration?', array('y', 'n')) == 'n') {
				$this->_stop();
				break;
			}
		}
	}

	function findData($limit = null, $type = null) {
		$options = array(
			'order' => 'document_id',
			'conditions' => array(
				'not' => array(
					$this->_oldPk => $this->_getPreMigrated()
				),
				'document_type' => $type
			)
		);
		if ($limit) {
			$options['limit'] = $limit;
		}
		return $this->old->find('all', $options);
	}

	function mapData() {
		$friendly = explode('.', $this->_editingRecord['displayname']);
		array_pop($friendly);
		$friendly = implode('.', $friendly);

		$this->_editingRecord = array(
			'Document' => array(
				'model' => $this->_editingRecord['document_type'],
				'foreign_key' => $this->_editingRecord['type_id'],
				'alternative' => low($friendly),
				'group' => 'Document',
				'approved' => true,
				'created' => $this->_editingRecord['created'],
				'file' => ROOT.DS.'attachments'.DS.$this->_editingRecord['filename']
			)
		);
	}

}