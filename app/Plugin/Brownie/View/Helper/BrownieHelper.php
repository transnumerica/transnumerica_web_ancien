<?php

class BrownieHelper extends AppHelper {
	public $helpers = array('Form','Media.Media');

	public function picture($picSizes, $index = 0) {
		$i = 0;
		foreach ($picSizes as $key => $value) {
			if ($index == $i) {
				return $picSizes[$key];
			}
			$i++;
		}
		return $picSizes;
	}

	public function arraycontent($data, $model,$key,$params) {
				echo '<div style=" border-left: 3px solid; padding: 0 50px; margin: 5px; ">';

		foreach ($data as $L => $R) {
		if (!is_array($params['label'])) { $a['label']['text'] = $params['label']; }else{ $a['label']['text'] = $params['label']['text'];}

			    $a['type'] = 'text';
			    $a['label']['text'] = $a['label']['text'].'.'.$L;
			    $a['label']['style'] = 'display: inline;';
			    $a['style'] = 'display: inline; width: initial;';

			if (!is_array($R)) {
				if (strstr($key, 'contenu')) {
				    if (Configure::read('redacteur')) {$redacteur = Configure::read('redacteur');}else{$redacteur = 'ckeditor';}
						echo $this->Media->$redacteur($key. '.' .$L, array('label'=> $key.'.' .$L), $model);
				}else{
						echo $this->Form->input($model . '.' . $key. '.' .$L, $a);
				}
			}else{
				$this->arraycontent($data[$L], $model, $key.'.'.$L, $a);
			}
		}
				echo '</div>';

	}

	public function arrayview($data) {
	/*

		foreach ($data as $key => $value) {

			if (!is_array($value)) {

					echo (($value === null or $value === '') ? '&nbsp;' : $key.' = '.$value ).'<br>';

			}else{

				echo '<div style="padding: 0 50px; margin: 5px; ">';

					$this->arrayview($data[$key]);
				echo '</div>';
			}
		}
	*/
	}


	function recursive_array_search($needle,$haystack) {
	    foreach($haystack as $key=>$value) {
	        $current_key=$key;
	        if($needle===$value OR (is_array($value) && $this->recursive_array_search($needle,$value) !== false)) {
	            return $current_key;
	        }
	    }
	    return false;
	}
}

