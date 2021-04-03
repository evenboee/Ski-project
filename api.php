<?php

use JetBrains\PhpStorm\Pure;

require_once 'controller/APIController.php';
require_once 'controller/APIException.php';
require_once 'RESTConstants.php';

header('Content-Type: application/json');

// Parse request parameters
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
if (!isset($queries['request'])) {
    http_response_code(RESTConstants::HTTP_NOT_FOUND);
    echo '{error: "error"}';// json_encode(generateErrorResponseContent(RESTConstants::HTTP_NOT_FOUND, '/'));
    return;
}

$uri = explode( '/', $queries['request']);
unset($queries['request']);

$requestMethod = $_SERVER['REQUEST_METHOD'];

$content = file_get_contents('php://input');
if (strlen($content) > 0) {
    $payload = json_decode($content, true);
} else {
    $payload = array();
}

$token = isset($_COOKIE['auth_token']) ? $_COOKIE['auth_token'] : '';

// Handle the request
$controller = new APIController();
try {
    // $controller->authorise($token, RESTConstants::API_URI . '/');
    $res = $controller->handleRequest($uri, RESTConstants::API_URI, $requestMethod, $queries, $payload);
    // http_response_code($res['status']);
    http_response_code(RESTConstants::HTTP_OK);
    echo json_encode($res);
    // if (isset($res['result']))  {echo json_encode($res['result']);}
// Handle application exceptions
} catch (APIException $e){
    http_response_code($e->getCode());
    echo json_encode(generateErrorResponseContent($e));
} catch (Throwable $e) {
    http_response_code(RESTConstants::HTTP_INTERNAL_SERVER_ERROR);
    // echo json_encode(RESTConstants::HTTP_INTERNAL_SERVER_ERROR, '/', -1);
    echo '{"error": "error"}';
}

function generateErrorResponseContent(APIException $e): array {
    $res = array();
    $res['Status'] = $e->getCode();
    $res['Message'] = $e->getMessage();
    $res['Instance'] = $e->getInstance();
    return $res;
}