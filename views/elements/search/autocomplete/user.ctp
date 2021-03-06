<?php
if (isset($user['ImageIcon'])) {
	$path = 's'.DS.$user['ImageIcon']['dirname'].DS.$user['ImageIcon']['basename'];
	echo $this->Html->tag('div', $this->Media->embed($path, array('restrict' => 'image')).'&nbsp;', array('class' => 'autocomplete-image'));
}
?>
<div class="autocomplete-row">
<?php
echo $this->Html->tag('p', $this->Text->highlight($user['Profile']['name'], $query).$this->Formatting->flags('User', $user));
$icon = $this->Html->tag('span', 'Email', array('class' => 'core-icon icon-email'));
$email = $this->Text->highlight($user['Profile']['primary_email'], $query);
if (!empty($user['Profile']['primary_email'])) {
	echo $this->Html->tag('p', $icon.$email);
}
echo $this->Html->tag('p', $this->Formatting->phone($user['Profile']['cell_phone']));
?>
</div>