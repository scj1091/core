<?php echo $leader['Profile']['name']; ?> has been removed from leading <?php echo strtolower($itemType); ?> in <strong><?php echo $this->Html->link($item[$model]['name'], array('controller' => Inflector::tableize($model), 'action' => 'view', $model => $item[$model]['id'])); ?></strong>.