<?php
/**
 * CakePHP helper for embedding YouTube, Vimeo and Dailymotion videos.
 * 
 * @name       Video Helper
 * @author     Andy Carter (@drmonkeyninja)
 * @author     Emerson Soares (dev.emerson@gmail.com)
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php) 
 */
class VideoHelper extends HtmlHelper {

	protected $_apis = array(
		'youtube_image' => '//i.ytimg.com/vi', // Location of youtube images 
		'youtube' => '//www.youtube.com', // Location of youtube player 
		'vimeo' => '//player.vimeo.com/video',
		'dailymotion' => '//www.dailymotion.com',
		'wistia' => '//fast.wistia.net'
	);


/**
 * Returns an embedded video.
 *
 * @param string $url video URL
 * @param array $settings (optional) parameters for the embedded video
 * @return string
 */
	public function embed($url, $settings = array()) {

		switch ($this->_getVideoSource($url)) {
			case 'youtube':
				return $this->youtube($url, $settings);
			case 'vimeo':
				return $this->vimeo($url, $settings);
			case 'dailymotion':
				return $this->dailymotion($url, $settings);
			case 'wistia':
				return $this->wistia($url, $settings);
			case false:
			default:
				return $this->_notFound(!empty($settings['failSilently']));
		}

	}


/**
 * Handles the response when no video is found
 *
 * @param boolean $failSilently
 * @return string
 */
	protected function _notFound($failSilently = false) {

		if ($failSilently===true) {
			return;
		} else {
			return $this->tag(
				'div', 
				__('Sorry, video does not exists'), 
				array('class' => 'error')
			);
		}

	}


/**
 * Returns an embedded Youtube video.
 *
 * @param string $url
 * @param array $settings
 * @return string
 */

	public function youtube($url, $settings = array()) {


		if(strstr($url, 'youtu.be')){

			$exUrl = explode('/', $url);
			krsort($exUrl);
			$exUrl = array_values($exUrl);

			$exUrl = explode('?', $exUrl[0]);

			$v = $exUrl[0];
			$url = 'https://www.youtube.com/watch?v='.$v;

		}


		$defaultSettings = array(
			'hd' => true, 
			'width' => 624,
			'height' => 369,
			'allowfullscreen' => 'true', 
			'frameborder' => 0,
			'related' => 0,
			'autoplay' => 0,
			'jsapi' => 0,
		);

		$settings = array_merge($defaultSettings, $settings);
		$videoId = $this->_getVideoId($url, 'youtube');

		if (empty($videoId)) {
			return $this->_notFound(!empty($settings['failSilently']));
		}

		$settings['src'] = $this->_apis['youtube'] . '/embed/' . $videoId . '?hd=' . $settings['hd'] . '&rel=' . $settings['related']. '&autoplay=' . $settings['autoplay'].'&enablejsapi=' . $settings['jsapi'];

		return $this->tag('iframe', null, array(
					'width' => $settings['width'],
					'height' => $settings['height'],
					'src' => $settings['src'],
					'frameborder' => $settings['frameborder'],
					'allowfullscreen' => $settings['allowfullscreen'])
				) . $this->tag('/iframe');
	}


/**
 * Returns an embedded Vimeo video.
 *
 * @param string $url
 * @param array $settings
 * @return string
 */
	public function vimeo($url, $settings = array()) {
		
		$defaultSettings = array
			(
			'width' => 400,
			'height' => 225,
			'show_title' => 1,
			'show_byline' => 1,
			'show_portrait' => 0,
			'color' => '00adef',
			'allowfullscreen' => 1,
			'autoplay' => 1,
			'loop' => 1,
			'frameborder' => 0
		);
		$settings = array_merge($defaultSettings, $settings);

		$videoId = $this->_getVideoId($url, 'vimeo');

		if (empty($videoId)) {
			return $this->_notFound(!empty($settings['failSilently']));
		}

		$settings['src'] = $this->_apis['vimeo'] . '/' . $videoId . '?title=' . $settings['show_title'] . '&amp;byline=' . $settings['show_byline'] . '&amp;portrait=' . $settings['show_portrait'] . '&amp;color=' . $settings['color'] . '&amp;autoplay=' . $settings['autoplay'] . '&amp;loop=' . $settings['loop'];
		return $this->tag('iframe', null, array(
					'src' => $settings['src'],
					'width' => $settings['width'],
					'height' => $settings['height'],
					'frameborder' => $settings['frameborder'],
					'webkitAllowFullScreen' => $settings['allowfullscreen'],
					'mozallowfullscreen' => $settings['allowfullscreen'],
					'allowFullScreen' => $settings['allowfullscreen']
				)) . $this->tag('/iframe');

	}


/**
 * Returns an embedded Dailymotion video.
 *
 * @param string $url
 * @param array $settings
 * @return string
 */
	public function dailymotion($url, $settings = array()) {

		$defaultSettings = array(
			'width' => 480,
			'height' => 270,
			'allowfullscreen' => 'true', 
			'frameborder' => 0,
			'related' => 0
		);

		$settings = array_merge($defaultSettings, $settings);

		$videoId = $this->_getVideoId($url, 'dailymotion');

		if (empty($videoId)) {
			return $this->_notFound(!empty($settings['failSilently']));
		}

		$settings['src'] = $this->_apis['dailymotion'] . '/embed/video/' . $videoId . '?related=' . $settings['related'];

		return $this->tag('iframe', null, array(
					'src' => $settings['src'],
					'width' => $settings['width'],
					'height' => $settings['height'],
					'frameborder' => $settings['frameborder'],
					'allowfullscreen' => $settings['allowfullscreen'])
				) . $this->tag('/iframe');

	}


/**
 * Returns an embedded Wistia video.
 *
 * @param string $url
 * @param array $settings
 * @return string
 */
	public function wistia($url, $settings = array()) {

		$defaultSettings = array(
			'width' => 480,
			'height' => 270,
			'allowfullscreen' => 'true', 
			'frameborder' => 0
		);

		$settings = array_merge($defaultSettings, $settings);

		$videoId = $this->_getVideoId($url, 'wistia');

		if (empty($videoId)) {
			return $this->_notFound(!empty($settings['failSilently']));
		}

		$settings['src'] = $this->_apis['wistia'] . '/embed/iframe/' . $videoId;

		return $this->tag('iframe', null, array(
					'src' => $settings['src'],
					'width' => $settings['width'],
					'height' => $settings['height'],
					'frameborder' => $settings['frameborder'],
					'allowfullscreen' => $settings['allowfullscreen'])
				) . $this->tag('/iframe');

	}


/**
 * Returns a Video ID
 *
 * @param string $url Video URL
 * @param string $source (optional) either 'youtube' or 'vimeo'
 * @return string
 */
	protected function _getVideoId($url, $source = null) {

		$source = empty($source) ? $this->_getVideoSource($url) : strtolower($source);

		switch ($source) {
			case 'youtube':
				$params = $this->_getUrlParams($url);
				return (isset($params['v']) ? $params['v'] : $url);
			case 'vimeo':
				$path = parse_url($url, PHP_URL_PATH);
				return substr($path, 1);
			case 'dailymotion':
				return strtok(basename($url), '_');
			case 'wistia':
				$path = parse_url($url, PHP_URL_PATH);
				preg_match('|^/medias/([0-9a-z]+)|i', $path, $matches);
				return !empty($matches[1]) ? $matches[1] : null;
		}

		return;

	}

	protected function _getUrlParams($url) {

		$query = parse_url($url, PHP_URL_QUERY);
		$queryParts = strpos($query, '=') ? explode('&', $query) : array();

		$params = array();

		foreach ($queryParts as $param) {
			$item = explode('=', $param);
			$params[$item[0]] = $item[1];
		}

		return $params;

	}

	protected function _getVideoSource($url) {

		$parsedUrl = parse_url($url);
		$host = $parsedUrl['host'];
		if ($this->_isIp($host) === false) {
			if (!empty($host)) {
				$host = $this->_returnDomain($host);
			} else {
				$host = $this->_returnDomain($url);
			}
		}
		$host = explode('.', $host);

		if (is_int(array_search('vimeo', $host))) {
			return 'vimeo';
		} elseif (is_int(array_search('youtube', $host)) OR is_int(array_search('youtu', $host))) {
			return 'youtube';
		} elseif (is_int(array_search('dailymotion', $host))) {
			return 'dailymotion';
		}  elseif (is_int(array_search('wistia', $host))) {
			return 'wistia';
		} else {
			return false;
		}

	}

	protected function _isIp($url) {

		//first of all the format of the ip address is matched 
		if (preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/", $url)) {
			//now all the intger values are separated 
			$parts = explode(".", $url);
			//now we need to check each part can range from 0-255 
			foreach ($parts as $ip_parts) {
				if (intval($ip_parts) > 255 || intval($ip_parts) < 0)
					return false; //if number is not within range of 0-255 
			}
			return true;
		} else {
			return false; //if format of ip address doesn't matches 
		}

	}

	protected function _returnDomain($domainb) {

		$bits = explode('/', $domainb);
		if ($bits[0] == 'http:' || $bits[0] == 'https:') {
			$domainb = $bits[2];
		} else {
			$domainb = $bits[0];
		}
		unset($bits);
		$bits = explode('.', $domainb);
		$idz = count($bits);
		$idz -=3;
		if (strlen($bits[($idz + 2)]) == 2) {
			$url = @$bits[$idz] . '.' . @$bits[($idz + 1)] . '.' . @$bits[($idz + 2)];
		} elseif (strlen($bits[($idz + 2)]) == 0) {
			$url = $bits[($idz)] . '.' . $bits[($idz + 1)];
		} else {
			$url = $bits[($idz + 1)] . '.' . $bits[($idz + 2)];
		}
		return $url;

	}


/**
 * Returns a Youtube video image
 * 
 * Available images:-
 * 
 * 		thumb - 120px x 90px (4:3)
 * 		large - 480px x 360px (4:3)
 * 		thumb1 - 120px x 90px (4:3) taken at 25% through the video
 * 		thumb2 - 120px x 90px (4:3) taken at 50% through the video
 * 		thumb3 - 120px x 90px (4:3) taken at 75% through the video
 * 		wide - 320px x 180px (16:9)
 * 		maxres - large image, not always available
 * 
 * @param string $url Youtube video URL
 * @param string $size (optional) thumbnail to be used
 * @param array $options (optional) parameters for HtmlHelper::image()
 * @return string
 */
	public function youtubeThumbnail($url, $size = 'thumb', $options = array()) {

		$videoId = $this->_getVideoId($url);

		$acceptedSizes = array(
			'thumb' => 'default', // 120px x 90px 
			'large' => 0, // 480px x 360px 
			'thumb1' => 1, // 120px x 90px at position 25% 
			'thumb2' => 2, // 120px x 90px at position 50% 
			'thumb3' => 3, // 120px x 90px at position 75%
			'wide' => 'mqdefault',
			'maxres' => 'maxresdefault'
		);

		if (empty($acceptedSizes[$size])===true) {
			return;
		}

		$imageUrl = $this->_apis['youtube_image'] . '/' . $videoId . '/' . $acceptedSizes[$size] . '.jpg';
		return $this->image($imageUrl, $options);

	}

    static $api_key = 'AIzaSyDAgk__NWh9in64VOrK62V1xdFxkg8cHM0';
    static $api_base = 'https://www.googleapis.com/youtube/v3/videos';
    static $thumbnail_base = 'https://i.ytimg.com/vi/';


    public function getVideoInfo($url, $options = array()) {

		$videoId = $this->_getVideoId($url);

		// On initialise le cache
		if (!empty($options['cache'])) $vi_infos = cache::read('vi_infos');
		if (!empty($vi_infos[$url])) return $vi_infos[$url];


        $params = array(
            'part' => 'statistics,contentDetails,snippet',
            'id' => $videoId,
            'key' => self::$api_key,
        );

        $api_url = self::$api_base . '?' . http_build_query($params);
        $result = json_decode(@file_get_contents($api_url), true);
        //debug($result);

        $vinfo = array();
        if(empty($result['items'][0]['contentDetails'])) {
	        
	        if (empty($videoId)) return null;

	        $vinfo['thumbnail']['default']       = self::$thumbnail_base . $videoId . '/default.jpg';
	        $vinfo['thumbnail']['mqDefault']     = self::$thumbnail_base . $videoId . '/mqdefault.jpg';
	        $vinfo['thumbnail']['hqDefault']     = self::$thumbnail_base . $videoId . '/hqdefault.jpg';

	        $vinfo['thumbnail']['sdDefault']     = self::$thumbnail_base . $videoId . '/sddefault.jpg';
	        $vinfo['thumbnail']['maxresDefault'] = self::$thumbnail_base . $videoId . '/maxresdefault.jpg';

        	//debug($vinfo);
        	return $vinfo;
        }
        else $vinfo += $result['items'][0]['contentDetails'];

        if(!empty($result['items'][0]['statistics'])) $vinfo += $result['items'][0]['statistics'];
        
        if(!empty($result['items'][0]['snippet'])) {
        	$vinfo['publishedAt'] = $result['items'][0]['snippet']['publishedAt'];
        	$vinfo['channelTitle'] = $result['items'][0]['snippet']['channelTitle'];
        	$vinfo['channelId'] = $result['items'][0]['snippet']['channelId'];
        	$vinfo += $result['items'][0]['snippet']['localized'];
        }

        $interval = new DateInterval($vinfo['duration']);
        $vinfo['duration_sec'] = $interval->h * 3600 + $interval->i * 60 + $interval->s;
		$vinfo['duration_time'] = ($interval->h ? str_pad($interval->h, 2, '0', STR_PAD_LEFT).':' : '').str_pad($interval->i, 2, '0', STR_PAD_LEFT).':'.str_pad($interval->s, 2, '0', STR_PAD_LEFT);	

        $vinfo['thumbnail']['default']       = self::$thumbnail_base . $videoId . '/default.jpg';
        $vinfo['thumbnail']['mqDefault']     = self::$thumbnail_base . $videoId . '/mqdefault.jpg';
        $vinfo['thumbnail']['hqDefault']     = self::$thumbnail_base . $videoId . '/hqdefault.jpg';

        $vinfo['thumbnail']['sdDefault']     = self::$thumbnail_base . $videoId . '/sddefault.jpg';
        $vinfo['thumbnail']['maxresDefault'] = self::$thumbnail_base . $videoId . '/maxresdefault.jpg';

        // On sauvegarde la mise en cache
		if (!empty($options['cache'])) cache::write('vi_infos', array_merge(($vi_infos ? $vi_infos : array()), array($url => $vinfo)));

		//debug($vinfo);
        return $vinfo;
    }
}