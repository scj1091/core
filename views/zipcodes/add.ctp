<h1>Zipcodes</h1>
<div class="zipcodes">
	<?php
		echo $this->Form->create('Zipcode', array(
			'default' => false
		));
	?>
	<fieldset>
		<legend>Add Zipcode</legend>
		<?php
			echo $this->Form->hidden('region_id');
			echo $this->Form->input('zip');
		?>
	</fieldset>
	<?php
		$defaultSubmitOptions['success'] = 'CORE.successForm(event, data, textStatus, {closeModals:true})';
		echo $this->Js->submit('Add', $defaultSubmitOptions);
		echo $this->Form->end();
	?>
</div>