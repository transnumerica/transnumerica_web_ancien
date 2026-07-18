<?php

    $title = $this->fetch('title');
    if (!empty($title)) echo '<title>'.$title.'</title>';

?>



<?php echo $this->element('siteheader'); ?>

	<?php echo $this->fetch('navbar'); ?>

<?php if($this->params->controller == 'panier'){
?><div id="flash0">
	<?php echo $this->Flash->render() ?>
</div>
<?php 
}
echo $this->fetch('content'); 
?>
