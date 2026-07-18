<?php
class Town extends AppModel {

    public $actsAs = array(
        'Media.Media' => array(
            'extensions' => array('pdf','jpg','png','jpeg', 'gif'),
            'path' => '/img/town/%id/%md5'
        ),
    );

    public $belongsTo = array(
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id',
        ),
    );

    public $hasMany = [
        'TaxiCity' => [
            'className' => 'CompanyTaxiCity',
            'foreignKey' => 'town_id',
        ]
    ];

    
}