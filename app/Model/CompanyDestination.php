<?php
class CompanyDestination extends AppModel {

    public $hasMany = array(
        'Itinerary' => array(
            'className' => 'CompanyDestinationItinerary',
            'foreignKey' => 'destination_id',
            'dependent' => false,
        ),
        'Schedule' => array(
            'className' => 'CompanyDestinationSchedule',
            'foreignKey' => 'destination_id',
            'dependent' => false,
        ),
        'Rate' => array(
            'className' => 'CompanyDestinationRate',
            'foreignKey' => 'destination_id',
            'dependent' => false,
        ),
    );


    public $belongsTo = array(

        'Car' => array(
            'className' => 'CompanyCar',
            'foreignKey' => 'car_id',
        ),
        
        'From' => array(
            'className' => 'Town',
            'foreignKey' => 'from_town_id',
        ),
        'To' => array(
            'className' => 'Town',
            'foreignKey' => 'to_town_id',
        ),
        'Company' => array(
            'className' => 'Company',
            'foreignKey' => 'company_id',
        ),
        'Currency' => array(
            'className' => 'Currency',
            'foreignKey' => 'currency_id',
        ),

    );

}