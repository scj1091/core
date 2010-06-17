<?php
/**
 * Sys email model class.
 *
 * @copyright     Copyright 2010, *ROCK*HARBOR
 * @link          http://rockharbor.org *ROCK*HARBOR
 * @package       core
 * @subpackage    core.app.models
 */

/**
 * SysEmail model
 *
 * @package       core
 * @subpackage    core.app.models
 */
class SysEmail extends AppModel {

/**
 * The name of the model
 *
 * @var string
 */
	var $name = 'SysEmail';

/**
 * The table to use, or false for none
 *
 * @var boolean
 */
	var $useTable = false;

/**
 * Manually defined schema for validation
 *
 * @var array
 */
	var $_schema = array(
		'subject' => array(
			'type' => 'string',
			'length' => 45
		),
		'body' => array(
			'type' => 'text'
		)
	);

/**
 * Validation rules
 *
 * @var array
 */
	var $validate = array(
		'subject' => array(
			'rule' => 'notempty',
			'required' => 'true'
		),
		'body' => array(
			'rule' => 'notempty',
			'required' => 'true'
		)
	);	
	
/**
 * Overwrite Model::exists() due to Cake looking for a table when validating.
 *
 * @return boolean True
 */
	function exists() {
		return true;
	}

	
/**
 * Garbage collects email attachments
 *
 * Deletes all attachments that don't have a cache file associated 
 * to them. Or, if $uid is defined, it will clear out attachments
 * associated with that id
 *
 * @param string $uid A foreign_key to look for
 * @return boolean True on success, false on failure
 */
	function gcAttachments($uid = null) {
		// load documents
		$Document = ClassRegistry::init('Document');
		if (!$uid) {
			// get a list of current cached lists this _could_ be using
			$Folder = new Folder(CACHE.'lists');
			$files = $Folder->find();
			$dontDeleteCacheUids = array();
			foreach ($files as $file) {
				$fileExploded = explode(DS, $file);
				$fullBaseName = array_pop($fileExploded);
				$fullBaseNameExploded = explode('_', $fullBaseName);
				$cacheuid = array_pop($fullBaseNameExploded);
				
				$dontDeleteCacheUids[] = $cacheuid;
			}
			
			// delete all attachments that don't have a cache file associated
			return $Document->deleteAll(array(
				'model' => 'SysEmail',
				'not' => array(
					'foreign_key' => $dontDeleteCacheUids					
				)
			));
		} else {
			return $Document->deleteAll(array(
				'foreign_key' => $uid,
				'model' => 'SysEmail'
			));
		}
	}
}
?>