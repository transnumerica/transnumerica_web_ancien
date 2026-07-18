<?php
class CompanyTag extends AppModel {

    public  $hasAndBelongsToMany = array(
        'Car' => array(
            'className'  => 'CompanyCar',
            'associationForeignKey' => 'car_id',
            'foreignKey' => 'tag_id',
            'with'      => 'CompanyCarRelation',
            'unique' => 'keepExisting',
        ),

    );

}