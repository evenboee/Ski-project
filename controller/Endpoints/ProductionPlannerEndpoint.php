<?php


require_once 'RESTConstants.php';
require_once 'db/db_models/ProductionPlanModel.php';

/**
 * Class ProductionPlannerEndpoint
 *
 * @author Amund Helgestad
 */
class ProductionPlannerEndpoint extends RequestHandler
{

    public function __construct()
    {
        parent::__construct();


        // $this->validRequests[] = RESTConstants::ENDPOINT_CUSTOMER;
        // $this->validMethods[RESTConstants::ENDPOINT_CUSTOMER] = array();

        $this->validMethods[''][RESTConstants::METHOD_POST] = RESTConstants::HTTP_OK;
        $this->validMethods[''][RESTConstants::METHOD_DELETE] = RESTConstants::HTTP_NOT_IMPLEMENTED;
        $this->validMethods[''][RESTConstants::METHOD_GET] = RESTConstants::HTTP_NOT_IMPLEMENTED;
    }

    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array
    {
        if ($this->isValidMethod('', $requestMethod) == RESTConstants::HTTP_METHOD_NOT_ALLOWED) {
            throw new APIException(RESTConstants::HTTP_METHOD_NOT_ALLOWED, $endpointPath,
                'Method '.$requestMethod.' is not allowed');
        } else if ($this->isValidMethod('',$requestMethod)==RESTConstants::HTTP_NOT_IMPLEMENTED) {
            throw new APIException(RESTConstants::HTTP_NOT_IMPLEMENTED, $endpointPath, 'Not implemented.');
        }

        if (count($uri) != 1) {throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath, 'Wrong number of parts.');}
        if ($uri[0] != RESTConstants::ENDPOINT_PLAN) {throw new APIException(RESTConstants::HTTP_NOT_FOUND, $endpointPath, 'Did not find the specified endpoint.');}


        $res['result'] = $this->doCreateProductionPlan($payload);
        $res['status'] = RESTConstants::HTTP_CREATED;
        return $res;
    }


    /**
     * Attempts to create a new production plan. If successful it will return an array with the added changes.
     * If anything goes wrong, it will not add anything and instead throw an @APIException.
     * @param array $payload the raw payload from request body
     * @return array an array with the response.
     * @throws APIException is thrown if it could not add the specified payload as a production plan.
     */
    protected function doCreateProductionPlan(array $payload): array {
        return (new ProductionPlanModel())->createProductionPlan($payload);
    }
}