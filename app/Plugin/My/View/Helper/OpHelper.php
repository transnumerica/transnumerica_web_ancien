<?php
class OpHelper extends AppHelper{

    public $view;
  public $helpers = array('Html','Time');

    public function __construct($view) {

        $this->view = $view;
        parent::__construct($view);
    }

	public function whereamI($tree = array(), $params = array('start' => false, 'separator' => '»', 'div' => false, 'tag' => false, 'class' => false)){
		
		$whereamI = null;

		if (!empty($params['id']) AND empty($params['div'])) $params['div'] = true;		


		if ($params['div']) {

			if ($params['div'] === true) $params['div'] = 'div';

			$whereamI .= '<'.$params['div'].'';

			if (!empty($params['id'])) $whereamI .= ' id="'.$params['id'].'"'; 
			if (!empty($params['class'])) $whereamI .= ' class="'.$params['class'].'"'; 

			$whereamI .= '>';
		}


		foreach ($tree as $name => $link) {
			reset($tree);
			$firstkey = key($tree);
			if ($name === $firstkey) {
				if (!empty($params['start'])) $whereamI .= ' '.$params['start'].' ';
			}

			end($tree);
			$lastkey = key($tree);
			if ($name !== $lastkey) {
				$iam = $this->Html->link($name, $link);

				if (!empty($params['tag'])) {
					$iam = $this->Html->tag($params['tag'], $iam);
				}

				$whereamI .= $iam;

				$whereamI .= ' '.$params['separator'].' ';
			}elseif (!is_int($name)) {

				if (empty($name) OR empty($params['tag'])) {
					$params['tag']= 'span';
				}

				$whereamI .= $this->Html->tag($params['tag'], $name, array('class' => 'active'));

			}else{

				if (empty($name)) {
					$params['tag']= 'span';
				}

				$whereamI .= $this->Html->tag($params['tag'], $link, array('class' => 'active'));
			}
		}

		if (!empty($params['div'])) {
			$whereamI .= '</'.$params['div'].'>';
		}

		return $whereamI;
	}
	
	public function pourcentage($nombre, $total){
		return number_format($nombre * 100 / $total,1);
	}

	public function temps($min){
		if ($min < 60) {
			$temps = $this->min($min);
		}elseif ($min >= 60) {
			$temps = floor($min/60).'h';
			$restmin =  $min - $temps*60;
			if ($restmin) $temps = $temps.' '.$this->min($restmin);
		}
		return $temps;
	}

	public function min($min){
		return $min = $min.'min';
	}


	function multiexplode($delimiters,$string) {
	    $ready = str_replace($delimiters, $delimiters[0], $string);
	    $launch = explode($delimiters[0], $ready);
	    return $launch;
	}

	public function timeformat($date, $format = null, $default = false, $timezone = null) {
		$format = $this->Time->format($date, $format, $default, $timezone);
		$format = ucwords($format);
		//$format = str_replace('Ã', 'à', $format);
		return $format;
	}

	public function HtmlToText($line = null) {

        $replace = array(
        '/ {2,}/' => '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s',
        '/!\s+!/' => ' ',
        '/ {2,}/' => ' ',
        '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s' => '',
        );

        $line = preg_replace(array_keys($replace), array_values($replace), $line);

        $line = str_replace(array("<br", "/p>"), array("\n<br", "/p>\n") , $line);
		$line = strip_tags($line);
		$line = html_entity_decode($line);
		//$line = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $line);
		$line = preg_replace('(\n|\r|\t)',' ',$line);
        if(Op::isEncoded($line)) $line = preg_replace('/\s\s+/', ' ', $line); 
		//$line = preg_replace('/\s\s+/', ' ', $line);
		while (strpos($line, ' ') === 0) $line = substr_replace($line, '', 0, 1);
        if (!mb_detect_encoding($line, 'UTF-8', true)) $line = utf8_decode($line);

		return $line;
	}

	// Afficher un nom complet sous forme de "Sigle"
	public function acronyme($mots) {
		$mots = str_replace(array('à ','et '," l'",'le ','la '), array(' ','','','',''), $mots);
		$mots = explode(' ', $mots);
		foreach ($mots as $key => $mot) {
			$mots[$key] = ucwords(substr($mot,0,1));
		}
		$mots = implode($mots);
		return $mots;
	}


	public function InputClass($data = array(), $class = null) {
		foreach ($data as $key => $value) {
		unset($data[$key]);
		$data[$value] = array('name' => $value, 'value' => $key, 'class' => $class);
		}
		return $data;
	}


	public function url_query_add() {
		foreach (func_get_args() as $key => $arg) {		

			// On supprime les sous domaines pour eviter de generer des duplicatas des sous domaines
    		if ($key === 0 AND stripos($arg, $this->base) === 0) {
		        $arg = substr_replace($arg, '', 0, strlen($this->base));
    		}

    		if ($key!== 0 AND stripos($arg, '?') !== 0) $arg = substr_replace($arg, '?', 0, 0);
			$url_parsed = parse_url($arg);

			if ($key === 0) {
				$url = $arg;

				$new_url = '';

				if (isset($url_parsed['scheme'])) {
					$new_url .= $url_parsed['scheme'];
					$new_url .= '://';	
				}
				
				if (isset($url_parsed['host'])) $new_url .= $url_parsed['host'];

				$new_url .= $url_parsed['path'];

			}else{
				$qs[] = $arg;
			}

		if (isset($url_parsed['query'])) $args[] = $url_parsed['query'];

		}

		$new_url .= '?';
		$new_url .= implode('&', $args);
	    return $new_url;
	}


	// Html Code to 1 line
	public function inlinehtml($line = null, $escape = false) {
	    $replace = array(
	        '/ {2,}/' => '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s',
	        '/!\s+!/' => ' ',
	        '/ {2,}/' => ' ',
	        '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s' => '',
	    );
	    $line = preg_replace(array_keys($replace), array_values($replace), $line);

	    if ($escape) $line = str_replace("'", htmlspecialchars("'"), $line);
		else {
			$line = str_replace("'", "\'", $line);
			$line = str_replace("</script>", "<\/script>", $line);
		}
			    
    return $line;
	}


	// Html Code to 1 line
	public function checkjscache($key) {

		if(empty($_COOKIE['___locache___'][$key])) return false;
		//debug(filter_input(INPUT_SERVER, 'HTTP_CACHE_CONTROL') === 'max-age=0')
		// On retourne à 'false' lors d'un rechargement pour vider le cache 'LocalStorage'
    	return !((isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' AND isset($_SERVER['HTTP_REFERER'])));
	}



	public function loadJsCache($val, $element, $key) {

		$val = $this->inlinehtml($val);
		$key = substr(md5($val), 0, 2);

		if(!$this->checkjscache($key)) {
			?>
			<script type="text/javascript">
		    	$('<?php echo $element ?>').prepend(cachevalue = '<?php echo $val ?>');
		    	locache.set("<?php echo $key ?>", cachevalue);
			</script>
		<?php }else{ ?>
			<script type="text/javascript">
		    	$('<?php echo $element ?>').prepend(locache.get("<?php echo $key ?>"));
			</script>
		<?php


	}

	}


	function rangement($array) {
		$array2 = $array;
		$i = 0;
		foreach ($array2 as $key => $value) {
		    $i ++;
		    $array3[$i] = $value;
		}
		return $array3;
	}


	function fb_comment_count($url){
	  @$json = json_decode(file_get_contents('https://graph.facebook.com/?ids=' . $url));
	  return isset($json->$url->comments) ? $json->$url->comments : 0;
	}



}
