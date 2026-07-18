<?php


class MController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		//$this->Security->unlockedActions = array('login');
		$this->Auth->allow();


		if (!$this->request->is('json')) {
			//exit();
		}

		$this->layout = null;
		$this->layout = 'ajax';
		if(!file_exists(APP.'View'.DS.$this->name.DS.$this->view.'.ctp')){
			$this->autoRender = null;
		}
		
		$this->handleRequest();


	}

	public function allpubs() {

		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			$Publicities = $this->Publicity->find('all', array(
				'limit' => 6,
				'order' => 'rand()',
			));

		    $Publicities = Hash::remove($Publicities, '{n}.Publicity.sponsor_id');
		    $Publicities = Hash::remove($Publicities, '{n}.Publicity.relevance');
		    $Publicities = Hash::remove($Publicities, '{n}.Publicity.online');
		    $Publicities = Hash::remove($Publicities, '{n}.Publicity.created');
		    $Publicities = Hash::remove($Publicities, '{n}.Publicity.modified');

			$Publicities = Hash::combine($Publicities, '{n}.Publicity.id', '{n}.Publicity');

			//debug($Publicities);

			$return = Hash::merge($return, array('success' => true, 'data' => $Publicities));
				
		} catch (Exception $e) {
			
		}


		echo json_encode($return); exit();

	}


	public function alltowns() {

		$return = array('success' => false, 'data' => array(), 'message' => array());
		try {

			$return = Hash::merge($return, $this->ApiAlltowns());
		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();

	}

	public function alltowns_categorified(){
		$return = array('success' => false, 'data' => array(), 'message' => array());
		try {

			$STowns = $this->ApiAlltowns();
			
			//liste des bus
			$busTowns = $this->townsCategorifiedByTownFromTo('CompanyDestination', false);
	
			//liste des avions
			$planeTowns = $this->townsCategorifiedByTownFromTo('CompanyPlaneDestination');
			
			//liste des hotels
			$hotelTowns = $this->townsCategorifiedByTown('CompanyHotel');
			
			//liste des trains
			$formerTowns = $this->townsCategorifiedByTownFromTo('CompanyFormerDestination');

			//liste des taxis
			$taxiTowns = $this->townsCategorifiedByTown('CompanyTaxi');

			$return = Hash::merge($return, array('success' => true, 'data' => [
				'alltowns'=>$STowns,
				'bustowns' => $busTowns,
				'planetowns' => $planeTowns,
				'hoteltowns' => $hotelTowns,
				'formertowns' => $formerTowns,
				'taxitowns' => $taxiTowns,

			]));

		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();
	}



	public function allbustags() {

		$return = array('success' => false, 'data' => array(), 'message' => array());
		try {

			$Tags = $this->Company->Car->Tag->find('all', array(
			    //'contain' => array('Media', 'Country'),
			    //'conditions' => array('Country.code' => CakeSession::read('Localisation')),
			));

			foreach ($Tags as $key => $Tag) {
			    $AllTags[$Tag['Tag']['id']] = $Tag['Tag']['name'];
			}

			$return = Hash::merge($return, array('success' => true, 'data' => $AllTags));

		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();

	}

	public function sales_marchand(){
		$return = array('success' => false, 'data' => array(), 'message' => array());

		try{
			$data = [];

			if($this->request->is('json') AND !empty(file_get_contents('php://input'))){
				$data = json_decode(file_get_contents('php://input'), true);
			}

			///$md5 = $this->ApiMd5Search();

			///$Options['md5'] = $md5;
			$Options['ajax'] = true;
			$Options['data'] = $data;

			if(is_numeric($data['marchand_user_id'])) $return = Hash::merge($return, $this->SalesMarchand($Options));


		}catch(Exception $e){

		}

		echo json_encode($return); exit();
	}

	public function consommer_billet_marchand(){
		$return = array('success' => false, 'data' => array(), 'message' => array());

		try{
			$data = [];

			if($this->request->is('json') AND !empty(file_get_contents('php://input'))){
				$data = json_decode(file_get_contents('php://input'), true);
			}

			

			if(isset($data['ticket_id'])){
				$id = $data['ticket_id'];
				$sale0 = $this->Sale->find('first', [
					'conditions'=> ['id'=>$id]
				]);

				if(isset($sale0)){
					$this->Sale->save([
						'id'=>$id,
						'consomme'=>true
					]);
					$return = array_merge($return, ['success' => true]);
				}else{
					$return = array_merge($return, ['message' => ['Ticket'=>'Ce ticket n\'est pas valide']]);
				}
				
			}else{
				$return = array_merge($return, ['message' => ['Data sent'=>'Aucune donnée']]);
			}


		}catch(Exception $e){
			$return = array_merge($return, ['message' => ['catch'=>'Erreur inattendue']]);
		}

		echo json_encode($return); exit();

	}

	public function search_destination_marchand(){
		$return = array('success'=>false, 'data' => array(), 'message' => array());

		try{
			$data = [];
			
			if($this->request->is('json') AND !empty(file_get_contents('php://input'))){
				$data = json_decode(file_get_contents('php://input'), true);
			}

			///$md5 = $this->ApiMd5Search();

			///$Options['md5'] = $md5; 
			$Options['ajax'] = true; 
			$Options['data'] = $data;

			$return['data_before'] = $data;
			$return['is_numeric'] = is_numeric($data['marchand_user_id']);

			if(is_numeric($data['marchand_user_id'])){
				switch($data['categorie']){
					case 'bus' : 
						$return = Hash::merge($return, $this->ApiSearchBusSimple($Options));
						break;
					case 'vol' : 
						$return = Hash::merge($return, $this->ApiSearchPlane($Options));
						break;
					case 'hotel':
						$Options['data']['town_id'] = $Options['data']['from_town_id'];
						$return = Hash::merge($return, $this->ApiSearchHotel($Options));
						break;
					case 'train':
						$return = Hash::merge($return, $this->ApiSearchFormer($Options));
						break;
					case 'taxi':
						$Options['data']['town_id'] = $Options['data']['from_town_id'];
						$return = Hash::merge($return, $this->ApiSearchTaxi($Options));
						break;
				}
				
			} 
			//debug($return);
			
		} catch (Exception $e) {
			
		}

		//$return = array('success'=>false, 'data' => array(), 'message' => array());

		echo json_encode($return); exit();

		
	}
	public function searchbus() {

		$return = array('success' => false, 'data' => array(), 'message' => array());
		try {


        //$data['data'] = $this->request->data['Search'];


		if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
			$this->autoRender = false;
			$this->request->data = json_decode(file_get_contents('php://input'), true);
		}elseif (empty($this->request->data)) {
	        $this->request->data = array(
				'from_town_id' => '1',
				'to_town_id' => '2',
				'form_date' => '07/03/2024',
				//'seats' => '1  Sièges'
			);
		}

		if (!empty($this->request->data) AND empty($this->request->data['Search'])) {
			$newData['Search'] = $this->request->data;
			$this->request->data = $newData;
		}

        $md5 = $this->ApiMd5Search();

        $Options['md5'] = $md5; 
        $Options['ajax'] = true; 

		$return = Hash::merge($return, $this->ApiSearchBus($Options));
		//debug($return);
		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();

	}

	public function bus_nbr_place_vendues($Options = []){
		$return = array('success' => false, 'data' => array(), 'message' => array());
		try {


        //$data['data'] = $this->request->data['Search'];


		if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
			//$this->autoRender = false;
			$this->request->data = json_decode(file_get_contents('php://input'), true);
		}elseif (empty($this->request->data)) {
	        $this->request->data = array(
				'departure_date'=>'2024-11-27', 
				'company_destination_id'=>155,
				
				//'seats' => '1  Sièges'
			);
		}

		$Options['data'] = $this->request->data;

		$return = Hash::merge($return, $this->ApiBusNbrPlaceVendues($Options));
		//debug($return);
		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();

	  }

	public function searchplane() {

		$return = array('success' => false, 'data' => array(), 'message' => array());
		
		try {


        //$data['data'] = $this->request->data['Search'];


		if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
			//$this->autoRender = false;
			$this->request->data = json_decode(file_get_contents('php://input'), true);
		}elseif (empty($this->request->data)) {
	        $this->request->data = array(
				'from_town_id' => '25',
				'to_town_id' => '1',
				'form_date' => '07/03/2024',
				//'seats' => '1  Sièges'
			);
		}

		/*
		if (!empty($this->request->data) AND empty($this->request->data['Search'])) {
			$newData['Search'] = $this->request->data;
			$this->request->data = $newData;
		}
*/
        ///$md5 = $this->ApiMd5Search();

        ///$Options['md5'] = $md5; 
        $Options['ajax'] = true; 
		$Options['data'] = $this->request->data;

		$return = Hash::merge($return, $this->ApiSearchPlane($Options));
		
		//debug($return);
		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();

	}

	public function searchhotel() {

		$return = array('success' => false, 'data' => array(), 'message' => array());
		


		
		try {

		

			// 1. Récupération du flux brut
			$rawInput = file_get_contents('php://input');

			// 2. Traitement si la requête est en JSON
			if ($this->request->is('json') && !empty($rawInput)) {
				$decodedData = json_decode($rawInput, true);
				
				// Vérification : Le JSON est-il valide ?
				if (json_last_error() === JSON_ERROR_NONE) {
					$this->request->data = $decodedData;
				} else {
					// En cas d'erreur de parsing, on log l'erreur pour déboguer
					CakeLog::write('error', 'Erreur JSON reçu : ' . json_last_error_msg());
					// On ne remplace pas, ou on force à vide pour éviter des crashs
					$this->request->data = []; 
				}
			}

			// 3. Fallback : Si $this->request->data est toujours vide (pas de données JSON 
			// ou pas de POST classique), on met les valeurs par défaut.
			if (empty($this->request->data)) {
				$this->request->data = array(
					'town_id' => '1',
					// 'form_date' => '07/03/2024',
				);
			}
			
			/*
			if (!empty($this->request->data) AND empty($this->request->data['Search'])) {
				$newData['Search'] = $this->request->data;
				$this->request->data = $newData;
			}

			
		*/
        ///$md5 = $this->ApiMd5Search();

        ///$Options['md5'] = $md5; 
        $Options['ajax'] = true; 
		$Options['data'] = $this->request->data;

		$return = Hash::merge($return, $this->ApiSearchHotel($Options));
		
		//debug($return);
		} catch (Exception $e) {
			
		}

		echo json_encode($return);
		//echo json_encode($return);
		exit();

	}

	public function hotel_nbr_place_vendues($Options = []){
		$return = array('success' => false, 'data' => array(), 'message' => array());
		try {


        //$data['data'] = $this->request->data['Search'];


		if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
			//$this->autoRender = false;
			$this->request->data = json_decode(file_get_contents('php://input'), true);
		}elseif (empty($this->request->data)) {
	        $this->request->data = array(
				'departure_date'=>'2024-11-27', 
				'company_destination_id'=>155,
				
				//'seats' => '1  Sièges'
			);
		}

		$Options['data'] = $this->request->data;

		$return = Hash::merge($return, $this->ApiHotelNbrPlaceVendues($Options));
		//debug($return);
		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();

	  }

	public function searchformer() {

		$return = array('success' => false, 'data' => array(), 'message' => array());
		try {


        //$data['data'] = $this->request->data['Search'];


		if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
			//$this->autoRender = false;
			$this->request->data = json_decode(file_get_contents('php://input'), true);
		}elseif (empty($this->request->data)) {
	        $this->request->data = array(
				'from_town_id' => '1',
				'to_town_id' => '2',
				//'form_date' => '07/03/2024',
				//'seats' => '1  Sièges'
			);
		}

		/*
		if (!empty($this->request->data) AND empty($this->request->data['Search'])) {
			$newData['Search'] = $this->request->data;
			$this->request->data = $newData;
		}
*/
        //$md5 = $this->ApiMd5Search();

        //$Options['md5'] = $md5; 
        $Options['ajax'] = true; 
		$Options['data'] = $this->request->data;

		$return = Hash::merge($return, $this->ApiSearchFormer($Options));
		//debug($return);
		} catch (Exception $e) {
			
		}

		echo json_encode($return);
		//echo json_encode($return);
		exit();

	}

	public function searchtaxi() {

		$return = array('success' => false, 'data' => array(), 'message' => array());
		try {


        //$data['data'] = $this->request->data['Search'];


		if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
			//$this->autoRender = false;
			$this->request->data = json_decode(file_get_contents('php://input'), true);
		}elseif (empty($this->request->data)) {
	        $this->request->data = array(
				'town_id' => '1',
				//'form_date' => '07/03/2024',
				//'seats' => '1  Sièges'
			);
		}

		/*
		if (!empty($this->request->data) AND empty($this->request->data['Search'])) {
			$newData['Search'] = $this->request->data;
			$this->request->data = $newData;
		}
*/
        ///$md5 = $this->ApiMd5Search();

        ///$Options['md5'] = $md5; 
        $Options['ajax'] = true; 
		$Options['data'] = $this->request->data;

		$return = Hash::merge($return, $this->ApiSearchTaxi($Options));
		//debug($return);
		} catch (Exception $e) {
			
		}

		echo json_encode($return);
		//echo json_encode($return);
		exit();

	}


	public function buymmoney() {

		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
				$this->autoRender = false;
				$this->request->data = json_decode(file_get_contents('php://input'), true);
			}elseif (empty($this->request->data)) {
		        $this->request->data = array(
					'auth_id' => '1',
					'user_id' => '1',
					'total' => '90000',
					'phone' => '+243-850933290',
					'rate_id' => '1',
					'departure_date' => '2024-07-04',
					'schedule_id' => '1',
					'place' => array('4_4' => '30000', '4_5' => '30000', '5_1' => '30000'),

					'user_facture_list_data' => "{\"kevin\":\"kikubi\", \"beau\":\"magnifique\"}",
					'currency_id'=>'1',
					'from_town_id'=>'1',
					'to_town_id'=>'2',

				);

			}

			
			$phone = $this->request->data['phone'];
			$currency = $this->request->data['currency_iso'];
			$amount = 0;

			if (!empty($this->request->data)) {
				$newData['Sale'] = $this->request->data;
				$this->request->data = $newData;
				
				$newData['Sale']['info']['place'] = (array) $this->request->data['Sale']['place'];

				$newData['Sale']['info']['company_name'] = $this->request->data['Sale']['company_name'];
				$newData['Sale']['info']['currency_iso'] = $this->request->data['Sale']['currency_iso'];
				$newData['Sale']['info']['from_country_mobile_code'] = $this->request->data['Sale']['from_country_mobile_code'];
				$newData['Sale']['info']['to_country_mobile_code'] = $this->request->data['Sale']['to_country_mobile_code'];
				
				
				$user_facture_list_data = $newData['Sale']['user_facture_list_data'];
				$user_facture_list_type_billet = $newData['Sale']['user_facture_list_type_billet'];
				//$newData['Sale']['user_facture_list'] = $user_facture_list;
				
				$userFactJson = json_decode($user_facture_list_data);
				$user_facture_list = [
					'count'=> count($userFactJson),
					'type_billet'=> $user_facture_list_type_billet,
					'data'=>$user_facture_list_data
				];

				$user_total = $this->request->data['Sale']['user_total'];
				$total = $this->request->data['Sale']['total']??$user_total; // a modifier
				
				$amount = substr_replace(''.ceil($total*100), '.', -2, 0) ;
				//a travailler sur ça
				$server_total = $user_total;
				$server_facture_list = $user_facture_list;
				//a travailler sur ça

				

				$listeDonSupp = [
					'phone' => $this->request->data['Sale']['phone'],
					'service' => $this->request->data['Sale']['service'],
					'company_destination_id' => $this->request->data['Sale']['company_destination_id'],
					'category_company_id' => $this->request->data['Sale']['category_company_id'],
					'currency_id' => $this->request->data['Sale']['currency_id'],
					'from_town_id' => $this->request->data['Sale']['from_town_id'],
					'to_town_id' => $this->request->data['Sale']['to_town_id'],
					'user_facture_list' => $user_facture_list,
					'user_total' => $user_total,
					'server_facture_list' => $server_facture_list,
					'server_total' => $server_total,
					'total' => $total,

					'nbr_place' => $this->request->data['Sale']['nbr_place'],
				];

				$newData['Sale'] = array_merge($newData['Sale'], $listeDonSupp);
				$newData['Sale']['info'] = array_merge($newData['Sale']['info'], $listeDonSupp);

				//echo 'newData : '.json_encode([$newData]);
				
				
				///test - a effacer
				if($total == 1){
    				echo json_encode(array(
        				'success' => false, 
        				'data' => array(), 
        				'etat'=>[
        					'status'=>-90,
        					'message'=>"BRAVO! TEST REUSSI",
        				],
        				'message' => array('exception'=>"BRAVO! TEST REUSSI")
        			)); 
    				exit();
			    }
				

				


			}

			if(isset($this->request->data['Sale']['company_destination_id'])){
					
			

				$AuthUserId = $this->AuthUserId(true);


				$newData = Hash::merge(array(
					'form' => 'buymobilemoney',
					'Sale' => array(
						'user_id' => $AuthUserId,
						'type' => 'mmoney',
					)
				), $newData);

				

				///A REMETRE
				$paymentUnipesa = $this->unipesa_payment_c2b($phone, $amount, $currency, $AuthUserId);
				
				///ET A ENLEVER (TESTE - SIMULATION)
				/*
				$paymentUnipesa = array (
					'merchant_id' => 'cdefa0eee6530985239b431b7d2593a55610483a',
					'customer_id' => '243817515851',
					'order_id' => 'TNSARL-f1e635991602730c6990-6a55882a6273e1.88381396',
					'country' => 'CD',
					'amount' => '40000.00',
					'currency' => 'CDF',
					'provider_id' => 9,
					'callback_url' => 'https://www.tnsarl.com/m/unipesa_callback_c2b',
					'return_url' => 'https://www.tnsarl.com/m/unipesa_return_c2b',
					'email' => 'transnumerica@gmail.com',
					'name' => '55',
					'signature' => 'db26da7657fb6c204da14030789c0b3a09b17b24f6d8e04fd45765dc45176a395cca3092db3f36f69a5f82f592e6ba6548702c8ba5c802a808a7191db0def04e',
					'transaction_id' => '5687EC5C4747E91CE0636C045A0AFD86',
					'transaction_ref' => '',
					'status' => 1,
					'result' => 
					array (
						'code' => 0,
						'message' => 'OK',
					),
					'provider_result' => 
					array (
						'code' => '0',
						'message' => 'Processed',
					),
					'service_id' => 1,
					'service_version' => '1.03/1.14|1.0/2.0|1.0/1.0|1.01/1.0|1.01/1.0||1.02/1.27',
					'service_date_time' => '2026-07-14 03:51:55.191661',
					'confirm_type' => 0,
					'details' => '{"merchant_id":"cdefa0eee6530985239b431b7d2593a55610483a","customer_id":"243817515851","order_id":"TNSARL-f1e635991602730c6990-6a55882a6273e1.88381396","country":"CD","amount":"40000.00","currency":"CDF","provider_id":9,"callback_url":"https:\\/\\/www.tnsarl.com\\/m\\/unipesa_callback_c2b","return_url":"https:\\/\\/www.tnsarl.com\\/m\\/unipesa_return_c2b","email":"transnumerica@gmail.com","name":"55","signature":"db26da7657fb6c204da14030789c0b3a09b17b24f6d8e04fd45765dc45176a395cca3092db3f36f69a5f82f592e6ba6548702c8ba5c802a808a7191db0def04e","transaction_id":"5687EC5C4747E91CE0636C045A0AFD86","transaction_ref":"","status":1,"result":{"code":0,"message":"OK"},"provider_result":{"code":"0","message":"Processed"},"service_id":1,"service_version":"1.03\\/1.14|1.0\\/2.0|1.0\\/1.0|1.01\\/1.0|1.01\\/1.0||1.02\\/1.27","service_date_time":"2026-07-14 03:51:55.191661","confirm_type":0}',
				);
				*/
				//$this->saveArrayToFile('simulation_data_unipesa_payment_c2b.php', $paymentUnipesa); //pour une simulation

				$status = $paymentUnipesa['status'];
				$newData['Sale']['status'] = $status;
				$return['etat'] = ['status'=>$status];

				
				if(isset($paymentUnipesa['error'])){
					$message = $paymentUnipesa['error']??"Une erreur inattendue est survenue";
					

					$return = array(
						'success' => false, 
						'data' => array(), 
						'etat'=>[
							'status'=>-90,
							'message'=>$message,//$e
						],
						'message' => array('Erreur de payement'=>$message)
					);
					

					//echo json_encode(); 
					//	exit();
						
					
					
				}else{

					$Options['data'] = $newData;
					$Options['ajax'] = true;

					$from_web = $newData['Sale']['from_web'];
					if($from_web == true){
						$Options['from_web'] = $from_web;
						unset($newData['Sale']['from_web']);
					}

					

					$returnBuyAppMobileMoney = $this->buyAppMobileMoney($Options);
					///A EFFACER
					//$return = array_merge($return, ['returnBuyAppMobileMoney'=>[$returnBuyAppMobileMoney]]);
					
					$paymentUnipesa['sale_id'] = $returnBuyAppMobileMoney['sale_id'];

					$return['etat']['message'] = "Payement encours...";

					if(in_array($status, [0,1,2])){
					
						$return = Hash::merge($return, $returnBuyAppMobileMoney);
						
					}else if($paymentUnipesa["result"]["code"]==0){
						$return = Hash::merge($return, $returnBuyAppMobileMoney);
						
						if($paymentUnipesa["provider_id"]!=17){
							$return['etat']['message'] = PHP_EOL.PHP_EOL.'Message de votre opérateur Mobile : '.PHP_EOL.PHP_EOL.'<< '.$paymentUnipesa["provider_result"]["message"].' >>';
						}

					}else{

						$message ='Echec! le payement mobile a échoué. avec pour status : '.$paymentUnipesa['status'].' et pour id de l\'ordre : '.$paymentUnipesa['order_id'];
						$return = array_merge($return, array(
							'success' => false, 
							'data' => array(), 
							'etat'=>array(
								'status'=>-90,
								'message'=>$message,//"Des travaux sont encours au niveau du serveur... Veuillez réessayer plus tard",
							),
							'message' => array('exception'=>$message),//"Des travaux sont encours au niveau du serveur... Veuillez réessayer plus tard")
						));
						/*
						echo json_encode(array(
							'success' => false, 
							'data' => array(), 
							'etat'=>array(
								'status'=>-90,
								'message'=>$message,//"Des travaux sont encours au niveau du serveur... Veuillez réessayer plus tard",
							),
							'message' => array('exception'=>$message),//"Des travaux sont encours au niveau du serveur... Veuillez réessayer plus tard")
						)); 
						exit();
						*/
					}
					

					$this->save_unipesa_payment_c2b($paymentUnipesa);

					

				}

				
			}else{
				$msg = "Vous utilisez une ancienne version de TransNumerica. Cette opération n'est disponible que pour une version plus récente. Vous pouvez l'obtenir en suivant ce lien : \n\nhttps://www.tnsarl.com/mobile/android";
				echo json_encode(array(
					'success' => false, 
					'data' => array(), 
					'etat'=>[
						'status'=>-100,
						'message'=>$msg,
					],
					'message' => array('old version'=>"Vous utilisez une ancienne version de TransNumerica. Cette opération n'est disponible que pour une version plus récente. Vous pouvez l'obtenir en suivant ce lien : \n\nhttps://www.tnsarl.com/mobile/android")
				)); 
					exit();
			}
			

		} catch (Exception $e) {
			echo json_encode(array(
				'success' => false, 
				'data' => array(), 
				'etat'=>[
					'status'=>-90,
					'message'=>$e->getMessage(),
				],
				'message' => array('exception'=>$e->getMessage())
			)); 
				exit();
		}
		

		echo json_encode($return); 
		exit();

	}

	public function unipesa_callback_c2b(){
		$jsonWeb = file_get_contents('php://input');
		
		/*
		$file = fopen("unipesaCallback.txt", "a") or die("Unable to open file!");
		
		fwrite($file, $txt);
		fclose($file);
		*/
		$arrayWeb = json_decode($jsonWeb, true);
		$arrayWeb['details'] = $jsonWeb;
		$this->save_unipesa_callback_c2b($arrayWeb);

		return json_encode(['success'=>true]);
	}

	private function unipesaProviderByCellular($cellularSuffix){
		switch(substr($cellularSuffix, 0, 2)){
			case "81";
			case "82";
			case "83": return 9;

			case "84";
			case "85";
			case "89" : return 10;

			case "97";
			case "98";
			case "99" : return 17;

			case "90" : return 19;

			default : return null;
		}
	}

	//country code top level domain of republique du congo
	private function countryCodeByCellular($cellularCode){
		switch($cellularCode){
			case "+243" : return 'CD';
			//case "+242" : return 'CG';
			default : return null;
		}

		
	}
	

	public function unipesa_return_c2b(){
		$file = fopen("unipesaReturn.txt", "a") or die("Unable to open file!");
		$txt = file_get_contents('php://input').PHP_EOL;
		fwrite($file, $txt);
		fclose($file);

		echo json_encode(['success' => true]); 
		exit();
	}

	private function calculateSignature($data, $secret){
		$signed = '';

		foreach ($data as $key => $value){
			$signed .= $key.$value;
		}

		return strtolower(hash_hmac('sha512', $signed, $secret));
	}

	private function unipesa_payment_c2b($phone, $amount, $currency, $name){
		
		list($codePays, $customer_id) = explode("-", $phone);
		if($codePays ==null || $customer_id ==null){
			return [
				'error'=>"Votre numéro n'est pas valide. préfix : $codePays, customer_id : $customer_id.",
				'status'=>1090,
			];
		}

		$provider_id = $this->unipesaProviderByCellular($customer_id);
		

		$country = $this->countryCodeByCellular($codePays);

		if($country == null){
			return [
				'error'=>"Le Code du pays inséré n'est pas pris en charge actuellement : $codePays",
				'status'=>1090,
			];
		}

		if($provider_id == null){
			return [
				'error'=>"Le fournisseur de ce réseau mobile n'est pas identifié : $phone",
				'status'=>1090,
			];
		}

		if($provider_id == 10)  $customer_id = '0'.$customer_id;
		else if($provider_id == 9)$customer_id = substr($codePays, 1).$customer_id;
		
		// create link with parameters
		$public_id = "cdef5d044319ba77fb3b8141fe183e761698f3d0";
		$url = "https://api.unipesa.tech/$public_id/payment_c2b";

		$secretKey = "006b29d04164d00db21f96e4d89f3a89a2ee9fef610aa1c6305fb6d15ccef25a8c3a7dd409e827090b434040a27fb5850dea205a04ede6c0049db2e7c203d819";

		$data = [
			'merchant_id' => 'cdefa0eee6530985239b431b7d2593a55610483a',
			'customer_id' => $customer_id,//'0850933290',//'0817515851',//'0821500289',//'0829339765'
			'order_id' => uniqid('TNSARL-'.(bin2hex(random_bytes(10))).'-', true),//'16280954971628095491', // ONLY a-zA-Z0-9
			'country' => $country,
			'amount' => $amount,//'200.00',
			'currency' => $currency,//'CDF',
			'provider_id' => $provider_id,//$this->unipesa_providers['ORANGE_MONEY_CD'],//['ORANGE_MONEY_CD'],//$this->unipesa_providers['VODACOM_CD'],
			'callback_url' => 'https://www.tnsarl.com/m/unipesa_callback_c2b',
			'return_url' => 'https://www.tnsarl.com/m/unipesa_return_c2b',
			'email' => 'transnumerica@gmail.com',
			'name' => $name,

		];


		//"signature": "d7d6d76b0e22c6f9d369fa6c24f107053d12bfd24d3b154f2deb6676bf179c123134e1f20879c803be455d81cfe792f00cd8892c26ce7cf5a05beebb9c80843e"

		$data['signature'] = $this->calculateSignature($data, $secretKey);

		$json_data = json_encode($data);

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($json_data)
		));

		$json_response = curl_exec($ch);
		$merged_response = array_merge($data, json_decode($json_response, true));
		/*
		$response = json_encode($merged_response).PHP_EOL;

		$file = fopen("unipesaRequest.txt", "a") or die("Unable to open file!");
		fwrite($file, $response);
		fclose($file);
*/

		//echo $response;
		$merged_response['details'] = json_encode($merged_response);

		return $merged_response;
	}


	public function reg_toleka() {

		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
				//$this->autoRender = false;
				$this->request->data = json_decode(file_get_contents('php://input'), true);
			}

			///$md5 = $this->ApiMd5Search();

			///$Options['md5'] = $md5; 
			$Options['ajax'] = true; 
			$Options['data'] = $this->request->data;
			//$Options['files'] = $_FILES;

			$return = Hash::merge($return, $this->ApiRegToleka($Options));
			
			


		}catch (Exception $e){
			echo json_encode(
				array(
					'success' => false, 
					'data' => array(), 
					'etat'=>[
						'status'=>-90,
						'message'=>$e->getMessage(),
					],
					'message' => array('exception'=>$e->getMessage())
				)	
			); 
			exit();
		}

		echo json_encode($return);
		exit();
	}

	public function ajout_pieces_toleka() {

		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
				//$this->autoRender = false;
				$this->request->data = json_decode(file_get_contents('php://input'), true);
			}

			///$md5 = $this->ApiMd5Search();

			///$Options['md5'] = $md5; 
			$Options['ajax'] = true; 
			$Options['data'] = $this->request->data;
			//$Options['files'] = $_FILES;

			$return = Hash::merge($return, $this->ApiAjoutPiecesToleka($Options));
			
			


		}catch (Exception $e){
			echo json_encode(
				array(
					'success' => false, 
					'data' => array(), 
					'etat'=>[
						'status'=>-90,
						'message'=>$e->getMessage(),
					],
					'message' => array('exception'=>$e->getMessage())
				)	
			); 
			exit();
		}

		echo json_encode($return);
		exit();
	}


	public function get_photos_taxi($user_id) {

		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			$companyTaxy = $this->CompanyTaxi->find('first', [
				'conditions' => ['user_id'=>$user_id]
			]);

			
			if(isset($companyTaxy)){
				$taxiDatas = $companyTaxy['CompanyTaxi'];

				$allImageNames = $this->chauffeurImageNames;
				
				$existImages = [];
				$notImages = [];

				
				foreach($allImageNames as $name => $titre){
					$image = $taxiDatas[$name];
					$allImages[$name]=[
						'titre' => $titre, 
						'lien' => isset($image)?Router::url($image, true):null
					];


				}
				


				$return = [
					'success' => true,
					'data' => [
						//'exist_images' => $existImages,
						//'not_images' => $notImages,
						'all_images' => $allImages,
					],
					//'taxiDatas' => $taxiDatas,
				];
			}
			


		}catch (Exception $e){
			echo json_encode(
				array(
					'success' => false, 
					'data' => array(), 
					'etat'=>[
						'status'=>-90,
						'message'=>$e->getMessage(),
					],
					'message' => array('exception'=>$e->getMessage())
				)	
			); 
			exit();
		}

		echo json_encode($return);
		exit();
	}

	
	public function toleka_commande($status = null){
		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
				//$this->autoRender = false;
				$this->request->data = json_decode(file_get_contents('php://input'), true);
			}

			///$md5 = $this->ApiMd5Search();

			///$Options['md5'] = $md5; 
			$Options['ajax'] = true; 
			$Options['data'] = $this->request->data;

			$return = Hash::merge($return, $this->ApiTolekaCommande($Options));
			
			


		}catch (Exception $e){
			echo json_encode(
				array(
					'success' => false, 
					'data' => array(), 
					'etat'=>[
						'status'=>-90,
						'message'=>$e->getMessage(),
					],
					'message' => array('exception'=>$e->getMessage())
				)	
			); 
			exit();
		}

		echo json_encode($return);
		exit();
	}

	public function toleka_tarif(){
		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
				//$this->autoRender = false;
				$this->request->data = json_decode(file_get_contents('php://input'), true);
			}

			///$md5 = $this->ApiMd5Search();

			///$Options['md5'] = $md5; 
			$Options['ajax'] = true; 
			$Options['data'] = $this->request->data;

			$return = Hash::merge($return, $this->ApiTolekaTarif($Options));
			
			


		}catch (Exception $e){
			echo json_encode(
				array(
					'success' => false, 
					'data' => array(), 
					'etat'=>[
						'status'=>-90,
						'message'=>$e->getMessage(),
					],
					'message' => array('exception'=>$e->getMessage())
				)	
			); 
			exit();
		}

		echo json_encode($return);
		exit();
	}

	public function get_liste_chauffeur_by_socket(){
		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
				//$this->autoRender = false;
				$this->request->data = json_decode(file_get_contents('php://input'), true);
			}

			///$md5 = $this->ApiMd5Search();

			///$Options['md5'] = $md5; 
			$Options['ajax'] = true; 
			$Options['data'] = $this->request->data;

			$return = Hash::merge($return, $this->ApiGetListeChauffeurBySocket($Options));
			
			


		}catch (Exception $e){
			echo json_encode(
				array(
					'success' => false, 
					'data' => array(), 
					'etat'=>[
						'status'=>-90,
						'message'=>$e->getMessage(),
					],
					'message' => array('exception'=>$e->getMessage())
				)	
			); 
			exit();
		}

		echo json_encode($return);
		exit();
	}

	public function accept_course_par_chauffeur_by_socket(){
		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
				//$this->autoRender = false;
				$this->request->data = json_decode(file_get_contents('php://input'), true);
			}

			///$md5 = $this->ApiMd5Search(); 

			///$Options['md5'] = $md5; 
			$Options['ajax'] = true; 
			$Options['data'] = $this->request->data;

			$return = Hash::merge($return, $this->ApiAcceptCourseParChauffeurBySocket($Options));
			
			


		}catch (Exception $e){
			echo json_encode(
				array(
					'success' => false, 
					'data' => array(), 
					'etat'=>[
						'status'=>-90,
						'message'=>$e->getMessage(),
					],
					'message' => array('exception'=>$e->getMessage())
				)	
			); 
			exit();
		}

		echo json_encode($return);
		exit();
	}

	public function aucun_chauffeur_trouve_by_socket(){
		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
				//$this->autoRender = false;
				$this->request->data = json_decode(file_get_contents('php://input'), true);
			}

			///$md5 = $this->ApiMd5Search();

			///$Options['md5'] = $md5; 
			$Options['ajax'] = true; 
			$Options['data'] = $this->request->data;

			$return = Hash::merge($return, $this->ApiAucunChauffeurTrouveBySocket($Options));
			
			


		}catch (Exception $e){
			echo json_encode(
				array(
					'success' => false, 
					'data' => array(), 
					'etat'=>[
						'status'=>-90,
						'message'=>$e->getMessage(),
					],
					'message' => array('exception'=>$e->getMessage())
				)	
			); 
			exit();
		}

		echo json_encode($return);
		exit();
	}

	public function toleka_course_monter_abord(){
		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
				//$this->autoRender = false;
				$this->request->data = json_decode(file_get_contents('php://input'), true);
			}

			///$md5 = $this->ApiMd5Search();

			///$Options['md5'] = $md5; 
			$Options['ajax'] = true; 
			$Options['data'] = $this->request->data;

			$return = Hash::merge($return, $this->ApiTolekaCourseMonterAbord($Options));
			
			


		}catch (Exception $e){
			echo json_encode(
				array(
					'success' => false, 
					'data' => array(), 
					'etat'=>[
						'status'=>-90,
						'message'=>$e->getMessage(),
					],
					'message' => array('exception'=>$e->getMessage())
				)	
			); 
			exit();
		}

		echo json_encode($return);
		exit();
	}

	public function terminer_toleka_course(){
		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
				//$this->autoRender = false;
				$this->request->data = json_decode(file_get_contents('php://input'), true);
			}

			///$md5 = $this->ApiMd5Search();

			///$Options['md5'] = $md5; 
			$Options['ajax'] = true; 
			$Options['data'] = $this->request->data;

			$return = Hash::merge($return, $this->ApiTerminerTolekaCourse($Options));
			
			


		}catch (Exception $e){
			echo json_encode(
				array(
					'success' => false, 
					'data' => array(), 
					'etat'=>[
						'status'=>-90,
						'message'=>$e->getMessage(),
					],
					'message' => array('exception'=>$e->getMessage())
				)	 
			); 
			exit();
		}

		echo json_encode($return);
		exit();
	}

	public function get_prix_toleka_taxi($userId = null) {

		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			/*
			$Sale = $this->ApiBusTicketQR(array('sale_id' => $id), true);

			if ($Sale) {
				$return = Hash::merge($return, array('success' => true, 'data' => $Sale));
			}else{
		        $return['message']['error'] [] = "Cette commande est invalide ou n'existe plus";
			}

			//debug($Sale);
			*/

			$return['success'] = true;
			
			
			$dbCurrency = $this->Currency->find('first', [
				'conditions' => [
					'id'=>1
				]
			]);

			$dataRep = array_merge([
				'user_id'=>$userId,
				'amount'=>"4000", //Prix
				'etat'=>false, // Etat pour indiquer si le billet du jour est deja achete
			], $dbCurrency);

			$return = array_merge($return, $dbCurrency, [
				'success'=>true,
				'data'=>$dataRep
			]);


		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();


	}

	public function get_toleka_paye_date($userId = null){
		$return = array('success' => false, 'data' => array(), 'message' => array());
		$return['success'] = true;
		$return['data']['toleka_paye_date'] = date('Y-m-d H:i:s');

		echo json_encode($return); exit();
	}

	public function get_route_toleka($toleka_order_id = null){
		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			
			$dbTolekaOrder = $this->TolekaOrder->find('first', [
				'conditions' => [
					'id'=>$toleka_order_id
				],
				'order'=>['id'=>'desc'],
				'contain'=>['User.Info', 'Chauffeur.Info', 'Chauffeur.CompanyTaxi'],
				'fields' => ['id', 'user_id', 'chauffeur_user_id', 'status','actif', 'abord', 'abord_date', 'chauffeur_trouve', 'categorie_prix', 'distance', 
					'source_latitude', 'source_longitude', 'destination_latitude', 'destination_longitude', 'route_lat_lng',]// 'source_latitude', 'source_longitude', 'destination']
			]);

			//$tolekaOrder = $dbTolekaOrder['TolekaOrder'];

			if(count($dbTolekaOrder)>0){
				$profilChauffeur = Router::url($dbTolekaOrder['Chauffeur']['CompanyTaxi']['photo_profil'], true);
				$telChauffeur = $dbTolekaOrder['Chauffeur']['Info']['phone'];
				$telClient = $dbTolekaOrder['User']['Info']['phone'];
				

				$dbTolekaOrder['TolekaOrder']['tel_client'] = $telClient;
				$dbTolekaOrder['TolekaOrder']['tel_chauffeur'] = $telChauffeur;
				$dbTolekaOrder['TolekaOrder']['photo_profil_chauffeur'] = $profilChauffeur;

				//unset($dbTolekaOrder['User']);
				//unset($dbTolekaOrder['Chauffeur']);
				$return = array_merge($return, [
					'success'=>true,
					'data'=>$dbTolekaOrder,
					//'tel_client' => $telClient,
					//'dbUser'=>$dbUser,
					//'dbChauffeur'=>$dbChauffeur,
				]);
	
			}
			

		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();
	}

	public function get_client_info($id = null){
		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			
			$dbUser = $this->User->find('first', [
				'conditions' => [
					'id'=>$id
				],
				'contain'=>['Info', 'CompanyTaxi'],
				
			]);

			//$tolekaOrder = $dbTolekaOrder['TolekaOrder'];

			if(count($dbUser)>0){
				$tel = $dbUser['Info']['phone'];
				$profil = $dbUser['CompanyTaxi']['photo_profil'];
				
				$return = array_merge($return, [
					'success'=>true,
					'data'=>[
						'tel'=>$tel,
						'photo_profil'=>$profil,
					],
					//'tel_client' => $telClient,
					//'dbUser'=>$dbUser,
					//'dbChauffeur'=>$dbChauffeur,
				]);
	
			}
			

		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();
	}

	public function get_app_version(){
		$return = array('success' => false, 'data' => array(), 'message' => array());
		$return['success'] = true;
		$return['data']['version'] = 8; //8
		$return['data']['obsolete'] = 7; //7

		echo json_encode($return); exit();
	}

	public function get_chauffeur_route_toleka_id($user_id = null){
		$return = array('success' => false, 'data' => array(), 'message' => array());
		
		$dbTolekaOrder = $this->TolekaOrder->find('first', [
			'conditions' => [
				'actif'=>true,
				'chauffeur_trouve'=>true,
				'chauffeur_user_id'=>$user_id
			],
			'order' => array('TolekaOrder.id' => 'DESC'),
			
		]);

		//$tolekaOrder = $dbTolekaOrder['TolekaOrder'];

		if(count($dbTolekaOrder)>0){
				
			$return['success'] = true;
			$return['data']['id'] = $dbTolekaOrder['TolekaOrder']['id'];

		}
	

		echo json_encode($return); exit();
	}

	public function get_client_route_toleka_id($user_id = null){
		$return = array('success' => false, 'data' => array(), 'message' => array());
		
		$return = array('success' => false, 'data' => array(), 'message' => array());
		
		$dbTolekaOrder = $this->TolekaOrder->find('first', [
			'conditions' => [
				'actif'=>true,
				'user_id'=>$user_id
			],
			'order' => array('TolekaOrder.id' => 'DESC'),
			
		]);

		//$tolekaOrder = $dbTolekaOrder['TolekaOrder'];

		if(count($dbTolekaOrder)>0){
				
			$return['success'] = true;
			$return['data']['id'] = $dbTolekaOrder['TolekaOrder']['id'];

		}
	

		echo json_encode($return); exit();
		
	}


	public function test_base_add_toleka_course(){
		if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
			//$this->autoRender = false;
			$this->request->data = json_decode(file_get_contents('php://input'), true);
		}

		$data = $this->request->data;


		//$arrayWeb = json_decode($jsonWeb, true);

		$fromSocket = $this->postRequest('/add_toleka_course_to_socket', $data);

		$return = [
			'success'=>true,
			'data'=> $data,
			'fromSocket' => $fromSocket
		];
		echo json_encode($return);
		exit();
	}

	

	public function test(){
		if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
			//$this->autoRender = false;
			$this->request->data = json_decode(file_get_contents('php://input'), true);
		}

		$data = $this->request->data;



		$jsonWeb = file_get_contents('php://input');
		$arrayWeb = json_decode($jsonWeb, true);

		$return = [
			'data'=> $data,
			'lat'=> -875462,
			'lon'=> 789454
		];
		echo json_encode($return);
		exit();
	}

	public function test_req(){
		$data = $this->postRequest('/test', [
			'data'=>$data,
			'manger'=> 'Riz',
			'devenir'=>'Riche'
		]);

		$data2 = $this->postRequest('/test2', [
			'data'=> $data,
		]);
	
		echo json_encode(['success' => true, 'data'=>$data, 'data2'=>$data2]);
		exit();


	}

	public function update_gps_toleka(){
		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
				//$this->autoRender = false;
				$this->request->data = json_decode(file_get_contents('php://input'), true);
			}

			///$md5 = $this->ApiMd5Search();

			///$Options['md5'] = $md5; 
			$Options['ajax'] = true; 
			$Options['data'] = $this->request->data;

			$return = Hash::merge($return, $this->ApiUpdateGpsToleka($Options));
			

		}catch (Exception $e){
			echo json_encode(
				array(
					'success' => false, 
					'data' => array(), 
					'etat'=>[
						'status'=>-90,
						'message'=>$e->getMessage(),
					],
					'message' => array('exception'=>$e->getMessage())
				)	
			); 
			exit();
		}

		echo json_encode($return);
		exit();
	}

	public function ticket($id = null) {

		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

			$Sale = $this->ApiBusTicketQR(array('sale_id' => $id), true);

			if ($Sale) {
				$return = Hash::merge($return, array('success' => true, 'data' => $Sale));
			}else{
		        $return['message']['error'] [] = "Cette commande est invalide ou n'existe plus";
			}

			//debug($Sale);


		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();


	}


	public function reg() {

		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

	        //$data['data'] = $this->request->data['Search'];
			if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
				$this->autoRender = false;
				$this->request->data = json_decode(file_get_contents('php://input'), true);
			}


			if (!empty($this->request->data)) {
				$this->request->data['passwd'] = $this->request->data['password'];
				$newData['User'] = $newData['Info'] = $this->request->data;
				$newData['User']['tos'] = true;
				$newData['ajax']=true;

				$errors = $this->ApiRegEnter($newData);
				$reg_data_user = $this->reg_data_toUser()??[];
				//$reg_data = $this->Session->read('reg_data')??[];

				if(count($errors)>0){

					$return = array('success' => false, 'data' => $reg_data_user, 'message' => $errors);

				}else{
					$return = array('success' => true, 'data' => $reg_data_user, 'message' => $errors);
				}
				//$this->request->data = $newData;
			}


	        //$Options['data'] = $data;
	       // $Options['ajax'] = true; 

			//$return = Hash::merge($return, $this->ApiReg($Options));

		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();



	}

	public function reg_code() {

		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

	        //$data['data'] = $this->request->data['Search'];
			if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
				$this->autoRender = false;
				$this->request->data = json_decode(file_get_contents('php://input'), true);
			}

			$reg_data = $this->Session->read('reg_data')??[];
			$data = $reg_data;

			$form = ['code'=>$this->request->data];

			$data['ajax']=true;
			$return = $this->ApiRegCode($form, $data);
			
			
/*
			if($messageAlert !=null){
				$return = ['success'=>false, 'data'=>[], 'message'=>['Erreur'=>$messageAlert]];

			}else{
				$return = ['success'=>true, 'data'=>$reg_data, 'message'=>[]];
				
			}
			*/

		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();



	}

	



	public function login() {

		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

	        //$data['data'] = $this->request->data['Search'];

			if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
				$this->autoRender = false;
				$this->request->data = json_decode(file_get_contents('php://input'), true);
			}elseif (empty($this->request->data)) {
		        $this->request->data = array(
					'username' => 'a@a.fr',
					'password' => 'aaaa',
				);
			}

			if (!empty($this->request->data) AND empty($this->request->data['User'])) {
				$newData['User'] = $this->request->data;
				$this->request->data = $newData;
			}


	        $Options['ajax'] = true; 
			$return = Hash::merge($return, $this->ApiLogin($Options));

		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();

	}




	public function logout() {

		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

	        $Options['ajax'] = true; 
			$return = Hash::merge($return, $this->ApiLogout($Options));

		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();

	}


	public function passforgot(){

		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

	        $Options['ajax'] = true; 
			$return = Hash::merge($return, $this->ApiPassforgot($Options));

		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();

	}

	public function passforgotcancel(){
		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {

	        $Options['ajax'] = true; 
			$return = Hash::merge($return, $this->ApiPassforgotcancel($Options));

		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();

		
	}

	public function passforgotsend(){
		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {
			$form = $this->request->data;
			$email = $form['email'];
			
	        $Options = [
				'ajax' => true,
				'email'=>$email,
			]; 
			$return = Hash::merge($return, $this->ApiPassforgotsend($Options));

		} catch (Exception $e) {
			
		}

		//a effacer
		//$return = array_merge($return, ['messageAlert'=> ''.$this->Session->id]);

		echo json_encode($return); exit();
		
		
	}

	public function passforgotsave(){
		$return = array('success' => false, 'data' => array(), 'message' => array());

		try {
			$form = $this->request->data;
			
	        $Options = [
				'ajax' => true,
				'codesecret'=>$form['codesecret'],
				'password'=>$form['password'],
				'passwordConf'=>$form['passwordConf'],
			]; 
			$return = Hash::merge($return, $this->ApiPassforgotsave($Options));

		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();

		
	}




	public function saleshistories() {

		$return = array('success' => false, 'data' => array(), 'message' => array());

		$AuthUserId = $this->AuthUserId(true);
		$authUser = $this->Auth->user();

	    if (!$AuthUserId) {
	        $return['message']['error'] [] = "Vous devez vous connecter avant d'effectuer cette opération";
	    }else{

			try {

				$Sales = $this->Sale->find('all', array(
					'order' => array('created' => 'DESC'),
					'conditions' => array('user_id' => $AuthUserId, 'status' => "2"),
					'contain' => array('Currency', 'From', 'To', 'Ticket', 'Schedule'/*.Car*/, 'Schedule.Destination' => array('From', 'To'), 'Schedule.Destination.Car.Company', 'Rate.From', 'Rate.Currency', 'Rate.To'),
				));

				$Sales = $this->TicketQrGen($Sales, true);

				$Sales = Hash::insert($Sales, '{n}.Sale.operation', 'bus');


				$return = Hash::merge($return, array('success' => true, 'data' => $Sales));

			} catch (Exception $e) {
				
			}

	    }

		echo json_encode($return); exit();

	}

	public function allbuscompanies() {

		$return = array('success' => false, 'data' => array(), 'message' => array());
		try {

			$Companies = $this->Company->find('all', array(
			    //'contain' => array('Media', 'Country'),
			    //'conditions' => array('Country.code' => CakeSession::read('Localisation')),
			));

			foreach ($Companies as $key => $Company) {
			    $AllCompanies[$Company['Company']['id']] = $Company['Company']['name'];
			}

			$return = Hash::merge($return, array('success' => true, 'data' => $AllCompanies));

		} catch (Exception $e) {
			
		}

		echo json_encode($return); exit();

	}

	public function user_delete(){
		$return = array('success' => false, 'data' => array());

		try{

			//$data = [];

			if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
				//$this->autoRender = false;
				$this->request->data = json_decode(file_get_contents('php://input'), true);
			}
	
			$data = $this->request->data;
	
/*
			if($this->request->is('json') AND !empty(file_get_contents('php://input'))){
				$data = json_decode(file_get_contents('php://input'), true);
			}
*/
			$user_id = null;

			if(isset($data['id'])){
				$user_id = $data['id'];
			}

			$Options = ['ajax' => true];
			
			///TEST
			//$return['message']['error'] = $user_id;
			//echo json_encode($return); exit();

			if (empty($user_id)) {
				$return['message']['error'] = "ATTENTION"; //implode(", ", array_keys($data));
				//$return['message']['error'] = 'Vous devez vous connecter avant d\'effectuer cette opération';
				$return['success'] = false;
			}else{
				$Options['user_id'] = $user_id;
			}

			$this->ApiAskDelete($Options);
			$return['success'] = true;
			
		}catch(Exception $e){
			$return['message']['error'] = 'Une erreur est survenue lors de la suppression de votre compte';
			$return['success'] = false;
		}
		

		

		echo json_encode($return); exit();
		return $return;
	}


	public function testk(){
		$return = array('success' => false, 'data' => array(), 'message' => array());
		

		try{
			
			//if ($this->request->is('post')) {
				// Récupération des données du formulaire
				$formData = $this->request->data;
				$userId = $formData['user_id']??"unknown";

				$folderPath = WWW_ROOT.'testk/'.$userId.'/';

				$folder = new Folder($folderPath, true, 0777); 


				$fileKeys = [];

				$imageNames = array_keys($_FILES);

				
				foreach($imageNames as $name){
					$file = $_FILES[$name];

					if(isset($file)){
						$fileKeys[$key] = $file['tmp_name'];

						if (!empty($file['tmp_name'])) {
							$targetPath =$folderPath.$name.'.jpg';
							move_uploaded_file($file['tmp_name'], $targetPath);
						}
					}
				
				}
					
			
				
				// Réponse JSON
				$return = array(
					//'WWW_ROOT'=> $folderPath,
					//'file_exist'=>file_exists($file['tmp_name']),
					//'elements_tmp' => scandir(WWW_ROOT),

					
					'status' => 'success',
					'form_data' => $formData,
					//'file_keys' => $fileKeys,
					//'uploaded_files' => $uploadedFiles,
					//'_serialize' => array('status', 'form_data', 'uploaded_files')
				);
			//} else {
			//	throw new MethodNotAllowedException();
			//}
			

		    
			echo json_encode($return);
	    } catch (Exception $e) {
			echo json_encode($e->getMessage());
		}

		 exit();
	}

	



	
	
	




}