<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, text/plain');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");

define('ROOT_PATH', dirname(__DIR__) . '/');
XML();
print "Hello";
function XML(){
	$id_cot = isset($_POST['id']) ? $_POST['id'] : 0;
	print $id_cot;
	$tipo= isset($_POST['tipo']) ? $_POST['tipo'] : 0;
	print $tipo;
	$url = GetURL_Mex($id_cot);
	echo $url;
}

/////////////////////////////////////////////////////////////// MEXICO
function GetURL_Mex($id){
	require_once ROOT_PATH . '../conexion-drive.php';
	echo "Hello";
}






?>