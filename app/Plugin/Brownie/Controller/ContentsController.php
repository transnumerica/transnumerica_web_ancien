<?php

class ContentsController extends BrownieAppController {

	public $components = array('Paginator');
	public $helpers = array('Brownie.Brownie', 'Brownie.i18n');
	public $Model;
	public $uses = array('Brownie.Content');
	public $paginate = array();
	public $data = array();


	public function beforeFilter() {
		parent::beforeFilter();

		if (!empty($this->params['pass'][0])) {
			$model = $this->params['pass'][0];
		} elseif (!empty($this->request->data['Content']['model'])) {
			$model = $this->request->data['Content']['model'];
		}
		if (empty($model) or !$this->Content->modelExists($model)) {
			throw new NotFoundException('Model does not exists');
		}

		$this->Model = ClassRegistry::init($model);
		$this->Model->recursive = -1;
		$this->Model->Behaviors->attach('Brownie.BrwPanel');
		$this->Model->attachBackend();

		$action = $this->params['action'];
		if ($action == 'edit' and empty($this->params['pass'][1]))  {
			$action = 'add';
		}
		if (!$this->_brwCheckPermissions($model, $action)) {
			throw new NotFoundException('No permissions');
		}

		$this->Model->brwConfig['actions'] = array_merge(
			$this->Model->brwConfig['actions'],
			$this->arrayPermissions($this->Model->alias)
		);
		$this->_checkBrwUserCrud();
		$this->Content->i18nInit($this->Model);

		$brwConfig = $this->Model->brwConfig;
		$primaryKey = $this->Model->primaryKey;
		$schema = $this->Content->schemaForView($this->Model);
		$model = $this->Model->alias;
		$this->set(compact('model', 'schema', 'brwConfig','primaryKey'));
	}


	public function index() {
		$this->Paginator->settings = $this->Model->brwConfig['paginate'];
		$this->Paginator->settings['fields'] = array_diff(
			$this->Model->brwConfig['paginate']['fields'],
			array_keys($this->Model->brwConfig['fields']['virtual'])
		);

		if ($this->Model->Behaviors->attached('Tree')) {
			$this->set('isTree', true);
			$this->Paginator->settings['order'] = $this->Model->alias.'.lft';
		}
		$filters = $this->_filterConditions($this->Model);
		$this->Paginator->settings['conditions'] = Set::merge($this->Paginator->settings['conditions'], $filters);
		$this->Paginator->settings['contain'] = $this->Content->relatedModelsForIndex($this->Model, $this->Paginator->settings);
		//$this->Paginator->settings['group'] = $this->Model->alias.'.id';
		$records = $this->paginate($this->Model);
		if (method_exists($this->Model, 'brwAfterFind')) {
			$records = $this->Model->brwAfterFind($records);
		}
		$this->set(array(
			'records' => $this->_formatForView($records, $this->Model),
			'permissions' => array($this->Model->alias => $this->Model->brwConfig['actions']),
			'filters' => $this->_filtersForView($filters),
			'isAnyFilter' => !empty($filters),
		));
		if ($this->Model->brwConfig['fields']['filter']) {
			$this->_setFilterData($this->Model);
		}
	}


	function recursive_array_replace($search, $replace, $subject) {

	    foreach($subject as $key=> &$value) {
	        $current_key=$key;

        	if (is_string($value)) {
		        $value = str_replace($search, $replace, $value);
        	}

	        if(is_array($value)) {
				$value = $this->recursive_array_replace($search, $replace,$value);
	        }
	    }

	    return $subject;
	}


	public function view($model, $id) {
		$this->Model->Behaviors->attach('Containable');
		$params = array(
			'conditions' => array($this->Model->alias . '.'.$this->Model->primaryKey => $id),
			'contain' => $this->Content->relatedModelsForView($this->Model),
		);
		$record = $this->Model->find('all', $params);

		if (empty($record)) {
			throw new NotFoundException('Record does not exists');
		}

		if (method_exists($this->Model, 'brwAfterFind')) {
			$record = $this->Model->brwAfterFind($record);
		}
		$record = $record[0];

		//ejecutar brwAfterFind en los modelos relacionados que estan en $contain

		$neighbors = $this->Content->neighborsForView($this->Model, $record, $restricted = null, $this->params['named']);
		$permissions[$model] = $this->arrayPermissions($model);

		//  On attribue le nom de classe au nom de l'association


		foreach ($this->Model->hasOne as $key => $value) {
			$this->Model->hasOne[$key]['alias'] = $key;
		}

		$this->Model->hasOne = Hash::combine($this->Model->hasOne, '{s}.className', '{s}');
		foreach ($this->Model->hasOne as $key => $value) {
			unset($this->Model->hasOne[$key]);
			$this->Model->hasOne[explode('.', $value['className'])[0]] = $value;
		}

		$this->Model->belongsTo = Hash::combine($this->Model->belongsTo, '{s}.className', '{s}');
		foreach ($this->Model->belongsTo as $key => $value) {
			$this->Model->belongsTo[$key]['alias'] = $key;
		}


		foreach ($this->Model->hasMany as $key => $value) {
			$this->Model->hasMany[$key]['alias'] = $key;
		}

		foreach ($this->Model->hasMany as $key => $value) {
			$this->Model->hasMany[$key]['originalNameModel'] = $key;
		}
		$this->Model->hasMany = Hash::combine($this->Model->hasMany, '{s}.className', '{s}');
		foreach ($this->Model->hasMany as $key => $value) {
			unset($this->Model->hasMany[$key]);
			$this->Model->hasMany[explode('.', $value['className'])[0]] = $value;
		}

		$assocs = array_merge($this->Model->hasMany, $this->Model->hasOne, $this->Model->belongsTo);
		if ($this->Model->Behaviors->attached('Tree')) {
			$assocs[$model] = array('className' => 'User', 'foreignKey' => 'parent_id');
		}
		$assoc_models = array();
		if ((!empty($this->Model->hasMany) OR !empty($this->Model->hasOne)  OR !empty($this->Model->belongsTo)) and $this->Model->brwConfig['show_children']) {
			foreach ($assocs as $key_model => $related_model) {
				if (substr($key_model, 0, 8) == 'BrwI18n_') continue;
				if (!in_array($key_model, $this->Model->brwConfig['hide_children'])) {
					if ($key_model == $model) {
						$AssocModel = $this->Model;
					} else {
						$AssocModel = $this->Model->$key_model;
					}
					$AssocModel->Behaviors->attach('Brownie.BrwPanel');
					if ($this->_brwCheckPermissions($key_model)) {
						if ($indx = array_search($related_model['foreignKey'], $AssocModel->brwConfig['paginate']['fields'])) {
							unset($AssocModel->brwConfig['paginate']['fields'][$indx]);
						}


						if (!function_exists('str_replace_deep')) {
							function str_replace_deep($search, $replace, $subject){
							    if (is_array($subject))
							    {
							        foreach($subject as &$oneSubject)
							            $oneSubject = str_replace_deep($search, $replace, $oneSubject);
							        unset($oneSubject);
							        return $subject;
							    } else {
							        return str_replace($search, $replace, $subject);
							    }
							}
						}


						if (!empty($this->Model->hasMany[$AssocModel->name]['conditions'])) {
							$this->Model->hasMany[$AssocModel->name]['conditions'] = array_combine( str_replace_deep($this->Model->hasMany[$AssocModel->name]['originalNameModel'], $AssocModel->alias, array_keys($this->Model->hasMany[$AssocModel->name]['conditions'])), str_replace_deep($this->Model->hasMany[$AssocModel->name]['originalNameModel'], $AssocModel->alias, array_values($this->Model->hasMany[$AssocModel->name]['conditions'])));
						}
					



						$filters = Hash::merge(
							$this->_filterConditions($AssocModel),
							(!empty($this->Model->hasMany[$AssocModel->name]['conditions'])) ?
								$this->Model->hasMany[$AssocModel->name]['conditions'] : array()
						);


						$filters = $this->recursive_array_replace('{$__cakeID__$}', '\''.$id.'\'', $filters);		
						if (!empty($this->Model->hasMany[$AssocModel->name]['alias'])) {
							$filters = $this->recursive_array_replace('`'.$this->Model->hasMany[$AssocModel->name]['alias'].'`', '`'.$AssocModel->name.'`', $filters);
						}

						$this->Paginator->settings = [];
						$this->Paginator->settings[$AssocModel->name] = Set::merge(
							$AssocModel->brwConfig['paginate'],
							array('conditions' => $filters),
							array('contain' => $this->Content->relatedModelsForIndex($AssocModel, $AssocModel->brwConfig['paginate']))
						);
						$this->Paginator->settings[$AssocModel->name]['fields'] = array_diff(
							$AssocModel->brwConfig['paginate']['fields'],
							array_keys($AssocModel->brwConfig['fields']['virtual'])
						);

						if ($AssocModel->Behaviors->attached('Tree')) {
							$this->Paginator->settings[$AssocModel->alias]['order'] = array($AssocModel->alias.'.lft' => 'ASC');
						}

						$AssocModel->recursive = 0;


						$AssocModelConditions = (!empty($related_model['foreignKey']) ? array($AssocModel->alias . '.' . $related_model['foreignKey'] => $id) : array());
						if (!empty($this->Model->belongsTo[$AssocModel->name])) {
							$AssocModelConditions = (!empty($related_model['foreignKey']) ? array($AssocModel->alias . '.' . $AssocModel->primaryKey => $record[$this->Model->alias][$related_model['foreignKey']]) : array());
						}

						if ($AssocModel->name == 'Media' AND $AssocModel->alias == 'Thumb') {
							continue;
						}

						$assoc_models[] = array(
							'brwConfig' => $AssocModel->brwConfig,
							'model' => $key_model,
							'records' => $this->_formatForView(
								$this->paginate(
									$AssocModel,
									$AssocModelConditions
								),
								$AssocModel
							),
							'schema' => $this->Content->schemaForView($AssocModel),
							'filters' => array_merge(
								$this->_filterConditions($AssocModel),
								array($AssocModel->alias . '.' . $related_model['foreignKey'] => $id)
							),
						);
						$permissions[$key_model] = $this->arrayPermissions($key_model);

						if (!empty($this->Model->belongsTo[$AssocModel->name])) {
							$permissions[$key_model]['add'] = false;
							$permissions[$key_model]['delete'] = false;
							//$this->Model->{$AssocModel->name}->brwConfig['actions']['delete'] = false;
							//$this->{$AssocModel->name}->brwConfig['actions']['delete'] = false;
						}

					}
				}
			}
		}

		$this->_hideConditionalFields($this->Model, $record);
		$record = $this->Content->formatHABTMforView($record, $this->Model);
		$record = $this->_formatForView($record, $this->Model);
		$record = $this->Content->addI18nValues($record, $this->Model);
		$this->set('record', $record);
		$this->set('neighbors', $neighbors);
		$this->set('assoc_models', $assoc_models);
		$this->set('permissions', $permissions);
		$this->set('brwConfig', $this->Model->brwConfig);
		$this->_setI18nParams($this->Model);
	}

	public function edit_id($model, $id = null) {

		 $key = $this->Model->primaryKey;

		if (!empty($this->request->data)) {
			$alias = $this->Model->alias;
			$new_id = $this->request->data[$alias][$this->Model->primaryKey];

			$this->Content->addValidationsRules($this->Model, $id);
			$this->request->data = $this->Content->brownieBeforeSave($this->request->data, $this->Model, $this->Session);

			$this->Model->set($this->request->data);

			// Au cas où on modifier l'ID, on desatribue l'ancien ID
			if ($new_id	!= $id) unset($this->Model->id);
			
			if ($this->Model->validates(array('fieldList' => array($this->Model->primaryKey))) AND $this->Model->updateAll(
						array_merge(array($alias.'.'.$this->Model->primaryKey => "'$new_id'")),
						array($alias.'.'.$this->Model->primaryKey => $id)
					)) { exit ($this->redirect(array('controller' => 'contents', 'action' => 'edit',$alias, $new_id)));

			}else{
				debug($this->Model->validationErrors);
				$msg =	($this->Model->brwConfig['names']['gender'] == 1) ?
					__d('brownie', 'The %s could not be saved. Please, check the error messages.[male]', __($this->Model->brwConfig['names']['singular'])):
					__d('brownie', 'The %s could not be saved. Please, check the error messages.[female]', __($this->Model->brwConfig['names']['singular']));
				$this->Flash->set($msg, array('element' => 'error'));
			}
			$action = 'edit';
		}elseif (!empty($id) AND $this->request->data = $this->Model->read(array($key), $id)) {
			$action = 'edit';
		} else {
			throw new NotFoundException(__d('brownie','Record does not exists'));
		}

		if (!$this->_brwCheckPermissions($model, $action)) {
			throw new NotFoundException(__d('brownie','No permissions'));
		}

		if ($this->Content->brwIsForeignKey($this->Model, $this->Model->primaryKey)) {
			throw new NotFoundException(__d('brownie','Your primaryKey is a foreignKey'));
		}

		$this->set('key', $key);
	}

	public function edit($model, $id = null) {

		if (!empty($id)) {
			if (!$this->Model->read(array($this->Model->primaryKey), $id)) {
				throw new NotFoundException('Record does not exists');
			}
			$action = 'edit';
		} else {
			$action = 'add';
		}
		if (!$this->_brwCheckPermissions($model, $action)) {
			throw new NotFoundException(__d('brownie','No permissions'));
		}
		$fields = $id ? $this->Content->fieldsEdit($this->Model) : $this->Content->fieldsAdd($this->Model);
		$refList = array();
		$refListDefault = null;
		foreach ($fields as $field => $fieldSchema) {

			if ($field == 'ref') {

				foreach ($this->loadedModel as $lModel) {

					if ($this->{$lModel}) {

						foreach ($this->{$lModel}->hasMany as $rfAlias => $rfData) {

							if ($rfData['className'] == $this->Model->name) {
								$rfRef = $rfData['conditions']['ref'];
								if (!$rfRef AND !empty($rfData['conditions'][$this->{$lModel}->alias.'.ref'])) {
									$rfRef = $rfData['conditions'][$this->{$lModel}->alias.'.ref'];
								}

								$refList[$rfRef] = $rfRef;

							}

						}


					}

				}

				$AllRef = $this->Model->find('all');
				$ListAllRef = Hash::combine($AllRef, '{n}.'.$this->Model->alias.'.'.$this->Model->displayField, '{n}.'.$this->Model->alias.'.'.$this->Model->displayField, '{n}.'.$this->Model->alias.'.ref');

				foreach ($ListAllRef as $ListCountAlias => $ListCountData) {
					$refList[$ListCountAlias] = count($ListCountData);
				}

				$refList = Hash::sort($refList, null, 'DSC');
				$refList = array_combine(array_keys($refList), array_keys($refList));

				$refParentAlias = @$this->request->query['parentmodel'];
				if($refParentAlias){
					$refParentModel = $this->{$refParentAlias};
					if ($refParentModel) {


						foreach ($refParentModel->hasMany as $rfAlias => $rfData) {

							if ($rfData['className'] == $this->Model->name) {
								$rfRef = $rfData['conditions']['ref'];
								if (!$rfRef AND !empty($rfData['conditions'][$this->{$lModel}->alias.'.ref'])) {
									$rfRef = $rfData['conditions'][$this->{$lModel}->alias.'.ref'];
								}

								$refListDefault = $rfRef;

							}

						}


					}
				}


			}

		}

		if ($this->Model->Behaviors->enabled('Upload')) {

			$UploadBehaviors = (array) $this->Model->Behaviors->__get('Upload');	
		    $UploadBehaviors= str_replace(array('\u0000*\u0000_','\u0000'),'',json_encode($UploadBehaviors));
		    $UploadBehaviors = json_decode($UploadBehaviors,true);

			$this->set('Upload', $this->Model->UploadFieldList = $UploadBehaviors['UploadBehavioroptions'][$this->Model->alias]['fields']);
		}
		
		if (!empty($this->request->data)) {
			if (!empty($this->request->data[$this->Model->alias][$this->Model->primaryKey]) and $this->request->data[$this->Model->alias][$this->Model->primaryKey] != $id) {
				throw new NotFoundException(__d('brownie','Record does not exists'));
			}
			$this->Content->addValidationsRules($this->Model, $id);
			$this->request->data = $this->Content->brownieBeforeSave($this->request->data, $this->Model, $this->Session);

			if ($this->Content->makeSave($this->Model, $this->request->data, $fields)) {
				$msg =	($this->Model->brwConfig['names']['gender'] == 1) ?
					__d('brownie', 'The %s has been saved [male]', __($this->Model->brwConfig['names']['singular'])):
					__d('brownie', 'The %s has been saved [female]', __($this->Model->brwConfig['names']['singular']));
				$this->Flash->set($msg, array('element' => 'success'));

				if (!empty($this->request->data['Content']['after_save'])) {
					$this->_afterSaveRedirect();
				}
			} else {				                
				debug($this->Model->validationErrors);
				$msg =	($this->Model->brwConfig['names']['gender'] == 1) ?
					__d('brownie', 'The %s could not be saved. Please, check the error messages.[male]', __($this->Model->brwConfig['names']['singular'])):
					__d('brownie', 'The %s could not be saved. Please, check the error messages.[female]', __($this->Model->brwConfig['names']['singular']));
				$this->Flash->set($msg, array('element' => 'error'));
			}
		}


		$contain = array();
		if (!empty($this->Model->hasAndBelongsToMany)) {
			foreach ($this->Model->hasAndBelongsToMany as $key_model => $related_model) {
				if (!in_array($key_model, $contain)) {
					$contain[] = $key_model;
				}
			}
		}

		$this->set('related', $this->Content->relatedData($this->Model));

		if (empty($this->request->data)) {
			if ($id) {
				$this->Model->Behaviors->attach('Containable');
				if ($this->Model->brwConfig['images']) {
					$contain[] = 'Media';
				}
				if ($this->Model->brwConfig['files']) {
					$contain[] = 'BrwFile';
				}
				foreach ((array)$this->Model->schema() as $field => $cnf) {
					$this->Model->brwConfig['fields']['sanitize_html'][$field] = false;
				}
				$this->request->data = $this->Model->find('first', array(
					'conditions' => array($this->Model->alias . '.'.$this->Model->primaryKey => $id),
					'contain' => $contain,
				));
				$this->request->data = $this->Content->i18nForEdit($this->request->data, $this->Model);
			} else {
				$this->request->data = Set::merge(
					$this->Content->defaults($this->Model),
					$this->_filterConditions($this->Model, true)
				);
			}
			$this->request->data['Content']['referer'] = env('HTTP_REFERER') ? $this->referer() : null;
		}

		if (method_exists($this->Model, 'brwBeforeEdit') or !empty($this->Model->Behaviors->__methods['brwBeforeEdit'])) {
			$this->request->data = $this->Model->brwBeforeEdit($this->request->data);
			$this->set('schema', $this->Content->schemaForView($this->Model));
		}
		
		$media = false;
		if ($this->Model->Behaviors->enabled('Media')) {
			$media = true;
		}

		// Au càs où l'id est non editable
		if (in_array($this->Model->primaryKey, $this->Model->brwConfig['fields']['no_'.$action])) $this->set('no_modified_primaryKey', true);

		$this->set('refListDefault', $refListDefault);
		$this->set('refList', $refList);
		$this->set('validate', $this->Model->validate);
		$this->set('media', $media);
		$this->set('fields', $fields);
		$this->set('fckFields', $this->Content->fckFields($this->Model));
		$this->_setI18nParams($this->Model);
		$this->_setAfterSaveOptionsParams($this->Model, $this->request->data);
	}


	public function delete($model, $id) {
		$record = $this->Model->findById($id);
		if (empty($record)) {
			throw new NotFoundException('Record does not exists');
		}
		$home = array('plugin' => 'brownie', 'controller' => 'brownie', 'action' => 'index', 'brw' => false);
		$redirect = $this->referer($home);
		$deleted = $this->Content->remove($this->Model, $id);
		if (!$deleted) {
			$this->Flash->set(__d('brownie', 'Unable to delete'), array('element' => 'error'));
			$this->redirect($redirect);
		} else {
			$this->Flash->set(__d('brownie', 'Successful delete'), array('element' => 'success'));
			$afterDelete = empty($this->params['named']['after_delete'])? null : $this->params['named']['after_delete'];
			if ($afterDelete == 'parent') {
				$parentModel = $this->Model->brwConfig['parent'];
				if (!$parentModel) {
					$afterDelete = 'index';
				} else {
					$foreignKey = $this->Model->belongsTo[$parentModel]['foreignKey'];
					$redirect = array(
						'plugin' => 'brownie', 'controller' => 'contents',
						'action' => 'view', $parentModel, $record[$model][$foreignKey]
					);
				}
			}
			if ($afterDelete == 'index') {
				if ($this->Model->brwConfig['actions']['index']) {
					$redirect = array(
						'plugin' => 'brownie', 'controller' => 'contents',
						'action' => 'index', $model
					);
				} else {
					$redirect = $home;
				}
			}
			$this->redirect($redirect);
		}
	}


	public function delete_file($model, $field, $id = null) {

		if (!$this->Model->read(array($this->Model->primaryKey), $id)) {
			throw new NotFoundException(__d('brownie','Record does not exists'));
		}

		$action = 'edit';
		if (!$this->_brwCheckPermissions($model, $action)) {
			throw new NotFoundException(__d('brownie','No permissions'));
		}


        if ($this->Model->field($field)){
        	if ($this->Model->deleteOldUpload($field)) {
				$this->Flash->set(__d('brownie', 'Successful delete'), array('element' => 'success'));
			}else{
				$this->Model->saveField($field, null);
			}
		}else{
			$this->Flash->set(__d('brownie', 'Unable to delete'), array('element' => 'error'));
		}

		exit ($this->redirect(array('controller' => 'contents', 'action' => 'edit', $this->Model->alias, $id)));

	}


	public function delete_multiple($model) {
		$plural = $this->Model->brwConfig['names']['plural'];
		if (empty($this->request->data['Content']['id'])) {
			$msg = __d('brownie', 'No %s selected to delete', $plural);
			$this->Flash->set($msg, array('element' => 'notice'));
		} else {
			$deleted = $no_deleted = 0;
			foreach ($this->request->data['Content']['id'] as $id) {
				if ($this->Content->remove($this->Model, $id)) {
					$deleted++;
				} else {
					$no_deleted++;
				}
			}
			$msg_deleted = $msg_no_deleted = '';
			if ($deleted) {
				$msg_deleted = __d('brownie', '%d %s deleted.', $deleted, $plural) . ' ';
			}
			if ($no_deleted) {
				$msg_no_deleted = __d('brownie', '%d %s no deleted.', $no_deleted, $plural) . ' ';
			}

			if ($deleted) {
				if ($no_deleted) $flashStatus = 'notice';
				else $flashStatus = 'success';
			} else {
				$flashStatus = 'error';
			}
			$this->Flash->set($msg_deleted . $msg_no_deleted, array('element' => $flashStatus));

		}

		$redir = env('HTTP_REFERER');
		if (empty($redir)) {
			$redir = array('action' => 'index', $model);
		}
		$this->redirect($redir);
	}


	public function edit_upload($model, $uploadType, $recordId, $categoryCode, $uploadId = null) {
		if (
			!in_array($uploadType, array('BrwFile', 'Media'))
			or empty($this->Model->brwConfig[($uploadType == 'BrwFile') ? 'files' : 'images'][$categoryCode])
		) {
			$this->response->statusCode('404');
		}

		if (!empty($this->request->data)) {
			$cantSaved = 0;
			
			foreach ($this->request->data[$uploadType] as $data) {
				$data['name'] = $data['file']['name'];
				if ($this->Model->{$uploadType}->save($data)) {
					$cantSaved++;
				}
			}
			if ($cantSaved) {
				$msg = ($uploadType == 'BrwFile') ?
					__d('brownie', '%s files saved', $cantSaved) :
					__d('brownie', '%s images saved', $cantSaved);
				$msgType = 'success';
			} else {
				$msg = ($uploadType == 'BrwFile') ?
					__d('brownie', 'No files saved', $cantSaved):
					__d('brownie', 'No images saved', $cantSaved);
				$msgType = 'notice';
			}
			$this->Flash->set($msg, array('element' => $msgType));

			$this->redirect(array(
				'plugin' => 'brownie', 'controller' => 'contents',
				'action' => 'view', $model, $recordId
			));

		}
		if (empty($this->request->data) and $uploadId) {
			$data = $this->Model->{$uploadType}->findById($uploadId);
			$this->request->data[$uploadType][0] = $data[$uploadType];
			$max = 1;
		} else {
			$uploadKey = ($uploadType == 'BrwFile') ? 'files' : 'images';
			$max = ($this->Model->brwConfig[$uploadKey][$categoryCode]['index'])? 1:10;
		}
		$this->set(compact('model', 'uploadType', 'recordId', 'categoryCode', 'uploadId', 'max'));
	}


	public function delete_upload($model, $uploadType, $recordId) {
		if (!in_array($uploadType, array('BrwFile', 'Media'))) {
			$this->response->statusCode('404');
		}
		if ($this->Model->{$uploadType}->delete($recordId)) {
			$msg = ($uploadType == 'BrwFile') ?
				__d('brownie', 'The file was deleted') :
				__d('brownie', 'The image was deleted');
			$this->Flash->set($msg, array('element' => 'success'));
		} else {
			$msg = ($uploadType == 'BrwFile') ?
				__d('brownie', 'The file could not be deleted') :
				__d('brownie', 'The image could not be deleted');
			$this->Flash->set($msg, array('element' => 'error'));
		}

		$redirecTo = env('HTTP_REFERER');
		if (!$redirecTo) {
			$redirecTo = array('controller' => 'brownie', 'action' => 'index', 'plugin' => 'brownie');
		}
		$this->redirect($redirecTo);
	}


	public function import($model) {
		if (!$this->Model->brwConfig['actions']['import']) {
			$this->response->statusCode('404');
		}
		if (!empty($this->request->data)) {
			$result = $this->Model->brwImport($this->request->data);
			if (is_array($result)) {
				$import = $result;
				if (empty($import['flash'])) {
					$import['flash'] = ($import['result']) ? 'success' : 'error';
				}
			} else {
				if ($result) {
					$import['msg'] = $import['result'] = $result;
					$import['flash'] = 'success';
				} else {
					$import['msg'] = __d('brownie', 'The import could not be done. Please try again');
					$import['result'] = false;
					$import['flash'] = 'error';
				}
			}

			$this->Flash->set($import['msg'], array('element' => $import['flash']));

			if ($import['result']) {
				$this->redirect(array('controller' => 'contents', 'action' => 'index', $model));
			}
		}

		if (Configure::read('debug') and !method_exists($this->Model, 'brwImport')) {
			$msg = __d('brownie', 'Warning: %s::brwImport() must be defined', $model);
			$this->Flash->set($msg, array('element' => 'error'));
		}

		$this->set('related', $this->Content->relatedData($this->Model));
	}


	public function export($model) {
		$type = $this->Model->brwConfig['export']['type'];
		if (empty($type)) {
			throw new NotFoundException();
		}
		if (!in_array($type, array('xml', 'csv', 'json', 'php', 'xls', 'xlsx'))) {
			$type = 'xml';
		}
		$this->layout = 'ajax';
		if ($type == 'xml') {
			$this->helpers[] = 'Xml';
		}
		header('Content-Disposition: attachment; filename=' . $model . '.' . $type . '');
		header('Content-Type: application/force-download');
		header('Pragma: no-cache');
		header('Pragma: public');
		header('Expires: 0');
		header('Content-Transfer-Encoding: binary');
		$this->set(array(
			'records' => $this->Content->getForExport($this->Model, $this->params['named']),
			'relatedBrwConfig' => $this->Content->getRelatedBrwConfig($this->Model),
		));
		$this->render('/Contents/export/' . $type);
	}


	public function reorder($model, $direction, $id) {
		if (
			!in_array($direction, array('up', 'down'))
			and !$this->Model->Bheaviors->attached('Tree')
			and empty($this->Model->brwConfig['sortable'])
		) {
			$this->response->statusCode('404');
		}

		if ($this->Content->reorder($this->Model, $direction, $id)) {
			$this->Flash->set(__d('brownie', 'Successfully reordered'), array('element' => 'success'));
		} else {
			$this->Flash->set(__d('brownie', 'Failed to reorder'), array('element' => 'error'));
		}

		if ($ref = env('HTTP_REFERER')) {
			$this->redirect($ref);
		} else {
			$this->redirect(array('controller' => 'contents', 'action' => 'index', $model));
		}
	}


	public function filter($model) {
		$url = array('controller' => 'contents', 'action' => 'index', $model);
		foreach ($this->Model->brwSchema() as $field => $cnf) {
			$type = $cnf['type'];
			if (in_array($type, array('date', 'datetime'))) {
				foreach (array('_from', '_to') as $key) {
					if (!empty($this->request->data[$model][$field . $key]['year'])) {
						$data = $this->Content->dateComplete($this->request->data[$model][$field . $key], $key, $type);
						$url[$model . '.' . $field . $key] = $data['year'] . '-' . $data['month'] . '-' . $data['day'];
						if ($type == 'datetime') {
							$url[$model . '.' . $field . $key] .= ' ' . $data['hour'] . ':' . $data['min'] . ':' . $data['sec'];
						}
					}
				}
			} elseif (
				$type == 'float' or
				(
					$type == 'integer'
					and !$this->Content->brwIsForeignKey($this->Model, $field)
					and array_key_exists($field, $this->Model->brwConfig['fields']['filter'])
					and empty($this->Model->brwConfig['fields']['filter'][$field])
				)
			) {
				foreach (array('_from', '_to') as $key) {
					if (
						array_key_exists($field . $key, $this->request->data[$model])
						and
						$this->request->data[$model][$field . $key] != ''
					) {
						$url[$model . '.' . $field . $key] = $this->request->data[$model][$field . $key];
					}
				}
			} elseif ($type == 'boolean') {
				if (
					array_key_exists($field, $this->request->data[$model])
					and
					in_array($this->request->data[$model][$field], array('1', '0'))
				) {
					$url[$model . '.' . $field] = $this->request->data[$model][$field];
				}
			} elseif (!empty($this->request->data[$model][$field])) {
				if (is_array($this->request->data[$model][$field])) {
					$url[$model . '.' . $field] = join('.', $this->request->data[$model][$field]);
				} else {
					$url[$model . '.' . $field] = trim($this->request->data[$model][$field]);
				}
			}
		}
		foreach ($this->Model->hasAndBelongsToMany as $relatedModel => $cnf) {
			if (!empty($this->request->data[$relatedModel][$relatedModel])) {
				$url[$relatedModel] = join('.', $this->request->data[$relatedModel][$relatedModel]);
			}
		}
		$this->redirect($url);
	}


	public function _formatForView($data, $Model) {
		$out = array();
		if (!empty($data[$Model->alias])) {
			$out = $this->_formatSingleForView($data, $Model);
		} else {
			if ($Model->Behaviors->attached('Tree')) {
				$data = $this->_formatTree($data, $Model);
			}
			foreach ($data as $dataset) {
				$out[] = $this->_formatSingleForView($dataset, $Model);
			}
		}
		return $out;
	}


	public function _formatSingleForView($data, $Model, $inView = false) {
		$fieldsConfig = $Model->brwConfig['fields'];
		$fieldsHide = array_merge($fieldsConfig['no_view'], $fieldsConfig['hide']);
		$fK = $this->Content->getForeignKeys($Model);
		$permissions = $this->arrayPermissions($Model->alias);
		$retData = $data;
		$schema = $Model->schema();
		if (!empty($retData[$Model->alias])) {
			foreach ($retData[$Model->alias] as $key => $value) {
				if (in_array($key, $fieldsHide)) {
					unset($retData[$Model->alias][$key]);
				} elseif (in_array($key, $fieldsConfig['code'])) {
					$retData[$Model->alias][$key] = '<pre>' . htmlspecialchars($retData[$Model->alias][$key]) . '</pre>';
				} elseif (isset($fK[$key]) and !empty($data[$fK[$key]['alias']])) {
					$RelModel = ($fK[$key]['className'] == $Model->alias) ? $Model : $Model->{$fK[$key]['alias']};


					if(isset($data[$fK[$key]['alias']][$RelModel->displayField]) AND $data[$fK[$key]['alias']][$RelModel->displayField]) {

						if ($RelModel->primaryKeyArray AND count($RelModel->primaryKeyArray)  == count(array_intersect($RelModel->primaryKeyArray, array_keys($data[$fK[$key]['alias']])))) {

							$primaryKeyArrayFieldArrayValue = array();
							foreach ($RelModel->primaryKeyArray as $primaryKeyArrayForeignKey) {

								$primaryKeyArrayAlias = $this->Content->brwIsForeignKey($RelModel, $primaryKeyArrayForeignKey);

								$primaryKeyArrayFieldArrayValue[] = $data[$fK[$key]['alias']][$primaryKeyArrayAlias][$RelModel->{$primaryKeyArrayAlias}->displayField];

							}

							$retData[$Model->alias][$key] = implode('<br/>', $primaryKeyArrayFieldArrayValue).'';

						}else{
							$retData[$Model->alias][$key] = $data[$fK[$key]['alias']][$RelModel->displayField];
						}

					}

					if ($this->_brwCheckPermissions($RelModel->alias, 'view', $data[$fK[$key]['alias']][$Model->{$fK[$key]['alias']}->primaryKey])) {
						$relatedURL = Router::url(array(
							'controller' => 'contents', 'action' => 'view', 'plugin' => 'brownie',
							$fK[$key]['className'], $data[$fK[$key]['alias']][$Model->{$fK[$key]['alias']}->primaryKey]
						));
						$retData[$Model->alias][$key] = '<a href="'.$relatedURL.'">' . $retData[$Model->alias][$key] . '</a>';
					}
				} else {
					if (!empty($schema[$key]['type'])) {
						switch($schema[$key]['type']) {
							case 'boolean':
								if (!is_null($retData[$Model->alias][$key])) {
									$retData[$Model->alias][$key] = $retData[$Model->alias][$key] ?
										__d('brownie', 'Yes'): __d('brownie', 'No');
								} else {
									$retData[$Model->alias][$key] = '';
								}
							break;
							case 'datetime':
								$retData[$Model->alias][$key] = $this->_formatDateTime($retData[$Model->alias][$key]);
							break;
							case 'date':
								$retData[$Model->alias][$key] = $this->_formatDate($retData[$Model->alias][$key]);
							break;
						}
					}
				}
				if (in_array($key, $fieldsConfig['email']) and $schema[$key]['type'] == 'string') {
					$email = $retData[$Model->alias][$key];
					$retData[$Model->alias][$key] = '<a class="mailto" href="mailto:' . $email . '">' . $email . '</a>';
				}
			}
			$retData[$Model->alias]['brw_actions'] = $this->Content->actions($Model, $data, $permissions);
			$retData[$Model->alias]['primaryKey'] = $Model->primaryKey;
			$retData[$Model->alias]['Mname'] = $Model->name;

		}
		return $retData;
	}


	public function _formatTree($data, $Model) {
		$Model->displayField = $this->Model->brwConfig['paginate']['fields'][1];
		$treeList = $Model->generateTreeList(null, null, null, '<span class="tree_prepend"></span>');
		foreach ($data as $i => $value) {
			if (isset($data[$i][$Model->alias][$Model->displayField])) {
				$displayField = $Model->displayField;
				$displayValue = $data[$i][$Model->alias][$displayField];
			}else{
				$displayField = $this->Model->brwConfig['paginate']['fields'][1];
				$displayValue = $data[$i][$Model->alias][$displayField];
			}
			$data[$i][$Model->alias][$displayField] =
				str_replace($displayValue, '', $treeList[$value[$Model->alias]['id']])
				. '<span class="tree_arrow"></span>' . $displayValue;
		}
		return $data;
	}


	public function _formatDate($date) {
		if (empty($date) or $date == '0000-00-00') {
			return __d('brownie', 'Date not set');
		} else {
			return date(Configure::read('brwSettings.dateFormat'), strtotime($date));
		}
	}


	public function _formatDateTime($datetime) {
		if (empty($datetime) or $datetime == '0000-00-00 00:00:00') {
			return __d('brownie', 'Datetime not set');
		} else {
			return date(Configure::read('brwSettings.datetimeFormat'), strtotime($datetime));
		}
	}


	public function _setAfterSaveOptionsParams($Model, $data) {

		if (!empty($this->params['named']['after_save'])) {
			$default = $this->params['named']['after_save'];
		} elseif ($data['Content']['referer']) {
			$default = 'referer';
		} elseif ($Model->brwConfig['actions']['view']) {
			$default = 'view';
		} elseif ($Model->brwConfig['actions']['index']) {
			$default = 'index';
		} else {
			$default = 'home';
		}

		$params = array(
			'type' => 'select',
			'label' => __d('brownie', 'After save'),
			'options' => array(
				'referer' => __d('brownie', 'Back to where I was'),
				'view' => ($Model->brwConfig['names']['gender'] == 1) ?
					__d('brownie', 'View saved %s [male]', __($Model->brwConfig['names']['singular'])):
					__d('brownie', 'View saved %s [female]', __($Model->brwConfig['names']['singular']))
				,
				'add' =>  ($Model->brwConfig['names']['gender'] == 1) ?
					__d('brownie', 'Add another %s [male]', __($Model->brwConfig['names']['singular'])):
					__d('brownie', 'Add another %s [female]', __($Model->brwConfig['names']['singular']))
				,
				'index' => ($Model->brwConfig['names']['gender'] == 1) ?
					__d('brownie', 'Go to to index of all %s [male]', __($Model->brwConfig['names']['plural'])):
					__d('brownie', 'Go to to index of all %s [female]', __($Model->brwConfig['names']['plural']))
				,
				'edit' => ($Model->brwConfig['names']['gender'] == 1) ?
					__d('brownie', 'Continue editing this %s [male]', __($Model->brwConfig['names']['singular'])):
					__d('brownie', 'Continue editing this %s [female]', __($Model->brwConfig['names']['singular']))
				,
				'home' => __d('brownie', 'Go home'),
			),
			'default' => $default,
		);
		foreach (array('add', 'view', 'index') as $action) {
			if (!$Model->brwConfig['actions'][$action]) {
				unset($params['options'][$action]);
			}
		}

		if ($Model->brwConfig['parent']) {
			$parentModel = $Model->{$Model->brwConfig['parent']};
			$params['options']['parent'] =	($parentModel->brwConfig['names']['gender'] == 1) ?
				__d('brownie', 'Go to the %s [male]', __($parentModel->brwConfig['names']['singular'])):
				__d('brownie', 'Go to the %s [female]', __($parentModel->brwConfig['names']['singular']));
		}
		if (!$data['Content']['referer'] or !empty($this->params['named']['after_save'])) {
			unset($params['options']['referer']);
		}
		$this->set('afterSaveOptionsParams', $params);
	}


	public function _filterConditions($Model, $forData = false) {
		return $this->Content->filterConditions($Model, $this->params['named'], $forData);
	}


	public function _filtersForView($filters) {
		foreach ($filters as $field => $value) {
			if (strstr($field, '>') or strstr($field, '<')) {
				unset($filters[$field]);
			} elseif (is_array($value)) {
				$filters[$field] = join('.', $value);
			}
		}
		return $filters;
	}


	public function _setFilterData($Model) {
		$filterFields = $this->Model->brwConfig['fields']['filter'];
		$model = $Model->alias;
		foreach ($filterFields as $field => $multiple) {
			if ($field == 'brwHABTM') continue;
			$schema = $Model->brwSchema();
			$type = $schema[$field]['type'];
			$isRange = (
				in_array($type, array('date', 'datetime', 'float'))
				or
				(in_array($type, array('integer')) and !$this->Content->brwIsForeignKey($this->Model, $field) and !$multiple)
			);
			if ($isRange) {
				foreach (array('_from', '_to') as $key) {
					if (isset($this->params['named'][$model . '.' . $field . $key])) {
						$this->request->data[$model][$field . $key] = $this->params['named'][$model . '.' . $field . $key];
					}
				}
			} elseif ($type == 'integer' or $type == 'boolean' or $type == 'string' or $type == 'select') {
				if (array_key_exists($model . '.' . $field, $this->params['named'])) {
					$fieldData = $this->params['named'][$model . '.' . $field];
					if ($type  == 'integer' and strstr($fieldData, '.')) {
						$fieldData = explode('.', $fieldData);
					}
					$this->request->data[$model][$field] = $fieldData;
				}
			}
		}
		foreach ($filterFields['brwHABTM'] as $relatedModel) {
			if (!empty($this->params['named'][$relatedModel])) {
				$this->request->data[$relatedModel][$relatedModel] = explode('.', $this->params['named'][$relatedModel]);
			}
		}
		$relatedClassNames = array();
		foreach ($Model->belongsTo as $alias => $relatedModel) {
			if (in_array($relatedModel['foreignKey'], array_keys($filterFields))) {
				$relatedClassNames[] = $alias;
			}
		}
		foreach ($Model->hasAndBelongsToMany as $alias => $relatedModel) {
			if (in_array($relatedModel['className'], $filterFields['brwHABTM'])) {
				$relatedClassNames[] = $alias;
			}
		}
		foreach ($relatedClassNames as $className) {
			$varSet = Inflector::pluralize($className);
			$varSet[0] = strToLower($varSet[0]);
			if ($this->Model->{$className}->Behaviors->attached('Tree')) {
				$list = $this->Model->{$className}->generateTreeList(null, null, null, '_');
			} else {
				$list = $this->Model->{$className}->find('list');
			}
			$this->set($varSet, $list);
		}
	}


	public function _setI18nParams($Model) {
		$i18nFields = array();
		if ($Model->Behaviors->enabled('Translate')) {
			$i18nFields = array_keys($Model->Behaviors->Translate->settings[$Model->alias]);
		}
		$this->set(array('i18nFields' => $i18nFields, 'langs3chars' => Configure::read('Config.langs')));
	}


	public function _checkBrwUserCrud() {
		$authModel = AuthComponent::user('model');
		$mustRedirect = false;
		if ($this->Model->alias == 'BrwUser') {
			if ($authModel != 'BrwUser') {
				$mustRedirect = true;
			}
		} else {
			if ($this->Model->alias == $authModel and $this->params['action'] == 'index') {
				$mustRedirect = true;
			}
		}
		if ($mustRedirect) {
			$this->redirect(array('action' => 'view', $authModel, AuthComponent::user('id')));
		}
	}


	public function _hideConditionalFields($Model, $record) {
		$habtmToHide = $fieldsToHide = array();
		foreach ($Model->brwConfig['fields']['conditional'] as $field => $config) {
			if (isset($record[$Model->alias][$field])) {
				$toHide = array_diff(
					$config['hide'],
					$config['show_conditions'][$record[$Model->alias][$field]]
				);
				$fieldsToHide = array_merge($fieldsToHide, $toHide);
				if (!empty($fieldsToHide['HABTM'])) {
					$habtmToHide = array_merge($habtmToHide, $fieldsToHide['HABTM']);
					unset($fieldsToHide['HABTM']);
				}
			}
		}

		$Model->brwConfig['fields']['no_view']
			= array_merge($Model->brwConfig['fields']['no_view'], $Model->brwConfig['fields']['hide'], $fieldsToHide);
		$Model->brwConfig['hide_related']['hasAndBelongsToMany']
			= array_merge($Model->brwConfig['hide_related']['hasAndBelongsToMany'], $habtmToHide);

	}


	public function _afterSaveRedirect() {
		switch ($this->request->data['Content']['after_save']) {
			case 'referer':
				if ($this->request->data['Content']['referer']) {
					$this->redirect($this->request->data['Content']['referer']);
				} else {
					$this->redirect(array('controller' => 'brownie', 'action' => 'index'));
				}
			break;
			case 'edit':
				$this->redirect(array('action' => 'edit', $this->Model->alias, $this->Model->id, 'after_save' => 'edit'));
			break;
			case 'add':
				$this->redirect(array('action' => 'edit', $this->Model->alias, 'after_save' => 'add'));
			break;
			case 'index':
				$this->redirect(array('action' => 'index', $this->Model->alias));
			break;
			case 'parent':
				if ($parent = $this->Model->brwConfig['parent']) {
					$foreignKey = $this->Model->belongsTo[$parent]['foreignKey'];
					if (!empty($this->request->data[$this->Model->alias][$foreignKey])) {
						$idRedir = $this->request->data[$this->Model->alias][$foreignKey];
					} else {
						$record = $this->Model->findById($this->Model->id);
						$idRedir = $record[$this->Model->alias][$foreignKey];
					}
					$this->redirect(array('action' => 'view', $parent, $idRedir));
				}
				$this->redirect(array('action' => 'index', $this->Model->alias));
			break;
			case 'view':
				$this->redirect(array('action' => 'view', $this->Model->alias, $this->Model->id));
			break;
			case 'home':
				$this->redirect(array('controller' => 'brownie', 'action' => 'index'));
			break;
		}
	}

}