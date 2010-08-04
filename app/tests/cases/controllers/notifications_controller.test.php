<?php
/* Notifications Test cases generated on: 2010-07-09 10:07:32 : 1278696092 */
App::import('Lib', 'CoreTestCase');
App::import('Component', 'QueueEmail');
App::import('Controller', 'Notifications');

Mock::generate('QueueEmailComponent');
Mock::generatePartial('NotificationsController', 'TestNotificationsController', array('isAuthorized', 'render', 'redirect', '_stop', 'header'));

class NotificationsControllerTestCase extends CoreTestCase {
	var $fixtures = array(
		'app.notification','app.ministries_rev', 'app.involvements_rev',
		'app.user', 'app.group', 'app.profile', 'app.classification', 'app.job_category',
		'app.school', 'app.campus', 'plugin.media.attachment', 'app.ministry', 'app.involvement',
		'app.involvement_type', 'app.address', 'app.zipcode', 'app.region', 'app.date',
		'app.payment_option', 'app.question', 'app.roster', 'app.role', 'app.roster_status',
		'app.answer', 'app.payment', 'app.payment_type', 'app.leader', 'app.comment', 'app.comment_type',
		'app.comments', 'app.notification', 'app.image', 'plugin.media.document', 'app.household_member',
		'app.household', 'app.publication', 'app.publications_user', 'app.log', 'app.app_setting',
		'app.alert', 'app.alerts_user'
	);

	var $autoFixtures = false;

	function startTest() {
		$this->loadFixtures('Notification');
		$this->Notifications =& new TestNotificationsController();
		$this->Notifications->constructClasses();
		$this->Notifications->QueueEmail = new MockQueueEmailComponent();
		$this->Notifications->QueueEmail->setReturnValue('send', true);
		$this->Notifications->setReturnValue('isAuthorized', true);		
		$this->testController = $this->Notifications;
	}

	function endTest() {
		$this->Notifications->Session->destroy();
		unset($this->Notifications);		
		ClassRegistry::flush();
	}

	function testIndex() {
		$vars = $this->testAction('/notifications/index/User:1', array(
			'return' => 'vars'
		));
		$expected = array(
			array(
				'Notification' => array(
					'id' => 1,
					'user_id' => 1,
					'created' => '2010-06-24 14:37:38',
					'modified' => '2010-06-24 14:37:38',
					'read' => 0,
					'type' => 'invitation',
					'body' => 'You have been invited somewhere.'
				)
			),
			array(
				'Notification' => array(
					'id' => 2,
					'user_id' => 1,
					'created' => '2010-06-04 10:24:49',
					'modified' => '2010-06-24 10:21:54',
					'read' => 0,
					'type' => 'default',
					'body' => 'Jeremy Harris is now managing the campus Fischer.'
				)
			)
		);
		$this->assertEqual($vars['notifications'], $expected);
	}

	function testIndexFiltered() {
		$vars = $this->testAction('/notifications/index/invitation/User:1', array(
			'return' => 'vars'
		));
		$expected = array(
			array(
				'Notification' => array(
					'id' => 1,
					'user_id' => 1,
					'created' => '2010-06-24 14:37:38',
					'modified' => '2010-06-24 14:37:38',
					'read' => 0,
					'type' => 'invitation',
					'body' => 'You have been invited somewhere.'
				)
			)
		);
		$this->assertEqual($vars['notifications'], $expected);
	}

	function testRead() {
		$vars = $this->testAction('/notifications/read/3');
		$this->Notifications->Notification->id = 3;
		$this->assertFalse($this->Notifications->Notification->field('read'));

		$this->Notifications->Session->write('Auth.User', array('id' => 2));
		$vars = $this->testAction('/notifications/read/3');
		$this->Notifications->Notification->id = 3;
		$this->assertTrue($this->Notifications->Notification->field('read'));
	}

	function testMultiSelectRead() {
		$this->Notifications->Session->write('MultiSelect.test', array(
			'selected' => array(1,2)
		));
		$vars = $this->testAction('/notifications/read/test');
		$results = $this->Notifications->Notification->find('all', array(
			'conditions' => array(
				'user_id' => 1
			)
		));
		$results = Set::extract('/Notification/read', $results);
		$expected = array(1, 1);
		$this->assertEqual($results, $expected);
	}

	function testDelete() {
		$vars = $this->testAction('/notifications/delete/3');
		$this->assertNotNull($this->Notifications->Notification->read(null, 3));

		$this->Notifications->Session->write('Auth.User', array('id' => 2));
		$vars = $this->testAction('/notifications/delete/3');
		$this->Notifications->Notification->id = 3;
		$this->assertFalse($this->Notifications->Notification->read(null, 3));
	}

	function testMultiSelectDelete() {
		$this->Notifications->Session->write('MultiSelect.test', array(
			'selected' => array(1,2,3)
		));
		$vars = $this->testAction('/notifications/delete/test');
		$results = $this->Notifications->Notification->find('count');
		$this->assertEqual($results, 2);
	}

}
?>