<?php
class CompanyDestinationItinerary extends AppModel {

    public $belongsTo = array(
        'Destination' => array(
            'className' => 'CompanyDestination',
            'foreignKey' => 'destination_id',
        ),

        'To' => array(
            'className' => 'Town',
            'foreignKey' => 'to_town_id',
        ),

    );

}