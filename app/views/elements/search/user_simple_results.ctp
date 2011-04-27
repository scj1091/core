<?php
$this->Paginator->options(array(
    'updateable' => 'parent'
));
?>

<table cellpadding="0" cellspacing="0" class="datatable">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort('username'); ?></th>
			<th><?php echo $this->Paginator->sort('First Name', 'Profile.first_name'); ?></th>
			<th><?php echo $this->Paginator->sort('Last Name', 'Profile.last_name'); ?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$i = 0;
		foreach ($results as $result):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
			<tr<?php echo $class;?>>
				<td><?php echo $result['User']['username'].$this->Formatting->flags('User', $result); ?></td>
				<td><?php echo $result['Profile']['first_name']; ?></td>
				<td><?php echo $result['Profile']['last_name']; ?></td>
				<td><?php echo $this->element('search'.DS.'actions'.DS.$element, compact('result')); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo $this->element('pagination'); ?>