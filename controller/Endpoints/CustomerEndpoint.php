<?php

require_once 'RESTConstants.php';
require_once 'db/db_models/OrderModel.php';
require_once 'db/db_models/ProductionPlanModel.php';

class CustomerEndpoint extends RequestHandler {

    public function __construct()
    {
        parent::__construct();


       // $this->validRequests[] = RESTConstants::ENDPOINT_CUSTOMER;
       // $this->validMethods[RESTConstants::ENDPOINT_CUSTOMER] = array();

        $this->validMethods[''][RESTConstants::METHOD_POST] = RESTConstants::HTTP_OK;
        $this->validMethods[''][RESTConstants::METHOD_DELETE] = RESTConstants::HTTP_OK;
        $this->validMethods[''][RESTConstants::METHOD_GET] = RESTConstants::HTTP_OK;
    }


    /**
     * Handles POST, DELETE and GET requests to customer endpoint.
     * POST only works to   ../customer/order/new
     * DELETE only works to ../customer/order/{order_id}
     * GET only works to    ../customer/production-plan
     *
     * @param array $uri
     * @param string $endpointPath
     * @param string $requestMethod
     * @param array $queries
     * @param array $payload
     * @return array
     * @throws APIException
     */
    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array
    {
        if ($this->isValidMethod('', $requestMethod) == RESTConstants::HTTP_METHOD_NOT_ALLOWED) {
            throw new APIException(RESTConstants::HTTP_METHOD_NOT_ALLOWED, $endpointPath,
                'Method '.$requestMethod.' is not allowed');
        }
        if (count($uri) == 0) {throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath, 'You are missing parts!');}

        if ($uri[0]==RESTConstants::ENDPOINT_ORDER) {
            if (count($uri) != 2) {throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath, 'Wrong number of parts');}
            if ($requestMethod == RESTConstants::METHOD_POST) {
                if ($uri[1]==RESTConstants::ENDPOINT_NEW) {
                    // Create order

                    $res['result'] = $this->doCreateOrder($payload);
                    $res['status'] = RESTConstants::HTTP_CREATED;
                    return $res;
                }

            } else if ($requestMethod == RESTConstants::METHOD_DELETE) {

                $res['result'] = $this->doDeleteOrder($uri[1], $payload);
                $res['status'] = RESTConstants::HTTP_OK; // TODO: another http code perhaps?
                return $res;
            }
        } else if ($uri[0]==RESTConstants::ENDPOINT_PLAN){
            if (count($uri) != 2) {throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath, 'Wrong number of parts');}

            $res['result'] = $this->doRetrieveProductionPlanSummary($uri[1]); // TODO: retrieve production plan
            $res['status'] = RESTConstants::HTTP_OK;
            return $res;
        }
        throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath, 'Endpoint of type customer not found');
    }


    /**
     * @param string $order_id
     * @param array $payload
     * @return array
     * @throws APIException
     */
    protected function doDeleteOrder(string $order_id, array $payload): array {
        return (new OrderModel())-> deleteOrder($order_id, $payload);
    }

    /**
     * @param array $payload
     * @return array
     * @throws APIException
     */
    protected function doCreateOrder(array $payload): array{
        return (new OrderModel())-> createOrder($payload);
    }

    /**
     * @param string $plan_id
     * @return array
     * @throws APIException
     */
    protected function doRetrieveProductionPlanSummary(string $plan_id): array{

        return (new ProductionPlanModel())->retrieveProductionPlan($plan_id);
    }
}
