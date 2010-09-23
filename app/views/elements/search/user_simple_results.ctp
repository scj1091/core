<?php
$this->Paginator->options(array(
    'update' => '#content',
    'evalScripts' => true
));
?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('username'); ?></th>
	<th><?php echo $this->Paginator->sort('First Name', 'Profile.first_name'); ?></th>
	<th><?php echo $this->Paginator->sort('Last Name', 'Profile.last_name'); ?></th>
	<th class="actions">Actions</th>
</tr>
<?php
$i = 0;
foreach ($results as $result):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td><?php echo $this->Formatting->flags('User', $result).$result['User']['username']; ?></td>
		<td><?php echo $result['Profile']['first_name']; ?></td>
		<td><?php echo $result['Profile']['last_name']; ?></td>
		<td class="actions"><?php
			if (!empty($actions)) {
				foreach ($actions as $name => $jsfunc) {
					echo $this->Html->link($name, 'javascript:;', array(
						'onclick' => $jsfunc.'('.$result['User']['id'].');$(this).addClass("disabled");$(this).removeAttr("onclick");'
					));
				}
			}
		?></td>
	</tr>
<?php
endforeach;
?>
</table>

<p>
<?php
echo $this->Paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?>	</p>

<div class="paging">
	<?php echo $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $this->Paginator->numbers();?>
|
	<?php echo $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
</div>