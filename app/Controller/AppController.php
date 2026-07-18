<?php
App::uses('CakeTime', 'Utility');
App::uses('ApiController', 'Controller');
App::uses('Op', 'My.Lib');
//App::uses('GeoLoc', 'My.Lib');
//App::uses('GeoLoc', 'My.Utility');
App::uses('GeoLoc', 'GeoLoc.Lib');


CakeTime::$niceShortFormat = '%d %B, %H:%M';

class AppController extends ApiController {
  
  public $uses = array('Company', 'Town', 'Search', 'Sale', 'Operator', 'Transaction', 'Country', 'Currency', 'Sendmethod', 'Getmethod', 'Texte', 'User', 'Post', 'School', 'Relevance', 'Publicity','CompanyDestination', 'CompanyPlane', 'CompanyPlaneDestination', 'CompanyHotel', 'CompanyHotelRoom', 'CompanyFormer', 'CompanyFormerDestination', 'CompanyTaxi', 'CompanyTaxiCity', 'TolekaOrder', 'UnipesaPaymentC2b', 'UnipesaCallbackC2b', 'MarchandAdmin', 'ParamGain', 'UserTest', 'Requete'); 
  public $helpers = array('AssetCompress.AssetCompress','Html','My.Op','Media.Media','Media.Image', 'Js' => array('Jquery'),'VideoEmbed.Video');
  //public $layout = 'default';

  public function canUploadMedias($model, $id){
    return true; // Le reste des média n'est gérable que par l'administrateur
    return $this->Auth->User('role') == 'admin'; // Le reste des média n'est gérable que par l'administrateur
  } 

  public $components = array('Security' => array('blackHoleCallback' => 'blackhole'), 'Session', 'Auth', 'Cookie', 'Paginator','RequestHandler','My.Op','Flash', 'RememberMe', 'Cron'/*, 'DebugKit.Toolbar'*/);


  public function implementedEvents() {
    return array_merge(parent::implementedEvents(), array(
      'Auth.afterIdentify' => array('callable' => 'afterIdentify', 'passParams' => true),
    ));
  }


  public $isMobile = false;
  public $layouts = array('desktop', 'mobile');

  public function beforeFilter() {
    parent::beforeFilter();

    // On récupère la connexion par défaut
    $db = ConnectionManager::getDataSource('default');

    
    $db->execute("SET SESSION sql_mode = ''");


    if (@$this->request->data['auth_id']) {
      $this->auth_id = $this->request->data['auth_id'];
    }


    $this->layout = 'default';

    // On met les www. pour rendre beau nos sites
    $server_name = $_SERVER['HTTP_HOST'];
    if (!$this->request->is('json') AND substr($server_name, 0, 4) != 'www.' AND Configure::read('App.localhost') === false) {
      $this->redirect('http://www.' . $server_name . $this->here);
    }


    if (!$this->Components->enabled('Security')){
      if (!isset($this->request->data['_Token'])) unset($this->request->data); // Important pour eviter les trous noir indessirable
      $this->Components->enable('Security', array('blackHoleCallback' => 'blackhole'));
    }

    if(Configure::read('App.localhost') === false) {
      //$this->Security->requireSecure();
      //$this->Security->requireSecure = array('*');
    }
    // On configure l'action lié au trou noir
    $this->Security->blackHoleCallback = 'blackhole';
    $this->Security->csrfUseOnce = false;
    $this->Security->csrfCheck = false; //Expiration des formulaires
    $this->Security->csrfExpires = '+4 hour';

    if (Configure::read('debug')) {
      //$this->Components->disable('Security');
    }
    $this->Security->validatePost = false;

    
    $this->response->header('Access-Control-Allow-Origin','*');
    $this->response->header('Access-Control-Allow-Methods','*');
    $this->response->header('Access-Control-Allow-Headers','X-Requested-With');
    $this->response->header('Access-Control-Allow-Headers','Content-Type, x-xsrf-token');
    $this->response->header('Access-Control-Max-Age','172800');




    $this->Cookie->type('rijndael');



    // When using "rijndael" encryption the "key" value must be longer than 32 bytes.
    $this->Cookie->key = 'qSI242342432qs*&sXOw!adre@34SasdadAWQEAv!@*(XSL#$%)asGb$@11~_+!@#HKis~#^'; // When using rijndael encryption this value must be longer than 32 bytes.

    //$this->setTableOneConnection('localhost', 'root','','shopcongo');
    //$this->setTableTwoConnection('localhost', 'root','','shopc');
    //$this->matchTables('articles', 'articles');

    //$this->autoLayout = false;
    //$this->autoRender = false;
    // On detecte le navigateur navigateur en cours
    $this->request->navigateur = Op::navigateur();

    //On configure le paginate
    $this->Paginator->settings['paramType'] = 'querystring';

    // On configure l'action lié au trou noir
    //$this->Security->csrfCheck = false;
    //$this->Security->blackHoleCallback = 'blackhole';
    //$this->Security->csrfExpires = '+30 min';







    // Configuré le paramettre d'authentification de connexion
    AuthComponent::$sessionKey = 'Auth.User';

  //App::uses('NoPasswordHasher', 'Controller/Component/Auth');

    $this->Auth->authenticate = array(
      'Form' => array(
        'fields' => array(
          'username' => 'email',
          'password' => 'password'
        ),
        'userModel' => 'User',
        'scope' => array('User.active' => 1),
        //'passwordHasher' => 'No',
        'contain' => array()
      )
    );




    $user = $this->Auth->user();

    $this->Auth->deny();

    if (isset(Router::getParams()['plugin']) AND in_array(Router::getParams()['plugin'], array('media'))) {
      $this->Auth->allow();
    }



    $this->Auth->flash = array('element' => 'default','key' => 'auth','params' => 'auth');
    if ($this->Auth->user()) {
      $this->Auth->authError = "Cette page n’est malheureusement pas disponible.";
    }else {
      $this->Auth->authError = __d('tra',"Veuillez vous connecter pour poursuivre.");
    }

    $this->Auth->loginRedirect = '/';
    //$this->Auth->loginRedirect = array('plugin' => false,'controller' => 'users', 'action' => 'index');
    $this->Auth->logoutRedirect = array('plugin' => false,'controller' => 'users', 'action' => 'login');
    $this->Auth->loginAction = array('plugin' => false,'controller' => 'users', 'action' => 'login');
    $this->Auth->flash = array('element' => 'notif', 'params' => 'warning', 'key' => false);

    //debug($ArticleMarques); 

    // On configure la pagination de page
    if(isset($this->request->query['limit']) AND !in_array($this->request->query['limit'], Configure::read('pageLimit.valeur'))) { 
      unset($this->request->query['limit']); // On empeche le limitation non autorisé de requette sur les articles à afficher
    }
    if(isset($this->request->query['sort'])) { // On empeche le triage non autorfisé des articles
      unset($this->request->query['sort']);
    }

    // Pour les cas où vos formulaires ont étés envoyer avec Une ID ="0" (Très important)
    if (!empty($this->request->data)) {      
      foreach ($this->request->data as $L => $R) {
        if (isset($R['id']) AND $R['id'] === '0'){
                exit("Vous n'etes pas autorisés à faire cette action<br>Veuillez respecter les politiques du site");
        }
      }
    }
//$this->Rayons->recover('tree');
//$this->Rayons->recover('parent');

//sdebug($this->Rayons->verify());




    if ($this->request->is('post')) {

      if (@$this->request->data['form'] == 'searchbus') {

        $md5 = $this->ApiMd5Search();

        $this->redirect(array('controller' => 'search', 'action' => 'index', 'md5' => $md5));
        //debug($save);

      }

    }










/// A REMETRE
CakeSession::write('Localisation', 'CG');


    if (!CakeSession::read('Localisation')) {

      $Localisation = Op::geoip();
      $Localisation2 = GeoLoc::get();
      //$Localisation = Op::geoip('102.223.129.18'); Kinshasa
      //$Localisation = Op::geoip('102.141.63.124'); // Brazaville
      //debug($Localisation);

      $flagList = array('CD', 'CG');

      if (in_array(@$Localisation['country_code'], $flagList)) {
        $finalLocalisation = $Localisation['country_code'];
      }elseif (in_array(@$Localisation2['country_code'], $flagList)) {
        $finalLocalisation = $Localisation2['country_code'];
      }else{
        $finalLocalisation = 'CG';
      }

      CakeSession::write('Localisation', $finalLocalisation);

      //debug(CakeSession::read('Localisation'));

    }



    







    // On Mignifie la page quand le debug est à 0
    if (Configure::read('debug') < 1 AND !in_array('MinifyHtml.MinifyHtml', $this->helpers)) $this->helpers[] = 'MinifyHtml.MinifyHtml';




      $this->set('nowtime', strtotime(date('Y-m-d H:i:s')));



    if(!empty($this->request->query['search'])){



      //substr_replace(string, replacement, start)

      $homeRoute = Router::parse(substr(Router::url('/'), strlen($this->base)));
      //debug($homeRoute);
      if(!($this->request->controller == $homeRoute['controller'] AND $this->request->action == $homeRoute['action'])){

        //$this->redirect(array('controller' => $homeRoute['controller'], 'action' => $homeRoute['action'], '?' => $this->request->query));
      }
      $this->search();

      return true;
    }



  }





  public function blackhole($type) {

    if ($type == 'auth'){
      $this->Flash->notif(__d('translate', "Erreur, veuillez remplir les champs correctement"), ['params' => 'echec']);
    }elseif ($type == 'csrf') {
      $this->Flash->notif(__d('translate',  "Le formulaire a expiré, Veuillez réessayer"), ['params' => 'echec']);
    }elseif ($type == 'secure') {
      $server_name = env('SERVER_NAME');
      // On enleve les www. pour eviter les blems des fausses redirections
      if (substr($server_name, 0, 4) == 'www.') $server_name = substr_replace($server_name, '', 0, 4);
      
      // ANCIEN CODE QUI CASSE TOUT SUR L'IP :
      // $this->redirect('https://www.' . $server_name . $this->here);
      
      // NOUVEAU CODE : Si c'est une IP, on ne force pas le HTTPS/WWW pour le test
      if (filter_var($server_name, FILTER_VALIDATE_IP)) {
          // On reste en HTTP normal sur l'IP
          return; 
      } else {
          // Comportement normal si c'est un vrai nom de domaine plus tard
          $this->redirect('https://www.' . $server_name . $this->here);
      }
    }else{
      $this->Flash->notif(sprintf(__d('translate',  "Erreur, veuillez remplir les champs correctement (%s)"), $type), ['params' => 'echec']);
    }

    if ($this->request->is('ajax')) {
      exit($this->redirect(Router::url(array('plugin' => $this->params->plugin, 'controller' => $this->params->controller, 'action' => $this->params->action),true)));
    }
    exit($this->redirect($this->referer()));
  }


  

  public function beforeRender() {
    parent::beforeRender(); // Toujours appeler le parent

    // --- INITIALISATION DES VARIABLES PAR DÉFAUT (Pour éviter les erreurs dans default.ctp) ---
    $this->set([
        'merchantServices' => [], // Valeur par défaut vide
        'rubrique' => null,       // Valeur par défaut nulle
        'color_scheme' => 'light' // Initialisation de base
    ]);
    // ------------------------------------------------------------------------------------------

    $backEnd = array(/*'admin', */'brownie');
    if (!isset(Router::getParams()['plugin']) OR !in_array(Router::getParams()['plugin'], $backEnd)) {
                                  


      $userAgent = Op::userAgent();

      $themeShow = true;
      if($userAgent['os']['name'] == 'Android' AND $userAgent['os']['version'] >= 10) {
        $themeShow = false;
      }

      if ($themeShow) {

        $color_scheme = $this->Session->read('color_scheme');
        if ($color_scheme) {
          $_COOKIE['color_scheme'] = $color_scheme;
          setcookie("color_scheme", $color_scheme, null, '/');

        }else{
          $color_scheme = isset($_COOKIE["color_scheme"]) ? $_COOKIE["color_scheme"] : false;
          if ($color_scheme === false) {
              $color_scheme = 'light';  // fallback
          }
        }

      }else{
          $color_scheme = null;
          $_COOKIE['color_scheme'] = $color_scheme;
          setcookie("color_scheme", $color_scheme, null, '/');
      }



      $this->set('color_scheme', $color_scheme);




      
      $merchantServices = ($this->Auth->loggedIn()) ? $this->getMerchantServices() : array();
      ///$this->set('espaceMarchand', $espaceMarchand);
      $this->set('merchantServices', $merchantServices);

      //$this->set('rubrique', 'getOut');


      $Towns = $this->Town->find('all', array(
        'contain' => array('Media'),
      ));

      $Towns = Hash::combine($Towns, '{n}.Town.id', '{n}');      
      $this->set('Towns', $Towns);




      $Countries = $this->Country->find('all', array(
        //'conditions' => array('Country.code' => CakeSession::read('Localisation')),
      ));
      $Countries = Hash::combine($Countries, '{n}.Country.code', '{n}');
      $this->set('Countries', $Countries);
      //debug($Countries);


      $Towns = $this->Town->find('all', array(
        'contain' => array('Media', 'Country'),
        'conditions' => array('Country.code' => CakeSession::read('Localisation')),
      ));

      foreach ($Towns as $key => $Town) {
        $STowns[$Towns[0]['Country']['name']][$Town['Town']['id']] = $Town['Town']['name'];
      }
      $this->set('STowns', $STowns);


      $Towns = $this->Town->find('all', array(
        'contain' => array('Media', 'Country'),
        'conditions' => array('Country.code !=' => CakeSession::read('Localisation')),
      ));

      foreach ($Towns as $key => $Town) {
        $STowns['Autre'][$Town['Town']['id']] = $Town['Town']['name'];
      }
      $this->set('STowns', $STowns);


      $KTowns = $this->ApiAlltowns()['data'];
      $this->set('KTowns', $KTowns);


      $Textes = $this->Texte->find('all', array(
        'contain' => array('Media'),
      ));
      
      $Textes = Hash::combine($Textes, '{n}.Texte.key', '{n}');
      $this->set('Textes', $Textes);
      //debug($Textes);


      $AuthUser = $this->AuthUser();

      // Si $AuthUser existe, on définit $User, sinon un tableau vide
      $User = !empty($AuthUser) ? $AuthUser : array();
      $this->set('User', $User);


      $OnlineUsers = $SuggestionUsers = array();


      if (!empty($AuthUser['Info']['id'])) {


        $UserValidationErrors = $this->User->validationErrors;
        $this->User->save(array('id' => $AuthUser['User']['id'], 'last_seen' => date('Y-m-d H:i:s')), array(
          'atomic' => false,
          'fiedList' => array('id', 'last_seen'),
          'validate' => false,
          'callbacks' => false
        ));
        $this->User->validationErrors = $UserValidationErrors;


        $User = $AuthUser;




        $today = date('Y-m-d H:i:s');
        $todaystrotime = date('Y-m-d H:i:s', strtotime('- 2 month'));




        // Invalidate Profil

        $InvalidateUrl = false;
        if ($this->request->controller == 'profil' AND $this->request->action == 'edit') {
          $InvalidateUrl = true;
        }



        $invalidateProfil = @$this->viewVars['invalidateProfil'];
        if ($invalidateProfil) {

          CakeSession::delete('Message.flash');

          $this->set('return_to', Router::url('/', true));

          $redirect = false;
          if ($this->request->action != 'edit' AND !$this->request->is('ajax') AND !$this->request->is('json')) {
            $redirect = true;
          }

          if(in_array($this->request->controller, array('users', 'musers', 'm'))) {
            $redirect = false;
          }

          if($redirect) {
            $this->redirect(array('controller' => 'profil', 'action' => 'edit', 'slug' => mb_strtolower(Inflector::slug($AuthUser['Account']['fullname'])), 'id' => $AuthUser['Info']['id']));
          }
        }

        //debug($User); exit();
        $this->set('User', $User);

        // Tous les Notifications
        $Notifications = $this->notifications();

        $this->set('Notifications', $Notifications);

        //debug($Notifications);


        }

      // On compress les pages
      if (!$this->response->outputCompressed() AND !Configure::read('debug')) $this->response->compress();

      if ($this->params->paging AND !$this->request->is('ajax') AND $this->request->navigateur['nom'] == 'Internet Explorer') $this->disableCache();
      // On fais passé les info de session
      $this->set('authUser', $this->Auth->user());
      $this->set('AuthUser', $AuthUser);

      // On fais passé le nom du model
      if ($this->request->plugin != 'brownie') $this->set('model', $this->name);

      // On definie les paramettres des requetes ajax et on fixe les bugs des caches 
      if ($this->request->is('ajax') AND $this->request->plugin != 'media' AND $this->layout == 'default') {
        //if ($this->request->navigateur['nom'] != 'Mozilla Firefox') $this->disableCache(); // Mozilla n'a pas besoin qu'on lui vide son cache
        $this->layout = 'ajax';// Mettre dans le before filter sinon le plugin media ne marchera pas bien
      }



    }
  }






  protected function maxicash($options) {

    $reference = $price = false;

    extract($options);

        $user = $this->Auth->user();

    $url = "https://api.maxicashapp.com/payentry";
    $url = "https://api-testbed.maxicashapp.com/payentry"; //Sandbox
    $urlData = array(
      'PayType' => "MaxiCash",
      'Amount' => $price*100,
      'Currency' => "USD", //USD, ZAR, maxiRand or maxiDollar
      'Telephone' => "+243",
      'Email' => $user['email'],
      'MerchantID' => "86b7ff2fb54d4495885725f2b46744d6",
      'MerchantPassword' => "ffb8e073bcbf490ab09dc470f3d95129",
      'Language' => "fr", //fr or en
      'Reference' => CakeText::truncate($reference, 57, array('exact' => false)),
      'accepturl' => Router::url(array('controller' => 'down', 'action' => 'maxstatut', $data), true),
      'cancelurl' => Router::url(explode('?', $this->referer())[0], true),
      'declineurl' => Router::url(array('controller' => 'down', 'action' => 'maxstatut', $data), true),
      //'notifyurl' => Router::url(array('controller' => 'down', 'action' => 'maxstatut', $type, $id), true),

    );

    $buyUrl = $url.'?data='.json_encode($urlData);



    return str_replace("\/", "/", $buyUrl);

  }






  public function authId() {

    $authId = null;

    $authUser = $this->Auth->user();
    if (!empty($this->request->data['auth_id'])) {
      $authId = $this->request->data['auth_id'];
    }elseif ($authUser) {
      $authId = $authUser['id'];
    }

    if ($authId) {
      return $authId;
    }else{
      return false;
    }

  }







  public function afterFilter() {


    // Rassembler les cookies et mettre la valeur final dans l'header lastcookie
    $HeaderCookies =  str_replace(' ', '', $this->request->header('Cookie'));
    $Cookies = $lastcookie = array();

    if (Validation::notBlank($HeaderCookies)) {

      foreach (explode(';', $HeaderCookies) as $key => $HeaderCookie) {

        list($header, $value) = explode('=', $HeaderCookie);

        $Cookies [$header] = $value;

      }
    }

    $Cookies[Configure::read('Session.cookie')] = CakeSession::id();

    $lastcookie = array();
    foreach ($Cookies as $header => $value) {
      $lastcookie[] = $header.'='.$value;
    }

    $lastcookie = implode('; ', $lastcookie);


    $this->response->header('Cookie', $lastcookie);
    $this->response->header('User-Agent', $this->request->header('User-Agent'));

    // Fin

  }


  public function afterIdentify() {

    $AuthUserId = $this->AuthUserId(true);

    if ($AuthUserId) {
      $User = $this->User->find('first', array('conditions' => array('User.id' => $AuthUserId)
      ));

      $userAgent = Op::userAgent();

      /*

      $themeShow = true;
      if($userAgent['os']['name'] == 'Android' AND $userAgent['os']['version'] >= 10) {
          $themeShow = false;
      }

      if ($themeShow) {

        if($User['User']['dark'] === true){
          $this->Session->write('color_scheme', 'dark');
        }elseif($User['User']['dark'] === false){
          $this->Session->write('color_scheme', 'light');
        }
        
      }

      */

    }

  }


  protected function AuthUser($AuthUserId = null, $ajax = false) {

    if (!$AuthUserId) {
      $AuthUserId = $this->AuthUserId(true);
    }

    if (!$AuthUserId) {

      if ($ajax) {
        return false;
      }

      $AuthUser = array();
      $AuthUser['Info']['id'] = null;
      $AuthUser['Info']['admin'] = null;
      $AuthUser['Intro']['fullname'] = $AuthUser['Account']['fullname'] = null;
      $AuthUser['Role']['class'] = null;
      $AuthUser['Info']['profil'] = $AuthUser['Account']['profil'] = '/img/emptyprofil.png';
      return $AuthUser;
    }


    $containAuth = array('User');
    $AuthUser = $this->User->Info->find('first', array('conditions' => array('User.id' => $AuthUserId), 'contain' => $containAuth));
    if (!$AuthUser AND $this->Auth->User()) {
      $this->RememberMe->destroyCookie();
      $this->Auth->logout();
      $this->Session->delete('color_scheme');
      setcookie("color_scheme", null, null, '/');
      $this->redirect('/');
      return $AuthUser;
    }


    $AuthUser = $this->User->Info->find('first', array('conditions' => array('User.id' => $AuthUserId), 'contain' => $containAuth));

    if (!$AuthUser AND $this->Auth->User()) {
      $this->redirect(array('plugin' => false,'controller' => 'users', 'action' => 'logout'));
    }

    return $AuthUser;

  }



  protected function AuthUserId($ajax = true) {
    $AuthUserId = $this->Auth->user('id');

    if (!$AuthUserId AND $ajax) {
        if ($this->request->is('json') AND !empty(file_get_contents('php://input'))) {
            $this->autoRender = false;
            $this->request->data = json_decode(file_get_contents('php://input'), true);
        }

        $AuthUserId = @$this->request->data['auth_id'];
        
        if (!$AuthUserId) {
            $AuthUserId = $this->auth_id;
        }

        // On vérifie d'abord si request->data est un tableau avant de faire quoi que ce soit
        if (!$AuthUserId && is_array($this->request->data)) {
            $keys = array_keys($this->request->data);
            
            // Premier essai (index 0)
            if (!empty($keys[0])) {
                $AuthUserId = @$this->request->data[$keys[0]]['auth_id'];
            }

            // Deuxième essai (index 1) seulement si AuthUserId est toujours vide
            if (!$AuthUserId && !empty($keys[1])) {
                $AuthUserId = @$this->request->data[$keys[1]]['auth_id'];
            }
        }
    }

    return $AuthUserId;
}
  public function getMerchantServices($userId=null){
    
    if($userId == null)  $userId = $this->Auth->user('id');
    if($userId != null){
      $merchantServices = [];

      $conditions = ['User.id'=>$userId];
     

      $isAdmin = $this->MarchandAdmin->find('first', [
        'conditions' => ['user_id' => $userId]
      ]) != null;

      if($isAdmin){
        $conditions = [];
      }

      $aBus = $this->Company->find('first',[
        'conditions'=> $conditions,
        'contain' => array('User'),
      ]);

      if($aBus !=null) array_push($merchantServices,'bus');

      $aPlane = $this->CompanyPlane->find('first',[
        'conditions'=> $conditions,
        'contain' => array('User'),
      ]);

      if($aPlane !=null) array_push($merchantServices,'vol');

      $aHotel = $this->CompanyHotel->find('first',[
        'conditions'=> $conditions,
        'contain' => array('User'),
      ]);

      if($aHotel !=null) array_push($merchantServices,'hotel');

      $aFormer = $this->CompanyFormer->find('first',[
        'conditions'=> $conditions,
        'contain' => array('User'),
      ]);

      if($aFormer !=null) array_push($merchantServices,'train');

      $aTaxi = $this->CompanyTaxi->find('first',[
        'conditions'=> $conditions,
        'contain' => array('User'),
      ]);

      if($aTaxi !=null) array_push($merchantServices,'taxi');
      
      
      
      
      return $merchantServices;

    }else return [];
    

    //return ['bus'];

  }


  public function notifications() {

    $limit = 100;

    $AuthUser = $this->AuthUser();
    $Notifications = array();

    if (!$AuthUser['Info']['created']) {
      $AuthUser['Info']['created'] = $AuthUser['User']['created'];
    }








    $Notifications = Hash::sort($Notifications, '{n}.date', 'DESC');

    $count = '';
    $Notifications2 = array();
    $i = 0;
    foreach ($Notifications as $key => $Notification) {

      if ($i == 15) {
        break;
      }

      if ($Notification['date'] >= $AuthUser['Info']['created']) {
        $Notifications2[$key] = $Notification;

        if ($Notification['date'] > $AuthUser['Info']['notification_lastime']) {
          $Notifications2[$key]['new'] = true;
          $count ++;
        }else{
          $Notifications2[$key]['new'] = false;
        }

      }

      $i ++;
    }
    $Notifications = $Notifications2;


    return array('count' => $count, 'Notifications' => $Notifications);

  }















  
}
