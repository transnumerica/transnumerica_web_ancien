<div class="alert alert-<?= ($params ? h($params) : '') ?> alert-styled-left alert-dismissible">
	<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
	<span class="font-weight-semibold"><?php echo $message; ?></span>
</div>