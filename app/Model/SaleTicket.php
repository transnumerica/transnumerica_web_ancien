<?php
class SaleTicket extends AppModel {

    public $brwConfig = array(
        'actions' => array(
            'add' => false,
            'export' => false, 
            'index' => true,
            'edit' => false,
            'delete' => false),
    );
    public $belongsTo = array(
        'Sale' => array(
            'className' => 'Sale',
            'foreignKey' => 'sale_id',
        ),
    );
 

    public function beforeSave($options = array()) {
        parent::beforeSave($options);

        // New Invoice ID
        if(!$this->exists()){
            $count = true;
            while ($count) {
                $this->data[$this->alias]['invoice'] = Op::RandomString(5, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ').Op::RandomString(4, '0123456789');       

                $count = $this->find('all', array('conditions' => array('invoice' => $this->data[$this->alias]['invoice'])));
            }
        }


        //Pharma::createRandomPassword()

        if (is_array(@$this->data[$this->alias]['info'])) {
            $this->data[$this->alias]['info'] = serialize($this->data[$this->alias]['info']);        
        }

    }


}