<?php
class UploadBehavior extends ModelBehavior{

    /**
    * Fields is used to define fields that are "uploadable"
    * array(
    *   'avatar' => 'img/:id'
    * )
    *
    * :id     => Record ID
    * :id1000 => ceil( Record ID / 1000 )
    * :id100  => ceil( Record ID / 100 )
    * :y      => year
    * :m      => month
    * :uid    => user id (Auth.User.id)
    * :md5    => random MD5
    **/
    private $defaultOptions = array(
        'fields' => array()
    );
    private $options = array();

    public function setup(Model $model, $config = array()){
        $this->options[$model->alias] = array_merge($this->defaultOptions, $config);
    }

    /**
    * CakePHP Model Functions
    **/
    public function afterSave(Model $model, $created, $options = array()){
        $data = $model->data;
        foreach($this->options[$model->alias]['fields'] as $field => $path){
           if(
                isset($data[$model->alias][$field . '_file']) &&
                !empty($data[$model->alias][$field . '_file']['name']) &&
                (
                    !$model->whitelist ||
                    empty($model->whitelist) ||
                    in_array($field, $model->whitelist)
                )
            ){
                $file = $data[$model->alias][$field . '_file'];
                $extension = mb_strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $filename = pathinfo($file['name'])['filename'];
                $path = $this->getUploadPath($model, $path, $extension, $filename);
                $dirname = dirname($path);
                if(!file_exists(WWW_ROOT . $dirname)){
                    mkdir(WWW_ROOT . $dirname, 0777, true);
                }
                if ($model->move_uploaded_file(
                    $file['tmp_name'],
                    WWW_ROOT . $path
                )){
                    chmod(WWW_ROOT . $path, 0777);
                    if ('/'.$path != $model->field($field)) $model->deleteOldUpload($field);
                    $oldData = $model->data;
                    //unset($oldData[$model->alias][$field . '_file']);
                    $newData = $model->saveField($field, '/' . $path, array('callbacks' => false));
                    
                    $model->data = $oldData;
                    $model->data[$model->alias][$field] = $newData[$model->alias][$field];

                }else{
                    exit("Le document a été sauvegarder mais sans la piece jointe, verifier la taille du fichier");
                }
           }
        }
    }

/*
    public function beforeFind(Model $model, $query){
            foreach ($model->getAssociated() as $key => $val){
                if ($val == 'belongsTo' AND !empty($model->$key->hasMany[$model->alias])) $model->$key->hasMany[$model->alias]['exclusive'] = false;
            }
        return $query;
    }
*/
    public function beforeDelete(Model $model, $cascade = true){
        foreach($this->options[$model->alias]['fields'] as $field => $path){
            $model->deleteOldUpload($field);
        }
        return true;
    }

    /**
     * Alias for the move_uploaded_file function, so it can be mocked for testing purpose
    */
    public function move_uploaded_file(Model $model, $source, $destination){
        return move_uploaded_file($source, $destination);
    }

    /**
     * Custom Validation Rules
     */
    public function fileExtension(Model $model, $check, $extensions, $params){

        if (isset($params) AND !is_array($params) AND empty($params['allowEmpty'])) {
            $params = array('allowEmpty' => $params);
        }

        $file = current($check);
        if($params['allowEmpty'] && empty($file['tmp_name'])){
            return true;
        }

        $extension = strtolower(pathinfo($file['name'] , PATHINFO_EXTENSION));
        return in_array($extension, $extensions);
    }



    public function fileIsUpload(Model $model, $check, $params = array()){

        $file = current($check);
        $field_file = array_keys($check)[0];
        $_file = '_file';
        $field = substr_replace($field_file, '', strlen($field_file)- strlen($_file), strlen($_file));

        if (!empty($file['tmp_name']) AND Validation::uploadedFile($file, $params)) {
            return true;
        }elseif (empty($file['tmp_name']) AND $model->id) {
            $field_data = $model->field($field);
            $fileDir = WWW_ROOT . $field_data;
            if(!is_dir($fileDir) AND file_exists($fileDir)) return true;
        }

        return false;

    }



    /**
    * MISC
    **/
    private function getUploadPath(Model $model, $path, $extension, $filename){
        $path = trim($path, '/');

        App::uses('AuthComponent', 'Controller/Component');
        $replace = array(
            ':id1000'  => ceil($model->id / 1000),
            ':id100'   => ceil($model->id / 100),
            ':id'      => $model->id,
            ':y'       => date('Y'),
            ':m'       => date('m'),
            ':uid'     => CakeSession::read(AuthComponent::$sessionKey.'.id'),
            ':md5'     => md5(rand() . uniqid() . time()),
            ':filename'      => $filename,
        );

        // On ajoute les methodes fields data
        preg_match_all('/data\[?(.*?)\]/', $path, $output);

        foreach ($output[0] as $key => $val) {
            if (strpos($field = $output[1][$key], '_id')) {
                $ascModel = $model->getModelbyForeign($field);
                $replace[$val] =  Inflector::slug($model->$ascModel->field($model->$ascModel->displayField,array('id' => $model->field($field))), '-');

            }else{
                $replace[$val] =  Inflector::slug($model->field($field), '-');
            }
        }
        
        $path = strtr($path, $replace) . '.' . $extension;
        return $path;
    }

    public function deleteOldUpload(Model $model, $field){
        $file = $model->field($field);
        if(empty($file)){
            return true;
        }
        $info = pathinfo($file);
        $subfiles = glob(WWW_ROOT . $info['dirname'] . DS . $info['filename'] . '_*x*.*');
        if(file_exists(WWW_ROOT . $file)){
            if (unlink(WWW_ROOT . $file)) return $model->saveField($field, null, array('callbacks' => false));
        }
        if($subfiles){
            foreach($subfiles as $file){
                if (unlink($file)) return $model->saveField($field, null, array('callbacks' => false));
            }
        }
    }


}
