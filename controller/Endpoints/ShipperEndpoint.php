<?php

require_once 'controller/RequestHandler.php';
require_once 'db/db_models/ShipmentModel.php';

/**
 * Class ShipperEndpoint
 *
 * Based on https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project/-/blob/master/db/DealerModel.php
 *  By Rune Hjelsvold
 *
 * @author Even B. Bøe
 */
class ShipperEndpoint extends RequestHandler {

    public function __construct() {
        parent::__construct();

        $this->validRequests[] = RESTConstants::ENDPOINT_SHIP;
        $this->validMethods[RESTConstants::ENDPOINT_SHIP] = array();
        $this->validMethods[RESTConstants::ENDPOINT_SHIP][RESTConstants::METHOD_PATCH] = RESTConstants::HTTP_OK;
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
            throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath, 'Endpoint needs another part');
        }

        $res = array();
        // Expecting uri = ['ship', '{id}']
        if (!$this->isValidRequest($uri[0])) {
            throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath.'/'.$uri[0], 'Endpoint not found');
        }

        if ($uri[0] == RESTConstants::ENDPOINT_SHIP) {
            if ($this->isValidMethod(RESTConstants::ENDPOINT_SHIP, $requestMethod) == RESTConstants::HTTP_METHOD_NOT_ALLOWED) {
                throw new APIException(RESTConstants::HTTP_METHOD_NOT_ALLOWED, $endpointPath.'/'.$uri[0],
                    'Method '.$requestMethod.' not allowed');
            }
            if (($cnt = count($uri)) != 2) {
                throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath.'/'.$uri[0],
                    'Not right number of parts. Expected 2, but got '.$cnt);
            }
            $shipment_number = $uri[1];
            $res['result'] = $this->doSetStateOfShipment($shipment_number);
            if (count($res['result']) == 0) {
                throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath, "Shipment not found. Check ID");
            } else {
                $res['status'] = $this->validMethods[RESTConstants::ENDPOINT_SHIP][RESTConstants::METHOD_PATCH];
            }
            return $res;
        }

        throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath.'/'.$uri[0], 'Endpoint of shipper rep not found');
    }

    protected function doSetStateOfShipment($shipment_number): array {
        $model = new ShipmentModel();
        return $model->setStateOfShipment($shipment_number);
    }
}
