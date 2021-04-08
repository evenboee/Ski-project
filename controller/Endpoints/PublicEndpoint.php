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

        if ($this->isValidMethod('', $requestMethod) == RESTConstants::HTTP_METHOD_NOT_ALLOWED) {
            throw new APIException(RESTConstants::HTTP_METHOD_NOT_ALLOWED, $endpointPath,
                'Method '.$requestMethod.' not allowed');
        }
        if (count($uri) == 0) {

            if (isset($queries['model']) && isset($queries['grip'])) {
                $ski_model = $queries['model'];
                $grip_system = $queries['grip'];
                return $this->doGetSkiTypeByModelAndGrip($ski_model, $grip_system);
            } else if (isset($queries['model'])) {
                $ski_model = $queries['model'];
                return $this->doGetSkiTypeByModel($ski_model);
            } else if (isset($queries['grip'])) {
                $grip_system = $queries['grip'];
                return $this->doGetSkiTypeByGrip($grip_system);
            } else {
                return $this->doGetAllSkiTypes();
            }
        } else {
            throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath, 'Wrong number of parts');
        }

    }


    protected function doGetSkiTypeByModel(string $ski_model): array {
        $model = new SkiTypeModel();
        return $model->getSkiTypesWithModelFilter($ski_model);
    }

    protected function doGetSkiTypeByGrip(string $grip_system): array {
        return (new SkiTypeModel())->getSkiTypesWithGripFilter($grip_system);
    }

    protected function doGetSkiTypeByModelAndGrip(string $ski_model, string $grip_system): array {
        return (new SkiTypeModel())->getSkiTypesWithModelGripFilter($ski_model, $grip_system);
    }

    protected function doGetAllSkiTypes(): array {
        $model = new SkiTypeModel();
        return $model->getAllSkiTypes();
    }
}
