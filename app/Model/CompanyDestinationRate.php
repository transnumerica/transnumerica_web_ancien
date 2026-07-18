<?php

class CompanyDestinationRate extends AppModel {

    public $brwConfig = array(
        'actions' => array(
            //'add' => false,
            'export' => false
        ),
    );


    public $belongsTo = array(
        'Destination' => array(
            'className' => 'CompanyDestination',
            'foreignKey' => 'destination_id',
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