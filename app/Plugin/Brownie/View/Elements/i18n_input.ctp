<?php
foreach ($langs3chars as $lang) {
	$label = str_replace(' Lang', '', $params['label']) . ' <span class="lang '. $lang . '">' . $this->i18n->humanize($lang) . '</span>';
		if(isset($schema[str_replace('_lang','', $field)])){$type = $schema[str_replace('_lang','', $field)]['type'] == 'text' ? 'textarea' : 'text';}
		else{$type = $schema[$field]['type'] == 'text' ? 'textarea' : 'text';}

	if (strstr($field, 'contenu')) {
		if (Configure::read('redacteur')) {$redacteur = Configure::read('redacteur');}else{$redacteur = 'ckeditor';}
			echo $this->Media->$redacteur($field. '.' .$lang, array('label'=> $label), $model);
	}else{

		//debug($this->request->data[$model][$field][$lang] );

		//debug($fields);
		//exit();
		echo $this->element('input', array('fields' => $fields, 'key' => $field.'.'.$lang, 'field' => $field, 'value' => $value, 'related' => $related, 'adding' => $adding, 'lang' => $lang));

		/*
			if (strstr($field, 'note')) {$type = 'textarea';}
			//else{$type = 'text';}
			echo $this->Form->input(
				$model . '.' . $field . '.' . $lang,
				array_merge($params, array('label' => $label, 'type' => $type))
			);
		*/
	}
}