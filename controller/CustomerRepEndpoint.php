<?php

require_once 'RequestHandler.php';
require_once 'db/OrderModel.php';
require_once 'APIException.php';

class CustomerRepEndpoint extends RequestHandler {

    public function __construct() {
        parent::__construct();

        // Orders
        $this->validRequests[] = RESTConstants::ENDPOINT_ORDERS;
        $this->validMethods[RESTConstants::ENDPOINT_ORDERS] = array();
        $this->validMethods[RESTConstants::ENDPOINT_ORDERS][RESTConstants::METHOD_GET] = RESTConstants::HTTP_OK;

        // Order
        $this->validRequests[] = RESTConstants::ENDPOINT_ORDER;
        $this->validMethods[RESTConstants::ENDPOINT_ORDER] = array();
        $this->validMethods[RESTConstants::ENDPOINT_ORDER][RESTConstants::METHOD_PATCH] = RESTConstants::HTTP_OK;

    }

    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array {

        if (count($uri) == 0) {
            throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath);
        }

        if (!$this->isValidRequest($uri[0])) {
            throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath.'/'.$uri[0], 'Endpoint not found');
        }

        // Expecting uri = ['orders'] and query = ['state'= one of RESTConstants::ORDER_STATES]
        if ($uri[0] == RESTConstants::ENDPOINT_ORDERS) {
            if ($this->isValidMethod(RESTConstants::ENDPOINT_ORDERS, $requestMethod) == RESTConstants::HTTP_METHOD_NOT_ALLOWED) {
                throw new APIException(RESTConstants::HTTP_METHOD_NOT_ALLOWED, $endpointPath.'/'.$uri[0],
                'Method '.$requestMethod.' not allowed');
            }
            if (!isset($queries['state'])) {
                throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath . '/' . $uri[0],
                    'Query state has to be set');
            }
            if (!in_array($queries['state'], RESTConstants::ORDER_STATES)) {
                throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath . '/' . $uri[0],
                    'state not given as one of ' . implode(', ', RESTConstants::ORDER_STATES));
            }
            // TODO: Add check for method
            // if $this->validMethods[$uri[0]][$requestMethod] isset?
            if (count($uri) == 1) {
                $state = $queries['state'];
                return $this->doGetOrdersWithState($state);
            } else {
                throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath . '/' . $uri[0], 'Wrong number of parts');
            }
        }
        // Expecting uri = ['order', '{state}', {id}]
        else if ($uri[0] == RESTConstants::ENDPOINT_ORDER) {
            // TODO: Check that state exists
            // TODO: Add check for valid method
            if (count($uri) == 3) {
                $state = $uri[1];
                $id = $uri[2];
                return $this->doSetStateOfOrder($id, $state);
            } else {
                throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath . '/' . $uri[0], 'Wrong number of parts');
            }
        }

        throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath, 'Endpoint of customer rep not found');
    }

    protected function doGetOrdersWithState(string $state): array {
        $model = new OrderModel();
        return $model->getOrdersWithState($state);
    }

    protected function doSetStateOfOrder(string $id, string $state) {
        $model = new OrderModel();
        $model->setStateOfOrder($id, $state);
        return array(); // TODO: Consider changing - maybe return object?
    }
}
