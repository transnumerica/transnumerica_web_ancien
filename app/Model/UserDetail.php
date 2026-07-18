<?php
class UserDetail extends AppModel {

    public $brwConfig = array(
        'actions' => array(
            'edit' => false,
        ),
    );

    public $belongsTo = array(
     'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'exclusive' => true,
        ),
     );

    public function beforeSave($options = array()) {
        parent::beforeSave($options);
        // On sauvegarde aussi l'ip et les info sur navigateur de l'utilisateur
        $this->data[$this->alias]['ip'] = Router::getRequest()->clientIp();
        $this->data[$this->alias]['navigateur'] = serialize(Router::getRequest()->navigateur);
    }
}