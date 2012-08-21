<h1>Search Ministries</h1>
<div class="ministries content-box">
<?php echo $this->Form->create('Search', array(
	'action' => 'ministry',
	'default' => false
));?>
	<fieldset>
 		<legend>Search Ministries</legend>
	<?php
		echo $this->Form->input('Ministry.name');
		echo $this->Form->input('Ministry.description');
		echo $this->Form->input('Ministry.campus_id', array(
			'empty' => true
		));
		if ($inactive) {
			echo $this->Form->input('Ministry.inactive', array(
				'type' => 'checkbox'
			));
		}
		if ($private) {
			echo $this->Form->input('Ministry.private');
		}
	?>
	</fieldset>
<?php
$defaultSubmitOptions['success'] = '$("#ministry-results").html(data);';
echo $this->Js->submit('Search!', $defaultSubmitOptions);
echo $this->Form->end();
?>
	<div id="ministry-results" data-core-update-url="<?php echo $this->here; ?>">
	</div>
</div>