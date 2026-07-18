<?php
App::uses('Model', 'Model');
class MyAppModel extends Model {

    public $validationDomain = 'default';
	public $recursive = -1;

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);

        // On charge le plugin Containable au cas où elle ne sera pas chargé
        if(!$this->Behaviors->loaded('Op')) $this->Behaviors->load('My.Op');
        if(!$this->Behaviors->loaded('EagerLoader') AND CakePlugin::loaded('EagerLoader')) $this->Behaviors->load('EagerLoader.EagerLoader');
        if(!$this->Behaviors->loaded('Containable')) $this->Behaviors->load('Containable');
    }

    public function lastQuery($options = array()) {

        $dbo = $this->getDataSource();
        $logs = $dbo->getLog(false, false);
        $lastLog = end($logs['log']);

        return $lastLog['query'];

    }

    public function lastQueryData($options = array()) {

        $dbo = $this->getDataSource();
        $logs = $dbo->getLog(false, false);
        $lastLog = end($logs['log']);

        if ($lastLog) {
            $fullDebug = $dbo->fullDebug;

            $dbo->fullDebug = false;
            $data = $dbo->fetchAll($lastLog['query']);
            $dbo->fullDebug = $fullDebug;

            return $data;
        }

        return array();
    }


    public function beforeValidate($options = array()) {
        parent::beforeValidate($options);

        if(!empty($this->belongsTo)) {
            $foreignKey = false;
            foreach ($AllAscModel = array_keys($this->belongsTo) as $AscModel) {
                if(empty($this->$AscModel->hasAndBelongsToMany)) break;
                if ($foreignKey == true) break;
                foreach ($this->$AscModel->hasAndBelongsToMany as $hasandModel => $value) {
                    if (in_array($hasandModel, $AllAscModel)) {
                        $foreignKey[] = $value['foreignKey'];
                        $foreignKey[] = $value['associationForeignKey'];
                        break;
                    }
                }
            }
        }    
        
        if(!empty($foreignKey) AND !$this->primaryKeyArray) {
            $this->primaryKeyArray = $foreignKey;
            if ($this->displayField != $this->primaryKey) $this->primaryKeyArray[] = $this->displayField;
            // On empeche les erreurs des duplications et des mauvaises creation d'ID
            if (isset($this->primaryKeyArray) AND !empty($this->data[$this->alias])) {
                $this->validator()
                ->add($this->primaryKey, 'DuplicateId',array(
                    'rule' => 'isUnique',
                    'message' => 'Une relation simulaire est deja existante',
                    'last' => false,
                ));
            }
        }

        //Validation "HasAndBelongsToMany" Partie 1
        foreach (array_keys($this->hasAndBelongsToMany) as $model){
            if(isset($this->data[$model])){
                if (isset($this->data[$model][$model])) {
                    $this->data[$this->alias][$model] = $this->data[$model][$model];
                }else{
                    $this->data[$this->alias][$model] = Hash::extract($this->data[$model], '{n}.'.$this->hasAndBelongsToMany[$model]['associationForeignKey']);
                }
            }elseif(isset($this->data[$this->alias][$model])){
                if (isset($this->data[$this->alias][$model][$model])) {
                    $this->data[$this->alias][$model] = $this->data[$this->alias][$model][$model];
                }else{
                    $this->data[$this->alias][$model] = Hash::extract($this->data[$this->alias][$model], '{n}.'.$this->hasAndBelongsToMany[$model]['associationForeignKey']);
                }
            }

            if ($this->unsethasAndBelongsToMany === null) {
                $this->unsethasAndBelongsToMany = array();
            }

            $this->unsethasAndBelongsToMany[] = $model;
        }

        // On donne la valeur du lien par rapport au display fields
        $slugfields = array('lien', 'slug');
        if (isset($this->data[$this->alias][$this->displayField.'_lang'])) {

            $toSource = Op::language();
            $localeFallback = $toSource[1];

            foreach ($slugfields as $slugfield) {
                $this->data[$this->alias][$slugfield] = strtolower(Inflector::slug($this->data[$this->alias][$this->displayField.'_lang'][$localeFallback]));
            }

        }elseif(isset($this->data[$this->alias][$this->displayField]) AND is_string($this->data[$this->alias][$this->displayField])){

            foreach ($slugfields as $slugfield) {
                $this->data[$this->alias][$slugfield] = strtolower(Inflector::slug($this->data[$this->alias][$this->displayField]));
            }                
        }


        //On force la validation des modeles imbriqués en hasAndBelongsToMany
        foreach ($this->hasAndBelongsToMany as $model => $model_data){

            if (!empty($this->data[$model])) {
                if(is_array($model_data['with'])) $with = key($model_data['with']);
                else $with = $model_data['with'];

                $data_HABTM = $this->data[$model];
                $data_HABTM = Hash::insert($data_HABTM, '{n}.'.$model_data['foreignKey'], $this->id);

                
                $a = $this->options;
                if (!isset($a['validate']) OR $a['validate']) $a['validate'] = 'only';
                if (!$this->{$with}->saveAll($data_HABTM, $a)) {
                    $this->invalidate($with, array());
                    unset($this->data[$model]);
                }

                //Configure::write('valRu.'.$model, $this->{$with}->validationErrors);
            }
        }


        if(!empty($this->data[$this->alias])){
            foreach ($this->data[$this->alias] as $key => $value) {

                if (in_array(@$this->_schema[$key]['type'], array('float', 'decimal'))) {
                    $this->data[$this->alias][$key] = str_replace(array(' '), '', $this->data[$this->alias][$key]);
                }

            }
        }

   



    }


    public function afterValidate($options = array()) {
        parent::afterValidate($options);




        //Validation "HasAndBelongsToMany"  Partie 2
        foreach ($this->validationErrors as $model => $error) {
            if (in_array($model, array_keys($this->hasAndBelongsToMany))) {

                foreach ($error as $message) {
                    $this->{$model}->invalidate($model, $message);
                }
            }
        }

        //Validation "HasAndBelongsToMany"  Partie 2 Enlever la redondance data pour eviter les erreurs de save
        if (!empty($this->unsethasAndBelongsToMany)) {
            foreach ($this->unsethasAndBelongsToMany as $unsethasAndBelongsToMany) {
                unset($this->data[$this->alias][$unsethasAndBelongsToMany]);
            }            
        };


        //$this->data[$this->alias][$model] = Hash::extract($this->data[$model], '{n}.'.$this->hasAndBelongsToMany[$model]['associationForeignKey']);


    }

    public function exists($id = null) {
        if (empty($this->id) AND empty($this->data[$this->alias][$this->primaryKey]) AND !empty($this->primaryKeyArray) AND !empty($this->data[$this->alias])) {
            $foreignKey = array();
            foreach ($this->belongsTo as $for_alias => $for_data) {
                $foreignKey[$for_data['foreignKey']] = array_merge(array('alias' => $for_alias), $for_data);
            }

            $conditions = array();

            $oldData = $this->data;

            if (!$this->exisStart) {
                $this->exisStart = true;
                $this->beforeSave(array('callbacks' => false));
                $this->exisStart = false;
            }
            
            $compData = $this->data;
            $this->data = $oldData;

            foreach ($this->primaryKeyArray as $pk) {
                if (in_array($pk, array_keys($foreignKey)) AND isset($this->{$foreignKey[$pk]['alias']}->data[$this->{$foreignKey[$pk]['alias']}->alias][$this->{$foreignKey[$pk]['alias']}->primaryKey]) && $for_key_data = $this->{$foreignKey[$pk]['alias']}->data[$this->{$foreignKey[$pk]['alias']}->alias][$this->{$foreignKey[$pk]['alias']}->primaryKey]) {
                    $conditions[$this->alias.'.'.$pk] = $for_key_data;
                }else{

                    if (isset($compData[$this->alias][$pk]) && $compData[$this->alias][$pk]) {
                        $conditions[$this->alias.'.'.$pk] = $compData[$this->alias][$pk];
                    }else{
                        return parent::exists($id);
                    }
                }
            }

            if (is_array($id)) $conditions = array_merge($conditions, $id);

            if ($exists = $this->field($this->primaryKey, $conditions)) {
                $this->id = $this->data[$this->alias][$this->primaryKey] =  $exists;
                return true; 
            }elseif (!empty($this->data[$this->alias][$this->primaryKey])) $this->id =  $this->data[$this->alias][$this->primaryKey];
        }

        return parent::exists($id);
    }


    public function beforeSave($options = array()) {
        parent::beforeSave($options);

        // On paramettre la date de modification à true au cas où un champs non primaire est impliqué
        if (!empty($this->data[$this->alias]['modified']) AND empty($this->data[$this->alias]['created'])) {
            $foreignKey[] = $this->primaryKey;
            foreach ($this->getAssociated() as $key => $val) $foreignKey[] = $this->{$val}[$key]['foreignKey'];

            $modified = $this->data[$this->alias]['modified'];
            unset($this->data[$this->alias]['modified']);

            foreach ($this->data[$this->alias] as $key => $val) {
                if (empty($this->data[$this->alias]['modified']) AND in_array($key, $foreignKey)) $this->data[$this->alias]['modified'] = false;
                elseif (empty($this->data[$this->alias]['modified']) AND  !in_array($key, $foreignKey)) $this->data[$this->alias]['modified'] = $modified;
            }
        }

        // On met à la ligne tous les champs 'texte'
        foreach ($this->data[$this->alias] as $key => $value) {
            if (stripos(@$this->_schema[$key]['type'], 'text')  !== false) {

              $replace = array(
                    '/ {2,}/' => '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s',
                    '/!\s+!/' => ' ',
                    '/ {2,}/' => ' ',
                    '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s' => '',
                );

                if(is_string($this->data[$this->alias][$key]) AND !preg_match('/<([^>]*)>/', $this->data[$this->alias][$key]) AND !$this->is_serial2($this->data[$this->alias][$key])) {

                    $text = '';
                    foreach (explode("\n", $this->data[$this->alias][$key]) as $string) {

                            
                            $str = preg_replace(array_keys($replace), array_values($replace), $string);

                            $str = html_entity_decode($this->HtmlToText($str));
                            $str = preg_replace('/\s{2,}/u', '', preg_replace('/[\n\r\t]+/', '', $str));
                            $str = str_replace(' ', '', $str);

                            if($str AND !is_numeric($str)) $string = '<p>'.$string.'</p>';


                        $text .= $string;
                    }

                    $this->data[$this->alias][$key] = preg_replace(array_keys($replace), array_values($replace), $text);                    
                }

            }


        }
        // On vide le cache validation
        Cache::delete('Validation.'.session_id().'.'.$this->alias);
    }

    // On active le callback pour les deleteAll
    public function deleteAll($conditions, $cascade = true, $callbacks = true) {
        return parent::deleteAll($conditions, $cascade, $callbacks);
    }


    // On permet l'enregistrement des noms du model dans un champs 'ref: Important' si elle existe
    public function saveAssociated($data = null, $options = array()) {
        foreach ($data as $alias => $modelData) {
            $HABTM = $HASM = $HASO = null;

            if (isset($this->hasOne[$alias])) $HASO = $this->hasOne[$alias];
            if (!empty($HASO) AND !empty($data[$alias]) AND (isset($HASO['conditions']['ref']) OR isset($HASO['conditions'][$HASO['className'].'.ref']))) {
                if (isset($HASO['conditions']['ref'])) $ref = $HASO['conditions']['ref'];
                else $ref = $HASO['conditions'][$HASO['className'].'.ref'];

                $data[$alias] += array('ref' => $ref);

            }


            if (isset($this->hasMany[$alias])) $HASM = $this->hasMany[$alias];
            if (!empty($HASM) AND !empty($data[$alias]) AND (isset($HASM['conditions']['ref']) OR isset($HASM['conditions'][$HASM['className'].'.ref']))) {
                if (isset($HASM['conditions']['ref'])) $ref = $HASM['conditions']['ref'];
                else $ref = $HASM['conditions'][$HASM['className'].'.ref'];

                foreach ($data[$alias] as $key => $assoc_id) {
                    $data[$alias][$key] += array('ref' => $ref);
                }
            }

            if (isset($this->hasAndBelongsToMany[$alias])) $HABTM = $this->hasAndBelongsToMany[$alias];
            if (!empty($HABTM) AND !empty($data[$alias][$alias]) AND (isset($HABTM['conditions']['ref']) OR isset($HABTM['conditions'][$HABTM['with'].'.ref']))) {
                if (isset($HABTM['conditions']['ref'])) $ref = $HABTM['conditions']['ref'];
                else $ref = $HABTM['conditions'][$HABTM['with'].'.ref'];

                if (is_string($data[$alias][$alias])) {
                    $data[$alias][$alias] = array($data[$alias][$alias]);
                }

                foreach ($data[$alias][$alias] as $assoc_id) {

                    $aliasTrueIds = $this->{$alias}->find('list', array('fields' => array($this->{$alias}->primaryKey, $this->{$alias}->primaryKey), 'conditions' => array('OR' => array(
                        array($alias.'.'.$this->{$alias}->primaryKey => $data[$alias][$alias]),
                        //array($alias.'.'.$this->{$alias}->displayField => $data[$alias][$alias]),
                    ))));

                    $aliasTrueNames = $this->{$alias}->find('list', array('fields' => array($this->{$alias}->primaryKey, $this->{$alias}->displayField), 'conditions' => array('OR' => array(
                        array($alias.'.'.$this->{$alias}->primaryKey => $data[$alias][$alias]),
                        //array($alias.'.'.$this->{$alias}->displayField => $data[$alias][$alias]),
                    ))));



                    if(in_array($assoc_id, $aliasTrueIds)){

                        $data[$alias][] = array($HABTM['associationForeignKey'] => $assoc_id, 'ref' => $ref);

                    }elseif(in_array($assoc_id, $aliasTrueNames)){

                        $data[$alias][] = array($HABTM['associationForeignKey'] => array_flip($aliasTrueNames)[$assoc_id], 'ref' => $ref);
                    }else{

                        $newAliasSaveData = array($this->{$alias}->displayField => $assoc_id);
                        if (!empty($HABTM['newCreate'])) {
                            $newAliasSaveData = Hash::merge($newAliasSaveData, $HABTM['newCreate']);
                        }

                        $HABTModelName = $this->{$alias}->name;
                        $createAlias = $this->{$HABTModelName}->create();
                        $createAlias = $this->{$HABTModelName}->save($newAliasSaveData, array('fields' => array($this->{$HABTModelName}->primaryKey, $this->{$HABTModelName}->displayField), 'validate' => false, 'callbacks' => false));
                        if ($createAlias) {
                            $data[$alias][] = array($HABTM['associationForeignKey'] => $this->{$HABTModelName}->id, 'ref' => $ref);
                        }

                    }
                }

                unset($data[$alias][$alias]);

            }
        }

        return parent::saveAssociated($data, $options);
    }


    protected function _doSave($data = null, $options = array()) {

        if (!in_array($this->alias, array('Session','Media'))) {

            $fields = $this->_schema;
            if (!$fields) $fields = $this->schema();

            if (!empty($data[$this->alias])) $data2 = $data[$this->alias];
            elseif (!empty($data)) $data2 = $data;
            else $alias = false;


            if (!empty($data2)) {

                foreach ($data2 as $key => $value) {
                    if (!empty($fields[$key]['type'])) {                
                        if (stripos($fields[$key]['type'], 'set') === 0 AND is_array($value)) {
                            $data2[$key] = implode(',', $value);
                        }

                        if ($fields[$key]['type'] == 'text' AND !isset($fields[$key]['collate']) AND !isset($fields[$key]['charset']) AND $fields[$key]['length'] == 4) {


                            $daTesV = null;
                            if (is_string($value)) {
                                $daTesV = $value;
                            }elseif (is_array($value)) {                                
                                $daTesV = array_values($value)[0];
                            }

                            $data2[$key] = ($daTesV ? $daTesV : null) ;
                        }
                    }

                    // On remplace la valeur 'vide' par 'null' dans les foreight keys
                    if (!empty($fields[$key]['null']) AND is_string($data2[$key]) AND !Validation::notBlank($data2[$key])) $data2[$key] = null;
                }

            }

            if (!empty($data[$this->alias])) $data[$this->alias] =  $data2;
            elseif (!empty($data)) $data = $data2;
        }



        // Très sensisble, permet à modifier les ID, un calback avant et après enregistrementement
        if (empty($this->data)) {
            $this->data = $data;
        }

        if (empty($this->data[$this->alias])) {
            $this->data = array($this->alias => $data);
        }

        if (empty($data[$this->alias])) {
            $data = array($this->alias => $data);
        }

        //debug($this->create(false));
        $oldThisData = $this->data;
        $oldData = $data;
        $oldID = $this->id;

        $this->data = $data = Hash::merge($this->data, $data);

        if (empty($this->id) AND !empty($this->data[$this->alias][$this->primaryKey])) {
            $this->id = $this->data[$this->alias][$this->primaryKey];
        }

        if ($options['callbacks']) $this::beforeDoSave($data, $options);

        if ($this->id AND $oldID != $this->id) {
            // Au cas où on modifie la requeste d'un Create à Update ne ajoutant la valeur d'ID, très important à mettre pour eviter de merger la table avec les donnés d'une nouvelle table
            $unsetKeys = array_diff_key($oldThisData[$this->alias], $oldData[$this->alias]);
            foreach (array_keys($unsetKeys) as $unsetKey) {
                unset($this->data[$this->alias][$unsetKey]);
                unset($data[$this->alias][$unsetKey]);
            }
        }

        // On attribue ma valeur d'ID à celui des donnés pour eviter des faux Update
        if (!empty($this->id) AND !empty($this->data[$this->alias][$this->primaryKey])) {
            $data[$this->alias][$this->primaryKey] = $this->id;
        }


       return parent::_doSave($data, $options);

    }

    public function beforeDoSave($options = array()) {
        return true;
    }
    // AutoCache de requete (Ajouter 'cache' dans $query ou ['name' => 'other_cache','duration' => '+2 weeks'])
    public function find($type = 'first', $query = array()) {
        // Dans APP/Plugin/My/Model/MyAppModel.php
        // Juste avant l'exécution de la requête qui plante :

        $this->getDataSource()->execute("SET SESSION sql_mode = ''");

        // Ensuite, votre code actuel de find() qui plante :


        if(!empty($query['cache']) OR  (isset($query['conditions'][0]) AND $query['conditions'][0] === true)) {
            if (empty($query['cache'])) $query['cache'] = false;
            if (isset($query['conditions'][0]) AND $query['conditions'][0] === true) unset($query['conditions'][0]);
            $cacheName = md5(json_encode(array_merge(array($this->alias),$query)));

            if ($query['cache'] !== true) Cache::set('duration', $query['cache'], 'query');
            $cache = Cache::read($cacheName, 'query');

            if ($cache !== false) return unserialize(gzinflate($cache));

            $results = parent::find($type, $query);
            if ($query['cache'] !== true) Cache::set('duration', $query['cache'], 'query');
            Cache::write($cacheName, gzdeflate(serialize($results)), 'query');
           return $results;
        }else return parent::find($type, $query);
    }

    public function afterFind($results, $primary = false) {
        parent::afterFind($results, $primary);

        //On force les Callbacks des Behaviors lors de Containable
        if (!empty($this->hasAndBelongsToMany)) {
            $HABTOModels = array_keys($this->hasAndBelongsToMany);

            foreach ($HABTOModels as $key => $HABTOModel) {

                foreach (array_keys($results) as $key => $modelKeys) {
                    if (!empty($results[$modelKeys][$HABTOModel])) {

                        if (!empty($this->{$HABTOModel}->Media)) {
                            $results[$modelKeys][$HABTOModel] = $this->{$HABTOModel}->Media->afterFind($results[$modelKeys][$HABTOModel], true);

                            $results[$modelKeys][$HABTOModel] = $this->{$HABTOModel}->Behaviors->Media->afterFind($this->{$HABTOModel}, $results[$modelKeys][$HABTOModel], true);                            
                        }


                    }
                }
            }
        }

        if (!$primary) {
            if ($this->Behaviors->enabled('Media') AND in_array('Media', array_keys($this->hasMany))) {

                $lastQueryData = configure::read('lastMedia');

                if ($lastQueryData) {
                    
                    $lastQueryData = $this->Media->afterFind($lastQueryData, true); # forcing true on primary

                    $lastMediaData = array();
                    foreach ($lastQueryData as $lastQueryDataKey => $lastQueryDataValue) {
                        if (isset($lastQueryDataValue['Media']['ref_id']) AND $lastQueryDataValue['Media']['ref_id'] == @$results[0][$this->alias][$this->primaryKey]) {
                            $lastMediaData[] = $lastQueryDataValue['Media'];
                        }
                    }

                    $results[0]['Media'] = $lastMediaData;

                }
                
                $results = $this->Behaviors->Media->afterFind($this, $results, true); # forcing true on primary

            }
        }

        if (!in_array($this->alias, array('Session','Media'))) {

            foreach ($results as $key => $data) {

                // On remplace les valeurs FALSE/TRUE des booléans par '0'/'1' pour eviter les erreurs de validation
                if (!empty($data[$this->alias])) foreach ($data[$this->alias] as $key_model => $val) {

                    $schema = $this->_schema;   
                    if (!$schema) $schema = $this->schema();

                    if (@$schema[$key_model]['type'] == 'boolean') {

                        if(@$schema[$key_model]['null'] AND $val === null ) {

                        }elseif (empty($val)) {
                            $results[$key][$this->alias][$key_model] = '0';
                        }elseif ($val === true) {
                            $results[$key][$this->alias][$key_model] = '1';
                        }
                    }
                }


                foreach ($data as $model => $modelData) {
                    
                    if (empty($data[$model]) AND !in_array($model, array('Session','Media'))) {
                        //unset($results[$key][$model]); // On supprime les Modeles vides
                        continue;
                    }

                    // On deserialise les valeurs serialiser
                    foreach ($modelData as $field => $fieldvalue) {
                        if ($this->is_serial2($fieldvalue)) {
                            $unserializeval = @unserialize($fieldvalue);
                            if ($unserializeval !== null) {
                                $results[$key][$model][$field] = $unserializeval;
                            }
                        }
                    }

                }
            }
        }

        return $results;
    }

    public static function is_serial($string) {
        return (@unserialize($string) !== false || $string === 'b:0;');
    }

    public function is_serial2($s){

        if($s === 'b:0;' OR $s === 'a:0:{}' OR (is_string($s) && stristr($s, '{' ) != false && stristr($s, '}' ) != false && stristr($s, ';' ) != false && stristr($s, ':' ) != false)) return true;
        else return false;
    }

    // On supprime les caches lors de la suppression ou modifcation des données
    public function clearCacheQuery($defaultCacheName = 'query') {
        Cache::clear(false, $defaultCacheName);
    }

    public function path($options = array()) {

        App::uses('AuthComponent', 'Controller/Component');

        $replace = array(
            ':id1000'  => ceil($this->id / 1000),
            ':id100'   => ceil($this->id / 100),
            ':id'      => (@$options[$this->primaryKey] ? $options[$this->primaryKey] : $this->id),
            ':y'       => date('Y'),
            ':m'       => date('m'),
            ':uid'     => CakeSession::read(AuthComponent::$sessionKey.'.id'),
            ':md5'     => md5(rand() . uniqid() . time())
        );

        $goodpath = strtr($this->path, $replace);

        return $goodpath;
    }



    public function afterDelete() {
        if (!in_array($this->alias, array('Session','Media'))) $this->clearCacheQuery();

        // Supprimer chemin fichier dossier
        if ($this->path) {

            App::uses('AuthComponent', 'Controller/Component');

            $replace = array(
                ':id1000'  => ceil($this->id / 1000),
                ':id100'   => ceil($this->id / 100),
                ':id'      => $this->id,
                ':y'       => date('Y'),
                ':m'       => date('m'),
                ':uid'     => CakeSession::read(AuthComponent::$sessionKey.'.id'),
                ':md5'     => md5(rand() . uniqid() . time())
            );

            $goodpath = strtr($this->path, $replace);
            $goodpath = realpath(WWW_ROOT.strtr($this->path, $replace));

            if ($goodpath) {
                App::uses('Folder', 'Utility');
                $Folder = new Folder(WWW_ROOT);
                $Folder->delete($goodpath);
            }

        }

    }
    public function afterSave($created, $options = array()) {
    parent::afterSave($created, $options);

        if (!in_array($this->alias, array('Session','Media'))) $this->clearCacheQuery();
    }


    public function updateAll($fields, $conditions = true) {

        // On detecte si changement d'ID ou pas
        $changeId = false;
        foreach ($fields as $field => $value) {
        $field = explode('.', $field);
        $key = end($field);

            if ($key == $this->primaryKey) {
                if (!empty($conditions[$this->alias.'.'.$this->primaryKey])) $old_id = $conditions[$this->alias.'.'.$this->primaryKey];
                if (!empty($conditions[$this->primaryKey])) $old_id = $conditions[$this->primaryKey];
                $changeId = true;
                break;
            }
        }

        if ($changeId AND !empty($old_id) AND !$this->isForeignKey($this->primaryKey)) {

            if (empty($Contmodel = Router::getRequest()->data['Content']['model'])) $Contmodel = Router::getRequest()->data['Content']['model'] = $this->alias;
                else $Contmodel = Router::getRequest()->data['Content']['model'];
            if (empty(Router::getRequest()->data['Content']['primaryKey'])) $ContprimaryKey = Router::getRequest()->data['Content']['primaryKey'] = $this->primaryKey;
                else $ContprimaryKey = Router::getRequest()->data['Content']['primaryKey'];

            if (!empty(Router::getRequest()->data[$Contmodel][$ContprimaryKey])) $new_id = Router::getRequest()->data[$Contmodel][$ContprimaryKey];
            else {
                if (!empty($fields[$this->alias.'.'.$this->primaryKey])) $new_id = $fields[$this->alias.'.'.$this->primaryKey];
                if (!empty($fields[$this->primaryKey])) $new_id = $fields[$this->primaryKey];
            }
            
            if ($new_id != $old_id) unset($this->id);
            
            // On demare la transaction
            if ($Contmodel == $this->alias) $datasource = $this->getDataSource();

            try{
                if ($Contmodel == $this->alias) $datasource->begin();

                // On liste les associations liées au model
                $result = $this->getAssociated();

                // On conditionne les relations du type HasOne et BelongsTo
                $HasOneFields = $HasOneConditions = $BelongsToFields = $BelongsToConditions = $foreignKeys = array();
                foreach ($result as $class => $typeasc) {
                    if ($typeasc == 'hasOne') {
                        $rel = $this->{$typeasc}[$class];

                        $HasOneFields[$class][$class.'.'.$rel['foreignKey']] = "'".$new_id."'";
                        $HasOneConditions[$class][$class.'.'.$rel['foreignKey']] = $old_id;
                    }

                    if ($typeasc == 'belongsTo') {
                        $foreignKeys[$class] = $this->{$typeasc}[$class]['foreignKey'];

                        if ($foreignKeys[$class] == $this->primaryKey) {
                            $BelongsToFields[$class][$this->{$class}->alias.'.'.$this->{$class}->primaryKey] = "'".$new_id."'";
                            $BelongsToConditions[$class][$this->{$class}->alias.'.'.$this->{$class}->primaryKey] = $old_id;
                        }

                    }
                }


                // On sauvagegarde
                try{
                    // Au cas où L'ID n'est pas une clé etrangère, on fais une association sinon on commencera par le referent puis elle meme
                    foreach ($BelongsToConditions as $modelFor => $foreignKey) {
                        $this->{$modelFor}->updateAll($BelongsToFields[$modelFor], $BelongsToConditions[$modelFor]);
                    }
                    parent::updateAll(array_merge($fields), $conditions);

                    foreach ($HasOneConditions as $modelFor => $foreignKey) {
                        $this->{$modelFor}->updateAll($HasOneFields[$modelFor], $HasOneConditions[$modelFor]);
                    }

                } catch(Exception $e) {
                    debug($e);        
                    throw new Exception();
                }

                // On cherche la bonne configuration pour les hasMany
                foreach ($result as $class => $typeasc) {
                    if ($typeasc == 'hasMany') {
                        $rel = $this->{$typeasc}[$class];

                        try{
                            // On sauvegarde pour chacune de relation hasMany
                             $this->{$class}->updateAll(
                                array($class.'.'.$rel['foreignKey'] => "'$new_id'"),
                                array($class.'.'.$rel['foreignKey'] => $old_id)
                            );
                        } catch(Exception $e) {
                            debug($e);        
                            throw new Exception();
                        }

                    }
                }

                // On cherche la bonne configuration pour les hasAndBelongsToMany
                foreach ($result as $class => $typeasc) {
                    if ($typeasc == 'hasAndBelongsToMany') {
                        $rel = $this->{$typeasc}[$class];
                        $hasAndBelongsToManyconditions = array();
                        $hasAndBelongsToManyconditions[$rel['with'].'.'.$rel['foreignKey']] = $old_id;

                        if (!empty($rel['conditions']['ref'])) {
                            $hasAndBelongsToManyconditions[$rel['with'].'.ref'] = $rel['conditions']['ref'];
                        }elseif (!empty($rel['conditions'][$rel['with'].'.'.'ref']))  {
                            $hasAndBelongsToManyconditions[$rel['with'].'.ref'] = $rel['with'].'.'.$rel['conditions']['ref'];
                        }


                        try{

                            // On sauvegarde pour chacune de relation hasAndBelongsToMany
                            $this->{$rel['with']}->updateAll(
                                array($rel['with'].'.'.$rel['foreignKey'] => "'$new_id'"),
                                $hasAndBelongsToManyconditions
                            );
                        } catch(Exception $e) {
                            debug($e);        
                            throw new Exception();
                        }


                    }
                }

                // On cloture la transaction
                 if ($Contmodel == $this->alias) return $datasource->commit();
                                    
            } catch(Exception $e) {
                 if ($Contmodel == $this->alias) $datasource->rollback();
            }

        }else{
            return parent::updateAll($fields, $conditions);
        }
    }


}