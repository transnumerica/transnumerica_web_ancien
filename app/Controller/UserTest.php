<?php

class UserTest extends AppModel {

    

    public $primaryKeyArray = array('info_id');



    public function __construct($id = false, $table = null, $ds = null) {

        parent::__construct($id, $table, $ds);
        //$this->virtualFields['fullname'] = '';//$this->AccountInfo->virtualFields['fullname'];
        $this->virtualFields['idents'] = sprintf('CONCAT(%s.email, " - ",  %s.id)', $this->alias, $this->alias);
        
        //$this->virtualFields['fullname'] = sprintf('CONCAT(%s.id, " ", %s.email)', $this->alias, $this->alias);
        //$this->virtualFields['idents'] = sprintf('CONCAT("Lever mon verre : ", %s.fullname)', $this->alias);
        //$this->virtualFields += $this->Info->virtualFields;
    }





    public $brwConfig = array(

        'actions' => array('add' => true,'export' => true),

         'fields' => array(

            'no_add' => array('password', 'passwd'),

            'no_edit' => array('password', 'passwd'),

        ),

    );





    public $belongsTo = array(

        'Info' => array(

            'className' => 'AccountInfo',

            'foreignKey' => 'info_id',

        ),

    );





    public $hasMany = array(

        'Detail' => array(

            'className' => 'UserDetail',

            'foreignKey' => 'user_id',

            'dependent' => true,

        ),

    );

/**/

    public $validate = array(

        


        'firstname' => array(

            'required' => array(
                'on' => 'create',

                'rule' => array('minLength', '2'),

                'required' => true, 'allowEmpty' => false,

                'message' => "Veuillez saisir un nom d'au moins 2 charactères..."

            ),

        ),


        'name' => array(

            'required' => array(
                'on' => 'create',

                'rule' => array('minLength', '3'),

                'required' => true, 'allowEmpty' => false,

                'message' => "Veuillez saisir un nom d'au moins 3 charactères..."

            ),

        ),










        'email' => array(

            'isValid' => array(

                'rule' => array('email'),

                'required' => true, 'allowEmpty' => false,

                'message' => "S'il vous plaît, indiquez une adresse email valide..."

            ),

            'isUndisposable' => array(

                'rule' => 'isUndisposable',

                'message' => "Veuillez saisir une adresse electronique valide..."

            ),

            'isUnique' => array(

                'rule' => array('isUnique', 'email'),

                'message' => 'Cet e-mail est déjà utilisée ...'

            )

        ),

/*

        'old_password' => array(

            'passwordequal' => array(

                'required' => true, 'allowEmpty' => false,

                'rule' => 'checkold_passwords',

                'message' => 'Le mot de passe actuel est incorrecte!'

            ),

            'samepassword' => array(

                'on' => 'update', 'last' => false,

                'rule' => 'checksamepasswords',

                'message' => false

            ),

        ),



        'password' => array(

            'too_short' => array(

                'required' => true, 'allowEmpty' => false,

                'rule' => array('minLength', 4),

                'message' => 'Le mot de passe doit contenir au 4 charactère.'

            ),

            'passwordequal' => array(

                'rule' => 'checkpasswords',

                'message' => 'Les mots de passe ne correspondent pas!'

            ),

        ),



        'passwd' => array(

            'rule' => 'notBlank',

            'required' => true, 'allowEmpty' => false,

            'message' => 'Vous devez confirmer votre mot de passe!'

        ),

        

        'tos' => array(

            'rule' => array('custom','[1]'),

            'message' => "Vous devez accepter les conditions d'utilisation!"

        ),









        'username' => array(



            'isUnique' => array(

                'rule' => array('isUnique', 'username'),

                'message' => "Ce nom d'utisileur est déjà utilisé ..."

            ),

        ),

        */



    );

/**/











    public function beforeValidate($options = array()) {

    parent::beforeValidate($options);







    }





    public function checkold_passwords($check){

        $field = key($check);

        $check = array_values($check)[0];



        if (!AuthComponent::user()) {

            return true;

        }elseif (!empty($this->data[$this->alias]['old_password'])) {

            $authUser = $this->find('first', array('fields' => 'password','callbacks' => false,'conditions' => array($this->primaryKey => $this->id, 'password' => AuthComponent::password($check))));



            if (!$authUser) return false;

        }

        return true;

    }





    public function checksamepasswords($check){

        $field = key($check);

        $check = array_values($check)[0];

        if (!empty($this->data[$this->alias]['password'])) {

            if(strcmp($this->data[$this->alias]['password'],$this->data[$this->alias][$field]) == 0) $this->invalidate('password', "L'ancien et le mot de passe correspondent!");    

        }       

        return true;

    }





    public function checkpasswords(){



        if (!empty($this->data[$this->alias]['passwd'])) {

            if(strcmp($this->data[$this->alias]['password'],$this->data[$this->alias]['passwd']) == 0) return true;

            else return false;    

        }

        return true;

    }







     function beforeSave($options = array()) {

        parent::beforeSave($options);



        if (isset($this->data[$this->alias]['password'])) $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);



        if(!$this->id OR !$this->field('password')){

            $this->data[$this->alias]['register_token'] = $this->generateToken(4, '0123456789');

        }



    }



	function afterSave($created, $options = array()) {

	    parent::afterSave($options);



	    $authModel = 'User';

	    if(CakeSession::read('Auth.'.$authModel.'.id') == $this->id){

		    $data = $this->data[$this->alias];

	    	$_SESSION['Auth']['User'] = Hash::merge(CakeSession::read('Auth.User'), $data);



	    }



	}







	public function generateToken($length = 10, $possible = null) {

		

        if (!$possible) $possible = '0123456789abcdefghijklmnopqrstuvwxyz';



		$token = "";

		$i = 0;



		while ($i < $length) {

			$char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);

			if (!stristr($token, $char)) {

				$token .= $char;

				$i++;

			}

		}

		return $token;

	}





    public function passwordReset($postData = array()) {



        $user = $this->Info->find('first', array(

            'group' => array('Info.id'),

            'contain' => array('User', 'Student', 'Teacher', 'School', 'Faculty'),

            'conditions' => array(

                $this->alias . '.active' => 1,

                $this->alias . '.email' => $postData[$this->alias]['email']

            ),

        ));



        if (!empty($user)) {

            $sixtyMins = time() + 43000;

            $token = $this->generateToken();

            $user[$this->alias]['password_token'] = $token;

            $user[$this->alias]['email_token_expires'] = date('Y-m-d H:i:s', $sixtyMins);

            $user = $this->save($user, array(

                'fieldList' => array('id', 'password_token', 'email_token_expires'),

                'validate' => false,

            ));



            $userRef = $user['Info']['ref'];



            $user['Account'] = $user[$userRef];



            $this->data = $user;

            return $user;

        } else {

            $this->invalidate('email', "Cette adresse e-mail n'existe pas.");

        }



        return false;

    }



    public function checkPasswordToken($token = null) {

        $user = $this->find('first', array(

            'contain' => array(),

            'conditions' => array(

                $this->alias . '.active' => 1,

                $this->alias . '.password_token' => $token,

                $this->alias . '.email_token_expires >=' => date('Y-m-d H:i:s'))));

        if (empty($user)) {

            return false;

        }

        return $user;

    }



    public function resetPassword($postData = array()) {

        $tmp = $this->validate;



        $postData[$this->alias]['password_token'] = null;

        $save = $this->save($postData, array(

            'fieldList' => array('id', 'password', 'passwd', 'password_token'),

            //'validate' => false,

        ));



        $this->validate = $tmp;

        return $save;

    }



   



}