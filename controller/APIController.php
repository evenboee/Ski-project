<?php

require_once 'RequestHandler.php';
require_once 'CustomerRepEndpoint.php';
require_once 'APIException.php';
require_once 'RESTConstants.php';

class APIController extends RequestHandler {

    public function __construct() {
        parent::__construct();
        $this->validRequests[] = RESTConstants::ENDPOINT_CUSTOMER_REP;
    }

    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array {
        $endpointUri = $uri[0];
        $endpointPath .= '/' . $endpointUri;

        switch ($endpointUri) {
            case RESTConstants::ENDPOINT_CUSTOMER_REP:
                $endpoint = new CustomerRepEndpoint();
                break;
            default:
                throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath);
        }

        return $endpoint->handleRequest(array_slice($uri, 1), $endpointPath, $requestMethod, $queries, $payload);
    }
}
