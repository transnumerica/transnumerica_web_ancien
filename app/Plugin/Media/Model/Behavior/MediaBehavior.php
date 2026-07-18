<?php
class MediaBehavior extends ModelBehavior{

	private $options = array(
		'path'    => 'img/uploads/%y/%m/%f',
		'extensions' => array('jpg','png'),
		'limit' => 0,
		'max_width' => 0,
		'max_height' => 0,
		'size' => 0, // in KB (Ko)
	);

	public function setup(Model $model, $config = array()){
		$model->medias = array_merge($this->options,$config);
		$model->hasMany['Media'] = array(
			'className'  => 'Media.Media',
			'foreignKey' => 'ref_id',
			'order'		 => array('Media.position IS NULL', 'Media.position' => 'ASC'),
			'conditions' => array('OR' => array('ref' => $model->alias, 'ref' => $model->name), 'Media.online' => 1),
			'dependent'  => true,
			'fields'  => array('id', 'file','name','description'),
		);
		if($model->hasField('media_id')){
			$model->belongsTo['Thumb'] = array(
				'className'  => 'Media.Media',
				'foreignKey' => 'media_id',
				'conditions' => null,
				'counterCache'=> false,
    			'fields'=> array('Thumb.file'),
			);
		}
	}

	public function afterSave(Model $model, $created, $options = array()){

		if ($created) {

			if(!empty($model->data[$model->alias]['media_tmp'])){

				$media_id = $model->data[$model->alias]['id'];
				$media_tmp = $model->data[$model->alias]['media_tmp'];

				$update = $model->Media->updateAll(
					array(
						'ref_id' => $media_id,
					),
					array(
						'ref_id' => $media_tmp,
						'ref' => array($model->name, $model->alias),
					),
				);

				$tmpSaveData = array('id' => $model->id, 'media_tmp' => null);
				$model->save($tmpSaveData, array('fieldList' => array_keys($tmpSaveData), 'validate' => false, 'callbacks' => false));

			}

		}


		if(!empty($model->data[$model->alias]['thumb']['alias'])){
			$file = $model->data[$model->alias]['thumb'];

			// Current thumb
			$media_id = $model->field('media_id');
			if($media_id != 0){
				$model->Media->delete($media_id);
			}

			// Update thumb
			$model->Media->save(array(
				'ref_id' => $model->id,
				'ref'	 => $model->alias,
				'file'   => $file
			));
			$model->saveField('media_id',$model->Media->id);
		}
	}

	public function beforeFind(Model $model, $query){

	        $dbo = $model->getDataSource();

            $this->fullDebug = $dbo->fullDebug;

            $dbo->fullDebug = true;

            return $query;

	}

	public function afterFind(Model $model, $results, $primary = false){

		foreach($results as $k=>$v){
			// Thumbnail
			if(isset($v['Thumb']['file'])){

				if (!empty($v[$model->alias])) {
					$v[$model->alias]['thumb'] = $v['Thumb']['file'];
				}else{
					$v['thumb'] = $v['Thumb']['file'];
				}

			}elseif(isset($v[$model->alias]['Thumb']['file'])){
				$v[$model->alias]['thumb'] = $v[$model->alias]['Thumb']['file'];
			}elseif(isset($v['Thumb']['file'])){
				$v['thumb'] = $v['Thumb']['file'];
			}elseif(isset($v[$model->alias]['media_id'])) {
				// On fais un champ vide au cas où l'image n'existe pas
				$v[$model->alias]['thumb'] = '/';
			}

			if(!empty($v['Media'])){
				$v['Media'] = Set::Combine($v['Media'],'{n}.id','{n}');
				if (count($v['Media'])) {
					$media_thumb = array_values($v['Media'])[0];

					if (!empty($v[$model->alias])) {
						$v[$model->alias]['thumb'] = $media_thumb['file'];
					}else{
						$v['thumb'] = $media_thumb['file'];
					}

					if (empty($v[$model->alias]['Thumb'])) {

						if (!empty($v[$model->alias])) {
							$v[$model->alias]['Thumb']['file'] = $media_thumb['file'];						
						}else{
							$v['Thumb']['file'] = $media_thumb['file'];						
						}

					}
				}
			}


			if( isset($v[$model->alias]['media_id']) && isset($v['Media'][$v[$model->alias]['media_id']]) ){
				$media_id = $v[$model->alias]['media_id'];
				$v[$model->alias]['thumb'] = $v['Media'][$media_id]['file'];
				//Supprimer le champ correspondant au thumb
				$v['Media'][$media_id]['thumb'] = true;
				//unset($v['Media'][$media_id]);

			}

			
			// Supprimer table Thumb
			if (!empty($v['Thumb'])) unset($v['Thumb']);
			if (!empty($v[$model->alias]['Thumb'])) unset($v[$model->alias]['Thumb']);

			$results[$k] = $v;
		}


		return $results;
	}





}
