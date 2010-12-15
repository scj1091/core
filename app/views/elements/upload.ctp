<?php
if (!isset($model) || !isset(${$model}) || !isset($type)) {
	return;
}
echo $this->Html->script('jquery.plugins/jquery.form', array('inline' => false));

if (!isset($update)) {
	$update = $type.'Attachments';
}
if (!isset($title)) {
	$title = 'Upload';
}
$type = ucfirst($type);
$uid = uniqid();

echo $this->Form->create($model, array(
	'type' => 'file',
	'url' => array(
		'action' => 'upload',
		'controller' => Inflector::tableize($model.$type),
		'model' => $model,
		$model => ${$model},
		'ext' => 'json'
	),
	'id' => 'Upload'.$model.'Form'.$uid
));
echo $this->Form->hidden($type.'.foreign_key', array('value' => ${$model}));
echo $this->Form->hidden($type.'.model', array('value' => $model));
echo $this->Form->hidden($type.'.group', array('value' => $type));
echo $this->Form->file($type.'.file', array(
	'id' => $type.'File'.$uid
));

echo $this->Form->end($title);

$this->Js->buffer('CORE.ajaxUpload("Upload'.$model.'Form'.$uid.'", "'.$update.'");');
?>