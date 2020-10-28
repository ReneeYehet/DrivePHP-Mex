<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json, text/plain');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");

require __DIR__ . '/vendor/autoload.php';
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/client_secret_mex.json');
$client = new Google_Client;
$client->useApplicationDefaultCredentials();

$client->setApplicationName("Drive PHP Mex");
$client->setScopes(Google_Service_Drive::DRIVE);

if ($client->isAccessTokenExpired()) {
    $client->refreshTokenWithAssertion();
}

$accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];

ServiceRequestFactory::setInstance(
    new DefaultServiceRequest($accessToken)
);

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
	echo "Hello";
}






?>