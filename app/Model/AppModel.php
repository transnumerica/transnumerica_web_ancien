<?php
App::uses('Model', 'Model');
App::uses('MyAppModel', 'My.Model');
class AppModel extends MyAppModel {

    public $actsAs = array('Containable','My.Op');
    public $validationDomain = 'tra';
    //public $useDbConfig = 'default';


}