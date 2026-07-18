<?php
class Texte extends AppModel {

    public $actsAs = array(
        'Media.Media' => array(
            'extensions' => array('pdf','jpg','png','jpeg', 'gif'),
            'path' => '/img/texte/%id/%md5'
        ),
        'Upload.Upload' => array(
            'fields' => array(
                'file1' => 'img/text/:md5',
            ),
        ),
    );

    public $validate = array(

        'key' => array(
            'required' => array(
                'rule' => array('minLength', 'notBlank'),
                'required' => true, 'allowEmpty' => false,
                'message' => "Ce champs ne peut pas être vide..."
            ),
        ),

    );
    
}