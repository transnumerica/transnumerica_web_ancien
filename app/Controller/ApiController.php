<?php
//use Cake\Http\Client;
App::uses('HttpSocket', 'Network/Http');

  

class ApiController extends Controller {
  // --- FONCTION POUR SAUVEGARDER ---
  





  protected $GOOGLE_MAPS_API_KEY = "AIzaSyBJk8m5DOeM5qTVTxvAch9GygNNRxyGtng";
  protected $PHONE_NUMBERS_STD = ["+243850000001", "+243843325605", "+243824924551", "+243817406676", "+243979148291", "+243896966953", "+243840815232", "+243893121842", "+17202086582"]; 
  protected $SECRET_CODE_STD = "000000";
  
    // On déclare le composant RequestHandler pour sérialiser le JSON automatiquement
    public $components = array('RequestHandler');


    public function saveArrayToFile($filename, $array) {
        // var_export($array, true) génère la représentation chaîne du tableau
        $content = "<?php\nreturn " . var_export($array, true) . ";\n?>";
        file_put_contents($filename, $content);
    }
    
  public function postRequest($route, $body)//, $callback)
    {
      /*
      $client = new Client();
      $response = $http->post('https://socket.tnsarl.com'.$route, $body);// ['type' => 'json']); // Replace with your URL
            
      // Get the response body as a string
      $bodyRep = $response->getBody()->getContents();
      // Decode JSON response
      $data = json_decode($bodyRep, true);

      return $data;
*/    
      $HttpSocket = new HttpSocket();
      try{
        $response = $HttpSocket->post('https://socket.tnsarl.com'.$route, $body);
        return json_decode($response['body']);
        //$data = json_decode($bodyRep, true);
      }catch(Exception $e){

      }
      return ['postRequest'];

    }

  protected function getCityFromLatLng($latitude, $longitude) {
      $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latitude . "," . $longitude . "&key=" . $this->GOOGLE_MAPS_API_KEY;
  
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);
      curl_close($ch);
  
      $data = json_decode($response, true);
  
      if ($data['status'] === 'OK') {
          foreach ($data['results'] as $result) {
              foreach ($result['address_components'] as $component) {
                  if (in_array('locality', $component['types'])) {
                      return $component['long_name']; // City name
                  } else if (in_array('administrative_area_level_3', $component['types'])) {
                      //If locality is unavailable, try admin_area_level_3 which is often a town/city.
                      return $component['long_name'];
                  }
              }
          }
          return null;//"City not found";
      } else {
          return null; //"Geocoding API error: " . $data['status'];
      }
  }
  

  public function ApiMd5Search() {

    $data['data'] = $this->request->data['Search'];
    unset($data['data']['_Token']);

    $data['data'] = serialize($data['data']);
    $md5 = md5(json_encode($data['data']));
    $data['md5'] = $md5;
    $save = $this->Search->save($data);

    return $md5;

  }

  public function ApiAlltowns(){
    $Towns = $this->Town->find('all', array(
      'contain' => array('Media', 'Country'),
      //'conditions' => array('Country.code' => CakeSession::read('Localisation')),
    ));

    $STowns = array();
    
    foreach ($Towns as $key => $Town) {
      
      //$STowns[$key] = $Town;
      
      $country_id = $Town['Country']['id'];
      if(!array_key_exists($country_id, $STowns)){
        $country_code = $Town['Country']['code'];
        $country_name = $Town['Country']['name'];
        $country_mobile_code = $Town['Country']['mobile_code'];

        $STowns[$country_id] = [
          'code' => $country_code,
          'name' => $country_name,
          'mobile_code' => $country_mobile_code,
          'towns' => array()
        ];
      }

        $STowns[$country_id]['towns'][$Town['Town']['id']] = $Town['Town']['name'];
      
      
        //$STowns[$Towns[0]['Country']['name']][$Town['Town']['id']] = $Town['Town']['name'];
    }


    $return = array('success' => true, 'data' => $STowns);

    return $return;
  }

  private function pointUserFromTownCategorie($categorie){
    switch($categorie){
      case 'bus' : return "Company.User";
      case 'vol' : return "Plane.User";
      case 'hotel' : return "User";
      case 'train' : return "Former.User";
      case 'taxi' : return "User";

      default : return "Company.User";
    }

  }

  public function townsCategorifiedByTownFromTo($modelName, $verifSymmetrical = true,  $marchand = []){
    //liste des avions
    $fields = ['from_town_id', 'to_town_id'];
    $arrayQuery = [
      'fields' => $fields,
      'group' => ['from_town_id', 'to_town_id'],
    ];

    //A REMETTRE
    /*
    $marchandUserId = $marchand['userId'];
    $marchandCategorie = $marchand['categorie'];
    */
    // Remplacez les lignes 142-143 par ceci :
    $marchandUserId = isset($marchand['userId']) ? $marchand['userId'] : null;
    $marchandCategorie = isset($marchand['categorie']) ? $marchand['categorie'] : null;


    //if($isAdmin)$arrayQuery['conditions'] = ['not'=>['User.id' => null]];

    if($marchandUserId!=null){
      $isAdmin = $this->MarchandAdmin->find('first', [
        'conditions' => ['user_id' => $marchandUserId]
      ]) != null;

      if(!$isAdmin){
        $arrayQuery['conditions'] = ['User.id' => $marchandUserId];
        $arrayQuery['contain'] = [$this->pointUserFromTownCategorie($marchandCategorie)];
      }
      
    }

    if($verifSymmetrical) array_push($fields, 'symmetrical');
			$townsBrut = $this->{$modelName}->find('all', $arrayQuery);
      
			$towns = [];
			foreach($townsBrut as $town){
				$townInterne = $town[$modelName];
				$from_town_id = $townInterne['from_town_id'];
				$to_town_id = $townInterne['to_town_id'];

				if(empty($towns[$from_town_id])) $towns[$from_town_id] = [];
				array_push($towns[$from_town_id], $to_town_id);

        //debug($townInterne);

				if($verifSymmetrical){
          if($townInterne['symmetrical']??false){
            //echo '<br/>'.$modelName.' : '.$to_town_id.' -> '.$from_town_id;
            
            if(empty($towns[$to_town_id])) $towns[$to_town_id] = [];
            array_push($towns[$to_town_id], $from_town_id);
            
          }
        }
			}

      return $towns;
      
  }

  public function townsCategorifiedByTown($modelName, $marchand = []){

    $arrayQuery = [
      'fields' => ['town_id'],
      'group' => ['town_id'],
    ];

    // A REMETTRE
    /*
    $marchandUserId = $marchand['userId'];
    $marchandCategorie = $marchand['categorie'];
    */

    // Remplacez les lignes 173-174 par ceci :
    $marchandUserId = isset($marchand['userId']) ? $marchand['userId'] : null;
    $marchandCategorie = isset($marchand['categorie']) ? $marchand['categorie'] : null;

    if($marchandUserId!=null){
      $isAdmin = $this->MarchandAdmin->find('first', [
        'conditions' => ['user_id' => $marchandUserId]
      ]) != null;

      if(!$isAdmin){

        $arrayQuery['conditions'] = ['User.id' => $marchandUserId];
        $arrayQuery['contain'] = [$this->pointUserFromTownCategorie($marchandCategorie)];
      }
    }

    $townsBrut = $this->{$modelName}->find('all', $arrayQuery);

    $towns = [];
    foreach($townsBrut as $town){
      array_push($towns ,$town[$modelName]['town_id']);
    }
      

    return $towns;
      
  }

  public function validateDate($date, $format = 'Y-m-d'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
  }

  private function pointCompagnieModelByService($service){
    switch($service){
      case 'bus' : return "Company";
      case 'avion' : return "CompanyPlane";
      case 'hotel' : return "CompanyHotel";
      case 'train' : return "CompanyFormer";
      case 'taxi' : return "CompanyTaxi";

      default : return "Company";
    }

  }

  private function nameDestinationByService($service){
    switch($service){
      case 'bus' : return "Destination";
      case 'avion' : return "PlaneDestination";
      case 'hotel' : return "HotelRoom";
      case 'train' : return "FormerDestination";
      case 'taxi' : return "CompanyTaxi";

      default : return "Destination";
    }

  }

  public function SalesMarchand($Options = []){
    $return = ['success' => false, 'data' => []];

    try{

      extract($Options);

      $isAdmin = $this->MarchandAdmin->find('first', [
        'conditions' => ['user_id' => $data['marchand_user_id']]
      ]) != null;


      $service = $data['categorie'];
      if($service == 'vol') $service = 'avion';

      $conditions=['service'=> $service,
      'status' => 2];

      $contain = [
        'User', 'User.Info', 'Currency', 
      ];

      $join = null;

      $companyName = $this->pointCompagnieModelByService($service);
      $companyDestination = $this->nameDestinationByService($service);

      

      switch($service){
        case 'bus' : 
          array_push($contain, 'Rate.Destination', 'Rate.Destination.Company');
          if(!$isAdmin) $conditions['Company.user_id'] =  $data['marchand_user_id'];
          $conditions['Destination.id'] =  $data['destination_id'];
          break;

        default : 
          if(!$isAdmin) $conditions[''.$companyName.'.user_id'] =  $data['marchand_user_id'];
          //$conditions[''.$companyDestination.'.id'] =  $data['destination_id'];
          $conditions['company_destination_id'] =  $data['destination_id'];
          

      }

      //return array_merge($return, ['Comp'=>''.$companyDestination.'.id']);
          
      
      

      
      if($data['check_date']==true){
        $dateMin = $data['date_min'];
        $dateMax = $data['date_max'];
        if($this->validateDate($dateMin)) $conditions['departure_date >= '] = $dateMin;
        if($this->validateDate($dateMax)) $conditions['departure_date <= '] = $dateMax;
        
      }

      $or = [];
      
      if($data['check_client']){
        $strClient = $data['search_client'];
        //spec chars spaces
        $strClient = str_replace([',', ';', ':', '/', '"', "'", '&', '%'], " ", $strClient);
        
        $arrayClientVol = explode(" ", $strClient);
        foreach($arrayClientVol as $one){
          if(strlen($one)>0){
            $modif = '%'.$one.'%';
            array_push($or, 
              ['Info.firstname'.' LIKE' => $modif],
              ['Info.name'.' LIKE' => $modif],
              ['Info.phone'.' LIKE' => $modif],
              ['User.email'.' LIKE' => $modif],
              ['Sale.phone'.' LIKE' => $modif],
            );
            
            //array_push($arrayClient, '%'.$one.'%');
          }
        }

        
      }

      if(count($or)>0) $conditions['OR'] = $or;
      
      

      $sales = $this->Sale->find('all', [
        'conditions' => $conditions,
        'contain' =>$contain,
        'order'=>['Sale.departure_date'=>'desc'],
        'join'=>$join,
      ]);

      
        
      $canAdded = function ($sale, $companyName, $companyDestination, $data, $isAdmin){return true;};
      
      /*
      if($service != 'bus'){
        $canAdded = function ($sale, $companyName, $companyDestination, $data, $isAdmin){
          $findOne = $this->{$companyName}->find('first', [
            'conditions' => [
              'id'=>$sale['Sale']['category_company_id'],
              //'HotelRoom.id'=>23,//$sale['Sale']['company_destination_id'],//$companyDestination.'.id' => $sale['Sale']['company_destination_id'],
              'user_id' => $isAdmin?null:$data['marchand_user_id'],
              
            ],
            //'contain' => ['HotelRoom']//$companyDestination,
          ]);

          return $findOne!=null;
        };
      }
*/
     
      $salesRes = [];
      foreach($sales as $sale){
        $ok = [];
        $ok['command'] = $sale['Sale']['command'];
        $ok['consomme'] = $sale['Sale']['consomme'];
        $ok['created'] = $sale['Sale']['created'];
        $ok['departure_date'] = $sale['Sale']['departure_date'];
        $ok['mobile_money'] = $sale['Sale']['phone'];
        $ok['total'] = $sale['Sale']['server_total'];
        $ok['currency_iso'] = $sale['Currency']['iso'];

        $ok['email'] = $sale['User']['email'];
        $ok['firstname'] = $sale['User']['Info']['firstname'];
        $ok['name'] = $sale['User']['Info']['name'];
        $ok['phone'] = $sale['User']['Info']['phone'];
        $ok['ticket_id'] = $sale['Sale']['id'];

        
        /*
        $findOne = $this->find('first', [
          $salesRes = 
        ]);
        */
        if($canAdded($sale, $companyName, $companyDestination, $data, $isAdmin)) array_push($salesRes, $ok);

      }
      $return = ['success' => true, 'data' => $salesRes];//, 'overData'=>$sales];
      

      //$return = ['success' => true, 'data' => $sales];


    }catch(Exception $e){

    }

    return $return;
  }


  public function ApiSearchBusSimple($Options = []){
    $return = ['success' => false, 'data' => []];

    try{

      extract($Options);

      $conditions = [
        'from_town_id'=> $data['from_town_id'],
        'to_town_id'=> $data['to_town_id'],
      ];

      $isAdmin = $this->MarchandAdmin->find('first', [
        'conditions' => ['user_id' => $data['marchand_user_id']]
      ]) != null;


      if(!$isAdmin && is_numeric($data['marchand_user_id'])) $conditions['Company.user_id']=$data['marchand_user_id'];

      $destinations = $this->CompanyDestination->find('all', array(
        'conditions' => $conditions,
        'contain' =>[
          'From', 'To', 'Company', 'Car', 'Currency'
        ],
        'order'=>['CompanyDestination.id'=>'desc']
        //'joins' => array($this->Company->Destination->Schedule->fastJoint('Destination'), $this->Company->Destination->fastJoint('Rate')),
      ));
      
      
      $return = ['success' => true, 'data' => $destinations];

    }catch(Exception $e){
      
    }

    return $return;
  }
  
  public function handleRequest() {
        // En CakePHP 2.x, on accède à l'environnement via l'objet CakeRequest
        
        // Dit à CakePHP de faire confiance aux en-têtes de proxy HTTP
        $this->request->trustProxy = true; 
        $clientIp = $this->request->clientIp();
        
        $vraiIpDuTelephone = env('HTTP_X_REAL_IP');
        if (!empty($vraiIpDuTelephone)) {
            // Si cet en-tête existe, c'est qu'un proxy masquait l'IP !
            $clientIp = explode(',', $vraiIpDuTelephone)[0]; // On prend la première IP de la liste
        }
        
        $logData = array(
            'client_ip'        => $clientIp,
            'session_id' => session_id(),
            'accept_language'  => env('HTTP_ACCEPT_LANGUAGE'),
            'remote_port'      => (int)env('REMOTE_PORT'),
            
            // Encodage en JSON des données reçues (POST/PUT) et des paramètres d'URL (GET)
            //'request_payload'  => json_encode($this->request->data, JSON_UNESCAPED_UNICODE),
            //'request_payload' => json_encode($_SERVER),
            //'query_params'     => json_encode($this->request->query, JSON_UNESCAPED_UNICODE),
            
            'request_method'   => $this->request->method(),
            'is_json'          => $this->request->is('json') ? 1 : 0,
            'is_ssl'           => $this->request->is('ssl') ? 1 : 0,
            //'request_path'     => $this->request->here, // Donne le chemin complet de l'URL
            //'cake_controller'  => $this->request->params['controller'],
            'cake_action'      => $this->request->params['action'],
            'cake_pass_params' => implode(',', $this->request->params['pass']),
            //'http_host'        => env('HTTP_HOST'),
        );

        // En CakePHP 2.x, pas d'entités (newEntity). On passe directement le tableau à save().
        // On réinitialise d'abord le modèle pour éviter les effets de bord
        $this->Requete->create();
        
        if ($this->Requete->save($logData)) {
            $response = array('success' => true, 'message' => 'Requête journalisée avec succès.');
        } else {
            // Récupération des erreurs de validation façon CakePHP 2
            $errors = $this->Requete->validationErrors;
            $this->log($errors, 'error');
            $response = array('success' => false, 'errors' => $errors);
        }

        // Rendu JSON propre pour CakePHP 2.x (géré par RequestHandler)
        $this->set(array(
            'response' => $response,
            '_serialize' => array('response')
        ));
        
    }

  public function ApiSearchBus($Options = array()) {
    
  try{
      
    

    // 1. Définir des valeurs par défaut pour éviter les erreurs "Undefined variable"
      $md5 = isset($Options['md5']) ? $Options['md5'] : null;
      $ajax = isset($Options['ajax']) ? $Options['ajax'] : false;

      // 2. Extraire les autres variables seulement si nécessaire
      
      extract($Options);

      $Search = $this->Search->find('first', array(
        'conditions' => array('md5' => $md5)
      ));


      if (empty($Search)) {
        $Search['Search']['data']['form_date'] = date('m/d/Y');
        $Search['Search']['data']['from_town_id'] = '';
        $Search['Search']['data']['to_town_id'] = '';
      }

      //debug($Search);

      if ($Search) {

        if (!Validation::date($Search['Search']['data']['form_date'], 'mdy')) {
          $Search['Search']['data']['form_date'] = date('m/d/Y');
        }


        if (CakeTime::format($Search['Search']['data']['form_date'], '%Y-%m-%d') < date('Y-m-d')) {
      //$this->Flash->set("Vous ne pouvez pas rechercher une date déjà ecoulé", ['params' => 'echec']);
      $Search['Search']['data']['form_date'] = date('m/d/Y');
        }



        $this->request->data['Search'] = $Search['Search']['data'];
      }

      
      $Companies = $this->Company->find('all', array(
        //'conditions' => $conditionsSchedules,
        //'joins' => array($this->Company->Destination->Schedule->fastJoint('Destination'), $this->Company->Destination->fastJoint('Rate')),
      ));
      $this->set('Companies', $Companies);
      //debug($Companies);




      $Tags = $this->Company->Car->Tag->find('all', array(
        //'conditions' => $conditionsSchedules,
        //'joins' => array($this->Company->Destination->Schedule->fastJoint('Destination'), $this->Company->Destination->fastJoint('Rate')),
      ));
      $this->set('Tags', $Tags);
      //debug($Companies);








      $conditionsSchedules = array();
      /*
      $conditionsSchedules[1]['OR'][]['start_day LIKE'] = "%".date('w', strtotime($Search['Search']['data']['form_date'])).'%'

      ;
      $conditionsSchedules[1]['OR'][]['start_day'] = $Search['Search']['data']['form_date'];
      */
      $conditionsSchedules['Rate.from_town_id'] = $Search['Search']['data']['from_town_id'];
      $conditionsSchedules['Rate.to_town_id'] = $Search['Search']['data']['to_town_id'];
      $conditionsSchedules['Destination.visible'] = true;

      
      $Schedules = $this->Company->Destination->Schedule->find('all', array(
        'conditions' => $conditionsSchedules,
        'joins' => array($this->Company->Destination->Schedule->fastJoint('Destination'), $this->Company->Destination->fastJoint('Rate')),
        'order'=>['Destination.id'=>'desc']
      ));

      $paramGain = $this->ParamGain->find('first', [
        'order'=>['id'=>'desc']
      ]);

      foreach ($Schedules as $key => &$Schedule) {
        $Rate = $this->Company->Destination->Rate->find('first', array(
          'contain' => array('From', 'To', 'Destination' => array('Company', 'Car.Tag', 'Car.Company'),'Currency'),
            'conditions' => array('destination_id' => $Schedule['Schedule']['destination_id'], 'from_town_id' => $Search['Search']['data']['from_town_id'], 'to_town_id' => $Search['Search']['data']['to_town_id']),
          ));

          $prix_str = $Rate['Rate']['amount'];
          //$prix = floatval($prix_str);
          $currency = $Rate['Currency']['iso'];
          
          $prix = $this->newPrice($prix_str, $currency);

    /*
          
          $tnUsd = floatval($paramGain['ParamGain']['tnsarl_usd']);
          $avadaPourcent = floatval($paramGain['ParamGain']['avada_pourcent']);
          $taux_usd_cdf = floatval($paramGain['ParamGain']['taux_usd_cdf']);
          $ceilN = 1;

          if($currency == 'CDF'){
            $prix += $tnUsd*$taux_usd_cdf;
            $ceilN = 0.01;
          }else if($currency == 'USD'){
            $prix += $tnUsd;
            $ceilN = 10;
          }

          $prix += (($avadaPourcent/100)*$prix);
          $prix = ceil($prix*$ceilN)/$ceilN;
          
    */

          $Rate['Rate']['amount'] = number_format($prix, 2,"." ,"");



        
          $Schedule = Hash::merge($Schedule, $Rate);
        }



        $this->set('Schedules', $Schedules);

        $Schedules = Hash::remove($Schedules, '{n}.Schedule.destination_id');
        $Schedules = Hash::remove($Schedules, '{n}.Schedule.created');
        $Schedules = Hash::remove($Schedules, '{n}.Schedule.modified');

        $Schedules = Hash::remove($Schedules, '{n}.Rate.destination_id');
        $Schedules = Hash::remove($Schedules, '{n}.Rate.from_town_id');
        $Schedules = Hash::remove($Schedules, '{n}.Rate.to_town_id');
        $Schedules = Hash::remove($Schedules, '{n}.Rate.currency_id');
        $Schedules = Hash::remove($Schedules, '{n}.Rate.created');
        $Schedules = Hash::remove($Schedules, '{n}.Rate.modified');

        $Schedules = Hash::remove($Schedules, '{n}.From.country_id');
        $Schedules = Hash::remove($Schedules, '{n}.From.online');
        $Schedules = Hash::remove($Schedules, '{n}.From.created');
        $Schedules = Hash::remove($Schedules, '{n}.From.modified');

        $Schedules = Hash::remove($Schedules, '{n}.To.country_id');
        $Schedules = Hash::remove($Schedules, '{n}.To.online');
        $Schedules = Hash::remove($Schedules, '{n}.To.created');
        $Schedules = Hash::remove($Schedules, '{n}.To.modified');

        $Schedules = Hash::remove($Schedules, '{n}.Destination.company_id');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.from_town_id');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.car_id');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.from_point');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.to_town_id');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.to_point');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.min_arrival');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.amount');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.currency_id');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.description');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.note');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.created');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.modified');

        $Schedules = Hash::remove($Schedules, '{n}.Destination.Company.fullname');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Company.code');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Company.icon');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Company.favorite');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Company.online');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Company.mobile_code');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Company.mobile_length');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Company.cover');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Company.created');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Company.modified');

        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.company_id');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.fullname');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.descripton');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.cover');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.created');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.modified');

        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.Tag.{n}.created');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.Tag.{n}.modified');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.Tag.{n}.CompanyCarRelation');

        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.Company.fullname');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.Company.code');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.Company.icon');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.Company.favorite');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.Company.online');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.Company.mobile_code');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.Company.mobile_length');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.Company.cover');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.Company.created');
        $Schedules = Hash::remove($Schedules, '{n}.Destination.Car.Company.modified');

        $Schedules = Hash::remove($Schedules, '{n}.Currency.number');
        $Schedules = Hash::remove($Schedules, '{n}.Currency.favorite');
        $Schedules = Hash::remove($Schedules, '{n}.Currency.online');
        $Schedules = Hash::remove($Schedules, '{n}.Currency.created');
        $Schedules = Hash::remove($Schedules, '{n}.Currency.modified');



    }catch (Exception $e) {
			$return = array('success' => false, 'data' => [], 'exception'=>$e->getMessage());
      return $return;
		}

    if ($ajax) {

      $return = array('success' => true, 'data' => $Schedules);
      return $return;

    }

    //debug($Schedules);


        //debug($this->request->data);

    //debug($Search);


  }

  public function ApiBusNbrPlaceVendues($Options = []){
    extract($Options);

    
    $Sales = $this->Sale->find('all', array(
      'conditions' => [
        'status'=>2, 
        'departure_date'=>$data['departure_date'], 
        'service'=>'bus', 
        'company_destination_id'=>$data['company_destination_id']],
    ));

    $nbrPlaceVendues = 0;
    foreach($Sales as $key => $Sale){
      $nbrPlaceVendues += intVal($Sale['Sale']['nbr_place']??0);
    }

    $return = ['success' => true, 'data' => ['nbr_place_vendues'=>$nbrPlaceVendues]];

    return $return;
  }

  public function newPrice($prix_str, $currency){
    // 1. Sécurité sur la globale
    if (!isset($GLOBALS['paramGain']['ParamGain'])) {
        // En cas d'erreur de config, on renvoie le prix brut au lieu de planter
        return $prix_str;
    }

    $paramGain = $GLOBALS['paramGain']['ParamGain'];

    // 2. Utilisation de isset() pour éviter les "Undefined index"
    $tnUsd = isset($paramGain['tnsarl_usd']) ? floatval($paramGain['tnsarl_usd']) : 0;
    $avadaPourcent = isset($paramGain['avada_pourcent']) ? floatval($paramGain['avada_pourcent']) : 0;
    $taux_usd_cdf = isset($paramGain['taux_usd_cdf']) ? floatval($paramGain['taux_usd_cdf']) : 1;
    
    $prix = floatval($prix_str);
    $ceilN = 1;

    // 3. Calculs
    if($currency == 'CDF'){
      $prix += $tnUsd * $taux_usd_cdf;
      $ceilN = 0.01;
    } else if($currency == 'USD'){
      $prix += $tnUsd;
      $ceilN = 10;
    }

    $prix += ($avadaPourcent / 100) * $prix;
    
    // Éviter la division par zéro si $ceilN était 0
    if ($ceilN > 0) {
        $prix = ceil($prix * $ceilN) / $ceilN;
    }


    
    return number_format($prix, 2, ".", "");
  }

  public function ApiSearchPlane($Options = array()) {

    extract($Options);

    $conditions = ['visible'=>true,
      'or'=>[
        [
          'from_town_id'=> $data['from_town_id'],
          'to_town_id'=> $data['to_town_id'],
        ],

        [
          'from_town_id'=> $data['to_town_id'],
          'to_town_id'=> $data['from_town_id'],
          'symmetrical'=>true,
        ]
      ]
      
    ];

    



    $marchandUserId = isset($data['marchand_user_id']) ? $data['marchand_user_id'] : null;

    

    // 3. Vérification sécurisée pour isAdmin
    $isAdmin = false;
    if ($marchandUserId) {
        $adminRecord = $this->MarchandAdmin->find('first', [
            'conditions' => ['user_id' => $marchandUserId]
        ]);
        // On vérifie si $adminRecord n'est pas vide
        $isAdmin = !empty($adminRecord);
    }


    if(!$isAdmin && is_numeric($data['marchand_user_id']??null)) $conditions['Plane.user_id']=$data['marchand_user_id'];

    $destinations = $this->CompanyPlaneDestination->find('all', array(
      'conditions' => $conditions,
      'contain' =>[
        'Plane', 'Currency'
      ],
      'order'=>['CompanyPlaneDestination.id'=>'desc']
      //'joins' => array($this->Company->Destination->Schedule->fastJoint('Destination'), $this->Company->Destination->fastJoint('Rate')),
    ));

    
    $GLOBALS['paramGain'] = $this->ParamGain->find('first', [
      'order'=>['id'=>'desc']
    ]);

    $GLOBALS['thisObj'] = $this;

    
    
    function newPriceGlobal($matches){
      
      $currency = $GLOBALS['currency'];
      $thisObj = $GLOBALS['thisObj'];
      return '='.$thisObj->newPrice($matches[1], $currency).';';
    }

    function amounts($name, $destination, $currency){
      //$currency_gl = $currency;
      
      $GLOBALS['currency'] = $currency;

      return preg_replace_callback('/(?:=([0-9.]+)(?:;|$))/', "newPriceGlobal", $destination['CompanyPlaneDestination'][$name]);//$destination['CompanyPlaneDestination'][$name];// preg_replace("/(?:=([0-9.]+)(?:;|$))/e", "$3", $destination['CompanyPlaneDestination'][$name]);
    }

    foreach($destinations as $key => &$destination){
      $currency = $destination['Currency']['iso'];
      $destination['CompanyPlaneDestination']['amounts_economic_oneway'] = amounts('amounts_economic_oneway', $destination, $currency);
      $destination['CompanyPlaneDestination']['amounts_economic_roundtrip'] = amounts('amounts_economic_roundtrip', $destination, $currency);
      $destination['CompanyPlaneDestination']['amounts_firstclass_oneway'] = amounts('amounts_firstclass_oneway', $destination, $currency);
      $destination['CompanyPlaneDestination']['amounts_firstclass_roundtrip'] = amounts('amounts_firstclass_roundtrip', $destination, $currency);
      
      $destination['TESTE'] = preg_replace_callback('/(?:=([0-9.]+)(?:;|$))/', "newPriceGlobal", $destination['CompanyPlaneDestination']['amounts_economic_oneway']);
    }
    
    $return = ['success' => true, 'data' => $destinations];

    return $return;
  }

  public function ApiSearchHotel($Options = array()) {
    // 1. Récupération sécurisée des données (évite le plantage sur undefined)
    $data = isset($Options['data']) ? $Options['data'] : [];
    $townId = isset($data['town_id']) ? $data['town_id'] : null;
    $marchandUserId = isset($data['marchand_user_id']) ? $data['marchand_user_id'] : null;

    // 2. Initialisation des conditions avec valeurs par défaut
    $conditions = [
        'town_id' => $townId,
        'visible' => true,
    ];

    // 3. Vérification sécurisée pour isAdmin
    $isAdmin = false;
    if ($marchandUserId) {
        $adminRecord = $this->MarchandAdmin->find('first', [
            'conditions' => ['user_id' => $marchandUserId]
        ]);
        // On vérifie si $adminRecord n'est pas vide
        $isAdmin = !empty($adminRecord);
    }

    // 4. Application du filtre si ce n'est pas admin
    if (!$isAdmin && is_numeric($marchandUserId)) {
        $conditions['Hotel.user_id'] = $marchandUserId;
    }

    // 5. Recherche
    $hotels = $this->CompanyHotel->find('all', array(
        'conditions' => $conditions,
        'order' => ['CompanyHotel.id' => 'desc'],
        'contain' => ['Currency', 'HotelRoom', 'HotelRoom.Media'],
    ));

    // 6. Récupération des gains (sécurisé)
    $paramGain = $this->ParamGain->find('first', ['order' => ['id' => 'desc']]);
    $GLOBALS['paramGain'] = $paramGain;

    // 7. Calcul des prix
    foreach($hotels as $key => &$hotel){
        $currency = isset($hotel['Currency']['iso']) ? $hotel['Currency']['iso'] : 'USD';

        if (!empty($hotel['HotelRoom'])) {
            foreach($hotel['HotelRoom'] as $keyRoom => &$room){
                $room['amount'] = $this->newPrice($room['amount'], $currency);
            }
        }
    }

    return ['success' => true, 'data' => $hotels];
}

  public function ApiHotelNbrPlaceVendues($Options = []){
    extract($Options);

    
    $Sales = $this->Sale->find('all', array(
      'conditions' => [
        'status'=>2, 
        'departure_date'=>$data['departure_date'], 
        'service'=>'hotel', 
        'company_destination_id'=>$data['company_destination_id']],
    ));

    $nbrPlaceVendues = 0;
    foreach($Sales as $key => $Sale){
      $nbrPlaceVendues += intVal($Sale['Sale']['nbr_place']??0);
    }

    $return = ['success' => true, 'data' => ['nbr_place_vendues'=>$nbrPlaceVendues]];

    return $return;
  }

  public function ApiSearchFormer($Options = array()) {

    extract($Options);
    
    $conditions = ['visible'=>true,
      'or'=>[
        [
          'from_town_id' => $data['from_town_id'],
          'to_town_id' => $data['to_town_id'],
        ],
        [
          'from_town_id' => $data['to_town_id'],
          'to_town_id' => $data['from_town_id'],
          'symmetrical' => true
        ]
        
        ]
      ];


    $isAdmin = $this->MarchandAdmin->find('first', [
      'conditions' => ['user_id' => $data['marchand_user_id']??null]
    ]) != null;

    if(!$isAdmin && is_numeric($data['marchand_user_id']??null)) $conditions['Former.user_id']=$data['marchand_user_id'];


    $destinations = $this->CompanyFormerDestination->find('all', array(
      'conditions' => $conditions,
        'contain' =>[
          'Former', 'Currency'
        ],
        'order'=>['CompanyFormerDestination.id'=>'desc'],
        
      //'joins' => array($this->Company->Destination->Schedule->fastJoint('Destination'), $this->Company->Destination->fastJoint('Rate')),
    ));

    
    
    $GLOBALS['paramGain'] = $this->ParamGain->find('first', [
      'order'=>['id'=>'desc']
    ]);

    $GLOBALS['thisObj'] = $this;

    function newPriceGlobal($matches){
      
      $currency = $GLOBALS['currency'];
      $thisObj = $GLOBALS['thisObj'];
      return '='.$thisObj->newPrice($matches[1], $currency).';';
    }

    function amounts($name, $destination, $currency){
      //$currency_gl = $currency;
      
      $GLOBALS['currency'] = $currency;

      return preg_replace_callback('/(?:=([0-9.]+)(?:;|$))/', "newPriceGlobal", $destination['CompanyFormerDestination'][$name]);//$destination['CompanyFormerDestination'][$name];// preg_replace("/(?:=([0-9.]+)(?:;|$))/e", "$3", $destination['CompanyFormerDestination'][$name]);
    }

    foreach($destinations as $key => &$destination){
      $currency = $destination['Currency']['iso'];
      $destination['CompanyFormerDestination']['amounts_economic_oneway'] = amounts('amounts_economic_oneway', $destination, $currency);
      $destination['CompanyFormerDestination']['amounts_economic_roundtrip'] = amounts('amounts_economic_roundtrip', $destination, $currency);
      $destination['CompanyFormerDestination']['amounts_firstclass_oneway'] = amounts('amounts_firstclass_oneway', $destination, $currency);
      $destination['CompanyFormerDestination']['amounts_firstclass_roundtrip'] = amounts('amounts_firstclass_roundtrip', $destination, $currency);
      
      $destination['TESTE'] = preg_replace_callback('/(?:=([0-9.]+)(?:;|$))/', "newPriceGlobal", $destination['CompanyFormerDestination']['amounts_economic_oneway']);
    }


    $return = ['success' => true, 'data' => $destinations];

    //print_r($hotels);
    return $return;
  }

  public function ApiSearchTaxi($Options = array()) {
    
    $return = ['success' => false, 'data' => [], 'message' => []];

    

    extract($Options);

    $conditions = [
      'town_id' => $data['town_id'],
      'visible'=>true,
    ];

    $isAdmin = $this->MarchandAdmin->find('first', [
      'conditions' => ['user_id' => $data['marchand_user_id']]
    ]) != null;

    if(!$isAdmin && is_numeric($data['marchand_user_id'])) $conditions['CompanyTaxi.user_id']=$data['marchand_user_id'];


    $taxis = $this->CompanyTaxi->find('all', array(
      'conditions' => $conditions,
      'contain' =>[
        'Town',
        'Town.TaxiCity',
        'Town.TaxiCity.Currency',
        'User.Info'
      ],
      'order'=>['CompanyTaxi.id'=>'desc'],
      //'joins' => array($this->Company->Destination->Schedule->fastJoint('Destination'), $this->Company->Destination->fastJoint('Rate')),
    ));

    
    $GLOBALS['paramGain'] = $this->ParamGain->find('first', [
      'order'=>['id'=>'desc']
    ]);

    
    //return array_merge($return, ['test'=> $taxis]);
/*
    foreach($taxis as $key => &$taxi){
      $taxiCity = &$taxi['Town']['TaxiCity'][0];
      $currency = $taxiCity['Currency']['iso'];
      $prixL = &$taxiCity['hour_amount'];
      $prixL = $this->newPrice($prixL, $currency);
      
      
      //$hotel['TESTE'] = 
    }
*/
    

    $return = ['success' => true, 'data' => $taxis];

    //print_r($hotels);
    return $return;
  }

  public function save_unipesa_payment_c2b($paymentUnipesa) {

    $lstDon = ['sale_id', 'customer_id', 'order_id', 'country', 'amount', 'currency', 'provider_id', 'name', 'details'];


    $data = [];
    foreach($lstDon as $value){
      $data[$value] = $paymentUnipesa[$value];
    }

    return $this->UnipesaPaymentC2b->save($data);

    
  }

  public function save_unipesa_callback_c2b($callbackUnipesa) {

    $lstDon = ['operation_type', 'customer_id', 'order_id', 'country', 'amount', 'currency', 'provider_id', 'transaction_id',
    'transaction_ref', 'status', 'destination_id', 'result', 'provider_result', 'service_id', 'service_date_time', 'signature', 'details'];


    $data = [];
    foreach($lstDon as $value){
      $data[$value] = $callbackUnipesa[$value];
    }

    
    $return = $this->UnipesaCallbackC2b->save($data);



    $uniPay = $this->UnipesaPaymentC2b->find('first', [
			'conditions'=>['order_id' => $callbackUnipesa['order_id']]
		]);

    $status = $callbackUnipesa['status'];
		
		$sale_id = ( ($uniPay??[])['UnipesaPaymentC2b']??[] )['sale_id'];
		$this->Sale->save(['id'=>$sale_id, 'status'=>$status, 'message'=>($callbackUnipesa['provider_result']??[])["message"]]);
    
    if($status == 2){
      $sale = $this->Sale->find('first', ['conditions'=>['id'=> $sale_id]]);
      $userId = ( ($sale??[])['Sale']??[] )['user_id'];

      // On envoie une notification sur chez le destinateur
      $User = $this->User->Info->find('first', array(
        'contain' => array('User'),
        'conditions' => array('User.id' => $userId)
      ));


      $gendText1 = 'le bienvenu';
      if($User['Info']['gender'] == 'f'){
        $gendText1 = 'la bienvenue';
      }
      $gendText1 = 'la bienvenue';

      $sale_link = Router::url(array('controller' => 'buy', 'action' => 'successful', $this->Sale->id), true);

      $options = array(
        'config' => 'noreply',
        'replyTo' => Configure::read('Mail.contact.Business.email'),
        'to' => array($User['User']['email'] => $User['Info']['fullname']),
        'subject' => "Billet(s) achéte(s) dans TransNumerica",
        //'template' => "bookinghotel",
        'message' => '<p>Vous avez achété avec succès cette commande</p><p><a href="'.$sale_link.'">Cliquer ici</a></p>',
        'debug' => 1,
      );

      if(Validation::email($User['User']['email'])){
        try {
          Op::sendMail($options);
        } catch (Exception $e) {
          
        }
      }
      
    }
    

    return $return;

    
  }

  public function buyAppMobileMoney($Options = array()) {

      $return = ['success' => false, 'data' => [], 'message' => []];

      $ajax = false; 
      $from_web = false;
      extract($Options);

      $AuthUserId = $this->AuthUserId(true);

      if (!$AuthUserId) {

        if ($ajax) {
          $return['message']['error'] [] = "Vous devez vous connecter avant d'effectuer cette opération";
          return $return;
        }

      }


      if (!empty($data)) {
        $this->request->data = $data;
      }

      

      //Test
      //echo json_encode(['place'=> $this->request->data['Sale']['info']['place']]); exit();

      foreach ($this->request->data['Sale']['info']['place'] as $placeKey => $placePrice) {
        $this->request->data['Sale']['Ticket'][] = array('rate_id' => $this->request->data['Sale']['rate_id'], 'amount' => $placePrice, 'place' => $placeKey);
      }

      //echo json_encode($this->request->data['Sale']['info']['place']); exit();


      if (empty($this->request->data['Sale']['Ticket'])) {
        $return['message']['error'] [] = "Vous n'avez pas selectionné des tickets";
        return $return;
      }
/*ici */
      //echo json_encode(array('success' => "kevin", 'data' => array(), 'message' => array('Sale'=>$this->request->data['Sale']))); exit();
      $save = $this->Sale->saveAll($this->request->data['Sale'], array('deep' => true));
      //debug($this->request->data);
      
/*ici fin*/

     

      if ($save) {

        
          
        
      
        if (!$ajax) {
          $this->redirect(array('controller' => 'buy', 'action' => 'successful', $this->Sale->id));
        }

        if($from_web){

          $dataDB = [
            'sale_link' => $sale_link??'',
            'sale_id' => $this->Sale->id
          ];
          
          
          

          $SaleDB = $this->ApiBusTicketQR(array('sale_id' => $this->Sale->id), true);
      
          if ($SaleDB) {
            $dataDB['SaleDB'] = $SaleDB;
          }else{
            $return['message']['error'] [] = "Cette commande est invalide ou n'existe plus";
          }
          

          $return = array('success' => true, 'data' => $dataDB, 'sale_id'=>$this->Sale->id);

          
        }
        else $return = array('success' => true, 'data' => $this->Sale->id, 'sale_id'=>$this->Sale->id);
        
        return $return;


      } else{
        $return['message']['error'] [] = "Echec a l'enregistrement de la transaction";
        return $return;
      }




  }

  protected $chauffeurImageNames = [
    'photo_profil' => 'photo de profil',
    'carte_identite' => "carte d'identité", 
    'permis_conduire' => "permis de conduire",
    'autorisation_transport' => "autorisation de transport", 
    'controle_technique' => 'controle technique', 
    'assurance' => 'assurance'
  ];

  protected function saveTolekaImages($imageNames, $userId){
    $relativeFolder = 'toleka/chauffeur/'.$userId.'/';
    //$folderPath = WWW_ROOT.$relativeFolder;

    $folder = new Folder(WWW_ROOT.$relativeFolder, true, 0777); 

    $files_saved = [];

    foreach($imageNames as $name){
      $file = $_FILES[$name];

      if(isset($file)){
        //$fileKeys[$key] = $file['tmp_name'];

        if (!empty($file['tmp_name'])) {
          $relativePath = $relativeFolder.$name.'.jpg';
          $targetPath = WWW_ROOT.$relativePath;
          move_uploaded_file($file['tmp_name'], $targetPath);

          $files_saved[$name] = '/'.$relativePath;
        }
      }
    
    }

    return $files_saved;
  }




  public function ApiRegToleka($Options = array()) {
    try{

      $ajax = false;
      extract($Options);

      $AuthUserId = $this->AuthUserId(true);

      $return = ['success'=>false, 'data'=>[], 'message'=>[]];

      /*
      if ($AuthUserId) {

        if ($ajax) {
          $return['message']['error'] [] = 'Vous êtes deja connecté';
          return $return;
        }


      }
      */

      $user = $this->User->find('first', [
        'conditions' => ['id'=>$data['user_id']]
      ]);

      //test
      //$return['message']['error'] = 'Test : '.json_encode($user);
      //return $return;
      //fin test

      if(isset($user)){
        
        $chauffeurExist = $this->CompanyTaxi->find('first', [
          'conditions'=>['user_id'=>$data['user_id']]
        ]);

        $isToleka = false;
        if($chauffeurExist) if(count($chauffeurExist)>0) $isToleka =true;

        if($isToleka){
          $return['message']['error'] = "Vous êtes déjà enregistré dans Toleka...";
          

        }else{


          $userId = $data['user_id'];

          $imageNames = array_keys($this->chauffeurImageNames);
          $files = $this->saveTolekaImages($imageNames, $userId);
          

          $don = [
            'user_id'=>$data['user_id'],
            'plaque'=> $data['plaque'],
            'model_vehicule'=> $data['model_vehicule'],
            'town_id'=> $data['town_id'],
            
          ];

          $don = array_merge($don, $files);

        

          $this->CompanyTaxi->save($don);
       
  
          $return['files'] = $files;
          $return['success'] = true;
          //$return['files'] = 
          
        }
        
        
        
      }else{
        $return['message']['error'] = "Vous n'avez pas pu être identifié. Veuillez contacter le service client svp...";
      }

      


      if($ajax) {
        return $return;
      }

      return $return;

      //return ['success'=>];
    }catch(Exception $e){
      $return['message']['error'] = $e->getMessage();
      return $return;
    }

  	


  }

  public function ApiAjoutPiecesToleka($Options = array()) {
    try{

      $ajax = false;
      extract($Options);

      $AuthUserId = $this->AuthUserId(true);

      $return = ['success'=>false, 'data'=>[], 'message'=>[]];




      $user = $this->User->find('first', [
        'conditions' => ['id'=>$data['user_id']]
      ]);


      if(isset($user)){
        
        $companyTaxi = $this->CompanyTaxi->find('first', [
          'conditions'=>['user_id'=>$data['user_id']]
        ]);

        $isToleka = false;
        if($companyTaxi) if(count($companyTaxi)>0) $isToleka =true;

        if($isToleka){
          

          $userId = $data['user_id'];

          $imageNames = array_keys($this->chauffeurImageNames);
          $files = $this->saveTolekaImages($imageNames, $userId);
          

          $don = [
            'id' => $companyTaxi['CompanyTaxi']['id'],  
          ];

          $don = array_merge($don, $files);

        

          $this->CompanyTaxi->save($don);
       
  
          $return = array_merge($return, [
            'success' => true,
            //'files' => $files,
            //'don' => $don,
            //"comcompanyTaxip" => $companyTaxi,
          ]);


        }else{
          $return['message']['error'] = "Vous n'avez pas de compte enregistré dans Toleka...";
          

        }
        
        
        
      }else{
        $return['message']['error'] = "Vous n'avez pas pu être identifié. Veuillez contacter le service client svp...";
      }

      


      if($ajax) {
        return $return;
      }

      return $return;

      //return ['success'=>];
    }catch(Exception $e){
      $return['message']['error'] = $e->getMessage();
      return $return;
    }

  	


  }

  public function ApiUpdateGpsToleka($Options = []){
    try{
    
      $ajax = false;
      extract($Options);
      
      $return = ['success'=>false, 'data'=>[], 'message'=>[]];
      
      $companyT = $this->CompanyTaxi->find('first', [
        'conditions'=>[
          'user_id'=>$data['id']
        ]
      ]);

      $companyId = $companyT['CompanyTaxi']['id'];

      if(isset($companyId)){
        $saveGps = $this->CompanyTaxi->save([
          'id' => $companyId,
          'latitude' => $data['latitude'],
          'longitude' => $data['longitude'],
          'located_at' => (new DateTime())->format('Y-m-d H:i:s'),//()->i18nFormat('yyyy-MM-dd HH:mm:ss'), //DboSource::expression('NOW()'),//(new DboSource())->expression('NOW()');
        ]);
  
        $return['message'] = [(new DateTime())->format('Y-m-d H:i:s')];
        
        if(isset($saveGps)) if(count($saveGps)>0) $return['success'] = true;
  
      }

      //$return['companyId'] = $companyId;

      
      

      return $return;
    

    }catch(Exception $e){
      $return['message']['error'] = $e->getMessage();
      return $return;
    }


  }

  protected function tarifByLocation($latitude, $longitude){
    $townName = $this->getCityFromLatLng($latitude, $longitude);

    $dbTown = $this->Town->find('first', [
      'conditions'=>[
        'name'=>$townName,
      ]
    ]);

    $townId = $dbTown["Town"]['id'];

    $dbTarif = $this->CompanyTaxiCity->find('first', [
      'conditions'=>[
        'town_id'=>$townId,
      ],
      'contain'=>[
        'Currency'
      ]
    ]);

    $tarif = [
      'currency_id' => $dbTarif['Currency']['id'],
      'currency_iso' => $dbTarif['Currency']['iso'],
      'currency_symbol' => $dbTarif['Currency']['symbol'],

      'prix_min' => $dbTarif['CompanyTaxiCity']['prix_min'],
      'prix_max' => $dbTarif['CompanyTaxiCity']['prix_max'],
      'prix_confort' => $dbTarif['CompanyTaxiCity']['prix_confort'],
    ];

    return $tarif;
  }

  public function ApiTolekaTarif($Options = []){
    try{
    
      $ajax = false;
      extract($Options);
      
      $return = ['success'=>false, 'data'=>[], 'message'=>[]];
      
      $tarif = $this->tarifByLocation($data['latitude'], $data['longitude']);
      
      $return['data'] = $tarif;
      
      $return['success'] = true;

      return $return;
    

    }catch(Exception $e){
      $return['message']['error'] = $e->getMessage();
      return $return;
    }


  }

  public function ApiTolekaCommande($Options = []){
    try{
    
      $ajax = false;
      extract($Options);
      
      $return = ['success'=>false, 'data'=>[], 'message'=>[]];
      $mapPos = json_decode($data['mapPos'], true);
      

      
      $dataToSave = [
        'user_id'=> $data['user_id'],
        'distance' => $data['distance'],
        'categorie_prix'=>$data['categorie_prix'],

        'route_lat_lng' => [$mapPos['route_lat_lng']], 
        'source_latitude' => $mapPos['source']['latitude'],
        'source_longitude' => $mapPos['source']['longitude'],

        'destination_latitude' => $mapPos['destination']['latitude'],
        'destination_longitude' => $mapPos['destination']['longitude'],

      ];

      $dbTolekaOrder = $this->TolekaOrder->save($dataToSave);
      $tolekaOrderId = $dbTolekaOrder['TolekaOrder']['id'];



      $tarif = $this->tarifByLocation($mapPos['source']['latitude'], $mapPos['source']['longitude']);
      
      $amount = $data['categorie_prix']=='ECO'?$tarif['prix_min']:$tarif['prix_confort'];
      $distance = round($data['distance'], 2);
      
      $dataToChauffeur = [
        'client_user_id'=>$data['user_id'], 
        'toleka_order_id'=>$tolekaOrderId,

        'distance' => $distance,
        'categorie_prix'=>$data['categorie_prix'],

        'source_latitude' => $mapPos['source']['latitude'],
        'source_longitude' => $mapPos['source']['longitude'],

        'destination_latitude' => $mapPos['destination']['latitude'],
        'destination_longitude' => $mapPos['destination']['longitude'],

        'amount' => $amount,
        'currency_symbol' => $tarif['currency_symbol']
      ];///array_merge($dataToSave, ['toleka_order_id'=>$tolekaOrderId]);

      //file_put_contents('test.txt', json_encode($dataToChauffeur)); //test

      ///$fromSocket = $this->postRequest('/call_toleka_chauffeur', json_encode($dataToChauffeur));
      $fromSocket = $this->postRequest('/add_toleka_course_to_socket', $dataToChauffeur);

      //$return['data'] = $dataToChauffeur['toleka_order_id'];//gettype($mapPos);
      
      $return['success'] = true;
      //$return ['fromSocket']=$fromSocket;
      $return['data'] = $dataToChauffeur;

      return $return;
    

    }catch(Exception $e){
      $return['message']['error'] = $e->getMessage();
      return $return;
    }


  }

  public function ApiGetListeChauffeurBySocket($Options){
    try{
    
      $ajax = false;
      extract($Options);
      
      $return = ['success'=>false, 'data'=>[], 'message'=>[]];
      
      $liste = [];

      $dbCompanyTaxi = $this->CompanyTaxi->find('all');

      $chauffeurs = [];
      foreach($dbCompanyTaxi as $key => $value){
        array_push($chauffeurs, $value['CompanyTaxi']['user_id']);
      }

      foreach($data as $client_user_id => $don){
        ///$don['chauffeurs'] = $chauffeurs;
        $liste[$client_user_id] = $chauffeurs;
      }
      

      $return['success'] = true;
      $return['data'] = $liste;
      return $return;
    

    }catch(Exception $e){
      $return['message']['error'] = $e->getMessage();
      return $return;
    }
  }

  public function ApiAcceptCourseParChauffeurBySocket($Options){
    try{
    
      $ajax = false;
      extract($Options);
      
      $return = ['success'=>false, 'data'=>[], 'message'=>[]];
      
      $tolekaOrderId = $data['toleka_order_id'];
      $chauffeurUserId = $data['chauffeur_user_id'];
      //$userId = $data['user_id'];
      
      $dataToSave = [
        'id'=> $tolekaOrderId,
        'chauffeur_user_id'=>$chauffeurUserId,
        'chauffeur_trouve'=>true,

      ];

      $saved = $this->TolekaOrder->save($dataToSave);

      if($saved){
        $return['success'] = true;
      }
      
      return $return;
    

    }catch(Exception $e){
      $return['message']['error'] = $e->getMessage();
      return $return;
    }
  }

  public function ApiAucunChauffeurTrouveBySocket($Options){
    try{
    
      $ajax = false;
      extract($Options);
      
      $return = ['success'=>false, 'data'=>[], 'message'=>[]];
      
      $tolekaOrderId = $data['toleka_order_id'];
      //$chauffeurUserId = $data['chauffeur_user_id'];
      //$userId = $data['user_id'];
      
      $dataToSave = [
        'id'=> $tolekaOrderId,
        'actif'=> false,
        
      ];

      $dbTolekaOrder = $this->TolekaOrder->find('first', [
        'conditions'=>[
          'id'=>$tolekaOrderId,
        ]
      ]);

      if(isset($dbTolekaOrder)){
        $arrayTolekaOrder = $dbTolekaOrder['TolekaOrder'];
        if(isset($arrayTolekaOrder)){
          if(!$arrayTolekaOrder['chauffeur_trouve']){
            $saved = $this->TolekaOrder->save($dataToSave);

            if($saved){
              $return['success'] = true;
            }
          }
        }
      }

      
      
      return $return;
    

    }catch(Exception $e){
      $return['message']['error'] = $e->getMessage();
      return $return;
    }
  }

  public function ApiTolekaCourseMonterAbord($Options){
    try{
    
      $ajax = false;
      extract($Options);
      
      $return = ['success'=>false, 'data'=>[], 'message'=>[]];
      
      $tolekaOrderId = $data['toleka_order_id'];
      $userId = $data['user_id'];

      $dateTime = (new DateTime())->format('Y-m-d H:i:s');
      
      $dataToSave = [
        'id'=> $tolekaOrderId,
        'abord'=>true,
        'abord_date'=> $dateTime,
      ];

      $saved = $this->TolekaOrder->save($dataToSave);

      if(isset($saved)) if(count($saved)>0){
        $return['success'] = true;
        $return['data']['abord_date'] = $dateTime;
      } 
      
      return $return;
    

    }catch(Exception $e){
      $return['message']['error'] = $e->getMessage();
      return $return;
    }
  }

  public function ApiTerminerTolekaCourse($Options){
    try{
    
      $ajax = false;
      extract($Options);
      
      $return = ['success'=>false, 'data'=>[], 'message'=>[]];
      $mapPos = json_decode($data['mapPos'], true);
      
      $tolekaOrderId = $data['toleka_order_id'];
      $userId = $data['user_id'];

      $status = 4;
      $message = "course_termine";
      if($data['stop_appel_chauffeurs']=='true'){
        $status = 3;
        $message = 'stop_appel_chauffeurs';
      }else if($data['arrive_destination']=='true'){
        $status = 2;
        $message = 'arrive_destination';
      }
      
      $dataToSave = [
        'id'=> $tolekaOrderId,
        'status'=>$status,
        'actif'=>false,
        'message'=>$message,

      ];

      $saved = $this->TolekaOrder->save($dataToSave);

      if($saved){
        $return['success'] = true;

        try{
          
          $this->postRequest('/action_terminer_course', [
            'status'=>$status,
            'client_user_id'=>$saved['user_id'], 
            'chauffeur_user_id'=>$saved['chauffeur_user_id'],
            'toleka_order_id'=>$tolekaOrderId,
          ]);
        
        }catch(Exception $e){}
      }
     
      return $return;
    

    }catch(Exception $e){
      $return['message']['error'] = $e->getMessage();
      return $return;
    }
  }






  public function ApiReg($Options = array()) {

  	$ajax = false;
    extract($Options);

    $AuthUserId = $this->AuthUserId(true);

    $return = ['success'=>false, 'data'=>[], 'message'=>[]];

    if ($AuthUserId) {

      if ($ajax) {
        $return['message']['error'] [] = 'Vous êtes deja connecté';
        return $return;
      }


    }


    $fieldList['Info'] = array('id', 'firstname', 'name', 'country_id', 'phone', 'birthday');
    $fieldList['User'] = array('id', 'email', 'password', 'passwd');

    if (!$ajax) {
      //$fieldList['User'][] = 'tos';
    }

    //debug($this->request->data);

    $transaction = $this->User->Info->saveAll($this->request->data, array('fieldList' => $fieldList, 'deep' => false));


    unset($this->request->data['User']['password']);
    unset($this->request->data['User']['passwd']);
      $this->User->validationErrors = $this->User->validationErrors;


    if ($transaction) {

      // On envoie une notification sur chez le destinateur
          $User = $this->User->Info->find('first', array(
        'contain' => array('User'),
            'conditions' => array('Info.id' => $this->User->Info->id)));
      /*
            $gendText1 = 'le bienvenu';
      if($User['Info']['gender'] == 'f'){
        $gendText1 = 'la bienvenue';
      }
      */
      $gendText1 = 'la bienvenue';


      $options = array(
        'config' => 'noreply',
        'replyTo' => Configure::read('Mail.contact.Business.email'),
        'to' => array($User['User']['email'] => $User['Info']['fullname']),
        'subject' => "Bienvenu(e) dans TransNumerica",
        //'template' => "bookinghotel",
        'message' => '<p>Bonjour <b>'.$User['Info']['fullname'].'</b>.</p><p>Nous vous souhaitons '.$gendText1.' dans notre Plateforme</p>',
        'debug' => 1,
      );

      if(Validation::email($User['User']['email'])){
        try {
          Op::sendMail($options);
        } catch (Exception $e) {
          
        }
      }

      $this->autologin(false);
 
      if ($ajax) {

        $return =  array('success' => true, 'data' => $this->AuthUser($User['User']['id']));
        return $return;

      }else{
        $this->redirect(array('controller' => 'users', 'action' => 'index'));

      }

    }else{

      if ($ajax) {

        $error = $this->User->Info->validationErrors;
        $error = Hash::merge($error, $error['User']);
        unset($error['User']);

        $return =  array('success' => false, 'message' => $error);
        

      }else{

      }


    }

    if($ajax) {
      return $return;
    }


  }

  protected function ApiAskDelete($Options= []){
    //$return = ['success' => true, 'message' => []]
    $ajax = false;
    extract($Options);

    if($ajax){
      $userId = $Options['user_id'];
    }else{
      $userId = $this->AuthUserId();
    }

    $User = $this->User->find('first', [
      'conditions'=>['id'=>$userId]
    ]);
    $email = $User['User']['email'];

    $this->User->save([
      'id'=>$userId, 
      'ask_delete'=>(new DateTime())->format('Y-m-d H:i:s') ]);

    //$email = "kevkikimage@gmail.com";

    $this->sendEmail(
      $email,
      "Suppression",
      "La suppression définitive de vos données sera effective dans 90 jours. Sans action de votre part durant ce délai, le compte sera supprimé définitivement. Si vous changez d\'avis, il vous suffira de vous reconnecter avant l'échéance pour annuler la procédure.",
      'Cher Client'
    );

    //$this->redirect('/'.$email);
  }

  protected function ApiRegEnter($data){
    $errors = [];

    $reg_data = ['User' => [], 'Info' => []];

    $reg_data['User'] = $data['User'];
    $reg_data['Info'] = $data['Info'];
    
    $email = $reg_data['User']['email'] ?? "";
    $firstname = $reg_data['Info']['firstname'] ?? "";
    $name = $reg_data['Info']['name'] ?? "";
    $fullname = '' . $firstname . ' ' . $name;
    $phone = $this->soignerPhone($reg_data['Info']['phone'] ?? "");
    if(!isset($phone)) $phone = "";

    $password = $reg_data['User']['password'] ?? "";
    $passwd = $reg_data['User']['passwd'] ?? "";

    if (strlen($firstname) == 0) {
      $errors['firstname'] = "Vous devez insérer votre prénom";
    }

    if (strlen($name) == 0) {
      $errors['name'] = "Vous devez insérer votre nom";
    }

    if (strlen($phone) < 8) {
      $errors['phone'] = "Le numéro inséré est très court";
    } else {
      
      $phoneExist = $this->User->Info->find('first', [
        'conditions' => ['Info.phone' => $phone]
      ]) != null; //$this->User->find('')

      if ($phoneExist)
        $errors['phone'] = "Le numéro inséré existe déjà";
      
    }

    if (isset($email)) {
      if (strlen($email) == 0)
        $email = null;
    }


    if (isset($email)) {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Cette adresse email est invalide";
      } else {
        $emailExist = $this->User->find('first', [
          'conditions' => ['email' => $email]
        ]) != null;

        if ($emailExist)
          $errors['email'] = "Cette adresse email existe déjà";
      }
    }

    if (strlen($password) < 5) {
      $errors['password'] = "Le mot de passe doit être au moins constitué de 5 caractères";
    } elseif (strcmp($password, $passwd) !=0) {
      $errors['password'] = "Le mot de passe de vérification est différent de celui inséré";
    }

    $this->Session->write('reg_data', $reg_data);

    if(!$reg_data['User']['tos']) $errors['tos'] = "Veuillez accepter les conditions d'utilisation";
    
    $birthday = $reg_data['Info']['birthday'];
    if(isset($birthday)){
      if(empty($birthday));
      elseif(!$this->validateDate($birthday)) 
        $errors['birthday'] = "Date de naissance invalide";
    }else{
      //$errors['birthday'] = "Veuillez insérer une date de naissance";
    }

    if (count($errors) == 0) {
      
      $codesecret_phone = $this->SECRET_CODE_STD;
      if(!in_array($phone, $this->PHONE_NUMBERS_STD)){
        $codesecret_phone = random_int(100000, 999999);
        $this->sendVerifCodeSMS($phone, $codesecret_phone);
      }
        
      $this->Session->write('reg_codesecret_phone', $codesecret_phone);
      
      if (isset($email)) {
        $codesecret_email = random_int(100000, 999999);

        $this->sendEmail(
          $email,
          "code enregistrement email",
          '<p>Bonjour <b>' . $fullname . '</b>.</p><p>Veuillez copier ce code secret dans votre page d\'inscription : "' . $codesecret_email . '"</p>',
          $fullname
        );

        $this->Session->write('reg_codesecret_email', $codesecret_email);

      }

      $this->Session->write('reg.codesecret_time', time());	

      if(!$data['ajax']) $this->redirect(['action' => 'reg_code']);//'/users/reg_code');

    } else {


      //$this->set('errors', $errors);
      //$this->set('reg_data', $this->Session->read('reg_data'));
      
      //regDataNull($this);//$this->Session->write('reg_data', null);
    }

    return $errors;
  }

  protected function ApiRegCode($form, $data){
    $codesecret_phone = $this->Session->read('reg_codesecret_phone');
		$codesecret_email = $this->Session->read('reg_codesecret_email');

    $codepage_sms = $form['code']['sms']??"";
    $codepage_email = $form['code']['mail']??"";

    $messageAlert = null;

    $return = ['success'=>false, 'data'=>[], 'message'=>[]];

    $timeSecret0 = $this->Session->read('reg.codesecret_time');
    $time = time();

    $codeSended = false;
    $codes_verif = false;

    //$code_secret_bypass = '109022';
    $codes_secret_bypass = ['109022', '123123'];

    if(
      in_array($codepage_sms, $codes_secret_bypass, true) || 
      in_array($codepage_email, $codes_secret_bypass, true) ||
      true// A effacer
      /*  
      (strcmp($codepage_sms, $code_secret_bypass)==0) || 
      (strcmp($codepage_email, $code_secret_bypass)==0)
      */
      
      ){
      $codeSended = true;
      $codes_verif = true;
    }else if(isset($timeSecret0)){
      //remettre 300000 à 300
      if($time - $timeSecret0 < 300000) $codeSended = true;
    }


    if($codeSended){

      if(isset($codesecret_email)){
        if(strcmp($codesecret_email, $codepage_email)==0){
          $codes_verif = true;
        }
      }

      if(strcmp($codesecret_phone, $codepage_sms)==0){
        $codes_verif = true;
      }
      

      if($codes_verif){
        $this->request->data = $data;
        $return =  $this->ApiReg($data);
      }else{
        $messageAlert = 'Les codes insérés ne correspondent pas à ceux qui vous ont été envoyés';
        /*.implode(', ', [
          $codesecret_email, $codepage_email, $codesecret_phone, $codepage_sms
        ]);
        */
        
      }
    }else{
      $messageAlert = "Les codes n'ont pas étés envoyés ou ont déjà expirés";

      if($data['ajax']){
        
      }else{
        $this->redirect([
          'controller'=>'users',
          'action'=>'reg',
          '?'=>['recup_params'=>true]
        ]);
      }
      
    }

    if($messageAlert!=null) $return = ['success'=>false, 'data'=>[], 'message'=>['Erreur'=>$messageAlert]];
    return $return;
    //return $messageAlert;
  }

  protected function reg_data_toUser(){
    $reg_data = $this->Session->read('reg_data');

    return [
      'Info'=>[
        "firstname" =>$reg_data['Info']["firstname"],
        "name"=>$reg_data['Info']["name"],
        "phone"=>$reg_data['Info']["phone"],
        "birthday"=>$reg_data['Info']["birthday"],
      ],
      'User'=>[
        "email"=>$reg_data['User']["email"],
      ]
    ];
    /*   
    array_intersect_key(
      $reg_data,
      array_flip(["firstname", "name", ]);
    );
    */
  }


  protected function autologin($redirect = true){
    $return =  array('success' => true, 'message' => '', 'type' => 'register');

    if (!empty($this->User->id)) {
      $User = $this->User->find('first', array('conditions' => array('id' => $this->User->id)));
      
      $this->request->data = Hash::merge($this->request->data, $User);    

      $this->Auth->authenticate['Form']['fields']['username'] = 'id';  
      $this->Auth->authenticate['Form']['fields']['password'] = 'password';  
      $this->Auth->authenticate['Form']['passwordHasher'] = 'No';  
    }

    $this->Auth->constructAuthenticate();
    if($this->Auth->login()){

      $user = null;

      if (!$user) {
        $AuthUserId = $this->AuthUserId(true);
        if ($AuthUserId) {
          $user['id'] = $AuthUserId;
        }
      }

      
      // On enregistre la date du dernier login
      $this->_addDetail('Connexion', $user);

      // On paramettre les option de redirection automatique selon le cas
      if (!empty($this->request->data)) {
        if (empty($this->request->data['remember'])) {
          $this->RememberMe->destroyCookie();
        } else {
          $this->RememberMe->setCookie();
        }
      }

      if ($redirect) {
        if (!empty($this->request->data['return_to'])) {
              $this->redirect($this->request->data['return_to']);
        }else{
              $this->redirect($this->Auth->loginRedirect);
        }
      }

      return true;

    }else{
      if (!empty($this->User->id)) {

        if(!$this->User->schema('active')['default']) {
          debug('Metre la valeur par default du champ "active" à 1');
          exit();
        }

        $this->User->delete($this->User->id);
      }
      //$this->Flash->set("Problemen interne est survenu lors de l'inscription",['params' => 'echec']);
      return false;
    }

  }

  public function ApiPassforgot($Options = array()){
    $ajax = false; extract($Options);

		$email = $this->Session->read('passforgot.email');
		$timeSecret0 = $this->Session->read('passforgot.codesecret_time');
		$time = time();

		$codeSended = false;
		if(isset($timeSecret0)){
			if($time - $timeSecret0 < 300) $codeSended = true;
		}


    return ['success'=>true, 'codeSended'=> $codeSended, 'email'=>$email];
  }

  public function ApiPassforgotcancel($Options = array()){
    $ajax=false; extract($Options);

    $this->Session->delete('passforgot.codesecret');
		$this->Session->delete('passforgot.codesecret_time');
		$this->Session->delete('passforgot.email');

		if($ajax){
      return ['success'=>true];
    }else $this->redirect(['action' => 'passforgot']);
  }

  protected function soignerPhone($numStr){
    $num = null;
      try{
        
        $first = substr($numStr, 0, 1);
        $second = substr($numStr, 1, 1);
        if(strcmp($first, '0') ==0 && strcmp($second, '0') !=0){
          $numStr = '243'.substr($numStr, 1);
        }

        $num = '+'.intval($numStr);
        

      }catch(Exception $e){
        $num = null;
      }

      return $num;
  }

  public function ApiPassforgotsend($Options = array()){
    $ajax=false;
    extract($Options);
   
    $User = $this->User->find('first', [
      'conditions' => ['email' => $email,],
      'contain'=>['Info']
    ]);

			
		$messageAlert = '';
			
			
    if($User !=null){
      $code = random_int(100000,999999);
      $numStr = $User['Info']['phone'];
      $num = $this->soignerPhone($numStr);

      $email = $User['User']['email'];

      $numHide = null;
      if($num != null){
        $this->sendVerifCodeSMS($num, $code);

        $prefix = substr($num, 0, 6);
        $repeat = str_repeat("*", strlen($num)-8);
        $suffix = substr($num, -2);
        $numHide = ''.$prefix.$repeat.$suffix;
      }
      $emailHide = null;
      if($email != null){
        try{
          $arobase = strpos($email, '@');

          $prefix = substr($email, 0, 2);
          $repeat = str_repeat("*", $arobase-2);
          $suffix = substr($email, $arobase);

          
          $emailHide = ''.$prefix.$repeat.$suffix;
        }catch(Exception $e){}
        
      }

      
      $options = array(
        'config' => 'noreply',
        'replyTo' => Configure::read('Mail.contact.Business.email'),
        'to' => array($email => $User['Info']['fullname']),
        'subject' => "TransNumerica Code Secret",
        //'template' => "bookinghotel",
        'message' => '<p>Bonjour <b>'.$User['Info']['fullname'].'</b>.</p><p>Veuillez copier ce code secret dans votre page "Mot de passe oublié" : "'.$code.'"</p>',
        'debug' => 1,
      );

      if(Validation::email($email)){
        try {
          Op::sendMail($options);
          $this->Session->write('passforgot.codesecret', $code);
          $this->Session->write('passforgot.email', $email);
          $this->Session->write('passforgot.codesecret_time', time());

          
          //$this->redirect(array('action' => 'passforgot'));
        } catch (Exception $e) {
          $messageAlert = "Erreur lors de l'envoi du code secret";
          $emailHide = null;
        }
      }else{
        $messageAlert = "Adresse email invalide";
        $emailHide = null;
      }

      $destHide = [];
      if($numHide !=null){array_push($destHide, $numHide);}
      if($emailHide !=null){array_push($destHide, $emailHide);}
      
      if(count($destHide)>0){
        $messageAlert = "code secret envoyé à ".implode(", ", $destHide);// "code secret envoyé à ";
      }else if(strlen($messageAlert)==0){
        $messageAlert = "Erreur lors de l'envoi du code secret";
      }

      
      
    }else{
      $messageAlert = "Cette adresse email n'est pas enregistré";
    }

    if($ajax){
      return ['success'=>true, 'messageAlert' => $messageAlert];
    }else{
      $this->redirect(['action'=>'passforgot', '?'=>[
        'messageAlert' => $messageAlert
      ]]);
    }
    
  }

  public function ApiPassforgotsave($Options = array()){
    $ajax = false;
    extract($Options);

    $email = $this->Session->read('passforgot.email');
		$timeSecret0 = $this->Session->read('passforgot.codesecret_time');
		$time = time();

		$codeSended = false;
		if(isset($timeSecret0)){
			if($time - $timeSecret0 < 300) $codeSended = true;
		}

		
		//$email = $form['User']['email'];
		$User = $this->User->find('first', [
			'conditions' => ['email' => $email,],
			'contain'=>['Info']
		]);

		$messageAlert = '';


		if($codeSended){
			$codeSecret = $this->Session->read('passforgot.codesecret');
			if(strcmp($codeSecret, $codesecret)==0){
				$password = $password;
				$passwordConf = $passwordConf;
				
				if(strcmp($password,$passwordConf)==0){
					if(strlen($password)>=4){
						try{
							$UserSave = ['id'=>$User['User']['id'], 'password'=>$password, 'passwd'=>$password];
							//$UserSave = ['email'=>"kevkikimage2@gmail.com"];
							//$this->User->id = $User['User']['id'];
							$this->User->save($UserSave);
							if($ajax){
                $messageAlert = "Le mot de passe a été modifié avec succès !";
              }else{
                $this->redirect(['action'=>'login']);
              } 
						}catch(Exception $e){
							
							$messageAlert = 'Une erreur a survenue lors de la tentative de modification du mot de passe : '.$e->getMessage();
						}
					
					}else{
						$messageAlert = 'Le mot de passe doit posséder au moins 4 caractères';
					}
					
				}else{
					$messageAlert = "Le Mot de passe confirmé est différent de celui inséré";
				}
				
				
			}else{
				$messageAlert = 'Le code secret que vous avez inséré ne correpond pas à celui que nous vous avons envoyé';
			}
		}else{
			$messageAlert = 'Le code secret a déjà expiré, veuillez en demander un autre';
			
		}

    if($ajax){
      return ['success'=>true, 'messageAlert' => $messageAlert];
    }else{
      $this->redirect(['action'=>'passforgot', '?'=>[
        'messageAlert' => $messageAlert
      ]]);
    }

		
  }

  protected function sendEmail($email, $sujet, $message = "", $fullname= ""){
 
    try{
      /*
        $email = $emailInfos['email'];
        $sujet = $emailInfos['sujet']??"";
        $message = $emailInfos['message']??"";
        $fullname = $emailInfos['fullname']??"";
      */
      
      $options = array(
        'config' => 'noreply',
        'replyTo' => Configure::read('Mail.contact.Business.email'),
        'to' => array($email => $fullname),
        'subject' => $sujet,
        //'template' => "bookinghotel",
        'message' => $message,
        'debug' => 1,
      );

      if(Validation::email($email)){
        try {
          Op::sendMail($options);
          
          //$this->redirect(array('action' => 'passforgot'));
        } catch (Exception $e) {
          return "Erreur lors de l'envoi du mail";
          
        }
      }else{
        return "Adresse email invalide";
        
      }
      
    }catch(Exception $e){
      
      return 'Erreur d\'envoie de l\'email : '.$e->getMessage();
    }
			
		
  }


  public function ApiLogin($Options = array()) {

    $ajax = false; extract($Options);

    $AuthUserId = $this->AuthUserId(true);

    if ($AuthUserId) {

      if ($ajax) {
        $return['message']['error'] [] = 'Vous êtes deja connecté';
        return $return;
      }

    }


    // Avant tout on supprime les comptes dont les emails n'ont pas été valider
    if (empty($removeExpiredRegistrations)) {
      //$this->User->Info->_removeExpiredRegistrations();
    }

    $username = $this->request->data['User']['username'];//mb_strtolower(Op::noaccent($this->request->data['User']['username']));
    // On verifie la methode de connection, soit par email ou soit par nom d'utilisateur
    $field = 'phone';
    if (filter_var($username, FILTER_VALIDATE_EMAIL) AND Validation::email($username)) {
      $field = 'email';

      $User = $this->User->find('first', [
        'conditions' => ['email' => $username],
        
      ]);
      
    }else{//if (preg_match('/^[0-9a-zA-Z]{5,6}$/i', $username)) {

      $field = 'id';
      
      $this->set('erreurK', $this->soignerPhone($username));
      
      $phone =  /*$this->soignerPhone($username)??*/$username;
      $byPhone = $this->User->Info->find('first', [
        'contain' => array('User'),
        'conditions' => ['Info.phone'=> $phone]
      ]);
      $username = $byPhone['User']['id'];

      $User = $this->User->find('first', [
        'conditions' => ['id'=>$username],
      ]);
      
    }       

    if (!empty($field)) {
      $this->Auth->authenticate['Form']['fields']['username'] = $field;  
      $this->request->data['User'][$field] = $username;
      $this->request->data['User']['password'] = $this->request->data['User']['password'];
    }           

    $this->Auth->constructAuthenticate();


    /// On verifie si ask_user est null ou a deja atteind son delais;
    /// Afin de decider de l'ouverture du compte ou pas et 
    /// d'annuler la suppression du compte ou pas

    ///A effacer
    //$this->User->validationErrors['password'][] = var_dump($username);
    //$error = $this->User->validationErrors;
    $open_auto_login = true;

    if(!empty($User)){
      $ask_delete = $User['User']['ask_delete'];

      if(!empty($ask_delete)){
        $dateDemande = new DateTime($ask_delete);
        $dateActuelle = new DateTime();

        // 3. On calcule la différence entre les deux dates
        $interval = $dateDemande->diff($dateActuelle);
        
        // 4. On vérifie si le nombre total de jours est supérieur ou égal à 90
        if ($interval->days >= 90) {
          //return array('success' => false, 'message' => ['interval'=> $interval->days]);
          
          $open_auto_login = false;

          $this->User->validationErrors['password'][] = "Ce compte n'existe plus";

          if ($ajax) {
    
            $error = $this->User->validationErrors;
            $return =  array('success' => false, 'message' => $error);
            return $return;
    
          }else{
    
          }
          
        }else{
          $this->User->save([
            'id'=>$User['User']['id'], 
            'ask_delete'=> null ]);

        }
      }
    }
    
    $auto_login = false;
    if($open_auto_login){
      $auto_login = $this->autologin((@$ajax ? false : true));
    }
    

    if ($auto_login) {

      if ($ajax) {
        $dataRes = $this->AuthUser();

        $chauffeurExist = $this->CompanyTaxi->find('first', [
          'conditions'=>['user_id'=>$dataRes['User']['id']]
        ]);

        $isToleka = false;
        if($chauffeurExist) if(count($chauffeurExist)>0) $isToleka =true;

        $dataRes['is_toleka'] = $isToleka;
        
        unset($dataRes["User"]["password"]);

        $return =  array('success' => true, 'data' => $dataRes);
        return $return;
      }

    }else{

      unset($this->request->data['User']['password']);
      $this->User->validationErrors['password'][] = "Identifiant ou/et Mot de passe invalide";

      if ($ajax) {

        $error = $this->User->validationErrors;
        $return =  array('success' => false, 'message' => $error);
        return $return;

      }else{

      }

    }

  }

  public function ApiDelete($Options = array()) {

    $ajax = false; extract($Options);

   
    // Avant tout on supprime les comptes dont les emails n'ont pas été valider
    if (empty($removeExpiredRegistrations)) {
      //$this->User->Info->_removeExpiredRegistrations();
    }

    $username = mb_strtolower(Op::noaccent($this->request->data['User']['username']));
    // On verifie la methode de connection, soit par email ou soit par nom d'utilisateur
    $field = 'username';
    if (filter_var($username, FILTER_VALIDATE_EMAIL) AND Validation::email($username)) {
      $field = 'email';
    }elseif (preg_match('/^[0-9a-zA-Z]{5,6}$/i', $username)) {
      $field = 'username';
    }       

    if (!empty($field)) {
      $this->Auth->authenticate['Form']['fields']['username'] = $field;  
      $this->request->data['User'][$field] = $username;
      $this->request->data['User']['password'] = $this->request->data['User']['password'];
    }           

    //$this->Auth->constructAuthenticate();

    if ($this->autologin((@$ajax ? false : true))) {

      if ($ajax) {
        $return =  array('success' => true, 'data' => $this->AuthUser());
        return $return;
      }

    }else{

      unset($this->request->data['User']['password']);
      $this->User->validationErrors['password'][] = "Identifiant ou/et Mot de passe invalide";

      if ($ajax) {

        $error = $this->User->validationErrors;
        $return =  array('success' => false, 'message' => $error);
        return $return;

      }else{

      }

    }

  }


  protected function _addDetail($note, $user = array()) {
    // On enregistre la derrière date sur une modification

    $AuthUserId = $this->AuthUserId(true);

    if (!$user AND $user = $AuthUserId) {}
        
        // On extrait la clé primaire
        $userprimaryKey = $this->User->primaryKey;
        // On determine la clé etrangere du model d'origine
        if (!$this->User->isForeignKey($userForeignKey = $userprimaryKey) AND stripos($userprimaryKey, '_') === false) $userForeignKey = strtolower(Inflector::singularize($this->User->alias). '_'.$userprimaryKey);

        // On sauvegarde le detail
      $this->User->Detail->save(array(
            'note' => $note,
            'datime' => date('Y-m-d H:i:s'),
            $userForeignKey => $user[$userprimaryKey]
        ));
  }


  public function ApiLogout($Options = array()) {

    $ajax = false; extract($Options);

    $this->RememberMe->destroyCookie();
    $this->Auth->logout();
    $this->Session->delete('color_scheme');
    setcookie("color_scheme", null, null, '/');

    if ($ajax) {

      $return =  array('success' => false, 'data' => array());
      return $return;

    }else{
      $this->redirect('/');
    }

  }



  public function TicketQrGen($Sales, $ajax = false) {

    $first = false;
    if (!empty($Sales['Ticket'])) {
      $first = true;
      $newSales = $Sales;

      $Sales = array();
      $Sales[] = $newSales;
    }

    foreach ($Sales as $keyS => $Sale) {

      foreach ($Sale['Ticket'] as $keyT => $Ticket) {

        $base2 = WWW_ROOT.'qr/base'.$Ticket['invoice'].'.png'; // Le Code QR
        $final = WWW_ROOT.'qr/final'.$Ticket['invoice'].'.png'; // Le Code QR

        //QRcode::png($Sale['Schedule']['Destination']['Car']['Company']['name'].chr(10).$Sale['Schedule']['Destination']['Car']['name'].chr(10).$Ticket['place'].chr(10).Router::url(array('controller' => 'invoice', 'action' => 'preview', $Ticket['id']), true), $base2, 'L', 14, 1);
        //QRcode::png(Router::url(array('controller' => 'invoice', 'action' => 'preview', $Ticket['id']), true), $base2, 'L', 14, 1);
        QRcode::png(Router::url(array('controller' => 'invoice', 'action' => 'preview', $Sale['Sale']['id']), true), $base2, 'L', 14, 1);

        $image_2 = imagecreatefrompng($base2);

        imagepng($image_2, $final);

        $finalImgLink = '/qr/final'.$Ticket['invoice'].'.png';
        if ($ajax) {
          $Sales[$keyS]['Ticket'][$keyT]['qr'] = Router::url($finalImgLink, $ajax);
        }else{
          $Sales[$keyS]['Ticket'][$keyT]['qr'] = $finalImgLink;
        }


      }

    }

    if (!empty($first)) {
      $Sales = $Sales[0];
    }

    return $Sales;

  }



  public function ApiBusTicketQR($Options, $ajax = false) {

    // Extraction sécurisée des options
    $sale_id = Hash::get($Options, 'sale_id');

    if (!$sale_id) {
        if ($ajax) {
            //debug('sale_id ou ticket_id non trouvé');
        }
        return false;
    }

    $Sale = $this->Sale->find('first', array(
        'conditions' => array('id' => $sale_id),
        'contain' => array(
            'Currency', 'From', 'To', 'Ticket', 'User.Info', 'Schedule', 
            'Schedule.Destination' => array('From', 'To'), 
            'Schedule.Destination.Car.Company', 'Rate.From', 'Rate.Currency', 'Rate.To'
        ),
    ));

    // Sécurité : Si aucun enregistrement trouvé, on arrête
    if (empty($Sale)) {
        return false;
    }

    // Récupération sécurisée du status
    $status = (int) Hash::get($Sale, 'Sale.status', 0);

    if ($status == 2) {
        $Sale = $this->TicketQrGen($Sale, $ajax);
    } else {
        // Extraction des informations nécessaires avant de reconstruire le tableau
        // pour éviter les accès à des clés inexistantes.
        $SaleMin = [
            'status'  => Hash::get($Sale, 'Sale.status'),
            'message' => Hash::get($Sale, 'Sale.message'),
            'Info'    => Hash::get($Sale, 'User.Info', []) // Extraction directe depuis le $Sale original
        ];

        // Reconstruction conforme à votre logique initiale
        $Sale = ['Sale' => $SaleMin];
    }

    return $Sale;
}


  private $twilio_sid = "ACe6d2b5ef2c1ac5532c5f5d0f02ad9faf";
  private $twilio_token = "edacbc202ceb3f4fc8db45971dbba5bc";
  private $twilio_sid_short_code = "VA5cbb9bee673090a4cfc584fcc4d0914c";


  protected function sendSMS($number, $txt){
		try{
		    require_once "../Plugin/TwilioPhpMain/src/Twilio/autoload.php";// "/path/to/vendor/autoload.php";


			//use Twilio\Rest\Client;


			// Find your Account SID and Auth Token at twilio.com/console

			// and set the environment variables. See http://twil.io/secure

			$sid = $this->twilio_sid;//"AC60c29514c687676ddbf4369d4da332c0";//getenv("TWILIO_ACCOUNT_SID");

			$token = $this->twilio_token;//"a93c1845b90bd15be390214f900df4d6";//getenv("TWILIO_AUTH_TOKEN");

			$twilio = new Twilio\Rest\Client($sid, $token); //Client($sid, $token);


			$message = $twilio->messages->create(

				$number,//"+243855088833", // To

				[

					"body" =>

						$txt,

					"from" => "+18788701125",//"+15017122661",

				]

			);


			$data = $message->body;

			return $data;

	    } catch (Exception $e) {
			return $e->getMessage();
		}
	}

  protected function sendVerifCodeSMS($number, $code){
		try{
      // Remplacez votre ancienne ligne par ceci :
        require_once(APP . 'Plugin' . DS . 'TwilioPhpMain' . DS . 'src' . DS . 'Twilio' . DS . 'autoload.php');
		    //require_once "../Plugin/TwilioPhpMain/src/Twilio/autoload.php";// "/path/to/vendor/autoload.php";

			$sid = $this->twilio_sid;//"ACe6d2b5ef2c1ac5532c5f5d0f02ad9faf";//getenv("TWILIO_ACCOUNT_SID");

			$token = $this->twilio_token;//"a93c1845b90bd15be390214f900df4d6";//getenv("TWILIO_AUTH_TOKEN");

			$twilio = new Twilio\Rest\Client($sid, $token); //Client($sid, $token);


      $verification = $twilio->verify->v2
        ->services($this->twilio_sid_short_code)//"VA5cbb9bee673090a4cfc584fcc4d0914c")
        ->verifications->create(
            $number, // To
            "sms",
            ["customCode" => $code] // Channel
        );
/*
			$verification = $twilio->verify->v2
          ->services("VA5cbb9bee673090a4cfc584fcc4d0914c")
          ->verifications->create(
              $number, // To
              "sms", // Channel
              //["customCode" => $code]
          );
*/

      //$data = $verification->sid->status;
      $data = $verification->sid->status;

			return $data;

	    } catch (Exception $e) {
			return $e->getMessage();
		}
	}


















}