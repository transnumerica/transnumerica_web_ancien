<?php
class CurrencyChange extends AppModel {

    public $primaryKeyArray = array('from', 'to');

    public $brwConfig = array(
        'actions' => array('add' => true,'export' => true,'delete' => false),
         'fields' => array(
            'no_add' => array('password', 'passwd'),
            'no_edit' => array('password', 'passwd'),
        ),
    );

    public $belongsTo = array(
        'Formcurrency' => array(
            'className' => 'Currency',
            'foreignKey' => 'from',
        ),
        'Tocurrency' => array(
            'className' => 'Currency',
            'foreignKey' => 'to',
        ),
    );


}