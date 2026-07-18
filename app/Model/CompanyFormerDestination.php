<?php

class CompanyFormerDestination extends AppModel {


    public $belongsTo = array(



        'Former' => array(

            'className' => 'CompanyFormer',

            'foreignKey' => 'former_id',

        ),

        'From' => array(

            'className' => 'Town',

            'foreignKey' => 'from_town_id',

        ),

        'To' => array(

            'className' => 'Town',

            'foreignKey' => 'to_town_id',

        ),
        
        'Currency' => array(

            'className' => 'Currency',

            'foreignKey' => 'currency_id',

        ),




    );


}