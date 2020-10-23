<?php
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