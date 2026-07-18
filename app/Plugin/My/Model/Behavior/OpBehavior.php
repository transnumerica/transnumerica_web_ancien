<?php

App::uses('L10n', 'I18n');

class OpBehavior extends ModelBehavior{

    public $transLabel = '_lang';

    public function IntoInt(Model $Model, $data){
        foreach ($data as $L => $R) {
            unset($data[$L]);
            $data[$L] = $L;
        }
        return $data;
    }

    public function tva(Model $Model, $prix){
        $prix = $prix + ($prix * Configure::read('Achat.taxe')/100);
        return $prix;
    }

    public function textformalise(Model $Model, $text){
        $text = mb_convert_case($text, MB_CASE_TITLE, "UTF-8");
        $search = array(' De ',' Du ',' En ',' Et '," D'",' To ',' Na ',' Bo ',' Ya ',' Ko ',' Pona ',' For ');
        $replace = array(' de ',' du ',' en ',' & '," d'",' to ',' na ',' bo ',' ya ',' ko ',' pona ',' for ');
        $text = str_replace($search, $replace,$text);
        return $text;
    }

    public function beforeSave(Model $Model, $options = array()) {

        parent::beforeSave($Model, $options);
        
        $Model->beforeSaveTranslate(); //Traduction doit s'executer après les sauvegarder //Important

        return true;

    }


    public function afterFind(Model $Model, $results, $primary = false) {

        //Traduction doit s'executer après les sauvegarder
        $results = $Model->afterFindTranslate($results); //traduction // Important

        parent::afterFind($Model, $results, $primary);

        return $results;

    }


    public function beforeSaveTranslate(Model $Model, $uc = true){
        if (!in_array($Model->alias, array('Session'))) {

            $toSource = Op::language();
            $localeFallback = $toSource[1];

            foreach ($Model->data[$Model->alias] as $trans => $value) {

                if ((($this->transLabelLen = strpos($trans, $this->transLabel)) === mb_strlen($trans) - mb_strlen($this->transLabel)) AND is_array($Model->data[$Model->alias][$trans]) AND !$Model->is_serial2($Model->data[$Model->alias][$trans])) {

                    $oTrans = substr($trans, 0, $this->transLabelLen);

                    $Model->data[$Model->alias][$oTrans] = $Model->data[$Model->alias][$trans][$localeFallback];
                    $Model->data[$Model->alias][$trans] = serialize($Model->data[$Model->alias][$trans]);
                }
            }
        }
    }


    public function afterFindTranslate(Model $Model, $results){

        if (!in_array($Model->alias, array('Session'))) {

            $toSource = Op::language();
            $localeFallback = $toSource[1];

            $dLangs = Configure::read('Config.languages');
            foreach ($results as $key => $val) {
                if(isset($val[$Model->alias])){
                    foreach ($val[$Model->alias] as $trans => $value) {

                        //On recherche les champs de traduction afin d'appliquer nos règles mais avant, on verifie les règles pour chaque champs
                        if (!empty($results[$key][$Model->alias][$trans]) AND (($this->transLabelLen = strpos($trans, $this->transLabel)) == mb_strlen($trans) - mb_strlen($this->transLabel)) AND $Model->is_serial2($val[$Model->alias][$trans])) {

                            
                            $oTrans = substr($trans, 0, $this->transLabelLen); // On extrait le field correspondant à l'internationalisation

                            $results[$key][$Model->alias][$trans] = unserialize($val[$Model->alias][$trans]);
                            $results[$key][$Model->alias][$oTrans] = @$results[$key][$Model->alias][$trans][$localeFallback];

                            $backEnd = Configure::read('backend');
                            if (!is_array($backEnd)) $backEnd = array('admin', 'brownie');
                            if (!isset(Router::getParams()['plugin']) OR !in_array(Router::getParams()['plugin'], $backEnd)) {
                                
                                //On verifier les champs si elle est vide afin d'essayer langue
                                if(!isset($results[$key][$Model->alias][$trans][$localeFallback]) OR !Validation::notBlank(Op::HtmlToText($results[$key][$Model->alias][$trans][$localeFallback]))) {

                                    //On extrait la listes des langues pour essayer de voir si les autres langues proposes un contenu afin de traduire ce dernier dans la langue voulu par le client

                                    if (empty($dLangs)) $dLangs =array_merge(array_keys($results[$key][$Model->alias][$trans]));

                                    foreach ($dLangs as $tlln) {
                                        if ($tlln == $localeFallback) {
                                            continue;
                                        }

                                        if(!empty($results[$key][$Model->alias][$trans][$tlln]) AND Validation::notBlank(Op::HtmlToText($results[$key][$Model->alias][$trans][$tlln]))) {

                                            $results[$key][$Model->alias][$oTrans] = Op::translate($results[$key][$Model->alias][$trans][$tlln]);
                                            break;
                                        }
                                    }
                                }

                                //On suprime l'array langue pour alleger les résultants en mode non BackEnd
                                unset($results[$key][$Model->alias][$trans]);
                            }
                        }
                    }
                }
            }
        }

        return $results;
    }

    public function idreorder(Model $Model, $newid = 100000){
                
        if (!$Model->Behaviors->enabled('Tree')) {
            $Niveau = $Model->find('all');

            foreach ($Niveau as $Niv) {

                $newidO = $newid;

                  $Model->updateAll(
                  array($Model->alias.'.id'=>$newid),
                  array($Model->alias.'.id' => $Niv[$Model->alias]['id'])
                );

                $newid = $newid+1;
            }
            
        }else{

            $Niveau = $Model->find('all', array('autocache' => false,'conditions' => array($Model->alias.'.parent_id' => null)));
            $Model->newid = $newid;

            foreach ($Niveau as $Niv) {
                $newidO = $Model->newid;
                $Model->updateAll(
                    array($Model->alias.'.id'=>$Model->newid),
                    array($Model->alias.'.id' => $Niv[$Model->alias]['id'])
                );
                $Model->newid = $Model->newid+1;
                $Model->nivsuivant($Niv[$Model->alias]['id'], $newidO);
            }
        }

        if ($newid >= 100000) {
            $Model->idreorder(1);
            $Model->query("ALTER TABLE `".$Model->useTable."` AUTO_INCREMENT=0");
            if ($Model->Behaviors->enabled('Tree')) debug($Model->verify());
        }
    }

    public function nivsuivant(Model $Model, $parentid, $newidO){

        $Niveau = $Model->find('all', array('cache' => false,'conditions' => array($Model->alias.'.parent_id' => $parentid)));

        foreach ($Niveau as $Niv) {
            $newidObon = $Model->newid;
            $Model->updateAll(
                array($Model->alias.'.parent_id'=>$newidO, $Model->alias.".id"=>$Model->newid),
                array($Model->alias.'.parent_id' => $parentid, $Model->alias.'.id' => $Niv[$Model->alias]['id'])
            );
            $Model->newid = $Model->newid + 1;
            $Model->nivsuivant($Niv[$Model->alias]['id'], $newidObon, $Model->newid);
        }
    }

    // On obtiens le model d'association via la clé etrangère - Plugin Upload
    public function getModelbyForeign(Model $Model, $foreignKey = null){
        foreach ($Model->belongsTo as $modelName => $value) if ($value['foreignKey'] == $foreignKey) $ascModel[] = $modelName;
        if (!isset($ascModel[1])) $ascModel = $ascModel[0];
        return $ascModel;
    }


    // ** Validation  **//

    public function isMajor(Model $Model, $check, $min = 18) {

        $check = array_values($check)[0];
        
        if(is_array($min)) $min = 18;

        $d = date_create_from_format('Y-m-d',$check);
        if ($check != $d->format('Y-m-d')) {
            return false;
        }

        return Validation::comparison($check,'<=', date('Y-m-d', strtotime('- '.$min.' year')));
    }


    public function beforeday(Model $Model, $check, $interval = 18) {

        $check = array_values($check)[0];
        
        if(is_array($interval)) $interval = 18;

        $d = date_create_from_format('Y-m-d',$check);
        if ($check != $d->format('Y-m-d')) {
            return false;
        }

        return Validation::comparison($check,'<=', date('Y-m-d', strtotime('- '.$interval.' day')));
    }

    public function afterday(Model $Model, $check, $interval = 18) {

        $check = array_values($check)[0];
        
        if(is_array($interval)) $interval = 18;

        $d = date_create_from_format('Y-m-d',$check);
        if ($check != $d->format('Y-m-d')) {
            return false;
        }

        return Validation::comparison($check,'>=', date('Y-m-d', strtotime('+ '.$interval.' day')));
    }

    public function realName(Model $Model, $check, $taille = 3) {
        return preg_match('/^[a-zA-Z\- é]{'.$taille.',}$/i', array_values($check)[0]);
    }

    public function userName(Model $Model, $check, $taille = 3) {
        return preg_match('/^[0-9a-z.]{'.$taille.',}$/i', array_values($check)[0]);
    }

    public function alphaNumericPlus(Model $Model, $check, $taille = 3) {
        if(is_array($taille)) $taille = 3;
        return preg_match('/^[0-9a-zA-Z\- éàè.+]{'.$taille.',}$/i', array_values($check)[0]);
    }


    public function isUndisposable(Model $Model, $check, $proceed = false) {
        $email = array_shift($check);
        $blacklists = array('emailgo','mbx.cc','spamgourmet','deadaddress','keepmymail','12minutemail','10x9','trashinbox','hmamail','spamkill','yxzx','yopmail','dontsendmespam','spamavert','mailinator','no-spam','nobugmail','losemymail','nabuma','nobuma','bugmenever','ignoremail','10minutemail','pookmail','humaility','incognitomail','mail4trash','spaml','dodgit','filzmail','spaml','eyepaste','pjjkp','odnorazovoe','wwwnew.eu','example','bofthew','lhsdv','prtnx','despam','lawlita','oneoffmail','spamgourmet','mytrashmail','2prong','temporaryinbox','jetable','tempinbox','guerrillamail','dontreg','bugmenot','wh4f','spamhole','tempomail','spammotel','spambox','tempemail','mailscrap','maileater','spam','fakedemail','spam','fakemail','antireg','mailforspam','asdasd','slopsbox','tilien','trashmail','otherinbox','antireg','mailinator2','sogetthis','mailin8r','mailinator','spamherelots','baxomale','thisisnotmyrealemail','spambog','bsnow','trash-mail','jetable','jetable','mailexpire','garrifulio','mailexpire','sofort-mail','uggsrock','nurfuerspam','binkmail','mailcatch','guerrillamailblock','cust','meltmail','wegwerfemail','giantmail','zippymail');
        $domains = explode('.', explode('@', $email)[1]);

        foreach ($domains as $domain) {
            if (in_array($domain, $blacklists)) {
                return false;                
            }
        }

        $url = "http://fakeinator.info/check/json/$email";
        $response = @json_decode(file_get_contents($url));
        if (isset($response) AND $response->is_fake) {
            return false;
        }
        return true;
    }


    public function isphone(Model $Model, $check) {

        if(is_array($check)) {
            $check = array_values($check)[0];            
        }

        // On verifie que les numéro comrpis entres parenthese sont bien ouvert et fermer
        $Pcount = preg_match_all('!\(([^\)]+)\)!', $check, $parentheses);
        if ($Pcount !== substr_count($check,')') OR $Pcount !== substr_count($check,'(')) return false;

        // On verifie que les numéro comrpis entre parenthese ne sont exclusivement que des numero
        foreach ($parentheses[1] as $parenthese) if (!preg_match('/^[0-9]{0,}$/i', $parenthese)) return false;

        // On rend le numero en format numerique
        $check = str_replace(array(' ','-','(',')'), array('','','',''), $check);

        // On normalise les indicatifs des pays
        $truephone = $check;
        if (stripos($check, '00') === 0) $truephone = substr_replace($check, '+', 0, 2);

        if (stripos($check, '+') === 0) $check = substr_replace($truephone, '', 0, 1);

        // On verifie que le format du numéro de telephone est correcte
        if (!preg_match('/^[0-9]{8,15}$/i', $check)) return false;

        return true;
    }

    
    public function internationalPhone(Model $Model, $check, $indfield) {
        if (is_array($indfield)) $indfield = 'indicatif';

        if (!empty($Model->data[$Model->alias][$indfield])) $indicatif = $Model->data[$Model->alias][$indfield];
        else return false;
        $indicatif = explode(',', $indicatif);
        if (isset($indicatif) AND preg_match('/^[0-9- +]{2,5}$/i', $indicatif[1])) {
            $Model->Pays = ClassRegistry::init(array('class' =>'MondePays', 'alias' => 'Pays'));
            // On verifie si le pays et l'indicatif pointe vers une entrée
            $Pays = $Model->Pays->find('first', array('contain' => array('Region'), 'conditions' => array('nom' => $indicatif[0], $indfield => $indicatif[1])));
            if (!$Pays) {$Model->invalidate($indfield, "Veuillez selectionner un indicatif valide!"); return true; /* On retourne une erreur si le code n'estpas repertorier*/ }
        }else{
            return false; // Au cas où l'indicatif n'est pas correcte au format, pays indicatif, on retourne une erreur
        }

        $check = array_values($check)[0];

        // On verifie que les numéro comrpis entre parenthese ne sont exclusivement que des numero et que les entres parenthese sont bien ouvert et fermer
        $Pcount = preg_match_all('!\(([^\)]+)\)!', $check, $parentheses);
        if ($Pcount !== substr_count($check,')') OR $Pcount !== substr_count($check,'(')) return false;
        foreach ($parentheses[1] as $parenthese) if (!preg_match('/^[0-9]{0,}$/i', $parenthese)) return false;

        // On rend le numero en format numerique
        $check = str_replace(array(' ','-','(',')'), array('','','',''), $check);

        // Au cas où l'utlisateur reprend l'indicatif du pays, on corrige l'erreur
        if (stripos($check, '00') === 0) $check = substr_replace($check, '+', 0, 2);
        $check = str_replace($indicatif[1], '', $check);

        // On verifie que le format du numéro de telephone est correcte
        if (!preg_match('/^[0-9]{9,10}$/i', $check)) return false;

        // Au cas où 0 commence le numéro, on la supprime
        if (stripos($check, '0') === 0) $check = substr_replace($check, '', 0, 1);

        if (!empty($Pays['Region'])) {
            // On extrait le code regionale du numéro officiel
            $regionlimit = strlen($check) - $Pays['Pays']['nTel'];
            $nRegion = substr($check, 0, $regionlimit);

            // On verifie le code regionale si elle est accepter par le pays
            if (in_array($nRegion, Hash::extract($Pays['Region'], '{n}.region'))) {
                $check = substr($check, -($Pays['Pays']['nTel']));
            }else{
                return false; // Sinon on retourne une erreur
            }
        }

        // Au cas où la taille du numéro correspond à la taille du numéro du pays, on valide
        if (strlen($check) == $Pays['Pays']['nTel']) return true;
            else return false;
    }

    public function isUploadedFile(Model $Model, $params) {
        $val = array_shift($params);
        if ((isset($val['error']) && $val['error'] == 0) ||
            (!empty( $val['tmp_name']) && $val['tmp_name'] != 'none')
        ) {
            return is_uploaded_file($val['tmp_name']);
        }
        return false;
    }


    // Forcer à ce qu'on definisse des dates d'expiration à des articles importantes
    public function nonexpire(Model $Model, $check){

        if (array_values($check)[0] AND empty($Model->data[$Model->alias]['expiration'])) {
            $Model->invalidate('expiration', "Vous devez indiquez une date d'expiration");    
            return false;
        }
        return true;
    }

    public function congoPhone(Model $Model, $check, $indfield) {
        if (is_array($indfield)) $indfield = 'indicatif';

        if (!empty($Model->data[$Model->alias][$indfield])) $indicatif = $Model->data[$Model->alias][$indfield];
        else $indicatif = '+243';

        if (!isset($indicatif) OR !preg_match('/^[0-9- +]{2,5}$/i', $indicatif)) {
            return false; // Au cas où l'indicatif n'est pas correcte au format, pays indicatif, on retourne une erreur
        }

        $check = array_values($check)[0];

        // On verifie que les numéro comrpis entre parenthese ne sont exclusivement que des numero et que les entres parenthese sont bien ouvert et fermer
        $Pcount = preg_match_all('!\(([^\)]+)\)!', $check, $parentheses);
        if ($Pcount !== substr_count($check,')') OR $Pcount !== substr_count($check,'(')) return false;
        foreach ($parentheses[1] as $parenthese) if (!preg_match('/^[0-9]{0,}$/i', $parenthese)) return false;

        // On rend le numero en format numerique
        $check = str_replace(array(' ','-','(',')'), array('','','',''), $check);

        // Au cas où l'utlisateur reprend l'indicatif du pays, on corrige l'erreur
        if (stripos($check, '00') === 0) $check = substr_replace($check, '+', 0, 2);
        $check = str_replace($indicatif, '', $check);

        // On verifie que le format du numéro de telephone est correcte
        if (!preg_match('/^[0-9]{9,10}$/i', $check)) return false;

        // Au cas où 0 commence le numéro, on la supprime
        if (stripos($check, '0') === 0) $check = substr_replace($check, '', 0, 1);

        $Pays['Region'] = array('80','81','82','84','85','89','90', '91','97','98','99');
        $Pays['Pays']['nTel'] = 7;

        if (!empty($Pays['Region'])) {
            // On extrait le code regionale du numéro officiel
            $regionlimit = strlen($check) - $Pays['Pays']['nTel'];
            $nRegion = substr($check, 0, $regionlimit);

            // On verifie le code regionale si elle est accepter par le pays
            if (in_array($nRegion, $Pays['Region'])) {
                $check = substr($check, -($Pays['Pays']['nTel']));
            }else{
                return false; // Sinon on retourne une erreur
            }
        }

        // Au cas où la taille du numéro correspond à la taille du numéro du pays, on valide
        if (strlen($check) == $Pays['Pays']['nTel']) return true;
            else return false;
    }



    // ** Fin Validation  **//


    public function fastJoint(Model $Model, $JointModel, $options =  array()) {

        $Model_alias = $Model->alias;
        $Model_table = $Model->useTable;
        $Model_primaryKey= $Model->primaryKey;

        $JointModel_table = $Model->{$JointModel}->useTable;
        $JointModel_alias = $JointModel;



        // On liste les associations liées au model
        $result = $Model->getAssociated($JointModel);
        //debug($result);
        if (in_array($result['association'], array('belongsTo'))) {

            if (!empty($options['foreignKey'])) {
                $Model_foreignKey = $options['foreignKey'];
            }else{
                $Model_foreignKey = $Model->{$result['association']}[$JointModel]['foreignKey'];
            }

            $conditions = array("`".$Model_alias.".".$Model_foreignKey."` = `".$JointModel_alias.".".$Model->{$JointModel}->primaryKey."`");

        }elseif (in_array($result['association'], array('hasMany', 'hasOne'))) {



            if (!empty($options['foreignKey'])) {
                $JointModel_foreignKey = $options['foreignKey'];
            }else{
                $JointModel_foreignKey = $Model->{$result['association']}[$JointModel]['foreignKey'];
            }

            if (!empty($JointModel_foreignKey)){
                $conditions = array("`".$Model_alias.".".$Model_primaryKey."` = `".$JointModel_alias.".".$JointModel_foreignKey."`");
            }else{


                //debug($JointModel_foreignKey);
                //debug($result['conditions']);

                $db = ConnectionManager::getDataSource('default');
                //$AuthFriendQuery = $db->buildAssociationQuery($this->Friend, $result);

                $genConditions = $db->conditions($result['conditions'], false, false);

                $genConditions = str_replace('{$__cakeID__$}', "`".$Model_alias.".".$Model_primaryKey."`", $genConditions);

                //debug($genConditions);
                //exit();

                $conditions = array($genConditions);
            }

        }

        if (in_array($result['association'], array('belongsTo', 'hasMany', 'hasOne'))) {


        $joint = array(
        'table' => $JointModel_table, 'alias' => $JointModel_alias,
        'type' => (@$options['type'] ? $options['type'] : 'LEFT'),
        'conditions' => $conditions,
        );

        return $joint;
        }



        if (in_array($result['association'], array('hasAndBelongsToMany'))) {

            $tableJoncture = $Model->{$result['with']};

            if (!empty($options['foreignKey'])) {
                $jonctureModel_foreignKey = $options['foreignKey'];
            }else{
                $jonctureModel_foreignKey = $Model->hasAndBelongsToMany[$JointModel]['foreignKey'];
            }


            if (!empty($options['associationForeignKey'])) {
                $jonctureAssociation_foreignKey = $options['associationForeignKey'];
            }else{
                $jonctureAssociation_foreignKey = $Model->hasAndBelongsToMany[$JointModel]['associationForeignKey'];
            }


            



        $conditions = array("`".$Model_alias.".".$Model_primaryKey."` = `".$tableJoncture->alias.".".$jonctureModel_foreignKey."`");


        $joint[] = (array(
            'table' => $Model->{$result['with']}->useTable, 'alias' => $result['with'],
            'type' => (@$options['type'] ? $options['type'] : 'LEFT'),
            'conditions' => $conditions,
        ));


        $conditions = array("`".$Model->$JointModel->alias.".".$Model->$JointModel->primaryKey."` = `".$tableJoncture->alias.".".$jonctureAssociation_foreignKey."`");

        $joint[] = (array(
            'table' => $JointModel_table, 'alias' => $JointModel_alias,
            'type' => (@$options['type'] ? $options['type'] : 'LEFT'),
            'conditions' => $conditions,
        ));


        return $joint;

        }





    }


    // ** Fin Fast Joint  **//





 public function modelExists($modelClass, $checkLoaded=true){
    $modelClass = !is_array($modelClass)?$modelClass:implode('.', $modelClass);//implode if is array
    list($plugin, $modelClass) = pluginSplit($modelClass, true);
    $plugin=rtrim($plugin,'.');
    $object = 'model';
    if($plugin){
      if($checkLoaded){
        if(!CakePlugin::loaded($plugin)){
          return false;
        }
      }
      $object = $plugin.'.'.$object;
      $libPaths = App::path("Lib/Plugin/$plugin");
    } else {
      $libPaths = App::path('Lib');
    }
    $list = App::objects($object, null, false);

    foreach($libPaths as $path){
      $libModels = App::objects('lib.'.$object, $path.'Model'.DS, false );
      if(is_array($libModels)){
        $list = Hash::merge($list, $libModels);
      }
    }
    if(in_array($modelClass, $list)){
      return true;
    }
    return false;
  }


    public function HtmlToText(Model $Model, $line = null) {
        $line = strip_tags($line);
        $line = html_entity_decode($line);
        //$line = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $line);
        //$line = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $line);
        $line = preg_replace('(\n|\r|\t)',' ',$line);
        $line = preg_replace('/\s\s+/', ' ', $line); 
        return $line;
    }

    
}