<?php
App::import('Lib', 'Media.Url', array('file' => 'Url.php'));
class MediaHelper extends AppHelper{

	public $helpers = array('Html','Form');
	public $javascript = false;
	public $explorer = false;
	public $editorcss = false;


    public $view;

    public function __construct($view) {

        $this->view = $view;
        parent::__construct($view);
    }
    
	protected function getModelAlias($field = null) {
		if ($field AND is_string($field) AND count(explode('.', $field)) > 1) {
			$model = explode('.', $field)[0];
		}else{
			$model = $this->Form->_models;
			$model = key($model);
		}
		return $model;
	}

	public function tinymce($field, $options = array(), $model = null){
		$this->Html->script('/media/js/tinymce/tinymce.min.js',array('inline'=>false));
		$this->Html->script('/media/js/tinymce/editor.js',array('inline'=>false));
		return $this->textarea($field, 'tinymce', $options, $model);
	}

	public function ckeditor($field, $options = array(), $model = null) {
		if (empty($model)) $model = $this->getModelAlias($field);
		$this->Html->script('/media/js/ckeditor/ckeditor.js',array('inline'=>false));
		return $this->textarea($field, 'ckeditor', $options, $model);
	}

	public function redactor($field, $options = array(), $model = null) {
		if (empty($model)) $model = $this->getModelAlias($field);

		if (!Configure::read('redactor_s_count')) {
			$this->view->AssetCompress->addScript(array('/media/js/redactor/redactor.js','/media/js/redactor/lang/fr.js'
				//,'/media/js/redactor/plugins/fullscreen/fullscreen.js'
				),'redactor.js');
			$this->view->AssetCompress->addCss(array('/media/js/redactor/redactor.css'),'redactor.css');

			Configure::write('redactor_s_count', true);


?>

<script type="text/javascript">

	jQuery(function($){

		$('.redactor').each(function(){
			$(this).redactor({
			lang: 'fr',
			//plugins: ['fullscreen'],
			//placeholder: 'ste',
			//imageUpload: true,

			buttons: ['formatting', 'bold', 'italic', 'underline', 'unorderedlist', 'orderedlist', 'image', 'file', 'alignment', 'horizontalrule'], // + 'underline'
			
			//image: '<iframe width="610" height="800" src="'+$('#explorer').val()+'" id="medias'+$(this).attr('id')+'">',
			//imageEdit: '<iframe width="610" height="800" src="'+$('#explorer').val()+'" id="medias'+$(this).attr('id')+'">',


			});
		});

	});

</script>

<?php

		}




		return $this->textarea($field, 'redactor', $options, $model);
	}

	public function textarea($field, $editor = false, $options = array(), $model = null){
		$options = array_merge(array('label'=>false,'style'=>'width:100%;height:500px','row' => 160, 'type' => 'textarea', 'class' => "wysiwyg $editor"), $options);

		if(count(explode('.', $field)) >= 2 AND strpos(explode('.', $field)[0], '_lang') === false){
			$model = false;
		}elseif (empty($model)){
			$model = $this->getModelAlias($field);
		}


		$fieldName = $field;
		if (count(explode('.', $field)) <= 1 OR strpos(explode('.', $field)[0], '_lang') !== false) {
			$fieldName = $model.'.'.$field;
		}

		$html = $this->Form->input($fieldName, $options);

		if(isset($this->request->data[$model]['id']) && !$this->explorer){

			$queryEncrypt = Op::compress(array(
				'ref' => $model, 
				'ref_id' => $this->request->data[$model]['id'], 
				'session_id' => CakeSession::id(),
			));

			$html .= '<input type="hidden" id="explorer" value="' . $this->Html->url("/media/medias/index?tk=".$queryEncrypt) . '">';
			$this->explorer = true;
  	}
  	if (!$this->editorcss) {
  		$html .= '<input type="hidden" id="editorcss" value="'. $this->Html->url('/media/css/editor.css') . '"/>';
  	}

	$html .= '<div style=" margin-bottom: 1em; "></div>';


    return $html;
	}

	public function iframe($ref,$ref_id, $options = array()) {

		extract($options);
		if (empty($height)) {
			$height = 600;
		}

		if (!$ref_id) {
			if (empty($alias)) {
				$alias = $ref;
			}
			
			$ref_id = $mediaTmp = Op::mediaTmp();
			echo $this->Form->hidden($alias.'.media_tmp', array('default' => $mediaTmp));
		}

		$queryEncrypt = Op::compress(array(
			'ref' => $ref, 
			'ref_id' => $ref_id, 
			'session_id' => CakeSession::id(),
		));

		return '<iframe src="' . $this->Html->url("/media/medias/index?tk=".$queryEncrypt) . '" style="width:100%;height:'.$height.'px;" id="medias-' . $ref . '-' . $ref_id . '"></iframe>';
	}
}
