<?php

require_once 'RESTConstants.php';
require_once 'db/db_models/SkiTypeModel.php';
class PublicEndpoint extends RequestHandler {


    public function __construct()
    {
        parent::__construct();

        //$this->validRequests[] = RESTConstants::ENDPOINT_PUBLIC;
        //$this->validMethods[RESTConstants::ENDPOINT_PUBLIC] = array();
        $this->validMethods[''][RESTConstants::METHOD_GET] = RESTConstants::HTTP_OK;
    }
    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array
    {
        //echo '"message": "test"';
        // return array("dei","hei");



        if ($this->isValidMethod('', $requestMethod) == RESTConstants::HTTP_METHOD_NOT_ALLOWED) {
            throw new APIException(RESTConstants::HTTP_METHOD_NOT_ALLOWED, $endpointPath,
                'Method '.$requestMethod.' not allowed');
        }

        // TODO: Add check for method
        // if $this->validMethods[$uri[0]][$requestMethod] isset?
        if (count($uri) == 0) {
            if (!isset($queries['model'])) {
                return $this->doGetAllSkiTypes();
            } else {
                $ski_model = $queries['model'];
                return $this->doGetSkiTypeByModel($ski_model);
            }
        } else {
            throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath, 'Wrong number of parts');
        }

    }


    protected function doGetSkiTypeByModel(string $ski_model): array {
        $model = new SkiTypeModel();
        return $model->getSkiTypesWithModelFilter($ski_model);
    }

    protected function doGetAllSkiTypes(): array {
        $model = new SkiTypeModel();
        return $model->getAllSkiTypes();
    }
}
