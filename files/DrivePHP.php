<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, text/plain');
header("Access-Control-Allow-Methods: GET, POST");
header("Allow: GET, POST");

require_once '../conexion-drive.php';
/*$id = '2ef4ff1f';
$body = GetBody($id);*/

/////////////////////////////////////////////////////////////// MEXICO
function GetURL_Mex($id){
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


