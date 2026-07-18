<?php

class MarchandAdmin extends AppModel {



    public $brwConfig = array(
        'fields' => array(

            
            'no_edit' => array('id'),

        ),


    );



    public $belongsTo = array(

     'User' => array(

            'className' => 'User',

            'foreignKey' => 'user_id',
            
            'fields' => ['idents'],
            'order' => ['email'=>'asc', 'id'],
        ),

     );

     





}