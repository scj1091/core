<?php
if (!empty($results)) {

$this->Paginator->options(array(
    'updateable' => 'parent'
));
echo $this->MultiSelect->create();
?>
<h3>Results</h3>
	<table cellpadding="0" cellspacing="0">
	<tr class="multi-select">
		<th colspan="6">
		<?php			
			echo $this->Html->link('Email', array(
				'controller' => 'sys_emails',
				'action' => 'compose',
				$this->MultiSelect->token
			), array(
				'rel' => 'modal-none'
			));
			
			echo $this->Html->link('Export List', array(
				'controller' => 'reports',
				'action' => 'export',
				'User',
				$this->MultiSelect->token
			), array(
				'rel' => 'modal-none'
			));
			
			echo $this->Html->link('View Map', array(
				'controller' => 'reports',
				'action' => 'map',
				$this->MultiSelect->token
			), array(
				'rel' => 'modal-none'
			));
		?>
		</th>
	</tr>
	<tr>
		<th width="20px;"><?php echo $this->MultiSelect->checkbox('all'); ?></th>
		<th width="40px;"></th>
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
			<td><?php echo $this->MultiSelect->checkbox($result['User']['id']); ?></td>
			<td><?php 
			if (!empty($result['Image'])) {
				$path = 'xs'.DS.$result['Image'][0]['dirname'].DS.$result['Image'][0]['basename'];
				echo $this->Media->embed($path, array('restrict' => 'image'));
			}			
			?></td>
			<td><?php echo $this->Formatting->flags('User', $result).$this->Html->link($result['User']['username'], array('controller' => 'profiles', 'action' => 'view', 'User' => $result['User']['id'])); ?></td>
			<td><?php echo $result['Profile']['first_name']; ?></td>
			<td><?php echo $result['Profile']['last_name']; ?></td>
			<td class="actions">&nbsp;</td>
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
<?php

echo $this->MultiSelect->end();

} else {
?>
<h3>Results</h3>
<p>No results</p>
<?php 
}
?>