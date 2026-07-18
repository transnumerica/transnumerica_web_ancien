<?php
class TolekaOrder extends AppModel {

    public $brwConfig = array(
        'actions' => array(
            //'add' => false,
            'export' => false, 
            'index' => true,
            //'edit' => false,
            //'delete' => false
        ),
    );



    public $belongsTo = array(

        'CompanyTaxi' => array(
            'className' => 'CompanyTaxi',
            'foreignKey' => 'company_taxi_id',
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
        'Chauffeur' => array(
            'className' => 'User',
            'foreignKey' => 'chauffeur_user_id',
        ),
        
        
    );

    public function beforeSave($options = array()) {
        parent::beforeSave($options);

        // New Command ID
        
        if (!is_null(@$this->data[$this->alias]['route_lat_lng'])) {
            $this->data[$this->alias]['route_lat_lng'] = serialize($this->data[$this->alias]['route_lat_lng']);      
        }
        

    }


}