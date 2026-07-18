<?php

class AccountInfo extends AppModel {



    public function __construct($id = false, $table = null, $ds = null) {

        parent::__construct($id, $table, $ds);

        $this->construct();

        parent::__construct($id, $table, $ds);



    }



    public function construct() {



        if($this->name == 'AccountInfo'){



            $this->belongsTo = array();

            $this->belongsTo = Hash::merge($this->belongsTo, array(

                'Role' => array(

                    'className' => 'AccountRole',

                    'foreignKey' => 'role_id',

                ),

                'Nationality' => array(

                    'className' => 'AccountNationality',

                    'foreignKey' => 'nationality_id',

                    'counterCache' => array(

                        'user_count' => array(),

                    ),

                ),

            ));





            $this->hasOne = array(

                'User' => array(

                    'className' => 'User',

                    'foreignKey' => 'info_id',

                ),

            );



            $this->hasAndBelongsToMany = array(



            );





            $this->validate = array(



                'firstname' => array(

                    'required' => array(

                        'rule' => array('minLength', '2'),

                        'required' => true, 'allowEmpty' => false,

                        'message' => "Veuillez saisir un nom d'au moins 2 charactères..."

                    ),

                ),



                'name' => array(

                    'required' => array(

                        'rule' => array('minLength', '3'),

                        'required' => true, 'allowEmpty' => false,

                        'message' => "Veuillez saisir un nom d'au moins 3 charactères..."

                    ),

                ),



                'birthday' => array(

                    'isValid' => array(

                        'allowEmpty' => false,

                        'rule' => array('date'),

                        'message' => "S'il vous plaît, une date de naissance correct..."

                    ),

                    'required' => [
                        'required' => true,
                        'allowEmpty' => false,

                        'message' => "Veuillez insérer votre date de naissance"

                    ],



                    'isMajor' => array(

                        'allowEmpty' => false,

                        'rule' => array('isMajor', 0),

                        'message' => 'Vous devez entrer une date valide',

                    ),

                ),



                'profil_file' => array(

        /*            'isUpload' => array(

                        'required' => false, 'allowEmpty' => true,

                        'rule' => array('fileIsUpload', array('allowEmpty' => true)),

                        'message' => 'Vous ne pouvez pas envoyer une photo vide',

                    ),

        */

                    'extension' => array(

                        'required' => false, 'allowEmpty' => true,

                        'rule' => array('fileExtension', array('jpg','png','jpeg', 'gif'), 'allowEmpty' => true),

                        'message' => 'Une photo valide',

                    ),



                ),



                'paiementidentity_file' => array(

                    'extension' => array(

                        'required' => false, 'allowEmpty' => true,

                        'rule' => array('fileExtension', array('jpg','png','jpeg', 'gif', 'pdf'), 'allowEmpty' => true),

                        'message' => 'Un fichier image ou pdf',

                    ),



                ),



                'phone' => array(

                    'isUnique' => array(

                        'rule' => array('isUnique', 'phone'),

                        'message' => 'Ce numéro de téléphone est déjà utilisé ...'

                    ),

                    'verifiertel' => array(

                        //'required' => true, 'allowEmpty' => true,
                        'required' => true, 'allowEmpty' => false,

                        'rule' => array('isphone', 'indicatif'),

                        'message' => "S'il vous plaît, indiquez un numero de telephone valide..."

                    ),

                ),





                'identity_file' => array(

                    /*

                    'isUpload' => array(

                        'required' => false, 'allowEmpty' => true,

                        'rule' => 'fileIsUpload',

                        'message' => "Veuillez fournir votre pièce d'identité",

                    ),

                    */



                   'Identity' => array(

                        'required' => false, 'allowEmpty' => true,

                        'rule' => 'cardidentity',

                        'message' => "Merci de nous soumettre une bonne pièce d'identité et bien visible",

                    ),

                    'extension' => array(

                        'required' => false, 'allowEmpty' => true,

                        'rule' => array('fileExtension', array('jpg','png','jpeg', 'gif'), 'allowEmpty' => true),

                        'message' => 'Une piste en format *.mp3 Valide!',

                    ),



                ),





                /*



                'identity2_file' => array(

                    /*

                    'isUpload' => array(

                        'required' => false, 'allowEmpty' => true,

                        'rule' => 'fileIsUpload',

                        'message' => "Veuillez fournir votre pièce d'identité",

                    ),



                   'Identity' => array(

                        'required' => false, 'allowEmpty' => true,

                        'rule' => 'cardidentity',

                        'message' => "Merci de nous soumettre une bonne pièce d'identité et bien visible",

                    ),

                    'extension' => array(

                        'required' => false, 'allowEmpty' => true,

                        'rule' => array('fileExtension', array('jpg','png','jpeg', 'gif'), 'allowEmpty' => true),

                        'message' => 'Une piste en format *.mp3 Valide!',

                    ),



                ),



                */





                'birthday' => array(

                    'isValid' => array(

                        'rule' => array('date'),

                        'required' => false, 'allowEmpty' => true,

                        'message' => "S'il vous plaît, une date de naissance correct..."

                    ),

                ),





                'gender' => array(

                    'isValid' => array(

                        'required' => true, 'allowEmpty' => false,

                        'rule' => array('inList', array('m', 'f')),

                        'message' => 'Vous devez specifier votre sexe',

                    ),

                ),



            );





            $this->virtualFields['fullname'] = sprintf('CONCAT(%s.firstname, " ", %s.name)', $this->alias, $this->alias);

            $this->displayField = 'fullname';





        }else{



            $this->hasOne = array(

                'Info' => array(

                    'className' => 'AccountInfo',

                    'foreignKey' => 'ref_id',

                    'conditions' => array('ref' => $this->alias),

                ),

            );





            $this->virtualFields['fullname'] = sprintf('CONCAT(%s.firstname, " ", %s.name)', $this->alias, $this->alias);

            $this->displayField = 'fullname';







            $this->validate = array(

                'name' => array(

                    'required' => array(

                        'required' => true, 'allowEmpty' => false,

                        'rule' => array('notBlank'),

                        'message' => "Un nom d'utilisateur est requis"

                    ),

                ),

            );



        }



    }





    public $actsAs = array(

        'Upload.Upload' => array(

            'fields' => array(

                'profil' => 'img/profil/:md5',

                'identity' => 'img/iden1/:md5',

            ),

        ),

    );







    public function afterFind($results, $primary = false) {

        $results = parent::afterFind($results, $primary);



        if($this->name != 'AccountInfo'){



            if ($results) {



                if(!$this->Info){

                    $this->construct();

                }



                $ParentDatas = $this->Info->find('all', array(

                    'cache' => '+3 sec',

                    'callbacks' => 'before',

                    'conditions' => array(

                        'ref' => array($this->alias, $this->name),

                    )

                ));



                $ParentDatas = Hash::combine($ParentDatas, '{n}.Info.ref_id', '{n}');

            }



            foreach ($results as $key => $val) {



                $ParentData = array();

                if (!empty($val[$this->alias])) {

                    if ($val[$this->alias]['id'] AND !empty($ParentDatas[ $val[$this->alias]['id'] ]['Info'])) {

                        $ParentData = Hash::merge($ParentDatas[ $val[$this->alias]['id'] ]['Info'], $val[$this->alias]);

                    }else{

                        $ParentData = $val[$this->alias];

                    }

                }



                $results[$key][$this->alias] = $ParentData;

            }



            /*

            foreach ($results as $key => $val) {

                // On desatribue pour l'administrateur et le manageur pour eviter des erreurs

                if (!in_array(Router::getParams()['plugin'], Configure::read('backend'))) {



                    if (isset($results[$key][$this->alias]) AND in_array('profil', array_keys(($results[$key][$this->alias])))) {

                        $profil = $results[$key][$this->alias]['profil'];

                        if (empty($profil) OR !file_exists(WWW_ROOT.$profil)) {

                            $results[$key][$this->alias]['profil'] = '/img/emptyprofil.png';

                        }

                    }



                }



            }

            */



        }



        foreach ($results as $key => $val) {

            // On desatribue pour l'administrateur et le manageur pour eviter des erreurs

            if (!in_array(Router::getParams()['plugin'], Configure::read('backend'))) {



                if (isset($results[$key][$this->alias]) AND in_array('profil', array_keys(($results[$key][$this->alias])))) {

                    $profil = $results[$key][$this->alias]['profil'];

                    if (empty($profil) OR !file_exists(WWW_ROOT.$profil)) {

                        $results[$key][$this->alias]['profil'] = '/img/emptyprofil.png';

                    }

                }



            }



        }



        return $results;



    }

















    public function cardidentity($check, $params = array()){



        $validation = true;



        $file = $check;

        if (!isset($check['tmp_name'])) {

            $file = current($check);        

        }



        //$field_file = array_keys($check)[0];

        //$_file = '_file';

        //$field = substr_replace($field_file, '', strlen($field_file)- strlen($_file), strlen($_file));





        if (!empty($file['tmp_name']) AND Validation::uploadedFile($file, $params)) {





            App::import('Vendor', 'autoload', array('file' => 'ocr/vendor/autoload.php'));



        

            //debug($file['tmp_name']);



            //debug($file);





            $ocr = Op::ocr($file);



            $ocrs= preg_replace("(\r\n|\n|\r)",' ',$ocr);

            if(strlen($ocrs) < 150 AND strlen($ocrs) > 600){

                $validation = false;

            }



            $ocrs = explode(' ',$ocrs);







            $keywords = array('passport','nom', 'électeur', 'république', 'france', 'code', 'sexe', 'delivrance', 'nationalité', 'émission', 'MINAFFET', 'date de naissance', 'lieu de naissance', 'COD', 'CONGOLAISE', 'CONGOLESE', 'certifié', 'élections', 'locales', 'municipales', 'adresse', 'permis', 'conduire', 'driving', 'licence', 'nom/name', 'prenom/firstname', 'naissance/date', 'adresse/home', 'birth', 'delivrance/date');







            $refdata = $this->findById($this->id);



            $UserFields = array('firstname', 'name', 'birthday');

            foreach ($UserFields as $key => $Field) {



                foreach (array($refdata, $this->data) as $key => $UseData) {



                    if (!empty($UseData[$this->alias][$Field])) {



                        $keywords[] = $UseData[$this->alias][$Field];

                        if (Validation::date($UseData[$this->alias][$Field])) {

                            $keywords[] = Caketime::format('d/m/Y', $UseData[$this->alias][$Field]);

                        }



                    }

                }



            }





            $map = Inflector::$_transliteration;

            

            $keywords = preg_replace(array_keys($map), array_values($map), $keywords);

            $ocrs = preg_replace(array_keys($map), array_values($map), $ocrs);





            $countL = array();

            foreach ($ocrs as $key => $wd) {

                $wd = strtolower($wd);





                $levenref = 2;

                if (strlen($wd) == 4) {

                    $levenref = 1;

                }elseif (strlen($wd) <= 4) {

                    continue;

                }



                foreach ($keywords as $key => $kw) {

                    $kw = strtolower($kw);



                    if (levenshtein($wd, $kw) <= $levenref) {

                        //debug($wd.' - '.$kw);

                        //debug(levenshtein($wd, $kw));

                        $countL[$kw] = 1;

                    }



                }



            }





            if(count($countL) < 1){

                $validation = false;

            }



        }



        return $validation;





    }













}