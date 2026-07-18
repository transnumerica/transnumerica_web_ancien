<?php

class BrwUser extends AppModel {

    public $brwConfig = array(
        'actions' => array(
            'index' => true,
            'edit' => false,
            'delete' => false,
        ),
    );


    public $name='BrwUser';

}