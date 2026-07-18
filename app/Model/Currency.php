<?php

class Currency extends AppModel {



    public $brwConfig = array(

        'actions' => array('add' => true,'export' => true),

         'fields' => array(

            'no_add' => array('password', 'passwd'),

            'no_edit' => array('password', 'passwd'),

        ),

    );

    public $hasMany = array(
        'Change' => array(
            'className' => 'CurrencyChange',
            'foreignKey' => 'from',
            'dependent' => true,
        ),
    );

    public  $hasAndBelongsToMany = array(

        /*

        'Country' => array(

            'className'  => 'Country',

            'associationForeignKey' => 'country_id',

            'foreignKey' => 'currency_id',

            //'joinTable'  => 'currency_relations',

            'with'      => 'CountryCurrencyRelation',

            'unique' => 'keepExisting',

        ),

        */



    );





}