<?php

require_once 'controller/APIController.php';
require_once 'RESTConstants.php';

$endpointPath = 'rep/orders/new';
$uri = explode('/', $endpointPath);
print_r($uri);
$payload = array();
$queries = array();
$requestMethod = RESTConstants::METHOD_GET;

$controller = new APIController();
$res = $controller->handleRequest($uri, $endpointPath, $requestMethod, $queries, $payload);
print_r($res);
