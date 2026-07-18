<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo "<title>".Configure::read('Company.name')." - Erreur</title>"; ?>
	</title>
	<?php
        echo $this->Html->meta('logo','/logo.png', array('type' => 'icon'));

		echo $this->Html->css('miroir');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		//echo $this->fetch('script');
	?>
</head>
<body>
		<div id="header">
			<h1><?php echo Configure::read('Company.name'); ?></h1>
		</div>
		<div id="contenu">

			<?php echo $this->Flash->render();?></div>

			<?php echo $this->fetch('content'); ?>
		</div>
		
</body>
</html>
