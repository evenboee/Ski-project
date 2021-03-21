<?php

require_once 'RequestHandler.php';
require_once 'CustomerRepEndpoint.php';

class APIController extends RequestHandler {

    public function __construct() {
        parent::__construct();
        $this->validRequests[] = RESTConstants::ENDPOINT_CUSTOMER_REP;
    }

    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array {
        $endpointUri = $uri[0];

        switch ($endpointUri) {
            case RESTConstants::ENDPOINT_CUSTOMER_REP:
                $endpoint = new CustomerRepEndpoint();
                break;
            default:
                return array(); // Replace with error
        }

        return $endpoint->handleRequest(array_slice($uri, 1), $endpointPath, $requestMethod, $queries, $payload);
    }

}