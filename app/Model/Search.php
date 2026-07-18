<?php
class Search extends AppModel {

    public $brwConfig = array(
        'actions' => array('add' => false, 'edit' => false, 'export' => true),
         'fields' => array(
            'no_add' => array('password', 'passwd'),
            'no_edit' => array('password', 'passwd'),
        ),
    );

    public $primaryKeyArray = array('md5');

    public function beforeSave($options = array()) {

        parent::beforeSave($options);

        // On sauvegarde aussi l'ip et les info sur navigateur de l'utilisateur

        $this->data[$this->alias]['ip'] = Router::getRequest()->clientIp();

        $this->data[$this->alias]['browser'] = serialize(Router::getRequest()->navigateur);

    }





}