<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, text/plain');
header("Access-Control-Allow-Methods: GET, POST");
header("Allow: GET, POST");
header('Access-Control-Max-Age: 1728000');

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

?>