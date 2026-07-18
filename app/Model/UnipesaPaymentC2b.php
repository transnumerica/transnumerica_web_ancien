<?php
class UnipesaPaymentC2b extends AppModel {

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

        
        if (!is_null(@$this->data[$this->alias]['details'])) {
            $this->data[$this->alias]['details'] = serialize($this->data[$this->alias]['details']);      
        }
        

    }


}