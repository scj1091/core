<?php
/**
 * Roster model class.
 *
 * @copyright     Copyright 2010, *ROCK*HARBOR
 * @link          http://rockharbor.org *ROCK*HARBOR
 * @package       core
 * @subpackage    core.app.models
 */

/**
 * Roster model
 *
 * @package       core
 * @subpackage    core.app.models
 */
class Roster extends AppModel {

/**
 * The name of the model
 *
 * @var string
 */
	var $name = 'Roster';
	
/**
 * Roster statuses
 * 
 * @var array
 */
	var $statuses = array(
		0 => 'Pending',
		1 => 'Confirmed'
	);

/**
 * Validation rules
 *
 * This validation rule is here to prevent empty roster saves
 *
 * @var array
 */
	var $validate = array(
		'roster_status' => array(
			'rule' => 'notEmpty',
			'required' => true
		)
	);

/**
 * Extra behaviors for this model
 *
 * @var array
 */
	var $actsAs = array(
		'Logable',
		'Containable'
	);

/**
 * Virtual field definitions
 *
 * @var array
 */
	var $virtualFields = array(
		'amount_due' => '@vad:=(SELECT (IF (Roster.parent_id IS NOT NULL, ad.childcare, ad.total)) FROM payment_options as ad WHERE ad.id = Roster.payment_option_id)',
		'amount_paid' => '@vap:=(COALESCE((SELECT SUM(ap.amount) FROM payments as ap WHERE ap.roster_id = Roster.id), 0))',
		'balance' => '@vad-@vap'
	);

/**
 * BelongsTo association link
 *
 * @var array
 */
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'Involvement' => array(
			'className' => 'Involvement',
			'foreignKey' => 'involvement_id'
		),
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id'
		),
		'PaymentOption' => array(
			'className' => 'PaymentOption',
			'foreignKey' => 'payment_option_id'
		),
		'Parent' => array(
			'className' => 'User',
			'foreignKey' => 'parent_id'
		)
	);

/**
 * HasMany association link
 *
 * @var array
 */
	var $hasMany = array(
		'Answer' => array(
			'className' => 'Answer',
			'foreignKey' => 'roster_id',
			'dependent' => true
		),
		'Payment' => array(
			'className' => 'Payment',
			'foreignKey' => 'roster_id',
			'dependent' => false
		)
	);

/**
 * Adds necessary information to a new roster record.
 *
 * ### Options:
 * - `roster` The Roster model data
 * - `defaults` The default data, (see list below)
 * - `involvement` The Involvement model data
 * - `creditCard` The CreditCard model data (if any)
 * - `payer` The User data for the person paying (if any)
 * - `parent` The parent (if childcare)
 *
 * ### Defaults:
 * - `payment_option_id` The PaymentOption id
 * - `payment_type_id` The PaymentType id
 * - `pay_later` Whether they chose to pay now or later
 * - `pay_deposit_amount` If they chose the payment deposit amount instead of total
 *
 * @param array $options List of information used to change the roster record
 * @return array New roster record
 *
 */
	function setDefaultData($options) {
		$_options = array(
			'creditCard' => array(),
			'defaults' => array(),
			'parent' => null
		);
		$options = array_merge($_options, $options);
		$_defaults = array(
			'payment_option_id' => null,
			'payment_type_id' => null,
			'pay_later' => false,
			'pay_deposit_amount' => false,
			'role_id' => null
		);
		$options['defaults'] = array_merge($_defaults, $options['defaults']);
		
		extract($options);

		$paymentOption = $this->PaymentOption->read(null, $defaults['payment_option_id']);
		$paymentType = $this->Payment->PaymentType->read(null, $defaults['payment_type_id']);

		// set defaults
		$roster['Roster']['involvement_id'] = $involvement['Involvement']['id'];
		$roster['Roster']['roster_status'] = 1;
		$roster['Roster']['parent'] = $parent;
		$roster['Roster']['payment_option_id'] = $defaults['payment_option_id'];
		$roster['Roster']['role_id'] = $defaults['role_id'];
		
		// only add a payment if we're taking one
		if ($involvement['Involvement']['take_payment'] && $defaults['payment_option_id'] > 0 && !$defaults['pay_later']) {
			if (is_null($parent)) {
				$amount = $defaults['pay_deposit_amount'] ? $paymentOption['PaymentOption']['deposit'] : $paymentOption['PaymentOption']['total'];
			} else {
				$amount = $paymentOption['PaymentOption']['childcare'];
			}

			// add payment record to be saved (transaction id to be added later)
			$roster['Payment'] = array(
				'0' => array(
					'user_id' => $roster['Roster']['user_id'],
					'amount' => $amount,
					'payment_type_id' => $paymentType['PaymentType']['id'],
					'number' => substr($creditCard['CreditCard']['credit_card_number'], -4),
					'payment_placed_by' => $payer['User']['id'],
					'payment_option_id' => $defaults['payment_option_id'],
					'comment' => $creditCard['CreditCard']['first_name'].' '.$creditCard['CreditCard']['last_name'].'\'s card processed by '.$payer['Profile']['name'].'.'
				)
			);
		}

		return $roster;
	}
}
?>