<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, text/plain');
header("Access-Control-Allow-Methods: GET, POST");
header("Allow: POST");


$id_cot = isset($_POST['id']) ? $_POST['id'] : 0;
print $id_cot;
$tipo= isset($_POST['tipo']) ? $_POST['tipo'] : 0;
print $tipo;

switch ($tipo) {
	case 1:
		echo "entro";
		/*$data = array();
		$url = GetURL_Mex($id_cot);
		$data = feed($url);
		$insertar = InsertXML_Mex($data, $id_cot);
		if ($insertar) {
			echo "201";
		}else{
			echo "404";
		}*/
		break;
	
	default:
		# code...
		break;
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
	
	
}*/

/////////////////////////////////////////////////////////////// MEXICO
function GetURL_Mex($id){
	require_once '../conexion-drive.php';
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

///////////////////////////////////////

function InsertXML_Mex($data, $id){
	require_once '../conexion-drive.php';
	$spreadsheet = (new Google\Spreadsheet\SpreadsheetService)
	->getSpreadsheetFeed()
	->getByTitle('Proceso Cotizaciones');

	// Get the first worksheet (tab)
	$worksheets = $spreadsheet->getWorksheetFeed()->getEntries();
	$worksheet  = $worksheets[5];
	
	$listFeed = $worksheet->getListFeed();

		for($i=0; $i < $data['lenght']; $i++) {
			if (strpos($data['noitem'][$i], "-") !== false) {
				$image = substr(strstr($data['noitem'][$i], '-', true), 0);
			}else{
				$image = $data['noitem'][$i];
			}
			
			$listFeed->insert([
			   'idcotizaciónpartidas' => $uniquecp = generateRandomString(),
			   'idcotizaciónbase' => $id,
			   'partnumber' => $data['noitem'][$i],
			   'encabezado' => $data['title'][$i],
			   'spk1' => 'E+H',
			   'marca' => 'E+H',
			   'descripción' => $descripcion = Description($data['desc'][$i]),
			   'imagenurl' => 'https://portal.endress.com/wa002/picturepool/500/1/'.$image.'.jpg',
			   'displayimagen' => '=IMAGE(RC[-1])',
			   'tiempodeentrega' => '2 semanas',
			   'qty' => $data['qty'][$i],
			   'preciounitario' => $data['pu'][$i]
			]);
		
		}
	return json_encode($listFeed);
}

//////////////////////////////////////////////////////////// Funciones Random
function generateRandomString() {
	$length = 8;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function Description($des){
	$space = str_replace("\r\n", '', $des);
	$texto2 = preg_replace('/[0-9][0-9][0-9]+[:]/', "\r\n", $space);
	$texto3 = preg_replace('/[A-Z]+[:]/', '', $texto2);
	$texto4 = preg_replace('/[0-9]+[:]/', '', $texto3);

	return $texto4;
}


//////////////////////////////////////////////////////////////////////////////////////////////
///////////////// PARA AGREGAR XML A COTIZACIÓN PARTIDA PDF //////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////

function feed($feedURL){

	//obtener la url del file XML en la base de datos de appsheet
	$xml = $feedURL;
	$datos = array();

	//Llamada a la url file xml para obtener su contenido
	$curl = curl_init();
	$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
	$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
	$header[]  = "Cache-Control: max-age=0";
	$header[]  = "Connection: keep-alive";
	$header[]  = "Keep-Alive: 300";
	$header[]  = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$header[]  = "Accept-Language: en-us,en;q=0.5";
	$header[] = ""; // browsers keep this blank.

	curl_setopt($curl, CURLOPT_URL, $xml);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com');
	curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
	curl_setopt($curl, CURLOPT_AUTOREFERER, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	
	//se guarda en una variable para extraer los datos
	$html = curl_exec($curl);
	curl_close($curl);
	//echo json_encode($html);

	//comienza el proceso de extraccion de datos en el xml y se pasan a variables
	$eh = simplexml_load_string($html);
	
	foreach ($eh->xpath("/bas:basket/bas:item") as $item) {
		$noitem = $item->xpath("//bas:itemNo");
		$iditem = $item->xpath("//bas:product/bas:orderCode");
		$qty	= $item->xpath("//bas:product/bas:quantity");
		$title	= $item->xpath("//bas:product/bas:texts/bas:longDescription");
		$desc 	= $item->xpath("//bas:product/bas:texts/bas:configuration");
		$cu		= implode(" ", $item->xpath("bas:itemPricing/bas:itemListPrice/@currency"));
		$pl		= $item->xpath("//bas:itemPricing/bas:itemListPrice");
		$des	= $item->xpath("//bas:itemPricing/bas:itemDiscount");
		$pu 	= $item->xpath("//bas:itemPricing/bas:unitSalesPrice");
		$total 	= $item->xpath("//bas:itemPricing/bas:itemSalesPrice");

		
	}

		$long = count($eh->xpath("/bas:basket/bas:item"));

		$datos = array('item' => $noitem, 'noitem' => $iditem, 'qty' => $qty, 'title' => $title, 'desc' => $desc, 'cu' => $cu, 'des' => $des, 'pl' => $pl, 'pu' => $pu, 'total' => $total, 'lenght' => $long);
	
		//echo json_encode($datos);

		return $datos;
}




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