
<?php
App::uses('AppModel', 'Model');

class Requete extends AppModel {
    // CakePHP utilise le pluriel par défaut. On force le nom de la table ici.
    public $useTable = 'requetes';

/*
    // Optionnel : validations de base
    public $validate = array(
        'request_method' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'La méthode HTTP est requise.'
            )
        )
    );
*/

}