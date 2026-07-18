<?php
class Sale extends AppModel {

    public $brwConfig = array(
        'actions' => array(
            'add' => false,
            'export' => false, 
            'index' => true,
            'edit' => false,
            'delete' => false),
    );

    public $hasMany = array(
        'Ticket' => array(
            'className' => 'SaleTicket',
            'foreignKey' => 'sale_id',
            'dependent' => false,
        ),
    );

    public $belongsTo = array(
        'Schedule' => array(
            'className' => 'CompanyDestinationSchedule',
            'foreignKey' => 'schedule_id',
        ),
        'Rate' => array(
            'className' => 'CompanyDestinationRate',
            'foreignKey' => 'rate_id',
        ),
        'From' => [
            'className' => 'Town',
            'foreignKey' => 'from_town_id'
        ],
        'To' => [
            'className' => 'Town',
            'foreignKey' => 'to_town_id'
        ],
        'Currency' => [
            'className' => 'Currency',
            'foreignKey' => 'currency_id'
        ],
        'User' => [
            'className' => 'User',
            'foreignKey' => 'user_id'
        ],
    );

    public function beforeSave($options = array()) {
        parent::beforeSave($options);

        // New Command ID
        if(!$this->exists()){
            $count = true;
            while ($count) {
                $this->data[$this->alias]['command'] = Op::RandomString(8, '0123456789');       

                $count = $this->find('all', array('conditions' => array('command' => $this->data[$this->alias]['command'])));
            }
        }

        if (is_array(@$this->data[$this->alias]['info'])) {
            $this->data[$this->alias]['info'] = serialize($this->data[$this->alias]['info']);      
        }
        if (!is_null(@$this->data[$this->alias]['user_facture_list'])) {
            $this->data[$this->alias]['user_facture_list'] = serialize($this->data[$this->alias]['user_facture_list']);      
        }
        if (!is_null(@$this->data[$this->alias]['server_facture_list'])) {
            $this->data[$this->alias]['server_facture_list'] = serialize($this->data[$this->alias]['server_facture_list']);      
        }

    }


}