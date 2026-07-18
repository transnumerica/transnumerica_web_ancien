<table>
	<?php foreach ($data as $lang => $value): ?>
	<tr>
		<td class="locale locale_<?php echo $lang ?>">
			<?php echo $this->i18n->humanize($lang); ?>
		</td>
		<td class="fcktxt">
			<?php echo $value ?>
		</td>
	</tr>
	<?php endforeach ?>
</table>