<?php
class CompanyCar extends AppModel {

    public $actsAs = array(
        'Media.Media' => array(
            'extensions' => array('pdf','jpg','png','jpeg', 'gif'),
            'path' => '/img/car/%id/%md5'
        ),
    );

    public $belongsTo = array(
        'Company' => array(
            'className' => 'Company',
            'foreignKey' => 'company_id',
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