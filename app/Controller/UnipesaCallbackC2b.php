<?php
class UnipesaCallbackC2b extends AppModel {

    public function beforeSave($options = array()) {
        parent::beforeSave($options);

        
        if (!is_null(@$this->data[$this->alias]['result'])) {
            $this->data[$this->alias]['result'] = serialize($this->data[$this->alias]['result']);      
        }

        if (!is_null(@$this->data[$this->alias]['provider_result'])) {
            $this->data[$this->alias]['provider_result'] = serialize($this->data[$this->alias]['provider_result']);      
        }

        if (!is_null(@$this->data[$this->alias]['details'])) {
            $this->data[$this->alias]['details'] = serialize($this->data[$this->alias]['details']);      
        }
        

    }


}