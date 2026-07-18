<?php

	$sizes = getimagesize(WWW_ROOT.trim($media['file'], '/'));
	$fileThumb = $media['file'];
	if (!$sizes) {
		$imageThu = APP.'/Plugin/Media/webroot/img/'.$media['type'].'.png';
		$sizes = getimagesize($imageThu);
		$fileThumb = '/Media/img/'.$media['type'].'.png';
	}

 ?>


<div class="gallery-item <?php if($thumbID && $media['id'] === $thumbID): ?>is-thumbnail<?php endif; ?>" id="gallery-<?= $media['id']; ?>" data-id="<?= $media['id']; ?>">
	<div class="gallery-item-thumb">
		<?= $this->Html->image($media['icon']); ?>
	</div>
	<div class="gallery-item-infos">
		<h2><?= __d('media','About this file'); ?></h2>
		<div class="file">
			<?= $this->Html->image($fileThumb);?>
			<p>
				<strong><?= basename($media['file']); ?></strong><br>
				<?= $this->Time->i18nFormat($media['created']); ?><br>
				<?= $sizes[0].' x '.$sizes[1]; ?>
				<br><br>
				<?php

				$queryEncrypt = Op::compress(array(
					'id' => $media['id'], 
					'session_id' => CakeSession::id(), 
				));

				echo $this->Html->link(__d('media','Delete definitively'),array('action'=>'delete', '?' => array('tk' => $queryEncrypt)), array('class'=>'delete red')); ?>
			</p>
			<div class="cb"></div>
		</div>
		<?= $this->Form->create('Media', array('url' => array('controller' => 'medias', 'action' => 'update', $media['id']))); ?>
		<table>
			<tr>
				<td style="width:140px"><label><?= __d('media',"Is Online"); ?></label></td>
				<td><?= $this->Form->input('online', array('type' => 'checkbox', 'class' => 'isonline autosubmit', 'div' => false, 'label' => false, 'default' => $media['online'])); ?></td>
			</tr>
			<tr>
				<td style="width:140px"><label><?= __d('media',"Title"); ?></label></td>
				<td><?= $this->Form->input('name', array('class' => 'title autosubmit', 'div' => false, 'label' => false, 'value' => $media['name'])); ?></td>
			</tr>
			<tr>
				<td style="width:140px"><label><?= __d('media',"Alt text"); ?></label></td>
				<td><input class="alt" name="alt" type="text"></td>
			</tr>
			<tr>
				<td style="width:140px"><label><?= __d('media',"Target"); ?></label></td>
				<td><input class="href" name="href" type="text" value="<?= $this->Html->url($fileThumb); ?>"></td>
			</tr>
			<tr>
				<td style="width:140px"><label><?= __d('media',"Alignment"); ?></label></td>
				<td>
					<select name="align" class="align">
						<option value="none"><?= __d('media','None'); ?></option>
						<option value="center"><?= __d('media','Center'); ?></option>
						<option value="left"><?= __d('media','Left'); ?></option>
						<option value="right"><?= __d('media','Right'); ?></option>
					</select
				</td>
			</tr>
			<tr>
				<td style="width:140px"><input type="hidden" class="filetype" name="filetype" value="<?= $media['type']; ?>" /></td>
				<td>
				</td>
			</tr>
			<input type="hidden" name="file" value="<?= $this->Html->url($fileThumb); ?>" class="path">
		</table>
		<?= $this->Form->end(); ?>
		<p class="tright">
				<?php if($thumbID !== false && $media['id'] !== $thumbID && $media['type'] == 'pic'): ?>
					<?= $this->Html->link(__d('media',"Set as thumbnail"),array('action'=>'thumb',$media['id']), array('class' => 'btn btn-grey')); ?>
				<?php endif; ?>
				&nbsp;
				<?php if ($editor): ?>
					<a href="" class="submit btn"><?= __d('media',"Insert into post"); ?></a>
				<?php endif; ?>
			</p>
	</div>
</div>