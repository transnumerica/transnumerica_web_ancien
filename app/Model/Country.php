<?php
class Country extends AppModel {

    public $brwConfig = array(
        'actions' => array(
            'add' => false,
        ),
    );


    public $actsAs = array(
        'Upload.Upload' => array(
            'fields' => array(
                'cover' => 'img/country/:md5',
            ),
        ),
    );



    public $validate = array(

        'cover_file' => array(
	        'rule' => array('fileExtension', array('gif', 'png', 'jpg', 'jpeg')),
			'required' => false, 'allowEmpty' => true,
            'message' => "Veuillez selectionner une image uniquement"
	
        ),

    );
    




}