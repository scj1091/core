<?php
/* Leaders Test cases generated on: 2010-07-14 12:07:47 : 1279136267 */
App::import('Lib', 'CoreTestCase');
App::import('Controller', array('InvolvementLeaders'));

Mock::generatePartial('InvolvementLeadersController', 'MockInvolvementLeadersController', array('isAuthorized', 'disableCache', 'render', 'redirect', '_stop', 'header', 'cakeError'));

class InvolvementLeadersControllerTestCase extends CoreTestCase {

	function startTest() {
		$this->loadFixtures('Leader', 'User', 'Ministry', 'Role');
		$this->Leaders =& new MockInvolvementLeadersController;
		$this->Leaders->__construct();
		$this->Leaders->constructClasses();
		$this->testController = $this->Leaders;
	}

	function endTest() {
		unset($this->Leaders);
		ClassRegistry::flush();
	}

	function testDashboard() {
		$vars = $this->testAction('involvement_leaders/dashboard/User:1');
		$results = Set::extract('/Leader/id', $vars['leaders']);
		sort($results);
		$this->assertEqual($results, array(2, 5));

		$vars = $this->testAction('involvement_leaders/dashboard/User:100');
		$results = Set::extract('/Leader/id', $vars['leaders']);
		sort($results);
		$this->assertEqual($results, array());
	}
}
?>