<?php

class Content extends BrownieAppModel {

	public $useTable = false;
	public $brwConfig = array();


	public function modelExists($model) {
		if ($model == 'BrwUser') {
			return true;
		}
		return in_array($model, App::objects('model'));
	}


	public function getForeignKeys($Model) {
		$out = array();
		if (!empty($Model->belongsTo)) {
			foreach ($Model->belongsTo as $alias => $assocModel) {
				$out[$assocModel['foreignKey']] = array(
					'alias' => $alias,
					'className' => $assocModel['className'],
				);
			}
		}
		return $out;
	}


	public function fieldsAdd($Model) {
		return $this->_fieldsForForm($Model, 'add');
	}


	public function fieldsEdit($Model) {
		return $this->_fieldsForForm($Model, 'edit');
	}


	public function _fieldsForForm($Model, $action) {
		$schema = $Model->brwSchema();
		$fieldsConfig = $Model->brwConfig['fields'];
		$fieldsNotUsed = array_merge(array('created', 'modified', 'updated'), $fieldsConfig['hide']);
		//$fieldsNotUsed = array_merge(array('created', 'modified', 'updated'), $fieldsConfig['no_' . $action], $fieldsConfig['hide']);
		if (!empty(array_flip($fieldsNotUsed)[$this->primaryKey])) unset($fieldsNotUsed[array_flip($fieldsNotUsed)[$this->primaryKey]]);
		foreach ($fieldsNotUsed as $field) {
			if (isset($schema[$field])) {
				unset($schema[$field]);
			}
		}
		return $schema;
	}


	public function addValidationsRules($Model, $edit) {
		if ($edit) {
			$fields = $this->fieldsEdit($Model);
		} else {
			$fields = $this->fieldsAdd($Model);
			if (isset($fields['id'])) {
				unset($fields['id']);
			}
		}
		$rules = $fields;
		foreach ($fields as $key => $value) {
			$rules[$key] = array();

			if (!$value['null'] AND !isset($value['default'])) {
				$allowEmpty = false;
				$rules[$key][] = array(
					'rule' => 'notBlank',
					'allowEmpty' => $allowEmpty,
					'message' => __d('brownie', 'This field is required')
				);
			} else {
				$allowEmpty = true;
			}


			if (!empty($value['length']) AND stripos($fields[$key]['type'], 'set') === false AND stripos($fields[$key]['type'], 'enum') === false) {

				$rules[$key][] = array(
					'rule' => array('maxLength', $value['length']),
					'allowEmpty' => $allowEmpty,
					'message' => __d('brownie', 'You can\'t write over '.$value['length'].' character')
				);
			}

			if (in_array($value['type'], array('integer','biginteger','float'))) {
				if ( !($Model->Behaviors->attached('Tree') and $key =='parent_id') ) {
					$rules[$key][] = array(
						'rule' => 'numeric',
						'allowEmpty' => $allowEmpty,
						'message' => __d('brownie', 'Please supply  a valid number')
					);
				}
			}

			if ($key == 'email' or strstr($key, '_email')) {
				$rules[$key][] = array(
					'rule' => 'email',
					'allowEmpty' => $allowEmpty,
					'message' => __d('brownie', 'Please supply a valid email address')
				);
			}

			if ($value['type'] == 'boolean') {
				$rules[$key][] = array(
					'rule' => 'boolean',
					'message' => __d('brownie', 'Incorrect value')
				);
			}

			if ($this->fieldUnique($Model, $key)) {
				$rules[$key][] = array(
					'rule' => 'isUnique',
					'message' => sprintf(
						($Model->brwConfig['names']['gender'] == 1) ?
							__d('brownie', "This value must be unique and it's already in use by another %s [male]"):
							__d('brownie', "This value must be unique and it's already in use by another %s [female]"),
						__($Model->brwConfig['names']['singular'])
					),
					'allowEmpty' => true,
				);
			}

		}

		if (!empty($Model->validate)) {
			$Model->validate = array_merge($rules, $Model->validate);
		} else {
			$Model->validate = $rules;
		}
	}


	public function brownieBeforeSave($data, $Model, $Session) {
		$data['Content']['fieldList'] = array();
		foreach ($Model->schema() as $field => $value) {
			if (
				$value['null']
				and !empty($data[$Model->name][$field])
				and $data[$Model->name][$field] === ''
				and !in_array($field, $Model->brwConfig['fields']['hide'])
			) {
				$data[$Model->name][$field] = null;
			}
		}
		if ($Model->Behaviors->attached('Tree')) {
			$data = $this->treeBeforeSave($data, $Model);
		}
		if ($Model->Behaviors->attached('Translate')) {
			$data = $this->translateBeforeSave($data, $Model);
		}
		$data = $this->ownedBeforeSave($data, $Model, AuthComponent::user('id'));
		$data = $this->convertUploadsArray($data);
		return $data;
	}


	public function treeBeforeSave($data, $Model) {
		if (!empty($data[$Model->name]['parent_id_NULL']) and $data[$Model->name]['parent_id_NULL']) {
			$data[$Model->name]['parent_id'] = NULL;
		}
		if (array_key_exists('lft', $data[$Model->name])) {
			unset($data[$Model->name]['lft']);
		}
		if (array_key_exists('rght', $data[$Model->name])) {
			unset($data[$Model->name]['rght']);
		}
		return $data;
	}


	/**
	* This method is to solve a cake issue with saveAll and languages
	*/
	public function makeSave($Model, $data, $fields) {
		foreach ($data[$Model->alias] as $key => $value) {
			if (!empty($fields[$key]['type'])) {				
				if (stripos($fields[$key]['type'], 'set') === 0 AND is_array($value)) {
					$data[$Model->alias][$key] = implode(',', $value);
				}

				if ($fields[$key]['type'] == 'text' AND !isset($fields[$key]['collate']) AND !isset($fields[$key]['charset']) AND $fields[$key]['length'] == 4) {
					$data[$Model->alias][$key] = (array_values($value)[0] ? array_values($value)[0] : null) ;
				}
			}


			if (!empty($fields[$key]['null']) AND empty($data[$Model->alias][$key]) AND $data[$Model->alias][$key] !== '0' AND is_string($data[$Model->alias][$key])) $data[$Model->alias][$key] = null;
		}

		$fieldList = array_merge(
			array_keys($fields),
			array('name', 'model', 'category_code', 'description', 'record_id'),
			$data['Content']['fieldList'],
			array_diff_assoc(array_diff(array_keys($Model->validate), $Model->brwConfig['fields']['no_fieldList']), array_keys($fields)),
			array_keys($Model->hasAndBelongsToMany)
		);

		$fieldList = array_diff($fieldList, $Model->brwConfig['fields']['no_fieldList']);

		if ($Model->brwConfig['sortable']) {
			$fieldList[] = $Model->brwConfig['sortable']['field'];
		}

		if (!empty($Model->UploadFieldList)) {
			
			foreach ($Model->UploadFieldList as $upload_key => $path) {
				$fieldList[] = $upload_key.'_file';
			}
		
		}

		return $Model->saveAll($data, array('fieldList' => $fieldList, 'validate' => 'all', 'deep' => true));
	}


	public function translateBeforeSave($data, $Model) {
		/*
		foreach (Configure::read('Config.languages') as $lang) {
			if (empty($data['Content']['enabled_' . $lang])) {
				$translatableFields = array_keys($Model->Behaviors->Translate->settings[$Model->alias]);
				foreach ($translatableFields as $field) {
					if (isset($data[$Model->alias][$field][$lang])) {
						unset($data[$Model->alias][$field][$lang]);
					}
				}
			}
		}
		pr($data);
		*/
		return $data;
	}


	public function ownedBeforeSave($data, $Model, $authUserId) {
		$authModel = AuthComponent::user('model');
		if (
			$authModel
			and $authModel != 'BrwUser'
			and $Model->name != $authModel
			and $Model->brwConfigPerAuthUser[$authModel]['type'] == 'owned'
		) {
			$data['Content']['fieldList'][] = $fk = $Model->belongsTo[$authModel]['foreignKey'];
			$data[$Model->name][$fk] = $authUserId;
		}
		return $data;
	}


	public function fckFields($Model) {
		$out = array();
		foreach ($Model->schema() as $field => $metadata) {
			if ($metadata['type'] == 'text' and !in_array($field, $Model->brwConfig['fields']['no_editor'])) {
				$out[] = $field;
			}
		}
		return $out;
	}


	public function defaults($Model) {
		$data = array();
		foreach ($Model->schema() as $field => $value) {
			if (array_key_exists($field, $Model->brwConfig['default'])) {
				$data[$field] = $Model->brwConfig['default'][$field];
			} elseif (!empty($value['default'])) {
				 $data[$field] = $value['default'];
			}
		}
		return array($Model->alias => $data);
	}


	public function remove($Model, $id) {
		if ($Model->Behaviors->attached('Tree')) {
			$deleted = $Model->removeFromTree($id, true);
		} else {
			$deleted = $Model->delete($id);
		}
		return $deleted;
	}


	public function fieldUnique($Model, $field) {
		$indexes = $Model->getDataSource()->index($Model->table);
		foreach ($indexes as $index) {
			if ($index['column'] == $field and !empty($index['unique'])) {
				return true;
			}
		}
		return false;
	}


	public function reorder($Model, $direction, $id) {
		if ($Model->Behaviors->attached('Tree')) {
			return ($direction == 'down') ? $Model->moveDown($id, 1) : $Model->moveUp($id, 1);
		}

		$isTanslatable = $Model->Behaviors->enabled('Translate');
		if ($isTanslatable) {
			$Model->Behaviors->disable('Translate');
		}

		$sortField = $Model->brwConfig['sortable']['field'];
		$record = $Model->findById($id);
		$params = array('field' => $sortField, 'value' => $record[$Model->alias][$sortField]);
		if ($parent = $Model->brwConfig['parent']) {
			$foreignKey = $Model->belongsTo[$parent]['foreignKey'];
			$params['conditions'] = array($Model->alias . '.' . $foreignKey => $record[$Model->alias][$foreignKey]);
		}
		$neighbors = $Model->find('neighbors', $params);
		if ($Model->brwConfig['sortable']['direction'] == 'desc') {
			$prev = 'prev'; $next = 'next';
		} else {
			$prev = 'next'; $next = 'prev';
		}
		$cual = ($direction == 'down')? $prev:$next;
		if (empty($neighbors[$cual])) {
			return false;
		}
		$swap = $neighbors[$cual];
		$saved1 = $Model->save(array('id' => $record[$Model->alias]['id'], $sortField => null));
		$saved2 = $Model->save(array('id' => $swap[$Model->alias]['id'], $sortField => $record[$Model->alias][$sortField]));
		$saved3 = $Model->save(array('id' => $record[$Model->alias]['id'], $sortField => $swap[$Model->alias][$sortField]));

		if ($isTanslatable) {
			$Model->Behaviors->enable('Translate');
		}

		return ($saved1 and $saved2 and $saved3);
	}


	public function schemaForView($Model) {
		$schema = $Model->brwSchema();
		foreach ($schema as $field => $extra) {
			$isForeignKey = $this->brwIsForeignKey($Model, $field);
			$schema[$field]['isForeignKey'] = $isForeignKey;
			switch ($extra['type']) {
				case 'float':
					$class = 'number';
				break;
				case 'integer':
					$class = ($isForeignKey) ? 'string' : 'number';
				break;
				case 'date': case 'datetime':
					$class = 'date';
				break;
				default:
					$class = $extra['type'];
				break;
			}
			$schema[$field]['class'] = $class;
		}
		return $schema;
	}


	public function brwIsForeignKey($Model, $field) {
		foreach ($Model->belongsTo as $model => $belongsTo) {
			if ($belongsTo['foreignKey'] == $field) {
				return $model;
			}
		}
		return false;
	}


	public function actions($Model, $record, $permissions) {
		$actions = $actionsTitles = array();
		$defaultAction = array(
			'title' => false,
			'url' => array(),
			'target' => '_self',
			'options' => array(),
			'confirmMessage' => false,
		);
		$actionsTitles = array_merge($actionsTitles, array(
			'add' => __d('brownie', $Model->brwConfig['action_labels']['add']),
			'view' => __d('brownie', $Model->brwConfig['action_labels']['view']),
			'edit' => __d('brownie', $Model->brwConfig['action_labels']['edit']),
			'delete' => __d('brownie', $Model->brwConfig['action_labels']['delete']),
			'index' => __d('brownie', $Model->brwConfig['action_labels']['index']),
		));
		foreach ($actionsTitles as $action => $title) {
			if (!empty($permissions[$action]) or in_array($action, array('up', 'down'))) {
				$url = array(
					'controller' => 'contents',
					'action' => ($action == 'add') ? 'edit' : $action,
					$Model->alias
				);
				$options = array('title' => $title);
				if (!in_array($action, array('index', 'add'))) {
					$url[] = $record[$Model->alias][$Model->primaryKey];
				}
				$actions[$action] = Set::merge($defaultAction, array(
					'title' => $title,
					'url' => $url,
					'options' => $options,
					'confirmMessage' => ($action == 'delete') ?
						sprintf(
							($Model->brwConfig['names']['gender'] == 1) ?
								__d('brownie', 'Are you sure you want to delete this %s?[male]'):
								__d('brownie', 'Are you sure you want to delete this %s?[female]')
							,
							__($Model->brwConfig['names']['singular'])
						):
						false,
					'class' => $action,
				));
			}
		}
		foreach ($Model->brwConfig['custom_actions'] as $action => $custom) {
			$matchCondition = true;
			if (!empty($custom['conditions'])) {
				$matchCondition = call_user_func(array($Model, $custom['conditions']), $record);
			}
			if ($matchCondition) {
				$custom['url'][] = $record[$Model->alias]['id'];
				$actions[$action] = Set::merge($defaultAction, $custom);
			}
		}
		return $actions;
	}


	public function convertUploadsArray($data) {
		foreach (array('BrwImage', 'BrwFile') as $upload) {
			if (!empty($data[$upload]['model'])) {
				$retData = array();
				$fields = array('model', 'category_code', 'description', 'file');
				$count = count($data[$upload]['model']);
				foreach ($fields as $field) {
					for ($i = 0; $i < $count; $i++) {
						$retData[$upload][$i][$field] = $data[$upload][$field][$i];
					}
				}
				$data[$upload] = $retData[$upload];
				foreach ($data[$upload] as $key => $value) {
					if (empty($value['file']) or (!empty($value['file']['error']))) {
						unset($data[$upload][$key]);
					}
				}
				if (empty($data[$upload])) {
					unset($data[$upload]);
				}
			}
		}
		return $data;
	}


	public function findList($Model, $relationData) {
		if (isset($Model->brwConfig['parent'])) $parent = $Model->brwConfig['parent'];
		$displayField = $Model->displayField;
		if (empty($parent)) {
			return $Model->find('list', $relationData);
		} else {
			$parentDisplayField = $Model->{$parent}->displayField;
			$ret = array();
			$data = $Model->{$parent}->find('all', array('contain' => $Model->name));
			foreach ($data as $parentModel => $entry) {
				foreach ($entry[$Model->name] as $value) {
					$ret[$entry[$parent][$parentDisplayField]][$value['id']] = $value[$displayField];
				}
			}
			return $ret;
		}
	}


	public function neighborsForView($Model, $record, $restricted, $named = array()) {
		$neighbors = array();
		$isTanslatable = $Model->Behaviors->enabled('Translate');
		if ($isTanslatable) {
			$Model->Behaviors->disable('Translate');
		}
		if (!$restricted) {
			if (!empty($named['sort']) and in_array($named['sort'], array_keys($Model->schema()))) {
				$sort_field = $named['sort'];
				$direction = 'asc';
				if (!empty($named['direction']) and in_array($named['direction'], array('desc', 'asc'))) {
					$direction = $named['direction'];
				}
			} elseif (is_array($Model->order)) {
				list($sort_field, $direction) = each($Model->order);
			} else {
				$sort_field = 'id';
				$direction = 'asc';
			}
			$sort_field = str_replace($Model->alias . '.', '', $sort_field);
			$schema = $Model->schema();
			if (
				!empty($schema[$sort_field]['key']) and
				in_array($schema[$sort_field]['key'], array('unique', 'primary'))
			) {
				$neighbors = $Model->find('neighbors', array(
					'field' => $sort_field,
					'value' => $record[$Model->alias][$sort_field],
					'conditions' => $this->filterConditions($Model, $named)
				));
			} else {
				//ToDo: custom neighborgs function for non-unique fields
			}
			if ($neighbors and $direction == 'desc') {
				$tmp = $neighbors['prev'];
				$neighbors['prev'] = $neighbors['next'];
				$neighbors['next'] = $tmp;
			}
		}
		if ($isTanslatable) {
			$Model->Behaviors->enable('Translate');
		}
		return $neighbors;
	}


	public function i18nInit($Model) {
		if ($Model->Behaviors->attached('Translate')) {
			$i18nSettings = $Model->Behaviors->Translate->settings[$Model->alias];
			$settings = array();
			foreach ($i18nSettings as $key => $value) {
				$field = is_string($key)? $key : $value;
				$settings[$field] = 'BrwI18n_' . $field;
			}
			$Model->Behaviors->detach('Translate');
			$Model->Behaviors->attach('Translate', $settings);
		}
	}


	public function addI18nValues($record, $Model) {
		if ($Model->Behaviors->attached('Translate')) {
			$translated = $Model->find('first', array(
				'conditions' => array($Model->alias . '.id' => $record[$Model->alias]['id']),
				'contain' => array_values($Model->Behaviors->Translate->settings[$Model->alias]),
			));
			unset($translated[$Model->alias]);
			$record = array_merge($record, $translated);
		}
		return $record;
	}


	public function i18nForEdit($data, $Model) {
		if ($Model->Behaviors->attached('Translate')) {
			$dataWithTranslations = $this->addI18nValues($data, $Model);
			$settings = $Model->Behaviors->Translate->settings[$Model->alias];
			foreach ($settings as $field => $i18nModelName) {
				$dataWithTranslations[$Model->alias][$field] = array();
				foreach($dataWithTranslations[$i18nModelName] as $value) {
					$dataWithTranslations[$Model->alias][$field][$value['locale']] = $value['content'];
				}
				unset($dataWithTranslations[$i18nModelName]);
			}
			$data = array_merge($data, $dataWithTranslations);
		}
		return $data;
	}


	public function relatedModelsForView($Model) {
		$rets = array_keys(array_merge($Model->hasAndBelongsToMany, $Model->belongsTo));
		if ($Model->brwConfig['images']) {
			$ret['BrwImage'] = array('fields' => '*', 'order' => 'BrwImage.id asc');
		}
		if ($Model->brwConfig['files']) {
			$ret['BrwFile'] = array('fields' => '*', 'order' => 'BrwFile.id asc');
		}

		foreach ($rets as $ret) {
			if($Model->{$ret}->primaryKeyArray){

				foreach ($Model->{$ret}->primaryKeyArray as $key => $primaryKeyArrayForeignKey) {
					$a = $this->brwIsForeignKey($Model->{$ret}, $primaryKeyArrayForeignKey);
					if ($a) {
						$rets[$ret][] = $a;
					}
				}

			}

		}

		return $rets;
	}


	public function relatedModelsForIndex($Model, $paginate) {
		$containedForIndex = array();
		foreach ($paginate['fields'] as $field) {
			$relModel = $this->brwIsForeignKey($Model, $field);
			if ($relModel) {
				$containedForIndex[] = $relModel;
			}
		}
		if (!empty($paginate['images'])) {
			$containedForIndex[] = 'BrwImage';
		}
		$containedModels = Set::normalize($this->relatedModelsForView($Model));
		foreach ($containedModels as $containedModel => $fields) {
							//debug($Model->{$containedModel}->virtualFields);

			if (
				!in_array($containedModel, $containedForIndex)
				and $containedModel != AuthComponent::user('model')
			) {
				unset($containedModels[$containedModel]);
			} else {
				if (in_array($containedModel, array('BrwImage', 'BrwFile'))) {
					$containedModels[$containedModel]['fields'] = '*';
				} else {
					if (in_array($Model->{$containedModel}->displayField, array_keys($Model->{$containedModel}->virtualFields))) {
						$containedModels[$containedModel]['fields'] = array($Model->{$containedModel}->primaryKey, $Model->{$containedModel}->virtualFields[$Model->{$containedModel}->displayField].'AS '.$containedModel.'__'.$Model->{$containedModel}->displayField);
					}else{
						$containedModels[$containedModel]['fields'] = array($Model->{$containedModel}->primaryKey, $Model->{$containedModel}->displayField);
					}
				}
			}
		}
		return $containedModels;
	}


	public function formatHABTMforView($record, $Model) {
		$record['HABTM'] = array();
		$i = 0;
		foreach ($Model->hasAndBelongsToMany as $relModel => $settings) {
			$record['HABTM'][$i] = array(
				'model' => $settings['className'],
				//'name' => $Model->{$relModel}->brwConfig['names']['plural'],
				'name' => $relModel,
				'data' => array(),
			);
			foreach ($record[$relModel] as $value) {
				$record['HABTM'][$i]['data'][$value['id']] = $value[$Model->{$relModel}->displayField];
			}
			unset($record[$relModel]);
			$i++;
		}
		return $record;
	}


	public function getForExport($Model, $named) {
		if (!empty($named['direction']) and !empty($named['sort'])) {
			$order = array($Model->alias . '.' . $named['sort'] => $named['direction']);
		} else {
			$order = $Model->order;
		}
		$params = array(
			'conditions' => $this->filterConditions($Model, $named),
			'order' => $order,
			'contain' => array_keys($Model->belongsTo),
		);
		$records = $Model->find('all', $params);
		if ($Model->brwConfig['export']['replace_foreign_keys']) {
			foreach ($records as $i => $record) {
				foreach ($record[$Model->alias] as $field => $value) {
					$relModel = $this->brwIsForeignKey($Model, $field);
					if ($relModel) {
						$displayField = $Model->{$relModel}->displayField;

						if (!empty($records[$i][$relModel])) {
							$records[$i][$Model->alias][$field] = $records[$i][$relModel][$displayField];
						}else{
							//$records[$i][$Model->alias][$field] = 'Ref. non disponible';
						}


					}
				}
			}
		}
		return $records;
	}


	public function filterConditions($Model, $named, $forData = false) {
		$filter = array();
		foreach ($Model->schema() as $field => $value) {
			$keyNamed = $Model->alias . '.' . $field;
			$isRange = (!$forData and (
				in_array($value['type'], array('datetime', 'date', 'float'))
				or (
					$value['type'] == 'integer'
					and !$this->brwIsForeignKey($Model, $field)
					and empty($Model->brwConfig['fields']['filter'][$field])
				)
			));
			if ($isRange) {
				if (array_key_exists($keyNamed . '_from', $named)) {
					$filter[$keyNamed . ' >= '] = $named[$keyNamed . '_from'];
				}
				if (array_key_exists($keyNamed . '_to', $named)) {
					$filter[$keyNamed . ' <= '] = $named[$keyNamed . '_to'];
				}
			} else {
				if (array_key_exists($keyNamed, $named)) {
					if (in_array($value['type'], array('integer', 'boolean', 'date', 'datetime'))) {
						if (strstr($named[$keyNamed], '.')) {
							$named[$keyNamed] = explode('.', $named[$keyNamed]);
						}
						if ($forData) {
							$filter[$Model->alias][$field] = $named[$keyNamed];
						} else {
							$filter[$keyNamed] = $named[$keyNamed];
						}
					} else {
						if ($forData) {
							$filter[$Model->alias][$field] =  $named[$keyNamed];
						} else {
							$filter[$keyNamed . ' like'] = '%' . $named[$keyNamed] . '%';
						}
					}
				}
			}
		}
		foreach ($Model->hasAndBelongsToMany as $related) {
			if (!empty($named[$related['className']])) {
				$values = explode('.', $named[$related['className']]);
				if ($forData) {
					$filter[$related['className']][$related['className']] = $values;
				} else {
					$relatedIds = $Model->{$related['with']}->find('all', array('conditions' => array(
						$related['with'] . '.' . $related['associationForeignKey'] => $values
					)));
					$xpath = '{n}.' . $related['with'] . '.' . $related['foreignKey'];
					$ids = Set::extract($xpath, $relatedIds);
					if (empty($filter[$Model->alias . '.id'])) {
						$filter[$Model->alias . '.id'] = $ids;
					} else {
						$filter[$Model->alias . '.id'] = array_diff($filter[$Model->alias . '.id'], $ids);
					}

				}
			}
		}
		if (!$forData and method_exists($Model, 'brwFilter')) {
			$filter = $Model->brwFilter($filter, $named);
		}
		return $filter;
	}


	public function getRelatedBrwConfig($Model) {
		$brwConfigs = array();
		$models = array_merge($Model->hasAndBelongsToMany, $Model->belongsTo, $Model->hasMany, $Model->hasOne);
		foreach ($models as $model => $config) {
			$brwConfigs[$model] = $Model->{$model}->brwConfig;
		}
		return $brwConfigs;
	}


	public function dateComplete($data, $fromOrTo, $type) {
		if (!in_array($fromOrTo, array('_from', '_to')) or empty($data['year'])) {
			return false;
		}
		if ($fromOrTo == '_from') {
			if (empty($data['month'])) {
				$data['month'] = '01';
			}
			if (empty($data['day'])) {
				$data['day'] = '01';
			}
			if ($type == 'datetime') {
				if (empty($data['hour'])) {
					$data['hour'] = '00';
				}
				if (empty($data['min'])) {
					$data['min'] = '00';
				}
				$data['sec'] = '00';
			}
		} elseif ($fromOrTo == '_to') {
			if (empty($data['month'])) {
				$data['month'] = '12';
			}
			if (empty($data['day'])) {
				$data['day'] = '31';
			}
			if ($type == 'datetime') {
				if (empty($data['hour'])) {
					$data['hour'] = '23';
				}
				if (empty($data['min'])) {
					$data['min'] = '59';
				}
				$data['sec'] = '59';
			}
		}
		return $data;
	}


	public function relatedData($Model) {
		$related = array();

		if (!empty($Model->hasOne)) {
			foreach ($Model->hasOne as $key_model => $related_model) {
				$AssocModel = $Model->$key_model;
				if (!in_array($AssocModel, array('BrwImage', 'BrwFile')) AND $related_model['foreignKey'] != 'parent_id') {
					if ($AssocModel->Behaviors->enabled('Tree')) {
						$relatedData = $AssocModel->generateTreeList();
						// On generale la liste blanche pour disactiver les exedents de liste
						$whitelist = $this->findList($AssocModel, $related_model);
						foreach ($relatedData as $key => $value) {
							if (!in_array($key, array_keys($whitelist))) {
								$relatedData[$key] = array('name' => $value,'value' => false,'disabled' => true);
							}
						}

					} else {
						$relatedData = $this->findList($AssocModel, $related_model);
					}

					if (!empty($AssocModel->hasOne[$Model->alias]['foreignKey'])) {
						$related['hasOne'][$AssocModel->hasOne[$Model->alias]['foreignKey']] = $relatedData;
					}else{
						$related['hasOne'][mb_strtolower(Inflector::singularize($AssocModel->alias)).'_'.$AssocModel->primaryKey] = $relatedData;
					}

				}
			}
		}

		if (!empty($Model->belongsTo)) {
			foreach ($Model->belongsTo as $key_model => $related_model) {
				$AssocModel = $Model->$key_model;
				if (!in_array($AssocModel, array('BrwImage', 'BrwFile')) AND $related_model['foreignKey'] != 'parent_id') {
					if ($AssocModel->Behaviors->enabled('Tree')) {
						$relatedData = $AssocModel->generateTreeList();
						// On generale la liste blanche pour disactiver les exedents de liste
						$whitelist = $this->findList($AssocModel, $related_model);
						foreach ($relatedData as $key => $value) {
							if (!in_array($key, array_keys($whitelist))) {
								$relatedData[$key] = array('name' => $value,'value' => false,'disabled' => true);
							}
						}

					} else {

						if (!empty($related_model['conditions'])) {

							$related_model['joins'] = array(
						      array(
						        'table' => $Model->useTable, 'alias' => $Model->alias,
						        'type' => 'LEFT',
						        'conditions' => array(
						          '`'.$AssocModel->alias.'.'.$AssocModel->primaryKey.'` =  `'.$Model->alias.'.'.$related_model['foreignKey'].'`',
						        )
						      ),   
						    );

							unset($related_model['conditions']);
						}

						$relatedData = $this->findList($AssocModel, $related_model);
					}

					if (Hash::apply($Model->belongsTo, '{s}[foreignKey=/^'.$related_model['foreignKey'].'/]', 'count') > 1) {
						$related['belongsTo'][$related_model['foreignKey']][$AssocModel->alias] = $relatedData;
					}else{
						$related['belongsTo'][$related_model['foreignKey']] = $relatedData;
					}
				}
			}
		}

		if (!empty($Model->hasAndBelongsToMany)) {
			foreach ($Model->hasAndBelongsToMany as $key_model => $related_model) {
				if (!in_array($key_model, $Model->brwConfig['fields']['no_edit'])) {
				
					// Au cas où une condition est placé dans le Model relationnel, on vera en 1er les permissions
					$assoc_related_model = $Model->$key_model->hasAndBelongsToMany;

					//On fais un merge pour correspondre le nom de classe au nom principal pour assurer la coérance
					$assoc_related_model2 = array();
					foreach ($assoc_related_model as $key => $arm) {
						if (!empty($assoc_related_model2[$arm['className']])) continue;
						$assoc_related_model2[$arm['className']] = $arm;
						$assoc_related_model2[$arm['className']]['alias'] = $key;
					}
					$assoc_related_model = $assoc_related_model2;

					// Bug Table relationnel avec "WITH"
					$related_model_alias = $Model->{$related_model['with']}->alias;
					$related_model_table = $Model->{$related_model['with']}->useTable;
					if (!empty($related_model['dynamicWith']) AND !empty($related_model['joinTable'])) $related_model_table = $related_model['joinTable'];



					if (!empty($assoc_related_model[$Model->alias]['conditions'])) {
						// On enleve les clés referant aussi avant de verifier car elle ne sont pas concerner
						$assocrelatedCondition =  array_merge($assoc_related_model[$Model->alias]['conditions'], array($Model->alias.'.id' => $Model->id));
						unset($assocrelatedCondition['ref']); unset($assocrelatedCondition[$assoc_related_model[$Model->alias]['with'].'.ref']);

						foreach ($assocrelatedCondition as $key => $AssoCondition) {
							$assocrelatedCondition[$key] = str_replace($assoc_related_model[$Model->alias]['alias'], $assoc_related_model[$Model->alias]['className'], $AssoCondition);
						}

						if (!empty($Model->find('count', array('conditions' => $assocrelatedCondition)))) {
							$findrelated_model = $related_model;

							// On fais une joncture automatique pour eviter les erreurs de conditions avec la table associer
							$findrelated_model['joins'][] =  array(
								'alias' => $related_model_alias,
								'table' => $related_model_table,
								'type' => 'left',
								'conditions' => array('`'.$Model->$key_model->alias.'.'.$Model->$key_model->primaryKey.'` = `'.$findrelated_model['with'].'.'.$findrelated_model['foreignKey'].'`'));

							if (isset($findrelated_model['conditions']['ref'])) unset($findrelated_model['conditions']['ref']); // On enleve la reference dans les conditions de verification!Important

							if (!empty($findrelated_model['containconditions'])) {
								$findrelated_model['conditions'] = Hash::merge($findrelated_model['conditions'], $findrelated_model['containconditions']);
							}

							$related['hasAndBelongsToMany'][$key_model] = $Model->$key_model->find('list', $findrelated_model);
						}
					}else{ // On reprend la route
						$listOptionsRelated_model = $related_model;

						if (!empty($listOptionsRelated_model['conditions']) OR !empty($listOptionsRelated_model['order'])) {

							unset($listOptionsRelated_model['order']);

							$listOptionsRelated_model['joins'] = array(
								array(
									'alias' => $related_model_alias,
									'table' => $related_model_table,
									'type' => 'LEFT',
									'conditions' => '`'.$related_model_alias.'`.`'.$listOptionsRelated_model['associationForeignKey'].'` = `'.$key_model.'`.`'.$Model->$key_model->primaryKey.'`'
								)
							);

						}


						if (!empty($listOptionsRelated_model['conditions']['ref'])) unset($listOptionsRelated_model['conditions']['ref']);
						$related['hasAndBelongsToMany'][$key_model] = $Model->$key_model->find('list', $listOptionsRelated_model);

					}

					if ($Model->$key_model->Behaviors->enabled('Tree')) {
						if (!empty($related['hasAndBelongsToMany'][$key_model])) {
							$whitelist = $related['hasAndBelongsToMany'][$key_model];
						}else{
							$whitelist = array();
						}
						$related['hasAndBelongsToMany'][$key_model] = $Model->$key_model->generateTreeList();
							
						foreach ($related['hasAndBelongsToMany'][$key_model] as $key => $value) {
							if (!in_array($key, array_keys($whitelist))) {
								$related['hasAndBelongsToMany'][$key_model][$key] = array('name' => $value,'value' => false,'disabled' => true);
							}
						}
					}
				}

			}
		}

		if ($Model->Behaviors->enabled('Tree')) {
			$conditions = array();
			if ($Model->maxlevel) $conditions[] = array('level <' => $Model->maxlevel);
			$related['tree']['parent_id'] = $Model->generateTreeList($conditions);
		}

		return $related;
	}

}