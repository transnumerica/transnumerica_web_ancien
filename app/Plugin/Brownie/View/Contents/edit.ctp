<?php 

if (!empty($brwConfig['fields']['conditional'])) echo $this->element('js_conditional_fields'); ?>
<div class="form">
<?php
$url = array('controller' => 'contents', 'action' => 'edit', $model);
$adding = empty($this->data[$model][$primaryKey]);
if (!$adding) {
	$url[] = $this->data[$model][$primaryKey];
}
echo $this->Form->create('Content', array(
	'type' => 'file', /*'action' => 'edit', */'autocomplete' => 'off', 'url' => $url, 'novalidate' => true,
	'inputDefaults' => array('separator' => ' ')
));
?>
<fieldset>
	<legend>
	<?php
	$action = $adding ? __d('brownie', 'Add :name_singular') : __d('brownie', 'Edit :name_singular');
	echo CakeText::insert($action, array('name_singular' => __($brwConfig['names']['singular'])));
	?>
	</legend>
	<?php
	echo $this->Form->input('model', array('value' => $model, 'type' => 'hidden'));


	/*if (!empty($langs3chars)) {
		echo '<div id="enabledLangs" class="clearfix">
		<label class="enabledLangs">' . __d('brownie', 'Enabled languages', true) . '</label>' ;
		foreach ($langs3chars as $lang2 => $lang3) {
			echo $this->Form->input('enabled_' . $lang3, array(
				'type' => 'checkbox', 'label' => $this->i18n->humanize($lang3)
			));
		}
		echo '</div>';
	}*/
//debug($fields);

	if($adding){
		$disabledField = $brwConfig['fields']['no_add'];
	}else{
		$disabledField = $brwConfig['fields']['no_edit'];
	}

	foreach ($fields as $key => $value) {

		$disabled = false;
		if (in_array($key, $disabledField)) {
			$disabled = true;
		}

		echo $this->element('input', array('fields' => $fields, 'key' => $key, 'value' => $value, 'related' => $related, 'adding' => $adding, 'disabled' => $disabled));
	}

	if ($media AND !array_key_exists('media_id',$fields)) {

		if (!empty($this->request->data[$model]['id'])) {
			echo $this->Media->iframe($model, $this->request->data[$model]['id']);
		}else{
			echo "<p>Créer et sauvegarder l'enregistrement une fois avant de pouvoir placer des medias</p>";
		}

	}

	if (isset($related['hasAndBelongsToMany'])) {
		foreach ($related['hasAndBelongsToMany'] as $key => $list) {
			if (!empty($list)) {
				$params = array('multiple' => 'checkbox', 'options' => $list);

				foreach ($list as $val) {
					$group = false;
					if (is_string($val) OR (isset($val['name']) AND isset($val['value']))) {
						
					}else{
						$group = true;
						break;
					}
				}


				if (count($list) > 5 AND $group == false) {
					$params['multiple'] = 'multiple';
					$params['escape'] = false;
					$params['size'] = 5;
					$params['class'] = 'combo-select';
					$params['class'] = 'searchable';
					//echo $this->Html->script('/brownie/js/jquery.selso');
					//echo $this->Html->script('/brownie/js/jquery.comboselect');

					echo $this->Html->css('/brownie/css/multi-select', array('media' => 'screen'));
					echo $this->Html->script('/brownie/js/jquery.multi-select');
					echo $this->Html->script('/brownie/js/jquery.quicksearch');

				}
				echo $this->Form->input($key . '.' . $key, $params);
			}
		}
	}


	?>




<script type="text/javascript">

  if ($('.searchable').length > 0) {
      $('.searchable').ready(function(){

				$('.searchable').multiSelect({
					dblClick: true,
					keepOrder: true,
					selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Recherche'>",
					selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Recherche'>",
				afterInit: function(ms){
				  var that = this,
				      $selectableSearch = that.$selectableUl.prev(),
				      $selectionSearch = that.$selectionUl.prev(),
				      selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
				      selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

				  that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
				  .on('keydown', function(e){
				    if (e.which === 40){
				      that.$selectableUl.focus();
				      return false;
				    }
				  });

				  that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
				  .on('keydown', function(e){
				    if (e.which == 40){
				      that.$selectionUl.focus();
				      return false;
				    }
				  });
				},
				afterSelect: function(){
				  this.qs1.cache();
				  this.qs2.cache();
				},
				afterDeselect: function(){
				  this.qs1.cache();
				  this.qs2.cache();
				}
				});

      });
  }

</script>


</fieldset>
<?php
$uploads = array('Image', 'File');
$i = 0;
foreach ($uploads as $upload) :

	$continue = false;
	if ($upload == 'Image' and !empty($brwConfig['images'])) {
		$continue = true;
		$uploadConfig = $brwConfig['images'];
	} elseif ($upload == 'File' and !empty($brwConfig['files'])) {
		$continue = true;
		$uploadConfig = $brwConfig['files'];
	}

	if ($continue and $adding) :
		foreach ($uploadConfig as $categoryCode => $uploadCat) : ?>
			<fieldset class="fieldsUploads">
				<legend><?php echo $uploadCat['name_category'] ?></legend>
				<?php $classes = array('fieldsetUploads'); if (!$uploadCat['index']) $classes[] = 'hide'; ?>
				<div id="fieldset<?php echo $i ?>" class="<?php echo  join(' ', $classes) ?>">
					<input type="file" name="data[Brw<?php echo $upload ?>][file][]" size="100%" />
					<input type="hidden" name="data[Brw<?php echo $upload ?>][model][]" value="<?php echo $model ?>" />
					<input type="hidden" name="data[Brw<?php echo $upload ?>][category_code][]" value="<?php echo $categoryCode ?>" />
					<?php
					if ($uploadCat['description']) :
						echo $this->Form->input('Brw' . $upload . '.' . $i . '.description', array(
							'label' => __d('brownie', 'Description'),
							'name' => 'data[Brw' . $upload . '][description][]',
						));
					else : ?>
						<input type="hidden" name="data[Brw<?php echo $upload ?>][description][]" value="" />
					<?php endif ?>

					<?php if (!$uploadCat['index']) : ?>
						<ul class="actions"><li class="delete"><a href="#" class="cloneRemove">Remove</a></li></ul>
					<?php endif ?>

				</div>
				<?php if (!$uploadCat['index']) : ?>
				<div id="cloneHoder<?php echo $i ?>" class="cloneHolder"></div>
				<a href="#" class="cloneLink cloneLink_<?php echo $upload ?>" id="clone_<?php echo $i ?>"><?php
				echo ($upload == 'Image')? __d('brownie', 'Add Image') : __d('brownie', 'Add File')
				?></a>
				<?php endif ?>
			</fieldset>
		<?php
		$i++;
		endforeach;
	endif;
endforeach;


?>

<fieldset>
<?php echo $this->Form->input('after_save', $afterSaveOptionsParams) ?>
</fieldset>
<?php echo $this->Form->hidden('referer') ?>
<div class="submit">
	<input type="submit" value="<?php echo __d('brownie', 'Save') ?>" />
	<input type="reset" value="<?php echo __d('brownie', 'Reset') ?>" />
	<a href="<?php echo Router::url(array('controller' => 'brownie', 'action' => 'index')) ?>" class="cancel">Cancel</a>
</div>

<?php echo $this->Form->end(); ?>
</div>