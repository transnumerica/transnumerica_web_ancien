<?php
class PolicyController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		//$this->Security->unlockedActions = array('login');
		$this->Auth->allow();
		
	}

	public function index() {
		
		
	}



}