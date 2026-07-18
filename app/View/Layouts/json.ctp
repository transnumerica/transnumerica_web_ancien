<?php 

if (empty($return)) {
	$return = array();
}

echo json_encode(Hash::merge($return, array('content' => $content_for_layout))); ?>