<?php
$this->Paginator->options('#roster');
?>
<h1><?php echo $involvement['Involvement']['name']; ?> Roster</h1>
<?php
	echo $this->MultiSelect->create();
?>
	<table cellpadding="0" cellspacing="0" id="rosterTable" class="datatable">
		<thead>
			<?php
			$links = array(
				array(
					'title' => 'Email',
					'url' => array(
						'controller' => 'sys_emails',
						'action' => 'compose',
						$this->MultiSelect->token,
						'model' => 'Roster'
					),
					'options' => array(
						'rel' => 'modal-none'
					)
				),
				array(
					'title' => 'Export',
					'url' => array(
						'controller' => 'reports',
						'action' => 'export',
						'Roster',
						$this->MultiSelect->token
					),
					'options' => array(
						'rel' => 'modal-none'
					)
				),				
				array(
					'title' => 'View Map Results',
					'url' => array(
						'controller' => 'reports',
						'action' => 'map',
						'Roster',
						$this->MultiSelect->token
					),
					'options' => array(
						'rel' => 'modal-none'
					)
				),
				array(
					'title' => 'Add Payment',
					'url' => array(
						'controller' => 'payments',
						'action' => 'add',
						'Involvement' => $involvement['Involvement']['id'],
						$this->MultiSelect->token
					),
					'options' => array(
						'rel' => 'modal-involvement'
					),
					'permission' => $involvement['Involvement']['take_payment']
				),
				array(
					'title' => 'Remove',
					'url' => array(
						'controller' => 'rosters',
						'action' => 'delete',
						$this->MultiSelect->token
					),
					'options' => array(
						'rel' => 'modal-involvement'
					)
				),
				array(
					'title' => 'Confirm',
					'url' => array(
						'controller' => 'rosters',
						'action' => 'confirm',
						$this->MultiSelect->token
					),
					'options' => array(
						'rel' => 'modal-involvement'
					)
				)
			);
			$colCount = 7;
			if (!$involvement['Involvement']['take_payment']) {
				$colCount--;
			}
			echo $this->element('multiselect', array(
				'colCount' => $colCount,
				'checkAll' => $canCheckAll,
				'links' => $links
			));
			?>			
			<tr>
				<th>&nbsp;</th>
				<th><?php echo $this->Paginator->sort('Name', 'Profile.last_name');?></th>
				<th><?php echo $this->Paginator->sort('Phone', 'Profile.cell_phone');?></th>
				<th><?php echo $this->Paginator->sort('Status', 'Roster.roster_status');?></th>
				<?php if ($involvement['Involvement']['take_payment']) { ?>
				<th><?php echo $this->Paginator->sort('balance');?></th>
				<?php } ?>
				<th><?php echo $this->Paginator->sort('Date Joined', 'created');?></th>
				<th>Roles</th>
			</tr>
		</thead>
	<tbody>
	<?php
	$i = 0;
	foreach ($rosters as $roster):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php 
		if (in_array($roster['User']['id'], $householdIds) || $roster['Profile']['allow_sponsorage'] || $canCheckAll) {
			echo $this->MultiSelect->checkbox($roster['Roster']['id']);
		}
		?></td>
		<td><?php 
		$name = $roster['Profile']['name'].$this->Formatting->flags('User', $roster);
		echo $this->Html->link($name, array('controller' => 'users', 'action' => 'view', 'User' => $roster['User']['id']), array('escape' => false));
		?>&nbsp;
		<div class="core-tooltip"><?php
			if (isset($roster['ImageIcon'])) {
				$path = 's'.DS.$roster['ImageIcon']['dirname'].DS.$roster['ImageIcon']['basename'];
				echo $this->Media->embed($path, array('restrict' => 'image'));
			}
			echo $this->Html->link('Edit Info', array('controller' => 'rosters', 'action' => 'edit', $roster['Roster']['id']), array('rel' => 'modal-roster'));
			echo $this->Html->link('View Profile', array('controller' => 'users', 'action' => 'view', 'User' => $roster['User']['id']));
			echo $this->Html->link('View Payments', array('controller' => 'payments', 'action' => 'index', 'User' => $roster['User']['id'], 'Involvement' => $involvement['Involvement']['id']), array('rel' => 'modal-none'));
		?></div>
		</td>
		<td><?php echo $this->Formatting->phone($roster['Profile']['cell_phone']); ?>&nbsp;</td>
		<td><?php echo $statuses[$roster['Roster']['roster_status']]; ?>&nbsp;</td>
		<?php if ($involvement['Involvement']['take_payment']) { ?>
		<td><?php echo $this->Formatting->money($roster['Roster']['balance']); ?>&nbsp;</td>
		<?php } ?>
		<td><?php echo $this->Formatting->date($roster['Roster']['created']); ?>&nbsp;</td>
		<td><?php
		echo $this->Html->link(count($roster['Role']).' Roles', array('controller' => 'rosters', 'action' => 'roles', 'Involvement' => $involvement['Involvement']['id'], $roster['Roster']['id']), array('class' => 'icon-add', 'rel' => 'modal-roster'));
		if (!empty($roster['Role'])) {
			echo '<div class="core-tooltip">';
			echo $this->Text->toList(Set::extract('/name', $roster['Role']));
			echo '</div>';
		}
		?></td>
	</tr>
<?php endforeach; ?>
	</tbody>
		<tfoot>
			<?php
			echo $this->element('pagination', array('colCount' => $colCount));
			?>
		</tfoot>
	</table>	
<?php
	echo $this->MultiSelect->end();
?>

<?php

$this->Js->buffer('function addToRoster(userid) {
	redirect("'.Router::url(array(
		'controller' => 'rosters',
		'action' => 'add',
		'Involvement' => $involvement['Involvement']['id']
	)).'/User:"+userid);
}');

?>