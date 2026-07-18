<?php
class HomeController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		//$this->Security->unlockedActions = array('login');
		$this->Auth->allow();
		$this->set('read_en_savoir_plus', true);

		$TTowns = $this->Town->find('all', array(
			'contain' => array('Media', 'Country'),
			'conditions' => array('Country.code' => CakeSession::read('Localisation')),
		));
		$this->set('TTowns', $TTowns);


		$Publicities = $this->Publicity->find('all', array(
			'limit' => 6,
			'order' => ['Publicity.id'=>'desc'],//'rand()',
		));
		$this->set('Publicities', $Publicities);
		$this->set('rubrique', 'home');

		$merchantServices = $this->getMerchantServices();
		$this->set('merchantServices', $merchantServices);
		//debug($Publicities);
	}

	public function index() {
		$rub = 'bus';
		$this->set('rub', $rub);
		$this->set('rubrique', $rub);
		
		
		$busTowns = $this->townsCategorifiedByTownFromTo('CompanyDestination', false);
		$this->set('KTownsJson', json_encode($busTowns));
		
	}

	public function bus(){
		$this->redirect('/');
	}

	public function vol(){
		$rub = 'vol';
		$this->set('rub', $rub);
		$this->set('rubrique', $rub);
		

		$planeTowns = $this->townsCategorifiedByTownFromTo('CompanyPlaneDestination', false);
		$this->set('KTownsJson', json_encode($planeTowns));
	}

	public function hotel(){
		$rub = 'hotel';
		$this->set('rub', $rub);
		$this->set('rubrique', $rub);


		$tab = $this->townsCategorifiedByTown('CompanyHotel');
		$array = [];
		foreach ($tab as $value) {
			$array[$value]="1";
		}
		
		$this->set('KTownsJson', json_encode($array));
	}

	public function train(){
		$rub = 'train';
		$this->set('rub', $rub);
		$this->set('rubrique', $rub);


		$formerTowns = $this->townsCategorifiedByTownFromTo('CompanyFormerDestination');
		$this->set('KTownsJson', json_encode($formerTowns));
	}

	public function taxi(){
		$rub = 'taxi';
		$this->set('rub', $rub);
		$this->set('rubrique', $rub);


		$tab = $this->townsCategorifiedByTown('CompanyTaxi');
		$array = [];
		foreach ($tab as $value) {
			$array[$value]="1";
		}
		
		$this->set('KTownsJson', json_encode($array));
	}




}