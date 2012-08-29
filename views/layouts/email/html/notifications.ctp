<!DOCTYPE html>
<html>
	<body>
		<div style="
			font-family: 'Lucida Sans',Helvetica,Arial,sans-serif;
			font-size:11px;
			color: #838383;
			width: 440px;
		">
			<?php if ($include_greeting): ?>
			<p>Hey <?php echo ucfirst($toUser['Profile']['first_name']); ?>,</p>
			<?php endif; ?>
			<?php echo $content_for_layout; ?>
			<?php if ($include_signoff): ?>
			<p><img src="<?php echo Router::url('/', true).'img/logo-small.png'; ?>" /><br /><?php echo $this->Html->link(Router::url('/', true), Router::url('/', true)); ?></p>
			<?php endif; ?>
		</div>
	</body>
</html>