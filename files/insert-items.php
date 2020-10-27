<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
	header('Access-Control-Allow-Headers: token, Content-Type');
	header('Access-Control-Max-Age: 1728000');
	header('Content-Length: 0');
	header('Content-Type: text/plain');
	die();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET, POST");
header("Allow: GET, POST");

$ret = [
	'result' => 'OK',
];
print json_encode($ret);
/*

// Tipo 1 = Perú; Tipo 2 = Mex; Tipo 3 = Chile
include '../conexion-drive.php';

include('DrivePHP.php');
include('feed.php');


$id_cot = isset($_POST['id']) ? $_POST['id'] : 0;
$tipo= isset($_POST['tipo']) ? $_POST['tipo'] : 0;

switch ($tipo) {
	case '1':
		$data = array();
		$url = GetURL_Mex($id_cot);
		$data = feed($url);
		$insertar = InsertXML_Mex($data, $id_cot);
		if ($insertar) {
			echo "201";
		}else{
			echo "404";
		}
		break;
	
	default:
		# code...
		break;
}


*/


//$id_cot = isset($_POST['id_cot']) ? $_POST['id_cot'] : 0;
//$id_cot=$_GET['id'];
//$tipo=$_GET['id'];

?>