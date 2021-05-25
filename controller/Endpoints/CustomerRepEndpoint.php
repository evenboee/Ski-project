<?php

require_once 'controller/RequestHandler.php';
require_once 'db/db_models/OrderModel.php';
require_once 'controller/APIException.php';
require_once 'db/dbCredentials.php';

/**
 * Class CustomerRepEndpoint
 *
 * Based on https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project/-/blob/master/db/DealerModel.php
 *  By Rune Hjelsvold
 *
 * @author Even B. Bøe
 */
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

        if (count($uri) == 0) {
            throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath);
        }

        if (!$this->isValidRequest($uri[0])) {
            throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath.'/'.$uri[0], 'Endpoint not found');
        }
        $res = array();
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
            if (count($uri) == 1) {
                $state = $queries['state'];
                $res['result'] = $this->doGetOrdersWithState($state);
                $res['status'] = $this->validMethods[RESTConstants::ENDPOINT_ORDERS][RESTConstants::METHOD_GET];
                return $res;
            } else {
                throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath . '/' . $uri[0], 'Wrong number of parts');
            }
        }
        // Expecting uri = ['order', '{state}', {id}]
        else if ($uri[0] == RESTConstants::ENDPOINT_ORDER) {
            if ($this->isValidMethod(RESTConstants::ENDPOINT_ORDER, $requestMethod) == RESTConstants::HTTP_METHOD_NOT_ALLOWED) {
                throw new APIException(RESTConstants::HTTP_METHOD_NOT_ALLOWED, $endpointPath.'/'.$uri[0],
                    'Method '.$requestMethod.' not allowed');
            }
            if (count($uri) == 3) {
                $state = $uri[1];
                $id = $uri[2];
                $employee_number = $this->getEmployeeNumberFromPayload($payload);
                if ($employee_number == 0) {
                    throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath.'/'.implode('/', $uri),
                    "employee_number has to be set");
                }
                if (!(new OrderModel())->customerRepExists($employee_number)) {
                    throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath.'/'.implode('/', $uri),
                        "given id is not an id of a customer rep in database");
                }
                $res['result'] = $this->doSetStateOfOrder($id, $state, $employee_number);
                $res['result']['id'] = $employee_number;
                $res['status'] = $this->validMethods[RESTConstants::ENDPOINT_ORDER][RESTConstants::METHOD_PATCH];
                return $res;
            } else {
                throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath . '/' . $uri[0], 'Wrong number of parts');
            }
        }

        throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath, 'Endpoint of customer rep not found');
    }

    protected function doGetOrdersWithState(string $state): array {
        $model = new OrderModel(REP_USER, REP_PWD);
        return $model->getOrdersWithState($state);
    }

    protected function doSetStateOfOrder(string $id, string $state, int $employee_number): array {
        $model = new OrderModel(REP_USER, REP_PWD);
        return $model->setStateOfOrder($id, $state, $employee_number);
    }

    /**
     * Validates employee number payload
     * @param array $payload => body of request (key value array)
     * @return int => employee number from payload
     *                if payload is not set returns 0
     */
    protected function getEmployeeNumberFromPayload(array $payload): int {
        if (!isset($payload['employee_number'])) {
            return 0;
        }
        return intval($payload['employee_number']);
    }
}
