<?php
/* Profile Fixture generated on: 2010-06-28 08:06:25 : 1277740765 */
class ProfileFixture extends CakeTestFixture {
	var $name = 'Profile';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 8, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 8, 'key' => 'index'),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 32),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 32),
		'gender' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 1),
		'birth_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'adult' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'classification_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'marital_status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 1),
		'job_category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'occupation' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 64),
		'accepted_christ' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'accepted_christ_year' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5),
		'baptism_date' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 32),
		'allergies' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 1000),
		'special_needs' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 1000),
		'special_alert' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 1000),
		'cell_phone' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10),
		'home_phone' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10),
		'work_phone' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10),
		'work_phone_ext' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10),
		'primary_email' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 64),
		'alternate_email_1' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 64),
		'alternate_email_2' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 64),
		'cpr_certified_date' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 32),
		'baby_dedication_date' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 32),
		'qualified_leader' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'background_check_complete' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'background_check_by' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 32),
		'background_check_date' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 32),
		'grade' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 2),
		'graduation_year' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'created_by' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'created_by_type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 32),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'campus_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'email_on_notification' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'allow_sponsorage' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'household_contact_signups' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'elementary_school_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'middle_school_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'high_school_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'college_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 8),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'user_key' => array('column' => 'user_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'first_name' => 'Jeremy',
			'last_name' => 'Harris',
			'gender' => 'm',
			'birth_date' => '1984-04-14',
			'adult' => 1,
			'classification_id' => NULL,
			'marital_status' => 's',
			'job_category_id' => NULL,
			'occupation' => '',
			'accepted_christ' => 0,
			'accepted_christ_year' => 1997,
			'baptism_date' => '2000-00-00',
			'allergies' => '',
			'special_needs' => '',
			'special_alert' => '',
			'cell_phone' => '4178729564',
			'home_phone' => '',
			'work_phone' => '',
			'work_phone_ext' => '',
			'primary_email' => 'jharris@rockharbor.org',
			'alternate_email_1' => 'jeremy@paxtechservices.com',
			'alternate_email_2' => '',
			'cpr_certified_date' => '0000-00-00',
			'baby_dedication_date' => '0000-08-00',
			'qualified_leader' => 1,
			'background_check_complete' => 0,
			'background_check_by' => '',
			'background_check_date' => '2010-01-06',
			'grade' => '',
			'graduation_year' => 2002,
			'created_by' => 1,
			'created_by_type' => '',
			'created' => '2010-01-22 07:17:20',
			'modified' => '2010-06-23 08:17:35',
			'campus_id' => 1,
			'email_on_notification' => 1,
			'allow_sponsorage' => 0,
			'household_contact_signups' => 0,
			'elementary_school_id' => NULL,
			'middle_school_id' => NULL,
			'high_school_id' => 1,
			'college_id' => NULL
		),
		array(
			'id' => 2,
			'user_id' => 2,
			'first_name' => 'ricky',
			'last_name' => 'rockharbor',
			'gender' => '',
			'birth_date' => NULL,
			'adult' => NULL,
			'classification_id' => NULL,
			'marital_status' => '',
			'job_category_id' => NULL,
			'occupation' => '',
			'accepted_christ' => 0,
			'accepted_christ_year' => NULL,
			'baptism_date' => '0000-00-00',
			'allergies' => '',
			'special_needs' => '',
			'special_alert' => '',
			'cell_phone' => '',
			'home_phone' => '',
			'work_phone' => '',
			'work_phone_ext' => '',
			'primary_email' => 'ricky@rockharbor.org',
			'alternate_email_1' => '',
			'alternate_email_2' => '',
			'cpr_certified_date' => NULL,
			'baby_dedication_date' => '0000-00-00',
			'qualified_leader' => 1,
			'background_check_complete' => NULL,
			'background_check_by' => NULL,
			'background_check_date' => NULL,
			'grade' => '',
			'graduation_year' => NULL,
			'created_by' => 44,
			'created_by_type' => '3',
			'created' => '2010-04-07 13:55:09',
			'modified' => '2010-04-07 13:55:09',
			'campus_id' => NULL,
			'email_on_notification' => 0,
			'allow_sponsorage' => 0,
			'household_contact_signups' => 0,
			'elementary_school_id' => 0,
			'middle_school_id' => 0,
			'high_school_id' => 0,
			'college_id' => 0
		),
		array(
			'id' => 3,
			'user_id' => 3,
			'first_name' => 'ricky jr.',
			'last_name' => 'rockharbor',
			'gender' => 'm',
			'birth_date' => NULL,
			'adult' => NULL,
			'classification_id' => NULL,
			'marital_status' => '',
			'job_category_id' => NULL,
			'occupation' => '',
			'accepted_christ' => 0,
			'accepted_christ_year' => NULL,
			'baptism_date' => '0000-00-00',
			'allergies' => '',
			'special_needs' => '',
			'special_alert' => '',
			'cell_phone' => '',
			'home_phone' => '',
			'work_phone' => '',
			'work_phone_ext' => '',
			'primary_email' => 'rickyjr@rockharbor.org',
			'alternate_email_1' => '',
			'alternate_email_2' => '',
			'cpr_certified_date' => NULL,
			'baby_dedication_date' => '0000-00-00',
			'qualified_leader' => NULL,
			'background_check_complete' => NULL,
			'background_check_by' => NULL,
			'background_check_date' => NULL,
			'grade' => '',
			'graduation_year' => NULL,
			'created_by' => 44,
			'created_by_type' => '3',
			'created' => '2010-04-07 13:55:09',
			'modified' => '2010-04-07 13:55:09',
			'campus_id' => NULL,
			'email_on_notification' => 0,
			'allow_sponsorage' => 0,
			'household_contact_signups' => 0,
			'elementary_school_id' => 0,
			'middle_school_id' => 0,
			'high_school_id' => 0,
			'college_id' => 0
		),
		array(
			'id' => 4,
			'user_id' => 4,
			'first_name' => 'joe',
			'last_name' => 'schmoe',
			'gender' => 'm',
			'birth_date' => NULL,
			'adult' => NULL,
			'classification_id' => NULL,
			'marital_status' => '',
			'job_category_id' => NULL,
			'occupation' => '',
			'accepted_christ' => 0,
			'accepted_christ_year' => NULL,
			'baptism_date' => '0000-00-00',
			'allergies' => '',
			'special_needs' => '',
			'special_alert' => '',
			'cell_phone' => '',
			'home_phone' => '',
			'work_phone' => '',
			'work_phone_ext' => '',
			'primary_email' => 'joe@rockharbor.org',
			'alternate_email_1' => '',
			'alternate_email_2' => '',
			'cpr_certified_date' => NULL,
			'baby_dedication_date' => '0000-00-00',
			'qualified_leader' => NULL,
			'background_check_complete' => NULL,
			'background_check_by' => NULL,
			'background_check_date' => NULL,
			'grade' => '',
			'graduation_year' => NULL,
			'created_by' => 44,
			'created_by_type' => '3',
			'created' => '2010-04-07 13:55:09',
			'modified' => '2010-04-07 13:55:09',
			'campus_id' => NULL,
			'email_on_notification' => 0,
			'allow_sponsorage' => 0,
			'household_contact_signups' => 0,
			'elementary_school_id' => 0,
			'middle_school_id' => 0,
			'high_school_id' => 0,
			'college_id' => 0
		),
		array(
			'id' => 5,
			'user_id' => 5,
			'first_name' => 'bob',
			'last_name' => 'the builder',
			'gender' => 'm',
			'birth_date' => NULL,
			'adult' => NULL,
			'classification_id' => NULL,
			'marital_status' => '',
			'job_category_id' => NULL,
			'occupation' => '',
			'accepted_christ' => 0,
			'accepted_christ_year' => NULL,
			'baptism_date' => '0000-00-00',
			'allergies' => '',
			'special_needs' => '',
			'special_alert' => '',
			'cell_phone' => '',
			'home_phone' => '',
			'work_phone' => '',
			'work_phone_ext' => '',
			'primary_email' => 'bob@builder.com',
			'alternate_email_1' => '',
			'alternate_email_2' => '',
			'cpr_certified_date' => NULL,
			'baby_dedication_date' => '0000-00-00',
			'qualified_leader' => NULL,
			'background_check_complete' => NULL,
			'background_check_by' => NULL,
			'background_check_date' => NULL,
			'grade' => '',
			'graduation_year' => NULL,
			'created_by' => 44,
			'created_by_type' => '3',
			'created' => '2010-04-07 13:55:09',
			'modified' => '2010-04-07 13:55:09',
			'campus_id' => NULL,
			'email_on_notification' => 0,
			'allow_sponsorage' => 0,
			'household_contact_signups' => 0,
			'elementary_school_id' => 0,
			'middle_school_id' => 0,
			'high_school_id' => 0,
			'college_id' => 0
		)
	);
}
?>