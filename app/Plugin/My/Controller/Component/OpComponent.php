<?php
App::uses('Validation', 'Utility');
class OpComponent extends Component {

    public function _sendEmail($options = array(), $pass = array()) {
        trigger_error('$this->_sendEmail is deprecated. Use Op::sendMail() instead.', E_USER_DEPRECATED);
        return Op::sendMail($options, $pass);        
    }

    public function startup(Controller $controller) {
        $this->request = $controller->request;
    }


    function navigateur() { 
        trigger_error('$this->navigateur is deprecated. Use Op::navigateur() instead.', E_USER_DEPRECATED);
        return Op::navigateur($options, $pass);        
    }

    function random_float($options) { 
        trigger_error('$this->random_float is deprecated. Use Op::random_float() instead.', E_USER_DEPRECATED);
        return Op::random_float($options);        
    }


}