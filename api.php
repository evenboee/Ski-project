<?php
/**
 * Based on https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project/-/blob/master/api.php
 *  By Rune Hjelsvold
 * @author Even B. Bøe
 */


require_once 'controller/APIController.php';
require_once 'controller/APIException.php';
require_once 'RESTConstants.php';

header('Content-Type: application/json');

// Parse request parameters
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
if (!isset($queries['request'])) {
    http_response_code(RESTConstants::HTTP_NOT_FOUND);
    echo json_encode(['error' => 'request not set']);
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
$queries['token'] = $token;

// Handle the request
$controller = new APIController();
try {
    $res = $controller->handleRequest($uri, RESTConstants::API_URI, $requestMethod, $queries, $payload);
    if (isset($res['status'])) { http_response_code($res['status']); }
    else { http_response_code(RESTConstants::HTTP_OK); }

    if (isset($res['result'])) { echo json_encode($res['result']); }
    else { echo json_encode($res); }
// Handle application exceptions
} catch (APIException $e){
    http_response_code($e->getCode());
    echo json_encode(generateErrorResponseContent($e));
} catch (Throwable $e) {
    http_response_code(RESTConstants::HTTP_INTERNAL_SERVER_ERROR);
    echo json_encode(['error' => $e->getMessage(), 'status' => RESTConstants::HTTP_INTERNAL_SERVER_ERROR]); // Not final solution, but very useful for debugging
}

function generateErrorResponseContent(APIException $e): array {
    $res = array();
    $res['Status'] = $e->getCode();
    $res['Message'] = $e->getMessage();
    $res['Instance'] = $e->getInstance();
    return $res;
}