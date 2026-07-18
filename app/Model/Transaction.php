<?php

class Transaction extends AppModel {

    public $brwConfig = array(
        'actions' => array('add' => true,'export' => true),
         'fields' => array(
            'no_add' => array('password', 'passwd'),
            'no_edit' => array('password', 'passwd'),
        ),
    );


    public $belongsTo = array(

    );


    public function beforeSave($options = array()) {
        parent::beforeSave($options);

        if(!$this->exists()){

            $ref = @$this->data[$this->alias]['ref'];

            if(in_array($ref, array_keys($this->belongsTo))) {

                $this->data[$this->alias]['serial'] = $this->{"Token".$ref}();

            }else{

                exit('Type de Transaction non reconnu');

            }



        }


        // On sauvegarde aussi l'ip et les info sur navigateur de l'utilisateur
        $this->data[$this->alias]['ip'] = Router::getRequest()->clientIp();
        $this->data[$this->alias]['userAgent'] = serialize(Op::userAgent());

    }





    public function TokenDeposit() {

        return $this->generateserial('SNVG');

    }



    public function TokenTransfer() {

        return $this->generateserial('TRSF');

    }



    public function TokenSend() {

        return $this->generateserial('SEND');

    }



    public function loantoken() {

        return $this->generateserial('LOAN');

    }



    public function generateserial($start = null, $length = 16, $possible = null) {



        if (!$possible) $possible = '0123456789';



        $count = true;

        while ($count) {



            $token = "";

            $i = 0;



            while ($i < $length) {

                $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);

                $token .= $char;

                $i++;

            }



            $token = $start.$token;



            $count = $this->find('first', array('conditions' => array('serial' => $token)));



        }



        return $token;

    }







}