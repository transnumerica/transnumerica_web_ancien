<?php

class CompanyPlane extends AppModel {


/*
    public $actsAs = array(

        'Media.Media' => array(

            'extensions' => array('pdf','jpg','png','jpeg', 'gif'),

            'path' => '/img/car/%id/%md5'

        ),

    );

*/
    public $hasMany = [
        'PlaneDestination' => [
            'className' => 'CompanyPlaneDestination',
            'foreignKey' => 'plane_id',
            'dependent' => false,
        ],
    ];

    public $belongsTo = array(

        'User' => array(

            'className' => 'User',

            'foreignKey' => 'user_id',
            'fields' => ['idents'],
            'order' => ['email'=>'asc', 'id'],

        ),

    );

    /*

   


    public  $hasAndBelongsToMany = array(

        'Tag' => array(

            'className'  => 'CompanyTag',

            'associationForeignKey' => 'tag_id',

            'foreignKey' => 'car_id',

            'with'      => 'CompanyCarRelation',

            'unique' => 'keepExisting',

        ),



    );

*/

}