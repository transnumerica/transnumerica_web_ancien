<?php
class BuyController extends AppController {

	public function mmoney() {

		if (!empty($this->request->data)) {
			
			$authUser = $this->Auth->user();

			$this->request->data['Sale']['info']['place'] = json_decode($this->request->data['Sale']['info']['place'], true);

	        $Options['data'] = $this->request->data; 
	        $Options['ajax'] = false; 
			$this->buyAppMobileMoney($Options);

		}

	}

	public function successful($id) {

		$urldown = Router::url(array('controller' => 'invoice', 'action' => 'preview', $id), true);

		$url = Router::url('/', true);
		exit('<center><b style=" font-size: 28px; "><br>'.Configure::read('Company.name').'<br>
			'.__d('translate', 'Paiement réussi, Vous serez redirigé dans quelques secondes ...').'</b></center>
		<script type="text/javascript">
		setTimeout(function(){
		window.location.replace("'.$urldown.'");
		},1500);
		</script>

		<script type="text/javascript">
		setTimeout(function(){
		window.location.replace("'.$url.'");
		},4500);
		</script>

		');

	}


}