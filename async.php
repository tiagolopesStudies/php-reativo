<?php

require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Promise\Utils;

$client = new GuzzleHttpClient();

$firstPromise = $client->getAsync('http://localhost:8080/http-server.php');
$secondPromise = $client->getAsync('http://localhost:8000/http-server.php');

$responses = Utils::unwrap([
    $firstPromise,
    $secondPromise,
]);

echo $responses[0]->getBody()->getContents();
echo $responses[1]->getBody()->getContents();
