<div class="image" style="width: <?php echo '200' ?>px;">
	<ul class="image-actions clearfix actions">
		<?php
		if($permissions[$model]['edit']){
			echo '
			<li class="edit">' . $this->Html->link(__d('brownie', 'Edit'), array(
				'controller' => 'contents', 'action' => 'edit_upload', 'plugin' => 'brownie',
				$image['ref'], 'BrwImage', $image['ref_id'], $image['category_code'], $image['id']
			)) . '</li>
			<li class="delete">' . $this->Html->link(__d('brownie', 'Delete'), array(
				'controller' => 'contents', 'action' => 'delete_upload', 'plugin' => 'brownie',
				$image['ref'], 'BrwImage', $image['id']
			), null, __d('brownie', 'Are you sure you want to delete this image?')) . '</li>';
		}
		?>
	</ul>

	<a class="brw-image" title="<?php echo $image['name'] ?>" href="/drive/<?php echo $image['path'] ?>" rel="brw_image_<?php echo $image['ref_id'] ?>">'<img alt="<?php echo $image['name'] ?>" src="<?php echo Router::url($this->image->resizedURL($image['path'],200 ,200, 60, true)) ?>" />'</a>

	<?php if ($image['description']): ?>
		<p class="description"><?php echo $image['description'] ?></p>
	<?php endif ?>
</div>