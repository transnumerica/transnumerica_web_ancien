

<?php
class MobileController extends AppController{
    public function beforeFilter() {
		parent::beforeFilter();
		//$this->Security->unlockedActions = array('login');
		$this->Auth->allow();


		if (!$this->request->is('json')) {
			//exit();
		}

		$this->layout = null;
		$this->layout = 'ajax';
		if(!file_exists(APP.'View'.DS.$this->name.DS.$this->view.'.ctp')){
			$this->autoRender = null;
		}


	}
    public function android(){
        
        $androidLink = WWW_ROOT . '/application/trans.numerica.apk';
		$this->response->type(['apk'=>'application/vnd.android.package-archive']);
		
		$this->response->file($androidLink, [
            'download' => true,
            'name' => 'TransNumerica.apk',
        ]);
		//$this->response->length(10000);//filesize($androidLink));
        

		return $this->response;
        
        
       
    }

}





