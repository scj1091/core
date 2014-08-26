<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo Core::read('general.site_name_tagless').' '.Core::read('version').' :: '.$title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		// CORE css
		$this->AssetCompress->css('reset');
		$this->AssetCompress->css('960');
		$this->AssetCompress->css('960-modal');
		$this->AssetCompress->css('font-face');
		$this->AssetCompress->css('menu');
		//$this->AssetCompress->css('jquery.qtip');
		echo $this->Html->css('//qtip2.com/v/2.2.0/basic/jquery.qtip.css');
		$this->AssetCompress->css('jquery-ui');
		$this->AssetCompress->css('styles');
		$this->AssetCompress->css('tables');
		$this->AssetCompress->css('wysiwyg');
		$this->AssetCompress->css('email');

		//$this->AssetCompress->css('fullcalendar');
		$this->AssetCompress->css('fullcalendar164');
		$this->AssetCompress->css('calendar');

		echo '<!--[if lt IE 9]>'.$this->Html->css('ie').'<![endif]-->';

		// google cdn scripts
		$min = Configure::read('debug') == 0 ? '.min' : null;
		echo $this->Html->script('//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery'.$min.'.js');
		echo $this->Html->script('//code.jquery.com/jquery-migrate-1.2.1.js');
		echo $this->Html->script('//ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/jquery-ui'.$min.'.js');

		// vendor scripts
		$this->AssetCompress->script('jquery.plugins/jquery.form');
		//$this->AssetCompress->script('jquery.plugins/jquery.qtip');
		echo $this->Html->script('//qtip2.com/v/2.2.0/basic/jquery.qtip.js');
		$this->AssetCompress->script('jquery.plugins/jquery.equalheights');
		//$this->AssetCompress->script('jquery.plugins/jquery.fullcalendar');
		$this->AssetCompress->script('jquery.plugins/jquery.fullcalendar164');
		$this->AssetCompress->script('wysiwyg/advanced');
		$this->AssetCompress->script('wysiwyg/wysihtml5-0.3.0.min');

		// CORE scripts
		$this->AssetCompress->script('functions');
		$this->AssetCompress->script('global');
		$this->AssetCompress->script('ui');
		$this->AssetCompress->script('form');
		$this->AssetCompress->script('navigation');

		// setup
		$this->Js->buffer('CORE.init();');
		$element = addslashes(str_replace(array("\r", "\r\n", "\n"), '', $this->element('wysiwyg_toolbar')));
		$this->Js->buffer("CORE.wysiwygToolbar = '$element';", true);
		if ($this->params['plugin']) {
			// set plugin so update system is aware (for magic `/index/page:1` append)
			$this->Js->buffer('CORE.plugin = "'.$this->params['plugin'].'";');
		}

		echo $this->AssetCompress->includeAssets(Configure::read('debug') == 0);
		echo $scripts_for_layout;
		echo $this->Js->writeBuffer();

		// analytics
		$trackingcode = Core::read('general.tracking_code');
		if (!empty($trackingcode)) {
			echo $trackingcode;
		}
	?>
</head>
<body>
	<div class="container_12" id="wrapper">
		<div class="container_12 clearfix" id="header">
			<div class="grid_10 main-nav-menu" id="primary">
				<?php echo $this->element('menu'.DS.'main_nav'); ?>
			</div>
			<div class="grid_2" id="secondary">
				<?php
				if (Core::read('notifications.support_email')) {
					echo $this->Html->link('Support', 'mailto:'.Core::read('notifications.support_email'));
					echo ' / ';
				}
				echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout', 'plugin' => false));
				?>
			</div>
		</div>
		<div id="content-container" class="container_12 clearfix">
			<div id="content" class="grid_10 prefix_1 suffix_1" data-core-update-url="<?php echo $this->here; ?>">
				<?php echo $this->Session->flash('auth'); ?>
				<?php echo $this->Session->flash(); ?>

				<?php echo $content_for_layout; ?>
			</div>
		</div>
		<div id="footer" class="container_12 clearfix">
			<?php
			echo $this->Html->image('logo-small.png');
			?>
		</div>
	</div>
	<?php
	// write any buffered scripts that were added in the layout
	echo $this->Js->writeBuffer();
	?>
</body>
</html>
