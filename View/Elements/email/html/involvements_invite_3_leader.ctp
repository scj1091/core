<?php echo $invitee['Profile']['name']; ?> was invited to the <?php echo $involvement['InvolvementType']['name']; ?> <strong><?php echo $this->Html->link($involvement['Involvement']['name'], array('controller' => 'involvements', 'action' => 'view', 'Involvement' => $involvement['Involvement']['id'], 'full_base' => true)); ?></strong>.