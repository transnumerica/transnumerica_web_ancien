<?php

//App::uses('GeoLoc', 'GeoLoc.Lib');

class UsersController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
		//$this->Security->unlockedActions = array('login');
		$this->Auth->allow(array('reg', 'reg_code', 'online', 'dark', 'reset_password', 'passforgot', 'passforgotsend', 'passforgotsave', 'passforgotcancel', 'save_pass_test', 'delete'));

		$authUser = $this->Auth->user();
		if ($authUser and in_array($this->request->action, array('reset_password'))) {
			$this->redirect('/');
		}

		if ($this->request->is('post') or $this->request->is('put')) {

			if (isset($this->request->data['form']) and $this->request->data['form'] == 'createBankAccount') {
				if ($this->request->data['type'] == 'Saving') {
					$this->redirect(array('controller' => 'users', 'action' => 'saving_create'));
				} elseif ($this->request->data['type'] == 'Bwakisa') {
					$this->redirect(array('controller' => 'users', 'action' => 'bwakisa_create'));
				}
			}
		}

	}


	public function beforeRender()
	{
		parent::beforeRender();


	}



	private function transaction_need()
	{

		$AuthUser = $this->AuthUser();





		// Debut

		$Froms = $this->Operator->Entity->OperatorEntityCurrency->find('all', array(
			'order' => array('Country.id' => 'ASC'),
			'contain' => array('Currency', 'Entity.Country', 'Entity.Operator.Group'),
			'conditions' => array(
				'Country.id' => $AuthUser['Country']['id'],
				'OperatorEntityCurrency.send' => true,
				'Entity.send' => true,
				'Operator.send' => true,
			),
		));



		$ListToCountries = array();
		$toCountryDefault = null;
		foreach ($Froms as $key => $From) {


			$ListCode[$From['Entity']['Country']['id']]['c'] = $From['Entity']['Country']['code'];
			$ListCode[$From['Entity']['Country']['id']]['n'] = $From['Entity']['Country']['mobile_code'];


			$Operator = $From['Entity']['Operator'];

			$ListFromOperators[$Operator['id']]['name'] = $Operator['name'];
			$ListFromOperators[$Operator['id']]['value'] = $Operator['id'];
			$ListFromOperators[$Operator['id']]['data-subtext'] = $Operator['speed'];


			$ListFromOperatorCurrencies[$Operator['id']][$From['Currency']['id']] = $From['Currency']['id'];

			$ListFromCurrencies[$From['Currency']['id']]['name'] = $From['Currency']['iso'];
			$ListFromCurrencies[$From['Currency']['id']]['value'] = $From['Currency']['id'];
			$ListFromCurrencies[$From['Currency']['id']]['data-subtext'] = $From['Currency']['name'];

		}


		$this->set('ListFromOperators', $ListFromOperators);

		$this->set('ListFromOperatorCurrencies', $ListFromOperatorCurrencies);

		$this->set('ListFromCurrencies', $ListFromCurrencies);

		// Fin









		// Debut

		$Tos = $this->Operator->Entity->OperatorEntityCurrency->find('all', array(
			'order' => array('Country.id' => 'ASC'),
			'contain' => array('Currency', 'Entity.Country', 'Entity.Operator.Group'),
			'conditions' => array(
				'Country.id' => $AuthUser['Country']['id'],
				'OperatorEntityCurrency.get' => true,
				'Entity.get' => true,
				'Operator.get' => true,
			),
		));



		$ListToCountries = array();
		$toCountryDefault = null;
		foreach ($Tos as $key => $To) {


			$ListCode[$To['Entity']['Country']['id']]['c'] = $To['Entity']['Country']['code'];
			$ListCode[$To['Entity']['Country']['id']]['n'] = $To['Entity']['Country']['mobile_code'];


			$Operator = $To['Entity']['Operator'];

			$ListToOperators[$Operator['id']]['name'] = $Operator['name'];
			$ListToOperators[$Operator['id']]['value'] = $Operator['id'];
			$ListToOperators[$Operator['id']]['data-subtext'] = $Operator['speed'];


			$ListToOperatorCurrencies[$Operator['id']][$To['Currency']['id']] = $To['Currency']['id'];

			$ListToCurrencies[$To['Currency']['id']]['name'] = $To['Currency']['iso'];
			$ListToCurrencies[$To['Currency']['id']]['value'] = $To['Currency']['id'];
			$ListToCurrencies[$To['Currency']['id']]['data-subtext'] = $To['Currency']['name'];


			$ListToCurrencies[$To['Currency']['id']]['name'] = $To['Currency']['iso'];
			$ListToCurrencies[$To['Currency']['id']]['value'] = $To['Currency']['id'];
			$ListToCurrencies[$To['Currency']['id']]['data-subtext'] = $To['Currency']['name'];



		}


		$this->set('ListToOperators', $ListToOperators);

		$this->set('ListToOperatorCurrencies', $ListToOperatorCurrencies);

		$this->set('ListToCurrencies', $ListToCurrencies);

		// Fin




































		$i = 0;
		foreach ($Froms as $key => $From) {

			foreach ($AuthUser['Banking'] as $key => $Bankings) {

				foreach ($Bankings as $key => $Banking) {

					$ToCurrencyId = $Banking['Banking']['currency_id'];

					$ConditionChanges['OR'][$i]['from'] = $From['Currency']['id'];
					$ConditionChanges['OR'][$i]['to'] = $ToCurrencyId;

					$i++;

				}

			}

		}


		$Changes = array();
		if (!empty($ConditionChanges)) {
			$Changes = $this->Currency->Change->find('all', array(
				'contain' => array(),
				'conditions' => $ConditionChanges,
			));
		}
		//debug($Changes);

		$listChanges = array();
		foreach ($Changes as $key => $Change) {
			$ChangeValueUpdate = $Change['Change']['value'];
			if ($Change['Change']['from'] != $Change['Change']['to'] and $Change['Change']['retrievepercent'] < 100) {
				$ChangeValueUpdate = $ChangeValueUpdate - ($ChangeValueUpdate * $Change['Change']['retrievepercent'] / 100);
			}

			$listChanges[$Change['Change']['from']][$Change['Change']['to']] = $ChangeValueUpdate;
		}


		$this->set('listChanges', $listChanges);







		$SavingProjects = $this->Banking->Saving->Project->find('all', array(
		));
		//debug($SavingProjects);
		$this->set('SavingProjects', $SavingProjects);








	}

	public function index()
	{
		$this->redirect('/');
	}

	public function login()
	{

		if ($this->Auth->user()) {
			$this->redirect($this->Auth->redirectUrl());
		}

		if ($this->request->is('post') or $this->request->is('put')) {

			if (isset($this->request->data['form']) and $this->request->data['form'] == 'login') {

				$this->ApiLogin();

			}


		}

		$authUrl1 = $this->Auth->redirectUrl();
		$authUrl2 = $this->Auth->redirectUrl();

		if (!empty($this->request->data['return_to'])) {
			$returnTo = $this->request->data['return_to'];
		} elseif ($authUrl1 != $authUrl2) {
			$returnTo = $authUrl1;
		} elseif (isset($this->request->params['named']['return_to'])) {
			$returnTo = urldecode($this->request->params['named']['return_to']);
		} elseif (isset($this->request->query['return_to'])) {
			$returnTo = $this->request->query['return_to'];
		} else {
			$returnTo = $this->referer();
		}

		$this->set('return_to', $returnTo);

	}

	public function delete()
	{
		$authUser = $this->Auth->user();
		if ($authUser){

		}else{
			$this->set('not_connected', true);
			
		}
	}

	public function delete_account(){
		$this->redirect('/users/delete');
	}

	public function ask_delete()
	{
		$this->ApiAskDelete();
		$this->ApiLogout();

	}

	public function logout_remove_account(){
		

		$this->ApiLogout();
	}



	public function reg()
	{
		function regDataNull(&$thisObj)
		{
			$thisObj->Session->write('reg_data', null);
		}

		if ($this->Auth->user()) {
			$this->redirect($this->Auth->redirectUrl());
		}

		//$reg_data['Info']['firstname']
		//$this->set('reg_data', ['Info'=>['firstname'=>$this->params['url']['recup_params']]]);

		$this->request->data['Info']['birthday'] = $this->request->data['Info']??[]['birthday_']??NULL;
		if ($this->request->is('post') or $this->request->is('put')) {
			if (isset($this->request->data['form']) and $this->request->data['form'] == 'register') {

					$errors = $this->ApiRegEnter($this->request->data);

					if(count($errors)>0){

						$this->set('errors', $errors);
						
						$this->set('reg_data', $this->reg_data_toUser());
					}
				  



			} else {
				regDataNull($this);//$this->Session->write('reg_data', null);
			}


		} elseif($this->params['url']['recup_params']??null){
			$this->set('reg_data', $this->reg_data_toUser());
			//$this->request->data = array_merge($this->request->data, );
		} else {
			regDataNull($this);//$this->Session->write('reg_data', null);
		}

		$authUrl1 = $this->Auth->redirectUrl();
		$authUrl2 = $this->Auth->redirectUrl();

		if (!empty($this->request->data['return_to'])) {
			$returnTo = $this->request->data['return_to'];
		} elseif ($authUrl1 != $authUrl2) {
			$returnTo = $authUrl1;
		} elseif (isset($this->request->params['named']['return_to'])) {
			$returnTo = urldecode($this->request->params['named']['return_to']);
		} elseif (isset($this->request->query['return_to'])) {
			$returnTo = $this->request->query['return_to'];
		} else {
			$returnTo = $this->referer();
		}

		$this->set('return_to', $returnTo);

		$Countries = $this->Country->find('all', array(
		));
		//debug($SavingProjects);
		$this->set('Countries', $Countries);

		try{
			$GeoLoc = GeoLoc::get();
			$this->set('GeoLoc', $GeoLoc);
		}catch(Exception $e){
			debug($e->getMessage());
		}
		

	}

	public function reg_code()
	{	
		
		$data = $this->Session->read('reg_data');
		$form = $this->request->data;

		$messageAlert = "Veuillez saisir les codes qui vous ont été envoyés";

		

		if ($this->request->is('post') or $this->request->is('put')) {
			if (isset($this->request->data['form']) and $this->request->data['form'] == 'register') {
				$messageAlert = $this->ApiRegCode($form, $data);
				

			}else{
				$this->redirect(['action' => 'index']);
			}


		} 
			
		if (isset($data)) {
			$info = $data['Info'];//array('id', 'firstname', 'name', 'country_id', 'phone');
			$user = $data['User'];//array('id', 'email', 'password', 'passwd');

			//$email = $user['email'];

			$this->set('firstname', $info['firstname']);
			$this->set('name', $info['name']);
			$this->set('phone', $info['phone']);
			$this->set('email', $user['email']);
			$this->set('birthday', $info['birthday']);

		} else {
			$this->redirect(['action' => 'index']);
		}


		/*
			if() $this->set('phone', false);
			$this->set('email', false);
		*/
		

		$this->set('messageAlert', $messageAlert);

	}


	public function regsuccess()
	{
		$authUser = $this->Auth->user();

		if (!$authUser) {
			$this->redirect('/');
		}

	}


	public function logout()
	{
		$this->ApiLogout();
	}

	public function passforgot()
	{

		$res = $this->ApiPassforgot(['messageAlert' => $this->params['url']['messageAlert']??'']);

		extract($res);

		$this->set('codeSended', $codeSended);
		if ($codeSended)
			$this->set('email', $email);


		$messageAlert = $this->params['url']['messageAlert']??'';

		if (isset($messageAlert)) {
			$this->set('messageAlert', $messageAlert);
		}

	}

	public function passforgotcancel()
	{
		$this->ApiPassforgotcancel([]);

	}

	public function passforgotsend()
	{
		$form = $this->request->data;//$this->request->data['form'];
		$email = $form['User']['email'];

		$this->ApiPassforgotsend(['email' => $email]);
	}

	public function passforgotsave()
	{
		$form = $this->request->data;//$this->request->data['form'];

		$this->ApiPassforgotsave([
			'codesecret' => $form['codesecret'],
			'password' => $form['User']['password'],
			'passwordConf' => $form['User']['passwordConf'],
		]);
	}

	function save_pass_test()
	{
		/*
			  $val = $this->UserTest->find('first', [
				  'conditions' => ['id'=> 55]
			  ]);
		  
			  
			  $UserSave = $val['UserTest'];

			  */

		$pass = $this->params['url']['pass'];
		$UserSave = ['id' => 55, 'password' => $pass,];// 'passwd'=>pppppp];
		//$UserSave['password'] = $pass;
		$this->UserTest->save($UserSave);
		//$this->redirect(['action'=>'login']);
	}





























	public function reset_password($token = null, $user = null)
	{
		if (empty($token)) {
			$admin = false;
			if ($user) {
				$this->request->data = $user;
				$admin = true;
			}
			$this->_sendPasswordReset($admin);
		} else {
			$this->_resetPassword($token);
		}
	}


	protected function _sendPasswordReset($options = array())
	{

		if (!empty($this->request->data)) {
			$User = $this->User->passwordReset($this->request->data);

			if (!empty($User)) {

				$options = array(
					'config' => 'noreply',
					'replyTo' => Configure::read('Mail.contact.Business.email'),
					'to' => array($User['User']['email'] => $User['Account']['fullname']),
					'viewVars' => array(
						'model' => $this->User,
						'user' => $this->User->data,
						'token' => $this->User->data['User']['password_token']
					),
					'subject' => "Réinitialiser votre mot de passe",
					'template' => "reinitialiser_mot_passe",
					'debug' => 0,
				);

				if (Validation::email($User['User']['email']))
					Op::sendMail($options);
				$this->Flash->notif("Nous vous avons envoyez un lien de reinitialiser de votre mot passe.", ['params' => 'info absolute', 'key' => 'auth']);
				$this->redirect(array('action' => 'login'));

			} else {

				$this->Flash->notif("Aucun utilisateur n'a été trouvé avec cet e-mail.", ['params' => 'warning', 'key' => 'auth']);

			}
		}
		$this->render('request_password_change');
	}

	protected function _resetPassword($token)
	{
		$User = $this->User->checkPasswordToken($token);
		if (empty($User)) {
			$this->Flash->notif("Jeton de réinitialisation de mot de passe non valide, réessayez.", ['params' => 'warning', 'key' => 'auth']);
			$this->redirect(array('action' => 'reset_password'));
			return;
		}

		if (!empty($this->request->data) and $this->User->resetPassword(Hash::merge($User, $this->request->data))) {

			//$this->Flash->notif("Mot de passe changé, vous pouvez maintenant vous connecter avec votre nouveau mot de passe.", ['params' => 'info', 'key' => 'auth']);

			$this->User->id = $User['User']['id'];
			$this->autologin();

			//$this->redirect($this->Auth->loginAction);
		}

		$this->set('token', $token);
	}






}