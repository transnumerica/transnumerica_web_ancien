<?php
class CompanyDestinationSchedule extends AppModel {

    public $belongsTo = array(
        'Destination' => array(
            'className' => 'CompanyDestination',
            'foreignKey' => 'destination_id',
        ),

        'SDay' => array(
            'className' => 'Day',
            'foreignKey' => 'star_day',
        ),

        'EDay' => array(
            'className' => 'Day',
            'foreignKey' => 'end_day',
        ),

        'Car' => array(
            'className' => 'CompanyCar',
            'foreignKey' => 'remplacement_car_id',
        ),

    );

}