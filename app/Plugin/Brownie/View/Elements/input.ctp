<?php

		$key_field = $key;

		if (in_array($key.'_lang', array_keys($fields))) {
			return false;
		}

		$params = array();
		if (@$disabled) {
			$params['disabled'] = true;
		}


		if (isset($related['belongsTo'][$key]) AND $key != $primaryKey) {
			$params = array('type' => 'select', 'options' => $related['belongsTo'][$key], 'escape' => false);

			if ($schema[$key]['null']) {
				$params['empty'] = '-';
			}
		} elseif (isset($related['tree'][$key])) {
			if (!empty($related['tree'][$key])) {
				$params = array(
					'type' => 'select',
					'options' => $related['tree'][$key],
					'empty' => __d('brownie', '(No parent)'),
				);
				if (!empty($this->params['named'][$key])) {
					$params['selected'] = $this->params['named'][$key];
				}
			} else {
				return false;
			}
		}
		if (in_array($value['type'], array('datetime', 'date'))) {
			$params['minYear'] = $brwConfig['fields']['date_ranges'][$key]['minYear'];
			$params['maxYear'] = $brwConfig['fields']['date_ranges'][$key]['maxYear'];
			$params['dateFormat'] = $brwConfig['fields']['date_ranges'][$key]['dateFormat'];
			$params['timeFormat'] = $brwConfig['fields']['date_ranges'][$key]['timeFormat'];
			if ($value['null']) {
				$params['empty'] = '-';
			}
		}

		if (in_array($value['type'], array('time'))) {
			$params['timeFormat'] = $brwConfig['fields']['date_ranges'][$key]['timeFormat'];
			if ($value['null']) {
				$params['empty'] = '-';
			}
		}

		if (!empty($brwConfig['fields']['legends'][$key])) {
			$params['after'] = $brwConfig['fields']['legends'][$key];
		}

		if(count(explode('.', $key)) > 1){
			//debug($field);
			//debug($key);
			//debug($schema[$field]);

		    $key_field = substr($field, 0, mb_strlen($field) - mb_strlen('_lang'));
			//exit();
		    if (mb_strlen($key_field) === strpos($field, '_lang')) {

		    }

			$schema[$key] = $schema[$key_field];
			$brwConfig['fields']['names'][$key] = $brwConfig['fields']['names'][$key_field];
			$fields[$key] = $fields[$key_field];

		}


		//Champ enum
		if ($key_field == 'ref' AND stripos($fields[$key_field]['type'], 'string') === 0) {

			$params = array(
				'type' => 'select',
				'options' => $refList,
				'default' => $refListDefault,
				'empty' => false,
			);

		} elseif (strstr($key_field, 'password')) {
			$params['type'] = 'password';
		} elseif (
			(empty($schema[$key_field]['key']) or $key_field != $primaryKey)
			and !$schema[$key_field]['isForeignKey']
			and (in_array($schema[$key_field]['type'], array('biginteger','string', 'integer', 'float')))
			and empty($related['tree'][$key_field])
		) {
			$params['type'] = 'text';
		} elseif (!empty($schema[$key_field]['key']) AND $key_field == $primaryKey) {

			if ($adding) {
				if (empty($no_modified_primaryKey)) echo $this->Form->input($model . '.' . $key_field, array('placeholder' => 'Laissez-vide', 'type' => 'text','div' => false, 'style' => 'width:'.$schema[$key_field]['length'].'em'));
			}elseif (!$adding){
				echo $this->Form->input($model . '.' . $key_field, array('disabled' => 'disabled', 'type' => 'text','div' => false, 'style' => 'width:'.$schema[$key_field]['length'].'em'));
				if (!$schema[$key_field]['isForeignKey'] AND empty($no_modified_primaryKey)) echo $this->Html->link('Modifier', array('controller' => 'contents', 'action' => 'edit_id',$model, $this->request->data[$model][$key_field]), array('disabled' => 'disabled', 'type' => 'text', 'style' => 'width:80%'));
			}		
		}

		if (isset($related['hasOne'][$key_field])) {
			$params = array('type' => 'select', 'options' => $related['hasOne'][$key_field], 'escape' => false);

			if ($schema[$key_field]['null']) {
				$params['empty'] = '-';
			}
		}


		$params['div'] = array('id' => 'brw' . $model . Inflector::camelize($key_field));
		$params['label'] = __($brwConfig['fields']['names'][$key_field]);
		if (!empty($lang)) {
			$params['label'] = $params['label'] . ' <span class="lang '. $lang . '">' . $this->i18n->humanize($lang) . '</span>';
		}

		if (in_array($key_field, $fckFields)) {
			$params['class'] = 'richEditor';
		}

		//Champ enum
		if (stripos($fields[$key_field]['type'], 'enum') === 0) {

	    preg_match_all("/'(.*?)'/", $value['type'], $options);
	    $options = Hash::combine($options[1], '{n}', '{n}');
	      	
			$params = array(
				'type' => 'select',
				'options' => $options,
				'empty' => false,
			);
		}

		if (stripos($fields[$key_field]['type'], 'set') === 0) {

		if (!is_array($this->request->data[$model][$key_field])) $this->request->data[$model][$key_field] = Hash::combine(explode(',', $this->request->data[$model][$key_field]), '{n}', '{n}');

	    preg_match_all("/'(.*?)'/", $value['type'], $options);
	    $options = Hash::combine($options[1], '{n}', '{n}');
	      	
			$params = array(
				'multiple' => 'checkbox',
				'options' => $options,
				'empty' => false,
			);

			$value['type'] = 'set';
		}


		if (isset($validate[$key_field]) AND !$adding) {
			$readonly = $this->Brownie->recursive_array_search('readonly', $validate[$key_field]); // $key_field = 2;
			if ($readonly AND !$adding){
				$params['options'] =  array($this->request->data[$model][$key_field] => $params['options'][$this->request->data[$model][$key_field]]);
				$params = array_merge($params, array('empty' => false,'readonly' => 'readonly', 'style' => '   background: #eee; cursor:no-drop;', 'value' => $this->request->data[$model][$key_field]));
			}
		}

		if (@$disabled) {
			$params['disabled'] = true;
		}
		
		//debug($key_field);
		//debug($this->request->data[$model][$key_field]);
		//debug($params);
		//if (empty($this->request->data[$model][$key_field])) $this->request->data[$model][$key] = null;


		if ($key == 'media_id') {
			if (!empty($this->request->data[$model][$primaryKey])) {
				echo $this->Media->iframe($model, $this->request->data[$model][$primaryKey]);
			}
		}elseif ($key == 'lien') {
			echo $this->Form->hidden($model . '.' . $key);
		}elseif (!empty($Upload) AND in_array($key, array_keys($Upload))) {

			echo $this->Form->input($model . '.' . $key.'_file', array('type' => 'file'));
			if (!empty($this->request->data[$model][$key]) AND $this->request->data[$model][$key] != $schema[$key]['default']) {
				echo $this->Html->link('Ouvrir le document actuelle', $this->request->data[$model][$key], array('class' => 'button', 'target' => '_blank', 'style' => 'margin-right:15px'));


				echo '<span class="delete" style="list-style: none; display: inline;">';
				echo $this->Html->link(__('Supprimer'), array(
					'controller' => 'contents',
					'action' => 'delete_file', $model, $key, $this->request->data[$model][$primaryKey]),
					 array(
					'title' => 'Supprimer',
					'style' => 'background-repeat: no-repeat; padding-left: 20px;'

				), __( 'Êtes-vous sûr de que vouloir supprimer ce piece jointe ?'));
				echo '</span>';

			}
		}elseif (in_array($key, $i18nFields) OR ($key_field = substr($key, 0, mb_strlen($key) - mb_strlen('_lang')) AND mb_strlen($key_field) === strpos($key, '_lang'))) {
			echo $this->element('i18n_input', array('model' => $model, 'field' => $key, 'params' => $params, 'value' => $value, 'adding' => $adding));
		}elseif ($value['type'] == 'text' AND !isset($value['collate']) AND !isset($value['charset']) AND $value['length'] == 4) {
			
			$this->request->data[$model][$key] = array('year' => $this->request->data[$model][$key]);
			echo $this->Form->label($model . '.' . $key, $params['label']);
			echo $this->Form->year($model . '.' . $key, date('Y')-120, date('Y')+15);

		}elseif ($value['type'] == 'time' AND strstr($key, 'duree')) {


		echo '<div>';
			echo $this->Form->label($model . '.' . $key);

			echo $this->Form->hour($model . '.' . $key, true, array('div' => false,'label' => false,'empty' => false));
			echo ' - '.$this->Form->minute($model . '.' . $key, array('div' => false,'label' => false,'empty' => false)).' - ';

			if (!is_array($this->request->data[$model][$key])) $defaultTime =  $this->Time->format($this->request->data[$model][$key], '%S');
				else $defaultTime = $this->request->data[$model][$key]['sec'];

			echo $this->Form->input($model . '.' . $key.'.sec', array('default' => $defaultTime,'div' => false,'label' => false,'type' => 'select',
			    'options' => array_combine(range(0,59), range(0,59)),
			));
		echo '</div>';


		} elseif (isset($this->request->data[$model][$key]) AND is_array($this->request->data[$model][$key]) AND !in_array($value['type'], array('datetime', 'date','set'))) {
			$this->Brownie->arraycontent($this->request->data[$model][$key], $model, $key, $params);
		}elseif ((isset($fields[$key]['columns']) AND in_array($fields[$key]['columns'], array('mediumtext','text'))) OR (isset($fields[$key]['type']) AND in_array($fields[$key]['type'], array('mediumtext','text')))) {
			// Redacteur principale (ckeditor,tinymce et redactor)
			
			//debug($params);
    		if ($fields[$key]['columns'] == 'text') {
    			$redacteur = 'ckeditor';
    		}else{
				unset($params['class']);
				$redacteur = 'ckeditor';
    		}

			echo $this->Media->$redacteur($key, $params, $model);

		}elseif (!strstr($key, '_count') 
			AND (!in_array($key, $i18nFields) AND (empty($this->request->data[$model][$key]) OR !is_array($this->request->data[$model][$key]))) OR in_array($value['type'], array('datetime', 'date','set'))) {
			echo $this->Form->input($model . '.' . $key, $params);
		}
