<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

if(isset($_GET['level'])) {
	$id = $_GET['level'];
}

$data = array('description' => 'Doodles. Make your collection now.', 'image' => 'https://network.addoodles.top/assets/images/nfts/'.$id.'.webp' );

echo json_encode($data);

?>