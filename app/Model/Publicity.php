<?php
class Publicity extends AppModel {

    public $actsAs = array(
        'Upload.Upload' => array(
            'fields' => array(
                'cover' => 'img/puc/:md5',
            ),
        ),
    );

    public $belongsTo = array(
        'Sponsor' => array(
            'className' => 'Sponsor',
            'foreignKey' => 'sponsor_id',
        ),
    );

    public $validate = array(



    

    );
    

    public function beforeValidate($options = array()) {

        parent::beforeValidate($options);

        if(Validation::notBlank($this->data[$this->alias]['youtube']) AND in_array('youtube', $options['fieldList'])) {

            $this->validate = Hash::merge(array(
                'youtube' => array(
                    'required' => array(
                        'rule' => array('minLength', '16'),
                        'required' => true, 'allowEmpty' => false,
                        'message' => "Veuillez installer un lien Youtube valable"
                    ),
                ),
            ), $this->validate);            

        }else{

            $this->validate = Hash::merge(array(
                'cover_file' => array(
                    'fileIsUpload' => array(
                        'rule' => array('fileIsUpload'),
                        'required' => false, 'allowEmpty' => true,
                        'message' => "Veuillez selectionner une image"
                        ),
                    'fileExtension' => array(
                        'rule' => array('fileExtension', array('gif', 'png', 'jpg', 'jpeg')),
                        'required' => false, 'allowEmpty' => true,
                        'message' => "Veuillez selectionner une image uniquement"
                    ),
                )
            ), $this->validate);

        }




    }


}