/*
 * Namespaced object
 */
var CORE_roster = {};

/*
 * Initializes the roster sign up page
 *
 * @TODO selecting the questions tab for the first checked
 * member is not really using classes correctly. Should probably
 * be in a data attribute. As soon as jQuery supports quick easy
 * selecting/filtering by data attributes.
 */
CORE_roster.init = function() {
	CORE.noDuplicateCheckboxes('members');
	$('#members input[type=checkbox]').change();
	$('#DefaultPayDepositAmount').on('change', CORE_roster.updateAmount);
	$('#DefaultPaymentOptionId').change();
	$('#RosterAddressId').change();
	// by default, click the first available tab under answers
	$('#questions_tab a').on('click', function() {
		$('#question_tabs > ul:not(.admin) li.member-checked:first a').click()
	});
	$('input[id^=Child]').on('change', CORE_roster.updateAmount);
	$('#DefaultPayLater').on('change', function() {
		CORE_roster.updateAmount();
	});

	$('#DefaultAddressId').on('change', function() {
		if ($(this).val() == 0) {
			$('#AddressAddressLine1').val('');
			$('#AddressAddressLine2').val('');
			$('#AddressCity').val('');
			$('#AddressState').val('');
			$('#AddressZip').val('');
		} else {
			var selected = CORE_roster.addresses[$(this).val()].Address;
			$('#AddressAddressLine1').val(selected.address_line_1);
			$('#AddressAddressLine2').val(selected.address_line_2);
			$('#AddressCity').val(selected.city);
			$('#AddressState').val(selected.state);
			$('#AddressZip').val(selected.zip);
		}
	});
	$('#members input[id^=Adult]:checkbox').on('change', function() {
		// only show the answer tabs for members that are checked
		if (this.checked) {
			$('a[href=#answers_'+$(this).val()+']').parent().show().addClass("member-checked");
		} else {
			$('a[href=#answers_'+$(this).val()+']').parent().hide().removeClass("member-checked");
		}
		$('#members input[id^=Adult]:checked').length > 0 ? $('#questions_tab').show() : $('#questions_tab').hide();
		CORE_roster.updateAmount();
	});

	$('.payment-option input:radio').on('change', function() {
		$('.payment-option').removeClass('selected');
		$(this).closest('.payment-option').addClass('selected');
		CORE_roster.updateAmount();
	});

	$('#members input[id^=Adult]:checkbox').change();
	$('.payment-option input:radio:checked').change().closest('.payment-option').addClass('selected');
}

/**
 * Updates amount fields based on selected options
 */
CORE_roster.updateAmount = function() {
	if (CORE_roster.payments[$('.payment-options input:radio:checked').val()] == undefined) {
		return;
	}

	var peopleAmount, totalDue, childcareAmount, numberChildcareSignedUp, deposit, childcare, numberSignedUp, payLater;
	var selectedOption = CORE_roster.payments[$('.payment-options input:radio:checked').val()].PaymentOption;

	deposit = Number(selectedOption.deposit) > 0;
	childcare = Number(selectedOption.childcare) > 0;
	payLater = $('#DefaultPayLater').is(':checked');

	deposit > 0 ? $('#pay-deposit').show() : $('#pay-deposit').hide();

	numberSignedUp = $('input[id^=Adult]:checked').length;
	peopleAmount = Number(selectedOption.total);
	totalDue = peopleAmount*numberSignedUp;
	if (deposit && $('#DefaultPayDepositAmount').is(':checked')) {
		totalDue = Number(selectedOption.deposit)*numberSignedUp;
	}
	$('#people-number').html(numberSignedUp);
	$('#people-total').html(peopleAmount*numberSignedUp);

	if (childcare) {
		// get number of children checked
		numberChildcareSignedUp = $('input[id^=Child]:checked').length;
		childcareAmount = Number(selectedOption.childcare);
		Number($('#children-number').html(numberChildcareSignedUp));
		Number($('#children-total').html(numberChildcareSignedUp*childcareAmount));
		totalDue += numberChildcareSignedUp*childcareAmount;
	} else {
		numberChildcareSignedUp = childcareAmount = 0;
	}

	if (payLater) {
		totalDue = 0;
	}

	if (totalDue == 0) {
		$('#billing, #billing-tab').hide();
	} else {
		$('#billing, #billing-tab').show();
	}

	// trigger wizard button update
	var activeTab = $('#roster_tabs').tabs('option', 'active');
	$('#roster_tabs').find('li').eq(activeTab).removeClass('ui-tabs-active');
	$('#roster_tabs').tabs('select', activeTab);

	$('#total-total').html(numberChildcareSignedUp*childcareAmount + peopleAmount*numberSignedUp);
	$('#balance').html($('#total-total').html() - totalDue);
	$('#confirm-balance').html($('#total-total').html() - totalDue);
	$('#amount').html(totalDue);
	$('#confirm-amount').html(totalDue);
}

