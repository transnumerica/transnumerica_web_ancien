<?php
class MarchandController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		//$this->Security->unlockedActions = array('login');


		//$this->Auth->allow();

		
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
		$this->set('espaceMarchand', true);

		$merchantServices = $this->getMerchantServices();

		if(count($merchantServices)==0) $this->redirect(
			['controller'=>'Home', 'action'=>'index']
		);

		$this->set('merchantServices', $merchantServices);
		
		//debug($Publicities);
	}

	public function index() {
		$merchantServices = $this->getMerchantServices();
		if(!in_array('bus', $merchantServices)){
			if(count($merchantServices)>0) $this->redirect(['controller'=>'Marchand', 'action'=>$merchantServices[0]]);
			else $this->redirect(['controller'=>'Home', 'action'=>'index']);
		}

		$rubrique = 'bus';
		$this->set('rub', $rubrique);
		$this->set('rubrique', $rubrique);

		$userId = $this->Auth->user('id');
		$busTowns = $this->townsCategorifiedByTownFromTo('CompanyDestination', false, ['userId' => $userId, 'categorie'=>$rubrique]);
		$this->set('KTownsJson', json_encode($busTowns));

		
		
	}

	public function vol(){
		$rubrique = 'vol';
		$this->set('rub', $rubrique);
		$this->set('rubrique', $rubrique);

		$userId = $this->Auth->user('id');
		$planeTowns = $this->townsCategorifiedByTownFromTo('CompanyPlaneDestination', true, ['userId' => $userId, 'categorie'=>$rubrique]);
		$this->set('KTownsJson', json_encode($planeTowns));

		$this->render('/Marchand/index');
	}

	public function hotel(){
		$rubrique = 'hotel';
		$this->set('rub', $rubrique);
		$this->set('rubrique', $rubrique);

		$userId = $this->Auth->user('id');
		$tab = $this->townsCategorifiedByTown('CompanyHotel', ['userId' => $userId, 'categorie'=>$rubrique]);
		$array = [];
		foreach ($tab as $value) {
			$array[$value]="1";
		}
		
		$this->set('KTownsJson', json_encode($array));

		$this->render('/Marchand/index');
	}

	public function train(){
		$rubrique = 'train';
		$this->set('rub', $rubrique);
		$this->set('rubrique', $rubrique);

		$userId = $this->Auth->user('id');
		$formerTowns = $this->townsCategorifiedByTownFromTo('CompanyFormerDestination', true, ['userId' => $userId, 'categorie'=>$rubrique]);
		$this->set('KTownsJson', json_encode($formerTowns));

		$this->render('/Marchand/index');
	}

	public function taxi(){
		$rubrique = 'taxi';
		$this->set('rub', $rubrique);
		$this->set('rubrique', $rubrique);

		$userId = $this->Auth->user('id');
		$tab = $this->townsCategorifiedByTown('CompanyTaxi', ['userId' => $userId, 'categorie'=>$rubrique]);
		$array = [];
		foreach ($tab as $value) {
			$array[$value]="1";
		}
		
		$this->set('KTownsJson', json_encode($array));

		$this->render('/Marchand/index');
	}




}