<?php
class Media extends AppModel{

	public $useTable = 'medias';
	public $order    = array('position IS NULL', 'position' => 'ASC');
	private $pictures = array('jpg', 'jpeg','png','gif','bmp');
    public $displayField = 'file';

	public function beforeDelete($cascade = true){

		$Media = $this->find('first', array(
			'fields' => array('id','ref', 'file'),
			'recursive' => -1,
			'callbacks' => false,
			'conditions' => array('id' => $this->id),
		));

		extract($Media['Media']);

		if (!empty($Media['Media'])) {

			$model = ClassRegistry::init($ref);

			// On instancie une relation pour definir un conterCache Principalement
			$this->belongsTo[$model->alias] = array(
				'className'  => $model->alias,
				'foreignKey' => 'ref_id',
				'conditions' => array('ref' => $model->alias, 'Media.online' => 1),
	        	'counterCache' => array(
	                'media_count' => array('Media.online' => 1),
	        	),
			);
				
		}


		//$file = $this->field('file');
		$info = pathinfo($file);
		foreach(glob(WWW_ROOT.$info['dirname'].'/'.$info['filename'].'_*x*.jpg') as $v){
			unlink($v);
		}
		foreach(glob(WWW_ROOT.$info['dirname'].'/'.$info['filename'].'.'.$info['extension']) as $v){
			unlink($v);
		}
		return true;
	}

	public function afterFind($results, $primary = false){
		foreach($results as $k => $result){
			if(isset($result[$this->alias]) && is_array($result)){
				$media = $result[$this->alias];
				if(isset($media['file'])){
					$pathinfo = pathinfo($media['file']);
					/*if (!empty($pathinfo['extension']))*/ $extension= $pathinfo['extension'];

					if(!in_array($extension, $this->pictures)){
						$results[$k][$this->alias]['type'] = $extension;
						$results[$k][$this->alias]['icon'] = 'Media.' . $extension . '.png';

	                    $istyle = array();
		                if (in_array($extension, array('pdf'))) {
		                    $ifont = 'fa-file-pdf-o';
		                    $istyle[] = 'background: #ad0707';
		                }elseif (in_array($extension, array('txt'))) {
		                    $ifont = 'fa-file-text-o';
		                    $istyle[] = 'background: #565656';
		                }elseif (in_array($extension, array('doc', 'docx'))) {
		                    $ifont = 'fa-file-word-o';
		                    $istyle[] = 'background: #1931a2';
		                }elseif (in_array($extension, array('xls','xlsx'))) {
		                    $ifont = 'fa-file-excel-o';
		                    $istyle[] = 'background: #067934';
		                }elseif (in_array($extension, array('ppt','pptx'))) {
		                    $ifont = 'fa-file-powerpoint-o';
		                    $istyle[] = 'background: #B7472A';
		                }elseif (in_array($extension, array('mp3'))) {
		                    $ifont = 'fa-file-audio-o';
		                    $istyle[] = 'background: #448fff';
		                }elseif (in_array($extension, array('mp4'))) {
		                    $ifont = 'fa-file-mp4-o';
		                    $istyle[] = 'background: #4e0202';
		                }elseif (in_array($extension, array('pic'))) {
		                    $ifont = 'fa-file-image-o';
		                    $istyle[] = 'background: #cc2121';
		                }else{
			                $ifont = 'fa-file-o';
		                    $istyle[] = 'color: #565656';
		                }

					}else{
						$results[$k][$this->alias]['type'] = 'pic';
						$results[$k][$this->alias]['icon'] = $media['file'];
						$ifont = 'fa-file-image-o';
	                    $istyle[] = 'background: #cc2121';
					}

					$results[$k][$this->alias]['i'] = $ifont;
					$results[$k][$this->alias]['istyle'] = implode(';', $istyle);

				}
			}
		}
		configure::write('lastMedia', $results);
		return $results;
	}

	public function beforeSave($options = array()){
		if( isset($this->data[$this->alias]['ref'])){
			$ref = $this->data['Media']['ref'];
			$model = ClassRegistry::init($ref);
        	if(!in_array('Media', $model->Behaviors->loaded())){
        		throw new CakeException(__d('media',"The model '%s' doesn't have a 'Media' Behaviour", $ref));
        	}

			// On instancie une relation pour definir un conterCache Principalement
			$this->belongsTo[$model->alias] = array(
				'className'  => $model->alias,
				'foreignKey' => 'ref_id',
				'conditions' => array('ref' => $model->alias, 'Media.online' => 1),
	        	'counterCache' => array(
	                'media_count' => array('Media.online' => 1),
	        	),
			);


		}else{

			$Media = $this->find('first', array(
				'fields' => array('id','ref'),
				'recursive' => -1,
				'callbacks' => false,
				'conditions' => array('id' => $this->id),
			));

			extract($Media['Media']);


			if (!empty($Media['Media'])) {

				$model = ClassRegistry::init($ref);

				// On instancie une relation pour definir un conterCache Principalement
				$this->belongsTo[$model->alias] = array(
					'className'  => $model->alias,
					'foreignKey' => 'ref_id',
					'conditions' => array('ref' => $model->alias, 'Media.online' => 1),
		        	'counterCache' => array(
		                'media_count' => array('Media.online' => 1),
		        	),
				);
					
			}

		}




		

		if( isset($this->data['Media']['file']) && is_array($this->data['Media']['file']) && isset($this->data['Media']['ref']) ){
			$model 		= ClassRegistry::init($this->data['Media']['ref']);
			$ref_id 	= $this->data['Media']['ref_id'];
	        if(method_exists($this->data['Media']['ref'], 'uploadMediasPath')){
	          $path = $model->uploadMediasPath($ref_id);
	        }else{
			  $path = $model->medias['path'];
	        }
			$pathinfo 	= pathinfo($this->data['Media']['file']['name']);
			$extension  = strtolower($pathinfo['extension']) == 'jpeg' ? 'jpg' : strtolower($pathinfo['extension']);

			if(!in_array($extension, $model->medias['extensions'])){
				$this->error = __d('media',"You don't have the permission to upload this filetype (%s only)", implode(', ', $model->medias['extensions']));
				return false;
			}

			// Limit files count by ref/ref_id
			if ($model->medias['limit'] > 0 && $this->data['Media']['ref_id'] > 0) {
				$qty = $this->find('count', array('conditions' => array('ref' => $this->data['Media']['ref'], 'ref_id' => $this->data['Media']['ref_id'])));
				if ($qty >= $model->medias['limit']) {
					$this->error = __d('media', "You can't send more than %d files", $model->medias['limit']);
					return false;
				}
			}

			// Limit image size (for png/jpg/bmp/tiff)
			if (in_array($extension, array('jpg', 'png', 'bmp', 'tiff')) && ($model->medias['max_width'] > 0 || $model->medias['max_height'] > 0 )) {
				list($width,$height) = getimagesize($this->data['Media']['file']['tmp_name']);
				if ($model->medias['max_width'] > 0 && $width > $model->medias['max_width']) {
					$this->error = __d('media', "The width is too big, it must be less than %dpx", $model->medias['max_width']);
					return false;
				}
				if ($model->medias['max_height'] > 0 && $height > $model->medias['max_height']) {
					$this->error = __d('media', "The height is too big, it must be less than %dpx", $model->medias['max_height']);
					return false;
				}
			}

			// Limit Image size
			if ($model->medias['size'] > 0 && floor($this->data['Media']['file']['size'] / 1024) > $model->medias['size']) {
				$humanSize		= $model->medias['size'] > 1024 ? round($model->medias['size']/1024,1).' Mo' : $model->medias['size'].' Ko';
				$this->error	= __d('media', "The file size is too big, %s max", $humanSize);
				return false;
			}

			if(method_exists($this->data['Media']['ref'], 'uploadMediasPath')){
				$path = $model->uploadMediasPath($ref_id);
			}else{
				$path = $model->medias['path'];
			}

			$filename 	= Inflector::slug($pathinfo['filename'],'-');
			$search 	= array('%md5', '/', '%id', '%mid', '%cid', '%y', '%m', '%f');
			$replace 	= array(md5(rand() . uniqid() . time()), DS, $ref_id, @ceil($ref_id/1000), @ceil($ref_id/100), date('Y'), date('m'), Inflector::slug($filename));
			$file  		= str_replace($search, $replace, $path) . '.' . $extension;
			$this->testDuplicate($file);
			if(!file_exists(dirname(WWW_ROOT.$file))){
				mkdir(dirname(WWW_ROOT.$file),0777,true);
			}
			$this->move_uploaded_file($this->data['Media']['file']['tmp_name'], WWW_ROOT.$file);
			chmod(WWW_ROOT.$file,0777);
			$this->data['Media']['file'] = '/' . trim(str_replace(DS, '/', $file), '/');
		}
		return true;
	}

	/**
	 * Aliast for the move_uploaded_file function, so it can be mocked for testing purpose
	 */
	public function move_uploaded_file($filename, $destination){
		return move_uploaded_file($filename, $destination);
	}

	/**
	* If the file $dir already exists we add a {n} before the extension
	**/
	public function testDuplicate(&$dir,$count = 0){
		$file = $dir;
		if($count > 0){
			$pathinfo = pathinfo($dir);
			$file = $pathinfo['dirname'].'/'.$pathinfo['filename'].'-'.$count.'.'.$pathinfo['extension'];
		}
		if(!file_exists(WWW_ROOT.$file)){
			$dir = $file;
		}else{
			$count++;
			$this->testDuplicate($dir,$count);
		}
	}

}
