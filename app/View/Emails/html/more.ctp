<?php
$content = explode("\n", $content);

foreach ($content as $line):
	echo '<p> ' . $line . "</p>\n";
endforeach;
?>
<hr>
<p>Si vous avez un problème ponctuel ou particulier ? Nous pouvons optimiser vos dépenses ! <?php echo $this->Html->link('FAITES UNE CONSULTATION', Router::url(array('plugin' => false, 'controller' => 'consultation', 'action' => 'index'), true))?>.</p>