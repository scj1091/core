<?php echo $signedUpUsers; ?> <?php echo $verb; ?> signed up for the <?php echo $involvement['InvolvementType']['name']; ?> <strong><?php echo $this->Html->link($involvement['Involvement']['name'], array('controller' => 'involvements', 'action' => 'view', 'Involvement' => $involvement['Involvement']['id'])); ?></strong>.