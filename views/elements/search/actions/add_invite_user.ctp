<?php
$links = array(
	array(
		'title' => 'Add to roster',
		'url' => array(
			'controller' => 'involvements',
			'action' => 'invite',
			1, // status = confirmed
			'Involvement' => $named['Involvement']
		),
		'options' => array(
			'success' => 'CORE.showFlash(data);'
		)
	),
	array(
		'title' => 'Invite to roster',
		'url' => array(
			'controller' => 'involvements',
			'action' => 'invite',
			3, // status = invited
			'Involvement' => $named['Involvement']
		),
		'options' => array(
			'success' => 'CORE.showFlash(data);'
		)
	)
);

echo $this->element('multiselect', compact('links', 'colCount'));
