<?php
/* Date Fixture generated on: 2010-06-28 09:06:48 : 1277741268 */
class DateFixture extends CakeTestFixture {
	var $name = 'Date';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'start_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'end_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'start_time' => array('type' => 'time', 'null' => true, 'default' => NULL),
		'end_time' => array('type' => 'time', 'null' => true, 'default' => NULL),
		'all_day' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'permanent' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'recurring' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'recurrance_type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10),
		'frequency' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 2),
		'weekday' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 2),
		'day' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 2),
		'involvement_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'exemption' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'offset' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 2),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'start_date' => '2010-03-16',
			'end_date' => '2010-03-16',
			'start_time' => '00:00:00',
			'end_time' => '11:59:00',
			'all_day' => 1,
			'permanent' => 1,
			'recurring' => 1,
			'recurrance_type' => 'mw',
			'frequency' => 1,
			'weekday' => 3,
			'day' => 1,
			'involvement_id' => 2,
			'created' => '2010-03-16 13:32:33',
			'modified' => '2010-03-16 13:32:48',
			'exemption' => 0,
			'offset' => 3
		),
		array(
			'id' => 2,
			'start_date' => '2010-04-09',
			'end_date' => '2010-08-09',
			'start_time' => '08:00:00',
			'end_time' => '11:00:00',
			'all_day' => 0,
			'permanent' => 0,
			'recurring' => 1,
			'recurrance_type' => 'mw',
			'frequency' => 2,
			'weekday' => 2,
			'day' => 1,
			'involvement_id' => 2,
			'created' => '2010-04-09 08:57:07',
			'modified' => '2010-04-09 09:16:47',
			'exemption' => 0,
			'offset' => 2
		),
		array(
			'id' => 3,
			'start_date' => '2010-04-14',
			'end_date' => '2010-04-14',
			'start_time' => '06:00:00',
			'end_time' => '11:15:00',
			'all_day' => 0,
			'permanent' => 0,
			'recurring' => 0,
			'recurrance_type' => 'h',
			'frequency' => 1,
			'weekday' => 0,
			'day' => 1,
			'involvement_id' => 1,
			'created' => '2010-04-09 09:17:40',
			'modified' => '2010-04-09 09:17:40',
			'exemption' => 0,
			'offset' => 1
		),
		array(
			'id' => 4,
			'start_date' => '2010-04-18',
			'end_date' => '2010-04-18',
			'start_time' => '00:00:00',
			'end_time' => '23:59:00',
			'all_day' => 1,
			'permanent' => 0,
			'recurring' => 0,
			'recurrance_type' => 'h',
			'frequency' => 1,
			'weekday' => 0,
			'day' => 1,
			'involvement_id' => 1,
			'created' => '2010-04-09 09:19:23',
			'modified' => '2010-04-09 09:19:23',
			'exemption' => 0,
			'offset' => 1
		),
		array(
			'id' => 5,
			'start_date' => '2010-04-07',
			'end_date' => '2010-04-10',
			'start_time' => '11:15:00',
			'end_time' => '11:15:00',
			'all_day' => 0,
			'permanent' => 0,
			'recurring' => 0,
			'recurrance_type' => 'h',
			'frequency' => 1,
			'weekday' => 0,
			'day' => 1,
			'involvement_id' => 3,
			'created' => '2010-04-23 11:15:27',
			'modified' => '2010-04-23 11:15:27',
			'exemption' => 0,
			'offset' => 1
		),
		array(
			'id' => 6,
			'start_date' => '2010-06-24',
			'end_date' => '2010-06-30',
			'start_time' => '19:30:00',
			'end_time' => '20:30:00',
			'all_day' => 0,
			'permanent' => 0,
			'recurring' => 1,
			'recurrance_type' => 'd',
			'frequency' => 1,
			'weekday' => 1,
			'day' => 1,
			'involvement_id' => 3,
			'created' => '2010-06-24 19:30:02',
			'modified' => '2010-06-24 19:30:02',
			'exemption' => 0,
			'offset' => 1
		)
	);
}
?>