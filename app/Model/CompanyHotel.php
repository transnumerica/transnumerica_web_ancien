<?php

class CompanyHotel extends AppModel {



    public $actsAs = array(

        'Media.Media' => array(

            'extensions' => array('pdf','jpg','png','jpeg', 'gif'),

            'path' => '/img/hotel/%id/%md5'

        ),

    );



    public $belongsTo = array(

        'Town' => array(

            'className' => 'Town',

            'foreignKey' => 'town_id',

        ),

        'Currency' => [
            'className' => 'Currency',
            'foreignKey' => 'currency_id'
        ],

        'User' => array(

            'className' => 'User',

            'foreignKey' => 'user_id',
            'fields' => ['idents'],
            'order' => ['email'=>'asc', 'id'],

        ),

    );

    public $hasMany = array(

        'HotelRoom' => array(

            'className' => 'CompanyHotelRoom',
            'foreignKey' => 'hotel_id',
            'dependent' => false,

        ),

    );



    public function afterFind($results, $primary = false){
        forEach($results as &$res){
            //$res = "vers le trésor";
            
            $description = &$res["CompanyHotel"]["description"];
            $adresse = &$res["CompanyHotel"]["adresse"];
            
            $description = strip_tags($description);//htmlentities(htmlspecialchars($description, ENT_QUOTES, 'UTF-8'));
            $adresse = strip_tags("adresse");//htmlspecialchars($adresse);
        }

        return $results;
    }
/*


    public  $hasAndBelongsToMany = array(

        'Tag' => array(

            'className'  => 'CompanyTag',

            'associationForeignKey' => 'tag_id',

            'foreignKey' => 'car_id',

            'with'      => 'CompanyCarRelation',

            'unique' => 'keepExisting',

        ),



    );

*/

}