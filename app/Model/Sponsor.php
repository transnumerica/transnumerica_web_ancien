<?php
class Sponsor extends AppModel {




    public $hasMany = array(
        'Publicity' => array(
            'className' => 'Publicity',
            'foreignKey' => 'Sponsor_id',
            //'dependent' => true,
        ),
    );




}