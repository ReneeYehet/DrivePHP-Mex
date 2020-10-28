<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, text/plain');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");

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
	include '../conexion-drive.php';
	// Get our spreadsheet
$spreadsheet = (new Google\Spreadsheet\SpreadsheetService)
   ->getSpreadsheetFeed()
   ->getByTitle('Proceso Cotizaciones');

// Get the first worksheet (tab)
$worksheets = $spreadsheet->getWorksheetFeed()->getEntries();
$worksheet = $worksheets[4];

$listFeed = $worksheet->getListFeed();
/** @var ListEntry */
	foreach ($listFeed->getEntries() as $entry) {
	   $representative = $entry->getValues();
	   //echo json_encode($representative);
	   if ($entry->getValues()['idcotizaciónbase'] === $id) {
	   	$link = $entry->getValues()['xmlurl'];
	   	
	   }
	}
	//echo $link;
	return $link;
}




/*function isXmlHttpRequest()
{
    $header = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : null;
    return ($header === 'XMLHttpRequest');
}

// example - checking our active call
if(!isXmlHttpRequest())
{
    echo 'Not an ajax request';
}
else
{
	
	echo 'is an ajax request';
	$data = array();
	$url = GetURL_Mex($id_cot);
	$data = feed($url);
	$insertar = InsertXML_Mex($data, $id_cot);
	if ($insertar) {
		echo "201";
	}else{
		echo "404";
	}
	
}*/


/*switch ($tipo) {
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
}*/

/*
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
header('Access-Control-Max-Age: 1728000');

$ret = [
	'result' => 'OK',
];
print json_encode($ret);

*/
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