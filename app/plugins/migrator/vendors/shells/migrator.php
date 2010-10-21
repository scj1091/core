<?php

Configure::write('Cache.disable', false);

class MigratorShell extends Shell {

	var $tasks = array(
		'Migrator', 'Cleanup',
		'Address', 
		'Attachment',
		'Ministry',
		'Payment',
		'User',
	);

	var $_oldDbConfig = 'old';

	var $linkages = array();

	function migrate() {
		ini_set('memory_limit', '256M');

		$this->_createLinkageTable();
		
		if (!empty($this->args[0]) && isset($this->{$this->args[0]})) {
			$limit = null;
			if (!empty($this->args[1])) {
				$limit = $this->args[1];
			}
			$this->{$this->args[0]}->IdLinkage = ClassRegistry::init('IdLinkage');

			$this->{$this->args[0]}->migrate($limit);
		} else {
			$this->out($this->args[0].' task isn\'t attached.');
		}

		$this->Cleanup->cleanup();

		$this->out('Migration complete!');
	}

	function _createLinkageTable() {
		$ds = ConnectionManager::getDataSource('default');
		$ds->execute('
		CREATE TABLE IF NOT EXISTS `id_linkages`(
			id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,			
			old_pk VARCHAR(100),
			old_table VARCHAR(100),
			new_model VARCHAR(100),
			new_pk VARCHAR(100)	
      ) TYPE=innodb;
		');
	}

}