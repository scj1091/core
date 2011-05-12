<?php
/* Rosters Test cases generated on: 2010-08-05 12:08:42 : 1281037602 */
App::import('Lib', 'CoreTestCase');
App::import('Component', array('QueueEmail.QueueEmail', 'Notifier'));
App::import('Controller', 'Rosters');
App::import('Model', 'CreditCard');

Mock::generatePartial('QueueEmailComponent', 'MockQueueEmailComponent', array('_smtp', '_mail'));
Mock::generatePartial('NotifierComponent', 'MockNotifierComponent', array('_render'));
Mock::generatePartial('RostersController', 'MockRostersController', array('isAuthorized', 'render', 'redirect', '_stop', 'header'));
Mock::generatePartial('CreditCard', 'MockCreditCard', array('save', 'saveAll'));

class RostersControllerTestCase extends CoreTestCase {

	function startTest() {
		$this->loadFixtures('Roster', 'User', 'Involvement', 'Group', 'Date', 
			'Payment', 'Notification', 'PaymentOption', 'PaymentType',
			'InvolvementType', 'Role', 'RolesRoster', 'Leader', 'Ministry');
		$this->Rosters =& new MockRostersController();
		$this->Rosters->__construct();
		$this->Rosters->constructClasses();
		$this->Rosters->Notifier = new MockNotifierComponent();
		$this->Rosters->Notifier->initialize($this->Rosters);
		$this->Rosters->Notifier->setReturnValue('_render', 'Notification body text');
		$this->Rosters->Notifier->QueueEmail = new MockQueueEmailComponent();
		$this->Rosters->Notifier->QueueEmail->initialize($this->Rosters);
		$this->Rosters->Notifier->QueueEmail->setReturnValue('_smtp', true);
		$this->Rosters->Notifier->QueueEmail->setReturnValue('_mail', true);
		$CreditCard =& new MockCreditCard();
		$CreditCard->something = 'nothing';
		$CreditCard->setReturnValue('save', true);
		$CreditCard->setReturnValue('saveAll', true);
		ClassRegistry::removeObject('CreditCard');
		ClassRegistry::addObject('CreditCard', $CreditCard);
		ClassRegistry::init('CreditCard');
		$this->loadSettings();
		$this->Rosters->setReturnValue('isAuthorized', true);
		$this->testController = $this->Rosters;
	}

	function endTest() {
		$this->unloadSettings();
		$this->Rosters->Session->destroy();
		unset($this->Rosters);		
		ClassRegistry::flush();
	}

	function testRoles() {
		$vars = $this->testAction('/rosters/roles/5/Involvement:3');
		$results = array_keys($vars['roles']);
		sort($results);
		$expected = array(1, 2);
		$this->assertEqual($results, $expected);

		$results = Set::extract('/Role/id', $this->testController->data);
		sort($results);
		$expected = array(1, 2);
		$this->assertEqual($results, $expected);

		$data = array(
			'Roster' => array(
				'id' => 5,
				'roster_status' => 1
			),
			'Role' => array('Role' => array(2))
		);
		$vars = $this->testAction('/rosters/roles/5/Involvement:3', array(
			'data' => $data
		));
		$this->Rosters->Roster->contain(array(
			'Role'
		));
		$data = $this->Rosters->Roster->read(null, 5);
		$results = Set::extract('/Role/id', $data);
		sort($results);
		$expected = array(2);
		$this->assertEqual($results, $expected);
	}

	function testFilterIndex() {
		$data = array(
			'Filter' => array(
				'pending' => 0,
				'Role' => array(
					2
				)
			)
		);
		$vars = $this->testAction('/rosters/index/Involvement:3');
		$results = Set::extract('/Roster/id', $vars['rosters']);
		sort($results);
		$expected = array(4, 5);
		$this->assertEqual($results, $expected);

		$vars = $this->testAction('/rosters/index/Involvement:3', array(
			'data' => $data
		));
		$results = Set::extract('/Roster/id', $vars['rosters']);
		$expected = array(5);
		$this->assertEqual($results, $expected);
	}

	function testIndex() {
		$vars = $this->testAction('/rosters/index/Involvement:1');

		$results = Set::extract('/Roster/id', $vars['rosters']);
		$expected = array(1, 2);
		$this->assertEqual($results, $expected);

		$vars = $this->testAction('/rosters/index/User:1/Involvement:2');
		$results = Set::extract('/Involvement/name', $vars['involvement']);
		$expected = array(
			'Third Wednesday',
		);
		$this->assertEqual($results, $expected);

		$results = count($vars['rosters']);
		$this->assertEqual($results, 1);

		$vars = $this->testAction('/rosters/index/User:1/Involvement:2');
		$results = Set::extract('/Involvement/name', $vars['involvement']);
		$expected = array(
			'Third Wednesday'
		);
		$this->assertEqual($results, $expected);

		$results = count($vars['rosters']);
		$this->assertEqual($results, 1);

		$vars = $this->testAction('/rosters/index/User:2/Involvement:1');
		$results = Set::extract('/Involvement/name', $vars['involvement']);
		$expected = array(
			'CORE 2.0 testing',
		);
		$this->assertEqual($results, $expected);
		$this->assertEqual($vars['counts']['leaders'], 1);
		$this->assertEqual($vars['roles'], array(
			1 => 'Snack Bringer',
			2 => 'Snack Eater'
		));
	}

	function testInvolvement() {
		$vars = $this->testAction('/rosters/involvement/User:1');
		$results = Set::extract('/Involvement/name', $vars['rosters']);
		$expected = array();
		$this->assertEqual($results, $expected);

		$vars = $this->testAction('/rosters/involvement/User:1', array(
			'data' => array(
				'Roster' => array(
					'passed' => true,
					'leading' => true,
					'inactive' => true,
					'private' => false
				)
			)
		));
		$results = Set::extract('/Involvement/name', $vars['rosters']);
		$expected = array(
			'CORE 2.0 testing',
			'Third Wednesday',			
		);
		$this->assertEqual($results, $expected);

		$vars = $this->testAction('/rosters/involvement/User:1', array(
			'data' => array(
				'Roster' => array(
					'passed' => true,
					'leading' => false,
					'inactive' => true,
					'private' => true
				)
			)
		));
		$results = Set::extract('/Involvement/name', $vars['rosters']);
		$expected = array(
			'Third Wednesday',
			'Team CORE'
		);
		$this->assertEqual($results, $expected);
	}

	function testAdd() {
		$notificationsBefore = $this->Rosters->Roster->User->Notification->find('count');

		$data = array(
			'Default' => array(
				'payment_option_id' => 1,
				'payment_type_id' => 1,
				'pay_later' => false,
				'pay_deposit_amount' => false,
			),
			'Adult' => array(
				array(
					'Roster' => array(
						'user_id' => 1
					)
				)
			),
			'CreditCard' => array(
				'first_name' => 'Joe',
				'last_name' => 'Schmoe',
				'credit_card_number' => '1234567891001234',
				'cvv' => '123',
				'email' => 'joe@test.com'
			)
		);
		$vars = $this->testAction('/rosters/add/User:1/Involvement:1', array(
			'data' => $data
		));
		$result = $this->Rosters->Roster->validationErrors;
		$this->assertEqual($result, array());

		$payment = $this->Rosters->Roster->Payment->read();
		$result = $payment['Payment']['user_id'];
		$this->assertEqual($result, 1);
		$result = $payment['Payment']['roster_id'];
		$this->assertEqual($result, $this->Rosters->Roster->id);

		$notificationsNow = $this->Rosters->Roster->User->Notification->find('count');
		$this->assertEqual($notificationsNow-$notificationsBefore, 2);

		$roster = $this->Rosters->Roster->read();
		$result = $roster['Roster']['involvement_id'];
		$this->assertEqual($result, 1);
		$result = $roster['Roster']['user_id'];
		$this->assertEqual($result, 1);		
	}
	
	function testAddMultiple() {
		$notificationsBefore = $this->Rosters->Roster->User->Notification->find('count');
		$rostersBefore = $this->Rosters->Roster->find('count');
		$paymentsBefore = $this->Rosters->Roster->Payment->find('count');

		$data = array(
			'Default' => array(
				'payment_option_id' => 1,
				'payment_type_id' => 1,
				'pay_later' => false,
				'pay_deposit_amount' => false,
			),
			'Adult' => array(
				array(
					'Roster' => array(
						'user_id' => 1
					)
				),
				array(
					'Roster' => array(
						'user_id' => 2
					)
				)
			),
			'CreditCard' => array(
				'first_name' => 'Joe',
				'last_name' => 'Schmoe',
				'credit_card_number' => '1234567891001234',
				'cvv' => '123',
				'email' => 'joe@test.com'
			)
		);
		$vars = $this->testAction('/rosters/add/User:1/Involvement:1', array(
			'data' => $data
		));
		$result = $this->Rosters->Roster->validationErrors;
		$this->assertEqual($result, array());

		$paymentsNow = $this->Rosters->Roster->Payment->find('count');
		$this->assertEqual($paymentsNow-$paymentsBefore, 2);

		$notificationsNow = $this->Rosters->Roster->User->Notification->find('count');
		$this->assertEqual($notificationsNow-$notificationsBefore, 3);

		$rostersNow = $this->Rosters->Roster->find('count');
		$this->assertEqual($rostersNow-$rostersBefore, 2);
	}

	function testEdit() {
		$this->Rosters->Roster->contain(array('Role'));
		$data = $this->Rosters->Roster->read(null, 5);
		$data['Role']['Role'] = array(3);
		
		$vars = $this->testAction('/rosters/edit/5', array(
			'data' => $data
		));
		$result = $this->Rosters->Session->read('Message.flash.element');
		$this->assertEqual($result, 'flash'.DS.'success');

		$this->Rosters->Roster->contain(array('Role'));
		$roster = $this->Rosters->Roster->read();
		$roles = Set::extract('/Role/id', $roster);
		$this->assertEqual($roles, array(3));
		
		$data['Child'] = array(
			array(
				'user_id' => 2
			)
		);
		$vars = $this->testAction('/rosters/edit/5', array(
			'data' => $data
		));
		$result = $this->Rosters->Session->read('Message.flash.element');
		$this->assertEqual($result, 'flash'.DS.'success');

		$this->assertEqual($this->Rosters->Roster->field('parent_id'), 1);
	}

	function testDelete() {
		$this->loadFixtures('Leader');		

		$this->Rosters->Session->write('MultiSelect.testDelete', array(
			'selected' => array(2, 4)
		));

		$rostersBefore = $this->Rosters->Roster->find('count');
		$notificationsBefore = $this->Rosters->Roster->User->Notification->find('count');

		$vars = $this->testAction('/rosters/delete/testDelete');
		$this->assertFalse($this->Rosters->Roster->read(null, 1));
		$this->assertFalse($this->Rosters->Roster->read(null, 2));
		$this->assertFalse($this->Rosters->Roster->read(null, 4));

		$rostersAfter = $this->Rosters->Roster->find('count');
		$this->assertEqual($rostersAfter-$rostersBefore, -3);

		$notificationsAfter = $this->Rosters->Roster->User->Notification->find('count');
		$this->assertEqual($notificationsAfter-$notificationsBefore, 3);
	}

	function testConfirm() {
		$this->Rosters->Session->write('MultiSelect.testConfirm', array(
			'selected' => array(2, 4)
		));

		$vars = $this->testAction('/rosters/confirm/testConfirm');
		$rosters = $this->Rosters->Roster->find('all', array(
			'conditions' => array(
				'Roster.id' => array(2, 4)
			)
		));
		$results = Set::extract('/Roster/roster_status', $rosters);
		$this->assertEqual($results, array(1,1));
	}

}
?>