<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo Core::read('site_name_tagless').' '.Configure::read('CORE.version').' :: '.$title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->css('print');
		
		// google cdn scripts
		echo $this->Html->script('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js');
		
		echo $scripts_for_layout;
		
		echo $this->Js->writeBuffer();
	?>
</head>
<body>
<?php echo $content_for_layout; ?>
</body>
</html>