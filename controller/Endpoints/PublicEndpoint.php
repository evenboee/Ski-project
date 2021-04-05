<?php

require_once 'RESTConstants.php';
require_once 'db/SkiTypeModel.php';
class PublicEndpoint extends RequestHandler {

    public function handleRequest(array $uri, string $endpointPath, string $requestMethod, array $queries, array $payload): array
    {
        //echo '"message": "test"';
       // return array("dei","hei");


        if (!isset($queries['model'])) {
            return $this->doGetAllSkiTypes();
        } else
        // TODO: Add check for method
        // if $this->validMethods[$uri[0]][$requestMethod] isset?
        if (count($uri) == 0) {
            $ski_model = $queries['model'];
            return $this->doGetSkiTypeByModel($ski_model);
        } else {
            throw new APIException(RESTConstants::HTTP_BAD_REQUEST, $endpointPath . '/' . $uri[0], 'Wrong number of parts');
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
