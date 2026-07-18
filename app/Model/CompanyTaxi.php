<?php

class CompanyTaxi extends AppModel {



    public $actsAs = array(

        'Media.Media' => array(

            'extensions' => array('pdf','jpg','png','jpeg', 'gif'),

            'path' => '/img/taxi/%id/%md5'

        ),

    );



    public $belongsTo = array(

        'Town' => array(

            'className' => 'Town',

            'foreignKey' => 'town_id',

        ),
/*
        'Currency' => array(

            'className' => 'Currency',

            'foreignKey' => 'currency_id',

        ),
        */
        'User' => array(

            'className' => 'User',

            'foreignKey' => 'user_id',
            'fields' => ['idents'],
            'order' => ['email'=>'asc', 'id'],

        ),

    );



    public  $hasAndBelongsToMany = array(

        'Tag' => array(

            'className'  => 'CompanyTag',

            'associationForeignKey' => 'tag_id',

            'foreignKey' => 'car_id',

            'with'      => 'CompanyCarRelation',

            'unique' => 'keepExisting',

        ),



    );



}