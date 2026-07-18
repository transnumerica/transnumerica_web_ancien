<div class="form">
<?php
$adding = true;
echo $this->Form->create('Content', array('type' => 'edit'));
?>
<h1><?php echo sprintf(__d('brownie', 'Edit %s'), $key); ?></h1>
<?php
echo $this->Form->input('model', array('value' => $model, 'type' => 'hidden'));

			echo $this->Form->input($model . '.' . $key, array('type' => 'text','div' => false, 'style' => 'width:'.$schema[$key]['length'].'em'));

?>

<div class="submit">
	<input type="submit" value="<?php echo __d('brownie', 'Save') ?>" />
	<input type="reset" value="<?php echo __d('brownie', 'Reset') ?>" />
	<a href="<?php echo Router::url(array('controller' => 'brownie', 'action' => 'index')) ?>" class="cancel">Cancel</a>
</div>


<?php echo $this->Form->end(); ?>
</div>