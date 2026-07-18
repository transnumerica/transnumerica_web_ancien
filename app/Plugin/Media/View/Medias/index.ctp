
<div id="dropzone">

	<div class="tabs">
		<a id="browse" href="#" class="tab-item"><?php echo __d('media','Upload a new file'); ?></a>
		<div class="tab-item is-current"><?= __d('media','Gallery'); ?></div>
	</div>

	<div class="gallery">
		<?php foreach ($medias as $media): $media = current($media); ?>
			<?php require('media.ctp'); ?>
		<?php endforeach ?>
		<div class="gallery-item" id="gallery-loader">
			<div class="gallery-item-thumb">
				<div class="gallery-loader"></div>
			</div>
		</div>
		<div class="cb"></div>
	</div>

	<div class="overlay">
		<div class="borders"><span><?php echo __d('media',"Drop your files to upload"); ?></span></div>
	</div>
</div>

<div id="loader"></div>

<?php $this->start("script"); ?>
<?= $this->Html->script('/media/js/underscore.js'); ?>
<?= $this->Html->script('/media/js/dropzone.js'); ?>

<?php

	$queryEncrypt = Op::compress(array(
		'id' => $id, 
		'ref' => $ref, 
		'ref_id' => $ref_id, 
		'session_id' => CakeSession::id(), 
	));

?>

<script type="text/javascript">
(function($){

	var timer = null;
	var $loader = $('#loader');
	dragEnteredEls = [];

	// Dropzone
	var drop = $("body").dropzone({
		url: "<?php echo Router::url(array('controller'=>'medias','action'=>'upload','editor'=>$editor,'?' => array('id' => $id, 'tk' => $queryEncrypt))); ?>",
		//acceptedFiles: ".<?php echo implode(';.', $extensions); ?>",
		clickable: "#browse",
		dragenter: function (e) {
		    dragEnteredEls.push(e.target);
		    $('.overlay').stop().fadeIn();
		},
		drop: function (e) {
		    $('.overlay').stop().fadeOut();
		},
		dragleave: function (e) {
		    dragEnteredEls = _.without(dragEnteredEls, e.target);
		    if (dragEnteredEls.length === 0) {
		        $('.overlay').stop().fadeOut();
		    }
		},
		success: function (file, data) {
			$item = $($.parseJSON(data).content);
			$item.addClass('is-active');
			$('.gallery-item-infos').hide();
			$('.gallery-item-infos', $item).show();
			$('.gallery').prepend($item);
		},
		uploadprogress: function(file, percent) {
			$loader.show();
			$('.gallery-loader', $loader).height(percent + "%");
			if(percent === 100){
				$loader.hide();
			}
		},
		previewTemplate: false,
	});

	// Order
	$('.gallery').sortable({
		items: '.gallery-item',
		handle: '.gallery-item-thumb',
		update: function(event, ui) {
    	var order = $(this).sortable("toArray", {attribute: "data-id"});
    	var ids = {};
    	for(var i in order){
    		ids[order[i]] = i;
    	}
    	$loader.stop().fadeIn();
    	$.post('<?= $this->Html->url(array('action' => 'order')); ?>', {Media: ids}, function(data){
    		$loader.stop().fadeOut();
    	});
    }
	});
	$('.gallery').disableSelection();

	// Clicks on items to reveal details
	$('.gallery').on('click', '.gallery-item-thumb', function(e){
		e.preventDefault();
		$item = $(this).parent();
		$item.addClass('is-active').siblings().removeClass('is-active');
		$('.gallery-item-infos').hide();
		$('.gallery-item-infos', $item).show();
	});

	$('.gallery').on('submit', 'form', function(e){
		datas = $(this).serialize();
		$loader.stop().fadeIn();
		$.post($(this).attr('action'), datas, function(data){
			$loader.fadeOut();
		});
		e.preventDefault();
		return false;
	});

	// Delete link
	$('.gallery').on('click', '.delete', function(e){
		e.preventDefault();
		if (confirm("<?= __d('media','Do you really want to delete this file ?'); ?>")) {
			$this = $(this);
			$.get($(this).attr('href'), {}, function(){
				$this.parents('.gallery-item').fadeOut();
			});
		}
	});

	$('.gallery').on('blur', '.autosubmit', function(){
		$(this).parents('form').trigger('submit');
	});


	<?php if($editor): ?>

		$('.gallery').on('click', 'a.submit', function(e){
			e.preventDefault();
			var $this = $(this);
			var html = createHtmlElement($this);
			var editor = '<?php echo $editor; ?>';
			var win = window.dialogArugments || opener || parent || top;
			win.send_to_<?php echo $editor; ?>(html, window, "<?php echo $id; ?>");
			return false;
		});

		function createHtmlElement($this) {
			var item = $this.parents('.gallery-item');
			var type = $('.filetype', item).val();
			if(type === 'pic') {
				var html = '<img src="'+$('.path', item).val()+'"';
				if( $('.alt', item).val() != '' ){
					html += ' alt="'+$('.alt', item).val()+'"';
				}
				if( $('.align', item).val() != 'none' ){
					html += ' class="align'+$('.align', item).val()+'"';
				}
				html += ' />';
				if( $('.href', item).val() != '' ){
					html = '<a href="'+$('.href', item).val()+'" title="'+$('.title', item).val()+'">'+html+'</a>';
				}
			} else {
				html = '<a href="'+$('.href', item).val()+'" title="'+$('.title', item).val()+'">' + $('.title', item).val() + '</a>';
			}
			return html;
		}

	<?php endif; ?>

})(jQuery);
</script>
<?php $this->end(); ?>
