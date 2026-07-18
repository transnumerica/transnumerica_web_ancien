<?php

class CompanyTaxiCity extends AppModel {
/*
    public $actsAs = array(

        'Media.Media' => array(

            'extensions' => array('pdf','jpg','png','jpeg', 'gif'),

            'path' => '/img/hotel_room/%id/%md5'

        ),

    );
*/
    public $belongsTo = array(



        'Town' => array(

            'className' => 'Town',

            'foreignKey' => 'town_id',

        ),

        
        'Currency' => array(

            'className' => 'Currency',

            'foreignKey' => 'currency_id',

        ),




    );

    public function afterFind($results, $primary = false){
        forEach($results as &$res){
            //$res = "vers le trésor";
            
            $description = &$res["CompanyTaxiDestination"]["description"];
            $description = strip_tags($description);//htmlentities(htmlspecialchars($description, ENT_QUOTES, 'UTF-8'));
            
        }

        return $results;
    }


}