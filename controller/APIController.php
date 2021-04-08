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


class APIController extends RequestHandler {

    public function __construct() {
        parent::__construct();
        $this->validRequests[] = RESTConstants::ENDPOINT_CUSTOMER_REP;
    }

    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array {
        $endpointUri = $uri[0];
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
