<?php
// Tipo 1 = Perú; Tipo 2 = Mex; Tipo 3 = Chile
require_once 'conexion-drive.php';

include('DrivePHP.php');
include('feed.php');

//$id_cot = isset($_POST['id_cot']) ? $_POST['id_cot'] : 0;
//$id_cot=$_GET['id'];
//$tipo=$_GET['id'];
$id_cot = isset($_POST['id']) ? $_POST['id'] : 0;
$tipo= isset($_POST['tipo']) ? $_POST['tipo'] : 0;


if ($tipo==1) {
	$data = array();
	$url = GetURL_Mex($id_cot);
	$data = feed($url);
	$insertar = InsertXML_Mex($data, $id_cot);
	if ($insertar) {
		echo "201";
	}else{
		echo "404";
	}
}


?>