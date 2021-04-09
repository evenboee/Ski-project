<?php

require_once 'RequestHandler.php';
require_once 'Endpoints/CustomerRepEndpoint.php';
require_once 'APIException.php';
require_once 'RESTConstants.php';
require_once 'Endpoints/PublicEndpoint.php';
require_once 'Endpoints/StorekeeperEndpoint.php';

require_once 'Endpoints/ShipperEndpoint.php';
require_once 'db/db_models/AuthorizationModel.php';

require_once 'Endpoints/CustomerEndpoint.php';
require_once 'Endpoints/ProductionPlannerEndpoint.php';

/**
 * Class APIController
 *
 * Based on https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project/-/blob/master/controller/APIController.php
 *   By Rune Hjelsvold
 *
 * @author Even B. Bøe and Amund Helgestad
 */
class APIController extends RequestHandler {

    public function __construct() {
        parent::__construct();
        $this->validRequests[] = RESTConstants::ENDPOINT_CUSTOMER_REP;
        $this->validRequests[] = RESTConstants::ENDPOINT_PUBLIC;
        $this->validRequests[] = RESTConstants::ENDPOINT_SHIPPER;
        $this->validRequests[] = RESTConstants::ENDPOINT_STOREKEEPER;
        $this->validRequests[] = RESTConstants::ENDPOINT_CUSTOMER;
        $this->validRequests[] = RESTConstants::ENDPOINT_PLANNER;
    }

    /**
     * handleRequest takes information about request, validates request and routes to propper model to query database and returns result
     *
     * @param array $uri => list of parts from the path
     * @param string $endpointPath => path from request
     * @param string $requestMethod => method of the request
     * @param array $queries => queries given by the user
     * @param array $payload => body of the request
     * @return array => appropriate array for the request being made
     * @throws APIException is thrown if anything went wrong
     */
    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array {
        $endpointUri = $uri[0];
        if (!$this->isValidRequest($endpointUri)) {
            throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath);
        }
        $endpointPath .= '/' . $endpointUri;
        if ($endpointUri != RESTConstants::ENDPOINT_PUBLIC) {
            $notAuthorizedException = new APIException(RESTConstants::HTTP_FORBIDDEN, $endpointPath, 'Client is not authorized for this endpoint');
            $role = '';
            if (isset($queries['token'])) {
                $role = (new AuthorizationModel())->getRole($queries['token']);
            }
            if ($role != $endpointUri) {
                throw $notAuthorizedException;
            }
        }

        switch ($endpointUri) {
            case RESTConstants::ENDPOINT_CUSTOMER_REP:
                $endpoint = new CustomerRepEndpoint();
                break;
            case RESTConstants::ENDPOINT_PUBLIC:
                $endpoint = new PublicEndpoint();
                break;
            case RESTConstants::ENDPOINT_STOREKEEPER:
                $endpoint = new StorekeeperEndpoint();
                break;
            case RESTConstants::ENDPOINT_CUSTOMER:
                $endpoint = new CustomerEndpoint();
                break;
            case RESTConstants::ENDPOINT_SHIPPER:
                $endpoint = new ShipperEndpoint();
                break;
            case RESTConstants::ENDPOINT_PLANNER:
                $endpoint = new ProductionPlannerEndpoint();
                break;
            default:
                throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath, 'Endpoint not found');
        }

        return $endpoint->handleRequest(array_slice($uri, 1), $endpointPath, $requestMethod, $queries, $payload);
    }
}
