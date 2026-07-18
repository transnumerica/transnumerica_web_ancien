<?php

class Company extends AppModel {

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        
    }

    public $hasMany = array(

        'Destination' => array(

            'className' => 'CompanyDestination',

            'foreignKey' => 'company_id',

            'dependent' => false,

        ),

        'Car' => array(

            'className' => 'CompanyCar',

            'foreignKey' => 'company_id',

            'dependent' => false,

        ),

    );

    
    public $belongsTo = array(

        'User' => array(

            'className' => 'User',

            'foreignKey' => 'user_id',
            'fields' => ['idents'],
            'order' => ['email'=>'asc', 'id'],

        ),

    );




}