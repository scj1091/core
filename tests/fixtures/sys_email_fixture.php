<?php
class SysEmailFixture extends CakeTestFixture {
	public $name = 'SysEmail';

	public $table = 'queues';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'to' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'cc' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'bcc' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'from' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'subject' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'delivery' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 4),
		'smtp_options' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'message' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'header' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'attempts' => array('type' => 'integer', 'null' => true, 'length' => 4, 'default' => 0),
		'status' => array('type' => 'integer', 'null' => true, 'length' => 2, 'default' => 0),
		'to_id' => array('type' => 'integer', 'null' => true, 'length' => 8, 'default' => 0),
		'from_id' => array('type' => 'integer', 'null' => true, 'length' => 8, 'default' => 0),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $records = array(
		array(
			'id' => 1,
			'to' => 'test@test.com',
			'cc' => null,
			'bcc' => null,
			'from' => 'test@test.com',
			'subject' => 'Mail',
			'delivery' => null,
			'smtp_options' => null,
			'message' => null,
			'header' => null,
			'attempts' => 0,
			'status' => 0,
			'to_id' => 1,
			'from_id' => 2,
			'created' => '2010-09-20 00:00:01',
			'modified' => '2010-09-20 00:00:01',
		),
		array(
			'id' => 2,
			'to' => 'test@test.com',
			'cc' => null,
			'bcc' => null,
			'from' => 'test@test.com',
			'subject' => 'Mail',
			'delivery' => 'smtp',
			'smtp_options' => 'a:4:{s:4:"port";i:25;s:4:"host";s:19:"example.smtp.server";s:8:"username";s:8:"username";s:8:"password";s:8:"password";}',
			'message' => null,
			'header' => null,
			'attempts' => 0,
			'status' => 1,
			'to_id' => 3,
			'from_id' => 2,
			'created' => '2010-09-20 00:00:01',
			'modified' => '2010-09-20 00:00:01',
		),
		array(
			'id' => 3,
			'to' => 'test@test.com',
			'cc' => null,
			'bcc' => null,
			'from' => 'test@test.com',
			'subject' => 'Some Mail',
			'delivery' => null,
			'smtp_options' => null,
			'message' => 'a:6:{i:0;s:62:"<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">";i:1;s:6:"<html>";i:2;s:6:"<body>";i:3;s:49:"<p>This is a test <strong>with</strong> html.</p>";i:4;s:7:"</body>";i:5;s:7:"</html>";}',
			'header' => null,
			'attempts' => 0,
			'status' => 0,
			'to_id' => 2,
			'from_id' => 1,
			'created' => '2010-09-20 00:00:01',
			'modified' => '2010-09-20 00:00:01',
		),
		array(
			'id' => 4,
			'to' => 'test@test.com',
			'cc' => null,
			'bcc' => null,
			'from' => 'test@test.com',
			'subject' => 'Test Message',
			'delivery' => null,
			'smtp_options' => null,
			'message' => null,
			'header' => null,
			'attempts' => 1,
			'status' => 0,
			'to_id' => 3,
			'from_id' => 3,
			'created' => '2010-09-20 00:00:01',
			'modified' => '2010-09-20 00:00:01',
		),
		array(
			'id' => 5,
			'to' => 'test@test.com',
			'cc' => null,
			'bcc' => null,
			'from' => 'test@test.com',
			'subject' => 'More Mail',
			'delivery' => null,
			'smtp_options' => null,
			'message' => null,
			'header' => null,
			'attempts' => 0,
			'status' => 0,
			'to_id' => 2,
			'from_id' => 2,
			'created' => '2010-09-20 00:00:01',
			'modified' => '2010-09-20 00:00:01',
		),
		array(
			'id' => 6,
			'to' => 'test@test.com',
			'cc' => null,
			'bcc' => null,
			'from' => 'test@test.com',
			'subject' => 'System email',
			'delivery' => null,
			'smtp_options' => null,
			'message' => null,
			'header' => null,
			'attempts' => 0,
			'status' => 0,
			'to_id' => 2,
			'from_id' => 0,
			'created' => '2010-09-20 00:00:01',
			'modified' => '2010-09-20 00:00:01',
		),
	);
}
