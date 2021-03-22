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
        $this->validMethods[RESTConstants::ENDPOINT_ORDERS][RESTConstants::METHOD_GET] = RESTConstants::HTTP_NOT_IMPLEMENTED;

        // Order
        $this->validRequests[] = RESTConstants::ENDPOINT_ORDER;
        $this->validMethods[RESTConstants::ENDPOINT_ORDER] = array();
        $this->validMethods[RESTConstants::ENDPOINT_ORDER][RESTConstants::METHOD_POST] = RESTConstants::HTTP_NOT_IMPLEMENTED;

    }

    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array {

        if (count($uri) == 0) {
            throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath);
        }

        // Expecting uri = ['orders', '{state}']
        if ($uri[0] == RESTConstants::ENDPOINT_ORDERS) {
            if (count($uri) == 2) {
                $state = $uri[1];
                return $this->doGetOrdersWithState($state);
            } else {
                throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath . '/' . $uri[0]);
            }
        }

        throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath);
    }

    protected function doGetOrdersWithState(string $state): array {
        $model = new OrderModel();
        return $model->getOrdersWithState($state);
    }
}
